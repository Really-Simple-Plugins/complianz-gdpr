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
        public $user_cookie_variation;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;

            $scan_in_progress = isset($_GET['complianz_scan_token']) && (sanitize_title($_GET['complianz_scan_token']) == get_option('complianz_scan_token'));
            if ($scan_in_progress) {
                //add_action('init', array($this, 'maybe_clear_cookies'), 10, 2);
                add_action('wp_print_footer_scripts', array($this, 'test_cookies'), 10, 2);
            } else {
                add_action('admin_init', array($this, 'track_cookie_changes'));
            }

            if (!is_admin() && get_option('cmplz_wizard_completed_once')) {
                if ($this->site_needs_cookie_warning()) {
                    add_action('wp_print_footer_scripts', array($this, 'inline_cookie_script'), 9999);
                    add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 99999);
                } else {
                    add_action('wp_print_footer_scripts', array($this, 'inline_cookie_script_no_warning'), 10, 2);
                }
            }


//            //cookie script for styling purposes on backend
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
            add_action('admin_footer', array($this, 'run_cookie_scan'));
            add_action('wp_ajax_load_detected_cookies', array($this, 'load_detected_cookies'));
            add_action('wp_ajax_cmplz_get_scan_progress', array($this, 'get_scan_progress'));

            add_action('wp_ajax_store_detected_cookies', array($this, 'store_detected_cookies'));
            add_action('wp_ajax_cmplz_report_unknown_cookies', array($this, 'ajax_report_unknown_cookies'));
            add_action('wp_ajax_cmplz_delete_snapshot', array($this, 'ajax_delete_snapshot'));
            add_action('admin_init', array($this, 'force_snapshot_generation'));

            add_action('deactivated_plugin', array($this, 'plugin_changes'), 10, 2);
            add_action('activated_plugin', array($this, 'plugin_changes'), 10, 2);

            add_action('plugins_loaded', array($this, 'rescan'), 11, 2);

            add_action('cmplz_notice_compile_statistics', array($this, 'show_compile_statistics_notice'), 10, 1);
            add_action('cmplz_notice_statistical_cookies_usage', array($this, 'show_statistical_cookies_usage_notice'), 10, 1);
            add_action('cmplz_notice_statistics_script', array($this, 'statistics_script_notice'));

            add_filter('cmplz_default_value', array($this, 'set_default'), 10, 2);

            //callback from settings
            add_action('cmplz_cookie_scan', array($this, 'scan_progress'), 10, 1);
            add_action('cmplz_report_unknown_cookies', array($this, 'report_unknown_cookies_callback'));

            //clear pages list on page changes.
            add_action('cmplz_wizard_wizard', array($this, 'update_social_media_cookies'), 10, 1);
            //add_action('delete_post', array($this, 'clear_pages_list'), 10, 1);
            //add_action('wp_insert_post', array($this, 'clear_pages_list'), 10, 3);

            add_action('cmplz_statistics_script', array($this, 'get_statistics_script'),10);

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

        public function show_compile_statistics_notice($args)
        {
            if ($this->site_uses_cookie_of_type('google-analytics') || $this->site_uses_cookie_of_type('matomo')) {

                $type = $this->site_uses_cookie_of_type('google-analytics') ? __("Google Analytics or Tag Manager", 'complianz-gdpr') : __("Matomo", 'complianz-gdpr');

                    cmplz_notice(sprintf(__("The cookie scan detected %s cookies on your site, which means the answer to this question should be %s.", 'complianz-gdpr'), $type, $type));
            }
        }

        /**
         * Forces generation of a snapshot for today, triggered by the button
         *
         */

        public function force_snapshot_generation(){
            if (!cmplz_user_can_manage()) return;

            if (isset($_POST["cmplz_generate_snapshot"]) && isset($_POST["cmplz_nonce"]) && wp_verify_nonce($_POST['cmplz_nonce'],'cmplz_generate_snapshot')){
                COMPLIANZ()->document->generate_cookie_policy_snapshot($force=true);
            }
        }

        public function ajax_delete_snapshot(){

            if (!cmplz_user_can_manage()) return;

            if (isset($_POST['snapshot_id'])) {
                $uploads = wp_upload_dir();
                $upload_dir = $uploads['basedir'];
                $path = $upload_dir . '/complianz/snapshots/';
                $success = unlink($path.sanitize_text_field($_POST['snapshot_id']));
                $response = json_encode(array(
                    'success' => true,
                ));
                header("Content-Type: application/json");
                echo $response;
                exit;
            }
        }

        public function cookie_statement_snapshots(){

            include(dirname(__FILE__) . '/class-cookiestatement-snapshot-table.php');

            $customers_table = new cmplz_CookieStatement_Snapshots_Table();
            $customers_table->prepare_items();

            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $(document).on('click', '.cmplz-delete-snapshot', function (e) {

                        e.preventDefault();
                        var btn = $(this);
                        btn.closest('tr').css('background-color', 'red');
                        var delete_snapshot_id = btn.data('id');
                        $.ajax({
                            type: "POST",
                            url: '<?php echo admin_url('admin-ajax.php')?>',
                            dataType: 'json',
                            data: ({
                                action: 'cmplz_delete_snapshot',
                                snapshot_id: delete_snapshot_id
                            }),
                            success: function (response) {
                                if (response.success) {
                                    btn.closest('tr').remove();
                                }
                            }
                        });

                    });
                });
            </script>

            <div id="cookie-policy-snapshots" class="wrap cookie-snapshot">
                <h1><?php _e("Proof of consent", 'complianz-gdpr') ?></h1>
                <p>
                    <?php
                    $link_open = '<a href="https://complianz.io/user-consent-registration/" target="_blank">';
                    cmplz_notice(sprintf(__('When you make significant changes to your cookie policy, cookie banner or revoke functionality, we will add a time-stamped document under "Proof of Consent" with the latest changes. If there is any concern if your website was ready for GDPR at a point of time, you can use the Complianz Proof of Consent to show the efforts you made being compliant, while respecting data minimization and full control of consent registration by the user. On a daily basis, the document will be generated if the plugin has detected significant changes. For more information read our article about %suser consent registration%s.', 'complianz-gdpr'), $link_open, '</a>')) ?>
                </p>
                <?php
                if (isset($_POST['cmplz_generate_snapshot'])){
                    cmplz_notice(__("Proof of consent updated!", "complianz-gdpr"), 'success', true);
                }
                ?>

                <form id="cmplz-cookiestatement-snapshot-generate" method="POST" action="">
                    <?php echo wp_nonce_field('cmplz_generate_snapshot','cmplz_nonce');?>
                    <input type="submit" class="button button-primary" name="cmplz_generate_snapshot" value="<?php _e("Generate now","complianz-gdpr")?>"/>
                </form>
                <form id="cmplz-cookiestatement-snapshot-filter" method="get"
                      action="">

                    <?php
                    $customers_table->search_box(__('Filter', 'complianz-gdpr'), 'cmplz-cookiesnapshot');
                    $customers_table->display();
                    ?>
                    <input type="hidden" name="page" value="cmplz-proof-of-consent"/>

                </form>
                <?php  do_action('cmplz_after_cookiesnapshot_list'); ?>
            </div>

            <?php
        }


        /**
         * Conditionally add extra social media cookies to the used cookies list
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

            $thirdparty = (cmplz_get_value('uses_thirdparty_services') === 'yes') ? true : false;
            if ($thirdparty) {
                $thirdparty_types = cmplz_get_value('thirdparty_services_on_site');
                foreach ($thirdparty_types as $type => $active) {
                    if ($active == 1) {
                        COMPLIANZ()->field->add_multiple_field('used_cookies', $type);
                    }
                }
                $this->add_cookies_to_wizard();
            }
        }

        public function show_statistical_cookies_usage_notice($args)
        {
            if ($this->site_uses_cookie_of_type('matomo')) {
                $type = __("Matomo", 'complianz-gdpr');
            } elseif ($this->site_uses_cookie_of_type('google-analytics') ) {
                $type = __("Google Analytics", 'complianz-gdpr');
            } else {
                return;
            }

            cmplz_notice(sprintf(__("The cookie scan detected %s cookies on your site, which means the answer to this question should be YES.", 'complianz-gdpr'), $type));

        }

        public function statistics_script_notice()
        {
            $anonimized = (cmplz_get_value('matomo_anonymized') === 'yes') ? true : false;
            if ($this->uses_matomo()) {
                if ($anonimized) {
                    cmplz_notice(__("You use Matomo for statistics on your site, with ip numbers anonymized, so it is not necessary to add the script here.", 'complianz-gdpr'));
                } else {
                    cmplz_notice(__("You use Matomo for statistics on your site, but ip numbers are not anonymized, so you should your tracking script here", 'complianz-gdpr'));
                }
            }
        }

        /*
         * Runs when nothing is entered yet
         * */

        public function set_default($value, $fieldname)
        {

            if ($fieldname == 'compile_statistics') {
                if ($this->site_uses_cookie_of_type('google-analytics')) {
                    return 'google-analytics';
                }

                if ($this->site_uses_cookie_of_type('matomo')) {
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
                //delete_option('cmplz_deleted_cookies');

                delete_transient('cmplz_detected_cookies');
                update_option('cmplz_detected_social_media', false);
                update_option('cmplz_detected_thirdparty_services', false);
                $this->reset_pages_list();

            }
        }

        /**
         * On activation or deactivation of plugins, we clear the cookie list so it will be scanned anew.
         *
         *
         * */

        public function plugin_changes($plugin, $network_activation)
        {
            update_option('cmplz_plugins_changed', 1);

            //we don't delete this transient, but just reschedule it. Otherwise the scan would start right away, which might cause a memory overload.
            $detected_cookies = get_transient('cmplz_detected_cookies');
            set_transient('cmplz_detected_cookies', $detected_cookies, HOUR_IN_SECONDS);
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
            $minified = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_style('cmplz-cookie', cmplz_url . "core/assets/css/cookieconsent$minified.css", "", cmplz_version);
            wp_enqueue_style('cmplz-cookie');

            $cookiesettings = $this->get_cookiebanner_settings(apply_filters('cmplz_user_banner_id', cmplz_get_default_banner_id()));

            $cookiesettings['placeholdertext'] = cmplz_get_value('blocked_content_text');

            wp_enqueue_script('cmplz-cookie', cmplz_url . "core/assets/js/cookieconsent$minified.js", array('jquery'), cmplz_version, true);

            if (!isset($_GET['complianz_scan_token'])) {
                $deps = array('jquery');
                if (cmplz_has_async_documentwrite_scripts()) {
                    $deps[] = 'cmplz-postscribe';
                    wp_enqueue_script('cmplz-postscribe', cmplz_url . "core/assets/js/postscribe.min.js", array('jquery'), cmplz_version, true);
                }
                wp_enqueue_script('cmplz-cookie-config', cmplz_url . "core/assets/js/cookieconfig$minified.js", $deps, cmplz_version, true);
                wp_localize_script(
                    'cmplz-cookie-config',
                    'complianz',
                    $cookiesettings
                );
            }
        }

        /**
         * Check if AB testing is enabled
         * @return bool $enabled
         */

        public function ab_testing_enabled()
        {
            return apply_filters('cmplz_ab_testing_enabled', false);
        }

        /**
         * Here we add scripts and styles for the wysywig editor on the backend
         *
         * */

        public function enqueue_admin_assets($hook)
        {
            //script to check for ad blockers
            if (isset($_GET['page']) && $_GET['page']=='cmplz-wizard') {
                wp_enqueue_script('cmplz-ad-checker', cmplz_url . "core/assets/js/ads.js", array('jquery', 'cmplz-admin'), cmplz_version, true);
            }


        }

        public function get_active_policy_id()
        {
            $policy_id = get_option('complianz_active_policy_id');
            $policy_id = $policy_id ? $policy_id : 1;
            return $policy_id;
        }

        /**
         * Upgrade the activate policy id with one
         * The active policy id is used to track if the user has consented to the latest policy changes.
         * If changes were made, the policy is increased, and user should consent again.
         */

        public function upgrade_active_policy_id()
        {
            $policy_id = get_option('complianz_active_policy_id');
            $policy_id = $policy_id ? $policy_id : 1;
            $policy_id++;

            update_option('complianz_active_policy_id', $policy_id);
        }


        /**
         * Make sure we only have the front-end settings for the output
         *
         * */

        public function get_cookiebanner_settings($banner_id)
        {
            $banner = new CMPLZ_COOKIEBANNER($banner_id);
            $output = $banner->get_settings_array();

            //deprecated filter
            $output = apply_filters('cmplz_cookie_settings', $output);

            return apply_filters('cmplz_cookiebanner_settings', $output);
        }


        /**
         * The classes that are passed to the statistics script determine if these are executed immediately or not.
         *
         *
         * */

        public function get_statistics_script_classes(){
            //if a cookie warning is needed for the stats we don't add a native class, so it will be disabled by the cookie blocker by default
            $classes[] = 'cmplz-stats';

            //if no cookie warning is needed for the stats specifically, we can move this out of the warning code by adding the native class
            if ($this->tagmamanager_fires_scripts() || !$this->cookie_warning_required_stats()) $classes[] = 'cmplz-native';

            return $classes;
        }

        /**
         * Print inline cookie enabling scripts and statistics scripts
         */

        public function inline_cookie_script()
        {

            $classes = $this->get_statistics_script_classes();

            if ($this->tagmamanager_fires_scripts() || !$this->cookie_warning_required_stats() || ($this->cookie_warning_required_stats() && $this->uses_google_analytics())) { ?>
                <script type='text/javascript' class="<?php echo implode(" ", $classes)?>">
                    <?php do_action('cmplz_statistics_script');?>
                </script>
            <?php }

            do_action('cmplz_before_statistics_script');

            //when analytics is used it is inserted always, but anonymized by default.
            ?>
            <script class="cmplz-native">
                function complianz_enable_cookies() {
                    console.log("enabling cookies");
                    <?php
                    if (!$this->tagmamanager_fires_scripts() && $this->cookie_warning_required_stats() && !$this->uses_google_analytics()) {
                        do_action('cmplz_statistics_script');
                    }
                    $this->get_cookie_script();
                    ?>
                }
            </script>

            <?php

        }



        public function inline_cookie_script_no_warning()
        {
            ?>
            <script type='text/javascript' class="cmplz-native">
                <?php do_action('cmplz_statistics_script');?>
                <?php $this->get_cookie_script();?>
            </script>
            <?php
        }


        /**
         *
         * @hooked cmplz_statistics_script
         *
         *
         * */

        public function get_statistics_script()
        {
            if (cmplz_get_value('configuration_by_complianz')==='no') return;

            $statistics = cmplz_get_value('compile_statistics');
            if ($statistics === 'google-tag-manager') {
                $script = cmplz_get_template('google-tag-manager.js');
                $script = str_replace('{GTM_code}', esc_attr(cmplz_get_value("GTM_code")), $script);
            } elseif ($statistics === 'google-analytics') {
                $anonymize_ip = $this->google_analytics_always_block_ip() ? "'anonymizeIp': true" : "";
                $script = cmplz_get_template('google-analytics.js');
                $script = str_replace('{UA_code}', esc_attr(cmplz_get_value("UA_code")), $script);
                $script = str_replace('{anonymize_ip}', $anonymize_ip, $script);
            } elseif ($statistics === 'matomo') {
                $script = cmplz_get_template('matomo.js');
                $script = str_replace('{site_id}', esc_attr(cmplz_get_value('matomo_site_id')), $script);
                $script = str_replace('{matomo_url}', esc_url_raw(trailingslashit(cmplz_get_value('matomo_url'))), $script);
            } else {
                $script = cmplz_get_value('statistics_script');
            }

            echo ($script);
        }

        /**
         * Retrieve scripts that place cookies, as user inserted on the back-end.
         */

        private function get_cookie_script()
        {
            echo cmplz_get_value('cookie_scripts');
        }


//        public function maybe_clear_cookies()
//        {
//            if ($this->scan_complete()) return;
//            $id = sanitize_title($_GET['complianz_id']);
//            //the first run should clean up the cookies.
//            if ($id === 'clean') {
//                if (isset($_SERVER['HTTP_COOKIE'])) {
//                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
//                    foreach ($cookies as $cookie) {
//                        $parts = explode('=', $cookie);
//                        $name = trim($parts[0]);
//                        if (strpos($name, 'complianz') === FALSE && strpos($name, 'wordpress') === FALSE && strpos($name, 'wp-') === FALSE) {
//
//                            setcookie($name, '', time() - 1000, '/', $this->get_domain(), true, true);
//                        }
//
//                    }
//                }
//            }
//        }

        /**
         *
         * Get the domain from the current site url
         * @return bool|string $domain
         */

        public function get_domain(){
            $url = site_url();
            $parse = parse_url($url);
            if (!isset($parse['host'])) return false;

            return $parse['host'];
        }


        /**
         * Get all cookies, and post back to site with ajax.
         * This script is only inserted when a valid token is passed, so will never run for other visitors than the site admin
         *
         * */

        public function test_cookies()
        {
            if ($this->scan_complete()) return;

            $token = sanitize_title($_GET['complianz_scan_token']);
            $id = sanitize_title($_GET['complianz_id']);
            $admin_url = admin_url('admin-ajax.php');

            $javascript = cmplz_get_template('test-cookies.js');
            $javascript = str_replace(array('{admin_url}', '{token}', '{id}'), array(esc_url_raw($admin_url), esc_attr($token), esc_attr($id)), $javascript);
            ?>
            <script>
                <?php echo $javascript;?>
            </script>

            <?php
        }

        public function track_cookie_changes()
        {
            if (!current_user_can('manage_options')) return;

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
            if (!current_user_can('manage_options')) return;

            if (defined('CMPLZ_DO_NOT_SCAN') && CMPLZ_DO_NOT_SCAN) return;

            if (isset($_GET['complianz_scan_token'])) {
                return;
            }

            //if the cookie list cache is cleared, empty the processed page list so the scan starts again.
            if (!get_transient('cmplz_detected_cookies')) {
                $this->reset_pages_list();
            }

            if (!$this->scan_complete()) {
                //store the date
                $timezone_offset = get_option('gmt_offset');
                $time = time() + (60 * 60 * $timezone_offset);
                update_option('cmplz_last_cookie_scan', $time);

                $url = $this->get_next_page_url();
                if (!$url) return;
                //first, get the html of this page.
                if (strpos($url, 'complianz_id') !== FALSE) {

                    $response = wp_remote_get($url);
                    if (!is_wp_error($response)) {
                        $html = $response['body'];
                        $stored_social_media = cmplz_scan_detected_social_media();
                        if (!$stored_social_media) $stored_social_media = array();
                        $social_media = $this->parse_for_social_media($html);

                        $social_media = array_unique(array_merge($stored_social_media, $social_media), SORT_REGULAR);
                        update_option('cmplz_detected_social_media', $social_media);

                        $stored_thirdparty_services = cmplz_scan_detected_thirdparty_services();
                        if (!$stored_thirdparty_services) $stored_thirdparty_services = array();
                        $thirdparty = $this->parse_for_thirdparty_services($html);
                        $thirdparty = array_unique(array_merge($stored_thirdparty_services, $thirdparty), SORT_REGULAR);
                        update_option('cmplz_detected_thirdparty_services', $thirdparty);
                    }
                }

                //load in iframe so the scripts run.
                echo '<iframe id="cmplz_cookie_scan_frame" class="hidden" src="' . $url . '"></iframe>';

            }
        }

        /**
         * Check the webpage html output for social media markers.
         * @param string $html
         * @param bool $single_key
         * @return array|bool|string $social_media_key
         */

        public function parse_for_social_media($html, $single_key=false)
        {
            $social_media = array();
            $social_media_markers = COMPLIANZ()->config->social_media_markers;
            foreach ($social_media_markers as $key => $markers) {
                foreach ($markers as $marker) {
                    if (strpos($html, $marker) !== FALSE && !in_array($key, $social_media)) {
                        if ($single_key) return $key;
                        $social_media[] = $key;
                    }
                }
            }
            if ($single_key) return false;

            return $social_media;
        }

        /**
         * Check a string for third party services
         * @param string $html
         * @param bool $single_key //return a single string instead of array
         * @return array|string $thirdparty
         *
         * */

        public function parse_for_thirdparty_services($html, $single_key=false)
        {

            $thirdparty = array();
            $thirdparty_markers = COMPLIANZ()->config->thirdparty_service_markers;
            foreach ($thirdparty_markers as $key => $markers) {
                foreach ($markers as $marker) {

                    if (strpos($html, $marker) !== FALSE && !in_array($key, $thirdparty)) {
                        if ($single_key) return $key;
                        $thirdparty[] = $key;
                    }
                }
            }
            if ($single_key) return false;

            return $thirdparty;
        }


        private function get_next_page_url()
        {
            if (!current_user_can('manage_options')) return;

            $token = time();
            update_option('complianz_scan_token', $token);
            $pages = $this->pages_to_process();
            if (count($pages) == 0) return false;

            $id_to_process = reset($pages);
            $this->set_page_as_processed($id_to_process);
            $url = ($id_to_process === 'home') ? site_url() : get_permalink($id_to_process);
            $url = add_query_arg(array("complianz_scan_token" => $token, 'complianz_id' => $id_to_process), $url);
            if (is_ssl()) $url = str_replace("http://", "https://", $url);
            return $url;
        }




        /**
         *
         * Get list of page id's that we want to process this set of scan requests, which weren't included in the scan before
         *
         * @since 1.0
         * @return array $pages
         * */

        public function get_pages_list_single_run()
        {
            $posts = get_transient('cmplz_pages_list');
            if (!$posts) {
                $args = array(
                    'public'  => true,
                );
                $post_types = get_post_types( $args);

                unset($post_types['elementor_font']);
                unset($post_types['attachment']);
                unset($post_types['revision']);
                unset($post_types['nav_menu_item']);
                unset($post_types['custom_css']);
                unset($post_types['customize_changeset']);
                unset($post_types['user_request']);

                $posts = array();
                foreach ($post_types as $post_type){
                    $args = array(
                        'post_type' => $post_type,
                        'posts_per_page' => 5,
                        'meta_query' => array(
                            array(
                                'key' => '_cmplz_scanned_post',
                                'compare' => 'NOT EXISTS'
                            ),
                        )
                    );
                    $new_posts = get_posts($args);
                    $posts = array_merge($posts , $new_posts);
                }

                if (count($posts)==0){
                    /*
                     * If we didn't find any posts, we reset the post meta that tracks if all posts have been scanned.
                     * This way we will find some posts on the next scan attempt
                     * */
                    if (!function_exists('delete_post_meta_by_key')) {
                        require_once ABSPATH . WPINC . '/post.php';
                    }
                    delete_post_meta_by_key('_cmplz_scanned_post');

                    //now we need to reset the scanned pages list too
                    $this->reset_pages_list();
                } else {
                    $posts = wp_list_pluck($posts, 'ID');
                    foreach ($posts as $post_id){
                        update_post_meta($post_id, '_cmplz_scanned_post', true);
                    }
                }

                $posts[]='home';
                set_transient('cmplz_pages_list', $posts, WEEK_IN_SECONDS);
            }
            return $posts;
        }

        /**
         * Reset the list of pages
         *
         * @return void
         *
         * @since 2.1.5
         */

        public function reset_pages_list(){
            delete_transient('cmplz_pages_list');
            update_option('cmplz_processed_pages_list', array());
        }


        /**
         * Get list of pages that were processed before
         *
         * @return array $pages
         */

        public function get_processed_pages_list()
        {

            $pages = get_option('cmplz_processed_pages_list');
            if (!is_array($pages)) $pages = array();

            return $pages;
        }

        /**
         * Check if the scan is complete
         *
         * @param void
         * @return bool
         * @since 1.0
         *
         * */


        public function scan_complete()
        {
            $pages = $this->pages_to_process();

            if (count($pages) == 0) return true;

            return false;
        }

        /**
         *
         * Get list of pages that still have to be processed
         *
         * @param void
         * @return array $pages
         * @since 1.0
         */

        private function pages_to_process()
        {
            $pages_list = COMPLIANZ()->cookie->get_pages_list_single_run();
            $processed_pages_list = $this->get_processed_pages_list();

            $pages = array_diff($pages_list, $processed_pages_list);

            return $pages;
        }

        /**
         * Set a page as being processed
         * @param $id
         * @return void
         * @since 1.0
         */

        public function set_page_as_processed($id)
        {
            if (!current_user_can('manage_options')) return;

            if ($id !== 'home' && !is_numeric($id)) {
                return;
            }

            $pages = $this->get_processed_pages_list();
            if (!in_array($id, $pages)) {
                $pages[] = $id;
                update_option('cmplz_processed_pages_list', $pages);
            }
        }

        /**
         * Get list of detected cookies
         * @param void
         * @return array $cookies
         * @since 1.0
         *
         */

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


        /**
         * This function gets the cookies by types, so we only get one type per set of cookies.
         *
         * @param bool $count_statistics
         * @param bool $count_php_session
         * @return array detected cookie types
         */

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
            if (!current_user_can('manage_options')) return;
            if (isset($_POST['token']) && (sanitize_title($_POST['token']) == get_option('complianz_scan_token'))) {

                $post_cookies = isset($_POST['cookies']) && is_array($_POST['cookies']) ? $_POST['cookies'] : array();
                $found_cookies = array_map(function ($el) {
                    return sanitize_title($el);
                }, $post_cookies);
                if (!is_array($found_cookies)) $found_cookies = array();

                $post_storage = isset($_POST['lstorage']) && is_array($_POST['lstorage']) ? $_POST['lstorage'] : array();
                $found_storage = array_map(function ($el) {
                    return sanitize_title($el);
                }, $post_storage);
                if (!is_array($found_storage)) $found_storage = array();


                $found_cookies = array_merge($found_cookies, $_COOKIE, $found_storage);
                $found_cookies = array_map('sanitize_text_field', $found_cookies);
                $cookies = array();

                foreach ($found_cookies as $key => $value) {
                    $cookies[$key] = $this->get_cookie_description($key);
                }

                if (!is_array($cookies)) $cookies = array($cookies);
                set_transient('cmplz_detected_cookies', $cookies, MONTH_IN_SECONDS);

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
                $date = sprintf(__("%s at %s", 'complianz-gdpr'), $date, $time);
            } else {
                $date = false;
            }
            return $date;
        }


        public function set_cookies_changed()
        {
            update_option('cmplz_changed_cookies', 1);

        }

        public function cookies_changed()
        {
            return (get_option('cmplz_changed_cookies') == 1);
        }

        public function reset_cookies_changed()
        {
            delete_transient('cmplz_cookie_settings_cache');
            update_option('cmplz_changed_cookies', -1);
        }

        public function update_cookie_policy_date()
        {
            $date = date(get_option('date_format'), time());
            update_option('cmplz_publish_date', $date);

            //also reset the email notification, so it will get sent next year.
            update_option('cmplz_update_legal_documents_mail_sent', false);
        }

        /**
         * Get a label/description based on a list of known cookie keys.
         * @param string $cookie_name
         * @return string $label
         *
         * @since 1.0.0
         *
         * */

        public function get_cookie_description($cookie_name)
        {
            $label = __("Origin unknown", 'complianz-gdpr');
            foreach ($this->known_cookie_keys as $id => $cookie) {
                $used_cookie_names = $cookie['unique_used_names'];
                foreach ($used_cookie_names as $used_cookie_name) {
                    if ($this->match($cookie_name, $used_cookie_name)) return $cookie['label'];
                }

            }

            return $label;
        }

        /**
         * Check if the passed cookiename matches the passed test cookiename
         *
         * @param string $compare_cookie_name cookiename to compare with
         * @param string $test cookiename to test for
         * @return bool
         *
         * @since 2.0.5
         */


        public function match($test, $compare_cookie_name){

            //check if the string "partial" is in the comparison cookie name
            if (strpos($compare_cookie_name, 'partial') !== false) {
                //check if it has an underscore before or after the partial. If so, take it into account
                if (strpos($compare_cookie_name, '_partial') !== false) $partial = '_partial';
                if (strpos($compare_cookie_name, 'partial_') !== false) $partial = 'partial_';
                if (strpos($compare_cookie_name, '_partial_') !== false) $partial = '_partial_';

                //get the substring before or after the partial
                $str1 = substr($compare_cookie_name, 0, strpos($compare_cookie_name, $partial));
                $str2 = substr($compare_cookie_name, strpos($compare_cookie_name, $partial)+strlen($partial));
                //a partial match is enough on this type
                if (empty($str1)){
                    if (strpos($test, $str2) !== FALSE) {
                        return true;
                    }
                } elseif (empty($str2)){
                    if (strpos($test, $str1) !== FALSE) {
                        return true;
                    }
                } else {
                    if (strpos($test, $str1) !== FALSE && strpos($test, $str1) !== FALSE) {
                        return true;
                    }
                }

            } elseif ($compare_cookie_name === $test) {
                return true;
            }
            return false;
        }



        /**
         * Checks if the cookie an unknown cookie, not listed in our database
         * @oaram $cookie_name
         * @return bool
         *
         * */

        public function is_unknown_cookie($cookie_name)
        {

            foreach ($this->known_cookie_keys as $id => $cookie) {
                $used_cookie_names = $cookie['unique_used_names'];
                foreach ($used_cookie_names as $used_cookie_name) {
                    if ($this->match($cookie_name, $used_cookie_name)) return false;
                }

            }

            return true;
        }

        /*
         * Check if the scan has detected any unknown cookies. If a cookie is reported, it is not counted.
         *
         * @return bool
         * */

        public function has_unknown_cookies(){
            $cookies = $this->get_detected_cookies();
            foreach ($cookies as $key => $label){
                if ($this->is_unknown_cookie($key) && !$this->is_reported($key)){
                    return true;
                }
            }
            return false;
        }

        public function ajax_report_unknown_cookies(){
            if (!current_user_can('manage_options')) return;


            //send mail
            $headers = array();
            $user_info = get_userdata(get_current_user_id());
            $nicename = $user_info->user_nicename;
            $email = $user_info->user_email;

            $headers[] = "Reply-to: $nicename <$email>" . "\r\n";

            add_filter('wp_mail_content_type', function ($content_type) {
                return 'text/html';
            });

            //make list of not recognized cookies
            $cookies = array_keys($this->get_detected_cookies());
            $used_cookies = cmplz_get_value('used_cookies');
            $unknown_cookies = array();
            foreach($cookies as $key){
                if ($this->is_unknown_cookie($key)){
                    $unknown_cookies[] = $key;
                }
            }

            if (count($unknown_cookies)!=0) {

                //store these unknown cookies as having been reported
                $reported_cookies = get_option('cmplz_reported_cookies');
                if (!is_array($reported_cookies)) $reported_cookies = array();
                foreach ($unknown_cookies as $key) {
                    if (!in_array($key, $reported_cookies)) $reported_cookies[] = $key;
                }
                update_option('cmplz_reported_cookies', $reported_cookies);

                //create message
                $plugins = get_option('active_plugins');

                //get entered cookie descriptions:
                foreach ($unknown_cookies as $key) {
                    $cookie_id = $this->get_cookie_id($key);
                    $used_cookie_index = array_search($cookie_id, array_column($used_cookies, 'key'));
                    $label = !empty($used_cookie_index) && isset($used_cookies[$used_cookie_index]['label']) ? $used_cookies[$used_cookie_index]['label'] : "";
                    $desc = !empty($used_cookie_index) && isset($used_cookies[$used_cookie_index]['description']) ? $used_cookies[$used_cookie_index]['description'] : "";
                    $unknown_cookies_description[] = $key . " / label: " . $label . " / description : " . $desc . "";
                }

                $message = "Unknown cookies reported on " . home_url() . "<br>";
                $message .= "Uknown cookies:<br>" . implode('<br>', $unknown_cookies_description) . "<br><br>";
                $message .= "Active plugins:<br>";
                $message .= implode('<br>', $plugins);

                wp_mail("support@complianz.io", "Unknown cookie report from " . home_url(), $message, $headers);

                // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
                remove_filter('wp_mail_content_type', 'set_html_content_type');
            }
            $data = array('success' => true);
            $response = json_encode($data);
            header("Content-Type: application/json");
            echo $response;
            exit;

        }

        private function is_reported($key){
            delete_option('cmplz_reported_cookies');
            $reported_cookies = get_option('cmplz_reported_cookies');

            if (is_array($reported_cookies) && in_array($key, $reported_cookies)) return true;

            return false;
        }

        /*
         * Get cookie id by cookie name
         *
         *
         * */

        public function get_cookie_id($cookie_name)
        {
            foreach ($this->known_cookie_keys as $id => $cookie) {
                $used_cookie_names = $cookie['unique_used_names'];
                foreach ($used_cookie_names as $used_cookie_name) {
                    if ($this->match($cookie_name, $used_cookie_name)) return $id;
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
                $html = $this->get_detected_cookies_table();
            }

            $out = array(
                'success' => !$error,
                'cookies' => $html,
            );

            die(json_encode($out));
        }

        public function get_detected_cookies_table()
        {
            $html = '';

            $cookies = $this->get_detected_cookies();
            $social_media = cmplz_scan_detected_social_media();
            $thirdparty = cmplz_scan_detected_thirdparty_services();
            if (!$cookies && !$social_media && !$thirdparty) {
                if ($this->scan_complete()) {
                    $html = __("No cookies detected", 'complianz-gdpr');
                } else {
                    $html = __("Cookie scan in progress", 'complianz-gdpr');
                }
            } else {

                /*
                 * Show the cookies from our own domain
                 * */
                $html .= '<tr class="group-header"><td colspan="2"><b>' . __('Cookies on your own domain', 'complianz-gdpr') . "</b></td></tr>";
                $cookies = $this->get_detected_cookies();
                if ($cookies) {
                    foreach ($cookies as $key => $value) {
                        $html .= '<tr>';
                        $html .= '<td>' . $key . "</td><td>" . $value . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td></td><td>---</td></tr>';
                }
                /*
                 * Show the social media which are placing cookies
                 * */
                $html .= '<tr class="group-header"><td colspan="2"><b>' . __('Social media', 'complianz-gdpr') . "</b></td></tr>";
                if ($social_media && count($social_media)>0) {
                    foreach ($social_media as $key => $type) {
                        if (isset($this->known_cookie_keys[$type])) {
                            $known_cookie = $this->known_cookie_keys[$type];
                            $html .= '<tr><td>'.implode(', ',$known_cookie['used_names']).'</td><td>' . $known_cookie['label'] . "</td></tr>";
                        }

                    }
                } else {
                    $html .= '<tr><td></td><td>---</td></tr>';
                }
                /*
                 * Show the third party services which are placing cookies
                 * */
                $html .= '<tr class="group-header"><td colspan="2"><b>' . __('Third party services', 'complianz-gdpr') . "</b></td></tr>";
                if ($thirdparty && count($thirdparty)>0) {
                    foreach ($thirdparty as $key => $type) {
                        if (isset($this->known_cookie_keys[$type])) {
                            $known_cookie = $this->known_cookie_keys[$type];
                            $html .= '<tr><td>'.implode(', ', $known_cookie['used_names']).'</td><td>' . $known_cookie['label'] . "</td></tr>";
                        }
                    }
                } else {
                    $html .= '<tr><td></td><td>---</td></tr>';
                }
            }
            $html = '<table style="width:100%">' . $html . "</table>";
            return $html;
        }

        /**
         * Check if there's a cookie which is not completed
         * @return bool
         */


        public function has_empty_cookie_descriptions(){

            $values = cmplz_get_value('used_cookies');
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $value_key = (isset($value['key'])) ? $value['key'] : false;
                    $value['label'] = empty($value['label']) ? COMPLIANZ()->cookie->get_default_value('label', $value_key) : $value['label'];
                    $value['used_names'] = empty($value['used_names']) ? COMPLIANZ()->cookie->get_default_value('used_names', $value_key) : $value['used_names'];
                    $value['purpose'] = empty($value['purpose']) ? COMPLIANZ()->cookie->get_default_value('purpose', $value_key) : $value['purpose'];
                    $value['privacy_policy_url'] = empty($value['privacy_policy_url']) ? COMPLIANZ()->cookie->get_default_value('privacy_policy_url', $value_key) : $value['privacy_policy_url'];
                    $value['storage_duration'] = empty($value['storage_duration']) ? COMPLIANZ()->cookie->get_default_value('storage_duration', $value_key) : $value['storage_duration'];
                    $value['description']= empty($value['description']) ? COMPLIANZ()->cookie->get_default_value('description', $value_key) : $value['description'];
                    $value['key'] = empty($value['key']) ? COMPLIANZ()->cookie->get_default_value('key', $value_key) : $value['key'];

                    $empty = ( empty($value['label'])
                            || empty($value['used_names'])
                            || empty($value['purpose'])
                            || empty($value['privacy_policy_url'])
                            || empty($value['storage_duration'])
                            || empty($value['description'])
                    );
                    $saved_by_user = (isset($value['saved_by_user']) && $value['saved_by_user']) ? true : false;
                    $show_on_policy = !$saved_by_user && empty($value['show']) ? COMPLIANZ()->cookie->get_default_value('show', $value_key) : $value['show'];

                    if ($show_on_policy && $empty) {
                        return true;
                    }
                }
            }
            return false;
        }


        public function get_progress_count()
        {
            $done = $this->get_processed_pages_list();
            $total = COMPLIANZ()->cookie->get_pages_list_single_run();
            $progress = 100 * (count($done) / count($total));
            if ($progress > 100) $progress = 100;
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
            <div class="field-group first">
                <?php
                if (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1) {
                    cmplz_notice(__("You have Do Not Track enabled. This will prevent most cookies from being placed. Please run the scan with Do Not Track disabled.",'complianz-gdpr'));
                }
                ?>

                <div id="cmplz_adblock_warning" style="display:none"><?php cmplz_notice(__("You are using an ad blocker. This will prevent most cookies from being placed. Please run the scan without an adblocker enabled.",'complianz-gdpr'), 'warning')?></div>
                <div id="cmplz_anonymous_window_warning" style="display:none"><?php cmplz_notice(__("You are using an anonymous window. This will prevent most cookies from being placed. Please run the scan in a normal browser window.",'complianz-gdpr'), 'warning')?></div>

                <div class="cmplz-label">
                    <label for="scan_progress"><?php _e("Cookie scan", 'complianz-gdpr') ?></label>
                </div>
                <div id="cmplz-scan-progress">
                    <div class="cmplz-progress-bar"></div>
                </div>
                <br>
                <?php echo __("Cookies as detected by the automatic cookie scan. Please note that only cookies set on your own domain are detected by this scan.", 'complianz-gdpr')." ".__("Third party scripts will get detected if they're listed in the third party list.", 'complianz-gdpr') ?>
                <div class="detected-cookies">
                    <?php echo $this->get_detected_cookies_table(); ?>
                </div>
                <input type="submit" class="button cmplz-rescan"
                       value="<?php _e('Re-scan', 'complianz-gdpr') ?>" name="rescan">

            </div>

            <?php
        }



        public function report_unknown_cookies_callback($args){
            if ($this->has_unknown_cookies()) {
                do_action('complianz_before_label', $args); ?>
                <label for=""><?php echo esc_html($args['label']) ?></label>
                <?php do_action('complianz_after_label', $args); ?>

                <button id="cmplz-report-unknown-cookies" type="button"
                        class="button"><?php _e("Report all unknown cookies", 'complianz-gdpr') ?></button>
                <span id="cmplz-report-confirmation"
                      style="display:none"><?php cmplz_notice(__('Thank you, your report has been received successfully', 'complianz-gdpr'), 'success') ?></span>
                <?php
                do_action('complianz_after_label', $args);
                do_action('complianz_after_field', $args);
            }
        }


        /*
         * Check if site uses Google Analytics
         *
         *
         * */

        public function uses_google_analytics()
        {
            $statistics = cmplz_get_value('compile_statistics');
            if ($statistics === 'google-analytics') {
                return true;
            }

            return false;
        }

        public function uses_google_tagmanager()
        {

            $statistics = cmplz_get_value('compile_statistics');

            if ($statistics === 'google-tag-manager') {
                return true;
            }

            return false;
        }

        public function uses_matomo()
        {
            $statistics = cmplz_get_value('compile_statistics');
            if ($statistics === 'matomo') {
                return true;
            }
            return false;
        }


        public function analytics_configured()
        {
            //if the user has chosen to configure it himself, we consider it to be configured.
            if (cmplz_get_value('configuration_by_complianz')==='no') return true;

            $UA_code = COMPLIANZ()->field->get_value('UA_code');
            if (!empty($UA_code)) return true;

            return false;
        }

        public function tagmanager_configured()
        {
            //if the user has chosen to configure it himself, we consider it to be configured.
            if (cmplz_get_value('configuration_by_complianz')==='no') return true;
            $GTM_code = COMPLIANZ()->field->get_value('GTM_code');
            if (!empty($GTM_code)) return true;

            return false;
        }

        public function matomo_configured()
        {
            //if the user has chosen to configure it himself, we consider it to be configured.
            if (cmplz_get_value('configuration_by_complianz')==='no') return true;

            $matomo_url = COMPLIANZ()->field->get_value('matomo_url');
            $site_id = COMPLIANZ()->field->get_value('matomo_site_id');
            if (!empty($matomo_url) && !empty($site_id)) return true;

            return false;
        }


        /**
         * Check if the site needs a cookie banner. Pass a region to check cookie banner requirement for a specific region
         *
         * @@since 1.2
         *
         * @param string|bool $region
         *
         * @return bool
         * */


        public function site_needs_cookie_warning($region=false)
        {
            if (cmplz_get_value('uses_cookies') !== 'yes') {
                return false;
            }

            //if we do not target this region, we don't show a banner for that region
            if ($region && !cmplz_has_region($region)) return false;

            /*
             * for the US, a cookie warning is always required
             * if a region other than US is passed, we check the region's requirements
             * if US is passed, we always need a banner.
             *
             */

            if ($region && !cmplz_has_region($region)) return false;

            if ((!$region || $region==='us') && cmplz_has_region('us')){
                return true;
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

        /**
         * Check if the site has third party cookies active
         *
         * @@since 1.0
         *
         * @return bool
         * */

        public function third_party_cookies_active()
        {
            //if user states no cookies are used, we simply return false.
            if (cmplz_get_value('uses_cookies') !== 'yes') {
                return false;
            }

            $thirdparty_scripts = cmplz_get_value('thirdparty_scripts');
            $thirdparty_iframes = cmplz_get_value('thirdparty_iframes');
            $thirdparty_scripts = empty($thirdparty_scripts) ? false : true;
            $thirdparty_iframes = empty($thirdparty_iframes) ? false : true;

            $ad_cookies = (cmplz_get_value('uses_ad_cookies') === 'yes') ? true : false;
            $social_media = (cmplz_get_value('uses_social_media') === 'yes') ? true : false;

            $thirdparty_services = (cmplz_get_value('uses_thirdparty_services') === 'yes') ? true : false;

            if ($thirdparty_scripts || $thirdparty_iframes || $ad_cookies || $social_media || $thirdparty_services) {
                return true;
            }

            return false;
        }

        /**
         * Check if the site needs a cookie banner considering statistics only
         * @param $region bool|string
         * @@since 1.0
         *
         * @return bool
         * */

        public function cookie_warning_required_stats($region=false)
        {

            if ($region){
                $eu = false;
                $uk = false;
                if ($region==='uk') $uk = true;
                if ($region==='eu') $eu = true;
            } else {
                $eu = cmplz_has_region('eu');
                $uk = cmplz_has_region('uk');
            }

            if (cmplz_get_value('uses_cookies') !== 'yes') {
                return false;
            }

            //uk requires cookie warning for stats
            if ($uk) return true;

            //us only, no cookie warning required for stats
            if (!$eu & !$uk) return false;

            $statistics = cmplz_get_value('compile_statistics');

            //uk requires cookie warning for stats
            if ($uk && $statistics !== 'no') return true;

            //us only, no cookie warning required for stats
            //but for us a cookie warning is required anyway
            if (!$eu & !$uk) return false;

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

            if ($matomo && (cmplz_get_value('matomo_anonymized') !== 'yes')) return true;

            return false;
        }


        public function google_analytics_always_block_ip()
        {
            $statistics = cmplz_get_value('compile_statistics');
            $google_analytics = ($statistics === 'google-analytics') ? true : false;

            if ($google_analytics) {
                $thirdparty = cmplz_get_value('compile_statistics_more_info');
                $always_block_ip = (isset($thirdparty['ip-addresses-blocked']) && ($thirdparty['ip-addresses-blocked'] == 1)) ? true : false;
                if ($always_block_ip) return true;
            }

            return false;
        }


        /*
         * Check if Google Tag Manager is configured to fire scripts, managed remotely
         *
         *
         * */

        public function tagmamanager_fires_scripts()
        {

            if (!$this->uses_google_tagmanager()) return false;

            $tm_fires_scripts = (cmplz_get_value('fire_scripts_in_tagmanager') === 'yes') ? TRUE : FALSE;

            return $tm_fires_scripts;
        }

        /*
         *
         * Check if the site uses non functional cookies
         *
         *
         * */

        public function uses_non_functional_cookies()
        {
            if ($this->tagmamanager_fires_scripts()) return true;

            //third party cookies are not always non-functional, like vimeo (?).
            //if ($this->third_party_cookies_active()) return true;

            //get all used cookies
            $used_cookies = cmplz_get_value('used_cookies');
            if (empty($used_cookies) || !is_array($used_cookies)) return false;
            foreach ($used_cookies as $cookie) {
                if ($cookie === 'google-analytics') continue;
                if ($cookie === 'matomo') continue;
                if (!isset($cookie['functional'])) continue;
                if ($cookie['functional'] !== 'on') {
                    return true;
                }
            }
            return false;

            //count cookies that are not functional
        }


        public function uses_only_functional_cookies()
        {
            //get all used cookies
            $used_cookies = cmplz_get_value('used_cookies');
            if (empty($used_cookies) || !is_array($used_cookies)) return false;
            foreach ($used_cookies as $cookie) {
                if (!isset($cookie['functional'])) continue;
                if ($cookie['functional'] !== 'on') {
                    return false;
                }
            }
            return true;

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
                    COMPLIANZ()->field->add_multiple_field('used_cookies', $cookie_type);
                }
            }

        }

        /**
         * Removes legacy (pre 2.1.7) cookie settings. Settings have been moved to separate database table and object
         * @param $variation_id
         *
         */

        public function migrate_legacy_cookie_settings($variation_id=''){
            //check if there is already a default item.
            global $wpdb;
            $default_cookiebanner = false;
            $cookiebanners = $wpdb->get_results("select * from {$wpdb->prefix}cmplz_cookiebanners as cdb where cdb.default=true");
            if ($variation_id=='' && count($cookiebanners)>=1) {
                $default_cookiebanner = $cookiebanners[0];
            }

            //the variation without ID is the default one.
            $cookie_settings = get_option('complianz_options_cookie_settings');

            if ($variation_id==='' && $default_cookiebanner){
                $banner_id = $default_cookiebanner->ID;
                $banner = new CMPLZ_COOKIEBANNER($banner_id);
            } else {
                $banner = new CMPLZ_COOKIEBANNER();
            }

            $banner->title = $variation_id=='' ? __('Default Cookie banner', 'complianz-gdpr') :COMPLIANZ()->statistics->get_variation_nicename($variation_id);
            $banner->default = ($variation_id === '') ? true : false;

            if (isset($cookie_settings['position'.$variation_id])) $banner->position = $cookie_settings['position'.$variation_id];
            if (isset($cookie_settings['theme'.$variation_id])) $banner->theme = $cookie_settings['theme'.$variation_id];
            if (isset($cookie_settings['revoke'.$variation_id])) $banner->revoke = $cookie_settings['revoke'.$variation_id];
            if (isset($cookie_settings['dismiss'.$variation_id])) $banner->dismiss = $cookie_settings['dismiss'.$variation_id];
            if (isset($cookie_settings['save_preferences'.$variation_id])) $banner->save_preferences = $cookie_settings['save_preferences'.$variation_id];
            if (isset($cookie_settings['view_preferences'.$variation_id])) $banner->view_preferences = $cookie_settings['view_preferences'.$variation_id];
            if (isset($cookie_settings['category_functional'.$variation_id])) $banner->category_functional = $cookie_settings['category_functional'.$variation_id];
            if (isset($cookie_settings['category_all'.$variation_id])) $banner->category_all = $cookie_settings['category_all'.$variation_id];
            if (isset($cookie_settings['category_stats'.$variation_id])) $banner->category_stats = $cookie_settings['category_stats'.$variation_id];
            if (isset($cookie_settings['accept'.$variation_id])) $banner->accept = $cookie_settings['accept'.$variation_id];
            if (isset($cookie_settings['message'.$variation_id])) $banner->message_optin = $cookie_settings['message'.$variation_id];
            if (isset($cookie_settings['readmore'.$variation_id])) $banner->readmore_optin = $cookie_settings['readmore'.$variation_id];
            if (isset($cookie_settings['use_categories'.$variation_id])) $banner->use_categories = $cookie_settings['use_categories'.$variation_id];
            if (isset($cookie_settings['tagmanager_categories'.$variation_id])) $banner->tagmanager_categories = $cookie_settings['tagmanager_categories'.$variation_id];
            if (isset($cookie_settings['hide_revoke'.$variation_id])) $banner->hide_revoke = $cookie_settings['hide_revoke'.$variation_id];
            if (isset($cookie_settings['dismiss_on_scroll'.$variation_id])) $banner->dismiss_on_scroll = $cookie_settings['dismiss_on_scroll'.$variation_id];
            if (isset($cookie_settings['dismiss_on_timeout'.$variation_id])) $banner->dismiss_on_timeout = $cookie_settings['dismiss_on_timeout'.$variation_id];
            if (isset($cookie_settings['dismiss_timeout'.$variation_id])) $banner->dismiss_timeout = $cookie_settings['dismiss_timeout'.$variation_id];
            if (isset($cookie_settings['accept_informational'.$variation_id])) $banner->accept_informational = $cookie_settings['accept_informational'.$variation_id];
            if (isset($cookie_settings['message_us'.$variation_id])) $banner->message_optout = $cookie_settings['message_us'.$variation_id];
            if (isset($cookie_settings['readmore_us'.$variation_id])) $banner->readmore_optout = $cookie_settings['readmore_us'.$variation_id];
            if (isset($cookie_settings['readmore_privacy'.$variation_id])) $banner->readmore_privacy = $cookie_settings['readmore_privacy'.$variation_id];
            if (isset($cookie_settings['popup_background_color'.$variation_id])) $banner->popup_background_color = $cookie_settings['popup_background_color'.$variation_id];
            if (isset($cookie_settings['popup_text_color'.$variation_id])) $banner->popup_text_color = $cookie_settings['popup_text_color'.$variation_id];
            if (isset($cookie_settings['button_background_color'.$variation_id])) $banner->button_background_color = $cookie_settings['button_background_color'.$variation_id];
            if (isset($cookie_settings['button_text_color'.$variation_id])) $banner->button_text_color = $cookie_settings['button_text_color'.$variation_id];
            if (isset($cookie_settings['border_color'.$variation_id])) $banner->border_color = $cookie_settings['border_color'.$variation_id];
            if (isset($cookie_settings['cookie_expiry'.$variation_id])) $banner->cookie_expiry = $cookie_settings['cookie_expiry'.$variation_id];
            if (isset($cookie_settings['use_custom_cookie_css'.$variation_id])) $banner->use_custom_cookie_css = $cookie_settings['use_custom_cookie_css'.$variation_id];
            if (isset($cookie_settings['custom_css'.$variation_id])) $banner->custom_css = $cookie_settings['custom_css'.$variation_id];

            $banner->save();

            global $wpdb;
            //set the variation as having been migrated, to prevent doubles
            //update the banner id in the statistics table
            $wpdb->update($wpdb->prefix . 'cmplz_variations',
                array('title' => 'migrated'),
                array('ID' => $variation_id)
            );

            //update the banner id in the statistics table
            $wpdb->update($wpdb->prefix . 'cmplz_statistics',
                array('cookiebanner_id' => $banner->id),
                array('variation' => $variation_id)
            );

            //update the regions to consenttypes
            $wpdb->update($wpdb->prefix . 'cmplz_statistics',
                array('consenttype' => 'optin'),
                array('region' => 'eu')
            );

            $wpdb->update($wpdb->prefix . 'cmplz_statistics',
                array('consenttype' => 'optout'),
                array('region' => 'us')
            );

            //remove old data
            unset($cookie_settings['position'.$variation_id]);
            unset($cookie_settings['cookie_expiry'.$variation_id]);
            unset($cookie_settings['title'.$variation_id]);
            unset($cookie_settings['theme'.$variation_id]);
            unset($cookie_settings['revoke'.$variation_id]);
            unset($cookie_settings['dismiss'.$variation_id]);
            unset($cookie_settings['save_preferences'.$variation_id]);
            unset($cookie_settings['view_preferences'.$variation_id]);
            unset($cookie_settings['category_functional'.$variation_id]);
            unset($cookie_settings['category_all'.$variation_id]);
            unset($cookie_settings['category_stats'.$variation_id]);
            unset($cookie_settings['accept'.$variation_id]);
            unset($cookie_settings['message'.$variation_id]);
            unset($cookie_settings['readmore'.$variation_id]);
            unset($cookie_settings['use_categories'.$variation_id]);
            unset($cookie_settings['tagmanager_categories'.$variation_id]);
            unset($cookie_settings['hide_revoke'.$variation_id]);
            unset($cookie_settings['dismiss_on_scroll'.$variation_id]);
            unset($cookie_settings['dismiss_on_timeout'.$variation_id]);
            unset($cookie_settings['dismiss_timeout'.$variation_id]);
            unset($cookie_settings['accept_informational'.$variation_id]);
            unset($cookie_settings['message_us'.$variation_id]);
            unset($cookie_settings['readmore_us'.$variation_id]);
            unset($cookie_settings['readmore_privacy'.$variation_id]);
            unset($cookie_settings['popup_background_color'.$variation_id]);
            unset($cookie_settings['popup_text_color'.$variation_id]);
            unset($cookie_settings['button_text_color'.$variation_id]);
            unset($cookie_settings['button_background_color'.$variation_id]);
            unset($cookie_settings['border_color'.$variation_id]);
            unset($cookie_settings['use_custom_cookie_css'.$variation_id]);
            unset($cookie_settings['custom_css'.$variation_id]);

            if (is_array($cookie_settings)) update_option('complianz_options_cookie_settings', $cookie_settings);
        }


    }
} //class closure
