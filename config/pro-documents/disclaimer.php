<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->document_elements['disclaimer'] = array(
    'aansprakelijkheid' => array(
        'content' => sprintf(_x('%s is committed to keeping this website up to date and accurate. Should you nevertheless encounter anything that is incorrect or out of date, we would appreciate it if you could let us know. Please indicate where on the website you read the information. We will then look at this as soon as possible. Please send your response by email to: [email_company].', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
    ),

    'themes-1' => array(
        'content' => sprintf(_x('We are not liable for loss as a result of inaccuracies or incompleteness, nor for loss resulting from problems caused by or inherent to the dissemination of information through the internet, such as disruptions or interruptions. When using web forms, we strive to limit the number of required fields to a minimum. For any loss suffered as a result of the use of data, advice or ideas provided by or on behalf of %s via this website, %s accepts no liability.', 'Legal document disclaimer', 'complianz'), '[organisation_name]', '[organisation_name]'),
        'condition' => array(
            'themes' => 1,
        ),
    ),
    'themes-2' => array(
        'content' => sprintf(_x('The use of the website and all its components (including forums) is subject to %sterms of use%s. The mere use of this website implies the knowledge and the acceptance of these terms of use.', 'Legal document disclaimer', 'complianz'), '[terms_of_use_link]', '[/terms_of_use_link]'),
        'condition' => array(
            'themes' => 2,
        ),
    ),
    'themes-3' => array(
        'content' => _x('Responses and privacy inquiries submitted by email or using a web form will be treated in the same way as letters. This means that you can expect a response from us within a period of 1 month at the latest. In the case of complex requests, we will let you know within 1 month if we need a maximum of 3 months.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'themes' => 3,
        ),
    ),
    'themes-4' => array(
        'content' => _x('Any personal data you provide us with in the context of your response or request for information will only be used in accordance with our privacy statement.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'themes' => 4,
        ),
    ),
    'themes-5' => array(
        'content' => sprintf(_x('%s shall make every reasonable effort to protect its systems against any form of unlawful use. %s shall implement appropriate technical and organisational measures to this end, taking into account, among other things, the state of the art. However, it shall not be liable for any loss whatsoever, direct and/or indirect, suffered by a user of the website, which arises as a result of the unlawful use of its systems by a third party.', 'Legal document disclaimer', 'complianz'), '[organisation_name]', '[organisation_name]'),
        'condition' => array(
            'themes' => 5,
        ),
    ),
    'themes-6' => array(
        'content' => sprintf(_x('%s accepts no responsibility for the content of websites to which or from which a hyperlink or other reference is made. Products or services offered by third parties shall be subject to the applicable terms and conditions of those third parties.', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
        'condition' => array(
            'themes' => 6,
        ),
    ),
    'themes-7' => array(
        'content' => _x('Our employees shall make every effort to guarantee the accessibility of our website and to continuously improve it. Including for people who use special software due to a disability.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'themes' => 7,
        ),
    ),

    'wcag' => array(
        'content' => _x('This website is therefore built according to the WCAG 2.0 level AA guidelines. These guidelines are internationally recognised agreements on accessibility, sustainability, interchangeability, and findability of websites.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'wcag' => 'yes',
        ),
    ),

    'development-1' => array(
        'content' => sprintf(_x('All intellectual property rights to content on this website are vested in %s.', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
        'condition' => array(
            'development' => 1,
        ),
    ),
    'development-2' => array(
        'content' => sprintf(_x('All intellectual property rights to content on this website are vested in third parties who have placed the content themselves or from whom %s has obtained a user licence.', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
        'condition' => array(
            'development' => 2,
        ),
    ),
    'development-3' => array(
        'content' => sprintf(_x('All intellectual property rights to content on this website are vested in %s or in third parties who have placed the content themselves or from whom %s has obtained a user licence.', 'Legal document disclaimer', 'complianz'), '[organisation_name]', '[organisation_name]'),
        'condition' => array(
            'development' => 3,
        ),
    ),

    'ip-claims' => array(
        'content' => sprintf(_x('Copying, disseminating and any other use of these materials is not permitted without the written permission of %s, except and only insofar as otherwise stipulated in regulations of mandatory law (such as the right to quote), unless specific content dictates otherwise.', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
        'condition' => array(
            'ip-claims' => 1,
        ),
    ),
    'ip-claims-2' => array(
        'content' => sprintf(_x('Copying, distributing and any other use of these materials is permitted without the written permission of %s.', 'Legal document disclaimer', 'complianz'), '[organisation_name]'),
        'condition' => array(
            'ip-claims' => 2,
        ),
    ),
    'ip-claims-3' => array(
        'content' => _x('The content on this website is available under a Creative Commons Attribution 3.0 Netherlands Licence unless specified otherwise.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'ip-claims' => 3,
        ),
    ),
    'ip-claims-4' => array(
        'content' => _x('The content on this website is available under a Creative Commons Attribution-Share Alike Licence, unless specified otherwise.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'ip-claims' => 4,
        ),
    ),
    'ip-claims-5' => array(
        'content' => _x('The content on this website is available under a Creative Commons Attribution No Derivative Works Licence, unless specified otherwise.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'ip-claims' => 5,
        ),
    ),
    'ip-claims-6' => array(
        'content' => _x('The content on this website is available under a Creative Commons Attribution Non-Commercial Licence, unless specified otherwise.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'ip-claims' => 6,
        ),
    ),
    'ip-claims-7' => array(
        'content' => _x('The content on this website is available under a Creative Commons Attribution Share Alike Non-Commercial Licence, unless specified otherwise.', 'Legal document disclaimer', 'complianz'),
        'condition' => array(
            'ip-claims' => 7,
        ),
    ),

    'conclusion' => array(
        'content' => _x('If you have any questions or problems with the accessibility of the website, please do not hesitate to contact us.', 'Legal document disclaimer', 'complianz'),
    ),

);
