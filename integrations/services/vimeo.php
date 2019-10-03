<?php
defined('ABSPATH') or die("you do not have acces to this page!");


add_filter('cmplz_known_iframe_tags', 'cmplz_vimeo_iframetags');
function cmplz_vimeo_iframetags($tags){
    $tags[] = 'player.vimeo.com';

    return $tags;
}
