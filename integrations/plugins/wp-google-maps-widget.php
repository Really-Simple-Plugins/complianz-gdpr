<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_maps_widget_script' );

/**
 * override the flex feature of the blocked content container in case of this map
 */
function cmplz_wp_google_maps_widget_css() {
	?>
		.gmw-thumbnail-map.cmplz-blocked-content-container {
			display: inline-block;
		}
	<?php
}
add_action( 'cmplz_banner_css', 'cmplz_wp_google_maps_widget_css' );


function cmplz_wp_google_maps_widget_script( $tags ) {
	$tags[] = 'gmw.js';

	return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_wp_google_maps_widget_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}

add_filter( 'cmplz_detected_services',
	'cmplz_wp_google_maps_widget_detected_services' );


