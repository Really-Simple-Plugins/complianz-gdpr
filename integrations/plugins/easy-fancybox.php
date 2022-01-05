<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_easy_fancybox_script' );
function cmplz_easy_fancybox_script( $tags ) {
	if ( cmplz_uses_thirdparty('youtube') ) {
		// 	$tags[] = 'plugins/easy-fancybox/';
		$tags[] = array(
			'name' => 'easy-fancybox',
			'category' => 'marketing',
			'placeholder' => 'youtube',
			'urls' => array(
				'fancybox-youtube',
			),
		);
  }
	return $tags;
}
