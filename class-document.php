<?php

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_document")) {
    class cmplz_document extends cmplz_document_core
    {
        private static $_this;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            $this->init();


        }

        static function this()
        {

            return self::$_this;
        }

        public function enqueue_assets()
        {
            $load_css = cmplz_get_value('use_document_css');
            if ($load_css) {
                wp_register_style('cmplz-document', cmplz_url . 'assets/css/document.css', false, cmplz_version);
                wp_enqueue_style('cmplz-document');
            }
        }

        /*
         * This class is extended with pro functions, so init is called also from the pro extension.
         * */

        public function init()
        {
            foreach (COMPLIANZ()->config->pages as $type => $page) {
                add_shortcode('cmplz-document', array($this, 'load_document'));
            }

            add_shortcode('cmplz-revoke-link', array($this, 'revoke_link'));

            //clear shortcode transients after post update
            add_action('save_post', array($this, 'clear_shortcode_transients'), 10, 1);
            add_action('cmplz_wizard_add_pages_to_menu', array($this, 'wizard_add_pages_to_menu'), 10, 1);
            add_action('admin_init', array($this, 'assign_documents_to_menu'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));

        }

        public function wizard_add_pages_to_menu()
        {
            //get list of menus
            $locations = get_theme_mod('nav_menu_locations');

            $link = '<a href="' . admin_url('nav-menus.php') . '">';
            if (!$locations) {
                cmplz_notice(sprintf(__("No menus were found. Skip this step, or %screate a menu%s first."), $link, '</a>'));
                return;
            }

            $pages_not_in_menu = $this->pages_not_in_menu();
            if ($pages_not_in_menu) {
                $docs = array_map('get_the_title', $pages_not_in_menu);
                $docs = implode(", ", $docs);
                cmplz_notice(sprintf(esc_html(_n('The generated document %s has not been assigned to a menu yet, you can do this now, or skip this step and do it later.',
                    'The generated documents %s have not been assigned to a menu yet, you can do this now, or skip this step and do it later.', count($pages_not_in_menu), 'complianz')), $docs));
            } else {
                _e("Great! All your generated documents have been assigned to a menu, so you can skip this step.", 'copmlianz');
            }
            $menus = array();
            //search in menus for the current post
            foreach ($locations as $location => $menu_id) {
                if (has_nav_menu($location)) {
                    $menus[$location] = wp_get_nav_menu_name($location);
                }
            }
            $pages = $this->get_required_pages();
            echo '<table>';
            foreach ($pages as $page_id) {
                echo "<tr><td>";
                echo get_the_title($page_id);
                echo "</td><td>";
                ?>

                <select name="cmplz_assigned_menu[<?php echo $page_id ?>]">
                    <option value=""><?php _e("Select a menu", 'complianz'); ?></option>
                    <?php foreach ($menus as $location => $menu) {
                        $selected = ($this->is_assigned_this_menu($page_id, $location)) ? "selected" : "";
                        echo '<option ' . $selected . ' value="' . $location . '">' . $menu . '</option>';
                    } ?>

                </select>
                <?php
                echo "</td></tr>";
            }
            echo "</table>";


        }

        public function assign_documents_to_menu()
        {
            if (isset($_POST['cmplz_assigned_menu'])) {
                foreach ($_POST['cmplz_assigned_menu'] as $page_id => $location) {
                    if (empty($location)) continue;
                    if ($this->is_assigned_this_menu($page_id, $location)) continue;

                    $page = get_post($page_id);
                    $menu_id = $this->get_menu_id_by_location($location);
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

        /*
         * Get all pages that are not assigned to any menu
         *
         *
         * */

        public function pages_not_in_menu()
        {
            $locations = get_theme_mod('nav_menu_locations');

            if (!$locations) return false;

            //search in menus for the current post

            $pages = $this->get_required_pages();
            $pages_in_menu = array();


            foreach ($locations as $location => $menu_id) {
                if (has_nav_menu($location)) {
                    $menu_items = wp_get_nav_menu_items($menu_id);
                    foreach ($menu_items as $post) {
                        if (in_array($post->object_id, $pages)) {
                            $pages_in_menu[] = $post->object_id;
                        }
                    }
                }
            }
            $pages_not_in_menu = array_diff($pages, $pages_in_menu);
            if (count($pages_not_in_menu) == 0) return false;

            return $pages_not_in_menu;
        }

        public function get_menu_id_by_location($location)
        {
            $theme_locations = get_nav_menu_locations();
            $menu_obj = get_term($theme_locations[$location], 'nav_menu');
            if (!$menu_obj) return false;
            return $menu_obj->term_id;
        }

        public function is_assigned_this_menu($page_id, $location)
        {
            $locations = get_theme_mod('nav_menu_locations');

            if (!$locations) return false;

            foreach ($locations as $location_key => $menu_id) {
                if ($location_key !== $location) continue;
                if (has_nav_menu($location_key)) {

                    if (has_nav_menu($location_key)) {
                        $menu_items = wp_get_nav_menu_items($menu_id);

                        foreach ($menu_items as $post) {
                            if ($post->object_id == $page_id) return true;
                        }
                    }
                }
            }

            return false;
        }


        public function create_page($type)
        {
            $pages = $page_titles = COMPLIANZ()->config->pages;

            if (!isset($pages[$type])) return false;

            //only insert if there is no shortcode page of this type yet.
            $page_id = $this->get_shortcode_page_id($type);
            if (!$page_id) {

                $page = $pages[$type];

                $page = array(
                    'post_title' => $page['title'],
                    'post_type' => "page",
                    'post_content' => '[' . $this->get_shortcode($type) . ']',
                    'post_status' => 'publish',
                );

                // Insert the post into the database
                $page_id = wp_insert_post($page);
            }

            /*
             * Set default privacy page for WP
             *
             * */

            if ($type == 'privacy-statement') {
                update_option('wp_page_for_privacy_policy', $page_id);
            }

        }

        public function delete_page($type)
        {
            $page_id = $this->get_shortcode_page_id($type);
            if ($page_id) {
                wp_delete_post($page_id, false);
            }
        }

        public function page_exists($type)
        {
            if ($this->get_shortcode_page_id($type)) return true;

            return false;
        }


        public function get_shortcode($type)
        {
            return 'cmplz-document type="' . $type . '"';
        }


        public function get_required_pages()
        {
            $required_pages = COMPLIANZ()->config->pages;
            $pages = array();

            foreach ($required_pages as $type => $page) {
                if (!$page['public']) continue;

                if (COMPLIANZ()->document->page_required($page)) {
                    $pages[] = $this->get_shortcode_page_id($type);
                }
            }
            return $pages;
        }




        public function load_document($atts = [], $content = null, $tag = '')
        {
            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['type' => false,], $atts, $tag);
            $type = $atts['type'];
            if ($type) {
                $html = get_transient("complianz_document_$type");

                if ($this->use_cache($type)) {
                    if (!$html) $html = $this->get_document_html($type);
                    set_transient("complianz_document_$type", $html, WEEK_IN_SECONDS);
                } else {
                    $html = $this->get_document_html($type);
                }
                echo $html;
            }

            return ob_get_clean();
        }

        private function use_cache($type)
        {
            if (function_exists('pll__') || function_exists('icl_translate')) {
                return false;
            }

            if (defined('WP_DEBUG') && WP_DEBUG) return false;

            //do not cache for these types
            if (($type === 'processing') || ($type === 'dataleak')) return false;

            return true;

        }


        /*
          checks if the current page contains the shortcode.
        */

        public function is_shortcode_page($shortcode)
        {
            global $post;
            if ($post) {
                if (has_shortcode($post->post_content, $shortcode)) return true;
            }

            return false;
        }

        /*
          gets the  page that contains the shortcode.
        */

        public function get_shortcode_page_id($type)
        {
            $shortcode = 'cmplz-document';

            delete_transient('cmplz_shortcode_' . $type);
            $page_id = get_transient('cmplz_shortcode_' . $type);

            if (!$page_id) {

                $pages = get_pages();
                foreach ($pages as $page) {
                    if (has_shortcode($page->post_content, $shortcode) && strpos($page->post_content, 'type="' . $type)) {
                        set_transient('cmplz_shortcode_' . $type, $page->ID, DAY_IN_SECONDS);
                        return $page->ID;
                    }
                }
            } else {
                return $page_id;
            }
            return false;
        }


        /*
         *
         *
         * clear shortcode transients after page update */

        public function clear_shortcode_transients($post_id, $post = false)
        {
            $pages = COMPLIANZ()->config->pages;
            foreach ($pages as $type => $page) {
                delete_transient('cmplz_shortcode_' . $type);
            }
        }


    }
} //class closure
