<?php
defined('ABSPATH') or die("you do not have acces to this page!");

function cmplz_uses_google_analytics()
{
    return COMPLIANZ()->cookie->uses_google_analytics();
}
//
//    $delete_options = array(
//        "complianz_options_wizard",
//        'complianz_options_cookie_settings',
//        'complianz_options_dataleak',
//        'complianz_options_processing',
//        'complianz_active_policy_id',
//        'complianz_scan_token',
//        'cmplz_license_notice_dismissed',
//        'cmplz_license_key',
//        'cmplz_license_status',
//        'cmplz_changed_cookies',
//        'cmplz_processed_pages_list',
//        'cmplz_license_notice_dismissed',
//        'cmplz_processed_pages_list',
//        'cmplz_detected_cookies',
//        'cmplz_plugins_changed',
//        'cmplz_detected_social_media',
//        'cmplz_deleted_cookies',
//    );
//    delete_all_options($delete_options);
//
//
//    function delete_all_options($options) {
//        foreach ($options as $option_name){
//            delete_option( $option_name );
//            delete_site_option( $option_name );
//        }
//
//    }

function cmplz_revoke_link($text = false)
{
    $text = $text ? $text : __('Revoke cookie consent', 'complianz');
    return '<a href="#" class="cc-revoke-custom">' . $text . '</a>';
}

function cmplz_get_value($fieldname, $post_id = false)
{
    if (!isset(COMPLIANZ()->config->fields[$fieldname])) return false;
    //if  a post id is passed we retrieve the data from the post
    $page = COMPLIANZ()->config->fields[$fieldname]['page'];

    if ($post_id && ($page !== 'wizard')) {
        $value = get_post_meta($post_id, $fieldname, true);
    } else {
        $fields = get_option('complianz_options_' . $page);
        $default = isset(COMPLIANZ()->config->fields[$fieldname]['default']) ? COMPLIANZ()->config->fields[$fieldname]['default'] : '';
        $value = isset($fields[$fieldname]) ? $fields[$fieldname] : $default;
    }

    if (function_exists('icl_translate') || function_exists('pll__')) {
        $type = COMPLIANZ()->config->fields[$fieldname]['type'];
        if ($type==='cookies' || $type==='thirdparties'){
            foreach ($value as $key=>$key_value){
                if (function_exists('pll__')) $value[$key] = pll__($key_value);
                if (function_exists('icl_translate')) $value[$key] = icl_translate('complianz', $fieldname."_".$key, $key_value);
            }
        } else {
            if (function_exists('pll__'))  $value = pll__($value);
            if (function_exists('icl_translate')) $value = icl_translate('complianz', $fieldname, $value);
        }
    }
    return $value;
}

function cmplz_cookie_warning_required()
{
    return COMPLIANZ()->cookie->cookie_warning_required();
}

function cmplz_company_in_eu()
{
    $country_code = cmplz_get_value('country_company');
    if (in_array($country_code, COMPLIANZ()->config->eu_countries)) {
        return true;
    }

    return false;
}

function cmplz_notice($msg){
    if ($msg=='') return;
    echo '<div class="cmplz-notice">'.$msg.'</div>';
}
function cmplz_notice_success($msg){
    if ($msg=='') return;
    echo '<div class="cmplz-notice cmplz-success">'.$msg.'</div>';
}

/*
 * Check if the scan detected social media on the site.
 *
 *
 * */

function cmplz_scan_detected_social_media()
{
    $social_media = get_option('cmplz_detected_social_media');

    //nothing scanned yet, or nothing found
    if (!$social_media || (count($social_media) == 0)) return false;

    return $social_media;
}

function cmplz_update_option($page, $fieldname, $value)
{
    $options = get_option('complianz_options_' . $page);

    $options[$fieldname] = $value;
    update_option('complianz_options_' . $page, $options);
}

function cmplz_uses_statistics()
{
    $stats = cmplz_get_value('compile_statistics');
    if ($stats !== 'no') return true;

    return false;
}


function cmplz_dnt_enabled()
{
    return (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1);
}

function cmplz_third_party_cookies_active()
{
    return COMPLIANZ()->cookie->third_party_cookies_active();
}

function cmplz_strip_spaces($string){
    return preg_replace( '/\s*/m', '',$string);

}

function cmplz_localize_date($date){
    $month = date('F', strtotime($date)); //june
    $month_localized = __($month); //juni
    $date = str_replace($month, $month_localized, $date);
    $weekday = date('l', strtotime($date)); //wednesday
    $weekday_localized = __($weekday); //woensdag
    $date = str_replace($weekday, $weekday_localized, $date);
    return $date;
}

function cmplz_wp_privacy_version(){
    global $wp_version;
    return ($wp_version >= '4.9.6');
}

/*
 * Add a class to third party scripts so the acceptance of the cookie warning results in enabling these scripts.
 *
 * */
add_filter('cmplz_set_class', 'cmplz_set_class', 10, 1);
function cmplz_set_class($script){
    $script = COMPLIANZ()->cookie_blocker->add_class($script, 'script', 'cmplz-script');
    //$script = str_replace('<script', '<script class="cmplz-script"', $script);
    return $script;
}

/*
 * Move the source to a data attribute
 *
 * */

add_filter('cmplz_third_party_iframe', 'cmplz_third_party_iframe', 10, 2);
function cmplz_third_party_iframe($iframe, $src_iframe){
    $iframe = str_replace('<iframe', '<iframe data-src-cmplz="'.$src_iframe.'"', $iframe);
    return $iframe;
}


add_filter('cmplz_script_tags', 'cmplz_script_tags');
function cmplz_script_tags($script_tags){
    $custom_scripts = cmplz_strip_spaces(cmplz_get_value('thirdparty_scripts'));

    if (!empty($custom_scripts) && strlen($custom_scripts)>0){
        $custom_scripts = explode(',', $custom_scripts);
        $script_tags = array_merge($script_tags ,  $custom_scripts);
    }

    return $script_tags;
}

add_filter('cmplz_iframe_tags','cmplz_iframe_tags');
function cmplz_iframe_tags($iframe_tags){
    $custom_iframes = cmplz_strip_spaces(cmplz_get_value('thirdparty_iframes'));

    if (!empty($custom_iframes) && strlen($custom_iframes)>0){
        $custom_iframes = explode(',', $custom_iframes);
        $iframe_tags = array_merge($iframe_tags ,  $custom_iframes);
    }

    return $iframe_tags;
}