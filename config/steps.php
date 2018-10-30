<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->steps = array(
    'wizard' =>
        array(
            STEP_COMPANY => array(
                "id" => "company",
                "title" => __("General", 'complianz'),
                'intro' => _x('We need some company information to be able to generate your documents.', 'intro company info', 'complianz'),
                'sections' => array(
                    2 => array('title' => __('Visitors', 'complianz'),
                        'intro' => _x('To determine what laws apply, we need to know where your website visitors are coming from.', 'intro company info', 'complianz'),
                    ),
                    3 => array('title' => __('Company information', 'complianz'),
                        'intro' => _x('We need some company information to be able to generate your documents.', 'intro company info', 'complianz'),
                    ),
                    5 => array('title' => __('Purpose', 'complianz'),
                        //'intro' => _x( 'In this section information regarding the purpose of processing personal data is asked.  ', 'intro purpose', 'complianz' ),
                    ),

                ),
            ),

            STEP_COOKIES => array(
                "title" => __("Cookies", 'complianz'),
                "id" => "cookies",
                //'intro' => _x('explanatory text', 'intro cookies', 'complianz'),
                'sections' => array(
                    1 => array('title' => __('Cookie scan', 'complianz'),
                        'intro' => _x('If you have an adblocker enabled, please disable it to get the best results.', 'intro scan', 'complianz'),
                    ),
                    2 => array('title' => __('Cookie usage', 'complianz'),
                        //'intro' => _x('You can add scripts that should be activated whenever someone accepts the cookie policy. In the third party iframes and scripts sections, you can add URLs from third party scripts that should be blocked until the cookie warning is accepted.', 'intro cookie usage', 'complianz'),
                    ),
                    3 => array('title' => __('Used cookies', 'complianz'),
                        'intro' => _x('With the automatic cookie scan most first party cookies should be detected. Below you can choose if it needs to be shown on the cookie policy, add more detailed information, or add cookies which are still missing.', 'intro used cookies', 'complianz'),
                    ),
//                    4 => array('title' => __('Script center', 'complianz'),
//                        'intro' => _x('Advanced users only. Only if you have scripts which are not supported by the default functionality should you need this step.', 'intro cookie usage', 'complianz')
//                    ),
                ),
            ),
            STEP_MENU => array(
                "id" => "menu",
                "title" => __("Menu", 'complianz'),
                //'intro' => _x("Your generated documents should be placed in one of your website's menu's. To do this, select the menu you want your pages to show, or skip this step.", 'intro finish', 'complianz'),
            ),
            STEP_FINISH => array("title" => __("Finish", 'complianz'),
                //'intro' => _x('explanatory text', 'intro finish', 'complianz'),),
            ),
        ),
);