<?php
function cmplz_elb_cookieblocker($html){
	return COMPLIANZ::$cookie_blocker->replace_tags($html);
}

add_filter( 'elb_entry_content', 'cmplz_elb_cookieblocker' );

/**
 * Add custom elb placeholder css
 */

function cmplz_elb_css() {
	?>
	<style>
		#elb-liveblog [class^="cmplz-placeholder-"] {
			height: 300px;
	</style>

	<?php

}
add_action( 'wp_footer', 'cmplz_elb_css' );
