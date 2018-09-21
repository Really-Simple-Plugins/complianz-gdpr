<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->warning_types = array(
    'complianz-gdpr-feature-update' => array(
        'label_error' => __('The Complianz GDPR plugin has new features. Please check the wizard to see if all your settings are still up to date.', 'complianz'),
    ),
    'wizard-incomplete' => array(
        'label_ok' => __('The wizard has been completed.', 'complianz'),
        'label_error' => __('The wizard is not completed yet.', 'complianz')
    ),
    'no-cookie-policy' => array(
        'label_ok' => sprintf(__('Great, you have a %scookie policy%s!', 'complianz'), '<a href="'.get_option('cmplz_cookie_policy_url').'">', '</a>'),
        'label_error' => __('You do not have a cookie policy validated by Complianz GDPR yet.', 'complianz')
    ),

    'cookies-changed' => array(
        'label_ok' => __('No cookie changes have been detected.', 'complianz'),
        'label_error' => __('Cookie changes have been detected.', 'complianz') . " " . sprintf(__('Please review step %s of the wizard for changes in cookies.', 'complianz'), STEP_COOKIES),
    ),

    'no-ssl' => array(
        'label_ok' => __("Great! You're already on SSL!", 'complianz'),
        'label_error' => sprintf(__("You don't have SSL on your site yet. Most hosting companies can install SSL for you, which you can quickly enable with %sReally Simple SSL%s", 'complianz'), '<a target="_blank" href="https://wordpress.org/plugins/really-simple-ssl/">', '</a>'),
    ),
    'plugins-changed' => array(
        'label_ok' => __('No plugin changes have been detected.', 'complianz'),
        'label_error' => __('Plugin changes have been detected.', 'complianz') . " " . sprintf(__('Please review step %s of the wizard for changes in plugin privacy statements and cookies.', 'complianz'), CMPLZ_REVIEW_STEPS),
    ),
    'ga-needs-configuring' => array(
        'label_error' => __('Google Analytics is being used, but is not configured in Complianz.', 'complianz'),
    ),
    'gtm-needs-configuring' => array(
        'label_error' => __('Google Tagmanager is being used, but is not configured in Complianz.', 'complianz'),
    ),
    'matomo-needs-configuring' => array(
        'label_error' => __('Matomo is being used, but is not configured in Complianz.', 'complianz'),
    ),

);
