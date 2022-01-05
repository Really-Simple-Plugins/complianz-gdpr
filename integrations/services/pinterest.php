<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_pinterest_script' );
function cmplz_pinterest_script( $tags ) {
	$tags[] = array(
		'name' => 'pinterest',
		'category' => 'marketing',
		'urls' => array(
			'assets.pinterest.com',
			'pinmarklet.js',
		),
	);
	return $tags;
}
