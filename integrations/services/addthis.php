<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_addthis_script' );
function cmplz_addthis_script( $tags ) {
	$tags[] = array(
		'name' => 'addthis',
		'category' => 'marketing',
		'urls' => array(
			'addthis.com',
		),
	);
	return $tags;
}
