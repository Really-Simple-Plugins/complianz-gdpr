<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

/*
 * condition: if a question should be dynamically shown or hidden, depending on another answer. Use NOT answer to hide if not answer.
 * callback_condition: if should be shown or hidden based on an answer in another screen.
 * callback roept action cmplz_$page_$callback aan
 * required: verplicht veld.
 * help: helptext die achter het veld getoond wordt.


                "fieldname" => '',
                "type" => 'text',
                "required" => false,
                'default' => '',
                'label' => '',
                'table' => false,
                'callback_condition' => false,
                'condition' => false,
                'callback' => false,
                'placeholder' => '',
                'optional' => false,

* */

// MY COMPANY SECTION
$this->fields = $this->fields + array(

		'regions'                     => array(
			'step'     => STEP_COMPANY,
			'section'  => 1,
			'source'   => 'wizard',
			'default'  => '',
			'type'     => 'radio',
			'revoke_consent_onchange' => true,

			'options'  => $this->supported_regions,
			'label'    => __( "Which privacy law or guideline do you want to use as the default for your worldwide visitors?", 'complianz-gdpr' ),
			'help'     => __( "You don’t need to configure your website for ‘accidental’ visitors. Only choose the regions your website is intended for.", 'complianz-gdpr')
											. cmplz_read_more( 'https://complianz.io/what-regions-do-i-target/' ),

			'comment'  => __( "The plugin will apply the above-selected region's settings to all visitors worldwide.",
					'complianz-gdpr' ) . " "
			              . sprintf( __( "If you want to dynamically apply privacy laws based on the visitor's location, consider upgrading to the %spremium version%s, which allows you to apply a privacy law specific for that region.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io" target="_blank">', '</a>' ),
			'required' => true,
		),

		'other_region_behaviour' => array(
			'step'     => STEP_COMPANY,
			'section'  => 1,
			'source'   => 'wizard',
			'default'  => 'none',
			'type'     => 'select',
			'options'  => array('none'=> __("None", 'complianz-gdpr')) + $this->supported_regions,
			'label'    => __( "Which banner do you want to display in other regions?", 'complianz-gdpr' ),
			'callback_condition' => 'cmplz_geoip_enabled'
		),

		'eu_consent_regions' => array(
			'step'      => STEP_COMPANY,
			'section'   => 1,
			'source'    => 'wizard',
			'default'   => 'no',
			'type'      => 'radio',
			'options'   => $this->yes_no,
			'condition' => array( 'regions' => 'eu' ),
			'label'     => __( "Do you target visitors from Germany, Austria, Belgium and/or Spain?", 'complianz-gdpr' ),
			'required'  => true,
		),

		'california' => array(
			'step'      => STEP_COMPANY,
			'section'   => 1,
			'source'    => 'wizard',
			'default'   => 'yes',
			'type'      => 'radio',
			'options'   => $this->yes_no,
			'condition' => array( 'regions' => 'us' ),
			'label'     => __( "Do you target visitors from California?",
				'complianz-gdpr' ),
			'tooltip'      => __( "There are some rules which only apply to California.",
				'complianz-gdpr' ),
			'required'  => true,
		),

		'cookie-statement' => array(
			'step'     => STEP_COMPANY,
			'section'  => 2,
			'source'   => 'wizard',
			'default'  => 'generated',
			'type'     => 'document',
			'label'    => __( "Cookie Policy", 'complianz-gdpr' ),
			'required' => true,
			'tooltip'     => __( 'A Cookie Policy is required to inform your visitors about the way cookies and similar techniques are used on your website. A link to this document is placed on your cookie banner.',
				"complianz-gdpr" ),
		),

		'impressum' => array(
			'step'     => STEP_COMPANY,
			'section'  => 2,
			'disabled' => array('generated'),
			'source'   => 'wizard',
			'default'  => 'none',
			'type'     => 'document',
			'label'    => __( "Impressum", 'complianz-gdpr' ),
			'required' => true,
			'tooltip'  => __( 'Complianz will generate the Impressum based on the answers in the wizard, but you can also create your own, custom document.',
				"complianz-gdpr" ),
			'callback_condition' => array('eu_consent_regions' => 'yes'),
		),

		'privacy-statement' => array(
			'step'     => STEP_COMPANY,
			'section'  => 2,
			'disabled' => array('generated'),
			'source'   => 'wizard',
			'type'     => 'document',
			'default'  => 'custom',
			'label'    => __( "Privacy Statement", 'complianz-gdpr' ),
			'options'  => $this->yes_no,
			'required' => false,
		),

		'disclaimer' => array(
			'step'     => STEP_COMPANY,
			'section'  => 2,
			'source'   => 'wizard',
			'default'  => 'none',
			'disabled' => array('generated'),
			'type'     => 'document',
			'options'  => $this->yes_no,
			'label'    => __( "Disclaimer",
				'complianz-gdpr' ),
			'required' => false,
		),

		'organisation_name' => array(
			'step'     => STEP_COMPANY,
			'section'  => 3,
			'source'   => 'wizard',
			'type'     => 'text',
			'default'  => '',
			'placeholder'  => __( "Name or company name", 'complianz-gdpr' ),
			'label'    => __( "Who is the owner of the website?", 'complianz-gdpr' ),
			'required' => true,
		),

		'address_company' => array(
			'step'        => STEP_COMPANY,
			'section'     => 3,
			'source'      => 'wizard',
			'placeholder' => __( 'Address, City and Zipcode',
				'complianz-gdpr' ),
			'type'        => 'textarea',
			'default'     => '',
			'label'       => __( "What is your address?", 'complianz-gdpr' ),
			'required'    => true,
		),

		'country_company'   => array(
			'step'     => STEP_COMPANY,
			'section'  => 3,
			'source'   => 'wizard',
			'type'     => 'select',
			'options'  => $this->countries,
			'default'  => 'NL',
			'label'    => __( "What is your country?", 'complianz-gdpr' ),
			'required' => true,
			'tooltip'     => __( "This setting is automatically selected based on your WordPress language setting.",
				'complianz-gdpr' ),
		),

		'email_company'     => array(
			'step'     => STEP_COMPANY,
			'section'  => 3,
			'source'   => 'wizard',
			'type'     => 'email',
			'default'  => '',
			'tooltip'     => __( "The email address will be obfuscated on the front-end to prevent spidering.",
				'complianz-gdpr' ),
			'label'    => __( "What is the email address your visitors can use to contact you about privacy issues?",
				'complianz-gdpr' ),
			'required' => true,
		),

		'telephone_company' => array(
			'step'           => STEP_COMPANY,
			'section'        => 3,
			'source'         => 'wizard',
			'type'           => 'phone',
			'default'        => '',
			'document_label' => __( 'Phone number', 'complianz-gdpr' ) . ': ',
			'label'          => __( "What is the telephone number your visitors can use to contact you about privacy issues?",
				'complianz-gdpr' ),
			'required'       => false,
		),

		'wp_admin_access_users' => array(
			'step'     => STEP_COMPANY,
			'section'  => 3,
			'source'   => 'wizard',
			'type'     => 'radio',
			'default'  => 'no',
			'label'    => __( "Does your site have users with access to the wp-admin part of the website?", 'complianz-gdpr' ),
			'tooltip'     => __( "If so, the scan will be extended to the wp-admin part of your site. ", 'complianz-gdpr' ),
			'required' => false,
			'options'  => $this->yes_no,
		),

		// Purpose
		'purpose_personaldata' => array(
			'step'               => STEP_COMPANY,
			'section'            => 6,
			'source'             => 'wizard',
			'type'               => 'multicheckbox',
			'default'            => '',
			'label'              => __( "Indicate for what purpose personal data is processed via your website:", 'complianz-gdpr' ),
			'required'           => true,
			'options'            => $this->purposes,
			'callback_condition' => array(
				'regions' => array( 'us' )
			),
		),

		'records_of_consent' => array(
			'source'  => 'wizard',
			'label'   => __( "Extend Proof of Consent with Records of Consent (Premium)", 'complianz-gdpr' ),
			'step'    => STEP_COMPANY,
			'section' => 11,
			'type'    => 'radio',
			'options' => $this->yes_no,
			'default' => 'no',
			'disabled' => array('yes'),
			'comment'  => __( "Enabling this option will extend our Proof of Consent method with user consent registration.", 'complianz-gdpr' ).cmplz_read_more( 'https://complianz.io/records-of-consent' ),
		),

		'respect_dnt' => array(
			'step' => STEP_COMPANY,
			'section' => 11,
			'source' => 'wizard',
			'disabled' => true,
			'type' => 'select',
			'options' => $this->yes_no,
			'default' => 'no',
			'label' => __("Respect Do Not Track and Global Privacy Control with Premium", 'complianz-gdpr'),
			'tooltip' => __('If you enable this option, Complianz will not show the cookie banner to users that enabled a ‘Do Not Track’ or \'Global Privacy Control\' setting in their browsers and their default consent status is set to ‘denied’.','complianz-gdpr'),
		),

		'sensitive_information_processed' => array(
			'step' => STEP_COMPANY,
			'section' => 11,
			'source' => 'wizard',
			'type' => 'radio',
			'required' => false,
			'default' => '',
			'options' => $this->yes_no,
			'label' => __("Does your website contain or process sensitive (personal) information?", 'complianz-gdpr'),
			'callback_condition' => array(
				'regions' => array('ca', 'au'),
			),
			'tooltip' => __('Sensitive personal information is considered to data that is very likely to have a greater impact on Privacy. For example medical, religious or legal information.', 'complianz-gdpr'),
		),
	);

$this->fields = $this->fields + array(
		'use_cdb_api' => array(
			'step'      => STEP_COOKIES,
			'section'   => 1,
			'source'    => 'wizard',
			'type'      => 'radio',
			'required'  => true,
			'default'   => '',
			'options'   => $this->yes_no,
			'label'     => __( "Do you consent to the use of the cookiedatabase.org API?", 'complianz-gdpr' ),
			'comment'   => __( "Without the API, you will have to manually describe all found cookies, their purpose, function, service and service types. ",
				'complianz-gdpr' )
		. sprintf( __( "Complianz provides your Cookie Policy with comprehensive cookie descriptions, supplied by %scookiedatabase.org%s. We connect to this open-source database using an external API, which sends the results of the cookiescan (a list of found cookies, used plugins and your domain) to cookiedatabase.org, for the sole purpose of providing you with accurate descriptions and keeping them up-to-date at a weekly schedule. For more information, read the %sPrivacy Statement%s",
					'complianz-gdpr' ),
					'<a target="_blank" href="https://cookiedatabase.org">', '</a>',
					'<a target="_blank" href="https://cookiedatabase.org/privacy-statement">',
					'</a>' ),
		),

		'use_cdb_links' => array(
			'step'      => STEP_COOKIES,
			'section'   => 1,
			'source'    => 'wizard',
			'type'      => 'radio',
			'required'  => false,
			'default'   => '',
			'options'   => $this->yes_no,
			'condition' => array( 'use_cdb_api' => 'yes' ),
			'label'     => __( "Do you want to hyperlink cookie names so visitors can find more information on Cookiedatabase.org?", 'complianz-gdpr' ),
			'tooltip'   => __("These links will be added with HTML attributes so it won't hurt SEO.", "complianz-gdpr"),
		),

		'cookie_scan' => array(
			'step'     => STEP_COOKIES,
			'section'  => 1,
			'source'   => 'wizard',
			'type'     => 'radio',
			'options'  => $this->yes_no,
			'label'    => '',
			'callback' => 'cookie_scan',
			'help'     => __( "If you want to clear all cookies from the plugin, you can do so here. You'll need to run a scan again afterwards. If you want to start with a clean slate, you might need to clear your browsercache, to make sure all cookies are removed from your browser as well.", "complianz-gdpr" ),
		),

		'compile_statistics' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 2,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'default'                 => '',
			'revoke_consent_onchange' => true,
			'label'                   => __( "Do you compile statistics of this website?",
				'complianz-gdpr' ),
			'options'                 => array(
				'yes-anonymous'      => __( 'Yes, anonymous',
					'complianz-gdpr' ),
				'yes'                => __( 'Yes, and the personal data is available to us',
					'complianz-gdpr' ),
				'google-analytics'   => __( 'Yes, with Google Analytics',
					'complianz-gdpr' ),
				'matomo'             => __( 'Yes, with Matomo',
					'complianz-gdpr' ),
					'clicky'             => __( 'Yes, with Clicky',
						'complianz-gdpr' ),
				'google-tag-manager' => __( 'Yes, with Google Tag Manager',
					'complianz-gdpr' ),
				'no'                 => __( 'No', 'complianz-gdpr' )
			),
		),

		'compile_statistics_more_info' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 2,
			'source'                  => 'wizard',
			'type'                    => 'multicheckbox',
			'revoke_consent_onchange' => true,
			'default'                 => '',
			'label'                   => __( "Regarding the previous question, can you give more information?", 'complianz-gdpr' ),
			'options'                 => array(
				'accepted'             => __( 'I have accepted the Google data processing amendment', 'complianz-gdpr' ),
				'no-sharing'           => __( 'Google is not allowed to use this data for other Google services', 'complianz-gdpr' ),
				'ip-addresses-blocked' => __( 'Let Complianz anonymize IP addresses', 'complianz-gdpr' ),
			),
			'help'                    => __( 'If you do not check to let Complianz anonymize IP addresses, the IP addresses will get collected as soon as the user consents to the category statistics or higher.', 'complianz-gdpr' )
			                             . cmplz_read_more( 'https://complianz.io/how-to-configure-google-analytics-for-gdpr/' ),
			'condition'               => array(
				'compile_statistics' => 'google-analytics',
			),
		),

		'compile_statistics_more_info_tag_manager' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 2,
			'source'                  => 'wizard',
			'type'                    => 'multicheckbox',
			'revoke_consent_onchange' => true,
			'default'                 => '',
			'label'                   => __( "Regarding the previous question, can you give more information?",
				'complianz-gdpr' ),
			'options'                 => array(
				'accepted'             => __( 'I have accepted the Google data processing amendment',
					'complianz-gdpr' ),
				'no-sharing'           => __( 'Google is not allowed to use this data for other Google services',
					'complianz-gdpr' ),
				'ip-addresses-blocked' => __( 'Acquiring IP-addresses is blocked',
					'complianz-gdpr' ),
			),
			'help'                    => __( 'With Tag Manager you can configure the selective firing of cookies in the Tag Manager dashboard.',
					'complianz-gdpr' )
			                             . cmplz_read_more( 'https://complianz.io/how-to-configure-tag-manager-for-gdpr/' ),
			'condition'               => array(
				'compile_statistics' => 'google-tag-manager',
			),
		),

		'fire_scripts_in_tagmanager' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 2,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'default'                 => '',
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'condition'               => array(
				'compile_statistics' => 'google-tag-manager',
			),
			'label'                   => __( "Tag Manager fires scripts which place cookies",
				'complianz-gdpr' ),
			'help'                    => __( 'If you use Tag Manager to fire scripts on your site, Complianz will automatically enable categories.',
				'complianz-gdpr' ),
		),

		'matomo_anonymized' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 2,
			'source'                  => 'wizard',
			'type'                    => 'select',
			'revoke_consent_onchange' => true,
			'default'                 => '',
			'label'                   => __( "Do you anonymize IP addresses in Matomo?",
				'complianz-gdpr' ),
			'options'                 => $this->yes_no,
			'help'                    => __( 'If IP addresses are anonymized, the statistics cookies do not require a separate category on your banner.',
				'complianz-gdpr' ),
			'condition'               => array(
				'compile_statistics' => 'matomo',
			),
		),

		'consent_for_anonymous_stats' => array(
			'step'               => STEP_COOKIES,
			'section'            => 3,
			'source'             => 'wizard',
			'type'               => 'select',
			'default'            => 'yes',
			'label'              => __( "Do you want to ask consent for statistics?", 'complianz-gdpr' ),
			'options'            => $this->yes_no,
			'help'               => __( "In some countries, like Germany, Austria, Belgium or Spain, consent is required for statistics, even if the data is anonymized.", 'complianz-gdpr' ) . cmplz_read_more( 'https://complianz.io/google-analytics' ),
			'callback_condition' => 'cmplz_consent_required_for_anonymous_stats',
		),

		'configuration_by_complianz' => array(
			'step'               => STEP_COOKIES,
			'section'            => 3,
			'source'             => 'wizard',
			'type'               => 'select',
			'default'            => 'yes',
			'label'              => __( "Do you want Complianz to configure your statistics?", 'complianz-gdpr' ),
			'options'            => $this->yes_no,
			'tooltip'               => __( "It's recommended to let Complianz handle the statistics script. This way, the plugin can detect if it needs to be hooked into the cookie consent code or not. But if you have set it up yourself and don't want to change this, you can choose to do so",
				'complianz-gdpr' ),
			'callback_condition' => 'cmplz_manual_stats_config_possible',
		),

		'GTM_code' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 3,
			'source'                  => 'wizard',
			'type'                    => 'text',
			'default'                 => '',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'label'                   => __( "Enter your Google Tag Manager code",
				'complianz-gdpr' ),
			'callback_condition'      => array( 'compile_statistics' => 'google-tag-manager' ),
			'condition'               => array( 'configuration_by_complianz' => 'yes' ),
			'tooltip'                    => __( "For the Google Tag Manager code, log on. Then, you will immediatly see Container codes. The one next to your website name is the code you will need to fill in here, the Container ID.",
				'complianz-gdpr' ),
		),

		'UA_code' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 3,
			'source'                  => 'wizard',
			'type'                    => 'text',
			'default'                 => '',
			'required'                => false,
			'revoke_consent_onchange' => true,
			'label'                   => __( "Enter your tracking-ID", 'complianz-gdpr' ),
			'callback_condition'      => array( 'compile_statistics' => 'google-analytics' ),
			'condition'               => array( 'configuration_by_complianz' => 'yes' ),
			'tooltip'                 => __( "For the Google Analytics tracking-ID, log on and click Admin and copy the Tracking-ID.", 'complianz-gdpr' ),
		),

		'matomo_url' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 3,
			'source'                  => 'wizard',
			'type'                    => 'url',
			'placeholder'             => 'https://domain.com/stats',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'label'                   => __( "Enter the URL of Matomo",
				'complianz-gdpr' ),
			'callback_condition'      => array( 'compile_statistics' => 'matomo' ),
			'condition'               => array( 'configuration_by_complianz' => 'yes' ),
			'help'                    => __( "e.g. https://domain.com/stats",
				'complianz-gdpr' ),
		),

		'matomo_site_id' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 3,
			'source'                  => 'wizard',
			'type'                    => 'number',
			'default'                 => '',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'label'                   => __( "Enter your Matomo site ID", 'complianz-gdpr' ),
			'condition'               => array( 'configuration_by_complianz' => 'yes' ),
			'callback_condition'      => array( 'compile_statistics' => 'matomo' ),
		),

		'clicky_site_id' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 3,
			'source'                  => 'wizard',
			'type'                    => 'number',
			'default'                 => '',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'label'                   => __( "Enter your Clicky site ID", 'complianz-gdpr' ),
			'callback_condition'               => array(
				'compile_statistics' => 'clicky',
			),
			'help'                    => __( "Because Clicky always sets a so-called unique identifier cookie, consent for statistics is always required.", 'complianz-gdpr' ) . cmplz_read_more( 'https://complianz.io/configuring-clicky-for-gdpr/' ),
		),

		'uses_thirdparty_services' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => '',
			'label'                   => __( "Does your website use third-party services?",
				'complianz-gdpr' ),
			'tooltip'                    => __( "e.g. services like Google Fonts, Maps or reCAPTCHA usually place cookies.",
				'complianz-gdpr' ),
		),

		'thirdparty_services_on_site' => array(
			'step'      => STEP_COOKIES,
			'section'   => 4,
			'source'    => 'wizard',
			'type'      => 'multicheckbox',
			'options'   => $this->thirdparty_services,
			'default'   => '',
			'revoke_consent_onchange' => true,
			'condition' => array( 'uses_thirdparty_services' => 'yes' ),
			'label'     => __( "Select the types of third-party services you use on your site.",
				'complianz-gdpr' ),
			'tooltip'      => __( "Checking services here will add the associated cookies to your Cookie Policy, and block the service until consent is given (opt-in), or after consent is revoked (opt-out).",
				'complianz-gdpr' ),
			'comment'   => __( "When possible a placeholder is activated. You can also disable or configure the placeholder to your liking. You can disable services and placeholders under Integrations.",
					'complianz-gdpr' ) .'</br>' .cmplz_read_more( 'https://complianz.io/configuring-hotjar-for-gdpr/', false ),
		),

		'block_recaptcha_service' => array(
			'step'      => STEP_COOKIES,
			'section'   => 4,
			'source'    => 'wizard',
			'type'      => 'radio',
			'options'   => $this->yes_no,
			'default'   => 'no',
			'condition' => array( 'thirdparty_services_on_site' => 'google-recaptcha' ),
			'label'     => __( "Do you want to block reCAPTCHA before consent, and when consent is revoked?",
				'complianz-gdpr' ),
		),

		'block_hubspot_service' => array(
			'step'      => STEP_COOKIES,
			'section'   => 4,
			'source'    => 'wizard',
			'type'      => 'radio',
			'options'   => $this->yes_no,
			'default'   => 'no',
			'condition' => array( 'thirdparty_services_on_site' => 'hubspot' ),
			'label'     => __( "Complianz integrates with the consent module of HubSpot. Did you enable the consent module in your HubSpot account?",
				'complianz-gdpr' ) . cmplz_read_more( 'https://complianz.io/hubspot-integration/' ),

		),

		'hotjar_privacyfriendly' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => '',
			'label'                   => __( "Is Hotjar configured in a privacy-friendly way?",
				'complianz-gdpr' ),
			'help'                    => __( "You can configure Hotjar privacy-friendly, if you do this, no consent is required for Hotjar.",
					'complianz-gdpr' )
			                             . cmplz_read_more( 'https://complianz.io/configuring-hotjar-for-gdpr/' ),
			'condition'               => array( 'thirdparty_services_on_site' => 'hotjar' ),
			'callback_condition'      => array( 'consent_for_anonymous_stats' => 'NOT yes' ),
		),

		'uses_social_media' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => '',
			'label'                   => __( "Does your website contain embedded social media content, like buttons, timelines, videos or pixels?",
				'complianz-gdpr' ),
			'tooltip'                    => __( "Content from social media is mostly embedded through iFrames. These often place third party (tracking) cookies, so must be blocked based on visitor consent. If your website only contains buttons or links to a social media profile on an external page you can answer No.",
				'complianz-gdpr' ),
		),

		'socialmedia_on_site' => array(
			'revoke_consent_onchange' => true,
			'step'      => STEP_COOKIES,
			'section'   => 4,
			'source'    => 'wizard',
			'type'      => 'multicheckbox',
			'options'   => $this->thirdparty_socialmedia,
			'condition' => array( 'uses_social_media' => 'yes' ),
			'default'   => '',
			'label'     => __( "Select which social media are used on the website.",
				'complianz-gdpr' ),
			'tooltip'      => __( "Checking services here will add the associated cookies to your Cookie Policy, and block the service until consent is given (opt-in), or after consent is revoked (opt-out)",
				'complianz-gdpr' ),
		),

		'uses_firstparty_marketing_cookies' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => 'no',
			'label'                   => __( "You have stated that you don't use third-party services. Do you use plugins that might set marketing cookies?", 'complianz-gdpr' ),
			'tooltip'                    => __( "Complianz cannot automatically block first-party marketing cookies unless these plugins conform to the WP Consent API. Look for any possible integrations on our website if you're not sure. When you answer 'No' to this question, the marketing category will be removed.", 'complianz-gdpr' ),
			'condition'               =>array(
				'uses_thirdparty_services' => 'no',
			),
		),

		'uses_ad_cookies' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => '',
			'label'                   => __( "Does your website use cookies for advertising?", 'complianz-gdpr' ),
		),

		'uses_ad_cookies_personalized' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => array(
				'yes' => __("Yes", "complianz-gdpr"),
				'no' => __("No", "complianz-gdpr"),
				'tcf' => __("Enable TCF", "complianz-gdpr").' (premium)',
			),
			'default'                 => 'no',
			'label'                   => __( "Are any of your advertising cookies used to show personalized ads?", 'complianz-gdpr' ),
			'comment'                 => __( "Google recommends an integration with TCF V2.0 to avoid loss of revenue.", 'complianz-gdpr' ).cmplz_read_more("https://complianz.io/tcf-for-wordpress"),
			'help'                    => __( "If you only use Google for advertising, and have activated the option to use only non personalized ads, you can select no here.", 'complianz-gdpr' ),
			'condition'               => array(
				'uses_ad_cookies' => 'yes'
			),
			'disabled' => array(
				'tcf'
			)
		),

		'uses_wordpress_comments' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => '',
			'label'                   => __( "Does your website use WordPress comments?",
				'complianz-gdpr' ),
			'callback_condition'      => array(
				'regions' => array( 'eu', 'uk' ),
				'NOT cmplz_consent_api_active',
			),
		),

		'block_wordpress_comment_cookies' => array(
			'step'                    => STEP_COOKIES,
			'section'                 => 4,
			'source'                  => 'wizard',
			'type'                    => 'radio',
			'required'                => true,
			'revoke_consent_onchange' => true,
			'options'                 => $this->yes_no,
			'default'                 => 'yes',
			'label'                   => __( "Do you want to disable the storage of personal data by the WP comments function and the checkbox?",
				'complianz-gdpr' ),
			'help'                    => __( "If you enable this, WordPress will not store personal data with comments and you won't need a consent checkbox for the comment form. The consent box will not be displayed.",
				'complianz-gdpr' ),
			'condition'               => array(
				'uses_wordpress_comments' => 'yes',
			),
			'callback_condition'      => array(
				'regions' => array( 'eu', 'uk' ),
				'NOT cmplz_consent_api_active',
			),
		),

		'cookiedatabase_sync' => array(
			'step'     => STEP_COOKIES,
			'section'  => 5,
			'source'   => 'wizard',
			'label'    => __( "Connect with Cookiedatabase.org", 'complianz-gdpr' ),
			'callback' => 'cookiedatabase_sync',
		),

		'used_cookies' => array(
			'step'               => STEP_COOKIES,
			'section'            => 5,
			'source'             => 'wizard',
			'translatable'       => true,
			'type'               => 'cookies',
			'default'            => '',
			'label'              => __( "Add the used cookies here",
				'complianz-gdpr' ),
			'time'               => 5,
		),

		'used_services' => array(
			'step'               => STEP_COOKIES,
			'section'            => 6,
			'source'             => 'wizard',
			'translatable'       => true,
			'type'               => 'services',
			'default'            => '',
			'label'              => __( "Add the services to which your cookies belong here",
				'complianz-gdpr' ),
			'time'               => 5,
		),
	);

$this->fields = $this->fields + array(
		'create_pages' => array(
			'step'     => STEP_MENU,
			'section'  => 1,
			'source'   => 'wizard',
			'callback' => 'wizard_add_pages',
			'label'    => '',
		),
	);

$this->fields = $this->fields + array(
		'add_pages_to_menu' => array(
			'step'     => STEP_MENU,
			'section'  => 2,
			'source'   => 'wizard',
			'callback' => 'wizard_add_pages_to_menu',
			'label'    => '',
		),
	);

$this->fields = $this->fields + array(
		'finish_setup' => array(
			'step'     => STEP_FINISH,
			'source'   => 'wizard',
			'callback' => 'wizard_last_step',
			'label'    => '',
		),
	);
