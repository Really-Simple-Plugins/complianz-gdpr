<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Do not activate if any of these plugins is active, or if the user has not explicitly chosen to block 
 */
 if ( !defined('CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE') && cmplz_get_value('self_host_google_fonts') === 'block' ) {
	 add_filter( 'cmplz_known_script_tags', 'cmplz_google_fonts_script' );
	 add_filter( 'cmplz_known_style_tags', 'cmplz_google_fonts_style' );
 }

function cmplz_google_fonts_script( $tags ) {
	$tags[] = array(
		'name' => 'google-fonts',
		'category' => 'marketing',
		'urls' => array(
			'fonts.googleapis.com',
            'ajax.googleapis.com/ajax/libs/webfont',
            'fonts.gstatic.com',
		),
		'enable_placeholder' => '0',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'fonts.googleapis.com' => 'WebFont.load',
			'fonts.googleapis.' => 'WebFontConfig',
		],
	);
	return $tags;
}

function cmplz_google_fonts_style( $tags ) {
	$tags[] = array(
		'name' => 'google-fonts',
		'category' => 'marketing',
		'urls' => array(
			'fonts.googleapis.com',
            'fonts.gstatic.com',
		),
		'enable_placeholder' => '0',
		'enable_dependency' => '0',
	);
	return $tags;
}
