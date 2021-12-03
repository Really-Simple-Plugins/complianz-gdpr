<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_map_plugin_script' );
function cmplz_wp_google_map_plugin_script( $tags ) {
	$tags[] = array(
		'name' => 'google-analytics',
		'category' => 'statistics',
		'urls' => array(
			'google-analytics.com/ga.js',
			'www.google-analytics.com/analytics.js',
			'www.googletagmanager.com/gtag/js',
			'_getTracker',
			'apis.google.com/js/platform.js',
		),
	);

	return $tags;
}
