<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

function cmplz_beaver_builder_whitelist($tags){
	$tags[] = 'FLBuilderLayout';
	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_beaver_builder_whitelist');
