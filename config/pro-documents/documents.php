<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages = $this->pages + array(
    'privacy-statement' => array(
        'title' => __("Privacy statement (EU)", 'complianz'),
        'condition' => array(
            'privacy-statement' => 'yes',
            'regions' => 'eu',
        ),
        'public' => true,
    ),

    'privacy-statement-us' => array(
        'title' => __("Privacy statement (US)", 'complianz'),
        'condition' => array(
            'privacy-statement' => 'yes',
            'regions' => 'us',
        ),
        'public' => true,
    ),
    'privacy-statement-children-us' => array(
        'title' => __("Privacy Statement for Children", 'complianz'),
        'condition' => array(
            'privacy-statement' => 'yes',
            'targets-children' => 'yes',
            'regions' => 'us',
        ),
        'public' => true,
    ),
    'disclaimer' => array(
        'title' => __("Disclaimer", 'complianz'),
        'condition' => array('disclaimer' => 'yes'),
        'public' => true,
    ),
    'processing-eu' => array(
        'title' => _x('Processing agreement', 'Title on processing agreement page', 'complianz'),
        'public' => false,
    ),
    'processing-us' => array(
        'title' => _x('Processing agreement', 'Title on processing agreement page', 'complianz'),
        'public' => false,
    ),
    'dataleak-eu' => array(
        'title' => __("Dataleak", 'complianz'),
        'public' => false,
    ),
    'dataleak-us' => array(
        'title' => __("Dataleak", 'complianz'),
        'public' => false,
    ),


);


