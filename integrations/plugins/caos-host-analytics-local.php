<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_known_script_tags', 'cmplz_caos_script' );
function cmplz_caos_script( $tags ) {
	$tags[] = array(
		'name' => 'caos-analytics',
		'category' => 'statistics',
		'urls' => array(
			'analytics.js',
			'gtag.js',
			'ga.js',
			'caos-analytics',
			CAOS_OPT_CACHE_DIR,
			'caosLocalGa',
			'CaosGtag',
		),
	);

	return $tags;
}

/**
 * We remove some actions to integrate fully
 * */

function cmplz_caos_remove_scripts_others() {
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$cookie_admin, 'get_statistics_script' ), 10 );
}

add_action( 'after_setup_theme', 'cmplz_caos_remove_scripts_others' );


/**
 * Hide the stats configuration options when caos is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_caos_filter_fields( $fields ) {
	unset( $fields['configuration_by_complianz'] );
	unset( $fields['UA_code'] );
	unset( $fields['AW_code'] );
	unset( $fields['consent-mode'] );
	unset( $fields['compile_statistics_more_info']['help']);
	return $fields;
}

add_filter( 'cmplz_fields', 'cmplz_caos_filter_fields' );


add_filter( 'cmplz_default_value', 'cmplz_caos_set_default', 20, 2 );
function cmplz_caos_set_default( $value, $fieldname ) {
	if ( $fieldname == 'compile_statistics' ) {
		return "google-analytics";
	}

	return $value;
}

/**
 * Remove stuff which is not necessary anymore
 *
 * */

function cmplz_caos_remove_actions() {
	remove_action( 'cmplz_notice_compile_statistics',
		'cmplz_show_compile_statistics_notice', 10 );
}

add_action( 'admin_init', 'cmplz_caos_remove_actions' );

/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */
function cmplz_caos_show_compile_statistics_notice( $args ) {
	cmplz_sidebar_notice( sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.",
		'complianz-gdpr' ), 'CAOS host analytics locally' ) );
}

add_action( 'cmplz_notice_compile_statistics',
	'cmplz_caos_show_compile_statistics_notice', 10, 1 );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_caos_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	return $warnings;
}

add_filter( 'cmplz_warning_types', 'cmplz_caos_filter_warnings' );
