<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_company")) {
    class cmplz_company
    {
        private static $_this;


        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

        }


        static function this()
        {
            return self::$_this;
        }

        public function sells_personal_data()
        {
            $purposes = cmplz_get_value('purpose_personaldata');
            if (isset($purposes['selling-data-thirdparty']) && $purposes['selling-data-thirdparty']) {
                return true;
            }
            return false;
        }

        public function get_default_region(){
            $company_region_code = $this->get_company_region_code();
            $default_region = cmplz_has_region($company_region_code) ? $company_region_code : false;
            return $default_region ? $default_region : CMPLZ_DEFAULT_REGION;
        }

        /*
         * Get the company region code. The EU is a region, as is the US
         *
         *
         * */

        public function get_company_region_code()
        {
            $country_code = cmplz_get_value('country_company');
            $region = cmplz_get_region_for_country($country_code);
            if ($region) return $region;

            return CMPLZ_DEFAULT_REGION;
        }



        public function sold_data_12months(){
            if (!$this->sells_personal_data()) return false;

            $cats = cmplz_get_value('data_sold_us');
            foreach($cats as $cat => $value){
                if ($value==1) return true;
            }

            return false;

        }

        public function disclosed_data_12months(){
            $cats = cmplz_get_value('data_disclosed_us');
            foreach($cats as $cat => $value){
                if ($value==1) return true;
            }

            return false;

        }


    }
} //class closure
