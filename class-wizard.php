<?php
/*100% match*/

defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_wizard" ) ) {
	class cmplz_wizard {
		private static $_this;
		public $position;
		public $cookies = array();
		public $known_wizard_keys;
		public $total_steps = false;
		public $last_section;
		public $page_url;
		public $percentage_complete = false;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			//callback from settings
			add_action( 'cmplz_wizard_last_step', array( $this, 'wizard_last_step_callback' ), 10, 1 );

			//link action to custom hook
			add_action( 'cmplz_wizard_wizard', array( $this, 'wizard_after_step' ), 10, 1 );

			//process custom hooks
			add_action( 'admin_init', array( $this, 'process_custom_hooks' ) );
			add_action( 'complianz_before_save_wizard_option', array( $this, 'before_save_wizard_option' ), 10, 4 );
			add_action( 'complianz_after_save_wizard_option', array( $this, 'after_save_wizard_option' ), 10, 4 );
			add_action( 'cmplz_after_saved_all_fields', array( $this, 'after_saved_all_fields' ), 10, 1 );

			//dataleaks:
			add_action( 'cmplz_is_wizard_completed', array( $this, 'is_wizard_completed_callback' ) );
		}

		static function this() {
			return self::$_this;
		}


		public function is_wizard_completed_callback() {
			if ( $this->wizard_completed_once() ) {
				cmplz_notice( __( "Great, the main wizard is completed. This means the general data is already in the system, and you can continue with the next question. This will start a new, empty document.",
						'complianz-gdpr' ) );
			} else {
				$link = '<a href="' . admin_url( 'admin.php?page=cmplz-wizard' )
				        . '">';
				cmplz_notice( sprintf( __( "The wizard isn't completed yet. If you have answered all required questions, you just need to click 'finish' to complete it. In the wizard some general data is entered which is needed for this document. %sPlease complete the wizard first%s.",
					'complianz-gdpr' ), $link, "</a>" ), 'warning' );
			}
		}


		public function process_custom_hooks() {
			$wizard_type = ( isset( $_POST['wizard_type'] ) )
				? sanitize_title( $_POST['wizard_type'] ) : '';
			do_action( "cmplz_wizard_$wizard_type" );
		}

		/**
		 * Initialize a page in the wizard
		 * @param $page
		 */
		public function initialize( $page ) {
			$this->last_section = $this->last_section( $page, $this->step() );
			$this->page_url     = admin_url( 'admin.php?page=cmplz-' . $page );
			//if a post id was passed, we copy the contents of that page to the wizard settings.
			if ( isset( $_GET['post_id'] ) ) {
				$post_id = intval( $_GET['post_id'] );
				//get all fields for this page
				$fields = COMPLIANZ::$config->fields( $page );
				foreach ( $fields as $fieldname => $field ) {
					$fieldvalue = get_post_meta( $post_id, $fieldname, true );
					if ( $fieldvalue ) {
						if ( ! COMPLIANZ::$field->is_multiple_field( $fieldname ) ) {
							COMPLIANZ::$field->save_field( $fieldname, $fieldvalue );
						} else {
							$field[ $fieldname ] = $fieldvalue;
							COMPLIANZ::$field->save_multiple( $field );
						}
					}

				}
			}
		}

		/**
		 * Some actions after the last step has been completed
		 */
		public function wizard_last_step_callback() {
			$page = $this->wizard_type();

			if ( ! $this->all_required_fields_completed( $page ) ) {
				cmplz_notice( __( "Not all required fields are completed yet. Please check the steps to complete all required questions",
					'complianz-gdpr' ), 'warning' );
			} else {
				cmplz_notice( sprintf( '<h1>'
				                       . __( "All steps have been completed",
						'complianz-gdpr' ) . "</h1>"
				                       . __( "Click '%s' to complete the configuration. You can come back to change your configuration at any time.",
						'complianz-gdpr' ),
					__( "Finish", 'complianz-gdpr' ) ) );

				if ( COMPLIANZ::$cookie_admin->site_needs_cookie_warning() ) {

					$link_open = '<a href="'
					             . admin_url( 'admin.php?page=cmplz-cookiebanner' )
					             . '">';
					cmplz_notice( sprintf( __( "The cookie banner and cookie blocker are enabled. Please check your website if your configuration is working properly. Please read %sthese instructions%s to debug any issues while in safe mode. Safe mode is available under settings.",
						'complianz-gdpr' ), 				'<a  target="_blank" href="https://complianz.io/debugging-manual">', '</a>' )
							, 'warning' );
				}

			}

		}


		/**
		 * Process completion of setup
		 *
		 * */

		public function wizard_after_step() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			//clear document cache
			COMPLIANZ::$document->clear_shortcode_transients();

			//if the plugins page is reviewed, we can reset the privacy statement suggestions from WordPress.
			if ( cmplz_wp_privacy_version()
			     && ( $this->step( 'wizard' ) == STEP_PLUGINS )
			     && cmplz_get_value( 'privacy-statement' ) === 'generated'
			) {
				$policy_page_id
					= (int) get_option( 'wp_page_for_privacy_policy' );
				WP_Privacy_Policy_Content::_policy_page_updated( $policy_page_id );
				//check again, to update the cache.
				WP_Privacy_Policy_Content::text_change_check();
			}

			COMPLIANZ::$admin->reset_complianz_plugin_has_new_features();
			COMPLIANZ::$cookie_admin->reset_plugins_changed();
			COMPLIANZ::$cookie_admin->reset_cookies_changed();
			COMPLIANZ::$cookie_admin->reset_plugins_updated();

			//when clicking to the last page, or clicking finish, run the finish sequence.
			if ( isset( $_POST['cmplz-cookie-settings'] )
			     || isset( $_POST['cmplz-finish'] )
			     || ( isset( $_POST["step"] ) && $_POST['step'] == STEP_MENU
			          && isset( $_POST['cmplz-next'] ) )
			) {
				$this->set_wizard_completed_once();
			}


			//after clicking finish, redirect to dashboard.
			if ( isset( $_POST['cmplz-finish'] ) ) {
				wp_redirect( admin_url( 'admin.php?page=complianz' ) );
				exit();
			}

			if ( isset( $_POST['cmplz-cookie-settings'] ) ) {
				wp_redirect( admin_url( 'admin.php?page=cmplz-cookiebanner' ) );
				exit();
			}
		}

		/**
		 * Do stuff before a page from the wizard is saved.
		 *
		 * */

		public function before_save_wizard_option(
			$fieldname, $fieldvalue, $prev_value, $type
		) {

			update_option( 'cmplz_documents_update_date', time() );

			//only run when changes have been made
			if ( $fieldvalue === $prev_value ) {
				return;
			}

			$enable_categories = false;
			$tm_fires_scripts  = cmplz_get_value( 'fire_scripts_in_tagmanager' ) === 'yes' ? true : false;
			$uses_tagmanager   = cmplz_get_value( 'compile_statistics' ) === 'google-tag-manager' ? true : false;

			/* if tag manager fires scripts, cats should be enabled for each cookiebanner. */
			if ( ( $fieldname === 'fire_scripts_in_tagmanager' )
			     && $uses_tagmanager
			     && $tm_fires_scripts
			) {
				$enable_categories = true;
			}

			/* if tag manager fires scripts, cats should be enabled for each cookiebanner. */
			if ( ( $fieldname === 'consent_for_anonymous_stats' )
			     && $fieldvalue === 'yes'
			) {
				$enable_categories = true;
			}

			//when ab testing is just enabled icw TM, cats should be enabled for each banner.
			if ( ( $fieldname == 'a_b_testing' && $fieldvalue === true
			       && $prev_value == false )
			) {
				if ( $uses_tagmanager && $tm_fires_scripts ) {
					$enable_categories = true;
				}
			}

			if ( $enable_categories ) {
				$banners = cmplz_get_cookiebanners();
				if ( ! empty( $banners ) ) {
					foreach ( $banners as $banner ) {
						$banner                 = new CMPLZ_COOKIEBANNER( $banner->ID );
						$banner->use_categories = 'visible';
						$banner->save();
					}
				}
			}


			//when region or policy generation type is changed, update cookiebanner version to ensure the changed banner is loaded
			if ( $fieldname === 'privacy-statement' || $fieldname === 'regions'
			     || $fieldname === 'cookie-statement'
			) {
				cmplz_update_banner_version_all_banners();
			}

			//we can check here if certain things have been updated,
			COMPLIANZ::$cookie_admin->reset_cookies_changed();

			//save last changed date.
			COMPLIANZ::$cookie_admin->update_cookie_policy_date();

			//if the fieldname is from the "revoke cookie consent on change" list, change the policy if it's changed
			$fields = COMPLIANZ::$config->fields;
			$field  = $fields[ $fieldname ];
			if ( ( $fieldvalue != $prev_value )
			     && isset( $field['revoke_consent_onchange'] )
			     && $field['revoke_consent_onchange']
			) {
				COMPLIANZ::$cookie_admin->upgrade_active_policy_id();
				update_option( 'cmplz_generate_new_cookiepolicy_snapshot',
					true );
			}

			if ( $fieldname === 'configuration_by_complianz'
			     || $fieldname === 'GTM_code'
			     || $fieldname === 'matomo_url'
			     || $fieldname === 'matomo_site_id'
			     || $fieldname === 'UA_code'
			) {
				delete_option( 'cmplz_detected_stats_data' );
				delete_option( 'cmplz_detected_stats_type' );
			}
		}

		/**
		 * Handle some custom options after saving the wizard options
		 *
		 * After all fields have been saved
		 * @param $posted_fields
		 */

		public function after_saved_all_fields($posted_fields){
			//if the region is not EU anymore, and it was previously enabled for EU / eu_consent_regions, reset impressum
			if ( array_key_exists('cmplz_regions', $posted_fields) && cmplz_get_value('eu_consent_regions') === 'yes' && !cmplz_has_region('eu')
			) {
				cmplz_update_option('wizard', 'eu_consent_regions', 'no' );
			}
		}

		/**
		 * Handle some custom options after saving the wizard options
		 * @param string $fieldname
		 * @param mixed $fieldvalue
		 * @param mixed $prev_value
		 * @param string $type
		 */

		public function after_save_wizard_option( $fieldname, $fieldvalue, $prev_value, $type ) {
			if ( $fieldname == 'california' || $fieldname == 'purpose_personaldata' ) {
				add_action( 'shutdown', 'cmplz_update_cookie_policy_title', 12 );
			}

			if ( $fieldname === 'children-safe-harbor'
			     && cmplz_get_value( 'targets-children' ) === 'no'
			) {
				cmplz_update_option( 'wizard', 'children-safe-harbor', 'no' );
			}

			if ( $fieldname === 'use_cdb_api' ) {
				delete_option( 'cmplz_show_cookiedatabase_optin' );
			}

			if ( $fieldvalue === $prev_value ) {
				return;
			}

			//keep services in sync
			if ( $fieldname === 'socialmedia_on_site'
			     || $fieldname === 'thirdparty_services_on_site'
			) {
				COMPLIANZ::$cookie_admin->update_services();
			}

			//update google analytics service depending on anonymization choices
			if ( $fieldname === 'compile_statistics'
			     || $fieldname === 'compile_statistics_more_info'
			     || $fieldname === 'compile_statistics_more_info_tag_manager'
			) {
				COMPLIANZ::$cookie_admin->maybe_add_statistics_service();
			}

			$enable_categories_uk = $enable_categories_eu = false;
			if ( $fieldname === 'compile_statistics_more_info'
			     || $fieldname === 'compile_statistics_more_info_tag_manager'
			) {
				if ( COMPLIANZ::$cookie_admin->cookie_warning_required_stats( 'eu' ) ) {
					$enable_categories_eu = true;
				}
				if ( COMPLIANZ::$cookie_admin->cookie_warning_required_stats( 'uk' ) ) {
					$enable_categories_uk = true;
				}
			}

			if ( $enable_categories_eu || $enable_categories_uk ) {
				$banners = cmplz_get_cookiebanners();
				if ( ! empty( $banners ) ) {
					foreach ( $banners as $banner ) {
						$banner = new CMPLZ_COOKIEBANNER( $banner->ID );
						if ( $enable_categories_uk ) {
							$banner->use_categories_optinstats = 'visible';
						}
						if ( $enable_categories_eu ) {
							$banner->use_categories_optinstats = 'visible';
						}
						$banner->save();
					}

				}
			}
		}

		/**
		 * Get the next step with fields in it
		 * @param string $page
		 * @param int $step
		 *
		 * @return int
		 */
		public function get_next_not_empty_step( $page, $step ) {
			if ( ! COMPLIANZ::$field->step_has_fields( $page, $step ) ) {
				if ( $step >= $this->total_steps( $page ) ) {
					return $step;
				}
				$step ++;
				$step = $this->get_next_not_empty_step( $page, $step );
			}

			return $step;
		}

		/**
		 * Get the next section which is not empty
		 * @param string $page
		 * @param int $step
		 * @param int $section
		 *
		 * @return int|bool
		 */
		public function get_next_not_empty_section( $page, $step, $section ) {
			if ( ! COMPLIANZ::$field->step_has_fields( $page, $step,
				$section )
			) {
				//some keys are missing, so we need to count the actual number of keys.
				if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'] ) ) {
					$n = array_keys( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'] ); //<---- Grab all the keys of your actual array and put in another array
					$count = array_search( $section, $n ); //<--- Returns the position of the offset from this array using search

					//this is the actual list up to section key.
					$new_arr = array_slice( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'], 0, $count + 1, true );//<--- Slice it with the 0 index as start and position+1 as the length parameter.
					$section_count = count( $new_arr ) + 1;
				} else {
					$section_count = $section + 1;
				}

				$section ++;

				if ( $section_count > $this->total_sections( $page, $step ) ) {
					return false;
				}

				$section = $this->get_next_not_empty_section( $page, $step, $section );
			}

			return $section;
		}

		public function get_previous_not_empty_step( $page, $step ) {
			if ( ! COMPLIANZ::$field->step_has_fields( $page, $step ) ) {
				if ( $step <= 1 ) {
					return $step;
				}
				$step --;
				$step = $this->get_previous_not_empty_step( $page, $step );
			}

			return $step;
		}

		public function get_previous_not_empty_section( $page, $step, $section
		) {

			if ( ! COMPLIANZ::$field->step_has_fields( $page, $step,
				$section )
			) {
				$section --;
				if ( $section < 1 ) {
					return false;
				}
				$section = $this->get_previous_not_empty_section( $page, $step,
					$section );
			}

			return $section;
		}

		/*
		 * Lock the wizard for further use while it's being edited by the current user.
		 *
		 *
		 * */

		public function lock_wizard() {
			$user_id = get_current_user_id();
			set_transient( 'cmplz_wizard_locked_by_user', $user_id,
				apply_filters( "cmplz_wizard_lock_time",
					2 * MINUTE_IN_SECONDS ) );
		}


		/*
		 * Check if the wizard is locked by another user
		 *
		 *
		 * */

		public function wizard_is_locked() {
			$user_id      = get_current_user_id();
			$lock_user_id = $this->get_lock_user();
			if ( $lock_user_id && $lock_user_id != $user_id ) {
				return true;
			}

			return false;
		}

		public function get_lock_user() {
			return get_transient( 'cmplz_wizard_locked_by_user' );
		}


		public function wizard( $page ) {

			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( $this->wizard_is_locked() ) {
				$user_id   = $this->get_lock_user();
				$user      = get_user_by( "id", $user_id );
				$lock_time = apply_filters( "cmplz_wizard_lock_time",
						2 * MINUTE_IN_SECONDS ) / 60;

				cmplz_notice( sprintf( __( "The wizard is currently being edited by %s",
						'complianz-gdpr' ), $user->user_nicename ) . '<br>'
				              . sprintf( __( "If this user stops editing, the lock will expire after %s minutes.",
						'complianz-gdpr' ), $lock_time ), 'warning' );

				return;
			}
			//lock the wizard for other users.
			$this->lock_wizard();


			$this->initialize( $page );

			$section = $this->section();
			$step    = $this->step();

			if ( $this->section_is_empty( $page, $step, $section )
			     || ( isset( $_POST['cmplz-next'] )
			          && ! COMPLIANZ::$field->has_errors() )
			) {
				if ( COMPLIANZ::$config->has_sections( $page, $step )
				     && ( $section < $this->last_section )
				) {
					$section = $section + 1;
				} else {
					$step ++;
					$section = $this->first_section( $page, $step );
				}

				$step    = $this->get_next_not_empty_step( $page, $step );
				$section = $this->get_next_not_empty_section( $page, $step,
					$section );
				//if the last section is also empty, it will return false, so we need to skip the step too.
				if ( ! $section ) {
					$step    = $this->get_next_not_empty_step( $page,
						$step + 1 );
					$section = 1;
				}
			}

			if ( isset( $_POST['cmplz-previous'] ) ) {
				if ( COMPLIANZ::$config->has_sections( $page, $step )
				     && $section > $this->first_section( $page, $step )
				) {
					$section --;
				} else {
					$step --;
					$section = $this->last_section( $page, $step );
				}

				$step    = $this->get_previous_not_empty_step( $page, $step );
				$section = $this->get_previous_not_empty_section( $page, $step,
					$section );
			}

			?>

			<div id="cmplz-wizard">
				<div class="cmplz-header">

					<div class="cmplz-wizard-steps">
						<?php for (
							$i = 1; $i <= $this->total_steps( $page ); $i ++
						) {
							$active = ( $i == $step ) ? true : false;
							$url    = add_query_arg( array( 'step' => $i ),
								$this->page_url );
							if ( $this->post_id() ) {
								$url
									= add_query_arg( array( 'post_id' => $this->post_id() ),
									$url );
							}
							$step_completed
								= $this->required_fields_completed( $page, $i,
								false ) ? 'complete' : 'incomplete';
							?>
							<div class="cmplz-step <?php echo ( $active )
								? 'active'
								: "not-active"; ?> <?php echo $step_completed ?>">
								<div class="cmplz-step-wrap">
									<a href="<?php echo $url ?>">
										<span
											class="cmplz-step-count"><span><?php echo $i ?></span></span>
										<span
											class="cmplz-step-title"><?php echo COMPLIANZ::$config->steps[ $page ][ $i ]['title'] ?></span>
									</a>
								</div>
								<?php if ( $active ) { ?>
									<div
										class="cmplz-step-time"><?php printf( __( '%s min',
											'complianz-gdpr' ),
											$this->remaining_time( $page, $step,
												$section ) ) ?></div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="cmplz-body">
					<div class="cmplz-section-content">
						<h1><?php
							if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['title'] ) ) {
								$title
									     = COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['title'];
								$regions = $this->get_section_regions( $page,
									$step, $section );
								$title   .= $regions ? ' - ' . implode( ' | ',
										$regions ) : '';
								echo $title;
							}
							?></h1>
						<?php $this->get_content( $page, $step, $section ); ?>
					</div>
					<?php if ( COMPLIANZ::$config->has_sections( $page,
						$step )
					) { ?>
						<div
							class="cmplz-section-menu cmplz-step-<?php echo $step ?>">

							<?php

							for (
								$i = $this->first_section( $page, $step );
								$i <= $this->last_section( $page, $step ); $i ++
							) {
								if ( $this->section_is_empty( $page, $step,
									$i )
								) {
									continue;
								}
								$section_compare
									= $this->get_next_not_empty_section( $page,
									$step, $i );

								if ( $i < $section_compare ) {
									continue;
								}
								$active = ( $i == $section ) ? true : false;
								$icon
								        = ( $this->required_fields_completed( $page,
									$step, $i ) ) ? "check" : "fw";
								$url    = add_query_arg( array(
									'step'    => $step,
									'section' => $i
								), $this->page_url );
								if ( $this->post_id() ) {
									$url
										= add_query_arg( array( 'post_id' => $this->post_id() ),
										$url );
								}

								if ( $active ) {
									$icon = "angle-right";
								}
								?>
								<div
									class="cmplz-menu-item <?php echo ( $this->required_fields_completed( $page,
										$step, $i ) ) ? "cmplz-done"
										: "cmplz-to-do"; ?><?php if ( $active ) {
										echo " active";
									} ?>">
									<i class="fa fa-<?php echo $icon ?>"></i>
									<a href="<?php echo $url ?>"><?php
										$title
											   = COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $i ]['title'];
										$regions
											   = $this->get_section_regions( $page,
											$step, $i );
										$title .= $regions ? ' - '
										                     . implode( ' | ',
												$regions ) : '';
										echo $title;
										?></a>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php

		}

		/*
		 * If a section does not contain any fields to be filled, just drop it from the menu.
		 *
		 *
		 * */

		public function section_is_empty( $page, $step, $section ) {
			$section_compare = $this->get_next_not_empty_section( $page, $step,
				$section );
			if ( $section != $section_compare ) {
				return true;
			}

			return false;
		}

		public function enqueue_assets( $hook ) {
			if ( ( strpos( $hook, 'complianz' ) === false )
			     && strpos( $hook, 'cmplz' ) === false
			) {
				return;
			}

			wp_register_style( 'cmplz-wizard',
				cmplz_url . 'assets/css/wizard.css', false, cmplz_version );
			wp_enqueue_style( 'cmplz-wizard' );
		}


		/*
		 *
		 * Foreach required field, check if it's been answered
		 *
		 * if section is false, check all fields of the step.
		 *
		 *
		 * */


		public function required_fields_completed( $page, $step, $section ) {
			//get all required fields for this section, and check if they're filled in
			$fields = COMPLIANZ::$config->fields( $page, $step, $section );

			//get
			$fields = cmplz_array_filter_multidimensional( $fields, 'required',
				true );
			foreach ( $fields as $fieldname => $args ) {
				//if a condition exists, only check for this field if the condition applies.
				if ( isset( $args['condition'] )
				     || isset( $args['callback_condition'] )
				        && ! COMPLIANZ::$field->condition_applies( $args )
				) {
					continue;
				}
				$value = COMPLIANZ::$field->get_value( $fieldname );
				if ( empty( $value ) ) {
					return false;
				}

			}

			return true;
		}


		/*
		 * Check if all required fields are filled
		 *
		 *
		 * */

		public function all_required_fields_completed( $page ) {
			for ( $step = 1; $step <= $this->total_steps( $page ); $step ++ ) {
				if ( COMPLIANZ::$config->has_sections( $page, $step ) ) {
					for (
						$section = $this->first_section( $page, $step );
						$section <= $this->last_section( $page, $step );
						$section ++
					) {
						if ( ! $this->required_fields_completed( $page, $step,
							$section )
						) {
							return false;
						}
					}
				} else {
					if ( ! $this->required_fields_completed( $page, $step,
						false )
					) {
						return false;
					}
				}
			}

			return true;
		}

		/*
		 *
		 * Get the current selected post id for documents
		 *
		 *
		 * */

		public function post_id() {
			$post_id = false;
			if ( isset( $_GET['post_id'] ) || isset( $_POST['post_id'] ) ) {
				$post_id = ( isset( $_GET['post_id'] ) )
					? intval( $_GET['post_id'] ) : intval( $_POST['post_id'] );
			}

			return $post_id;
		}

		public function wizard_type() {
			$wizard_type = 'wizard';
			if ( isset( $_POST['wizard_type'] )
			     || isset( $_POST['wizard_type'] )
			) {
				$wizard_type = isset( $_POST['wizard_type'] )
					? $_POST['wizard_type'] : $_GET['wizard_type'];
			} else {
				if ( isset( $_GET['page'] ) ) {
					$wizard_type = str_replace( 'cmplz-', '', $_GET['page'] );
				}
			}

			return $wizard_type;
		}


		/**
		 * Get a notice style header with an intro above a step or section
		 *
		 *
		 * */

		public function get_intro( $page, $step, $section ) {
			//only show when in action
			$intro = '';
			if ( COMPLIANZ::$config->has_sections( $page, $step ) ) {
				if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['intro'] ) ) {
					$intro .= COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['intro'];
				}
			} else {
				if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['intro'] ) ) {
					$intro .= COMPLIANZ::$config->steps[ $page ][ $step ]['intro'];
				}
			}
			if ( strlen( $intro ) > 0 ) {
				$intro = '<div class="cmplz-wizard-intro">'
				         . cmplz_notice( $intro, 'notice', false, false )
				         . '</div>';
			}

			return $intro;
		}


		/**
		 * Retrieves the region to which this step applies
		 *
		 * @param $page
		 * @param $step
		 * @param $section
		 *
		 * @return array|bool
		 */
		public function get_section_regions( $page, $step, $section ) {
			//only show when in action
			$regions = false;

			if ( COMPLIANZ::$config->has_sections( $page, $step ) ) {
				if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['region'] ) ) {
					$regions
						= COMPLIANZ::$config->steps[ $page ][ $step ]['sections'][ $section ]['region'];
				}
			} else {
				if ( isset( COMPLIANZ::$config->steps[ $page ][ $step ]['region'] ) ) {
					$regions
						= COMPLIANZ::$config->steps[ $page ][ $step ]['region'];
				}
			}

			if ( $regions ) {
				if ( ! is_array( $regions ) ) {
					$regions = array( $regions );
				}

				foreach ( $regions as $index => $region ) {
					if ( ! cmplz_has_region( $region ) ) {
						unset( $regions[ $index ] );
					}
				}
				if ( count( $regions ) == 0 ) {
					$regions = false;
				}

			}
			if ( $regions ) {
				$regions = array_map( 'strtoupper', $regions );
			}

			return $regions;
		}


		/**
		 * Get content of wizard for a page/step/section combination
		 *
		 *
		 * */


		public function get_content( $page, $step, $section = false ) {
			$regions = $this->get_section_regions( $page, $step, $section );
			cmplz_flag( $regions );

			if ( isset( $_POST['cmplz-save'] ) ) {
				cmplz_notice( __( "Changes saved successfully",
					'complianz-gdpr' ), 'success', true );
			}

			if ( $page != 'wizard' ) {
				if ( $this->post_id() && $step == 2
				     && ( ! $section
				          || $section == 1 )
				) {
					$link = '<a href="' . admin_url( 'edit.php?post_type='
					                                 . get_post_type( $this->post_id() ) )
					        . '">';

					$link_pdf = '<a href="' . admin_url( "post.php?post="
					                                     . $this->post_id()
					                                     . "&action=edit" )
					            . '">';
					cmplz_notice( sprintf( __( 'This document has been saved as "%s" (%sview%s). You can view existing documents on the %soverview page%s',
						'complianz-gdpr' ), get_the_title( $this->post_id() ),
						$link_pdf, '</a>', $link, '</a>' ), 'success', false );
				} elseif ( $step == 1 ) {
					delete_option( 'complianz_options_' . $page );

					if ( strpos( $page, 'processing' ) !== false ) {
						$about = __( 'Processing Agreements',
							'complianz-gdpr' );
						$link_article
						       = '<a href="https://complianz.io/what-are-processing-agreements">';
					} else {
						$about = __( 'dataleak reports', 'complianz-gdpr' );
						$link_article
						       = '<a href="https://complianz.io/what-are-dataleak-reports">';
					}

					cmplz_notice( sprintf( __( "To learn what %s are and what you need them for, please read this  %sarticle%s",
						'complianz-gdpr' ), $about, $link_article, '</a>' ) );

				}
			}

			?>
			<?php echo $this->get_intro( $page, $step, $section ) ?>
			<form action="<?php echo $this->page_url ?>" method="POST">
				<input type="hidden" value="<?php echo $page ?>"
				       name="wizard_type">
				<?php if ( $this->post_id() ) { ?>
					<input type="hidden" value="<?php echo $this->post_id() ?>"
					       name="post_id">
				<?php } ?>

				<?php
				COMPLIANZ::$field->get_fields( $page, $step, $section ); ?>

				<input type="hidden" value="<?php echo $step ?>" name="step">
				<input type="hidden" value="<?php echo $section ?>"
				       name="section">
				<?php wp_nonce_field( 'complianz_save', 'complianz_nonce' ); ?>
				<div class="cmplz-buttons-container">

					<?php if ( $step > 1 || $section > 1 ) { ?>
						<div class="cmplz-button cmplz-previous icon">

							<input class="" type="submit"
							       name="cmplz-previous"
							       value="<?php _e( "Previous",
								       'complianz-gdpr' ) ?>">

						</div>
					<?php } ?>
					<?php if ( $step < $this->total_steps( $page ) ) { ?>
						<div class="cmplz-button cmplz-next">

							<input class="fa " type="submit"
							       name="cmplz-next"
							       value="<?php _e( "Next",
								       'complianz-gdpr' ) ?>">

						</div>
					<?php } ?>

					<?php
					$hide_finish_button = false;
					if ( strpos( $page, 'dataleak' ) !== false
					     && ! COMPLIANZ::$dataleak->dataleak_has_to_be_reported()
					) {
						$hide_finish_button = true;
					}
					$label = ( strpos( $page, 'dataleak' ) !== false
					           || strpos( $page, 'processing' ) !== false )
						? __( "View document", 'complianz-gdpr' )
						: __( "Finish", 'complianz-gdpr' );
					?>
					<?php if ( ! $hide_finish_button
					           && ( $step == $this->total_steps( $page ) )
					           && $this->all_required_fields_completed( $page )
					) {
						/*
						 * Only for the wizard type, should there optional be a button redirecting to the cookie settings page
						 * */
						if ( $page == 'wizard'
						     && COMPLIANZ::$cookie_admin->site_needs_cookie_warning()
						) { ?>
							<div class="cmplz-button cmplz-next">
								<input class="" type="submit"
								       name="cmplz-cookie-settings"
								       value="<?php _e( "Finish and check cookie banner settings",
									       'complianz-gdpr' ) ?>">
							</div>
						<?php } else { ?>
							<div class="cmplz-button cmplz-next">
								<input class="" type="submit"
								       name="cmplz-finish"
								       value="<?php echo $label ?>">
							</div>
						<?php }
						?>

					<?php } ?>

					<?php if ( ( $step > 1 || $page == 'wizard' )
					           && $step < $this->total_steps( $page )
					) {
						if ( $step != STEP_COOKIES
						     || ( $section != 5
						          && $section != 6 )
						) {
							?>

							<div class="cmplz-button cmplz-save">
								<input class="fa " type="submit"
								       name="cmplz-save"
								       value="<?php _e( "Save",
									       'complianz-gdpr' ) ?>">

							</div>
						<?php }
					} ?>
				</div>
			</form>
			<?php
		}

		public function get_type( $post_id = false ) {
			$page = false;
			if ( $post_id ) {
				$region    = COMPLIANZ::$document->get_region( $post_id );
				$post_type = get_post_type( $post_id );
				$page      = str_replace( 'cmplz-', '', $post_type ) . '-'
				             . $region;
			}
			if ( isset( $_GET['page'] ) ) {
				$page = str_replace( 'cmplz-', '',
					sanitize_title( $_GET['page'] ) );
			}

			return $page;
		}


		public function wizard_completed_once() {
			return get_option( 'cmplz_wizard_completed_once' );
		}


		public function set_wizard_completed_once() {
			update_option( 'cmplz_wizard_completed_once', true );
		}

		public function step( $page = false ) {
			$step = 1;
			if ( ! $page ) {
				$page = $this->wizard_type();
			}

			$total_steps = $this->total_steps( $page );

			if ( isset( $_GET["step"] ) ) {
				$step = intval( $_GET['step'] );
			}

			if ( isset( $_POST["step"] ) ) {
				$step = intval( $_POST['step'] );
			}

			if ( $step > $total_steps ) {
				$step = $total_steps;
			}

			if ( $step <= 1 ) {
				$step = 1;
			}

			return $step;
		}

		public function section() {
			$section = 1;
			if ( isset( $_GET["section"] ) ) {
				$section = intval( $_GET['section'] );
			}

			if ( isset( $_POST["section"] ) ) {
				$section = intval( $_POST['section'] );
			}

			if ( $section > $this->last_section ) {
				$section = $this->last_section;
			}

			if ( $section <= 1 ) {
				$section = 1;
			}

			return $section;
		}

		/**
		 * Get total number of steps for a page
		 *
		 * @param $page
		 *
		 * @return int
		 */

		public function total_steps( $page ) {
			return count( COMPLIANZ::$config->steps[ $page ] );
		}

		public function total_sections( $page, $step ) {
			if ( ! isset( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'] ) ) {
				return 0;
			}

			return count( COMPLIANZ::$config->steps[ $page ][ $step ]['sections'] );
		}


		public function last_section( $page, $step ) {
			if ( ! isset( COMPLIANZ::$config->steps[ $page ][ $step ]["sections"] ) ) {
				return 1;
			}

			$array = COMPLIANZ::$config->steps[ $page ][ $step ]["sections"];

			return max( array_keys( $array ) );

		}

		public function first_section( $page, $step ) {
			if ( ! isset( COMPLIANZ::$config->steps[ $page ][ $step ]["sections"] ) ) {
				return 1;
			}

			$arr       = COMPLIANZ::$config->steps[ $page ][ $step ]["sections"];
			$first_key = key( $arr );

			return $first_key;
		}


		public function remaining_time( $page, $step, $section = false ) {

			//get remaining steps including this one
			$time        = 0;
			$total_steps = $this->total_steps( $page );
			for ( $i = $total_steps; $i >= $step; $i -- ) {
				$sub = 0;

				//if we're on a step with sections, we should add the sections that still need to be done.
				if ( ( $step == $i )
				     && COMPLIANZ::$config->has_sections( $page, $step )
				) {

					for (
						$s = $this->last_section( $page, $i ); $s >= $section;
						$s --
					) {
						$subsub         = 0;
						$section_fields = COMPLIANZ::$config->fields( $page,
							$step, $s );
						foreach (
							$section_fields as $section_fieldname =>
							$section_field
						) {
							if ( isset( $section_field['time'] ) ) {
								$sub    += $section_field['time'];
								$subsub += $section_field['time'];
								$time   += $section_field['time'];
							}
						}
					}
				} else {
					$fields = COMPLIANZ::$config->fields( $page, $i, false );

					foreach ( $fields as $fieldname => $field ) {
						if ( isset( $field['time'] ) ) {
							$sub  += $field['time'];
							$time += $field['time'];
						}

					}
				}
			}

			return round( $time + 0.45 );
		}

		/*
		 *
		 * Check which percentage of the wizard is completed
		 *
		 *
		 * @return int
		 * */


		public function wizard_percentage_complete()  //($page)
		{
			//store to make sure it only runs once.
			if ( $this->percentage_complete !== false ) {
				return $this->percentage_complete;
			}
			$total_fields     = 0;
			$completed_fields = 0;
			$total_steps      = $this->total_steps( 'wizard' );
			for ( $i = 1; $i <= $total_steps; $i ++ ) {
				$fields = COMPLIANZ::$config->fields( 'wizard', $i, false );

				foreach ( $fields as $fieldname => $field ) {
					//is field required
					$required = isset( $field['required'] ) ? $field['required']
						: false;
					if ( ( isset( $field['condition'] )
					       || isset( $field['callback_condition'] ) )
					     && ! COMPLIANZ::$field->condition_applies( $field )
					) {
						$required = false;
					}
					if ( $required ) {
						$value = cmplz_get_value( $fieldname );
						$total_fields ++;
						if ( ! empty( $value ) ) {
							$completed_fields ++;
						}
					}
				}
			}

			$total_warnings     = count( COMPLIANZ::$config->warning_types );
			$completed_warnings = $total_warnings
			                      - count( COMPLIANZ::$admin->get_warnings( false,
					false, array( 'no-dnt' ) ) );

			$completed_fields += $completed_warnings;
			$total_fields     += $total_warnings;

			$pages = COMPLIANZ::$document->get_required_pages();
			foreach ( $pages as $region => $region_pages ) {
				foreach ( $region_pages as $type => $page ) {
					if ( COMPLIANZ::$document->page_exists( $type, $region ) ) {
						$completed_fields ++;
					}
					$total_fields ++;
				}
			}

			$percentage = round( 100 * ( $completed_fields / $total_fields )
			                     + 0.45 );

			$this->percentage_complete = $percentage;

			return $percentage;

		}

	}


} //class closure
