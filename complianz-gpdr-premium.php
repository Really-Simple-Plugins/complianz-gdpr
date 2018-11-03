<?php
/**
 * Plugin Name: Complianz Privacy Suite (GDPR/CaCPA) premium
 * Plugin URI: https://complianz.io/download/complianz-gdpr-premium
 * Description: Plugin to help you make your website GDPR/CaCPa compliant
 * Version: 2.0.0
 * Text Domain: complianz
 * Domain Path: /config/languages
 * Author: Complianz team
 * Author URI: https://complianz.io
 */

/*

    Copyright 2018  Complianz.io  (email : info@complianz.io)
    This product includes GeoLite2 data created by MaxMind, available from
    http://www.maxmind.com.

*/

defined('ABSPATH') or die("you do not have access to this page!");
define('cmplz_premium', true);




if (!class_exists('COMPLIANZ')) {
    class COMPLIANZ
    {
        private static $instance;
        public $cmplz_front_end;
        public $cmplz_admin;

        private function __construct()
        {
        }

        public static function instance()
        {

            if (!isset(self::$instance) && !(self::$instance instanceof COMPLIANZ)) {
                self::$instance = new COMPLIANZ;
                if (self::$instance->is_compatible()) {

                    self::$instance->setup_constants();
                    self::$instance->includes();

                    //pro
                    self::$instance->form = new cmplz_form();

                    //free
                    self::$instance->config = new cmplz_config();
                    self::$instance->integrations = new cmplz_integrations();
                    self::$instance->company = new cmplz_company();
                    if (cmplz_has_region('us')) self::$instance->DNSMPD = new cmplz_DNSMPD();

                    if (is_admin()) {
                        self::$instance->review = new cmplz_review();
                        self::$instance->admin = new cmplz_admin();
                        self::$instance->field = new cmplz_field();
                        self::$instance->wizard = new cmplz_wizard();

                        /* pro instances */
                        self::$instance->comments = new cmplz_comments();
                        self::$instance->processing = new cmplz_processing();
                        self::$instance->dataleak = new cmplz_dataleak();
                        self::$instance->export_settings = new cmplz_export_settings();

                        self::$instance->license = new cmplz_license();
                    }

                    self::$instance->geoip = new cmplz_geoip();
                    self::$instance->cookie = new cmplz_cookie();
                    self::$instance->statistics = new cmplz_statistics();
                    //in the free version, the document() class is loaded instead.
                    self::$instance->document = new cmplz_document_pro();

                    if (cmplz_third_party_cookies_active()) {
                        self::$instance->cookie_blocker = new cmplz_cookie_blocker();
                    }

                    self::$instance->hooks();
                }

            }

            return self::$instance;
        }


        /*
         * Compatiblity checks
         *
         * */

        private function is_compatible()
        {
            return true;
        }

        private function setup_constants()
        {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            $plugin_data = get_plugin_data(__FILE__);

            define('cmplz_url', plugin_dir_url(__FILE__));
            define('cmplz_path', plugin_dir_path(__FILE__));
            define('cmplz_plugin', plugin_basename(__FILE__));
            $debug = (defined('WP_DEBUG') && WP_DEBUG) ? time() : '';
            define('cmplz_version', $plugin_data['Version'] . $debug);

            define('cmplz_plugin_file', __FILE__);
        }

        private function includes()
        {
            require_once(cmplz_path . 'core/php/class-document-core.php');
            require_once(cmplz_path . 'class-document.php');
            require_once(cmplz_path . 'class-form.php');

            /* pro files */
            require_once(cmplz_path . 'pro/class-geoip.php');
            require_once(cmplz_path . 'pro/class-document.php');
            require_once(cmplz_path . 'pro/class-statistics.php');
            require_once(cmplz_path . 'pro/class-document.php');

            if (is_admin()) {
                require_once(cmplz_path . 'class-admin.php');
                require_once(cmplz_path . 'class-review.php');
                require_once(cmplz_path . 'class-field.php');
                require_once(cmplz_path . 'class-wizard.php');
                require_once(cmplz_path . 'callback-notices.php');

                /* pro files */
                require_once(cmplz_path . 'pro/class-processing.php');
                require_once(cmplz_path . 'pro/class-comments.php');
                require_once(cmplz_path . 'pro/class-dataleak.php');
                require_once(cmplz_path . 'pro/class-licensing.php');
                require_once(cmplz_path . 'pro/framework/post-types.php');
                require_once(cmplz_path . 'pro/functions-admin.php');
                require_once(cmplz_path . 'pro/filters-actions.php');
                require_once(cmplz_path . 'pro/class-export.php');
            }

            require_once(cmplz_path . 'cron/cron.php');
            require_once(cmplz_path . 'class-cookie.php');
            require_once(cmplz_path . 'class-company.php');
            require_once(cmplz_path . 'DNSMPD/class-DNSMPD.php');
            require_once(cmplz_path . 'integrations.php');

            require_once(cmplz_path . 'config/class-config.php');
            require_once(cmplz_path . 'core/php/class-cookie-blocker.php');
        }

        private function hooks()
        {
            add_action('init', 'cmplz_init_cookie_blocker');
            add_action('wp_ajax_nopriv_cmplz_user_settings', 'cmplz_ajax_user_settings');
            add_action('wp_ajax_cmplz_user_settings', 'cmplz_ajax_user_settings');

        }
    }
}


if (!function_exists('COMPLIANZ')) {
    function COMPLIANZ()
    {
        return COMPLIANZ::instance();
    }

    add_action('plugins_loaded', 'COMPLIANZ', 9);
}


require_once(plugin_dir_path(__FILE__) . 'functions.php');
