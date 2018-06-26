<?php
defined('ABSPATH') or die("you do not have acces to this page!");

$this->document_elements['cookie-statement'] = array(
    'last-updated' => array(
        'content' => '<i>' . sprintf(__('This cookie statement was last updated on %s', 'complianz'), '[publish_date]') . '</i>',
    ),
    'introduction' => array(

        'title' => __('Introduction', 'complianz'),
        'content' => sprintf(__('Our website, %s (hereinafter: "the website") uses cookies and other related technologies (for convenience all technologies are referred to as "cookies"). Cookies are also placed by third parties we have engaged. In the document below we inform you about the use of cookies on our website.', 'complianz'), '[domain]', '[article-cookie_names]'),
    ),
    'what-are-cookies' => array(
        'title' => __('What are cookies', 'complianz'),
        'content' => __('A cookie is a small simple file that is sent along with pages of this website and stored by your browser on the hard drive of your computer or another device. The information stored therein may be returned to our servers or to the servers of the relevant third parties during a subsequent visit.', 'complianz'),
    ),
    'what-are-scripts' => array(
        'title' => __('What are scripts?', 'complianz'),
        'content' => __('A script is a piece of programme code that is used to make our website function properly and interactively. This code is executed on our server or on your device.', 'complianz'),
    ),
    'what-is-a-webbeacon' => array(
        'title' => __('What is a webbeacon?', 'complianz'),
        'content' => __('A web beacon (or a pixel tag) is a small, invisible piece of text or image on a website that is used to monitor traffic on a website. In order to do this, various data about you is stored using web beacons.', 'complianz'),
    ),
    'consent' => array(
        'title' => __('Consent', 'complianz'),
        'content' => __('When you visit our website for the first time, we will show you a pop-up with an explanation about cookies. As soon as you click on "agree", you consent to us using all cookies and plug-ins as described in the pop-up and this cookie statement. You can disable the use of cookies via your browser, but please note that our website may no longer work properly.', 'complianz'),
        'callback_condition' => 'cmplz_cookie_warning_required',
    ),
    'third-party' => array(
        'title' => __('Third parties', 'complianz'),
        'content' => __('We have made agreements about the use of cookies with other companies that place cookies. However, we cannot guarantee that these third parties handle your personal data in a reliable or secure manner. Parties such as Google are to be considered as independent data controllers within the meaning of the General Data Protection Regulation. We recommend that you read the privacy statements of these companies.', 'complianz')
    ),
    'cookies' => array(
        'title' => __('Cookies', 'complianz'),
        'subtitle' => __('Technical or functional cookies', 'complianz'),
        'content' => __('Some cookies ensure that certain parts of the website work properly and that your user preferences remain known. By placing functional cookies, we make it easier for you to visit our website. This way, you do not need to repeatedly enter the same information when visiting our website and, for example, the items remain in your shopping cart until you have paid. We may place these cookies without your consent.  <br><br> The following technical and functional cookies are placed:', 'complianz'),
    ),

    //analytical
    'cookies-analytical' => array(
        'subtitle' => __('Analytical cookies', 'complianz'),
        'content' => __('We use analytical cookies to optimize the website experience for our users. With these analytical cookies we get insights in the usage of our website. We ask your permission to place analytical cookies.', 'complianz'),
        'callback_condition' => 'cmplz_uses_statistics',
    ),

    'cookies-analytical-no' => array(
        'subtitle' => __('Analytical cookies', 'complianz'),
        'content' => __('We do not use analytical cookies on this website.', 'complianz'),
        'condition' => array('compile_statistics' => 'no'),
    ),

    //ads
    'cookies-ads-yes' => array(
        'subtitle' => __('Advertising cookies', 'complianz'),
        'content' => sprintf(__('On this website we use advertising cookies, enabling us to personalize the advertisements for you, and we (and third parties) gain insights into the campaign results. This happens based on a profile we create based on your click and surfing on and outside %s. With these cookies you, as website visitor are linked to a unique ID, so you do not see the same add more than once for example.', 'complianz'), '[domain]'),
        'condition' => array('uses_ad_cookies' => 'yes'),
    ),

    'advertising-cookies-yes-2' => array(
        'content' => __('Because these cookies are marked as tracking cookies, we ask your permission to place these.', 'complianz'),
        'condition' => array('uses_ad_cookies' => 'yes'),
    ),

    'advertising-cookies-no' => array(
        'subtitle' => __('Advertising cookies', 'complianz'),
        'content' => __('We do not use any advertising cookies on this website.', 'complianz'),
        'condition' => array('uses_ad_cookies' => 'no'),
    ),

    //social media
    'social-media' => array(
        'subtitle' => __('Social media buttons', 'complianz'),
        'content' => __('On our website we do not use social media buttons to promote web pages or share them on social networks.', 'complianz'),
        'condition' => array('uses_social_media' => 'no'),
    ),
    'social-media-yes' => array(
        'subtitle' => __('Social media buttons', 'complianz'),
        'content' => sprintf(__('On our website we have included buttons for %s to promote webpages (e.g. “like”, “pin”) or share (e.g. “tweet”) on social networks like %s. These buttons work using pieces of code coming from %s themselves. This code places cookies. These social media buttons also can store and process certain information, so a personalized advertisement can be shown to you. Please read the privacy statement of these social networks (which can change regularly) to read what they do with your (personal) data which they process using these cookies. The data that is retrieved is anonymized as much as possible. %s are located in the United States.', 'complianz'), '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]'),
        'condition' => array('uses_social_media' => 'yes'),
    ),

    'cookie_names' => array(
        'title' => __('Cookie usage', 'complianz'),
        'content' =>
            '<table><tr><td colspan="3"><b>[label]</b></td></tr>
                                 <tr><td colspan="3">' . __("Purpose:", 'complianz') . ' [purpose]</td></tr>
                                 <tr><td colspan="3">' . __("Description:", 'complianz') . ' [description]</td></tr>
                                 <tr>
                                    <td>' . __("Retention period", 'complianz') . '</td>
                                    <td>' . __("Used names", 'complianz') . '</td>
                                    <td>' . __("Sharing", 'complianz') . '</td>
                                  </tr><tr>
                                     <td>[storage_duration]</td>
                                     <td>[used_names]</td>
                                     <td>' . sprintf(__("For more information see the privacy policy of %s at %s", 'complianz'), '[label]', '[privacy_policy_url]') . '</td>
                                 </tr>
                     </table>',
        'condition' => array(
            'used_cookies' => 'loop',
        ),
    ),

    'your-rights' => array(
        'title' => __('Your rights with respect to personal data', 'complianz'),
        'content' =>
            __('You have the following rights with respect to your personal data:', 'complianz') .
            '<ul>
                    <li>' . __('You have the right to know why your personal data is needed, what will happen to it, and how long it will be retained for.', 'complianz') . '</li>
                    <li>' . __('Right of access: You have the right to access your personal data that is known to us.', 'complianz') . '</li>
                    <li>' . __('Right to rectification: you have the right to supplement, correct, have deleted or blocked your personal data whenever you wish.', 'complianz') . '</li>
                    <li>' . __('If you give us your consent to process your data, you have the right to revoke that consent and to have your personal data deleted.', 'complianz') . '</li>
                    <li>' . __('Right to transfer your data: you have the right to request all your personal data from the controller and transfer it in its entirety to another controller.', 'complianz') . '</li>
                    <li>' . __('Right to object: you may object to the processing of your data. We comply with this, unless there are justified grounds for processing.', 'complianz') . '</li>
                </ul>' .
            __('To exercise these rights, please contact us. Please refer to the contact details at the bottom of this cookie statement. If you have a complaint about how we handle your data, we would like to hear from you, but you also have the right to submit a complaint to the supervisory authority (the Data Protection Authority).', 'complianz'),
    ),
    'enable-disable-removal-cookies' => array(
        'title' => __('Enabling/disabling and deleting cookies', 'complianz'),
        'content' => __('You can use your internet browser to automatically or manually delete cookies. You can also specify that certain cookies may not be placed. Another option is to change the settings of your internet browser so that you receive a message each time a cookie is placed. For more information about these options, please refer to the instructions in the Help section of your browser. Or you can indicate your preferences on the following page:  www.youronlinechoices.eu.', 'complianz')
    ),
    'enable-disable-removal-cookies-2' => array(
        'content' => __('Please note that our website may not work properly if all cookies are disabled. If you do delete the cookies in your browser, they will be placed again after your consent when you visit our websites again.', 'complianz')
    ),

    'contact-details' => array(
        'title' => __('Contact details', 'complianz'),
        'content' => __('For questions and/or comments about our cookie policy and this statement, please contact us by using the following contact details:', 'complianz'),
    ),
    'contact-details-2' => array(
        'content' => '[organisation_name]<br>
                    [address_company]<br>
                    [postalcode_company] [city_company]<br> 
                    [country_company]<br>
                    ' . __('Website:', 'complianz') . ' [domain] <br> 
                    ' . __('Email:', 'complianz') . ' [email_company] <br> 
                    ' . __('Phone:', 'complianz') . ' [telephone_company]',
    ),

    'revoke_btn' => array(
        'content' => cmplz_revoke_link(),
        'callback_condition' => 'cmplz_cookie_warning_required',
    ),

);