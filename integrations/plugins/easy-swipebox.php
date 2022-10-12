<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_swipebox' );
function cmplz_swipebox( $tags ) {
	if ( cmplz_uses_thirdparty( 'youtube' ) ) {
		$tags[] = array(
			'name'        => 'youtube',
			'category'    => 'marketing',
			'placeholder' => 'youtube',
			'urls'        => array(
				'swipebox',
			),
			'placeholder_class' => 'elenco_video',
		);
	}

	if ( cmplz_uses_thirdparty( 'vimeo' ) ) {
		$tags[] = array(
			'name'        => 'vimeo',
			'category'    => 'marketing',
			'placeholder' => 'vimeo',
			'urls'        => array(
				'swipebox',
			),
			'placeholder_class' => 'elenco_video',
		);
	}

	return $tags;
}
