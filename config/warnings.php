<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->warning_types = array(
    'complianz-gdpr-feature-update' => array(
        'type' => 'general',
        'label_error' => __('The Complianz Privacy Suite plugin has new features. Please check the wizard to see if all your settings are still up to date.', 'complianz'),
    ),
    'no-dnt' => array(
        'type' => 'general',
        'label_ok' => __('Do Not Track is respected.', 'complianz'),
        'label_error' => sprintf(__('The browser setting Do Not Track is not respected yet - (%spremium%s)', 'complianz'), '<a  target="_blank" href="https://complianz.io">', '</a>')
    ),
    'wizard-incomplete' => array(
        'type' => 'general',
        'label_ok' => __('The wizard has been completed.', 'complianz'),
        'label_error' => __('Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz')
    ),
    'cookies-changed' => array(
        'type' => 'general',
        'label_ok' => __('No cookie changes have been detected.', 'complianz'),
        'label_error' => __('Cookie changes have been detected.', 'complianz') . " " . sprintf(__('Please review step %s of the wizard for changes in cookies.', 'complianz'), STEP_COOKIES),
    ),

    'no-ssl' => array(
        'type' => 'general',
        'label_ok' => __("Great! You're already on SSL!", 'complianz'),
        'label_error' => sprintf(__("You don't have SSL on your site yet. Most hosting companies can install SSL for you, which you can quickly enable with %sReally Simple SSL%s", 'complianz'), '<a target="_blank" href="https://wordpress.org/plugins/really-simple-ssl/">', '</a>'),
    ),
    'plugins-changed' => array(
        'type' => 'general',
        'label_ok' => __('No plugin changes have been detected.', 'complianz'),
        'label_error' => __('Plugin changes have been detected.', 'complianz') . " " . sprintf(__('Please review step %s of the wizard for changes in plugin privacy statements and cookies.', 'complianz'), $this->steps_to_review_on_changes),
    ),
    'ga-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Google Analytics is being used, but is not configured in Complianz.', 'complianz'),
    ),
    'gtm-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Google Tagmanager is being used, but is not configured in Complianz.', 'complianz'),
    ),
    'matomo-needs-configuring' => array(
        'type' => 'general',
        'label_error' => __('Matomo is being used, but is not configured in Complianz.', 'complianz'),
    ),

);
