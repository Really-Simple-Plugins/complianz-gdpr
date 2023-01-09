<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_microsoftads_script' );
function cmplz_microsoftads_script( $tags ) {
	$tags[] = 'window.uetq';
	$tags[] = 'bat.bing.com';

	return $tags;
}


add_filter( 'cmplz_known_script_tags', 'cmplz_microsoftads_iframetags' );
function cmplz_microsoftads_iframetags( $tags ) {
	$tags[] = 'bing.com';

	return $tags;
}
