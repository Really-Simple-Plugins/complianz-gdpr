<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_openstreetmaps_plugin_script' );
function cmplz_openstreetmaps_plugin_script( $tags ) {
	$tags[] = array(
			'name' => 'openstreetmaps',
			'category' => 'marketing',
			'placeholder' => 'openstreetmaps',
			'urls' => array(
					'ol.js',
					'var raster = getTileLayer',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'map',
			'enable_dependency' => '1',
			'dependency' => [
				//'wait-for-this-script' => 'script-that-should-wait'
				'ol.js' => 'var raster = getTileLayer',
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
function cmplz_openstreetmaps_plugin_detected_services( $services ) {
	if ( ! in_array( 'openstreetmaps', $services ) ) {
		$services[] = 'openstreetmaps';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_openstreetmaps_plugin_detected_services' );

/**
 * Add some custom css for the placeholder
 */

add_action( 'cmplz_banner_css', 'cmplz_openstreetmaps_plugin_css' );
function cmplz_openstreetmaps_plugin_css() {
    ?>
        .cmplz-placeholder-element .ol-popup {
            display: none;
        }
    <?php
}
