<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_config")) {

    class cmplz_config
    {
        private static $_this;
        public $fields = array();


        //used to check if social media is used on site

        public $thirdparty_services = array(
            'google-fonts' => 'Google Fonts',
            'google-recaptcha' => 'Google reCAPTCHA',
            "google-maps" => 'Google Maps',
            "openstreetmaps" => 'OpenStreetMaps',
            "vimeo" => 'Vimeo',
            "youtube" => 'YouTube',
            "videopress" => 'VideoPress',
            "dailymotion" => 'Dailymotion',
            "soundcloud" => 'SoundCloud',
            "paypal" => 'PayPal',
            "spotify" => 'Spotify',
            "hotjar" => 'Hotjar',
            "disqus" => 'Disqus',
            "addthis" => 'AddThis',
            "sharethis" => 'ShareThis',
            "livechat" => 'LiveChat',
        );

        public $thirdparty_socialmedia = array(
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
            'whatsapp' => 'WhatsApp',
            'instagram' => 'Instagram',
            'disqus' => 'Disqus',
            'pinterest' => 'Pinterest',
        );

        public $stats = array(
            'google-analytics' => 'Google Analytics',
            'google-tag-manager' => 'Tag Manager',
            'matomo' => 'Matomo',
        );

        /**
         * This is used in the scan function to tell the user he/she uses social media
         * Also in the function to determine a media type for the placeholders
         * Based on this the cookie warning is enabled.
         *
         * */

        public $social_media_markers = array(
            "linkedin" => array("platform.linkedin.com", 'addthis_widget.js'),
            "twitter" => array('super-socializer', 'sumoSiteId', 'addthis_widget.js', "platform.twitter.com", 'twitter-widgets.js'),
            "facebook" => array('super-socializer', 'sumoSiteId', 'addthis_widget.js', "fb-root", "<!-- Facebook Pixel Code -->", 'connect.facebook.net', 'www.facebook.com/plugins', 'pixel-caffeine'),
            "pinterest" => array('super-socializer', 'assets.pinterest.com'),
            "disqus" => array('disqus.com'),
            "instagram" => array('instawidget.net/js/instawidget.js', 'cdninstagram.com', 'instagram.com'),
        );

        /*
         * Scripts with this string in the content get listed in the third party list.
         * Also used in cmplz_placeholder()
         * */

        public $thirdparty_service_markers = array(
            "google-maps" => array('new google.maps.', 'google.com/maps', 'maps.google.com', 'wp-google-maps'),
            "soundcloud" => array('w.soundcloud.com/player'),
            "openstreetmaps" => array('openstreetmap.org'),
            "vimeo" => array('player.vimeo.com'),
            "google-recaptcha" => array('google.com/recaptcha'),
            "youtube" => array('youtube.com'),
            "videopress" => array('videopress.com/embed', 'videopress.com/videopress-iframe.js'),
            "dailymotion" => array('dailymotion.com/embed/video/'),
            "hotjar" => array('static.hotjar.com'),
            "spotify" => array('open.spotify.com/embed'),
            "google-fonts" => array('fonts.googleapis.com'),
            "paypal" => array('www.paypal.com/tagmanager/pptm.js', 'www.paypalobjects.com/api/checkout.js'),
            "disqus" => array('disqus.com'),
            "addthis" => array('addthis.com'),
            "sharethis" => array('sharethis.com'),
            "livechat" => array('cdn.livechatinc.com/tracking.js'),
        );

        public $stats_markers = array(
            'google-analytics' => array('google-analytics.com/ga.js', 'www.google-analytics.com/analytics.js'),
            'google-tag-manager' => array('googletagmanager.com/gtag/js', 'gtm.js'),
            'matomo' => array('piwik.js'),
        );


        /**
         * Some scripts need to be loaded in specific order
         * key: script or part of script to wait for
         * value: script or part of script that should wait
         * */

        /**
         * example:
         *
         *
         * add_filter('cmplz_dependencies', 'my_dependency');
         * function my_dependency($deps){
         * $deps['wait-for-this-script'] = 'script-that-should-wait';
         * return $deps;
         * }
         */
        public $dependencies = array();

        /**
         * placeholders for not iframes
         * */

        public $placeholder_markers = array();

        /**
         * Scripts with this string in the source or in the content of the script tags get blocked.
         *
         * */

        public $script_tags = array();

        /**
         * Style strings (google fonts have been removed in favor of plugin recommendation)
         * */

        public $style_tags = array();

        /**
         * Scripts in this list are loaded with post scribe.js
         * due to the implementation, these should also be added to the list above
         *
         * */

        public $async_list = array();

        public $iframe_tags = array();

        /**
         * images with a URl in this list will get blocked
         * */

        public $image_tags = array();

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
        public $eu_countries;
        public $premium_geo_ip;
        public $premium_ab_testing;
        public $premium_privacypolicy;
        public $premium_disclaimer;
        public $collected_info_children;
        public $steps_to_review_on_changes;

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


            $this->steps_to_review_on_changes = (STEP_PLUGINS == STEP_COOKIES) ? STEP_COOKIES : STEP_PLUGINS . ", " . STEP_COOKIES;
            $this->premium_geo_ip = sprintf(__("To enable the warning only for countries with a cookie law, %sget premium%s.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_ab_testing = sprintf(__("If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_privacypolicy = sprintf(__("A comprehensive, legally validated privacy statement is part of the %spremium%s plugin.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";
            $this->premium_disclaimer = sprintf(__("A comprehensive, legally validated disclaimer is part of the %spremium%s plugin.", 'complianz-gdpr'), '<a href="https://complianz.io" target="_blank">', '</a>') . "&nbsp;";

            /* config files */
            require_once(cmplz_path . '/config/countries.php');
            require_once(cmplz_path . '/config/purpose.php');
            require_once(cmplz_path . '/config/steps.php');
            require_once(cmplz_path . '/config/warnings.php');
            require_once(cmplz_path . '/config/cookie-settings.php');
            require_once(cmplz_path . '/config/general-settings.php');
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

            //after loading integrations on 10
            add_action('plugins_loaded', array($this, 'init'), 15);
        }

        static function this()
        {
            return self::$_this;
        }


        public function get_section_by_id($id)
        {

            $steps = $this->steps['wizard'];
            foreach ($steps as $step) {
                if (!isset($step['sections'])) continue;
                $sections = $step['sections'];
                //because the step arrays start with one instead of 0, we increase with one
                return array_search($id, array_column($sections, 'id')) + 1;
            }

        }

        public function get_step_by_id($id)
        {

            $steps = $this->steps['wizard'];
            //because the step arrays start with one instead of 0, we increase with one
            return array_search($id, array_column($steps, 'id')) + 1;
        }


        /**
         * Create a generic read more text with link for help texts.
         * @param string $url
         * @param bool $add_space
         * @return string
         */

        public function read_more($url, $add_space = true)
        {
            $html = sprintf(__("For more information on this subject, please read this %sarticle%s", 'complianz-gdpr'), '<a target="_blank" href="' . $url . '">', '</a>');
            if ($add_space) $html = '&nbsp;' . $html;
            return $html;
        }

        public function fields($page = false, $step = false, $section = false, $get_by_fieldname = false)
        {

            $output = array();
            $fields = $this->fields;
            if ($page) $fields = cmplz_array_filter_multidimensional($this->fields, 'source', $page);

            foreach ($fields as $fieldname => $field) {
                if ($get_by_fieldname) {
                }
                if ($get_by_fieldname && $fieldname !== $get_by_fieldname) continue;

                if ($step) {
                    if ($section && isset($field['section'])) {
                        if (($field['step'] == $step || (is_array($field['step']) && in_array($step, $field['step']))) && ($field['section'] == $section)) $output[$fieldname] = $field;
                    } else {
                        if (($field['step'] == $step) || (is_array($field['step']) && in_array($step, $field['step']))) $output[$fieldname] = $field;
                    }
                }
                if (!$step) {
                    $output[$fieldname] = $field;
                }

            }

            if ($get_by_fieldname) {
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

            if (!is_admin() || (is_admin() && isset($_GET['page']) && strpos($_GET['page'], 'cmplz') !== FALSE)) {
                $this->fields = apply_filters('cmplz_fields', $this->fields);
            }

            if (!is_admin()) {
                $this->document_elements = apply_filters('cmplz_document_elements', $this->document_elements, $this->fields());
            }
        }


    }

} //class closure

