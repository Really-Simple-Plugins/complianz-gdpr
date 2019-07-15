<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages = array(
    'cookie-statement' => array(
        'title' => __("Cookie policy", 'complianz-gdpr'),
        'public' => true,
        'condition' => array(
            'regions' => 'eu',
            'cookie-policy-type' => 'default',
        ),
    ),

    'cookie-statement-us' => array(
        'title' => cmplz_us_cookie_statement_title(),
        'public' => true,
        'condition' => array(
            'regions' => 'us',
            'cookie-policy-type' => 'default',
        ),
    ),
);


