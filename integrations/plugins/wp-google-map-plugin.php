<?php
/**
 * Currently broken.
 * https://wordpress.org/support/topic/gdpr-integration-for-complianz-gdpr/
 */
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_map_plugin_script' );
function cmplz_wp_google_map_plugin_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'maps.js',
			'maps.googleapis.com',
			'maps.google.com',
			'wpgmp_map',
		),
		'enable_placeholder' => 1,
		'placeholder_class' => 'wpgmp_map_container',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'/maps/api/js' => 'maps.js',
			'maps.js' => 'wpgmp_map',
		],
	);

	return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_wp_google_map_plugin_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_wp_google_map_plugin_detected_services' );

function cmplz_wp_google_map_plugin_placeholder(){

}
