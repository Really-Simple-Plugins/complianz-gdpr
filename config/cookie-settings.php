<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->fields = $this->fields + array(

        'title' => array(
            'step' => 'general',
            'source' => 'CMPLZ_COOKIEBANNER',
            'type' => 'text',
            'label' => __("Cookie banner title", 'complianz-gdpr'),
            'placeholder' => __('Descriptive title of the cookiebanner'),
            'table' => true,
            'help' => __('For internal use only', 'complianz-gdpr'),
        ),

        'default' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'checkbox',
            'label' => __("Default cookie banner", 'complianz-gdpr'),
            'help' => __('When enabled, this is the cookie banner that is used for all visitors. Enabling it will disable this setting on the current default banner. Disabling it will enable randomly a different default banner.', 'complianz-gdpr'),
            'table' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => 'cmplz_ab_testing_enabled',
        ),

        'position' => array(
            'step' => 'general',
            'source' => 'CMPLZ_COOKIEBANNER',
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
        ),

        'theme' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
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
        ),

        'soft_cookiewall' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'checkbox',
            'default' => false,
            'label' => __("Show as soft cookie wall", 'complianz-gdpr'),
            'table' => true,
            'help' => sprintf(__("You can grey out the rest of the website, which makes it look like a cookie wall, but it is dismissible: a 'soft' cookie wall. This is not visible in preview, as it would prevent editing the options. Read more about the soft cookie wall in this %sarticle%s.", 'complianz-gdpr'), '<a href="https://complianz.io/the-soft-cookie-wall/" target="_blank">',"</a>"),
        ),

        'revoke' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'text',
            'default' => __("Settings", 'complianz-gdpr'),
            'label' => __("Settings text", 'complianz-gdpr'),
            'table' => true,
            'help' => __('The text that appears on the revoke button, which shows when a choice has been made in the cookie warning.', 'complianz-gdpr'),
            'condition' => array('use_categories' => false),
        ),

        'dismiss' => array(
            'step' => 'optin',
            'source' => 'CMPLZ_COOKIEBANNER',
            'type' => 'text',
            'default' => __("Functional only", 'complianz-gdpr'),
            'label' => __("Functional cookies text", 'complianz-gdpr'),
            'table' => true,
            'help' => __('When a users clicks this button, the message is dismissed, without activating all cookies. This can be described as a "dismiss" button or as an "activate functional cookies" only button.', 'complianz-gdpr'),
            'condition' => array('use_categories' => false),
            'callback_condition' => 'cmplz_uses_optin',

        ),

        'save_preferences' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("Save preferences", 'complianz-gdpr'),
            'label' => __("Save settings text", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => 'cmplz_uses_optin',

        ),

        'view_preferences' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("View preferences", 'complianz-gdpr'),
            'label' => __("View preferences text", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => 'cmplz_uses_optin',

        ),
        'category_functional' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("Functional cookies", 'complianz-gdpr'),
            'label' => __("Functional cookies text", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => 'cmplz_uses_optin',

        ),
        'category_all' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("Marketing", 'complianz-gdpr'),
            'label' => __("Accept all cookies text", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => 'cmplz_uses_optin',

        ),
        'category_stats' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("Statistics", 'complianz-gdpr'),
            'label' => __("Statistics cookies text", 'complianz-gdpr'),
            'help' => __("It depends on your settings if this category is necessary, so it will conditionally show", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => true),
            'callback_condition' => 'cmplz_uses_optin',
        ),

        'accept' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("All cookies", 'complianz-gdpr'),
            'help' => __('This text is shown in the button which accepts all cookies. These are generally marketing related cookies, so you could also name it "Marketing"', 'complianz-gdpr'),
            'label' => __("Accept all cookies text", 'complianz-gdpr'),
            'table' => true,
            'condition' => array('use_categories' => false),
            'callback_condition' => 'cmplz_uses_optin',
        ),

        'message_optin' => array(
            'step' => 'optin',
            'source' => 'CMPLZ_COOKIEBANNER',
            'type' => 'editor',
            'default' => __("We use cookies to optimize our website and our service.", 'complianz-gdpr'),
            'label' => __("Cookie message", 'complianz-gdpr'),
            'table' => true,
        ),

        'readmore_optin' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'text',
            'default' => __("Read more", 'complianz-gdpr'),
            'label' => __("Text on link to cookie policy", 'complianz-gdpr'),
            'table' => true,
            'callback_condition' => 'cmplz_uses_optin',

        ),

        'use_categories' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'checkbox',
            'label' => __("Use categories", 'complianz-gdpr'),
            'help' => __('With categories, you can let users choose which category of cookies they want to accept.', 'complianz-gdpr') . ' ' . __('Depending on your settings and cookies you use, there can be two or three categories. With Tag Manager you can use more, custom categories.', 'complianz-gdpr'),
            'table' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => 'cmplz_uses_optin',

        ),

        'tagmanager_categories' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optin',
            'type' => 'textarea',
            'label' => __("Custom Tag Manager categories", 'complianz-gdpr'),
            'help' => __('Enter your custom Tag Manager categories, comma separated. The first item will fire event cmplz_event_0, the second one will fire cmplz_event_1 and so on. At page load cmplz_event_functional is fired, Marketing fires cmplz_event_all', 'complianz-gdpr') . "<br><br>" . cmplz_tagmanager_conditional_helptext() . $this->read_more('https://complianz.io/configure-categories-tag-manager'),
            'table' => true,
            'placeholder' => __('First category, Second category', 'complianz-gdpr'),
            'callback_condition' => array(
                'fire_scripts_in_tagmanager' => 'yes',
                'compile_statistics' => 'google-tag-manager',
                'regions' => 'eu',
            ),
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),

        'hide_revoke' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'checkbox',
            'default' => false,
            'label' => __("Hide settings button", 'complianz-gdpr'),
            'table' => true,
            'help' => __('If you want to hide the revoke button, enable this check box. The revoke button will normally show after making a choice.', 'complianz-gdpr'),
            'comment' => __('If you hide this button, you should at least leave the option to revoke consent on your cookie policy or Do Not Sell My Personal Information page', 'complianz-gdpr'),
        ),

        /*
         *
         * US settings
         *
         * */

        'dismiss_on_scroll' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'checkbox',
            'label' => __("Dismiss on scroll", 'complianz-gdpr'),
            'help' => __('When dismiss on scroll is enabled, the cookie warning will be dismissed as soon as the user scrolls.', 'complianz-gdpr'),
            'table' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => 'cmplz_uses_optout',
        ),

        'dismiss_on_timeout' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'checkbox',
            'label' => __("Dismiss on timeout", 'complianz-gdpr'),
            'help' => __('When dismiss on time out is enabled, the cookie warning will be dismissed after 10 seconds, or the time you choose below.', 'complianz-gdpr'),
            'table' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'callback_condition' => 'cmplz_uses_optout',
        ),

        'dismiss_timeout' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'number',
            'label' => __("Timeout in seconds", 'complianz-gdpr'),
            'table' => true,
            'default' => 10, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
            'condition' => array(
                'dismiss_on_timeout' => true,
            ),
            'callback_condition' => 'cmplz_uses_optout',

        ),

        'accept_informational' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'text',
            'default' => __("Accept", 'complianz-gdpr'),
            'label' => __("Accept", 'complianz-gdpr'),
            'table' => true,
            'callback_condition' => 'cmplz_uses_optout',

        ),

        'message_optout' => array(
            'step' => 'optout',
            'source' => 'CMPLZ_COOKIEBANNER',
            'type' => 'editor',
            'default' => __("We use cookies to optimize our website and our service.", 'complianz-gdpr'),
            'label' => __("Cookie message", 'complianz-gdpr'),
            'table' => true,
        ),

        'readmore_optout' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'text',
            'default' => cmplz_us_cookie_statement_title(),
            'label' => __("Text on link to the US cookie statement", 'complianz-gdpr'),
            'table' => true,
            'callback_condition' => 'cmplz_uses_optout',

        ),

        'readmore_privacy' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'optout',
            'type' => 'text',
            'default' => __("Privacy statement", 'complianz-gdpr'),
            'label' => __("Text on link to privacy statement", 'complianz-gdpr'),
            'table' => true,
            'callback_condition' => 'cmplz_uses_optout',

        ),

        'popup_background_color' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#37a8de',
            'label' => __("Popup background color", 'complianz-gdpr'),
            'table' => true,
        ),

        'popup_text_color' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Popup text color", 'complianz-gdpr'),
            'table' => true,
        ),
        'button_background_color' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Button background color", 'complianz-gdpr'),
            'table' => true,
        ),

        'button_text_color' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#37a8de',
            'label' => __("Button text color", 'complianz-gdpr'),
            'table' => true,
        ),

        'border_color' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Border color", 'complianz-gdpr'),
            'table' => true,
        ),

        'use_custom_cookie_css' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'checkbox',
            'label' => __("Use Custom CSS", 'complianz-gdpr'),
            'default' => false,
            'table' => true,
        ),

        'custom_css' => array(
            'source' => 'CMPLZ_COOKIEBANNER',
            'step' => 'general',
            'type' => 'css',
            'label' => __("Custom CSS", 'complianz-gdpr'),
            'default' => '.cc-message{} /* styles for the message box */' . "\n" . '.cc-dismiss{} /* styles for the dismiss button */' . "\n" . '.cc-allow{} /* styles for the accept button */' . "\n" . '.cc-window{} /* styles for the popup banner */' . "\n" . '.cc-window .cc-category{} /* styles for categories*/' . "\n" . '.cc-window .cc-check{} /* styles for the checkboxes with categories */' . "\n" . '.cc-revoke{} /* styles for the revoke / settings popup */'. "\n" . '.cmplz-soft-cookiewall{} /* styles for the soft cookie wall / settings popup */' ,
            'table' => true,
            'condition' => array('use_custom_cookie_css' => true),
        ),

    );
