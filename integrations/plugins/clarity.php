<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Kept simple, no intervention with wizard to allow other analtyics tooling
 */

add_filter( 'cmplz_known_script_tags', 'cmplz_clarity_script' );
function cmplz_clarity_script( $tags ) {
	$tags[] = array(
		'name' => 'clarity',
		'category' => 'statistics',
		'urls' => array(
			'clarity.ms',
		),
	);
	return $tags;
}
