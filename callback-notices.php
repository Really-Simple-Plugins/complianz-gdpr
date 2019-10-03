<?php
defined('ABSPATH') or die("you do not have acces to this page!");


add_action('cmplz_notice_uses_social_media', 'cmplz_uses_social_media_notice');
function cmplz_uses_social_media_notice(){
    $social_media = cmplz_scan_detected_social_media();
    if ($social_media){
        $social_media = implode(', ', $social_media);
        cmplz_notice(sprintf(__("The scan found social media buttons or widgets for %s on your site, which means the answer should be yes", 'complianz-gdpr'), $social_media));
    }
}

add_action('cmplz_notice_purpose_personaldata', 'cmplz_purpose_personaldata');
function cmplz_purpose_personaldata(){
    $contact_forms = cmplz_site_uses_contact_forms();
    if ($contact_forms){
        cmplz_notice(__('The scan found forms on your site, which means answer should probably include "contact".', 'complianz-gdpr'));
    }
}

add_action('cmplz_notice_uses_thirdparty_services', 'cmplz_uses_thirdparty_services_notice');
function cmplz_uses_thirdparty_services_notice(){
    $thirdparty = cmplz_scan_detected_thirdparty_services();
    if ($thirdparty){
        $thirdparty = implode(', ', $thirdparty);
        cmplz_notice(sprintf(__("The scan found third party services for %s on your site, which means the answer should be yes", 'complianz-gdpr'), $thirdparty));
    }
}

add_action('cmplz_notice_purpose_personaldata', 'cmplz_purpose_personaldata_notice');
function cmplz_purpose_personaldata_notice(){
    if (cmplz_has_region('us') && COMPLIANZ()->cookie->uses_non_functional_cookies()){
        cmplz_notice(__("The cookie scan detected non-functional cookies on your site. According to the CCPA, you are considered to 'Sell' personal data if you collect, share or sell personal data by any means. When a website uses non-functional cookies, it is collecting personal data.", 'complianz-gdpr'));
    }
}

add_action('cmplz_notice_thirdparty_services_on_site', 'cmplz_google_fonts_recommendation');
function cmplz_google_fonts_recommendation(){

    if (!cmplz_has_region('eu')) return;

    $thirdparties = cmplz_get_value('thirdparty_services_on_site');
    if ($thirdparties) {
        foreach ($thirdparties as $thirdparty=>$key) {
            if ($key!=1) continue;
            if ($thirdparty==='google-fonts') {
                cmplz_notice(sprintf(__("Your site uses Google Fonts. For best privacy compliance, we recommended to self host Google Fonts. To self host, follow the instructions in %sthis article%s", 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/self-hosting-google-fonts-for-wordpress/">','</a>'));

            }
            if ($thirdparty==='google-recaptcha' && cmplz_get_value('disable_cookie_block')!=1) {
                cmplz_notice(sprintf(__("Your site uses Google Recaptcha. For privacy compliance, recaptcha will be blocked until cookies are accepted. Please read %sthis article%s for more information", 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/google-recaptcha-and-the-gdpr-a-possible-conflict/">','</a>'),'warning');
            }
        }
    }

}

add_action('cmplz_notice_used_cookies', 'cmplz_used_cookies_notice');
function cmplz_used_cookies_notice(){

    if (cmplz_uses_only_functional_cookies()) return;

    //not relevant if cookie blocker is disabled
    if (cmplz_get_value('disable_cookie_block')==1) return;

    cmplz_notice(sprintf(__("Because your site uses third party cookies, the cookie blocker is now activated. If you experience issues on the front-end of your site due to blocked scripts, please try disabling the cookie blocker in the %ssettings%s", 'complianz-gdpr'), '<a href="'.admin_url('admin.php?page=cmplz-settings').'">','</a>'),'warning');

}

add_action('cmplz_notice_data_disclosed_us', 'cmplz_data_disclosed_us');
function cmplz_data_disclosed_us(){

    if (COMPLIANZ()->cookie->uses_non_functional_cookies()) {
        cmplz_notice(__("The cookie scan detected non-functional cookies on your site. If these cookies were also used in the past 12 months, you should at least select the option 'Internet activity...'", 'complianz-gdpr'));
    }
}

add_action('cmplz_notice_data_sold_us', 'cmplz_data_sold_us');
function cmplz_data_sold_us(){

    if (COMPLIANZ()->cookie->uses_non_functional_cookies()) {
        cmplz_notice(__("The cookie scan detected non-functional cookies on your site. If these cookies were also used in the past 12 months, you should at least select the option 'Internet activity...'", 'complianz-gdpr'));
    }

}

add_action('cmplz_notice_no_cookies_used', 'cmplz_notice_no_cookies_used');
function cmplz_notice_no_cookies_used(){

    if (cmplz_get_value('uses_cookies')==='no') {
        cmplz_notice(__("You have indicated your site does not use cookies. If you're sure about this, you can skip this step", 'complianz-gdpr'),'warning');
    }

}

add_action('cmplz_notice_uses_ad_cookies_personalized', 'cmplz_notice_personalized_ads_based_on_consent');
function cmplz_notice_personalized_ads_based_on_consent(){
    cmplz_notice(__("With Tag Manager, you can also configure your (personalized) advertising based on consent.", 'complianz-gdpr').COMPLIANZ()->config->read_more('https://complianz.io/setting-up-consent-based-advertising/'));
}

add_action('cmplz_notice_GTM_code', 'cmplz_notice_stats_non_functional');
add_action('cmplz_notice_UA_code', 'cmplz_notice_stats_non_functional');
add_action('cmplz_notice_matomo_site_id', 'cmplz_notice_stats_non_functional');

function cmplz_notice_stats_non_functional(){
    if (!cmplz_manual_stats_config_possible()) {
        cmplz_notice(__("You have selected options which indicate your statistics tracking needs a cookie banner. To enable Complianz to handle the statistics, you should remove your current statistics tracking, and configure it in Complianz", 'complianz-gdpr'),'warning');
    } else {
        cmplz_notice( __('If you add the ID for your statistics tool here, Complianz will configure your site for statistics tracking.', 'intro cookie usage', 'complianz-gdpr'));
    }
}

add_action('cmplz_notice_statistics_script', 'cmplz_notice_statistics_script');
function cmplz_notice_statistics_script(){

        cmplz_notice( __('You have indicated you use a statistics tool which tracks personal data. You can insert this script here so it only fires if the user consents to this.', 'intro cookie usage', 'complianz-gdpr'));

}

/*
 * Suggest answer for the uses cookies question
 *
 * */

add_action('cmplz_notice_uses_cookies', 'cmplz_show_cookie_usage_notice');
function cmplz_show_cookie_usage_notice()
{
    $cookie_types = COMPLIANZ()->cookie->get_detected_cookie_types(true, true);
    if (count($cookie_types) > 0) {
        $count = count($cookie_types);
        $cookie_types = implode(', ', $cookie_types);

        cmplz_notice(sprintf(__("The cookie scan detected %s types of cookies on your site: %s, which means the answer to this question should be Yes.", 'complianz-gdpr'), $count, $cookie_types), 'warning');

    } else {
        cmplz_notice(__("Statistical cookies and PHP session cookie aside, the cookie scan detected no cookies on your site which means the answer to this question can be answered with No.", 'complianz-gdpr'));

    }
}

add_action('cmplz_notice_use_categories', 'cmplz_show_use_categories_notice');
function cmplz_show_use_categories_notice()
{
    $tm_fires_scripts = cmplz_get_value('fire_scripts_in_tagmanager') === 'yes' ? true : false;
    $uses_tagmanager = cmplz_get_value('compile_statistics') === 'google-tag-manager' ? true : false;
    if ($uses_tagmanager && $tm_fires_scripts) {
        cmplz_notice(__('If you want to specify the categories used by Tag Manager, you need to enable categories.','complianz-gdpr'), 'warning');

    } elseif (COMPLIANZ()->cookie->cookie_warning_required_stats()) {
        cmplz_notice(__("Categories are mandatory for your statistics configuration", 'complianz-gdpr').COMPLIANZ()->config->read_more('https://complianz.io/statistics-as-mandatory-category'), 'warning');
    }
}


/*
 * For the cookie page and the US banner we need a link to the privacy statement.
 * In free, and in premium when the privacy statement is not enabled, we choose the WP privacy page. If it is not set, the user needs to create one.
 *
 *
 * */

add_action('cmplz_notice_missing_privacy_page', 'cmplz_notice_missing_privacy_page');
function cmplz_notice_missing_privacy_page(){
    $privacy_policy_exists = get_option('wp_page_for_privacy_policy') && get_post(get_option('wp_page_for_privacy_policy')) && get_post_status(get_option('wp_page_for_privacy_policy'))==='publish';
    if ((defined('cmplz_free') || cmplz_get_value('privacy-statement')!=='yes') && !$privacy_policy_exists) {
        cmplz_notice(sprintf(__("You do not have a privacy statement page selected, which is needed to configure your site. You can either let Complianz Privacy Suite premium handle it for you, or create one yourself and set it as the WordPress privacy page %shere%s", 'complianz-gdpr'),'<a href="'.admin_url('privacy.php').'">','</a>'),'warning');
    }

}


add_filter('cmplz_default_value', 'cmplz_set_default', 10, 2);
function cmplz_set_default($value, $fieldname)
{
    if ($fieldname == 'purpose_personaldata') {
        if (cmplz_has_region('us') && COMPLIANZ()->cookie->uses_non_functional_cookies()) {
            //possibly not an array yet, when it's empty
            if (!is_array($value)) $value = array();
            $value['selling-data-thirdparty'] = 1;
            return $value;
        }
    }

//    if ($fieldname === 'use_categories') {
//        if (COMPLIANZ()->cookie->cookie_warning_required_stats()) {
//            return 1;
//        }
//    }

    /*
     * When cookies are detected, the user should select yes on this questino
     *
     * */

    if ($fieldname === 'uses_cookies') {
        if (!empty(COMPLIANZ()->cookie->get_detected_cookies())) {
            return 'yes';
        }
    }

    if ($fieldname == 'popup_background_color' || $fieldname == 'button_text_color') {
        $brand = cmplz_get_value('brand_color');
        if (!empty($brand)) return $brand;
    }

    if ($fieldname == 'purpose_personaldata') {
        $contact_forms = cmplz_site_uses_contact_forms();
        if ($contact_forms) {
            //possibly not an array yet, when it's empty
            if (!is_array($value)) $value = array();
            $value['contact'] = 1;
            return $value;
        }
    }

    if ($fieldname == 'dpo_or_gdpr') {
        if (!cmplz_company_located_in_region('eu')) return 'gdpr_rep';
    }

    if ($fieldname == 'dpo_or_uk_gdpr') {
        if (!cmplz_company_located_in_region('uk')) {
            return 'uk_gdpr_rep';
        }
    }

    if ($fieldname == 'country_company') {
        $country_code = substr(get_locale(),3,2);
        if (isset(COMPLIANZ()->config->countries[$country_code])) {
            $value = $country_code;
        }

    }

    if ($fieldname === 'uses_social_media'){
        $social_media = cmplz_scan_detected_social_media();
        if ($social_media) {
            return 'yes';
        }
    }

    if ($fieldname === 'socialmedia_on_site') {
        $social_media = cmplz_scan_detected_social_media();
        if ($social_media) {
            $current_social_media = array();
            foreach ($social_media as $key) {
                $current_social_media[$key] = 1;
            }
            return $current_social_media;
        }
    }

    if ($fieldname === 'uses_thirdparty_services'){
        $thirdparty = cmplz_scan_detected_thirdparty_services();
        if ($thirdparty) return 'yes';
    }

    if ($fieldname === 'thirdparty_services_on_site') {
        $thirdparty = cmplz_scan_detected_thirdparty_services();
        if ($thirdparty) {
            $current_thirdparty = array();
            foreach ($thirdparty as $key) {
                $current_thirdparty[$key] = 1;
            }

            return $current_thirdparty;
        }
    }

    if ($fieldname === 'data_disclosed_us' || $fieldname === 'data_sold_us'){
        if (COMPLIANZ()->cookie->uses_non_functional_cookies()) {
            //possibly not an array yet.
            if (!is_array($value)) $value = array();
            $value['internet'] = 1;
            return $value;
        }
    }

    return $value;
}
