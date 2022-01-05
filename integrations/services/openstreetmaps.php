<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_openstreetmaps_iframetags' );
function cmplz_openstreetmaps_iframetags( $tags ) {
	$tags[] = array(
		'name' => 'openstreetmaps',
		'placeholder' => 'openstreetmaps',
		'category' => 'marketing',
		'urls' => array(
			'openstreetmap.org',
		),
	);
	return $tags;
}

/**
 * function to let complianz detect this integration as having placeholders.
 */

function cmplz_openstreetmaps_placeholder() {

}
