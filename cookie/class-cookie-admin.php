<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_cookie_admin" ) ) {
	class cmplz_cookie_admin {
		private static $_this;
		public $position;
		public $cookies = array();
		public $cookie_settings = array();

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			$scan_in_progress = isset( $_GET['complianz_scan_token'] ) && ( sanitize_title( $_GET['complianz_scan_token'] ) == get_option( 'complianz_scan_token' ) );
			if ( $scan_in_progress ) {
				add_action( 'wp_print_footer_scripts', array( $this, 'test_cookies' ), PHP_INT_MAX, 2 );
			} else {
				add_action( 'admin_init', array( $this, 'track_cookie_changes' ) );
			}

			if ( ! is_admin() ) {
				if ( get_option( 'cmplz_wizard_completed_once' ) && $this->site_needs_cookie_warning() ) {
					add_action( 'wp_print_footer_scripts', array( $this, 'inline_cookie_script' ), PHP_INT_MAX - 50 );
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), PHP_INT_MAX - 50 );
					add_filter( 'script_loader_tag', array( $this, 'add_asyncdefer_attribute' ), 10, 2 );
					add_action( 'wp_head', array( $this, 'cookiebanner_css') );
					add_action( 'wp_footer', array( $this, 'cookiebanner_html') );
				} else {
					add_action( 'wp_print_footer_scripts', array( $this, 'inline_cookie_script_no_warning' ), 10, 2 );
				}
			} else if (isset( $_GET['page'] ) && $_GET['page'] === 'cmplz-cookiebanner' ) {
				if ( isset( $_GET['id'] ) ||  ( isset( $_GET['action'] ) && $_GET['action'] == 'new' ) ) {
					add_action( 'admin_footer', array( $this, 'cookiebanner_css' ) );
					add_action( 'admin_footer', array( $this, 'cookiebanner_html' ) );
				}
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			add_action( 'admin_footer', array( $this, 'run_cookie_scan' ) );
			add_action( 'wp_footer', array( $this, 'detect_conflicts' ), PHP_INT_MAX );
			add_action( 'wp_ajax_cmplz_store_console_errors', array( $this, 'store_console_errors' ) );
			add_action( 'wp_ajax_load_detected_cookies', array( $this, 'load_detected_cookies' ) );
			add_action( 'wp_ajax_cmplz_get_scan_progress', array( $this, 'get_scan_progress' ) );
			add_action( 'wp_ajax_cmplz_run_sync', array( $this, 'run_sync' ) );
			add_action( 'admin_init', array( $this, 'run_sync_on_update' ) );
			add_action( 'admin_init', array( $this, 'ensure_cookies_in_all_languages' ) );
			add_action( 'plugins_loaded', array( $this, 'rescan' ), 20, 2 );
			add_action( 'plugins_loaded', array( $this, 'clear_cookies' ), 20, 2 );

			//callback from settings
			add_action( 'cmplz_cookie_scan', array( $this, 'scan_progress' ), 10, 1 );
			add_action( 'cmplz_cookiedatabase_sync', array( $this, 'sync_progress' ), 10, 1 );
			add_action( 'cmplz_statistics_script', array( $this, 'get_statistics_script' ), 10 );
			add_action( 'cmplz_tagmanager_script', array( $this, 'get_tagmanager_script' ), 10 );
			add_action( 'cmplz_before_statistics_script', array( $this, 'add_gtag_js' ), 10 );
			add_action( 'cmplz_before_statistics_script', array( $this, 'add_clicky_js' ), 10 );
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

		public function detect_conflicts() {

			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( wp_doing_ajax() || is_admin() ) {
				return;
			}

			if ( ! $this->site_needs_cookie_warning() ) {
				return;
			}

			//don't fire on the back-end
			if ( is_preview() || cmplz_is_pagebuilder_preview() || isset($_GET["cmplz_safe_mode"]) ) {
				return;
			}

			if ( cmplz_get_value( 'disable_cookie_block' ) ) {
				return;
			}

			//not when scan runs
			if ( isset( $_GET['complianz_scan_token'] ) ) {
				return;
			}

			/* Do not fix mixed content when call is coming from wp_api or from xmlrpc or feed */
			if ( defined( 'JSON_REQUEST' ) && JSON_REQUEST ) {
				return;
			}

			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
				return;
			}

			$checked_count = intval( get_transient( 'cmplz_checked_for_js_count' ) );
			if ( $checked_count > 5  ) {
				return;
			}

			$nonce = wp_create_nonce( 'cmplz-detect-errors' );
			?>
			<script>
				var request = new XMLHttpRequest();
				var error_ocurred = false;
				window.onerror = function (msg, url, lineNo, columnNo, error) {
					error_ocurred = true;

					var request = new XMLHttpRequest();
					request.open('POST', '<?php echo add_query_arg(
						array(
							'type'   => 'errors',
							'nonce'  => $nonce,
							'action' => 'cmplz_store_console_errors'
						),
						admin_url( 'admin-ajax.php' )
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
								'type'   => 'errors',
								'nonce'  => $nonce,
								'action' => 'cmplz_store_console_errors'
							),
							admin_url( 'admin-ajax.php' )
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

		public function store_console_errors() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( ! $this->site_needs_cookie_warning() ) {
				return;
			}

			/**
			 * limit to one request each two minutes.
			 */

			$checked_count = intval( get_transient( 'cmplz_checked_for_js_count' ) );
			if ( $checked_count > 5  ) {
				return;
			}

			set_transient( 'cmplz_checked_for_js_count' , $checked_count + 1, 5 * MINUTE_IN_SECONDS );
			$success = false;
			if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'cmplz-detect-errors' ) ) {
				if ( isset( $_POST['no-errors'] ) ) {
					update_option( 'cmplz_detected_console_errors', false );
					$success = true;
				} else {
					$errors = array_keys( array_map( 'sanitize_text_field', $_POST ) );
					if ( count( $errors ) > 0 && strpos($errors[0], 'runReadyTrigger') === false) {
						$errors = explode( ',', str_replace( site_url(), '', $errors[0] ) );
						if ( isset( $errors[1] ) && $errors[1] > 1 ) {
							update_option( 'cmplz_detected_console_errors', $errors );
						}
						$success = true;
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
		 * When special data is processed, Canada requires optin consenttype
		 *
		 * @param string $consenttype
		 * @param string $region
		 *
		 * @return string $consenttype
		 */

		public function maybe_filter_consenttype( $consenttype, $region ) {
			if ( $region === 'ca'
				 && cmplz_site_shares_data()
			     && cmplz_get_value( 'sensitive_information_processed' ) === 'yes'
			) {
				$consenttype = 'optin';
			}
			if ( $region === 'au'
				 && cmplz_site_shares_data()
			     && cmplz_get_value( 'sensitive_information_processed' )==='yes'
			     && cmplz_uses_marketing_cookies()
			) {
				$consenttype = 'optin';
			}

			return $consenttype;
		}

		/**
		 * create select html for services
		 *
		 * @param string $selected_value
		 * @param string $language
		 *
		 * @return string
		 */

		public function get_services_options( $selected_value, $language ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return '';
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
		 * @param string $selected_value
		 * @param string $language
		 *
		 * @return string
		 */

		public function get_serviceTypes_options( $selected_value, $language ) {
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
						$serviceTypes, MONTH_IN_SECONDS );
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
		 * @param string $selected_value
		 * @param string $language
		 *
		 * @return string
		 */

		public function get_cookiePurpose_options( $selected_value, $language ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$html = '<option value="" >' . esc_html( __( 'Select a purpose',
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
							cmplz_register_translation( $cookiePurpose, $cookiePurpose );
						}
					}

					set_transient( 'cmplz_purposes_' . $language, $cookiePurposes, MONTH_IN_SECONDS );
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
		 * @param CMPLZ_COOKIE
		 *
		 * @return string
		 */

		public function get_cookie_list_item_html( $tmpl, $cookie ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return '';
			}

			$sync         = COMPLIANZ::$cookie_admin->use_cdb_api() ? $cookie->sync : false;
			$syncDisabled = '';
			if ( ! COMPLIANZ::$cookie_admin->use_cdb_api() ) {
				$syncDisabled = 'cmplz-disabled';
			}

			$disabled      = $sync ? 'disabled="disabled"' : false;
			$disabledClass = $sync ? 'cmplz-disabled' : false;
			$sync          = $sync ? 'checked="checked"' : '';

			$isPersonalData = $cookie->isPersonalData == 1 ? 'checked="checked"' : '';
			$showOnPolicy   = $cookie->showOnPolicy == 1 ? 'checked="checked"' : '';
			$services       = $this->get_services_options( $cookie->service, $cookie->language );
			$cookiePurposes = $this->get_cookiePurpose_options( $cookie->purpose, $cookie->language );

			$link = '';
			if ( cmplz_get_value( 'use_cdb_links' ) === 'yes'
			     && strlen( $cookie->slug ) !== 0
			) {
				$service_slug = ( strlen( $cookie->service ) === 0 ) ? 'unknown-service' : $cookie->service;
				$link         = '<a target="_blank" href="https://cookiedatabase.org/cookie/' . $service_slug . '/' . $cookie->slug . '/">'
				                . __( "View cookie on cookiedatabase.org", "complianz-gdpr" ) .
				                '</a>';
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
					$link,
				),
				$tmpl );

			$icons = '';

			$membersOnly = ( !$cookie->ignored && cmplz_get_value( 'wp_admin_access_users' ) === 'no' && $cookie->isMembersOnly );

			if ( $cookie->complete || $cookie->ignored || $membersOnly) {
				$icons .= cmplz_icon( 'check', 'success', __( "The data for this cookie is complete", "complianz-gdpr" ));
			} else {
				$icons .= cmplz_icon( 'times', 'error', __( "This cookie has missing fields", "complianz-gdpr" ));
			}

			if ( ! $cookie->sync ) {
				$icons .= cmplz_icon( 'sync', 'disabled', __( "Synchronization with cookiedatabase.org is not enabled for this cookie", "complianz-gdpr" ) );
			} elseif ( $cookie->synced ) {
				$icons .= cmplz_icon( 'sync', 'success', __( "This cookie has been synchronized with cookiedatabase.org.",'complianz-gdpr').'&nbsp;'.__( "Our moderators will keep the cookies up-to-date.", "complianz-gdpr" ) );
			} else {
				$icons .= cmplz_icon( 'sync-error', 'error', __( "This cookie is not yet synchronized with cookiedatabase.org.",'complianz-gdpr').'&nbsp;'.__( "Either try again, or if this fails; our moderators will investigate further and automatically update the descriptions.", "complianz-gdpr" ) );
			}

			if ( $cookie->showOnPolicy && ! $cookie->ignored ) {
				$icons .= cmplz_icon( 'file', 'success', __( "This cookie will be on your Cookie Policy", "complianz-gdpr" ) );
			} else {
				$icons .= cmplz_icon( 'file-disabled', 'disabled', __( "This cookie is not shown on the Cookie Policy", "complianz-gdpr" ) );
			}

			if ( ! $cookie->old ) {
				$icons .= cmplz_icon( 'calendar', 'success', __( "This cookie has recently been detected", "complianz-gdpr" ) );
			} else {
				$icons .= cmplz_icon( 'calendar-error', 'error', __( "This cookie has not been detected on your site in the last three months", "complianz-gdpr" ) );
			}

			$notice      = ( $cookie->old ) ? cmplz_notice( __( 'This cookie has not been found in the scan for three months. Please check if you are still using this cookie',
				'complianz-gdpr' ), 'warning', false ) : '';
			$cookie_html = $notice . $cookie_html;
			$ignored     = ( $cookie->ignored ) ? ' <i>' . __( '(Administrator cookie, will be ignored)', 'complianz-gdpr' ) . '</i>' : '';
			$membersOnly =  $membersOnly ? ' <i>' . __( '(Logged in users only, will be ignored)', 'complianz-gdpr' ) . '</i>' : '';

			$html = cmplz_panel( cmplz_sprintf( __( 'Cookie "%s"%s%s', 'complianz-gdpr' ), $cookie->name, $ignored, $membersOnly ),
				$cookie_html, $icons, false, false );

			if ( $cookie->deleted ) {
				$html = str_replace( array( 'cmplz-toggle-active' ), array( 'cmplz-toggle-active cmplz-deleted' ), $html );
			}
			if ( $cookie->ignored ) {
				$html = str_replace( array( 'cmplz-toggle-active' ), array( 'cmplz-toggle-disabled' ), $html );
			}

			return $html;
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

			$sync         = COMPLIANZ::$cookie_admin->use_cdb_api() ? $service->sync : false;
			$syncDisabled = '';
			if ( ! COMPLIANZ::$cookie_admin->use_cdb_api() ) {
				$syncDisabled = 'cmplz-disabled';
			}

			$serviceTypes = $this->get_serviceTypes_options( $service->serviceType, $language );

			$disabled      = $sync ? 'disabled="disabled"' : false;
			$disabledClass = $sync ? 'cmplz-disabled' : false;
			$sync          = $sync ? 'checked="checked"' : '';

			$link = '';
			if ( cmplz_get_value( 'use_cdb_links' ) === 'yes'
			     && strlen( $service->slug ) !== 0
			     && $service->slug !== 'unknown-service'
			) {
				$link = '<a target="_blank" href="https://cookiedatabase.org/service/' . $service->slug . '/">'
				        . __( "View service on cookiedatabase.org", "complianz-gdpr" ) .
				        '</a>';
			}

			$sharesData   = $service->sharesData == 1 ? 'checked="checked"' : '';
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
					$link,
				),
				$tmpl );

			$icons = '';

			if ( $service->complete ) {
				$icons .= cmplz_icon( 'check', 'success', __( "The data for this service is complete", "complianz-gdpr" ) );
			} else {
				$icons .= cmplz_icon( 'times', 'error', __( "This service has missing fields", "complianz-gdpr" ) );
			}

			if ( ! $service->sync ) {
				$icons .= cmplz_icon( 'sync', 'disabled', __( "Synchronization with cookiedatabase.org is not enabled for this service", "complianz-gdpr" ) );
			} elseif ( $service->synced ) {
				$icons .= cmplz_icon( 'sync', 'success', __( "This service has been synchronized with cookiedatabase.org", "complianz-gdpr" ) );
			} else {
				$icons .= cmplz_icon( 'sync', 'error', __( "This service is not synchronized with cookiedatabase.org", "complianz-gdpr" ) );
			}

			return cmplz_panel( cmplz_sprintf( __( 'Service "%s"', 'complianz-gdpr' ), $service->name ), $service_html, $icons, false, false );
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
				'isMembersOnly' => 'all',
			);

			if ( $type == 'cookie' ) {
				$this->reset_cookies_changed();
				$items = $this->get_cookies( $args );
				//group by service
				$grouped_by_service = array();
				foreach ( $items as $cookie ) {
					$service = strlen( $cookie->service ) !== 0 ? $cookie->service : 'no-service';
					$grouped_by_service[ $service ][] = $cookie;
				}

				$html = '';
				$tmpl = cmplz_get_template( $type . '_settings.php' );
				if ( $grouped_by_service ) {
					foreach ( $grouped_by_service as $service_name => $cookies ) {
						$class = '';
						if ( $service_name === 'no-service' ) {
							$service = __( 'Cookies without selected service', 'complianz-gdpr' );
							$class   = 'no-service';
						} else {
							$service = $service_name;
						}
						$html .= '<div class="cmplz-service-cookie-list">';
						$html .= '<div class="cmplz-service-divider ' . $class . '">' . $service . '</div>';
						foreach ( $cookies as $cookie ) {
							$html .= $this->get_cookie_list_item_html( $tmpl, $cookie );
						}
						$html .= '</div>';
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
			$divider = '';
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
				$services     = $this->get_services_options( '', $language );
				$purposes     = $this->get_cookiePurpose_options( '', $language );
				$serviceTypes = $this->get_serviceTypes_options( '', $language );

				if ( $type === 'cookie' ) {
					$title   = 'Cookie "' . $name . '"';
					$divider = '<div class="cmplz-service-divider no-service">'
					           . __( 'Cookies without selected service',
							'complianz-gdpr' ) . '</div>';
					$html    = str_replace( array(
						'{' . $type . '_id}',
						'{disabled}',
						'{name}',
						'{services}',
						'{retention}',
						'{sync}',
						'{syncDisabled}',
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
						'',
						'checked="checked"',
						'',
						$purposes,
						'',
						'',
					), $tmpl );
				} else {
					$title        = 'Service "' . $name . '"';
					$syncDisabled = ! COMPLIANZ::$cookie_admin->use_cdb_api() ? 'cmplz-disabled' : '';
					$html         = str_replace( array(
						'{' . $type . '_id}',
						'{disabled}',
						'{name}',
						'{serviceTypes}',
						'{privacyStatementURL}',
						'{sync}',
						'{syncDisabled}',
						'{showOnPolicy}',
						'{link}',
					), array(
						$new_id,
						'',
						$name,
						$serviceTypes,
						'',
						'',
						$syncDisabled,
						'checked="checked"',
						'',
					), $tmpl );
				}
				$html = cmplz_panel( __( $title, 'complianz-gdpr' ), $html, '',
					'', false, true );
			}

			$data     = array(
				'success' => true,
				'message' => $msg,
				'action'  => $action,
				'html'    => $html,
				'divider' => $divider,

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
		 * Runs each used cookies overview pageload to check if any new languages were added in the meantime.
		 *
		 * @hooked admin_init
		 */

		public function ensure_cookies_in_all_languages() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			$step = isset($_REQUEST['step']) ? $_REQUEST['step'] : '1';
			if ( $step != STEP_COOKIES ) {
				return;
			}

			$data = $this->get_syncable_cookies( true );
			//if no syncable cookies are found, exit.
			if ( $data['count'] == 0 ) {
				return;
			}

			if ( ! isset( $data['en'] ) ) {
				return;
			}

			//get english cookies
			$en_cookie_data = $data['en'];
			$languages      = $this->get_supported_languages();
			foreach ( $languages as $language ) {

				if ( $language === 'en' ) {
					continue;
				}

				//make sure each cookie is available in all languages
				foreach ( $en_cookie_data as $service_name => $en_cookies ) {
					foreach ( $en_cookies as $cookie_name ) {
						$en_cookie         = new CMPLZ_COOKIE( $cookie_name );
						$translated_cookie = new CMPLZ_COOKIE( $cookie_name, $language );
						if ( ! $translated_cookie->ID ) {
							$translated_cookie->isTranslationFrom = $en_cookie->ID;
							$translated_cookie->sync              = $en_cookie->sync;
							$translated_cookie->showOnPolicy      = $en_cookie->showOnPolicy;
							$translated_cookie->name              = $cookie_name;
							$translated_cookie->isPersonalData    = $en_cookie->isPersonalData;
							$translated_cookie->isMembersOnly     = $en_cookie->isMembersOnly;
							$translated_cookie->serviceID         = $en_cookie->serviceID;
							$translated_cookie->service           = $en_cookie->service;
							$translated_cookie->slug              = $en_cookie->slug;
							$translated_cookie->ignored           = $en_cookie->ignored;
							$translated_cookie->lastAddDate       = time();
							$translated_cookie->save();
						}
					}
				}
			}

		}


		/**
		 * Runs once a week to check if the CDB should be synced
		 *
		 * @param bool $running_after_services
		 *
		 * @hooked cmplz_every_week_hook
		 */

		public function maybe_sync_cookies( $running_after_services = false ) {
			if ( ! wp_doing_cron() && ! current_user_can( 'manage_options' ) ) {
				return 'No permissions';
			}
			$msg   = '';
			$error = false;
			$data  = $this->get_syncable_cookies();
			if ( ! $this->use_cdb_api() ) {
				$error = true;
				$msg   = __( 'You haven\'t accepted the usage of the cookiedatabase.org API. To automatically complete your cookie descriptions, please choose yes.', 'complianz-gdpr' );
			}

			//if no syncable cookies are found, exit.
			if ( $data['count'] == 0 ) {
				update_option( 'cmplz_sync_cookies_complete', true );
				$msg   = "No cookies";
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
				$endpoint        = trailingslashit( CMPLZ_COOKIEDATABASE_URL ) . 'v2/cookies/';
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
				if ( $result === false ) {
					$error = true;
				}

				if ( strpos( $result, '<title>502 Bad Gateway</title>' ) !== false ) {
					$error = true;
				}

				if ( $error ) {
					$msg = __( "Could not connect to cookiedatabase.org", "complianz-gdpr" );
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
				if ( is_object( $result ) && property_exists($result, 'en') ) {
					$services           = $result->en;
					foreach ($services as $service => $cookies ) {
						$service_name = ($service !== 'no-service-set') ? $service : false;
						$isTranslationFrom = array();
						foreach (
							$cookies as $original_cookie_name => $cookie_object
						) {
							if ( !is_object( $cookie_object ) || !property_exists($cookie_object, 'name') ) {
								continue;
							}

							$cookie                        = new CMPLZ_COOKIE( $original_cookie_name, 'en', $service_name );
							$cookie->name                  = $cookie_object->name;
							$cookie->retention             = $cookie_object->retention;
							$cookie->type                  = $cookie_object->type;
							$cookie->collectedPersonalData = $cookie_object->collectedPersonalData;
							$cookie->cookieFunction        = $cookie_object->cookieFunction;
							$cookie->purpose               = $cookie_object->purpose;
							$cookie->isPersonalData        = $cookie_object->isPersonalData;
							$cookie->isMembersOnly         = $cookie_object->isMembersOnly;
							$cookie->service               = $cookie_object->service;
							$cookie->ignored               = $cookie_object->ignore;
							$cookie->slug                  = $cookie_object->slug;
							$cookie->lastUpdatedDate       = time();
							$cookie->save();
							$isTranslationFrom[ $cookie->name ] = $cookie->ID;
						}
					}

					foreach ( $result as $language => $services ) {
						if ( $language === 'en' ) {
							continue;
						}

						foreach ($services as $service => $cookies ) {
							$service_name = ($service !== 'no-service-set') ? $service : false;
							foreach (
								$cookies as $original_cookie_name => $cookie_object
							) {
								if ( !is_object( $cookie_object ) || !property_exists($cookie_object, 'name') ) {
									continue;
								}

								$cookie                  	   = new CMPLZ_COOKIE( $original_cookie_name, $language, $service_name);
								$cookie->name                  = $cookie_object->name;
								$cookie->retention             = $cookie_object->retention;
								$cookie->collectedPersonalData = $cookie_object->collectedPersonalData;
								$cookie->cookieFunction        = $cookie_object->cookieFunction;
								$cookie->purpose               = $cookie_object->purpose;
								$cookie->isPersonalData        = $cookie_object->isPersonalData;
								$cookie->isMembersOnly         = $cookie_object->isMembersOnly;
								$cookie->service               = $cookie_object->service;
								$cookie->slug                  = $cookie_object->slug;
								$cookie->ignored               = $cookie_object->ignore;
								$cookie->lastUpdatedDate     = time();

								//when there's no en cookie, create one.
								if ( ! isset( $isTranslationFrom[ $cookie->name ] )
									 && $language !== 'en'
								) {
									$parent_cookie = new CMPLZ_COOKIE( $cookie->name, 'en' );
									$parent_cookie->save();
									$isTranslationFrom[ $cookie->name ] = $parent_cookie->ID;
								}

								$cookie->isTranslationFrom = $isTranslationFrom[ $cookie->name ];
								$cookie->save();
							}
						}
					}
				}
				$this->update_sync_date();
			}

			if ( $running_after_services ) {
				update_option( 'cmplz_sync_cookies_after_services_complete', true );
			} else {
				update_option( 'cmplz_sync_cookies_complete', true );
			}

			return $msg;
		}

		/**
		 * Get list of services to be synced
		 *
		 * @return array
		 */
		public function get_syncable_services() {
			$languages = $this->get_supported_languages();
			$data      = array();
			$count_all    = 0;
			$one_week_ago = strtotime( "-1 month" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language, 'includeServicesWithoutCookies' => true );
				if ( ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_MONTH_CHECK' )
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

		/**
		 * Get cookies to be synced
		 *
		 * @param false $ignore_time_limit
		 *
		 * @return array
		 */

		public function get_syncable_cookies( $ignore_time_limit = false ) {
			$languages            = $this->get_supported_languages();
			$data                 = array();
			$index                = array();
			$thirdparty_cookies   = array();
			$localstorage_cookies = array();
			$count_all            = 0;
			$ownDomainCookies = $this->get_cookies(array('isOwnDomainCookie'=>true));
			$hasOwnDomainCookies = count($ownDomainCookies) >0 ;
			$one_week_ago = strtotime( "-1 month" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language );
				if ( ! $ignore_time_limit && ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_MONTH_CHECK' )
				) {
					$args['lastUpdatedDate'] = $one_week_ago;
				}
				$cookies   = $this->get_cookies( $args );
				$index[$language]     = 0;
				foreach ( $cookies as $c_index => $cookie ) {
					$c    = new CMPLZ_COOKIE( $cookie->name, $language, $cookie->service );
					$slug = $c->slug ?: $index[$language];
					//pass the type to the CDB
					if ( $c->type === 'localstorage' ) {
						if (!in_array($cookie->name, $localstorage_cookies) ) $localstorage_cookies[] = $cookie->name;
					}
					//need to pass a service here.
					if ( strlen( $c->service ) != 0 ) {
						$service = new CMPLZ_SERVICE( $c->service );

						//deprecated as of 5.3. Use only if no own domain cookie property has ever been saved
						if ( !$hasOwnDomainCookies ) {
							if ( $service->thirdParty || $service->secondParty ) {
								if (!in_array($cookie->name, $thirdparty_cookies) ) $thirdparty_cookies[] = $cookie->name;
							}
						}

						$data[ $language ][ $c->service ][ $slug ] = $cookie->name;
						//make sure the cookie is available in en as well.
						if (!isset($data[ 'en' ][ $c->service ][ $slug ])) {
							$data[ 'en' ][ $c->service ][ $slug ] = $cookie->name;
						}
					} else {
						$data[ $language ]['no-service-set'][ $slug ] = $cookie->name;
						if (!isset($data[ 'en' ]['no-service-set'][ $slug ])) {
							$data[ 'en' ]['no-service-set'][ $slug ] = $cookie->name;
						}
					}

					//use as of 5.3. Each non own domain cookie is added to the "thirdparty" list, which is synced onlly with non own domain cookies.
					if ( $hasOwnDomainCookies ) {
						if ( !$c->isOwnDomainCookie ) {
							if (!in_array($cookie, $thirdparty_cookies) ) $thirdparty_cookies[] = $cookie->name;
						}
					}

					$index[$language] ++;
				}
			}

			//now count the "EN" cookies
			if ( isset($data['en']) && is_array($data['en']) ) {
				foreach ($data['en'] as $service => $cookies ){
					$count_all += is_array($cookies) ? count($cookies) : 0;
				}
			}

			$data['count']               = $count_all;
			$data['thirdpartyCookies']   = $thirdparty_cookies;
			$data['localstorageCookies'] = $localstorage_cookies;

			return $data;
		}

		/**
		 * Get the cookie domain, without https or end slash
		 *
		 * @return string
		 */

		public function get_cookie_domain() {
			$domain = str_replace( array( 'http://', 'https://' ), '', cmplz_get_value( 'cookie_domain' ) );
			if ( substr( $domain, - 1 ) == '/' ) {
				$domain = substr( $domain, 0, - 1 );
			}

			return apply_filters('cmplz_cookie_domain', $domain);
		}

		/**
		 * Get prefix for our Complianz cookies
		 *
		 * @return string
		 */
		public function get_cookie_prefix(){
			if ( is_multisite() && is_main_site() && !cmplz_get_value( 'set_cookies_on_root' ) ) {
				return 'cmplz_rt_';
			} else {
				return 'cmplz_';
			}
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
				$msg   = __( 'You haven\'t accepted the usage of the cookiedatabase.org API. To automatically complete your cookie descriptions, please choose yes.', 'complianz-gdpr' );
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

				if ( $result === false ) {
					$error = true;
				}

				if ( strpos( $result, '<title>502 Bad Gateway</title>' ) !== false ) {
					$error = true;
				}

				if ( $error ) {
					$msg = __( "Could not connect to cookiedatabase.org", "complianz-gdpr" );
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
				if ( is_object( $result) && property_exists($result, 'en') ) {
					$services = $result->en;

					$isTranslationFrom = array();
					foreach (
						$services as $original_service_name =>
						$service_and_cookies
					) {
						if ( !is_object( $service_and_cookies) || !property_exists( $service_and_cookies, 'service') ){
							continue;
						}

						$service_object = $service_and_cookies->service;

						//sync service data
						if ( !is_object( $service_object) || !property_exists( $service_object, 'name') ) {
							continue;
						}

						$service                      = new CMPLZ_SERVICE( $original_service_name, 'en' );
						$service->name                = $service_object->name;
						$service->privacyStatementURL = $service_object->privacyStatementURL;
						$service->sharesData          = $service_object->sharesData;
						$service->secondParty         = $service_object->secondParty;
						$service->thirdParty          = $service_object->sharesData && ! $service_object->secondParty;
						$service->serviceType         = $service_object->serviceType;
						$service->slug                = $service_object->slug;
						$service->lastUpdatedDate     = time();
						$service->save( true, false );
						$isTranslationFrom[ $service->name ] = $service->ID;

						//get the cookies only if it's third party service. Otherwise, just sync the service itself.
						if ( $service->thirdParty
						     || $service->secondParty
						        && isset( $service_and_cookies->cookies )
						) {
							$cookies = $service_and_cookies->cookies;
							if ( ! is_array( $cookies ) ) {
								continue;
							}

							foreach ( $cookies as $cookie_name ) {
								$cookie = new CMPLZ_COOKIE( $cookie_name, 'en', $service->name );
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
						if ( !is_object( $service_and_cookies) || !property_exists( $service_and_cookies, 'service') ){
							continue;
						}
						$service_object = $service_and_cookies->service;

						if ( !is_object( $service_object) || !property_exists( $service_object, 'name') ){
							continue;
						}

						$service                  = new CMPLZ_SERVICE( $original_service_name, $language );
						$service->name = $service_object->name;
						$service->privacyStatementURL = $service_object->privacyStatementURL;
						$service->sharesData = $service_object->sharesData;
						$service->secondParty = $service_object->secondParty;
						$service->serviceType = $service_object->serviceType;
						$service->slug = $service_object->slug;

						//when there's no 'en' service, create one.
						if ( ! isset( $isTranslationFrom[ $service->name ] ) ) {
							$parent_service           = new CMPLZ_SERVICE( $service->name, 'en' );
							$parent_service->save( false, false );
							$isTranslationFrom[ $service->name ] = $parent_service->ID;
						}

						$service->isTranslationFrom = $isTranslationFrom[ $service->name ];
						$service->save( false, false );

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
						= $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}cmplz_cookies where name = %s and language = %s and serviceID = %s ",
						$cookie->name, $language, $cookie->serviceID ) );
					if ( count( $same_name_cookies ) > 1 ) {
						array_shift( $same_name_cookies );
						$IDS = wp_list_pluck( $same_name_cookies, 'ID' );
						$sql = implode( ' OR ID =', $IDS );
						$sql = "DELETE from {$wpdb->prefix}cmplz_cookies where ID=" . $sql;
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

		public function processing_agreements() {
			ob_start();
			//include( cmplz_path . '/class-processing-table.php' );

			$processing_agreements_table = new WP_List_Table();
			$processing_agreements_table->prepare_items();

			?>

			<div id="processing_agreements" class="wrap processing_agreements">
				<h1><?php _e( "Processing Agreements", 'complianz-gdpr' ) ?></h1>
				<?php
				cmplz_notice( __( 'A Processor or Service Provider is the party that processes personal data on behalf of a responsible organization or person. ' .
				                  'If you are this organization or person, you should probably sign a Processing Agreement with them. ' .
				                  'You can use a Processing Agreement outside of Complianz, or generate one here.', 'complianz-gdpr' ) ) ?>

				<form id="cmplz-processing-agreements-create" method="POST" action="">
					<?php echo wp_nonce_field( 'cmplz_processing_agreements', 'cmplz_nonce' ); ?>
					<select>
						<option id="eu">EU</option>
					</select>
					<input type="submit" class="button button-primary"
					       name="cmplz_create_processing_agreement"
					       value="<?php _e( "Create", "complianz-gdpr" ) ?>"/>
				</form>
				<form id="cmplz-processing-agreements-filter" method="get" action="">

					<?php
					$processing_agreements_table->display();
					?>
					<input type="hidden" name="page" value="cmplz-processing-agreements"/>

				</form>
				<?php do_action( 'cmplz_after_cookiesnapshot_list' ); ?>
			</div>

			<?php

			$content = ob_get_clean();
			$args    = array(
				'page'    => 'processing-agreements',
				'content' => $content,
			);
			echo cmplz_get_template( 'admin_wrap.php', $args );
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

			$thirdparty = ( cmplz_get_value( 'uses_thirdparty_services' ) === 'yes' ) ? true : false;
			if ( $thirdparty ) {
				$thirdparty_types = cmplz_get_value( 'thirdparty_services_on_site' );
				foreach ( $thirdparty_types as $slug => $active ) {
					if ( $active == 1 ) {
						$service = new CMPLZ_SERVICE();
						//add for all languages
						$service_name = $thirdparty_services = COMPLIANZ::$config->thirdparty_services[ $slug ];
						$service->add( $service_name, $this->get_supported_languages(), false, 'service' );
					} else {
						$service = new CMPLZ_SERVICE( $slug );
						$service->delete();
					}
				}
			}
		}

		/**
		 * Rescan after a manual "rescan" command from the user
		 */

		public function rescan() {
			if ( isset( $_POST['rescan'] ) ) {
				if ( ! isset( $_POST['cmplz_nonce'] )
				     || ! wp_verify_nonce( $_POST['cmplz_nonce'],
						'complianz_save' )
				) {
					return;
				}

				update_option( 'cmplz_detected_social_media', false );
				update_option( 'cmplz_detected_thirdparty_services', false );
				update_option( 'cmplz_detected_stats', false );
				$this->reset_pages_list( false, true );
			}
		}

		/**
		 * Clear the cookies table
		 */

		public function clear_cookies() {
			if ( isset( $_POST['clear'] ) ) {
				if ( ! isset( $_POST['cmplz_nonce'] )
				     || ! wp_verify_nonce( $_POST['cmplz_nonce'],
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

				$this->resync();
			}
		}

		/**
		 * Start a new sync
		 *
		 * @param bool $force
		 */

		public function resync() {
			update_option( 'cmplz_sync_cookies_complete', false );
			update_option( 'cmplz_sync_cookies_after_services_complete', false );
			update_option( 'cmplz_sync_services_complete', false );
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

		/**
		 * Defer complianz.js
		 * @param string $tag
		 * @param string $handle
		 *
		 * @return string
		 */
		public function add_asyncdefer_attribute($tag, $handle) {
			if ( $handle === 'cmplz-cookiebanner' || $handle === 'cmplz-tcf' ) {
				return preg_replace( '/^<script /', '<script defer ', $tag );
			}
			return $tag;
		}

		/**
		 * Enqueue cookie banner javascript
		 *
		 * @return void
		 */
		public function enqueue_assets( ) {
			$minified = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			$banner = new CMPLZ_COOKIEBANNER( apply_filters( 'cmplz_user_banner_id', cmplz_get_default_banner_id() ) );
			$cookiesettings = $banner->get_front_end_settings();
			$deps = array();
			if ( cmplz_tcf_active() ) {
				$deps[] = 'cmplz-tcf';
			}
			if ( get_option('cmplz_post_scribe_required') ) {
				$deps[] = 'cmplz-postscribe';
				wp_enqueue_script( 'cmplz-postscribe', cmplz_url . "assets/js/postscribe.min.js", array( 'jquery' ), cmplz_version, true );
			}
			wp_enqueue_script( 'cmplz-cookiebanner', cmplz_url . "cookiebanner/js/complianz$minified.js", $deps, cmplz_version, true );
			wp_localize_script( 'cmplz-cookiebanner', 'complianz', $cookiesettings );

			if ( cmplz_get_value( 'enable_migrate_js' ) ) {
				wp_enqueue_script( 'cmplz-migrate', cmplz_url . "cookiebanner/js/migrate$minified.js", array('cmplz-cookiebanner'), cmplz_version, true );
			}
		}

		/**
		 * Inline css to default hide the banner until fully loaded
		 * @return void
		 */
		public function cookiebanner_css(){
			?><style>.cmplz-hidden{display:none!important;}</style><?php
		}
		/**
		 * Load the cookie banner html for each consenttype
		 */
		public function cookiebanner_html(){
			$editor = $new = false;
			if (is_admin() && isset($_GET['page'] ) && $_GET['page'] === 'cmplz-cookiebanner' && cmplz_user_can_manage() ) {
				$editor = isset( $_GET['id'] ) ||  ( isset( $_GET['action'] ) && $_GET['action'] == 'new' );
				$new = isset( $_GET['action'] ) && $_GET['action'] == 'new' ;
			}

			$consent_types = cmplz_get_used_consenttypes();
			$path = trailingslashit( cmplz_path ).'cookiebanner/templates/';
			$manage_consent_template = cmplz_get_template( "manage-consent.php", false, $path);
			$banner_html='';
			$manage_consent_html = '';
			global $consent_type;
			foreach ( $consent_types as $consent_type ) {
				$banner_template = cmplz_get_template( "cookiebanner.php", array( 'consent_type' => $consent_type ), $path);
				if ( $editor ) {
					if ($new) {
						$banner_ids = array(false);
					} else {
						$banner_ids = array(intval($_GET['id']));
					}
				} else {
					if ( cmplz_ab_testing_enabled() ) {
						$banner_ids = wp_list_pluck(cmplz_get_cookiebanners(), 'ID');
					} else {
						$banner_ids = array(cmplz_get_default_banner_id());
					}
				}

				foreach ( $banner_ids  as $banner_id ) {
					$temp_banner_html = $banner_template;
					$temp_manage_consent_html = $manage_consent_template;
					$banner = new CMPLZ_COOKIEBANNER( $banner_id );
					$cookie_settings = $banner->get_html_settings();
					foreach($cookie_settings as $fieldname => $value ) {
						if ( isset($value['text']) ) $value = $value['text'];
						if ( is_array($value) ) continue;
						if ( $fieldname !== 'logo') $value = nl2br($value);
						$temp_banner_html = str_replace( '{'.$fieldname.'}', $value, $temp_banner_html );
						$temp_manage_consent_html = str_replace( '{'.$fieldname.'}', $value, $temp_manage_consent_html );
					}
					$banner_html .= $temp_banner_html;
					$manage_consent_html .= $temp_manage_consent_html;
				}
			}

			$comment = apply_filters('cmplz_document_comment', "\n"
															   . "<!-- Consent Management powered by Complianz | GDPR/CCPA Cookie Consent https://wordpress.org/plugins/complianz-gdpr -->"
															   . "\n");
			echo   $comment .
					'<div id="cmplz-cookiebanner-container">'.apply_filters("cmplz_banner_html", $banner_html).'</div>
					<div id="cmplz-manage-consent" data-nosnippet="true">'.apply_filters("cmplz_manage_consent_html", $manage_consent_html).'</div>';
		}

		/**
		 * Here we add scripts and styles for the wysywig editor on the backend
		 * @param string $hook
		 *
		 * */

		public function enqueue_admin_assets( $hook ) {
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cmplz-wizard' ) {
				wp_register_style( 'select2', cmplz_url . 'assets/select2/css/select2.min.css', false, cmplz_version );
				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'select2', cmplz_url . "assets/select2/js/select2.min.js", array( 'jquery' ), cmplz_version, true );

				//script to check for ad blockers
				wp_enqueue_script( 'cmplz-ad-checker', cmplz_url . "assets/js/ads.js", array( 'cmplz-admin' ), cmplz_version, true );
			}
		}

		/**
		 * On multisite, we want to get the policy consistent across sites
		 *
		 * @return int
		 */

		public function get_active_policy_id() {
			return get_site_option( 'complianz_active_policy_id', 1 );
		}

		/**
		 * Upgrade the activate policy id with one
		 * The active policy id is used to track if the user has consented to the latest policy changes.
		 * If changes were made, the policy is increased, and user should consent again.
		 *
		 * On multisite, we want to get the policy consistent across sites
		 */

		public function upgrade_active_policy_id() {
			$policy_id = get_site_option( 'complianz_active_policy_id', 1 );
			$policy_id++;
			update_site_option( 'complianz_active_policy_id', $policy_id );
		}

		/**

		 * Check if we're in a subfolder setup (home_url consists of domain+path, e.g. domain.com/sub)
		 *
		 * @return string $path //$path should at least contain a '/', for root application.
		 */

		public function get_cookie_path() {
			//if cookies are to be set on the root, don't send a path
			if ( cmplz_get_value( 'set_cookies_on_root' )
			     || function_exists( 'pll__' )
			     || function_exists( 'icl_translate' )
			     || class_exists( 'TRP_Translate_Press', false )
			) {
				return apply_filters( 'cmplz_cookie_path', '/' );
			}

			$domain      = home_url();
			$parse       = parse_url( $domain );
			$root_domain = $parse['host'];
			$path        = str_replace( array( 'http://', 'https://', $root_domain ), '', $domain );

			return apply_filters( 'cmplz_cookie_path', trailingslashit( $path ) );
		}


		/**
		 * The category that is passed to the statistics script determine if these are executed immediately or not.
		 *
		 * @return string
		 **/

		public function get_statistics_category() {
			//if a cookie warning is needed for the stats we don't add a native class, so it will be disabled by the cookie blocker by default
			$category       = 'statistics';
			$uses_tagmanager = cmplz_get_value( 'compile_statistics' ) === 'google-tag-manager' ? true : false;
			$matomo = cmplz_get_value( 'compile_statistics' ) === 'matomo' ? true : false;

			//without tag manager, set as functional if no cookie warning required for stats
			if ( !$uses_tagmanager && ! $this->cookie_warning_required_stats() ) {
				$category = 'functional';
			}

			//tag manager always fires as functional
			if ( $uses_tagmanager ){
				$category = 'functional';
			}

			if ( $matomo && cmplz_get_value('matomo_anonymized')==='yes' ) {
				$category = 'functional';
			}

			/*
			 * Run Tag Manager or gtag by default if consent mode is enabled
			 */
			if ( cmplz_consent_mode() ) {
				$category = 'functional';
			}

			return apply_filters( 'cmplz_statistics_category', $category );
		}

		public function inline_cookie_script() {
			//based on the script classes, the statistics will get added on consent, or without consent
			$category    = $this->get_statistics_category();
			$statistics = cmplz_get_value( 'compile_statistics' );
			$fields     = COMPLIANZ::$config->fields();
			$aw_code    = cmplz_get_value( 'AW_code' );

			$configured_by_complianz = isset( $fields['configuration_by_complianz'] ) && cmplz_get_value( 'configuration_by_complianz' ) !== 'no';
			do_action( 'cmplz_before_statistics_script' );

			/**
			 * Tag manager needs to be included as text/javascript (omitted as it's default), as it always needs to fire.
			 * All other scripts will be included with the appropriate tags, and fired when possible
			 */

			$stats_comment  = '<!-- Statistics script Complianz GDPR/CCPA -->' . "\n";
			if ( $configured_by_complianz ) {
				echo $stats_comment;
				if ( $statistics === 'google-tag-manager' || $statistics === 'matomo-tag-manager' ) {
					?>
					<script data-category="<?php echo esc_attr($category) ?>">
						<?php do_action( 'cmplz_tagmanager_script' ); ?>
					</script><?php
				} else {
					?>
					<script <?php echo $category==='functional' ? '' : 'type="text/plain"' ?> data-category="<?php echo esc_attr($category) ?>"><?php do_action( 'cmplz_statistics_script' ); ?></script><?php
				}

				if ( !empty($aw_code ) ) {
					$script = str_replace( '{AW_code}', $aw_code, cmplz_get_template( "statistics/gtag-remarketing.js" ) );
					//remarketing with consent mode should be executed without consent, as consent mode handles the consent
					if ( cmplz_consent_mode() ) {
						?>
						<script data-category="functional"><?php echo $script; ?></script><?php
					} else {
						?>
						<script type="text/plain" data-category="marketing"><?php echo $script; ?></script><?php
					}
				}
			}

			if ( cmplz_get_value( 'disable_cookie_block' ) == 1 ) {
				return;
			}

			$scripts = get_option("complianz_options_custom-scripts");
			if ( !is_array($scripts) || !isset($scripts['add_script']) || !is_array( $scripts['add_script'] ) ) {
				return;
			}

			$added_scripts =  array_filter( $scripts['add_script'], function($script) {
				return $script['enable'] == 1;
			});

			$added_scripts = apply_filters('cmplz_added_scripts', $added_scripts );
            foreach ( $added_scripts as $script ) {
                echo "<!-- Script Center {$script['category']} script Complianz GDPR/CCPA -->\n";
				$async = $script['async']== 1 ? 'async' : '';
                ?>
                <script <?php echo $async?> type="text/plain" data-category="<?php echo esc_attr($script['category'])?>">
                    <?php echo $script['editor'] ?>
                </script>
                <?php
            }
		}

		/**
		 * Insert the gtag.js script required if gtag.js is used
		 *
		 * @hooked cmplz_before_statistics_script
		 * @since  4.7.8
		 */
		public function add_gtag_js() {
			if ( cmplz_get_value( 'configuration_by_complianz' ) === 'no' ) {
				return;
			}

			$statistics = cmplz_get_value( 'compile_statistics' );
			$gtag_code  = esc_attr( cmplz_get_value( "UA_code" ) );
			if ( $statistics === 'google-analytics' ) {
				$category = $this->get_statistics_category();
				?>
				<script async data-category="<?php echo $category ?>" src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gtag_code ?>"></script><?php
			}
		}

		/**
		 * Add generic clicky js script
		 */

		public function add_clicky_js(){
			$statistics = cmplz_get_value( 'compile_statistics' );
			if ( $statistics === 'clicky' ) {
				$category = $this->get_statistics_category();
				?>
				<script async <?php echo $category==='functional' ? '' : 'type="text/plain"' ?> data-category="<?php echo $category ?>" src="//static.getclicky.com/js"></script>
				<?php
			}
		}

		/**
		 * Inline scripts which do not require a warning
		 */

		public function inline_cookie_script_no_warning() {
			do_action( 'cmplz_before_statistics_script' );
			?>
			<script data-category="functional">
				<?php do_action( 'cmplz_statistics_script' );?>
				<?php do_action( 'cmplz_tagmanager_script' );?>
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
			$script = '';
			$statistics = cmplz_get_value( 'compile_statistics' );
			if ( $statistics === 'google-tag-manager' ) {
				$consent_mode = cmplz_consent_mode() ? '-consent-mode' : '';
				$script = cmplz_get_template( "statistics/google-tag-manager$consent_mode.js" );
				$script = str_replace( '{GTM_code}', esc_attr( cmplz_get_value( "GTM_code" ) ), $script );
			} elseif ( $statistics === 'matomo-tag-manager' ) {
				$script = cmplz_get_template( 'statistics/matomo-tag-manager.js' );
				$script = str_replace( '{container_id}', esc_attr( cmplz_get_value( 'matomo_container_id' ) ), $script );
				$script = str_replace( '{matomo_url}', esc_url_raw( trailingslashit( cmplz_get_value( 'matomo_tag_url' ) ) ), $script );
			}
			echo apply_filters( 'cmplz_script_filter', $script );

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
			$script     = '';
			if ( $statistics === 'google-analytics' ) {
				$consent_mode = cmplz_consent_mode() ? '-consent-mode' : '';
				$code         = esc_attr( cmplz_get_value( "UA_code" ) );
				$anonymize_ip = $this->google_analytics_always_block_ip() ? "'anonymize_ip': true" : "";
				if ( substr( strtoupper($code), 0, 2) === 'G-' ) {
					$anonymize_ip = '';
				}
				$enable_tcf_support = cmplz_tcf_active() ? 'true' : 'false';
				$script       = cmplz_get_template( "statistics/gtag$consent_mode.js" );
				$script       = str_replace( array('{G_code}', '{anonymize_ip}', '{enable_tcf_support}'), array($code, $anonymize_ip, $enable_tcf_support), $script );
			} elseif ( $statistics === 'matomo' ) {
				$cookieless = ( cmplz_get_value( 'matomo_anonymized' ) === 'yes' ) ? '-cookieless' : '';
				$script = cmplz_get_template( "statistics/matomo$cookieless.js" );
				$script = str_replace( '{site_id}', esc_attr( cmplz_get_value( 'matomo_site_id' ) ), $script );
				$script = str_replace( '{matomo_url}', esc_url_raw( trailingslashit( cmplz_get_value( 'matomo_url' ) ) ), $script );
			} elseif ( $statistics === 'clicky' ) {
				$script = cmplz_get_template( 'statistics/clicky.js' );
				$script = str_replace( '{site_ID}', esc_attr( cmplz_get_value( 'clicky_site_id' ) ), $script );
			} elseif ( $statistics === 'yandex' ) {
				$script = cmplz_get_template( 'statistics/yandex.js' );
				$data_layer = cmplz_get_value('yandex_ecommerce') === 'yes';
				$data_layer_str = '';
				if ( $data_layer ) {
					$data_layer_str = 'ecommerce:"dataLayer"';
				}
				$script = str_replace( array('{yandex_id}','{ecommerce}'), array(cmplz_get_value( 'yandex_id' ), $data_layer_str ), $script );
			}
			echo apply_filters( 'cmplz_script_filter', $script );
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
			$admin_url = esc_url_raw( rest_url('complianz/v1/') );
			$nonce 	= wp_create_nonce( 'wp_rest' );
			$javascript = cmplz_get_template( 'test-cookies.js' );
			$javascript = str_replace( array(
				'{admin_url}',
				'{token}',
				'{id}',
				'{nonce}'
			), array(
				esc_url_raw( $admin_url ),
				esc_attr( $token ),
				esc_attr( $id ),
				$nonce
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

		/**
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
			$one_month_ago  = apply_filters( 'cmplz_scan_frequency', strtotime( '-1 month' ) );
			if ( $this->scan_complete()
			     && ( $one_month_ago > $last_scan_date )
			     && ! $this->automatic_cookiescan_disabled()
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

						$stored_social_media = cmplz_scan_detected_social_media();
						if ( ! $stored_social_media ) {
							$stored_social_media = array();
						}
						$social_media = $this->parse_for_social_media( $html );
						$social_media = array_unique( array_merge( $stored_social_media, $social_media ), SORT_REGULAR );
						update_option( 'cmplz_detected_social_media', $social_media );

						$stored_thirdparty_services = cmplz_scan_detected_thirdparty_services();
						if ( ! $stored_thirdparty_services ) {
							$stored_thirdparty_services = array();
						}
						$thirdparty = $this->parse_for_thirdparty_services( $html );
						$thirdparty = array_unique( array_merge( $stored_thirdparty_services, $thirdparty ), SORT_REGULAR );
						update_option( 'cmplz_detected_thirdparty_services', $thirdparty );

						//parse for google analytics and tagmanager, but only if the wizard wasn't completed before.
						//with this data we prefill the settings and give warnings when tracking is doubled
						if ( ! COMPLIANZ::$wizard->wizard_completed_once() ) {
							$this->parse_for_statistics_settings( $html );
						}

						if ( preg_match_all( '/ga\.js/', $html ) > 1
						     || preg_match_all( '/analytics\.js/', $html ) > 1
						     || preg_match_all( '/googletagmanager\.com\/gtm\.js/', $html ) > 1
						     || preg_match_all( '/piwik\.js/', $html ) > 1
						     || preg_match_all( '/matomo\.js/', $html ) > 1
						     || preg_match_all( '/getclicky\.com\/js/', $html ) > 1
						     || preg_match_all( '/mc\.yandex\.ru\/metrika\/watch\.js/', $html ) > 1
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
				echo '<iframe id="cmplz_cookie_scan_frame" class="hidden" src="' . $url . '"></iframe>';
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

			if ( strpos( $html, 'gtm.js' ) !== false || strpos( $html, 'gtm.start' ) !== false
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

			if ( strpos( $html, 'analytics.js' ) !== false || strpos( $html, 'ga.js' ) !== false || strpos( $html, '_getTracker' ) !== false ) {
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
				$pattern = '/\'anonymizeIp|anonymize_ip\'|:[ ]{0,1}true/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches ) {
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
				if ( $matches && isset( $matches[1] ) ) {
					cmplz_update_option( 'wizard', 'matomo_site_id', intval( $matches[1] ) );
					update_option( 'cmplz_detected_stats_data', true );
				}

				cmplz_update_option( 'wizard', 'compile_statistics', 'matomo' );
			}

			if ( strpos( $html, 'static.getclicky.com/js' ) !== false ) {
				update_option( 'cmplz_detected_stats_type', true );

				$pattern = '/clicky_site_ids\.push\(([0-9]{1,3})\)/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[1] ) ) {
					cmplz_update_option( 'wizard', 'clicky_site_id', intval( $matches[1] ) );
					update_option( 'cmplz_detected_stats_data', true );
					cmplz_update_option( 'wizard', 'compile_statistics', 'clicky' );
				}
			}

			if ( strpos( $html, 'mc.yandex.ru/metrika/watch.js' ) !== false ) {
				update_option( 'cmplz_detected_stats_type', true );

				$pattern = '/w.yaCounter([0-9]{1,10}) = new/i';
				preg_match( $pattern, $html, $matches );
				if ( $matches && isset( $matches[1] ) ) {
					cmplz_update_option( 'wizard', 'yandex_id', intval( $matches[1] ) );
					update_option( 'cmplz_detected_stats_data', true );
					cmplz_update_option( 'wizard', 'compile_statistics', 'yandex' );
				}
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

			return apply_filters("cmplz_next_page_url", $url);
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
				unset( $post_types['product'] );
				$post_types = apply_filters('cmplz_cookiescan_post_types',$post_types );

				//from each post type, get one, for faster results.
				$all_types_posts = $all_types_array = array();
				foreach ( $post_types as $post_type ) {
					$args      = array(
							'post_type'      => $post_type,
							'posts_per_page' => 1,
							'meta_query'     => array(
									array(
											'key'     => '_cmplz_scanned_post',
											'compare' => 'NOT EXISTS'
									),
							)
					);
					$new_posts = get_posts( $args );
					$all_types_posts     = array_merge( $all_types_posts, $new_posts );
				}
				$all_types_array = wp_list_pluck($all_types_posts, 'ID');
				$posts = array();
				foreach ( $post_types as $post_type ) {
					$args      = array(
						'post__not_in' 	 => $all_types_array,
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

				$posts     = array_merge( $posts, $all_types_posts );

				if ( count( $posts ) == 0 && ! $this->automatic_cookiescan_disabled() ) {
					/*
                     * If we didn't find any posts, we reset the post meta that tracks if all posts have been scanned.
                     * This way we will find some posts on the next scan attempt
                     * */
					$this->reset_scanned_post_batches();

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

				set_transient( 'cmplz_pages_list', $posts, MONTH_IN_SECONDS );
			}

			return $posts;
		}

		/**
		 * Reset the list of pages
		 *
		 * @param bool $delay
		 * @param bool $manual //if it's manual, we always reset. If automatic scan is disabled, we do not reset.
		 *
		 * @return void
		 *
		 * @since 2.1.5
		 */

		public function reset_pages_list( $delay = false, $manual = false ) {

			if ( ! $manual && $this->automatic_cookiescan_disabled() ) {
				return;
			}

			if ( $manual && $this->automatic_cookiescan_disabled() ) {
				$this->reset_scanned_post_batches();
			}

			if ( $delay ) {
				$current_list    = get_transient( 'cmplz_pages_list' );
				$processed_pages = get_transient( 'cmplz_processed_pages_list' );
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
		 * The scanned post meta is used to create batches of posts. A batch that is being processed is set to scanned.
		 * This is only reset when all posts have been processed, or if user has disabled automatic scanning, and the manual scan is fired.
		 * */

		public function reset_scanned_post_batches() {

			if ( ! function_exists( 'delete_post_meta_by_key' ) ) {
				require_once ABSPATH . WPINC . '/post.php';
			}
			delete_post_meta_by_key( '_cmplz_scanned_post' );
		}

		/**
		 * Check if the automatic scan is disabled
		 *
		 * @return bool
		 */

		public function automatic_cookiescan_disabled() {
			return cmplz_get_value( 'disable_automatic_cookiescan' ) == 1;
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
			$pages_list           = $this->get_pages_list_single_run();
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
				$pages[]    = $id;
				$expiration = $this->automatic_cookiescan_disabled() ? 10 * YEAR_IN_SECONDS : MONTH_IN_SECONDS;
				set_transient( 'cmplz_processed_pages_list', $pages, $expiration );
			}
		}

		public function get_cookies_by_service( $settings = array() ) {
			$cookies = COMPLIANZ::$cookie_admin->get_cookies( $settings );
			$grouped_by_service = array();
			$topServiceID       = 0;
			foreach ( $cookies as $cookie ) {
				$serviceID    = $cookie->serviceID ?: 999999999;
				$topServiceID   = $serviceID > $topServiceID ? $serviceID : $topServiceID;
				$purpose = $cookie->purpose === 0 || strlen( $cookie->purpose ) == 0
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
			$table_exists = wp_cache_get('cmplz_cookie_table_exists', 'complianz');
			if ( !$table_exists ){
				$table_exists = $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}cmplz_cookies'" );
				wp_cache_set('cmplz_cookie_table_exists', $table_exists, 'complianz');
			}
			if ( empty( $table_exists ) ) {
				return array();
			}

			$defaults = array(
					'ignored'           => 'all',
					'new'               => false,
					'language'          => false,
					'isPersonalData'    => 'all',
					'isMembersOnly'     => false,
					'hideEmpty'         => false,
					'showOnPolicy'      => 'all',
					'lastUpdatedDate'   => false,
					'deleted'           => false,
					'isOwnDomainCookie' => 'all',
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

			if ( $settings['isOwnDomainCookie'] !== 'all' ) {
				$sql .= ' AND isOwnDomainCookie = '
						. $this->bool_string( $settings['isOwnDomainCookie'] );
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
				$sql .= $wpdb->prepare( ' AND (lastUpdatedDate < %s OR lastUpdatedDate=FALSE OR lastUpdatedDate=0)',
					intval( $settings['lastUpdatedDate'] ) );
			}

			//stringyfy select args.
			$settings_args = sanitize_title(json_encode($settings));
			$cookies = wp_cache_get('cmplz_cookies_'.$settings_args, 'complianz');
			if ( !$cookies || is_admin() ){
				$cookies = $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookies where " . $sql );
				wp_cache_set('cmplz_cookies_'.$settings_args, $cookies, 'complianz', HOUR_IN_SECONDS);
			}
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
			$result = $wpdb->query( "SHOW TABLES LIKE '{$wpdb->prefix}cmplz_cookies'" );
			if ( empty( $result ) ) {
				return array();
			}

			$defaults = array(
					'language'                      => false,
					'hideEmpty'                     => false,
					'category'                      => 'all',
					'lastUpdatedDate'               => false,
					'includeServicesWithoutCookies' => false,
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

			$no_cookies_where = $sql;

			if ( $settings['lastUpdatedDate'] ) {
				$sql .= $wpdb->prepare( ' AND (lastUpdatedDate < %s OR lastUpdatedDate=FALSE OR lastUpdatedDate = 0 )',
						intval( $settings['lastUpdatedDate'] ) );
			}
			$sql      = "select * from {$wpdb->prefix}cmplz_services where " . $sql;
			$services = $wpdb->get_results( $sql );

			if ( $settings['includeServicesWithoutCookies'] ) {
				$sql = "select * from ( select * from {$wpdb->prefix}cmplz_services where NOT ID in (select DISTINCT services.ID from {$wpdb->prefix}cmplz_services as services inner join {$wpdb->prefix}cmplz_cookies on services.ID = {$wpdb->prefix}cmplz_cookies.serviceID)) as services where $no_cookies_where";
				$services_no_cookies = $wpdb->get_results( $sql );
				$service_ids = wp_list_pluck($services, 'ID');
				foreach ( $services_no_cookies as $service_no_cookies ) {
					if ( !in_array( $service_no_cookies->ID ,$service_ids) ){
						$services[] = $service_no_cookies;
					}
				}
			}

			return $services;
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
				$wpml = apply_filters( 'wpml_active_languages', null,
					array( 'skip_missing' => 0 ) );
				/**
				 * WPML has changed the index from 'language_code' to 'code' so
				 * we check for both.
				 */
				$wpml_test_index = reset( $wpml );
				if ( isset( $wpml_test_index['language_code'] ) ) {
					$wpml = wp_list_pluck( $wpml, 'language_code' );
				} elseif ( isset( $wpml_test_index['code'] ) ) {
					$wpml = wp_list_pluck( $wpml, 'code' );
				} else {
					$wpml = array();
				}
				$languages = array_merge( $wpml, $languages );
			}

			/**
			 * TranslatePress support
			 * There does not seem to be an easy accessible API to get the languages, so we retrieve from the settings directly
			 */

			if ( class_exists( 'TRP_Translate_Press' ) ) {
				$trp_settings = get_option( 'trp_settings', array() );
				if ( isset( $trp_settings['translation-languages'] ) ) {
					$trp_languages = $trp_settings['translation-languages'];
					foreach ( $trp_languages as $language_code ) {
						$key               = substr( $language_code, 0, 2 );
						$languages[ $key ] = $key;
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
				$date = cmplz_sprintf( __( "%s at %s", 'complianz-gdpr' ), $date,
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
		 *
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
		 *
		 * @hooked wp_ajax_load_detected_cookies
		 */

		public function load_detected_cookies() {
			$error   = false;
			$cookies=array();
			if ( ! is_user_logged_in() ) {
				$error = true;
			}


			if ( ! $error ) {
				$args         = array(
						'isTranslationFrom' => false,
				);
				$cookies      = $this->get_cookies( $args );
				$cookies = wp_list_pluck($cookies, 'name');
			}
			$out = array(
				'success' => true,
				'cookies' => $cookies,
			);
			$obj      = new stdClass();
			$obj      = $out;
			echo json_encode( $obj );
			wp_die();
		}

		/**
		 * Get html for list of detected cookies
		 *
		 * @return string
		 */

		public function get_detected_cookies_table() {
			$list_html         = '';
			$args         = array(
				'isTranslationFrom' => false,
			);
			$cookies      = $this->get_cookies( $args );
			if ( ! $cookies && $this->scan_complete() ) {
				$detected = __( "No cookies detected", 'complianz-gdpr' );
			} else {
				$cookie_count = $this->scan_complete() ? count($cookies) : 0;
				$detected = cmplz_sprintf( _n( 'The scan found %s cookie on your domain.', 'The scan found %s cookies on your domain.', $cookie_count, 'complianz-gdpr' ), '<span class="cmplz-scan-count">'.number_format_i18n( $cookie_count ).'</span>' )	;
				$detected .= ' '.__('Continue the wizard to categorize cookies and configure consent.', 'complianz-gdpr');

				/**
				 * Create list
				 */
				$cookies = wp_list_pluck( $cookies, 'name' );
				$list_html    .= '<div class="cmplz-cookies-table">';
				if ( $cookies ) {
					foreach ( $cookies as $name ) {
						$list_html .= '<div>' . $name . '</div>';
					}
				} else {
					$list_html .= '<span>' . __("Nothing found yet.", "complianz-gdpr") . '</span>';
				}
				$list_html .= '</div>';
			}
			return cmplz_panel( $detected,	$list_html, false, false);
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
		 * Get progress of the current scan to output with ajax
		 */

		public function get_scan_progress() {
			$next_url = $this->get_next_page_url();
			$args = array(
				'isTranslationFrom' => false,
			);
			$cookies  = $this->get_cookies( $args );
			$progress = $this->get_progress_count();
			$total = count($cookies);
			$current = intval($progress/100 * $total);
			$cookies = array_slice( $cookies, 0, $current);

			$cookies = wp_list_pluck( $cookies, 'name' );
			$output   = array(
				"progress"  => $progress,
				"next_page" => $next_url,
				'cookies' => $cookies,
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
					$this->maybe_sync_cookies( true );
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
			if ( isset( $_GET['restart'] ) && $_GET['restart'] == 'true' ) {
				$this->resync();
			}
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
				$this->maybe_sync_cookies( true );
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

		/**
 		* Get syn progress
		* @return int
	    */
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
			     && get_option( 'cmplz_sync_services_complete' )
			     && get_option( 'cmplz_sync_cookies_after_services_complete' )
			) {
				//if sync was started after update, stop it now
				update_option( 'cmplz_run_cdb_sync_once', false );
				$progress = 100;
			}

			return $progress;
		}

		/**
		* Get scan progress html
	    */

		public function scan_progress() {
			$disabled = "";
			if ( ! function_exists( 'curl_version' ) ) {
				$disabled = "disabled";
			}
			?>

			<div class="cmplz-field">
				<div class="cmplz-buttons-row-left">
					<input
						<?php echo $disabled ?>
						type="submit"
						class="button cmplz-rescan"
						value="<?php _e( 'Scan', 'complianz-gdpr' ) ?>"
						name="rescan"
					>
					<input
						<?php echo $disabled ?>
						type="submit"
						class="button button-red cmplz-reset"
						onclick="return confirm('<?php _e( 'Are you sure? This will permanently delete the list of cookies.', 'complianz-gdpr' ) ?>');"
						value="<?php _e( 'Clear cookies', 'complianz-gdpr' ) ?>"
						name="clear"
					>
				</div>
				<br>
				<div class="cmplz-label"><label><?php _e( "Cookie scan", "complianz-gdpr" ) ?></label></div>
				<div id="cmplz-scan-progress">
					<div class="cmplz-progress-bar"></div>
				</div>
				<br>
				<div class="detected-cookies">
					<?php echo $this->get_detected_cookies_table(); ?>
				</div>
			</div>
			<?php
		}

		public function sync_progress() {
			$explanation   = '';
			$data_cookies  = $this->get_syncable_cookies();
			$data_services = $this->get_syncable_services();
			$disabled = $this->use_cdb_api() ? '' : 'disabled';
			if ( ! function_exists( 'curl_version' ) ) {
				$disabled    = "disabled";
			}

			if ( $data_cookies['count'] == 0 && $data_services['count'] == 0 ) {
				$disabled    = "disabled";
			}

			$default_language = substr( get_locale(), 0, 2 );
			$languages        = COMPLIANZ::$cookie_admin->get_supported_languages();

				?>

			<div class="field-group sync_progress">
				<div class="cmplz-field">
					<div class="cmplz-buttons-row-left">
						<input type="button" <?php echo $disabled ?>
						       class="button cmplz-resync"
						       value="<?php _e( 'Sync', 'complianz-gdpr' ) ?>"
						       name="resync">

						<?php if ( count( $languages ) > 1 ) { ?>
							<select id="cmplz_language" class="cmplz_cookie_language_selector" data-type="cookie">
								<?php foreach ( $languages as $language ) { ?>
									<option value="<?php echo $language ?>" <?php if ( $default_language === $language )
										echo "selected" ?>>
										<?php echo $this->get_language_descriptor( $language ); ?>
									</option>
								<?php } ?>
							</select>
						<?php } else { ?>
							<input type="hidden" id="cmplz_language" data-type="cookie" value="<?php echo reset( $languages ) ?>">
						<?php } ?>

						<label tabindex="0" role="button" aria-pressed="false" class="cmplz-switch">
							<input name="cmplz_show_deleted" size="40" type="checkbox" value="1"/>
							<span class="cmplz-slider cmplz-round"></span>
						</label>
						<span><?php _e( "Show deleted cookies", "complianz-gdpr" ) ?></span>
					</div>
					<div class="cmplz-label cmplz-sync-status">
						<label class=""><span><?php _e( "Syncing...", 'complianz-gdpr' ) ?></span>&nbsp;</label>
					</div>
					<div id="cmplz-sync-progress">
						<div class="cmplz-sync-progress-bar"></div>
					</div>

				</div>

				<div class="cmplz-help-warning-wrap">
					<?php echo $explanation ?>

					<div id="cmplz-warning cmplz_action_error" class="cmplz-hidden">
						<?php echo cmplz_notice( '<!-- error msg-->', 'warning' ) ?>
					</div>
				</div>
			</div>
			<?php

		}


		/**
		 * @param $language
		 *
		 * @return string
		 */

		private function get_language_descriptor( $language, $type = 'cookie' ) {
			$string = $type == 'cookie' ? __( 'Cookies in %s', 'complianz-gdpr' ) : __( 'Services in %s', 'complianz-gdpr' );
			if ( isset( COMPLIANZ::$config->language_codes[ $language ] ) ) {
				$string = cmplz_sprintf( $string,
					COMPLIANZ::$config->language_codes[ $language ] );
			} else {
				$string = cmplz_sprintf( $string,
					strtoupper( $language ) );
			}

			return $string;
		}


		public function use_cdb_api() {
			$use_api = cmplz_get_value( 'use_cdb_api' ) === 'yes';
			return apply_filters( 'cmplz_use_cdb_api', $use_api );
		}


		/**
		 * Check if site uses Google Analytics
		 *
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
		 *
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
		 *
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
			if ( $region && ! cmplz_has_region( $region ) ) {

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

			$url                  = $_SERVER['REQUEST_URI'];
			$excluded_posts_array = get_option( 'cmplz_excluded_posts_array', array() );
			if ( ! empty( $excluded_posts_array ) ) {
				foreach ( $excluded_posts_array as $excluded_slug ) {
					if ( strpos( $url, $excluded_slug ) !== false ) {
						return false;
					}
				}
			}
			$needs_warning = apply_filters( 'cmplz_site_needs_cookiewarning', $needs_warning );
			return $needs_warning;
		}

		/**
		 * Check if the site needs a cookie banner considering statistics only
		 *
		 * @param $region bool|string
		 *
		 * @return bool
		 * @since 1.0
		 *
		 */

		public function cookie_warning_required_stats( $region = false ) {

			if ( cmplz_get_value('consent_for_anonymous_stats')==='yes' ) {
				return apply_filters( 'cmplz_cookie_warning_required_stats', true );
			}

			if ( $region ) {
				if ( COMPLIANZ::$config->regions[$region]['statistics_consent'] === 'no' ) {
					return apply_filters( 'cmplz_cookie_warning_required_stats', false );
				}

				if ( COMPLIANZ::$config->regions[$region]['statistics_consent'] === 'always' ) {
					return apply_filters( 'cmplz_cookie_warning_required_stats', true );
				}

				if ( COMPLIANZ::$config->regions[$region]['statistics_consent'] === 'when_not_anonymous' ) {
					if ( cmplz_get_value( 'eu_consent_regions' ) === 'yes') {
						return apply_filters( 'cmplz_cookie_warning_required_stats', true );
					} elseif ( $this->statistics_privacy_friendly() ) {
						return apply_filters( 'cmplz_cookie_warning_required_stats', false );
					} else {
						return apply_filters( 'cmplz_cookie_warning_required_stats', true );
					}
				}

				return apply_filters( 'cmplz_cookie_warning_required_stats', false );
			}

			/**
			 * if region is not provided. Generic check
			 */

			if ( $this->statistics_privacy_friendly() && $this->consent_required_for_anonymous_stats() ) {
				return apply_filters( 'cmplz_cookie_warning_required_stats', true );
			}

			//if we're here, we don't need stats if they're set up privacy friendly
			return apply_filters( 'cmplz_cookie_warning_required_stats', ! $this->statistics_privacy_friendly() );
		}

		/**
		 * Check if consent is required for anonymous statistics
		 *
		 * @return bool
		 */

		public function consent_required_for_anonymous_stats() {
			$active_regions = COMPLIANZ::$config->active_regions();
			if ( array_search('always', array_column( $active_regions, 'statistics_consent') ) ) {
				return true;
			}

			$when_not_anonymous = array_search('when_not_anonymous', array_column( $active_regions, 'statistics_consent') );
			$uses_google = $this->uses_google_analytics() || $this->uses_google_tagmanager();
			if ( $when_not_anonymous && $uses_google && cmplz_get_value( 'consent_for_anonymous_stats' ) === 'yes'  ) {
				return true;
			}

			return false;
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
			$statistics = cmplz_get_value( 'compile_statistics' );

			//no statistics at all, it's privacy friendly
			if ( $statistics === 'no' ) {
				return apply_filters('cmplz_statistics_privacy_friendly', true);
			}

			//not anonymous stats.
			if ( $statistics === 'yes' ) {
				return apply_filters('cmplz_statistics_privacy_friendly', false);
			}

			$tagmanager                                = $statistics === 'google-tag-manager';
			$matomo                                    = $statistics === 'matomo';
			$google_analytics                          = $statistics === 'google-analytics';
			$clicky                          		   = $statistics === 'clicky';
			$accepted_google_data_processing_agreement = false;
			$ip_anonymous                              = false;
			$no_sharing                                = false;

			if ( $clicky ) {
				return apply_filters('cmplz_statistics_privacy_friendly', false);
			}

			if ( $matomo ) {
				return apply_filters('cmplz_statistics_privacy_friendly', false);
			}

			if ( $google_analytics || $tagmanager ) {
				$thirdparty = $google_analytics ? cmplz_get_value( 'compile_statistics_more_info' ) : cmplz_get_value( 'compile_statistics_more_info_tag_manager' );
				$accepted_google_data_processing_agreement = ( isset( $thirdparty['accepted'] ) && ( $thirdparty['accepted'] == 1 ) ) ? true : false;
				$ip_anonymous = ( isset( $thirdparty['ip-addresses-blocked'] ) && ( $thirdparty['ip-addresses-blocked'] == 1 ) ) ? true : false;
				$no_sharing = ( isset( $thirdparty['no-sharing'] ) && ( $thirdparty['no-sharing'] == 1 ) ) ? true : false;
			}

			if ( ( $tagmanager || $google_analytics )
			     && ( ! $accepted_google_data_processing_agreement
			          || ! $ip_anonymous
			          || ! $no_sharing )
			) {
				return apply_filters('cmplz_statistics_privacy_friendly', false);
			}



			//everything set up privacy friendly!
			return apply_filters('cmplz_statistics_privacy_friendly', true);
		}


		/**
		 * Check if ip is always blocked
		 * @return bool
		 */
		public function google_analytics_always_block_ip() {
			$statistics       = cmplz_get_value( 'compile_statistics' );
			$google_analytics = $statistics === 'google-analytics';

			if ( $google_analytics ) {
				$thirdparty = cmplz_get_value( 'compile_statistics_more_info' );
				$always_block_ip = isset( $thirdparty['ip-addresses-blocked'] ) && ( $thirdparty['ip-addresses-blocked'] == 1 );
				if ( $always_block_ip ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check if this website shares data with third parties, used for recommendations, cookiebanner check and canada policies
		 *
		 * @return bool
		 */

		public function site_shares_data() {
			//TCF always shares data
			if ( cmplz_tcf_active() ) {
				return true;
			}

			if ( $this->uses_google_tagmanager() ) {
				return true;
			}

			if ( cmplz_uses_marketing_cookies() ) {
				return true;
			}

			/**
			 * Script Center
			 */
			$blocked_scripts = get_transient('cmplz_blocked_scripts');
			$blocked_scripts = $blocked_scripts ?: COMPLIANZ::$cookie_blocker->blocked_scripts();
			$thirdparty_scripts = is_array($blocked_scripts) && count( $blocked_scripts ) > 0;
			$ad_cookies   = ( cmplz_get_value( 'uses_ad_cookies' ) === 'yes' ) ? true : false;
			$social_media = ( cmplz_get_value( 'uses_social_media' ) === 'yes' ) ? true : false;
			$thirdparty_services = ( cmplz_get_value( 'uses_thirdparty_services' ) === 'yes' ) ? true : false;
			if (
					$thirdparty_scripts
				 || $ad_cookies
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

			if ( !$this->statistics_privacy_friendly() ) {
				return true;
			}

			$cookies = $this->get_cookies( $args );
			if ( empty( $cookies ) ) {
				return false;
			}

			foreach ( $cookies as $cookie ) {
				$service = new CMPLZ_SERVICE( $cookie->serviceID );
				if ( $service->secondParty || $service->thirdParty ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check if non functional cookies are used on this site
		 * @return bool
		 */
		public function uses_non_functional_cookies() {
			if ( $this->uses_google_tagmanager() ) {
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
				if ( strpos( strtolower( $cookie->purpose ), 'functional' ) === false ) {
					return true;
				}
			}

			return false;
		}


		public function uses_only_functional_cookies() {
			return ! $this->uses_non_functional_cookies();
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
	}

} //class closure
