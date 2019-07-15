<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_integrations")) {
    class cmplz_integrations
    {
        private static $_this;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;

            add_action('after_setup_theme', array($this, 'maybe_remove_scripts_others'));

            add_filter('cmplz_default_value', array($this, 'set_default'), 20, 2);
            add_action('cmplz_notice_compile_statistics_more_info', array($this, 'compile_statistics_more_info_notice'));
            add_action('cmplz_notice_compile_statistics', array($this, 'compile_statistics_notice'));
            add_action('init', array($this, 'remove_actions'));
            add_action('init', array($this, 'maybe_disable_wordpress_personaldata_storage'));

            //add_action( 'wp_print_styles', array($this, 'remove_jetpack_responsive_video'), 100 );

            $this->integrate();

        }

        static function this()
        {
            return self::$_this;
        }

//        public function remove_jetpack_responsive_video() {
//            if (defined('JETPACK__VERSION')) {
//                wp_dequeue_style('twentysixteen-jetpack');
//            }
//        }

        public function maybe_remove_scripts_others(){
            /*
             * When monsterinsights is active, we remove some actions to integrate fullly
             * */
            if ($this->monsterinsights()) {
                remove_action('wp_head', 'monsterinsights_tracking_script', 6);
                remove_action('cmplz_statistics_script', array(COMPLIANZ()->cookie, 'get_statistics_script'),10);
            }

            /*
             *
             * JetPack breaks the video rendering from Complianz.
             *
             * */

//            if (defined('JETPACK__VERSION')) {
//                remove_action('after_setup_theme', 'jetpack_responsive_videos_init', 99);
//            }
        }


        /*
         * Check if Google Analytics by monsterinsights is active
         *
         * */


        public function monsterinsights(){

            if (class_exists('MonsterInsights_Lite')){
                return true;
            }
            return false;
        }



        public function integrate(){

            if ($this->monsterinsights())
            {
                add_filter('monsterinsights_tracking_analytics_script_attributes', array($this, 'add_monsterinsights_attributes'), 10, 1);
                add_action('cmplz_before_statistics_script', 'monsterinsights_tracking_script', 10, 1);
            }

        }


        /*
         * Add conditional classes to the monsterinsights statistics script
         *
         * */

        public function add_monsterinsights_attributes($attr){
            $classes = COMPLIANZ()->cookie->get_statistics_script_classes();
            $attr['class'] = implode(' ', $classes);
            return $attr;
        }


        public function compile_statistics_notice(){
            if ($this->monsterinsights()){
                cmplz_notice(__("You use Monsterinsights, so the answer to this question should be Google Analytics", 'complianz-gdpr'));
            }
        }


        public function set_default($value, $fieldname){
            if ($fieldname == 'compile_statistics'){
                if ($this->monsterinsights()){
                    return "google-analytics";
                }
            }
            return $value;
        }



        /**
         * Remove stuff which is not necessary anymore
         *
         * */

        public function remove_actions(){
            if ($this->monsterinsights()) {
                remove_action('cmplz_notice_compile_statistics', array(COMPLIANZ()->cookie, 'show_compile_statistics_notice'), 10);
            }

        }

        /**
         * If disabled in the wizard, the consent checkbox is disabled, and personal data is not stored.
         */

        public function maybe_disable_wordpress_personaldata_storage(){

            if (cmplz_get_value('uses_wordpress_comments')==='yes' && cmplz_get_value('block_wordpress_comment_cookies')==='yes'){
                add_filter( 'pre_comment_user_ip', array($this, 'remove_commentsip'));
                remove_action( 'set_comment_cookies', 'wp_set_comment_cookies', 10);
                add_filter('comment_form_default_fields', array($this, 'comment_form_hide_cookies_consent'));
            }

        }

        /**
         * Helper function to disable storing of comments ip
         * @hooked remove_actions
         * @param $comment_author_ip
         * @return string
         */

        public function remove_commentsip( $comment_author_ip ) {
            return '';
        }

        /**
         * Remove the WP consent checkbox for comment fields
         * @param $fields
         * @return mixed
         */


        public function comment_form_hide_cookies_consent( $fields ) {
            unset( $fields['cookies'] );
            return $fields;
        }


        /*
         * If any of the integrated plugins is used, show a notice here.
         *
         *
         * */


        public function compile_statistics_more_info_notice()
        {
            if ($this->monsterinsights()){

                cmplz_notice(__("You use Monsterinsights: if you enable the anonymize ip option, please make sure that you have enabled it in Monsterinsights", 'complianz-gdpr'));

            }
        }

    }
}
