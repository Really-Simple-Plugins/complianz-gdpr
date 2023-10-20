<?php
defined('ABSPATH') or die();

/**
 * @param $field
 * @param $field_id
 *
 * @return mixed
 */
function cmplz_remove_fields($field, $field_id){
	if ( $field_id === 'regions' && cmplz_get_option('use_country') ) {
		$field['type']= 'multicheckbox';
	}

	if ( $field_id === 'configuration_by_complianz' && cmplz_get_option( 'compile_statistics' )==='matomo' && cmplz_get_option( 'matomo_anonymized' ) === 'yes' ) {
		$field['disabled'] = array('no');
		unset($field['premium']); //this could override the disabled state
		$field['default'] = 'yes';
		$field['comment'] = __( "With Matomo cookieless tracking, configuration by Complianz is required.", 'complianz-gdpr' );
	}

	return $field;
}
add_filter('cmplz_field', 'cmplz_remove_fields', 10, 2);





