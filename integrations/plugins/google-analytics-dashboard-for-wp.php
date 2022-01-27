<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Keep GADWP settings in sync with Complianz
 * @param string $value
 * @param string $key
 * @param string $default
 *
 * @return mixed
 */
function cmplz_gadwp_options( $value, $key, $default ) {

	if ( $key == 'anonymize_ips' ) {
		if (cmplz_no_ip_addresses()){
			return true;
		} else {
			return false;
		}
	}

	if ( $key == 'demographics' ) {
		if (cmplz_statistics_no_sharing_allowed()){
			return false;
		} else {
			return true;
		}
	}
	return $value;
}
add_filter( 'exactmetrics_get_option', 'cmplz_gadwp_options' , 10, 3 );
/**
 * Set analytics as suggested stats tool in the wizard
 */

function cmplz_gadwp_set_default( $value, $fieldname ) {
	if ( $fieldname == 'compile_statistics' ) {
		return "google-analytics";
	}
	return $value;
}
add_filter( 'cmplz_default_value', 'cmplz_gadwp_set_default', 20, 2 );

/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */

function cmplz_gadwp_show_compile_statistics_notice( $args ) {
	cmplz_sidebar_notice( cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'ExactMetrics' ) );
}
add_action( 'cmplz_notice_compile_statistics', 'cmplz_gadwp_show_compile_statistics_notice', 10, 1 );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_gadwp_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	unset( $warnings[ 'gtm-needs-configuring' ] );
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_gadwp_filter_warnings' );

/**
 * Hide the stats configuration options when gadwp is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_gadwp_filter_fields( $fields ) {
	unset( $fields['configuration_by_complianz'] );
	unset( $fields['UA_code'] );
	unset( $fields['AW_code'] );
	unset( $fields['consent-mode'] );
	unset( $fields['compile_statistics_more_info']['help']);

	return $fields;
}
add_filter( 'cmplz_fields', 'cmplz_gadwp_filter_fields', 20, 1 );

/**
 * We remove some actions to integrate fully
 * */
function cmplz_gadwp_remove_scripts_others() {
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$cookie_admin, 'get_statistics_script' ), 10 );
}
add_action( 'after_setup_theme', 'cmplz_gadwp_remove_scripts_others' );

/**
 * Remove stuff which is not necessary anymore
 *
 * */

function cmplz_gadwp_remove_actions() {
	remove_action( 'cmplz_notice_compile_statistics', 'cmplz_show_compile_statistics_notice', 10 );
}
add_action( 'admin_init', 'cmplz_gadwp_remove_actions' );

/**
 * Tell the user the consequences of choices made
 */
function cmplz_gadwp_compile_statistics_more_info_notice() {
	if ( cmplz_no_ip_addresses() ) {
		cmplz_sidebar_notice( cmplz_sprintf( __( "You have selected you anonymize IP addresses. This setting is now enabled in %s.",
			'complianz-gdpr' ), 'Google Analytics Dashboard for WP' ) );
	}
	if ( cmplz_statistics_no_sharing_allowed() ) {
		cmplz_sidebar_notice( cmplz_sprintf( __( "You have selected you do not share data with third-party networks. Display advertising is now disabled in %s.",
			'complianz-gdpr' ), 'Google Analytics Dashboard for WP' ) );
	}
}
add_action( 'cmplz_notice_compile_statistics_more_info', 'cmplz_gadwp_compile_statistics_more_info_notice' );
