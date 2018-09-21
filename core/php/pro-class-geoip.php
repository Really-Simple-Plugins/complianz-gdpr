<?php
defined('ABSPATH') or die("you do not have acces to this page!");
//https://dev.maxmind.com/geoip/geoip2/geolite2/
require cmplz_path . 'assets/vendor/autoload.php';
use GeoIp2\Database\Reader;

/*
 * Hooked in hooks.php
 *
 *
 * */

if (!class_exists("cmplz_geoip")) {
    class cmplz_geoip
    {
        private static $_this;
        public $reader;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            $this->initialize();

        }

        static function this()
        {
            return self::$_this;
        }

        public function initialize()
        {

            $this->reader = new Reader(cmplz_path . 'assets/geoip2-db/GeoLite2-Country.mmdb');
        }

//        public function ajax_is_eu()
//        {
//            $success = false;
//            $country_code = $this->get_country_code();
//            $eu_countries = COMPLIANZ()->config->eu_countries;
//
//            $is_eu = in_array($country_code, $eu_countries);
//
//            if ($country_code) $success = true;
//
//            $response = json_encode(array('success' => $success, 'is_eu' => $is_eu));
//            header("Content-Type: application/json");
//            echo $response;
//            exit;
//        }

        public function is_eu()
        {
            $country_code = $this->get_country_code();
            $eu_countries = COMPLIANZ()->config->eu_countries;

            return in_array($country_code, $eu_countries);
        }

        public function get_country_code()
        {
            $ip = $this->get_current_ip();
            if (!$ip) return false;

            $country_code = false;

            try {
                $record = $this->reader->country($ip);
                $country_code = $record->country->isoCode;
            } catch (Exception $e) {
                error_log("failed retrieving country");
            }
            return $country_code;
        }

        public function get_current_ip()
        {
            $current_ip = "";
            //localhost debugging
            if (strpos(home_url(), "localhost") !== false) {
                //$current_ip = "128.101.101.101";//US ip
                //$current_ip = "94.214.200.105"; //EU ip
                //$current_ip = "185.86.151.11"; //GB
                $current_ip = "185.69.233.170";
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $current_ip = $_SERVER['REMOTE_ADDR'];
            }

            //sanitize
            if (filter_var($current_ip, FILTER_VALIDATE_IP)) {
                return $current_ip;
            }

            return false;
        }

        public function geoip_enabled(){
            return !defined('cmplz_premium') ? false : cmplz_get_value('use_country');

        }


    }
} //class closure
