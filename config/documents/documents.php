<?php
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages = array(
    'cookie-statement' => array(
        'title' => __("Cookie policy", 'complianz'),
        'public' => true,
        'condition' => array(
            'regions' => 'eu',
        ),
    ),
    'cookie-statement-us' => array(
        'title' => __("Do Not Sell My Personal Information", 'complianz'),
        'public' => true,
        'condition' => array(
            'regions' => 'us',
        ),
    ),
);


