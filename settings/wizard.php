<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_wizard" ) ) {
	class cmplz_wizard {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			//callback from settings
			add_action( 'cmplz_finish_wizard', array( $this, 'finish_wizard' ), 10, 1 );
			add_action( "cmplz_after_save_field", array($this,"cmplz_after_save_field"), 10, 4 );
		}

		static function this() {
			return self::$_this;
		}


		/**
		 * On click finish wizard
		 * @return void
		 */
		public function finish_wizard(){
			if (!cmplz_user_can_manage()) {
				return;
			}
			update_option('cmplz_wizard_completed_once', true );
			//ensure some default values
			cmplz_update_all_banners();
		}


		/**
		 * Do stuff when a field in the wizard is saved
		 *
		 * */
		public function cmplz_after_save_field( $field_id = false, $field_value = false, $prev_value = false, $type = false ): void {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}
			update_option( 'cmplz_documents_update_date', time() );
			$enable_categories = false;
			$uses_tagmanager   = cmplz_get_option( 'compile_statistics' ) === 'google-tag-manager' ? true : false;

			//if the cookie banner is enabled by the user, add complianz cookies to the cookies array
			if ($field_id === 'enable_cookie_banner' && $field_value==='yes'){
				$prefix = COMPLIANZ::$banner_loader->get_cookie_prefix();
				$cookies = [$prefix.'functional', $prefix.'statistics', $prefix.'preferences', $prefix.'marketing'];
				if (!cmplz_uses_statistic_cookies()) {
					unset($cookies[1]);
				}
				$cookies += [$prefix.'policy_id',$prefix.'consented_services', $prefix.'banner-status', $prefix.'saved_categories'];
				foreach ($cookies as $cookie) {
					$cookie = new CMPLZ_COOKIE($cookie);
					$cookie->save(true);
				}
			}

			/* if tag manager fires scripts, cats should be enabled for each cookiebanner. */
			if ( $field_id === 'compile_statistics' && $field_value === 'google-tag-manager' ) {
				$enable_categories = true;
			}

			if ( ( $field_id === 'consent_for_anonymous_stats' ) && $field_value === 'yes' ) {
				$enable_categories = true;
			}

			if ( $field_id === 'a_b_testing' && !$field_value ) {
				cmplz_update_option('a_b_testing_buttons', false );
			}

			//when ab testing is just enabled icw TM, cats should be enabled for each banner.
			if ( ( $field_id === 'a_b_testing_buttons' && $field_value === true && $prev_value === false ) ) {
				if ( $uses_tagmanager ) {
					$enable_categories = true;
				}
			}

			if ( $enable_categories ) {
				$banners = cmplz_get_cookiebanners();
				if ( ! empty( $banners ) ) {
					foreach ( $banners as $banner ) {
						$banner                 = new CMPLZ_COOKIEBANNER( $banner->ID );
						$banner->use_categories = 'view-preferences';
						$banner->save();
					}
				}
			}

			//save last changed date.
			COMPLIANZ::$banner_loader->update_cookie_policy_date();

			$fields = COMPLIANZ::$config->fields;
			//if the fieldname is from the "revoke cookie consent on change" list, change the policy if it's changed

			$ids = array_column($fields, 'id');
			$index = array_search($field_id, $ids);
			$field = isset($fields[$index]) ? $fields[$index] : false;
			if ( $field && isset( $field['revoke_consent_onchange'] ) && $field['revoke_consent_onchange'] ) {
				COMPLIANZ::$banner_loader->upgrade_active_policy_id();
				if ( !get_option( 'cmplz_generate_new_cookiepolicy_snapshot') ) update_option( 'cmplz_generate_new_cookiepolicy_snapshot', time(), false );
			}

			if ( $field_id === 'configuration_by_complianz'
			     || $field_id === 'gtm_code'
			     || $field_id === 'matomo_url'
			     || $field_id === 'matomo_tag_url'
			     || $field_id === 'matomo_site_id'
			     || $field_id === 'matomo_container_id'
			     || $field_id === 'ua_code'
			) {
				delete_option( 'cmplz_detected_stats_data' );
				delete_option( 'cmplz_detected_stats_type' );
			}

			//if the region is not EU anymore, and it was previously enabled for EU / eu_consent_regions, reset impressum
			if ( ( $field_id === 'regions' ) && cmplz_get_option('eu_consent_regions') === 'yes' ) {
				if ( is_array($field_value) && !in_array('eu', $field_value)) {
					cmplz_update_option('eu_consent_regions', 'no' );
				} elseif (is_string($field_value) && $field_value !== 'eu') {
					cmplz_update_option('eu_consent_regions', 'no' );
				}
			}

			if ( $field_id === 'us_states' || $field_id === 'purpose_personaldata' ) {
				add_action( 'shutdown', 'cmplz_update_cookie_policy_title', 12 );
			}

			if ( $field_id === 'children-safe-harbor' && $field_value === 'no' ) {
				cmplz_update_option('children-safe-harbor', 'no' );
			}

			$generate_css = false;
			//update google analytics service depending on anonymization choices
			if ( $field_id === 'compile_statistics'
			     || $field_id === 'compile_statistics_more_info'
			     || $field_id === 'compile_statistics_more_info_tag_manager'
			) {
				COMPLIANZ::$banner_loader->maybe_add_statistics_service();
				$generate_css = true;
			}

			/**
			 * If TCF was just disabled or enabled, regenerate the css.
			 */
			if ( $field_id === 'uses_ad_cookies_personalized' ) {
				$generate_css = true;
			}

			if ($field_id==='uses_ad_cookies_personalized' && ($field_value !== 'tcf' && $field_value!=='yes') ) {
				delete_option("cmplz_tcf_initialized");
			}
			if ( $field_id === 'uses_ad_cookies' && $field_value === 'no' ) {
				delete_option("cmplz_tcf_initialized");
				cmplz_update_option( 'uses_ad_cookies_personalized', 'no');
			}

			if ($field_id==='uses_ad_cookies_personalized' && ($field_value === 'tcf' && $field_value==='yes') ) {
				if (file_exists(cmplz_path . 'pro/tcf/class-tcf-admin.php')) {
					require_once cmplz_path . 'pro/tcf/class-tcf-admin.php';
					cmplz_tcf_change_settings();
				}
			}

			//when region or policy generation type is changed, update cookiebanner version to ensure the changed banner is loaded
			if ( $generate_css || $field_id === 'privacy-statement' || $field_id === 'regions' || $field_id === 'cookie-statement' ) {
				cmplz_update_all_banners();
			}

			// Disable German imprint appendix option when eu_consent_regions is no
			if ( $field_id === 'eu_consent_regions'
			     && $field_value === 'no'
			     && cmplz_get_option('german_imprint_appendix') === 'yes') {
				cmplz_update_option( 'german_imprint_appendix', 'no');
			}
		}

		/**
		 * Lock the wizard for further use while it's being edited by the current user.
		 *
		 * */

		public function lock_wizard(): void {
			set_transient( 'cmplz_wizard_locked_by_user', get_current_user_id(), apply_filters( "cmplz_wizard_lock_time", 2 * MINUTE_IN_SECONDS ) );
		}

		/**
		 * Check if the wizard is locked by another user
		 *
		 * */

		public function wizard_is_locked(): bool {
			$user_id      = get_current_user_id();
			$lock_user_id = $this->get_lock_user();
			return $lock_user_id && $lock_user_id !== $user_id;
		}

		/**
		 * Get the user that has locked the wizard
		 * @return int
		 */
		public function get_lock_user(): int {
			$user_id = get_transient( 'cmplz_wizard_locked_by_user' ) ? get_transient( 'cmplz_wizard_locked_by_user' ) : get_current_user_id();
			return (int) $user_id;
		}

		/**
		 * @return bool
		 */

		public function wizard_completed_once() {
			return get_option( 'cmplz_wizard_completed_once', false );
		}

	}


} //class closure
