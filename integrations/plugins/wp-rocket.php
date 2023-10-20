<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Check if integration needs to be activated
 *
 * @return bool
 */
function cmplz_wprocket_activate_integration() {
	if ( ! get_rocket_option( 'lazyload_iframes' ) ) {
		return false;
	}

	if ( is_user_logged_in() && ! get_rocket_option( 'cache_logged_user' ) ) {
		return false;
	}

	// Exclude post if the post has excluded the lazyload option
	if ( is_rocket_post_excluded_option( 'lazyload' ) ) {
		return false;
	}

	global $post;
	if ( $post ) {
		$content = $post->post_content;

		// Check for iframes
		preg_match_all( '#<iframe\s+.*?</iframe>#si', $content, $iframe_matches, PREG_SET_ORDER );
		if ( $iframe_matches ) {
			foreach ( $iframe_matches as $match ) {
				if ( is_element_matching_excluded_patterns( $match[0] ) ) {
					return false; // This page contains an iframe that's excluded from lazy loading.
				}
			}
		}

		// Check for images
		preg_match_all( '#<img\s+.*?>#si', $content, $img_matches, PREG_SET_ORDER );
		if ( $img_matches ) {
			foreach ( $img_matches as $match ) {
				if ( is_element_matching_excluded_patterns( $match[0] ) ) {
					return false; // This page contains an image that's excluded from lazy loading.
				}
			}
		}
	}

	return true;
}

/**
 * Ensure the Lazy Load plugin knows it has work to do by replacing the lazyloaded class back to lazyload, after consent is given.
 * @return void
 */
function cmplz_wprocket_convert_data_src() {

	if ( cmplz_wprocket_activate_integration() ) {
		ob_start();
		?>
        <script>
            document.addEventListener("cmplz_enable_category", function () {
                document.querySelectorAll('[data-rocket-lazyload]').forEach(obj => {
                    if (obj.hasAttribute('data-lazy-src')) {
                        obj.setAttribute('src', obj.getAttribute('data-lazy-src'));
                    }
                });
            });
        </script>
		<?php
		$script = ob_get_clean();
		$script = str_replace( array( '<script>', '</script>' ), '', $script );
		wp_add_inline_script( 'cmplz-cookiebanner', $script );
	}
}

add_action( 'wp_enqueue_scripts', 'cmplz_wprocket_convert_data_src', PHP_INT_MAX );

/**
 * @param $iframe
 *
 * @return bool
 *
 * Check if iFrame matches excluded pattern
 */
function is_element_matching_excluded_patterns( $element ) {
	$excluded_patterns = apply_filters( 'rocket_lazyload_iframe_excluded_patterns', array() );
	foreach ( $excluded_patterns as $pattern ) {
		if ( strpos( $element, $pattern ) !== false ) {
			return true;
		}
	}

	return false;
}

/**
 * Tell complianz to replace the source to data-src instead of src
 *
 * @param string $target
 *
 * @return string
 */

function cmplz_wprocket_data_target( $target, $total_match ) {
	if ( is_element_matching_excluded_patterns( $total_match ) ) {
		return 'src';
	}

	if ( cmplz_wprocket_activate_integration() ) {
		return 'data-lazy-src';
	}

	return $target;
}

add_filter( 'cmplz_data_target', 'cmplz_wprocket_data_target', 100, 2 );
