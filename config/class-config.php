<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_config_free")) {
    class cmplz_config_free{
        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;
        }

        public function filter_fields($fields){
            return $fields;
        }

        public function add_dynamic_document_elements($elements, $fields)
        {
            return $elements;
        }
    }
}

class cmplz_config_base extends cmplz_config_free {}


if (!class_exists("cmplz_config")) {
    class cmplz_config extends cmplz_config_base
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

            $this->premium_geoip = sprintf(__("To enable the warning only for countries with a cookie law, %sget premium%s.", 'complianz'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_ab_testing = sprintf(__("If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.", 'complianz'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";

            define('CMPLZ_MINUTES_PER_QUESTION', 0.6);
            define('CMPLZ_MINUTES_PER_QUESTION_QUICK', 0.2);
            define('CMPLZ_MAIN_MENU_POSITION', 40);
            define('CMPLZ_PROCESSING_MENU_POSITION', 41);
            define('CMPLZ_DATALEAK_MENU_POSITION', 42);

            define('STEP_COMPANY', 1);
            define('STEP_PLUGINS', 2);
            define('STEP_COOKIES', 2);
            define('STEP_MENU',    3);
            define('STEP_FINISH',  4);

            $steps = (STEP_PLUGINS==STEP_COOKIES) ? STEP_COOKIES : STEP_PLUGINS.", ".STEP_COOKIES;
            define('CMPLZ_REVIEW_STEPS', $steps);


            require_once(cmplz_path . '/config/known-cookies.php');
            require_once(cmplz_path . '/config/countries.php');
            require_once(cmplz_path . '/config/steps.php');
            require_once(cmplz_path . '/config/warnings.php');
            require_once(cmplz_path . '/config/cookie-settings.php');
            require_once(cmplz_path . '/config/general-settings.php');
            require_once(cmplz_path . '/config/social-media-markers.php');

            require_once(cmplz_path . '/config/questions-wizard.php');
            require_once(cmplz_path . '/config/documents/documents.php');
            require_once(cmplz_path . '/config/documents/cookie-policy.php');

            $this->init();


        }

        static function this()
        {
            return self::$_this;
        }


//        public function get_pages_options()
//        {
//            $pages = COMPLIANZ()->cookie->get_pages_list();
//            $output = array();
//            foreach ($pages as $page_id) {
//                $title = get_the_title($page_id);
//                if (empty($title)) continue;
//                $output[$page_id] = $title;
//            }
//            return $output;
//        }


        public function fields($page = false, $step = false, $section = false)
        {
            $output = array();
            $has_sections = $this->has_sections($page, $step);

            $this->fields = $this->filter_fields($this->fields);
            foreach ($this->fields as $fieldname => $field) {
                if ($page && ($field['page'] != $page)) continue;

                if ($step) {
                    if ($has_sections && $section && isset($field['section'])) {
                        if (($field['step'] == $step) && ($field['section'] == $section)) $output[$fieldname] = $field;
                    } else {
                        if (($field['step'] == $step)) $output[$fieldname] = $field;
                    }
                } else {
                    $output[$fieldname] = $field;
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
            $this->document_elements = $this->add_dynamic_document_elements($this->document_elements, $this->fields());

        }


    }

} //class closure

