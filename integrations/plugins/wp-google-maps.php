<?php
defined('ABSPATH') or die("you do not have acces to this page!");


/**
 * //WP Google Maps, should not be blocked as we use it's integrated GDPR feature
//    'wpgmaps.js',
//    'wpgmza_rectangle_data_array'
//    'wp-google-maps.min.js',
 */

/**
 * replace the wp google maps gdpr notice with our own, nice looking one.
 * @param $html
 * @return string
 */

function cmplz_wp_google_maps_replace_gdpr_notice($html){
    $img = cmplz_default_placeholder('googlemaps');
    $msg = '<div style="text-align:center;margin-bottom:15px">'.__('To enable Google Maps cookies, please click "I Agree"',"complianz-gdpr").'</div>';
    return apply_filters('cmplz_wp_google_maps_html', '<img src="'.$img.'" style="margin-bottom:15px">'.$msg);
}
add_filter('wpgmza_gdpr_notice_html', 'cmplz_wp_google_maps_replace_gdpr_notice');


/**
 * Force the GDPR option to be enabled
 * @param $settings
 * @return mixed
 */
function cmplz_wp_google_maps_settings()
{
    if (is_admin() && current_user_can('manage_options')) {
        $settings = json_decode(get_option('wpgmza_global_settings'));

        if ($settings->wpgmza_gdpr_require_consent_before_load === 'on') return;

        $settings->wpgmza_gdpr_require_consent_before_load = 'on';
        update_option('wpgmza_global_settings', json_encode($settings));
    }
}
add_action('admin_init','cmplz_wp_google_maps_settings');


/**
 * Add cookie that should be set on consent
 * @param $cookies
 * @return mixed
 */


function cmplz_wp_google_maps_add_cookie($cookies){
    $cookies['wpgmza-api-consent-given'] = array( '1','');
    return $cookies;
}
add_filter('cmplz_set_cookies_on_consent',  'cmplz_wp_google_maps_add_cookie');


/**
 * Add placeholder to the list
 * @param $tags
 * @return array
 */
function cmplz_wp_google_maps_placeholder($tags){

    $tags['googlemaps'] = 'gmw-map-cover';

    return $tags;
}
add_filter('cmplz_placeholder_markers', 'cmplz_wp_google_maps_placeholder');
