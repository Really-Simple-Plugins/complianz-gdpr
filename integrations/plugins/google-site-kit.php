<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */

function cmplz_google_site_kit_show_compile_statistics_notice( $args ) {
	cmplz_sidebar_notice(
		sprintf( __( "Because you're using %s, you can choose which plugin should insert the relevant snippet.", 'complianz-gdpr' ), "Google Site Kit" )
		. cmplz_read_more( "https://complianz.io/configuring-google-site-kit/" ),
	'warning' );
}

add_action( 'cmplz_notice_compile_statistics', 'cmplz_google_site_kit_show_compile_statistics_notice', 10, 1 );


/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_google_site_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	unset( $warnings[ 'gtm-needs-configuring' ] );
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_google_site_filter_warnings' );

/**
 * Hide the stats configuration options when gadwp is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_google_site_kit_filter_fields( $fields ) {
	unset( $fields['configuration_by_complianz'] );
	unset( $fields['UA_code'] );
	unset( $fields['AW_code'] );
	unset( $fields['consent-mode'] );
	unset( $fields['compile_statistics_more_info']['help']);

	return $fields;
}
add_filter( 'cmplz_fields', 'cmplz_google_site_kit_filter_fields', 20, 1 );

/**
 * We remove some actions to integrate fully
 * */
function cmplz_google_site_kit_remove_scripts_others() {
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$cookie_admin, 'get_statistics_script' ), 10 );
}
add_action( 'after_setup_theme', 'cmplz_google_site_kit_remove_scripts_others' );
/**
 * Remove stuff which is not necessary anymore
 *
 * */

function cmplz_google_site_kit_remove_actions() {
	remove_action( 'cmplz_notice_compile_statistics', 'cmplz_show_compile_statistics_notice', 10 );
}
add_action( 'admin_init', 'cmplz_google_site_kit_remove_actions' );
