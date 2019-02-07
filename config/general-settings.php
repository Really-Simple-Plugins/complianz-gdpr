<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->fields = $this->fields + array(
        'use_document_css' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Use document CSS", 'complianz-gdpr'),
            'table' => true,
            'default' => true,
            'help' => __("Disable if you don't want the default Complianz document CSS to load",'complianz-gdpr'),
        ),

        'use_custom_document_css' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Add custom document CSS", 'complianz-gdpr'),
            'table' => true,
            'default' => false,
            'help' => __("Enable if you want to add custom CSS for the documents",'complianz-gdpr'),
        ),

        'custom_document_css' => array(
            'page' => 'settings',
            'type' => 'css',
            'label' => __("Custom document CSS", 'complianz-gdpr'),
            'default' => '#cmplz-document h3 {} /* titles in complianz documents */'."\n".'#cmplz-document .subtitle {} /* subtitles */'."\n".'#cmplz-document h3.annex{} /* titles in annexes */'."\n".'#cmplz-document .subtitle.annex{} /* subtitles in annexes */'."\n".'#cmplz-document, #cmplz-document p, #cmplz-document span, #cmplz-document li {} /* text */'."\n".'#cmplz-document table {} /* table styles */'."\n".'#cmplz-document td {} /* row styles */',
            'help' => __('Add your own custom document css here','complianz-gdpr'),
            'table' => true,
            'condition' => array('use_custom_document_css' => true),
        ),

        'disable_cookie_block' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Disable cookie blocker", 'complianz-gdpr'),
            'default' => false,
            'help' => __('Not recommended. You can disable the brute force blocking of third party scripts if you encounter issues with styling on your front-end.','complianz-gdpr'),
            'table' => true,
        ),

        'blocked_content_text' => array(
            'page' => 'settings',
            'type' => 'text',
            'label' => __("Blocked content text", 'complianz-gdpr'),
            'default' => _x('Click to accept cookies and enable this content','Accept cookies on blocked content','complianz-gdpr'),
            'help' => __('The blocked content text appears when for example a Youtube video is embeded. Because Youtube places cookie which require consent, the video will be blocked initially, with an explanatory text.','complianz-gdpr'),
            'table' => true,
        ),

        'notification_from_email' => array(
            'page' => 'settings',
            'type' => 'email',
            'label' => __("Notification sender email address", 'complianz-gdpr'),
            'default' => false,
            'help' => __("When emails are sent, you can choose the sender email address here. Please note that it should have this website's domain as sender domain, otherwise the server might block the email from being sent.",'complianz-gdpr'),
            'table' => true,
            'callback_condition' => array(
                //'regions' => 'us',
                'purpose_personaldata' => 'selling-data-thirdparty',
            ),
        ),

        'notification_email_subject' => array(
            'page' => 'settings',
            'type' => 'text',
            'label' => __("Notification email subject", 'complianz-gdpr'),
            'default' => __('Your request has been processed','complianz-gdpr'),
            'table' => true,
            'callback_condition' => array(
                //'regions' => 'us',
                'purpose_personaldata' => 'selling-data-thirdparty',
            ),
        ),

        'notification_email_content' => array(
            'page' => 'settings',
            'type' => 'wysiwyg',
            'label' => __("Notification email content", 'complianz-gdpr'),
            'default' => __('Hi {name}','complianz-gdpr')."<br><br>".__('Your request has been processed successfully.','complianz-gdpr')."<br><br>"._x('Regards,','email signature','complianz-gdpr').'<br><br>{blogname}',
            'table' => true,
            'callback_condition' => array(
                //'regions' => 'us',
                'purpose_personaldata' => 'selling-data-thirdparty',
            ),
        ),

        'export_settings' => array(
            'page' => 'settings',
            'disabled' =>true,
            'type' => 'button',
            'action' => 'cmplz_export_settings',
            'post_get' => 'get',
            'label' => __("Export settings", 'complianz-gdpr'),
            'table' => true,
            'comment' => sprintf(__('If you want to export your settings, please check out the %spremium version%s', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io">', "</a>"),
        ),

        'import_settings' => array(
            'page' => 'settings',
            'disabled' => true,
            'type' => 'upload',
            'action' => 'cmplz_import_settings',
            'label' => __("Import settings", 'complianz-gdpr'),
            'table' => true,
            'comment' => sprintf(__('If you want to import your settings, please check out the %spremium version%s', 'complianz-gdpr'), '<a target="_blank" href="https://complianz.io">', "</a>"),
        ),

        'reset_settings' => array(
            'warn' => __('Are you sure? This will remove all Complianz data.','complianz-gdpr'),
            'page' => 'settings',
            'type' => 'button',
            'action' => 'cmplz_reset_settings',
            'post_get' => 'post',
            'label' => __("Reset settings", 'complianz-gdpr'),
            'table' => true,
            'help' => __('This will reset all settings to defaults. All data in the Complianz plugin will be deleted', 'complianz-gdpr'),
        ),


    );


