<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

function cmplz_uafe_initDomContentLoaded() {
//	if ( cmplz_uses_thirdparty('youtube') ) {
		?>
		<script>
			jQuery(document).ready(function ($) {
				$(document).on("cmplzRunAfterAllScripts", cmplz_uafe_fire_initOnReadyComponents);
				function cmplz_uafe_fire_initOnReadyComponents() {
					console.log("init elementor frontend");
				// 	setTimeout(
			  // function()
			  // {
					window.elementorFrontend.init();
			  // }, 2000);
				}
			})
		</script>
	<?php
//	}
}
add_action( 'wp_footer', 'cmplz_uafe_initDomContentLoaded' );

add_filter( 'cmplz_known_script_tags', 'cmplz_uafe_script' );
function cmplz_uafe_script( $tags ) {

	$tags[] = 'uael-google-map.js';
	$tags[] = 'uael-google-map.js';
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

function cmplz_uafe_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}

add_filter( 'cmplz_detected_services', 'cmplz_uafe_detected_services' );


/**
 * Add placeholder for google maps
 *
 * @param $tags
 *
 * @return mixed
 */

function cmplz_uafe_placeholder( $tags ) {
	$tags['google-maps'][] = 'uael-google-map-wrap';

	return $tags;
}

add_filter( 'cmplz_placeholder_markers', 'cmplz_uafe_placeholder' );


/**
 * Conditionally add the dependency from the plugin core file to the api files
 */

add_filter( 'cmplz_dependencies', 'cmplz_uafe_dependencies' );
function cmplz_uafe_dependencies( $tags ) {

	$tags['maps.googleapis.com'] = 'uael-google-map.js';

	return $tags;
}
