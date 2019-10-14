<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_youtube_script');
function cmplz_youtube_script($tags){
    $tags[] = 'www.youtube.com/iframe_api';

    return $tags;
}

add_filter('cmplz_known_iframe_tags', 'cmplz_youtube_iframetags');
function cmplz_youtube_iframetags($tags){
    $tags[] = 'youtube.com';
    $tags[] = 'youtube-nocookie.com';
    $tags[] = 'youtu.be';

    return $tags;
}


function cmplz_youtube_placeholder($new_src, $src){
    $youtube_pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/videoseries\?list=RD|embed\/|watch\?v=)([^#\&\?]*).*/i';
    if (preg_match($youtube_pattern, $src, $matches)) {
        $youtube_id = $matches[1];
        /*
         * The highest resolution of youtube thumbnail is the maxres, but it does not
         * always exist. In that case, we take the hq thumb
         * To lower the number of file exists checks, we cache the result.
         *
         * */
        $new_src = get_transient("cmplz_youtube_image_$youtube_id");
        if (!$new_src || !file_exists($new_src)) {
            $new_src = "https://img.youtube.com/vi/$youtube_id/maxresdefault.jpg";
            if (!cmplz_remote_file_exists($new_src)) {
                $new_src = "https://img.youtube.com/vi/$youtube_id/hqdefault.jpg";
            }
            $new_src = cmplz_download_to_site($new_src, 'youtube'.$youtube_id);

            set_transient("cmplz_youtube_image_$youtube_id", $new_src, WEEK_IN_SECONDS);
        }
    }
    return $new_src;
}
add_filter('cmplz_placeholder_youtube', 'cmplz_youtube_placeholder', 10, 2);

