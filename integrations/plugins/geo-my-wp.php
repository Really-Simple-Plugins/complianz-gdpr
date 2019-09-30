<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_geo_my_wp_script');
function cmplz_geo_my_wp_script($tags){

    $tags[] = 'gmw.core.min.js';
    $tags[] = 'gmw.map.min.js';
    $tags[] = 'gmw.js';
    $tags[] = 'new GMW_Map';

    return $tags;
}