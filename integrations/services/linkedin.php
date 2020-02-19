<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_linkedin_script' );
function cmplz_linkedin_script( $tags ) {
	$tags[] = 'platform.linkedin.com/in.js';

	return $tags;
}
