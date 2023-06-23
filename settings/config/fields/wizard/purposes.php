<?php
defined( 'ABSPATH' ) or die();

/**
 * For saving purposes, types should be overridden at the earliest moment
 *
 * @param array $fields
 *
 * @return array
 */
function cmplz_add_data_per_purpose( array $fields ): array {

	$index = 10;
	if ( ! empty( COMPLIANZ::$config->details_per_purpose_us ) ) {
		foreach ( COMPLIANZ::$config->purposes as $key => $label ) {
			$index += 10;
			$fields[] = [
				'id'               => $key . '_data_purpose_us',
				'parent_label'     => $label,
				'menu_id'          => 'details-per-purpose',
				'order'            => $index + 3,
				'loadmore'         => 13,
				'type'             => 'multicheckbox',
				'default'          => '',
				'required'         => true,
				'label'              => __( "Specify the types of data you collect", 'complianz-gdpr' ),
				'options'            => COMPLIANZ::$config->details_per_purpose_us,
				'react_conditions' => [
					'relation' => 'AND',
					[
						'purpose_personaldata' => $key,
						'relation' => 'OR',
						[
							'privacy-statement'    => 'generated',
							'regions' => ['us'],
						]
					]
				],
			];

		}
	}

	return $fields;
}

add_filter( 'cmplz_fields', 'cmplz_add_data_per_purpose', 25, 1 );
