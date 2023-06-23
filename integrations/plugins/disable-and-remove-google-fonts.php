<?php
defined( 'ABSPATH' ) or die();
if ( !defined("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE") ) define("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE", true);


function cmplz_disable_and_remove_gf_filter_pro_fields($fields) {
	$index = cmplz_get_field_index('self_host_google_fonts');
	$fields[$index]['help'] = [
		'title' => __('self-hosting Google Fonts', 'complianz-gdpr'),
		'text' => sprintf( __("You have %s installed. We recommend saying 'Yes' to self-hosting Google Fonts", "complianz-gdpr") ,"Disable and remove Google Fonts"),
	];

	return $fields;
}
add_filter('cmplz_fields', 'cmplz_disable_and_remove_gf_filter_pro_fields', 10,);
