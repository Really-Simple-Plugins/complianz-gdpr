<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_known_script_tags', 'cmplz_caos_script' );
function cmplz_caos_script( $tags ) {
	$tags[] = array(
		'name' => 'caos-analytics',
		'category' => 'statistics',
		'urls' => array(
			'analytics.js',
			'gtag.js',
			'ga.js',
			'caos-analytics',
			'uploads/caos',
			'caosLocalGa',
			'CaosGtag',
		),
	);

	return $tags;
}

/**
 * We remove some actions to integrate fully
 * */

function cmplz_caos_remove_scripts_others() {
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$banner_loader, 'get_statistics_script' ), 10 );
}

add_action( 'after_setup_theme', 'cmplz_caos_remove_scripts_others' );


/**
 * Hide the stats configuration options when caos is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_caos_filter_fields( $fields ) {
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

add_filter( 'cmplz_fields', 'cmplz_caos_filter_fields', 200 );


add_filter( 'cmplz_default_value', 'cmplz_caos_set_default', 20, 3 );
function cmplz_caos_set_default( $value, $fieldname, $field ) {
	if ( $fieldname === 'compile_statistics' ) {
		return "google-analytics";
	}

	return $value;
}

/**
 * Remove stats
 *
 * */
function cmplz_caos_show_compile_statistics_notice($notices) {
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
		'text'     => cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'CAOS host analytics locally' ),
	];
	if ($found_key){
		$notices[$found_key] = $notice;
	} else {
		$notices[] = $notice;
	}
	return $notices;

}
add_filter( 'cmplz_field_notices', 'cmplz_caos_show_compile_statistics_notice' );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_caos_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	return $warnings;
}

add_filter( 'cmplz_warning_types', 'cmplz_caos_filter_warnings' );
