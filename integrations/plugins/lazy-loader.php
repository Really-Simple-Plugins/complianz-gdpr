<?php
defined( 'ABSPATH' ) or die();
/**
 * Ensure the Lazy Load plugin knows it has work to do by replacing the lazyloaded class back to lazyload, after consent is given.
 * @return void
 */
function cmplz_lazyloader_convert_data_src() {
	ob_start();
	?>
	<script>
		document.addEventListener("cmplz_category_enabled", function(){
			document.querySelectorAll('.lazyloaded').forEach(obj => {
				obj.classList.remove('lazyloaded');
				obj.classList.add('lazyload');
			});
		});
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script);
}
add_action( 'wp_enqueue_scripts', 'cmplz_lazyloader_convert_data_src',PHP_INT_MAX );


/**
 * Tell complianz to replace the source to data-src instead of src
 *
 * @param string $target
 *
 * @return string
 */

function cmplz_lazyloader_data_target($target, $total_match){
	return 'data-src';
}
add_filter('cmplz_data_target', 'cmplz_lazyloader_data_target', 100, 2);
