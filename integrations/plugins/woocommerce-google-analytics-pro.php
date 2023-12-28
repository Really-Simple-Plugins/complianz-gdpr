<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Make sure it's set as not anonymous when tracking enabled
 * @param bool $stats_category_required
 */
function cmplz_wc_google_analytics_pro_set_statistics_required( $stats_category_required ){
	$settings = get_option('woocommerce_google_analytics_pro_settings');
	if ( $settings && isset( $settings['enable_displayfeatures']) && $settings['enable_displayfeatures'] === 'yes' ) {
		$stats_category_required = true;
	}
	return $stats_category_required;
}
add_filter('cmplz_cookie_warning_required_stats', 'cmplz_wc_google_analytics_pro_set_statistics_required');


/**
 * Set analytics as suggested stats tool in the wizard
 */

add_filter( 'cmplz_default_value', 'cmplz_wc_google_analytics_pro_set_default', 20, 3 );
function cmplz_wc_google_analytics_pro_set_default( $value, $fieldname, $field ) {
	if ( $fieldname === 'compile_statistics' ) {
		return "google-analytics";
	}

	return $value;
}

/**
 * If display ads is enabled, ensure a marketing category is added to the banner
 * @param bool $uses_marketing_cookies
 *
 * @return bool|mixed
 */
function cmplz_wc_google_analytics_pro_uses_marketing_cookies( $uses_marketing_cookies ) {
	$settings = get_option('woocommerce_google_analytics_pro_settings');
	if ( $settings && isset( $settings['enable_displayfeatures']) && $settings['enable_displayfeatures'] === 'yes' ) {
		$uses_marketing_cookies = true;
	}

	return $uses_marketing_cookies;
}
add_filter( 'cmplz_uses_marketing_cookies', 'cmplz_wc_google_analytics_pro_uses_marketing_cookies', 20, 2 );

/**
 * Add markers to the statistics markers list
 * @param array $markers
 *
 * @return array
 */

add_filter( 'cmplz_known_script_tags', 'cmplz_wc_google_analytics_pro_stats_markers' );
function cmplz_wc_google_analytics_pro_stats_markers( $tags ) {
	$tags[] = array(
		'name' => 'google-analytics',
		'category' => 'statistics',
		'urls' => array(
			'wc_google_analytics_pro_loaded',
			"ga( 'send', 'pageview' )",
			'_gaq.push',
			'stats.g.doubleclick.net/dc.js',
			'gaProperty',
			'GoogleAnalyticsObject',
			'add_to_cart_button',
			'wc_ga_pro',
			'ga_orders',
		),
	);
	return $tags;
}

/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $notices
 */

function cmplz_wc_google_analytics_pro_show_compile_statistics_notice($notices) {
	//find notice with field_id 'compile_statistics' and replace it with our own
	$found_key = false;
	foreach ($notices as $key=>$notice) {
		if ($notice['field_id']==='compile_statistics') {
			$found_key = $key;
		}
	}
	$notice = [
		'field_id' => 'compile_statistics',
		'label'    => 'default',
		'title'    => __( "Statistics plugin detected", 'complianz-gdpr' ),
		'text'     => cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'WooCommerce Google Analytics Pro' ),
	];
	if ($found_key){
		$notices[$found_key] = $notice;
	} else {
		$notices[] = $notice;
	}
	return $notices;

}
add_filter( 'cmplz_field_notices', 'cmplz_wc_google_analytics_pro_show_compile_statistics_notice' );

/**
 * Hide the stats configuration options when wc_google_analytics_pro is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_pro_filter_fields( $fields ) {
	$index = cmplz_get_field_index('compile_statistics_more_info', $fields);
	if ($index!==false) unset($fields[$index]['help']);
	return  cmplz_remove_field( $fields,
		[
			'configuration_by_complianz',
			'ua_code',
			'aw_code',
			'additional_gtags_stats',
			'additional_gtags_marketing',
			'consent-mode',
			'gtag-basic-consent-mode',
			'cmplz-gtag-urlpassthrough',
			'cmplz-gtag-ads_data_redaction',
			'gtm_code',
			'cmplz-tm-template'
		]);
}
add_filter( 'cmplz_fields', 'cmplz_wc_google_analytics_pro_filter_fields', 200 );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_pro_filter_warnings( $warnings ) {
	unset($warnings['ga-needs-configuring']);
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_wc_google_analytics_pro_filter_warnings' );
