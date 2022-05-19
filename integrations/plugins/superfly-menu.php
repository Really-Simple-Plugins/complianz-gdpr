<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Whitelist a string for the cookie blocker
 * @param string $class
 * @param int $total_match
 * @param bool $found
 *
 * @return string
 */

/**
 * @param array $whitelisted_script_tags
 *
 * @return array
 */
function cmplz_superfly_menu_whitelisted_script_tags( $whitelisted_script_tags ) {
	$whitelisted_script_tags[] = 'SFM_template'; //'string from inline script or source that should be whitelisted'
	return $whitelisted_script_tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_superfly_menu_whitelisted_script_tags', 10, 1 );
