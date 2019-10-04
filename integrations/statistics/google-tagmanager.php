<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_googletagmanager_script');
function cmplz_googletagmanager_script($tags){
    $tags[] = 'googletagmanager.com/gtag/js';
    $tags[] =  '_getTracker';
    $tags[] =  'apis.google.com/js/platform.js';


    return $tags;
}