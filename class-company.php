<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_company")) {
    class cmplz_company
    {
        private static $_this;


        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

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

        /**
         * Get the default region based on region settings
         *
         * @return string
         */

        public function get_default_region(){
            //check default region
            $company_region_code = $this->get_company_region_code();
            $regions = cmplz_get_regions();

            if (is_array($regions)) {
                $region_code = "";
                foreach($regions as $region_code => $label){
                    //if we have several regions, get the one this company is located in
                    if ($company_region_code === $region_code) return $region_code;
                }

                //if no match was found, return the last region.
                return $region_code;
            }

            //fallback one: company location
            if (!empty($company_region_code)) return $company_region_code;

            //fallback if no array was returned.
            return CMPLZ_DEFAULT_REGION;
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
            if (!empty($cats)) {
                foreach ($cats as $cat => $value) {
                    if ($value == 1) return true;
                }
            }

            return false;

        }

        public function disclosed_data_12months(){
            $cats = cmplz_get_value('data_disclosed_us');
            if (!empty($cats)) {
                foreach ($cats as $cat => $value) {
                    if ($value == 1) return true;
                }
            }

            return false;

        }


    }
} //class closure
