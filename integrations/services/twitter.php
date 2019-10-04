<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_twitter_script');
function cmplz_twitter_script($tags){
    $tags[] =  'platform.twitter.com';
    $tags[] =  'twitter-widgets.js';

    return $tags;
}


add_filter('cmplz_placeholder_markers', 'cmplz_twitter_placeholders');
function cmplz_twitter_placeholders($placeholders){
    $tags['twitter'] =  'twitter-tweet';

    return $placeholders;
}

add_filter('cmplz_known_iframe_tags', 'cmplz_twitter_iframetags');
function cmplz_twitter_iframetags($tags){
    $tags[] = 'platform.twitter.com';

    return $tags;
}
