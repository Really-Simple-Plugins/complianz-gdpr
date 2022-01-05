<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_wp_video_lightbox_script' );
function cmplz_wp_video_lightbox_script( $tags ) {
	if ( cmplz_uses_thirdparty('youtube') ) {
		$tags[] = array(
			'name' => 'youtube',
			'category' => 'marketing',
			'placeholder' => 'youtube',
			'urls' => array(
				'wp-video-lightbox',
			),
		);
	  }
	return $tags;
}
