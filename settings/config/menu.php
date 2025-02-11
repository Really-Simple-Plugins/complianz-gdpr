<?php
defined( 'ABSPATH' ) or die();

function cmplz_menu() {
	if ( ! cmplz_user_can_manage() ) {
		return [];
	}
	$menu_items = [
		[
			"id"             => "dashboard",
			"title"          => __( "Dashboard", 'complianz-gdpr' ),
			'menu_items'     => [],
		],
		[
			"id"             => "wizard",
			"title"          => __( "Wizard", 'complianz-gdpr' ),
			'menu_items'     => [
				[
					'id'       => 'general',
					'group_id' => 'general',
					'title'    => __( 'General', 'complianz-gdpr' ),
					'menu_items' => [
						[
							'id'       => 'visitors',
							'title'    => __( 'Visitors', 'complianz-gdpr' ),
							'intro' => __('The Complianz wizard will guide you through the necessary steps to configure your website for privacy legislation around the world. We designed the wizard to be comprehensible, without making concessions in legal compliance.', 'complianz-gdpr'),
						],
						[
							'id' => 'documents',
							'title' => __('Documents', 'complianz-gdpr' ),
							'intro' => __('Here you can select which legal documents you want to generate with Complianz. You can also use existing legal documents.', 'complianz-gdpr'),
						],
						[
							'id' => 'website-information',
							'title' => __('Website information', 'complianz-gdpr' ),
							'intro' => __('We need some information to be able to generate your documents and configure your consent banner.', 'complianz-gdpr'),
						],
						[
							'id' => 'impressum',
							'title' => __('Imprint', 'complianz-gdpr'),
							'intro' => __('We need some information to be able to generate your Imprint. Not all fields are required.', 'complianz-gdpr'),
							'helpLink' => 'https://complianz.io/impressum-required-information',
						],
						[
							'id' => 'disclaimer',
							'title' => __('Disclaimer', 'complianz-gdpr'),
							'intro' => __('As you have selected the Disclaimer to be generated, please fill out the questions below.', 'complianz-gdpr'),
							'helpLink' => 'https://complianz.io/definition/what-is-a-disclaimer/',
						],
						[
							'id' => 'financial',
							'title' => __("Financial incentives", 'complianz-gdpr'),
							'helpLink' => 'https://complianz.io/',
							'region' => array('us'),
						],
						[
							'id' => 'children',
							'title' => __("Children's Privacy Policy", 'complianz-gdpr'),
							'intro' => __('In one ore more regions your selected, you need to specify if you target children.', 'complianz-gdpr'),
							'helpLink' => 'https://complianz.io/',
							'region' => array('us','uk', 'ca', 'au', 'za', 'br'),
						],
						[
							'id' => 'children-purposes',
							'title' => __('Children: Purposes', 'complianz-gdpr'),
							'intro' => __('In one ore more regions your selected, you need to specify if you target children.', 'complianz-gdpr'),
							'helpLink' => 'https://complianz.io/',
							'region' => array('us', 'au'),
						],
						[
							'id' => 'dpo',
							'title' => __('Data Protection Officer', 'complianz-gdpr'),
							'intro' => '',
							'region' => ['eu', 'uk'],
						],
						[
							'id' => 'purpose',
							'title' => __('Purpose', 'complianz-gdpr'),
							'intro' => '',
						],
						[
							'id' => 'details-per-purpose',
							'title' => __('Details per Purpose', 'complianz-gdpr'),
							'intro' => '',
						],
						[
							'id' => 'sharing-of-data',
							'title' => __('Sharing of Data', 'complianz-gdpr'),
							'region' => array('eu','us', 'uk', 'au', 'za', 'br'),

						],
						[
							'id' => 'security-consent',
							'title' => __('Security & Consent', 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'consent',
					'group_id' => 'consent',
					'title'    => __( 'Consent', 'complianz-gdpr' ),
					'menu_items' => [
						[
							'id' => 'cookie-scan',
							'title' => __('Website Scan', 'complianz-gdpr' ),
							'intro' => __( 'Complianz will scan several pages of your website for first-party cookies and known third-party scripts. The scan will be recurring monthly to keep you up-to-date!', 'complianz-gdpr' ).' '. cmplz_sprintf( __( 'For more information, %sread our 5 tips%s about the site scan.', 'complianz-gdpr'), '<a href="https://complianz.io/cookie-scan-results/" target="_blank">','</a>'),
							'helpLink' => 'https://complianz.io/cookie-scan-results/',
							'save_buttons_required' => false,
						],
						[
							'id' => 'consent-statistics',
							'title' => __('Statistics', 'complianz-gdpr' ),
							'intro' => __('Below you can choose to implement your statistics tooling with Complianz. We will add the needed snippets and control consent at the same time', 'complianz-gdpr' ),
							'helpLink' => 'https://complianz.io/statistics-implementation',
						],
						[
							'id' => 'statistics-configuration',
							'title' => __('Statistics configuration', 'complianz-gdpr' ),
							'intro' => __('If you choose Complianz to handle your statistics implementation, please delete the current implementation.', 'complianz-gdpr' ),
							'helpLink' => 'https://complianz.io/statistics-implementation#configuration',
						],
						[
							'id' => 'services',
							'title' => __('Services', 'complianz-gdpr' ),
						],
						[
							'id' => 'plugins',
							'title' => __('Plugins', 'complianz-gdpr' ),
							'intro' => __('We have detected the below plugins.', 'complianz-gdpr' ).' '.__('We have enabled the integrations and possible placeholders.', 'complianz-gdpr' ).' '.__('To change these settings, please visit the script center.','complianz-gdpr'),
						],
						//we need TCF at least one menu item separated from the option to enabled it (services) otherwise the fields data
						//might not be loaded when we get here.
						[
							'id' => 'tcf',
							'title' => __( 'Advertising', 'complianz-gdpr' ),
							'intro' => __( 'The below questions will help you configure a vendor list of your choosing. Only vendors that adhere to the purposes and special features you configure will be able to serve ads.',
								'complianz-gdpr' ),
							'helpLink' => 'https://complianz.io/tcf/',
						],
						[
							'id' => 'cookie-descriptions',
							'title' => 'Cookiedatabase.org',
							'intro' => __( 'Complianz provides your Cookie Policy with comprehensive cookie descriptions, supplied by cookiedatabase.org.','complianz-gdpr') ." "
							. __('We connect to this open-source database using an external API, which sends the results of the cookiescan (a list of found cookies, used plugins and your domain) to cookiedatabase.org, for the sole purpose of providing you with accurate descriptions and keeping them up-to-date on a regular basis.','complianz-gdpr'),
							'helpLink' => 'https://complianz.io/our-cookiedatabase-a-new-initiative/',
							'save_buttons_required' => false,
						],
					],
				],
				[
					'id'       => 'manage-documents',
					'group_id' => 'manage-documents',
					'title'    => __( 'Documents', 'complianz-gdpr' ),
					'menu_items' => [
						[
							'id' => 'create-documents',
							'title' => __('Documents', 'complianz-gdpr' ),
							'intro' => __( "Generate your documents, then you can add them to your menu directly or do it manually after the wizard is finished.", 'complianz-gdpr' ),
							'helpLink' => 'https://complianz.io/how-to-create-a-menu-in-wordpress/',
						],
						[
							'id' => 'document-menu',
							'title' => __( 'Link to menu', 'complianz-gdpr' ),
							'intro' => __( 'It\'s possible to use region redirect when GEO IP is enabled, and you have multiple policies and statements.','complianz-gdpr' ),
							'helpLink' => 'https://complianz.io/how-to-redirect-your-policies-based-on-region/',
						],
					],
				],
				[
					'id'       => 'finish',
					'group_id' => 'finish',
					'title'    => __( 'Finish', 'complianz-gdpr' ),

				]
			],
		],
		[
			"id"             => "banner",
			"title"          => __( "Consent Banner", 'complianz-gdpr' ),
			'menu_items'     => [
				[
					'id'       => 'banner-general',
					'title'    => __( 'General',  'complianz-gdpr'  ),
					'intro'    => __( 'These are the main options to customize your consent banner. To go even further you can use our documentation on complianz.io for CSS Lessons, or even start from scratch and create your own with just HTML and CSS.',  'complianz-gdpr'  ),
				],
				[
					'id'       => 'appearance',
					'title'    => __( 'Appearance',  'complianz-gdpr'  ),
				],
				[
					'id'       => 'colors',
					'title'    => __( 'Colors',  'complianz-gdpr'  ),
					'groups' => [
						[
							'id' => 'colors-general',
							'title' => __('General', 'complianz-gdpr' ),
						],
						[
							'id' => 'colors-toggles',
							'title' => __('Toggles', 'complianz-gdpr' ),
						],
						[
							'id' => 'colors-buttons',
							'title' => __('Buttons', 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'banner-texts',
					'title'    => __( 'Texts',  'complianz-gdpr'  ),
					'intro'    => __( 'Here you can edit the texts on your banner.',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/social-media-on-a-cookiebanner/',
				],
				[
					'id'       => 'custom-css',
					'title'    => __( 'Custom CSS',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/?s=CSS+Lesson',
				],
			],
		],
		[
			"id"             => "integrations",
			"title"          => __( "Integrations", 'complianz-gdpr' ),
			'menu_items'     => [
				[
					'id'       => 'integrations-services',
					'title'    => __( 'Services',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/integrating-plugins/',
				],
				[
					'id'       => 'integrations-plugins',
					'title'    => __( 'Plugins',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/integrating-plugins/',
				],
				[
					'id'       => 'integrations-script-center',
					'title'    => __( 'Script Center',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/integrating-plugins/',
				],
			],
		],
		[
			"id"             => "settings",
			"title"          => __( "Settings", 'complianz-gdpr' ),
			'menu_items'     => [
				[
					'id'       => 'settings-general',
					'group_id' => 'settings-general',
					'title'    => __( 'General',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'settings-general',
							'title'    => __( 'General',  'complianz-gdpr'  ),
							'intro'    => __( 'Missing any settings? We have moved settings to Tools, available in the menu.',  'complianz-gdpr'  ),
						],
					],
				],
				[
					'id'       => 'settings-cd',
					'group_id' => 'settings-cd',
					'title'    => 'APIs',
					'groups'   => [
						[
							'id'       => 'settings-cd',
							'title'    => 'Cookiedatabase.org',
						],
					],
				],
			],
		],
		[
			"id"             => "tools",
			"title"          => __( "Tools", 'complianz-gdpr' ),
			'menu_items'     => [
				[
					'id'       => 'support',
					'title'    => __( 'Support',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'premiumsupport',
							'title'    => __( 'Support',  'complianz-gdpr'  ),
							'intro'    => __( 'You will be redirected to our support form, with the needed information, automatically.',  'complianz-gdpr'  ) . ' '.
							              cmplz_sprintf(__( 'If you encounter issues, you can also go to the <a href="%s">support</a> form directly.',  'complianz-gdpr'  ), 'https://complianz.io/support'),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "Get premium support with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
						[
							'id'       => 'debugging',
							'title'    => __( 'Debugging',  'complianz-gdpr'  ),
						],
					],
				],
				[
					'id'       => 'data-requests',
					'title'    => __( 'Data Requests',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/data-requests-forms/',
					'groups'   => [
						[
							'id'       => 'datarequest-entries',
							'title'    => __( 'Data Requests',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/responding-to-a-data-request/',
						],
						[
							'id'       => 'settings',
							'title'    => __( 'Settings',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/data-requests-forms/',
						],
					],
				],
				[
					'id'       => 'placeholders',
					'title'    => __( 'Placeholders',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'placeholders-appearance',
							'title'    => __( 'Placeholder Style',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/changing-the-default-social-placeholders/',
						],
						[
							'id'       => 'placeholders-settings',
							'title'    => __( 'Settings',  'complianz-gdpr'  ),
						],
					],
				],
				[
					'id'       => 'tools-documents',
					'title'    => __( 'Documents',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'tools-documents-general',
							'title'    => __( 'General',  'complianz-gdpr'  ),
						],
						[
							'id'       => 'tools-documents-css',
							'title'    => __( 'Document CSS',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/?s=document+css',
						],
					],
				],
				[
					'id'       => 'multisite',
					'title'    => __( 'Multisite options',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/cross-domain-cookie-consent/',
				],
				[
					'id'       => 'processing-agreements',
					'title'    => __( 'Processing Agreements',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/',
					"save_buttons_required" => false,
					'groups'   => [
						[
							'id'       => 'create-processing-agreements',
							'title'    => __( 'Create Processing Agreements',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/do-i-need-a-processing-agreement-with-complianz/',
							'intro'    => __( 'Here you can create and upload processing agreements. These are necessary when you allow other third parties to process your data.',  'complianz-gdpr'  ),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "Create Processing Agreements with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
						[
							'id'       => 'processing-agreements',
							'title'    => __( 'Processing Agreements',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/definition/what-is-a-processing-agreement/',
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "View and manage Processing Agreements with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'data-breach-reports',
					'title'    => __( 'Data Breach Reports',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/',
					"save_buttons_required" => false,
					'groups'   => [
						[
							'id'       => 'create-data-breach-reports',
							'title'    => __( 'Create Data Breach Reports',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/definition/what-is-a-data-breach/',
							'intro'    => __( 'Do you think your data might have been compromised? Did you experience a security incident or are not sure who had access to personal data for a period of time? Create a data breach report below to see what you need to do.',  'complianz-gdpr'  ),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "Create Data Breach Reports with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
						[
							'id'       => 'data-breach-reports',
							'title'    => __( 'Data Breach Reports',  'complianz-gdpr'  ),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "View and manage Data Breach Reports with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'proof-of-consent',
					'title'    => __( 'Proof of Consent',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/definition/what-is-proof-of-consent/',
					"save_buttons_required" => false,
					'groups'   => [
						[
							'id'       => 'create-proof-of-consent',
							'title'    => __( 'General',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/definition/what-is-proof-of-consent/',
						],
						[
							'id'       => 'proof-of-consent',
							'title'    => __( 'Proof of Consent',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/',
						],
					],
				],
				[
					'id'       => 'records-of-consent',
					'title'    => __( 'Records of Consent',  'complianz-gdpr'  ),
					'helpLink' => 'https://complianz.io/records-of-consent/',
					"save_buttons_required" => false,
					'groups'   => [
						[
							'id'       => 'create-records-of-consent',
							'title'    => __( 'General',  'complianz-gdpr'  ),
							'helpLink' => 'https://complianz.io/records-of-consent/',
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "View and manage Records of Consent with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
						[
							'id'       => 'records-of-consent',
							'title'    => __( 'Records of Consent',  'complianz-gdpr'  ),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "View and manage Records of Consent with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'ab-testing',
					'title'    => __( 'Statistics',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'statistics-settings',
							'title'    => __( 'General',  'complianz-gdpr'  ),
							"save_buttons_required" => true,
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
						],
						[
							'id'       => 'statistics-view',
							'title'    => __( 'Statistics',  'complianz-gdpr'  ),
							'premium'      => true,
							'upgrade'     => 'https://complianz.io/pricing',
							'premium_text' => __( "View and manage Records of Consent with %sComplianz GDPR Premium%s", 'complianz-gdpr' ),
						],
					],
				],
				[
					'id'       => 'security',
					'title'    => __( 'Security',  'complianz-gdpr'  ),
					"save_buttons_required" => false,
					'groups'   => [
						[
							'id'       => 'security-install',
							'title'    => __( 'Improve Security',  'complianz-gdpr'  ),
						],
						[
							'id'       => 'security-privacy',
							'title'    => __( 'Privacy Statement',  'complianz-gdpr'  ),
							'intro'    => __( 'Below text is meant for your Privacy Statement, and is created by using Really Simple Security. In Complianz Premium the text will be automatically added to the Privacy Statement.',  'complianz-gdpr'  ),
						],
					],
				],
				[
					'id'       => 'tools-data',
					'group_id' => 'tools-data',
					'title'    => __( 'Data',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'settings-data',
							'title'    => __( 'Data',  'complianz-gdpr'  ),
						],
					],
				],
				[
					'id'       => 'tools-multisite',
					'group_id' => 'tools-multisite',
					'title'    => __( 'Multisite',  'complianz-gdpr'  ),
					'groups'   => [
						[
							'id'       => 'tools-multisite',
							'title'    => __( 'Data',  'complianz-gdpr'  ),
						],
					],
				],
			],
		],
	];

	return apply_filters( 'cmplz_menu', $menu_items );
}
