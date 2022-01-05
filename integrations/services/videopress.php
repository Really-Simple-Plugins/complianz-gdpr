<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_videopress_script' );
function cmplz_videopress_script( $tags ) {
	$tags[] = 'videopress.com/videopress-iframe.js';
	$tags[] = array(
		'name' => 'videopress',
		'placeholder' => 'videopress',
		'category' => 'marketing',
		'urls' => array(
			'videopress.com/videopress-iframe',
			'videopress.com/embed',
		),
	);
	return $tags;
}
