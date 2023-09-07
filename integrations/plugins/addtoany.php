<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_addtoany_script' );
function cmplz_addtoany_script( $tags ) {
	$tags[] = array(
		'name' => 'addtoany',
		'category' => 'marketing',
		'urls' => array(
			'static.addtoany.com/menu/page.js',
		),
		'enable_placeholder' => '0',
	);
	return $tags;
}

/**
 * Add social media to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param array $social_media
 *
 * @return array
 */
function cmplz_addtoany_detected_social_media( $social_media ) {

	if ( ! in_array( 'facebook', $social_media ) ) {
		$social_media[] = 'facebook';
	}
	if ( ! in_array( 'twitter', $social_media ) ) {
		$social_media[] = 'twitter';
	}
	if ( ! in_array( 'pinterest', $social_media ) ) {
		$social_media[] = 'pinterest';
	}

	return $social_media;
}
add_filter( 'cmplz_detected_social_media', 'cmplz_addtoany_detected_social_media' );


/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param array $services
 *
 * @return array
 */

function cmplz_addtoany_detected_services( $services ) {
	if ( ! in_array( 'addtoany', $services ) ) {
		$services[] = 'addtoany';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_addtoany_detected_services' );

