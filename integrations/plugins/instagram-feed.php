<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_instagram_feed_script');
function cmplz_instagram_feed_script($tags){

    $tags[] = 'plugins/instagram-feed/js/sb-instagram.min.js';

    return $tags;
}