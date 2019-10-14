<?php
defined('ABSPATH') or die("you do not have acces to this page!");


add_filter('cmplz_known_iframe_tags', 'cmplz_vimeo_iframetags');
function cmplz_vimeo_iframetags($tags){
    $tags[] = 'player.vimeo.com';

    return $tags;
}


function cmplz_vimeo_placeholder($new_src, $src){
    $vimeo_pattern = '/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/i';
    if (preg_match($vimeo_pattern, $src, $matches)) {
        $vimeo_id = $matches[1];
        $new_src = get_transient("cmplz_vimeo_image_$vimeo_id");
        if (!$new_src || !file_exists($new_src)) {
            $vimeo_images = simplexml_load_string(file_get_contents("http://vimeo.com/api/v2/video/$vimeo_id.xml"));
            $new_src = $vimeo_images->video->thumbnail_large;
            $new_src = cmplz_download_to_site($new_src, 'vimeo'.$vimeo_id);
            set_transient("cmplz_vimeo_image_$vimeo_id", $new_src, WEEK_IN_SECONDS);
        }
    }
    return $new_src;
}
add_filter('cmplz_placeholder_vimeo', 'cmplz_vimeo_placeholder', 10, 2);
