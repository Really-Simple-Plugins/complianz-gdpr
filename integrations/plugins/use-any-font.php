<?php
defined( 'ABSPATH' ) or die();
if ( !defined("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE") ) define("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE", true);

function cmplz_uaf_filter_pro_fields($fields) {
	if ( isset( $fields['consent-mode'] ) ) {
		$fields['self_host_google_fonts']['help'] = sprintf( __("You have %s installed. We recommend saying 'Yes' to self-hosting Google Fonts", "complianz-gdpr") ,"Use Any Font");
	}
	return $fields;
}
add_filter('cmplz_fields', 'cmplz_uaf_filter_pro_fields', 10, 1);
