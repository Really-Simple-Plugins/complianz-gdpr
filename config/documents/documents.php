<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages['eu'] = array(

    'cookie-statement' => array(
        'title' => __("Cookie policy (EU)", 'complianz-gdpr'),
        'public' => true,
        'document_elements' => '',
        'condition' => array(
            'regions' => 'eu',
            'cookie-policy-type' => 'default',
        ),
    ),
);

$this->pages['us'] = array(
	'cookie-statement' => array(
        'title' => cmplz_us_cookie_statement_title(),
        'public' => true,
        'document_elements' => '',
        'condition' => array(
            'regions' => 'us',
            'cookie-policy-type' => 'default',
        ),
    ),);

$this->pages['uk'] = array(
	'cookie-statement' => array(
        'title' => __("Cookie policy (UK)", 'complianz-gdpr'),
        'public' => true,
        'document_elements' => '',
        'condition' => array(
            'regions' => 'uk',
            'cookie-policy-type' => 'default',
        ),
    ),
);

$this->pages['ca'] = array(
	'cookie-statement' => array(
	    'title' => __("Cookie policy (CA)", 'complianz-gdpr'),
	    'public' => true,
	    'document_elements' => '',
	    'condition' => array(
		    'regions' => 'ca',
		    'cookie-policy-type' => 'default',
	    ),
    ),
);



