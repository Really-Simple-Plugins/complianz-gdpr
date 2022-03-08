<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Check if integration needs to be activated
 *
 * @return bool
 */
function cmplz_wprocket_activate_integration(){
	if ( !get_rocket_option( 'lazyload_iframes' ) ) {
		return false;
	}

	if ( is_user_logged_in() && !get_rocket_option( 'cache_logged_user' ) ) {
		return false;
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
			document.addEventListener("cmplz_category_enabled", function(){
				document.querySelectorAll('[data-rocket-lazyload]').forEach(obj => {
					obj.setAttribute('src', obj.getAttribute('data-lazy-src'));
				});
			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = str_replace(array('<script>', '</script>'), '', $script);
		wp_add_inline_script( 'cmplz-cookiebanner', $script);
	}
}
add_action( 'wp_enqueue_scripts', 'cmplz_wprocket_convert_data_src',PHP_INT_MAX );

/**
 * Tell complianz to replace the source to data-src instead of src
 *
 * @param string $target
 *
 * @return string
 */

function cmplz_wprocket_data_target($target){
	if ( cmplz_wprocket_activate_integration() ) {
		return 'data-lazy-src';
	}
	return $target;
}
add_filter('cmplz_data_target', 'cmplz_wprocket_data_target', 100);
