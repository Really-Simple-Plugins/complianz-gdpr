<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

add_action( 'cmplz_notice_compile_statistics', 'cmplz_compile_statistics' );
function cmplz_compile_statistics() {

	if ( get_option( 'cmplz_detected_stats_type' )
	     || get_option( 'cmplz_detected_stats_data' )
	) {
		cmplz_notice( __( "This field has been pre-filled based on the scan results.",
			'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_share_data_other', 'cmplz_notice_share_data_other' );
add_action( 'cmplz_notice_share_data_other_us', 'cmplz_notice_share_data_other' );
function cmplz_notice_share_data_other() {
	if ( COMPLIANZ::$cookie_admin->site_shares_data()
	) {
		cmplz_notice( __( "Complianz detected settings that suggest your site shares data, which means the answer should probably be Yes, or Limited", 'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_GTM_code', 'cmplz_notice_stats_non_functional' );
add_action( 'cmplz_notice_UA_code', 'cmplz_notice_stats_non_functional' );
add_action( 'cmplz_notice_matomo_site_id',
	'cmplz_notice_stats_non_functional' );
function cmplz_notice_stats_non_functional() {
	if ( ! cmplz_manual_stats_config_possible() ) {
		cmplz_notice( __( "You have selected options which indicate your statistics tracking needs a cookie banner. To enable Complianz to handle the statistics, you should remove your current statistics tracking, and configure it in Complianz",
			'complianz-gdpr' ), 'warning' );
	} else {
		if ( get_option( 'cmplz_detected_stats_type' )
		     || get_option( 'cmplz_detected_stats_data' )
		) {
			cmplz_notice( __( "This field has been pre-filled based on the scan results.",
					'complianz-gdpr' ) . "&nbsp;"
			              . __( "Please make sure you remove your current implementation to prevent double statistics tracking.",
					'complianz-gdpr' ) );
		} else {
			cmplz_notice( __( 'If you add the ID for your statistics tool here, Complianz will configure your site for statistics tracking.',
				'intro cookie usage', 'complianz-gdpr' ) );
		}
	}
}

add_action( 'cmplz_notice_compile_statistics',
	'cmplz_show_compile_statistics_notice', 10, 1 );
function cmplz_show_compile_statistics_notice( $args ) {
	$stats = cmplz_scan_detected_stats();
	if ( $stats ) {
		$type = reset( $stats );
		$type = COMPLIANZ::$config->stats[ $type ];

		cmplz_notice( sprintf( __( "The cookie scan detected %s on your site, which means the answer to this question should be %s.",
			'complianz-gdpr' ), $type, $type ) );
	}

}

add_action( 'cmplz_notice_consent_for_anonymous_stats',
	'cmplz_notice_consent_for_anonymous_stats', 10, 1 );
function cmplz_notice_consent_for_anonymous_stats( $args ) {
	cmplz_notice( __( "You have configured your statistics tracking privacy-friendly with Google Analytics. Therefore, in most EU countries, asking for consent for placing these particular cookies is generally not required. For some countries, like Germany, asking consent for Google Analytics is always required.",
			'complianz-gdpr' )
	              . cmplz_read_more( 'https://complianz.io/google-analytics' ),
		'warning' );

}


add_action( 'cmplz_notice_uses_social_media',
	'cmplz_uses_social_media_notice' );
function cmplz_uses_social_media_notice() {
	$social_media = cmplz_scan_detected_social_media();
	if ( $social_media ) {
		foreach ( $social_media as $key => $social_medium ) {
			$social_media[ $key ]
				= COMPLIANZ::$config->thirdparty_socialmedia[ $social_medium ];
		}
		$social_media = implode( ', ', $social_media );
		cmplz_notice( sprintf( __( "The scan found social media buttons or widgets for %s on your site, which means the answer should be yes",
			'complianz-gdpr' ), $social_media ) );
	}
}


add_action( 'cmplz_notice_purpose_personaldata', 'cmplz_purpose_personaldata' );
function cmplz_purpose_personaldata() {
	$contact_forms = cmplz_site_uses_contact_forms();
	if ( $contact_forms ) {
		cmplz_notice( __( 'The scan found forms on your site, which means answer should probably include "contact".',
			'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_uses_thirdparty_services',
	'cmplz_uses_thirdparty_services_notice' );
function cmplz_uses_thirdparty_services_notice() {


	$thirdparties = cmplz_scan_detected_thirdparty_services();
	if ( $thirdparties || cmplz_detected_custom_marketing_scripts() ) {
		foreach ( $thirdparties as $key => $thirdparty ) {
			$thirdparties[ $key ]
				= COMPLIANZ::$config->thirdparty_services[ $thirdparty ];
		}
		$thirdparties = implode( ', ', $thirdparties );
		cmplz_notice( sprintf( __( "The scan found third-party services on your website: %s, this means the answer should be yes.",
			'complianz-gdpr' ), $thirdparties ) );
	}
}


add_action( 'cmplz_notice_purpose_personaldata',
	'cmplz_purpose_personaldata_notice' );
function cmplz_purpose_personaldata_notice() {
	if ( cmplz_has_region( 'us' )
	     && COMPLIANZ::$cookie_admin->site_shares_data()
	) {
		cmplz_notice( __( "The cookie scan detected cookies from services that share data with Third Parties. According to the CCPA, your website is considered to sell personal data in terms of the CCPA if it collects and shares with a Third Party any personal data in return for money or services. This includes services like Google Analytics.",
			'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_thirdparty_services_on_site',
	'cmplz_google_fonts_recommendation' );
function cmplz_google_fonts_recommendation() {
	//Divi specific notice
	if (function_exists('et_setup_theme')) {
		cmplz_notice( __( "Your site uses Divi. If you use reCAPTCHA on your site, you may need to disable the reCAPTCHA integration in Complianz. ", 'complianz-gdpr' ).cmplz_read_more( "https://complianz.io/blocking-recaptcha-on-divi/" ) , 'warning');
	}
	if ( ! cmplz_has_region( 'eu' ) ) {
		return;
	}
	cmplz_notice( sprintf( __( "Enabled %s will be blocked on the front-end of your website until the user has given consent (opt-in), or after the user has revoked consent (opt-out). When possible a placeholder is activated. You can also disable or configure the placeholder to your liking.",
			'complianz-gdpr' ), __( "services", "complianz-gdpr" ) )
	              . cmplz_read_more( "https://complianz.io/blocking-recaptcha-manually/" ),
		'warning' );

	// cmplz_notice( sprintf( __( "You can disable placeholders per service/plugin on the %sintegrations settings page%s",
	// 	'complianz-gdpr' ),
	// 	'<a href="' . admin_url( 'admin.php?page=cmplz-script-center' ) . '">',
	// 	'</a>' )
 // );


	$thirdparties = cmplz_get_value( 'thirdparty_services_on_site' );
	if ( $thirdparties ) {
		foreach ( $thirdparties as $thirdparty => $key ) {
			if ( $key != 1 ) {
				continue;
			}
			if ( $thirdparty === 'google-fonts' ) {
				cmplz_notice( sprintf( __( "Your site uses Google Fonts. For best privacy compliance, we recommend to self host Google Fonts. To self host, follow the instructions in %sthis article%s",
					'complianz-gdpr' ),
					'<a target="_blank" href="https://complianz.io/self-hosting-google-fonts-for-wordpress/">',
					'</a>' ) );

			}
		}
	}

}

add_action( 'cmplz_notice_used_cookies', 'cmplz_used_cookies_notice' );
function cmplz_used_cookies_notice() {

	if ( cmplz_uses_only_functional_cookies() ) {
		return;
	}

	//not relevant if cookie blocker is disabled
	if ( cmplz_get_value( 'disable_cookie_block' ) == 1 ) {
		return;
	}

	cmplz_notice( sprintf( __( "Because your site uses third-party cookies, the cookie blocker is now activated. If you experience issues on the front-end of your site due to blocked scripts, you can disable specific services or plugin integrations in the %sintegrations section%s, or you can disable the cookie blocker entirely on the %ssettings page%s",
		'complianz-gdpr' ),
		'<a href="' . admin_url( 'admin.php?page=cmplz-script-center' ) . '">',
		'</a>',
		'<a href="' . admin_url( 'admin.php?page=cmplz-settings' ) . '">',
		'</a>' ), 'warning' );

}

add_action( 'cmplz_notice_use_cdb_api', 'cmplz_use_cdb_api_notice' );
function cmplz_use_cdb_api_notice() {
	cmplz_notice( sprintf( __( "Complianz provides your Cookie Policy with comprehensive cookie descriptions, supplied by %scookiedatabase.org%s. We connect to this open-source database using an external API, which sends the results of the cookiescan (a list of found cookies, used plugins and your domain) to cookiedatabase.org, for the sole purpose of providing you with accurate descriptions and keeping them up-to-date at a weekly schedule. For more information, read the %sPrivacy Statement%s",
		'complianz-gdpr' ),
		'<a target="_blank" href="https://cookiedatabase.org">', '</a>',
		'<a target="_blank" href="https://cookiedatabase.org/privacy-statement">',
		'</a>' ), 'warning' );
}

add_action( 'cmplz_notice_data_disclosed_us', 'cmplz_data_disclosed_us' );
function cmplz_data_disclosed_us() {

	if ( COMPLIANZ::$cookie_admin->site_shares_data() ) {
		cmplz_notice( __( "The cookie scan detected cookies from services which share data with Third Parties. If these cookies were also used in the past 12 months, you should at least select the option 'Internet activity...'",
			'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_data_sold_us', 'cmplz_data_sold_us' );
function cmplz_data_sold_us() {

	if ( COMPLIANZ::$cookie_admin->site_shares_data() ) {
		cmplz_notice( __( "The cookie scan detected cookies from services which share data with Third Parties. If these cookies were also used in the past 12 months, you should at least select the option 'Internet activity...'",
			'complianz-gdpr' ) );
	}

}

add_action( 'cmplz_notice_no_cookies_used', 'cmplz_notice_no_cookies_used' );
function cmplz_notice_no_cookies_used() {

	if ( cmplz_get_value( 'uses_cookies' ) === 'no' ) {
		cmplz_notice( __( "You have indicated your site does not use cookies. If you're sure about this, you can skip this step",
			'complianz-gdpr' ), 'warning' );
	}

}

add_action( 'cmplz_notice_uses_ad_cookies_personalized',
	'cmplz_notice_personalized_ads_based_on_consent' );
function cmplz_notice_personalized_ads_based_on_consent() {
	cmplz_notice( __( "With Tag Manager, you can also configure your (personalized) advertising based on consent.",
			'complianz-gdpr' )
	              . cmplz_read_more( 'https://complianz.io/setting-up-consent-based-advertising/' ) );
}

add_action( 'cmplz_notice_block_recaptcha_service',
	'cmplz_notice_block_recaptcha_service' );
function cmplz_notice_block_recaptcha_service() {
	cmplz_notice( __( "If you choose to block reCAPTCHA, please make sure you add a placeholder to your forms.",
			'complianz-gdpr' )
	              . cmplz_read_more( 'https://complianz.io/blocking-recaptcha-manually/' ) );
}

add_action( 'cmplz_notice_statistics_script',
	'cmplz_notice_statistics_script' );
function cmplz_notice_statistics_script() {

	cmplz_notice( __( 'You have indicated you use a statistics tool which tracks personal data. You can insert this script here so it only fires if the user consents to this.',
		'intro cookie usage', 'complianz-gdpr' ) );

}

/*
 * Suggest answer for the uses cookies question
 *
 * */

add_action( 'cmplz_notice_uses_cookies', 'cmplz_show_cookie_usage_notice' );
function cmplz_show_cookie_usage_notice() {
	$args    = array(
		'isTranslationFrom' => false,
		'ignored'           => false,
	);
	$cookies = COMPLIANZ::$cookie_admin->get_cookies( $args );
	if ( count( $cookies ) > 0 ) {
		$count = count( $cookies );
		cmplz_notice( sprintf( __( "The cookie scan detected %s types of cookies on your site which means the answer to this question should be Yes.",
			'complianz-gdpr' ), $count, $cookies ) );
	} else {
		cmplz_notice( __( "The cookie scan detected no cookies on your site which means the answer to this question can be answered with No.",
			'complianz-gdpr' ) );
	}
}

add_action( 'cmplz_notice_use_categories', 'cmplz_show_use_categories_notice' );
function cmplz_show_use_categories_notice() {
	$tm_fires_scripts = cmplz_get_value( 'fire_scripts_in_tagmanager' )
	                    === 'yes' ? true : false;
	$uses_tagmanager  = cmplz_get_value( 'compile_statistics' )
	                    === 'google-tag-manager' ? true : false;
	if ( $uses_tagmanager && $tm_fires_scripts ) {
		cmplz_notice( __( 'If you want to specify the categories used by Tag Manager, you need to enable categories.',
			'complianz-gdpr' ), 'warning' );

	} elseif ( COMPLIANZ::$cookie_admin->cookie_warning_required_stats( 'eu' ) ) {
		cmplz_notice( __( "Categories are mandatory for your statistics configuration",
				'complianz-gdpr' )
		              . cmplz_read_more( 'https://complianz.io/statistics-as-mandatory-category' ),
			'warning' );
	}
}

add_action( 'cmplz_notice_use_categories_optinstats',
	'cmplz_show_use_categories_optinstats_notice' );
function cmplz_show_use_categories_optinstats_notice() {
	$tm_fires_scripts = cmplz_get_value( 'fire_scripts_in_tagmanager' )
	                    === 'yes' ? true : false;
	$uses_tagmanager  = cmplz_get_value( 'compile_statistics' )
	                    === 'google-tag-manager' ? true : false;
	if ( $uses_tagmanager && $tm_fires_scripts ) {
		cmplz_notice( __( 'If you want to specify the categories used by Tag Manager, you need to enable categories.',
			'complianz-gdpr' ), 'warning' );

	} elseif ( COMPLIANZ::$cookie_admin->cookie_warning_required_stats( 'uk' ) ) {
		cmplz_notice( __( "Categories are mandatory for your statistics configuration",
				'complianz-gdpr' )
		              . cmplz_read_more( 'https://complianz.io/statistics-as-mandatory-category' ),
			'warning' );
	}
}

/**
 * For the cookie page and the US banner we need a link to the privacy statement.
 * In free, and in premium when the privacy statement is not enabled, we choose the WP privacy page. If it is not set, the user needs to create one.
 * */

function cmplz_notice_missing_privacy_page() {
	if (cmplz_has_region('us') || cmplz_has_region('ca') || cmplz_has_region('au')){
		cmplz_notice( __( "It is recommended to select a Privacy Statement.", 'complianz-gdpr' )." ".__("The link to the Privacy Statement is used in the cookie banner and in your Cookie Policy.", 'complianz-gdpr' ) );
	} else {
		cmplz_notice( __( "It is recommended to select a Privacy Statement.", 'complianz-gdpr' )." ".__("The link to the Privacy Statement is used in your Cookie Policy.", 'complianz-gdpr' ) );
	}

}
add_action( 'cmplz_notice_privacy-statement', 'cmplz_notice_missing_privacy_page' );

/**
 * If a plugin places marketing cookie as first party, we can't block it automatically, unless the wp consent api is used.
 * User should be warned, and category marketing is necessary
 * */

function cmplz_notice_firstparty_marketing() {
	if ( cmplz_detected_firstparty_marketing() ) {
		cmplz_notice( __( "You use plugins which place first-party marketing cookies. You can view these plugins on the integrations page. Complianz cannot automatically block first-party marketing cookies unless these plugins conform to the WP Consent API.", 'complianz-gdpr' )
		              . cmplz_read_more( 'https://complianz.io/first-party-marketing-cookies' )
		);
	}
}
add_action( 'cmplz_notice_uses_firstparty_marketing_cookies', 'cmplz_notice_firstparty_marketing' );


add_action( 'cmplz_notice_sensitive_information_processed',
	'cmplz_notice_sensitive_information_processed' );
function cmplz_notice_sensitive_information_processed() {
	if ( cmplz_uses_sensitive_data() ) {
		cmplz_notice( __( "You have selected options that indicate your site processes sensitive, personal data. You should select 'Yes'",
			'complianz-gdpr' ) );
	}
}

add_filter( 'cmplz_default_value', 'cmplz_set_default', 10, 2 );
function cmplz_set_default( $value, $fieldname ) {
	if ( $fieldname == 'compile_statistics' ) {
		$stats = cmplz_scan_detected_stats();
		if ( $stats ) {
			return reset( $stats );
		}
	}

	if ( $fieldname == 'purpose_personaldata' ) {
		if ( cmplz_has_region( 'us' )
		     && COMPLIANZ::$cookie_admin->site_shares_data()
		) {
			//possibly not an array yet, when it's empty
			if ( ! is_array( $value ) ) {
				$value = array();
			}
			$value['selling-data-thirdparty'] = 1;

			return $value;
		}
	}

	/*
	 * When cookies are detected, the user should select yes on this questino
	 *
	 * */

	if ( $fieldname == 'uses_cookies' ) {
		$args         = array(
			'isTranslationFrom' => false,
			'ignored'           => false,
		);
		$cookie_types = COMPLIANZ::$cookie_admin->get_cookies( $args );
		if ( count( $cookie_types ) > 0 ) {
			return 'yes';
		} else {
			return 'no';
		}
	}

	if ( $fieldname == 'sensitive_information_processed' ) {
		if ( cmplz_uses_sensitive_data() ) {
			return 'yes';
		}
	}

	if ( $fieldname == 'purpose_personaldata' ) {
		$contact_forms = cmplz_site_uses_contact_forms();
		if ( $contact_forms ) {
			//possibly not an array yet, when it's empty
			if ( ! is_array( $value ) ) {
				$value = array();
			}
			$value['contact'] = 1;

			return $value;
		}
	}

	if ( $fieldname == 'dpo_or_gdpr' ) {
		if ( ! cmplz_company_located_in_region( 'eu' ) ) {
			return 'gdpr_rep';
		}
	}

	if ( $fieldname == 'dpo_or_uk_gdpr' ) {
		if ( ! cmplz_company_located_in_region( 'uk' ) ) {
			return 'uk_gdpr_rep';
		}
	}

	if ( $fieldname == 'country_company' ) {
		$country_code = substr( get_locale(), 3, 2 );
		if ( isset( COMPLIANZ::$config->countries[ $country_code ] ) ) {
			$value = $country_code;
		}

	}

	if ( $fieldname === 'uses_social_media' ) {
		$social_media = cmplz_scan_detected_social_media();
		if ( $social_media ) {
			return 'yes';
		}
	}

	if ( $fieldname === 'socialmedia_on_site' ) {
		$social_media = cmplz_scan_detected_social_media();
		if ( $social_media ) {
			$current_social_media = array();
			foreach ( $social_media as $key ) {
				$current_social_media[ $key ] = 1;
			}

			return $current_social_media;
		}
	}

	if ( $fieldname === 'uses_thirdparty_services' ) {
		$thirdparty = cmplz_scan_detected_thirdparty_services();
		if ( $thirdparty || cmplz_detected_custom_marketing_scripts()) {
			return 'yes';
		}
	}
	if ( $fieldname === 'thirdparty_services_on_site' ) {
		$thirdparty = cmplz_scan_detected_thirdparty_services();
		if ( $thirdparty ) {
			$current_thirdparty = array();
			foreach ( $thirdparty as $key ) {
				$current_thirdparty[ $key ] = 1;
			}

			return $current_thirdparty;
		}
	}

	if ( $fieldname === 'data_disclosed_us' || $fieldname === 'data_sold_us' ) {
		if ( COMPLIANZ::$cookie_admin->site_shares_data() ) {
			//possibly not an array yet.
			if ( ! is_array( $value ) ) {
				$value = array();
			}
			$value['internet'] = 1;

			return $value;
		}
	}

	if ( $fieldname === 'uses_firstparty_marketing_cookies' ) {
		if ( cmplz_detected_firstparty_marketing() ) {
			return 'yes';
		}
	}

	return $value;
}
