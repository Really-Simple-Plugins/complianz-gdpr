<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_facebook_script');
function cmplz_facebook_script($tags){
    $tags[] =  'connect.facebook.net';

    return $tags;
}


add_filter('cmplz_placeholder_markers', 'cmplz_facebook_placeholders');
function cmplz_facebook_placeholders($placeholders){
    $tags['facebook'] =  array("fb-page", "fb-post");
    return $placeholders;
}

    
add_filter('cmplz_known_iframe_tags', 'cmplz_facebook_iframetags');
function cmplz_facebook_iframetags($tags){
    $tags[] = 'facebook.com/plugins';

    return $tags;
}
