<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_instagram_script');
function cmplz_instagram_script($tags)
{

    $tags[] = 'instagram.com/embed.js';
    $tags[] = 'instagram.com';
    $tags[] = 'www.instagram.com/embed.js';
    $tags[] = 'instawidget.net/js/instawidget.js';

    return $tags;
}

add_filter('cmplz_placeholder_markers', 'cmplz_instagram_placeholders');
function cmplz_instagram_placeholders($placeholders){
    $tags['instagram'] =  'instagram-media';

    return $placeholders;
}


add_filter('cmplz_known_iframe_tags', 'cmplz_instagram_iframetags');
function cmplz_instagram_iframetags($tags){
    $tags[] = 'instagram.com';

    return $tags;
}


add_filter('cmplz_known_async_tags', 'cmplz_instagram_asynclist');
function cmplz_instagram_asynclist($tags){
    $tags[] = 'instawidget.net/js/instawidget.js';

    return $tags;
}