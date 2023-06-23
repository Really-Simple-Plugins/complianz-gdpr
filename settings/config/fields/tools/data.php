<?php
defined( 'ABSPATH' ) or die();

add_filter( 'cmplz_fields', 'cmplz_tools_data_fields', 100 );
function cmplz_tools_data_fields( $fields ) {
	return array_merge( $fields, [
		[
			'id'          => 'export_settings',
			'menu_id'     => 'tools-data',
			'group_id'    => 'tools-data',
			'disabled'    => false,
			'type'        => 'export',
			'url'         => add_query_arg( array( 'page' => 'complianz', 'action' => 'cmplz_export_settings' ), admin_url( 'admin.php' ) ),
			'button_text' => __( "Export settings", 'complianz-gdpr' ),
			'label'       => __( "Export", 'complianz-gdpr' ),
			'tooltip'     => __( 'You can use this to export your settings to another site', 'complianz-gdpr' ),
		],
		[
			'id'          => 'import_settings',
			'menu_id'     => 'tools-data',
			'group_id'    => 'tools-data',
			'premium' => [
				'comment' => __('You can use this to import your settings from another site', 'complianz-gdpr'),
				'disabled' => false,
			],
			'disabled' => true,
			'type'     => 'import',
			'label'    => __( "Import", 'complianz-gdpr' ),
		],
		[
			'id'          => 'reset_settings',
			'menu_id'     => 'tools-data',
			'group_id'    => 'tools-data',
			'warn'     => __( 'Are you sure? This will remove all Complianz data.', 'complianz-gdpr' ),
			'type'     => 'button',
			'action'   => 'reset_settings',
			'label'    => __( "Reset", 'complianz-gdpr' ),
			'tooltip'     => __( 'This will reset all settings to defaults. All data in the Complianz plugin will be deleted!', 'complianz-gdpr' ),
		],
		[
			'id'       => 'clear_data_on_uninstall',
			'menu_id'  => 'tools-data',
			'group_id' => 'tools-data',
			'type'     => 'checkbox',
			'label'    => __( "Clear all data from Complianz on uninstall", 'complianz-gdpr' ),
			'tooltip'  => __( 'Enabling this option will delete all your settings, and the Complianz tables when you deactivate and remove Complianz.', 'complianz-gdpr' ),
		],
		// Aert Hulsebos
		// [
		// 	'id'       => 'restart_tour',
		// 	'menu_id'  => 'tools-data',
		// 	'group_id' => 'tools-data',
		// 	'url'         => add_query_arg( array( 'page' => 'complianz','tour'=>1), admin_url( 'admin.php' ) ).'#dashboard',
		// 	'type'     => 'button',
		// 	'label'    => __( "Restart plugin tour", 'complianz-gdpr' ),
		// 	'button_text'    => __( "Start", 'complianz-gdpr' ),
		// ],
		// [
		// 	'id'       => 'disable_notifications',
		// 	'menu_id'  => 'tools-data',
		// 	'group_id' => 'tools-data',
		// 	'type'     => 'checkbox',
		// 	'label'    => __( "Disable notifications", 'complianz-gdpr' ),
		// 	'tooltip'  => __( 'Disable all plus ones and warnings on your dashboard.', 'complianz-gdpr' ),
		// 	'disabled' => false,
		// ],
	] );

}
