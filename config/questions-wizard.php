<?php
defined('ABSPATH') or die("you do not have acces to this page!");

/*
 * condition: if a question should be dynamically shown or hidden, depending on another answer. Use NOT answer to hide if not answer.
 * callback_condition: if should be shown or hidden based on an answer in another screen.
 * callback roept action cmplz_$page_$callback aan
 * required: verplicht veld.
 * help: helptext die achter het veld getoond wordt.


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
                'optional' => false,

* */

// MY COMPANY SECTION
$this->fields = $this->fields + array(
        'cookie-policy-type' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'source' => 'wizard',
            'default' => 'default',
            'type' => 'select',
            'options' => array(
                'default' => __("Auto generated cookie policy", 'complianz-gdpr'),
                'custom' => __("Custom cookie policy", 'complianz-gdpr'),
            ),
            'label' => __("Select if you want to use the auto generated cookie policy or your own", 'complianz-gdpr'),
            'required' => true,
            'help' => __('Complianz will generate the cookie policy based on your cookies and the answers in the wizard, but you can also create your own, custom document.',"complianz-gdpr"),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'custom-cookie-policy-url' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'source' => 'wizard',
            'type' => 'url',
            'options' => array(
                'default' => __("Auto generated cookie policy", 'complianz-gdpr'),
                'custom' => __("Custom cookie policy", 'complianz-gdpr'),
            ),
            'condition' => array(
                'cookie-policy-type' => 'custom',
            ),
            'label' => __("Enter the URL to your custom cookie policy", 'complianz-gdpr'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'privacy-statement' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'disabled' => true,
            'source' => 'wizard',
            'type' => 'radio',
            'default' => 'no',
            'label' => __("Do you want to add a privacy statement on your site?", 'complianz-gdpr'),
            'options' => $this->yes_no,
            'comment' => $this->premium_privacypolicy,
            'required' => false,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'disclaimer' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'source' => 'wizard',
            'default' => 'no',
            'disabled' => true,
            'type' => 'radio',
            'options' => $this->yes_no,
            'label' => __("Do you want to add a disclaimer on your site?", 'complianz-gdpr'),
            'comment' => $this->premium_disclaimer,
            'required' => false,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'notice_missing_privacy_page'=> array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'callback',
            'callback' => 'notice_missing_privacy_page',
            'time' => 0,
        ),
        'regions' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'source' => 'wizard',
            'default' => 'eu',
            'type' => 'radio',
            'options' => array(
                'eu' => __('European Union (GDPR), excluding the UK','complianz-gdpr'),
                'uk' => __('United Kingdom (UK-GDPR, PECR, Data Protection Act)','complianz-gdpr'),
                'us' => __('United States','complianz-gdpr'),
            ),
            'label' => __("Which region(s) do you target with your website?", 'complianz-gdpr'),
            'help' => __("This will determine how many and what kind of legal documents and the type of cookie banner and other requirements your site needs.", 'complianz-gdpr'),
            'comment' => sprintf(__("If you want to target customers from several regions, consider upgrading to the %spremium version%s, which allows to select several or all regions simultaneously.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'california' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'source' => 'wizard',
            'default' => 'yes',
            'type' => 'radio',
            'options' => $this->yes_no,
            'condition' => array('regions' => 'us'),
            'label' => __("Do you target visitors from california?", 'complianz-gdpr'),
            'help' => __("There are some rules which only apply to California.", 'complianz-gdpr'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'organisation_name' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'text',
            'default' => '',
            'label' => __("Who is the owner of the website (person or company)?", 'complianz-gdpr'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'address_company' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'placeholder' => __('Address, City and Zipcode','complianz-gdpr'),
            'type' => 'textarea',
            'default' => '',
            'label' => __("What is your address?", 'complianz-gdpr'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'country_company' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'select',
            'options' => $this->countries,
            'default' => 'NL',
            'label' => __("What is your Country?", 'complianz-gdpr'),
            'required' => true,
            'help' => __("This setting is automatically selected based on your WordPress language setting.", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'email_company' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'email',
            'default' => '',
            'help' => __("The email address will be obfuscated on the front-end to prevent spidering.", 'complianz-gdpr'),
            'label' => __("What is the email address your visitors can use to contact you about privacy issues?", 'complianz-gdpr'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'telephone_company' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'phone',
            'default' => '',
            'document_label' => __('Phone number','complianz-gdpr').': ',
            'label' => __("What is the telephone number your visitors can use to contact you about privacy issues?", 'complianz-gdpr'),
            'required' => false,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'brand_color' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'colorpicker',
            'default' => '',
            'label' => __("What is the brand color on your website?", 'complianz-gdpr'),
            'help' => __("This color is used to setup your cookie warning, if you need one", 'complianz-gdpr'),
            'required' => false,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        // Purpose
        'purpose_personaldata' => array(
            'step' => STEP_COMPANY,
            'section' => 5,
            'source' => 'wizard',
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Indicate for what purpose personal data is processed via your website:", 'complianz-gdpr'),
            'help' => __("Also consider future purposes. Regarding personalized products: these are products and/or services which are personalized based on visitor's behavior. E.g. advertisements based on pages visited.", 'complianz-gdpr'),
            'required' => true,
            'options' => $this->purposes,
            'callback_condition' => array('regions' => 'us'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
    );

$this->fields = $this->fields + array(
        // Cookie policy
        'cookie_scan' => array(
            'step' => STEP_COOKIES,
            'section' => 1,
            'source' => 'wizard',
            'type' => 'radio',
            'options' => $this->yes_no,
            'label' => __("Cookie scan", 'complianz-gdpr'),
            'callback' => 'cookie_scan',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'compile_statistics' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'label' => __("Do you compile statistics of your website?", 'complianz-gdpr'),
            'options' => array(
                'yes-anonymous' => __('Yes, anonymous', 'complianz-gdpr'),
                'yes' => __('Yes, and the personal data is available to us.', 'complianz-gdpr'),
                'google-analytics' => __('Yes, with Google Analytics', 'complianz-gdpr'),
                'matomo' => __('Yes, with Matomo', 'complianz-gdpr'),
                'google-tag-manager' => __('Yes, with Google Tag Manager', 'complianz-gdpr'),
                'no' => __('No', 'complianz-gdpr')
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'compile_statistics_more_info' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'source' => 'wizard',
            'type' => 'multicheckbox',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Regarding the previous question, can you give more information?", 'complianz-gdpr'),
            'options' => array(
                'accepted' => __('I have accepted the Google data processing amendment', 'complianz-gdpr'),
                'no-sharing' => __('Google is not allowed to use this data for other Google services', 'complianz-gdpr'),
                'ip-addresses-blocked' => __('Always block acquiring of IP addresses', 'complianz-gdpr'),
            ),
            'help' => __('If you do not check to always block acquiring IP addresses, the IP addresses will get acquired as soon as the user accepts statistics or higher.', 'complianz-gdpr') . "<br>" . __('If you can check all three options, you might not need a cookie warning on your site.', 'complianz-gdpr') . $this->read_more('https://complianz.io/how-to-configure-google-analytics-for-gdpr/'),
            'condition' => array(
                'compile_statistics' => 'google-analytics',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'compile_statistics_more_info_tag_manager' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'source' => 'wizard',
            'type' => 'multicheckbox',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Regarding the previous question, can you give more information?", 'complianz-gdpr'),
            'options' => array(
                'accepted' => __('I have accepted the Google data processing amendment', 'complianz-gdpr'),
                'no-sharing' => __('Google is not allowed to use this data for other Google services', 'complianz-gdpr'),
                'ip-addresses-blocked' => __('Acquiring IP-addresses is blocked.', 'complianz-gdpr'),
            ),
            'help' => __('With Tag Manager you can configure the selective firing of cookies in the Tag Manager dashboard.', 'complianz-gdpr') . $this->read_more('https://complianz.io/how-to-configure-tag-manager-for-gdpr/'),
            'condition' => array(
                'compile_statistics' => 'google-tag-manager',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            //'help' => __('If you use the built in method for Google Tag Manager, anonymization of ip numbers is automatically enabled.','complianz-gdpr'),
        ),

        'fire_scripts_in_tagmanager' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'source' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'condition' => array(
                'compile_statistics' => 'google-tag-manager',
            ),
            'label' => __("Tag Manager fires scripts which place cookies", 'complianz-gdpr'),
            'help' => __('If you use Tag Manager to fire scripts on your site, Complianz will automatically enable categories.', 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'matomo_anonymized' => array(
            'step' => STEP_COOKIES,
            'section' => 2,
            'source' => 'wizard',
            'type' => 'select',
            'revoke_consent_onchange' => true,
            'default' => '',
            'label' => __("Do you anonymize ip numbers in Matomo?", 'complianz-gdpr'),
            'options' => $this->yes_no,
            'help' => __('If ip numbers are anonymized, the statistics cookie do not require a cookie warning', 'complianz-gdpr'),
            'condition' => array(
                'compile_statistics' => 'matomo',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'configuration_by_complianz' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'select',
            'default' => 'yes',
            'label' => __("Do you want Complianz to configure your statistics?", 'complianz-gdpr'),
            'options' => $this->yes_no,
            'help' => __("It's recommended to let Complianz handle the statistics script. This way, the plugin can detect if it needs to be hooked into the cookie consent code or not. But if you have set it up yourself and don't want to change this, you can choose to do so", 'complianz-gdpr'),
            'callback_condition' => 'cmplz_manual_stats_config_possible',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'GTM_code' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'text',
            'default' => '',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter your Google Tagmanager code", 'complianz-gdpr'),
            'callback_condition' => array('compile_statistics' => 'google-tag-manager'),
            'condition' => array('configuration_by_complianz' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' =>__("For the Google Tag Manager code, log on. Then, you will immediatly see Container codes. The one next to your website name is the code you will need to fill in here, the Container ID.", 'complianz-gdpr'),
        ),

        'UA_code' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'text',
            'default' => '',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter your Analytics UA code", 'complianz-gdpr'),
            'callback_condition' => array('compile_statistics' => 'google-analytics'),
            'condition' => array('configuration_by_complianz' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' =>__("For the Google Analytics UA code, log on and click Admin and copy the UA code below Tracking-ID.", 'complianz-gdpr'),
        ),

        'matomo_site_id' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'number',
            'default' => '',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter your Matomo site ID", 'complianz-gdpr'),
            'condition' => array('configuration_by_complianz' => 'yes'),
            'callback_condition' => array('compile_statistics' => 'matomo'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'matomo_url' => array(
            'step' => STEP_COOKIES,
            'section' => 3,
            'source' => 'wizard',
            'type' => 'url',
            'placeholder' => 'https://domain.com/stats',
            'required' => true,
            'revoke_consent_onchange' => true,
            'label' => __("Enter the URL of Matomo", 'complianz-gdpr'),
            'callback_condition' => array('compile_statistics' => 'matomo'),
            'condition' => array('configuration_by_complianz' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' =>__("e.g. https://domain.com/stats", 'complianz-gdpr'),
        ),

        'uses_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'label' => __("This website uses cookies or similiar techniques.", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'uses_thirdparty_services' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use third party services?", 'complianz-gdpr'),
            'help' => __("e.g. services like Google Fonts, Maps or recaptcha usually place cookies.", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),


        'thirdparty_services_on_site' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'multicheckbox',
            'options' => $this->thirdparty_services,
            'default' => '',
            'condition' => array('uses_thirdparty_services' => 'yes'),
            'label' => __("Select the types of third party services you use on your site.", 'complianz-gdpr'),
            'help' => __("Checking services here will add the associated cookies to your cookie policy, and block the service until consent is given (opt-in), or after consent is revoked (opt-out).", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'hotjar_privacyfriendly' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Is Hotjar configured in a privacy-friendly way?", 'complianz-gdpr'),
            'help' => __("You can configure Hotjar privacy-friendly, if you do this, no consent is required for Hotjar.", 'complianz-gdpr').$this->read_more('https://complianz.io/configuring-hotjar-for-gdpr/'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'condition' => array('thirdparty_services_on_site' => 'hotjar'),
        ),

        'uses_social_media' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use social media buttons or widgets?", 'complianz-gdpr'),
            'help' => __("e.g. Facebook, Twitter, LinkedIn sharing buttons or widgets. These usually place cookies", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'socialmedia_on_site' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'multicheckbox',
            'options' => $this->thirdparty_socialmedia,
            'condition' => array('uses_social_media' => 'yes'),
            'default' => '',
            'label' => __("Select the types of social media you use on the site", 'complianz-gdpr'),
            'help' => __("Checking services here will add the associated cookies to your cookie policy, and block the service until consent is given (opt-in), or after consent is revoked (opt-out)", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),


        'uses_ad_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use cookies for advertising?", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'uses_ad_cookies_personalized' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Are any of your advertising cookies used to show personalized ads?", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' => __("If you only use Google for advertising, and have activated the option to use only non personalized ads, you can select no here.", 'complianz-gdpr'),
            'condition' => array(
                'uses_ad_cookies' => 'yes'
            ),
        ),

        'uses_wordpress_comments' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => '',
            'label' => __("Does your website use wordpress comments?", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'block_wordpress_comment_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 4,
            'source' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'revoke_consent_onchange' => true,
            'options' => $this->yes_no,
            'default' => 'yes',
            'label' => __("Disable storage of personal data by WP comments function and consent checkbox", 'complianz-gdpr'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' => __("If you enable this, WordPress will not store personal data with comments and you won't need a consent checkbox for the comment form. The consent box will not be displayed.", 'complianz-gdpr'),
            'condition' => array(
                'uses_wordpress_comments' => 'yes',
                'regions' => 'eu'
            ),
        ),

        'no_cookies_used'=> array(
            'step' => STEP_COOKIES,
            'section' => 5,
            'source' => 'wizard',
            'type' => 'callback',
            'callback' => 'notice_no_cookies_used',
            'time' => 0,
        ),

        'used_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 5,
            'source' => 'wizard',
            'translatable' => true,
            'type' => 'cookies',
            'default' => '',
            'label' => __("Add the used cookies here", 'complianz-gdpr'),
            'callback_condition' => array('uses_cookies' => 'yes'),
            'time' => 5,
        ),

        'report_unknown_cookies' => array(
            'step' => STEP_COOKIES,
            'section' => 5,
            'source' => 'wizard',
            'type' => 'radio',
            'label' => __("Unknown cookies detected", 'complianz-gdpr'),
            'callback' => 'report_unknown_cookies',
            'help' => __('The scan detected cookies which are not listed in the cookie database. You can help us improve the database by reporting these cookies. If you know what a currently unrecognized cookie is for, please add this to the descriptions below, so we can process that information as well.','complianz-gdpr'),
            'callback_condition' => array('uses_cookies' => 'yes'),
            'time' => 0,
        ),

        'statistics_script' => array(
            'step' => STEP_COOKIES,
            'section' => 6,
            'source' => 'wizard',
            'type' => 'javascript',
            'default' => '',
            'revoke_consent_onchange' => true,
            'label' => __("Statistics script", 'complianz-gdpr'),
            'callback_condition' => array(
                'compile_statistics' => 'NOT google-analytics,NOT matomo,NOT google-tag-manager,NOT no,NOT yes-anonymous',
            ),
            'help' => __('Paste here all your scripts that activate cookies. Enter the scripts without the script tags', 'complianz-gdpr').'.&nbsp;'.sprintf(__('To be able to activate cookies when a user accepts the cookie policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/articles/adding-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'table' => true,

        ),

        'cookie_scripts' => array(
            'step' => STEP_COOKIES,
            'section' => 6,
            'source' => 'wizard',
            'type' => 'javascript',
            'optional' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'label' => __("Other scripts used to activate cookies", 'complianz-gdpr'),
            'callback_condition' => array(
                'uses_cookies' => 'yes',
                'compile_statistics' => 'NOT google-tag-manager',
            ),
            'help' => __("Paste here all your scripts that activate cookies. Enter the scripts without the script tags", 'complianz-gdpr').'&nbsp;'.sprintf(__('To be able to activate cookies when a user accepts the cookie policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/articles/adding-scripts/">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'table' => true,

        ),

        'thirdparty_scripts' => array(
            'step' => STEP_COOKIES,
            'section' => 6,
            'source' => 'wizard',
            'type' => 'textarea',
            'optional' => true,
            'default' => '',
            'revoke_consent_onchange' => true,
            'placeholder' => 'domain.com, domain.org',
            'label' => __("URL's from scripts you want to be blocked before the cookie warning is accepted", 'complianz-gdpr'),
            'callback_condition' => array(
                'compile_statistics' => 'NOT google-tag-manager',
            ),
            'help' => sprintf(__('The most common third party cookies are blocked automatically. If you want to block other third party cookies, enter the script source here, comma separated. For more information on this, please read %sthis%s article', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'table' => true,
        ),

        'thirdparty_iframes' => array(
            'step' => STEP_COOKIES,
            'section' => 6,
            'source' => 'wizard',
            'type' => 'textarea',
            'optional' => true,
            'default' => '',
            'placeholder' => 'domain.com, domain.org',
            'revoke_consent_onchange' => true,
            'label' => __("URL's from iframes you want to be blocked before the cookie warning is accepted", 'complianz-gdpr'),
            'help' => sprintf(__('The most common third party cookies are blocked automatically. If you want to block other third party cookies, enter the iframe source here, comma separated. For more information on this, please read %sthis%s article', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">', '</a>'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'table' => true,

        ),





    );


$this->fields = $this->fields + array(
        'add_pages_to_menu' => array(
            'step' => STEP_MENU,
            'source' => 'wizard',
            'callback' => 'wizard_add_pages_to_menu',
            'label' => '',
            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
        ),
    );

$this->fields = $this->fields + array(
        'finish_setup' => array(
            'step' => STEP_FINISH,
            'source' => 'wizard',
            'callback' => 'wizard_last_step',
            'label' => '',
        ),
    );
