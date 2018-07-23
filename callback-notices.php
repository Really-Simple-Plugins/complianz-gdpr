<?php

add_action('cmplz_notice_dpo_or_gdpr', 'cmplz_dpo_or_gdpr');
function cmplz_dpo_or_gdpr(){

    ?><div class="cmplz-notice"><?php
    if (!cmplz_company_in_eu()){
        echo __("Your company is located outside the EU, so should appoint a GDPR representative in the EU.", 'complianz');
    } else {
        echo __("Your company is located in the EU, so you do not need to appoint a GDPR representative in the EU.", 'complianz');
    }
    ?></div><?php
}

add_action('cmplz_notice_uses_social_media', 'cmplz_uses_social_media_notice');
function cmplz_uses_social_media_notice(){
    $social_media = cmplz_scan_detected_social_media();
    if ($social_media){
        ?><div class="cmplz-notice"><?php
        $social_media = implode(', ', $social_media);
        printf(__("The scan found social media buttons or widgets for %s on your site, which means the answer should be yes", 'complianz'), $social_media);
        ?></div><?php
    }
}

add_action('cmplz_notice_uses_thirdparty_services', 'cmplz_uses_thirdparty_services_notice');
function cmplz_uses_thirdparty_services_notice(){
    $thirdparty = cmplz_scan_detected_thirdparty_services();
    if ($thirdparty){
        ?><div class="cmplz-notice"><?php
        $thirdparty = implode(', ', $thirdparty);
        printf(__("The scan found third party services for %s on your site, which means the answer should be yes", 'complianz'), $thirdparty);
        ?></div><?php
    }
}



add_action('cmplz_notice_purpose_personal_data', 'cmplz_purpose_personal_data');
function cmplz_purpose_personal_data(){
    $contact_forms = cmplz_site_uses_contact_forms();
    if ($contact_forms){
        ?><div class="cmplz-notice"><?php
        $social_media = implode(', ', $social_media);
        printf(__("The scan found contact forms on your site, so you should select the 'contact' option.", 'complianz'), $social_media);
        ?></div><?php
    }
}




add_filter('complianz_default_value', 'cmplz_set_default', 10, 2);
function cmplz_set_default($value, $fieldname)
{

    if ($fieldname == 'popup_background_color' || $fieldname == 'button_text_color') {
        $brand = cmplz_get_value('brand_color');
        if (!empty($brand)) return $brand;
    }

    if ($fieldname == 'purpose_personaldata') {
        $contact_forms = cmplz_site_uses_contact_forms();
        if ($contact_forms) {
            $value['contact'] = 1;
        }
    }

    if ($fieldname == 'dpo_or_gdpr') {
        if (!cmplz_company_in_eu()) return 'gdpr_rep';
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



    return $value;
}

