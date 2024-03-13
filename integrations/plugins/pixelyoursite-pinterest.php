<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_pixelyoursite_pinterest_script' );
function cmplz_pixelyoursite_pinterest_script( $tags ) {
	$tags[] = 'pixelyoursite-pinterest/dist';

	return $tags;
}

/**
 * Add social media to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $social_media
 *
 * @return array
 */
function cmplz_pixelyoursite_pinterest_detected_social_media( $social_media ) {
	if ( ! in_array( 'pinterest', $social_media ) ) {
		$social_media[] = 'pinterest';
	}

	return $social_media;
}

add_filter( 'cmplz_detected_social_media',
	'cmplz_pixelyoursite_detected_social_media' );
