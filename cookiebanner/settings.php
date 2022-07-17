<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

 function cmplz_banner_color_schemes(){
	$schemes = array(
		'Wordpress' => array(
			'slider_background_color' => '#1e73be',
			'slider_bullet_color' => '#f9f9f9',
			'slider_background_color_inactive' => '#F56E28',
			'accept_all_background_color' => '#1e73be',
			'accept_all_text_color' => '#fff',
			'accept_all_border_color' => '#1e73be',
			'functional_background_color' => '#fff',
			'functional_text_color' => '#1e73be',
			'functional_border_color' => '#ffffff',
			'colorpalette_background_color' => '#fff',
			'colorpalette_text_color' => '#191e23',
			'button_background_color' => '#fff',
			'button_text_color' => '#1e73be',
			'border_color' => '#1e73be',
			'theme' => 'minimal',
		),

		//keep this one
		'tcf' => array(
			'colorpalette_background' => array(
				'color'     => '#ffffff',
				'border'    => '#333333',
			),
			'colorpalette_text' => array(
				'color'       => '#222222',
				'hyperlink'   => '#1E73BE',
			),
			'colorpalette_toggles' => array(
				'background'    => '#61CE71',
				'bullet'        => '#ffffff',
				'inactive'      => '#f8be2e',
			),

			'colorpalette_button_accept' => array(
				'background'    => '#333333',
				'border'        => '#333333',
				'text'          => '#ffffff',
			),

			'colorpalette_button_deny' => array(
				'background'    => '#ffffff',
				'border'        => '#ffffff',
				'text'          => '#333333',
			),
			'colorpalette_button_settings' => array(
				'background'    => '#ffffff',
				'border'        => '#333333',
				'text'          => '#333333',
			),
		),
	);

	return $schemes;
}

function cmplz_get_banner_color_scheme_options(){
 	$schemes = cmplz_banner_color_schemes();
 	$schemes = array_keys($schemes);
 	$options = array();
 	foreach ($schemes as $scheme) {
 		$options[$scheme] = str_replace('-', ' ', ucfirst($scheme));
    }
 	return $options;
}


add_filter('cmplz_fields_load_types', 'cmplz_add_cookiebanner_settings');
function cmplz_add_cookiebanner_settings($fields){

	$fields = $fields + array(

        /* ----- General ----- */
		'title' => array(
			'step'        => 'general',
			'source'      => 'CMPLZ_COOKIEBANNER',
			'type'        => 'text',
			'label'       => __( "Cookie banner title", 'complianz-gdpr' ),
			'placeholder' => __( 'Descriptive title of the cookiebanner' ),
			'tooltip'     => __( 'For internal use only', 'complianz-gdpr' ),
			'help'        => __("You can customize the cookie banner with custom CSS for endless possibilities.","complianz-gdpr") . cmplz_read_more( 'https://complianz.io/docs/customization/' ),
		),

      'revoke' => array(
          'source'       => 'CMPLZ_COOKIEBANNER',
          'step'         => 'general',
          'type'         => 'text',
          'default'      => __( "Manage consent", 'complianz-gdpr' ),
          'placeholder'  => __( "Manage consent", 'complianz-gdpr' ),
          'label'        => __( "Text on the manage consent tab", 'complianz-gdpr' ),
          'tooltip'      => __( 'The tab will show after the visitor interacted with the banner, and can be used to make the cookie banner reappear.', 'complianz-gdpr' ),
//          'comment'      => __("The default will not show on mobile devices for UX optimization.","complianz-gdpr") . cmplz_read_more( 'https://complianz.io/show-settings-button-on-mobile/' ),
      ),

		'manage_consent_options' => array(
          'source'       => 'CMPLZ_COOKIEBANNER',
          'step'         => 'general',
          'type'         => 'select',
          'placeholder'  => __( "Manage consent", 'complianz-gdpr' ),
          'label'        => __( "Manage consent display options", 'complianz-gdpr' ),
          'tooltip'      => __( 'Select how the manage consent text should appear.', 'complianz-gdpr' ),
          'comment'      => __("The default will not show on mobile devices for UX optimization.","complianz-gdpr") . cmplz_read_more( 'https://complianz.io/show-settings-button-on-mobile/' ),
          'options'   => array(
			  'hover-hide-mobile' => __('Hover on Desktop - Hide on Mobile (Default)', 'complianz-gdpr'),
			  'hover-show-mobile' => __('Hover on Desktop - Show on Mobile', 'complianz-gdpr'),
			  'show-everywhere'   => __('Show everywhere', 'complianz-gdpr'),
			  'hide-everywhere'   => __('Hide everywhere', 'complianz-gdpr'),
          ),
          'default'      => 'hover-hide-mobile',
		),

            'disable_cookiebanner' => array(
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'general',
                'type'    => 'checkbox',
                'label'   => __( "Disable cookie banner", 'complianz-gdpr' ),
                'default' => false,
            ),

			'default' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'general',
				'type'               => 'checkbox',
				'label'              => __( "Default cookie banner", 'complianz-gdpr' ),
				'help'               => __( 'When enabled, this is the cookie banner that is used for all visitors. Enabling it will disable this setting on the current default banner. Disabling it will enable randomly a different default banner.',
					'complianz-gdpr' ),

				'default'            => false,
				'callback_condition' => 'cmplz_ab_testing_enabled',
			),

            'hide_preview' => array(
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'general',
                'type'    => 'checkbox',
                'label'   => __( "Hide preview", 'complianz-gdpr' ),
                'default' => false,
            ),

            'use_custom_cookie_css' => array(
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'general',
                'type'    => 'checkbox',
                'label'   => __( "Use Custom CSS", 'complianz-gdpr' ),
                'default' => false,
                'comment'   => __("The custom CSS editor will appear at the bottom of this page when enabled.","complianz-gdpr"),
            ),

			'reset_cookiebanner' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'button',
				'post_get' => '',
				'action'  => 'reset_cookie_banner',
				'label'   => __( "Reset to default values", 'complianz-gdpr' ),
				'help'   => __("If you want to start from the default values, you can use the reset button.","complianz-gdpr").'&nbsp;'.__("Texts will also get reset.","complianz-gdpr"),
				'default' => false,
			),

            /* ----- Appearance ----- */

            'position' => array(
                'step'    => 'appearance',
                'source'  => 'CMPLZ_COOKIEBANNER',
                'type'    => 'select',
                'label'   => __( "Position", 'complianz-gdpr' ),
                'options' => array(
	                'center'       => __( "Center", 'complianz-gdpr' ),
	                'bottom'       => __( "Bottom", 'complianz-gdpr' ),
	                'bottom-left'  => __( "Bottom left", 'complianz-gdpr' ),
	                'bottom-right' => __( "Bottom right", 'complianz-gdpr' ),
                ),
                'default' => 'bottom-right',
            ),

            'animation' => array(
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'appearance',
                'type'    => 'select',
                'label'   => __( "Animation", 'complianz-gdpr' ),
                'options' => array(
                    'none'    => __( "None", 'complianz-gdpr' ),
                    'fade'  => __( "Fade", 'complianz-gdpr' ),
                    'slide' => __( "Slide", 'complianz-gdpr' ),
                    //'expand' => __( "Expand", 'complianz-gdpr' ),
                ),
                'default' => 'none',
            ),

			'banner_width' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'number',
				'default' => '526',
				'minimum'   => '300',
				'maximum'   => '1500',
				'validation_step' => 2,
				'label'   => __( "Width of the banner in pixels", 'complianz-gdpr' ),
				'condition' => array(
					'position' => 'NOT bottom',
				)
			),

			'use_categories' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'appearance',
				'type'               => 'select',
				'options'            => array(
					'view-preferences'	=> __('Accept - Deny - View Preferences', 'complianz-gdpr'),
					'save-preferences' 	=> __('Accept - Deny - Save Preferences', 'complianz-gdpr'),
					'no' 		        => __('Accept - Deny', 'complianz-gdpr'),
				),
				'label'              => __( "Categories", 'complianz-gdpr' ),
				'tooltip'               => __( 'With categories, you can let users choose which category of cookies they want to accept.', 'complianz-gdpr' ) . ' '
					. __( 'Depending on your settings and cookies you use, there can be two or three categories. With Tag Manager you can use more, custom categories.', 'complianz-gdpr' ),
				'help'    => cmplz_cookiebanner_category_conditional_helptext(),
				'default'            => 'view-preferences',
				'condition'          => array('consenttype' => 'optin'),
				'callback_condition' => 'cmplz_uses_optin',
			),

			'checkbox_style' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'select',
				'label'   => __( "Checkbox style", 'complianz-gdpr' ),
				'tooltip' => __( "This style is for the checkboxes on the cookie banner, as well as on your policy for managing consent.", 'complianz-gdpr' ),
				'options' => array(
					'classic'   => __( "Classic", 'complianz-gdpr' ),
					'slider' 	=> __( "Slider", 'complianz-gdpr' ),
				),
				'default' => 'slider',
				'condition' => array(
					'use_categories' => 'NOT no',
				)
			),

			'use_logo' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'select',
				'label'   => __( "Logo", 'complianz-gdpr' ),
				'options' => array(
					'hide'    	=> __( "Hide", 'complianz-gdpr' ),
					'site'  	=> __( "Use Site Logo", 'complianz-gdpr' ),
					'complianz' => __( "Use \"Powered by Complianz\"", 'complianz-gdpr' ),
					'custom' 	=> __( "Upload Custom Logo", 'complianz-gdpr' ).' (2 : 1)',
				),
				'default' => 'hide',
			),

			'use_logo_complianz' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'use_logo_complianz',
				'condition' => array(
					'use_logo' => 'complianz',
				),
				'label'  => __( "Preview", 'complianz-gdpr' ),
			),

			'use_logo_site' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'use_logo_site',
				'tooltip'    => __( "The site logo is the default logo set in your theme's site identity.", 'complianz-gdpr' ),
				'condition' => array(
					'use_logo' => 'site',
				),
				'label'  => __( "Preview", 'complianz-gdpr' ),
			),

			'logo_attachment_id' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'use_logo_custom',
				'tooltip'    => __( "Upload your custom logo for use on the banner.", 'complianz-gdpr' ),
				'condition' => array(
					'use_logo' => 'custom',
				),
				'label'  => __( "Preview", 'complianz-gdpr' ),
			),

			'close_button' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'checkbox',
				'label'   => __( "Close button", 'complianz-gdpr' ),
				'tooltip'    => __( "If enabled, a close icon will be shown on your cookie banner.", 'complianz-gdpr' ),
				'default' => true,
			),

            'use_box_shadow' => array(
            	'default' => true,
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'appearance',
                'type'    => 'checkbox',
                'label'   => __( "Box shadow", 'complianz-gdpr' ),
            ),

			'header_footer_shadow' => array(
            	'default' => false,
                'source'  => 'CMPLZ_COOKIEBANNER',
                'step'    => 'appearance',
                'type'    => 'checkbox',
                'label'   => __( "Box shadow on header and footer", 'complianz-gdpr' ),
            ),

			'soft_cookiewall' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'appearance',
				'type'    => 'checkbox',
				'default' => false,
				'label'   => __( "Show as soft cookie wall", 'complianz-gdpr' ),
				'help'    => __( "A privacy-friendly cookie wall.", 'complianz-gdpr' ) . cmplz_read_more( 'https://complianz.io/the-soft-cookie-wall/' ),
				'tooltip' => __( 'After saving, a preview of the soft cookie wall will be shown for 3 seconds', 'complianz-gdpr' ),
				'condition'          => array(
					'consenttype' => 'NOT optout',
				),
			),

            /* ----- Customization ----- */

            'colorpalette_background' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'colorpicker',
                'master_label'  => __( "General", 'complianz-gdpr' ),
                'label'         => __( "Background", 'complianz-gdpr' ),
                'default'       => array(
                    'color'     => '#ffffff',
                    'border'    => '#f2f2f2',
                ),
                'fields'        => array(
                    array(
                        'fieldname'     => 'color',
                        'label'         => __( "Background", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'border',
                        'label'         => __( "Border", 'complianz-gdpr' ),
                    ),
                ),
            ),

            'colorpalette_text' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'colorpicker',
                'label'         => __( "Text", 'complianz-gdpr' ),
                'default'      => array(
                    'color'       => '#222222',
                    'hyperlink'   => '#1E73BE',
                ),
                'fields'        => array(
                    array(
                        'fieldname'     => 'color',
                        'label'         => __( "Color", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'hyperlink',
                        'label'         => __( "Hyperlink", 'complianz-gdpr' ),
                    ),
                ),
            ),

            'colorpalette_border_radius' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'borderradius',
                'default'       => array(
                    'top'       => '12',
                    'right'     => '12',
                    'bottom'    => '12',
                    'left'      => '12',
                    'type'      => 'px',
                ),
                'label'         => __( "Border radius", 'complianz-gdpr' ),
            ),

            'border_width' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'borderwidth',
                'default'       => array(
                    'top'       => '0',
                    'right'     => '0',
                    'bottom'    => '0',
                    'left'      => '0',
                ),
                'label'         => __( "Border width", 'complianz-gdpr' ),
            ),

			'colorpalette_toggles' => array(
				'source'        => 'CMPLZ_COOKIEBANNER',
				'step'          => 'customization',
				'type'          => 'colorpicker',
				'master_label'  => __( "Toggles", 'complianz-gdpr' ),
				'default'       => array(
					'background'    => '#1e73be',
					'bullet'        => '#ffffff',
					'inactive'      => '#F56E28',
				),
				'fields'        => array(
					array(
						'fieldname'     => 'background',
						'label'         => __( "Background", 'complianz-gdpr' ),
					),
					array(
						'fieldname'     => 'bullet',
						'label'         => __( "Bullet", 'complianz-gdpr' ),
					),
					array(
						'fieldname'     => 'inactive',
						'label'         => __( "Inactive", 'complianz-gdpr' ),
					),
				),
				'condition'     => array('checkbox_style' => 'slider'),
			),

            'colorpalette_button_accept' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'colorpicker',
                'master_label'  => __( "Buttons", 'complianz-gdpr' ),
                'label'         => __( "Accept", 'complianz-gdpr' ),
                'default'      => array(
                    'background'    => '#1E73BE',
                    'border'        => '#1E73BE',
                    'text'          => '#ffffff',
                ),
                'fields'        => array(
                    array(
                        'fieldname'     => 'background',
                        'label'         => __( "Background", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'border',
                        'label'         => __( "Border", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'text',
                        'label'         => __( "Text", 'complianz-gdpr' ),
                    ),
                ),
            ),

            'colorpalette_button_deny' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'colorpicker',
                'label'         => __( "Deny", 'complianz-gdpr' ),
                'default'      => array(
                    'background'    => '#f9f9f9',
                    'border'        => '#f2f2f2',
                    'text'          => '#222222',
                ),
                'fields'        => array(
                    array(
                        'fieldname'     => 'background',
                        'label'         => __( "Background", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'border',
                        'label'         => __( "Border", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'text',
                        'label'         => __( "Text", 'complianz-gdpr' ),
                    ),
                ),
				'condition'          => array(
					'consenttype' => 'NOT optout',
				),
            ),

            'colorpalette_button_settings' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'colorpicker',
                'label'         => __( "Settings", 'complianz-gdpr' ),
                'default'      => array(
                    'background'    => '#f9f9f9',
                    'border'        => '#f2f2f2',
                    'text'          => '#333333',
                ),
                'fields'        => array(
                    array(
                        'fieldname'     => 'background',
                        'label'         => __( "Background", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'border',
                        'label'         => __( "Border", 'complianz-gdpr' ),
                    ),
                    array(
                        'fieldname'     => 'text',
                        'label'         => __( "Text", 'complianz-gdpr' ),
                    ),
                ),
				'condition'          => array(
					'consenttype' => 'NOT optout',
				),
            ),

            'buttons_border_radius' => array(
                'source'        => 'CMPLZ_COOKIEBANNER',
                'step'          => 'customization',
                'type'          => 'borderradius',
                'default'       => array(
                    'top'       => '6',
                    'right'     => '6',
                    'bottom'    => '6',
                    'left'      => '6',
                    'type'      => 'px',
                ),
                'label'         => __( "Border radius", 'complianz-gdpr' ),
            ),

            /* ----- Custom CSS ----- */

			'disable_width_correction' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'custom_css',
				'type'    => 'checkbox',
				'label'   => __( "Disable width auto correction", 'complianz-gdpr' ),
				'default' => false,
				'callback_condition' => 'NOT cmplz_tcf_active',
				'tooltip' => __('This will disable a back-end javascript to keep the banner width aligned with other elements.','complianz-gdpr'),
			),

			'custom_css' => array(
				'source'    => 'CMPLZ_COOKIEBANNER',
				'step'      => 'custom_css',
				'type'      => 'css',
				'help'      => cmplz_sprintf(__('You can add additional custom CSS here. For tips and CSS lessons, check out our %sdocumentation%s', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io/?s=css">', '</a>'),
				'label'     => '',
				'default'   => '.cmplz-message{}'
				               . "\n".' /* styles for the message box */'
				               . "\n". '.cmplz-deny{}'
				               . "\n".' /* styles for the dismiss button */'
				               . "\n". '.cmplz-btn{}'
				               . "\n".' /* styles for buttons */'
				               . "\n" . '.cmplz-accept{} '
				               . "\n".'/* styles for the accept button */'
				               . "\n" . '.cmplz-cookiebanner{} '
				               . "\n".'/* styles for the popup banner */'
				               . "\n" . '.cmplz-cookiebanner .cmplz-category{} '
				               . "\n".'/* styles for categories*/'
				               . "\n" . '.cmplz-manage-consent{} '
				               . "\n".'/* styles for the settings popup */'
				               . "\n" . '.cmplz-soft-cookiewall{} '
				               . "\n".'/* styles for the soft cookie wall */'
                               . "\n"
                               . "\n" . "/* styles for the AMP notice */"
                               . "\n" . '#cmplz-consent-ui, #cmplz-post-consent-ui {} '
				               . "\n".'/* styles for entire banner */'
                               . "\n" . '#cmplz-consent-ui .cmplz-consent-message {} '
				               . "\n".'/* styles for the message area */'
                               . "\n" . '#cmplz-consent-ui button, #cmplz-post-consent-ui button {} '
				               . "\n".'/* styles for the buttons */',
				'condition' => array( 'use_custom_cookie_css' => true ),
			),

            'header' => array(
                'source'             => 'CMPLZ_COOKIEBANNER',
                'step'               => 'settings',
                'type'               => 'text_checkbox',
                'master_label'		 => __( "Header", 'complianz-gdpr' ),
                'label'              => __( "Title", 'complianz-gdpr' ),
                'placeholder'        => __( "Manage Cookie Consent", 'complianz-gdpr' ),
                'default'            => ['text' => __( "Manage Cookie Consent", 'complianz-gdpr' ), 'show' => true],
            ),

			'accept' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'settings',
				'type'               => 'text',
				'default'            => __( "Accept", 'complianz-gdpr' ),
				'label'              => __( "Accept button", 'complianz-gdpr' ),
                'placeholder'        => __( "Accept", 'complianz-gdpr' ),
				'callback_condition' => 'cmplz_uses_optin',
				'condition'          => array(
                    'consenttype'           => 'optin',
                ),
			),

			'accept_informational' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'settings',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "Accept", 'complianz-gdpr' ), 'show' => true],
				'label'              => __( "Accept button", 'complianz-gdpr' ),
				'placeholder'        => __( "Accept", 'complianz-gdpr' ),
				'callback_condition' => 'cmplz_uses_optout',
				'condition'          => array(
					'consenttype'           => 'optout',
				),
			),

            'dismiss' => array(
                'step'               => 'settings',
                'source'             => 'CMPLZ_COOKIEBANNER',
                'type'               => 'text_checkbox',
                'default'            => ['text' => __( "Deny", 'complianz-gdpr' ), 'show' => true],
                'label'              => __( "Deny button", 'complianz-gdpr' ),
                'placeholder'        => __( "Deny", 'complianz-gdpr' ),
                'help'               => __( 'This button will reject all cookies except necessary cookies, and dismisses the cookie banner.', 'complianz-gdpr' ),
                'condition'          => array(
                    'consenttype' => 'NOT optout',
                ),
                'callback_condition' => 'cmplz_uses_optin',
            ),

            'view_preferences'    => array(
                'source'             => 'CMPLZ_COOKIEBANNER',
                'step'               => 'settings',
                'type'               => 'text',
                'default'            => __( "View preferences", 'complianz-gdpr' ),
                'label'              => __( "View preferences", 'complianz-gdpr' ),
                'placeholder'        => __( "View preferences", 'complianz-gdpr' ),
                'condition'          => array(
                    'use_categories' => 'view-preferences',
                    'consenttype'           => 'optin',
                ),
                'callback_condition' => 'cmplz_uses_optin',
            ),

            'save_preferences' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'settings',
				'type'               => 'text',
				'default'            => __( "Save preferences", 'complianz-gdpr' ),
                'placeholder'        => __( "Save preferences", 'complianz-gdpr' ),
				'label'              => __( "Save preferences", 'complianz-gdpr' ),
				'condition'          => array(
                    'use_categories' => 'view-preferences OR save-preferences',
					'consenttype' => 'optin',
				),
				'callback_condition' => 'cmplz_uses_optin',
			),

           'message_optin' => array(
                'step'          => 'settings',
                'source'        => 'CMPLZ_COOKIEBANNER',
                'type'          => 'editor',
                'default'       => __( "To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.", 'complianz-gdpr' ),
                'label'         => __( "Cookie message", 'complianz-gdpr' ),
                'placeholder'        => __( "To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.", 'complianz-gdpr' ),
                'condition'     => array( 'consenttype' => 'optin' ),
            ),

			'font_size' => array(
				'source'        => 'CMPLZ_COOKIEBANNER',
				'step'          => 'settings',
				'type'          => 'number',
				'default'       => 12,
				'label'         => __( "Font size", 'complianz-gdpr' ),
			),

			'legal_documents' => array(
				'source'        => 'CMPLZ_COOKIEBANNER',
				'step'          => 'settings',
				'type'          => 'checkbox',
				'default'       => true,
				'label'         => __( "Legal document links on banner", 'complianz-gdpr' ),
				'comment'       => __( 'On the cookie banner the generated documents are shown. The title is based on the actual post title.', 'complianz-gdpr' ),
			),

			/* ----- Categories ----- */
			'category_functional' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text',
				'default'            => __( "Functional", 'complianz-gdpr' ),
				'placeholder'        => __( "Functional", 'complianz-gdpr' ),
				'label'              => __( "Functional", 'complianz-gdpr' ),
			),

			'functional_text' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "The technical storage or access is strictly necessary for the legitimate purpose of enabling the use of a specific service explicitly requested by the subscriber or user, or for the sole purpose of carrying out the transmission of a communication over an electronic communications network.", 'complianz-gdpr' ), 'show'=>true],
				'label'              => __( "Functional description", 'complianz-gdpr' ),
			),

			'category_prefs' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "Preferences", 'complianz-gdpr' ), 'show' => true],
				'placeholder'        => __( "Preferences", 'complianz-gdpr' ),
				'label'              => __( "Preferences", 'complianz-gdpr' ),
				'callback_condition' => array(
					'cmplz_uses_preferences_cookies',
				),
			),

			'preferences_text' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "The technical storage or access is necessary for the legitimate purpose of storing preferences that are not requested by the subscriber or user.", 'complianz-gdpr' ), 'show'=>true],
				'label'              => __( "Preferences description", 'complianz-gdpr' ),
				'callback_condition' => array(
					'cmplz_uses_preferences_cookies',
				),
			),

			'category_stats' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "Statistics", 'complianz-gdpr' ), 'show' => true],
				'label'              => __( "Statistics", 'complianz-gdpr' ),
				'placeholder'        => __( "Statistics", 'complianz-gdpr' ),
			),

			'statistics_text' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text'=>__( "The technical storage or access that is used exclusively for statistical purposes.", 'complianz-gdpr' ), 'show'=>true],
				'label'              => __( "Statistics description", 'complianz-gdpr' ),
				'condition'         => array(
					'category_stats[show]' => true,
				),
				'callback_condition' => array(
					'NOT cmplz_statistics_privacy_friendly'
				)
			),

			'statistics_text_anonymous' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "The technical storage or access that is used exclusively for anonymous statistical purposes. Without a subpoena, voluntary compliance on the part of your Internet Service Provider, or additional records from a third party, information stored or retrieved for this purpose alone cannot usually be used to identify you.", 'complianz-gdpr' ), 'show'=>true],
				'label'              => __( "Anonymous statistics description", 'complianz-gdpr' ),
				'condition'         => array(
					'category_stats[show]' => true,
				),
				'callback_condition' => array(
					'cmplz_statistics_privacy_friendly'
				)
			),

			'category_all' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text' => __( "Marketing", 'complianz-gdpr' ), 'show' => true],
				'label'              => __( "Marketing", 'complianz-gdpr' ),
				'placeholder'        => __( "Marketing", 'complianz-gdpr' ),
				'callback_condition' => array(
					'cmplz_uses_marketing_cookies',
				),
			),

			'marketing_text' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'banner-categories',
				'type'               => 'text_checkbox',
				'default'            => ['text'=>__( "The technical storage or access is required to create user profiles to send advertising, or to track the user on a website or across several websites for similar marketing purposes.", 'complianz-gdpr' ), 'show'=>true],
				'label'              => __( "Marketing description", 'complianz-gdpr' ),
				'callback_condition' => array(
					'cmplz_uses_marketing_cookies',
				),
				'condition'         => array(
					'category_all[show]' => true,
				)
			),

			/*
			 *
			 * US settings
			 *
			 * */

            'dismiss_on_scroll' => array(
                'source'             => 'CMPLZ_COOKIEBANNER',
                'step'               => 'settings',
                'type'               => 'checkbox',
                'label'              => __( "Dismiss on scroll", 'complianz-gdpr' ),
                'tooltip'               => __( 'When dismiss on scroll is enabled, the cookie banner will be dismissed as soon as the user scrolls.',
                    'complianz-gdpr' ),
                'default'            => false,
                //setting this to true will set it always to true, as the get_cookie settings will see an empty value
                'callback_condition' => 'cmplz_uses_optout',
                'condition'          => array( 'consenttype' => 'optout' ),
            ),

			'dismiss_on_timeout' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'settings',
				'type'               => 'checkbox',
				'label'              => __( "Dismiss on time out", 'complianz-gdpr' ),
				'tooltip'               => __( 'When dismiss on time out is enabled, the cookie banner will be dismissed after 10 seconds, or the time you choose below.', 'complianz-gdpr' ),
				'default'            => false,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optout',
                'condition'          => array( 'consenttype' => 'optout' ),
			),

			'dismiss_timeout' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'settings',
				'type'               => 'number',
				'label'              => __( "Timeout in seconds",
					'complianz-gdpr' ),
				'default'            => 10,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optout',
                'condition'          => array(
                    'consenttype' => 'optout',
                    'dismiss_on_timeout' => true,
                    ),
			),

			'message_optout' => array(
				'step'        => 'settings',
				'source'      => 'CMPLZ_COOKIEBANNER',
				'type'        => 'editor',
				'default'     => __( "To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.", 'complianz-gdpr' ),
				'placeholder' => __( "To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behavior or unique IDs on this site. Not consenting or withdrawing consent, may adversely affect certain features and functions.", 'complianz-gdpr' ),
				'label'       => __( "Cookie message", 'complianz-gdpr' ),
				'condition'   => array( 'consenttype' => 'optout' ),
			),
		);


	return $fields;
}
