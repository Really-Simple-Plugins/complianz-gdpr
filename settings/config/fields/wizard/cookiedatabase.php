<?php
defined( 'ABSPATH' ) or die();

add_filter( 'cmplz_fields', 'cmplz_wizard_cookiedatabase_fields', 100 );
function cmplz_wizard_cookiedatabase_fields( $fields ) {

	$fields = array_merge( $fields,
		[
			[
				'id'                      => 'cookiedatabase_sync',
				'menu_id'                 => 'cookie-descriptions',
				'label'    => __( "Connect with Cookiedatabase.org", 'complianz-gdpr' ),
				'type' => 'cookiedatabase_sync',
			],
		]
	);


	return $fields;
}
