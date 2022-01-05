<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

add_filter( 'cmplz_known_script_tags', 'cmplz_google_maps_easy_script' );
function cmplz_google_maps_easy_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'google-maps-easy',
			'maps.googleapis.com',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'gmpMapDetailsContainer',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com' => 'google-maps-easy',
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

function cmplz_google_maps_easy_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}

add_filter( 'cmplz_detected_services', 'cmplz_google_maps_easy_detected_services' );
