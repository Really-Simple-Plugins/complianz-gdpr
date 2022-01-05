<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_known_script_tags', 'cmplz_facebook_script' );
function cmplz_facebook_script( $tags ) {
	$tags[] = array(
				'name' => 'facebook',
				'category' => 'marketing',
				'urls' => array(
					'connect.facebook.net',
					'facebook.com/plugins',
				),
				'enable_placeholder' => '1',
				'placeholder_class' => 'fb-page,fb-post',
		);
	return $tags;
}

/**
 * Add some custom css for the placeholder
 */

add_action( 'cmplz_banner_css', 'cmplz_facebook_css' );
function cmplz_facebook_css() {
	?>
		.cmplz-placeholder-element > blockquote.fb-xfbml-parse-ignore {
			margin: 0 20px;
		}
	<?php
}
