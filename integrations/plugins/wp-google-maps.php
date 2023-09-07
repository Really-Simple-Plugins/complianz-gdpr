<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

function cmplz_wp_google_maps_whitelist($tags){
	$tags[] = 'WPGMZA_localized_data';
	$tags[] = 'maps.google.com';
	$tags[] = 'maps.googleapis.com';
	$tags[] = 'openstreetmap.org';

	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_wp_google_maps_whitelist');

/**
 * Declare a placeholder
 *
 */
function cmplz_wp_google_maps_placeholder(){}


add_filter( 'cmplz_known_script_tags', 'cmplz_wp_google_maps_script' );
function cmplz_wp_google_maps_script( $tags ) {
	$tags[] = array(
			'name' => 'google-maps',
			'category' => 'marketing',
			'placeholder' => 'google-maps',
			'urls' => array(
					'wp-google-maps/js/',
					'wp-google-maps-pro',
					'wp-google-maps-gold/js/v8',
					'wpgmaps-gold-user.js',
			),
			'enable_placeholder' => '1',
			'enable_dependency' => '1',
			'dependency' => [
				//'wait-for-this-script' => 'script-that-should-wait'
				'wp-google-maps/js/' => 'wp-google-maps-gold/js/v8'
			],
			'placeholder_class' => 'wpgmza_map',
	);
	return $tags;
}

/**
 * Force the Wp Google Maps GDPR option to be disabled, as it's handled by Complianz
 *
 * @param $settings
 *
 * @return mixed
 */
function cmplz_wp_google_maps_settings() {
	if ( cmplz_admin_logged_in() ) {
		$settings = json_decode( get_option( 'wpgmza_global_settings' ) );
		if ( property_exists($settings, 'wpgmza_gdpr_require_consent_before_load') && $settings->wpgmza_gdpr_require_consent_before_load === 'on' ) {
			$settings->wpgmza_gdpr_require_consent_before_load = false;
			update_option( 'wpgmza_global_settings', json_encode( $settings ) );
		}
	}
}
add_action( 'admin_init', 'cmplz_wp_google_maps_settings' );


/**
 * Add cookie that should be set on consent
 *
 * @param $cookies
 *
 * @return mixed
 */


function cmplz_wp_google_maps_add_cookie( $cookies ) {
	$cookies['wpgmza-api-consent-given'] = array( '1', 0 );
	return $cookies;
}
add_filter( 'cmplz_set_cookies_on_consent', 'cmplz_wp_google_maps_add_cookie' );

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_wp_google_maps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_wp_google_maps_detected_services' );
