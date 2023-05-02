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
