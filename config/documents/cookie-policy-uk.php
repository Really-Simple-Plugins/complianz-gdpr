<?php
defined('ABSPATH') or die("you do not have access to this page!");

$social_media_format = (cmplz_count_socialmedia() === 1) ? '%s is located in the United States.' : '%s are located in the United States.';

$this->pages['uk']['cookie-statement']['document_elements'] = array(
    'last-updated' => array(
        'content' => '<i>' . cmplz_sprintf('This Cookie Policy was last updated on %s and applies to citizens and legal permanent residents of the United Kingdom.', '[publish_date]') . '</i><br>',
    ),
    'introduction' => array(
        'title' => 'Introduction',
        'content' => cmplz_sprintf('Our website, %s (hereinafter: "the website") uses cookies and other related technologies (for convenience all technologies are referred to as "cookies"). Cookies are also placed by third parties we have engaged. In the document below we inform you about the use of cookies on our website.', '[domain]'),
    ),
    'what-are-cookies' => array(
        'title' => 'What are cookies?',
        'content' => 'A cookie is a small simple file that is sent along with pages of this website and stored by your browser on the hard drive of your computer or another device. The information stored therein may be returned to our servers or to the servers of the relevant third parties during a subsequent visit.',
    ),
    'what-are-scripts' => array(
        'title' => 'What are scripts?',
        'content' => 'A script is a piece of program code that is used to make our website function properly and interactively. This code is executed on our server or on your device.',
    ),
    'what-is-a-webbeacon' => array(
        'title' => 'What is a web beacon?',
        'content' => 'A web beacon (or a pixel tag) is a small, invisible piece of text or image on a website that is used to monitor traffic on a website. In order to do this, various data about you is stored using web beacons.',
        'callback_condition' => 'NOT cmplz_uses_only_functional_cookies',
    ),

    'cookies' => array(
	    'title' => 'Cookies',
    ),

    array(
	    'subtitle' => 'Technical or functional cookies',
	    'content' => 'Some cookies ensure that certain parts of the website work properly and that your user preferences remain known. By placing functional cookies, we make it easier for you to visit our website. This way, you do not need to repeatedly enter the same information when visiting our website and, for example, the items remain in your shopping cart until you have paid. We may place these cookies without your consent.',
    ),

	array(
	    'subtitle' => 'Statistics cookies',
	    'content' => 'We use statistics cookies to optimize the website experience for our users. With these statistics cookies we get insights in the usage of our website.'
	                 .'&nbsp;'.'We ask your permission to place statistics cookies.',
	    'callback_condition' => 'cmplz_cookie_warning_required_stats_uk',
	    'condition' => array('compile_statistics' => 'NOT no'),
    ),

	array(
		'subtitle' => 'Statistics cookies',
		'content' => 'Because statistics are being tracked anonymously, no permission is asked to place statistics cookies.',
		'callback_condition' => 'NOT cmplz_cookie_warning_required_stats_uk',
		'condition' => array('compile_statistics' => 'NOT no'),

	),

    array(
	    'subtitle' => 'Advertising cookies',
	    'content' => cmplz_sprintf('On this website we use advertising cookies, enabling us to personalize the advertisements for you, and we (and third parties) gain insights into the campaign results. This happens based on a profile we create based on your click and surfing on and outside %s. With these cookies you, as website visitor are linked to a unique ID, so you do not see the same ad more than once for example.', '[domain]'),
	    'condition' => array(
		    'uses_ad_cookies' => 'yes',
		    'uses_ad_cookies_personalized' => 'NOT no'
	    ),
    ),

    array(
	    'subtitle' => 'Advertising cookies',
	    'content' => cmplz_sprintf('On this website we use advertising cookies, enabling us to gain insights into the campaign results. This happens based on a profile we create based on your behaviour on %s. With these cookies you, as website visitor, are linked to a unique ID but these cookies will not profile your behaviour and interests to serve personalized ads.', '[domain]'),
	    'condition' => array(
		    'uses_ad_cookies' => 'yes',
		    'uses_ad_cookies_personalized' => 'no'
	    ),
    ),

    array(
	    'content' => 'Because these cookies are marked as tracking cookies, we ask your permission to place these.',
	    'condition' => array('uses_ad_cookies' => 'yes'),
    ),

	array(
		'subtitle' => 'Marketing/Tracking cookies',
		'content' => 'Marketing/Tracking cookies are cookies or any other form of local storage, used to create user profiles to display advertising or to track the user on this website or across several websites for similar marketing purposes.',
//		'condition' => array(
//			'uses_ad_cookies' => 'no',
//		),
		'callback_condition' => 'cmplz_uses_marketing_cookies',
	),

	array(
		'subtitle' => 'Social media',
		'content' => 'On our website, we have included content to promote web pages (e.g. “like”, “pin”) or share (e.g. “tweet”) on social networks. This content is embedded with code derived from third parties and places cookies. This content might store and process certain information for personalized advertising.', 'Legal document cookie policy',
		'condition' => array(
			'uses_social_media' => 'yes',
			'socialmedia_on_site' => 'EMPTY',
		),
	),
	array(
		'subtitle' => 'Social media', 'Legal document cookie policy:paragraph title',
		'content' => cmplz_sprintf('On our website, we have included content from %s to promote web pages (e.g. “like”, “pin”) or share (e.g. “tweet”) on social networks like %s. This content is embedded with code derived from %s and places cookies. This content might store and process certain information for personalized advertising.', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]', '[comma_socialmedia_on_site]'),
		'condition' => array(
			'uses_social_media' => 'yes',
			'socialmedia_on_site' => 'NOT EMPTY',
		),
	),

	array(
		'content' => 'Please read the privacy statement of these social networks.' .' ' . cmplz_sprintf($social_media_format, '[comma_socialmedia_on_site]'),
		'condition' => array(
			'uses_social_media' => 'yes',
			'socialmedia_on_site' => 'NOT EMPTY',
		),
	),

	array(
		'content' => 'Please read the privacy statement of these social networks (which can change regularly) to read what they do with your (personal) data which they process using these cookies. The data that is retrieved is anonymized as much as possible.',
		'condition' => array(
			'uses_social_media' => 'yes',
			'socialmedia_on_site' => 'EMPTY',
		),
	),

    'cookie_names' => array(
	    'title' => 'Placed cookies',
	    'callback' => 'cmplz_used_cookies',
    ),

    'consent' => array(
        'title' => 'Consent',
        'content' => cmplz_sprintf('When you visit our website for the first time, we will show you a pop-up with an explanation about cookies. As soon as you click on "%s", you consent to us using all cookies and plug-ins as described in the pop-up and this Cookie Policy. You can disable the use of cookies via your browser, but please note that our website may no longer work properly.', '[cookie_accept_text]'),
        'callback_condition' => array(
            'NOT cmplz_site_uses_cookie_warning_cats',
            'cmplz_uk_site_needs_cookie_warning'
        ),
    ),

    'consent_cats' => array(
        'title' => 'Consent',
        'content' => cmplz_sprintf('When you visit our website for the first time, we will show you a pop-up with an explanation about cookies. As soon as you click on "%s", you consent to us using the categories of cookies and plug-ins you selected in the pop-up, as described in this Cookie Policy. You can disable the use of cookies via your browser, but please note that our website may no longer work properly.', '[cookie_save_preferences_text]'),
        'callback_condition' => array(
            'cmplz_site_uses_cookie_warning_cats',
            'cmplz_uk_site_needs_cookie_warning',
        )
    ),

    'revoke_btn' => array(
	    'p' => false,
	    'subtitle' => 'Manage your consent settings',
	    'content' => '[cmplz-manage-consent]',
	    'callback_condition' => 'cmplz_uk_site_needs_cookie_warning',
    ),

	array(
		'p' => false,
		'subtitle' => 'Vendors',
		'content' => '[cmplz-tcf-vendors]',
		'callback_condition' => 'cmplz_tcf_active',
	),

	'enable-disable-removal-cookies' => array(
		'title' => 'Enabling/disabling and deleting cookies', 'Legal document cookie policy:paragraph title',
		'content' => 'You can use your internet browser to automatically or manually delete cookies. You can also specify that certain cookies may not be placed. Another option is to change the settings of your internet browser so that you receive a message each time a cookie is placed. For more information about these options, please refer to the instructions in the Help section of your browser.',
	),

	'enable-disable-removal-cookies-2' => array(
		'content' => 'Please note that our website may not work properly if all cookies are disabled. If you do delete the cookies in your browser, they will be placed again after your consent when you visit our website again.',
	),

	'your-rights' => array(
        'title' => 'Your rights with respect to personal data',
        'content' =>
            'You have the following rights with respect to your personal data:',
    ),
    'your-rights-2' => array(
        'p' => false,
        'content' =>
            '<ul>
                    <li>' . 'You have the right to know why your personal data is needed, what will happen to it, and how long it will be retained for.' . '</li>
                    <li>' . 'Right of access: You have the right to access your personal data that is known to us.' . '</li>
                    <li>' . 'Right to rectification: you have the right to supplement, correct, have deleted or blocked your personal data whenever you wish.' . '</li>
                    <li>' . 'If you give us your consent to process your data, you have the right to revoke that consent and to have your personal data deleted.' . '</li>
                    <li>' . 'Right to transfer your data: you have the right to request all your personal data from the controller and transfer it in its entirety to another controller.' . '</li>
                    <li>' . 'Right to object: you may object to the processing of your data. We comply with this, unless there are justified grounds for processing.' . '</li>
                </ul>',
    ),
    'your-rights-3' => array(
        'content' =>
            "To exercise these rights, please contact us. Please refer to the contact details at the bottom of this Cookie Policy. If you have a complaint about how we handle your data, we would like to hear from you, but you also have the right to submit a complaint to the supervisory authority (the Information Commissioner's Office (ICO)).",
    ),

    'your-rights-4' => array(
        'content' =>  "For Jersey residents, please contact the Jersey Office of The Information Commissioner. Guernsey residents can contact the Office of the Data Protection Authority in Guernsey.",
        'condition' => array(
        'uk_consent_regions' => 'yes',
      )
    ),

    'contact-details' => array(
        'title' => 'Contact details',
        'content' => 'For questions and/or comments about our Cookie Policy and this statement, please contact us by using the following contact details:',
    ),
    'contact-details-2' => array(
        'content' => '<span class="cmplz-contact-organisation">[organisation_name]</span><br>
                    <span class="cmplz-contact-address">[address_company]</span><br>
                    <span class="cmplz-contact-country">[country_company]</span><br>
                    ' . 'Website: <span class="cmplz-contact-domain">[domain]</span><br>
                    ' . 'Email: <span class="cmplz-contact-email">[email_company]</span><br>
                    <span class="cmplz-contact-telephone">[telephone_company]</span>',
    ),

    'last-sync' => array(
        'content' => cmplz_sprintf('This Cookie Policy was synchronised with %scookiedatabase.org%s on %s.', '<a href="https://cookiedatabase.org/" target="_blank">', '</a>', '[sync_date]'),
        'callback_condition' => array(
	        'cmplz_cdb_reference_in_policy',
        )
    ),

);
