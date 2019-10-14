<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_geo_my_wp_script');
function cmplz_geo_my_wp_script($tags){

//    $tags[] = 'gmw.core.min.js';
//    $tags[] = 'gmw.map.min.js';
//    $tags[] = 'gmw.js';
    $tags[] = 'gmw.';
    $tags[] = 'GMW_Map';

    return $tags;
}

add_filter('cmplz_known_iframe_tags', 'cmplz_geo_my_wp_iframetags');
function cmplz_geo_my_wp_iframetags($tags){
    $tags[] =  'apis.google.com';
    return $tags;
}


/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 * @param $services
 * @return array
 */
function cmplz_geo_my_wp_detected_services($services){
    if (!in_array('googlemaps', $services)){
        $services[] = 'googlemaps';
    }
    return $services;
}
add_filter('cmplz_detected_services','cmplz_geo_my_wp_detected_services' );


add_filter('cmplz_dependencies', 'cmplz_geo_my_wp_dependencies');
function cmplz_geo_my_wp_dependencies($tags){
    //specifically applicable to the members KLEO plugin
    $tags['gmw.map.min.js']="GMW_Maps['kleo_geo']";

    return $tags;
}

add_filter('cmplz_placeholder_markers', 'cmplz_geo_my_wp_placeholders');
function cmplz_geo_my_wp_placeholders($tags){
    $tags['googlemaps'] =  'gmw-map-cover';

    return $tags;
}
