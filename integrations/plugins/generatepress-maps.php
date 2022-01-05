<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_known_script_tags', 'cmplz_custom_googlemaps_script' );

/**
 * Block the script, and an inline script with string 'initMap'.
 * initMap can also be something else. That's the problem with custom maps :)
 *
 * @param $tags
 *
 * @return array
 */
function cmplz_custom_googlemaps_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'show_google_map_acf.js',
			'maps.googleapis.com/maps/api/js',
		),
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com/maps/api/' => 'show_google_map_acf.js',
		],
	);
	return $tags;
}
