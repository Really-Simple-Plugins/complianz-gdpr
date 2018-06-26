<?php
/*100% match*/
defined('ABSPATH') or die("you do not have access to this page!");

if ( ! class_exists( 'cmplz_cookie_blocker' ) ) {
    class cmplz_cookie_blocker
    {
        private static $_this;
        public $script_tags = array();
        public $script_async_tags = array();
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
           // if (!cmplz_cookie_warning_required()) return;

            /* Do not fix mixed content when call is coming from wp_api or from xmlrpc or feed */
            if (defined('JSON_REQUEST') && JSON_REQUEST) return;
            if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) return;

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

            libxml_use_internal_errors(true);
            $doc = new DOMDocument();
            $doc->encoding = 'utf-8';
            $doc->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
            // get all the script tags
            $script_tags = $doc->getElementsByTagName('script');

            foreach ($script_tags as $script):
                $src_script = $script->getAttribute('src');
                if ($src_script):
                    if ($this->strpos_arr($src_script, $known_script_tags) !== false):
                        $script = apply_filters('cmplz_set_class', $script);
//                        $script->setAttribute("class", "cmplz-script");
                        $script->setAttribute("type", "text/plain");
                        continue;
                    endif;
                endif;
                if ($script->nodeValue):
                    $key = $this->strpos_arr($script->nodeValue, $known_script_tags);
                    //if it's google analytics, and it's not anonymous or from complianz, remove it.
                    if ($known_script_tags[$key] == 'www.google-analytics.com/analytics.js' || $known_script_tags[$key] == 'google-analytics.com/ga.js'){
                        if (strpos($script->nodeValue, 'anonymizeIp') !== FALSE) continue;
                        $class = $script->getAttribute('class');
                        if (strpos($class,'cmplz-native')!==FALSE) continue;

                        $script->remove();
                    } elseif ($key !== false) {
                        $script = apply_filters('cmplz_set_class', $script);
                        //$script->setAttribute("class", "cmplz-script");
                        $script->setAttribute("type", "text/plain");
                    }
                endif;
            endforeach;
            // get all the iframe tags
            $iframe_tags = $doc->getElementsByTagName('iframe');
            foreach ($iframe_tags as $iframe):
                $src_iframe = $iframe->getAttribute('src');
                if ($src_iframe):
                    if ($this->strpos_arr($src_iframe, $known_iframe_tags) !== false):
                        $iframe = apply_filters('cmplz_third_party_iframe', $iframe);
//                        $iframe->setAttribute("data-src-cmplz", $src_iframe);
                        $iframe->removeAttribute('src');
                        $addclass = ($iframe->hasAttribute('class')) ? $iframe->getAttribute('class') : "";
                        $iframe->setAttribute("class", "cmplz-iframe " . $addclass);
                    endif;
                endif;
            endforeach;

            // get the HTML string back
            $output = $doc->saveHTML();
            libxml_use_internal_errors(false);

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
    }
}



