<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_config")) {

    class cmplz_config
    {
        private static $_this;
        public $fields = array();
        public $sections;
        public $pages;
        public $warning_types;
        public $document_elements;
        public $yes_no;
        public $known_cookie_keys;
        public $ignore_cookie_list;
        public $countries;
        public $purposes;
        public $details_per_purpose_us;
        public $regions;
        public $social_media_markers, $script_tags, $iframe_tags;
        public $eu_countries;
        public $premium_geo_ip;
        public $premium_ab_testing;
        public $collected_info_children;
        public $steps_to_review_on_changes;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            //common options type
            $this->yes_no = array(
                'yes' => __('Yes', 'complianz'),
                'no' => __('No', 'complianz'),
            );

            $this->premium_geo_ip = sprintf(__("To enable the warning only for countries with a cookie law, %sget premium%s.", 'complianz'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_ab_testing = sprintf(__("If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.", 'complianz'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";

            define('CMPLZ_MINUTES_PER_QUESTION', 0.33);
            define('CMPLZ_MINUTES_PER_QUESTION_QUICK', 0.1);
            define('CMPLZ_MAIN_MENU_POSITION', 40);
            define('CMPLZ_PROCESSING_MENU_POSITION', 41);
            define('CMPLZ_DATALEAK_MENU_POSITION', 42);



            //default region code
            define('CMPLZ_DEFAULT_REGION',  'us');

            /*
             * The legal version is only updated when document contents or the questions leading to it are changed
             * 1: start version
             * 2: introduction of US privacy questions
             *
             * */
            define('CMPLZ_LEGAL_VERSION', '2');

            /*statistics*/
            define('CMPLZ_AB_TESTING_DURATION', 30); //Days

            define('STEP_COMPANY', 1);
            define('STEP_PLUGINS', 2);
            define('STEP_COOKIES', 2);
            define('STEP_MENU',    3);
            define('STEP_FINISH',  4);

            $this->steps_to_review_on_changes = (STEP_PLUGINS==STEP_COOKIES) ? STEP_COOKIES : STEP_PLUGINS.", ".STEP_COOKIES;


            require_once(cmplz_path . '/config/countries.php');
            $this->init_arrays();

            require_once(cmplz_path . '/config/known-cookies.php');
            require_once(cmplz_path . '/config/steps.php');


            require_once(cmplz_path . '/config/warnings.php');
            require_once(cmplz_path . '/config/cookie-settings.php');
            require_once(cmplz_path . '/config/general-settings.php');
            require_once(cmplz_path . '/config/social-media-markers.php');

            require_once(cmplz_path . '/config/questions-wizard.php');
            require_once(cmplz_path . '/config/dynamic-fields.php');
            require_once(cmplz_path . '/config/dynamic-document-elements.php');

            require_once(cmplz_path . '/config/documents/documents.php');
            require_once(cmplz_path . '/config/documents/cookie-policy.php');
            require_once(cmplz_path . '/config/documents/cookie-policy-us.php');


            $this->init();
        }

        static function this()
        {
            return self::$_this;
        }


        public function get_section_by_id($id) {

            $steps = $this->steps['wizard'];
            foreach ($steps as $step){
                if (!isset($step['sections'])) continue;
                $sections = $step['sections'];
                foreach($sections as $key => $section){
                    if (isset($section['id']) && $section['id']===$id) return $key;
                }

            }

        }

        public function get_step_by_id($id) {

            $steps = $this->steps['wizard'];
            foreach($steps as $key => $step){
                if (isset($step['id']) && $step['id']===$id) return $key;
            }

        }

        public function init_arrays(){

            $this->purposes = array(
                'contact' => __('Contact - Through phone,  mail, e-mail and/or webforms', 'complianz'),
                'payments' => __('Payments', 'complianz'),
                'register-account' => __('Registering an account', 'complianz'),
                'newsletters' => __('Newsletters', 'complianz'),
                'support-services' => __('To support services or products that your customer wants to buy or have purchased', 'complianz'),
                'legal-obligations' => __('To be able to comply with legal obligations', 'complianz'),
                'statistics' => __('Compiling and analyzing statistics for website improvement.', 'complianz'),
                'offer-personalized-products' => __('To be able to offer personalized products and services.', 'complianz'),
            );

            if (cmplz_has_region('us')) {
                $this->purposes['selling-data-thirdparty'] = __('To sell data to third parties', 'complianz');
            }
            $this->details_per_purpose_us = array(
                'first-lastname' => __('A first and last name', 'complianz'),
                'accountname-alias' => __('Accountname or alias', 'complianz'),
                'address' => __('A home or other physical address, including street name and name of a city or town', 'complianz'),
                'email' => __('An e-mail address', 'complianz'),
                'phone' => __('A telephone number', 'complianz'),
                'social-security' => __('A social security number', 'complianz'),
                'any-other' => __('Any other identifier that permits the physical or online contacting of a specific individual', 'complianz'),
                'ip' => __('IP adres', 'complianz'),
                'signature' => __('A signature', 'complianz'),
                'physical-characteristic' => __('Physical characteristics or description', 'complianz'),
                'passport' => __('Passport number', 'complianz'),
                'drivers-license' => __("Driver's license", 'complianz'),
                'state-id' => __('State identification card number', 'complianz'),
                'insurance-policy' => __('Insurance policy number', 'complianz'),
                'education' => __('Education information', 'complianz'),
                'employment' => __('Professional or employment-related information', 'complianz'),
                'employment-history' => __('Employment history', 'complianz'),
                'bank-account' => __('Bank account number', 'complianz'),
                'financial-information' => __('Financial information such as bank account number of credit card number', 'complianz'),
                'medical' => __('Medical information', 'complianz'),
                'health-insurcance' => __('Health insurance information', 'complianz'),
                'commercial' => __('Commercial information, including records of personal property, products or services purchased, obtained, or considered', 'complianz'),
                'biometric' => __('Biometric information', 'complianz'),
                'internet' => __("Internet activity information, including, but not limited to, browsing history, search history, and information regarding a consumer's interaction with an Internet Web site, application, or advertisement.", 'complianz'),
                'geo' => __('Geolocation data', 'complianz'),
                'audio' => __('Audio, electronic, visual, thermal, olfactory, or simular information', 'complianz'),
            );


            $this->collected_info_children = array(
                'name' => __('a first and last name','complianz'),
                'address' => __('a home or other physical address including street name and name of a city or town','complianz'),
                'email-child' => __('an email adress from the child','complianz'),
                'email-parent' => __('an email adress from the parent or guardian','complianz'),
                'phone' => __('a telephone number','complianz'),
                'social-security-nr' => __('a Social Security number','complianz'),
                'identifier-online' => __('an identifier that permits the physical or online contacting of a child','complianz'),
                'other' => __('other information concerning the child or the parents, combined with an identifier as described above.','complianz'),
            );


        }


        public function fields($page = false, $step = false, $section = false, $variant_id = '')
        {

            $output = array();
            $has_sections = $this->has_sections($page, $step);

            $fields = $this->fields;
            if ($page) $fields = cmplz_array_filter_multidimensional($this->fields, 'page', $page);

            foreach ($fields as $fieldname => $field) {
                //if ($page && ($field['page'] != $page)) continue;

                if ($step) {
                    if ($has_sections && $section && isset($field['section'])) {
                        if (($field['step'] == $step) && ($field['section'] == $section)) $output[$fieldname] = $field;
                    } else {
                        if (($field['step'] == $step)) $output[$fieldname] = $field;
                    }
                }
                if (!$step) {
                    //the variant id is only used for cookies, which does not use steps or sections, so it's only applied here.
                    $field_variant_id = (!isset($field['has_variations']) || !$field['has_variations']) ? '' : $variant_id;
                    $output[$fieldname . $field_variant_id] = $field;
                }

            }

            return $output;
        }

        public function has_sections($page, $step)
        {

            if (isset($this->steps[$page][$step]["sections"])) {
                return true;
            }

            return false;
        }

        public function init()
        {
            $this->fields = apply_filters('cmplz_fields', $this->fields);
            $this->document_elements = apply_filters('cmplz_document_elements', $this->document_elements, $this->fields());


        }






    }

} //class closure

