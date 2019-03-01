# gettext-to-messageformat

Converts gettext input (po/pot/mo files) into [messageformat]-compatible JSON,
using [gettext-parser].


### Installation

```sh
npm install --save gettext-to-messageformat
```
or
```sh
yarn add gettext-to-messageformat
```

If using in an environment that does not natively support ES6 features such as
object destructuring and arrow functions, you'll want to use a transpiler for this.


### Usage

```js
const { parsePo, parseMo } = require('gettext-to-messageformat')
const { headers, pluralFunction, translations } = parsePo(`
# Examples from http://pology.nedohodnik.net/doc/user/en_US/ch-poformat.html
msgid ""
msgstr ""
"Content-Type: text/plain; charset=UTF-8\n"
"Language: pl\n"
"Plural-Forms: nplurals=3; plural=(n==1 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);\n"

msgid "Time: %1 second"
msgid_plural "Time: %1 seconds"
msgstr[0] "Czas: %1 sekunda"
msgstr[1] "Czas: %1 sekundy"
msgstr[2] "Czas: %1 sekund"

msgid "%1 took %2 ms to complete."
msgstr "Trebalo je %2 ms da se %1 završi."

msgid "%s took %d ms to complete."
msgstr "Trebalo je %2$d ms da se %1$s završi."

msgid "No star named %(starname)s found."
msgstr "Nema zvezde po imenu %(starname)s."
`)

const MessageFormat = require('messageformat')
const mf = new MessageFormat({ [headers.language]: pluralFunction })
const messages = mf.compile(translations)

messages['Time: %1 second']([1])
// 'Czas: 1 sekunda'

messages['%s took %d ms to complete.'](['TASK', 42])
// 'Trebalo je 42 ms da se TASK završi.'

messages['No star named %(starname)s found.']({ starname: 'Chi Draconis' })
// 'Nema zvezde po imenu Chi Draconis.'
```

For more examples, [gettext-parser] includes a selection of `.po` and `.mo` files
in its test fixtures.


### API: `parseMo(input, options)` and `parsePo(input, options)`

The two functions differ only in their expectation of the input's format. `input`
may be a string or a Buffer; `options` is an optional set of configuration for
the parser, including the following fields:

- `defaultCharset` (string, default `null`) – For Buffer input only, sets the
  default charset -- otherwise UTF-8 is assumed

- `forceContext` (boolean, default `false`) – If any of the gettext messages
  define a `msgctxt`, that is used as a top-level key in the output, and all
  messages without a context are included under the `''` empty string context.
  If no context is set, by default this top-level key is not included unless
  `forceContext` is set to `true`.

- `pluralFunction` (function) – If your input file does not include a Plural-Forms
  header, or if for whatever reason you'd prefer to use your own, set this to be
  a stringifiable function that takes in a single variable, and returns the
  appropriate pluralisation category. Following the model used internally in
  [messageformat], the function variable should also include `cardinal` as a
  member array of its possible categories, in the order corresponding to the
  gettext pluralisation categories. This is relevant if you'd like to avoid the
  `new Function` constructor otherwise used to generate `pluralFunction`, or to
  allow for more fine-tuned categories than gettext allows, e.g. differentiating
  between the categories of `'1.0'` and `'1'`.

- `verbose` (boolean, default `false`) – If set to `true`, missing translations
  will cause warnings.

For more options, take a look at the [source](./index.js).

Both functions return an object containing the following fields:

- `headers` (object) – The raw contents of the input file's headers, with keys
  lower-cased
- `pluralFunction` (function) – An appropriate pluralisation function to use for
  the output translations, suitable to be used directly by [messageformat]. May
  be `null` if none was set in `options` and if the input did not include a
  Plural-Forms header.
- `translations` (object) – An object containing the MessageFormat strings keyed
  by their `msgid` and if used, `msgctxt`.

[messageformat]: https://messageformat.github.io/
[gettext-parser]: https://github.com/smhg/gettext-parser
