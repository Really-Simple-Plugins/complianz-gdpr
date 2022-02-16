<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Ensure the Lazy Load plugin knows it has work to do by replacing the lazyloaded class back to lazyload, after consent is given.
 * @return void
 */
function cmplz_wprocket_convert_data_src() {
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
add_action( 'wp_enqueue_scripts', 'cmplz_wprocket_convert_data_src',PHP_INT_MAX );

/**
 * Tell complianz to replace the source to data-src instead of src
 *
 * @param string $target
 *
 * @return string
 */

function cmplz_wprocket_data_target($target){
	return 'data-lazy-src';
}
add_filter('cmplz_data_target', 'cmplz_wprocket_data_target', 100);
