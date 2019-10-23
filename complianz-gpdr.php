<?php
/**
 * Plugin Name: Complianz | GDPR Cookie Consent
 * Plugin URI: https://www.wordpress.org/plugins/complianz-gdpr
 * Description: Complianz Privacy Suite for GDPR, CaCPA, DSVGO, AVG with a conditional cookie warning and customized cookie policy
 * Version: 3.2.3
 * Text Domain: complianz-gdpr
 * Domain Path: /languages
 * Author: RogierLankhorst, complianz
 * Author URI: https://www.complianz.io
 */

/*
    Copyright 2018  Complianz BV  (email : support@complianz.io)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

defined('ABSPATH') or die("you do not have access to this page!");

define('cmplz_free', true);

/**
 * Checks if the plugin can safely be activated, at least php 5.6 and wp 4.6
 * @since 2.1.5
 */
if (!function_exists('cmplz_activation_check')) {
    function cmplz_activation_check()
    {
        if (version_compare(PHP_VERSION, '5.6', '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(__('Complianz GDPR cannot be activated. The plugin requires PHP 5.6 or higher', 'complianz-gdpr'));
        }

        global $wp_version;
        if (version_compare($wp_version, '4.6', '<')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(__('Complianz GDPR cannot be activated. The plugin requires WordPress 4.6 or higher', 'complianz-gdpr'));
        }
    }
}
register_activation_hook( __FILE__, 'cmplz_activation_check' );


require_once(plugin_dir_path(__FILE__) . 'functions.php');
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

                    self::$instance->config = new cmplz_config();
                    self::$instance->company = new cmplz_company();
                    if (cmplz_has_region('us')) self::$instance->DNSMPD = new cmplz_DNSMPD();

                    if (is_admin()) {
                        self::$instance->review = new cmplz_review();
                        self::$instance->admin = new cmplz_admin();
                        self::$instance->field = new cmplz_field();
                        self::$instance->wizard = new cmplz_wizard();
                        self::$instance->export_settings = new cmplz_export_settings();

                    }

                    self::$instance->geoip = '';
                    self::$instance->cookie = new cmplz_cookie();

                    self::$instance->document = new cmplz_document();


                    if (cmplz_third_party_cookies_active() || cmplz_cookie_warning_required_stats()) {
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
            define('CMPLZ_MINUTES_PER_QUESTION', 0.33);
            define('CMPLZ_MINUTES_PER_QUESTION_QUICK', 0.1);
            define('CMPLZ_MAIN_MENU_POSITION', 40);
            define('CMPLZ_PROCESSING_MENU_POSITION', 41);
            define('CMPLZ_DATALEAK_MENU_POSITION', 42);

            //default region code
            if (!defined('CMPLZ_DEFAULT_REGION')) define('CMPLZ_DEFAULT_REGION',  'us');

            /*
             * The legal version is only updated when document contents or the questions leading to it are changed
             * 1: start version
             * 2: introduction of US privacy questions
             * 3: new questions
             * 4: new questions
             * 5: UK as seperate region
             * */
            define('CMPLZ_LEGAL_VERSION', '4');

            /*statistics*/
            if (!defined('CMPLZ_AB_TESTING_DURATION')) define('CMPLZ_AB_TESTING_DURATION', 30); //Days

            define('STEP_COMPANY', 1);
            define('STEP_PLUGINS', 2);
            define('STEP_COOKIES', 2);
            define('STEP_MENU',    3);
            define('STEP_FINISH',  4);

            define('cmplz_url', plugin_dir_url(__FILE__));
            define('cmplz_path', plugin_dir_path(__FILE__));
            define('cmplz_plugin', plugin_basename(__FILE__));
            $debug = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? time() : '';
            define('cmplz_version', $plugin_data['Version'] . $debug);
            define('cmplz_plugin_file', __FILE__);
        }

        private function includes()
        {

            require_once(cmplz_path . 'core/php/class-document-core.php');
            require_once(cmplz_path . 'class-document.php');
            require_once(cmplz_path . 'integrations/integrations.php');

            /* Gutenberg block */
            if (cmplz_uses_gutenberg()) {
                require_once plugin_dir_path(__FILE__) . 'src/block.php';
            }
            require_once plugin_dir_path( __FILE__ ) . 'rest-api/rest-api.php';


            if (is_admin()) {
                require_once(cmplz_path . 'class-admin.php');
                require_once(cmplz_path . 'class-review.php');
                require_once(cmplz_path . 'class-field.php');
                require_once(cmplz_path . 'class-wizard.php');
                require_once(cmplz_path . 'callback-notices.php');
                require_once(cmplz_path . 'cookiebanner/cookiebanner.php');
                require_once(cmplz_path . 'class-export.php');
            }

            require_once(cmplz_path . 'cron/cron.php');
            require_once(cmplz_path . 'class-cookie.php');
            require_once(cmplz_path . 'class-company.php');
            require_once(cmplz_path . 'DNSMPD/class-DNSMPD.php');
            require_once(cmplz_path . 'cookiebanner/class-cookiebanner.php');


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
    function COMPLIANZ() {
        return COMPLIANZ::instance();
    }

    add_action( 'plugins_loaded', 'COMPLIANZ', 9 );
}

register_activation_hook( __FILE__, 'cmplz_set_activation_time_stamp');
if (!function_exists('cmplz_set_activation_time_stamp')) {
    function cmplz_set_activation_time_stamp($networkwide)
    {
        update_option('cmplz_activation_time', time());
    }
}

/**
 * Load the translation files
 * For the free this is different from the premium, as we only want to load languages that are not on the repository
 *
 */

if (!function_exists('cmplz_load_translation')) {
    add_action('init', 'cmplz_load_translation', 20);
    function cmplz_load_translation()
    {
        load_plugin_textdomain('complianz-gdpr', FALSE, cmplz_path . '/config/languages/');
    }
}