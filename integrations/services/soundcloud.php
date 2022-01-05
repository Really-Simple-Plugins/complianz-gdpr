<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );


add_filter( 'cmplz_known_script_tags', 'cmplz_soundcloud_iframetags' );
function cmplz_soundcloud_iframetags( $tags ) {
	$tags[] = array(
		'name' => 'soundcloud',
		'placeholder' => 'soundcloud',
		'category' => 'marketing',
		'urls' => array(
			'soundcloud.com/player',
		),
	);
	return $tags;
}

/**
 * function to let complianz detect this integration as having placeholders.
 */

function cmplz_soundcloud_placeholder() {}
