<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_lead_forensics_script' );
function cmplz_lead_forensics_script( $tags ) {
	$tags[] = array(
		'name' => 'lead-forensics',
		'category' => 'marketing',
		'urls' => array(
			'secure.lead5beat.com',
		),
		'enable_placeholder' => '0',
	);
	return $tags;
}
