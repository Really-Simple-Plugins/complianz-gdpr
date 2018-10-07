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




        public function get_single_region(){
            $regions = cmplz_get_value('regions', false, 'wizard');

            //only if radio
            if (!is_array($regions) && !empty($regions)) return $regions;

            return CMPLZ_DEFAULT_REGION;
        }



        public function sold_data_12months(){
            if (!$this->sells_personal_data()) return false;

            $cats = cmplz_get_value('data_sold_us');
            error_log(print_r($cats,true));
            foreach($cats as $cat => $value){
                if ($value==1) return true;
            }

            return false;

        }

        public function disclosed_data_12months(){
            $cats = cmplz_get_value('data_disclosed_us');
            error_log(print_r($cats,true));
            foreach($cats as $cat => $value){
                if ($value==1) return true;
            }

            return false;

        }


    }
} //class closure
