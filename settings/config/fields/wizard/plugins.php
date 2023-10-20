<?php
defined( 'ABSPATH' ) or die();

add_filter( 'cmplz_fields', 'cmplz_plugins_fields', 100 );
function cmplz_plugins_fields( $fields ) {

	$fields = array_merge( $fields,
		[
			[
				'label' => __('Enabled integrations', "complianz-gdpr"),
				'id'                      => 'plugins_overviews',
				'menu_id'                 => 'plugins',
				'type' => 'plugins_overview',
				'required' => false,
			],
		]
	);

	return $fields;
}
