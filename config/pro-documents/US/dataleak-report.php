<?php
/*
 * This document is intentionally not translatable, as it is intended to be for US citizens, and should therefore always be in English
 *
 * */
defined('ABSPATH') or die("you do not have acces to this page!");
$this->document_elements['dataleak-us'] = array(
    array(
        'content' => sprintf('Date: %s', '[publish_date]'),
    ),
    array(
        'content' => 'RE: NOTICE OF DATA BREACH',
    ),
    array(
        'content' => 'Dear Sir/Madam,',
    ),
    array(
        'content' => 'With this letter, I would like to inform you of a recently discovered security incident in our organisation.',
    ),

    array(
        'content' => 'Encrypted personal data is lost, and it cannot be excluded that unauthorized persons have access to the encryption key or password.',
        'condition' => array(
            'type-of-dataloss-us' => 1,
        )
    ),

    array(
        'content' => 'As a result of that incident, we cannot rule out the possibility that unauthorised persons have had access to your personal data. ',
        'condition' => array(
            'type-of-dataloss-us' => 2,
        )
    ),

    array(
        'content' => 'As we expect possible adverse consequences for your privacy, we inform you as a data subject. We would like to provide you with the following information in order to limit the possible consequences for you:',
    ),

    array(
        'title' => 'When did it happen?','Legal document dataleak',
        'content' => sprintf('The security breach took place on %s', '[date-of-breach-us]'),
    ),

    array(
        'title' => 'What happened?',
        'content' => '[what-occurred-us]',
    ),

    array(
        'title' => 'What information was involved?',
        'content' => sprintf('The security breach involved the following information: %s', '[what-information-was-involved-us]'),
    ),

    array(
        'content' => 'In the case of a breach of the security of the system involving personal information for an online account, and no other personal information, we will be providing the security breach notification in electronic or other form that directs the person whose personal information has been breached promptly to change his or her password and security question or answer, as applicable, or to take other steps appropriate to protect the online account, and all other online accounts for which you use the same user name or email address and password or security question or answer.
In the case of a breach of the security of the system involving login credentials of an email account furnished by ourselves, we shall not provide the security breach notification to that email address, but may, instead, provide notice by another method or by clear and conspicuous notice delivered to you online when you are connected to the online account from an Internet Protocol address or online location from which we know you customarily accesses the account.',
        'condition' => array(
            'what-information-was-involved-us' => 'username-email',
        )
    ),

    array(
        'title' => 'What are we doing?',
        'content' => '[measures-us]',
    ),

    array(
        'title' => 'Other Important Information:',
        'content' => 'Because the breach exposed a social security number, a driver’s license or an identification card number,  you can call the toll-free telephone number or write a letter to the addresses of the major credit reporting agencies:,',
        'callback_condition' => 'cmplz_socialsecurity_or_driverslicense',
    ),

    array(
        'content' => 'Despite these measures we have taken, the security breach may have adverse consequences for your privacy. To limit these as much as possible, we recommend that you take a number of measures:',
    ),

    array(
        'content' => '[measures_by_person_involved-us]',
    ),

    array(
        'content' => 'We hope that this letter has provided you with sufficient information about the security incident and its consequences. We are continuously working to improve security and counteract the possible consequences of this breach. We would like to apologise for any inconvenience you have experienced to date. ',
    ),

    array(
        'content' => sprintf('If you would like more information about the data breach, please send a message to %s', '[email_company]'),
    ),
    array(
        'content' => 'Kind regards, ',
    ),
    array(
        'content' => '[organisation_name]<br>
                    [address_company]<br>
                    [postalcode_company] [city_company]<br>
                    [country_company]<br>
                    Website: [domain] <br>
                    Email: [email_company] <br>
                    Phone: [telephone_company]',
    ),
);
