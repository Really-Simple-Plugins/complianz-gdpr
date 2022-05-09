<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_googlemaps_script' );
function cmplz_googlemaps_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'placeholder' => 'google-maps',
		'category' => 'marketing',
		'urls' => array(
			'new google.maps.',
			'maps.google.com',
			'google.com/maps',
			'apis.google.com',
			'maps.google.de',
		),
	);
	return $tags;
}

add_filter( 'cmplz_image_tags', 'cmplz_googlemaps_imagetags' );
function cmplz_googlemaps_imagetags( $tags ) {
	$tags[] = 'maps.googleapis.com/maps/api/staticmap';
	return $tags;
}

/**
 * Declare a placeholder
 * @param string $new_src
 * @param string $src
 *
 * @return mixed|string
 */
function cmplz_google_maps_placeholder( $new_src, $src ) {

	$key_pattern = '/maps\.googleapis\.com\/maps\/api\/staticmap/i';
	if ( preg_match( $key_pattern, $src, $matches ) ) {
		$id = str_replace(array('http://', 'https://','maps.googleapis.com/maps/api/staticmap'), '', $src);
		//to prevent issues with the url as ID, we create a separate ID, and look it up by the url of this image
		$new_src = get_transient('cmplz_googlemaps_image_' . sanitize_title( $id )  );

		if ( ! $new_src || ! cmplz_file_exists_on_url( $new_src ) ) {
			$guid = time();
			$new_src = cmplz_download_to_site( html_entity_decode( $src ), sanitize_title( 'cmplz_googlemaps_image_'.$guid ), false );
			set_transient( 'cmplz_googlemaps_image_' . sanitize_title( $id ) , $new_src, MONTH_IN_SECONDS );
		}
	}
	return $new_src;
}

add_filter( 'cmplz_placeholder_google-maps', 'cmplz_google_maps_placeholder', 10, 2 );



