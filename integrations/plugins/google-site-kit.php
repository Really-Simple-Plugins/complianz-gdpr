<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Conditional notices for fields
 *
 * @param array           $notices
 *
 * @return array
 */
function cmplz_google_site_kit_show_compile_statistics_notice(array $notices): array {
	if ( ! cmplz_user_can_manage() ) {
		return [];
	}

	$notices[] = [
		'field_id' => 'compile_statistics_google_site_kit',
		'label'    => 'warning',
		'url' => 'https://complianz.io/configuring-google-site-kit/',
		'title'    => "Google Site Kit",
		'text'     =>  cmplz_sprintf( __( "Because you're using %s, you can choose which plugin should insert the relevant snippet.", 'complianz-gdpr' ), "Google Site Kit" ),
	];

	return $notices;
}
add_filter( 'cmplz_field_notices', 'cmplz_google_site_kit_show_compile_statistics_notice', 10, 1 );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_google_site_filter_warnings( $warnings ) {
	unset( $warnings[ 'ga-needs-configuring' ] );
	unset( $warnings[ 'gtm-needs-configuring' ] );
	return $warnings;
}
add_filter( 'cmplz_warning_types', 'cmplz_google_site_filter_warnings' );

/**
 * Hide the stats configuration options when gadwp is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_google_site_kit_filter_fields( $fields ) {
	$index = cmplz_get_field_index('compile_statistics_more_info', $fields);
	if ($index!==false) unset($fields[$index]['help']);
	return  cmplz_remove_field( $fields,
		[
			'configuration_by_complianz',
			'ua_code',
			'aw_code',
			'consent-mode',
			'gtm_code',
		]);
}
add_filter( 'cmplz_fields', 'cmplz_google_site_kit_filter_fields', 200, 1 );
