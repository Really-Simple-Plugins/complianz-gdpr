<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

 function cmplz_banner_color_schemes(){
	$schemes = array(
		'Wordpress' => array(
			'slider_background_color' => '#21759b',
			'slider_bullet_color' => '#fff',
			'slider_background_color_inactive' => '#F56E28',
			'accept_all_background_color' => '#21759b',
			'accept_all_text_color' => '#fff',
			'accept_all_border_color' => '#21759b',
			'functional_background_color' => '#f1f1f1',
			'functional_text_color' => '#21759b',
			'functional_border_color' => '#f1f1f1',
			'popup_background_color' => '#f1f1f1',
			'popup_text_color' => '#191e23',
			'button_background_color' => '#f1f1f1',
			'button_text_color' => '#21759b',
			'border_color' => '#21759b',
			'theme' => 'minimal',
		),

		'Minimalist' => array(
			'slider_background_color' => '#5ccebb',
			'slider_bullet_color' => '#fff',
			'slider_background_color_inactive' => '#f4a977',
			'accept_all_background_color' => '#fff',
			'accept_all_text_color' => '#333',
			'accept_all_border_color' => '#333',
			'functional_background_color' => '#fff',
			'functional_text_color' => '#333',
			'functional_border_color' => '#fff',
			'popup_background_color' => '#fff',
			'popup_text_color' => '#333',
			'button_background_color' => '#fff',
			'button_text_color' => '#333',
			'border_color' => '#fff',
		),

		'Darkmode' => array(
			'slider_background_color' => '#081e2e',
			'slider_bullet_color' => '#BBBBBB',
			'slider_background_color_inactive' => '#230200',
			'accept_all_background_color' => '#090030',
			'accept_all_text_color' => '#BBBBBB',
			'accept_all_border_color' => '#090030',
			'functional_background_color' => '#230200',
			'functional_text_color' => '#BBBBBB',
			'functional_border_color' => '#230200',
			'popup_background_color' => '#000000',
			'popup_text_color' => '#BBBBBB',
			'button_background_color' => '#081E2E',
			'button_text_color' => '#BBBBBB',
			'border_color' => '#081E2E',
		),

		'Really-Simple-SSL' => array(
			'slider_background_color' => '#333333',
			'slider_bullet_color' => '#f8be2e',
			'slider_background_color_inactive' => '#F1f1f1',
			'accept_all_background_color' => '#333333',
			'accept_all_text_color' => '#f8be2e',
			'accept_all_border_color' => '#333333',
			'functional_background_color' => '#f8be2e',
			'functional_text_color' => '#333333',
			'functional_border_color' => '#333333',
			'popup_background_color' => '#f8be2e',
			'popup_text_color' => '#333333',
			'button_background_color' => '#f8be2e',
			'button_text_color' => '#333333',
			'border_color' => '#333333',
		),

		'Complianz' => array(
			'slider_background_color' => '#29b6f6',
			'slider_bullet_color' => '#ffffff',
			'slider_background_color_inactive' => '#cccccc',
			'accept_all_background_color' => '#29b6f6',
			'accept_all_text_color' => '#ffffff',
			'accept_all_border_color' => '#29b6f6',
			'functional_background_color' => '#000000',
			'functional_text_color' => '#ffffff',
			'functional_border_color' => '#0000000',
			'popup_background_color' => '#ffffff',
			'popup_text_color' => '#000000',
			'button_background_color' => '#000000',
			'button_text_color' => '#ffffff',
			'border_color' => '#000000',
		),

		'WP-Search-Insights' => array(
			'slider_background_color' => '#61ce70',
			'slider_bullet_color' => '#ffffff',
			'slider_background_color_inactive' => '#d7263d',
			'accept_all_background_color' => '#d7263d',
			'accept_all_text_color' => '#ffffff',
			'accept_all_border_color' => '#d7263d',
			'functional_background_color' => '#d7263d',
			'functional_text_color' => '#ffffff',
			'functional_border_color' => '#d7263d',
			'popup_background_color' => '#f1f1f1',
			'popup_text_color' => '#000000',
			'button_background_color' => '#d7263d',
			'button_text_color' => '#ffffff',
			'border_color' => '#d7263d',
		),

		'Zip-Recipes' => array(
			'slider_background_color' => '#81d742',
			'slider_bullet_color' => '#ffffff',
			'slider_background_color_inactive' => '#d60039',
			'accept_all_background_color' => '#333333',
			'accept_all_text_color' => '#ffffff',
			'accept_all_border_color' => '#e35899',
			'functional_background_color' => '#e35899',
			'functional_text_color' => '#000000',
			'functional_border_color' => '#e35899',
			'popup_background_color' => '#333333',
			'popup_text_color' => '#ffffff',
			'button_background_color' => '#e35899',
			'button_text_color' => '#000000',
			'border_color' => '#e35899',
		),

		'tcf' => array(
			'slider_background_color' => '#33CC66',
			'slider_bullet_color' => '#fff',
			'slider_background_color_inactive' => '#CC0000',
			'accept_all_background_color' => '#333',
			'accept_all_text_color' => '#fff',
			'accept_all_border_color' => '#333',
			'functional_background_color' => '#fff',
			'functional_text_color' => '#333',
			'functional_border_color' => '#fff',
			'popup_background_color' => '#fff',
			'popup_text_color' => '#333',
			'button_background_color' => '#fff',
			'button_text_color' => '#333',
			'border_color' => '#333',
			'theme' => 'minimal',
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

			'title' => array(
				'step'        => 'general',
				'source'      => 'CMPLZ_COOKIEBANNER',
				'type'        => 'text',
				'label'       => __( "Cookie banner title", 'complianz-gdpr' ),
				'placeholder' => __( 'Descriptive title of the cookiebanner' ),
				'help'        => __( 'For internal use only', 'complianz-gdpr' ),
				'cols'     => 12,
			),

			'default' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'general',
				'type'               => 'checkbox',
				'label'              => __( "Default cookie banner",
					'complianz-gdpr' ),
				'help'               => __( 'When enabled, this is the cookie banner that is used for all visitors. Enabling it will disable this setting on the current default banner. Disabling it will enable randomly a different default banner.',
					'complianz-gdpr' ),

				'default'            => false,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_ab_testing_enabled',
				'cols'     => 12,
			),

			'color_scheme' => array(
				'step'    => 'general',
				'source'  => 'CMPLZ_COOKIEBANNER',
				'type'    => 'select',
				'label'   => __( "Color scheme", 'complianz-gdpr' ),
				'help'   => __( "This is not a setting, but a tool to switch all colors to a predefined set.", 'complianz-gdpr' ),
				'options' => cmplz_get_banner_color_scheme_options(),
				'default' => 'bottom-right',
				'cols'     => 4,
			),

			'position' => array(
				'step'    => 'general',
				'source'  => 'CMPLZ_COOKIEBANNER',
				'type'    => 'select',
				'label'   => __( "Banner position", 'complianz-gdpr' ),
				'options' => array(
					'bottom'       => __( "Banner bottom", 'complianz-gdpr' ),
					'bottom-left'  => __( "Floating left", 'complianz-gdpr' ),
					'bottom-right' => __( "Floating right", 'complianz-gdpr' ),
					'center'       => __( "Center", 'complianz-gdpr' ),
					'top'          => __( "Banner top", 'complianz-gdpr' ),
					'static'       => __( "Push down", 'complianz-gdpr' ),
				),
				'default' => 'bottom-right',
				'cols'     => 4,
			),

			'theme' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'select',
				'label'   => __( "Banner style", 'complianz-gdpr' ),
				'options' => array(
					'block'    => __( "Block", 'complianz-gdpr' ),
					'classic'  => __( "Classic", 'complianz-gdpr' ),
					'edgeless' => __( "Edgeless", 'complianz-gdpr' ),
					'minimal'  => __( "Minimal", 'complianz-gdpr' ),
				),
				'default' => 'classic',
				'cols'     => 4,
			),

			'checkbox_style' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'select',
				'label'   => __( "Checkbox style", 'complianz-gdpr' ),
				'help'    => __( "This style is for the checkboxes on the cookie banner, as well as on your policy for managing consent.",
					'complianz-gdpr' ),
				'options' => array(
					'classic'    => __( "Classic", 'complianz-gdpr' ),
					'square'  => __( "Square", 'complianz-gdpr' ),
					'slider' => __( "Slider", 'complianz-gdpr' ),
				),
				'default' => 'square',
				'cols'     => 12,
				'condition' => array(
					'use_categories' => 'NOT no',
				)
			),

			'soft_cookiewall' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'checkbox',
				'default' => false,
				'label'   => __( "Show as soft cookie wall", 'complianz-gdpr' ),
				'help'    => sprintf( __( "You can grey out the rest of the website, which makes it look like a cookie wall, but it is dismissible: a 'soft' cookie wall. Read more about the soft cookie wall in this %sarticle%s.",
					'complianz-gdpr' ),
					'<a href="https://complianz.io/the-soft-cookie-wall/" target="_blank">',
					"</a>" ),
				'comment' => __( 'After saving, a preview of the soft cookie wall will be shown for 3 seconds',
					'complianz-gdpr' ),
				'cols'     => 4,
			),

			'banner_width' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'number',
				'default' => '476',
				'validation_step' => 2,
				'label'   => __( "Min width of banner in pixels", 'complianz-gdpr' ),
				'cols'     => 4,
			),

			'hide_revoke' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'checkbox',
				'default' => false,
				'label'   => __( "Hide manage consent button", 'complianz-gdpr' ),
				'help'    => __( 'If you want to hide the button, enable this check box. The button will normally show after making a choice.',
					'complianz-gdpr' ),
				'comment' => __( 'If you hide this button, you should at least leave the option to revoke consent on your cookie policy or Do Not Sell My Personal Information page',
					'complianz-gdpr' ),
				'cols'     => 4,
			),

			'revoke' => array(
				'source'    => 'CMPLZ_COOKIEBANNER',
				'step'      => 'general',
				'type'      => 'text',
				'default'   => __( "Manage consent", 'complianz-gdpr' ),
				'label'     => __( "Manage consent text", 'complianz-gdpr' ),
				'help'      => __( 'The text that appears on the button used to change consent, which shows when a choice has been made in the cookie warning.',
					'complianz-gdpr' ),
				'cols'     => 12,
			),

			'popup_background_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#f1f1f1',
				'label'   => __( "Background color | Banner", 'complianz-gdpr' ),
				'cols'     => 4,
			),

			'popup_text_color'        => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#191e23',
				'label'   => __( "Text color | Banner", 'complianz-gdpr' ),
				'cols'     => 8,
			),

			'slider_background_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0073aa',
				'label'   => __( "Background color | Slider", 'complianz-gdpr' ),
				'condition' => array(
					'checkbox_style' => 'slider'
				),
				'cols'     => 4,
			),

			'slider_bullet_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#fff',
				'label'   => __( "Bullet color | Slider", 'complianz-gdpr' ),
				'condition' => array(
					'checkbox_style' => 'slider'
				),
				'cols'     => 4,
			),

			'slider_background_color_inactive' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#F56E28',
				'label'   => __( "Background color | Inactive slider", 'complianz-gdpr' ),
				'condition' => array(
					'checkbox_style' => 'slider'
				),
				'cols'     => 4,
			),

			'accept_all_background_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0085ba',
				'label'   => __( "Background color | Accept all button", 'complianz-gdpr' ),
				'condition' => array(
					//'use_categories' => 'hidden'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

			'accept_all_text_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#fff',
				'label'   => __( "Text color | Accept all button", 'complianz-gdpr' ),
				'condition' => array(
					//'use_categories' => 'hidden'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

			'accept_all_border_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0073aa',
				'label'   => __( "Border color | Accept all button", 'complianz-gdpr' ),
				'condition' => array(
					//'use_categories' => 'hidden'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

			'functional_background_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#f1f1f1',
				'label'   => __( "Background color | Deny button", 'complianz-gdpr' ),
				'condition' => array(
					//'use_categories' => 'hidden'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

			'functional_text_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0073aa',
				'label'   => __( "Text color | Deny button", 'complianz-gdpr' ),
				'condition' => array(
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

			'functional_border_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#f1f1f1',
				'label'   => __( "Border color | Deny button", 'complianz-gdpr' ),
				'condition' => array(
					//'use_categories' => 'hidden'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 4,
			),

//			'popup_border_color'        => array(
//				'source'  => 'CMPLZ_COOKIEBANNER',
//				'step'    => 'general',
//				'type'    => 'colorpicker',
//				'default' => '#fff',
//				'label'   => __( "Popup border color", 'complianz-gdpr' ),
//				'cols'     => 4,
//			),

			'button_background_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#f1f1f1',
				'label'   => __( "Background color | Accept or Preferences button", 'complianz-gdpr' ),
				'cols'     => 4,
			),

			'button_text_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0073aa',
				'label'   => __( "Text color | Accept or Preferences button", 'complianz-gdpr' ),
				'cols'     => 4,
			),

			'border_color' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'colorpicker',
				'default' => '#0073aa',
				'label'   => __( "Border color | Accept or Preferences button", 'complianz-gdpr' ),
				'cols'     => 4,
			),

			'use_custom_cookie_css' => array(
				'source'  => 'CMPLZ_COOKIEBANNER',
				'step'    => 'general',
				'type'    => 'checkbox',
				'label'   => __( "Use Custom CSS", 'complianz-gdpr' ),
				'default' => false,
				'cols'     => 12,

			),

			'custom_css' => array(
				'source'    => 'CMPLZ_COOKIEBANNER',
				'step'      => 'general',
				'type'      => 'css',
				'label'     => __( "Custom CSS", 'complianz-gdpr' ),
				'default'   => '.cc-message{} /* styles for the message box */'
				               . "\n"
				               . '.cc-dismiss{} /* styles for the dismiss button */'
				               . "\n" . '.cc-btn{} /* styles for buttons */' . "\n"
				               . '.cc-allow{} /* styles for the accept button */'
				               . "\n"
				               . '.cc-accept-all{} /* styles for the accept all button */'
				               . "\n"
				               . '.cc-window{} /* styles for the popup banner */'
				               . "\n"
				               . '.cc-window .cc-category{} /* styles for categories*/'
				               . "\n"
				               . '.cc-window .cc-check{} /* styles for the checkboxes with categories */'
				               . "\n"
				               . '.cc-revoke{} /* styles for the revoke / settings popup */'
				               . "\n"
				               . '.cmplz-slider-checkbox{} /* styles for the checkboxes */'
				               . "\n"
				               . '.cmplz-soft-cookiewall{} /* styles for the soft cookie wall */',
				'condition' => array( 'use_custom_cookie_css' => true ),
				'cols'     => 12,
			),

			'custom_css_amp' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'general',
				'type'               => 'css',
				'label'              => __( "Custom CSS for AMP",
					'complianz-gdpr' ),
				'default'            => '#cmplz-consent-ui, #cmplz-post-consent-ui {} /* styles for entire banner */'
				                        . "\n"
				                        . '#cmplz-consent-ui .cmplz-consent-message {} /* styles for the message area */'
				                        . "\n"
				                        . '#cmplz-consent-ui button, #cmplz-post-consent-ui button {} /* styles for the buttons */',

				'condition'          => array( 'use_custom_cookie_css' => true ),
				'callback_condition' => 'cmplz_amp_integration_active',
				'cols'     => 12,

			),

			/**
			 * Opt in settings
			 */

			'use_categories' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optin',
				'type'               => 'select',
				'options'            => array(
					'no' => __('Accept/Deny', 'complianz-gdpr'),
					'legacy' => __('Legacy categories', 'complianz-gdpr'),
					'hidden' => __('Accept all + view preferences', 'complianz-gdpr'),
					'visible' => __('Accept all + categories', 'complianz-gdpr'),
				),
				'label'              => __( "Categories", 'complianz-gdpr' ),
				'help'               => __( 'With categories, you can let users choose which category of cookies they want to accept.',
						'complianz-gdpr' ) . ' '
				                        . __( 'Depending on your settings and cookies you use, there can be two or three categories. With Tag Manager you can use more, custom categories.',
						'complianz-gdpr' ),

				'default'            => 'hidden',
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'use_categories_optinstats' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optinstats',
				'type'               => 'select',
				'label'              => __( "Categories", 'complianz-gdpr' ),
				'help'               => __( 'With categories, you can let users choose which category of cookies they want to accept.',
						'complianz-gdpr' ) . ' '
				                        . __( 'Depending on your settings and cookies you use, there can be two or three categories. With Tag Manager you can use more, custom categories.',
						'complianz-gdpr' ),
				'options'            => array(
					'no' => __('Accept/Deny', 'complianz-gdpr'),
					'legacy' => __('Legacy categories', 'complianz-gdpr'),
					'hidden' => __('Accept all + view preferences', 'complianz-gdpr'),
					'visible' => __('Accept all + categories', 'complianz-gdpr'),
				),

				'condition'          => array( 'show_always' => true ),
				'default'            => 'hidden',
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'tagmanager_categories' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'textarea',
				'label'              => __( "Custom Tag Manager categories",
					'complianz-gdpr' ),
				'help'               => __( 'Enter your custom Tag Manager categories, comma separated. The first item will fire event cmplz_event_0, the second one will fire cmplz_event_1 and so on. At page load cmplz_event_functional is fired, Marketing fires cmplz_event_marketing',
						'complianz-gdpr' ) . "<br><br>"
				                        . cmplz_tagmanager_conditional_helptext()
				                        . cmplz_read_more( 'https://complianz.io/configure-categories-tag-manager' ),

				'placeholder'        => __( 'First category, Second category',
					'complianz-gdpr' ),
				'callback_condition' => array(
					'fire_scripts_in_tagmanager' => 'yes',
					'compile_statistics'         => 'google-tag-manager',
					'regions'                    => array( 'eu', 'uk' ),
				),
				'condition'          => array( 'use_categories' => 'NOT no' ),
				'default'            => false,
				'cols'     => 12,
			),

			'dismiss' => array(
				'step'               => array( 'optin', 'optinstats' ),
				'source'             => 'CMPLZ_COOKIEBANNER',
				'type'               => 'text',
				'default'            => __( "Functional only", 'complianz-gdpr' ),
				'label'              => __( "Functional cookies text",
					'complianz-gdpr' ),

				'help'               => __( 'When a users clicks this button, the message is dismissed, without activating all cookies. This can be described as a "dismiss" button or as an "activate functional cookies" only button.',
					'complianz-gdpr' ),
				//'condition'          => array( 'use_categories' => 'no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'save_preferences' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Save preferences", 'complianz-gdpr' ),
				'label'              => __( "Save preferences text",
					'complianz-gdpr' ),

				'condition'          => array(
					'use_categories' => 'NOT no'
				),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'view_preferences'    => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "View preferences", 'complianz-gdpr' ),
				'label'              => __( "View preferences text", 'complianz-gdpr' ),

				'condition'          => array( 'use_categories' => 'NOT no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'category_functional' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Functional",
					'complianz-gdpr' ),
				'label'              => __( "Functional category text",
					'complianz-gdpr' ),

				'condition'          => array( 'use_categories' => 'NOT no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'category_prefs' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Preferences", 'complianz-gdpr' ),
				'label'              => __( "Preferences category text",
					'complianz-gdpr' ),

				'condition'          => array( 'use_categories' => 'NOT no' ),
				'callback_condition' => array(
					'cmplz_uses_optin',
					'cmplz_consent_api_active',
				),
				'cols'     => 12,
			),

			'category_stats' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Statistics", 'complianz-gdpr' ),
				'label'              => __( "Statistics category text",
					'complianz-gdpr' ),
				'help'               => __( "It depends on your settings if this category is necessary, so it will conditionally show",
					'complianz-gdpr' ),

				'condition'          => array( 'use_categories' => 'NOT no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'category_all' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Marketing", 'complianz-gdpr' ),
				'label'              => __( "Marketing category text", 'complianz-gdpr' ),
				'condition'          => array( 'use_categories' => 'NOT no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'accept' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Accept cookies", 'complianz-gdpr' ),
				'help'               => __( 'This text is shown in the button which accepts all cookies. These are generally marketing related cookies, so you could also name it "Marketing"',
					'complianz-gdpr' ),
				'label'              => __( "Accept all",
					'complianz-gdpr' ),

				'condition'          => array( 'use_categories' => 'no' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'accept_all' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Accept all", 'complianz-gdpr' ),
				'label'              => __( "Text on accept all button", 'complianz-gdpr' ),

				'callback_condition' => 'cmplz_uses_optin',
				'condition'          => array( 'use_categories' => 'NOT no' ),
				'cols'     => 12,
			),

			'readmore_optin' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Cookie Policy", 'complianz-gdpr' ),
				'label'              => __( "Text on link to Cookie Policy",
					'complianz-gdpr' ),
				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'readmore_impressum' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => array( 'optin', 'optinstats' ),
				'type'               => 'text',
				'default'            => __( "Impressum", 'complianz-gdpr' ),
				'label'              => __( "Text on link to Impressum",
					'complianz-gdpr' ),

				'callback_condition' => 'cmplz_uses_optin',
				'cols'     => 12,
			),

			'message_optin' => array(
				'step'    => array( 'optin', 'optinstats' ),
				'source'  => 'CMPLZ_COOKIEBANNER',
				'type'    => 'editor',
				'default' => __( "We use cookies to optimize our website and our service.",
					'complianz-gdpr' ),
				'label'   => __( "Cookie message", 'complianz-gdpr' ),
				'cols'     => 12,
			),



			/*
			 *
			 * US settings
			 *
			 * */

			'dismiss_on_scroll' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'checkbox',
				'label'              => __( "Dismiss on scroll", 'complianz-gdpr' ),
				'help'               => __( 'When dismiss on scroll is enabled, the cookie banner will be dismissed as soon as the user scrolls.',
					'complianz-gdpr' ),
				'default'            => false,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optout',
				'cols'     => 12,
			),

			'dismiss_on_timeout' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'checkbox',
				'label'              => __( "Dismiss on time out",
					'complianz-gdpr' ),
				'help'               => __( 'When dismiss on time out is enabled, the cookie banner will be dismissed after 10 seconds, or the time you choose below.',
					'complianz-gdpr' ),
				'default'            => false,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'callback_condition' => 'cmplz_uses_optout',
				'cols'     => 12,
			),

			'dismiss_timeout' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'number',
				'label'              => __( "Timeout in seconds",
					'complianz-gdpr' ),
				'default'            => 10,
				//setting this to true will set it always to true, as the get_cookie settings will see an empty value
				'condition'          => array(
					'dismiss_on_timeout' => true,
				),
				'callback_condition' => 'cmplz_uses_optout',
				'cols'     => 12,

			),

			'accept_informational' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'text',
				'default'            => __( "Accept", 'complianz-gdpr' ),
				'label'              => __( "Accept", 'complianz-gdpr' ),
				'callback_condition' => 'cmplz_uses_optout',
				'cols'     => 12,
			),

			/**
			 * The condition NOT CCPA is removed, because it will hide the policy when in combination with Canada, where the text will be needed.
			 */

			'readmore_optout' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'text',
				'default'            => 'Cookie Policy',
				'label'              => __( "Text on link to the Cookie Policy",
					'complianz-gdpr' ),
				'cols'     => 12,
			),


			'readmore_optout_dnsmpi' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'text',
				'default'            => 'Do Not Sell My Personal Information',
				'label'              => __( "Text on link to the Do Not Sell My Personal Information page.",
					'complianz-gdpr' ),

				'callback_condition' => 'cmplz_ccpa_applies',
				'help'               => __( 'This text is not shown in the preview, but is used instead of the Cookie Policy text when the region is US, and CCPA applies.',
					'complianz-gdpr' ),
				'cols'     => 12,
			),

			'readmore_privacy' => array(
				'source'             => 'CMPLZ_COOKIEBANNER',
				'step'               => 'optout',
				'type'               => 'text',
				'default'            => __( "Privacy Statement", 'complianz-gdpr' ),
				'label'              => __( "Text on link to Privacy Statement",
					'complianz-gdpr' ),

				'callback_condition' => 'cmplz_uses_optout',
				'cols'     => 12,
			),

			'message_optout' => array(
				'step'    => 'optout',
				'source'  => 'CMPLZ_COOKIEBANNER',
				'type'    => 'editor',
				'default' => __( "We use cookies to optimize our website and our service.",
					'complianz-gdpr' ),
				'label'   => __( "Cookie message", 'complianz-gdpr' ),
				'cols'     => 12,
			),


		);


	return $fields;
}
