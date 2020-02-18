<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_jetpack_script' );
function cmplz_jetpack_script( $tags ) {

	$tags[] = '/twitter-timeline.min.js';
	$tags[] = '/twitter-timeline.js';

	return $tags;
}

/**
 * placeholder not needed, as it's handled by core twitter integration
 */
add_filter( 'cmplz_placeholder_markers', 'cmplz_jetpack_placeholder' );
function cmplz_jetpack_placeholder( $tags ) {
	$tags['twitter'][] = 'widget_twitter_timeline';

	return $tags;
}

