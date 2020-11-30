<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_admin" ) ) {
	class cmplz_admin {
		private static $_this;
		public $error_message = "";
		public $success_message = "";
		public $task_count = 0;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'admin_menu', array( $this, 'register_admin_page' ), 20 );

			$plugin = cmplz_plugin;
			add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ) );
			//multisite
			add_filter( "network_admin_plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ) );

			//Add actions for dashboard components
			add_action( "cmplz_dashboard_third_block", array( $this, 'dashboard_third_block' ) );
			add_action( "cmplz_dashboard_footer", array( $this, 'dashboard_footer' ) );
			add_action( "cmplz_dashboard_second_block", array( $this, 'dashboard_second_block' ) );
			add_action( "cmplz_documents_footer", array( $this, 'documents_footer' ) );
			add_action( "cmplz_documents", array( $this, 'documents' ) );

			//some custom warnings
			add_filter( 'cmplz_warnings_types', array( $this, 'filter_warnings' ) );
			add_action( 'cmplz_tools', array( $this, 'dashboard_tools' ) );
			add_action( 'admin_init', array( $this, 'check_upgrade' ), 10, 2 );
			add_action( 'cmplz_show_message', array( $this, 'show_message' ) );
			add_action( 'admin_init', array( $this, 'process_reset_action' ), 10, 1 );

			if ( get_option( 'cmplz_show_cookiedatabase_optin' ) ) {
				add_action( 'admin_notices', array( $this, 'notice_optin_on_upgrade' ) );
			}

			add_action('cmplz_fieldvalue', array($this, 'filter_cookie_domain'), 10, 2);
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Sanitize the cookiedomain
		 * @param $fieldname
		 * @param $fieldvalue
		 * @param $fieldvalue
		 *
		 * @return string|string[]
		 */

		public function filter_cookie_domain($fieldvalue, $fieldname){
			if (!current_user_can('manage_options')) return $fieldvalue;
			//sanitize the cookie domain
			if ( ( $fieldname === 'cmplz_cookie_domain' && strlen($fieldvalue)>0 )
			) {
				$fieldvalue = str_replace(array("https://", "http://", "www."), "", $fieldvalue);
			}

			return $fieldvalue;
		}

		/**
		 * process the reset
		 */

		public function process_reset_action() {

			if ( ! isset( $_POST['cmplz_reset_settings'] ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( ! isset( $_POST['complianz_nonce'] )
			     || ! wp_verify_nonce( $_POST['complianz_nonce'],
					'complianz_save' )
			) {
				return;
			}

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
				'cmplz_plugins_changed',
				'cmplz_detected_stats',
				'cmplz_deleted_cookies',
				'cmplz_reported_cookies',
				'cmplz_sync_cookies_complete',
				'cmplz_sync_services_complete',
				'cmplz_detected_social_media',
				'cmplz_detected_thirdparty_services',
				'cmplz_run_cdb_sync_once',

			);


			foreach ( $options as $option_name ) {
				delete_option( $option_name );
				delete_site_option( $option_name );
			}

			global $wpdb;
			$table_names = array(
				$wpdb->prefix . 'cmplz_statistics',
				$wpdb->prefix . 'cmplz_cookies',
				$wpdb->prefix . 'cmplz_services'
			);

			foreach ( $table_names as $table_name ) {
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" )
				     === $table_name
				) {
					$wpdb->query( "TRUNCATE TABLE $table_name" );
				}
			}

			$banners = cmplz_get_cookiebanners( array( 'status' => 'all' ) );
			foreach ( $banners as $banner ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner->ID );
				$banner->delete( true );
			}


			$this->success_message = __( 'Data successfully cleared',
				'complianz-gdpr' );
		}

		public function show_message() {
			if ( ! empty( $this->error_message ) ) {
				cmplz_notice( $this->error_message, 'warning' );
				$this->error_message = "";
			}

			if ( ! empty( $this->success_message ) ) {
				cmplz_notice( $this->success_message, 'success', true );
				$this->success_message = "";
			}
		}

		public function check_upgrade() {
			//when debug is enabled, a timestamp is appended. We strip this for version comparison purposes.
			$prev_version = get_option( 'cmplz-current-version', false );

			/**
			 * Migrate use_country and a_b_testing to general settings
			 *
			 * */
			if ( $prev_version
			     && version_compare( $prev_version, '3.0.0', '<' )
			) {
				$cookie_settings
					              = get_option( 'complianz_options_cookie_settings' );
				$general_settings = get_option( 'complianz_options_settings' );

				if ( isset( $cookie_settings['use_country'] ) ) {
					$general_settings['use_country']
						= $cookie_settings['use_country'];
				}
				if ( isset( $cookie_settings['a_b_testing'] ) ) {
					$general_settings['a_b_testing']
						= $cookie_settings['a_b_testing'];
				}
				if ( isset( $cookie_settings['a_b_testing_duration'] ) ) {
					$general_settings['a_b_testing_duration']
						= $cookie_settings['a_b_testing_duration'];
				}
				if ( isset( $cookie_settings['cookie_expiry'] ) ) {
					$general_settings['cookie_expiry']
						= $cookie_settings['cookie_expiry'];
				}

				unset( $cookie_settings['use_country'] );
				unset( $cookie_settings['a_b_testing'] );
				unset( $cookie_settings['a_b_testing_duration'] );
				unset( $cookie_settings['cookie_expiry'] );

				update_option( 'complianz_options_settings',
					$general_settings );
				update_option( 'complianz_options_cookie_settings',
					$cookie_settings );
			}


			/*
			 * Upgrade to new cookie banner database table
			 *
			 * */


			if ( $prev_version
			     && version_compare( $prev_version, '3.0.0', '<' )
			) {
				COMPLIANZ::$cookie_admin->migrate_legacy_cookie_settings();

			}

			/*
			 * Merge address data into one field for more flexibility
			 * */

			if ( $prev_version
			     && version_compare( $prev_version, '3.0.0', '<' )
			) {
				//get address data
				$wizard_settings = get_option( 'complianz_options_wizard' );

				$adress                             = isset( $wizard_settings['address_company'] )
					? $wizard_settings['address_company'] : '';
				$zip
				                                    = isset( $wizard_settings['postalcode_company'] )
					? $wizard_settings['postalcode_company'] : '';
				$city
				                                    = isset( $wizard_settings['city_company'] )
					? $wizard_settings['city_company'] : '';
				$new_adress                         = $adress . "\n" . $zip
				                                      . ' ' . $city;
				$wizard_settings['address_company'] = $new_adress;
				unset( $wizard_settings['postalcode_company'] );
				unset( $wizard_settings['city_company'] );
				update_option( 'complianz_options_wizard', $wizard_settings );
			}

			/*
			 * set new cookie policy url option to correct default state
			 * */

			if ( $prev_version
			     && version_compare( $prev_version, '3.0.8', '<' )
			) {
				$wizard_settings                       = get_option( 'complianz_options_wizard' );
				$wizard_settings['cookie-statement'] = 'generated';
				update_option( 'complianz_options_wizard', $wizard_settings );
			}

			/*
			 * change googlemaps into google-maps
			 * */
			if ( $prev_version
			     && version_compare( $prev_version, '4.0.0', '<' )
			) {
				$wizard_settings = get_option( 'complianz_options_wizard' );
				if ( isset( $wizard_settings['thirdparty_services_on_site']['googlemaps'] )
				     && $wizard_settings['thirdparty_services_on_site']['googlemaps']
				        == 1
				) {
					unset( $wizard_settings['thirdparty_services_on_site']['googlemaps'] );
					$wizard_settings['thirdparty_services_on_site']['google-maps']
						= 1;
					update_option( 'complianz_options_wizard',
						$wizard_settings );
				}

				//migrate detected cookies

				//upgrade only cookies from an accepted list.
				$upgrade_cookies = COMPLIANZ::$config->upgrade_cookies;
				$used_cookies    = cmplz_get_value( 'used_cookies' );
				if ( ! empty( $used_cookies ) || is_array( $used_cookies ) ) {
					foreach ( $used_cookies as $cookie ) {
						if ( ! isset( $cookie['used_names'] ) ) {
							continue;
						}

						$found_cookies = $cookie['used_names'];
						$found_cookies = explode( ',', $found_cookies );
						foreach ( $found_cookies as $name ) {
							$cookie = new CMPLZ_COOKIE();
							if ( in_array( $name, $upgrade_cookies ) ) {
								$cookie->add( $name,
									COMPLIANZ::$cookie_admin->get_supported_languages() );
							}
						}

					}
				}

			}

			/**
			 * upgrade existing eu and uk settings to separate uk optinstats
			 */

			if ( $prev_version
			     && version_compare( $prev_version, '4.0.0', '<' )
			) {
				if ( cmplz_has_region( 'eu' ) && cmplz_has_region( 'uk' ) ) {
					$banners = cmplz_get_cookiebanners();
					foreach ( $banners as $banner ) {
						$banner = new CMPLZ_COOKIEBANNER( $banner->ID );
						$banner->use_categories_optinstats
						        = $banner->use_categories;
						$banner->save();
					}
				}

			}

			/**
			 * migrate to anonymous if anonymous settings are selected
			 */

			if ( $prev_version
			     && version_compare( $prev_version, '4.0.4', '<' )
			) {
				$selected_stat_service
					= cmplz_get_value( 'compile_statistics' );
				if ( $selected_stat_service === 'google-analytics'
				     || $selected_stat_service === 'matomo'
				     || $selected_stat_service === 'google-tag-manager'
				) {
					$service_name
						= COMPLIANZ::$cookie_admin->convert_slug_to_name( $selected_stat_service );

					//check if we have ohter types of this service, to prevent double services here.
					$service_anonymized = new CMPLZ_SERVICE( $service_name
					                                         . ' (anonymized)' );
					$service            = new CMPLZ_SERVICE( $service_name );

					//check if we have two service types. If so, just delete the anonymized one
					if ( $service_anonymized->ID && $service->ID ) {
						$service_anonymized->delete();
					} else if ( $service_anonymized->ID && ! $service->ID ) {
						//just one. If it's the anonymous service, rename, and save it.
						$service_anonymized->name = $service_name;
						$service_anonymized->save();
					}
				}
			}

			/**
			 * ask consent for cookiedatabase sync and reference, and start sync and scan
			 */

			if ( $prev_version
			     && version_compare( $prev_version, '4.0.4', '<' )
			) {

				//upgrade option to transient
				if ( ! get_transient( 'cmplz_processed_pages_list' ) ) {
					set_transient( 'cmplz_processed_pages_list',
						get_option( 'cmplz_processed_pages_list' ),
						MONTH_IN_SECONDS );
				}

				//reset scan, delayed
				COMPLIANZ::$cookie_admin->reset_pages_list( true );
				//initialize a sync
				update_option( 'cmplz_run_cdb_sync_once', true );

				update_option( 'cmplz_show_cookiedatabase_optin', true );
			}

			/**
			 * upgrade publish date to more generic unix
			 */

			if ( $prev_version
			     && version_compare( $prev_version, '4.2', '<' )
			) {
				$publish_date = strtotime( get_option( 'cmplz_publish_date' ) );
				if ( intval( $publish_date ) > 0 ) {
					update_option( 'cmplz_publish_date',
						intval( $publish_date ) );
				}
			}

			/**
			 * upgrade to new custom and generated document settings
			 */
			if (  $prev_version
			     && version_compare( $prev_version, '4.4.0', '<' )
			) {
				//upgrade cookie policy setting to new field
				$wizard_settings = get_option( 'complianz_options_wizard' );
				if ( isset($wizard_settings["cookie-policy-type"]) ){
					$value = $wizard_settings["cookie-policy-type"];
					unset($wizard_settings["cookie-policy-type"]);
					//upgrade cookie policy custom url
					if ($value === 'custom') {
						$url     = cmplz_get_value( 'custom-cookie-policy-url' );
						update_option( "cmplz_cookie-statement_custom_page", $url );
						unset($wizard_settings["custom-cookie-policy-url"]);
					} else {
						$value = 'generated';
					}
				} else {
					$value = 'generated';
				}

				$wizard_settings['cookie-statement'] = $value;
				$wizard_settings['impressum'] = 'none';

				//upgrade privacy statement settings
				$value = $wizard_settings["privacy-statement"];

				if ( $value === 'yes' ) {
					$value = 'generated';
				} else {
					$wp_privacy_policy = get_option('wp_page_for_privacy_policy');
					if ($wp_privacy_policy) {
						$value = 'custom';
						update_option("cmplz_privacy-statement_custom_page", $wp_privacy_policy);
					} else {
						$value = 'none';
					}
				}
				$wizard_settings['privacy-statement'] = $value;

				//upgrade disclaimer settings
				$value = $wizard_settings["disclaimer"];
				if ($value==='yes'){
					$value = 'generated';
				} else {
					$value = 'none';
				}
				$wizard_settings['disclaimer'] = $value;

				//save the data
				update_option( 'complianz_options_wizard', $wizard_settings );
			}

			/**
			 * upgrade to new category field
			 */
			if (  $prev_version
			      && version_compare( $prev_version, '4.6.0', '<' )
			) {
				$banners = cmplz_get_cookiebanners();
				if ( $banners ) {
					foreach ( $banners as $banner_item ) {
						$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID, false );
						$banner->banner_version++;
						if ($banner->use_categories ) {
							$banner->use_categories = 'legacy';
						} else {
							$banner->use_categories = 'no';
						}
						if ($banner->use_categories_optinstats) {
							$banner->use_categories_optinstats = 'legacy';
						} else {
							$banner->use_categories_optinstats = 'no';
						}
						//also set the deny button to banner color, to make sure users start with correct colors
						$banner->functional_background_color = $banner->popup_background_color;
						$banner->functional_border_color = $banner->popup_background_color;
						$banner->functional_text_color = $banner->popup_text_color;
						$banner->save();
					}
				}
			}

			/**
			 * migrate policy id to network option for multisites
			 */

			if (  $prev_version && version_compare( $prev_version, '4.6.7', '<' )
			) {
				if (is_multisite()) update_site_option( 'complianz_active_policy_id', get_option( 'complianz_active_policy_id', 1 ));
			}

			/**
			 * migrate odd numbers
			 */
			if (  $prev_version && version_compare( $prev_version, '4.6.8', '<' )
			) {
				$banners = cmplz_get_cookiebanners();
				if ( $banners ) {
					foreach ( $banners as $banner_item ) {
						$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID );
						if($banner->banner_width % 2 == 1) $banner->banner_width++;
						$banner->save();
					}
				}
			}

			/**
			 * new progress option default
			 */

			if (  $prev_version && version_compare( $prev_version, '4.6.10.1', '<' ) ){
				if (get_option( 'cmplz_sync_cookies_complete' )) update_option( 'cmplz_sync_cookies_after_services_complete', true );
			}

			if (  $prev_version
			     && version_compare( $prev_version, '4.7.1', '<' )
			) {
				//upgrade cookie policy setting to new field
				$wizard_settings = get_option( 'complianz_options_wizard' );
				$wizard_settings['block_recaptcha_service'] = 'yes';
				update_option( 'complianz_options_wizard', $wizard_settings );
			}

			do_action( 'cmplz_upgrade', $prev_version );

			update_option( 'cmplz-current-version', cmplz_version );
		}

		/**
		 * Check if new features are shipped with the plugin
		 *
		 * @return mixed|void
		 */
		public function complianz_plugin_has_new_features() {
			return get_option( 'cmplz_plugin_new_features' );
		}

		/**
		 * Reset the new features option
		 *
		 * @return bool
		 */
		public function reset_complianz_plugin_has_new_features() {
			return update_option( 'cmplz_plugin_new_features', false );
		}

		/**
		 * Enqueue some assets
		 *
		 * @param $hook
		 */
		public function enqueue_assets( $hook ) {
			if ( ( strpos( $hook, 'complianz' ) === false )
			     && strpos( $hook, 'cmplz' ) === false
			) {
				return;
			}

			wp_register_style( 'cmplz-circle',
				cmplz_url . 'assets/css/circle.css', array(), cmplz_version );
			wp_enqueue_style( 'cmplz-circle' );

			wp_register_style( 'cmplz-fontawesome',
				cmplz_url . 'assets/fontawesome/fontawesome-all.css', "",
				cmplz_version );
			wp_enqueue_style( 'cmplz-fontawesome' );

			wp_register_style( 'cmplz',
				trailingslashit( cmplz_url ) . 'assets/css/style.css', "",
				cmplz_version );
			wp_enqueue_style( 'cmplz' );

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( 'cmplz-ace', cmplz_url . "assets/ace/ace.js",
				array(), cmplz_version, false );

			$minified = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? ''
				: '.min';
			wp_enqueue_script( 'cmplz-admin',
				cmplz_url . "assets/js/admin$minified.js",
				array( 'jquery', 'wp-color-picker' ), cmplz_version, true );

			$sync_progress = COMPLIANZ::$cookie_admin->get_sync_progress();
			$progress      = COMPLIANZ::$cookie_admin->get_progress_count();
			wp_localize_script(
				'cmplz-admin',
				'complianz_admin',
				array(
					'admin_url'    => admin_url( 'admin-ajax.php' ),
					'progress'     => $progress,
					'syncProgress' => $sync_progress,
				)
			);
		}

		/**
		 * Add custom link to plugins overview page
		 *
		 * @hooked plugin_action_links_$plugin
		 *
		 * @param array $links
		 *
		 * @return array $links
		 */

		public function plugin_settings_link( $links ) {
			$settings_link = '<a href="'
			                 . admin_url( "admin.php?page=complianz" )
			                 . '" class="cmplz-settings-link">'
			                 . __( "Settings", 'complianz-gdpr' ) . '</a>';
			array_unshift( $links, $settings_link );

			$support_link = defined( 'cmplz_free' )
				? "https://wordpress.org/support/plugin/complianz-gdpr"
				: "https://complianz.io/support";
			$faq_link     = '<a target="_blank" href="' . $support_link . '">'
			                . __( 'Support', 'complianz-gdpr' ) . '</a>';
			array_unshift( $links, $faq_link );

			if ( ! defined( 'cmplz_premium' ) ) {
				$upgrade_link
					= '<a style="color:#2DAAE1;font-weight:bold" target="_blank" href="https://complianz.io/l/pricing">'
					  . __( 'Upgrade to premium', 'complianz-gdpr' ) . '</a>';
				array_unshift( $links, $upgrade_link );
			}

			return $links;
		}

		public function filter_warnings( $warnings ) {

			if ( ! COMPLIANZ::$wizard->wizard_completed_once()
			     && COMPLIANZ::$wizard->all_required_fields_completed( 'wizard' )
			) {
				$warnings['wizard-incomplete']['label_error']
					= __( 'All fields have been completed, but you have not clicked the finish button yet.',
					'complianz-gdpr' );
			}

			return $warnings;
		}

		/**
		 * get a list of applicable warnings.
		 *
		 * @param bool  $cache
		 * @param bool  $plus_ones_only
		 * @param array $ignore_warnings
		 *
		 * @return array
		 */


		public function get_warnings(
			$cache = true, $plus_ones_only = false, $ignore_warnings = array()
		) {
			//return nothing when notifications disabled
			if ( cmplz_get_value( 'disable_notifications' ) ) {
				return array();
			}

			$warnings = $cache ? get_transient( 'complianz_warnings' ) : false;
			//re-check if there are no warnings, or if the transient has expired
			if ( ! $warnings || count( $warnings ) > 0 ) {
				$warnings = array();

				if ( ! $plus_ones_only ) {
					if ( cmplz_get_value( 'respect_dnt' ) !== 'yes' ) {
						$warnings[] = 'no-dnt';
					}
				}

				if ( ! COMPLIANZ::$wizard->wizard_completed_once()
				     || ! COMPLIANZ::$wizard->all_required_fields_completed( 'wizard' )
				) {
					$warnings[] = 'wizard-incomplete';
				}

				if ( COMPLIANZ::$cookie_admin->uses_google_analytics()
				     && ! COMPLIANZ::$cookie_admin->analytics_configured()
				) {
					$warnings[] = 'ga-needs-configuring';
				}

				if ( COMPLIANZ::$cookie_admin->uses_google_tagmanager()
				     && ! COMPLIANZ::$cookie_admin->tagmanager_configured()
				) {
					$warnings[] = 'gtm-needs-configuring';
				}

				if ( COMPLIANZ::$cookie_admin->uses_matomo()
				     && ! COMPLIANZ::$cookie_admin->matomo_configured()
				) {
					$warnings[] = 'matomo-needs-configuring';
				}

				if ( ! COMPLIANZ::$cookie_admin->use_cdb_api()
				     && COMPLIANZ::$cookie_admin->has_empty_cookie_descriptions()
				) {
					$warnings[] = 'cookies-incomplete';
				}

				if ( COMPLIANZ::$document->documents_need_updating() ) {
					$warnings[] = 'docs-need-updating';
				}

				if ( COMPLIANZ::$cookie_admin->cookies_changed() ) {
					$warnings[] = 'cookies-changed';
				}

				if ( get_option( 'cmplz_double_stats' ) ) {
					$warnings[] = 'double-stats';
				}

				if ( ! is_ssl() ) {
					$warnings[] = 'no-ssl';
				}

				if ( $this->complianz_plugin_has_new_features() ) {
					$warnings[] = 'complianz-gdpr-feature-update';
				}

				if ( COMPLIANZ::$cookie_admin->site_needs_cookie_warning() && get_option('cmplz_detected_console_errors') ) {
					//clear all other errors, as this one is important
					$warnings = array();
					$warnings[] = 'console-errors';
				}

				if ( COMPLIANZ::$cookie_admin->site_needs_cookie_warning() && get_option('cmplz_detected_missing_jquery') ) {
					$warnings[] = 'no-jquery';
				}

				$warnings = apply_filters( 'cmplz_warnings', $warnings );

				set_transient( 'complianz_warnings', $warnings,
					HOUR_IN_SECONDS );
			}

			$warnings = array_diff( $warnings, $ignore_warnings );

			return $warnings;
		}

		// Register a custom menu page.
		public function register_admin_page() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			$warnings      = $this->get_warnings( true, true );
			$warning_count = count( $warnings );
			$warning_title = esc_attr( sprintf( '%d plugin warnings',
				$warning_count ) );
			$menu_label    = sprintf( __( 'Complianz %s', 'complianz-gdpr' ),
				"<span class='update-plugins count-$warning_count' title='$warning_title'><span class='update-count'>"
				. number_format_i18n( $warning_count ) . "</span></span>" );


			global $cmplz_admin_page;
			$cmplz_admin_page = add_menu_page(
				__( 'Complianz', 'complianz-gdpr' ),
				$menu_label,
				'manage_options',
				'complianz',
				array( $this, 'main_page' ),
				cmplz_url . 'assets/images/menu-icon.svg',
				CMPLZ_MAIN_MENU_POSITION
			);

			add_submenu_page(
				'complianz',
				__( 'Dashboard', 'complianz-gdpr' ),
				__( 'Dashboard', 'complianz-gdpr' ),
				'manage_options',
				'complianz',
				array( $this, 'main_page' )
			);

			add_submenu_page(
				'complianz',
				__( 'Wizard', 'complianz-gdpr' ),
				__( 'Wizard', 'complianz-gdpr' ),
				'manage_options',
				'cmplz-wizard',
				array( $this, 'wizard_page' )
			);

			do_action( 'cmplz_cookiebanner_menu' );

			do_action( 'cmplz_integrations_menu' );

			add_submenu_page(
				'complianz',
				__( 'Settings' ),
				__( 'Settings' ),
				'manage_options',
				"cmplz-settings",
				array( $this, 'settings' )
			);

			add_submenu_page(
				'complianz',
				__( 'Proof of consent', 'complianz-gdpr' ),
				__( 'Proof of consent', 'complianz-gdpr' ),
				'manage_options',
				"cmplz-proof-of-consent",
				array( COMPLIANZ::$cookie_admin, 'cookie_statement_snapshots' )
			);

			do_action( 'cmplz_admin_menu' );

			if ( defined( 'cmplz_free' ) && cmplz_free ) {
				global $submenu;
				$class                  = 'cmplz-submenu';
				$highest_index = count($submenu['complianz']);
				$submenu['complianz'][] = array(
						__( 'Upgrade to premium', 'complianz-gdpr' ),
						'manage_options',
						'https://complianz.io/l/pricing'
				);
				if ( isset( $submenu['complianz'][$highest_index] ) ) {
					if (! isset ($submenu['complianz'][$highest_index][4])) $submenu['complianz'][$highest_index][4] = '';
					$submenu['complianz'][$highest_index][4] .= ' ' . $class;
				}
			}

		}


		public function wizard_page() {

			?>
			<div class="wrap">
				<div class="cmplz-wizard-title"><h1><?php _e( "Wizard",
							'complianz-gdpr' ) ?></h1></div>

				<?php if ( apply_filters( 'cmplz_show_wizard_page',
					true )
				) { ?>
					<?php COMPLIANZ::$wizard->wizard( 'wizard' ); ?>
				<?php } else {
					cmplz_notice( __( 'Your license needs to be activated to unlock the wizard',
						'complianz-gdpr' ), 'warning' );
				}
				?>
			</div>
			<?php
		}

		public function main_page() {
			?>
			<div class="wrap" id="complianz">
				<div class="dashboard">
					<?php $this->get_status_overview() ?>
					<?php

					if ( $this->error_message != "" ) {
						echo $this->error_message;
					}
					if ( $this->success_message != "" ) {
						echo $this->success_message;
					}

					?>

				</div>
			</div>
			<?php
		}


		public function dashboard_second_block() {
			?>

			<div class="cmplz-header-top cmplz-dashboard-text">
				<div class="cmplz-dashboard-title"> <?php echo __( 'Tools',
						'complianz-gdpr' ); ?> </div>
			</div>
			<?php
			?>
			<div class="cmplz-dashboard-support-content cmplz-dashboard-text">
				<ul>
					<?php do_action( 'cmplz_tools' ) ?>
					<li>
						<i class="fas fa-plus"></i><?php echo sprintf( __( "For the most common issues see the Complianz %sknowledge base%s",
							'complianz-gdpr' ),
							'<a target="_blank" href="https://complianz.io/support">',
							'</a>' ); ?>
					</li>
					<li>
						<i class="fas fa-plus"></i><?php echo sprintf( __( "Ask your questions on the %sWordPress forum%s",
							'complianz-gdpr' ),
							'<a target="_blank" href="https://wordpress.org/support/plugin/complianz-gdpr">',
							'</a>' ); ?>
					</li>
					<li>
						<i class="fas fa-plus"></i><?php echo __( "Create dataleak report",
								'complianz-gdpr' ) . " "
						                                      . sprintf( __( '(%spremium%s)',
								'complianz-gdpr' ),
								'<a target="_blank" href="https://complianz.io">',
								"</a>" ); ?>
					</li>
					<li>
						<i class="fas fa-plus"></i><?php echo __( "Create Processing Agreement",
								'complianz-gdpr' ) . " "
						                                      . sprintf( __( '(%spremium%s)',
								'complianz-gdpr' ),
								'<a target="_blank" href="https://complianz.io">',
								"</a>" ); ?>
					</li>
					<li>
						<i class="fas fa-plus"></i><?php echo sprintf( __( "Upgrade to Complianz premium for %spremium support%s",
							'complianz-gdpr' ),
							'<a target="_blank" href="https://complianz.io/l/pricing">',
							'</a>' ); ?>
					</li>
				</ul>
			</div>

			<?php
		}

		public function dashboard_tools() {
			if ( cmplz_wp_privacy_version() ) {
				?>
				<li><i class="fas fa-plus"></i><a
						href="<?php echo admin_url( 'admin.php?page=cmplz-proof-of-consent' ) ?>"><?php _e( "Proof of consent",
							'complianz-gdpr' ); ?></a>
				</li>
				<li><i class="fas fa-plus"></i><a
						href="<?php echo admin_url( 'export-personal-data.php' ) ?>"><?php _e( "Export personal data",
							'complianz-gdpr' ); ?></a>
				</li>
				<li><i class="fas fa-plus"></i><a
						href="<?php echo admin_url( 'erase-personal-data.php' ) ?>"><?php _e( "Erase personal data",
							'complianz-gdpr' ); ?></a>
				</li>

				<?php
			}
			if ( class_exists( 'WooCommerce' ) ) {
				?>
				<li><i class="fas fa-plus"></i><a
						href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=account' ) ?>"><?php _e( "Manage shop privacy",
							'complianz-gdpr' ); ?></a>
				</li>
				<?php
			}
		}


		function dashboard_third_block() {
			?>
			<div class="cmplz-header-top cmplz-dashboard-text pro">
				<div class="cmplz-dashboard-title"> <?php echo __( 'Documents',
						'complianz-gdpr' ); ?> </div>
			</div>
			<table class="cmplz-dashboard-documents-table cmplz-dashboard-text">
				<?php
				//we want to look at all regions, so we can show which pages are obsolete
				$regions        = COMPLIANZ::$config->regions;
				$regions['all'] = 'All';
				foreach ( $regions as $region => $label ) {
					if ( ! isset( COMPLIANZ::$config->pages[ $region ] ) ) {
						continue;
					}

					foreach (
						COMPLIANZ::$config->pages[ $region ] as $type => $page
					) {
						if ( ! $page['public'] ) {
							continue;
						}

						//get region of this page , and maybe add it to the title
						$img = '<img width="25px" height="5px" src="'
						       . cmplz_url . '/assets/images/s.png">';

						if ( isset( $page['condition']['regions'] ) ) {
							$region = $page['condition']['regions'];
							$region = is_array( $region ) ? reset( $region )
								: $region;
							$img    = '<img width="25px" src="' . cmplz_url
							          . '/assets/images/' . $region . '.png?v='
							          . cmplz_version . '">';
						}
						$link      = '<a href="' . get_permalink( COMPLIANZ::$document->get_shortcode_page_id( $type, $region ) ) . '">'
						             . $page['title']
						             . '</a>';
						$shortcode = COMPLIANZ::$document->get_shortcode( $type,
							$region, $force_classic = true );
						$link      .= '<div class="cmplz-selectable cmplz-shortcode">'
						              . $shortcode . '</div>';
						if ( COMPLIANZ::$document->page_exists( $type,
							$region )
						) {
							if ( ! COMPLIANZ::$document->page_required( $page,
								$region )
							) {
								$this->get_dashboard_element( sprintf( __( "Obsolete page, you can delete %s",
											'complianz-gdpr'),

									$link, $img ), 'error' );
							} else {
								$sync_status
									            = COMPLIANZ::$document->syncStatus( COMPLIANZ::$document->get_shortcode_page_id( $type,
									$region ) );
								$shortcode_icon = '';
								$status         = "success";
								if ( $sync_status === 'sync' ) {
									$shortcode_icon = '<span cmplz-tooltip="'
									                  . __( "Click to view the document shortcode",
											"complianz-gdpr" )
									                  . '" flow="left" class="cmplz-open-shortcode"><svg width="20" height="20" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M8.5,21.4l1.9,0.5l5.2-19.3l-1.9-0.5L8.5,21.4z M3,19h4v-2H5V7h2V5H3V19z M17,5v2h2v10h-2v2h4V5H17z"></path></svg></span>';
								} else {
									$status = "error";
								}
								$sync_icon
									 = COMPLIANZ::$cookie_admin->get_icon( array(
									'status'       => $status,
									'icon_success' => 'sync-alt',
									'desc_success' => __( 'Document is kept up to date by Complianz',
										'complianz-gdpr' ),
									'desc_error'   => __( 'Document is not kept up to date by Complianz',
										'complianz-gdpr' )
								) );
								$img = $shortcode_icon
								       . '<span class="cmplz-document-sync">'
								       . $sync_icon . '</span>' . $img;
								$this->get_dashboard_element( $link, 'success',
									$img );
							}

						} elseif ( COMPLIANZ::$document->page_required( $page,
							$region )
						) {
							$this->get_dashboard_element( sprintf( __( "You should create a %s", "complianz-gdpr"),
								$page['title'], $img ), 'error' );
						}
					}
				}
				$warnings      = COMPLIANZ::$admin->get_warnings( false );
				$warning_types = apply_filters( 'cmplz_warnings_types',
					COMPLIANZ::$config->warning_types );

				foreach ( $warning_types as $key => $type ) {
					if ( $type['type'] === 'general' ) {
						continue;
					}
					if ( isset( $type['region'] )
					     && ! cmplz_has_region( $type['region'] )
					) {
						continue;
					}
					if ( in_array( $key, $warnings ) ) {
						if ( isset( $type['label_error'] ) ) {
							COMPLIANZ::$admin->get_dashboard_element( $type['label_error'],
								'error' );
						}
					} else {
						if ( isset( $type['label_ok'] ) ) {
							COMPLIANZ::$admin->get_dashboard_element( $type['label_ok'],
								'success' );
						}
					}
				}
				do_action( 'cmplz_documents' );
				?>
			</table>
			<?php do_action( 'cmplz_documents_footer' );

		}


		public function documents() {
			$regions = cmplz_get_regions();
			foreach ( $regions as $region => $label ) {
				$region = COMPLIANZ::$config->regions[ $region ]['law'];
				$this->get_dashboard_element( sprintf( __( 'Privacy Statement (%s) - (%spremium%s)',
					'complianz-gdpr' ), $region,
					'<a href="https://complianz.io">', '</a>' ), 'error' );
			}
		}

		public function documents_footer() {
			?>
			<div class="cmplz-documents-bottom cmplz-dashboard-text">
				<div
					class="cmplz-dashboard-title"><?php _e( "Like Complianz | GDPR/CCPA Cookie Consent?",
						'complianz-gdpr' ) ?></div>
				<div>
					<?php _e( "Then you'll like the premium plugin even more! With: ",
						'complianz-gdpr' ); ?>
					<?php _e( 'A/B testing', 'complianz-gdpr' ) ?> -
					<?php _e( 'Statistics', 'complianz-gdpr' ) ?> -
					<?php _e( 'Multiple regions', 'complianz-gdpr' ) ?> -
					<?php _e( 'More legal documents', 'complianz-gdpr' ) ?> -
					<?php _e( 'Premium support', 'complianz-gdpr' ) ?> -
					<?php _e( '& more!', 'complianz-gdpr' ) ?>
				</div>
				<a class="button cmplz"
				   href="https://complianz.io/l/pricing"
				   target="_blank"><?php echo __( 'Discover premium',
						'complianz-gdpr' ); ?>
					<i class="fa fa-angle-right"></i>
				</a>

			</div>
			<?php
		}

		public function dashboard_footer() {
			?>
			<div class="cmplz-footer-block">
				<div
					class="cmplz-footer-title"><?php echo __( 'Really Simple SSL',
						'complianz-gdpr' ); ?></div>
				<div
					class="cmplz-footer-description"><?php echo __( 'Trusted by over 5 million WordPress users',
						'complianz-gdpr' ); ?></div>
				<a href="https://really-simple-ssl.com" target="_blank">
					<div class="cmplz-external-btn">
						<i class="fa fa-angle-right"></i>
					</div>
				</a>
			</div>

			<div class="cmplz-footer-block">
				<div
					class="cmplz-footer-title"><?php echo __( 'Feature requests',
						'complianz-gdpr' ); ?></div>
				<div
					class="cmplz-footer-description"><?php echo __( 'Need new features or languages? Let us know!',
						'complianz-gdpr' ); ?></div>
				<a href="https://complianz.io/contact" target="_blank">
					<div class="cmplz-external-btn">
						<i class="fa fa-angle-right"></i>
					</div>
				</a>
			</div>

			<div class="cmplz-footer-block">
				<div class="cmplz-footer-title"><?php echo __( 'Documentation',
						'complianz-gdpr' ); ?></div>
				<div
					class="cmplz-footer-description"><?php echo __( 'Check out the docs on complianz.io!',
						'complianz-gdpr' ); ?></div>
				<a href="https://complianz.io/documentation/" target="_blank">
					<div class="cmplz-external-btn">
						<i class="fa fa-angle-right"></i>
					</div>
				</a>
			</div>

			<div class="cmplz-footer-block">
				<div class="cmplz-footer-title"><?php echo __( 'Our blog',
						'complianz-gdpr' ); ?></div>
				<div
					class="cmplz-footer-description"><?php echo __( 'Stay up to date with the latest news',
						'complianz-gdpr' ); ?></div>
				<a href="https://complianz.io/blog" target="_blank">
					<div class="cmplz-external-btn">
						<i class="fa fa-angle-right"></i>
					</div>
				</a>
			</div>

			<?php
		}


		public function get_status_overview() {
			?>

			<div class="cmplz-dashboard-container">

				<div class="cmplz-dashboard-header">
					<div class="cmplz-header-top">
					</div>
				</div>
				<div class="cmplz-dashboard-content-container">
					<div class="cmplz-logo">
						<img alt="region" src="<?php echo cmplz_url
						                                  . 'assets/images/cmplz-logo.png' ?>"> <?php echo apply_filters( 'cmplz_logo_extension',
							__( 'Free', 'complianz-gdpr' ) ) ?>
					</div>
					<div class="cmplz-completed-text">
						<div class="cmplz-header-text">


						</div>
					</div>
					<div class="cmplz-dashboard-progress cmplz-dashboard-item">
						<div
							class="cmplz-dashboard-progress-top cmplz-dashboard-text">
							<div class="cmplz-dashboard-top-text">
								<div
									class="cmplz-dashboard-title"><?php echo __( 'Your progress',
										'complianz-gdpr' ); ?> </div>
								<div class='cmplz-dashboard-top-text-subtitle'>
									<?php if ( COMPLIANZ::$wizard->wizard_percentage_complete()
									           < 100
									) {
										printf( __( 'Your website is not ready for your selected regions yet.',
											'complianz-gdpr' ),
											cmplz_supported_laws() );
									} else {
										printf( __( 'Well done! Your website is ready for your selected regions.',
											'complianz-gdpr' ),
											cmplz_supported_laws() );
									} ?>
								</div>
							</div>
							<div
								class="cmplz-percentage-complete green c100 p<?php echo COMPLIANZ::$wizard->wizard_percentage_complete(); ?>">
								<span><?php echo COMPLIANZ::$wizard->wizard_percentage_complete(); ?>%</span>
								<div class="slice">
									<div class="bar"></div>
									<div class="fill"></div>
								</div>
							</div>


							<div class="cmplz-continue-wizard-btn">
								<?php if ( COMPLIANZ::$wizard->wizard_percentage_complete()
								           < 100
								) { ?>
									<div>
										<a href="<?php echo admin_url( 'admin.php?page=cmplz-wizard' ) ?>"
										   class="button cmplz cmplz-continue-button">
											<?php echo __( 'Continue',
												'complianz-gdpr' ); ?>
											<i class="fa fa-angle-right"></i></a>
									</div>
								<?php } ?>
							</div>


						</div>
						<table class="cmplz-steps-table cmplz-dashboard-text">
							<tr>
								<td></td>
								<td>
									<div
										class="cmplz-dashboard-info"><?php _e( 'Tasks',
											'complianz-gdpr' ) ?></div>
								</td>
							</tr>
							<?php

							$last_cookie_scan
								= COMPLIANZ::$cookie_admin->get_last_cookie_scan_date();
							if ( ! $last_cookie_scan ) {
								$this->task_count ++;
								$this->get_dashboard_element( sprintf( __( 'No cookies detected yet',
									'complianz-gdpr' ), $last_cookie_scan ),
									'error' );
							}

							do_action( 'cmplz_dashboard_elements_error' );

							$warnings      = $this->get_warnings( false );
							$warning_types
							               = apply_filters( 'cmplz_warnings_types',
								COMPLIANZ::$config->warning_types );
							$warning_count = $this->task_count
							                 + count( $warnings );

							foreach ( $warning_types as $key => $type ) {

								if ( in_array( $key, $warnings )
								     && isset( $type['label_error'] )
								) {
									if ( $type['type'] === 'document' ) {
										$warning_count --;
										continue;
									}

									if ( isset( $type['region'] )
									     && ! cmplz_has_region( $type['region'] )
									) {
										$warning_count --;
										continue;
									}

									$this->get_dashboard_element( $type['label_error'],
										'error' );
								}
							}

							if ( $warning_count <= 0 ) {
								$this->get_dashboard_element( __( "Nothing on your to do list",
									'complianz-gdpr' ), 'success' );
							}
							?>
							<tr>
								<td></td>
								<td>
									<div
										class="cmplz-dashboard-info"><?php _e( 'System status',
											'complianz-gdpr' ) ?></div>
								</td>
							</tr>

							<?php

							$regions = cmplz_get_regions();

							if ( count( $regions ) > 0 ) {
								$labels = array();
								foreach ( $regions as $region => $label ) {
									if ( ! isset( COMPLIANZ::$config->regions[ $region ]['label'] ) ) {
										continue;
									}
									$labels[]
										= COMPLIANZ::$config->regions[ $region ]['label'];
								}
								$labels = implode( '/', $labels );
								$this->get_dashboard_element( sprintf( __( 'Your site is configured for %s.',
									'complianz-gdpr' ), $labels ), 'success' );
							}

							do_action( 'cmplz_dashboard_elements_success' );

							if ( COMPLIANZ::$cookie_admin->site_needs_cookie_warning()
							     && COMPLIANZ::$wizard->wizard_completed_once()
							) {
								$this->get_dashboard_element( __( 'Your site requires a cookie banner, which has been enabled.',
									'complianz-gdpr' ), 'success' );
							}
							if ( ! COMPLIANZ::$cookie_admin->site_needs_cookie_warning() ) {
								$this->get_dashboard_element( __( 'Your site does not require a cookie banner. No cookie banner has been enabled.',
									'complianz-gdpr' ), 'success' );
							}
							if ( $last_cookie_scan ) {
								$this->get_dashboard_element( sprintf( __( 'Last cookie scan on %s',
									'complianz-gdpr' ), $last_cookie_scan ),
									'success' );
							}

							foreach ( $warning_types as $key => $type ) {
								if ( $type['type'] === 'document' ) {
									continue;
								}
								if ( isset( $type['region'] )
								     && ! cmplz_has_region( $type['region'] )
								) {
									continue;
								}
								if ( ! in_array( $key, $warnings ) ) {
									if ( isset( $type['label_ok'] ) ) {
										$this->get_dashboard_element( $type['label_ok'],
											'success' );
									}
								}
							}

							?>

						</table>
					</div>

					<div class="cmplz-dashboard-support cmplz-dashboard-item">
						<?php do_action( "cmplz_dashboard_second_block" ) ?>
					</div>

					<div class="cmplz-dashboard-documents cmplz-dashboard-item">
						<?php do_action( "cmplz_dashboard_third_block" ) ?>
					</div>
					<div class="cmplz-dashboard-footer">
						<?php do_action( "cmplz_dashboard_footer" ) ?>
					</div>
				</div>
			</div>
			<?php
		}

		public function get_dashboard_element(
			$content, $type = 'error', $img = ''
		) {
			$icon = "";
			switch ( $type ) {
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

			$type = ( $type == 'success' ) ? 'success' : 'error';

			?>
			<tr class="<?php echo "cmplz-" . $type ?>">
				<td><i class="fa <?php echo $icon ?>"></i></td>
				<td class="cmplz-second-column-docs"><?php echo $content ?></td>
				<td class="cmplz-last-column-docs"><?php echo $img ?></td>
			</tr>
			<?php
		}


		public function settings() {
			?>
			<div class="wrap cmplz-settings">
				<h1><?php _e( "Settings" ) ?></h1>
				<?php do_action( 'cmplz_show_message' ) ?>
				<form action="" method="post" enctype="multipart/form-data">


					<table class="form-table">
						<?php
						COMPLIANZ::$field->get_fields( 'settings' );

						COMPLIANZ::$field->save_button();

						?>

					</table>
				</form>
			</div>
			<?php
		}


		/**
		 * Get the html output for a help tip
		 *
		 * @param $str
		 */

		public function get_help_tip( $str ) {
			?>
			<span class="cmplz-tooltip-right tooltip-right"
			      data-cmplz-tooltip="<?php echo $str ?>">
              <span class="dashicons dashicons-editor-help"></span>
            </span>
			<?php
		}

		public function send_mail( $message, $from_name, $from_email ) {
			$subject = "Support request from $from_name";
			$to      = "support@complianz.io";
			$headers = array();
			add_filter( 'wp_mail_content_type', function ( $content_type ) {
				return 'text/html';
			} );

			$headers[] = "Reply-To: $from_name <$from_email>" . "\r\n";
			$success   = wp_mail( $to, $subject, $message, $headers );

			// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

			return $success;
		}


		/**
		 * Show a notice on upgrade to get opt in consent
		 */

		public function notice_optin_on_upgrade() {
			if ( cmplz_get_value( 'uses_cookies' ) === 'no' ) {
				return;
			}

			?>
			<div id="message"
			     class="error fade notice is-dismissible really-simple-plugins">
				<h2><?php _e( "Upgrade action required",
						"complianz-gdpr" ) ?></h2>
				<p>
					<?php echo __( "Complianz 4.0 is Live! This major update provides automatic comprehensive cookie descriptions by cookiedatabase.org!",
						"complianz-gdpr" ); ?>
					<br>
					<?php echo __( "Please check the following to ensure a smooth update:",
						"complianz-gdpr" ); ?>

				<ol>
					<li>
						<?php echo sprintf( __( "Issue a new %scookiescan%s (we will automatically start one 30 minutes after update)",
							"complianz-gdpr" ), '<a href="'
						                        . add_query_arg( array(
								'page'    => 'cmplz-wizard',
								'step'    => STEP_COOKIES,
								'section' => '1'
							), admin_url( 'admin.php' ) ) . '">', '</a>' ); ?>

					</li>
					<li>
						<?php echo sprintf( __( "Opt in to the %sCookiedatabase.org API%s",
							"complianz-gdpr" ), '<a href="'
						                        . add_query_arg( array(
								'page'    => 'cmplz-wizard',
								'step'    => STEP_COOKIES,
								'section' => '4'
							), admin_url( 'admin.php' ) ) . '">', '</a>' ); ?>

					</li>
					<li>
						<?php echo sprintf( __( "%sCheck the results%s of the cookiedatabase.org synchronization and complete missing descriptions.",
							"complianz-gdpr" ), '<a href="'
						                        . add_query_arg( array(
								'page'    => 'cmplz-wizard',
								'step'    => STEP_COOKIES,
								'section' => '5'
							), admin_url( 'admin.php' ) ) . '">', '</a>' ); ?>

					</li>
				</ol>

				<?php echo sprintf( __( "Complianz and the cookiedatabase.org community are working round the clock in adding more complete cookie descriptions, which will get added to your policy automatically. If you have any questions or issues regarding updating, please %scontact support%s or join the %scookiedatabase.org%s community!",
					"complianz-gdpr" ),
					'<a href="https://complianz.io/support" target="_blank">',
					'</a>',
					'<a href="https://cookiedatabase.org" target="_blank">',
					'</a>' ); ?>

				<p>
			</div>
			<?php
		}


	}
} //class closure
