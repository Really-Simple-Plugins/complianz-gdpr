<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_sync" ) ) {
	class cmplz_sync {
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
			add_filter( 'cmplz_do_action', array( $this, 'get_sync_data' ), 10, 3 );
			add_filter( 'cmplz_do_action', array( $this, 'update_cookies_services' ), 10, 3 );
			add_action( 'plugins_loaded', array( $this, 'do_sync_batch_rest' ), 20 );
			add_action( 'cmplz_every_five_minutes_hook', array( $this, 'do_sync_batch' ) );
		}

		static function this() {
			return self::$_this;
		}

		public function do_sync_batch_rest(){
			if ( !cmplz_is_logged_in_rest() ) {
				return;
			}
			$this->do_sync_batch();
		}

		/**
		 * Runs each complianz page rest api request to check if any new languages were added in the meantime.
		 *
		 * @hooked admin_init
		 */

		public function ensure_cookies_in_all_languages(): void {
			if ( ! cmplz_is_logged_in_rest() ) {
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
			$languages      = COMPLIANZ::$banner_loader->get_supported_languages();
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
			if ( ! wp_doing_cron() && ! cmplz_user_can_manage() ) {
				return 'No permissions';
			}
			$msg   = '';
			$error = false;
			$data  = $this->get_syncable_cookies();
			if ( ! COMPLIANZ::$banner_loader->use_cdb_api() ) {
				$error = true;
				$msg   = __( 'You haven\'t accepted the usage of the cookiedatabase.org API. To automatically complete your cookie descriptions, please choose yes.', 'complianz-gdpr' );
			}

			//if no syncable cookies are found, exit.
			if ( $data['count'] == 0 ) {
				update_option( 'cmplz_sync_cookies_complete', true, false );
				$msg   = "";
				$error = true;
			}

			unset( $data['count'] );

			if ( get_transient( 'cmplz_cookiedatabase_request_active' ) ) {
				$error = true;
				$msg = __( "A request is already running. Please be patient until the current request finishes", "complianz-gdpr" );
			}

			if ( ! $error ) {
				set_transient( 'cmplz_cookiedatabase_request_active', true, MINUTE_IN_SECONDS );
				//add the plugins list to the data
				$plugins         = get_option( 'active_plugins' );
				$data['plugins'] = "<pre>" . implode( "<br>", $plugins ) . "</pre>";
				$data['website'] = '<a href="' . esc_url_raw( site_url() ) . '">' . esc_url_raw( site_url() ) . '</a>';
				$data            = apply_filters( 'cmplz_api_data', $data );
				$json            = json_encode( $data );
				$endpoint        = trailingslashit( CMPLZ_COOKIEDATABASE_URL ) . 'v2/cookies/';
				$ch = curl_init();

				$ssl_verification = apply_filters('cmplz_ssl_verify', get_site_option('cmplz_ssl_verify', 'true' )==='true' );
				if ( !$ssl_verification ) {
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
				}

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
				if (curl_errno($ch)) {
					$error_msg = curl_error($ch);
					update_option('cmplz_curl_error', $error_msg, false );
				}

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
				update_option( 'cmplz_sync_cookies_after_services_complete', true, false );
			} else {
				update_option( 'cmplz_sync_cookies_complete', true, false );
			}

			return $msg;
		}

		/**
		 * Get list of services to be synced
		 *
		 * @return array
		 */
		public function get_syncable_services() {
			$languages = COMPLIANZ::$banner_loader->get_supported_languages();
			$data      = array();
			$count_all    = 0;
			$scan_interval = apply_filters('cmplz_sync_interval', 3);
			$one_week_ago = strtotime( "-".$scan_interval." month" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language, 'includeServicesWithoutCookies' => true );
				if ( ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_MONTH_CHECK' )
				) {
					$args['lastUpdatedDate'] = $one_week_ago;
				}
				$services = COMPLIANZ::$banner_loader->get_services( $args );
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
			$languages            = COMPLIANZ::$banner_loader->get_supported_languages();
			$data                 = array();
			$index                = array();
			$thirdparty_cookies   = array();
			$localstorage_cookies = array();
			$count_all            = 0;
			$ownDomainCookies = COMPLIANZ::$banner_loader->get_cookies(array('isOwnDomainCookie'=>true));
			$hasOwnDomainCookies = count($ownDomainCookies) >0;
			$scan_interval = apply_filters('cmplz_sync_interval', 3);
			$one_week_ago = strtotime( "-".$scan_interval." month" );
			foreach ( $languages as $language ) {
				$args = array( 'sync' => true, 'language' => $language );
				if ( ! $ignore_time_limit && ! wp_doing_cron()
				     && ! defined( 'CMPLZ_SKIP_MONTH_CHECK' )
				) {
					$args['lastUpdatedDate'] = $one_week_ago;
				}
				$cookies   = COMPLIANZ::$banner_loader->get_cookies( $args );
				$index[$language]     = 0;
				foreach ( $cookies as $c_index => $cookie ) {
					$c    = new CMPLZ_COOKIE( $cookie->name, $language, $cookie->service );
					$slug = $c->slug ?: $index[$language];
					//pass the type to the CDB
					if ( $c->type === 'localstorage' ) {
						if (!in_array($cookie->name, $localstorage_cookies) ) $localstorage_cookies[] = $cookie->name;
					}
					//need to pass a service here.
					if ( !empty( $c->service ) ) {
						$service = new CMPLZ_SERVICE( $c->service );

						//deprecated as of 5.3. Use only if no own domain cookie property has ever been saved
						if ( !$hasOwnDomainCookies ) {
							if ( $service->thirdParty || $service->secondParty ) {
								if (! in_array( $cookie->name, $thirdparty_cookies, true ) ) $thirdparty_cookies[] = $cookie->name;
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

					//use as of 5.3. Each non own domain cookie is added to the "thirdparty" list, which is synced only with non own domain cookies.
					if ( $hasOwnDomainCookies ) {
						if ( !$c->isOwnDomainCookie ) {
							if (! in_array( $cookie, $thirdparty_cookies, true ) ) $thirdparty_cookies[] = $cookie->name;
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
		 * Sync all services
		 */

		public function maybe_sync_services() {
			if ( ! wp_doing_cron() && ! cmplz_user_can_manage() ) {
				return;
			}
			/**
			 * get cookies by service name
			 */
			$msg   = '';
			$error = false;
			$data  = $this->get_syncable_services();
			if ( ! COMPLIANZ::$banner_loader->use_cdb_api() ) {
				$error = true;
				$msg   = __( 'You haven\'t accepted the usage of the cookiedatabase.org API. To automatically complete your cookie descriptions, please choose yes.', 'complianz-gdpr' );
			}

			//if no syncable services found, exit.
			if ( $data['count'] == 0 ) {
				update_option( 'cmplz_sync_services_complete', true, false );
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
				$data     = apply_filters( 'cmplz_api_data', $data );
				$json     = json_encode( $data );
				$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL )
				            . 'v1/services/';

				$ch = curl_init();

				$ssl_verification = apply_filters('cmplz_ssl_verify', get_site_option('cmplz_ssl_verify', 'true' )==='true' );
				if ( !$ssl_verification ) {
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
				}
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
						if ( $service->thirdParty || $service->secondParty ) {
							$cookies = property_exists( $service_and_cookies, 'cookies' ) ? $service_and_cookies->cookies : false;
							if ( ! is_array( $cookies ) ) {
								continue;
							}

							foreach ( $cookies as $cookie_name ) {
								$cookie = new CMPLZ_COOKIE( $cookie_name, 'en', $service->name );
								$cookie->add( $cookie_name, COMPLIANZ::$banner_loader->get_supported_languages(), false, $service->name );
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
			if ( ! cmplz_user_can_manage() ) {
				return [];
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
						COMPLIANZ::$banner_loader->get_supported_languages(), false,
					$type );
				$services[] = $service;
			}

			return $services;
		}

		/**
		 * Keep services in sync with selected answers in wizard
		 *
		 *
		 **/

		public function update_services() {
			$social_media = ( cmplz_get_option( 'uses_social_media' ) === 'yes' )
				? true : false;
			if ( $social_media ) {
				$social_media_types = cmplz_get_option( 'socialmedia_on_site' );
				foreach ( $social_media_types as $slug => $active ) {
					if ( $active == 1 ) {
						$service = new CMPLZ_SERVICE();
						//add for all languages
						$service_name = COMPLIANZ::$config->thirdparty_socialmedia[ $slug ];
						$service->add( $service_name, COMPLIANZ::$banner_loader->get_supported_languages(), false, 'social' );
					} else {
						$service = new CMPLZ_SERVICE( $slug );
						$service->delete();
					}
				}
			}

			$thirdparty = ( cmplz_get_option( 'uses_thirdparty_services' ) === 'yes' ) ? true : false;
			if ( $thirdparty ) {
				$thirdparty_types = cmplz_get_option( 'thirdparty_services_on_site' );
				foreach ( $thirdparty_types as $slug => $active ) {
					if ( $active == 1 ) {
						$service = new CMPLZ_SERVICE();
						//add for all languages
						$service_name  = COMPLIANZ::$config->thirdparty_services[ $slug ];
						$service->add( $service_name, COMPLIANZ::$banner_loader->get_supported_languages(), false, 'service' );
					} else {
						$service = new CMPLZ_SERVICE( $slug );
						$service->delete();
					}
				}
			}
		}



		/**
		 * Update a cookie from the REACT application
		 * @param array           $data
		 * @param string          $action
		 * @param WP_REST_Request $request
		 *
		 * @return array
		 */
		public function update_cookies_services( array $data, string $action, WP_REST_Request $request): array {
			if (!cmplz_user_can_manage()) {
				return [];
			}
			if ( $action === 'add_cookie' ) {
				$service = sanitize_text_field($request->get_param( 'service' ) );
				$name = sanitize_text_field($request->get_param( 'cookieName' ) );
				$name = __("New cookie", "complianz-gdpr").' '.$name ;
				$cookie = new CMPLZ_COOKIE($name, 'en', $service);
				$cookie->type   = 'cookie';
				$cookie->domain = 'self';
				$languages = COMPLIANZ::$banner_loader->get_supported_languages();
				$cookie->add($name, $languages, false, $service, false);
				$new_ids = $cookie->get_translations();
				$new_cookies = [];
				foreach ($new_ids as $id) {
					$new_cookies[] = new CMPLZ_COOKIE($id);
				}
				$data = [
					'cookies' => $new_cookies,
				];
			} else if ( $action === 'update_cookie' ) {
				$data = [];
				$cookie_item = $request->get_param( 'cookie' );
				$id          = $cookie_item['ID'] ?? false;
				$id          = (int) $id;
				if ( $id > 0 ) {
					$cookie = new CMPLZ_COOKIE( $id );
					if ( isset( $cookie_item['name'] ) ) {
						$cookie->name = sanitize_text_field( $cookie_item['name'] );
					}
					if ( isset( $cookie_item['retention'] ) ) {
						$cookie->retention = sanitize_text_field( $cookie_item['retention'] );
					}
					if ( isset( $cookie_item['serviceID'] ) ) {
						$cookie->serviceID = (int) $cookie_item['serviceID'];
					}
					if ( isset( $cookie_item['cookieFunction'] ) ) {
						$cookie->cookieFunction = sanitize_text_field( $cookie_item['cookieFunction'] );
					}
					if ( isset( $cookie_item['purpose'] ) ) {
						$cookie->purpose = sanitize_text_field( $cookie_item['purpose'] );
					}

					if ( isset( $cookie_item['collectedPersonalData'] ) ) {
						$cookie->collectedPersonalData = sanitize_text_field( $cookie_item['collectedPersonalData'] );
					}
					if ( isset( $cookie_item['sync'] ) ) {
						$cookie->sync = (int) $cookie_item['sync'];
					}
					if ( isset( $cookie_item['showOnPolicy'] ) ) {
						$cookie->showOnPolicy = (int) $cookie_item['showOnPolicy'];
					}
					$cookie->save(true);
					//update in all languages, then return all cookies to ensure they're all updated
					$new_ids = $cookie->get_translations();
					$new_cookies = [];
					foreach ($new_ids as $id) {
						$new_cookies[] = new CMPLZ_COOKIE($id);
					}
					$data = [
						'cookies' => $new_cookies,
					];
				}

			}

			if ( $action === 'update_service' ) {
				$data = [];
				$service_item     = $request->get_param( 'service' );
				$id = $service_item['ID'] ?? false;
				$id = (int) $id;
				if ( $id!==0 ) {
					if ( $id<0 ) {
						$id = sanitize_text_field( $service_item['name'] );
					}
					$service = new CMPLZ_SERVICE($id);
					if (isset($service_item['name'])) {
						$service->name = sanitize_text_field($service_item['name']);
					}
					if (isset($service_item['serviceType'])) {
						$service->serviceType = sanitize_text_field($service_item['serviceType']);
					}
					if (isset($service_item['sharesData'])) {
						$service->sharesData = (int) $service_item['sharesData'];
					}
					if (isset($service_item['privacyStatementURL'])) {
						$service->privacyStatementURL = sanitize_text_field($service_item['privacyStatementURL']);
					}
					if (isset($service_item['sync'])) {
						$service->sync = (int) $service_item['sync'];
					}
					$service->save();
					$data = ['ID'=> $service->ID];
				}

			}

			if ( $action === 'delete_cookie' ) {
				$id = (int) $request->get_param('id');
				$cookie = new CMPLZ_COOKIE($id);
				$cookie->delete();
				$data = [];
			}
			if ( $action === 'delete_service' ) {
				$id = (int) $request->get_param('id');
				$service = new CMPLZ_SERVICE($id);

				$service->delete();
				$data = [];
			}
			return $data;
		}

		/**
		 * Start a new sync
		 *
		 * @param bool $force
		 */

		public function resync() {
			update_option( 'cmplz_sync_cookies_complete', false, false );
			update_option( 'cmplz_sync_cookies_after_services_complete', false, false );
			update_option( 'cmplz_sync_services_complete', false, false );
		}

		/**
		 * Check if there's a cookie which is not filled out entirely
		 *
		 * @return bool
		 */

		public function has_empty_cookie_descriptions() {

			$cookies
				= COMPLIANZ::$banner_loader->get_cookies( array(
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
		 * Reset the cookies changed value
		 */

		public function reset_cookies_changed() {
			update_option( 'cmplz_cookie_data_verified_date', time() );
			delete_transient( 'cmplz_cookie_settings_cache' );
			delete_option( 'cmplz_changed_cookies' );
		}

		/**
		 * Run or reset a sync
		 *
		 * @param array           $data
		 * @param string          $action
		 * @param WP_REST_Request $request
		 *
		 * @return array
		 */

		public function get_sync_data( array $data, string $action, WP_REST_Request $request) {
			if ( $action === 'sync' ) {
				$this->reset_cookies_changed();
				$this->ensure_cookies_in_all_languages();
				$scan_action = sanitize_title($request->get_param('scan_action'));
				$language = cmplz_sanitize_language($request->get_param('language'));
				if ( $scan_action === 'restart') {
					$this->resync();
				}

				$msg = $this->do_sync_batch(true);
				$data_cookies = $this->get_syncable_cookies();
				$data_services = $this->get_syncable_services();
				$has_syncable_data = (count($data_cookies) + count($data_services))>0;
				$languages = COMPLIANZ::$banner_loader->get_supported_languages();
				$settings = array(
						'isMembersOnly' => 'all',
						'language' => $language,
						'deleted' => 'all',
				);
				$cookie_objects  = COMPLIANZ::$banner_loader->get_cookies( $settings );
				$services = COMPLIANZ::$banner_loader->get_services(['language' => $language]);
				$service_objects = [];
				foreach ($services as $service) {
					$service_objects[] = new CMPLZ_SERVICE($service->ID);
				}

				//change into array
				$data = [
					"cookies"           => $cookie_objects,
					"services"          => $service_objects,
					"has_syncable_data" => $has_syncable_data,
					"message"           => $msg,
					"progress"          => $this->get_sync_progress(),
					"curl_exists"       => function_exists( 'curl_version' ),
					"default_language"  => substr( get_locale(), 0, 2 ),
					"languages"         => $languages,
					"purposes_options"  => $this->get_cookiePurpose_options(),
					"serviceType_options"  => $this->get_serviceTypes_options(),
				];
			}
			return $data;
		}

		/**
		 * Runs on cron, on complianz page, or on rest request
		 */

		public function do_sync_batch($request_from_sync = false){
			//we leave rest requests to the react app to handle.
			$is_complianz_page = isset($_GET['page']) && $_GET['page'] === 'complianz';
			if ( !$is_complianz_page && !wp_doing_cron() && !cmplz_is_logged_in_rest()  ) {
				return '';
			}

			if ( defined('CMPLZ_DISABLE_SYNC') && CMPLZ_DISABLE_SYNC ) {
				return '';
			}

			//we only want to start the sync if the sync has been started from the react app at least once.
			if ( !$request_from_sync && !get_option('cmplz_first_sync_started')) {
				return '';
			}

			if ($request_from_sync){
				update_option('cmplz_first_sync_started', true, false);
			}

			if ( !$request_from_sync && cmplz_is_logged_in_rest() ){
				return '';
			}

			$msg      = "";
			$progress = $this->get_sync_progress();

			if ( $progress===100 ) {
				return "";
			}
			if ( 0 < $progress && $progress < 20) {
				$this->get_cookiePurpose_options();
			}
			if ( 20 <= $progress && $progress < 40 ) {
				$this->get_serviceTypes_options();
			}
			if ( 40 <= $progress && $progress < 60 ) {
				$msg = $this->maybe_sync_cookies();
			}

			if ( 60 <= $progress && $progress < 80 ) {
				$msg = $this->maybe_sync_services();
			}

			//after adding the cookies, do one more cookies sync
			if ( 80 <= $progress && $progress < 100 ) {
				$this->maybe_sync_cookies( true );
				COMPLIANZ::$scan->clear_double_cookienames();
			}
			cmplz_delete_transient('cmplz_cookie_shredder_list');
			return $msg;
		}

		/**
		 * Get syn progress
		 * @return int
		 */
		public function get_sync_progress(): int {
			if ( defined('CMPLZ_DISABLE_SYNC') && CMPLZ_DISABLE_SYNC ) {
				return 100;
			}

			$progress = 5;
			if ( get_option( 'cmplz_purposes_stored' ) ) {
				$progress = 20;
			}

			if ( get_option( 'cmplz_servicetypes_stored' ) ) {
				$progress = 40;
			}

			if ( get_option( 'cmplz_sync_cookies_complete' ) ) {
				$progress = 60;
			}

			if ( get_option( 'cmplz_sync_cookies_complete' )
			     && get_option( 'cmplz_sync_services_complete' )
			) {
				$progress = 80;
			}

			if ( get_option( 'cmplz_sync_cookies_complete' )
			     && get_option( 'cmplz_sync_services_complete' )
			     && get_option( 'cmplz_sync_cookies_after_services_complete' )
			) {
				$progress = 100;
			}

			return $progress;
		}

		/**
		 * create select html for service type
		 *
		 * @return array
		 */

		public function get_serviceTypes_options( ) {
			if ( ! cmplz_user_can_manage() ) {
				return [];
			}
			$languages = COMPLIANZ::$banner_loader->get_supported_languages();
			$out = [];
			foreach ($languages as $language ) {
				$serviceTypes = get_option( 'cmplz_serviceTypes_' . $language );
				if (isset($_GET['cmplz_nocache'])) {
					$serviceTypes = false;
				}
				if ( ! $serviceTypes ) {
					$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL ) . 'v1/servicetypes/' . $language;
					$response = wp_remote_get( $endpoint );
					$status   = wp_remote_retrieve_response_code( $response );
					$body     = wp_remote_retrieve_body( $response );
					if ( $status == 200 ) {
						$body         = json_decode( $body );
						$serviceTypes = $body->data;
						if ( $language === 'en' ) {
							foreach ( $serviceTypes as $serviceType ) {
								if ( empty( $serviceType ) ) {
									continue;
								}
								cmplz_register_translation( $serviceType, $serviceType );
							}
						}
						$s = $serviceTypes;
						$serviceTypes = [];
						foreach ( $s as $id => $serviceType ) {
							$serviceTypes[] = ['value' => $id, 'label'=>$serviceType];
						}
						update_option( "cmplz_serviceTypes_stored", true, false );
						update_option( 'cmplz_serviceTypes_' . $language, $serviceTypes, false );
					}
				}
				//unescape label
				foreach ($serviceTypes as $index => $serviceType){
					$serviceTypes[$index]['label'] = html_entity_decode($serviceType['label']);
				}
				$out[$language] = $serviceTypes;
			}

			return $out;
		}


		/**
		 * create select html for purposes
		 *
		 * @param string $selected_value
		 * @param string $language
		 *
		 * @return array
		 */

		public function get_cookiePurpose_options() {
			if ( ! cmplz_user_can_manage() ) {
				return [];
			}

			$languages = COMPLIANZ::$banner_loader->get_supported_languages();
			$out = [];
			foreach ($languages as $language ) {
				$cookiePurposes = get_option( "cmplz_purposes_$language" );
				if ( isset($_GET['cmplz_nocache']) ) {
					$cookiePurposes = false;
				}

				if ( !$cookiePurposes ) {
					$endpoint = trailingslashit( CMPLZ_COOKIEDATABASE_URL ) . 'v1/cookiepurposes/' . $language;
					$response = wp_remote_get( $endpoint );
					$status   = wp_remote_retrieve_response_code( $response );
					$body     = wp_remote_retrieve_body( $response );
					if ( $status === 200 ) {
						$body           = json_decode( $body );
						$cookiePurposes = $body->data;
						if ( $language === 'en' ) {
							foreach ( $cookiePurposes as $cookiePurpose ) {
								if ( empty( $cookiePurpose ) ) {
									continue;
								}
								cmplz_register_translation( $cookiePurpose, $cookiePurpose );
							}
						}
						//convert to react compatible array
						$c = $cookiePurposes;
						$cookiePurposes = [];
						foreach ($c as $id => $cookiePurpose) {
							$cookiePurposes[] = ['value' => $id, 'label'=>$cookiePurpose];
						}
						update_option( "cmplz_purposes_stored", true, false );
						update_option( "cmplz_purposes_$language", $cookiePurposes, false );
					}
				}
				//unescape label
				foreach ($cookiePurposes as $index => $cookiePurpose){
					$cookiePurpose[$index]['label'] = html_entity_decode($cookiePurpose['label']);
				}
				$out[$language] = $cookiePurposes;
			}
			return $out;
		}

	}

}
