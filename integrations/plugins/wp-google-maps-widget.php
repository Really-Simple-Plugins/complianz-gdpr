<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

function cmplz_wp_google_maps_widget_image_html($html, $image_url){
	$html = COMPLIANZ::$cookie_blocker->add_class( $html, 'img', 'cmplz-iframe cmplz-iframe-styles ' . apply_filters( 'cmplz_video_class', 'cmplz-no-video' ) );
	if ( ! cmplz_get_value( 'dont_use_placeholders' )
	     && cmplz_use_placeholder( $image_url )
	) {
		$html = COMPLIANZ::$cookie_blocker->add_class( $html, 'img',
			" cmplz-placeholder-element " );

		$html = COMPLIANZ::$cookie_blocker->add_data( $html, 'img', 'placeholder-text',
			apply_filters( 'cmplz_accept_cookies_blocked_content',
				cmplz_get_value( 'blocked_content_text' ) ) );
	}
	return $html;
}
add_filter( 'cmplz_image_html', 'cmplz_wp_google_maps_widget_image_html', 10, 2 );

/**
 * override the flex feature of the blocked content container in case of this map
 */
function cmplz_wp_google_maps_widget_css() {
	?>
	<style>
		.gmw-thumbnail-map.cmplz-blocked-content-container {
			display: inline-block;
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'cmplz_wp_google_maps_widget_css' );


add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_maps_widget_script' );
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


