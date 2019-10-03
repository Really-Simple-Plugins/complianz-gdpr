<?php
defined('ABSPATH') or die("you do not have acces to this page!");

/*
 * Install cookiebanner table
 * */

add_action('plugins_loaded', 'cmplz_install_cookiebanner_table', 10);
function cmplz_install_cookiebanner_table()
{
    if (get_option('cmplz_cbdb_version') !== cmplz_version) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'cmplz_cookiebanners';
        $sql = "CREATE TABLE $table_name (
             `ID` int(11) NOT NULL AUTO_INCREMENT,
             `banner_version` int(11) NOT NULL,
             `default` int(11) NOT NULL,
             `archived` int(11) NOT NULL,
             `title` varchar(255) NOT NULL,
            `position` varchar(255) NOT NULL,
            `theme` varchar(255) NOT NULL,
            `revoke` varchar(255) NOT NULL,
            `dismiss` varchar(255) NOT NULL,
            `save_preferences` varchar(255) NOT NULL,
            `view_preferences` varchar(255) NOT NULL,
            `category_functional` varchar(255) NOT NULL,
            `category_all` varchar(255) NOT NULL,
            `category_stats` varchar(255) NOT NULL,
            `accept` varchar(255) NOT NULL,
            `message_optin` text NOT NULL,
            `readmore_optin` varchar(255) NOT NULL,
            `use_categories` int(11) NOT NULL,
            `tagmanager_categories` text NOT NULL,
            `hide_revoke` int(11) NOT NULL,
            `soft_cookiewall` int(11) NOT NULL,
            `dismiss_on_scroll` int(11) NOT NULL,
            `dismiss_on_timeout` int(11) NOT NULL,
            `dismiss_timeout` varchar(255) NOT NULL,
            `accept_informational` varchar(255) NOT NULL,
            `message_optout` text NOT NULL,
            `readmore_optout` varchar(255) NOT NULL,
            `readmore_privacy` varchar(255) NOT NULL,
            `popup_background_color` varchar(255) NOT NULL,
            `popup_text_color` varchar(255) NOT NULL,
            `button_background_color` varchar(255) NOT NULL,
            `button_text_color` varchar(255) NOT NULL,
            `border_color` varchar(255) NOT NULL,
            `use_custom_cookie_css` varchar(255) NOT NULL,
            `custom_css` text NOT NULL,
            `statistics` text NOT NULL,
              PRIMARY KEY  (ID)
            ) $charset_collate;";
        dbDelta($sql);
        update_option('cmplz_cbdb_version', cmplz_version);

    }
}

if (!class_exists("cmplz_cookiebanner")) {
    class CMPLZ_COOKIEBANNER
    {
        public $id = false;
        public $banner_version = 0;
        public $title;
        public $default = false;
        public $archived = false;

        /* styling */
        public $position;
        public $theme;
        public $popup_background_color;
        public $popup_text_color;
        public $button_background_color;
        public $button_text_color;
        public $border_color;
        public $use_custom_cookie_css;
        public $custom_css;

        /* texts */
        public $revoke;
        public $dismiss;
        public $save_preferences;
        public $view_preferences;
        public $category_functional;
        public $category_all;
        public $category_stats;
        public $accept;
        public $message_optin;
        public $readmore_optin;
        public $tagmanager_categories;
        public $accept_informational;
        public $message_optout;
        public $readmore_optout;
        public $readmore_privacy;

        public $use_categories;
        public $hide_revoke;
        public $soft_cookiewall;
        public $dismiss_on_scroll;
        public $dismiss_on_timeout;
        public $dismiss_timeout;

        public $save_preferences_x;
        public $view_preferences_x;
        public $category_functional_x;
        public $category_all_x;
        public $category_stats_x;
        public $accept_x;
        public $dismiss_x;
        public $revoke_x;
        public $message_optin_x;
        public $readmore_optin_x;
        public $tagmanager_categories_x;
        public $accept_informational_x;
        public $message_optout_x;
        public $readmore_optout_x;
        public $readmore_privacy_x;

        public $statistics;



        function __construct($id = false)
        {

            $this->id = $id;

            if ($this->id!==FALSE) {
                //initialize the cookiebanner settings with this id.
                $this->get();
            }
        }


        /**
         * Add a new cookiebanner database entry
         */

        private function add(){
            if (!current_user_can('manage_options')) return false;
            $array = array('title' => __('New cookie banner','complianz-gdpr'));

            global $wpdb;
            //make sure we have at least one default banner
            $cookiebanners = $wpdb->get_results("select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = true");
            if (empty($cookiebanners)){
                $array['default'] = true;
            }

            $wpdb->insert(
                $wpdb->prefix.'cmplz_cookiebanners',
                $array
            );
            $this->id = $wpdb->insert_id;

        }



        public function process_form($post){

            if (!current_user_can('manage_options')) return false;

            if (!isset($post['cmplz_nonce'])) return false;

            //check nonce
            if (!isset($post['cmplz_nonce']) || !wp_verify_nonce($post['cmplz_nonce'], 'complianz_save_cookiebanner')) return false;

            foreach ($this as $property => $value)  {
                if (isset($post['cmplz_'.$property])){
                    $this->{$property} = $post['cmplz_'.$property];
                }
            }

            $this->save();
        }

        /**
         * Load the cookiebanner data
         * If ID has value 'default', we get the one with the value 'default'
         */

        private function get()
        {
            global $wpdb;

            if (!intval($this->id)>0) return;

            $cookiebanners = $wpdb->get_results($wpdb->prepare("select * from {$wpdb->prefix}cmplz_cookiebanners where ID = %s", intval($this->id)));

            if (isset($cookiebanners[0])) {
                $cookiebanner = $cookiebanners[0];
                $this->banner_version = $cookiebanner->banner_version;
                $this->title = $cookiebanner->title;
                $this->default = $cookiebanner->default;
                $this->archived = $cookiebanner->archived;
                $this->position = !empty($cookiebanner->position ) ? $cookiebanner->position : $this->get_default('position');
                $this->theme = !empty($cookiebanner->theme ) ? $cookiebanner->theme : $this->get_default('theme');
                $this->revoke = !empty($cookiebanner->revoke ) ? $cookiebanner->revoke : $this->get_default('revoke');
                $this->dismiss = !empty($cookiebanner->dismiss ) ? $cookiebanner->dismiss : $this->get_default('dismiss');
                $this->save_preferences = !empty($cookiebanner->save_preferences ) ? $cookiebanner->save_preferences : $this->get_default('save_preferences');
                $this->view_preferences = !empty($cookiebanner->view_preferences ) ? $cookiebanner->view_preferences : $this->get_default('view_preferences');
                $this->category_functional = !empty($cookiebanner->category_functional ) ? $cookiebanner->category_functional : $this->get_default('category_functional');
                $this->category_all = !empty($cookiebanner->category_all ) ? $cookiebanner->category_all : $this->get_default('category_all');
                $this->category_stats = !empty($cookiebanner->category_stats ) ? $cookiebanner->category_stats : $this->get_default('category_stats');
                $this->accept = !empty($cookiebanner->accept ) ? $cookiebanner->accept : $this->get_default('accept');
                $this->message_optin = !empty($cookiebanner->message_optin ) ? $cookiebanner->message_optin : $this->get_default('message_optin');
                $this->readmore_optin = !empty($cookiebanner->readmore_optin ) ? $cookiebanner->readmore_optin : $this->get_default('readmore_optin');
                $this->use_categories = !empty($cookiebanner->use_categories ) ? $cookiebanner->use_categories : $this->get_default('use_categories');
                $this->tagmanager_categories = !empty($cookiebanner->tagmanager_categories ) ? $cookiebanner->tagmanager_categories : $this->get_default('tagmanager_categories');
                $this->hide_revoke = !empty($cookiebanner->hide_revoke ) ? $cookiebanner->hide_revoke : $this->get_default('hide_revoke');
                $this->soft_cookiewall = !empty($cookiebanner->soft_cookiewall ) ? $cookiebanner->soft_cookiewall : $this->get_default('soft_cookiewall');
                $this->dismiss_on_scroll = !empty($cookiebanner->dismiss_on_scroll ) ? $cookiebanner->dismiss_on_scroll : $this->get_default('dismiss_on_scroll');
                $this->dismiss_on_timeout = !empty($cookiebanner->dismiss_on_timeout ) ? $cookiebanner->dismiss_on_timeout : $this->get_default('dismiss_on_timeout');
                $this->dismiss_timeout = !empty($cookiebanner->dismiss_timeout ) ? $cookiebanner->dismiss_timeout : $this->get_default('dismiss_timeout');
                $this->accept_informational = !empty($cookiebanner->accept_informational ) ? $cookiebanner->accept_informational : $this->get_default('accept_informational');
                $this->message_optout = !empty($cookiebanner->message_optout ) ? $cookiebanner->message_optout : $this->get_default('message_optout');
                $this->readmore_optout = !empty($cookiebanner->readmore_optout ) ? $cookiebanner->readmore_optout : $this->get_default('readmore_optout');
                $this->readmore_privacy = !empty($cookiebanner->readmore_privacy ) ? $cookiebanner->readmore_privacy : $this->get_default('readmore_privacy');
                $this->popup_background_color = !empty($cookiebanner->popup_background_color ) ? $cookiebanner->popup_background_color : $this->get_default('popup_background_color');
                $this->popup_text_color = !empty($cookiebanner->popup_text_color ) ? $cookiebanner->popup_text_color : $this->get_default('popup_text_color');
                $this->button_background_color = !empty($cookiebanner->button_background_color ) ? $cookiebanner->button_background_color : $this->get_default('button_background_color');
                $this->button_text_color = !empty($cookiebanner->button_text_color ) ? $cookiebanner->button_text_color : $this->get_default('button_text_color');
                $this->border_color = !empty($cookiebanner->border_color ) ? $cookiebanner->border_color : $this->get_default('border_color');
                $this->use_custom_cookie_css = !empty($cookiebanner->use_custom_cookie_css ) ? $cookiebanner->use_custom_cookie_css : $this->get_default('use_custom_cookie_css');
                $this->custom_css = !empty($cookiebanner->custom_css ) ? htmlspecialchars_decode($cookiebanner->custom_css) : $this->get_default('custom_css');

                //translated fields
                $this->save_preferences_x = $this->translate($this->save_preferences, 'save_preferences');
                $this->view_preferences_x = $this->translate($this->view_preferences,'view_preferences');
                $this->category_functional_x = $this->translate($this->category_functional,'category_functional');
                $this->category_all_x = $this->translate($this->category_all,'category_all');
                $this->category_stats_x = $this->translate($this->category_stats,'category_stats');
                $this->accept_x = $this->translate($this->accept,'accept');
                $this->revoke_x = $this->translate($this->revoke,'revoke');
                $this->dismiss_x = $this->translate($this->dismiss,'dismiss');
                $this->message_optin_x = $this->translate($this->message_optin,'message_optin');
                $this->readmore_optin_x = $this->translate($this->readmore_optin,'readmore_optin');
                $this->tagmanager_categories_x = $this->translate($this->tagmanager_categories,'tagmanager_categories');
                $this->accept_informational_x = $this->translate($this->accept_informational,'accept_informational');
                $this->message_optout_x = $this->translate($this->message_optout,'message_optout');
                $this->readmore_optout_x = $this->translate($this->readmore_optout,'readmore_optout');
                $this->readmore_privacy_x = $this->translate($this->readmore_privacy,'readmore_privacy');

                $this->statistics = unserialize($cookiebanner->statistics);
            }

        }

        /**
         * Check if this field is translatable
         * @param $fieldname
         * @return bool
         */

        private function translate($value, $fieldname){
            if (function_exists('pll__')) $value = pll__($value);

            if (function_exists('icl_translate')) $value = icl_translate('complianz', $fieldname, $value);

            return $value;

        }

        private function register_translation($string, $fieldname){
            //polylang
            if (function_exists("pll_register_string")) {
                pll_register_string($fieldname, $string, 'complianz');
            }

            //wpml
            if (function_exists('icl_register_string')) {
                icl_register_string('complianz', $fieldname, $string);
            }

            do_action('wpml_register_single_string', 'complianz', $fieldname, $string);

        }

        /**
         * Get a default value
         * @param $fieldname
         * @return string
         */

        private function get_default($fieldname){
            $default = (isset(COMPLIANZ()->config->fields[$fieldname]['default'])) ? COMPLIANZ()->config->fields[$fieldname]['default'] : '';

            return $default;
        }


        /**
         * Save the edited data in the object
         * @param bool $is_default
         * @return void
         */

        public function save()
        {
            if (!current_user_can('manage_options')) return;

            if (!$this->id) {
                $this->add();
            }

            $this->banner_version++;

            //register translations fields
            $this->register_translation($this->save_preferences, 'save_preferences');
            $this->register_translation($this->view_preferences,'view_preferences');
            $this->register_translation($this->category_functional,'category_functional');
            $this->register_translation($this->category_all,'category_all');
            $this->register_translation($this->category_stats,'category_stats');
            $this->register_translation($this->accept,'accept');
            $this->register_translation($this->revoke,'revoke');
            $this->register_translation($this->dismiss,'dismiss');
            $this->register_translation($this->message_optin,'message_optin');
            $this->register_translation($this->readmore_optin,'readmore_optin');
            $this->register_translation($this->tagmanager_categories,'tagmanager_categories');
            $this->register_translation($this->accept_informational,'accept_informational');
            $this->register_translation($this->message_optout,'message_optout');
            $this->register_translation($this->readmore_optout,'readmore_optout');
            $this->register_translation($this->readmore_privacy,'readmore_privacy');

            /**
             * If Tag manager fires categories, enable use categories by default
             */
//            $tm_fires_scripts = cmplz_get_value('fire_scripts_in_tagmanager') === 'yes' ? true : false;
//            $uses_tagmanager = cmplz_get_value('compile_statistics') === 'google-tag-manager' ? true : false;
//            if ($uses_tagmanager && $tm_fires_scripts) {
//                $this->use_categories = true;
//            }

            if (!is_array($this->statistics)) $this->statistics = array();
            $statistics = serialize($this->statistics);
            $update_array = array(
                'position' => sanitize_title($this->position),
                'banner_version' => intval($this->banner_version),
                'archived' => boolval($this->archived),
                'title' => sanitize_text_field($this->title),
                'theme' => sanitize_title($this->theme),
                'revoke' => sanitize_text_field($this->revoke),
                'dismiss' => sanitize_text_field($this->dismiss),
                'save_preferences' => sanitize_text_field($this->save_preferences),
                'view_preferences' => sanitize_text_field($this->view_preferences),
                'category_functional' => sanitize_text_field($this->category_functional),
                'category_all' => sanitize_text_field($this->category_all),
                'category_stats' => sanitize_text_field($this->category_stats),
                'accept' => sanitize_text_field($this->accept),
                'message_optin' => wp_kses($this->message_optin, cmplz_allowed_html()),
                'readmore_optin' => sanitize_text_field($this->readmore_optin),
                'use_categories' => sanitize_text_field($this->use_categories),
                'tagmanager_categories' => sanitize_text_field($this->tagmanager_categories),
                'hide_revoke' => sanitize_title($this->hide_revoke),
                'soft_cookiewall' => sanitize_title($this->soft_cookiewall),
                'dismiss_on_scroll' => boolval($this->dismiss_on_scroll),
                'dismiss_on_timeout' => boolval($this->dismiss_on_timeout),
                'dismiss_timeout' => intval($this->dismiss_timeout),
                'accept_informational' => sanitize_text_field($this->accept_informational),
                'message_optout' => wp_kses($this->message_optout, cmplz_allowed_html()),
                'readmore_optout' => sanitize_text_field($this->readmore_optout),
                'readmore_privacy' => sanitize_text_field($this->readmore_privacy),
                'popup_background_color' => sanitize_hex_color($this->popup_background_color),
                'popup_text_color' => sanitize_hex_color($this->popup_text_color),
                'button_background_color' => sanitize_hex_color($this->button_background_color),
                'button_text_color' => sanitize_hex_color($this->button_text_color),
                'border_color' => sanitize_hex_color($this->border_color),
                'use_custom_cookie_css' => boolval($this->use_custom_cookie_css),
                'statistics' => $statistics,
            );

            if ($this->use_custom_cookie_css){
                $update_array['custom_css']=htmlspecialchars($this->custom_css);
            }

            global $wpdb;
            $updated = $wpdb->update($wpdb->prefix . 'cmplz_cookiebanners',
                $update_array,
                array('ID' => $this->id)
            );

            if ($updated === 0){
                update_option('cmplz_generate_new_cookiepolicy_snapshot',true);
            }

            //get database value for "default"
            $db_default = $wpdb->get_var($wpdb->prepare("select cdb.default from {$wpdb->prefix}cmplz_cookiebanners as cdb where cdb.ID=%s", $this->id));
            if ($this->default && !$db_default){
                $this->enable_default();
            } elseif(!$this->default && $db_default) {
                $this->remove_default();
            }

        }

        /**
         * santize the css to remove any commented or empty classes
         * @param string $css
         * @return string
         */

        private function sanitize_custom_css($css)
        {
            $css = preg_replace('/\/\*(.|\s)*?\*\//i', '', $css);
            $css = str_replace(array('.cmplz-soft-cookiewall{}','.cc-window .cc-check{}','.cc-category{}','.cc-message{}', '.cc-revoke{}', '.cc-dismiss{}', '.cc-allow{}', '.cc-window{}'), '', $css);
            $css = trim($css);
            return $css;
        }

        /**
         * Delete a cookie variation
         *
         * @since 2.0
         * @return bool $success
         */

        public function delete($force=false)
        {
            if (!current_user_can('manage_options')) return false;

            $error = false;
            global $wpdb;

            //do not delete the last one.
            $count = $wpdb->get_var("select count(*) as count from {$wpdb->prefix}cmplz_cookiebanners");
            if ($count == 1 && !$force) {
                $error = true;
            }

            if (!$error) {
                if ($this->default) $this->remove_default();

                $wpdb->delete($wpdb->prefix . 'cmplz_cookiebanners', array(
                    'ID' => $this->id,
                ));

                //clear all statistics regarding this banner
                $wpdb->delete($wpdb->prefix . 'cmplz_statistics', array(
                    'cookiebanner_id' => $this->id,
                ));
            }

            return !$error;
        }

        /**
         * Archive this cookie banner
         * @return void
         */

        public function archive(){
            if (!current_user_can('manage_options')) return;

            //don't archive the last one
            if (count(cmplz_get_cookiebanners())===1) return;

//            //generate the stats
            $statuses = $this->get_statuses();
            $consenttypes = cmplz_get_used_consenttypes();
            $consenttypes['all'] = "all";

            $stats = array();
            foreach ($consenttypes as $consenttype => $label) {
                foreach ($statuses as $status) {
                    $count = $this->get_count($status, $consenttype);
                    $stats[$consenttype][$status] = $count;
                }
            }
            $this->archived = true;
            $this->statistics = $stats;

            $this->save();

            if ($this->default) $this->remove_default();
        }

        /**
         * Restore this cookiebanner
         * @return void
         */

        public function restore(){
            if (!current_user_can('manage_options')) return;

            $this->archived=false;
            $this->save();
        }

        /**
         * Get all possible statuses for the consent
         * With GTM integration this can be dynamic
         * @since 2.0
         *
         * @param bool $exclude_no_warning if true, the status 'no-warning' will be excluded
         * @return array
         */

        public function get_statuses($exclude_no_warning = false)
        {

            //get all categories
            $statuses = array();
            if (cmplz_get_value('use_country')) $statuses[] = 'no-choice';

            if (!$exclude_no_warning) $statuses[] = 'no-warning';
            $statuses[] = 'functional';

            if (COMPLIANZ()->cookie->tagmamanager_fires_scripts()) {
                if (COMPLIANZ()->cookie->cookie_warning_required_stats()) {
                    $statuses[] = 'stats';
                }

                $cats = cmplz_get_value('tagmanager_categories' . $this->id);
                $categories = explode(',', $cats);
                foreach ($categories as $index => $category) {
                    //if the category is empty (e.g, none were entered), skip it.
                    if (empty($category)) continue;
                    $statuses[] = 'cmplz_event_' . $index;
                }
            }
            $statuses[] = 'all';
            return $statuses;
        }


        /**
         * Check if current banner is the default, and if so move it to another banner.
         */

        public function remove_default(){
            if (current_user_can('manage_options')) {

                global $wpdb;
                //first, set one  of the other banners random to default.
                $cookiebanners = $wpdb->get_results("select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = false and cb.archived=false LIMIT 1");
                if (!empty($cookiebanners)) {
                    $wpdb->update($wpdb->prefix . 'cmplz_cookiebanners',
                        array('default' => true),
                        array('ID' => $cookiebanners[0]->ID)
                    );
                }

                //now set this one to not default and save
                $wpdb->update($wpdb->prefix . 'cmplz_cookiebanners',
                    array('default' => false),
                    array('ID' => $this->id)
                );

            }
        }

        /**
         * Check if current banner is not default, and if so disable the current default
         */

        public function enable_default(){
            if (current_user_can('manage_options')) {

                global $wpdb;
                //first set the current default to false
                $cookiebanners = $wpdb->get_results("select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = true LIMIT 1");
                if (!empty($cookiebanners)) {
                    $wpdb->update($wpdb->prefix . 'cmplz_cookiebanners',
                        array('default' => false),
                        array('ID' => $cookiebanners[0]->ID)
                    );
                }

                //now set this one to default
                $wpdb->update($wpdb->prefix . 'cmplz_cookiebanners',
                    array('default' => true),
                    array('ID' => $this->id)
                );
            }

        }

        /**
         * @param $statistics
         * @return mixed
         */

        public function report_conversion_count($statistics)
        {
            return $statistics['all'];
        }


        /**
         * Get the conversion to marketing for a cookiebanner
         * @return float percentage
         */

        public function conversion_percentage($filter_consenttype)
        {
            if ($this->archived) {
                if (!isset($this->statistics[$filter_consenttype])) return 0;
                $total = 0;
                $all = 0;
                foreach ($this->statistics[$filter_consenttype] as $status => $count) {
                    $total += $count;
                    if ($status === 'all') $all = $count;
                }

                $total = ($total == 0) ? 1 : $total;
                $score = ROUND(100 * ($all / $total));
            } else {
                $statuses = $this->get_statuses(true);

                $total = 0;
                $all = 0;
                foreach ($statuses as $status) {
                    $count = $this->get_count($status, $filter_consenttype);

                    $total += $count;
                    if ($status === 'all') $all = $count;
                }

                $total = ($total == 0) ? 1 : $total;

                $score = ROUND(100 * ($all / $total));
                return $score;
            }
            return $score;
        }

        /**
         * Get the count for this status and consenttype.
         * @param $status
         * @param string $region
         * @param string $variation_id
         * @return int $count
         */

        public function get_count($status, $consenttype)
        {
            global $wpdb;
            $status = sanitize_title($status);
            $consenttype_sql = " AND consenttype='$consenttype'";

            if ($consenttype === 'all') {
                $consenttypes = cmplz_get_used_consenttypes();
                $consenttype_sql = " AND (consenttype='" . implode("' OR consenttype='", $consenttypes) . "')";
            }

            $sql = $wpdb->prepare("SELECT count(*) from {$wpdb->prefix}cmplz_statistics WHERE status = %s " . $consenttype_sql, $status);
            if (COMPLIANZ()->cookie->ab_testing_enabled()) {
                $sql = $wpdb->prepare($sql . " AND cookiebanner_id=%s", $this->id);
            }
            $count = $wpdb->get_var($sql);
            return $count;
        }

        public function report_conversion_total_count($statistics)
        {
            $total = 0;
            foreach ($statistics as $status => $count) {
                $total += $count;
            }

            return $total;

        }

        /**
         * Get array to output to front-end
         *
         * @return array
         */
        public function get_settings_array(){

            $output = array();
            $output['static'] = false;
            $output['set_cookies'] = apply_filters('cmplz_set_cookies_on_consent',array());//cookies to set on acceptance, in order array('cookiename=>array('consent value', 'revoke value');
            $output['banner_version'] = $this->banner_version;
            $output['version'] = cmplz_version;
            $output['a_b_testing'] = cmplz_ab_testing_enabled();
            $output['do_not_track'] = apply_filters('cmplz_dnt_enabled', false);
            $output['consenttype'] = COMPLIANZ()->company->get_default_consenttype();
            $output['geoip'] = cmplz_geoip_enabled();
            $output['categories'] = '';
            $output['position'] = $this->position;
            $output['title'] = $this->title;
            $output['theme'] = $this->theme;
            $output['use_categories'] = $this->use_categories;
            $output['accept'] = $this->accept_x;
            $output['revoke'] = $this->revoke_x;
            $output['dismiss'] = $this->dismiss_x;

            $output['dismiss_on_scroll'] = $this->dismiss_on_scroll;
            $output['dismiss_on_timeout'] = $this->dismiss_on_timeout;
            $output['dismiss_timeout'] = $this->dismiss_timeout;
            $output['popup_background_color'] = $this->popup_background_color;
            $output['popup_text_color'] =  $this->popup_text_color;
            $output['button_background_color'] = $this->button_background_color;
            $output['button_text_color'] = $this->button_text_color;
            $output['border_color'] = $this->border_color;
            $output['use_custom_cookie_css'] = $this->use_custom_cookie_css;
            $output['custom_css'] = $this->sanitize_custom_css($this->custom_css);
            $output['save_preferences'] = $this->save_preferences_x;
            $output['readmore_optin'] = $this->readmore_optin_x;
            $output['accept_informational'] = $this->accept_informational_x;
            $output['message_optout'] = $this->message_optout_x;
            $output['message_optin'] = $this->message_optin_x;
            $output['readmore_optout'] = $this->readmore_optout_x;
            $output['readmore_privacy'] = $this->readmore_privacy_x;
            $output['view_preferences'] = $this->view_preferences_x;

            if ($output['position']=='static') {
                $output['static'] = true;
                $output['position'] = 'top';
            }

            //When theme is edgeless, don't set border color
            if ($output['theme']==='edgeless'){
                $output['border_color'] = false;
            }

            $output['hide_revoke'] = $this->hide_revoke ? 'cc-hidden' : '';
            $output['soft_cookiewall'] = $this->soft_cookiewall;
            $output['type'] = 'opt-in';
            $output['layout'] = 'basic';

            $output['dismiss_on_scroll'] = $output['dismiss_on_scroll'] ? 400 : false;
            $output['dismiss_on_timeout'] = $output['dismiss_on_timeout'] ? 1000 * $output['dismiss_timeout'] : false;

            if ($output['use_categories']) {

                /*
                 *
                 * This is for the category style popups
                 *
                 *
                 * */

                $checkbox_all = '<input type="checkbox" id="cmplz_all" style="display: none;"><label for="cmplz_all" class="cc-check"><svg width="18px" height="18px" viewBox="0 0 18 18"> <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path> <polyline points="1 9 7 14 15 4"></polyline></svg></label>';
                $checkbox_functional = str_replace(array('type', 'cmplz_all'), array('checked disabled type', 'cmplz_functional'), $checkbox_all);
                $output['categories'] = '<label>' . $checkbox_functional . '<span class="cc-category" style="color:'.$this->popup_text_color.'">'.$this->category_functional_x . '</span></label>';

                if (COMPLIANZ()->cookie->tagmamanager_fires_scripts()) {
                    $output['tm_categories'] = true;

                    $categories = explode(',', $this->tagmanager_categories);
                    foreach ($categories as $i => $category) {
                        if (empty($category)) continue;
                        $checkbox_category = str_replace('cmplz_all', 'cmplz_' . $i, $checkbox_all);
                        $output['categories'] .= '<label>' . $checkbox_category . '<span class="cc-category" style="color:'.$this->popup_text_color.'">'.trim($category) . '</span></label>';
                    }
                    $output['categories'] .= '<label>' . $checkbox_all . '<span class="cc-category" style="color:'.$this->popup_text_color.'">'.$this->category_all_x . '</span></label>';
                    $output['cat_num'] = count($categories);
                } else {
                    $output['categories'] .= (COMPLIANZ()->cookie->cookie_warning_required_stats()) ? '<label>' . str_replace('cmplz_all', 'cmplz_stats', $checkbox_all) . '<span class="cc-category" style="color:'.$this->popup_text_color.'">'. $this->category_stats_x . '</span></label>' : '';
                    $output['categories'] .= '<label>' . $checkbox_all . '<span class="cc-category" style="color:'.$this->popup_text_color.'">'. $this->category_all_x . '</span></label>';
                }

                $output['type'] = 'categories';
                $output['layout'] = 'categories-layout';
                $output['revoke'] = $this->view_preferences_x;
            } else {
                $output['accept'] = $this->accept_x;
            }
            $output['cookie_expiry'] = cmplz_get_value('cookie_expiry');
            $output['version'] = cmplz_version;
            $output['readmore_url'] = cmplz_get_cookie_policy_url('eu');
            $output['readmore_url_us'] = cmplz_get_cookie_policy_url('us');
            $privacy_link = COMPLIANZ()->document->get_page_url('privacy-statement','us');
            $output['privacy_link'] = !empty($privacy_link) ? '<span class="cc-divider">&nbsp;-&nbsp;</span><a aria-label="learn more about privacy" tabindex="0" class="cc-link" href="' . $privacy_link . '">' . $output['readmore_privacy'] . '</a>' : '';
            $output['nonce'] = wp_create_nonce('set_cookie');
            $output['url'] = admin_url('admin-ajax.php');
            $output['current_policy_id'] = COMPLIANZ()->cookie->get_active_policy_id();
            return $output;

        }

    }
}

