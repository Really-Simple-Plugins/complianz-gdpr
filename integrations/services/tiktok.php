<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_tiktok_script' );
function cmplz_tiktok_script( $tags ) {
	$tags[] = array(
			'name' => 'tiktok',
			'placeholder' => 'tiktok',
			'category' => 'marketing',
			'urls' => array(
					'tiktok.com',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'tiktok-embed',
	);
	return $tags;
}

/**
 * Add some custom css for the placeholder
 */

add_action( 'cmplz_banner_css', 'cmplz_tiktok_css' );
function cmplz_tiktok_css() {
	?>
		.tiktok-embed.cmplz-placeholder-element > div { max-width: 100%;}
	<?php
}
