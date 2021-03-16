<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_config" ) ) {

	class cmplz_config {
		private static $_this;
		public $fields = array();

		public $upgrade_cookies = array(
				'yith_wcwl_products',
			);

		//used to check if social media is used on site

		public $thirdparty_services
			= array(
				'google-fonts'     => 'Google Fonts',
				'google-recaptcha' => 'Google reCAPTCHA',
				"google-maps"      => 'Google Maps',
				"openstreetmaps"   => 'OpenStreetMaps',
				"vimeo"            => 'Vimeo',
				"youtube"          => 'YouTube',
				"videopress"       => 'VideoPress',
				"dailymotion"      => 'Dailymotion',
				"soundcloud"       => 'SoundCloud',
				"paypal"           => 'PayPal',
				"spotify"          => 'Spotify',
				"hotjar"           => 'Hotjar',
				"addthis"          => 'AddThis',
				"addtoany"         => 'AddToAny',
				"sharethis"        => 'ShareThis',
				"livechat"         => 'LiveChat',
				"calendly"         => 'Calendly',
			);

		public $thirdparty_socialmedia
			= array(
				'facebook'  => 'Facebook',
				'twitter'   => 'Twitter',
				'linkedin'  => 'LinkedIn',
				'whatsapp'  => 'WhatsApp',
				'instagram' => 'Instagram',
				'disqus'    => 'Disqus',
				'pinterest' => 'Pinterest',
			);

		public $stats
			= array(
				'google-analytics'   => 'Google Analytics',
				'google-tag-manager' => 'Tag Manager',
				'matomo'             => 'Matomo',
			);

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
					'addthis_widget.js'
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
					'pixel-caffeine'
				),
				"pinterest" => array(
					'super-socializer',
					'assets.pinterest.com'
				),
				"disqus"    => array( 'disqus.com' ),
				"instagram" => array(
					'instawidget.net/js/instawidget.js',
					'cdninstagram.com',
					'src="https://www.instagram.com',
					'src="https://instagram.com',
				),
			);

		/**
		 * Scripts with this string in the content get listed in the third party list.
		 * Also used in cmplz_placeholder()
		 * */

		public $thirdparty_service_markers
			= array(
				"google-maps"      => array(
					'new google.maps.',
					'google.com/maps',
					'maps.google.com',
					'wp-google-maps'
				),
				"soundcloud"       => array( 'w.soundcloud.com/player' ),
				"openstreetmaps"   => array(
					'openstreetmap.org',
					'osm/js/osm'
				),
				"vimeo"            => array( 'player.vimeo.com' ),
				"google-recaptcha" => array(
					'google.com/recaptcha',
					'google.com/recaptcha',
					'grecaptcha',
					'recaptcha.js',
					'recaptcha/api'
				),
				"youtube"          => array( 'youtube.com' ),
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
				"calendly"         => array( 'assets.calendly.com' ),
			);

		public $stats_markers = array(
				'google-analytics'   => array(
					'google-analytics.com/ga.js',
					'www.google-analytics.com/analytics.js',
				),
				'google-tag-manager' => array(
					'googletagmanager.com/gtag/js',
					'gtm.js',
				),
				'matomo' => array( 'piwik.js', 'matomo.js' ),
				'clicky' => array( 'static.getclicky.com/js', 'clicky_site_ids' ),
			);


		/**
		 * Some scripts need to be loaded in specific order
		 * key: script or part of script to wait for
		 * value: script or part of script that should wait
		 * */

		/**
		 * example:
		 *
		 *
		 * add_filter('cmplz_dependencies', 'my_dependency');
		 * function my_dependency($deps){
		 * $deps['wait-for-this-script'] = 'script-that-should-wait';
		 * return $deps;
		 * }
		 */
		public $dependencies = array();

		/**
		 * placeholders for not iframes
		 * */

		public $placeholder_markers = array();

		/**
		 * Scripts with this string in the source or in the content of the script tags get blocked.
		 *
		 * */

		public $script_tags = array();

		/**
		 * Style strings (google fonts have been removed in favor of plugin recommendation)
		 * */

		public $style_tags = array();

		/**
		 * Scripts in this list are loaded with post scribe.js
		 * due to the implementation, these should also be added to the list above
		 *
		 * */

		public $async_list = array();

		public $iframe_tags = array();
		public $iframe_tags_not_including = array();


		/**
		 * images with a URl in this list will get blocked
		 * */

		public $image_tags = array();

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
		public $pages;
		public $warning_types;
		public $yes_no;
		public $countries;
		public $purposes;
		public $details_per_purpose_us;
		public $regions;
		public $eu_countries;
		public $premium_geo_ip;
		public $premium_ab_testing;
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
			define( 'CMPLZ_LEGAL_VERSION', '7' );

			//common options type
			$this->yes_no = array(
				'yes' => __( 'Yes', 'complianz-gdpr' ),
				'no'  => __( 'No', 'complianz-gdpr' ),
			);

			$this->premium_geo_ip
				= sprintf( __( "To enable the warning only for countries with a cookie law, %sget premium%s.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io" target="_blank">', '</a>' )
				  . "&nbsp;";
			$this->premium_ab_testing
				= sprintf( __( "If you want to run a/b testing to track which banner gets the highest acceptance ratio, %sget premium%s.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io" target="_blank">', '</a>' )
				  . "&nbsp;";


				/* config files */
			require_once( cmplz_path . '/config/countries.php' );
			require_once( cmplz_path . '/config/purpose.php' );
			require_once( cmplz_path . '/config/steps.php' );
			require_once( cmplz_path . '/config/warnings.php' );
			require_once( cmplz_path . '/config/general-settings.php' );
			require_once( cmplz_path . '/config/questions-wizard.php' );
			require_once( cmplz_path . '/config/dynamic-fields.php' );
			require_once( cmplz_path . '/config/dynamic-document-elements.php' );
			require_once( cmplz_path . '/config/documents/documents.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-eu.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-us.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-uk.php' );
			require_once( cmplz_path . '/config/documents/cookie-policy-ca.php' );
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
			add_action( 'plugins_loaded', array( $this, 'init' ), 15 );
		}

		static function this() {
			return self::$_this;
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
				$fields = cmplz_array_filter_multidimensional( $this->fields,
					'source', $page );
			}

			foreach ( $fields as $fieldname => $field ) {
				if ( $get_by_fieldname && $fieldname !== $get_by_fieldname ) {
					continue;
				}

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
		}

		public function init() {
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
	}

} //class closure
