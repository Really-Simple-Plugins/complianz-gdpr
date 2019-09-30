<?php
/*
 * This document is intentionally not translatable, as it is intended to be for US citizens, and should therefore always be in English
 *
 * */
defined('ABSPATH') or die("you do not have acces to this page!");

$this->document_elements['cookie-statement-us'] = array(
    array(
        'content' => '<i>' . sprintf("This page was last changed on %s, last checked on %s and applies to citizens of the United States. ", '[publish_date]', '[checked_date]') . '</i>',
    ),
    array(
        'title' => 'Introduction',
        'content' => sprintf('Our website, %s (hereinafter: "the website") uses cookies and other related technologies (for convenience all technologies are referred to as "cookies"). Cookies are also placed by third parties we have engaged. In the document below we inform you about the use of cookies on our website.', '[domain]', '[article-cookie_names]'),
    ),
    array(
        'p' => false,
        'content' => "We also use the website to collect and sell your personal information. In the document below we explain which data we have sold in the last 12 months, and we give you the possibility to opt-out.",
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'title' => 'What are cookies',
        'content' => 'A cookie is a small simple file that is sent along with pages of this website and stored by your browser on the hard drive of your computer or another device. The information stored therein may be returned to our servers or to the servers of the relevant third parties during a subsequent visit.',
    ),
    array(
        'title' => 'What are scripts?',
        'content' => 'A script is a piece of program code that is used to make our website function properly and interactively. This code is executed on our server or on your device.',
    ),
    array(
        'title' => 'What is a webbeacon?',
        'content' => 'A web beacon (or a pixel tag) is a small, invisible piece of text or image on a website that is used to monitor traffic on a website. In order to do this, various data about you is stored using web beacons.',
        'callback_condition' => 'NOT cmplz_uses_only_functional_cookies',
    ),
    array(
        'title' => 'Consent',
        'content' => 'When you visit our website for the first time, we will show you a pop-up with an explanation about cookies. You do have the right to opt-out and to object against the further use of non-functional cookies.',
    ),
    array(
        'content' => cmplz_revoke_link(),
    ),

    array(
        'content' =>'You can also disable the use of cookies via your browser, but please note that our website may no longer work properly.',
    ),
    array(
        'title' => 'Third parties',
        'content' => 'We have made agreements about the use of cookies with other companies that place cookies. However, we cannot guarantee that these third parties handle your personal data in a reliable or secure manner. Parties such as Google are to be considered as independent data controllers. We recommend that you read the privacy statements of these companies.',
        'callback_condition' => 'cmplz_third_party_cookies_active',
    ),
    array(
        'title' => 'Cookies',
    ),
    array(
        'subtitle' => 'Technical or functional cookies',
        'content' => 'Some cookies ensure that certain parts of the website work properly and that your user preferences remain known. By placing functional cookies, we make it easier for you to visit our website. This way, you do not need to repeatedly enter the same information when visiting our website and, for example, the items remain in your shopping cart until you have paid. We may place these cookies without your consent.',
    ),

    //analytical
    array(
        'subtitle' => 'Analytical cookies',
        'content' => 'We use analytical cookies to optimize the website experience for our users. With these analytical cookies we get insights in the usage of our website.',
        'callback_condition' => 'cmplz_uses_statistics',
        'condition' => array('compile_statistics' => 'yes'),
    ),

    array(
        'subtitle' => 'Analytical cookies',
        'content' => 'We do not use analytical cookies on this website.',
        'condition' => array('compile_statistics' => 'no'),
    ),

    //ads
    array(
        'subtitle' => 'Advertising cookies',
        'content' => sprintf('On this website we use advertising cookies, enabling us to personalize the advertisements for you, and we (and third parties) gain insights into the campaign results. This happens based on a profile we create based on your click and surfing on and outside %s. With these cookies you, as website visitor are linked to a unique ID, so you do not see the same ad more than once for example.', '[domain]'),
        'condition' => array(
            'uses_ad_cookies' => 'yes',
            'uses_ad_cookies_personalized' => 'yes'
        ),
    ),

    array(
        'subtitle' => 'Advertising cookies', 'Legal document cookie policy:paragraph title',
        'content' => sprintf('On this website we use advertising cookies, enabling us to gain insights into the campaign results. This happens based on a profile we create based on your behavior on %s. With these cookies you, as website visitor are linked to a unique ID, but will not profile your behavior and interests to serve personalized ads.', '[domain]'),
        'condition' => array(
            'uses_ad_cookies' => 'yes',
            'uses_ad_cookies_personalized' => 'no'
        ),
    ),

    array(
        'content' => 'You can object to the tracking by these cookies by clicking the "Revoke cookie consent" button.',
        'condition' => array(
            'uses_ad_cookies' => 'yes',
        ),
    ),

    array(
        'subtitle' => 'Advertising cookies',
        'content' => 'We do not use any advertising cookies on this website.',
        'condition' => array('uses_ad_cookies' => 'no'),
    ),

    //social media
    array(
        'subtitle' => 'Social media buttons',
        'content' => 'On our website we do not use social media buttons to promote web pages or share them on social networks.',
        'condition' => array('uses_social_media' => 'no'),
    ),

    array(
        'subtitle' => 'Social media buttons',
        'content' => sprintf('On our website we have included buttons for %s to promote webpages (e.g. “like”, “pin”) or share (e.g. “tweet”) on social networks like %s. These buttons work using pieces of code coming from %s themselves. This code places cookies. These social media buttons also can store and process certain information, so a personalized advertisement can be shown to you.', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]'),
        'condition' => array('uses_social_media' => 'yes'),
    ),

    array(
        'content' => 'Please read the privacy statement of these social networks (which can change regularly) to read what they do with your (personal) data which they process using these cookies. The data that is retrieved is anonymized as much as possible.'.' '.sprintf( _n( '%s is located in the United States.', '%s are located in the United States.',  cmplz_count_socialmedia(), 'complianz-gdpr'  ) ,'[comma_socialmedia_on_site]' ),
        'condition' => array('uses_social_media' => 'yes'),
    ),

    'cookie_names' => array(
        'title' => 'Placed cookies',
        'p' => false,
        'content' => '<table><tr><td colspan="2"><b>[label]</b></td></tr>
                                 <tr><td colspan="2">
                                    Purpose: [purpose]<br>
                                    Retention period: [storage_duration]<br>
                                    Description: [description]
                                 </td></tr>
                                 <tr>
                                    <td>Used names</td>
                                    <td>Sharing</td>
                                  </tr><tr>
                                     <td>[used_names]</td>
                                     <td><a href="[privacy_policy_url]">'.__('Privacy statement','complianz-gdpr').'</a></td>
                                 </tr>
                     </table>',
        'condition' => array(
            'used_cookies' => 'loop',
        ),
    ),

//    array(
//        'title' => __('List of data for sale',
//        'content' => __("Internet activity information, including, but not limited to, browsing history, search history, and information regarding a consumer's interaction with an Internet Web site, application, or advertisement.",
//        'condition' => array('purpose_personaldata' => 'selling-data-thirdparty'),
//    ),

    array(
        'title' => 'Your rights with respect to personal data',
        'p'=> false,
        'content' =>
            '<p>You have the following rights with respect to your personal data:</p>' .
            '<ul>
                <li>you may submit a request for access to the data we process about you;</li>
                <li>you may object to the processing;</li>
                <li>you may request an overview, in a commonly used format, of the data we process about you;</li>
                <li>you may request correction or deletion of the data if it is incorrect or not or no longer relevant, or to ask to restrict the processing of the data.</li>
            </ul>' .
            '<p>To exercise these rights, please contact us. Please refer to the contact details at the bottom of this cookie statement. If you have a complaint about how we handle your data, we would like to hear from you.</p>',
    ),

    array(
        'title' => 'Enabling/disabling and deleting cookies',
        'content' => 'You can use your internet browser to automatically or manually delete cookies. You can also specify that certain cookies may not be placed. Another option is to change the settings of your internet browser so that you receive a message each time a cookie is placed. For more information about these options, please refer to the instructions in the Help section of your browser.',
    ),
    array(
        'title' => 'Selling data to third parties',
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),
    array(
        'subtitle' => 'Categories of data',
        'content' => 'The following categories of data are sold to third parties',
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'content' => '[selling-data-thirdparty_data_purpose_us]',
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'title' => 'Selling data to third parties',
        'content' => 'We do not sell data to third parties',
        'callback_condition' => 'NOT cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'subtitle' => 'Object to selling of personal data to third parties',
        'content' => 'You can object to our selling of your personal data by entering your email address and name here. We will then remove your data from the databases we sell to third parties.',
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'content' => cmplz_do_not_sell_personal_data_form(),
        'callback_condition' => 'cmplz_sells_personal_data',
        'condition' => array(
            'california' => 'yes',
        ),
    ),

    array(
        'title' => 'Contact details', 'Legal document cookie policy:',
        'content' => 'For questions and/or comments about our cookie policy and this statement, please contact us by using the following contact details:',
    ),

    array(
        'content' => '[organisation_name]<br>
                    [address_company]<br>
                    [country_company]<br>
                    Website: [domain] <br>
                    Email: [email_company] <br>
                    [telephone_company]',
    ),

);
