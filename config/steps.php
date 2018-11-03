<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->steps = array(
    'wizard' =>
        array(
            STEP_COMPANY => array(
                "id" => "company",
                "title" => __("General", 'complianz'),
                'intro' => '<h1>'.__("Hi there!", 'complianz').'</h1><p>'.
                    _x('Welcome to the Complianz Privacy Suite Wizard.','intro first step', 'complianz').'</p><p>'.
                    _x('We have tried to make our Wizard as simple and fast as possible, but you might think it is a bit much or maybe even boring. Although these questions are all necessary, if there’s any way you think we can make it more fun, please let us know!','intro first step', 'complianz').'<br>'.
                    _x('The answers in the first step of the wizard are needed to configure your documents and consent banner specifically to your needs.','intro first step', 'complianz').'</p><p>'.
                    _x('Please note that you can always save and finish the wizard later (if you need a break), use our documentation for additional information or log a support ticket if you need our assistance.', 'intro first step', 'complianz').'</p>',

                'sections' => array(
                    2 => array('title' => __('Visitors', 'complianz'),
                        'intro' => _x('To determine what laws apply, we need to know where your website visitors are coming from.', 'intro company info', 'complianz'),
                    ),
                    3 => array('title' => __('Company information', 'complianz'),
                        'intro' => _x('We need some company information to be able to generate your documents.', 'intro company info', 'complianz'),
                    ),
                    5 => array(
                        'region' => 'us',
                        'title' => __('Purpose', 'complianz'),
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
                        'intro' =>
                            '<h1>'._x('Almost there. Let’s have a look at your cookies.', 'intro scan', 'complianz').'</h1>'.
                            '<p>'._x('The cookie scan will request several pages on your site to check if cookies are placed and will check the html of your site for known third party scripts. The cookie scan will be recurring weekly to keep you up-to-date!', 'intro scan', 'complianz').'</p>',
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
                'intro' =>
                            '<h1>'._x("Get ready to finish your configuration.", 'intro menu', 'complianz').'</h1>'.
                            '<p>'._x("Your documents have been generated. You are able to add them to your menu directly or do it manually after the wizard is finished. Visit Pages to find your legal documents, after finishing the wizard.", 'intro menu', 'complianz').'</p>',

                ),
            STEP_FINISH => array("title" => __("Finish", 'complianz'),
            ),
        ),
);