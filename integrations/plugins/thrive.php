<?php
defined( 'ABSPATH' ) or die();

/**
 * Whitelist youtube and video from being blocked, in the text/templates scripts of the Trhive quiz builder
 * @param $tags
 *
 * @return mixed
 */

function cmplz_thrive_whitelist($tags){
	$tags[] = '//www.youtube.com/embed/<#=';
	$tags[] = '//player.vimeo.com/video/<#=';
	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_thrive_whitelist');
