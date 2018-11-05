<?php
defined('ABSPATH') or die("you do not have acces to this page!");
$this->document_elements['dataleak-eu'] = array(
    array(
        'content' => sprintf(_x('Date: %s', 'Legal document dataleak', 'complianz'), '[publish_date]'),
    ),
    array(
        'content' => _x('RE: Information regarding personal data breaches', 'Legal document dataleak', 'complianz'),
    ),
    array(
        'content' => _x('Dear Sir/Madam,', 'Legal document dataleak', 'complianz'),
    ),
    array(
        'content' => _x('With this letter, I would like to inform you of a recently discovered security incident in our organisation.', 'Legal document dataleak', 'complianz'),
    ),
    array(
        'content' => _x('In that incident, personal data was lost and there is no current back-up copy of that personal data.', 'Legal document dataleak', 'complianz'),
        'condition' => array(
            'type-of-dataloss' => 1,
        )
    ),
    array(
        'content' => _x('As a result of that incident, we cannot rule out the possibility that unauthorised persons have had access to your personal data. ', 'Legal document dataleak', 'complianz'),
        'condition' => array(
            'type-of-dataloss' => 2,
        )
    ),
    array(
        'content' => _x('We have therefore notified the national supervisory authority. As we expect possible adverse consequences for your privacy, we also inform you as a data subject. We would like to provide you with the following information in order to limit the possible consequences for you:', 'Legal document dataleak', 'complianz'),
        'condition' => array(
            'callback_condition' => 'cmplz_dataleak_has_to_be_reported',
        )
    ),
    array(
        'title' => _x('Explanation of the nature of the breach:', 'Legal document dataleak', 'complianz'),
        'content' => '[what-occurred]',
        'condition' => array('risk-of-data-loss'=>3),
    ),
    array(
        'title' => _x('Possible consequences:', 'Legal document dataleak', 'complianz'),
        'content' => '[consequences]',
        'condition' => array('risk-of-data-loss'=>3),
    ),
    array(
        'title' => _x('Measures we have taken:', 'Legal document dataleak', 'complianz'),
        'content' => '[measures]',
        'condition' => array('risk-of-data-loss'=>3),
    ),
    array(
        'title' => _x('Measures a person involved can take to minimise damage:' , 'Legal document dataleak','complianz'),
        'content' => '[measures_by_person_involved]',
        'condition' => array('risk-of-data-loss'=>3),
    ),
    array(
        'content' => _x('Despite these measures we have taken, the security breach may have adverse consequences for your privacy. To limit these as much as possible, we recommend that you take a number of measures. We hope that this letter has provided you with sufficient information about the security incident and its consequences. We are continuously working to improve security and counteract the possible consequences of this breach. We would like to apologise for any inconvenience you have experienced to date. ', 'Legal document dataleak', 'complianz'),
    ),
    array(
        'content' => sprintf(_x('If you would like more information about the data breach, please send a message to %s', 'Legal document dataleak', 'complianz'), '[email_company]'),
    ),
    array(
        'content' => _x('Kind regards, ', 'Legal document dataleak', 'complianz'),
    ),
    array(
        'content' => '[organisation_name]<br>
                    [address_company]<br>
                    [postalcode_company] [city_company]<br>
                    [country_company]<br>
                    ' . _x('Website:', 'Legal document dataleak', 'complianz') . ' [domain] <br>
                    ' . _x('Email:', 'Legal document dataleak', 'complianz') . ' [email_company] <br>
                    ' . _x('Phone:', 'Legal document dataleak', 'complianz') . ' [telephone_company]',
    ),
);
