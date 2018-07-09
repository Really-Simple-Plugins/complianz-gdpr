<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->fields = $this->fields + array(
        'use_document_css' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Use document CSS", 'complianz'),
            'table' => true,
            'default' => true,
            'help' => __("Disable if you don't want the default Complianz GDPR document CSS to load",'complianz'),
        ),
        'disable_cookie_block' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Disable cookie blocker", 'complianz'),
            'default' => false,
            'help' => __('Not recommended. You can disable the brute force blocking of third party scripts if you encounter issues with styling on your front-end.','complianz'),
            'table' => true,
        ),
    );