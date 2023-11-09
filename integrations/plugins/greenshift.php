<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_greenshift_script' );
function cmplz_greenshift_script( $tags ) {
	$tags[] = array(
		'name' => 'youtube',
		'placeholder' => 'youtube',
		'category' => 'marketing',
		'enable_placeholder' => '1',
		'placeholder_class' => 'gs-video-element',
		'urls' => array(
			'greenshift-video',
		),
	);
	return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_greenshift_detected_services( $services ) {
	if ( ! in_array( 'youtube', $services ) ) {
		$services[] = 'youtube';
	}

	return $services;
}

add_filter( 'cmplz_detected_services', 'cmplz_greenshift_detected_services' );

// Replace watch?v= with embed in the cookie blocker output, as Greenshift loads the video in an iFrame
function cmplz_greenshift_cookieblocker( $output ) {
	if ( cmplz_uses_thirdparty('youtube') ) {
		// This regex pattern finds iframes with YouTube watch URLs, handling both single and double quotes
		$iframe_pattern = '/<iframe[^>]*?(?:src|data-src-cmplz)=((["\'])(https:\/\/www\.youtube\.com\/watch\?v=([^"&\']+))\2)[^>]*?>/is';
		if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
			foreach ( $matches[0] as $key => $total_match ) {
				if ( isset($matches[4][$key]) ) {
					$video_id = $matches[4][$key];
					$embed_url = "https://www.youtube.com/embed/" . $video_id;
					// Replace both src and data-src-cmplz with the YouTube embed URL
					$new_match = str_replace($matches[3][$key], $embed_url, $total_match);
					$output = str_replace($total_match, $new_match, $output);
				}
			}
		}
	}
	return $output;
}
add_filter('cmplz_cookie_blocker_output', 'cmplz_greenshift_cookieblocker');



