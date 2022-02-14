<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Whitelisting Themify Bulder due to YouTube loader.
 *
 */

function cmplz_themify_whitelist($tags){
	$tags[] = 'themify-builder-loader-js';
	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_themify_whitelist');
