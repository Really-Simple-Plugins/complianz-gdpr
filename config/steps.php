<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->steps = array(
    'wizard' =>
        array(
            STEP_COMPANY => array(
                "title" => __("General", 'complianz'),
                'intro' => _x( ' The information you enter here will be added to your cookie policy.', 'intro company info', 'complianz' ),

            ),
            STEP_COOKIES => array("title" => __("Cookies", 'complianz'),
                //'intro' => _x('explanatory text', 'intro cookies', 'complianz'),
                'sections' => array(
                    1 => array('title' => __('Cookie scan', 'complianz'),
                        //    'intro' => _x('explanatory text', 'intro scan', 'complianz'),
                    ),
                    2 => array('title' => __('Cookie usage', 'complianz'),
                        //   'intro' => _x('explanatory text', 'intro cookie usage', 'complianz'),
                    ),
                    3 => array('title' => __('Cookie enabling scripts', 'complianz'),
                        'intro' => _x('If you use scripts on your site which enable cookies, and you want those cookies only enabled when the user accepts the cookie warning, you should add those scripts here.', 'intro cookie enabling scripts', 'complianz'),),
                    4 => array('title' => __('Used cookies', 'complianz'),
                        //    'intro' => _x('explanatory text', 'intro used cookies', 'complianz'),
                    ),
                ),
            ),
            STEP_MENU => array("title" => __("Menu", 'complianz'),
                'intro' => _x("Your generated documents should be placed in one of your website's menu's. To do this, select the menu you want your pages to show, or skip this step.", 'intro finish', 'complianz'),),
            STEP_FINISH => array("title" => __("Finish", 'complianz'),
                //'intro' => _x('explanatory text', 'intro finish', 'complianz'),),
            ),
        ),
);
$this->steps = apply_filters('cmplz_steps', $this->steps);