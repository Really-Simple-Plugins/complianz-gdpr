<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_filter( 'cmplz_fields_load_types', 'cmplz_filter_field_types', 10, 1 );
function cmplz_filter_field_types( $fields ) {
	/**
	 * Add dynamic purposes
	 *
	 * */
	if ( cmplz_has_region('us') || cmplz_get_value( 'privacy-statement' ) === 'generated' ) {
		$index = 10;
		foreach ( COMPLIANZ::$config->purposes as $key => $label ) {
			if ( ! empty( COMPLIANZ::$config->details_per_purpose_us ) ) {
				$index += 10;

				$fields = $fields +
			          array(
							$key . '_data_purpose_us' => array(
								'master_label' => __( "Purpose:", 'complianz-gdpr' ) . " " . $label,
								'step' => STEP_COMPANY,
								'order' => $index,
								'section' => 8,
								'source' => 'wizard',
								'type' => 'multicheckbox',
								'loadmore' => 13,
								'default' => '',
								'label' => __( "Specify the types of data you collect", 'complianz-gdpr' ),
								'required' => true,
								'callback_condition' => array(
									'purpose_personaldata' => $key
								),
								'options' => COMPLIANZ::$config->details_per_purpose_us,
							)
					);
			}
		}
	}

	return $fields;
}

function cmplz_filter_fields(  ) {
	if ( cmplz_get_value( 'compile_statistics' )==='matomo' && cmplz_get_value( 'matomo_anonymized' ) === 'yes' ) {
		COMPLIANZ::$config->fields['configuration_by_complianz']['disabled'] = array('no');
		COMPLIANZ::$config->fields['configuration_by_complianz']['default'] = 'yes';
		COMPLIANZ::$config->fields['configuration_by_complianz']['comment'] = __( "With Matomo cookieless tracking, configuration by Complianz is required.", 'complianz-gdpr' );
	}
}
add_filter( 'plugins_loaded', 'cmplz_filter_fields', 20, 1 );
