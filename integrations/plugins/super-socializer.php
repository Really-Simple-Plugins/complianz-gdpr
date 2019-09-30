<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_super_socializer_script');
function cmplz_super_socializer_script($tags){
    $tags[] = 'super-socializer';
    $tags[] = 'theChampFBKey';
    return $tags;
}