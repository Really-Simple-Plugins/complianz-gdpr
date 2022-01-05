<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_nudgify_script' );
function cmplz_nudgify_script( $tags ) {

	$tags[] = 'pixel.nudgify.com';

	return $tags;
}
