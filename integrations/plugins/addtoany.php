<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_addtoany_script');
function cmplz_addtoany_script($tags){
    $tags[] = 'addtoany.min.js';
    $tags[] = 'window.a2a_config';

    return $tags;
}