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

        'use_custom_document_css' => array(
            'page' => 'settings',
            'type' => 'checkbox',
            'label' => __("Add custom document CSS", 'complianz'),
            'table' => true,
            'default' => false,
            'help' => __("Enable if you want to add custom CSS for the documents",'complianz'),
        ),

        'custom_document_css' => array(
            'page' => 'settings',
            'type' => 'css',
            'label' => __("Custom document CSS", 'complianz'),
            'default' => '#cmplz-document h3 {} /* titles in complianz documents */'."\n".'#cmplz-document .subtitle {} /* subtitles */'."\n".'#cmplz-document h3.annex{} /* titles in annexes */'."\n".'#cmplz-document .subtitle.annex{} /* subtitles in annexes */'."\n".'#cmplz-document, #cmplz-document p, #cmplz-document span, #cmplz-document li {} /* text */'."\n".'#cmplz-document table {} /* table styles */'."\n".'#cmplz-document td {} /* row styles */',
            'help' => __('Add your own custom document css here','complianz'),
            'table' => true,
            'condition' => array('use_custom_document_css' => true),
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


