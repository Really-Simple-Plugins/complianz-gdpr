<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_config" ) ) {

	class cmplz_config {
		private static $_this;
		public $fields = array();
		public $steps = array();
		public $formal_languages = array();
		public $generic_documents_list;

		public $supported_regions;

		public $thirdparty_services
			= array(
				'activecampaign'   => 'Active Campaign',
				'google-fonts'     => 'Google Fonts',
				'google-recaptcha' => 'Google reCAPTCHA',
				"google-maps"      => 'Google Maps',
				"openstreetmaps"   => 'OpenStreetMaps',
				"vimeo"            => 'Vimeo',
				"youtube"          => 'YouTube',
				"videopress"       => 'VideoPress',
				"dailymotion"      => 'Dailymotion',
				"soundcloud"       => 'SoundCloud',
				"twitch"           => 'Twitch',
				"paypal"           => 'PayPal',
				"spotify"          => 'Spotify',
				"hotjar"           => 'Hotjar',
				"addthis"          => 'AddThis',
				"addtoany"         => 'AddToAny',
				"sharethis"        => 'ShareThis',
				"livechat"         => 'LiveChat',
				"hubspot"          => 'HubSpot',
				"calendly"         => 'Calendly',
			);

		public $thirdparty_socialmedia
			= array(
				'facebook'  => 'Facebook',
				'twitter'   => 'Twitter',
				'linkedin'  => 'LinkedIn',
				'whatsapp'  => 'WhatsApp',
				'instagram' => 'Instagram',
				'tiktok' 	=> 'TikTok',
				'disqus'    => 'Disqus',
				'pinterest' => 'Pinterest',
			);



		/**
		 * The services for which a placeholder exists in the assets/images/placeholders folder.
		 * @var array
		 */
		public $placeholders;

		/**
		 * This is used in the scan function to tell the user he/she uses social media
		 * Also in the function to determine a media type for the placeholders
		 * Based on this the cookie warning is enabled.
		 *
		 * */

		public $social_media_markers
			= array(
				"linkedin"  => array(
					"platform.linkedin.com",
					'addthis_widget.js',
					'linkedin.com/embed/feed'
				),
				"twitter"   => array(
					'super-socializer',
					'sumoSiteId',
					'addthis_widget.js',
					"platform.twitter.com",
					'twitter-widgets.js'
				),
				"facebook"  => array(
					'fbq',
					'super-socializer',
					'sumoSiteId',
					'addthis_widget.js',
					"fb-root",
					"<!-- Facebook Pixel Code -->",
					'connect.facebook.net',
					'www.facebook.com/plugins',
					'pixel-caffeine',
					'facebook.com/plugins',
				),
				"pinterest" => array(
					'super-socializer',
					'assets.pinterest.com'
				),
				"disqus"    => array( 'disqus.com' ),
				"tiktok"    => array( 'tiktok.com' ),
				"instagram" => array(
					'instawidget.net/js/instawidget.js',
					'instagram.com',
				),
			);

		/**
		 * Scripts with this string in the content get listed in the third party list.
		 * Also used in cmplz_placeholder()
		 * */

		public $thirdparty_service_markers
			= array(
				"google-maps"      => array(
					'apis.google.com/js/platform.js',
					'new google.maps.',
					'google.com/maps',
					'maps.google.com',
					'wp-google-maps',
					'new google.maps.InfoWindow',
					'new google.maps.Marker',
					'new google.maps.Map',
					'var mapOptions',
					'var map',
					'var Latlng',
				),
				"soundcloud"       => array( 'w.soundcloud.com/player' ),
				"openstreetmaps"   => array(
					'openstreetmap.org',
					'osm/js/osm'
				),
				"vimeo"            => array(
					'player.vimeo.com',
					'i.vimeocdn.com',
				),
				"google-recaptcha" => array(
					'google.com/recaptcha',
					'grecaptcha',
					'recaptcha.js',
					'recaptcha/api'
				),
				"youtube"          => array(
					'youtube.com',
					'youtube-nocookie.com',
					'youtu.be',
					'yotuwp',
				),
				"videopress"       => array(
					'videopress.com/embed',
					'videopress.com/videopress-iframe.js'
				),
				"dailymotion"      => array( 'dailymotion.com/embed/video/' ),
				"hotjar"           => array( 'static.hotjar.com' ),
				"spotify"          => array( 'open.spotify.com/embed' ),
				"google-fonts"     => array( 'fonts.googleapis.com' ),
				"paypal"           => array(
					'www.paypal.com/tagmanager/pptm.js',
					'www.paypalobjects.com/api/checkout.js'
				),
				"disqus"           => array( 'disqus.com' ),
				"addthis"          => array( 'addthis.com' ),
				"addtoany"          => array( 'addtoany.min.js', 'window.a2a_config' ),
				"sharethis"        => array( 'sharethis.com' ),
				"livechat"         => array( 'cdn.livechatinc.com/tracking.js' ),
				"hubspot"         => array( 'js.hs-scripts.com/', 'hbspt.forms.create', 'js.hsforms.net','track.hubspot.com','js.hs-analytics.net'),
				"calendly"         => array( 'assets.calendly.com' ),
				"twitch"          => array( 'twitch.tv', 'player.twitch.tv'),
			);

		public $stats
			= array(
				'google-analytics'   => 'Google Analytics',
				'google-tag-manager' => 'Tag Manager',
				'matomo'             => 'Matomo',
				'clicky'             => 'Clicky',
				'yandex'             => 'Yandex',
			);
		public $stats_markers = array(
				'google-analytics'   => array(
					'google-analytics.com/ga.js',
					'www.google-analytics.com/analytics.js',
					'_getTracker',
					"gtag('js'",
				),
				'google-tag-manager' => array(
					'gtm.start',
					'gtm.js',
				),
				'matomo' => array( 'piwik.js', 'matomo.js' ),
				'clicky' => array( 'static.getclicky.com/js', 'clicky_site_ids' ),
				'yandex' => array( 'mc.yandex.ru/metrika/watch.js' ),
			);

		public $amp_tags
			= array(
				'amp-ad-exit',
				'amp-ad',
				'amp-analytics',
				'amp-auto-ads',
				'amp-call-tracking',
				'amp-experiment',
				'amp-pixel',
				'amp-sticky-ad',
				// Dynamic content.
				'amp-google-document-embed',
				'amp-gist',
				// Media.
				'amp-brightcove',
				'amp-dailymotion',
				'amp-hulu',
				'amp-soundcloud',
				'amp-vimeo',
				'amp-youtube',
				'amp-iframe',
				// Social.
				'amp-addthis',
				'amp-beopinion',
				'amp-facebook-comments',
				'amp-facebook-like',
				'amp-facebook-page',
				'amp-facebook',
				'amp-gfycat',
				'amp-instagram',
				'amp-pinterest',
				'amp-reddit',
				'amp-riddle-quiz',
				'amp-social-share',
				'amp-twitter',
				'amp-vine',
				'amp-vk',
			);

		public $sections;
		public $pages = array();
		public $warning_types;
		public $yes_no;
		public $countries;
		public $purposes;
		public $details_per_purpose_us;
		public $regions;
		public $eu_countries;
		public $premium_geo_ip;
		public $premium_ab_testing;
		public $premium_ab_testing_buttons;
		public $collected_info_children;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;

			/**
			 * The legal version is only updated when document contents or the questions leading to it are changed
			 * 1: start version
			 * 2: introduction of US privacy questions
			 * 3: new questions
			 * 4: new questions
			 * 5: UK as separate region
			 * 6: CA as separate region
			 * 7: Impressum in germany
			 * */
			define( 'CMPLZ_LEGAL_VERSION', '8' );

			//common options type
			$this->yes_no = array(
				'yes' => __( 'Yes', 'complianz-gdpr' ),
				'no'  => __( 'No', 'complianz-gdpr' ),
			);

			$this->premium_geo_ip
				= cmplz_sprintf( __( "To enable the warning only for countries with a cookie law, %sget premium%s.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io/pricing/" target="_blank">', '</a>' )
				  . "&nbsp;";
			$this->premium_ab_testing
				= cmplz_sprintf( __( "If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io/pricing/" target="_blank">', '</a>' )
				  . "&nbsp;";

			$this->premium_ab_testing_buttons
						= cmplz_sprintf( __( "If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.",
							'complianz-gdpr' ),
							'<a href="https://complianz.io/pricing/" target="_blank">', '</a>' )
							. "&nbsp;";

			$this->placeholders = array(
				'default' => __('Default','complianz-gdpr'),
				'calendly' => 'Calendly',
				'facebook' => 'Facebook',
				'google-maps' => 'Google Maps',
				'google-recaptcha' => 'Google Recaptcha',
				'instagram' => 'Instagram',
				'openstreetmaps' => 'Open Street Maps',
				'soundcloud' => 'SoundCloud',
				'spotify' => 'Spotify',
				'ted' => 'Ted',
				'twitter' => 'Twitter',
				'tiktok' => 'Tik Tok'
			);

				/* config files */
			require_once( cmplz_path . '/config/countries.php' );
			require_once( cmplz_path . '/config/purpose.php' );
			require_once( cmplz_path . '/config/steps.php' );
			require_once( cmplz_path . '/config/general-settings.php' );
			require_once( cmplz_path . '/config/questions-wizard.php' );
			require_once( cmplz_path . '/config/dynamic-fields.php' );
			require_once( cmplz_path . '/config/documents/documents.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-eu.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-us.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-uk.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-ca.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-au.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-za.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-br.php' );
			require_once(cmplz_path . '/cookiebanner/settings.php' );

			if ( file_exists( cmplz_path . '/pro/config/' ) ) {
				require_once( cmplz_path . '/pro/config/includes.php' );
			}

			/**
			 * Preload fields with a filter, to allow for overriding types
			 */
			add_action( 'plugins_loaded', array( $this, 'preload_init' ), 10 );

			/**
			 * The integrations are loaded with priority 10
			 * Because we want to initialize after that, we use 15 here
			 */
			if ( is_admin() ) {
				add_action( 'plugins_loaded', array( $this, 'load_warning_types' ) );
			}

			add_action( 'plugins_loaded', array( $this, 'init' ), 15 );
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Get full array of regions, but only active ones
		 * @return array
		 */
		public function active_regions(){
			return array_intersect_key( COMPLIANZ::$config->regions, cmplz_get_regions() );
		}

		public function get_section_by_id( $id ) {

			$steps = $this->steps['wizard'];
			foreach ( $steps as $step ) {
				if ( ! isset( $step['sections'] ) ) {
					continue;
				}
				$sections = $step['sections'];

				//because the step arrays start with one instead of 0, we increase with one
				return array_search( $id, array_column( $sections, 'id' ) ) + 1;
			}

		}

		public function get_step_by_id( $id ) {
			$steps = $this->steps['wizard'];

			//because the step arrays start with one instead of 0, we increase with one
			return array_search( $id, array_column( $steps, 'id' ) ) + 1;
		}


		public function fields(
			$page = false, $step = false, $section = false,
			$get_by_fieldname = false
		) {

			$output = array();
			$fields = $this->fields;
			if ( $page ) {
				$fields = cmplz_array_filter_multidimensional( $this->fields, 'source', $page );
			}
			$order = 100;
			foreach ( $fields as $fieldname => $field ) {
				if ( $get_by_fieldname && $fieldname !== $get_by_fieldname ) {
					continue;
				}
				$field = wp_parse_args( $field, array( 'order'=> $order ) );
				$order++;
				if ( $step ) {
					if ( $section && isset( $field['section'] ) ) {
						if ( ( $field['step'] == $step
						       || ( is_array( $field['step'] )
						            && in_array( $step, $field['step'] ) ) )
						     && ( $field['section'] == $section )
						) {
							$output[ $fieldname ] = $field;
						}
					} else {
						if ( ( $field['step'] == $step )
						     || ( is_array( $field['step'] )
						          && in_array( $step, $field['step'] ) )
						) {
							$output[ $fieldname ] = $field;
						}
					}
				}
				if ( ! $step ) {
					$output[ $fieldname ] = $field;
				}
			}

			//maybe sort by order
			if ( $section ){
				uasort($output, function($a, $b) {
					return $a["order"] - $b["order"];
				});
			}

			return $output;
		}

		public function has_sections( $page, $step ) {
			if ( isset( $this->steps[ $page ][ $step ]["sections"] ) ) {
				return true;
			}

			return false;
		}

		public function preload_init(){
			$this->stats_markers = apply_filters( 'cmplz_stats_markers', $this->stats_markers );
			$this->fields = apply_filters( 'cmplz_fields_load_types', $this->fields );
			$this->pages = apply_filters( 'cmplz_pages_load_types', $this->pages );
		}

		public function init() {
			$this->steps = apply_filters('cmplz_steps', $this->steps );
			$this->fields = apply_filters( 'cmplz_fields', $this->fields );
			if ( ! is_admin() ) {
				$regions = cmplz_get_regions(true);
				foreach ( $regions as $region => $label ) {
					if ( !isset( $this->pages[ $region ] ) ) continue;

					foreach ( $this->pages[ $region ] as $type => $data ) {
						$this->pages[ $region ][ $type ]['document_elements']
							= apply_filters( 'cmplz_document_elements',
							$this->pages[ $region ][ $type ]['document_elements'],
							$region, $type, $this->fields() );
					}
				}
			}
		}

		public function load_warning_types() {
			$banner_url = admin_url(add_query_arg(array('page'=>'cmplz-cookiebanner','id'=>cmplz_get_default_banner_id()),'admin.php'));
			$this->warning_types = apply_filters('cmplz_warning_types' ,array(

				'datarequests' => array(
					'warning_condition' => 'cmplz_upgraded_to_current_version',
					'premium' => __( 'New Feature: Data requests for Privacy Statements.', 'complianz-gdpr' ).cmplz_read_more('https://complianz.io/data-requests/'),
					'plus_one' => true,
					'include_in_progress' => false,
				),

				'phpversion' => array(
					'warning_condition' => 'NOT cmplz_has_recommended_phpversion',
					'urgent' => __( 'Your PHP version is lower than the recommended PHP version. Some features are not available. Support for this PHP version will be dropped soon.', 'complianz-gdpr' ).cmplz_read_more('https://complianz.io/php-version/'),
					'plus_one' => true,
					'include_in_progress' => true,
				),

				'upgraded_to_6' => array(
					'warning_condition'  => 'cmplz_upgraded_to_current_version',
					'open' => cmplz_sprintf(__( 'Complianz GDPR/CCPA %s. Learn more about our newest release.', 'complianz-gdpr' ).cmplz_read_more('https://complianz.io/meet-complianz-6-0/'),'6.0.0' ).'&nbsp;'.
					          '<br><br>'.cmplz_sprintf(__('We have changed our Cookie Banner template for future capabilities, please check your %sCookie Banner settings%s.','complianz-gdpr'),'<a href="'.$banner_url.'">','</a>').'&nbsp;'.
					          __('You can reset to default values, if needed.','complianz-gdpr'),
					'admin_notice' => true,
				),

				'wizard-incomplete'  => array(
					'success_conditions'  => array(
						'wizard->all_required_fields_completed_wizard'
					),
					'completed'    => __( 'The wizard has been completed.', 'complianz-gdpr' ),
					'urgent' => __( 'Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz-gdpr' ),
					'plus_one' => true,
					'include_in_progress' => true,
					'dismissible' => false,
				),

				'no-dnt' => array(
					'success_conditions'  => array(
						'get_value_respect_dnt==yes'
					),
					'completed'    => __( 'Do Not Track and Global Privacy Control are respected.', 'complianz-gdpr' ),
					'open' => __( 'Do Not Track and Global Privacy Control are not yet respected.', 'complianz-gdpr' ).cmplz_read_more('https://complianz.io/browser-privacy-controls/'),
				),

				'drop-elementor-banner' => array(
					'plus_one' => true,
					'success_conditions' => array(
						'NOT get_option_cmplz_elementor_banner_dropped'
					),
					'urgent' => __( 'We have dropped support for creating a cookie banner with Elementor Pro. Your banner defaults to a standard cookie banner.', 'complianz-gdpr' ).cmplz_read_more("https://complianz.io/elementor-pro-support"),
				),

				'has_formal' => array(
					'success_conditions'  => array(
						'NOT document->locale_has_formal_variant',
					),
					'open' => cmplz_sprintf( __( 'You have currently selected an informal language, which will result in informal use of language on the legal documents. If you prefer the formal style, you can activate this in the %sgeneral settings%s.', 'complianz-gdpr' ), '<a  target="_blank" href="'.admin_url('options-general.php').'">', '</a>' ).
					          cmplz_read_more('https://complianz.io/informal-language-in-legal-documents/'),
					'include_in_progress' => true,

				),
				'google-fonts' => array(
					'plus_one' => true,
					'warning_condition' => 'cookie_admin->show_google_fonts_notice',
					'success_conditions'  => array(
					),
					'open' => __( 'Google Fonts requires your attention.', 'complianz-gdpr' ) ." ". cmplz_sprintf(__( 'We have added additional support and recommend reviewing your %ssettings%s.', 'complianz-gdpr' ), '<a href="'. admin_url('admin.php?page=cmplz-wizard&step=2&section=4') .'">','</a>')." " . cmplz_sprintf( __( 'Please read this %sarticle%s to read our position on self-hosting Google Fonts and Privacy by Design.', 'complianz-gdpr' ),  '<a href="http://complianz.io/self-hosting-google-fonts-for-wordpress/" target="_blank">', '</a>'),
					'include_in_progress' => true,
				),

				'cookies-changed' => array(
					'plus_one' => true,
					'warning_condition' => 'cookie_admin->cookies_changed',
					'success_conditions'  => array(
					),
					'completed'    => __( 'No cookie changes have been detected.', 'complianz-gdpr' ),
					'open' => __( 'Cookie changes have been detected.', 'complianz-gdpr' ) . " " . cmplz_sprintf( __( 'Please review step %s of the wizard for changes in cookies.', 'complianz-gdpr' ), STEP_COOKIES ),
					'include_in_progress' => true,
				),
				'no-cookie-scan' => array(
					'success_conditions'  => array(
						'cookie_admin->get_last_cookie_scan_date',
					),
					'completed'    => cmplz_sprintf( __( 'Last cookie scan completed on %s.', 'complianz-gdpr' ), COMPLIANZ::$cookie_admin->get_last_cookie_scan_date() ),
					'open' => __( 'No cookie scan has been completed yet.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
				),

				'all-pages-created' => array(
					'warning_condition' => 'wizard->wizard_completed_once',
					'success_conditions'  => array(
						'document->all_required_pages_created',
					),
					'completed'    => __( 'All required pages have been generated.', 'complianz-gdpr' ),
					'open' => __( 'Not all required pages have been generated.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'no-ssl' => array(
					'success_conditions'  => array(
						'is_ssl'
					),
					'completed'    => __( "Great! You're already on SSL!", 'complianz-gdpr' ),
					'open' => cmplz_sprintf( __( "You don't have SSL on your site yet. Install SSL for Free with %sReally Simple SSL%s", 'complianz-gdpr' ),
						'<a target="_blank" href="https://wordpress.org/plugins/really-simple-ssl/">', '</a>' ),
					'include_in_progress' => true,
				),

				'ga-needs-configuring'     => array(
					'warning_condition' => 'cookie_admin->uses_google_analytics',
					'success_conditions'  => array(
						'cookie_admin->analytics_configured',
					),
					'open' => __( 'Google Analytics is being used, but is not configured in Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'gtm-needs-configuring'    => array(
					'warning_condition' => 'cookie_admin->uses_google_tagmanager',
					'success_conditions'  => array(
						'cookie_admin->tagmanager_configured',
					),
					'open' => __( 'Google Tag Manager is being used, but is not configured in Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'matomo-needs-configuring' => array(
					'warning_condition' => 'cookie_admin->uses_matomo',
					'success_conditions'  => array(
						'cookie_admin->matomo_configured',
					),
					'open' => __( 'Matomo is being used, but is not configured in Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),
				'docs-need-updating'       => array(
					'success_conditions'  => array(
						'NOT document->documents_need_updating'
					),
					'open' => __( 'Your documents have not been updated in the past 12 months. Run the wizard to check your settings.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),
				'cookies-incomplete'       => array(
					'warning_condition' => 'NOT cookie_admin->use_cdb_api',
					'success_conditions'  => array(
				        'NOT cookie_admin->has_empty_cookie_descriptions',
					),
					'open' => __( 'You have cookies with incomplete descriptions.', 'complianz-gdpr' ) . " "
					                 . cmplz_sprintf( __( 'Enable the cookiedatabase.org API for automatic descriptions, or add these %smanually%s.', 'complianz-gdpr' ), '<a href="' . add_query_arg( array(
								'page'    => 'cmplz-wizard',
								'step'    => STEP_COOKIES,
								'section' => 5
							), admin_url( 'admin.php' ) ) . '">', '</a>' ),
					'include_in_progress' => true,
				),

				'double-stats' => array(
					'success_conditions'  => array(
						'NOT get_option_cmplz_double_stats',
					),
					'open' => __( 'You have a duplicate implementation of your statistics tool on your site.', 'complianz-gdpr' ) .
					          __( 'After the issue has been resolved, please re-run a scan to clear this message.', 'complianz-gdpr' )
					                 . cmplz_read_more( 'https://complianz.io/duplicate-implementation-of-analytics/' ),
					'include_in_progress' => true,
					'dismissible' => true,
				),

				'console-errors' => array(
					'warning_condition' => 'cookie_admin->site_needs_cookie_warning',
					'success_conditions'  => array(
						'NOT cmplz_get_console_errors',
					),
					'open' => __( 'Javascript errors are detected on the front-end of your site. This may break the cookie banner functionality.', 'complianz-gdpr' )
					                 . '<br>'.__("Last error in the console:", "complianz-gdpr")
					                 .'<div style="color:red">'
					                 . cmplz_get_console_errors()
					                 .'</div>'
					                 . cmplz_read_more( 'https://complianz.io/cookie-banner-does-not-appear/' , false ),
					'include_in_progress' => true,
				),

				'cookie-banner-enabled' => array(
					'success_conditions'  => array(
						'cmplz_cookiebanner_should_load(true)',
					),
					'completed' => __( 'Your site requires a cookie banner, which has been enabled.', 'complianz-gdpr' ),
					'urgent' => __( 'Your site is not configured to show a cookie banner at the moment.', 'complianz-gdpr' ).cmplz_read_more('https://complianz.io/cookie-banner-does-not-appear/'),
					'include_in_progress' => true,
					'dismissible' => true,
				),

				'pretty-permalinks-error' => array(
					'success_conditions'  => array(
						'get_option_permalink_structure',
					),
					'plus_one' => true,
					'urgent' => __( 'Pretty permalinks are not enabled on your site. This can cause issues with the REST API, used by Complianz.', 'complianz-gdpr' ).'&nbsp;<a href="'.admin_url('options-permalink.php').'">'.__("Enable", "complianz-gdpr").'</a>',
					'include_in_progress' => true,
					'dismissible' => false,
				),
				'uploads-folder-writable' => array(
					'success_conditions'  => array(
						'cmplz_uploads_folder_writable',
					),
					'plus_one' => true,
					'urgent' => __( 'Your uploads folder is not writable. Complianz needs this folder to save the cookie banner CSS.', 'complianz-gdpr' ).
					cmplz_read_more('https://complianz.io/folder-permissions/'),
					'include_in_progress' => true,
					'dismissible' => false,
				),
				'custom-google-maps' => array(
					'warning_condition' => 'cmplz_uses_google_maps',
					'success_conditions'  => array(
						'cmplz_google_maps_integration_enabled',
					),
					'plus_one' => false,
					'open' => __( 'We see you have enabled Google Maps as a service, but we can\'t find an integration. You can integrate manually if needed.', 'complianz-gdpr' ).
					cmplz_read_more('https://complianz.io/custom-google-maps-integration/'),
					'include_in_progress' => true,
				),

				'other-cookie-plugins' => array(
					'warning_condition'  => 'cmplz_detected_cookie_plugin',
					'plus_one' => true,
					'urgent' => cmplz_sprintf(__( 'We have detected the %s plugin on your website.', 'complianz-gdpr' ),cmplz_detected_cookie_plugin(true)).'&nbsp;'.__( 'As Complianz handles all the functionality this plugin provides, you should disable this plugin to prevent unexpected behaviour.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
				),

				'advertising-enabled' => array(
					'warning_condition' => 'cmplz_uses_ad_cookies_personalized',
					'premium' => __( 'Are you showing ads on your site? Consider implementing TCF.', 'complianz-gdpr' ) . cmplz_read_more('https://complianz.io/implementing-tcf-on-your-website/'),
					'include_in_progress' => false,
					'dismissible' => false,
				),

				'sync-privacy-statement' => array(
					'premium' => __( 'Create a Privacy Statement and other Legal Documents with Complianz.', 'complianz-gdpr' ) . ' <a href="https://complianz.io/l/pricing/?src=cmplz-plugin" target="_blank">' . __('Upgrade to premium', 'complianz-gdpr') . '</a>',
					'include_in_progress' => false,
					'dismissible' => false,
				),

				'bf-notice2022' => array(
					'warning_condition'  => 'admin->is_bf',
					'plus_one' => true,
					'open' => __( "Black Friday sale! Get 40% Off Complianz GDPR/CCPA premium!", 'complianz-gdpr' ).'&nbsp;'.'<a target="_blank" href="https://complianz.io/pricing">'.__('Learn more.','complianz-gdpr').'</a>',
					'include_in_progress' => false,
				),

				'ecommerce-legal' => array(
					'warning_condition' => 'cmplz_ecommerce_legal',
					'premium' => __( 'Legal compliance for webshops.', 'complianz-gdpr' ) . cmplz_read_more('https://complianz.io/legal-compliance-for-ecommerce/'),
					'include_in_progress' => false,
					'dismissible' => false,
				),

				'configure-tag-manager' => array(
					'warning_condition' => 'cmplz_uses_google_tagmanager_or_analytics',
					'premium' => __( 'Learn more about Google Consent Mode.', 'complianz-gdpr' ) . cmplz_read_more('https://complianz.io/configure-consent-mode/'),
					'include_in_progress' => false,
					'dismissible' => false,
				),

				'targeting-multiple-regions' => array(
					'warning_condition' => 'cmplz_targeting_multiple_regions',
					'premium' => __( 'Are you targeting multiple regions?', 'complianz-gdpr' ) . cmplz_read_more('https://complianz.io/what-regions-do-i-target/'),
					'include_in_progress' => false,
					'dismissible' => false,
				),
				'install-burst' => array(
					'warning_condition' => 'cmplz_show_install_burst_warning',
					'open' => __( 'Statistics without Consent. Meet Burst Statistics from Complianz.', 'complianz-gdpr' ) . ' <a href="'. admin_url('admin.php?page=cmplz-wizard&step=2&section=2') .'">' . __('Install directly from our wizard.', 'complianz-gdpr') . '</a>',
					'include_in_progress' => false,
				),

			) );
		}

	} //class closure
}
