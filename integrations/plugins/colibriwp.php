<?php
/* 
 * ColibriWP - Complianz Compatibility
 * Provided by ExtendThemes
*/

add_action( 'init', 'colibri_complianz_remove_video_shortcode', 20 );

function colibri_complianz_remove_video_shortcode() {
	remove_shortcode( 'colibri_video_player' );
}

add_action( 'init', function() {
	add_shortcode( 'colibri_video_player', function( $atts ) {
		ob_start();
		if ($atts['type']==='external') {
				printf( 
					'<iframe src="%1$s"  class="h-video-main" %2$s allowfullscreen></iframe>',
					esc_url( $atts['url'] ),
					(($atts['autoplay'] === 'true') ? 'allow="autoplay"' : '')
				);
			} else {
				printf( 
					'<video class="h-video-main" %1$s ><source src="%2$s" type="video/mp4" /></video>',
					$attributes,
					esc_url( $atts['url'] )
				);
			}
		$content = ob_get_clean();

		return $content;
	} );
}, 30 );

add_action( 'wp_head', function() {
	echo '
		<style type="text/css">
			.h-map > div {
				height: inherit !important;
			}
			.video-container .ratio-inner.cmplz-blocked-content-container {
				position: absolute;
				top: 0; left: 0; right: 0; bottom: 0;
			}
		</style>
	';
}, 999 );