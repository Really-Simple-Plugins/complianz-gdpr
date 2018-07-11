<?php
/*100% match*/
defined('ABSPATH') or die("you do not have access to this page!");

if ( ! class_exists( 'cmplz_cookie_blocker' ) ) {
    class cmplz_cookie_blocker
    {
        private static $_this;
        public $script_tags = array();
        public $iframe_tags = array();

        function __construct()
        {

            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'really-simple-ssl'), get_class($this)));

            self::$_this = $this;

            if (!is_admin()) {
                $this->remove_cookie_scripts();
            }
        }

        static function this()
        {
            return self::$_this;

        }

        /**
         *
         * add action hooks at the start and at the end of the WP process.
         *
         * @since  1.0
         *
         * @access public
         *
         */

        public function remove_cookie_scripts()
        {

            if (defined('CMPLZ_DO_NOT_BLOCK') && CMPLZ_DO_NOT_BLOCK) return;

            if (cmplz_get_value('disable_cookie_block')) return;

            /* Do not fix mixed content when call is coming from wp_api or from xmlrpc or feed */
            if (defined('JSON_REQUEST') && JSON_REQUEST) return;
            if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) return;

            //do not block cookies during the scan
            if (isset($_GET['complianz_scan_token']) && (sanitize_title($_GET['complianz_scan_token']) == get_option('complianz_scan_token'))) return;

            add_action("template_redirect", array($this, "start_buffer"));
            add_action("shutdown", array($this, "end_buffer"), 999);

        }


        /**
         * Apply the mixed content fixer.
         *
         * @since  1.0
         *
         * @access public
         *
         */

        public function filter_buffer($buffer)
        {
            $buffer = $this->replace_tags($buffer);
            return $buffer;
        }

        /**
         * Start buffering the output
         *
         * @since  1.0
         *
         * @access public
         *
         */

        public function start_buffer()
        {
            ob_start(array($this, "filter_buffer"));
        }

        /**
         * Flush the output buffer
         *
         * @since  1.0
         *
         * @access public
         *
         */

        public function end_buffer()
        {
            if (ob_get_length()) ob_end_flush();
        }

        /**
         * Just before the page is sent to the visitor's browser, remove all tracked third party scripts
         *
         * @since  1.0
         *
         * @access public
         *
         */

        public function replace_tags($output)
        {
            if (strpos($output, '<html') === false):
                return $output;
            elseif (strpos($output, '<html') > 200):
                return $output;
            endif;

            $known_script_tags = apply_filters('cmplz_script_tags',COMPLIANZ()->config->script_tags);
            $known_iframe_tags = apply_filters('cmplz_iframe_tags',COMPLIANZ()->config->iframe_tags);

            $url_pattern = '([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-]?)';

            /*
             * Handle iframes from third parties
             *
             *
             * */

            $iframe_pattern = '/<(iframe)[^>].*?src=[\'"](http:\/\/|https:\/\/|\/\/)'.$url_pattern.'[\'"].*?>/i';
            if (preg_match_all($iframe_pattern, $output, $matches, PREG_PATTERN_ORDER)) {
                foreach($matches[2] as $key => $match){
                    $total_match = $matches[0][$key];
                    $iframe_src = $matches[2][$key].$matches[3][$key];
                    if (strpos($iframe_src, 'youtube.com/embed/')!==false){
                        $output = str_replace('youtube.com/embed/', 'youtube-nocookie.com/embed/', $output);
                    } elseif ($this->strpos_arr($iframe_src, $known_iframe_tags) !== false) {
                        $new = $total_match;
                        $new = apply_filters('cmplz_third_party_iframe', $new, $iframe_src);
                        $new = $this->remove_src($new);
                        $new = $this->add_class($new, 'iframe', 'cmplz-iframe');
                        $output = str_replace($total_match, $new, $output);
                    }
                }
            }

            /*
             * Handle scripts from third parties
             *
             *
             * */

            $script_pattern = '/(<script.*?>)(\X*?)<\/script>/i';
            if (preg_match_all($script_pattern, $output, $matches, PREG_PATTERN_ORDER)) {
                foreach($matches[1] as $key => $script_open){
                    if (strpos($script_open,'cmplz-native')!==FALSE) continue;
                    $total_match = $matches[0][$key];
                    $content = $matches[2][$key];
                    if (!empty($content)) {
                        $key = $this->strpos_arr($content, $known_script_tags);
                        //if it's google analytics, and it's not anonymous or from complianz, remove it.
                        if ($known_script_tags[$key] == 'www.google-analytics.com/analytics.js' || $known_script_tags[$key] == 'google-analytics.com/ga.js') {
                            if (strpos($content, 'anonymizeIp') !== FALSE) continue;
                        }
                        if ($key !== false) {
                            $new = $total_match;
                            $new = apply_filters('cmplz_set_class', $new);
                            $new = $this->set_javascript_to_plain($new);
                            $output = str_replace($total_match, $new, $output);
                        }
                    }

                    //when script contains src
                    $script_src_pattern = '/<script [^>]*?src=[\'"](http:\/\/|https:\/\/|\/\/)'.$url_pattern.'[\'"].*?>/i';
                    if (preg_match_all($script_src_pattern, $total_match, $src_matches, PREG_PATTERN_ORDER)) {

                        foreach ($src_matches[2] as $src_key => $script_src) {

                            $script_src = $src_matches[1][$src_key].$src_matches[2][$src_key];
                            if ($this->strpos_arr($script_src, $known_script_tags) !== false){
                                $new = $total_match;
                                $new = apply_filters('cmplz_set_class', $new);
                                $new = $this->set_javascript_to_plain($new);

                                $output = str_replace($total_match, $new, $output);
                            }
                        }
                    }
                }
            }

            $output = str_replace("<body ", '<body data-cmplz=1 ', $output);
            return apply_filters("cmplz_cookie_blocker_output", $output);
        }


        public function strpos_arr($haystack, $needle)
        {
            if (!is_array($needle)) $needle = array($needle);
            foreach ($needle as $key => $what) {
                if (($pos = strpos($haystack, $what)) !== false) return $key;
            }
            return false;
        }

        public function set_javascript_to_plain($script){
            $pattern = '/type=[\'|\"]text\/javascript[\'|\"]/i';
            $script = preg_replace($pattern, 'type="text/plain"', $script);
            return $script;
        }

        public function remove_src($script){
            $pattern = '/src=[\'"](http:\/\/|https:\/\/)([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-]?)[\'"]/i';
            $script = preg_replace($pattern, '', $script);
            return $script;
        }

        public function add_class($html, $el, $class){
            if (strpos($html,'class' )===false){
                $html = str_replace("<$el", '<'.$el.' class="'.$class.'"', $html);
            }
            return $html;
        }
    }
}



