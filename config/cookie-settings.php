<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->fields = $this->fields + array(
        'use_country' => array(
            'step' => 'general',
            'page' => 'cookie_settings',
            'type' => 'checkbox',
            'label' => __("Use geolocation", 'complianz-gdpr'),
            'comment' => $this->premium_geo_ip.__('If enabled, the cookie warning will not show for countries without a cookie law, and will adjust the warning type depending on supported privacy laws','complianz-gdpr'),
            'table' => true,
            'disabled' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),

        'a_b_testing' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'checkbox',
            'label' => __("Enable A/B testing", 'complianz-gdpr'),
            'comment' => $this->premium_ab_testing.__('If enabled, the plugin will track which cookie warning has the best conversion rate.','complianz-gdpr'),
            'table' => true,
            'disabled' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),

        'a_b_testing_duration' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'number',
            'label' => __("Duration in days of the A/B testing period", 'complianz-gdpr'),
            'table' => true,
            'disabled' => true,
            'condition' => array('a_b_testing' => true),
            'default' => 30, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),

        'position' => array(
            'step' => 'general',
            'page' => 'cookie_settings',
            'type' => 'select',
            'label' => __("Popup position", 'complianz-gdpr'),
            'options' => array(
                'bottom' => __("Banner bottom", 'complianz-gdpr'),
                'bottom-left' => __("Floating left", 'complianz-gdpr'),
                'bottom-right' => __("Floating right", 'complianz-gdpr'),
                'center' => __("Center", 'complianz-gdpr'),
                'top' => __("Banner top", 'complianz-gdpr'),
                'static' => __("Push down", 'complianz-gdpr'),
            ),
            'default' => 'bottom',
            'table' => true,
            'has_variations' => true,
        ),

        'theme' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'select',
            'label' => __("Style", 'complianz-gdpr'),
            'options' => array(
                'block' => __("Block", 'complianz-gdpr'),
                'classic' => __("Classic", 'complianz-gdpr'),
                'edgeless' => __("Edgeless", 'complianz-gdpr'),
                'minimal' => __("Minimal", 'complianz-gdpr'),
            ),
            'default' => 'edgeless',
            'table' => true,
            'has_variations' => true,
        ),

        'revoke' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Settings", 'complianz-gdpr'),
            'label' => __("Settings text", 'complianz-gdpr'),
            'table' => true,
            'help'  => __('The text that appears on the revoke button, which shows when a choice has been made in the cookie warning.','complianz-gdpr'),
            'has_variations' => true,
            'condition' => array('use_categories' => false),
        ),

        'dismiss' => array(
            'step' => 'eu',
            'page' => 'cookie_settings',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Functional only", 'complianz-gdpr'),
            'label' => __("Functional cookies text", 'complianz-gdpr'),
            'table' => true,
            'help' => __('When a users clicks this button, the message is dismissed, without activating all cookies. This can be described as a "dismiss" button or as an "activate functional cookies" only button.','complianz-gdpr'),
            'has_variations' => true,
            'condition' => array('use_categories' => false),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),

        'save_preferences' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Save preferences", 'complianz-gdpr'),
            'label' => __("Save settings text", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),

        'view_preferences' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("View preferences", 'complianz-gdpr'),
            'label' => __("View preferences text", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),
        'category_functional' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Functional cookies", 'complianz-gdpr'),
            'label' => __("Functional cookies text", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),
        'category_all' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Marketing", 'complianz-gdpr'),
            'label' => __("Accept all cookies text", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),
        'category_stats' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Statistics", 'complianz-gdpr'),
            'label' => __("Statistics cookies text", 'complianz-gdpr'),
            'help' => __("It depends on your settings if this category is necessary, so it will conditionally show", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),
        'accept' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("All cookies", 'complianz-gdpr'),
            'help' => __('This text is shown in the button which accepts all cookies. These are generally marketing related cookies, so you could also name it "Marketing"', 'complianz-gdpr'),
            'label' => __("Accept all cookies text", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_categories' => false),
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),

        'message' => array(
            'step' => 'eu',
            'page' => 'cookie_settings',
            'type' => 'editor',
            'translatable' => true,
            'default' => __("We use cookies to optimize our website and our service.", 'complianz-gdpr'),
            'label' => __("Cookie message", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'readmore' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Read more", 'complianz-gdpr'),
            'label' => __("Text on link to cookie policy", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),

        'use_categories' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'checkbox',
            'label' => __("Use categories", 'complianz-gdpr'),
            'help' => __('With categories, you can let users choose which category of cookies they want to accept.','complianz-gdpr').' '.__('Depending on your settings and cookies you use, there can be two or three categories. With Tag Manager you can use more, custom categories.','complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => array(
                'regions' => 'eu',
            ),
        ),

        'tagmanager_categories' => array(
            'page' => 'cookie_settings',
            'step' => 'eu',
            'type' => 'textarea',
            'label' => __("Custom Tag Manager categories", 'complianz-gdpr'),
            'help' => __('Enter your custom Tag Manager categories, comma separated. The first item will fire event cmplz_event_0, the second one will fire cmplz_event_1 and so on. At page load cmplz_event_functional is fired, Marketing fires cmplz_event_all','complianz-gdpr')."<br><br>".cmplz_tagmanager_conditional_helptext().$this->read_more('https://complianz.io/configure-categories-tag-manager'),
            'table' => true,
            'has_variations' => true,
            'placeholder' => __('First category, Second category', 'complianz-gdpr'),
            'callback_condition' =>array(
                'fire_scripts_in_tagmanager' => 'yes',
                'compile_statistics' => 'google-tag-manager',
                'regions' => 'eu',
            ),
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),

        'hide_revoke' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'checkbox',
            'translatable' => true,
            'default' => false,
            'label' => __("Hide settings button", 'complianz-gdpr'),
            'table' => true,
            'help'  => __('If you want to hide the revoke button, enable this check box. The revoke button will normally show after making a choice.','complianz-gdpr'),
            'comment'  => __('If you hide this button, you should at least leave the option to revoke consent on your cookie policy or Do Not Sell My Personal Information page','complianz-gdpr'),
            'has_variations' => true,
        ),

        /*
         *
         * US settings
         *
         * */

        'dismiss_on_scroll' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'checkbox',
            'label' => __("Dismiss on scroll", 'complianz-gdpr'),
            'help' => __('When dismiss on scroll is enabled, the cookie warning will be dismissed as soon as the user scrolls.','complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'dismiss_on_timeout' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'checkbox',
            'label' => __("Dismiss on timeout", 'complianz-gdpr'),
            'help' => __('When dismiss on time out is enabled, the cookie warning will be dismissed after 10 seconds, or the time you choose below.','complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'dismiss_timeout' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'number',
            'label' => __("Timeout in seconds", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'default' => 10, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'condition' => array(
                'dismiss_on_timeout' => true,
            ),
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'accept_informational' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Accept", 'complianz-gdpr'),
            'label' => __("Accept", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'message_us' => array(
            'step' => 'us',
            'page' => 'cookie_settings',
            'type' => 'editor',
            'translatable' => true,
            'default' => __("We use cookies to optimize our website and our service.", 'complianz-gdpr'),
            'label' => __("Cookie message", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'readmore_us' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'text',
            'translatable' => true,
            'default' => cmplz_us_cookie_statement_title(),
            'label' => __("Text on link to the US cookie statement", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'readmore_privacy' => array(
            'page' => 'cookie_settings',
            'step' => 'us',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Privacy statement", 'complianz-gdpr'),
            'label' => __("Text on link to privacy statement", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
            'callback_condition' => array(
                'regions' => 'us',
            ),
        ),

        'popup_background_color' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' =>  '#37a8de',
            'label' => __("Popup background color", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'popup_text_color' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Popup text color", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),
        'button_background_color' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Button background color", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),
        'button_text_color' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#37a8de',
            'label' => __("Button text color", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'border_color' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Border color", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'cookie_expiry' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'number',
            'default' => 365,
            'label' => __("Cookie warning expiration in days", 'complianz-gdpr'),
            'table' => true,
            'has_variations' => true,
        ),

        'use_custom_cookie_css' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'checkbox',
            'label' => __("Use Custom CSS", 'complianz-gdpr'),
            'default' => false,
            'table' => true,
            'has_variations' => true,
        ),

        'custom_css' => array(
            'page' => 'cookie_settings',
            'step' => 'general',
            'type' => 'css',
            'label' => __("Custom CSS", 'complianz-gdpr'),
            'default' => '.cc-message{} /* styles for the message box */'."\n".'.cc-dismiss{} /* styles for the dismiss button */'."\n".'.cc-allow{} /* styles for the accept button */'."\n".'.cc-window{} /* styles for the popup banner */'."\n".'.cc-window .cc-category{} /* styles for categories*/'."\n".'.cc-window .cc-check{} /* styles for the checkboxes with categories */',
            'table' => true,
            'has_variations' => true,
            'condition' => array('use_custom_cookie_css' => true),
        ),

    );
