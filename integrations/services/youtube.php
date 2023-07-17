<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_youtube_script' );
function cmplz_youtube_script( $tags ) {
	$tags[] = array(
		'name'        => 'youtube',
		'placeholder' => 'youtube',
		'category'    => 'marketing',
		'urls'        => array(
			'www.youtube.com/iframe_api',
			'youtube.com',
			'youtube-nocookie.com',
			'youtu.be',
		),
	);

	return $tags;
}

/**
 * Get the first video id from a video series
 *
 * @param string $src
 *
 * @return string
 */

function cmplz_youtube_get_video_id_from_series( $src ) {
	$output     = wp_remote_get( $src );
	$youtube_id = false;
	if ( isset( $output['body'] ) ) {
		$body           = $output['body'];
		$body           = stripcslashes( $body );
		$series_pattern = '/VIDEO_ID\': "([^#\&\?].*?)"/i';
		if ( preg_match( $series_pattern, $body, $matches ) ) {
			$youtube_id = $matches[1];
		}
	}

	return $youtube_id;
}

/**
 * Get screenshot from YouTube as placeholder
 *
 * @param $new_src
 * @param $src
 *
 * @return mixed|string
 */

function cmplz_youtube_placeholder( $new_src, $src ) {
	$youtube_pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/videoseries\?list=RD|embed\/|watch\?v=)([^#\&\?]*).*/i';
	if ( preg_match( $youtube_pattern, $src, $matches ) ) {
		$youtube_id = $matches[1];
		//check if it's a video series. If so, we get the first video
		if ( $youtube_id === 'videoseries' ) {
			//get the videoseries id
			$series_pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/videoseries\?list=RD|embed\/|watch\?v=)[^#\&\?]*\?list=(.*)/i';
			//if we find the unique id, we save it in the cache
			if ( preg_match( $series_pattern, $src, $matches ) ) {
				$series_id = $matches[1];

				$youtube_id = get_transient( "cmplz_youtube_videoseries_video_id_$series_id" );
				if ( ! $youtube_id ) {
					//we do a get on the url to retrieve the first video
					$youtube_id = cmplz_youtube_get_video_id_from_series( $src );
					set_transient( "cmplz_youtube_videoseries_video_id_$series_id", $youtube_id, WEEK_IN_SECONDS );
				}
			}
			$new_src = cmplz_youtube_videoseries_placeholder( $src );
		} else {
			$new_src = cmplz_youtube_video_placeholder( $youtube_id );
		}
	}

	return $new_src;
}

function cmplz_youtube_video_placeholder( $youtube_id ) {
	$new_src = get_transient( "cmplz_youtube_image_$youtube_id" );
	if ( ! $new_src || ! cmplz_file_exists_on_url( $new_src ) ) {
		$new_src = "https://img.youtube.com/vi/$youtube_id/maxresdefault.jpg";
		if ( ! cmplz_remote_file_exists( $new_src ) ) {
			$new_src = "https://img.youtube.com/vi/$youtube_id/hqdefault.jpg";
		}
		$new_src = cmplz_download_to_site( $new_src, 'youtube' . $youtube_id );
		set_transient( "cmplz_youtube_image_$youtube_id", $new_src, WEEK_IN_SECONDS );
	}

	return $new_src;
}

add_filter( 'cmplz_placeholder_youtube', 'cmplz_youtube_placeholder', 10, 2 );

function cmplz_youtube_videoseries_placeholder( $src ) {
	$key = 'cmplz_youtube_image_' . md5( $src );

	$new_src = get_transient( $key );

	if ( ! $new_src || ! cmplz_file_exists_on_url( $new_src ) ) {
		$url         = str_replace( '/embed', '', $src );
		$url_encoded = urlencode( $url );
		$response    = wp_safe_remote_get( 'https://www.youtube.com/oembed?url=' . $url_encoded );

		if ( ! is_wp_error( $response ) ) {
			$data          = json_decode( wp_remote_retrieve_body( $response ) );
			$thumbnail_url = $data->thumbnail_url;
			$new_src       = cmplz_download_to_site( $thumbnail_url, 'youtube' . md5( $src ) );
			set_transient( $key, $new_src, WEEK_IN_SECONDS );
		} else {
			// In case of error, return some default image or handle the error appropriately
			$new_src = '';
		}
	}

	return $new_src;
}