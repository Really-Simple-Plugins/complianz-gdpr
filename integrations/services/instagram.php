<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_instagram_script' );
function cmplz_instagram_script( $tags ) {
	$tags[] = array(
			'name' => 'instagram',
			'placeholder' => 'instagram',
			'category' => 'marketing',
			'urls' => array(
					'instawidget.net/js/instawidget.js',
					'instagram.com',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'instagram-media',
	);
	return $tags;
}

add_filter( 'cmplz_post_scribe_tags', 'cmplz_instagram_asynclist' );
function cmplz_instagram_asynclist( $tags ) {
	$tags[] = 'instawidget.net/js/instawidget.js';

	return $tags;
}

/**
 * Add some custom css for the placeholder
 */

add_action( 'cmplz_banner_css', 'cmplz_instagram_css' );
function cmplz_instagram_css() {
	?>
		.instagram-media.cmplz-placeholder-element > div {
			max-width: 100%;
		}
	<?php
}
