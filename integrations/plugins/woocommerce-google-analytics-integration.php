<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Make sure it's set as not anonymous when tracking enabled
 * @param bool $stats_category_required
 */
function cmplz_wc_google_analytics_integration_set_statistics_required( $stats_category_required ){
	$settings = get_option('woocommerce_google_analytics_settings');
	if ( $settings && isset( $settings['ga_support_display_advertising']) && $settings['ga_support_display_advertising'] === 'yes' ) {
		$stats_category_required = true;
	}
	return $stats_category_required;
}
add_filter('cmplz_cookie_warning_required_stats', 'cmplz_wc_google_analytics_integration_set_statistics_required');

/**
 * Set analytics as suggested stats tool in the wizard
 */

add_filter( 'cmplz_default_value', 'cmplz_wc_google_analytics_integration_set_default', 20, 2 );
function cmplz_wc_google_analytics_integration_set_default( $value, $fieldname ) {
	if ( $fieldname == 'compile_statistics' ) {
		return "google-analytics";
	}
	return $value;
}
/**
 * If display ads is enabled, ensure a marketing category is added to the banner
 * @param bool $uses_marketing_cookies
 *
 * @return bool
 */
function cmplz_wc_google_analytics_integration_uses_marketing_cookies( $uses_marketing_cookies ) {
	$settings = get_option('woocommerce_google_analytics_settings');
	if ( $settings && isset( $settings['ga_support_display_advertising']) && $settings['ga_support_display_advertising'] === 'yes' ) {
		$uses_marketing_cookies = true;
	}

	return $uses_marketing_cookies;
}
add_filter( 'cmplz_uses_marketing_cookies', 'cmplz_wc_google_analytics_integration_uses_marketing_cookies', 20, 2 );

add_filter( 'cmplz_known_script_tags', 'cmplz_wc_google_analytics_integration_script' );
function cmplz_wc_google_analytics_integration_script( $tags ) {
	$tags[] = array(
		'name' => 'google-analytics',
		'category' => 'statistics',
		'urls' => array(
			'add_to_cart_button:not(.product_type_variable',
			"ga( 'send', 'pageview' )",
			'_gaq.push',
			'stats.g.doubleclick.net/dc.js',
			'gaProperty',
			'ga_orders',
		),
	);
	return $tags;
}

/**
 * Remove stuff which is not necessary anymore
 *
 * */

function cmplz_wc_google_analytics_integration_remove_actions() {
	remove_action( 'cmplz_notice_compile_statistics', 'cmplz_show_compile_statistics_notice', 10 );
}
add_action( 'admin_init', 'cmplz_wc_google_analytics_integration_remove_actions' );

/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */
function cmplz_wc_google_analytics_integration_show_compile_statistics_notice( $args ) {
	cmplz_sidebar_notice( cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'WooCommerce Google Analytics Integration' ) );
}
add_action( 'cmplz_notice_compile_statistics', 'cmplz_wc_google_analytics_integration_show_compile_statistics_notice', 10, 1 );

/**
 * Hide the stats configuration options when wc_google_analytics_integration is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_integration_filter_fields( $fields ) {
	unset( $fields['configuration_by_complianz'] );
	unset( $fields['UA_code'] );
	unset( $fields['AW_code'] );
	unset( $fields['consent-mode'] );
	unset( $fields['compile_statistics_more_info']['help']);
	return $fields;
}
add_filter( 'cmplz_fields', 'cmplz_wc_google_analytics_integration_filter_fields' );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_integration_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_wc_google_analytics_integration_filter_warnings' );

