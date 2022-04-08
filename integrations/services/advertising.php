<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

function cmplz_advertising_script( $tags ) {
	$tags[] = array(
		'name' => 'google-adsense',
		'category' => 'marketing',
		'urls' => array(
			'google_ad_client',
			'pagead/js/adsbygoogle.js',
			'doubleclick',
			'googlesyndication.com',
			'googleads',
		),
		'enable_placeholder' => '0',
		'placeholder' => '',
	);

	$tags[] = array(
		'name' => 'advanced-ads',
		'category' => 'marketing',
		'urls' => array(
			'advads_tracking_ads',
			'advanced_ads',
		),
		'enable_placeholder' => '0',
		'placeholder' => '',
	);

	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_advertising_script' );
