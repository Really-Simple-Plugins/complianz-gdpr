<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );


add_filter( 'cmplz_known_script_tags', 'cmplz_spotify_iframetags' );
function cmplz_spotify_iframetags( $tags ) {
	$tags[] = array(
		'name' => 'spotify',
		'placeholder' => 'spotify',
		'category' => 'marketing',
		'urls' => array(
			'open.spotify.com/embed',
		),
	);
	return $tags;
}

/**
 * function to let complianz detect this integration as having placeholders.
 */

function cmplz_spotify_placeholder() {}
