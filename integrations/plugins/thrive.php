<?php
defined( 'ABSPATH' ) or die();
/**
 * Filter Custom method from Thrive
 *
 * @param string $html
 *
 * @return mixed|null
 */
function cmplz_thrive_page_content_cookieblocker( $html ) {
	if ( ! is_admin() && ! cmplz_is_pagebuilder_preview() ) {
		$html = COMPLIANZ::$cookie_blocker->replace_tags( $html );
	}

	return $html;
}

add_filter( 'tve_landing_page_content', 'cmplz_thrive_page_content_cookieblocker' );

/**
 * Whitelist youtube and video from being blocked, in the text/templates scripts of the Trhive quiz builder
 *
 * @param $tags
 *
 * @return mixed
 */
function cmplz_thrive_whitelist( $tags ) {
	$tags[] = '//www.youtube.com/embed/<#=';
	$tags[] = '//player.vimeo.com/video/<#=';

	return $tags;
}

add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_thrive_whitelist' );

/**
 * Add inline css, as Thrive removes it
 *
 * @param string $html
 *
 * @return string
 */
function cmplz_thrive_inline_css( $html ) {
	$html .= '<style> .cmplz-hidden{display:none !important;}</style>';

	return $html;
}

add_filter( "cmplz_banner_html", 'cmplz_thrive_inline_css' );

/**
 * Filter cookie blocker output
 */

function cmplz_thrive_cookieblocker( $output ) {
	$iframe_pattern = '/thrv_responsive_video[ |\"][^>]+?data-url="(.*?)">/is';

	if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
		foreach ( $matches[0] as $key => $total_match ) {
			$placeholder = '';

			// Determine if it's YouTube or Vimeo based on the URL in the data-url attribute
			if ( strpos( $matches[1][ $key ], 'youtube.com' ) !== false && cmplz_uses_thirdparty( 'youtube' ) ) {
				if ( cmplz_use_placeholder( 'youtube' ) && isset( $matches[1][ $key ] ) ) {
					$youtube_url = $matches[1][ $key ];
					$placeholder = 'data-placeholder-image="' . cmplz_placeholder( false, stripcslashes( $youtube_url ) ) . '" ';
				}
				$new_match = str_replace( 'data-url', $placeholder . ' data-category="marketing" data-service="youtube" data-src-cmplz', $total_match );

			} elseif ( strpos( $matches[1][ $key ], 'vimeo.com' ) !== false && cmplz_uses_thirdparty( 'vimeo' ) ) {
				if ( cmplz_use_placeholder( 'vimeo' ) && isset( $matches[1][ $key ] ) ) {
					$vimeo_url   = $matches[1][ $key ];
					$placeholder = 'data-placeholder-image="' . cmplz_placeholder( false, stripcslashes( $vimeo_url ) ) . '" ';
				}
				$new_match = str_replace( 'data-url', $placeholder . ' data-category="statistics" data-service="vimeo" data-src-cmplz', $total_match );
			} else {
				// If it's neither Vimeo nor YouTube, skip this iteration and proceed to the next match
				continue;
			}

			$new_match = str_replace( 'thrv_responsive_video', 'thrv_responsive_video cmplz-placeholder-element', $new_match );
			$output    = str_replace( $total_match, $new_match, $output );
		}
	}

	return $output;
}

add_filter( 'cmplz_cookie_blocker_output', 'cmplz_thrive_cookieblocker' );

/**
 * @param string $target
 * @param string $total_match
 *
 * @return string
 */
function cmplz_thrive_data_target( $target, $total_match ) {
	// Look for thrive class in iframe here
	if ( strpos( $total_match, 'data-url' ) !== false &&
		 ( cmplz_uses_thirdparty( 'youtube' ) || cmplz_uses_thirdparty( 'vimeo' ) ) ) {
		return 'data-url';
	}

	return $target;
}

add_filter( 'cmplz_data_target', 'cmplz_thrive_data_target', 100, 2 );

/**
 * Initialize thrive youtube iframe
 *
 */

function cmplz_thrive_initDomContentLoaded() {
	if ( ! cmplz_uses_thirdparty( 'youtube' ) ) {
		return;
	}
	ob_start();
	?>
	<script>
		document.addEventListener("cmplz_run_after_all_scripts", cmplz_thrive_fire_domContentLoadedEvent);

		function cmplz_thrive_fire_domContentLoadedEvent() {
			dispatchEvent(new Event('load'));
		}
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace( array( '<script>', '</script>' ), '', $script );
	wp_add_inline_script( 'cmplz-cookiebanner', $script );
}

add_action( 'wp_enqueue_scripts', 'cmplz_thrive_initDomContentLoaded', PHP_INT_MAX );
