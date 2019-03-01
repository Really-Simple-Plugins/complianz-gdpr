import pluralForms from '@tannin/plural-forms';

/**
 * Tannin constructor options.
 *
 * @property {?string}   contextDelimiter Joiner in string lookup with context.
 * @property {?Function} onMissingKey     Callback to invoke when key missing.
 *
 * @type {Object}
 *
 * @typedef {TanninOptions}
 */

/**
 * Default Tannin constructor options.
 *
 * @type {TanninOptions}
 */
var DEFAULT_OPTIONS = {
	contextDelimiter: '\u0004',
	onMissingKey: null,
};

/**
 * Given a specific locale data's config `plural_forms` value, returns the
 * expression.
 *
 * @example
 *
 * ```
 * getPluralExpression( 'nplurals=2; plural=(n != 1);' ) === '(n != 1)'
 * ```
 *
 * @param {string} pf Locale data plural forms.
 *
 * @return {string} Plural forms expression.
 */
function getPluralExpression( pf ) {
	var parts, i, part;

	parts = pf.split( ';' );

	for ( i = 0; i < parts.length; i++ ) {
		part = parts[ i ].trim();
		if ( part.indexOf( 'plural=' ) === 0 ) {
			return part.substr( 7 );
		}
	}
}

/**
 * Tannin constructor.
 *
 * @param {Object}        data    Jed-formatted locale data.
 * @param {TanninOptions} options Tannin options.
 */
export default function Tannin( data, options ) {
	var key;

	this.data = data;
	this.pluralForms = {};

	options = options || {};
	this.options = {};
	for ( key in DEFAULT_OPTIONS ) {
		this.options[ key ] = options[ key ] || DEFAULT_OPTIONS[ key ];
	}
}

/**
 * Returns the plural form index for the given domain and value.
 *
 * @param {string} domain Domain on which to calculate plural form.
 * @param {number} n      Value for which plural form is to be calculated.
 *
 * @return {number} Plural form index.
 */
Tannin.prototype.getPluralForm = function( domain, n ) {
	var getPluralForm = this.pluralForms[ domain ],
		config, plural;

	if ( ! getPluralForm ) {
		config = this.data[ domain ][ '' ];
		plural = getPluralExpression(
			config[ 'Plural-Forms' ] ||
			config[ 'plural-forms' ] ||
			config.plural_forms
		);

		getPluralForm = this.pluralForms[ domain ] = pluralForms( plural );
	}

	return getPluralForm( n );
};

/**
 * Translate a string.
 *
 * @param {string} domain   Translation domain.
 * @param {string} context  Context distinguishing terms of the same name.
 * @param {string} singular Primary key for translation lookup.
 * @param {string} plural   Fallback value used for non-zero plural form index.
 * @param {number} n        Value to use in calculating plural form.
 *
 * @return {string} Translated string.
 */
Tannin.prototype.dcnpgettext = function( domain, context, singular, plural, n ) {
	var index, key, entry;

	if ( n === undefined ) {
		// Default to singular.
		index = 0;
	} else {
		// Find index by evaluating plural form for value.
		index = this.getPluralForm( domain, n );
	}

	key = singular;

	// If provided, context is prepended to key with delimiter.
	if ( context ) {
		key = context + this.options.contextDelimiter + singular;
	}

	entry = this.data[ domain ][ key ];

	// Verify not only that entry exists, but that the intended index is within
	// range and non-empty.
	if ( entry && entry[ index ] ) {
		return entry[ index ];
	}

	if ( this.options.onMissingKey ) {
		this.options.onMissingKey( singular, domain );
	}

	// If entry not found, fall back to singular vs. plural with zero index
	// representing the singular value.
	return index === 0 ? singular : plural;
};
