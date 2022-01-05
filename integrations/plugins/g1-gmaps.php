<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

add_filter( 'cmplz_known_script_tags', 'cmplz_g1_gmaps_script' );
function cmplz_g1_gmaps_script( $tags ) {
	$tags[] = array(
		'name' => 'g1-gmaps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'g1-gmaps.js',
			'infobox_packed.js',
			'maps.googleapis.com',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'g1gmap-main',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com' => 'g1-gmaps.js',
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

function cmplz_g1_gmaps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}

add_filter( 'cmplz_detected_services', 'cmplz_g1_gmaps_detected_services' );
