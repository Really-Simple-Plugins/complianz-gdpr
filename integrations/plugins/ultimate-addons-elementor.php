<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( "cmplz_warning_types", 'cmplz_uafe_disabled_warning', 10, 3 );
function cmplz_uafe_disabled_warning( $warnings ){
	$warnings['uafe_disabled'] = array(
			'open' => __('The integration for Ultimate Add ons for Elementor has been deprecated due to incompatibility issues with other Google Maps implementations. If you need this add-on for Google Maps, you can implement an mu-plugin.', 'complianz-gdpr' ) . cmplz_read_more("https://complianz.io/ultimate-addons-for-elementor"),
			'include_in_progress' => false,
	);

	return $warnings;
}
