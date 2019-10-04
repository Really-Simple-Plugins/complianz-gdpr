<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_paypal_script');
function cmplz_paypal_script($tags){

    $tags[] = 'www.paypal.com/tagmanager/pptm.js';
    $tags[] = 'www.paypalobjects.com/api/checkout.js';

    return $tags;
}