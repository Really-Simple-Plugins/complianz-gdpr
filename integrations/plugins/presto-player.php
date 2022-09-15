<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_presto_video_lightbox_script' );
function cmplz_presto_video_lightbox_script( $tags ) {
	// Do not block anything on self-hosted videos
	global $post;
	if ( $post && strpos( $post->post_content, 'presto-player/self-hosted') !== false ) {
		return $tags;
	}

	if ( cmplz_uses_thirdparty('youtube') ) {
		$tags[] = array(
			'name' => 'youtube',
			'category' => 'marketing',
			'placeholder' => 'youtube',
			'urls' => array(
				'//www.youtube.com/embed',
				'presto-player/src/player/player-static',
				'presto-player',
				'presto-components-static',
				'presto-fallback-iframe',
			),
		);
	}

	if ( cmplz_uses_thirdparty('vimeo') ) {
		$tags[] = array(
			'name' => 'vimeo',
			'category' => 'statistics',
			'placeholder' => 'vimeo',
			'urls' => array(
				'https://vimeo.com/',
				'player.vimeo.com/video/',
				'presto-player/src/player/player-static',
				'presto-player',
				'presto-components-static',
				'presto-fallback-iframe',
			),
		);
	}
	return $tags;
}
