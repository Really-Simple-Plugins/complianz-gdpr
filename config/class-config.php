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
        public $social_media_markers, $script_tags, $style_tags, $iframe_tags, $async_list, $image_tags;
        public $eu_countries;
        public $premium_geo_ip;
        public $premium_ab_testing;
        public $premium_privacypolicy;
        public $premium_disclaimer;
        public $collected_info_children;
        public $steps_to_review_on_changes;
        public $thirdparty_services;
        public $thirdparty_socialmedia;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;

            //common options type
            $this->yes_no = array(
                'yes' => __('Yes', 'complianz-gdpr'),
                'no' => __('No', 'complianz-gdpr'),
            );




            $this->steps_to_review_on_changes = (STEP_PLUGINS==STEP_COOKIES) ? STEP_COOKIES : STEP_PLUGINS.", ".STEP_COOKIES;
            $this->premium_geo_ip = sprintf(__("To enable the warning only for countries with a cookie law, %sget premium%s.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_ab_testing = sprintf(__("If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_privacypolicy = sprintf(__("A comprehensive, legally validated privacy statement is part of the %spremium%s plugin.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_disclaimer = sprintf(__("A comprehensive, legally validated disclaimer is part of the %spremium%s plugin.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";

            /* config files */
            require_once(cmplz_path . '/config/countries.php');
            require_once(cmplz_path . '/config/purpose.php');
            require_once(cmplz_path . '/config/cookie-database.php');
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
            require_once(cmplz_path . '/config/documents/cookie-policy-uk.php');

            if (file_exists(cmplz_path . '/pro/config/')) {
                require_once(cmplz_path . '/pro/config/steps.php');
                require_once(cmplz_path . '/pro/config/warnings.php');
                require_once(cmplz_path . '/pro/config/questions-wizard.php');
                require_once(cmplz_path . '/pro/config/EU/questions-dataleak.php');
                require_once(cmplz_path . '/pro/config/US/questions-dataleak.php');
                require_once(cmplz_path . '/pro/config/UK/questions-dataleak.php');
                require_once(cmplz_path . '/pro/config/EU/questions-processing.php');
                require_once(cmplz_path . '/pro/config/US/questions-processing.php');
                require_once(cmplz_path . '/pro/config/UK/questions-processing.php');
                require_once(cmplz_path . '/pro/config/dynamic-fields.php');
                require_once(cmplz_path . '/pro/config/dynamic-document-elements.php');
                require_once(cmplz_path . '/pro/config/documents/US/dataleak-report.php');
                require_once(cmplz_path . '/pro/config/documents/US/privacy-policy.php');
                require_once(cmplz_path . '/pro/config/documents/US/processing-agreement.php');
                require_once(cmplz_path . '/pro/config/documents/US/privacy-policy-children.php');
                require_once(cmplz_path . '/pro/config/documents/documents.php');
                require_once(cmplz_path . '/pro/config/documents/disclaimer.php');
                require_once(cmplz_path . '/pro/config/documents/EU/privacy-policy.php');
                require_once(cmplz_path . '/pro/config/documents/EU/processing-agreement.php');
                require_once(cmplz_path . '/pro/config/documents/EU/dataleak-report.php');

                require_once(cmplz_path . '/pro/config/documents/UK/privacy-policy.php');
                require_once(cmplz_path . '/pro/config/documents/UK/processing-agreement.php');
                require_once(cmplz_path . '/pro/config/documents/UK/dataleak-report.php');
                require_once(cmplz_path . '/pro/config/documents/UK/privacy-policy-children.php');
            }

            add_action('plugins_loaded', array($this,'init'), 10);

            //$this->init();
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
                //because the step arrays start with one instead of 0, we increase with one
                return array_search($id, array_column($sections, 'id'))+1;
            }

        }

        public function get_step_by_id($id) {

            $steps = $this->steps['wizard'];
            //because the step arrays start with one instead of 0, we increase with one
            return array_search($id, array_column($steps, 'id'))+1;
        }


        /**
         * Create a generic read more text with link for help texts.
         * @param string $url
         * @param bool $add_space
         * @return string
         */

        public function read_more($url, $add_space=true){
            $html = sprintf(__("For more information on this subject, please read this %sarticle%s", 'complianz-gdpr'), '<a target="_blank" href="'.$url.'">','</a>');
            if ($add_space) $html = '&nbsp;'.$html;
            return $html;
        }

        public function fields($page = false, $step = false, $section = false, $get_by_fieldname =false)
        {

            $output = array();
            $fields = $this->fields;
            if ($page) $fields = cmplz_array_filter_multidimensional($this->fields, 'source', $page);


            foreach ($fields as $fieldname => $field) {
                if ($get_by_fieldname) {
                }
                if ($get_by_fieldname && $fieldname!==$get_by_fieldname) continue;

                if ($step) {
                    if ($section && isset($field['section'])) {
                        if (($field['step'] == $step) && ($field['section'] == $section)) $output[$fieldname] = $field;
                    } else {
                        if (($field['step'] == $step)) $output[$fieldname] = $field;
                    }
                }
                if (!$step) {
                    $output[$fieldname] = $field;
                }

            }

            if ($get_by_fieldname){
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

            if (!is_admin() || (is_admin() && isset($_GET['page']) && strpos($_GET['page'],'cmplz')!==FALSE)){
                $this->fields = apply_filters('cmplz_fields', $this->fields);
            }

            if (!is_admin()) {
                $this->document_elements = apply_filters('cmplz_document_elements', $this->document_elements, $this->fields());
            }
        }


    }

} //class closure

