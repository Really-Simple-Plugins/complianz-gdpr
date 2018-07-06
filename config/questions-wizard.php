<?php
defined('ABSPATH') or die("you do not have acces to this page!");

/*
 * condition: if a question should be dynamically shown or hidden, depending on another answer. Use NOT answer to hide if not answer.
 * callback_condition: if should be shown or hidden based on an answer in another screen.
 * callback roept action cmplz_$page_$callback aan
 * required: verplicht veld.
 * help: helptext die achter het veld getoond wordt.
 * notice_callback: roept functie aan die iets in de notice stopt, functie die hooked aan cmplz_notice_{notice_callback}


                "fieldname" => '',
                "type" => 'text',
                "required" => false,
                'default' => '',
                'label' => '',
                'table' => false,
                'callback_condition' => false,
                'condition' => false,
                'callback' => false,
                'placeholder' => '',
                'notice_callback' => false,
                'optional' => false,

* */

// MY COMPANY SECTION
$this->fields = $this->fields + array(

//        'privacy-statement-page' => array(
//            'step' => 1,
//            'section' => 1,
//            'page' => 'wizard',
//            'type' => 'select',
//            'label' => __("Select your privacy statement page", 'complianz'),
//            'options' => $this->get_pages_options(),
//            'required' => true,
//            'condition' => array('privacy-statement' => 'no'),
//            'time' => CMPLZ_MINUTES_PER_QUESTION,
//        ),


        'organisation_name' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'label' => __("What is the name of your organization?", 'complianz'),
            'help' => __("The name of your head organisation.", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'address_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'label' => __("What is your address?", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'postalcode_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'label' => __("What is your Postal Code?", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'city_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'label' => __("What is your City?", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'country_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'select',
            'options' => $this->countries,
            'default' => 'NL',
            'label' => __("What is your Country?", 'complianz'),
            'required' => true,
            'help' => __("This setting is automatically selected based on your WordPress language setting.", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'email_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'email',
            'default' => '',
            'label' => __("What is the e-mail address your visitors can use to contact you about privacy issues?", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'telephone_company' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'phone',
            'default' => '',
            'label' => __("What is the telephone number your visitors can use to contact you about privacy issues?", 'complianz'),
            'required' => false,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
    );

$this->fields = $this->fields + array(
// Cookie policy
        'cookie_scan' => array(
            'step' => STEP_COOKIES,
            'section' => 1,
            'page' => 'wizard',
            'type' => 'radio',
            'options' => $this->yes_no,
            'label' => __("Cookie scan", 'complianz'),
            'callback' => 'cookie_scan',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'compile_statistics' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'label' => __("Do you compile statistics of your website?", 'complianz'),
            'options' => array(
                'yes-anonymous' => __('Yes, anonymous', 'complianz'),
                'yes' => __('Yes, and the personal data is available to us.', 'complianz'),
                'google-analytics' => __('Yes, with Google Analytics', 'complianz'),
                'matomo' => __('Yes, with Matomo', 'complianz'),
                'google-tag-manager' => __('Yes, with Google Tag Manager', 'complianz'),
                'no' => __('No', 'complianz')
            ),
            'notice_callback' => 'statistics_usage',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'compile_statistics_more_info' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Regarding the previous question, can you give more information?", 'complianz'),
            'options' => array(
                'accepted' => __('I have accepted the Google data processing amendment', 'complianz'),
                'no-sharing' => __('Google is not allowed to use this data for other Google services', 'complianz'),
                'ip-addresses-blocked' => __('Acquiring IP-addresses is blocked.', 'complianz'),
            ),
            'comment' => __('If you use the built in method for Google Analytics, anonymization of ip numbers is automatically enabled.', 'complianz') . "<br>" . __('If you can check all three options, you might not need a cookie warning on your site.', 'complianz') . "<br>" . sprintf(__('For detailed instructions how to configure Google analytics, please check this %sarticle%s', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/how-to-configure-google-analytics-for-gdpr/">', '</a>'),
            'condition' => array(
                'compile_statistics' => 'google-analytics',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            //'help' => __('If you use the built in method for Google Analytics, anonymization of ip numbers is automatically enabled.','complianz'),
        ),

        'compile_statistics_more_info_tag_manager' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Regarding the previous question, can you give more information?", 'complianz'),
            'options' => array(
                'accepted' => __('I have accepted the Google data processing amendment', 'complianz'),
                'no-sharing' => __('Google is not allowed to use this data for other Google services', 'complianz'),
                'ip-addresses-blocked' => __('Acquiring IP-addresses is blocked.', 'complianz'),
            ),
            'comment' => __('If you use the built in method for Google Analytics, anonymization of ip numbers is automatically enabled.', 'complianz') . "<br>" . __('If you can check all three options, you might not need a cookie warning on your site.', 'complianz') . "<br>" . sprintf(__('For detailed instructions how to configure Google analytics, please check this %sarticle%s', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/how-to-configure-google-analytics-for-gdpr/">', '</a>'),
            'condition' => array(
                'compile_statistics' => 'google-tag-manager',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            //'help' => __('If you use the built in method for Google Tag Manager, anonymization of ip numbers is automatically enabled.','complianz'),
        ),

        'matomo_anonymized' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'select',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Do you anonymize ip numbers in Matomo?", 'complianz'),
            'options' => $this->yes_no,
            'help' => __('If ip numbers are anonymized, the statistics cookie do not require a cookie warning', 'complianz'),
            'condition' => array(
                'compile_statistics' => 'matomo',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'GTM_code' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter your Google Tagmanager code", 'complianz'),
            'condition' => array('compile_statistics' => 'google-tag-manager'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'UA_code' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter your Analytics UA code", 'complianz'),
            'condition' => array('compile_statistics' => 'google-analytics'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'uses_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'label' => __("This website uses cookies or similiar techniques.", 'complianz'),
            'notice_callback' => 'cookie_usage',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'uses_ad_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use cookies for advertising?", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'condition' => array('uses_cookies' => 'yes'),
        ),

        'uses_social_media' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'notice_callback' => 'uses_social_media',
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use social media buttons or widgets?", 'complianz'),
            'help' => __("e.g. Facebook, Twitter, LinkedIn sharing buttons or widgets. These usually place cookies", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'socialmedia_on_site' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'options' => array(
                'facebook' => __('Facebook', 'complianz'),
                'twitter' => __('Twitter', 'complianz'),
                'linkedin' => __('Linkedin', 'complianz'),
                'googleplus' => __('Google Plus', 'complianz'),
                'whatsapp' => __('Whatsapp', 'complianz'),
            ),
            'condition' => array('uses_social_media' => 'yes'),
            'default' => '',
            'label' => __("Select the types of social media you use on the site", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'statistics_script' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'javascript',
            'default' => '',
            'help' => __('Paste here all your scripts that activate cookies. Enter the scripts without the script tags', 'complianz'),
            'revoke_consent_onchange' => true,
            'label' => __("Statistics script", 'complianz'),
            'callback_condition' => array(
                'compile_statistics' => 'NOT google-analytics,NOT google-tag-manager,NOT no',
            ),
            'notice_callback' => 'statistics_script',
            'comment' => sprintf(__('To be able to activate cookies when a user accepts the cookie policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/adding-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'cookie_scripts' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'javascript',
            'optional' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'label' => __("Other scripts used to activate cookies", 'complianz'),
            'help' => __("Paste here all your scripts that activate cookies. Enter the scripts without the script tags", 'complianz'),
            'callback_condition' => array('uses_cookies' => 'yes'),
            'comment' => sprintf(__('To be able to activate cookies when a user accepts the cookie policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/adding-scripts/">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'thirdparty_scripts' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'textarea',
            'optional' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'placeholder' => 'domain.com, domain.org',
            'label' => __("URL's from scripts you want to be blocked before the cookie warning is accepted", 'complianz'),
//            'callback_condition' => array(
//                'uses_cookies' => 'yes',
//                'uses_social_media' => 'yes',
//                'uses_ad_cookies' => 'yes',
//            ),
            'comment' => sprintf(__('The most common third party cookies are blocked automatically. If you want to block other third party cookies, enter the script source here, comma separated. For more information on this, please read %sthis%s article', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'thirdparty_iframes' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'textarea',
            'optional' => true,
            'default' => '',
            'placeholder' => 'domain.com, domain.org',
            'revoke_consent_onchange' => true,
            'label' => __("URL's from iframes you want to be blocked before the cookie warning is accepted", 'complianz'),
            'comment' => sprintf(__('The most common third party cookies are blocked automatically. If you want to block other third party cookies, enter the iframe source here, comma separated. For more information on this, please read %sthis%s article', 'complianz'), '<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'used_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'page' => 'wizard',
            'translatable' => true,
            'type' => 'cookies',
            'optional' => true,
            'default' => '',
            'label' => __("Add the used cookies here", 'complianz'),
            'callback_condition' => array('uses_cookies' => 'yes'),
            'time' => 5,
        ),


    );


$this->fields = $this->fields + array(
        'add_pages_to_menu' => array(
            'step' => STEP_MENU,
            'page' => 'wizard',
            'callback' => 'wizard_add_pages_to_menu',
            'label' => '',
            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
        ),
    );

$this->fields = $this->fields + array(
        'finish_setup' => array(
            'step' => STEP_FINISH,
            'page' => 'wizard',
            'callback' => 'wizard_last_step',
            'label' => '',
        ),
    );


