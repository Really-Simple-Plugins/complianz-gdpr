`@tannin/evaluate`
==================

Evaluates the result of an expression given as postfix terms.

## Installation

Using [npm](https://www.npmjs.com/) as a package manager:

```
npm install @tannin/evaluate
```

Otherwise, download a pre-built copy from unpkg:

[https://unpkg.com/@tannin/evaluate/dist/@tannin/evaluate.min.js](https://unpkg.com/@tannin/evaluate/dist/@tannin/evaluate.min.js)

## Usage

```js
import evaluate from '@tannin/evaluate';

// 3 + 4 * 5 / 6 ⇒ '3 4 5 * 6 / +'
const terms = [ '3', '4', '5', '*', '6', '/', '+' ];

evaluate( terms, {} );
// ⇒ 6.333333333333334
```

## License

Copyright 2018 Andrew Duthie

Released under the [MIT License](https://opensource.org/licenses/MIT).
