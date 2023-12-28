<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Set analytics as suggested stats tool in the wizard
 */
add_filter( 'cmplz_default_value', 'cmplz_beehive_set_default', 20, 3 );
function cmplz_beehive_set_default( $value, $fieldname, $field ) {
	if ( $fieldname === 'compile_statistics' ) {
		return "google-analytics";
	}
	return $value;
}

/**
 * We remove some actions to integrate fully
 * */
function cmplz_beehive_remove_scripts_others() {
	remove_action( 'cmplz_statistics_script', array( COMPLIANZ::$banner_loader, 'get_statistics_script' ), 10 );
}
add_action( 'after_setup_theme', 'cmplz_beehive_remove_scripts_others' );

/**
 * Remove stats
 *
 * */
function cmplz_beehive_show_compile_statistics_notice($notices) {
	//find notice with field_id 'compile_statistics' and replace it with our own
	$text = '';
	if ( cmplz_no_ip_addresses() ) {
		$text .= __( "You have selected you anonymize IP addresses. This setting is now enabled in Beehive.",
			'complianz-gdpr' );
	}
	if ( cmplz_statistics_no_sharing_allowed() ) {
		$text .=  __( "You have selected you do not share data with third-party networks. Display advertising is now disabled in Beehive.",
			'complianz-gdpr' );
	}
	$notice = [
		'field_id' => 'compile_statistics',
		'label'    => 'default',
		'title'    => __( "Statistics plugin detected", 'complianz-gdpr' ),
		'text'     => cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Analytics.", 'complianz-gdpr' ), 'Beehive' )
		              .' '.$text,
	];

	$found_key = false;
	foreach ($notices as $key=>$notice) {
		if ($notice['field_id']==='compile_statistics') {
			$found_key = $key;
		}
	}
	if ($found_key){
		$notices[$found_key] = $notice;
	} else {
		$notices[] = $notice;
	}
	return $notices;

}
add_filter( 'cmplz_field_notices', 'cmplz_beehive_show_compile_statistics_notice' );

add_filter( 'beehive_get_options', 'cmplz_beehive_options', 10, 2 );
function cmplz_beehive_options( $options, $network ) {
	//handle anonymization
	if ( cmplz_no_ip_addresses() ) {
		$options['general']['anonymize'] = true;
	}

	//handle sharing of data
	if ( cmplz_statistics_no_sharing_allowed() ) {
		$options['general']['advertising'] = false;
	}

	return $options;
}

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_beehive_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_beehive_filter_warnings' );

/**
 * Hide the stats configuration options when Beehive is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_beehive_filter_fields( $fields ) {
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

add_filter( 'cmplz_fields', 'cmplz_beehive_filter_fields', 200, 1 );
