<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->fields = $this->fields + array(
        'use_country' => array(
            'page' => 'cookie_settings',
            'type' => 'checkbox',
            'label' => __("Use geolocation", 'complianz'),
            'comment' => $this->premium.__('If enabled, the cookie warning will not show for countries without a cookie law.','complianz'),
            'table' => true,
            'disabled' => true,
            'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
        ),
        'popup_background_color' => array(
            'page' => 'cookie_settings',
            'type' => 'colorpicker',
            'default' => '#37a8de',
            'label' => __("Popup background color", 'complianz'),
            'table' => true,

        ),
        'popup_text_color' => array(
            'page' => 'cookie_settings',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Popup text color", 'complianz'),
            'table' => true,
        ),
        'button_background_color' => array(
            'page' => 'cookie_settings',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Button background color", 'complianz'),
            'table' => true,
        ),
        'button_text_color' => array(
            'page' => 'cookie_settings',
            'type' => 'colorpicker',
            'default' => '#37a8de',
            'label' => __("Button text color", 'complianz'),
            'table' => true,
        ),

        'border_color' => array(
            'page' => 'cookie_settings',
            'type' => 'colorpicker',
            'default' => '#fff',
            'label' => __("Border color", 'complianz'),
            'table' => true,
        ),

        'cookie_expiry' => array(
            'page' => 'cookie_settings',
            'type' => 'number',
            'default' => 30,
            'label' => __("Cookie warning expiration in days", 'complianz'),
            'table' => true,
        ),

        'message' => array(
            'page' => 'cookie_settings',
            'type' => 'textarea',
            'translatable' => true,
            'default' => __("We use cookies to optimize our website and our service.", 'complianz'),
            'label' => __("Cookie message", 'complianz'),
            'table' => true,
        ),
        'dismiss' => array(
            'page' => 'cookie_settings',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Deny", 'complianz'),
            'label' => __("Dismiss text", 'complianz'),
            'table' => true,
        ),
        'accept' => array(
            'page' => 'cookie_settings',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Accept", 'complianz'),
            'label' => __("Accept text", 'complianz'),
            'table' => true,
        ),
        'revoke' => array(
            'page' => 'cookie_settings',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Revoke", 'complianz'),
            'label' => __("Revoke text", 'complianz'),
            'table' => true,
            'help'  => __('The text that appears on the revoke button, when the policy is accepted','complianz'),
        ),
        'readmore' => array(
            'page' => 'cookie_settings',
            'type' => 'text',
            'translatable' => true,
            'default' => __("Read more", 'complianz'),
            'label' => __("Read more text", 'complianz'),
            'table' => true,
        ),
        'position' => array(
            'page' => 'cookie_settings',
            'type' => 'select',
            'label' => __("Popup position", 'complianz'),
            'options' => array(
                'bottom' => __("Banner bottom", 'complianz'),
                'top' => __("Banner top", 'complianz'),
                'bottom-left' => __("Floating left", 'complianz'),
                'bottom-right' => __("Floating right", 'complianz'),
                'static' => __("Push down", 'complianz'),
            ),
            'default' => 'bottom',
            'table' => true,
        ),
        'theme' => array(
            'page' => 'cookie_settings',
            'type' => 'select',
            'label' => __("Style", 'complianz'),
            'options' => array(
                'block' => __("Block", 'complianz'),
                'classic' => __("Classic", 'complianz'),
                'edgeless' => __("Edgeless", 'complianz'),
            ),
            'default' => 'edgeless',
            'table' => true,
        ),
//        'static' => array(
//            'page' => 'cookie_settings',
//            'type' => 'select',
//            'label' => __("Push down/overlay", 'complianz'),
//            'options' => array(
//                'false' => __("Default", 'complianz'),
//                'true' => __("Push down", 'complianz'),
//            ),
//            'default' => 'false',
//            'table' => true,
//        ),
//        'height' => array(
//            'page' => 'cookie_settings',
//            'type' => 'number',
//            'label' => __("Height of banner", 'complianz'),
//            'default' => '',
//            'table' => true,
//        ),

    );