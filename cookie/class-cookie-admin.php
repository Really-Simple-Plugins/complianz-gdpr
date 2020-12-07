<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_cookie_admin" ) ) {
	class cmplz_cookie_admin {
		private static $_this;
		public $position;
		public $cookies = array();
		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_jquery' ), PHP_INT_MAX - 100 );

			$scan_in_progress = isset( $_GET['complianz_scan_token'] )
			                    && ( sanitize_title( $_GET['complianz_scan_token'] )
			                         == get_option( 'complianz_scan_token' ) );
			if ( $scan_in_progress ) {
				add_action( 'wp_print_footer_scripts',
					array( $this, 'test_cookies' ), 10, 2 );
			} else {
				add_action( 'admin_init',
					array( $this, 'track_cookie_changes' ) );
			}

			if ( ! is_admin() && get_option( 'cmplz_wizard_completed_once' ) ) {
				if ( $this->site_needs_cookie_warning() ) {
					add_action( 'wp_print_footer_scripts',
						array( $this, 'inline_cookie_script' ),
						PHP_INT_MAX - 50 );
					add_action( 'wp_enqueue_scripts',
						array( $this, 'enqueue_assets' ), PHP_INT_MAX - 50 );
				} else {
					add_action( 'wp_print_footer_scripts',
						array( $this, 'inline_cookie_script_no_warning' ), 10,
						2 );
				}
			}

            //cookie script for styling purposes on backend
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			add_action( 'admin_footer', array( $this, 'run_cookie_scan' ) );
			add_action( 'wp_head', array( $this, 'detect_conflicts' ) );
			add_action( 'wp_ajax_cmplz_store_console_errors', array( $this, 'store_console_errors' ) );
			add_action( 'wp_ajax_load_detected_cookies', array( $this, 'load_detected_cookies' ) );
			add_action( 'wp_ajax_cmplz_get_scan_progress', array( $this, 'get_scan_progress' ) );
			add_action( 'wp_ajax_cmplz_run_sync', array( $this, 'run_sync' ) );
			add_action( 'admin_init', array( $this, 'run_sync_on_update' ) );

			add_action( 'wp_ajax_store_detected_cookies', array( $this, 'store_detected_cookies' ) );
			add_action( 'plugins_loaded', array( $this, 'resync' ), 11, 2 );
			add_action( 'wp_ajax_cmplz_report_unknown_cookies', array( $this, 'ajax_report_unknown_cookies' ) );
			add_action( 'wp_ajax_cmplz_delete_snapshot', array( $this, 'ajax_delete_snapshot' ) );
			add_action( 'admin_init', array( $this, 'force_snapshot_generation' ) );
			add_action( 'plugins_loaded', array( $this, 'rescan' ), 20, 2 );
			add_action( 'plugins_loaded', array( $this, 'clear_cookies' ), 20, 2 );
			add_action( 'cmplz_notice_statistics_script', array( $this, 'statistics_script_notice' ) );

			//callback from settings
			add_action( 'cmplz_cookie_scan', array( $this, 'scan_progress' ), 10, 1 );
			add_action( 'cmplz_cookiedatabase_sync', array( $this, 'sync_progress' ), 10, 1 );
			add_action( 'cmplz_statistics_script', array( $this, 'get_statistics_script' ), 10 );
			add_action( 'cmplz_tagmanager_script', array( $this, 'get_tagmanager_script' ), 10 );
			add_action( 'cmplz_before_statistics_script', array( $this, 'add_gtag_js' ), 10 );
			add_filter( 'cmplz_script_class', array( $this, 'add_script_classes_for_stats' ), 10, 3 );
			add_action( 'wp_ajax_cmplz_edit_item', array( $this, 'ajax_edit_item' ) );
			add_action( 'wp_ajax_cmplz_get_list', array( $this, 'ajax_get_list' ) );
			add_filter( 'cmplz_consenttype', array( $this, 'maybe_filter_consenttype' ), 10, 2 );
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Front end javascript error detection.
		 * Only for site admins
		 */

		public function detect_conflicts(){

			if ( !cmplz_user_can_manage() ) return;

			//no back-end warnings
			if ( is_admin() ) return;

			//not when scan runs
			if ( isset( $_GET['complianz_scan_token'] ) ) return;

			if ( !$this->site_needs_cookie_warning() ) return;

			$nonce = wp_create_nonce('cmplz-detect-errors');
			?>
			<script type="text/javascript">
				var cmplz_jquery_detected = 'jquery-detected';
				if (typeof jQuery === 'undefined') {
					cmplz_jquery_detected = 'no-jquery-detected';
				}
				var request = new XMLHttpRequest();
				request.open('POST', '<?php echo add_query_arg(
					array(
						'type' => 'jquery',
						'nonce' => $nonce,
						'action'=>'cmplz_store_console_errors'
					),
					admin_url('admin-ajax.php')
				)
				?>', true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.send(cmplz_jquery_detected);
				var error_ocurred = false;
				window.onerror = function (msg, url, lineNo, columnNo, error) {
					error_ocurred = true;
					var request = new XMLHttpRequest();
					request.open('POST', '<?php echo add_query_arg(
						array(
							'type' => 'errors',
							'nonce' => $nonce,
							'action'=>'cmplz_store_console_errors'
						),
						admin_url('admin-ajax.php')
					)
					?>', true);
					var data = [];
					data.push(msg);
					data.push(lineNo);
					data.push(url.substring(0, url.indexOf('?')));
					request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
					request.send(data);
				};

				//if no error occurred after 3 seconds, send a reset signal
				setTimeout(function () {
					if (!error_ocurred) {
						var request = new XMLHttpRequest();
						request.open('POST', '<?php echo add_query_arg(
							array(
								'type' => 'errors',
								'nonce' => $nonce,
								'action'=>'cmplz_store_console_errors'
							),
							admin_url('admin-ajax.php')
						)
							?>', true);
						var data = [];
						data.push('no-errors');
						request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
						request.send(data);
					}

				}, 3000);
			</script>
			<?php
		}

		/**
		 * Store detected errors
		 * Only for site admins
		 */

		public function store_console_errors(){
			if ( !cmplz_user_can_manage() ) return;

			if ( !$this->site_needs_cookie_warning() ) return;
			$success = false;

			if ( isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'cmplz-detect-errors') ) {
				if ( $_GET['type'] === 'jquery' ) {
					if (isset($_POST['no-jquery-detected'])){
						update_option('cmplz_detected_missing_jquery', true );
					} else {
						update_option('cmplz_detected_missing_jquery', false );
					}
				} else {
					if ( isset($_POST['no-errors']) ){
						update_option('cmplz_detected_console_errors', false);
						$success = true;
					} else {
						$errors = array_keys(array_map('sanitize_text_field', $_POST));
						if (count($errors)>0){
							$errors = explode(',', str_replace( site_url(),'',$errors[0]) );
							if ( isset($errors[1]) && $errors[1] != 0 ) update_option('cmplz_detected_console_errors', $errors);
							$success = true;
						}
					}

				}
			}

			$response = json_encode( array(
				'success' => $success,
			) );
			header( "Content-Type: application/json" );
			echo $response;
			exit;
		}

		/**
		 * enqueue if not available yet
		 */
		public function maybe_enqueue_jquery() {
			wp_enqueue_script( 'jquery' );
		}

		/**
		 * When special data is processed, Canada requires optinstats consenttype
		 *
		 * @param string $consenttype
		 * @param string $region
		 *
		 * @return string $consenttype
		 */

		public function maybe_filter_consenttype( $consenttype, $region ) {
			if ( $region === 'ca' && cmplz_site_shares_data()
			     && cmplz_get_value( 'sensitive_information_processed' )
			        === 'yes'
			) {
				$consenttype = 'optin';
			}

			return $consenttype;
		}

		/**
		 * create select html for services
		 *
		 * @param bool $selected_value
		 * @param      $language
		 *
		 * @return string
		 */

		public function get_services_options( $selected_value = false, $language
		) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$services = $this->get_services( array( 'language' => $language ) );
			$html     = '<option value="" >'
			            . esc_html( __( 'Select or add a service',
					'complianz-gdpr' ) ) . '</option>';
			foreach ( $services as $service ) {
				if ( strlen( $service->name ) == 0 ) {
					continue;
				}

				$sel  = ( $selected_value == $service->name )
					? 'selected="selected"' : '';
				$html .= '<option value="' . esc_html( $service->name ) . '" '
				         . $sel . '>' . esc_html( $service->name )
				         . '</option>';
			}

			if ( strlen( $html ) == 0 ) {
				$html = '<option value="">'
				        . esc_html( __( 'No services listed',
						'complianz-gdpr' ) ) . '</option>';
			}

			return $html;
		}


		/**
		 * create select html for service types
		 *
		 * @param bool $selected_value
		 * @param      $language
		 *
		 * @return string
		 */

		public function get_serviceTypes_options(
			$selected_value = false, $language
		) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return '';
			}

			$html = '<option value="0" >'
			        . esc_html( __( 'Select a service type',
					'complianz-gdpr' ) ) . '</option>';

			$serviceTypes = get_transient( 'cmplz_serviceTypes_' . $language );
			if ( ! $serviceTypes ) {
				$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL )
				            . 'v1/servicetypes/' . $language;

				$response = wp_remote_get( $endpoint );
				$status   = wp_remote_retrieve_response_code( $response );
				$body     = wp_remote_retrieve_body( $response );
				if ( $status == 200 ) {
					$body         = json_decode( $body );
					$serviceTypes = $body->data;
					if ( $language === 'en' ) {
						foreach ( $serviceTypes as $serviceType ) {
							if ( strlen( $serviceType ) == 0 ) {
								continue;
							}
							cmplz_register_translation( $serviceType,
								$serviceType );
						}
					}

					set_transient( 'cmplz_serviceTypes_' . $language,
						$serviceTypes, WEEK_IN_SECONDS );
				}
			}
			if ( $serviceTypes ) {

				foreach ( $serviceTypes as $serviceType ) {

					if ( strlen( $serviceType ) == 0 ) {
						continue;
					}
					$sel  = ( $selected_value == $serviceType )
						? 'selected="selected"' : '';
					$html .= '<option value="' . esc_html( $serviceType ) . '" '
					         . $sel . '>' . esc_html( $serviceType )
					         . '</option>';
				}
			}

			if ( strlen( $html ) == 0 ) {
				$html = '<option value="">'
				        . esc_html( __( 'No service types listed',
						'complianz-gdpr' ) ) . '</option>';
			}

			return $html;
		}


		/**
		 * create select html for purposes
		 *
		 * @param bool $selected_value
		 * @param      $language
		 *
		 * @return string
		 */

		public function get_cookiePurpose_options(
			$selected_value = false, $language
		) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$html = '<option value="0" >' . esc_html( __( 'Select a purpose',
					'complianz-gdpr' ) ) . '</option>';

			$cookiePurposes = get_transient( 'cmplz_purposes_' . $language );
			if ( ! $cookiePurposes ) {
				$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL )
				            . 'v1/cookiepurposes/' . $language;
				$response = wp_remote_get( $endpoint );
				$status   = wp_remote_retrieve_response_code( $response );
				$body     = wp_remote_retrieve_body( $response );
				if ( $status == 200 ) {
					$body           = json_decode( $body );
					$cookiePurposes = $body->data;
					if ( $language === 'en' ) {
						foreach ( $cookiePurposes as $cookiePurpose ) {
							if ( strlen( $cookiePurpose ) == 0 ) {
								continue;
							}
							cmplz_register_translation( $cookiePurpose,
								$cookiePurpose );
						}
					}

					set_transient( 'cmplz_purposes_' . $language,
						$cookiePurposes, WEEK_IN_SECONDS );
				}
			}

			if ( $cookiePurposes ) {
				foreach ( $cookiePurposes as $cookiePurpose ) {

					if ( strlen( $cookiePurpose ) == 0 ) {
						continue;
					}
					$sel  = ( $selected_value == $cookiePurpose )
						? 'selected="selected"' : '';
					$html .= '<option value="' . esc_html( $cookiePurpose )
					         . '" ' . $sel . '>' . esc_html( $cookiePurpose )
					         . '</option>';
				}
			}

			if ( strlen( $html ) == 0 ) {
				$html = '<option value="">'
				        . esc_html( __( 'No purposes listed',
						'complianz-gdpr' ) ) . '</option>';
			}

			return $html;
		}


		/**
		 * get list item html for one cookie setting form
		 *
		 * @param $tmpl
		 * @param $name
		 * @param $language
		 *
		 * @return string
		 */

		public function get_cookie_list_item_html( $tmpl, $name, $language ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$cookie = new CMPLZ_COOKIE( $name, $language );
			if ( ! $cookie->ID ) {
				return '';
			}

			$sync         = COMPLIANZ::$cookie_admin->use_cdb_api()
				? $cookie->sync : false;
			$syncDisabled = $syncDisabledClass = '';
			if ( ! COMPLIANZ::$cookie_admin->use_cdb_api() ) {
				$syncDisabled      = 'disabled="disabled"';
				$syncDisabledClass = "cmplz-disabled";
			}

			$disabled      = $sync ? 'disabled="disabled"' : false;
			$disabledClass = $sync ? 'cmplz-disabled' : false;
			$sync          = $sync ? 'checked="checked"' : '';

			$isPersonalData = $cookie->isPersonalData == 1 ? 'checked="checked"'
				: '';
			$showOnPolicy   = $cookie->showOnPolicy == 1 ? 'checked="checked"'
				: '';
			$services       = $this->get_services_options( $cookie->service,
				$language );
			$cookiePurposes
			                = $this->get_cookiePurpose_options( $cookie->purpose,
				$language );

			$link = '';
			if ( cmplz_get_value( 'use_cdb_links' ) === 'yes'
			     && strlen( $cookie->slug ) !== 0
			) {
				$service_slug = ( strlen( $cookie->service ) === 0 )
					? 'unknown-service' : $cookie->service;
				$link
				              = '<a target="_blank" href="https://cookiedatabase.org/cookie/'
				                . $service_slug . '/' . $cookie->slug . '">'
				                . __( "View cookie on cookiedatabase.org",
						"complianz-gdpr" ) . '</a>';
			}

			$cookie_html = str_replace(
				array(
					'{cookie_id}',
					'{name}',
					'{disabled}',
					'{disabledClass}',
					'{services}',
					'{retention}',
					'{sync}',
					'{cookieFunction}',
					'{purposes}',
					'{isPersonalData}',
					'{collectedPersonalData}',
					'{showOnPolicy}',
					'{syncDisabled}',
					"{syncDisabledClass}",
					"{link}",
				),
				array(
					$cookie->ID,
					$cookie->name,
					$disabled,
					$disabledClass,
					$services,
					esc_html( $cookie->retention ),
					$sync,
					$cookie->cookieFunction,
					$cookiePurposes,
					$isPersonalData,
					$cookie->collectedPersonalData,
					$showOnPolicy,
					$syncDisabled,
					$syncDisabledClass,
					$link,
				),
				$tmpl );

			$icons  = '';
			$status = $cookie->complete || $cookie->ignored ? 'success'
				: 'error';
			$icons  .= $this->get_icon( array(
				'status'       => $status,
				'icon_success' => 'check',
				'icon_error'   => 'times',
				'desc_success' => __( "The data for this cookie is complete",
					"complianz-gdpr" ),
				'desc_error'   => __( "This cookie has missing fields",
					"complianz-gdpr" ),
			) );
			if ( ! $cookie->sync ) {
				$status = 'disabled';
			} elseif ( $cookie->synced ) {
				$status = 'success';
			} else {
				$status = 'error';
			}

			$icons  .= $this->get_icon( array(
				'status'        => $status,
				'icon_success'  => 'sync-alt',
				'desc_success'  => __( "This cookie has been synchronized with cookiedatabase.org",
					"complianz-gdpr" ),
				'desc_error'    => __( "This cookie is not synchronized with cookiedatabase.org",
					"complianz-gdpr" ),
				'desc_disabled' => __( "Synchronization with cookiedatabase.org is not enabled for this cookie",
					"complianz-gdpr" ),
			) );
			$status = $cookie->showOnPolicy && ! $cookie->ignored ? 'success'
				: 'disabled';

			$icons  .= $this->get_icon( array(
				'status'        => $status,
				'icon_success'  => 'file',
				'desc_success'  => __( "This cookie will be on your Cookie Policy",
					"complianz-gdpr" ),
				'desc_disabled' => __( "This cookie is not shown on the Cookie Policy",
					"complianz-gdpr" ),
			) );
			$status = $cookie->old ? 'error' : 'success';

			$icons .= $this->get_icon( array(
				'status'       => $status,
				'icon_success' => 'calendar-check',
				'icon_error'   => 'calendar-times',
				'desc_success' => __( "This cookie has recently been detected",
					"complianz-gdpr" ),
				'desc_error'   => __( "This cookie has not been detected on your site in the last three months",
					"complianz-gdpr" ),
			) );

			$icons       = '<span style="float:right">' . $icons . '</span>';
			$notice      = ( $cookie->old )
				? cmplz_notice( __( 'This cookie has not been found in the scan for three months. Please check if you are still using this cookie',
					'complianz-gdpr' ), 'warning', false, false ) : '';
			$cookie_html = $notice . $cookie_html;
			$ignored     = ( $cookie->ignored ) ? ' <i>'
			                                      . __( '(Administrator cookie, will be ignored)',
					'complianz-gdpr' ) . '</i>' : '';
			$membersOnly = ( ! $cookie->ignored
			                 && cmplz_get_value( 'wp_admin_access_users' )
			                    === 'no'
			                 && $cookie->isMembersOnly ) ? ' <i>'
			                                               . __( '(Logged in users only, will be ignored)',
					'complianz-gdpr' ) . '</i>' : '';
			$html        = cmplz_panel( sprintf( __( 'Cookie "%s"%s%s',
				'complianz-gdpr' ), $cookie->name, $ignored, $membersOnly ),
				$cookie_html, $icons, false, false );
			if ( $cookie->deleted ) {
				$html = str_replace( array( 'cmplz-toggle-active' ),
					array( 'cmplz-toggle-active cmplz-deleted' ), $html );
			}
			if ( $cookie->ignored ) {
				$html = str_replace( array( 'cmplz-toggle-active' ),
					array( 'cmplz-toggle-disabled' ), $html );
			}

			return $html;
		}

		/**
		 * Get icon html for panel
		 *
		 * @param $status = success, error, disabled
		 * @param $icon_if_success
		 * @param $icon_if_error
		 *
		 * @return string
		 */

		public function get_icon( $args ) {
			$default_args = array(
				'status'        => 'success',
				'icon_success'  => '',
				'icon_error'    => false,
				'desc_success'  => '',
				'desc_error'    => '',
				'desc_disabled' => '',
			);
			$args         = wp_parse_args( $args, $default_args );
			if ( ! $args['icon_error'] ) {
				$args['icon_error'] = $args['icon_success'];
			}
			$complete = ( $args['status'] == 'success' ) ? $args['icon_success']
				: $args['icon_error'];
			switch ( $args['status'] ) {
				case 'success':
					$desc = $args['desc_success'];
					break;
				case 'error';
					$desc = $args['desc_error'];
					break;
				case 'disabled';
					$desc = $args['desc_disabled'];
					break;
			}

			return '<span cmplz-tooltip="' . $desc
			       . '" flow="left"><i class="fa cmplz-tooltip-icon  cmplz-'
			       . esc_attr( $args['status'] ) . ' fa-' . $complete
			       . '" ></i></span>';
		}

		/**
		 * Get html for service list item
		 *
		 * @param $tmpl
		 * @param $name
		 * @param $language
		 *
		 * @return string
		 */

		public function get_service_list_item_html( $tmpl, $name, $language ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$service = new CMPLZ_SERVICE( $name, $language );

			if ( ! $service->ID ) {
				return '';
			}

			$sync         = COMPLIANZ::$cookie_admin->use_cdb_api()
				? $service->sync : false;
			$syncDisabled = $syncDisabledClass = '';
			if ( ! COMPLIANZ::$cookie_admin->use_cdb_api() ) {
				$syncDisabled      = 'disabled="disabled"';
				$syncDisabledClass = "cmplz-disabled";
			}

			$serviceTypes
				= $this->get_serviceTypes_options( $service->serviceType,
				$language );

			$disabled      = $sync ? 'disabled="disabled"' : false;
			$disabledClass = $sync ? 'cmplz-disabled' : false;
			$sync          = $sync ? 'checked="checked"' : '';

			$link = '';
			if ( cmplz_get_value( 'use_cdb_links' ) === 'yes'
			     && strlen( $service->slug ) !== 0
			     && $service->slug !== 'unknown-service'
			) {
				$link
					= '<a target="_blank" href="https://cookiedatabase.org/service/'
					  . $service->slug . '">'
					  . __( "View service on cookiedatabase.org",
						"complianz-gdpr" ) . '</a>';
			}

			$sharesData   = $service->sharesData == 1 ? 'checked="checked"'
				: '';
			$service_html = str_replace(
				array(
					'{service_id}',
					'{name}',
					'{disabled}',
					'{disabledClass}',
					'{sync}',
					'{serviceTypes}',
					'{privacyStatementURL}',
					'{sharesData}',
					'{showOnPolicy}',
					'{syncDisabled}',
					"{syncDisabledClass}",
					"{link}",
				),
				array(
					$service->ID,
					$service->name,
					$disabled,
					$disabledClass,
					$sync,
					$serviceTypes,
					$service->privacyStatementURL,
					$sharesData,
					$service->showOnPolicy,
					$syncDisabled,
					$syncDisabledClass,
					$link,
				),
				$tmpl );
			$icons        = '';
			$status       = $service->complete ? 'success' : 'error';

			$icons .= $this->get_icon( array(
				'status'       => $status,
				'icon_success' => 'check',
				'icon_error'   => 'times',
				'desc_success' => __( "The data for this service is complete",
					"complianz-gdpr" ),
				'desc_error'   => __( "This service has missing fields",
					"complianz-gdpr" ),
			) );

			if ( ! $service->sync ) {
				$status = 'disabled';
			} elseif ( $service->synced ) {
				$status = 'success';
			} else {
				$status = 'error';
			}

			$icons .= $this->get_icon( array(
				'status'        => $status,
				'icon_success'  => 'sync-alt',
				'desc_success'  => __( "This service has been synchronized with cookiedatabase.org",
					"complianz-gdpr" ),
				'desc_error'    => __( "This service is not synchronized with cookiedatabase.org",
					"complianz-gdpr" ),
				'desc_disabled' => __( "Synchronization with cookiedatabase.org is not enabled for this service",
					"complianz-gdpr" ),
			) );

			$icons = '<span style="float:right">' . $icons . '</span>';


			return cmplz_panel( sprintf( __( 'Service "%s"', 'complianz-gdpr' ),
				$service->name ), $service_html, $icons, false, false );
		}

		/**
		 * Get a list of cookies or services, in a specific language
		 */

		public function ajax_get_list() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$msg      = 'success';
			$language = 'en';
			$deleted  = false;
			$type     = 'cookie';

			if ( isset( $_GET['language'] ) ) {
				$language = cmplz_sanitize_language( $_GET['language'] );
			}
			if ( isset( $_GET['type'] )
			     && in_array( $_GET['type'], array( 'service', 'cookie' ) )
			) {
				$type = $_GET['type'];
			}

			if ( isset( $_GET['deleted'] ) && $_GET["deleted"] == 'true' ) {
				$deleted = true;
			}

			$args = array(
				'language' => $language,
				'deleted'  => $deleted,
			);

			if ( $type == 'cookie' ) {
				$this->reset_cookies_changed();

				$items = $this->get_cookies( $args );
				//group by service
				$grouped_by_service = array();
				foreach ( $items as $cookie ) {
					$service                          = strlen( $cookie->service )
					                                    !== 0 ? $cookie->service
						: 'no-service';
					$grouped_by_service[ $service ][] = $cookie;
				}

				$html = '';
				$tmpl = cmplz_get_template( $type . '_settings.php' );
				if ( $grouped_by_service ) {
					foreach ( $grouped_by_service as $service => $cookies ) {
						$class = '';
						if ( $service === 'no-service' ) {
							$service = __( 'Cookies without selected service',
								'complianz-gdpr' );
							$class   = 'no-service';
						}
						$html .= '<div class="cmplz-service-divider ' . $class
						         . '">' . $service . '</div>';
						foreach ( $cookies as $cookie ) {
							$html .= $this->get_cookie_list_item_html( $tmpl,
								$cookie->name, $language );
						}
					}
				}

			} else {
				$items = $this->get_services( $args );
				$html  = '';
				$tmpl  = cmplz_get_template( $type . '_settings.php' );
				if ( $items ) {
					foreach ( $items as $service => $item ) {
						$html .= $this->get_service_list_item_html( $tmpl,
							$item->name, $language );
					}
				}
			}

			$data     = array(
				'success' => true,
				'message' => $msg,
				'html'    => $html,
			);
			$response = json_encode( $data );
			header( "Content-Type: application/json" );
			echo $response;
			exit;

		}

		/**
		 * Callback for editing an item
		 */

		public function ajax_edit_item() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$error   = false;
			$action  = "";
			$html    = '';
			$msg     = 'success';
			$item_id = false;

			$type = 'cookie';
			if ( isset( $_POST['type'] )
			     && in_array( $_POST['type'], array( 'service', 'cookie' ) )
			) {
				$type = $_POST['type'];
			}
			if ( isset( $_POST['item_id'] ) ) {
				$item_id = intval( $_POST['item_id'] );
			}

			if ( ! $error && isset( $_POST["cmplz_action"] ) ) {
				$action = sanitize_title( $_POST["cmplz_action"] );
			}

			if ( ! $error && $action === 'save' && $item_id ) {

				if ( ! $item_id || ! isset( $_POST['data'] ) ) {
					$error = true;
					$msg   = 'no data sent';
				}

				if ( ! $error ) {
					$item = ( $type === 'cookie' )
						? new CMPLZ_COOKIE( $item_id )
						: new CMPLZ_SERVICE( $item_id );

					$data = json_decode( stripslashes( $_POST['data'] ), true );
					foreach ( $data as $key => $value ) {
						if ( ! strpos( $key, 'cmplz_' ) === false ) {
							continue;
						}
						$fieldname = str_replace( 'cmplz_', '', $key );

						//test if property exists
						if ( ! property_exists( $item, $fieldname ) ) {
							continue;
						}
						$item->{$fieldname} = $value;
					}
					$item->save( $updateAllLanguages = true );

				}
			}

			if ( ! $error && $item_id && $action === 'delete' ) {
				$item = ( $type === 'cookie' ) ? new CMPLZ_COOKIE( $item_id )
					: new CMPLZ_SERVICE( $item_id );
				$item->delete();
			}

			if ( ! $error && $item_id && $action === 'restore' ) {
				$item = new CMPLZ_COOKIE( $item_id );
				$item->restore();
			}

			if ( ! $error && $action === 'add' ) {

				$language = cmplz_sanitize_language( $_POST['language'] );
				$item     = ( $type === 'cookie' ) ? new CMPLZ_COOKIE()
					: new CMPLZ_SERVICE();
				$name     = $type . '-' . time();
				$new_id   = $item->add( $name, $this->get_supported_languages(),
					$language, false, false );

				$tmpl = cmplz_get_template( $type . '_settings.php' );
				//create empty set, to use for ajax
				$services = $this->get_services_options( '', $language );
				$purposes = $this->get_cookiePurpose_options( '', $language );
				$serviceTypes = $this->get_serviceTypes_options( '', $language );

				if ( $type === 'cookie' ) {
					$html = str_replace( array(
						'{' . $type . '_id}',
						'{disabled}',
						'{name}',
						'{services}',
						'{retention}',
						'{sync}',
						'{showOnPolicy}',
						'{cookieFunction}',
						'{purposes}',
						'{collectedPersonalData}',
						'{link}',
					), array(
						$new_id,
						'',
						$name,
						$services,
						'',
						'',
						'checked="checked"',
						'',
						$purposes,
						'',
						'',
					), $tmpl );
				} else {
					$html = str_replace( array(
						'{' . $type . '_id}',
						'{disabled}',
						'{name}',
						'{serviceTypes}',
						'{privacyStatementURL}',
						'{sync}',
						'{showOnPolicy}',
						'{link}',
					), array(
						$new_id,
						'',
						$name,
						$serviceTypes,
						'',
						'',
						'checked="checked"',
						'',
					), $tmpl );
				}
				$html = cmplz_panel( __( $name, 'complianz-gdpr' ), $html, '',
					'', false );
			}

			$data     = array(
				'success' => true,
				'message' => $msg,
				'action'  => $action,
				'html'    => $html,
				'divider' => '<div class="cmplz-service-divider no-service">'
				             . __( 'Cookies without selected service',
						'complianz-gdpr' ) . '</div>',

			);
			$response = json_encode( $data );
			header( "Content-Type: application/json" );
			echo $response;
			exit;

		}

		/**
		 * Delete the transient that contains the pages list
		 *
		 * @param      $post_id
		 * @param bool $post_after
		 * @param bool $post_before
		 */


		public function clear_pages_list(
			$post_id, $post_after = false, $post_before = false
		) {
			delete_transient( 'cmplz_pages_list' );
		}


		/**
		 * Runs once a week to check if the CDB should be synced
		 *
		 * @hooked cmplz_every_week_hook
		 */

		public function maybe_sync_cookies($running_after_services = false) {
			if ( ! wp_doing_cron() && ! current_user_can( 'manage_options' ) ) {
				return 'No permissions';
			}
			$msg   = '';
			$error = false;
			$data  = $this->get_syncable_cookies();

			if ( ! $this->use_cdb_api() ) {
				$error = true;
				$msg
				       = COMPLIANZ::$config->warning_types['api-disabled']['label_error'];
			}

			//if no syncable cookies are found, exit.
			if ( $data['count'] == 0 ) {
				update_option( 'cmplz_sync_cookies_complete', true );
				$msg   = "";
				$error = true;
			}

			unset( $data['count'] );

			if ( get_transient( 'cmplz_cookiedatabase_request_active' ) ) {
				$error = true;
				$msg
				       = __( "A request is already running. Please be patient until the current request finishes",
					"complianz-gdpr" );
			}

			if ( ! $error ) {
				set_transient( 'cmplz_cookiedatabase_request_active', true,
					MINUTE_IN_SECONDS );
				//add the plugins list to the data
				$plugins         = get_option( 'active_plugins' );
				$data['plugins'] = "<pre>" . implode( "<br>", $plugins )
				                   . "</pre>";
				$data['website'] = '<a href="' . esc_url_raw( site_url() )
				                   . '">' . esc_url_raw( site_url() ) . '</a>';
				$data            = apply_filters( 'cmplz_api_data', $data );
				$json            = json_encode( $data );
				$endpoint        = trailingslashit( CMPLZ_COOKIEDATABASE_URL )
				                   . 'v1/cookies/';

				$ch = curl_init();

				curl_setopt( $ch, CURLOPT_URL, $endpoint );
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen( $json )
					)
				);

				$result = curl_exec( $ch );
				$error  = ( $result == 0
				            && strpos( $result,
						'<title>502 Bad Gateway</title>' ) === false ) ? false
					: true;
				if ( $error ) {
					$msg = __( "Could not connect to cookiedatabase.org",
						"complianz-gdpr" );
				}

				curl_close( $ch );
				delete_transient( 'cmplz_cookiedatabase_request_active' );
			}

			if ( ! $error ) {
				$result = json_decode( $result );

				//cookie creation also searches fuzzy, so we can now change the cookie name to an asterisk value
				//on updates it will still match.
				if ( isset( $result->data->error ) ) {
					$msg   = $result->data->error;
					$error = true;
				} else {
					$result = $result->data;
				}
			}

			if ( ! $error && isset( $result->status )
			     && $result->status !== 200
			) {
				$error = true;
			}

			//first, add "en" as base cookie, and get ID
			if ( ! $error ) {

				//make sure we have an en cookie
				if ( isset( $result->en ) ) {
					$cookies           = $result->en;
					$isTranslationFrom = array();
					foreach (
						$cookies as $original_cookie_name => $cookie_object
					) {
						if ( ! isset( $cookie_object->name ) ) {
							continue;
						}

						$cookie                  = new CMPLZ_COOKIE( $original_cookie_name, 'en' );
						$cookie->name            = $cookie_object->name;
						$cookie->retention       = $cookie_object->retention;
						$cookie->type            = $cookie_object->type;
						$cookie->collectedPersonalData
						                         = $cookie_object->collectedPersonalData;
						$cookie->cookieFunction  = $cookie_object->cookieFunction;
						$cookie->purpose         = $cookie_object->purpose;
						$cookie->isPersonalData  = $cookie_object->isPersonalData;
						$cookie->isMembersOnly   = $cookie_object->isMembersOnly;
						$cookie->service         = $cookie_object->service;
						$cookie->ignored         = $cookie_object->ignore;
						$cookie->slug            = $cookie_object->slug;
						$cookie->lastUpdatedDate = time();

						$cookie->save();
						$isTranslationFrom[ $cookie->name ] = $cookie->ID;
					}

					foreach ( $result as $language => $cookies ) {
						if ( $language === 'en' ) {
							continue;
						}

						foreach (
							$cookies as $original_cookie_name => $cookie_object
						) {
							if ( ! isset( $cookie_object->name ) ) {
								continue;
							}

							$cookie                  = new CMPLZ_COOKIE( $original_cookie_name, $language );
							$cookie->name            = $cookie_object->name;
							$cookie->retention       = $cookie_object->retention;
							$cookie->collectedPersonalData
							                         = $cookie_object->collectedPersonalData;
							$cookie->cookieFunction  = $cookie_object->cookieFunction;
							$cookie->purpose         = $cookie_object->purpose;
							$cookie->isPersonalData  = $cookie_object->isPersonalData;
							$cookie->isMembersOnly   = $cookie_object->isMembersOnly;
							$cookie->service         = $cookie_object->service;
							$cookie->slug            = $cookie_object->slug;
							$cookie->ignored         = $cookie_object->ignore;
							$cookie->lastUpdatedDate = time();

							//when there's no en cookie, create one.
							if ( ! isset( $isTranslationFrom[ $cookie->name ] )
							     && $language !== 'en'
							) {
								$parent_cookie
									= new CMPLZ_COOKIE( $cookie->name, 'en' );
								$parent_cookie->save();
								$isTranslationFrom[ $cookie->name ]
									= $parent_cookie->ID;
							}

							$cookie->isTranslationFrom
								= $isTranslationFrom[ $cookie->name ];

							$cookie->save();

						}
					}
				}

				$this->update_sync_date();
			}

			if ($running_after_services ) {
				update_option( 'cmplz_sync_cookies_after_services_complete', true );
			} else {
				update_option( 'cmplz_sync_cookies_complete', true );

			}

			return $msg;
		}


		public function get_syncable_services() {
			$languages = $this->get_supported_languages();
			$data      = array();

			$count_all    = 0;
			$one_week_ago = strtotime( "-1 week" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language );
				if ( ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_WEEK_CHECK' )
				) {
					$args['lastUpdatedDate'] = $one_week_ago;
				}
				$services = $this->get_services( $args );

				$services          = wp_list_pluck( $services, 'name' );
				$data[ $language ] = $services;
				$count_all         += count( $services );

			}
			$data['count'] = $count_all;

			return $data;

		}

		public function get_syncable_cookies() {
			$languages          = $this->get_supported_languages();
			$data               = array();
			$thirdparty_cookies = array();
			$localstorage_cookies = array();

			$count_all    = 0;
			$one_week_ago = strtotime( "-1 week" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language );
				if ( ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_WEEK_CHECK' )
				) {
					$args['lastUpdatedDate'] = $one_week_ago;
				}
				$cookies = $this->get_cookies( $args );
				$cookies = wp_list_pluck( $cookies, 'name' );
				$count_all += count( $cookies );
				$index = 0;
				foreach ( $cookies as $cookie ) {
					$c = new CMPLZ_COOKIE( $cookie, $language );
					$slug = $c->slug ? $c->slug : $index;
					//pass the type to the CDB
					if ($c->type === 'localstorage') {
						$localstorage_cookies[] = $cookie;
					}
					//need to pass a service here.
					if ( strlen( $c->service ) != 0 ) {
						$service = new CMPLZ_SERVICE( $c->service );
						if ( $service->thirdParty || $service->secondParty) {
							$thirdparty_cookies[] = $cookie;
						}
						$data[ $language ][ $c->service ][$slug] = $cookie;
					} else {
						$data[ $language ]['no-service-set'][$slug] = $cookie;
					}
					$index++;
				}
			}

			$data['count'] = $count_all;
			$data['thirdpartyCookies'] = $thirdparty_cookies;
			$data['localstorageCookies'] = $localstorage_cookies;

			return $data;
		}


		/**
		 * Sync all services
		 */

		public function maybe_sync_services() {
			if ( ! wp_doing_cron() && ! current_user_can( 'manage_options' ) ) {
				return;
			}
			/**
			 * get cookies by service name
			 */
			$msg   = '';
			$error = false;
			$data  = $this->get_syncable_services();
			if ( ! $this->use_cdb_api() ) {
				$error = true;
				$msg
				       = COMPLIANZ::$config->warning_types['api-disabled']['label_error'];
			}

			//if no syncable services found, exit.
			if ( $data['count'] == 0 ) {
				update_option( 'cmplz_sync_services_complete', true );
				$msg   = '';
				$error = true;
			}

			unset( $data['count'] );

			if ( get_transient( 'cmplz_cookiedatabase_request_active' ) ) {
				$error = true;
				$msg
				       = __( "A request is already running. Please be patient until the current request finishes",
					"complianz-gdpr" );
			}

			if ( ! $error ) {
				set_transient( 'cmplz_cookiedatabase_request_active', true,
					MINUTE_IN_SECONDS );

				//clear, for further use of this variable.
				$services = array();
				$data     = apply_filters( 'cmplz_api_data', $data );

				$json     = json_encode( $data );
				$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL )
				            . 'v1/services/';

				$ch = curl_init();

				curl_setopt( $ch, CURLOPT_URL, $endpoint );
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen( $json )
					)
				);

				$result = curl_exec( $ch );

				$error = ( $result == 0
				           && strpos( $result,
						'<title>502 Bad Gateway</title>' ) === false ) ? false
					: true;
				if ( $error ) {
					$msg = __( "Could not connect to cookiedatabase.org",
						"complianz-gdpr" );
				}

				curl_close( $ch );
				delete_transient( 'cmplz_cookiedatabase_request_active' );
			}

			if ( ! $error ) {
				$result = json_decode( $result );
				//cookie creation also searches fuzzy, so we can now change the cookie name to an asterisk value
				//on updates it will still match.
				if ( isset( $result->error ) ) {
					$msg   = $result->error;
					$error = true;
				} else {
					$result = $result->data;
				}
			}

			if ( ! $error ) {
				if ( isset( $result->en ) ) {
					$services = $result->en;

					$isTranslationFrom = array();
					foreach (
						$services as $original_service_name =>
						$service_and_cookies
					) {
						if ( ! isset( $service_and_cookies->service ) ) {
							continue;
						}

						$service_object = $service_and_cookies->service;

						//sync service data
						if ( ! isset( $service_object->name ) ) {
							continue;
						}

						$service       = new CMPLZ_SERVICE( $original_service_name,
							'en' );
						$service->name = $service_object->name;
						$service->privacyStatementURL
						               = $service_object->privacyStatementURL;
						$service->sharesData
						               = $service_object->sharesData;
						$service->secondParty
						               = $service_object->secondParty;
						$service->thirdParty
						               = $service_object->sharesData
						                 && ! $service_object->secondParty; //won't get saved, but for next part of code.
						$service->serviceType
						               = $service_object->serviceType;
						$service->slug = $service_object->slug;

						$service->lastUpdatedDate = time();

						$service->save(false, false);
						$isTranslationFrom[ $service->name ] = $service->ID;

						//get the cookies only if it's third party service. Otherwise, just sync the service itself.
						if ( $service->thirdParty || $service->secondParty
						     && isset( $service_and_cookies->cookies )
						) {
							$cookies = $service_and_cookies->cookies;
							if ( ! is_array( $cookies ) ) {
								continue;
							}

							foreach ( $cookies as $cookie_name ) {
								$cookie = new CMPLZ_COOKIE( $cookie_name, 'en',
									$service->name );
								$cookie->add( $cookie_name,
									$this->get_supported_languages(), false,
									$service->name );
							}
						}
					}
				}

				foreach ( $result as $language => $services ) {
					if ( $language === 'en' ) {
						continue;
					}

					foreach (
						$services as $original_service_name =>
						$service_and_cookies
					) {
						if ( ! isset( $service_and_cookies->service ) ) {
							continue;
						}
						$service_object = $service_and_cookies->service;

						if ( ! isset( $service_object->name ) ) {
							continue;
						}
						$service                  = new CMPLZ_SERVICE( $original_service_name,
							$language );
						$service->name            = $service_object->name;
						$service->privacyStatementURL
						                          = $service_object->privacyStatementURL;
						$service->sharesData
						                          = $service_object->sharesData;
						$service->secondParty
						                          = $service_object->secondParty;
						$service->serviceType
						                          = $service_object->serviceType;
						$service->slug            = $service_object->slug;
						$service->lastUpdatedDate = time();

						//when there's no 'en' service, create one.
						if ( ! isset( $isTranslationFrom[ $service->name ] ) ) {
							$parent_service           = new CMPLZ_SERVICE( $service->name,
								'en' );
							$service->lastUpdatedDate = time();
							$parent_service->save(false, false);
							$isTranslationFrom[ $service->name ]
								= $parent_service->ID;
						}

						$service->isTranslationFrom
							= $isTranslationFrom[ $service->name ];
						$service->save(false, false);

					}

				}
				$this->update_sync_date();


			}
			update_option( 'cmplz_sync_services_complete', true );

			return $msg;
		}

		/**
		 * Save the last sync date
		 */

		public function update_sync_date() {
			$timezone_offset = get_option( 'gmt_offset' );
			$time            = time() + ( 60 * 60 * $timezone_offset );
			update_option( 'cmplz_last_cookie_sync', $time );
		}


		public function clear_double_cookienames() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$languages = $this->get_supported_languages();
			global $wpdb;
			foreach ( $languages as $language ) {
				$settings = array(
					'language'      => $language,
					'isMembersOnly' => 'all',
				);
				$cookies  = $this->get_cookies( $settings );
				foreach ( $cookies as $cookie ) {
					$same_name_cookies
						= $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}cmplz_cookies where name = %s and language = %s",
						$cookie->name, $language ) );
					if ( count( $same_name_cookies ) > 1 ) {
						array_shift( $same_name_cookies );
						$IDS = wp_list_pluck( $same_name_cookies, 'ID' );
						$sql = implode( ' OR ID =', $IDS );
						$sql
						     = "DELETE from {$wpdb->prefix}cmplz_cookies where ID="
						       . $sql;
						$wpdb->query( $sql );
					}
				}
			}
		}


		/**
		 * Helper function to check if a service exists, and if not, add it.
		 *
		 * @param $services
		 * @param $service_to_add
		 * @param $type
		 *
		 * @return array
		 */

		public function maybe_add_service_to_list(
			$services, $service_to_add, $type
		) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$added = false;
			if ( ! is_array( $services ) ) {
				$services = array();
			}
			foreach ( $services as $service ) {
				if ( $service->name === $service_to_add ) {
					$added = true;
				}
			}

			if ( ! $added ) {
				$service = new CMPLZ_SERVICE();
				$service->add( $service_to_add,
					COMPLIANZ::$cookie_admin->get_supported_languages(), false,
					$type );
				$services[] = $service;
			}

			return $services;
		}

		/**
		 * Forces generation of a snapshot for today, triggered by the button
		 *
		 */

		public function force_snapshot_generation() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( isset( $_POST["cmplz_generate_snapshot"] )
			     && isset( $_POST["cmplz_nonce"] )
			     && wp_verify_nonce( $_POST['cmplz_nonce'],
					'cmplz_generate_snapshot' )
			) {
				COMPLIANZ::$document->generate_cookie_policy_snapshot(
					$force = true );
			}
		}

		/**
		 * Delete a snapshot
		 */

		public function ajax_delete_snapshot() {

			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( isset( $_POST['snapshot_id'] ) ) {
				$uploads    = wp_upload_dir();
				$upload_dir = $uploads['basedir'];
				$path       = $upload_dir . '/complianz/snapshots/';
				$success    = unlink( $path
				                      . sanitize_file_name( $_POST['snapshot_id'] ) );
				$response   = json_encode( array(
					'success' => true,
				) );
				header( "Content-Type: application/json" );
				echo $response;
				exit;
			}
		}

		public function cookie_statement_snapshots() {

			include( cmplz_path . '/class-cookiestatement-snapshot-table.php' );

			$customers_table = new cmplz_CookieStatement_Snapshots_Table();
			$customers_table->prepare_items();

			?>
			<script>
				jQuery(document).ready(function ($) {
					$(document).on('click', '.cmplz-delete-snapshot', function (e) {

						e.preventDefault();
						var btn = $(this);
						btn.closest('tr').css('background-color', 'red');
						var delete_snapshot_id = btn.data('id');
						$.ajax({
							type: "POST",
							url: '<?php echo admin_url( 'admin-ajax.php' )?>',
							dataType: 'json',
							data: ({
								action: 'cmplz_delete_snapshot',
								snapshot_id: delete_snapshot_id
							}),
							success: function (response) {
								if (response.success) {
									btn.closest('tr').remove();
								}
							}
						});

					});
				});
			</script>

			<div id="cookie-policy-snapshots" class="wrap cookie-snapshot">
				<h1><?php _e( "Proof of consent", 'complianz-gdpr' ) ?></h1>
				<p>
					<?php
					$link_open
						= '<a href="https://complianz.io/user-consent-registration/" target="_blank">';
					cmplz_notice( sprintf( __( 'When you make significant changes to your Cookie Policy, cookie banner or revoke functionality, we will add a time-stamped document under "Proof of Consent" with the latest changes. If there is any concern if your website was ready for GDPR at a point of time, you can use the Complianz Proof of Consent to show the efforts you made being compliant, while respecting data minimization and full control of consent registration by the user. On a daily basis, the document will be generated if the plugin has detected significant changes. For more information read our article about %suser consent registration%s.',
						'complianz-gdpr' ), $link_open, '</a>' ) ) ?>
				</p>
				<?php
				if ( isset( $_POST['cmplz_generate_snapshot'] ) ) {
					cmplz_notice( __( "Proof of consent updated!",
						"complianz-gdpr" ), 'success', true );
				}
				if ( isset( $_POST['cmplz_generate_snapshot_error'] ) ) {
					cmplz_notice( __( "Proof of consent generation failed. Check your write permissions in the uploads directory",
						"complianz-gdpr" ), 'warning' );
				}
				?>

				<form id="cmplz-cookiestatement-snapshot-generate" method="POST"
				      action="">
					<?php echo wp_nonce_field( 'cmplz_generate_snapshot',
						'cmplz_nonce' ); ?>
					<input type="submit" class="button button-primary"
					       name="cmplz_generate_snapshot"
					       value="<?php _e( "Generate now",
						       "complianz-gdpr" ) ?>"/>
				</form>
				<form id="cmplz-cookiestatement-snapshot-filter" method="get"
				      action="">

					<?php
					$customers_table->search_box( __( 'Filter',
						'complianz-gdpr' ), 'cmplz-cookiesnapshot' );
					$customers_table->display();
					?>
					<input type="hidden" name="page"
					       value="cmplz-proof-of-consent"/>

				</form>
				<?php do_action( 'cmplz_after_cookiesnapshot_list' ); ?>
			</div>

			<?php
		}


		/**
		 * Keep services in sync with selected answers in wizard
		 *
		 *
		 **/

		public function update_services() {


			$social_media = ( cmplz_get_value( 'uses_social_media' ) === 'yes' )
				? true : false;
			if ( $social_media ) {
				$social_media_types = cmplz_get_value( 'socialmedia_on_site' );
				foreach ( $social_media_types as $slug => $active ) {
					if ( $active == 1 ) {
						$service = new CMPLZ_SERVICE();
						//add for all languages
						$service_name
							= $thirdparty_services
							= COMPLIANZ::$config->thirdparty_socialmedia[ $slug ];
						$service->add( $service_name,
							$this->get_supported_languages(), false, 'social' );
					} else {
						$service = new CMPLZ_SERVICE( $slug );
						$service->delete();
					}
				}
			}

			$thirdparty = ( cmplz_get_value( 'uses_thirdparty_services' )
			                === 'yes' ) ? true : false;
			if ( $thirdparty ) {
				$thirdparty_types
					= cmplz_get_value( 'thirdparty_services_on_site' );
				foreach ( $thirdparty_types as $slug => $active ) {
					if ( $active == 1 ) {
						$service = new CMPLZ_SERVICE();
						//add for all languages
						$service_name
							= $thirdparty_services
							= COMPLIANZ::$config->thirdparty_services[ $slug ];
						$service->add( $service_name,
							$this->get_supported_languages(), false,
							'service' );
					} else {
						$service = new CMPLZ_SERVICE( $slug );
						$service->delete();
					}
				}
			}
		}


		public function statistics_script_notice() {
			$anonimized = ( cmplz_get_value( 'matomo_anonymized' ) === 'yes' )
				? true : false;
			if ( $this->uses_matomo() ) {
				if ( $anonimized ) {
					cmplz_notice( __( "You use Matomo for statistics on your site, with ip numbers anonymized, so it is not necessary to add the script here.",
						'complianz-gdpr' ) );
				} else {
					cmplz_notice( __( "You use Matomo for statistics on your site, but ip numbers are not anonymized, so you should your tracking script here",
						'complianz-gdpr' ) );
				}
			}
		}

		/**
		 * Rescan after a manual "rescan" command from the user
		 */

		public function rescan() {
			if ( isset( $_POST['rescan'] ) ) {
				if ( ! isset( $_POST['complianz_nonce'] )
				     || ! wp_verify_nonce( $_POST['complianz_nonce'],
						'complianz_save' )
				) {
					return;
				}

				update_option( 'cmplz_detected_social_media', false );
				update_option( 'cmplz_detected_thirdparty_services', false );
				update_option( 'cmplz_detected_stats', false );
				$this->reset_pages_list();
			}
		}

		public function clear_cookies() {
			if ( isset( $_POST['clear'] ) ) {
				if ( ! isset( $_POST['complianz_nonce'] )
				     || ! wp_verify_nonce( $_POST['complianz_nonce'],
						'complianz_save' )
				) {
					return;
				}
				global $wpdb;
				$table_names = array(
					$wpdb->prefix . 'cmplz_cookies',
				);

				foreach ( $table_names as $table_name ) {
					if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" )
					     === $table_name
					) {
						$wpdb->query( "TRUNCATE TABLE $table_name" );
					}
				}

				$this->resync( true );
			}
		}

		public function resync( $force = false ) {
			if ( $force || isset( $_POST['resync'] ) ) {
				if ( ! isset( $_POST['complianz_nonce'] )
				     || ! wp_verify_nonce( $_POST['complianz_nonce'],
						'complianz_save' )
				) {
					return;
				}
				update_option( 'cmplz_sync_cookies_complete', false );
				update_option( 'cmplz_sync_cookies_after_services_complete', false );
				update_option( 'cmplz_sync_services_complete', false );

			}
		}

		/**
		 * On activation or deactivation of plugins, we clear the cookie list so it will be scanned anew.
		 *
		 *
		 * */

		public function plugin_changes( $plugin, $network_activation ) {
			update_option( 'cmplz_plugins_changed', 1 );

			//we don't delete this transient, but just reschedule it. Otherwise the scan would start right away, which might cause a memory overload.
			$this->reset_pages_list( true );
		}

		/**
		 * Check if plugins were changed recently
		 *
		 * @return bool
		 */

		public function plugins_changed() {
			return ( get_option( 'cmplz_plugins_changed' ) == 1 );
		}

		/**
		 * Set plugins as having updated
		 *
		 * @param $upgrader_object
		 * @param $options
		 */

		public function plugins_updating( $upgrader_object, $options ) {
			update_option( 'cmplz_plugins_updated', 1 );
		}

		public function plugins_updated() {
			return ( get_option( 'cmplz_plugins_updated' ) == 1 );
		}

		public function reset_plugins_updated() {
			update_option( 'cmplz_plugins_updated', - 1 );
		}

		public function reset_plugins_changed() {
			update_option( 'cmplz_plugins_changed', - 1 );
		}

		public function enqueue_assets( $hook ) {
			$minified = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_register_style( 'cmplz-cookie',
				cmplz_url . "assets/css/cookieconsent$minified.css", "",
				cmplz_version );
			wp_enqueue_style( 'cmplz-cookie' );

			$cookiesettings
				= $this->get_cookiebanner_settings( apply_filters( 'cmplz_user_banner_id',
				cmplz_get_default_banner_id() ) );

			$cookiesettings['placeholdertext']
				= cmplz_get_value( 'blocked_content_text' );

			wp_enqueue_script( 'cmplz-cookie',
				cmplz_url . "assets/js/cookieconsent$minified.js",
				array( 'jquery' ), cmplz_version, true );

			if ( ! isset( $_GET['complianz_scan_token'] ) ) {
				$deps = array( 'jquery' );
				if (cmplz_tcf_active()){
					$deps[] = 'cmplz-tcf';
				}
				if ( cmplz_has_async_documentwrite_scripts() ) {
					$deps[] = 'cmplz-postscribe';
					wp_enqueue_script( 'cmplz-postscribe',
						cmplz_url . "assets/js/postscribe.min.js",
						array( 'jquery' ), cmplz_version, true );
				}
				wp_enqueue_script( 'cmplz-cookie-config',
					cmplz_url . "assets/js/complianz$minified.js", $deps,
					cmplz_version, true );
				wp_localize_script(
					'cmplz-cookie-config',
					'complianz',
					$cookiesettings
				);
			}
		}

		/**
		 * Here we add scripts and styles for the wysywig editor on the backend
		 *
		 * */

		public function enqueue_admin_assets( $hook ) {
			//script to check for ad blockers
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cmplz-wizard' ) {
				wp_register_style( 'select2',
					cmplz_url . 'assets/select2/css/select2.min.css', false,
					cmplz_version );
				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'select2',
					cmplz_url . "assets/select2/js/select2.min.js",
					array( 'jquery' ), cmplz_version, true );


				wp_enqueue_script( 'cmplz-ad-checker',
					cmplz_url . "assets/js/ads.js",
					array( 'jquery', 'cmplz-admin' ), cmplz_version, true );

			}

		}

		/**
		 * On multisite, we want to get the policy consistent across sites
		 * @return int
		 */

		public function get_active_policy_id() {
			if (is_multisite()) {
				$policy_id = get_site_option( 'complianz_active_policy_id', 1 );
			} else {
				$policy_id = get_option( 'complianz_active_policy_id', 1 );
			}


			return $policy_id;
		}

		/**
		 * Upgrade the activate policy id with one
		 * The active policy id is used to track if the user has consented to the latest policy changes.
		 * If changes were made, the policy is increased, and user should consent again.
		 *
		 * On multisite, we want to get the policy consistent across sites
		 */

		public function upgrade_active_policy_id() {
			if (is_multisite()) {
				$policy_id = get_site_option( 'complianz_active_policy_id', 1 );
			} else {
				$policy_id = get_option( 'complianz_active_policy_id', 1 );
			}

			$policy_id ++;

			if (is_multisite()) {
				update_site_option( 'complianz_active_policy_id', $policy_id );
			} else {
				update_option( 'complianz_active_policy_id', $policy_id );
			}
		}

		/**
		 * Make sure we only have the front-end settings for the output
		 *
		 * */

		public function get_cookiebanner_settings( $banner_id ) {
			$banner = new CMPLZ_COOKIEBANNER( $banner_id );
			$output = $banner->get_settings_array();

			//deprecated filter
			$output = apply_filters( 'cmplz_cookie_settings', $output );

			return $output;
		}


		/**
		 * The classes that are passed to the statistics script determine if these are executed immediately or not.
		 *
		 *
		 * */

		public function get_statistics_script_classes() {
			//if a cookie warning is needed for the stats we don't add a native class, so it will be disabled by the cookie blocker by default
			$classes[]       = 'cmplz-stats';
			$uses_tagmanager = cmplz_get_value( 'compile_statistics' ) === 'google-tag-manager' ? true : false;

			if ( $uses_tagmanager ) {
				if ( ! $this->tagmamanager_fires_scripts()
				     && ! $this->cookie_warning_required_stats()
				) {
					$classes[] = 'cmplz-native';
				}
			} else {
				//if no cookie warning is needed for the stats specifically, we can move this out of the warning code by adding the native class
				if ( ! $this->cookie_warning_required_stats() ) {
					$classes[] = 'cmplz-native';
				}
			}

			return apply_filters( 'cmplz_statistics_script_classes', $classes );
		}

		/**
		 *
		 * Add script classes based on settings, so stats can be activated if no consent is required
		 *
		 * @param $class
		 * @param $match
		 * @param $found
		 *
		 * @return string
		 */


		public function add_script_classes_for_stats( $class, $match, $found ) {
			$stats_tags = COMPLIANZ::$config->stats_markers;
			foreach ( $stats_tags as $type => $markers ) {
				if ( in_array( $found, $markers ) ) {
					$class = $class . " " . implode( " ",
							$this->get_statistics_script_classes() );
				}
			}
			return $class;
		}

		/**
		 * Print inline cookie enabling scripts and statistics scripts
		 */

		public function inline_cookie_script() {
			//based on the script classes, the statistics will get added on consent, or without consent
			$classes = $this->get_statistics_script_classes();
			$statistics = cmplz_get_value( 'compile_statistics' );
			$configured_by_complianz = cmplz_get_value( 'configuration_by_complianz' ) !== 'no';
			do_action( 'cmplz_before_statistics_script' );

			/**
			 * Tag manager needs to be included with text/javascript, as it always needs to fire.
			 * All other scripts will be included with the appropriate tags, and fired when possible
			 */
			if ( $configured_by_complianz ) {
				if ( $statistics === 'google-tag-manager' ) {
					?><script type="text/javascript" class="<?php echo implode( " ", $classes ) ?>"><?php do_action( 'cmplz_tagmanager_script' );?></script><?php
				} else {
					$type = in_array( 'cmplz-native', $classes) ? 'text/javascript' : 'text/plain';
					?><script type="<?php echo $type?>" class="<?php echo implode( " ", $classes ) ?>"><?php do_action( 'cmplz_statistics_script' );?></script><?php
				}
			}

			if ( cmplz_get_value( 'disable_cookie_block' ) == 1 ) return;

			//scripts that should get executed on consent here
			$script = cmplz_get_value( 'cookie_scripts' );
			if ( strlen($script) >0 ){
				?><script class="cmplz-script" type="text/plain"><?php echo $script; ?></script><?php
			}

			$script_async = cmplz_get_value( 'cookie_scripts_async' );
			if ( strlen($script_async) >0 ){
				?><script class="cmplz-script" type="text/plain" async><?php echo $script_async; ?></script><?php
			}

			//stats scripts that should get executed on consent here
			$stats_script = cmplz_get_value( 'statistics_script' );
			if ( strlen($stats_script) >0 && cmplz_get_value( 'compile_statistics' ) === 'yes' ){
				?><script class="cmplz-stats" type="text/plain"><?php echo $stats_script; ?></script><?php
			}
		}

		/**
		 * Insert the gtag.js script required if gtag.js is used
		 * @hooked cmplz_before_statistics_script
		 * @since 4.7.8
		 */
		public function add_gtag_js(){
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) return;

			$statistics = cmplz_get_value( 'compile_statistics' );
			$gtag_code = esc_attr( cmplz_get_value( "UA_code" ) );
			if ( $statistics === 'google-analytics' && strlen($gtag_code) > 0 && substr($gtag_code, 0, 1) === 'G' ) {
				$classes = $this->get_statistics_script_classes();
				?><script async class="<?php echo implode( " ", $classes ) ?>" src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gtag_code?>"></script><?php
			}
		}

		/**
		 * Inline scripts which do not require a warning
		 */

		public function inline_cookie_script_no_warning() {
			?>
			<script type='text/javascript' class="cmplz-native">
				<?php do_action( 'cmplz_statistics_script' );?>
				<?php do_action( 'cmplz_tagmanager_script' );?>
				<?php if ( cmplz_get_value( 'disable_cookie_block' ) != 1 ) echo cmplz_get_value( 'cookie_scripts' );?>
			</script>
			<?php
		}


		/**
		 *
		 * @hooked cmplz_tagmanager_script
		 *
		 * */

		public function get_tagmanager_script() {
			if ( cmplz_get_value( 'configuration_by_complianz' ) !== 'yes' ) {
				return;
			}

			$statistics = cmplz_get_value( 'compile_statistics' );
			if ( $statistics === 'google-tag-manager' ) {
				$script = cmplz_get_template( 'google-tag-manager.js' );
				$script = str_replace( '{GTM_code}', esc_attr( cmplz_get_value( "GTM_code" ) ), $script );
				echo apply_filters('cmplz_script_filter' , $script );
			}
		}

		/**
		 *
		 * @hooked cmplz_statistics_script
		 *
		 *
		 * */

		public function get_statistics_script() {
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) {
				return;
			}

			$statistics = cmplz_get_value( 'compile_statistics' );
			$script = '';
			if ( $statistics === 'google-analytics' ) {
				$code = esc_attr( cmplz_get_value( "UA_code" ) );
				$anonymize_ip = $this->google_analytics_always_block_ip() ? "'anonymizeIp': true" : "";
				if (strlen($code)>0 && substr($code, 0, 1) === 'G') {
					$script       = cmplz_get_template( 'gtag.js' );
					$script       = str_replace( '{G_code}', $code, $script );
					$script       = str_replace( '{anonymize_ip}', $anonymize_ip, $script );
				} else {
					$script       = cmplz_get_template( 'google-analytics.js' );
					$script       = str_replace( '{UA_code}', $code, $script );
					$script       = str_replace( '{anonymize_ip}', $anonymize_ip, $script );
				}
			} elseif ( $statistics === 'matomo' ) {
				$script = cmplz_get_template( 'matomo.js' );
				$script = str_replace( '{site_id}',
					esc_attr( cmplz_get_value( 'matomo_site_id' ) ), $script );
				$script = str_replace( '{matomo_url}',
					esc_url_raw( trailingslashit( cmplz_get_value( 'matomo_url' ) ) ),
					$script );
			}
			echo apply_filters('cmplz_script_filter' , $script );
		}

		/**
		 *
		 * Get the domain from the current site url
		 *
		 * @return bool|string $domain
		 */

		public function get_domain() {
			$url   = site_url();
			$parse = parse_url( $url );
			if ( ! isset( $parse['host'] ) ) {
				return false;
			}

			return $parse['host'];
		}

		/**
		 * Get all cookies, and post back to site with ajax.
		 * This script is only inserted when a valid token is passed, so will never run for other visitors than the site admin
		 *
		 * */

		public function test_cookies() {
			if ( $this->scan_complete() ) {
				return;
			}

			$token     = sanitize_title( $_GET['complianz_scan_token'] );
			$id        = sanitize_title( $_GET['complianz_id'] );
			$admin_url = admin_url( 'admin-ajax.php' );

			$javascript = cmplz_get_template( 'test-cookies.js' );
			$javascript = str_replace( array(
				'{admin_url}',
				'{token}',
				'{id}'
			), array(
				esc_url_raw( $admin_url ),
				esc_attr( $token ),
				esc_attr( $id )
			), $javascript );
			?>
			<script>
				<?php echo $javascript;?>
			</script>

			<?php
		}

		/**
		 * Check if there are any new cookies added
		 */

		public function track_cookie_changes() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			//only run if all pages are scanned.
			if ( ! $this->scan_complete() ) {
				return;
			}
			//check if anything was changed
			$new_cookies = $this->get_cookies( array( 'new' => true ) );
			if ( count( $new_cookies ) > 0 ) {
				$this->set_cookies_changed();
			}
		}

		/**
		 * get boolean string for database purposes
		 *
		 * @param bool $boolean
		 *
		 * @return string
		 */

		private function bool_string( $boolean ) {
			$bool = boolval( $boolean );

			return $bool ? 'TRUE' : 'FALSE';
		}


		/*
         * Insert an iframe to retrieve front-end cookies
         *
         *
         * */

		public function run_cookie_scan() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( defined( 'CMPLZ_DO_NOT_SCAN' ) && CMPLZ_DO_NOT_SCAN ) {
				return;
			}

			if ( isset( $_GET['complianz_scan_token'] ) ) {
				return;
			}
			//if the last cookie scan date is more than a month ago, we re-scan.
			$last_scan_date = $this->get_last_cookie_scan_date( true );
			$one_month_ago  = apply_filters( 'cmplz_scan_frequency' , strtotime( '-1 month' ) );
			if ( $this->scan_complete()
			     && ( $one_month_ago > $last_scan_date )
			) {
				$this->reset_pages_list();
			}

			if ( ! $this->scan_complete() ) {
				if ( ! get_option( 'cmplz_synced_cookiedatabase_once' ) ) {
					update_option( 'cmplz_sync_cookies_complete', false );
					update_option( 'cmplz_sync_cookies_after_services_complete', false );
					update_option( 'cmplz_sync_services_complete', false );
					update_option( 'cmplz_synced_cookiedatabase_once', true );
				}

				//store the date
				$timezone_offset = get_option( 'gmt_offset' );
				$time            = time() + ( 60 * 60 * $timezone_offset );
				update_option( 'cmplz_last_cookie_scan', $time );

				$url = $this->get_next_page_url();
				if ( ! $url ) {
					return;
				}

				//first, get the html of this page.
				if ( strpos( $url, 'complianz_id' ) !== false ) {

					$response = wp_remote_get( $url );
					if ( ! is_wp_error( $response ) ) {
						$html = $response['body'];

						$stored_social_media
							= cmplz_scan_detected_social_media();
						if ( ! $stored_social_media ) {
							$stored_social_media = array();
						}
						$social_media = $this->parse_for_social_media( $html );
						$social_media
						              = array_unique( array_merge( $stored_social_media,
							$social_media ), SORT_REGULAR );
						update_option( 'cmplz_detected_social_media',
							$social_media );

						$stored_thirdparty_services
							= cmplz_scan_detected_thirdparty_services();
						if ( ! $stored_thirdparty_services ) {
							$stored_thirdparty_services = array();
						}
						$thirdparty
							= $this->parse_for_thirdparty_services( $html );
						$thirdparty
							= array_unique( array_merge( $stored_thirdparty_services,
							$thirdparty ), SORT_REGULAR );
						update_option( 'cmplz_detected_thirdparty_services',
							$thirdparty );

						//parse for google analytics and tagmanager, but only if the wizard wasn't completed before.
						//with this data we prefill the settings and give warnings when tracking is doubled
						if ( ! COMPLIANZ::$wizard->wizard_completed_once() ) {
							$this->parse_for_statistics_settings( $html );
						}
						if ( preg_match_all( '/ga\.js/', $html ) > 1
						     || preg_match_all( '/analytics\.js/', $html ) > 1
						     || preg_match_all( '/googletagmanager\.com\/gtm\.js/',
								$html ) > 1
						     || preg_match_all( '/piwik\.js/', $html ) > 1
						     || preg_match_all( '/matomo\.js/', $html ) > 1
						) {
							update_option( 'cmplz_double_stats', true );
						} else {
							delete_option( 'cmplz_double_stats' );
						}

						$stored_stats = cmplz_scan_detected_stats();
						if ( ! $stored_stats ) {
							$stored_stats = array();
						}
						$stats = $this->parse_for_stats( $html );
						$stats = array_unique( array_merge( $stored_stats,
							$stats ), SORT_REGULAR );
						update_option( 'cmplz_detected_stats', $stats );

					}
				}
				//load in iframe so the scripts run.
				echo '<iframe id="cmplz_cookie_scan_frame" class="hidden" src="'
				     . $url . '"></iframe>';

			}
		}


		/**
		 * Check a string for statistics
		 *
		 * @param string $html
		 * @param bool   $single_key //return a single string instead of array
		 *
		 * @return array|string $thirdparty
		 *
		 * */

		public function parse_for_stats( $html, $single_key = false ) {

			$stats         = array();
			$stats_markers = COMPLIANZ::$config->stats_markers;
			foreach ( $stats_markers as $key => $markers ) {
				foreach ( $markers as $marker ) {
					if ( $single_key && strpos( $html, $marker ) !== false ) {
						return $key;
					} else if ( strpos( $html, $marker ) !== false
					            && ! in_array( $key, $stats )
					) {
						if ( $single_key ) {
							return $key;
						}
						$stats[] = $key;
					}
				}
			}
			if ( $single_key ) {
				return false;
			}

			return $stats;
		}

		/**
		 * Run once to retrieve the settings for most used stats tools
		 *
		 * @param $html
		 */

		private function parse_for_statistics_settings( $html ) {

			if ( strpos( $html, 'gtm.js' ) !== false || strpos( $html, 'gtag/js' ) !== false
			) {
				update_option( 'cmplz_detected_stats_type', true );

				$pattern = '/(\'|")(GTM-[A-Z]{7})(\'|")/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					cmplz_update_option( 'wizard', 'GTM_code',
						sanitize_text_field( $matches[2] ) );
					update_option( 'cmplz_detected_stats_data', true );
					cmplz_update_option( 'wizard', 'compile_statistics', 'google-tag-manager' );
				}
			}

			if ( strpos( $html, 'analytics.js' ) !== false || strpos( $html, 'ga.js' ) !== false || strpos( $html, 'gtag/js' ) !== false ) {
				update_option( 'cmplz_detected_stats_type', true );

				$pattern = '/(\'|")(UA-[0-9]{8}-[0-9]{1})(\'|")/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					cmplz_update_option( 'wizard', 'UA_code', sanitize_text_field( $matches[2] ) );
					cmplz_update_option( 'wizard', 'compile_statistics', 'google-analytics' );
				}

				//gtag
				$pattern = '/(\'|")(G-[0-9a-zA-Z]{10})(\'|")/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					cmplz_update_option( 'wizard', 'UA_code', sanitize_text_field( $matches[2] ) );
					cmplz_update_option( 'wizard', 'compile_statistics', 'google-analytics' );
				}

				$pattern = '/\'anonymizeIp\':[ ]{0,1}true/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					$value = cmplz_get_value( 'compile_statistics_more_info' );
					if ( ! is_array( $value ) ) {
						$value = array();
					}
					$value['ip-addresses-blocked'] = 1;
					cmplz_update_option( 'wizard', 'compile_statistics_more_info', $value );
				}
			}

			if ( strpos( $html, 'piwik.js' ) !== false || strpos( $html, 'matomo.js' ) !== false ) {
				update_option( 'cmplz_detected_stats_type', true );
				$pattern = '/(var u=")((https|http):\/\/.*?)"/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					cmplz_update_option( 'wizard', 'matomo_url', sanitize_text_field( $matches[2] ) );
					update_option( 'cmplz_detected_stats_data', true );

				}

				$pattern = '/\[\'setSiteId\', \'([0-9]){1,3}\'\]\)/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[2] ) ) {
					cmplz_update_option( 'wizard', 'matomo_site_id', sanitize_text_field( $matches[1] ) );
					update_option( 'cmplz_detected_stats_data', true );
				}

				cmplz_update_option( 'wizard', 'compile_statistics', 'matomo' );
			}


		}


		/**
		 * Check the webpage html output for social media markers.
		 *
		 * @param string $html
		 * @param bool   $single_key
		 *
		 * @return array|bool|string $social_media_key
		 */

		public function parse_for_social_media( $html, $single_key = false ) {
			$social_media         = array();
			$social_media_markers = COMPLIANZ::$config->social_media_markers;
			foreach ( $social_media_markers as $key => $markers ) {
				foreach ( $markers as $marker ) {
					if ( $single_key && strpos( $html, $marker ) !== false ) {
						return $key;
					} else if ( strpos( $html, $marker ) !== false
					            && ! in_array( $key, $social_media )
					) {
						$social_media[] = $key;
					}
				}
			}

			if ( $single_key ) {
				return false;
			}

			return $social_media;
		}

		/**
		 * Check a string for third party services
		 *
		 * @param string $html
		 * @param bool   $single_key //return a single string instead of array
		 *
		 * @return array|string $thirdparty
		 *
		 * */

		public function parse_for_thirdparty_services(
			$html, $single_key = false
		) {

			$thirdparty = array();
			$thirdparty_markers
			            = COMPLIANZ::$config->thirdparty_service_markers;
			foreach ( $thirdparty_markers as $key => $markers ) {
				foreach ( $markers as $marker ) {
					if ( $single_key && strpos( $html, $marker ) !== false ) {
						return $key;
					} else if ( strpos( $html, $marker ) !== false
					            && ! in_array( $key, $thirdparty )
					) {
						$thirdparty[] = $key;
					}
				}
			}
			if ( $single_key ) {
				return false;
			}

			return $thirdparty;
		}


		private function get_next_page_url() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$token = time();
			update_option( 'complianz_scan_token', $token );
			$pages = $this->pages_to_process();
			if ( count( $pages ) == 0 ) {
				return false;
			}

			$id_to_process = reset( $pages );
			$this->set_page_as_processed( $id_to_process );
			switch ( $id_to_process ) {
				case 'home':
					$url = site_url();
					break;
				case 'loginpage':
					$url = wp_login_url();
					break;
				default:
					$url = get_permalink( $id_to_process );
			}
			$url = add_query_arg( array(
				"complianz_scan_token" => $token,
				'complianz_id'         => $id_to_process
			), $url );
			if ( is_ssl() ) {
				$url = str_replace( "http://", "https://", $url );
			}

			return $url;
		}


		/**
		 *
		 * Get list of page id's that we want to process this set of scan requests, which weren't included in the scan before
		 *
		 * @return array $pages
		 * *@since 1.0
		 */

		public function get_pages_list_single_run() {
			$posts = get_transient( 'cmplz_pages_list' );
			if ( ! $posts ) {
				$args       = array(
					'public' => true,
				);
				$post_types = get_post_types( $args );

				unset( $post_types['elementor_font'] );
				unset( $post_types['attachment'] );
				unset( $post_types['revision'] );
				unset( $post_types['nav_menu_item'] );
				unset( $post_types['custom_css'] );
				unset( $post_types['customize_changeset'] );
				unset( $post_types['cmplz-dataleak'] );
				unset( $post_types['cmplz-processing'] );
				unset( $post_types['user_request'] );
				unset( $post_types['cookie'] );
				unset( $post_types['cmplz-dataleak'] );
				unset( $post_types['cmplz-processing'] );

				$posts = array();
				foreach ( $post_types as $post_type ) {
					$args      = array(
						'post_type'      => $post_type,
						'posts_per_page' => 5,
						'meta_query'     => array(
							array(
								'key'     => '_cmplz_scanned_post',
								'compare' => 'NOT EXISTS'
							),
						)
					);
					$new_posts = get_posts( $args );
					$posts     = array_merge( $posts, $new_posts );
				}

				if ( count( $posts ) == 0 ) {
					/*
                     * If we didn't find any posts, we reset the post meta that tracks if all posts have been scanned.
                     * This way we will find some posts on the next scan attempt
                     * */
					if ( ! function_exists( 'delete_post_meta_by_key' ) ) {
						require_once ABSPATH . WPINC . '/post.php';
					}
					delete_post_meta_by_key( '_cmplz_scanned_post' );

					//now we need to reset the scanned pages list too
					$this->reset_pages_list();
				} else {
					$posts = wp_list_pluck( $posts, 'ID' );
					foreach ( $posts as $post_id ) {
						update_post_meta( $post_id, '_cmplz_scanned_post',
							true );
					}
				}

				$posts[] = 'home';
				if ( cmplz_get_value( 'wp_admin_access_users' ) === 'yes' ) {
					$posts[] = 'loginpage';
				}

				set_transient( 'cmplz_pages_list', $posts, WEEK_IN_SECONDS );
			}

			return $posts;
		}

		/**
		 * Reset the list of pages
		 *
		 * @return void
		 *
		 * @since 2.1.5
		 */

		public function reset_pages_list( $delay = false ) {
			if ( $delay ) {
				$current_list = get_transient( 'cmplz_pages_list' );
				$processed_pages
				              = get_transient( 'cmplz_processed_pages_list' );
				set_transient( 'cmplz_pages_list', $current_list,
					HOUR_IN_SECONDS );
				set_transient( 'cmplz_processed_pages_list', $processed_pages,
					HOUR_IN_SECONDS );

			} else {
				delete_transient( 'cmplz_pages_list' );
				delete_transient( 'cmplz_processed_pages_list' );
			}

		}


		/**
		 * Get list of pages that were processed before
		 *
		 * @return array $pages
		 */

		public function get_processed_pages_list() {

			$pages = get_transient( 'cmplz_processed_pages_list' );
			if ( ! is_array( $pages ) ) {
				$pages = array();
			}

			return $pages;
		}

		/**
		 * Check if the scan is complete
		 *
		 * @param void
		 *
		 * @return bool
		 * @since 1.0
		 *
		 * */

		public function scan_complete() {
			$pages = $this->pages_to_process();

			if ( count( $pages ) == 0 ) {
				return true;
			}

			return false;
		}

		/**
		 *
		 * Get list of pages that still have to be processed
		 *
		 * @param void
		 *
		 * @return array $pages
		 * @since 1.0
		 */

		private function pages_to_process() {
			$pages_list           = COMPLIANZ::$cookie_admin->get_pages_list_single_run();
			$processed_pages_list = $this->get_processed_pages_list();

			$pages = array_diff( $pages_list, $processed_pages_list );

			return $pages;
		}

		/**
		 * Set a page as being processed
		 *
		 * @param $id
		 *
		 * @return void
		 * @since 1.0
		 */

		public function set_page_as_processed( $id ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( $id !== 'home' && $id !== 'loginpage'
			     && ! is_numeric( $id )
			) {
				return;
			}

			$pages = $this->get_processed_pages_list();
			if ( ! in_array( $id, $pages ) ) {
				$pages[] = $id;
				set_transient( 'cmplz_processed_pages_list', $pages,
					MONTH_IN_SECONDS );
			}
		}

		public function get_cookies_by_service( $settings = array() ) {
			$cookies = COMPLIANZ::$cookie_admin->get_cookies( $settings );

			$grouped_by_service = array();
			$topServiceID       = 0;
			foreach ( $cookies as $cookie ) {
				$serviceID                                      = $cookie->serviceID
					? $cookie->serviceID : 999999999;
				$topServiceID                                   = $serviceID
				                                                  > $topServiceID
					? $serviceID : $topServiceID;
				$purpose
				                                                = strlen( $cookie->purpose )
				                                                  == 0
					? __( 'Purpose pending investigation', 'complianz-gdpr' )
					: $cookie->purpose;
				$grouped_by_service[ $serviceID ][ $purpose ][] = $cookie;
			}

			//move misc to end of array
			$misc = isset( $grouped_by_service[999999999] )
				? $grouped_by_service[999999999] : false;
			unset( $grouped_by_service[999999999] );
			if ( $misc ) {
				$grouped_by_service[ $topServiceID + 1 ] = $misc;
			}

			return $grouped_by_service;
		}

		/**
		 * Get list of active cookies on site
		 *
		 * @return array|object|null
		 */

		public function get_cookies( $settings = array() ) {
			global $wpdb;
			$result
				= $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}cmplz_cookies'" );
			if ( empty( $result ) ) {
				return array();
			}

			$defaults = array(
				'ignored'         => 'all',
				'new'             => false,
				'language'        => false,
				'isPersonalData'  => 'all',
				'isMembersOnly'   => false,
				'hideEmpty'       => false,
				'showOnPolicy'    => 'all',
				'lastUpdatedDate' => false,
				'deleted'         => false,
			);

			$settings = wp_parse_args( $settings, $defaults );

			$sql = ' 1=1 ';

			if ( $settings['isPersonalData'] !== 'all' ) {
				$sql .= ' AND isPersonalData = '
				        . $this->bool_string( $settings['isPersonalData'] );
			}
			if ( $settings['isMembersOnly'] !== 'all' ) {
				$sql .= ' AND isMembersOnly = '
				        . $this->bool_string( $settings['isMembersOnly'] );
			}

			if ( $settings['showOnPolicy'] !== 'all' ) {
				$sql .= ' AND showOnPolicy = '
				        . $this->bool_string( $settings['showOnPolicy'] );
			}

			if ( $settings['ignored'] !== 'all' ) {
				$sql .= ' AND ignored = '
				        . $this->bool_string( $settings['ignored'] );
			}

			if ( ! $settings['language'] ) {
				$sql .= ' and isTranslationFrom = false ';
			} else {
				$sql .= $wpdb->prepare( ' and language = %s',
					$settings['language'] );
			}

			if ( $settings['hideEmpty'] ) {
				$sql .= " AND name <>'' ";
			}

			if ( ! $settings['deleted'] ) {
				$sql .= " AND deleted != true ";
			}

			if ( isset( $settings['sync'] ) ) {
				$sql .= ' AND sync = '
				        . $this->bool_string( $settings['sync'] );
			}

			if ( $settings['new'] ) {
				$sql .= $wpdb->prepare( ' AND firstAddDate > %s ',
					get_option( 'cmplz_cookie_data_verified_date' ) );
			}
			if ( $settings['lastUpdatedDate'] ) {
				$sql .= $wpdb->prepare( ' AND (lastUpdatedDate < %s OR lastUpdatedDate=FALSE)',
					intval( $settings['lastUpdatedDate'] ) );
			}
			$cookies
				= $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookies where "
				                      . $sql );

			//make sure service data is added
			foreach ( $cookies as $index => $cookie ) {
				$cookie            = new CMPLZ_COOKIE( $cookie->ID );
				$cookies[ $index ] = $cookie;
			}

			return $cookies;
		}

		/**
		 * convert a slug to a normal readable name, without -, and with uppercase start of each word
		 *
		 * @param $slug
		 *
		 * @return string
		 */
		public function convert_slug_to_name( $slug ) {
			$a = explode( '-', $slug );
			$a = array_map( 'ucfirst', $a );

			return implode( ' ', $a );
		}


		/**
		 * Get list of detected services
		 *
		 * @param $category 'social', 'utility', 'other'
		 *
		 * @return array $services
		 * @since 1.0
		 *
		 */

		public function get_services( $settings = array() ) {
			global $wpdb;
			$result
				= $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}cmplz_cookies'" );
			if ( empty( $result ) ) {
				return array();
			}

			$defaults = array(
				'language'        => false,
				'hideEmpty'       => false,
				'category'        => 'all',
				'lastUpdatedDate' => false,
			);
			$settings = wp_parse_args( $settings, $defaults );

			$sql = ' 1=1 ';

			if ( $settings['language'] ) {
				$lang = cmplz_sanitize_language( $settings['language'] );
				$sql  .= " AND language = '$lang' ";
			}

			if ( $settings['hideEmpty'] ) {
				$sql .= " AND name <>'' ";
			}

			if ( isset( $settings['sync'] ) ) {
				$sql .= ' AND sync = '
				        . $this->bool_string( $settings['sync'] );
			}

			if ( $settings['category'] !== 'all' ) {
				$sql .= $wpdb->prepare( " AND category = %s",
					$settings['category'] );
			}

			if ( ! $settings['language'] ) {
				$sql .= ' and isTranslationFrom = false ';
			} else {
				$sql .= $wpdb->prepare( ' and language = %s',
					$settings['language'] );
			}

			if ( $settings['lastUpdatedDate'] ) {
				$sql .= $wpdb->prepare( ' AND (lastUpdatedDate < %s OR lastUpdatedDate=FALSE)',
					intval( $settings['lastUpdatedDate'] ) );
			}
			$sql      = "select * from {$wpdb->prefix}cmplz_services where "
			            . $sql;
			$services = $wpdb->get_results( $sql );

			return $services;
		}

		/**
		 * Store the detected cookies in the cookies table
		 */

		public function store_detected_cookies() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_POST['token'] )
			     && ( sanitize_title( $_POST['token'] )
			          == get_option( 'complianz_scan_token' ) )
			) {
				$post_cookies  = isset( $_POST['cookies'] )
				                 && is_array( $_POST['cookies'] )
					? $_POST['cookies'] : array();
				$cookies = array_map( function ( $el ) {
					return sanitize_title( $el );
				}, $post_cookies );
				if ( ! is_array( $cookies ) ) {
					$cookies = array();
				}

				$post_storage  = isset( $_POST['lstorage'] ) && is_array( $_POST['lstorage'] ) ? $_POST['lstorage'] : array();
				$localstorage = array_map( function ( $el ) {
					return sanitize_title( $el );
				}, $post_storage );
				if ( ! is_array( $localstorage ) ) {
					$localstorage = array();
				}

				//add local storage data
				$localstorage = array_map( 'sanitize_text_field', $localstorage );
				foreach ( $localstorage as $key => $value ) {
					$cookie = new CMPLZ_COOKIE();
					$cookie->add( $key, $this->get_supported_languages() );
					$cookie->type = 'localstorage';
					$cookie->save(true);
				}

				//add cookies
				$cookies = array_merge($cookies, $_COOKIE);
				$cookies = array_map( 'sanitize_text_field', $cookies );
				foreach ( $cookies as $key => $value ) {
					$cookie = new CMPLZ_COOKIE();
					$cookie->add( $key, $this->get_supported_languages() );
					$cookie->type = 'cookie';
					$cookie->save(true);
				}

				//clear token
				update_option( 'complianz_scan_token', false );

				//store current requested page
				$this->set_page_as_processed( $_POST['complianz_id'] );
			}
		}

		/**
		 * Get an array of languages used on this site in format array('en' => 'en')
		 *
		 * @param bool $count
		 *
		 * @return int|array
		 */

		public function get_supported_languages( $count = false ) {
			$site_locale = cmplz_sanitize_language( get_locale() );

			$languages = array( $site_locale => $site_locale );

			if ( function_exists( 'icl_register_string' ) ) {
				$wpml      = apply_filters( 'wpml_active_languages', null,
					array( 'skip_missing' => 0 ) );
				/**
				 * WPML has changed the index from 'language_code' to 'code' so
				 * we check for both.
				 */
				$wpml_test_index = reset($wpml);
				if (isset($wpml_test_index['language_code'])){
					$wpml      = wp_list_pluck( $wpml, 'language_code' );
				} elseif (isset($wpml_test_index['code'])) {
					$wpml      = wp_list_pluck( $wpml, 'code' );
				} else {
					$wpml = array();
				}
				$languages = array_merge( $wpml, $languages );
			}

			/**
			 * TranslatePress support
			 * There does not seem to be an easy accessible API to get the languages, so we retrieve from the settings directly
			 */

			if (class_exists('TRP_Translate_Press')){
				$trp_settings = get_option('trp_settings', array());
				if (isset($trp_settings['translation-languages'])) {
					$trp_languages = $trp_settings['translation-languages'];
					foreach( $trp_languages as $language_code){
						$key = substr( $language_code, 0, 2 );
						$languages[$key] = $key;
					}
				}
			}

			if ( $count ) {
				return count( $languages );
			}

			//make sure the en is always available.
			if ( ! in_array( 'en', $languages ) ) {
				$languages['en'] = 'en';
			}


			return $languages;
		}

		/**
		 * Get the last cookie scan date in unix or human time format
		 *
		 * @param bool $unix
		 *
		 * @return bool|int|string
		 */

		public function get_last_cookie_scan_date( $unix = false ) {
			$last_scan_date = get_option( 'cmplz_last_cookie_scan' );

			if ( $unix ) {
				return $last_scan_date;
			}

			if ( $last_scan_date ) {
				$date = date( get_option( 'date_format' ), $last_scan_date );
				$date = cmplz_localize_date( $date );
				$time = date( get_option( 'time_format' ), $last_scan_date );
				$date = sprintf( __( "%s at %s.", 'complianz-gdpr' ), $date,
					$time );
			} else {
				$date = false;
			}

			return $date;
		}


		/**
		 * Get the last cookie sync date in unix or human time format
		 *
		 * @return string
		 */

		public function get_last_cookie_sync_date() {
			$last_sync_date = get_option( 'cmplz_last_cookie_sync' );
			if ( $last_sync_date ) {
				$date = date( get_option( 'date_format' ), $last_sync_date );
				$date = cmplz_localize_date( $date );
			} else {
				$date = __( '(not synced yet)', 'complianz-gdpr' );
			}

			return $date;
		}

		/**
		 * Set the cookies as having been changed
		 */

		public function set_cookies_changed() {
			update_option( 'cmplz_changed_cookies', 1 );

		}

		/**
		 * Check if cookies have been changed
		 * @return bool
		 */

		public function cookies_changed() {
			return ( get_option( 'cmplz_changed_cookies' ) == 1 );
		}

		/**
		 * Reset the cookies changed value
		 */

		public function reset_cookies_changed() {
			update_option( 'cmplz_cookie_data_verified_date', time() );
			delete_transient( 'cmplz_cookie_settings_cache' );
			update_option( 'cmplz_changed_cookies', - 1 );
		}

		/**
		 * Update the cookie policy date
		 */

		public function update_cookie_policy_date() {
			update_option( 'cmplz_publish_date', time() );

			//also reset the email notification, so it will get sent next year.
			update_option( 'cmplz_update_legal_documents_mail_sent', false );
		}

		/**
		 * Hooked into ajax call to load detected cookies
		 * @hooked wp_ajax_load_detected_cookies
		 */

		public function load_detected_cookies() {
			$error   = false;
			$cookies = '';

			if ( ! is_user_logged_in() ) {
				$error = true;
			}

			if ( ! $error ) {
				$html = $this->get_detected_cookies_table();
			}

			$out = array(
				'success' => ! $error,
				'cookies' => $html,
			);

			die( json_encode( $out ) );
		}

		/**
		 * Get html for list of detected cookies
		 * @return string
		 */

		public function get_detected_cookies_table() {
			$html         = '';
			$args         = array(
				'isTranslationFrom' => false,
			);
			$cookies      = $this->get_cookies( $args );
			$social_media = cmplz_scan_detected_social_media();
			$thirdparty   = cmplz_scan_detected_thirdparty_services();
			if ( ! $cookies && ! $social_media && ! $thirdparty ) {
				if ( $this->scan_complete() ) {
					$html = __( "No cookies detected", 'complianz-gdpr' );
				} else {
					$html = __( "Cookie scan in progress", 'complianz-gdpr' );
				}
			} else {

				/*
                 * Show the cookies from our own domain
                 * */
				$html    .= '<tr class="group-header"><td colspan="2"><b>'
				            . __( 'Cookies on your own domain',
						'complianz-gdpr' ) . "</b></td></tr>";
				$args    = array(
					'isTranslationFrom' => false,
				);
				$cookies = $this->get_cookies( $args );
				$cookies = wp_list_pluck( $cookies, 'name' );
				if ( $cookies ) {
					foreach ( $cookies as $name ) {
						$html .= '<tr>';
						$html .= '<td>' . $name . '</td><td></td>';
						$html .= '</tr>';
					}
				} else {
					$html .= '<tr><td></td><td>---</td></tr>';
				}

				/*
                 * Show the social media which are placing cookies
                 * */
				$html .= '<tr class="group-header"><td colspan="2"><b>'
				         . __( 'Social media', 'complianz-gdpr' )
				         . "</b></td></tr>";
				if ( $social_media && count( $social_media ) > 0 ) {
					foreach ( $social_media as $key => $service ) {
						$html .= '<tr><td>'
						         . COMPLIANZ::$config->thirdparty_socialmedia[ $service ]
						         . '</td><td></td></tr>';
					}
				} else {
					$html .= '<tr><td></td><td>---</td></tr>';
				}
				/*
                 * Show the third party services which are placing cookies
                 * */
				$html .= '<tr class="group-header"><td colspan="2"><b>'
				         . __( 'Third-party services', 'complianz-gdpr' )
				         . "</b></td></tr>";
				if ( $thirdparty && count( $thirdparty ) > 0 ) {
					foreach ( $thirdparty as $key => $service ) {
						$html .= '<tr><td>'
						         . COMPLIANZ::$config->thirdparty_services[ $service ]
						         . '</td><td></td></tr>';
					}
				} else {
					$html .= '<tr><td></td><td>---</td></tr>';
				}
			}
			$html = '<table style="width:100%">' . $html . "</table>";

			return $html;
		}

		/**
		 * Check if there's a cookie which is not filled out entirely
		 *
		 * @return bool
		 */

		public function has_empty_cookie_descriptions() {

			$cookies
				= COMPLIANZ::$cookie_admin->get_cookies( array(
				'showOnPolicy' => true,
				'ignored'      => false
			) );
			if ( is_array( $cookies ) ) {
				foreach ( $cookies as $cookie_name ) {
					$cookie = new CMPLZ_COOKIE( $cookie_name );

					if ( $cookie->showOnPolicy && ! $cookie->complete ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Get progress of the scan in percentage
		 *
		 * @return float
		 */

		public function get_progress_count() {
			$done  = $this->get_processed_pages_list();
			$total = COMPLIANZ::$cookie_admin->get_pages_list_single_run();

			$progress = 100 * ( count( $done ) / count( $total ) );
			if ( $progress > 100 ) {
				$progress = 100;
			}

			return $progress;
		}

		/**
		 * Get progress of the current scan to output with ajax
		 */

		public function get_scan_progress() {
			$next_url = $this->get_next_page_url();
			$output   = array(
				"progress"  => $this->get_progress_count(),
				"next_page" => $next_url,
			);
			$obj      = new stdClass();
			$obj      = $output;
			echo json_encode( $obj );
			wp_die();
		}

		/**
		 * force sync on update to new CDB versions
		 */

		public function run_sync_on_update() {

			if ( ! $this->use_cdb_api() ) {
				return;
			}

			if ( ! get_option( 'cmplz_run_cdb_sync_once' ) ) {
				return;
			}

			//make sure this is only attempted max 3 times.
			$attempts = get_transient( 'cmplz_sync_attempts' );
			if ( ! $attempts ) {
				$attempts = 0;
			}

			if ( $attempts < 5 ) {
				$progress = $this->get_sync_progress();
				if ( $progress < 50 ) {
					$this->maybe_sync_cookies();
				}

				if ( $progress >= 50 && $progress < 75 ) {
					$this->maybe_sync_services();
				}

				//after adding the cookies, do one more cookies sync
				if ( $progress >= 75 && $progress < 100 ) {
					$this->maybe_sync_cookies(true);
					$this->clear_double_cookienames();
				}

				$attempts = $attempts + 1;
				set_transient( 'cmplz_sync_attempts', $attempts,
					DAY_IN_SECONDS );
			}
		}

		/**
		 * Run a sync
		 *
		 */

		public function run_sync() {
			$msg      = "";
			$progress = $this->get_sync_progress();
			if ( $progress < 50 ) {
				$msg = $this->maybe_sync_cookies();
			}

			if ( $progress >= 50 && $progress < 75 ) {
				$msg = $this->maybe_sync_services();
			}

			//after adding the cookies, do one more cookies sync
			if ( $progress >= 75 && $progress < 100 ) {
				$this->maybe_sync_cookies(true);
				$this->clear_double_cookienames();
			}
			$output = array(
				"message"  => $msg,
				"progress" => $progress,
			);

			$obj = new stdClass();
			$obj = $output;
			echo json_encode( $obj );
			wp_die();

		}


		public function get_sync_progress() {
			$progress = 10;
			if ( get_option( 'cmplz_sync_cookies_complete' ) ) {
				$progress = 50;
			}

			if ( get_option( 'cmplz_sync_cookies_complete' )
			     && get_option( 'cmplz_sync_services_complete' )
			) {
				$progress = 75;
			}

			if ( get_option( 'cmplz_sync_cookies_complete' )
			     && get_option( 'cmplz_sync_services_complete' ) && get_option( 'cmplz_sync_cookies_after_services_complete' )
			) {
				//if sync was started after update, stop it now
				update_option( 'cmplz_run_cdb_sync_once', false );
				$progress = 100;
			}

			return $progress;
		}


		public function scan_progress() {
			$disabled = "";
			if ( ! function_exists( 'curl_version' ) ) {
				$disabled = "disabled";
				cmplz_notice( __( 'Your server does not have CURL installed, which is required for the scan. Please contact your hosting company to install CURL.',
					'complianz-gdpr' ), 'warning' );
			}
			?>
			<div class="field-group cookie-scan first">
				<?php
				if ( ( isset( $_SERVER['HTTP_DNT'] )
				     && $_SERVER['HTTP_DNT'] == 1 )
				     || isset($_SERVER['HTTP_SEC_GPC'])
				) {
					cmplz_notice( __( "You have Do Not Track or Global Privacy Control enabled. This will prevent most cookies from being placed. Please run the scan with these options disabled.",
						'complianz-gdpr' ) );
				}
				?>

				<div id="cmplz_adblock_warning"
				     style="display:none"><?php cmplz_notice( __( "You are using an ad blocker. This will prevent most cookies from being placed. Please run the scan without an adblocker enabled.",
						'complianz-gdpr' ), 'warning' ) ?></div>
				<div id="cmplz_anonymous_window_warning"
				     style="display:none"><?php cmplz_notice( __( "You are using an anonymous window. This will prevent most cookies from being placed. Please run the scan in a normal browser window.",
						'complianz-gdpr' ), 'warning' ) ?></div>

				<div class="cmplz-label">
					<label for="scan_progress"><?php _e( "Cookie scan",
							'complianz-gdpr' ) ?></label>
				</div>
				<div id="cmplz-scan-progress">
					<div class="cmplz-progress-bar"></div>
				</div>
				<br>
				<?php echo __( "Cookies as detected by the automatic cookie scan. Please note that only cookies set on your own domain are detected by this scan.",
						'complianz-gdpr' ) . " "
				           . __( "Third-party scripts will get detected if they're listed in the Third Party list.",
						'complianz-gdpr' ) ?>
				<div class="detected-cookies">
					<?php echo $this->get_detected_cookies_table(); ?>
				</div>
				<div>

					<input <?php echo $disabled ?> type="submit"
					                               class="button cmplz-rescan"
					                               value="<?php _e( 'Re-scan',
						                               'complianz-gdpr' ) ?>"
					                               name="rescan">
					<input <?php echo $disabled ?> type="submit"
					                               class="button cmplz-reset"
					                               onclick="return confirm('<?php _e( 'Are you sure? This will permanently delete the list of cookies.',
						                               'complianz-gdpr' ) ?>');"
					                               value="<?php _e( 'Clear cookies',
						                               'complianz-gdpr' ) ?>"
					                               name="clear">

					<?php
					echo COMPLIANZ::$field->get_help_tip_btn( array( 'help' => true ) );
					echo COMPLIANZ::$field->get_help_tip( array(
						'help' => __( "If you want to clear all cookies from the plugin, you can do so here. You'll need to run a scan again afterwards. If you want to start with a clean slate, you might need to clear your browsercache, to make sure all cookies are removed from your browser as well.",
							"complianz-gdpr" )
					) );
					?>
				</div>
			</div>

			<?php
		}

		public function sync_progress() {

			$disabled      = '';
			$explanation   = '';
			$data_cookies  = $this->get_syncable_cookies();
			$data_services = $this->get_syncable_services();

			if ( $data_cookies['count'] == 0 && $data_services['count'] == 0 ) {
				$disabled = "disabled";
				$explanation
				          = cmplz_notice( __( 'Synchronization disabled: This happens when all cookies have synchronized to cookiedatabase.org in the last week.',
					'complianz-gdpr' ), 'warning', false, false );
			}

			if ( ! function_exists( 'curl_version' ) ) {
				$disabled = "disabled";
				$explanation
				          = cmplz_notice( __( 'Your server does not have CURL installed, which is required for the sync. Please contact your hosting company to install CURL.',
					'complianz-gdpr' ), 'warning', false, false );
			}

			if ( ! $this->use_cdb_api() ) {
				$disabled = "disabled";
				echo cmplz_notice( COMPLIANZ::$config->warning_types['api-disabled']['label_error'],
					'warning' );
			}

			if ( ! $this->use_cdb_api() ) {
				return;
			}

			?>

			<div class="field-group first">
				<div id="cmplz_action_error" class="cmplz-hidden">
					<?php echo cmplz_notice( '<!-- error msg-->', 'warning' ) ?>
				</div>

				<div class="cmplz-label">
					<label
						for="sync_progress"><?php _e( "Connecting to Cookiedatabase.org",
							'complianz-gdpr' ) ?></label>
				</div>
				<div id="cmplz-sync-progress">
					<div class="cmplz-sync-progress-bar"></div>
				</div>
				<div id="cmplz-sync-loader"></div>
				<br>

				<input type="submit" <?php echo $disabled ?>
				       class="button cmplz-resync"
				       value="<?php _e( 'Sync cookies with Cookiedatabase.org', 'complianz-gdpr' ) ?>"
				       name="resync">
				<?php echo $explanation ?>
			</div>
			<?php
		}


		public function use_cdb_api() {
			$use_api = cmplz_get_value( 'use_cdb_api', false, false, false )
			           === 'yes';

			return apply_filters( 'cmplz_use_cdb_api', $use_api );
		}


		/**
         * Check if site uses Google Analytics
		 * @return bool
         * */

		public function uses_google_analytics() {
			$statistics = cmplz_get_value( 'compile_statistics' );
			if ( $statistics === 'google-analytics' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if tm is used
		 * @return bool
		 */

		public function uses_google_tagmanager() {

			$statistics = cmplz_get_value( 'compile_statistics' );

			if ( $statistics === 'google-tag-manager' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if matomo is used
		 * @return bool
		 */

		public function uses_matomo() {
			$statistics = cmplz_get_value( 'compile_statistics' );
			if ( $statistics === 'matomo' ) {
				return true;
			}

			return false;
		}


		public function analytics_configured() {
			//if the user has chosen to configure it himself, we consider it to be configured.
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) {
				return true;
			}

			$UA_code = COMPLIANZ::$field->get_value( 'UA_code' );
			if ( strlen( $UA_code ) != 0 ) {
				return true;
			}

			return false;
		}

		public function tagmanager_configured() {
			//if the user has chosen to configure it himself, we consider it to be configured.
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) {
				return true;
			}
			$GTM_code = COMPLIANZ::$field->get_value( 'GTM_code' );
			if ( strlen( $GTM_code ) != 0 ) {
				return true;
			}

			return false;
		}

		public function matomo_configured() {
			//if the user has chosen to configure it himself, we consider it to be configured.
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) {
				return true;
			}

			$matomo_url = COMPLIANZ::$field->get_value( 'matomo_url' );
			$site_id    = COMPLIANZ::$field->get_value( 'matomo_site_id' );
			if ( strlen( $matomo_url ) != 0 && strlen( $site_id ) !== 0 ) {
				return true;
			}

			return false;
		}


		/**
		 * Check if the site needs a cookie banner. Pass a region to check cookie banner requirement for a specific region
		 *
		 * @@param string|bool $region
		 *
		 * @return bool
		 * *@since 1.2
		 *
		 */

		public function site_needs_cookie_warning( $region = false ) {
			/**
			 * default is false
			 */
			$needs_warning = false;
			if ( cmplz_get_value( 'uses_cookies' ) !== 'yes' ) {
				/**
				 * if cookies are not used at all, no banner is needed
				 */
				$needs_warning = false;
			} else if ( $region && ! cmplz_has_region( $region ) ) {
				/**
				 * if we do not target this region, we don't show a banner for that region
				 */
				$needs_warning = false;
			} else if ( ( ! $region || $region === 'us' ) && cmplz_has_region( 'us' ) ) {
				/**
				 * for the US, a cookie warning is always required
				 * if a region other than US is passed, we check the region's requirements
				 * if US is passed, we always need a banner
				 */
				$needs_warning = true;
			} else if ( $this->site_shares_data() ) {
				/**
				 * site shares data
				 */
				$needs_warning = true;
			} else if ( $this->cookie_warning_required_stats() ) {
				/**
				 * does the config of the statistics require a cookie warning?
				 */
				$needs_warning = true;
			}

			$url = $_SERVER['REQUEST_URI'];
			$excluded_posts_array = get_option( 'cmplz_excluded_posts_array', array() );
			if ( !empty($excluded_posts_array) ) {
				foreach ( $excluded_posts_array as $excluded_slug ) {
					if ( strpos( $url, $excluded_slug ) !== FALSE) return false;
				}
			}

			$needs_warning = apply_filters( 'cmplz_site_needs_cookiewarning', $needs_warning );
			return $needs_warning;
		}




		/**
		 * Check if consent is required for anonymous statistics
		 * @return bool
		 */

		public function consent_required_for_anonymous_stats(){
			if ( ! cmplz_has_region( 'eu' ) ) {
				return false;
			}
			$uses_google = $this->uses_google_analytics()
			               || $this->uses_google_tagmanager();

			return $uses_google
			       && ( cmplz_get_value( 'eu_consent_regions' ) === 'yes' )
			       && $this->statistics_privacy_friendly();
		}

		/**
		 * Check if the site needs a cookie banner considering statistics only
		 *
		 * @param $region bool|string
		 * @return bool
		 * @since 1.0
		 *
		 */

		public function cookie_warning_required_stats( $region = false ) {
			/**
			 * user can override detected settings in wizard
			 */

			if ( $this->consent_required_for_anonymous_stats()
			     && cmplz_get_value( 'consent_for_anonymous_stats' ) === 'yes'
			) {
				return true;
			}

			if ( cmplz_get_value( 'uses_cookies' ) !== 'yes' ) {
				return false;
			}

			$has_optinstats = cmplz_uses_consenttype( 'optinstats', $region );
			$statistics     = cmplz_get_value( 'compile_statistics' );

			//uk requires cookie warning for stats
			if ( $has_optinstats && $statistics !== 'no' ) {
				return true;
			}

			//if we're here, we don't need stats if they're set up privacy friendly
			$privacy_friendly = $this->statistics_privacy_friendly();

			//not stats required if privacy friendly
			return ! $privacy_friendly;
		}

		/**
		 * Add the selected statistics service as a service, and check for doubles
		 */

		public function maybe_add_statistics_service() {
			$selected_stat_service = cmplz_get_value( 'compile_statistics' );
			if ( $selected_stat_service === 'google-analytics'
			     || $selected_stat_service === 'matomo'
			     || $selected_stat_service === 'google-tag-manager'
			) {
				$service_name
					     = COMPLIANZ::$cookie_admin->convert_slug_to_name( $selected_stat_service );
				$service = new CMPLZ_SERVICE( $service_name );

				if ( ! $service->ID ) {
					//Add new service
					$service = new CMPLZ_SERVICE();
					$service->add( $service_name,
						COMPLIANZ::$cookie_admin->get_supported_languages(),
						false );
				}
			}
		}


		/**
		 * Determine if statistics are used in a privacy friendly way
		 *
		 * @return bool
		 */

		public function statistics_privacy_friendly() {
			//we don't check for cookies being used here, when needed this is checked separately, and this could
			//cause issues when this function is checked before the "uses_cookies" question is asked

			$statistics = cmplz_get_value( 'compile_statistics' );

			//no statistics at all, it's privacy friendly
			if ( $statistics === 'no' ) {
				return true;
			}

			//not anonymous stats.
			if ( $statistics === 'yes' ) {
				return false;
			}

			$tagmanager                                = ( $statistics
			                                               === 'google-tag-manager' )
				? true : false;
			$matomo                                    = ( $statistics
			                                               === 'matomo' ) ? true
				: false;
			$google_analytics                          = ( $statistics
			                                               === 'google-analytics' )
				? true : false;
			$accepted_google_data_processing_agreement = false;
			$ip_anonymous                              = false;
			$no_sharing                                = false;

			if ( $google_analytics || $tagmanager ) {
				$thirdparty = $google_analytics
					? cmplz_get_value( 'compile_statistics_more_info' )
					: cmplz_get_value( 'compile_statistics_more_info_tag_manager' );
				$accepted_google_data_processing_agreement
				            = ( isset( $thirdparty['accepted'] )
				                && ( $thirdparty['accepted'] == 1 ) ) ? true
					: false;
				$ip_anonymous
				            = ( isset( $thirdparty['ip-addresses-blocked'] )
				                && ( $thirdparty['ip-addresses-blocked']
				                     == 1 ) ) ? true : false;
				$no_sharing
				            = ( isset( $thirdparty['no-sharing'] )
				                && ( $thirdparty['no-sharing'] == 1 ) ) ? true
					: false;
			}

			if ( ( $tagmanager || $google_analytics )
			     && ( ! $accepted_google_data_processing_agreement
			          || ! $ip_anonymous
			          || ! $no_sharing )
			) {
				return false;
			}

			if ( $matomo
			     && ( cmplz_get_value( 'matomo_anonymized' ) !== 'yes' )
			) {
				return false;
			}

			//everything set up privacy friendly!
			return true;
		}


		public function google_analytics_always_block_ip() {
			$statistics       = cmplz_get_value( 'compile_statistics' );
			$google_analytics = ( $statistics === 'google-analytics' ) ? true
				: false;

			if ( $google_analytics ) {
				$thirdparty = cmplz_get_value( 'compile_statistics_more_info' );
				$always_block_ip
				            = ( isset( $thirdparty['ip-addresses-blocked'] )
				                && ( $thirdparty['ip-addresses-blocked']
				                     == 1 ) ) ? true : false;
				if ( $always_block_ip ) {
					return true;
				}
			}

			return false;
		}


		/**
		 * Check if Google Tag Manager is configured to fire scripts, managed remotely
		 *
		 *
		 * */

		public function tagmamanager_fires_scripts() {

			if ( ! $this->uses_google_tagmanager() ) {
				return false;
			}

			$tm_fires_scripts
				= ( cmplz_get_value( 'fire_scripts_in_tagmanager' ) === 'yes' )
				? true : false;

			return $tm_fires_scripts;
		}


		/**
		 * Check if this website shares data with third parties, used for recommendations, cookiebanner check and canada policies
		 * @return bool
		 */

		public function site_shares_data() {

			//TCF always shares data
			if ( cmplz_tcf_active() ) return true;

			//if user states no cookies are used, we simply return false.
			if ( cmplz_get_value( 'uses_cookies' ) !== 'yes' ) {
				return false;
			}

			if ( $this->tagmamanager_fires_scripts() ) {
				return true;
			}

			/**
			 * Script Center
			 */
			$thirdparty_scripts = cmplz_get_value( 'thirdparty_scripts' );
			$thirdparty_iframes = cmplz_get_value( 'thirdparty_iframes' );
			$thirdparty_scripts = strlen( $thirdparty_scripts ) == 0 ? false : true;
			$thirdparty_iframes = strlen( $thirdparty_iframes ) == 0 ? false : true;

			$ad_cookies   = ( cmplz_get_value( 'uses_ad_cookies' ) === 'yes' )
				? true : false;
			$social_media = ( cmplz_get_value( 'uses_social_media' ) === 'yes' )
				? true : false;
			$thirdparty_services
			              = ( cmplz_get_value( 'uses_thirdparty_services' )
			                  === 'yes' ) ? true : false;

			if ( $thirdparty_scripts || $thirdparty_iframes || $ad_cookies
			     || $social_media
			     || $thirdparty_services
			) {
				return true;
			}


			//get all used cookies
			$args    = array(
				'isTranslationFrom' => false,
				'ignored'           => false,
			);
			$cookies = $this->get_cookies( $args );
			if ( empty( $cookies ) ) {
				return false;
			}

			foreach ( $cookies as $cookie ) {
				$cookie_service = sanitize_title( $cookie->service );
				$has_optinstats = cmplz_uses_consenttype( 'optinstats' );
				if ( $cookie_service === 'google-analytics'
				     || $cookie_service === 'matomo'
				) {
					if ( $has_optinstats ) {
						return true;
					}
					if ( ! $this->statistics_privacy_friendly() ) {
						return true;
					}
				} else {
					$service = new CMPLZ_SERVICE( $cookie->serviceID );
					if ( $service->secondParty || $service->thirdParty ) {
						return true;
					}
				}

			}

			return false;

		}

		/**
		 *
		 * Check if the site uses non functional cookies
		 *
		 *
		 * */

		public function uses_non_functional_cookies() {
			if ( $this->tagmamanager_fires_scripts() ) {
				return true;
			}

			//get all used cookies
			$args    = array(
				'isTranslationFrom' => false,
				'ignored'           => false,
			);
			$cookies = $this->get_cookies( $args );
			if ( empty( $cookies ) ) {
				return false;
			}

			foreach ( $cookies as $cookie ) {
				$cookie_service = sanitize_title( $cookie->service );
				$has_optinstats = cmplz_uses_consenttype( 'optinstats' );
				if ( $cookie_service === 'google-analytics'
				     || $cookie_service === 'matomo'
				) {
					if ( $has_optinstats ) {
						return true;
					}
					if ( ! $this->statistics_privacy_friendly() ) {
						return true;
					}
				}
				if ( strpos(strtolower( $cookie->purpose ), 'functional' ) ===FALSE ) {
					return true;
				}
			}

			return false;

		}


		public function uses_only_functional_cookies() {
			return ! $this->uses_non_functional_cookies();
		}


		/**
		 * Check if this website uses cookies from a specific service
		 *
		 * @param $service
		 *
		 * @return bool
		 */

		public function site_uses_cookie_from_service( $service ) {
			$args    = array(
				'isTranslationFrom' => false,
				'ignored'           => false,
			);
			$cookies = $this->get_cookies( $args );
			if ( ! empty( $cookies ) ) {
				foreach ( $cookies as $cookie_name => $label ) {
					//get identifier for this cookie name
					$cookie         = new CMPLZ_COOKIE( $cookie_name );
					$cookie_service = sanitize_title( $cookie->service );
					if ( $service == $cookie_service ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Removes legacy (pre 2.1.7) cookie settings. Settings have been moved to separate database table and object
		 *
		 * @param $variation_id
		 *
		 */

		public function migrate_legacy_cookie_settings( $variation_id = '' ) {
			//check if there is already a default item.
			global $wpdb;
			$default_cookiebanner = false;
			$cookiebanners
			                      = $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookiebanners as cdb where cdb.default=true" );
			if ( $variation_id == '' && count( $cookiebanners ) >= 1 ) {
				$default_cookiebanner = $cookiebanners[0];
			}

			//the variation without ID is the default one.
			$cookie_settings
				= get_option( 'complianz_options_cookie_settings' );

			if ( $variation_id === '' && $default_cookiebanner ) {
				$banner_id = $default_cookiebanner->ID;
				$banner    = new CMPLZ_COOKIEBANNER( $banner_id );
			} else {
				$banner = new CMPLZ_COOKIEBANNER();
			}

			$banner->title   = $variation_id == ''
				? __( 'Default Cookie banner', 'complianz-gdpr' )
				: COMPLIANZ::$statistics->get_variation_nicename( $variation_id );
			$banner->default = ( $variation_id === '' ) ? true : false;

			if ( isset( $cookie_settings[ 'position' . $variation_id ] ) ) {
				$banner->position = $cookie_settings[ 'position'
				                                      . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'theme' . $variation_id ] ) ) {
				$banner->theme = $cookie_settings[ 'theme' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'revoke' . $variation_id ] ) ) {
				$banner->revoke = $cookie_settings[ 'revoke' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'dismiss' . $variation_id ] ) ) {
				$banner->dismiss = $cookie_settings[ 'dismiss'
				                                     . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'save_preferences'
			                              . $variation_id ] )
			) {
				$banner->save_preferences = $cookie_settings[ 'save_preferences'
				                                              . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'view_preferences'
			                              . $variation_id ] )
			) {
				$banner->view_preferences = $cookie_settings[ 'view_preferences'
				                                              . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'category_functional'
			                              . $variation_id ] )
			) {
				$banner->category_functional
					= $cookie_settings[ 'category_functional' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'category_all' . $variation_id ] ) ) {
				$banner->category_all = $cookie_settings[ 'category_all'
				                                          . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'category_stats'
			                              . $variation_id ] )
			) {
				$banner->category_stats = $cookie_settings[ 'category_stats'
				                                            . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'accept' . $variation_id ] ) ) {
				$banner->accept = $cookie_settings[ 'accept' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'message' . $variation_id ] ) ) {
				$banner->message_optin = $cookie_settings[ 'message'
				                                           . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'readmore' . $variation_id ] ) ) {
				$banner->readmore_optin = $cookie_settings[ 'readmore'
				                                            . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'use_categories'
			                              . $variation_id ] )
			) {
				$banner->use_categories = $cookie_settings[ 'use_categories'
				                                            . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'tagmanager_categories'
			                              . $variation_id ] )
			) {
				$banner->tagmanager_categories
					= $cookie_settings[ 'tagmanager_categories'
					                    . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'hide_revoke' . $variation_id ] ) ) {
				$banner->hide_revoke = $cookie_settings[ 'hide_revoke'
				                                         . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'dismiss_on_scroll'
			                              . $variation_id ] )
			) {
				$banner->dismiss_on_scroll
					= $cookie_settings[ 'dismiss_on_scroll' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'dismiss_on_timeout'
			                              . $variation_id ] )
			) {
				$banner->dismiss_on_timeout
					= $cookie_settings[ 'dismiss_on_timeout' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'dismiss_timeout'
			                              . $variation_id ] )
			) {
				$banner->dismiss_timeout = $cookie_settings[ 'dismiss_timeout'
				                                             . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'accept_informational'
			                              . $variation_id ] )
			) {
				$banner->accept_informational
					= $cookie_settings[ 'accept_informational'
					                    . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'message_us' . $variation_id ] ) ) {
				$banner->message_optout = $cookie_settings[ 'message_us'
				                                            . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'readmore_us' . $variation_id ] ) ) {
				$banner->readmore_optout = $cookie_settings[ 'readmore_us'
				                                             . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'readmore_privacy'
			                              . $variation_id ] )
			) {
				$banner->readmore_privacy = $cookie_settings[ 'readmore_privacy'
				                                              . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'popup_background_color'
			                              . $variation_id ] )
			) {
				$banner->popup_background_color
					= $cookie_settings[ 'popup_background_color'
					                    . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'popup_text_color'
			                              . $variation_id ] )
			) {
				$banner->popup_text_color = $cookie_settings[ 'popup_text_color'
				                                              . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'button_background_color'
			                              . $variation_id ] )
			) {
				$banner->button_background_color
					= $cookie_settings[ 'button_background_color'
					                    . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'button_text_color'
			                              . $variation_id ] )
			) {
				$banner->button_text_color
					= $cookie_settings[ 'button_text_color' . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'border_color' . $variation_id ] ) ) {
				$banner->border_color = $cookie_settings[ 'border_color'
				                                          . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'cookie_expiry'
			                              . $variation_id ] )
			) {
				$banner->cookie_expiry = $cookie_settings[ 'cookie_expiry'
				                                           . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'use_custom_cookie_css'
			                              . $variation_id ] )
			) {
				$banner->use_custom_cookie_css
					= $cookie_settings[ 'use_custom_cookie_css'
					                    . $variation_id ];
			}
			if ( isset( $cookie_settings[ 'custom_css' . $variation_id ] ) ) {
				$banner->custom_css = $cookie_settings[ 'custom_css'
				                                        . $variation_id ];
			}

			$banner->save();

			global $wpdb;
			//set the variation as having been migrated, to prevent doubles
			//update the banner id in the statistics table
			$wpdb->update( $wpdb->prefix . 'cmplz_variations',
				array( 'title' => 'migrated' ),
				array( 'ID' => $variation_id )
			);

			//update the banner id in the statistics table
			$wpdb->update( $wpdb->prefix . 'cmplz_statistics',
				array( 'cookiebanner_id' => $banner->id ),
				array( 'variation' => $variation_id )
			);

			//update the regions to consenttypes
			$wpdb->update( $wpdb->prefix . 'cmplz_statistics',
				array( 'consenttype' => 'optin' ),
				array( 'region' => 'eu' )
			);

			$wpdb->update( $wpdb->prefix . 'cmplz_statistics',
				array( 'consenttype' => 'optout' ),
				array( 'region' => 'us' )
			);

			//remove old data
			unset( $cookie_settings[ 'position' . $variation_id ] );
			unset( $cookie_settings[ 'cookie_expiry' . $variation_id ] );
			unset( $cookie_settings[ 'title' . $variation_id ] );
			unset( $cookie_settings[ 'theme' . $variation_id ] );
			unset( $cookie_settings[ 'revoke' . $variation_id ] );
			unset( $cookie_settings[ 'dismiss' . $variation_id ] );
			unset( $cookie_settings[ 'save_preferences' . $variation_id ] );
			unset( $cookie_settings[ 'view_preferences' . $variation_id ] );
			unset( $cookie_settings[ 'category_functional' . $variation_id ] );
			unset( $cookie_settings[ 'category_all' . $variation_id ] );
			unset( $cookie_settings[ 'category_stats' . $variation_id ] );
			unset( $cookie_settings[ 'accept' . $variation_id ] );
			unset( $cookie_settings[ 'message' . $variation_id ] );
			unset( $cookie_settings[ 'readmore' . $variation_id ] );
			unset( $cookie_settings[ 'use_categories' . $variation_id ] );
			unset( $cookie_settings[ 'tagmanager_categories'
			                         . $variation_id ] );
			unset( $cookie_settings[ 'hide_revoke' . $variation_id ] );
			unset( $cookie_settings[ 'dismiss_on_scroll' . $variation_id ] );
			unset( $cookie_settings[ 'dismiss_on_timeout' . $variation_id ] );
			unset( $cookie_settings[ 'dismiss_timeout' . $variation_id ] );
			unset( $cookie_settings[ 'accept_informational' . $variation_id ] );
			unset( $cookie_settings[ 'message_us' . $variation_id ] );
			unset( $cookie_settings[ 'readmore_us' . $variation_id ] );
			unset( $cookie_settings[ 'readmore_privacy' . $variation_id ] );
			unset( $cookie_settings[ 'popup_background_color'
			                         . $variation_id ] );
			unset( $cookie_settings[ 'popup_text_color' . $variation_id ] );
			unset( $cookie_settings[ 'button_text_color' . $variation_id ] );
			unset( $cookie_settings[ 'button_background_color'
			                         . $variation_id ] );
			unset( $cookie_settings[ 'border_color' . $variation_id ] );
			unset( $cookie_settings[ 'use_custom_cookie_css'
			                         . $variation_id ] );
			unset( $cookie_settings[ 'custom_css' . $variation_id ] );

			if ( is_array( $cookie_settings ) ) {
				update_option( 'complianz_options_cookie_settings',
					$cookie_settings );
			}
		}


	}
} //class closure
