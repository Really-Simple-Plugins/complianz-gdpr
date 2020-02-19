<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_g1_gmaps_script' );
function cmplz_g1_gmaps_script( $tags ) {
	$tags[] = 'g1-gmaps.js';
	$tags[] = 'infobox_packed.js';
	$tags[] = 'maps.googleapis.com';

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


/**
 * Add placeholder for google maps
 *
 * @param $tags
 *
 * @return mixed
 */

function cmplz_g1_gmaps_placeholder( $tags ) {
	$tags['google-maps'][] = 'g1gmap-main';

	return $tags;
}

add_filter( 'cmplz_placeholder_markers', 'cmplz_g1_gmaps_placeholder' );


/**
 * Conditionally add the dependency from the plugin core file to the api files
 */

add_filter( 'cmplz_dependencies', 'cmplz_g1_gmaps_dependencies' );
function cmplz_g1_gmaps_dependencies( $tags ) {

	$tags['maps.googleapis.com'] = 'g1-gmaps.js';

	return $tags;
}
