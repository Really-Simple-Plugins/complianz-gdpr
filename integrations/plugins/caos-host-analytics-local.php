<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_caos_analytics_script');
function cmplz_caos_analytics_script($tags){
    $tags[] =  'caos-analytics/analytics.js';

    return $tags;
}