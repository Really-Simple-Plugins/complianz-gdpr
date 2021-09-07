<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages = array(
	'eu' => array(
	    'cookie-statement' => array(
	        'title' => __("Cookie Policy (EU)", 'complianz-gdpr'),
	        'public' => true,
	        'document_elements' => '',
	        'condition' => array(
	            'regions' => 'eu',
	            'cookie-statement' => 'generated',
	        ),
	    ),
	),
	'us' => array(
		'cookie-statement' => array(
			'title' => cmplz_us_cookie_statement_title(),
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'us',
				'cookie-statement' => 'generated',
			),
		),
	),
	'uk'=> array(
		'cookie-statement' => array(
			'title' => __("Cookie Policy (UK)", 'complianz-gdpr'),
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'uk',
				'cookie-statement' => 'generated',
			),
		),
	),
	'ca' => array(
		'cookie-statement' => array(
			'title' => __("Cookie Policy (CA)", 'complianz-gdpr'),
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'ca',
				'cookie-statement' => 'generated',
			),
		),
	),
	'au' => array(
		'cookie-statement' => array(
			'title' => 'Cookie Policy (AU)',
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'au',
				'cookie-statement' => 'generated',
			),
		),
	),
	'za' => array(
		'cookie-statement' => array(
			'title' => __("Cookie Policy (ZA)", 'complianz-gdpr'),
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'za',
				'cookie-statement' => 'generated',
			),
		),
	),
	'br' => array(
		'cookie-statement' => array(
			'title' => __("Cookie Policy (BR)", 'complianz-gdpr'),
			'public' => true,
			'document_elements' => '',
			'condition' => array(
				'regions' => 'br',
				'cookie-statement' => 'generated',
			),
		),
	),
);
