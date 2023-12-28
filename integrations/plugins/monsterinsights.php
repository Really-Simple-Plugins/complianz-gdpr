<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Set analytics as suggested stats tool in the wizard
 */

function cmplz_monsterinsights_set_default( $value, $fieldname, $field ) {
	if ( $fieldname === 'compile_statistics' ) {
		return "google-analytics";
	}
	return $value;
}
add_filter( 'cmplz_default_value', 'cmplz_monsterinsights_set_default', 20, 3 );

/**
 * Add blocked scripts
 *
 * @param array $tags
 *
 * @return mixed
 */
function cmplz_monsterinsights_script( $tags ) {
	$tags[] = array(
		'name' => 'google-analytics',
		'category' => 'statistics',
		'urls' => array(
			'monsterinsights_scroll_tracking_load',
			'google-analytics-premium/pro/assets/',
			'mi_version',
		),
	);

	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_monsterinsights_script' );

/**
 * Remove stats
 *
 * */
function cmplz_monsterinsights_show_compile_statistics_notice($notices) {
	$text = '';
	if ( cmplz_no_ip_addresses() ) {
		$text .= __( "You have selected you anonymize IP addresses. This setting is now enabled in MonsterInsights.", 'complianz-gdpr' );
	}

	if ( cmplz_statistics_no_sharing_allowed() ) {
		$text .= __( "You have selected you do not share data with third-party networks. Demographics is now disabled in MonsterInsights.", 'complianz-gdpr' );
	}

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
		'text'     => cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'Monsterinsights' )
		              .' '.$text,
	];
	if ($found_key){
		$notices[$found_key] = $notice;
	} else {
		$notices[] = $notice;
	}
	return $notices;

}
add_filter( 'cmplz_field_notices', 'cmplz_monsterinsights_show_compile_statistics_notice' );

/**
 * We remove some actions to integrate fully
 * */
function cmplz_monsterinsights_remove_scripts_others() {
	remove_action( 'wp_head', 'monsterinsights_tracking_script', 6 );
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$banner_loader, 'get_statistics_script' ), 10 );
}
add_action( 'after_setup_theme', 'cmplz_monsterinsights_remove_scripts_others' );

/**
 * Execute the monsterinsights script at the right point
 */
add_action( 'cmplz_before_statistics_script', 'monsterinsights_tracking_script', 10, 1 );


/**
 * Hide the stats configuration options when monsterinsights is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_monsterinsights_filter_fields( $fields ) {
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
add_filter( 'cmplz_fields', 'cmplz_monsterinsights_filter_fields', 200 );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_monsterinsights_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	return $warnings;
}

add_filter( 'cmplz_warning_types', 'cmplz_monsterinsights_filter_warnings' );

/**
 * Make sure Monsterinsights returns true for anonymize IP's when this option is selected in the wizard
 *
 * @param $value
 * @param $key
 * @param $default
 *
 * @return bool
 */
function cmplz_monsterinsights_force_anonymize_ips( $value, $key, $default ) {
	if ( cmplz_no_ip_addresses() ) {
		return true;
	}
	return $value;
}
add_filter( 'monsterinsights_get_option_anonymize_ips', 'cmplz_monsterinsights_force_anonymize_ips', 30, 3 );

/**
 * Make sure Monsterinsights returns false for third party sharing when this option is selected in the wizard
 *
 * @param $value
 * @param $key
 * @param $default
 *
 * @return bool
 */
function cmplz_monsterinsights_force_demographics( $value, $key, $default ) {
	if ( cmplz_statistics_no_sharing_allowed() ) {
		return false;
	}
	return $value;
}
add_filter( 'monsterinsights_get_option_demographics', 'cmplz_monsterinsights_force_demographics', 30, 3 );
