<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * WPEverest User Registration Pro
 */

add_filter( 'cmplz_known_script_tags', 'cmplz_user_registration_pro_script' );
function cmplz_user_registration_pro_script( $tags ) {
	$recaptcha_enabled
		= get_option( 'user_registration_login_options_enable_recaptcha',
		'no' );

	if ( 'yes' == $recaptcha_enabled ) {
		$tags[] = 'user-registration-pro-frontend.min.js';
		$tags[] = 'user-registration-pro-frontend.js';
	}

	return $tags;
}

/**
 * Conditionally add the dependency
 * $deps['wait-for-this-script'] = 'script-that-should-wait';
 */

add_filter( 'cmplz_dependencies', 'cmplz_userregistrationpro_dependencies' );
function cmplz_userregistrationpro_dependencies( $tags ) {
	$recaptcha_enabled
		= get_option( 'user_registration_login_options_enable_recaptcha',
		'no' );

	if ( 'yes' == $recaptcha_enabled ) {
		$tags['recaptcha/api.js'] = 'user-registration-pro-frontend.min.js';
	}

	return $tags;
}
