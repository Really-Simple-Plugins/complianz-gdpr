<?php

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_document")) {
    class cmplz_document extends cmplz_document_core
    {
        private static $_this;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;

            $this->init();

        }

        static function this()
        {

            return self::$_this;
        }

        public function enqueue_assets()
        {

            if ($this->is_complianz_page()) {
                $min = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
                $load_css = cmplz_get_value('use_document_css');
                if ($load_css) {
                    wp_register_style('cmplz-document', cmplz_url . "core/assets/css/document$min.css", false, cmplz_version);
                    wp_enqueue_style('cmplz-document');
                }

                add_action('wp_head', array($this, 'inline_styles'), 100);
            }

        }

        /**
         * Get custom CSS for documents
         *
         * */

        public function inline_styles(){

            //basic color style for revoke button
            $custom_css='';
            $background_color = sanitize_hex_color(cmplz_get_value('brand_color'));
            if (!empty($background_color) ){
                $light_background_color = $this->color_luminance($background_color, -0.2);
                $custom_css = "#cmplz-document button.cc-revoke-custom {background-color:".$background_color.";border-color: ".$background_color.";}";
                $custom_css .= "#cmplz-document button.cc-revoke-custom:hover {background-color: ".$light_background_color.";border-color: ".$light_background_color.";}";
            }

            if (cmplz_get_value('use_custom_document_css')) {
                $custom_css .= cmplz_get_value('custom_document_css');
            }

            $custom_css = apply_filters('cmplz_custom_document_css', $custom_css);
            if (empty($custom_css)) return;

            echo '<style>' . $custom_css . '</style>';
        }

        /**
         * Add some text to the privacy policy suggested texts in free.
         */

        public function add_privacy_info()
        {
            if (!function_exists('wp_add_privacy_policy_content')) {
                return;
            }

            //only necessary for free, as premium will generate the privacy policy
            if (!defined('cmplz_free')) return;

            $content = sprintf(
                __("Complianz GDPR Cookie Consent does not process any personally identifiable information, which means there's no need to add text about this plugin to your privacy policy. The used cookies (all functional) will be automatically added to your cookie policy. You can find our privacy policy %shere%s.", 'complianz-gdpr'),
                '<a href="https://complianz.io/privacy-statement/" target="_blank">', '</a>'
            );

            wp_add_privacy_policy_content(
                'Complianz | GDPR Cookie Consent',
                wp_kses_post(wpautop($content, false))
            );
        }


        /*
          * Get the region for a post id, based on the post type.
          *
          * */

        public function get_region($post_id = false){

            if ($post_id) {
                $term = wp_get_post_terms($post_id,'cmplz-region');
                if (is_wp_error($term)) return false;

                if (isset($term[0])) return $term[0]->slug;

                return false;
            }

            $regions = cmplz_get_regions();

            if (isset($_GET['page'])){
                $page = sanitize_title($_GET['page']);
                foreach($regions as $region => $label){
                    if (strpos($page, '-'.$region)!==false){
                        return $region;
                    }
                }
            }

            return false;
        }


        public function set_region($post_id, $region=false){
            if (!$region) $region = $this->get_region();

            $term = get_term_by('slug', $region,'cmplz-region');
            if (!$term) {
                wp_insert_term(COMPLIANZ()->config->regions[$region]['label'], 'cmplz-region',array(
                    'slug' => $region,
                ));
                $term = get_term_by('slug', $region,'cmplz-region');
            }

            if (empty($term)) return;

            $term_id = $term->term_id;

            wp_set_object_terms( $post_id, array($term_id), 'cmplz-region' );
        }


        /*
         * Check if legal documents should be updated
         *
         *
         * */

        public function documents_need_updating(){
            if (cmplz_has_region('us') && $this->not_updated_in(MONTH_IN_SECONDS*12)){
                return true;
            }
            return false;
        }

        /*
         * Check if legal documents should be updated, and send mail to admin if so
         *
         *
         * */

        public function cron_check_last_updated_status(){

            if ($this->documents_need_updating() && !get_option('cmplz_update_legal_documents_mail_sent')){
                update_option('cmplz_update_legal_documents_mail_sent', true);
                $to = get_option('admin_email');

                $headers = array();
                if (empty($subject)) $subject = sprintf(_x('Your legal documents on %s need to be updated.','Subject in notification email', 'complianz-gdpr'), home_url());

                $message = sprintf(_x('Your legal documents on %s have not been updated in 12 months. Please log in and run the wizard to check if everything is up to date.', 'Email message in notification email', 'complianz-gdpr'), home_url());

                add_filter('wp_mail_content_type', function ($content_type) {
                    return 'text/html';
                });

                wp_mail($to, $subject, $message, $headers);

                // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
                remove_filter('wp_mail_content_type', 'set_html_content_type');
            }
        }


        public function revoke_link($atts = [], $content = null, $tag = '')
        {

            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['text' => false,], $atts, $tag);

            echo cmplz_revoke_link($atts['text']);

            return ob_get_clean();
        }

        /**
         * Display an accept hyperlink
         *
         * @param array $atts
         * @param null $content
         * @param string $tag
         * @return string
         */

        public function accept_link($atts = [], $content = null, $tag = '')
        {

            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['text' => false,], $atts, $tag);

            $accept_text = $atts['text'] ? $atts['text'] : apply_filters('cmplz_accept_cookies_blocked_content',cmplz_get_value('blocked_content_text'));
            $html = '<div class="cmplz-blocked-content-notice cmplz-accept-cookies"><a href="#">'.$accept_text.'</a></div>';
            echo $html;

            return ob_get_clean();

        }

        /*
         * This class is extended with pro functions, so init is called also from the pro extension.
         * */

        public function init()
        {
            //this shortcode is also available as gutenberg block
            add_shortcode('cmplz-document', array($this, 'load_document'));

            /*
             * @todo add a gutenberg block for the revoke link and DNSMPD form
             *
             * These shortcodes are setup in a disconnected way, so this shortcode is not necessary: it's only used for user customizations
             * The gutenberg blocks will be calling the endpoint functions directly, in which case these shortcodes will become deprecated.
             *
             * */

            add_shortcode('cmplz-revoke-link', array($this, 'revoke_link'));
            add_shortcode('cmplz-accept-link', array($this, 'accept_link'));
            add_shortcode('cmplz-do-not-sell-personal-data-form', array($this, 'do_not_sell_personal_data_form'));

            //clear shortcode transients after post update
            add_action('save_post', array($this, 'clear_shortcode_transients'), 10, 1);
            add_action('cmplz_wizard_add_pages_to_menu', array($this, 'wizard_add_pages_to_menu'), 10, 1);
            add_action('admin_init', array($this, 'assign_documents_to_menu'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));

            add_action('admin_init', array($this, 'add_privacy_info'));


            add_filter('cmplz_document_email', array($this, 'obfuscate_email'));

            add_filter( 'body_class', array($this, 'add_body_class_for_complianz_documents') );

        }


        /**
         * add a class to the body telling the page it's a complianz doc. We use this for the soft cookie wall
         * @param $classes
         * @return array
         */
        public function add_body_class_for_complianz_documents($classes){
            global $post;
            if ($post && $this->is_complianz_page($post->ID)) {
                $classes[] = 'cmplz-document';
            }
            return $classes;
        }

        /**
         * obfuscate the email address
         * @param $email
         * @return string
         */

        public function obfuscate_email($email)
        {
            $alwaysEncode = array('.', ':', '@');

            $result = '';

            // Encode string using oct and hex character codes
            for ($i = 0; $i < strlen($email); $i++) {
                // Encode 25% of characters including several that always should be encoded
                if (in_array($email[$i], $alwaysEncode) || mt_rand(1, 100) < 25) {
                    if (mt_rand(0, 1)) {
                        $result .= '&#' . ord($email[$i]) . ';';
                    } else {
                        $result .= '&#x' . dechex(ord($email[$i])) . ';';
                    }
                } else {
                    $result .= $email[$i];
                }
            }
            return $result;
        }


        /**
         * Render shortcode for DNSMPI form
         *
         * @hooked shortcode hook
         * @param array $atts
         * @param null $content
         * @param string $tag
         * @return false|string
         * @since 2.0
         */

        public function do_not_sell_personal_data_form($atts = [], $content = null, $tag = '')
        {

            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['text' => false,], $atts, $tag);

            echo cmplz_do_not_sell_personal_data_form();

            return ob_get_clean();

        }

        /**
         *
         * Show form to enable user to add pages to a menu
         *
         * @hooked field callback wizard_add_pages_to_menu
         * @since 1.0
         *
         */

        public function wizard_add_pages_to_menu()
        {

            //this function is used as of 4.9.0
            if (!function_exists('wp_get_nav_menu_name')) {
                cmplz_notice(__('Your WordPress version does not support the functions needed for this step. You can upgrade to the latest WordPress version, or add the pages manually to a menu.', 'complianz-gdpr'),'warning');
                return;
            }

            //get list of menus

            $menus = wp_list_pluck( wp_get_nav_menus(), 'name', 'term_id');

            $link = '<a href="' . admin_url('nav-menus.php') . '">';
            if (empty($menus)) {
                cmplz_notice(sprintf(__("No menus were found. Skip this step, or %screate a menu%s first."), $link, '</a>'));
                return;
            }

            $pages_not_in_menu = $this->pages_not_in_menu();
            if ($pages_not_in_menu) {
                if (COMPLIANZ()->company->sells_personal_data()){
                    cmplz_notice(sprintf(__('You sell personal data from your customers. This means you are required to put the "%s" page clearly visible on your homepage.', 'complianz-gdpr'), cmplz_us_cookie_statement_title()));
                }

                $docs = array_map('get_the_title', $pages_not_in_menu);
                $docs = implode(", ", $docs);
                cmplz_notice(sprintf(esc_html(_n('The generated document %s has not been assigned to a menu yet, you can do this now, or skip this step and do it later.',
                    'The generated documents %s have not been assigned to a menu yet, you can do this now, or skip this step and do it later.', count($pages_not_in_menu), 'complianz-gdpr')), $docs), 'warning');
            } else {
                cmplz_notice(__("Great! All your generated documents have been assigned to a menu, so you can skip this step.", 'copmlianz'), 'warning');
            }


            $pages = $this->get_created_pages();
            echo '<table>';
            foreach ($pages as $page_id) {
                echo "<tr><td>";
                echo get_the_title($page_id);
                echo "</td><td>";
                ?>

                <select name="cmplz_assigned_menu[<?php echo $page_id ?>]">
                    <option value=""><?php _e("Select a menu", 'complianz-gdpr'); ?></option>
                    <?php foreach ($menus as $menu_id => $menu) {
                        $selected = ($this->is_assigned_this_menu($page_id, $menu_id)) ? "selected" : "";
                        echo '<option ' . $selected . ' value="' . $menu_id . '">' . $menu . '</option>';
                    } ?>

                </select>
                <?php
                echo "</td></tr>";
            }
            echo "</table>";

        }

        /**
         * Handle the submit of a form which assigns documents to a menu
         *
         * @hooked admin_init
         *
         */

        public function assign_documents_to_menu()
        {
            if (!current_user_can('manage_options')) return;

            if (isset($_POST['cmplz_assigned_menu'])) {
                foreach ($_POST['cmplz_assigned_menu'] as $page_id => $menu_id) {
                    if (empty($menu_id)) continue;
                    if ($this->is_assigned_this_menu($page_id, $menu_id)) continue;

                    $page = get_post($page_id);

                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => get_the_title($page),
                        'menu-item-object-id' => $page->ID,
                        'menu-item-object' => get_post_type($page),
                        'menu-item-status' => 'publish',
                        'menu-item-type' => 'post_type',
                    ));
                }
            }
        }


        /**
         * Get all pages that are not assigned to any menu
         * @return array|bool
         * @since 1.2
         *
         * */

        public function pages_not_in_menu()
        {
            //search in menus for the current post
            $menus = wp_list_pluck( wp_get_nav_menus(), 'name', 'term_id');
            $pages = $this->get_created_pages();
            $pages_in_menu = array();

            foreach ($menus as $menu_id => $title) {

                $menu_items = wp_get_nav_menu_items($menu_id);
                foreach ($menu_items as $post) {
                    if (in_array($post->object_id, $pages)) {
                        $pages_in_menu[] = $post->object_id;
                    }
                }

            }
            $pages_not_in_menu = array_diff($pages, $pages_in_menu);
            if (count($pages_not_in_menu) == 0) return false;

            return $pages_not_in_menu;
        }


        /**
         *
         * Check if a page is assigned to a menu
         * @since 1.2
         * @param int $page_id
         * @param int $menu_id
         * @return bool
         *
         */

        public function is_assigned_this_menu($page_id, $menu_id)
        {
            $menu_items = wp_list_pluck(wp_get_nav_menu_items($menu_id),'object_id');
            return (in_array($page_id, $menu_items));

        }

        /**
         * Create a page of certain type in wordpress
         * @since 1.0
         * @param $type
         * @return int page_id
         */

        public function create_page($type)
        {
            if (!current_user_can('manage_options')) return;

            $pages = COMPLIANZ()->config->pages;

            if (!isset($pages[$type])) return false;

            //only insert if there is no shortcode page of this type yet.
            $page_id = $this->get_shortcode_page_id($type);
            if (!$page_id) {

                $page = $pages[$type];


                $page = array(
                    'post_title' => $page['title'],
                    'post_type' => "page",
                    'post_content' => $this->get_shortcode($type),
                    'post_status' => 'publish',
                );

                // Insert the post into the database
                $page_id = wp_insert_post($page);
            }

            do_action('cmplz_create_page', $page_id, $type);

            return $page_id;

        }

        /**
         * Delete a page of a type
         * @param $type string
         *
         */

        public function delete_page($type)
        {
            if (!current_user_can('manage_options')) return;


            $page_id = $this->get_shortcode_page_id($type);
            if ($page_id) {
                wp_delete_post($page_id, false);
            }
        }


        /**
         *
         * Check if page of certain type exists
         * @param $type string
         * @return bool
         *
         */

        public function page_exists($type)
        {
            if ($this->get_shortcode_page_id($type)) return true;

            return false;
        }


        /**
         *
         * get the shortcode or block for a page type
         *
         * @param string $type
         * @param bool $force_classic
         * @return string $shortcode
         *
         *
         */


        public function get_shortcode($type, $force_classic=false)
        {
            //even if on gutenberg, with elementor we have to use classic shortcodes.
            if (!$force_classic && cmplz_uses_gutenberg() && !$this->uses_elementor()){
                $page = COMPLIANZ()->config->pages[$type];
                return '<!-- wp:complianz/document {"title":"'.$page['title'].'","selectedDocument":"'.$type.'"} /-->';
            } else {
                return '[cmplz-document type="' . $type . '"]';
            }
        }

        /**
         * Check if this site uses Elementor
         * When Elementor is used, the classic shortcode should be used, even when on Gutenberg
         *
         * @return bool $uses_elementor
         */

        public function uses_elementor(){
            if (defined('ELEMENTOR_VERSION')) return true;

            return false;
        }


        /**
         *
         * Get type of document
         * @param int $post_id
         * @return bool
         *
         *
         */

        public function get_document_type($post_id){

            $pattern = '/cmplz-document type="(.*?)"/i';
            $post = get_post($post_id);

            $content = $post->post_content;
            if (preg_match_all($pattern, $content, $matches, PREG_PATTERN_ORDER)) {
                if (isset($matches[1][0])) return $matches[1][0];
            }

            return false;
        }

        /**
         * Get list of all created pages for current setup
         *
         * @return array $pages
         *
         *
         */


        public function get_created_pages()
        {
            $required_pages = COMPLIANZ()->config->pages;
            $pages = array();

            foreach ($required_pages as $type => $page) {
                if (!$page['public']) continue;

                if ($this->page_required($page)) {
                    $pages[] = $this->get_shortcode_page_id($type);
                }
            }
            return $pages;
        }



        /**
         * Get list of all required pages for current setup
         *
         * @return array $pages
         *
         *
         */

        public function get_required_pages()
        {
            //create a page foreach page that is needed.
            $pages = COMPLIANZ()->config->pages;
            $required = array();
            foreach ($pages as $type => $page) {
                if (!$page['public']) continue;
                if ($this->page_required($page)) {
                    $required[$type] = $page;
                }
            }
            return $required;
        }


        /**
         * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
         * @param string $hex Colour as hexadecimal (with or without hash);
         * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
         * @return string Lightened/Darkend colour as hexadecimal (with hash);
         */

        function color_luminance( $hex, $percent ) {
            if (empty($hex)) return $hex;
            // validate hex string
            $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
            $new_hex = '#';

            if ( strlen( $hex ) < 6 ) {
                $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
            }

            // convert to decimal and change luminosity
            for ($i = 0; $i < 3; $i++) {
                $dec = hexdec( substr( $hex, $i*2, 2 ) );
                $dec = min( max( 0, $dec + $dec * $percent ), 255 );
                $new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
            }

            return $new_hex;
        }

        /**
         * Verify if $type is an existing document type
         * @param string $type
         * @return bool
         */

        public function sanitize_document_type($type){
            $types = array_keys(COMPLIANZ()->config->document_elements);
            if (in_array($type, $types)) return $type;

            return false;
        }


        /**
         * loads document content on shortcode call
         *
         * @param array $atts
         * @param null $content
         * @param string $tag
         * @return string $html
         *
         *
         */

        public function load_document($atts = [], $content = null, $tag = '')
        {
            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);



            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['type' => false,], $atts, $tag);
            $type = $this->sanitize_document_type($atts['type']);

            if ($type) {
                $html = $this->get_document_html($type);
                $allowed_html = cmplz_allowed_html();
                echo wp_kses($html, $allowed_html);
            }

            return ob_get_clean();
        }

        private function use_cache($type)
        {

            //do not cache on multilanguage environments
            if (function_exists('pll__') || function_exists('icl_translate')) {
                return false;
            }

            if (defined('WP_DEBUG') && WP_DEBUG) return false;

            //do not cache for these types
            if (($type === 'processing') || ($type === 'dataleak')) return false;

            return true;

        }


        /**
         * checks if the current page contains the shortcode.
         * @param int|bool $post_id
         * @return boolean
         * @since 1.0
         */

        public function is_complianz_page($post_id = false)
        {
            $shortcode = 'cmplz-document';
            $block = 'complianz/document';

            if ($post_id){
                $post = get_post($post_id);
            } else {
                global $post;
            }

            if ($post) {
                if (cmplz_uses_gutenberg() && has_block($block, $post)) return true;
                if (has_shortcode($post->post_content, $shortcode)) return true;
            }
            return false;
        }

        /**
         * gets the  page that contains the shortcode or the gutenberg block
         * @param string $type
         * @return int $page_id
         * @since 1.0
         */

        public function get_shortcode_page_id($type)
        {
            $shortcode = 'cmplz-document';
            $block = 'complianz/document';
            $page_id = get_transient('cmplz_shortcode_' . $type);

            if (!$page_id) {
                $pages = get_pages();
                foreach ($pages as $page) {
                    /*
                     * Gutenberg block check
                     *
                     * */
                    if (cmplz_uses_gutenberg() && has_block($block, $page)){
                        //check if block contains property
                        $html =  $page->post_content;

                        if (preg_match('/"selectedDocument":"(.*?)"/i', $html, $matches)) {
                            if ($matches[1]===$type) {
                                set_transient('cmplz_shortcode_' . $type, $page->ID, HOUR_IN_SECONDS);
                                return $page->ID;
                            }
                        }
                    }

                    /*
                     * If nothing found, or if not Gutenberg, check for shortcodes.
                     * Classic shortcode check
                     *
                     * */

                    if (has_shortcode($page->post_content, $shortcode) && strpos($page->post_content, 'type="' . $type.'"')!==FALSE) {
                        set_transient('cmplz_shortcode_' . $type, $page->ID, HOUR_IN_SECONDS);
                        return $page->ID;
                    }
                }
            } else {
                return $page_id;
            }


            return false;
        }


        /**
         * clear shortcode transients after page update
         * @param int|bool $post_id
         * @param object|bool $post
         * @hooked save_post which is why the $post param is passed without being used.
         *
         * @return void
         */


        public function clear_shortcode_transients($post_id=false, $post = false)
        {
            $pages = COMPLIANZ()->config->pages;
            foreach ($pages as $type => $page) {
                //if a post id is passed, this is from the save post hook. We only clear the transient for this specific post id.
                if ($post_id) {
                    if (get_transient('cmplz_shortcode_' . $type)==$post_id){
                        delete_transient('cmplz_shortcode_' . $type);
                        delete_transient("complianz_document_$type");
                    }

                } else {
                    delete_transient('cmplz_shortcode_' . $type);
                    delete_transient("complianz_document_$type");
                }

            }
        }

        /**
         *
         * get the URl of a specific page type
         * @param string $type cookie-policy, privacy-statement, etc
         * @return string
         *
         *
         */

        public function get_page_url($type, $region){

            if (!cmplz_has_region($region)) return '';

            $region = ($region == 'eu' || !$region) ? '' : '-' . $region;

            if (strpos($type,'privacy-statement')!==FALSE && cmplz_get_value('privacy-statement')!=='yes'){
                $policy_page_id = (int)get_option('wp_page_for_privacy_policy');
            } else {
                $policy_page_id = get_option('cmplz_document_id_'.$type.$region);
            }

            if ((get_post_status($policy_page_id) !== 'publish') || !$policy_page_id || !get_permalink($policy_page_id)){
                $policy_page_id = $this->get_shortcode_page_id($type.$region);

                if (!$policy_page_id) {
                    if (COMPLIANZ()->document->page_required($type.$region)) {
                        $policy_page_id = $this->create_page($type.$region);
                    }
                }
                update_option('cmplz_document_id_'.$type.$region, $policy_page_id);
            }

            //get correct translated id
            $policy_page_id = apply_filters( 'wpml_object_id', $policy_page_id, 'page', TRUE , substr(get_locale(),0,2) );

            return get_permalink($policy_page_id);
        }

        /**
         * Generate the cookie policy snapshot
         */

        public function generate_cookie_policy_snapshot($force=false){
            if (!$force && !get_option('cmplz_generate_new_cookiepolicy_snapshot')) return;

            $regions = cmplz_get_regions();
            foreach($regions as $region => $label){
                $region = ($region==='eu') ? '' : "-$region";
                $banner_id = cmplz_get_default_banner_id();
                $banner = new CMPLZ_COOKIEBANNER($banner_id);
                $settings = $banner->get_settings_array();
                $settings['privacy_link_us '] = COMPLIANZ()->document->get_page_url('privacy-statement','us');
                $settings_html='';
                $skip = array('static','set_cookies', 'hide_revoke','popup_background_color','popup_text_color','button_background_color', 'button_text_color','position', 'theme', 'version', 'banner_version', 'a_b_testing', 'title', 'privacy_link', 'nonce', 'url','current_policy_id', 'type', 'layout','use_custom_css','custom_css','border_color');
                foreach($settings as $key => $value) {
                    if (in_array($key, $skip)) continue;

                    $settings_html .= '<li>'.$key.' => '.esc_html($value).'</li>';
                }
                $settings_html = '<div><h1>'.__('Cookie consent settings','complianz-gdpr').'</h1><ul>'.($settings_html).'</ul></div>';
                $intro =
                    '<h1>'. __("Proof of Consent","complianz-gdpr").'</h1>
                     <p>'.sprintf(__("This document was generated to show efforts made to comply with privacy legislation.
                            This document will contain the cookie policy and the cookie consent settings to proof consent
                            for the time and region specified below. For more information about this document, please go
                            to %shttps://complianz.io/consent%s.","complianz-gdpr"),'<a target="_blank" href="https://complianz.io/consent">',"</a>").'</p>';

                COMPLIANZ()->document->generate_pdf('cookie-statement'.$region, false, true, $intro, $settings_html);
            }
            update_option('cmplz_generate_new_cookiepolicy_snapshot',false);
        }

        /**
         * Function to generate a pdf file, either saving to file, or echo to browser
         * @param $page
         * @param $post_id
         * @param $save_to_file
         * @param $append //if we want to add addition html
         * @throws \Mpdf\MpdfException
         */

        public function generate_pdf($page, $post_id=false, $save_to_file=false, $intro='', $append=''){
            if ( !defined( 'DOING_CRON' ) ) {
                if (!is_user_logged_in()) {
                    die("invalid command");
                }

                if (!current_user_can('manage_options')) {
                    die("invalid command");
                }
            }


            $uploads = wp_upload_dir();
            $upload_dir = $uploads['basedir'];



            $pages = COMPLIANZ()->config->pages;

            //double check if it exists
            if (!isset($pages[$page])) return;

            $title = $pages[$page]['title'];
            $region = $pages[$page]['condition']['regions'];
            $document_html = $intro.COMPLIANZ()->document->get_document_html($page, $post_id).$append;
            $load_css = cmplz_get_value('use_document_css');
            $css = '';
            if ($load_css) {
                $css = file_get_contents (cmplz_path . "core/assets/css/document.css");
            }
            $title_html = $save_to_file ? '' : '<h4 class="center">' . $title . '</h4>';

            $html = '
                    <style>
                    '.$css.'
                    body {
                      font-family: sans;
                      margin-top:100px;
                    }
                    h2 {
                        font-size:12pt;
                    }
                    
                    h3 {
                        font-size:12pt;
                    }
                    
                    h4 {
                        font-size:10pt;
                        font-weight: bold;
                    }
                    .center {
                      text-align:center;
                    }
                    
                    
                    
                    </style>
                    
                    <body >
                    ' . $title_html . '
                    ' . $document_html . '
                    </body>';

//==============================================================
//==============================================================
//==============================================================

            require cmplz_path . '/core/assets/vendor/autoload.php';

            //generate a token when it's not there, otherwise use the existing one.
            if (get_option('cmplz_pdf_dir_token')) {
                $token = get_option('cmplz_pdf_dir_token');
            } else {
                $token = time();
                update_option('cmplz_pdf_dir_token', $token);
            }

            if (!is_writable($upload_dir)){
                die($upload_dir." directory not writable");
            }

            if (!file_exists($upload_dir . '/complianz')){
                mkdir($upload_dir . '/complianz');
            }
            if (!file_exists($upload_dir . '/complianz/tmp')){
                mkdir($upload_dir . '/complianz/tmp');
            }
            if (!file_exists($upload_dir . '/complianz/snapshots')){
                mkdir($upload_dir . '/complianz/snapshots');
            }
            $save_dir = $upload_dir . '/complianz/snapshots/';
            $temp_dir = $upload_dir . '/complianz/tmp/' . $token;
            if (!file_exists($temp_dir)){
                mkdir($temp_dir);
            }
            $mpdf = new Mpdf\Mpdf(array(
                'setAutoTopMargin' => 'stretch',
                'autoMarginPadding' => 5,
                'tempDir' => $temp_dir,
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 30,
                'margin_bottom' => 30,
                'margin_header' => 30,
                'margin_footer' => 10,
            ));

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($title);

            $img = '';//'<img class="center" src="" width="150px">';
            $date = date_i18n( get_option( 'date_format' ), time()  );

            $mpdf->SetHTMLHeader($img);
            $footer_text = sprintf("%s $title $date", get_bloginfo('name'));

            $mpdf->SetFooter($footer_text);
            $mpdf->WriteHTML($html);

            // Save the pages to a file
            if ($save_to_file){
                $file_title = $save_dir.get_bloginfo('name').'-' .$region. "-proof-of-consent-" . sanitize_title($date);
            } else{
                $file_title = get_bloginfo('name') . "-export-" . sanitize_title($date);
            }

            $output_mode = $save_to_file ? 'F' : 'I';

            $mpdf->Output($file_title . ".pdf", $output_mode);

        }
    }
} //class closure
