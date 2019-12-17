<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_caos_analytics_script');
function cmplz_caos_analytics_script($tags){
    $tags[] =  'caos-analytics/analytics.js';

    return $tags;
}

/**
 * We remove some actions to integrate fully
 * */
function cmplz_caos_remove_scripts_others()
{
	remove_action('cmplz_statistics_script', array(COMPLIANZ()->cookie_admin, 'get_statistics_script'), 10);
}

add_action('after_setup_theme', 'cmplz_caos_remove_scripts_others');