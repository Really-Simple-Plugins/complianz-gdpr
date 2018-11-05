<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->steps['wizard'][STEP_COMPANY] = array(
    "id" => "company",
    "title" => __("General", 'complianz'),
    'sections' => array(
        1 => array(
            'id' => 'general',
            'title' => __('Documents', 'complianz'),
            'intro' => '<h1>'.__("Hi there!", 'complianz').'</h1><p>'.
                _x('Welcome to the Complianz Privacy Suite Wizard.','intro first step', 'complianz').'</p><p>'.
                sprintf(_x('We have tried to make our Wizard as simple and fast as possible. Although these questions are all necessary, if there’s any way you think we can improve the plugin, please let us %sknow%s!','intro first step', 'complianz'),'<a target="_blank" href="https://complianz.io/contact">', '</a>').'<br>'.
                _x('The answers in the first step of the wizard are needed to configure your documents and consent banner specifically to your needs.','intro first step', 'complianz').'</p><p>'.
                _x('Please note that you can always save and finish the wizard later (if you need a break), use our documentation for additional information or log a support ticket if you need our assistance.', 'intro first step', 'complianz').'</p>',

            ),
        2 => array(
            'id' => 'visitors',
            'title' => __('Visitors', 'complianz'),
            'intro' => _x('To determine what laws apply, we need to know where your website visitors are coming from.', 'intro company info', 'complianz'),
        ),
        3 => array(
            'id' => 'company_info',
            'title' => __('Company information', 'complianz'),
            'intro' => _x('We need some company information to be able to generate your documents.', 'intro company info', 'complianz'),
        ),
        4 => array(
            'region' => 'eu',
            'id' => 'dpo',
            'title' => __('Data protection officer', 'complianz'),
            //'intro' => _x( 'In this section, you can fill in information regarding your DPO or GDPR representative. Information that will be placed in your documents.', 'intro dpo', 'complianz' ),
        ),
        5 => array(
            'id' => 'purpose',
            'title' => __('Purpose', 'complianz'),
            //'intro' => _x( 'In this section information regarding the purpose of processing personal data is asked.  ', 'intro purpose', 'complianz' ),
        ),
        6 => array(
            'region' => 'eu',
            'id' => 'details_per_purpose_eu',
            'title' => __('Details per purpose - EU', 'complianz'),
            //'intro' => _x( 'In this section details regarding the purpose of processing personal data is asked.', 'intro details per purpose', 'complianz' ),
        ),
        7 => array(
            'region' => 'us',
            'id' => 'details_per_purpose_us',
            'title' => __('Details per purpose - US', 'complianz'),
            //'intro' => _x( 'In this section details regarding the purpose of processing personal data is asked.', 'intro details per purpose', 'complianz' ),
        ),
        8 => array(
            'region' => 'eu',
            'id' => 'sharing_of_data_eu',
            'title' => __('Sharing of data - EU', 'complianz'),
            'intro' => _x('In this section, we need you to fill in information about third parties and processors you’re working with.', 'intro third parties', 'complianz'),
        ),
        9 => array(
            'region' => 'us',
            'title' => __('Sharing of data - US', 'complianz'),
            'intro' => _x('In this section, we need you to fill in information about third parties and processors you’re working with.', 'intro third parties', 'complianz'),
        ),
        10 => array(
            'title' => __('Security & Consent', 'complianz'),
            //intro' => _x('In this section, we need you to fill in information about everything regarding security of data.', 'intro security', 'complianz'),
        ),
        11 => array(
            'region' => 'us',
            'title' => __('Financial incentives', 'complianz'),
//            'intro' => _x('In this section, we need you to fill in information about third parties and processors you’re working with.', 'intro third parties', 'complianz'),
        ),
        12 => array(
            'region' => 'us',
            'law' => 'COPPA',
            'title' => __('Children', 'complianz'),
            //'intro' => _x('In this section, we need you to fill in information about third parties and processors you’re working with.', 'intro third parties', 'complianz'),
        ),
        13 => array(
            'region' => 'us',
            'law' => 'COPPA',
            'title' => __('Children: data processing purposes', 'complianz'),
            //'intro' => _x('In this section, we need you to fill in information about third parties and processors you’re working with.', 'intro third parties', 'complianz'),
        ),
        14 => array('title' => __('Disclaimer', 'complianz'),
            'intro' => _x('Answers you will give below will be used to generate your disclaimer.', 'intro disclaimer', 'complianz'),
        ),
    )
);

$this->steps['wizard'][STEP_PLUGINS] = array("title" => __("Plugins", 'complianz'),
    'intro' => '<h1>'._x('Next!','intro plugins', 'complianz').'</h1>'.
        _x('Plugins and themes can add their own suggested privacy paragraphs here. You can choose to add these to your privacy statement.', 'intro plugins', 'complianz') .
        "&nbsp" . _x('You can also use the editor to add custom text to your privacy statement if you like.', 'intro plugins', 'complianz')
);

$this->steps['processing-eu'] = array(
    1 => array("title" => __("General", 'complianz')),
    2 => array("title" => __("Processing", 'complianz'),
        'sections' => array(
            1 => array('title' => __('Data', 'complianz')),
            2 => array('title' => __('Handling of requests', 'complianz')),
            3 => array('title' => __('Right of audit', 'complianz')),
        ),
    ),
    3 => array("title" => __("Data breach", 'complianz')),
    4 => array("title" => __("Finish", 'complianz')),
);

$this->steps['processing-us'] = array(
    1 => array("title" => __("General", 'complianz')),
    2 => array("title" => __("Processing", 'complianz'),
        'sections' => array(
            1 => array('title' => __('Data', 'complianz')),
            2 => array('title' => __('Handling of requests', 'complianz')),
            3 => array('title' => __('Right of audit', 'complianz')),
        ),
    ),
    3 => array("title" => __("Data breach", 'complianz')),
    4 => array("title" => __("Finish", 'complianz')),
);

$this->steps['dataleak-eu'] = array(
    1 => array(
        "title" => __("General", 'complianz'),
    ),
    2 => array("title" => __("Necessity", 'complianz'),
        'sections' => array(
            1 => array('title' => __('Incident', 'complianz')),
            2 => array('title' => __('Description of incident', 'complianz')),
        )),
    3 => array("title" => __("Options", 'complianz')),
    4 => array("title" => __("Finish", 'complianz')),
);

$this->steps['dataleak-us'] = array(
    1 => array(
        "title" => __("General", 'complianz'),
    ),
    2 => array("title" => __("Necessity", 'complianz'),
        'sections' => array(
            1 => array('title' => __('Incident', 'complianz')),
            2 => array('title' => __('Description of incident', 'complianz')),
        )),
    3 => array("title" => __("Options", 'complianz')),
    4 => array("title" => __("Details", 'complianz')),
    5 => array("title" => __("Finish", 'complianz')),
);


