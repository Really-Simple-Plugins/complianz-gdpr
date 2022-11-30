<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_style_tags', 'cmplz_adobe_fonts' );
function cmplz_adobe_fonts( $tags ) {
	$tags[] = array(
		'name' => 'adobe-fonts',
		'category' => 'marketing',
		'urls' => array(
			'use.typekit.net',
			'p.typekit.net',
		),
		'enable_placeholder' => '0',
		'dependency' => '0',
	);

	return $tags;
}
