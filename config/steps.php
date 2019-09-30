<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->steps = array(
    'wizard' =>
        array(
            STEP_COMPANY => array(
                "id" => "company",
                "title" => __("General", 'complianz-gdpr'),
                'intro' => '<h1>'.__("Hi there!", 'complianz-gdpr').'</h1><p>'.
                    _x('Welcome to the Complianz wizard.','intro first step', 'complianz-gdpr').'</p><p>'.
                    sprintf(_x('We have tried to make our Wizard as simple and fast as possible. Although these questions are all necessary, if there’s any way you think we can improve the plugin, please let us %sknow%s!','intro first step', 'complianz-gdpr'),'<a target="_blank" href="https://complianz.io/contact">', '</a>').'<br>'.
                    _x('The answers in the first step of the wizard are needed to configure your documents and consent banner specifically to your needs.','intro first step', 'complianz-gdpr').'</p><p>'.
                    _x('Please note that you can always save and finish the wizard later (if you need a break), use our %sdocumentation%s for additional information or log a support ticket if you need our assistance.', 'intro first step', 'complianz-gdpr').'</p>',

                'sections' => array(
                    1 => array(
                        'id' => 'general',
                        'title' => __('Documents', 'complianz-gdpr'),
                        'intro' => '<h1>'.__("Hi there!", 'complianz-gdpr').'</h1><p>'.
                            _x('Welcome to the Complianz Privacy Suite Wizard.','intro first step', 'complianz-gdpr').'</p><p>'.
                            sprintf(_x('We have tried to make our Wizard as simple and fast as possible. Although these questions are all necessary, if there’s any way you think we can improve the plugin, please let us %sknow%s!','intro first step', 'complianz-gdpr'),'<a target="_blank" href="https://complianz.io/contact">', '</a>').'<br>'.
                            _x('The answers in the first step of the wizard are needed to configure your documents and consent banner specifically to your needs.','intro first step', 'complianz-gdpr').'</p><p>'.
                            sprintf(_x('Please note that you can always save and finish the wizard later (if you need a break), use our %sdocumentation%s for additional information or log a %ssupport ticket%s if you need our assistance.', 'intro first step', 'complianz-gdpr'),'<a target="_blank" href="https://complianz.io/documentation">', '</a>','<a target="_blank" href="https://complianz.io/support">', '</a>').'</p>',

                    ),
                    2 => array('title' => __('Visitors', 'complianz-gdpr'),
                        'intro' => _x('To determine what laws apply, we need to know where your website visitors are coming from.', 'intro company info', 'complianz-gdpr'),
                    ),
                    3 => array('title' => __('Website information', 'complianz-gdpr'),
                        'intro' => _x('We need some information to be able to generate your documents.', 'intro company info', 'complianz-gdpr'),
                    ),
                    5 => array(
                        'title' => __('Purpose', 'complianz-gdpr'),
                        //'intro' => _x( 'In this section information regarding the purpose of processing personal data is asked.  ', 'intro purpose', 'complianz-gdpr'),
                    ),
                    7 => array(
                        'region' => array('us'),
                        'id' => 'details_per_purpose_us',
                        'title' => __('Details per purpose - US', 'complianz-gdpr'),
                    ),

                ),
            ),

            STEP_COOKIES => array(
                "title" => __("Cookies", 'complianz-gdpr'),
                "id" => "cookies",
                'sections' => array(
                    1 => array('title' => __('Cookie scan', 'complianz-gdpr'),
                        'intro' =>
                            '<h1>'._x('Almost there. Let’s have a look at your cookies.', 'intro scan', 'complianz-gdpr').'</h1>'.
                            '<p>'._x('The cookie scan will request several pages on your site to check if cookies are placed and will check the html of your site for known third party scripts. The cookie scan will be recurring weekly to keep you up-to-date!', 'intro scan', 'complianz-gdpr').'</p>',
                    ),
                    2 => array('title' => __('Statistics', 'complianz-gdpr'),),
                    3 => array('title' => __('Statistics - configuration', 'complianz-gdpr'),),
                    4 => array('title' => __('Integrations', 'complianz-gdpr'),
                        //'intro' => _x('You can add scripts that should be activated whenever someone accepts the cookie policy. In the third party iframes and scripts sections, you can add URLs from third party scripts that should be blocked until the cookie warning is accepted.', 'intro cookie usage', 'complianz-gdpr'),
                    ),

                    5 => array('title' => __('Used cookies', 'complianz-gdpr'),
                        'intro' => _x('With the automatic cookie scan most first party cookies should be detected. Below you can choose if it needs to be shown on the cookie policy, add more detailed information, or add cookies which are still missing.', 'intro used cookies', 'complianz-gdpr').
                        '<p>'._x("The following icons are used in the list:", 'intro used cookies', 'complianz-gdpr').
                        '<br>'.sprintf(_x("%s Marked as a functional cookie",'intro used cookies', 'complianz-gdpr'), '<i class="fa fa-code"></i>').
                        '<br>'.sprintf(_x("%s The cookie will be listed on the website.", 'intro used cookies', 'complianz-gdpr'),'<i class="fa fa-file"></i>').
                        '<br>'.sprintf(_x("%s All fields are completed", 'intro used cookies', 'complianz-gdpr'),'<i class="fa fa-check"></i>').
                        '<br>'.sprintf(_x("%s Missing fields", 'intro used cookies', 'complianz-gdpr'),'<i class="fa fa-times"></i>').'</p>',


                    ),


                ),
            ),
            STEP_MENU => array(
                "id" => "menu",
                "title" => __("Menu", 'complianz-gdpr'),
                'intro' =>
                            '<h1>'._x("Get ready to finish your configuration.", 'intro menu', 'complianz-gdpr').'</h1>'.
                            '<p>'._x("Your documents have been generated. You can add them to your menu directly or do it manually after the wizard is finished.", 'intro menu', 'complianz-gdpr').'</p>',

                ),
            STEP_FINISH => array("title" => __("Finish", 'complianz-gdpr'),
            ),
        ),
);