<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 */

function cmplz_wc_stripe_whitelist($tags){
	$tags[] = 'var wc_stripe_params';
	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_wc_stripe_whitelist');