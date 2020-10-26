<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

$this->fields = $this->fields + array(
		'use_country' => array(
			'step'     => 'general',
			'source'   => 'settings',
			'type'     => 'checkbox',
			'label'    => __( "Use geolocation", 'complianz-gdpr' ),
			'comment'  => $this->premium_geo_ip
			              . __( 'If enabled, the cookie warning will not show for countries without a cookie law, and will adjust consent management depending on supported privacy laws',
					'complianz-gdpr' ),
			'table'    => true,
			'disabled' => true,
			'default'  => false,
			//setting this to true will set it always to true, as the get_cookie settings will see an empty value
		),

		'a_b_testing' => array(
			'source'   => 'settings',
			'step'     => 'general',
			'type'     => 'checkbox',
			'label'    => __( "Enable A/B testing", 'complianz-gdpr' ),
			'comment'  => $this->premium_ab_testing
			              . __( 'If enabled, the plugin will track which cookie banner has the best conversion rate.',
					'complianz-gdpr' ),
			'table'    => true,
			'disabled' => true,
			'default'  => false,
			//setting this to true will set it always to true, as the get_cookie settings will see an empty value
		),

		'a_b_testing_duration' => array(
			'source'    => 'settings',
			'step'      => 'general',
			'type'      => 'number',
			'label'     => __( "Duration in days of the A/B testing period",
				'complianz-gdpr' ),
			'table'     => true,
			'disabled'  => true,
			'condition' => array( 'a_b_testing' => true ),
			'default'   => 30,
		),

		'cookie_expiry' => array(
			'source'  => 'settings',
			'step'    => 'general',
			'type'    => 'number',
			'default' => 365,
			'label'   => __( "Cookie banner expiration in days",
				'complianz-gdpr' ),
			'table'   => true,
		),

		'disable_cookie_block' => array(
			'source'  => 'settings',
			'type'    => 'checkbox',
			'table'   => true,
			'label'   => __( "Enable safe mode", 'complianz-gdpr' ),
			'default' => false,
			'comment'    => sprintf( __( 'When safe mode is enabled, all integrations will be disabled temporarily, please read %sthese instructions%s to debug the issue or ask support if needed.',
				'complianz-gdpr' ),
				'<a  target="_blank" href="https://complianz.io/debugging-issues/">', '</a>' )

		),

		'dont_use_placeholders' => array(
			'source'    => 'settings',
			'type'      => 'checkbox',
			'table'     => true,
			'label'     => __( "Disable placeholder insertion",
				'complianz-gdpr' ),
			'default'   => false,
			'comment'      => __( "If you experience styling issues with videos or iFrames you can disable the placeholder insertion, which in some themes can conflict with theme styling.",
				'complianz-gdpr' ),
			'condition' => array(
				'disable_cookie_block' => false,
			),
		),

		'set_cookies_on_root' => array(
			'source'  => 'settings',
			'step'    => 'general',
			'type'    => 'checkbox',
			'default' => false,
			'label'   => '',
			'help'    => '',
			'table'   => true,
			'condition' => array( 'hide_field' => true ),
		),

		'cookie_domain' => array(
			'source'  => 'settings',
			'step'    => 'general',
			'type'    => 'text',
			'default' => false,
			'label'   => '',
			'help'    => '',
			'table'   => true,
			'condition' => array( 'hide_field' => true ),

		),

		'use_document_css' => array(
			'source'  => 'settings',
			'type'    => 'checkbox',
			'label'   => __( "Use document CSS by Complianz", 'complianz-gdpr' ),
			'table'   => true,
			'default' => true,
			'comment'    => __( "Disable to let your theme take over.",
				'complianz-gdpr' ),
		),

		'use_custom_document_css' => array(
			'source'  => 'settings',
			'type'    => 'checkbox',
			'label'   => __( "Add custom document CSS", 'complianz-gdpr' ),
			'table'   => true,
			'default' => false,
			'help'    => __( "Enable if you want to add custom CSS for the documents",
				'complianz-gdpr' ),
		),

		'custom_document_css' => array(
			'source'    => 'settings',
			'type'      => 'css',
			'label'     => __( "Custom document CSS", 'complianz-gdpr' ),
			'default'   => '#cmplz-document h2 {} /* titles in complianz documents */'
				. "\n" . '#cmplz-document .subtitle {} /* subtitles */'
				. "\n" . '#cmplz-document h2.annex{} /* titles in annexes */'
				. "\n" . '#cmplz-document .subtitle.annex{} /* subtitles in annexes */'
				. "\n" . '#cmplz-document, #cmplz-document p, #cmplz-document span, #cmplz-document li {} /* text */'
                . "\n" . '#cmplz-cookies-overview .cmplz-service-header {} /* service header in cookie policy */'
                . "\n" . '#cmplz-cookies-overview .cmplz-service-desc {} /* service description */'
				. "\n" . '#cmplz-document.impressum, #cmplz-document.cookie-statement, #cmplz-document.privacy-statement {} /* styles for impressum */',
			'help'      => __( 'Add your own custom document css here',
				'complianz-gdpr' ),
			'table'     => true,
			'condition' => array( 'use_custom_document_css' => true ),
		),

		'blocked_content_text' => array(
			'source'       => 'settings',
			'type'         => 'text',
			'translatable' => true,
			'table'        => true,
			'label'        => __( "Blocked content text", 'complianz-gdpr' ),
			'default'      => _x( 'Click to accept marketing cookies and enable this content',
				'Accept cookies on blocked content', 'complianz-gdpr' ),
			'comment'         => __( 'The blocked content text appears when for example a Youtube video is embedded.',
				'complianz-gdpr' ),
			'condition'    => array(
				'disable_cookie_block' => false,
			)
		),

		'disable_notifications' => array(
			'step'     => 'general',
			'source'   => 'settings',
			'type'     => 'checkbox',
			'label'    => __( "Disable notifications", 'complianz-gdpr' ),
			'comment'  => __( 'Disable all plus ones and warnings on your dashboard.',
				'complianz-gdpr' ),
			'table'    => true,
			'disabled' => false,
			'default'  => false,
			//setting this to true will set it always to true
		),

		'enable_cookieblocker_ajax' => array(
			'step'     => 'general',
			'source'   => 'settings',
			'type'     => 'checkbox',
			'label'    => __( "Enable cookie blocker for ajax loaded content", 'complianz-gdpr' ),
			'table'    => true,
			'disabled' => false,
			'default'  => false,
		),

		'notification_from_email' => array(
			'source'             => 'settings',
			'type'               => 'email',
			'label'              => __( "Notification sender email address",
				'complianz-gdpr' ),
			'default'            => false,
			'help'               => __( "When emails are sent, you can choose the sender email address here. Please note that it should have this website's domain as sender domain, otherwise the server might block the email from being sent.",
				'complianz-gdpr' ),
			'table'              => true,
			'callback_condition' => array(
				//'regions' => 'us',
				'purpose_personaldata' => 'selling-data-thirdparty',
			),
		),

		'notification_email_subject' => array(
			'source'             => 'settings',
			'type'               => 'text',
			'label'              => __( "Notification email subject",
				'complianz-gdpr' ),
			'default'            => __( 'Your request has been processed',
				'complianz-gdpr' ),
			'table'              => true,
			'callback_condition' => array(
				//'regions' => 'us',
				'purpose_personaldata' => 'selling-data-thirdparty',
			),
		),

		'notification_email_content' => array(
			'source'             => 'settings',
			'type'               => 'wysiwyg',
			'label'              => __( "Notification email content",
				'complianz-gdpr' ),
			'default'            => __( 'Hi {name}', 'complianz-gdpr' )
			                        . "<br><br>"
			                        . __( 'Your request has been processed successfully.',
					'complianz-gdpr' ) . "<br><br>" . _x( 'Regards,',
					'email signature', 'complianz-gdpr' )
			                        . '<br><br>{blogname}',
			'table'              => true,
			'callback_condition' => array(
				//'regions' => 'us',
				'purpose_personaldata' => 'selling-data-thirdparty',
			),
		),

		'export_settings' => array(
			'source'   => 'settings',
			'disabled' => false,
			'type'     => 'button',
			'action'   => 'cmplz_export_settings',
			'post_get' => 'get',
			'label'    => __( "Export settings", 'complianz-gdpr' ),
			'table'    => true,
			'comment'  => __( 'You can use this to export your settings to another site',
				'complianz-gdpr' ),
		),


		'import_settings' => array(
			'source'   => 'settings',
			'disabled' => true,
			'type'     => 'upload',
			'action'   => 'cmplz_import_settings',
			'label'    => __( "Import settings", 'complianz-gdpr' ),
			'table'    => true,
			'comment'  => sprintf( __( 'If you want to import your settings, please check out the %spremium version%s',
				'complianz-gdpr' ),
				'<a target="_blank" href="https://complianz.io">', "</a>" ),
		),

		'restart_tour' => array(
			'source'   => 'settings',
			'type'     => 'button',
			'action'   => 'cmplz_restart_tour',
			'post_get' => 'post',
			'label'    => __( "Restart tour", 'complianz-gdpr' ),
			'table'    => true,
		),

		'reset_settings' => array(
			'warn'     => __( 'Are you sure? This will remove all Complianz data.',
				'complianz-gdpr' ),
			'source'   => 'settings',
			'type'     => 'button',
			'action'   => 'cmplz_reset_settings',
			'post_get' => 'post',
			'label'    => __( "Reset settings", 'complianz-gdpr' ),
			'table'    => true,
			'help'     => __( 'This will reset all settings to defaults. All data in the Complianz plugin will be deleted',
				'complianz-gdpr' ),
		),

		'clear_data_on_uninstall' => array(
			'source'  => 'settings',
			'type'    => 'checkbox',
			'label'   => __( "Clear all data from Complianz on uninstall",
				'complianz-gdpr' ),
			'default' => false,
			'help'    => __( 'Enabling this option will delete all your settings, and the Complianz tables when you deactivate and remove Complianz.',
				'complianz-gdpr' ),
			'table'   => true,
		),
	);
