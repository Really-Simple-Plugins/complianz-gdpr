<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_livechat_script' );
function cmplz_livechat_script( $tags ) {
	$tags[] = array(
		'name' => 'livechat',
		'placeholder' => 'livechat',
		'category' => 'marketing',
		'urls' => array(
			'cdn.livechatinc.com/tracking.js',
		),
	);
	return $tags;
}


