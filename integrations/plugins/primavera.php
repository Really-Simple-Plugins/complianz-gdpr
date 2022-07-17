<?php defined( 'ABSPATH' ) or die();
/**
 * was changed to position unset for primavera theme compat,
 * but has unexpected side effects. Set to "relative"
 *
 * @return void
 */
function cmplz_primavera_css() {
	?>
	<style>
		.cmplz-wp-video {
			position:unset;
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'cmplz_primavera_css' );
