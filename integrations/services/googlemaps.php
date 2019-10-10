<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_googlemaps_script');
function cmplz_googlemaps_script($tags){

    $tags[] = 'new google.maps.';
    $tags[] =  'apis.google.com/js/platform.js';

    //$tags[] = 'maps.googleapis.com'; //should be added, but need to test more first.

    return $tags;
}


add_filter('cmplz_known_iframe_tags', 'cmplz_googlemaps_iframetags');
function cmplz_googlemaps_iframetags($tags){
    $tags[] = 'maps.google.com';
    $tags[] =  'google.com/maps';
    $tags[] =  'apis.google.com';

    return $tags;
}


add_filter('cmplz_image_tags', 'cmplz_googlemaps_imagetags');
function cmplz_googlemaps_imagetags($tags){
    $tags[] = 'maps.googleapis.com/maps/api/staticmap';

    return $tags;
}
