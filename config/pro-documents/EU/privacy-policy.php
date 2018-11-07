<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->document_elements['privacy-statement'] = array(
    'last-updated' => array(
        'content' => '<i>' . sprintf(_x('This privacy statement was last updated on %s and applies to citizens of the European Union.', 'Legal document privacy statement', 'complianz'), '[publish_date]') . '</i>',
    ),
    'inleiding' => array(
        'p' => false,
        'content' =>
            '<p>'.sprintf(_x('In this privacy statement, we explain what we do with the data we obtain about you via %s. We recommend you carefully read this statement. In our processing we comply with the requirements of privacy legislation. That means, among other things, that:', 'Legal document privacy statement:paragraph title', 'complianz'), '[domain]') .
            '<ul>
                <li>' . _x('we clearly state the purposes for which we process personal data. We do this by means of this privacy statement;', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('we aim to limit our collection of personal data to only the personal data required for legitimate purposes;', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('we first request your explicit consent to process your personal data in cases requiring your consent;', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('we take appropriate security measures to protect your personal data and also require this from parties that process personal data on our behalf;', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('we respect your right to to access your personal data or have it corrected or deleted, at your request.', 'Legal document privacy statement', 'complianz') . '</li>
            </ul></p>' .
            _x('If you have any questions, or want to know exactly what data we keep of you, please contact us.', 'Legal document privacy statement', 'complianz'),
    ),

    //In the privacy-policy page the first paragraph containing purpose and data retention period is generated in the class-config.php
    'third-party-sharing' => array(
        'title' => _x('Sharing with other parties', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We only share this data with processors and with other third parties for which the data subjects consent must be obtained. It concerns the following party or parties:', 'Legal document privacy statement', 'complianz'),
        'condition' => array('share_data_other' => 'NOT 2'),
    ),

    'processor' => array(
        'p' => false,
        'numbering' => false,
        'content' =>
            "<p>
                <b>" . _x("Name:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[name]<br>
                <b>" . _x("Country:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[country]<br>
                <b>" . _x("Purpose:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[purpose]<br>
                <b>" . _x("Data:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[data]
            </p>",
        'condition' => array(
            'processor' => 'loop',
            'share_data_other' => 'NOT 2',
        ),
    ),

    'thirdparty' => array(
        'p' => false,
        'numbering' => false,
        'content' =>
            "<p>
                <b>" . _x("Name:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[name]<br>
                <b>" . _x("Country:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[country]<br>
                <b>" . _x("Purpose:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[purpose]<br>
                <b>" . _x("Data:", 'Legal document privacy statement', 'complianz') . "</b>&nbsp;[data]
            </p>",
        'condition' => array(
            'thirdparty' => 'loop',
            'share_data_other' => '1',
        ),
    ),

    'no-sharing' => array(
        'p' => false,
        'title' => _x('Sharing with other parties', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We do not share your data with third parties.', 'Legal document privacy statement', 'complianz'),
        'condition' => array('share_data_other' => '2'),
    ),

    'no-sharing-limited' => array(
        'p' => false,
        'title' => _x('Sharing with other parties', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We only share this data with processors that are necessary for the performance of my service.', 'Legal document privacy statement', 'complianz'),
        'condition' => array('share_data_other' => '3'),
    ),

    'privacy-policy-cookies' => array(
        'p' => false,
        'title' => _x('Cookies', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => sprintf(_x('Our website uses cookies. For more information about cookies, please refer to our %sCookie Statement%s.', 'Legal document privacy statement', 'complianz'), '<a href="[cookie-statement-url]">', '</a>')."&nbsp;",
        'condition' => array('uses_cookies' => 'yes'),
    ),

    'statistics_anonymous' => array(
        'p' => false,
        'title' => _x('Statistics', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We keep track of anonymised statistics to gain insight into how often and in what way visitors use our website.', 'Legal document privacy statement', 'complianz'),
        'condition' => array('compile_statistics' => 'yes-anonymous'),
    ),

    'statistics-google' => array(
        'p' => false,
        'content' => _x('We have concluded a data processing agreement with Google.', 'Legal document privacy statement', 'complianz'),
        'callback_condition' => 'cmplz_accepted_processing_agreement',
    ),

    'statistics-no-sharing' => array(
        'p' => false,
        'content' => _x('Google may not use the data for any other Google services.', 'Legal document privacy statement', 'complianz'),
        'callback_condition' => 'cmplz_statistics_no_sharing_allowed',
    ),

    'statistics-no-ip' => array(
        'p' => false,
        'content' => _x('The inclusion of full IP addresses is blocked by us.', 'Legal document privacy statement', 'complianz'),
        'callback_condition' => 'cmplz_no_ip_addresses',
    ),

    'security' => array(
        'p' => false,
        'title' => _x('Security', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We are committed to the security of personal data. We take appropriate security measures to limit abuse of and unauthorised access to personal data. This ensures that only the necessary persons have access to your data, that access to the data is protected, and that our security measures are regularly reviewed.', 'Legal document privacy statement', 'complianz')
    ),
    'security_which' => array(
        'p' => true,
        'content' => _x('The security measures we use consist of:', 'Legal document privacy statement', 'complianz'),
        'condition' => array('secure_personal_data' => 2),
    ),
    'security_which_content' => array(
        'p' => false,
        'content' => '[which_personal_data_secure]',
        'condition' => array('secure_personal_data' => 2),
    ),
    'third-party-website' => array(
        'p' => false,
        'title' => _x('Third party websites', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('This privacy statement does not apply to third party websites connected by links on our website. We cannot guarantee that these third parties handle your personal data in a reliable or secure manner. We recommend you read the privacy statements of these websites prior to making use of these websites.', 'Legal document privacy statement', 'complianz'),
    ),
    'changes-privacy-statement' => array(
        'p' => false,
        'title' => _x('Amendments to this privacy statement', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We reserve the right to make amendments to this privacy statement. It is recommended that you consult this privacy statement regularly in order to be aware of any changes. In addition, we will actively inform you wherever possible.', 'Legal document privacy statement', 'complianz'),
    ),
    'insight-changes-your-data' => array(
        'title' => _x('Accessing and modifying your data', 'Legal document privacy statement', 'complianz'),
        'content' =>
            _x('If you have any questions or want to know which personal data we have about you, please contact us. You can contact us by using the information below. You have the following rights:', 'Legal document privacy statement', 'complianz') .
            '<ul>
                <li>' . _x('You have the right to know why your personal data is needed, what will happen to it, and how long it will be retained for.', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('Right of access: You have the right to access your personal data that is known to us.', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('Right to rectification: you have the right to supplement, correct, have deleted or blocked your personal data whenever you wish.', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('If you give us your consent to process your data, you have the right to revoke that consent and to have your personal data deleted.', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('Right to transfer your data: you have the right to request all your personal data from the controller and transfer it in its entirety to another controller.', 'Legal document privacy statement', 'complianz') . '</li>
                <li>' . _x('Right to object: you may object to the processing of your data. We comply with this, unless there are justified grounds for processing.', 'Legal document privacy statement', 'complianz') . '</li>
            </ul>' .
            _x('Please make sure to always clearly state who you are, so that we can be certain that we do not modify or delete any data of the wrong person.', 'Legal document privacy statement', 'complianz'),
    ),

    'automated_processes' => array(
        'p' => false,
        'title' => _x('Automated decision-making', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('We make decisions on the basis of automated processing with respect to matters that may have (significant) consequences for individuals. These are decisions taken by computer programmes or systems without human intervention.', 'Legal document privacy statement', 'complianz'),
        'condition' => array('automated_processes' => 'yes'),
    ),
    'complaints' => array(
        'p' => false,
        'title' => _x('Submitting a complaint', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => _x('If you are not satisfied with the way in which we handle (a complaint about) the processing of your personal data, you have the right to submit a complaint to the Data Protection Authority.', 'Legal document privacy statement', 'complianz'),
    ),
    'data-protection-officer' => array(
        'p' => false,
        'title' => _x('Data Protection Officer', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => sprintf(_x('Our Data Protection Officer has been registered with the data protection authority in an EU Member State. If you have any questions or requests with respect to this privacy statement or for the Data Protection Officer, you may contact %s, via %s or by telephone on %s.', 'Legal document privacy statement', 'complianz'), '[name_dpo]', '[email_dpo]', '[phone_dpo]'),
        'condition' => array('dpo_or_gdpr' => 'dpo'),
    ),
    'contact-details' => array(
        'title' => _x('Contact details', 'Legal document privacy statement:paragraph title', 'complianz'),
        'content' => '[organisation_name]<br>
        [address_company]<br>
        [postalcode_company] [city_company]<br>
        [country_company]<br>
        ' . _x('Website:', 'Legal document privacy statement', 'complianz') . ' [domain] <br>
        ' . _x('Email:', 'Legal document privacy statement', 'complianz') . ' [email_company] <br>
        [telephone_company]',
    ),
    'gdpr_rep' => array(
        'p' => false,
        'content' => sprintf(_x('We have appointed a representative within the EU. If you have any questions or requests with respect to this privacy statement or for our representative, you may contact %s, via %s or by telephone on %s.', 'Legal document privacy statement', 'complianz'), '[name_gdpr]', '[email_gdpr]', '[phone_gdpr]'),
        'condition' => array('dpo_or_gdpr' => 'gdpr_rep'),
    ),
    /* Dit zijn de privacy policies die door wp worden aangeboden per plugin */
    'custom_privacy_policy_text' => array(
        'p' => false,
        'title' => _x('Annex', 'Legal document privacy statement', 'complianz'),
        'numbering' => false,
        'content' => '[custom_privacy_policy_text]',
        'callback_condition' => 'cmplz_has_custom_privacy_policy',
    ),

// End privacy statement array
);
