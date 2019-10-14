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




function cmplz_googlemaps_placeholder($new_src, $src){
    $key_pattern = '/maps\.googleapis\.com\/maps\/api\/staticmap\?key\=(.*?)&/i';
    if (preg_match($key_pattern, $src, $matches)) {
        $id = $matches[1];
        $new_src = get_transient('cmplz_googlemaps_image_'.sanitize_title($id));
        if (!$new_src || !file_exists($new_src)){
            $new_src = cmplz_download_to_site(html_entity_decode($src), sanitize_title($id), false);
            set_transient('cmplz_googlemaps_image_'.sanitize_title($id), $new_src, MONTH_IN_SECONDS);
        }
    }
    return $new_src;
}
add_filter('cmplz_placeholder_googlemaps', 'cmplz_googlemaps_placeholder', 10, 2);


