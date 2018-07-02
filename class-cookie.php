<?php
/* 100% match */
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_cookie")) {
    class cmplz_cookie
    {
        private static $_this;
        public $position;
        public $cookies = array();
        public $known_cookie_keys;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            //hook this function into cron, so it runs once a week, and on init.

            $scan_in_progress = isset($_GET['complianz_scan_token']) && (sanitize_title($_GET['complianz_scan_token']) == get_option('complianz_scan_token'));
            if ($scan_in_progress) {
                add_action('init', array($this, 'maybe_clear_cookies'), 10, 2);
                add_action('wp_print_footer_scripts', array($this, 'test_cookies'), 10, 2);
            } else {
                add_action('admin_init', array($this, 'track_cookie_changes'));
            }

            if ($this->cookie_warning_required()) {
                if (cmplz_get_value('use_country') == 1 && !defined('cmplz_free')) {
                    require_once(cmplz_path . 'pro-class-geoip.php');
                    COMPLIANZ()->geoip = new cmplz_geoip();
                }
                add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
                add_action('wp_print_footer_scripts', array($this, 'inline_cookie_script'), 10, 2);
                add_action('init', array($this, 'set_cookie_policy_id'));
            } else {
                add_action('wp_print_footer_scripts', array($this, 'inline_cookie_script_no_warning'), 10, 2);
            }

            //cookie script for styling purposes on backend
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

            add_action('wp_ajax_store_detected_cookies', array($this, 'store_detected_cookies'));
            add_action('admin_footer', array($this, 'run_cookie_scan'));
            add_action('wp_ajax_load_detected_cookies', array($this, 'load_detected_cookies'));
            add_action('wp_ajax_get_scan_progress', array($this, 'get_scan_progress'));

            add_action('deactivated_plugin', array($this, 'plugin_changes'), 10, 2);
            add_action('activated_plugin', array($this, 'plugin_changes'), 10, 2);
            add_action('upgrader_process_complete', array($this, 'plugins_updating'), 10, 2);

            add_action('plugins_loaded', array($this, 'rescan'), 11, 2);

            add_action('cmplz_notice_statistics_usage', array($this, 'show_statistics_usage_notice'), 10, 1);
            add_action('cmplz_notice_statistical_cookies_usage', array($this, 'show_statistical_cookies_usage_notice'), 10, 1);
            add_action('cmplz_notice_cookie_usage', array($this, 'show_cookie_usage_notice'), 10, 1);
            add_action('cmplz_notice_statistics_script', array($this, 'statistics_script_notice'));

            add_filter('complianz_default_value', array($this, 'set_default'), 10, 2);

            //callback from settings
            add_action('cmplz_cookie_scan', array($this, 'scan_progress'), 10, 1);

            //clear pages list on page changes.
            add_action('cmplz_wizard_wizard', array($this, 'update_social_media_cookies'), 10, 1);
            add_action('delete_post', array($this, 'clear_pages_list'), 10, 1);
            add_action('wp_insert_post', array($this, 'clear_pages_list'), 10, 3);

            $this->load();
        }

        static function this()
        {
            return self::$_this;
        }

        public function clear_pages_list($post_id, $post_after = false, $post_before = false)
        {
            delete_transient('cmplz_pages_list');
        }

        /*
         * Show a notice regarding the statistics usage
         *
         *
         * */

        public function show_statistics_usage_notice($args)
        {
            if ($this->uses_google_analytics() || $this->uses_matomo()) {
                $type = $this->uses_google_analytics() ? __("Google Analytics", 'complianz') : __("Matomo", 'complianz');
                ?>
                <div class="cmplz-notice">
                    <?php
                    printf(__("The cookie scan detected %s cookies on your site, which means the answer to this question should be %s.", 'complianz'), $type, $type);
                    ?>
                </div>
                <?php
            }
        }

        /*
         * Conditionally add extra social media cookies to the used cookeis list
         *
         *
         * */

        public function update_social_media_cookies()
        {

            $social_media = (cmplz_get_value('uses_social_media') === 'yes') ? true : false;
            if ($social_media) {
                $social_media_types = cmplz_get_value('socialmedia_on_site');
                foreach ($social_media_types as $type => $active) {
                    if ($active == 1) {
                        COMPLIANZ()->field->add_multiple_field('used_cookies', $type);
                    }
                }
                $this->add_cookies_to_wizard();
            }
        }

        public function show_statistical_cookies_usage_notice($args)
        {
            if ($this->uses_matomo()) {
                $type = __("Matomo", 'complianz');
            } elseif ($this->uses_google_analytics()) {
                $type = __("Google Analytics", 'complianz');
            } else{
                return;
            }

            ?>
            <div class="cmplz-notice">
                <?php
                printf(__("The cookie scan detected %s cookies on your site, which means the answer to this question should be YES.", 'complianz'), $type);
                ?>
            </div>
            <?php

        }

        public function show_cookie_usage_notice($args)
        {
            $cookie_types = $this->get_detected_cookie_types(true, true);
            if (count($cookie_types) > 0) {
                $count = count($cookie_types);
                $cookie_types = implode(', ', $cookie_types);
                ?>
                <div class="cmplz-notice">
                    <?php
                    printf(__("The cookie scan detected %s types of cookies on your site: %s, which means the answer to this question should be Yes.", 'complianz'), $count, $cookie_types);
                    ?>
                </div>
                <?php
            } else { ?>
                <div class="cmplz-notice">
                    <?php
                    _e("Statistical cookies and PHP session cookie aside, the cookie scan detected no cookies on your site which means the answer to this question can be answered with No.", 'complianz');
                    ?>
                </div>
                <?php
            }
        }

        public function statistics_script_notice(){
            $anonimized = (cmplz_get_value('matomo_anonymized') === 'yes') ? true : false;
            if ($this->uses_matomo()){
                if ($anonimized) {
                    cmplz_notice(__("You use Matomo for statistics on your site, with ip numbers anonymized, so it is not necessary to add the script here.", 'complianz'));
                } else {
                    cmplz_notice(__("You use Matomo for statistics on your site, but ip numbers are not anonymized, so you should your tracking script here", 'complianz'));
                }
            }
        }

        /*
         * Runs when nothing is entered yet
         * */

        public function set_default($value, $fieldname)
        {

            if ($fieldname == 'compile_statistics') {
                if ($this->uses_google_analytics()) {
                    return 'google-analytics';
                }

                if ($this->uses_matomo()) {
                    return 'matomo';
                }
            }

            if ($fieldname == 'uses_cookies') {
                $cookie_types = $this->get_detected_cookie_types(true, true);
                if (count($cookie_types) > 0) {
                    return 'yes';
                } else {
                    return 'no';
                }
            }

            return $value;
        }


        public function rescan()
        {
            if (isset($_POST['rescan'])) {
                if (!isset($_POST['complianz_nonce']) || !wp_verify_nonce($_POST['complianz_nonce'], 'complianz_save')) return;
                delete_option('cmplz_deleted_cookies');
                delete_transient('cmplz_detected_cookies');
                update_option('cmplz_detected_social_media', false);
                update_option('cmplz_processed_pages_list', array());
            }
        }

        /*
         * On activation or deactivation of plugins, we clear the cookie list so it will be scanned anew.
         *
         *
         * */

        public function plugin_changes($plugin, $network_activation)
        {
            //COMPLIANZ()->wizard->reset_wizard_completed();
            update_option('cmplz_plugins_changed', 1);
            delete_transient('cmplz_detected_cookies');
        }


        public function plugins_changed()
        {
            return (get_option('cmplz_plugins_changed') == 1);
        }


        public function plugins_updating($upgrader_object, $options)
        {
            update_option('cmplz_plugins_updated', 1);
        }

        public function plugins_updated()
        {
            return (get_option('cmplz_plugins_updated') == 1);
        }

        public function reset_plugins_updated()
        {
            update_option('cmplz_plugins_updated', -1);
        }

        public function reset_plugins_changed()
        {
            update_option('cmplz_plugins_changed', -1);
        }

        public function load()
        {
            $this->known_cookie_keys = COMPLIANZ()->config->known_cookie_keys;
        }


        public function enqueue_assets($hook)
        {

            wp_register_style('cmplz-cookie', cmplz_url . 'assets/css/cookieconsent.min.css', "", cmplz_version);
            wp_enqueue_style('cmplz-cookie');
            $cookiesettings = $this->get_cookie_settings();
            $cookiesettings['url'] = admin_url('admin-ajax.php');
            $cookiesettings['nonce'] = wp_create_nonce('set_cookie');
            $minified = (defined('WP_DEBUG') && WP_DEBUG) ? '' : '.min';
            wp_enqueue_script('cmplz-cookie', cmplz_url . "assets/js/cookieconsent$minified.js", array(), cmplz_version, true);

            if (!isset($_GET['complianz_scan_token'])) {
                wp_enqueue_script('cmplz-cookie-config', cmplz_url . "assets/js/cookieconfig$minified.js", array(), cmplz_version, true);
                wp_localize_script(
                    'cmplz-cookie',
                    'complianz',
                    $cookiesettings
                );
            }


        }

        public function enqueue_admin_assets($hook)
        {
            if (strpos($hook, 'cmplz-cookie-warning') === FALSE) return;

            wp_register_style('cmplz-cookie', cmplz_url . 'assets/css/cookieconsent.min.css', "", cmplz_version);
            wp_enqueue_style('cmplz-cookie');

            $cookiesettings = $this->get_cookie_settings();
            $cookiesettings['url'] = admin_url('admin-ajax.php');
            $cookiesettings['nonce'] = wp_create_nonce('set_cookie');
            wp_enqueue_script('cmplz-cookie', cmplz_url . "assets/js/cookieconsent.js", array(), cmplz_version, true);
            wp_localize_script(
                'cmplz-cookie',
                'complianz',
                $cookiesettings
            );


            wp_enqueue_script('cmplz-cookie-config-styling', cmplz_url . "assets/js/cookieconfig-styling.js", array(), cmplz_version, true);

        }

        public function get_active_policy_id()
        {
            $policy_id = get_option('complianz_active_policy_id');
            $policy_id = $policy_id ? $policy_id : 1;
            return $policy_id;
        }

        public function upgrade_active_policy_id()
        {
            $policy_id = get_option('complianz_active_policy_id');
            $policy_id = $policy_id ? $policy_id : 1;
            $policy_id++;

            update_option('complianz_active_policy_id', $policy_id);
        }

        /*
         * Make sure we only have the front-end settings for the output
         *
         * */

        public function get_cookie_settings()
        {
            $output = array();

            $fields = COMPLIANZ()->config->fields('cookie_settings');
            foreach ($fields as $fieldname => $field) {
                $value = cmplz_get_value($fieldname);
                if (empty($value)) $value = $field['default'];
                $output[$fieldname] = $value;
            }

            $output['readmore_url'] = $this->get_cookie_statement_page();

            return $output;

        }

        public function get_cookie_statement_page()
        {
            $url = "#";
            $page_id = COMPLIANZ()->document->get_shortcode_page_id('cookie-statement');
            if ($page_id) {
                $url = get_permalink($page_id);
            }
            return $url;
        }

        private function domain()
        {
            $url = site_url();
            $parse = parse_url($url);
            return $parse['host'];
        }

        /*
         * If current cookie policy has changed, reset cookie consent
         *
         * */


        public function set_cookie_policy_id()
        {
            $detected_policy_id = isset($_COOKIE['complianz_policy_id']) ? $_COOKIE['complianz_policy_id'] : false;
            if ($detected_policy_id && ($this->get_active_policy_id() != $detected_policy_id)) {
                setcookie("complianz_consent_status", "allow", time() - 3600, '/');
            }

            if (isset($_COOKIE['complianz_consent_status']) && ($_COOKIE['complianz_consent_status'] == 'allow')) {
                setcookie('complianz_policy_id', $this->get_active_policy_id(), time() + (DAY_IN_SECONDS * 30), '/');
            }
        }

        public function inline_cookie_script()
        {
            ?>
            <script class="cmplz-native">
                function complianz_enable_cookies() {
                    console.log("enabling cookies");
                    <?php if ($this->cookie_warning_required_stats()) $this->get_statistics_script();?>
                    <?php $this->get_cookie_script();?>
                }

                function complianz_disable_cookies() {
                    console.log('disabling cookies');
                    deleteAllCookies();
                }
                <?php
                //if no cookie warning is needed for the stats specifically, we can move this out of the warning code.
                if (!$this->cookie_warning_required_stats()) $this->get_statistics_script();?>
            </script>
            <?php
        }

        public function inline_cookie_script_no_warning()
        {
            ?>
            <script>
                <?php $this->get_statistics_script();?>
                <?php $this->get_cookie_script();?>
            </script>
            <?php
        }

        private function get_statistics_script()
        {
            $statistics = cmplz_get_value('compile_statistics');
            if ($statistics === 'google-tag-manager') {
                $GTM = cmplz_get_value("GTM_code");
                ?>
                (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','<?php echo $GTM ?>');
                <?php
            } elseif ($statistics === 'google-analytics') {
                $UA_code = cmplz_get_value("UA_code");
                ?>
                (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                ga('create', '<?php echo $UA_code ?>', 'auto');
                ga('send', 'pageview', {
                'anonymizeIp': true
                });
                <?php
            } else {
                echo cmplz_get_value('statistics_script');
            }

        }

        private function get_cookie_script()
        {
            echo cmplz_get_value('cookie_scripts');
        }


        public function maybe_clear_cookies()
        {
            if ($this->scan_complete()) return;
            $id = sanitize_title($_GET['complianz_id']);
            //the first run should clean up the cookies.
            if ($id === 'clean') {
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach ($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        if (strpos($name, 'complianz') === FALSE && strpos($name, 'wordpress') === FALSE && strpos($name, 'wp-') === FALSE) {

                            setcookie($name, '', time() - 1000);
                            setcookie($name, '', time() - 1000, '/');
                        }

                    }
                }
            }
        }

        /*
         * Get all cookies, and post back to site with ajax.
         * This script is only inserted when a valid token is passed.
         *
         * */


        public function test_cookies()
        {
            if ($this->scan_complete()) return;
            $token = sanitize_title($_GET['complianz_scan_token']);
            $id = sanitize_title($_GET['complianz_id']);
            //https://stackoverflow.com/questions/4919918/get-all-cookies-of-my-site

            ?>

            <script>
                <?php
                //force enable cookies to make sure the tool gets all of them.
                //as this script is only inserted when loaded by the scan, this does no harm.
                ?>

                jQuery(document).ready(function ($) {
                    <?php if ($id === 'clean') {?>
                    //deleteAllCookies();
                    var cookies = [];
                    <?php } ?>

                    if (cmplz_function_exists('complianz_enable_cookies')) complianz_enable_cookies();
                    var cookies = get_cookies_array();
                    $.post(
                        '<?php echo admin_url('admin-ajax.php')?>',
                        {
                            action: 'store_detected_cookies',
                            cookies: cookies,
                            token: '<?php echo $token;?>',
                            complianz_id: '<?php echo $id?>',
                        },
                    );


                    function get_cookies_array() {
                        var cookies = {};
                        if (document.cookie && document.cookie != '') {
                            var split = document.cookie.split(';');
                            for (var i = 0; i < split.length; i++) {
                                var name_value = split[i].split("=");
                                name_value[0] = name_value[0].replace(/^ /, '');
                                cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
                            }
                        }

                        return cookies;

                    }
                });

                function cmplz_function_exists(function_name) {
                    if (typeof function_name == 'string') {
                        return (typeof window[function_name] == 'function');
                    } else {
                        return (function_name instanceof Function);
                    }
                }

                function deleteAllCookies() {
                    document.cookie.split(";").forEach(function (c) {
                        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                    });
                }
            </script>

            <?php
        }

        public function track_cookie_changes()
        {
            $cookie_changes = false;
            //only run if all pages are scanned.
            if (!$this->scan_complete()) return;

            $cookies = get_transient('cmplz_detected_cookies');

            if (!$cookies) return;

            //check if anything was changed
            $cookies_from_last_complete_scan = get_option('cmplz_detected_cookies');


            $changed_count = count(array_diff($cookies, $cookies_from_last_complete_scan));
            if ($changed_count > 0) {
                $cookie_changes = true;
            }

            //store permanently to track changes
            update_option('cmplz_detected_cookies', $cookies);

            //store the date
            update_option('cmplz_last_cookie_scan', time());

            if ($cookie_changes) {
                update_option('cmplz_cookies_times_changed', 0);
                $this->set_cookies_changed();
                $this->add_cookies_to_wizard();
            }

        }


        /*
         * Insert an iframe to retrieve front-end cookies
         *
         *
         * */

        public function run_cookie_scan()
        {
            //if the cookie list cache is cleared, empty the processed page list so the scan starts again.
            if (!get_transient('cmplz_detected_cookies')) {
                update_option('cmplz_processed_pages_list', array());
            }

            if (!$this->scan_complete()) {
                $url = $this->get_next_page_url();
                if (!$url) return;
                //first, get the html of this page.
                //but we can skip if it's the "clean" page.
                if (strpos($url, 'complianz_id') !== FALSE || substr($url, strpos($url, 'complianz_id') + 13, 5) !== 'clean') {
                    $response = wp_remote_get($url);
                    if (!is_wp_error($response)) {
                        $html = $response['body'];
                        $stored_social_media = cmplz_scan_detected_social_media();
                        if (!$stored_social_media) $stored_social_media = array();
                        $social_media = $this->parse_for_social_media($html);
                        $social_media = array_unique(array_merge($stored_social_media, $social_media), SORT_REGULAR);
                        update_option('cmplz_detected_social_media', $social_media);
                    }
                }

                //load in iframe so the scripts run.
                echo '<iframe id="cmplz_cookie_scan_frame" class="hidden" src="' . $url . '"></iframe>';

            }
        }

        /*
         * Check the webpage html output for social media markers.
         *
         *
         *
         * */

        private function parse_for_social_media($html)
        {
            $social_media = array();
            $social_media_markers = COMPLIANZ()->config->social_media_markers;
            foreach ($social_media_markers as $key => $markers) {
                foreach ($markers as $marker) {
                    if (strpos($html, $marker) !== FALSE && !in_array($key, $social_media)) {
                        $social_media[] = $key;
                    }
                }
            }

            return $social_media;
        }


        private function get_next_page_url()
        {
            $token = time();
            update_option('complianz_scan_token', $token);
            $pages = $this->pages_to_process();

            if (count($pages) == 0) return false;

            $id_to_process = reset($pages);
            $this->set_page_as_processed($id_to_process);
            $url = (($id_to_process === 'home') || ($id_to_process === 'clean')) ? site_url() : get_permalink($id_to_process);
            $url = add_query_arg(array("complianz_scan_token" => $token, 'complianz_id' => $id_to_process), $url);
            if (is_ssl()) $url = str_replace("http://", "https://", $url);
            return $url;
        }


        /*
         *
         * Get list of page id's
         *
         * */

        public function get_pages_list()
        {
            $pages = get_transient('cmplz_pages_list');
            if (!$pages) {
                $post_types = 'page';
                $args = array(
                    'post_type' => $post_types,
                );

                $posts = get_posts($args);

                //first page to clean up cookies
                $pages[] = 'clean';
                $pages = array();
                $wp_pages = (!empty($posts)) ? wp_list_pluck($posts, 'ID') : array();
                $pages = array_merge($pages, $wp_pages);
                $pages[] = 'home';
                set_transient('cmplz_pages_list', $pages, DAY_IN_SECONDS);
            }
            return $pages;
        }

        public function get_processed_pages_list()
        {

            $pages = get_option('cmplz_processed_pages_list');
            if (!is_array($pages)) $pages = array();

            return $pages;
        }


        public function scan_complete()
        {
            $pages = $this->pages_to_process();

            if (count($pages) == 0) return true;

            return false;
        }

        private function pages_to_process()
        {
            $pages_list = COMPLIANZ()->config->get_pages_list();

            $processed_pages_list = $this->get_processed_pages_list();

            $pages = array_diff($pages_list, $processed_pages_list);
            return $pages;
        }

        public function set_page_as_processed($id)
        {

            if ($id !== 'home' && $id !== 'clean' && !is_numeric($id)) {
                return;
            }

            $pages = $this->get_processed_pages_list();
            if (!in_array($id, $pages)) {
                $pages[] = $id;
                update_option('cmplz_processed_pages_list', $pages);
            }
        }

        public function get_detected_cookies()
        {
            $cookies = get_option('cmplz_detected_cookies');

            if (!is_array($cookies)) $cookies = array($cookies);

            //filter out ignored list
            $ignore_cookies = COMPLIANZ()->config->ignore_cookie_list;

            foreach ($cookies as $cookie_name => $cookie) {
                foreach ($ignore_cookies as $ignore_cookie) {
                    if (strpos($cookie_name, $ignore_cookie) !== false) {
                        unset($cookies[$cookie_name]);
                    }
                }
            }
            return $cookies;
        }


        /*
         * This function gets the cookies by types, so we only get one type per set of cookies.
         *
         * */

        public function get_detected_cookie_types($count_statistics = false, $count_php_session = false)
        {
            $types = array();
            $cookies = $this->get_detected_cookies();
            if (!$count_statistics) {
                foreach ($cookies as $cookie_name => $label) {
                    if (($this->get_cookie_id($cookie_name) == 'google-analytics') || ($this->get_cookie_id($cookie_name) == 'matomo')) {
                        unset($cookies[$cookie_name]);
                    }
                }
            }

            if (!$count_php_session) {
                foreach ($cookies as $cookie_name => $label) {
                    if (($this->get_cookie_id($cookie_name) == 'php-session')) {
                        unset($cookies[$cookie_name]);
                    }
                }
            }

            //keep track of labels we already have
            $tracked_labels = array();
            //for each cookie, get the key
            foreach ($cookies as $key => $label) {
                if (in_array($label, $tracked_labels)) continue;
                $id = $this->get_cookie_id($key);
                if (!empty($id)) {
                    $types[$id] = $label;
                } else {
                    $types[$key] = $label;
                }
                $tracked_labels[] = $label;
            }

            return $types;
        }

        public function store_detected_cookies()
        {
            if (isset($_POST['token']) && (sanitize_title($_POST['token']) == get_option('complianz_scan_token'))) {

                $found_cookies = array_map(function ($el) {
                    return sanitize_title($el);
                }, $_POST['cookies']);
                $found_cookies = array_merge($found_cookies, $_COOKIE);
                $found_cookies = array_map('sanitize_text_field', $found_cookies);
                $cookies = array();

                foreach ($found_cookies as $key => $value) {
                    $cookies[$key] = $this->get_cookie_description($key);
                }

                if (!is_array($cookies)) $cookies = array($cookies);
                set_transient('cmplz_detected_cookies', $cookies, WEEK_IN_SECONDS);

                //we only store this at this point if there's nothing at all yet.
                //this way, when the scan has just started, we already have some cookies in the list.
                if (!get_option('cmplz_detected_cookies')) {
                    update_option('cmplz_detected_cookies', $cookies);
                }

                $this->add_cookies_to_wizard();

                //clear token
                update_option('complianz_scan_token', false);

                //store current requested page

                $this->set_page_as_processed($_POST['complianz_id']);

            }
        }

        public function get_last_cookie_scan_date()
        {
            if (get_option('cmplz_last_cookie_scan')) {
                $date = date(get_option('date_format'), get_option('cmplz_last_cookie_scan'));
                $date = cmplz_localize_date($date);
                $time = date(get_option('time_format'), get_option('cmplz_last_cookie_scan'));
                $date = sprintf(__("%s at %s", 'complianz'), $date, $time);
            } else {
                $date = false;
            }
            return $date;
        }


        public function set_cookies_changed()
        {
            //COMPLIANZ()->wizard->reset_wizard_completed();
            update_option('cmplz_changed_cookies', 1);

        }

        public function cookies_changed()
        {
            return (get_option('cmplz_changed_cookies') == 1);
        }

        public function reset_cookies_changed()
        {
            return update_option('cmplz_changed_cookies', -1);
        }

        /*
         * Get a label/description based on a list of known cookie keys.
         *
         *
         * */

        public function get_cookie_description($cookie_name)
        {
            $label = __("Origin unknown", 'complianz');
            foreach ($this->known_cookie_keys as $id => $cookie) {
                $used_cookie_names = $cookie['unique_used_names'];
                foreach ($used_cookie_names as $used_cookie_name) {
                    if (strpos($used_cookie_name, 'partial_') !== false) {
                        //a partial match is enough on this type
                        $partial_cookie_name = str_replace('partial_', '', $used_cookie_name);
                        if (strpos($cookie_name, $partial_cookie_name) !== FALSE) {
                            return $cookie['label'];
                        }
                    } elseif ($cookie_name == $used_cookie_name)
                        return $cookie['label'];
                }

            }

            return $label;
        }


        public function get_cookie_id($cookie_name)
        {
            foreach ($this->known_cookie_keys as $id => $cookie) {
                $used_cookie_names = $cookie['unique_used_names'];
                foreach ($used_cookie_names as $used_cookie_name) {
                    if ($cookie_name === $used_cookie_name) {
                        return $id;
                    }
                    if (strpos($used_cookie_name, 'partial_') !== false) {
                        //a partial match is enough on this type
                        $partial_cookie_name = str_replace('partial_', '', $used_cookie_name);
                        if (strpos($cookie_name, $partial_cookie_name) !== FALSE) {
                            return $id;
                        }
                    }
                }
            }

            return false;
        }

        public function load_detected_cookies()
        {
            $error = false;
            $cookies = '';

            if (!is_user_logged_in()) {
                $error = true;
            }

            if (!$error) {
                $cookies = $this->get_detected_cookies();
                if (!$cookies) {
                    if ($this->scan_complete()) {
                        $html = __("No cookies detected", 'complianz');
                    } else {
                        $html = __("Cookie scan in progress", 'complianz');
                    }
                } else {
                    $html = '';
                    foreach ($cookies as $key => $value) {
                        $html .= '<tr><td>' . $key . "</td><td>" . $value . "</td></tr>";
                    }
                    $html = '<table>' . $html . "</table>";
                }
            }

            $out = array(
                'success' => !$error,
                'cookies' => $html,
            );

            die(json_encode($out));
        }

        public function get_progress_count()
        {
            $done = $this->get_processed_pages_list();
            $total = COMPLIANZ()->config->get_pages_list();
            $progress = 100 * (count($done) / count($total));
            return $progress;
        }

        public function get_scan_progress()
        {
            $next_url = $this->get_next_page_url();

            $output = array(
                "progress" => $this->get_progress_count(),
                "next_page" => $next_url,
            );
            $obj = new stdClass();
            $obj = $output;
            echo json_encode($obj);
            wp_die();
        }


        public function scan_progress()
        {
            ?>
            <div class="field-group">
                <div class="cmplz-label">
                    <label for="scan_progress"><?php _e("Cookie scan", 'complianz') ?></label>
                </div>
                <div id="cmplz-scan-progress">
                    <div class="cmplz-progress-bar"></div>
                </div>
                <?php _e("Cookies as detected by the automatic cookie scan. Please note that only cookies set on your own domain are detected by this scan.", 'complianz') ?>
                <div class="detected-cookies">
                    <?php
                    $cookies = $this->get_detected_cookies();
                    if ($cookies) {
                        $html = '';
                        foreach ($cookies as $key => $value) {
                            $html .= '<tr><td>' . $key . "</td><td>" . $value . "</td></tr>";
                        }
                        echo "<table>" . $html . "</table>";
                    }
                    ?>
                </div>
                <input type="submit" class="button cmplz-rescan"
                       value="<?php _e('Re-scan', 'complianz') ?>" name="rescan">
            </div>

            <?php
        }


        /*
         * Check if site uses Google Analytics
         *
         *
         * */


        public function uses_google_analytics()
        {
            if ($this->site_uses_cookie_of_type('google-analytics')) return true;

            return false;
        }

        public function uses_matomo()
        {
            if ($this->site_uses_cookie_of_type('matomo')) return true;

            return false;
        }


        public function analytics_configured()
        {
            $UA_code = COMPLIANZ()->field->get_value('UA_code');
            if (!empty($UA_code)) return true;

            return false;
        }

        /*
         *      Warning is required when
         *          - cookies are used, or
         *          - stats are used, and data are shared, and ip not anonymous
         *
         * */

        public function cookie_warning_required()
        {
            /*
             * If Do not track is enabled, the stats do not require a cookie warning, and the user has not added
             * any cookie enabling scripts, the warning is not needed, as the thirdparty cookies
             * will be disabled by default, without option to enable them.
             *
             * */
            $scripts = cmplz_get_value('cookie_scripts');
            $cookie_enabling_scripts = empty($scripts) ? false : true;
            if (!is_admin() && cmplz_dnt_enabled() && !$this->cookie_warning_required_stats() && !$cookie_enabling_scripts) {
                return false;
            }

            //non functional cookies? we need a cookie warning
            if ($this->third_party_cookies_active()) {
                return true;
            }

            //non functional cookies? we need a cookie warning
            $uses_non_functional_cookies = $this->uses_non_functional_cookies();
            if ($uses_non_functional_cookies) {
                return true;
            }


            //does the config of the statistics require a cookie warning?
            if ($this->cookie_warning_required_stats()) {
                return true;
            }

            return false;
        }

        public function third_party_cookies_active()
        {
            $thirdparty_scripts = cmplz_get_value('thirdparty_scripts');
            $thirdparty_iframes = cmplz_get_value('thirdparty_iframes');
            $thirdparty_scripts = empty($thirdparty_scripts) ? false : true;
            $thirdparty_iframes = empty($thirdparty_iframes) ? false : true;
            $ad_cookies = (cmplz_get_value('uses_ad_cookies') === 'yes') ? true : false;
            $social_media = (cmplz_get_value('uses_social_media') === 'yes') ? true : false;

            if ($thirdparty_scripts || $thirdparty_iframes || $ad_cookies || $social_media) {
                return true;
            }

            return false;
        }

        public function cookie_warning_required_stats()
        {
            $statistics = cmplz_get_value('compile_statistics');
            $tagmanager = ($statistics === 'google-tag-manager') ? true : false;
            $matomo = ($statistics === 'matomo') ? true : false;
            $google_analytics = ($statistics === 'google-analytics') ? true : false;

            if ($google_analytics || $tagmanager) {
                $thirdparty = $google_analytics ? cmplz_get_value('compile_statistics_more_info') : cmplz_get_value('compile_statistics_more_info_tag_manager');
                $accepted_google_data_processing_agreement = (isset($thirdparty['accepted']) && ($thirdparty['accepted'] == 1)) ? true : false;
                $ip_anonymous = (isset($thirdparty['ip-addresses-blocked']) && ($thirdparty['ip-addresses-blocked'] == 1)) ? true : false;
                $no_sharing = (isset($thirdparty['no-sharing']) && ($thirdparty['no-sharing'] == 1)) ? true : false;
            }

            //not anonymous stats.
            if ($statistics === 'yes') {
                return true;
            }

            if (($tagmanager || $google_analytics) &&
                (!$accepted_google_data_processing_agreement || !$ip_anonymous || !$no_sharing)
            ) {
                return true;
            }


            if ($matomo &&  (cmplz_get_value('matomo_anonymized') !== 'yes')) return true;

            return false;
        }


        public function uses_non_functional_cookies()
        {
            //get all used cookies
            $used_cookies = cmplz_get_value('used_cookies');
            if (empty($used_cookies) || !is_array($used_cookies)) return false;
            foreach ($used_cookies as $cookie) {
                if (!isset($cookie['functional'])) continue;
                if ($cookie['functional'] !== 'on') {
                    return true;
                }
            }
            return false;

            //count cookies that are not functional
        }


//        /*
//         * Check if the scan has detected the usage of cookies
//         * stats and php session are not counted
//         *
//         * */
//
//        public
//        function uses_cookies()
//        {
//            $cookie_types = $this->get_detected_cookie_types(false, false);
//
//            if (count($cookie_types) > 0) return true;
//
//            return false;
//        }

        public function site_uses_cookie_of_type($type)
        {
            $cookies = $this->get_detected_cookies();
            if (!empty($cookies)) {
                foreach ($cookies as $cookie_name => $label) {
                    //get identifier for this cookie name
                    $id = $this->get_cookie_id($cookie_name);

                    if ($type == $id) return true;

                }
            }

            return false;
        }

        /*
         * $type = title, used_names, description, storage_duration, purpose
         *
         *
         *
         * the index runs from 0- the number of cookies.
         * so starting from 0, we get the first cookie in the detected cookie types list, and prefill the value
         * */
        public function get_default_value($type, $key)
        {
            if ($type === 'show') return true;

            $cookie_type = $key;
            if ($type == 'key') return $cookie_type;
            $value = isset($this->known_cookie_keys[$cookie_type][$type]) ? $this->known_cookie_keys[$cookie_type][$type] : '';

            //we set all the registered cookies as used cookies, so below is commented out
//            if ($type == 'used_names' && !empty($value)) {
//                $detected_cookies = array_keys($this->get_detected_cookies());
//                $value = array_intersect($value, $detected_cookies);
//            }
            if (is_array($value)) $value = implode(', ', $value);

            return $value;

        }

        //add dynamic cookie fields to wizard settings, but not if the key is already present in these settings.
        public function add_cookies_to_wizard()
        {
            //get cookie values in wizard
            $wizard_cookies = COMPLIANZ()->field->get_value('used_cookies');
            //get cookies from scan
            $scanned_cookies = $this->get_detected_cookie_types(true, true);

            foreach ($scanned_cookies as $cookie_type => $label) {
                //add to the settings if it's not already in there:
                $key_arr = array();

                if (!empty($wizard_cookies)) {
                    $key_arr = wp_list_pluck(array_filter($wizard_cookies, function ($value) {
                        return $value !== '';
                    }), 'key');
                }


                if (is_array($key_arr) && !in_array($cookie_type, $key_arr)) {
                    //add fieldc if not deleted previously

                    $deleted_cookies = get_option('cmplz_deleted_cookies');

                    if ((!$deleted_cookies || !in_array($cookie_type, $deleted_cookies)) && strpos($cookie_type, 'wordpress_') === false) {
                        COMPLIANZ()->field->add_multiple_field('used_cookies', $cookie_type);
                    }
                }
            }

        }


    }
} //class closure
