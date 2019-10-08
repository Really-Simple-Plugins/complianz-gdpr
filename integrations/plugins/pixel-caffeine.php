<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_pixel_caffeine_script');
function cmplz_pixel_caffeine_script($tags){

    $tags[] = 'pixel-caffeine/build/frontend.js';
    $tags[] = 'connect.facebook.net';

    return $tags;
}