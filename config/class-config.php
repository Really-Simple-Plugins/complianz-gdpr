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
        public $regions;
        public $social_media_markers, $script_tags, $iframe_tags;
        public $eu_countries;
        public $premium_geo_ip;
        public $premium_ab_testing;

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

            define('CMPLZ_MINUTES_PER_QUESTION', 0.6);
            define('CMPLZ_MINUTES_PER_QUESTION_QUICK', 0.2);
            define('CMPLZ_MAIN_MENU_POSITION', 40);
            define('CMPLZ_PROCESSING_MENU_POSITION', 41);
            define('CMPLZ_DATALEAK_MENU_POSITION', 42);

            define('STEP_COMPANY', 1);
            define('STEP_COOKIES', 2);
            define('STEP_PLUGINS', 2);
            define('STEP_MENU',    3);
            define('STEP_FINISH',  4);

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

            $steps = (STEP_PLUGINS==STEP_COOKIES) ? STEP_COOKIES : STEP_PLUGINS.", ".STEP_COOKIES;
            define('CMPLZ_REVIEW_STEPS', $steps);

            $this->init_arrays();

            require_once(cmplz_path . '/config/known-cookies.php');
            require_once(cmplz_path . '/config/countries.php');
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

            if (cmplz_has_region('us')){
                $this->purposes['selling-data-thirdparty'] = __('To sell data to third parties (US only)', 'complianz');
                $this->details_per_purpose_us = array(
                    '1' => __('A first and last name', 'complianz'),
                    '2' => __('Accountname or alias', 'complianz'),
                    '3' => __('A home or other physical address, including street name and name of a city or town', 'complianz'),
                    '4' => __('An e-mail address', 'complianz'),
                    '5' => __('A telephone number', 'complianz'),
                    '6' => __('A social security number', 'complianz'),
                    '7' => __('Any other identifier that permits the physical or online contacting of a specific individual', 'complianz'),
                    '8' => __('IP adres', 'complianz'),
                    '9' => __('A signature', 'complianz'),
                    '10' => __('A Social security number', 'complianz'),
                    '11' => __('Physical characteristics or description', 'complianz'),
                    '12' => __('Passport number', 'complianz'),
                    '13' => __("Driver's license", 'complianz'),
                    '14' => __('State identification card number', 'complianz'),
                    '15' => __('Onsurance policy number', 'complianz'),
                    '16' => __('Education information', 'complianz'),
                    '17' => __('Professional or employment-related information', 'complianz'),
                    '18' => __('Employment history', 'complianz'),
                    '19' => __('Bank account number', 'complianz'),
                    '20' => __('Financial information such as bank account number of credit card number', 'complianz'),
                    '21' => __('Medical information', 'complianz'),
                    '22' => __('Health insurance information', 'complianz'),
                    '23' => __('Commercial information, including records of personal property, products or services purchased, obtained, ord considered', 'complianz'),
                    '24' => __('Biometric information', 'complianz'),
                    '25' => __("Internet activity information, including, but not limited to, browsing history, search history, and information regarding a consumer's interaction with an Internet Web site, application, or advertisement.", 'complianz'),
                    '26' => __('Geolocation data', 'complianz'),
                    '27' => __('Audio, electronic, visual, thermal, olfactory, or simular information', 'complianz'),
                );
            }

        }


        public function fields($page = false, $step = false, $section = false, $variant_id = '')
        {
            $output = array();
            $has_sections = $this->has_sections($page, $step);

            //$this->fields = $this->filter_fields($this->fields);
            $this->fields = apply_filters('cmplz_fields', $this->fields);
            foreach ($this->fields as $fieldname => $field) {
                if ($page && ($field['page'] != $page)) continue;

                if ($step) {
                    if ($has_sections && $section && isset($field['section'])) {
                        if (($field['step'] == $step) && ($field['section'] == $section)) $output[$fieldname] = $field;
                    } else {
                        if (($field['step'] == $step)) $output[$fieldname] = $field;
                    }
                } else {
                    //the variant id is only used for cookies, which does not use steps or sections, so it's only applied here.
                    $field_variant_id = (!isset($field['has_variations']) || !$field['has_variations']) ? '' : $variant_id;
                    $output[$fieldname.$field_variant_id] = $field;
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
            //$this->document_elements = $this->add_dynamic_document_elements($this->document_elements, $this->fields());
            $this->document_elements = apply_filters('cmplz_document_elements', $this->document_elements, $this->fields());


        }






    }

} //class closure

