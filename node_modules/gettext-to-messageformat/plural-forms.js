/*
Examples from https://www.gnu.org/software/gettext/manual/html_node/Plural-forms.html

Note: true == 1, false == 0

Plural-Forms: nplurals=1; plural=0;
Plural-Forms: nplurals=2; plural=n != 1;
Plural-Forms: nplurals=2; plural=n>1;
Plural-Forms: nplurals=2; plural=n == 1 ? 0 : 1;
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2;
Plural-Forms: nplurals=3; plural=n==1 ? 0 : n==2 ? 1 : 2;
Plural-Forms: nplurals=3; plural=n==1 ? 0 : (n==0 || (n%100 > 0 && n%100 < 20)) ? 1 : 2;
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && (n%100<10 || n%100>=20) ? 1 : 2;
Plural-Forms: nplurals=4; plural=n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3;
Plural-Forms: nplurals=6; plural=n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 ? 4 : 5;
*/

const validPlural = (plural) => {
  const words = plural.match(/\b\w+\b/g)
  if (!words) return null
  for (let i = 0; i < words.length; ++i) {
    const word = words[i]
    if (word !== 'n' && isNaN(Number(word))) return null
  }
  return plural.trim()
}

const getPluralFunction = (pluralForms) => {
  if (!pluralForms) return null
  let nplurals
  let plural
  pluralForms.split(';').forEach(part => {
    const m = part.match(/^\s*(\w+)\s*=(.*)/)
    switch (m && m[1]) {
      case 'nplurals':
        nplurals = Number(m[2])
        break
      case 'plural':
        plural = validPlural(m[2])
        break
    }
  })
  if (!nplurals || !plural) throw new Error('Invalid plural-forms: ' + pluralForms)
  const pluralFunc = new Function('n', `return 'p' + Number(${plural})`)
  pluralFunc.cardinal = new Array(nplurals).fill().map((_, i) => 'p' + i)
  return pluralFunc
}

module.exports = getPluralFunction
