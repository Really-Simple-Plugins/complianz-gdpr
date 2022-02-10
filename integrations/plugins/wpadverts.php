<?php

defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

function cmplz_wpadverts_googlemaps_script( $tags ) {
	if( is_singular( "advert" ) ) {
		// if the map is on the ad details page, use map-single
		$tags[] = array(
			'name' => 'google-maps',
			'category' => 'marketing',
			'placeholder' => 'google-maps',
			'urls' => array(
				'maps.googleapis.com',
				'map-single.js',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'adverts-single-grid-details',
			'enable_dependency' => '1',
			'dependency' => [
				//'wait-for-this-script' => 'script-that-should-wait'
				'maps.googleapis.com' => 'map-single.js',
			],
		);
		return $tags;
	} else {
		// other page, use the multi marker map
		$tags[] = array(
			'name' => 'google-maps',
			'category' => 'marketing',
			'placeholder' => 'google-maps',
			'urls' => array(
				'maps.googleapis.com',
				'map-icons.js',
				'infobox.js',
				'map-complete.js',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'wpadverts-mal-map',
			'enable_dependency' => '1',
			'dependency' => [
				//'wait-for-this-script' => 'script-that-should-wait'
				'maps.googleapis.com' => 'map-icons.js',
				'map-icons.js' => 'infobox.js',
				'infobox.js' => 'map-complete.js',
			],
		);
		return $tags;
	}
}
add_filter( 'cmplz_known_script_tags', 'cmplz_wpadverts_googlemaps_script' );

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_wpadverts_googlemaps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_wpadverts_googlemaps_detected_services' );