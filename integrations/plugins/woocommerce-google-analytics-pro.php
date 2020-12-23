<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );
/**
 * Set analytics as suggested stats tool in the wizard
 */

add_filter( 'cmplz_default_value', 'cmplz_wc_google_analytics_pro_set_default', 20, 2 );
function cmplz_wc_google_analytics_pro_set_default( $value, $fieldname ) {
	if ( $fieldname == 'compile_statistics' ) {
		return "google-analytics";
	}

	return $value;
}

/**
 * Block inline script
 *
 * */

function cmplz_wc_google_analytics_pro_script( $tags ) {
	$tags[] = 'wc_ga_pro';
	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_wc_google_analytics_pro_script' );

/**
 * If "use advertising features" is enabled, block as if it's marketing
 * @param array $classes
 * @param string $match
 *
 * @return array
 */
function cmplz_wc_google_analytics_pro_script_classes($classes, $match){
	$settings = get_option('woocommerce_google_analytics_pro_settings');

	if ( $settings && isset( $settings['enable_displayfeatures']) && $settings['enable_displayfeatures'] === 'yes' && !in_array('cmplz-script', $classes )) {
		$classes[] = 'cmplz-script';
	}

	return $classes;
}
add_filter( 'cmplz_statistics_script_classes', 'cmplz_wc_google_analytics_pro_script_classes'. 10, 2  );

/**
 * Remove stuff which is not necessary anymore
 *
 * */

function cmplz_wc_google_analytics_pro_remove_actions() {
	remove_action( 'cmplz_notice_compile_statistics', 'cmplz_show_compile_statistics_notice', 10 );
}
add_action( 'init', 'cmplz_wc_google_analytics_pro_remove_actions' );

/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */
function cmplz_wc_google_analytics_pro_show_compile_statistics_notice( $args ) {
	cmplz_notice( sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'WooCommerce Google Analytics Pro' ) );
}
add_action( 'cmplz_notice_compile_statistics', 'cmplz_wc_google_analytics_pro_show_compile_statistics_notice', 10, 1 );


/**
 * Hide the stats configuration options when wc_google_analytics_pro is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_pro_filter_fields( $fields ) {
	unset( $fields['configuration_by_complianz'] );
	unset( $fields['UA_code'] );
	return $fields;
}
add_filter( 'cmplz_fields', 'cmplz_wc_google_analytics_pro_filter_fields' );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_wc_google_analytics_pro_filter_warnings( $warnings ) {
	if ( ( $key = array_search( 'ga-needs-configuring', $warnings ) )
	     !== false
	) {
		unset( $warnings[ $key ] );
	}

	return $warnings;
}

add_filter( 'cmplz_warnings', 'cmplz_wc_google_analytics_pro_filter_warnings' );

