<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages['eu'] = array(
    'cookie-statement' => array(
        'title' => __("Cookie Policy (EU)", 'complianz-gdpr'),
        'public' => true,
        'document_elements' => '',
        'condition' => array(
            'regions' => 'eu',
            'cookie-statement' => 'generated',
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
            'cookie-statement' => 'generated',
        ),
    ),);

$this->pages['uk'] = array(
	'cookie-statement' => array(
        'title' => __("Cookie Policy (UK)", 'complianz-gdpr'),
        'public' => true,
        'document_elements' => '',
        'condition' => array(
            'regions' => 'uk',
            'cookie-statement' => 'generated',
        ),
    ),
);

$this->pages['ca'] = array(
	'cookie-statement' => array(
	    'title' => __("Cookie Policy (CA)", 'complianz-gdpr'),
	    'public' => true,
	    'document_elements' => '',
	    'condition' => array(
		    'regions' => 'ca',
		    'cookie-statement' => 'generated',
	    ),
    ),
);
