<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_sbd_script' );
function cmplz_sbd_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'maps.googleapis.com',
			'directory-script.js',
			'category-tab.js',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'sbd-single-item-map',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com' => 'directory-script.js',
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

function cmplz_sbd_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_sbd_detected_services' );

