<?php
defined('ABSPATH') or die("you do not have acces to this page!");

function cmplz_uses_google_analytics()
{
    return COMPLIANZ()->cookie->uses_google_analytics();
}

//    $delete_options = array(
//        "cmplz_wizard_completed_once",
//        "cmplz_wizard_completed     ",
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
//        'cmplz_detected_thirdparty_services',
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
    //we allow for one random character at the end, for the cookie variationid
    $original_fieldname = isset(COMPLIANZ()->config->fields[substr($fieldname, 0, -1)]) ? substr($fieldname, 0, -1) : $fieldname;

    if (!isset(COMPLIANZ()->config->fields[$original_fieldname])) return false;

    //if  a post id is passed we retrieve the data from the post
    $page = COMPLIANZ()->config->fields[$original_fieldname]['page'];

    if ($post_id && ($page !== 'wizard')) {
        $value = get_post_meta($post_id, $fieldname, true);
    } else {
        $fields = get_option('complianz_options_' . $page);
        $default = isset(COMPLIANZ()->config->fields[$original_fieldname]['default']) ? COMPLIANZ()->config->fields[$original_fieldname]['default'] : '';
        $value = isset($fields[$fieldname]) ? $fields[$fieldname] : $default;
    }

    /*
     * Translate output
     *
     * */

    if (function_exists('icl_translate') || function_exists('pll__')) {
        $type = isset(COMPLIANZ()->config->fields[$original_fieldname]['type']) ? COMPLIANZ()->config->fields[$original_fieldname]['type'] : false;
        if ($type==='cookies' || $type==='thirdparties'){
            if (is_array($value)) {
                foreach ($value as $key => $key_value) {
                    if (function_exists('pll__')) $value[$key] = pll__($key_value);
                    if (function_exists('icl_translate')) $value[$key] = icl_translate('complianz', $fieldname . "_" . $key, $key_value);
                }
            }
        } else {
            if (isset(COMPLIANZ()->config->fields[$original_fieldname]['translatable']) && COMPLIANZ()->config->fields[$original_fieldname]['translatable']) {
                if (function_exists('pll__')) $value = pll__($value);
                if (function_exists('icl_translate')) $value = icl_translate('complianz', $fieldname, $value);
            }
        }
    }
    return $value;
}

function cmplz_user_needs_cookie_warning()
{
    return COMPLIANZ()->cookie->user_needs_cookie_warning();
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

function cmplz_scan_detected_thirdparty_services()
{
    $thirdparty = get_option('cmplz_detected_thirdparty_services');
    //nothing scanned yet, or nothing found
    if (!$thirdparty || (count($thirdparty) == 0)) return false;

    return $thirdparty;
}

function cmplz_update_option($page, $fieldname, $value)
{
    $options = get_option('complianz_options_' . $page);

    $options[$fieldname] = $value;
    if (!empty($options)) update_option('complianz_options_' . $page, $options);
}

function cmplz_uses_statistics()
{
    $stats = cmplz_get_value('compile_statistics');
    if ($stats !== 'no') return true;

    return false;
}


function cmplz_uses_only_functional_cookies(){
    return COMPLIANZ()->cookie->uses_only_functional_cookies();
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
 * callback for privacy document Check if there is a text entered in the custom privacy policy text
 *
 * */

function cmplz_has_custom_privacy_policy(){
    $policy = cmplz_get_value('custom_privacy_policy_text');
    if (empty($policy)) return false;

    return true;
}

/*
 * callback for privacy policy document, check if google is allowed to share data with other services
 *
 * */

function cmplz_statistics_no_sharing_allowed(){
    $statistics = cmplz_get_value('compile_statistics');
    $tagmanager = ($statistics === 'google-tag-manager') ? true : false;
    $google_analytics = ($statistics === 'google-analytics') ? true : false;

    if ($google_analytics || $tagmanager) {
        $thirdparty = $google_analytics ? cmplz_get_value('compile_statistics_more_info') : cmplz_get_value('compile_statistics_more_info_tag_manager');
        $no_sharing = (isset($thirdparty['no-sharing']) && ($thirdparty['no-sharing'] == 1)) ? true : false;
        if ($no_sharing) {
            return true;
        } else {
            return false;
        }
    }

    //only applies to google
    return false;
}

/*
 * callback for privacy policy document. Check if ip addresses are stored.
 *
 * */

function cmplz_no_ip_addresses(){
    $statistics = cmplz_get_value('compile_statistics');
    $tagmanager = ($statistics === 'google-tag-manager') ? true : false;
    $matomo = ($statistics === 'matomo') ? true : false;
    $google_analytics = ($statistics === 'google-analytics') ? true : false;

    //not anonymous stats.
    if ($statistics === 'yes') {
        return false;
    }

    if ($google_analytics || $tagmanager) {
        $thirdparty = $google_analytics ? cmplz_get_value('compile_statistics_more_info') : cmplz_get_value('compile_statistics_more_info_tag_manager');
        $ip_anonymous = (isset($thirdparty['ip-addresses-blocked']) && ($thirdparty['ip-addresses-blocked'] == 1)) ? true : false;
        if ($ip_anonymous) {
            return true;
        } else {
            return false;
        }
    }

    if ($matomo){
        if (cmplz_get_value('matomo_anonymized') === 'yes'){
            return true;
        } else{
            return false;
        }
    }


    return true;
}

function cmplz_accepted_processing_agreement()
{
    $statistics = cmplz_get_value('compile_statistics');
    $tagmanager = ($statistics === 'google-tag-manager') ? true : false;
    $google_analytics = ($statistics === 'google-analytics') ? true : false;

    if ($google_analytics || $tagmanager) {
        $thirdparty = $google_analytics ? cmplz_get_value('compile_statistics_more_info') : cmplz_get_value('compile_statistics_more_info_tag_manager');
        $accepted_google_data_processing_agreement = (isset($thirdparty['accepted']) && ($thirdparty['accepted'] == 1)) ? true : false;
        if ($accepted_google_data_processing_agreement) {
            return true;
        } else {
            return false;
        }
    }

    //only applies to google
    return false;
}

function cmplz_init_cookie_blocker(){
    if (!cmplz_third_party_cookies_active()) return;

    //don't fire on the back-end
    if (is_admin()) return;

    if (defined('CMPLZ_DO_NOT_BLOCK') && CMPLZ_DO_NOT_BLOCK) return;

    if (cmplz_get_value('disable_cookie_block')) return;

    /* Do not block when visitors are from outside EU, if geoip is enabled */
    //check cache, as otherwise all users would get the same output, while this is user specific
    if (!defined('wp_cache') && class_exists('cmplz_geoip') && COMPLIANZ()->geoip->geoip_enabled() && !COMPLIANZ()->geoip->is_eu()) return;

    /* Do not block if the cookie policy is already accepted */
    //check cache, as otherwise all users would get the same output, while this is user specific
    if (!defined('wp_cache') && COMPLIANZ()->cookie->cookie_policy_accepted()) return;

    //do not block cookies during the scan
    if (isset($_GET['complianz_scan_token']) && (sanitize_title($_GET['complianz_scan_token']) == get_option('complianz_scan_token'))) return;

    /* Do not fix mixed content when call is coming from wp_api or from xmlrpc or feed */
    if (defined('JSON_REQUEST') && JSON_REQUEST) return;
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) return;

    add_action("template_redirect", array(COMPLIANZ()->cookie_blocker, "start_buffer"));
    add_action("shutdown", array(COMPLIANZ()->cookie_blocker, "end_buffer"), 999);
}

function cmplz_ajax_user_settings(){
    $is_eu = true;

    //track a visit
    if (class_exists('cmplz_statistics')) COMPLIANZ()->statistics->page_view();

    if (class_exists('cmplz_geoip') && COMPLIANZ()->geoip->geoip_enabled()) {
        $is_eu = COMPLIANZ()->geoip->is_eu();
    }

    $response = json_encode(array(
        'success' => true,
        'is_eu' => $is_eu,
        'do_not_track'   => apply_filters('cmplz_dnt_enabled', false),
    ));
    header("Content-Type: application/json");
    echo $response;
    exit;
}

function cmplz_get_option($name){
    return get_option($name);
}

function cmplz_esc_html($html){
    return esc_html($html);
}

function cmplz_esc_url_raw($url){
    return esc_url_raw($url);
}

function cmplz_is_admin(){
    return is_admin();
}

/**
 * Load the translation files
 *
 * @since  1.1.5
 *
 * @access public
 *
 */

//add_action('init', 'cpmlz_load_translation', 20);
//function cpmlz_load_translation()
//{
//    load_plugin_textdomain('complianz', FALSE, cmplz_path . 'config/languages/');
//}

function cmplz_get_cookie_categories_text(){
    return get_option('cmplz_cookies_categories_text', __('Third party cookies', 'complianz'));
}
