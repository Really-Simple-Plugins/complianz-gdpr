<?php
defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_admin")) {
    class cmplz_admin
    {
        private static $_this;
        public $error_message = "";
        public $success_message = "";
        public $task_count=0;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;
            add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
            add_action('admin_menu', array($this, 'register_admin_page'), 20);
            add_action('admin_init', array($this, 'process_support_request'));

            $plugin = cmplz_plugin;
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));

            //Add actions for dashboard components
            add_action("cmplz_dashboard_third_block", array($this, 'dashboard_third_block'));
            add_action("cmplz_dashboard_footer", array($this, 'dashboard_footer'));
            add_action("cmplz_dashboard_second_block", array($this, 'dashboard_second_block'));
            add_action("cmplz_documents_footer", array($this, 'documents_footer'));
            add_action("cmplz_documents", array($this, 'documents'));

            //some custom warnings
            add_filter('cmplz_warnings_types', array($this, 'filter_warnings'));

            add_action('cmplz_tools', array($this, 'dashboard_tools'));

            add_action('admin_init', array($this, 'check_upgrade'), 10, 2);

            add_action('cmplz_show_message', array($this,'show_message'));

            add_action('admin_init', array($this, 'process_reset_action'),10, 1);


            //deprecated strings
            $deprecated_strings = _x('You can use your internet browser to automatically or manually delete cookies. You can also specify that certain cookies may not be placed. Another option is to change the settings of your internet browser so that you receive a message each time a cookie is placed. For more information about these options, please refer to the instructions in the Help section of your browser. Or you can indicate your preferences on the following page:  www.youronlinechoices.eu.',  'Legal document cookie policy','complianz-gdpr');



        }

        static function this()
        {
            return self::$_this;


        }


        public function process_reset_action(){

            if (!isset($_POST['cmplz_reset_settings'])) return;

            if (!current_user_can('manage_options')) return;

            if (!isset($_POST['complianz_nonce']) || !wp_verify_nonce($_POST['complianz_nonce'], 'complianz_save')) return;

            $options = array(
                'cmplz_activation_time',
                'cmplz_review_notice_shown',
                "cmplz_wizard_completed_once",
                'complianz_options_settings',
                'complianz_options_wizard',
                'complianz_options_dataleak',
                'complianz_options_processing',
                'complianz_active_policy_id',
                'complianz_scan_token',
                'cmplz_license_notice_dismissed',
                'cmplz_license_key',
                'cmplz_license_status',
                'cmplz_changed_cookies',
                'cmplz_processed_pages_list',
                'cmplz_license_notice_dismissed',
                'cmplz_processed_pages_list',
                'cmplz_detected_cookies',
                'cmplz_plugins_changed',
                'cmplz_detected_social_media',
                'cmplz_detected_thirdparty_services',
                'cmplz_deleted_cookies',
                'cmplz_reported_cookies',
            );

            foreach ($options as $option_name) {
                delete_option($option_name);
                delete_site_option($option_name);
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'cmplz_statistics';
            if($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'",$table_name)) != $table_name) {
                $wpdb->query($wpdb->prepare("TRUNCATE TABLE '%s'", $table_name));
            }

            $banners = cmplz_get_cookiebanners(array('status' => 'all'));
            foreach ($banners as $banner) {
                $banner = new CMPLZ_COOKIEBANNER($banner->ID);
                $banner->delete(true);
            }


            $this->success_message = __('Data successfully cleared', 'complianz-gdpr');
        }

        public function show_message(){
            if (!empty($this->error_message)){
                cmplz_notice($this->error_message, 'warning');
                $this->error_message = "";
            }

            if (!empty($this->success_message)){
                cmplz_notice($this->success_message, 'success', true);
                $this->success_message = "";
            }
        }

        public function check_upgrade()
        {
            //when debug is enabled, a timestamp is appended. We strip this for version comparison purposes.
            $prev_version = get_option('cmplz-current-version', false);
//            if (defined("SCRIPT_DEBUG") && SCRIPT_DEBUG) $prev_version = substr($prev_version,0, 5);
//
            //as of 1.1.10, publish date is stored in variable.
            if ($prev_version && version_compare($prev_version, '1.2.0', '<')) {
                $date = get_option('cmplz_publish_date');
                if (empty($date)) {
                    COMPLIANZ()->cookie->update_cookie_policy_date();
                }
            }

            //set a default region if this is an upgrade:
            if ($prev_version && version_compare($prev_version, '2.0.1', '<')) {
                $regions = cmplz_get_value('regions');
                if (empty($regions)) {
                    if (defined('cmplz_free')) cmplz_update_option('wizard', 'regions', 'eu');
                }
            }

            /*
             * Set the value of the US cookie banner message on upgrade
             * copy message text to message_us
             * */

            if ($prev_version && version_compare($prev_version, '2.1.2', '<')) {
                $settings = get_option('complianz_options_cookie_settings');
                $settings['message_us'] = $settings['message'];
                update_option('complianz_options_cookie_settings', $settings);
            }

            /*
             * If the legal documents have changed, we notify the user of this.
             *
             * */

            if (CMPLZ_LEGAL_VERSION > get_option('cmplz_legal_version',0)){
                update_option('cmplz_plugin_new_features', true);
                update_option('cmplz_legal_version', CMPLZ_LEGAL_VERSION);
            }


            /*
             * Migrate use_country and a_b_testing to general settings
             *
             * */
            if ($prev_version && version_compare($prev_version, '3.0.0', '<')) {
                $cookie_settings = get_option('complianz_options_cookie_settings');
                $general_settings = get_option('complianz_options_settings');

                if (isset($cookie_settings['use_country'])) $general_settings['use_country'] = $cookie_settings['use_country'];
                if (isset($cookie_settings['a_b_testing'])) $general_settings['a_b_testing'] = $cookie_settings['a_b_testing'];
                if (isset($cookie_settings['a_b_testing_duration'])) $general_settings['a_b_testing_duration'] = $cookie_settings['a_b_testing_duration'];
                if (isset($cookie_settings['cookie_expiry'])) $general_settings['cookie_expiry'] = $cookie_settings['cookie_expiry'];

                unset($cookie_settings['use_country']);
                unset($cookie_settings['a_b_testing']);
                unset($cookie_settings['a_b_testing_duration']);
                unset($cookie_settings['cookie_expiry']);

                update_option('complianz_options_settings', $general_settings);
                update_option('complianz_options_cookie_settings', $cookie_settings);
            }


            /*
             * Upgrade to new cookie banner database table
             *
             * */

            if ($prev_version && version_compare($prev_version, '3.0.0', '<')) {
                COMPLIANZ()->cookie->migrate_legacy_cookie_settings();
            }

            /*
             * Merge address data into one field for more flexibility
             * */

            if ($prev_version && version_compare($prev_version, '3.0.0', '<')) {
                //get address data
                $wizard_settings = get_option('complianz_options_wizard');

                $adress = isset($wizard_settings['address_company']) ? $wizard_settings['address_company'] : '';
                $zip = isset($wizard_settings['postalcode_company']) ? $wizard_settings['postalcode_company'] : '';
                $city = isset($wizard_settings['city_company']) ? $wizard_settings['city_company'] : '';
                $new_adress = $adress . "\n" . $zip . ' ' .$city;
                $wizard_settings['address_company'] = $new_adress;
                unset($wizard_settings['postalcode_company']);
                unset($wizard_settings['city_company']);
                update_option('complianz_options_wizard', $wizard_settings);
            }

            /*
             * set new cookie policy url option to correct default state
             * */

            if ($prev_version && version_compare($prev_version, '3.0.8', '<')) {
                $wizard_settings = get_option('complianz_options_wizard');
                $wizard_settings['cookie-policy-type'] = 'default';
                update_option('complianz_options_wizard', $wizard_settings);
            }

            do_action('cmplz_upgrade', $prev_version);

            update_option('cmplz-current-version', cmplz_version);
        }



        public function complianz_plugin_has_new_features(){
            return get_option('cmplz_plugin_new_features');
        }

        public function reset_complianz_plugin_has_new_features(){
            return update_option('cmplz_plugin_new_features', false);
        }

        public function enqueue_assets($hook)
        {
            if ((strpos($hook, 'complianz') === FALSE) && strpos($hook, 'cmplz') === FALSE) return;

            wp_register_style('cmplz-circle', cmplz_url . 'core/assets/css/circle.css', array(), cmplz_version);
            wp_enqueue_style('cmplz-circle');

            wp_register_style('cmplz-fontawesome', cmplz_url . 'core/assets/fontawesome/fontawesome-all.css', "", cmplz_version);
            wp_enqueue_style('cmplz-fontawesome');

            wp_register_style('cmplz', trailingslashit(cmplz_url) . 'core/assets/css/style.css', "", cmplz_version);
            wp_enqueue_style('cmplz');

            wp_enqueue_style('wp-color-picker');

            wp_enqueue_script('cmplz-ace', cmplz_url . "core/assets/ace/ace.js", array(), cmplz_version, false);

            $minified = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_script('cmplz-admin', cmplz_url . "core/assets/js/admin$minified.js", array('jquery', 'wp-color-picker'), cmplz_version, true);

            $progress = COMPLIANZ()->cookie->get_progress_count();
            wp_localize_script(
                'cmplz-admin',
                'complianz_admin',
                array(
                    'admin_url' => admin_url('admin-ajax.php'),
                    'progress' => $progress,
                )
            );
        }

        /**
         * Add custom link to plugins overview page
         * @hooked plugin_action_links_$plugin
         * @param array $links
         * @return array $links
         */

        public function plugin_settings_link($links)
        {
            $settings_link = '<a href="' . admin_url("admin.php?page=complianz") . '">' . __("Settings", 'complianz-gdpr') . '</a>';
            array_unshift($links, $settings_link);

            $support_link = defined('cmplz_free') ? "https://wordpress.org/support/plugin/complianz-gdpr" : "https://complianz.io/support";
            $faq_link = '<a target="_blank" href="'.$support_link.'">' . __('Support', 'complianz-gdpr') . '</a>';
            array_unshift($links, $faq_link);

            if (!defined('cmplz_premium')) {
                $upgrade_link = '<a style="color:#2DAAE1;font-weight:bold" target="_blank" href="https://complianz.io/pricing">' . __('Upgrade to premium', 'complianz-gdpr') . '</a>';
                array_unshift($links, $upgrade_link);
            }

            return $links;
        }

        public function filter_warnings($warnings)
        {

            if (!COMPLIANZ()->wizard->wizard_completed_once() && COMPLIANZ()->wizard->all_required_fields_completed('wizard')) {
                $warnings['wizard-incomplete']['label_error'] = __('All fields have been completed, but you have not clicked the finish button yet.', 'complianz-gdpr');
            }
            return $warnings;
        }

        /**
         * get a list of applicable warnings.
         * @param bool $cache
         * @param bool $plus_ones_only
         * @param array $ignore_warnings
         * @return array
         */


        public function get_warnings($cache = true, $plus_ones_only=false, $ignore_warnings=array())
        {
            $warnings = $cache ? get_transient('complianz_warnings') : false;
            //re-check if there are no warnings, or if the transient has expired
            if (!$warnings || count($warnings) > 0) {
                $warnings = array();

                if (!$plus_ones_only) {
                    if (cmplz_get_value('respect_dnt')!=='yes') $warnings[] = 'no-dnt';
                }

                if (cmplz_has_region('eu') && !COMPLIANZ()->document->page_exists('cookie-statement')) {
                    $warnings[] = 'no-cookie-policy';
                }

                if (cmplz_has_region('us') && !COMPLIANZ()->document->page_exists('cookie-statement-us')) {
                    $warnings[] = 'no-cookie-policy-us';
                }

                if (!COMPLIANZ()->wizard->wizard_completed_once() || !COMPLIANZ()->wizard->all_required_fields_completed('wizard')) {
                    $warnings[] = 'wizard-incomplete';
                }

                if (COMPLIANZ()->cookie->plugins_updated() || COMPLIANZ()->cookie->plugins_changed()) {
                    $warnings[] = 'plugins-changed';
                }

                if (COMPLIANZ()->cookie->uses_google_analytics() && !COMPLIANZ()->cookie->analytics_configured()) {
                    $warnings[] = 'ga-needs-configuring';
                }

                if (COMPLIANZ()->cookie->uses_google_tagmanager() && !COMPLIANZ()->cookie->tagmanager_configured()) {
                    $warnings[] = 'gtm-needs-configuring';
                }

                if (COMPLIANZ()->cookie->uses_matomo() && !COMPLIANZ()->cookie->matomo_configured()) {
                    $warnings[] = 'matomo-needs-configuring';
                }

//                if (COMPLIANZ()->cookie->has_empty_cookie_descriptions()) {
//                    $warnings[] = 'cookies-incomplete';
//                }

                if (COMPLIANZ()->document->documents_need_updating()){
                    $warnings[] = 'docs-need-updating';
                }

                if (!is_ssl()) {
                    $warnings[] = 'no-ssl';
                }

                if ($this->complianz_plugin_has_new_features()) {
                    $warnings[] = 'complianz-gdpr-feature-update';
                }

                $warnings = apply_filters('cmplz_warnings', $warnings);

                set_transient('complianz_warnings', $warnings, HOUR_IN_SECONDS);
            }

            $warnings = array_diff($warnings, $ignore_warnings);
            return $warnings;
        }

        // Register a custom menu page.
        public function register_admin_page()
        {
            if (!cmplz_user_can_manage()) return;

            $warnings = $this->get_warnings(true, true);
            $warning_count = count($warnings);
            $warning_title = esc_attr(sprintf('%d plugin warnings', $warning_count));
            $menu_label = sprintf(__('Complianz %s', 'complianz-gdpr'), "<span class='update-plugins count-$warning_count' title='$warning_title'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>");


            global $cmplz_admin_page;
            $cmplz_admin_page = add_menu_page(
                __('Complianz', 'complianz-gdpr'),
                $menu_label,
                'manage_options',
                'complianz',
                array($this, 'main_page'),
                cmplz_url . 'core/assets/images/menu-icon.png',
                CMPLZ_MAIN_MENU_POSITION
            );

            add_submenu_page(
                'complianz',
                __('Dashboard', 'complianz-gdpr'),
                __('Dashboard', 'complianz-gdpr'),
                'manage_options',
                'complianz',
                array($this, 'main_page')
            );

            add_submenu_page(
                'complianz',
                __('Wizard', 'complianz-gdpr'),
                __('Wizard', 'complianz-gdpr'),
                'manage_options',
                'cmplz-wizard',
                array($this, 'wizard_page')
            );

            add_submenu_page(
                'complianz',
                __('Script center', 'complianz-gdpr'),
                __('Script center', 'complianz-gdpr'),
                'manage_options',
                "cmplz-script-center",
                array($this, 'script_center')
            );

            add_submenu_page(
                'complianz',
                __('Settings'),
                __('Settings'),
                'manage_options',
                "cmplz-settings",
                array($this, 'settings')
            );

            do_action('cmplz_admin_menu');

            if (defined('cmplz_free') && cmplz_free){
                global $submenu;
                $class = 'cmplz-submenu';
                $submenu['complianz'][] = array(__('Upgrade to premium','complianz-gdpr'), 'manage_options', 'https://complianz.io/pricing');
                if (isset($submenu['complianz'][5])) {
                    if (!empty($submenu['complianz'][5][4])) // Append if css class exists
                        $submenu['complianz'][5][4] .= ' ' . $class;
                    else
                        $submenu['complianz'][5][4] = $class;
                }
            }



        }


        public function wizard_page()
        {

            ?>
            <div class="wrap">
                <div class="cmplz-wizard-title"><h1><?php _e("Wizard", 'complianz-gdpr') ?></h1></div>

                <?php if (apply_filters('cmplz_show_wizard_page', true)) { ?>
                    <?php COMPLIANZ()->wizard->wizard('wizard'); ?>
                <?php } else {
                    cmplz_notice(__('Your license needs to be activated to unlock the wizard', 'complianz-gdpr'), 'warning');
                }
                ?>
            </div>
            <?php
        }

        public function main_page()
        {
            ?>
            <div class="wrap" id="complianz">
                <div class="dashboard">
                    <?php $this->get_status_overview() ?>
                    <?php

                    if ($this->error_message != "") echo $this->error_message;
                    if ($this->success_message != "") echo $this->success_message;

                    ?>

                </div>
            </div>
            <?php
        }


        public function dashboard_second_block()
        {
            ?>

            <div class="cmplz-header-top cmplz-dashboard-text">
                <div class="cmplz-dashboard-title"> <?php echo __('Tools', 'complianz-gdpr'); ?> </div>
            </div>
            <?php
            ?>
            <div class="cmplz-dashboard-support-content cmplz-dashboard-text">
                <ul>
                    <?php do_action('cmplz_tools') ?>
                    <li>
                        <i class="fas fa-plus"></i><?php echo sprintf(__("For the most common issues see the Complianz %sknowledge base%s", 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/support">', '</a>'); ?>
                    </li>
                    <li>
                        <i class="fas fa-plus"></i><?php echo sprintf(__("Ask your questions on the %sWordPress forum%s", 'complianz-gdpr'), '<a target="_blank" href="https://wordpress.org/support/plugin/complianz-gdpr">', '</a>'); ?>
                    </li>
                    <li>
                        <i class="fas fa-plus"></i><?php echo __("Create dataleak report", 'complianz-gdpr') . " " . sprintf(__('(%spremium%s)', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io">', "</a>"); ?>
                    </li>
                    <li>
                        <i class="fas fa-plus"></i><?php echo __("Create processing agreement", 'complianz-gdpr') . " " . sprintf(__('(%spremium%s)', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io">', "</a>"); ?>
                    </li>
                    <li>
                        <i class="fas fa-plus"></i><?php echo sprintf(__("Upgrade to Complianz premium for %spremium support%s", 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/pricing">', '</a>'); ?>
                    </li>
                </ul>
            </div>

            <?php
        }

        public function dashboard_tools()
        {
            if (cmplz_wp_privacy_version()) {
                ?>
                <li><i class="fas fa-plus"></i><a
                            href="<?php echo admin_url('tools.php?page=export_personal_data') ?>"><?php _e("Export personal data", 'complianz-gdpr'); ?></a>
                </li>
                <li><i class="fas fa-plus"></i><a
                            href="<?php echo admin_url('tools.php?page=remove_personal_data') ?>"><?php _e("Erase personal data", 'complianz-gdpr'); ?></a>
                </li>

                <?php
            }
            if ( class_exists( 'WooCommerce' ) ) {
                ?>
                <li><i class="fas fa-plus"></i><a href="<?php echo admin_url('admin.php?page=wc-settings&tab=account') ?>"><?php _e("Manage shop privacy", 'complianz-gdpr'); ?></a>
                </li>
                <?php
            }
        }



        function dashboard_third_block()
        {
            ?>
            <div class="cmplz-header-top cmplz-dashboard-text pro">
                <div class="cmplz-dashboard-title"> <?php echo __('Documents', 'complianz-gdpr'); ?> </div>
            </div>
            <table class="cmplz-dashboard-documents-table cmplz-dashboard-text">
                <?php
                foreach (COMPLIANZ()->config->pages as $type => $page) {
                    //get region of this page , and maybe add it to the title
                    $img = '<img width="25px" height="5px" src="'.cmplz_url.'/core/assets/images/s.png">';

                    if (isset($page['condition']['regions'])) {
                        $region = $page['condition']['regions'];
                        $img = '<img width="25px" src="'.cmplz_url.'/core/assets/images/'.$region.'.png">';
                    }

                    if (COMPLIANZ()->document->page_exists($type)) {

                        $link = '<a href="' . get_permalink(COMPLIANZ()->document->get_shortcode_page_id($type)) . '">' . $page['title'] . '</a>';
                        $this->get_dashboard_element($link, 'success',$img);
                    } elseif (COMPLIANZ()->document->page_required($page)){
                        $this->get_dashboard_element(sprintf(__("You should create a %s"),$page['title'],$img), 'error');
                    }
                }

                $warnings = COMPLIANZ()->admin->get_warnings(false);
                $warning_types = apply_filters('cmplz_warnings_types', COMPLIANZ()->config->warning_types);

                foreach ($warning_types as $key => $type) {
                    if ($type['type'] === 'general') continue;
                    if (isset($type['region']) && !cmplz_has_region($type['region'])) continue;
                    if (in_array($key, $warnings)) {
                        if (isset($type['label_error'])) COMPLIANZ()->admin->get_dashboard_element($type['label_error'], 'error');
                    } else {
                        if (isset($type['label_ok'])) COMPLIANZ()->admin->get_dashboard_element($type['label_ok'], 'success');
                    }
                }
                do_action('cmplz_documents');
                ?>
            </table>
            <?php do_action('cmplz_documents_footer');

        }


        public function documents(){
            $regions = cmplz_get_regions();
            foreach($regions as $region => $label) {
                $region = COMPLIANZ()->config->regions[$region]['law'];
                $this->get_dashboard_element(sprintf(__('Privacy Statement (%s) - (%spremium%s)', 'complianz-gdpr'), $region, '<a href="https://complianz.io">', '</a>'), 'error');
            }
        }

        public function documents_footer(){
            ?>
            <div class="cmplz-documents-bottom cmplz-dashboard-text">
                <div class="cmplz-dashboard-title"><?php _e("Like Complianz | GDPR cookie consent?",'complianz-gdpr')?></div>
                <div>
                    <?php _e("Then you'll like the premium plugin even more! With: ", 'complianz-gdpr'); ?>
                    <?php _e('A/B testing','complianz-gdpr')?> -
                    <?php _e('Statistics','complianz-gdpr')?> -
                    <?php _e('Multiple regions','complianz-gdpr')?> -
                    <?php _e('More legal documents','complianz-gdpr')?> -
                    <?php _e('Premium support','complianz-gdpr')?> -
                    <?php _e('& more!','complianz-gdpr')?>
                </div>
                <a class="button cmplz"
                   href="https://complianz.io/pricing" target="_blank"><?php echo __('Discover premium', 'complianz-gdpr'); ?>
                    <i class="fa fa-angle-right"></i>
                </a>

            </div>
            <?php
        }

        public function dashboard_footer()
        {
            ?>
            <div class="cmplz-footer-block">
                <div class="cmplz-footer-title"><?php echo __('Really Simple SSL', 'complianz-gdpr'); ?></div>
                <div class="cmplz-footer-description"><?php echo __('Trusted by over 3 million WordPress users', 'complianz-gdpr'); ?></div>
                <a href="https://really-simple-ssl.com" target="_blank">
                    <div class="cmplz-external-btn">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </a>
            </div>

            <div class="cmplz-footer-block">
                <div class="cmplz-footer-title"><?php echo __('Feature requests', 'complianz-gdpr'); ?></div>
                <div class="cmplz-footer-description"><?php echo __('Need new features or languages? Let us know!', 'complianz-gdpr'); ?></div>
                <a href="https://complianz.io/contact" target="_blank">
                    <div class="cmplz-external-btn">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </a>
            </div>

            <div class="cmplz-footer-block">
                <div class="cmplz-footer-title"><?php echo __('Documentation', 'complianz-gdpr'); ?></div>
                <div class="cmplz-footer-description"><?php echo __('Check out the docs on complianz.io!', 'complianz-gdpr'); ?></div>
                <a href="https://complianz.io/documentation/" target="_blank">
                    <div class="cmplz-external-btn">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </a>
            </div>

            <div class="cmplz-footer-block">
                <div class="cmplz-footer-title"><?php echo __('Our blog', 'complianz-gdpr'); ?></div>
                <div class="cmplz-footer-description"><?php echo __('Stay up to date with the latest news', 'complianz-gdpr'); ?></div>
                <a href="https://complianz.io/blog" target="_blank">
                    <div class="cmplz-external-btn">
                        <i class="fa fa-angle-right"></i>
                    </div>
                </a>
            </div>

            <?php
        }


        public function get_status_overview()
        {
            ?>

            <div class="cmplz-dashboard-container">

                <?php
                //show an overlay when the wizard is not completed at least once yet

                if (!COMPLIANZ()->wizard->wizard_completed_once()) {
                    ?>
                    <div id="complete_wizard_first_notice">
                        <p>
                            <?php _e("You haven't completed the wizard yet. You should run the wizard at least once to get valid results in the dashboard.", 'complianz-gdpr') ?>
                            <a class="button cmplz-continue-button"
                               href="<?php echo admin_url('admin.php?page=cmplz-wizard') ?>">
                                <?php _e('Start wizard', 'complianz-gdpr') ?>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </p>
                    </div>
                <?php } ?>

                <div class="cmplz-dashboard-header">
                    <div class="cmplz-header-top">
                    </div>
                </div>
                <div class="cmplz-dashboard-content-container">
                    <div class="cmplz-logo">
                        <img src="<?php echo cmplz_url . 'core/assets/images/cmplz-logo.png' ?>"> <?php echo apply_filters('cmplz_logo_extension', __('Free', 'complianz-gdpr')) ?>
                    </div>
                    <div class="cmplz-completed-text">
                        <div class="cmplz-header-text">


                        </div>
                    </div>
                    <div class="cmplz-dashboard-progress cmplz-dashboard-item">
                        <div class="cmplz-dashboard-progress-top cmplz-dashboard-text">
                            <div class="cmplz-dashboard-top-text">
                                <div class="cmplz-dashboard-title"><?php echo __('Your progress', 'complianz-gdpr'); ?> </div>
                                <div class='cmplz-dashboard-top-text-subtitle'>
                                    <?php if (COMPLIANZ()->wizard->wizard_percentage_complete() < 100) {
                                        printf(__('Your website is not ready for the %s yet.', 'complianz-gdpr'),cmplz_supported_laws());
                                    } else {
                                        printf(__('Well done! Your website is ready for the %s.', 'complianz-gdpr'),cmplz_supported_laws());
                                    } ?>
                                </div>
                            </div>
                            <div class="cmplz-percentage-complete green c100 p<?php echo COMPLIANZ()->wizard->wizard_percentage_complete(); ?>">
                                <span><?php echo COMPLIANZ()->wizard->wizard_percentage_complete(); ?>%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>


                            <div class="cmplz-continue-wizard-btn">
                                <?php if (COMPLIANZ()->wizard->wizard_percentage_complete() < 100) { ?>
                                    <div>
                                        <a href="<?php echo admin_url('admin.php?page=cmplz-wizard') ?>"
                                           class="button cmplz cmplz-continue-button">
                                            <?php echo __('Continue', 'complianz-gdpr'); ?>
                                            <i class="fa fa-angle-right"></i></a>
                                    </div>
                                <?php } ?>
                            </div>


                        </div>
                        <table class="cmplz-steps-table cmplz-dashboard-text">
                            <tr><td></td><td><div class="cmplz-dashboard-info"><?php _e('Tasks','complianz-gdpr')?></div></td></tr>
                            <?php

                            $last_cookie_scan = COMPLIANZ()->cookie->get_last_cookie_scan_date();
                            if (!$last_cookie_scan) {
                                $this->task_count++;
                                $this->get_dashboard_element(sprintf(__('No cookies detected yet', 'complianz-gdpr'), $last_cookie_scan), 'error');
                            }

                            do_action('cmplz_dashboard_elements_error');

                            $warnings = $this->get_warnings(false);
                            $warning_types = apply_filters('cmplz_warnings_types', COMPLIANZ()->config->warning_types);
                            $warning_count = $this->task_count + count($warnings);

                            foreach ($warning_types as $key => $type) {

                                if (in_array($key, $warnings) && isset($type['label_error'])) {
                                    if ($type['type'] === 'document') {
                                        $warning_count--;
                                        continue;
                                    }

                                    if (isset($type['region']) && !cmplz_has_region($type['region'])) {
                                        $warning_count--;
                                        continue;
                                    }

                                    $this->get_dashboard_element($type['label_error'], 'error');
                                }
                            }

                            if ($warning_count<=0){
                                $this->get_dashboard_element(__("Nothing on your to do list", 'complianz-gdpr'), 'success');
                            }
                            ?>
                            <tr><td></td><td><div class="cmplz-dashboard-info"><?php _e('System status','complianz-gdpr')?></div></td></tr>

                            <?php

                            $regions = cmplz_get_regions();
                            $labels = array();
                            foreach($regions as $region => $label){
                                if (!isset(COMPLIANZ()->config->regions[$region]['label'])) continue;
                                $labels[] = COMPLIANZ()->config->regions[$region]['label'];
                            }
                            $labels = implode('/',$labels);
                            $this->get_dashboard_element(sprintf(__('Your site is configured for the %s.', 'complianz-gdpr'), $labels), 'success');




                            do_action('cmplz_dashboard_elements_success');

                            if (COMPLIANZ()->cookie->site_needs_cookie_warning() && COMPLIANZ()->wizard->wizard_completed_once()) {
                                $this->get_dashboard_element(__('Your site requires a cookie warning, which has been enabled', 'complianz-gdpr'), 'success');
                            }
                            if (!COMPLIANZ()->cookie->site_needs_cookie_warning()) {
                                $this->get_dashboard_element(__('Your site does not require a cookie warning. No cookie warning has been enabled.', 'complianz-gdpr'), 'success');
                            }
                            if ($last_cookie_scan) {
                                $this->get_dashboard_element(sprintf(__('Last cookie scan on %s', 'complianz-gdpr'), $last_cookie_scan), 'success');
                            }

                            foreach ($warning_types as $key => $type) {
                                if ($type['type'] === 'document') continue;
                                if (isset($type['region']) && !cmplz_has_region($type['region'])) continue;
                                if (!in_array($key, $warnings)) {
                                    if (isset($type['label_ok'])) $this->get_dashboard_element($type['label_ok'], 'success');
                                }
                            }

                            ?>

                        </table>
                    </div>

                    <div class="cmplz-dashboard-support cmplz-dashboard-item">
                        <?php do_action("cmplz_dashboard_second_block") ?>
                    </div>

                    <div class="cmplz-dashboard-documents cmplz-dashboard-item">
                        <?php do_action("cmplz_dashboard_third_block") ?>
                    </div>
                    <div class="cmplz-dashboard-footer">
                        <?php do_action("cmplz_dashboard_footer") ?>
                    </div>
                </div>
            </div>
            <?php
        }



        public function process_support_request()
        {
            if (isset($_POST['cmplz_support_request']) && isset($_POST['cmplz_support_email'])) {
                if (!is_email($_POST['cmplz_support_email'])) {
                    $this->error_message = __('Email address not valid', 'complianz-gdpr');
                    return;
                }

                if (!wp_verify_nonce($_POST['cmplz_nonce'], 'cmplz_support')) return;

                $email = sanitize_email($_POST['cmplz_support_email']);
                $subject = sanitize_text_field($_POST['cmplz_support_subject']);

                $allowed_tags = wp_kses_allowed_html('post');
                $support_request = wp_kses($_POST['cmplz_support_request'], $allowed_tags);

                $license = get_option('cmplz_license_key');
                $user_info = get_userdata(get_current_user_id());
                $nicename = $user_info->user_nicename;

                $headers[] = "Reply-to: $nicename <$email>" . "\r\n";

                $to = "support@complianz.io";
                $message = "License: $license <br><br>";
                $message .= $support_request . "<br><br>";
                $message .= $nicename;
                add_filter('wp_mail_content_type', function ($content_type) {
                    return 'text/html';
                });

                $success = wp_mail($to, $subject, $message, $headers);

                // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
                remove_filter('wp_mail_content_type', 'set_html_content_type');
                if ($success) {
                    $this->success_message = sprintf(__("Your support request has been received. We will reply shortly. You can track the status of your request at %scomplianz.io%s", 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/support">', '</a>');

                } else {
                    $this->error_message = __("Something went wrong while submitting the support request", 'complianz-gdpr');

                }
            }
        }

        public function get_dashboard_element($content, $type = 'error', $img='')
        {
            $icon = "";
            switch ($type) {
                case 'error':
                    $icon = 'fa-times';
                    break;
                case 'success':
                    $icon = 'fa-check';
                    break;
                case 'warning':
                    $icon = 'fa-exclamation-circle';
                    break;

            }

            $type = ($type == 'success') ? 'success' : 'error';

            ?>
            <tr class="<?php echo "cmplz-" . $type ?>">
                <td><i class="fa <?php echo $icon ?>"></i></td>
                <td style="width:100%"><?php echo $content ?></td>
                <td><?php echo $img?></td>
            </tr>
            <?php
        }





        public function settings()
        {
            ?>
            <div class="wrap cmplz-settings">
                <h1><?php _e("Settings") ?></h1>
                <?php do_action('cmplz_show_message')?>
                <form action="" method="post" enctype="multipart/form-data">


                    <table class="form-table">
                        <?php

                        COMPLIANZ()->field->get_fields('settings');

                        COMPLIANZ()->field->save_button();

                        ?>

                    </table>
                </form>
            </div>
            <?php
        }


        /**
         * Show the script center page
         *
         */

        public function script_center()
        {
            ?>
            <div class="wrap cmplz-settings" id="cmplz-wizard">
                <h1><?php _e("Script center") ?></h1>

                <form action="" method="post" class="cmplz-body">

                    <table class="form-table">
                        <tr><th></th><td>                <?php
                                cmplz_notice(_x("The script center should be used to add and block third-party scripts and iFrames before consent is given, or when consent is revoked. For example Hotjar and embedded video’s.", 'intro script center', 'complianz-gdpr'));
                                if (COMPLIANZ()->cookie->uses_google_tagmanager()) {
                                    cmplz_notice(__('Because you are using Google Tag Manager you can only add iFrames, as shown below.', 'complianz-gdpr'), 'warning');
                                }
                                ?></td></tr>
                        <tr>
                            <th></th>
                            <td><?php

                                COMPLIANZ()->field->get_fields('wizard', STEP_COOKIES, 5);

                                ?>
                            </td>
                        </tr>
                        <tr><th></th><td><?php COMPLIANZ()->field->save_button();?></td></tr>
                    </table>
                </form>
            </div>
            <?php
        }


        /**
         * Get the html output for a help tip
         * @param $str
         */

        public function get_help_tip($str)
        {
            ?>
            <span class="cmplz-tooltip-right tooltip-right" data-cmplz-tooltip="<?php echo $str ?>">
              <span class="dashicons dashicons-editor-help"></span>
            </span>
            <?php
        }

        public function send_mail($message, $from_name, $from_email)
        {
            $subject = "Support request from $from_name";
            $to = "support@complianz.io";
            $headers = array();
            add_filter('wp_mail_content_type', function ($content_type) {
                return 'text/html';
            });

            $headers[] = "Reply-To: $from_name <$from_email>" . "\r\n";
            $success = wp_mail($to, $subject, $message, $headers);

            // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
            remove_filter('wp_mail_content_type', 'set_html_content_type');
            return $success;
        }

    }
} //class closure
