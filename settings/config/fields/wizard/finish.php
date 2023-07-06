<?php
defined( 'ABSPATH' ) or die();

add_filter( 'cmplz_fields', 'cmplz_wizard_finish_fields', 1000 );
function cmplz_wizard_finish_fields( $fields ) {

	$fields = array_merge( $fields,
		[
			[
				'id'                      => 'cookie_banner_required',
				'menu_id'                 => 'finish',
				'group_id'                => 'finish',
				'type'                    => 'hidden',
				'default'                 => false,
				'disabled'                => false,
				'label'                   => '',
			],
			[
				'id'                      => 'last-step-feedback',
				'menu_id'                 => 'finish',
				'group_id'                => 'finish',
				'type'                    => 'finish',
				'disabled'                => false,
			],
			[
				'id'                      => 'enable_cookie_banner',
				'menu_id'                 => 'finish',
				'group_id'                => 'finish',
				'type'                    => 'radio',
				'required'                => true,
				'revoke_consent_onchange' => true,
				'disabled'                => false,
				'options'                 => COMPLIANZ::$config->yes_no,
				'default'                 => 'yes',
				'label'                   => __( "Show Consent Banner", 'complianz-gdpr' ),
				'tooltip'                 => __( "If you enable this setting, a consent banner will be enabled, if needed.", 'complianz-gdpr' ),
				'comment'                 => __( "You can always enable and disable the Consent Banner when styling the Consent Banner, under Consent Banner settings.", 'complianz-gdpr' ),
				'react_conditions'        => [
					'relation' => 'AND',
					[
						'cookie_banner_required' => true,
					]
				],
			],
			[
				'id'                      => 'enable_cookie_blocker',
				'menu_id'                 => 'finish',
				'group_id'                => 'finish',
				'type'                    => 'radio',
				'required'                => true,
				'revoke_consent_onchange' => true,
				'disabled'                => false,
				'options'                 => COMPLIANZ::$config->yes_no,
				'default'                 => 'yes',
				'label'                   => __( "Disable Safe Mode", 'complianz-gdpr' ),
				'tooltip'                 => __( "The Cookie Blocker will, among others, block any tracking and third-party scripts configured by the wizard, automatic configuration or our script center.",
					'complianz-gdpr' ),
				'help'                    => [
					'label' => 'warning',
					'title' => __( "Using Safe Mode", 'complianz-gdpr' ),
					'text'  => __( "If the Cookie Blocker causes an issue, you can enable Safe Mode under settings. Disabling Safe Mode will activate the Cookie Blocker.", 'complianz-gdpr' ),
					'url'   => 'https://complianz.io/debugging-issues/',
				],
				'react_conditions'        => [
					'relation' => 'AND',
					[
						'cookie_banner_required' => true,
					]
				],
			],
		]
	);


	return $fields;
}
