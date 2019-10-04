<?php
defined('ABSPATH') or die("you do not have acces to this page!");


add_filter('cmplz_known_iframe_tags', 'cmplz_dailymotion_iframetags');
function cmplz_dailymotion_iframetags($tags){
    $tags[] = 'dailymotion.com/embed/video/';

    return $tags;
}