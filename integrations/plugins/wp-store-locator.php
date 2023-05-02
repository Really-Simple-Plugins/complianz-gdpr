<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Block the script.
 *
 * @param $tags
 *
 * @return array
 */
function cmplz_wpsl_googlemaps_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'maps.google.com/maps/api/js',
			'wpsl-gmap',
			'wpsl-js-js-extra',
			'wpsl-js-js',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'wpsl-gmap-canvas',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'wpsl-gmap' => 'maps.google.com/maps/api/js'
		],
	);
	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_wpsl_googlemaps_script' );

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_wpsl_googlemaps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_wpsl_googlemaps_detected_services' );
