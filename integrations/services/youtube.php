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
