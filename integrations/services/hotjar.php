<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_hotjar_script' );
function cmplz_hotjar_script( $tags ) {

	/**
	 * hotjar should get blocked if
	 * - not privacy friendly or
	 * - privacy friendly AND Germany (eu_consent_regions = yes)
	 */

	if ( cmplz_get_value( 'eu_consent_regions' ) === 'yes'
	     || cmplz_get_value( 'hotjar_privacyfriendly' ) !== 'yes'
	) {
		$tags[] = 'static.hotjar.com';
	}

	return $tags;
}
