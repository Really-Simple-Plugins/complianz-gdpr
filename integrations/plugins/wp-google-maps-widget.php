<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_wp_google_maps_widget_script');
function cmplz_wp_google_maps_widget_script($tags){
    $tags[] =  'gmw.js';
    return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 * @param $services
 * @return array
 */
function cmplz_wp_google_maps_widget_detected_services($services){
    if (!in_array('googlemaps', $services)){
        $services[] = 'googlemaps';
    }
    return $services;
}
add_filter('cmplz_detected_services','cmplz_wp_google_maps_widget_detected_services' );