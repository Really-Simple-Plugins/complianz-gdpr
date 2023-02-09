<?php
defined( 'ABSPATH' ) or die();

/**
 * Block the Maps API and site_script.js, let the Maps API wait for site_script.js
 * @param $tags
 *
 * @return array
 */
function cmplz_agile_store_locator_scripts( $tags ) {
	$tags[] = array(
		'name' => 'agile-store-locator',
		'category' => 'marketing',
		'urls' => array(
			'maps.googleapis.com',
			'/plugins/agile-store-locator/public/js/',
		),
		'enable_placeholder' => '1',
		'placeholder' => 'google-maps',
		'placeholder_class' => 'asl-map-canv',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com' => '/plugins/agile-store-locator/public/js/',
		],
	);
	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_agile_store_locator_scripts' );
