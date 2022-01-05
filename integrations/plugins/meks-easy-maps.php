<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_meks_plugin_script' );
function cmplz_meks_plugin_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'main-osm.js',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'mks-maps',
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
function cmplz_meks_plugin_detected_services( $services ) {

	if ( ! in_array( 'openstreetmaps', $services ) ) {
		$services[] = 'openstreetmaps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_meks_plugin_detected_services' );
