<?php
/**
 * Plugin Name: Complianz GDPR
 * Plugin URI: https://www.complianz.io/complianz-gdpr
 * Description: Plugin to help you make your site GDPR compliant
 * Version: 1.1.5
 * Text Domain: complianz
 * Domain Path: /languages
 * Author: RogierLankhorst, Complianz team
 * Author URI: https://www.complianz.io
 */

/*
    Copyright 2018  Complianz BV  (email : info@complianz.io)

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
                    if (is_admin()) {
                        self::$instance->admin = new cmplz_admin();
                        self::$instance->field = new cmplz_field();
                        self::$instance->wizard = new cmplz_wizard();
                    }

                    self::$instance->geoip = '';
                    self::$instance->cookie = new cmplz_cookie();
                    self::$instance->document = new cmplz_document();

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
            if (is_admin()) {
                require_once(cmplz_path . 'class-admin.php');
                require_once(cmplz_path . 'class-field.php');
                require_once(cmplz_path . 'class-wizard.php');
                require_once(cmplz_path . 'callback-notices.php');
            }

            require_once(cmplz_path . 'class-cookie.php');
            require_once(cmplz_path . 'config/class-config.php');
            require_once(cmplz_path . 'class-document.php');
            require_once(cmplz_path . 'functions.php');
            require_once(cmplz_path . 'class-cookie-blocker.php');
        }

        private function hooks()
        {

        }
    }
}


if (!defined('cmplz_premium')) {
    function COMPLIANZ() {
        return COMPLIANZ::instance();
    }

    add_action( 'plugins_loaded', 'COMPLIANZ', 9 );
}
