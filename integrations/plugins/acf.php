<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !cmplz_integration_plugin_is_active( 'google-maps-easy' ) &&
     !cmplz_integration_plugin_is_active( 'g1-maps' ) &&
     !cmplz_integration_plugin_is_active( 'generatepress-maps' ) &&
     !cmplz_integration_plugin_is_active( 'map-multi-marker' ) &&
     !cmplz_integration_plugin_is_active( 'wp-google-maps' ) &&
     !cmplz_integration_plugin_is_active( 'avada-maps' ) &&
     !cmplz_integration_plugin_is_active( 'uncode' ) &&
     !cmplz_integration_plugin_is_active( 'wp-google-maps-widget' )
	) {

	add_filter( 'cmplz_known_script_tags', 'cmplz_acf_script' );
}
function cmplz_acf_script( $tags ) {
	if( acf_get_setting('enqueue_google_maps') ) {
		$tags[] = [
			'name' => 'acf-custom-maps',
			'urls' => [
				'google.maps.MapTypeId',
				'maps.googleapis.com/maps/api/js'
			],
			'category' => 'marketing',
			'enable_placeholder' => '1',
			'placeholder' => 'google-maps',
			'placeholder_class' => 'acf-map',
			'enable_dependency' => '1',
			'dependency' => [
				'maps.googleapis.com/maps/api/js' => 'google.maps.MapTypeId'
			],
		];
	}

	return $tags;
}
