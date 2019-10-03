<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_sumo_script');
function cmplz_sumo_script($tags){
    $tags[] =  'dataset.sumoSiteId';
    return $tags;
}