<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_tidio_live_chat_script');
function cmplz_tidio_live_chat_script($tags){

    $tags[] = 'document.tidioChatCode';

    return $tags;
}