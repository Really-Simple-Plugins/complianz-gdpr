<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
/**
 * Conditional notices for fields
 *
 * @return array
 */
function cmplz_field_notices( ): array {
	if ( ! cmplz_user_can_manage() ) {
		return [];
	}

	$notices = [];
	if ( get_option( 'cmplz_detected_stats_type' ) || get_option( 'cmplz_detected_stats_data' ) ) {
		$notices[] = [
			'field_id' => 'compile_statistics',
			'label'    => 'default',
			'title'    => __( "Prefilled field", 'complianz-gdpr' ),
			'text'     => __( "Some answers have been been pre-filled by our system detection. Please check before continuing", 'complianz-gdpr' ).
			              ' '.__( "Please make sure you remove your current implementation to prevent double statistics tracking.", 'complianz-gdpr' ),
		];
	}
	$stats = cmplz_scan_detected_stats();
	if ( $stats ) {
		$type = reset( $stats );
		$type = COMPLIANZ::$config->stats[ $type ];
		$notices[] = [
			'field_id' => 'compile_statistics',
			'label'    => 'default',
			'title'    => __( "Detected statistics", 'complianz-gdpr' ),
			'text'     => cmplz_sprintf( __( "The site scan detected %s on your site, which means the answer to this question should be %s.", 'complianz-gdpr' ), $type, $type ),
		];
	}

	$social_media = cmplz_scan_detected_social_media();
	if ( $social_media ) {
		foreach ( $social_media as $key => $social_medium ) {
			$social_media[ $key ] = COMPLIANZ::$config->thirdparty_socialmedia[ $social_medium ];
		}
		$social_media = implode( ', ', $social_media );
		$notices[] = [
			'field_id' => 'uses_social_media',
			'label'    => 'default',
			'title'    => __( "Detected social media", 'complianz-gdpr' ),
			'text'     => cmplz_sprintf( __( "The scan found social media buttons or widgets for %s on your site, which means the answer should be yes", 'complianz-gdpr' ), $social_media ),
		];
	}

	$contact_forms = cmplz_site_uses_contact_forms();
	if ( $contact_forms ) {
		$notices[] = [
			'field_id' => 'purpose_personaldata',
			'label'    => 'default',
			'title'    => __( "Detected forms", 'complianz-gdpr' ),
			'text'     => __( 'The scan found forms on your site, which means answer should probably include "contact".', 'complianz-gdpr' ),
		];
	}

	$thirdparties = cmplz_scan_detected_thirdparty_services();
	if ( $thirdparties ) {
		foreach ( $thirdparties as $key => $thirdparty ) {
			$thirdparties[ $key ] = COMPLIANZ::$config->thirdparty_services[ $thirdparty ];
		}
		$thirdparties = implode( ', ', $thirdparties );
		$notices[] = [
			'field_id' => 'uses_thirdparty_services',
			'label'    => 'default',
			'title'    => __( "Detected third-party services", 'complianz-gdpr' ),
			'text'     => cmplz_sprintf( __( "The scan found third-party services on your website: %s, this means the answer should be yes.", 'complianz-gdpr' ), $thirdparties ),
		];
	}

	if ( cmplz_has_region('us') && COMPLIANZ::$banner_loader->site_shares_data()
	) {
		$notices[] = [
			'field_id' => 'purpose_personaldata',
			'label'    => 'default',
			'title'    => __( "Selling personal data", 'complianz-gdpr' ),
			'text'     => __( "The site scan detected cookies from services that share data with Third Parties. According to US privacy laws, your website is considered to sell personal data if it collects and shares any personal data in return for money or services. This includes a service like Google Analytics.", 'complianz-gdpr' ),
		];
	}

	if (function_exists('et_setup_theme')) {
		$notices[] = [
			'field_id' => 'thirdparty_services_on_site',
			'label'    => 'warning',
			'title'    => __( "Divi detected", 'complianz-gdpr' ),
			'text'     => __( "Your site uses Divi. If you use reCAPTCHA on your site, you may need to disable the reCAPTCHA integration in Complianz. ", 'complianz-gdpr' ),
			'url'      => 'https://complianz.io/blocking-recaptcha-on-divi/',
		];
	}

	if ( COMPLIANZ::$banner_loader->site_shares_data() ) {
		$notices[] = [
			'field_id' => 'data_disclosed_us',
			'label'    => 'default',
			'title'    => __( "Third-party cookies", 'complianz-gdpr' ),
			'text'     => __( "The site scan detected cookies from services which share data with Third Parties. If these cookies were also used in the past 12 months, you should at least select the option 'Internet activity...'",
				'complianz-gdpr' ),
		];
	}
	if ( COMPLIANZ::$banner_loader->uses_google_tagmanager() ) {
		$notices[] = [
			'field_id' => 'category_all',
			'label'    => 'warning',
			'title'    => "Google Tag Manager",
			'text'     => __( "You're using Google Tag Manager. This means you need to configure Tag Manager to use the below categories.", 'complianz-gdpr' ),
			'url' => 'https://complianz.io/definitive-guide-to-tag-manager-and-complianz/',
		];
	}

	if ( COMPLIANZ::$banner_loader->cookie_warning_required_stats( 'eu' ) ) {
		$notices[] = [
			'field_id' => 'use_categories',
			'label'    => 'warning',
			'title'    => __( "Using categories is mandatory", 'complianz-gdpr' ),
			'text'     => __( "Categories are mandatory for your statistics configuration.", 'complianz-gdpr' ),
			'url' => 'https://complianz.io/statistics-as-mandatory-category',
		];
	}
	/**
	 * For the cookie page and the US banner we need a link to the privacy statement.
	 * In free, and in premium when the privacy statement is not enabled, we choose the WP privacy page. If it is not set, the user needs to create one.
	 * */
	if (cmplz_has_region('us') || cmplz_has_region('ca') || cmplz_has_region('au')){
		$notices[] = [
			'field_id' => 'privacy-statement',
			'label'    => 'default',
			'title'    => __( "Privacy Statement", 'complianz-gdpr' ),
			'text'     => __( "It is recommended to select a Privacy Statement.", 'complianz-gdpr' )." ".__("The link to the Privacy Statement is used in the consent banner and in your Cookie Policy.", 'complianz-gdpr' ),
		];
	} else {
		$notices[] = [
			'field_id' => 'privacy-statement',
			'label'    => 'default',
			'title'    => __( "Privacy Statement", 'complianz-gdpr' ),
			'text'     => __( "It is recommended to select a Privacy Statement.", 'complianz-gdpr' )." ".__("The link to the Privacy Statement is used in your Cookie Policy.", 'complianz-gdpr' ) ,
		];
	}

	/**
	 * If a plugin places marketing cookie as first party, we can't block it automatically, unless the wp consent api is used.
	 * User should be warned, and category marketing is necessary
	 * */
	if ( cmplz_detected_firstparty_marketing() ) {
		$notices[] = [
			'field_id' => 'uses_firstparty_marketing_cookies',
			'label'    => 'default',
			'title'    => __( "First-party marketing cookies", 'complianz-gdpr' ),
			'text'     => __( "You use plugins which place first-party marketing cookies. Complianz cannot only block such cookies if the plugin conforms to the WP Consent API, or you have enabled Consent Per Service", 'complianz-gdpr' ),
			'url' => 'https://complianz.io/first-party-marketing-cookies',
			];
	}

	if ( cmplz_uses_sensitive_data() ) {
		$notices[] = [
			'field_id' => 'sensitive_information_processed',
			'label'    => 'default',
			'title'    => __( "Sensitive & personal data", 'complianz-gdpr' ),
			'text'     => __( "You have selected options that indicate your site processes sensitive, personal data. You should select 'Yes'", 'complianz-gdpr' ) ,
		];
	}

	if (  cmplz_get_option( 'cookie-statement' ) !== 'generated' ) {
		$notices[] = [
			'field_id' => 'uses_ad_cookies_personalized',
			'label'    => 'warning',
			'title'    => __( "TCF not possible with custom Cookie Policy", 'complianz-gdpr' ),
			'text'     =>  __( "You have chosen a custom Cookie Policy. The TCF option is disabled as it can only be used in combination with the Cookie Policy generated by Complianz.", 'complianz-gdpr' ),
		];
	}

	if (  cmplz_tcf_active() ) {
		$notices[] = [
			'field_id' => 'cookie-statement',
			'label'    => 'default',
			'title'    => __( "TCF enabled", 'complianz-gdpr' ),
			'text'     =>  __( "You have enabled TCF. This option can only be used in combination with the Cookie Policy generated by Complianz.", 'complianz-gdpr' ),
		];
	}
	return apply_filters('cmplz_field_notices', $notices);
}
