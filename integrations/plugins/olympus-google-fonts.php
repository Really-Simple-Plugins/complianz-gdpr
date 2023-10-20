<?php
defined( 'ABSPATH' ) or die();
if ( !defined("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE") ) define("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE", true);

function cmplz_ogf_filter_pro_fields($fields) {
	$index = cmplz_get_field_index('self_host_google_fonts', $fields);
	if ($index!==false) {
		$fields[ $index ]['help'] = [
			'label' => 'default',
			'title' => __( 'Self-hosting Google Fonts', 'complianz-gdpr' ),
			'text'  => sprintf( __( "You have %s installed. We recommend saying 'Yes' to self-hosting Google Fonts", "complianz-gdpr" ), "Fonts Plugin | Google Fonts Typography" ),
		];
	}

	return $fields;
}
add_filter('cmplz_fields', 'cmplz_ogf_filter_pro_fields', 200, 1);
