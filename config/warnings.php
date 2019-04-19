<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->warning_types = array(
    'complianz-gdpr-feature-update' => array(
        'type' => 'general',
        'label_error' => __('The Complianz plugin has new features. Please check the wizard to see if all your settings are still up to date.', 'complianz-gdpr'),
    ),
    'no-dnt' => array(
        'type' => 'general',
        'label_ok' => __('Do Not Track is respected.', 'complianz-gdpr'),
        'label_error' => sprintf(__('The browser setting Do Not Track is not respected yet - (%spremium%s)', 'complianz-gdpr'), '<a  target="_blank" href="https://complianz.io">', '</a>')
    ),
    'wizard-incomplete' => array(
        'type' => 'general',
        'label_ok' => __('The wizard has been completed.', 'complianz-gdpr'),
        'label_error' => __('Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz-gdpr')
    ),
    'cookies-changed' => array(
        'type' => 'general',
        'label_ok' => __('No cookie changes have been detected.', 'complianz-gdpr'),
        'label_error' => __('Cookie changes have been detected.', 'complianz-gdpr') . " " . sprintf(__('Please review step %s of the wizard for changes in cookies.', 'complianz-gdpr'), STEP_COOKIES),
    ),

    'no-ssl' => array(
        'type' => 'general',
        'label_ok' => __("Great! You're already on SSL!", 'complianz-gdpr'),
        'label_error' => sprintf(__("You don't have SSL on your site yet. Most hosting companies can install SSL for you, which you can quickly enable with %sReally Simple SSL%s", 'complianz-gdpr'), '<a target="_blank" href="https://wordpress.org/plugins/really-simple-ssl/">', '</a>'),
    ),
    'plugins-changed' => array(
        'type' => 'general',
        'label_ok' => __('No plugin changes have been detected.', 'complianz-gdpr'),
        'label_error' => __('Plugin changes have been detected.', 'complianz-gdpr') . " " . sprintf(__('Please review step %s of the wizard for changes in plugin privacy statements and cookies.', 'complianz-gdpr'), $this->steps_to_review_on_changes),
    ),
    'ga-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Google Analytics is being used, but is not configured in Complianz.', 'complianz-gdpr'),
    ),
    'gtm-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Google Tagmanager is being used, but is not configured in Complianz.', 'complianz-gdpr'),
    ),
    'matomo-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Matomo is being used, but is not configured in Complianz.', 'complianz-gdpr'),
    ),
    'docs-need-updating' => array(
        'type'=> 'general',
        'label_error' => __('Your documents have not been updated in the past 12 months. Run the wizard to check your settings.','complianz-gdpr'),
    ),
//    'cookies-incomplete' => array(
//        'type'=> 'general',
//        'label_error' => __('You have cookies with incomplete descriptions.', 'complianz-gdpr') . " " . sprintf(__('Please review the %scookies%s in the wizard to complete the missing fields.', 'complianz-gdpr'), '<a href="'.add_query_arg(array('page'=>'cmplz-wizard', 'step'=>STEP_COOKIES,'section'=>3),admin_url('admin.php')).'">','</a>'),
//    )
);
