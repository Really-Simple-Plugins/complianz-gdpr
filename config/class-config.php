<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_config" ) ) {
	class cmplz_config {
		private static $_this;
		public $fields = array();
		public $formal_languages = array();
		public $generic_documents_list;
		public $supported_states;
		public $cookie_consent_converter;
		public $language_codes;
		public $supported_regions;
		public $thirdparty_services
			= array(
				'activecampaign'   => 'Active Campaign',
				"adobe-fonts"      => 'Adobe Fonts',
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
				"microsoftads"     => 'Microsoft Ads'
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
				"addtoany"          => array( 'addtoany.min.js', 'window.a2a_config', 'static.addtoany.com' ),
				"sharethis"        => array( 'sharethis.com' ),
				"microsoftads"     => array('bat.bing.com'),
				"livechat"         => array( 'cdn.livechatinc.com/tracking.js' ),
				"hubspot"         => array( 'js.hs-scripts.com/', 'hbspt.forms.create', 'js.hsforms.net','track.hubspot.com','js.hs-analytics.net'),
				"calendly"         => array( 'assets.calendly.com' ),
				"twitch"          => array( 'twitch.tv', 'player.twitch.tv'),
				"adobe-fonts"    => array( 'p.typekit.net', 'use.typekit.net'),
			);

		public $stats
			= array(
				'google-analytics'   => 'Google Analytics',
				'google-tag-manager' => 'Tag Manager',
				'matomo'             => 'Matomo',
				'clicky'             => 'Clicky',
				'yandex'             => 'Yandex',
				'clarity'            => 'Clarity',

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
				'clarity' => array( 'clarity.ms' ),
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

		public $lawful_bases;

		public $sections;
		public $pages = array();
		public $warning_types;
		public $yes_no;
		public $countries;
		public $purposes;
		public $details_per_purpose_us;
		public $regions;
		public $eu_countries;
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
			require_once( cmplz_path . '/config/countries.php' );

			//common options type
			$this->yes_no = array(
				'yes' => __( 'Yes', 'complianz-gdpr' ),
				'no'  => __( 'No', 'complianz-gdpr' ),
			);

			$this->lawful_bases = [
				'1' => __('I obtain permission from the person concerned', 'complianz-gdpr'),
				'2' => __('It is necessary for the execution of an agreement with the person concerned', 'complianz-gdpr'),
				'3' => __('I am obligated by law', 'complianz-gdpr'),
				'4' => __('It is necessary to fulfilll a task concerning public law', 'complianz-gdpr'),
				'5' => __('It is necessary for my own legitimate interest, and that interest outweighs the interest of the person concerned', 'complianz-gdpr'),
				'6' => __('It is necessary to protect the life or physical safety of a person', 'complianz-gdpr'),
			];

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

			require_once( cmplz_path . '/config/purpose.php' );
			require_once( cmplz_path . '/config/documents/documents.php' );
			//always load the fields, for translation purposes etc.
			require_once( cmplz_path . 'settings/config/config.php' );
			if ( file_exists(cmplz_path . 'pro/settings/config.php') ) {
				require_once( cmplz_path . 'pro/settings/config.php');
				require_once( cmplz_path . 'pro/config/dynamic-document-elements.php');
			}
			require_once( cmplz_path . '/cookiebanner/settings.php' );

			/**
			 * Preload fields with a filter, to allow for overriding types
			 */
			add_action( 'plugins_loaded', array( $this, 'init' ), 10 );
			add_action( 'plugins_loaded', array( $this, 'load_pages' ), 10 );
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Get full array of regions, but only active ones
		 * @return array
		 */
		public function active_regions(){
			return array_intersect_key( COMPLIANZ::$config->regions, cmplz_get_regions(false, 'short') );
		}


		public function init(){
			/**
			 * For the Brazil privacy law there are some additional options. These should only be enabled when the only chosen region is Brazil.
			 */
			if ( cmplz_has_region('br') && cmplz_multiple_regions() == false) {
				$this->lawful_bases['7'] = __('It is necessary to carry out studies by a research body, ensuring, whenever possible, the anonymization of personal data', 'complianz-gdpr');
				$this->lawful_bases['8'] = __('It is necessary for the regular exercise of rights in judicial, administrative or arbitration proceedings', 'complianz-gdpr');
				$this->lawful_bases['9'] = __('It is necessary for the protection of health, exclusively, in a procedure performed by health professionals, health services or health authority', 'complianz-gdpr');
				$this->lawful_bases['10'] = __('It is necessary for credit protection', 'complianz-gdpr');
			}
			$files = [];
			$regions = cmplz_get_regions();
			$cookie_policy = cmplz_get_option( 'cookie-statement' ) === 'generated';
			$privacy_statement = cmplz_get_option( 'privacy-statement' ) === 'generated';
			$files[] = '/pro/config/documents/documents.php';
			foreach ($regions as $region) {
				if ($cookie_policy) $files[] = "/config/documents/cookie-policy-$region.php";
				if ($privacy_statement) $files[] = "/pro/config/documents/$region/privacy-policy.php";
				$files[] = "/pro/config/documents/$region/privacy-policy-children.php";
			}

			if (cmplz_get_option( 'disclaimer' ) === 'generated') $files[] = '/pro/config/documents/disclaimer.php';
			if (cmplz_get_option( 'impressum' ) === 'generated') $files[] = '/pro/config/documents/impressum.php';
			foreach ($files as $file) {
				if ( file_exists( cmplz_path . $file ) ) {
					require_once( cmplz_path . $file );
				}
			}
			$this->stats_markers = apply_filters( 'cmplz_stats_markers', $this->stats_markers );
			$this->fields = apply_filters( 'cmplz_fields', [] );
		}

		public function load_pages(){
			$this->pages = apply_filters( 'cmplz_pages_load_types', $this->pages );
			$regions = cmplz_get_regions();
			foreach ($regions as $region) {
				if ( !isset( $this->pages[ $region ] ) ) {
					continue;
				}
				foreach ( $this->pages[ $region ] as $type => $data ) {
					if ( !isset( $this->pages[ $region ][ $type ]['document_elements'] ) ) {
						continue;
					}
					$this->pages[ $region ][ $type ]['document_elements'] = apply_filters( 'cmplz_document_elements', $this->pages[ $region ][ $type ]['document_elements'], $region, $type, $this->fields );
				}
			}
		}

		public function load_warning_types() {
			return apply_filters('cmplz_warning_types' ,array(
				'phpversion' => array(
					'warning_condition' => 'NOT cmplz_has_recommended_phpversion',
					'urgent' => __( 'Your PHP version is lower than the recommended PHP version. Some features are not available. Support for this PHP version will be dropped soon.', 'complianz-gdpr' ),
					'url' => 'https://complianz.io/php-version/',
					'plus_one' => true,
					'include_in_progress' => true,
				),

				'upgraded_to_7' => array(
					'warning_condition'  => 'cmplz_upgraded_to_current_version',
					'open' => cmplz_sprintf(__( 'Complianz GDPR/CCPA. Learn more about our newest release.', 'complianz-gdpr' ),'7.0'),
					'url' => 'https://complianz.io/meet-complianz-7-0/',
					'admin_notice' => true,
				),
				'migrate_js' => array(
					'warning_condition'  => 'cmplz_upgraded_to_current_version',
					'open' => __( 'Migrate.js, which allowed a smooth upgrade to 6.0, has been deprecated.', 'complianz-gdpr' ),
					'url' => 'https://complianz.io/migrate-js-deprecated/',
					'admin_notice' => true,
				),
				'new_gutenberg_consentarea' => array(
					'warning_condition'  => 'cmplz_upgraded_to_current_version',
					'open' => __( 'New: Gutenberg Block with consent capabilities.', 'complianz-gdpr' ),
					'admin_notice' => false,
					'plus_one' => true,
					'url' => 'https://complianz.io/gutenberg-block-consent/'
				),

				'no-dnt' => array(
					'success_conditions'  => array(
						'get_value_respect_dnt==yes'
					),
					'completed'    => __( 'Do Not Track and Global Privacy Control are respected.', 'complianz-gdpr' ),
					'open' => __( 'Do Not Track and Global Privacy Control are not yet respected.', 'complianz-gdpr' ),
				   'url' => 'https://complianz.io/browser-privacy-controls/',
				),

				'drop-elementor-banner' => array(
					'plus_one' => true,
					'success_conditions' => array(
						'NOT get_option_cmplz_elementor_banner_dropped'
					),
					'url' => '"https://complianz.io/elementor-pro-support',
					'urgent' => __( 'We have dropped support for creating a cookie banner with Elementor Pro. Your banner defaults to a standard cookie banner.', 'complianz-gdpr' ),
				),
				'ajax_fallback' => array(
					'warning_condition'  =>'get_option_cmplz_ajax_fallback_active',
					'urgent' => __( "Please check if your REST API is loading correctly. Your site currently is using the slower Ajax fallback method to load the settings.", 'complianz-gdpr' ),
					'url' => 'https://complianz.io/instructions/how-to-debug-a-blank-settings-page-in-complianz/',
					'plus_one' => true,
				),

				'has_formal' => array(
					'success_conditions'  => array(
						'NOT document->locale_has_formal_variant',
					),
					'open' =>  __( 'You have currently selected an informal language, which will result in informal use of language on the legal documents. If you prefer the formal style, you can activate this in the general settings.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'url' =>'https://complianz.io/informal-language-in-legal-documents/'

				),
				'google-fonts' => array(
					'plus_one' => true,
					'warning_condition' => 'banner_loader->show_google_fonts_notice',
					'success_conditions'  => array(
					),
					'open' => __( 'Google Fonts requires your attention.', 'complianz-gdpr' ) ." ". cmplz_sprintf(__( 'We have added additional support and recommend reviewing your %ssettings%s.', 'complianz-gdpr' ), '<a href="'. admin_url('admin.php?page=cmplz-wizard&step=2&section=4') .'">','</a>')." " . cmplz_sprintf( __( 'Please read this %sarticle%s to read our position on self-hosting Google Fonts and Privacy by Design.', 'complianz-gdpr' ),  '<a href="http://complianz.io/self-hosting-google-fonts-for-wordpress/" target="_blank">', '</a>'),
					'include_in_progress' => true,
				),

				'cookies-changed' => array(
					'plus_one' => true,
					'warning_condition' => 'scan->cookies_changed',
					'success_conditions'  => array(
					),
					'completed'    => __( 'No cookie changes have been detected.', 'complianz-gdpr' ),
					'open' => __( 'Cookie changes have been detected.', 'complianz-gdpr' ) . " " . cmplz_sprintf( __( 'Please review step %s of the wizard for changes in cookies.', 'complianz-gdpr' ), STEP_COOKIES ),
					'include_in_progress' => true,
				),
				'no-cookie-scan' => array(
					'success_conditions'  => array(
						'banner_loader->get_last_cookie_scan_date',
					),
					'completed'    => cmplz_sprintf( __( 'Last cookie scan completed on %s.', 'complianz-gdpr' ), COMPLIANZ::$banner_loader->get_last_cookie_scan_date() ),
					'open' => __( 'No cookie scan has been completed yet.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
				),

				'all-pages-created' => array(
					'warning_condition' => 'get_option_cmplz_wizard_completed_once',
					'success_conditions'  => array(
						'documents_admin->all_required_pages_created',
					),
					'completed'    => __( 'All required pages have been generated.', 'complianz-gdpr' ),
					'open' => __( 'Not all required pages have been generated.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'hardening' => array(
					'warning_condition' => 'admin->no_security_plugin_active',
					'open' =>  __( "Harden your website and quickly detect vulnerabilities with Really Simple SSL", 'complianz-gdpr' ),
					'include_in_progress' => true,
					'url' => '#tools/security'
				),

				'ga-needs-configuring'     => array(
					'warning_condition' => 'banner_loader->uses_google_analytics',
					'success_conditions'  => array(
						'banner_loader->analytics_configured',
					),
					'open' => __( 'Google Analytics is being used, but is not configured in Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'gtm-needs-configuring'    => array(
					'warning_condition' => 'banner_loader->uses_google_tagmanager',
					'success_conditions'  => array(
						'banner_loader->tagmanager_configured',
					),
					'open' => __( 'Google Tag Manager is being used, but is not configured in Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
				),

				'matomo-needs-configuring' => array(
					'warning_condition' => 'banner_loader->uses_matomo',
					'success_conditions'  => array(
						'banner_loader->matomo_configured',
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
					'warning_condition' => 'NOT banner_loader->use_cdb_api',
					'success_conditions'  => array(
				        'NOT sync->has_empty_cookie_descriptions',
					),
					'open' => __( 'You have cookies with incomplete descriptions.', 'complianz-gdpr' ) . " "
					                 .  __( 'Enable the cookiedatabase.org API for automatic descriptions, or add these manually.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'url' => '#wizard/cookie-descriptions'
				),

				'double-stats' => array(
					'success_conditions'  => array(
						'NOT get_option_cmplz_double_stats',
					),
					'warning_condition' => 'cmplz_uses_statistics',
					'open' => __( 'You have a duplicate implementation of your statistics tool on your site.', 'complianz-gdpr' ) .
					          __( 'After the issue has been resolved, please re-run a scan to clear this message.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => true,
					'url' => 'https://complianz.io/duplicate-implementation-of-analytics/',
				),

				'console-errors' => array(
					'warning_condition' => 'banner_loader->site_needs_cookie_warning',
					'success_conditions'  => array(
						'NOT cmplz_get_console_errors',
					),
					'open' => __( 'Javascript errors are detected on the front-end of your site. This may break the cookie banner functionality.', 'complianz-gdpr' )
					                 . '<br />'.__("Last error in the console:", "complianz-gdpr")
					                 .'<div style="color:red">'
					                 . cmplz_get_console_errors()
					                 .'</div>',
					'include_in_progress' => true,
					'url' => 'https://complianz.io/cookie-banner-does-not-appear/',
				),

				'cookie-banner-enabled' => array(
					'success_conditions'  => array(
						'cmplz_cookiebanner_should_load(true)',
					),
					'completed' => __( 'Your site requires a cookie banner, which has been enabled.', 'complianz-gdpr' ),
					'urgent' => __( 'Your site is not configured to show a cookie banner at the moment.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => true,
					'url' => 'https://complianz.io/cookie-banner-does-not-appear/'
				),

				'pretty-permalinks-error' => array(
					'success_conditions'  => array(
						'get_option_permalink_structure',
					),
					'plus_one' => true,
					'urgent' => __( 'Pretty permalinks are not enabled on your site. This can cause issues with the REST API, used by Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
					'url' => admin_url('options-permalink.php'),
				),
				'uploads-folder-writable' => array(
					'success_conditions'  => array(
						'cmplz_uploads_folder_writable',
					),
					'plus_one' => true,
					'urgent' => __( 'Your uploads folder is not writable. Complianz needs this folder to save the cookie banner CSS.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
					'url' => 'https://complianz.io/folder-permissions/'
				),
				'custom-google-maps' => array(
					'warning_condition' => 'cmplz_uses_google_maps',
					'success_conditions'  => array(
						'cmplz_google_maps_integration_enabled',
					),
					'plus_one' => false,
					'open' => __( 'We see you have enabled Google Maps as a service, but we can\'t find an integration. You can integrate manually if needed.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'url' => 'https://complianz.io/custom-google-maps-integration/',
				),

				'other-cookie-plugins' => array(
					'warning_condition'  => 'cmplz_detected_cookie_plugin',
					'plus_one' => true,
					'urgent' => cmplz_sprintf(__( 'We have detected the %s plugin on your website.', 'complianz-gdpr' ),cmplz_detected_cookie_plugin(true)).'&nbsp;'.__( 'As Complianz handles all the functionality this plugin provides, you should disable this plugin to prevent unexpected behaviour.', 'complianz-gdpr' ),
					'include_in_progress' => true,
					'dismissible' => false,
				),

				'advertising-enabled' => array(
					'warning_condition' => 'cmplz_uses_ad_cookies',
					'premium' => __( 'Are you showing ads on your site? Consider implementing TCF.', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'dismissible' => false,
					'url' => 'https://complianz.io/implementing-tcf-on-your-website/',
				),

				'sync-privacy-statement' => array(
					'premium' => __( 'Create a Privacy Statement and other Legal Documents with Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'dismissible' => false,
					'url' => 'https://complianz.io/l/pricing/?src=cmplz-plugin',
				),

				'bf-notice2022' => array(
					'warning_condition'  => 'admin->is_bf',
					'plus_one' => true,
					'open' => __( "Black Friday sale! Get 40% Off Complianz GDPR/CCPA premium!", 'complianz-gdpr' ),
					'include_in_progress' => false,
					'url' => 'https://complianz.io/pricing'
				),

				'ecommerce-legal' => array(
					'warning_condition' => 'cmplz_ecommerce_legal',
					'premium' => __( 'Legal compliance for webshops.', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'dismissible' => false,
					'url' => 'https://complianz.io/legal-compliance-for-ecommerce/',
				),

				'configure-tag-manager' => array(
					'warning_condition' => 'cmplz_uses_google_tagmanager_or_analytics',
					'premium' => __( 'Learn more about Google Consent Mode.', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'dismissible' => false,
					'url' => 'https://complianz.io/configure-consent-mode/'
				),

				'targeting-multiple-regions' => array(
					'warning_condition' => 'cmplz_targeting_multiple_regions',
					'premium' => __( 'Are you targeting multiple regions?', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'dismissible' => false,
					'url' => 'https://complianz.io/what-regions-do-i-target/',
				),
				'install-burst' => array(
					'warning_condition' => 'cmplz_show_install_burst_warning',
					'open' => __( 'Statistics without Consent. Meet Burst Statistics from Complianz.', 'complianz-gdpr' ),
					'include_in_progress' => false,
					'url' => '#wizard/statistics',
				),

			) );
		}

	} //class closure
}
