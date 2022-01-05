<?php
defined('ABSPATH') or die("you do not have access to this page!");

add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_analytics_script' );
function cmplz_wp_google_analytics_script( $tags ) {
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
