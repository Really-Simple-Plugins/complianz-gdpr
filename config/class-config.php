<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_config" ) ) {

	class cmplz_config {
		private static $_this;
		public $fields = array();

		public $upgrade_cookies
			= array(
				'__adroll_fpc',
				'__asc',
				'__auc',
				'__atuvs',
				'__atuvc',
				'psc',
				'uid',
				'uit',
				'uvc',
				'cw_id',
				'loc',
				'mus',
				'na_id',
				'na_tc',
				'uvc',
				'uid',
				'ouid',
				'__atssc',
				'_at.cww',
				'_at.hist.xxxxxx',
				'at-lojson-cache-ra-xxxxxx',
				'at-lojson-cache-wp-xxxxxx',
				'at-rand',
				'__cfduid',
				'_sm_au_c',
				'__insp_norec_sess, __insp_nv, __insp_ref, __insp_slim, __insp_targetlpt, __insp _targlpu, __insp_wid',
				'__smVID',
				'__smToken',
				'partial_smSessionId',
				'__smScrollBoxShown',
				'__smListBuilderShown',
				'__stid',
				'uset',
				'_utmb',
				'_utmz',
				'_utmc',
				'_utma',
				'optimizelyPendingLogEvents',
				'optimizelyBuckets',
				'optimizelySegments',
				'optimizelyEndUserId',
				'__uset',
				'stdlxmap',
				'_stacxiommap',
				'_stamap',
				'_stgmap',
				'_stid',
				'UID',
				'UIDR',
				'__stripe_mid',
				'__stripe_sid',
				'__tawkuuid',
				'TawkConnectionTime',
				'tawkUUID',
				'tawk-partial_',
				'twk-partial_',
				'TawkWindowName',
				'__zlcmid',
				'__zprivacy',
				'__zlcstore',
				'_beeketing_cart_token',
				'beeketing_show_review_request',
				'beeketing_show_cross_sell',
				'bk_show_tab_freeApps',
				'bk_abtest_last_changed_time',
				'bk_identify',
				'bk_cart',
				'bk_gs',
				'bk_gs_time',
				'beeketing_show_first_time',
				'beeketing_activated_plugin',
				'beeketing_hide_review_request',
				'beeketing_cart_fragments_init',
				'_ceir',
				'_ceg.s',
				'_ceg.u',
				'_ceg_s',
				'_ceg_u',
				'_drip_client_6994213',
				'_ga',
				'_gid',
				'_gat',
				'_gaexp',
				'_utm',
				'__utmc',
				'UTMD_',
				'__utmv',
				'__utmz',
				'_gat_gtag_UA_ID',
				'_hjIncludedInSample',
				'_hjMinimizedTestersWidgets',
				'_hjClosedSurveyInvites',
				'_hjDonePolls',
				'_hjMinimizedPolls',
				'_hjDoneTestersWidgets',
				'_hp2_ses',
				'_iub_cs-xxxx',
				'_jsuid',
				'_lscache_vary',
				'_omappvp',
				'_omappvs',
				'_pk_ref',
				'_pk_cvar',
				'_pk_id',
				'_pk_ses',
				'_pk_hsr',
				'piwik_ignore',
				'PIWIK_SESSID',
				'_shopify_y',
				'_shopify_sa_t',
				'_shopify_sa_p',
				'_shopify_s',
				'_shopify_fs',
				'_orig_referrer',
				'_tccl_visitor',
				'_tccl_visit',
				'_utmb',
				'_utmz',
				'_utmc',
				'_utma',
				'_vis_opt_out',
				'_vis_opt_s',
				'partial_vis_opt_exp',
				'_vis_opt_test_cookie',
				'_vis_opt_exp_[experiment_id]_goal_[goal_id]',
				'partial_vwo_',
				'_vwo_uuid_v2',
				'_wordpress_',
				'_wpfuuid',
				'1P_JAR',
				'ac_enable_tracking',
				'acalltracker',
				'acalltrackersession',
				'ADK_EX_15',
				'ADKUID',
				'advanced_ads_browser_width',
				'aelia_cs_selected_currency',
				'affwp_ref',
				'affwp_campaign',
				'affwp_ref_visit_id',
				'amazon-pay-connectedAuth',
				'amazon-pay-abtesting-new-widgets',
				'autoptimize_feed',
				'bleeper_customerID-xxxxxx',
				'bleeper_current',
				'bleeper_first',
				'bp-activity-oldestpage',
				'calltrk_landing',
				'calltrk_referrer',
				'calltrk_session_id_xxxxxx',
				'catAccCookies',
				'CMDD',
				'CMRUM3',
				'CMSC',
				'cmplz_marketing',
				'cmplz_event_xxx',
				'cmplz_stats',
				'cmplz_id',
				'complianz_config',
				'complianz_consent_status',
				'complianz_policy_id',
				'CookieConsent',
				'ct_sfw_pass_key',
				'apbct_site_landing_ts',
				'ct_checkjs',
				'apbct_visible_fields',
				'apbct_visible_fields_count',
				'ct_fkp_timestamp',
				'ct_pointer_data',
				'ct_timezone',
				'ct_ps_timestamp',
				'apbct_timestamp',
				'apbct_page_hits',
				'apbct_cookies_test',
				'ctm',
				'dextp',
				'dmvk',
				'hist',
				's_vi',
				'ts',
				'v1st',
				'DYAMAR_POLL_16_VOTED',
				'DYAMAR_POLL_xxxxxx_VOTED',
				'end_user_id',
				'cdnoptimizely',
				'ab_optimizely',
				'optimizelyPendingLogEvents',
				'optimizelyEndUserId',
				'optimizelySegments',
				'optimizelyBuckets',
				'optimizelyPPID',
				'eucookielaw',
				'everest_g_v2',
				'everest_session_v2',
				'ev_sync_dd',
				'actppresence',
				'sb',
				'csm',
				'c_user',
				'frstxs',
				'datr',
				'_fbp',
				'fca_eoi_pagecount',
				'ff_news_session',
				'fs_uid',
				'fusionredux_current_tab',
				'fusion_metabox_tab_10',
				'fusion_metabox_tab_744',
				'fusion_metabox_tab_16',
				'fusion_metabox_tab_14',
				'fusion_metabox_tab_2012',
				'fusion_metabox_tab_8',
				'gig_hasGmid',
				'google_experiment_mod',
				'hid',
				'has_js',
				'tk',
				'uic',
				'ect',
				'udc',
				'dis',
				'udi',
				'hide_eye_catcher',
				'autoinvite_callback',
				'__lc.visitor_id',
				'hide_eye_catcher',
				'autoinvite_callback',
				'__lc.visitor_id',
				'lc_invitation_opened',
				'lc_window_state',
				'lc_sso9818825',
				'__lc_visitor_id',
				'hppsession',
				'hubspotutk',
				'__hssrc',
				'__hssc',
				'__hstc',
				'hs-messages-is-open',
				'__hs_opt_out',
				'messagesUtk',
				'ibx_wpfomo_ip',
				'idIDE',
				'test_cookie',
				'id',
				'___gads',
				'_drt_',
				'DSID',
				'IDE',
				'ar_v4',
				'incap_ses_xxxxxx',
				'visid_incap_xxxxxx',
				'intercom-id-xxxxxx',
				'intercom-state',
				'intercom-id-xxxxxx',
				'itsec-hb-login-partial_',
				'liveagent_oref',
				'liveagent_sid',
				'liveagent_vc',
				'liveagent_ptid',
				'logglytrackingsession',
				'mailmunch_second_pageview',
				'maxmegamenu.themeeditor',
				'metrics_token',
				'mixpanel',
				'moove_gdpr_popup',
				'PHPSESSID',
				'pll_language',
				'pmpro_visit',
				'po_assigned_roles',
				'po_assigned_roles',
				'NID',
				'id',
				'_drt_',
				'HSID',
				'SSIDAPISID',
				'SAPISID',
				'__ut',
				'OGPC',
				'SID',
				'HSID',
				'SSID',
				'APISID',
				'SNID',
				'CONSENT',
				'_gat_gtag_UA_xxxxxx',
				'privacy_embeds',
				'pum-partial_',
				'pvc_visits',
				'qca',
				'qtrans_front_language',
				'qtrans_admin_language',
				'qtrans_edit_language',
				'rank-math-option-search-index',
				'rank-math-option-search-premium',
				'rank-math-option-sitemap-index',
				'rank-math-option-general-index',
				'rank-math-option-titles-index',
				'redux_current_tab',
				'redux_current_tab_get ',
				'SL_C_xxxxxx_SID',
				'SL_C_xxxxxx_KEY',
				'SL_C_xxxxxx_SID',
				'SL_C_xxxxxx_VID',
				'AWSELB',
				'Snoobisession_adcalls_nl',
				'SnoobiID',
				'Snoop_testi',
				'ssupp.visits',
				'ssupp.chatid',
				'ssupp_vid',
				'ssupp_visits',
				'ssupp_chatid',
				'tcb_google_fonts',
				'tidio_state-xxxx',
				'tk_lr',
				'tk_or',
				'tk_r3d',
				'tk_tc',
				'tk_qs',
				'tk_ai',
				'jetpackState',
				'tl_conversion',
				'tl-conv-partial_',
				'ucp_tabs',
				'uncodeAI_css',
				'uncodeAI_images',
				'uncodeAI_screen',
				'uncodeAI.css',
				'uncodeAI.images',
				'uncodeAI.screen',
				'utag_main',
				'uuid',
				'uuidc',
				'HRL8',
				'vchideactivationmsg_vc11',
				'viewed_cookie_policy',
				'vuid',
				'__utma',
				'__utmt_player',
				'__utmz',
				'__utmc',
				'__utmb',
				'wc_cart_hash_xxxxxx',
				'wpwoocommerce_session_xxxxxx',
				'woocommerce_recently_viewed',
				'woocommerce_items_in_cart',
				'woocommerce_cart_hash',
				'wp_woocommerce_session_xxxxxx',
				'wfvt_xxxxxx',
				'wordfence_verifiedHuman',
				'wfwaf-authcookie',
				'wfwaf-authcookie-partial_',
				'wf-scan-issue-expanded-1',
				'wistia',
				'woobe_calculator_operation',
				'woobe_calculator_how',
				'wp-donottrack_feed',
				'wp-reset-tabs',
				'wpdiscuz_last_visit',
				'wpe_test_group',
				'wpe-auth',
				'wpgdprc-consent',
				'wpglobus-language-old',
				'wpglobus-language',
				'wpml_browser_redirect_test',
				'_icl_current_language',
				'_icl_visitor_lang_js',
				'_icl_current_admin_language_',
				'wpr-hash',
				'wpr-show-sidebar',
				'X-LI-IDC',
				'xxxxxx_mf',
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

		public $stats_markers
			= array(
				'google-analytics'   => array(
					'google-analytics.com/ga.js',
					'www.google-analytics.com/analytics.js'
				),
				'google-tag-manager' => array(
					'googletagmanager.com/gtag/js',
					'gtm.js'
				),
				'matomo'             => array( 'piwik.js', 'matomo.js' ),
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
