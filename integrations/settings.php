<?php
/**
 * @param array $fields
 *
 * @return array
 */
function cmplz_filter_integrations_field_types( $fields ) {
	$fields = $fields + array(
			'add_script' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'add_script',
				'default'                 => '',
                'label'                   => __('Add a third-party script',"complianz-gdpr"),
			),

            'block_script' => array(
                'source'                  => 'custom-scripts',
                'type'                    => 'block_script',
                'default'                 => '',
                'label'                   => __('Block a script, iframe or plugin',"complianz-gdpr"),
            ),

            'whitelist_script' => array(
                'source'                  => 'custom-scripts',
                'type'                    => 'whitelist_script',
                'default'                 => '',
                'label'                   => __('Whitelist a script, iframe or plugin',"complianz-gdpr"),
            ),
		);
	return $fields;
}
add_filter( 'cmplz_fields_load_types', 'cmplz_filter_integrations_field_types', 10, 1 );




