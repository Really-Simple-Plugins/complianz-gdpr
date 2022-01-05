<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_geo_my_wp_script' );
function cmplz_geo_my_wp_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'GMW_Map',
			'gmw.',
			'apis.google.com',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'gmw-map-cover',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'gmw.map.min.js' => "GMW_Maps['kleo_geo']",
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
function cmplz_geo_my_wp_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_geo_my_wp_detected_services' );
