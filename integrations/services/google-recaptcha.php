<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_recaptcha_script');
function cmplz_recaptcha_script($tags){

    $tags[] = 'google.com/recaptcha';
    $tags[] = 'grecaptcha'; //contact form 7
    $tags[] = 'recaptcha.js';
    $tags[] = 'recaptcha/api.js';
    $tags[] =  'apis.google.com/js/platform.js';


    return $tags;
}

