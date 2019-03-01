const { mo, po } = require('gettext-parser')
const getPluralFunction = require('./plural-forms')

const defaultOptions = {
  defaultCharset: null,
  forceContext: false,
  pluralFunction: null,
  pluralVariablePattern: /%(?:\((\w+)\))?\w/,
  replacements: [
    {
      pattern: /[\\{}#]/g,
      replacement: '\\$&'
    },
    {
      pattern: /%(\d+)(?:\$\w)?/g,
      replacement: (_, n) => `{${n - 1}}`
    },
    {
      pattern: /%\((\w+)\)\w/g,
      replacement: '{$1}'
    },
    {
      pattern: /%\w/g,
      replacement: function () { return `{${this.n++}}` },
      state: { n: 0 }
    },
    {
      pattern: /%%/g,
      replacement: '%'
    }
  ],
  verbose: false
}

const getMessageFormat = (
  { pluralFunction, pluralVariablePattern, replacements, verbose },
  { msgid, msgid_plural, msgstr }
) => {
  if (!msgid || !msgstr) return null
  if (!msgstr[0]) {
    if (verbose) console.warn('Translation not found:', msgid)
    msgstr[0] = msgid
  }
  if (msgid_plural) {
    if (!pluralFunction) throw new Error('Plural-Forms not defined')
    for (let i = 1; i < pluralFunction.cardinal.length; ++i) {
      if (!msgstr[i]) {
        if (verbose) console.warn('Plural translation not found:', msgid, i)
        msgstr[i] = msgid_plural
      }
    }
  }
  msgstr = msgstr.map(str => (
    replacements.reduce((str, { pattern, replacement, state }) => {
      if (state) replacement = replacement.bind(Object.assign({}, state))
      return str.replace(pattern, replacement)
    }, str)
  ))
  if (msgid_plural) {
    const m = msgid_plural.match(pluralVariablePattern)
    const pv = m && m[1] || '0'
    const pc = pluralFunction.cardinal.map((c, i) => `${c}{${msgstr[i]}}`)
    return `{${pv}, plural, ${pc.join(' ')}}`
  }
  return msgstr[0]
}

const convert = (parse, input, options) => {
  options = Object.assign(defaultOptions, options)
  const { headers, translations } = parse(input, options.defaultCharset)
  if (!options.pluralFunction) {
    options.pluralFunction = getPluralFunction(headers['plural-forms'])
  }
  let hasContext = false
  for (const context in translations) {
    if (context) hasContext = true
    const data = translations[context]
    for (const id in data) {
      const mf = getMessageFormat(options, data[id])
      if (mf) data[id] = mf
      else delete data[id]
    }
  }
  return {
    headers,
    pluralFunction: options.pluralFunction,
    translations: hasContext || options.forceContext ? translations : translations['']
  }
}

module.exports = {
  parseMo: (input, options) => convert(mo.parse, input, options),
  parsePo: (input, options) => convert(po.parse, input, options)
}
