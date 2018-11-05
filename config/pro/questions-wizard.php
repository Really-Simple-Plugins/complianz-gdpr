<?php
defined('ABSPATH') or die("you do not have acces to this page!");

/*
 * condition: if a question should be dynamically shown or hidden, depending on another answer. Use NOT answer to hide if not answer.
 * callback_condition: if should be shown or hidden based on an answer in another screen.
 * callback roept action cmplz_$page_$callback aan
 * required: verplicht veld.
 * help: helptext die achter het veld getoond wordt.

                "fieldname" => '',
                "type" => 'text',
                "required" => false,
                'default' => '',
                'label' => '',
                'table' => false,
                'callback_condition' => false,
                'condition' => false,
                'callback' => false,
                'placeholder' => '',
                'optional' => false,

* */

// MY COMPANY SECTION
$this->fields = $this->fields + array(
        'privacy-statement' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => 'yes',
            'label' => __("Do you want to add a privacy statement on your site?", 'complianz'),
            'options' => $this->yes_no,
            'help' => __("This will determine whether you will have to answer questions regarding your privacy statement.", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'disclaimer' => array(
            'step' => STEP_COMPANY,
            'section' => 1,
            'page' => 'wizard',
            'default' => 'yes',
            'type' => 'radio',
            'options' => $this->yes_no,
            'label' => __("Do you want to add a disclaimer on your site?", 'complianz'),
            'help' => __("This will determine whether you will have to answer questions regarding your disclaimer.", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'california' => array(
            'step' => STEP_COMPANY,
            'section' => 2,
            'page' => 'wizard',
            'default' => 'yes',
            'type' => 'radio',
            'options' => $this->yes_no,
            'condition' => array('regions' => 'us'),
            'label' => __("Do you have visitors from california?", 'complianz'),
            'help' => __("There are some rules which only apply to California.", 'complianz'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),


        'socialmedia_company' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'options' => array(
                'facebook' => __('Facebook', 'complianz'),
                'twitter' => __('Twitter', 'complianz'),
                'linkedin' => __('Linkedin', 'complianz'),
                'googleplus' => __('Google Plus', 'complianz'),
                'whatsapp' => __('Whatsapp', 'complianz'),
            ),
            'optional' => true,
            'default' => '',
            'label' => __("Which social media do you use for questions regarding your privacy statement?", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'free_phonenr' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'phone',
            'default' => '',
            'required' => false,
            'label' => __("Enter a toll free phone number for the submission of information requests", 'complianz'),
            'document_label' => 'Toll free phone number: ',
            'callback_condition' => array(
                'regions' => 'us',
            ),
            'comment' => __('For US based companies, it is required to provide a toll free phone number for inquiries.','complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'automated_processes' => array(
            'step' => STEP_COMPANY,
            'section' => 3,
            'page' => 'wizard',
            'type' => 'radio',
            'options' => $this->yes_no,
            'required' => true,
            'help' => __("Do you use e.g. an automated CRM or Google Analytics/Adwords?", 'complianz'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'label' => __("We take decisions based on automated processes, such as profiling, that could have significant consequences for our users.", 'complianz'),
        ),

// DATA PROTECTION OFFICER
        'dpo_or_gdpr' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'label' => __("Does the following apply to you?", 'complianz'),
            'options' => array(
                'dpo' => __('We have appointed a data protection officer', 'complianz'),
                'gdpr_rep' => __('We have a GDPR representative within the EU', 'complianz'),
                'none' => __('None of the above', 'complianz')
            ),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'name_dpo' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'text',
            'required' => true,
            'default' => '',
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'label' => __("What is the name of the data protection officer", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'dpo'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'email_dpo' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'email',
            'default' => '',
            'required' => true,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'label' => __("What is the e-mail address of the data protection officer", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'dpo'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'phone_dpo' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'phone',
            'default' => '',
            'required' => false,
            'label' => __("What is the phone number of the data protection officer", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'dpo'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),
        'name_gdpr' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'text',
            'default' => '',
            'required' => true,
            'label' => __("What is the name of the representative", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'gdpr_rep'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'email_gdpr' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'email',
            'default' => '',
            'required' => true,
            'label' => __("What is the e-mail address of the representative", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'gdpr_rep'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'phone_gdpr' => array(
            'step' => STEP_COMPANY,
            'section' => 4,
            'page' => 'wizard',
            'type' => 'phone',
            'default' => '',
            'required' => true,
            'label' => __("What is the phone number of the representative?", 'complianz'),
            'condition' => array('dpo_or_gdpr' => 'gdpr_rep'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),



        // Purpose
        'purpose_personaldata' => array(
            'step' => STEP_COMPANY,
            'section' => 5,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Indicate for what purpose personal data is processed via your website:", 'complianz'),
            'help' => __("Also think about future work you will be carrying out. Regarding topic Personalized products, these are products which depend on the visitors behavior. E.g. advertisements based on pages visited.", 'complianz'),
            'required' => true,
            'options' => $this->purposes,
            'callback_notice' => 'purpose_personal_data',
            'callback_condition' => array(
                'privacy-statement' => 'yes',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        //dynamic purposes here


        // THIRD PARTIES
        'share_data_other' => array(
            'step' => STEP_COMPANY,
            'section' => 8,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'options' => array(
                '1' => __('Yes, both to processors and other third parties, whereby the data subject must give permission.', 'complianz'),
                '2' => __('No', 'complianz'),
                '3' => __('Limited: only with processors that are necessary for the fullfillment of my service', 'complianz'),
            ),
            'label' => __("Do you share personal data with other parties?", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => true,
        ),

        'processor' => array(
            'step' => STEP_COMPANY,
            'section' => 8,
            'page' => 'wizard',
            'region' => 'eu',
            'type' => 'processors',
            'required' => false,
            'default' => '',
            'condition' => array('share_data_other' => 'NOT 2'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'label' => __("Processor", 'complianz'),
            'comment' => __('If you share data with processors, add a "processor" for each one, and add the details of the data you share.', 'complianz'),
        ),

        'thirdparty' => array(
            'step' => STEP_COMPANY,
            'section' => 8,
            'page' => 'wizard',
            'type' => 'thirdparties',
            'required' => false,
            'default' => '',
            'condition' => array('share_data_other' => '1'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'label' => __("Third party details", 'complianz'),
            'comment' => __('If you share data with third parties, add a "third party" for each one, and add the details of the data you share.', 'complianz'),
        ),

        'share_data_other_us' => array(
            'step' => STEP_COMPANY,
            'section' => 9,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'options' => array(
                '1' => __('Yes, both to service providers and other third parties.', 'complianz'),
                '2' => __('No', 'complianz'),
                '3' => __('Limited: only with service providers that are necessary for the fulfillment of my service', 'complianz'),
            ),
            'label' => __("Do you share personal data with other parties?", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => true,
            'help' => __("A Service provider is a legal entity that processes information on behalf of a business and to which the business discloses a consumer's personal information for a business purpose pursuant to a written contract.", 'complianz'),
        ),

        'processor_us' => array(
            'step' => STEP_COMPANY,
            'region' => 'us',
            'section' => 9,
            'page' => 'wizard',
            'type' => 'processors',
            'required' => false,
            'default' => '',
            'condition' => array('share_data_other_us' => 'NOT 2'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'label' => __("Service Provider", 'complianz'),
            'comment' => __('If you share data with service providers, add a "Service Provider" for each one, and add the details of the data you share.', 'complianz'),
        ),

        'data_disclosed_us' => array(
            'step' => STEP_COMPANY,
            'section' => 9,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __('Select which categories of personal data you have disclosed for a business purpose in the past 12 months', 'complianz'),
            'required' => true,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us'
            ),
            //'condition' => array('share_data_other_us' => 'NOT 2'),
            'options' => $this->details_per_purpose_us,
            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
        ),

        'data_sold_us' => array(
            'step' => STEP_COMPANY,
            'section' => 9,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __('Select which categories of personal data you have sold to third parties in the past 12 months', 'complianz'),
            'required' => true,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'purpose_personaldata' => 'selling-data-thirdparty',
            ),
            'condition' => array(),
            'options' => $this->details_per_purpose_us,
            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
        ),

        /*
         * consent boxes
         * */

        'add_consent_to_forms' => array(
            'step' => STEP_COMPANY,
            'section' => 10,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'required' => false,
            'default' => '',
            'label' => __("For forms detected on your site, you can choose to add a consent checkbox", 'complianz'),
            'options' => get_option('cmplz_detected_forms'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'contact_processing_data_lawfull'=>'1',
                'regions' => 'eu',
            ),//when permission is required, add consent box
            'help' => __("You have answered that you use webforms on your site and need to ask permission. You can do this with a consent box.", 'complianz'),
        ),

        //  & SAFETY
        'consent_implemented_on_forms' => array(
            'step' => STEP_COMPANY,
            'section' => 10,
            'page' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'default' => '',
            'label' => __("Do you have consent boxes on all webforms?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'contact_processing_data_lawfull'=>'1',
//                'regions' => 'eu',
            ),//when permission is required, add consent box
            'help' => __("You have answered that you use webforms on your site and need to ask permission. You can do this with a consent box.", 'complianz'),
            'comment' => sprintf(__('See this %sarticle%s for instructions how to do this.','complianz'),'<a target="_blank" href="https://complianz.io/articles/how-to-implement-a-consent-box">','</a>'),
        ),

        'secure_personal_data' => array(
            'step' => STEP_COMPANY,
            'section' => 10,
            'page' => 'wizard',
            'type' => 'radio',
            'required' => true,
            'default' => '',
            'label' => __("How do you secure personal data acquired on your website?", 'complianz'),
            'options' => array(
                '1' => __('We give a general explanation about security in the privacy statement', 'complianz'),
                '2' => __('We will list our security measures', 'complianz')
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'which_personal_data_secure' => array(
            'step' => STEP_COMPANY,
            'section' => 10,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Which security measures did you take?", 'complianz'),
            'options' => array(
                '1' => __('Username and Password', 'complianz'),
                '2' => __('DNSSEC', 'complianz'),
                '3' => __('TLS / SSL', 'complianz'),
                '4' => __('DKIM, SPF en DMARC', 'complianz'),
                '5' => __('Physical security measures of systems which contain personal  data.', 'complianz'),
                '6' => __('Security software', 'complianz'),
                '7' => __('ISO27001/27002 certified', 'complianz'),
            ),
            'condition' => array('secure_personal_data' => '2'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        'financial-incentives' => array(
            'step' => STEP_COMPANY,
            'section' => 11,
            'page' => 'wizard',
            'required' => true,
            'type' => 'radio',
            'default' => '',
            'label' => __("Do you offer financial incentives, including payments to consumers as compensation, for the collection of personal information, the sale of personal information, or the deletion of personal information?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
        ),

        'financial-incentives-terms-url' => array(
            'step' => STEP_COMPANY,
            'section' => 11,
            'placholder' => __('https://your-terms-page.com','complianz'),
            'page' => 'wizard',
            'required' => true,
            'type' => 'url',
            'default' => '',
            'label' => __("Enter the URL of the terms & conditions page for the incentives", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'condition' => array('financial-incentives' => 'yes'),
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'comment' => __("Please note that the consumer explicitly has to consent to these terms, and that the consumer must be able to revoke this consent. ", 'complianz'),
        ),

        'targets-children' => array(
            'step' => STEP_COMPANY,
            'section' => 12,
            'page' => 'wizard',
            'required' => true,
            'type' => 'radio',
            'default' => '',
            'label' => __("Is your website designed to attract children and/or is it your intent to collect personal data from children under the age of 13?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
        ),

        'children-parent-consent-type' => array(
            'step' => STEP_COMPANY,
            'section' => 12,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("How do you obtain verifiable parental consent for the collection, use, or disclosure of personal information from children?", 'complianz'),
            'options' => array(
                'email' => __("We seek a parent or guardian's consent by email",'complianz'),
                'creditcard' => __('We seek a high level of consent by asking for a creditcard verification','complianz'),
                'phone-chat' => __('We use telephone or Videochat  to talk to the parent or guardian','complianz'),
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'condition' => array('targets-children' => 'yes'),
        ),

        'children-safe-harbor' => array(
            'step' => STEP_COMPANY,
            'section' => 12,
            'page' => 'wizard',
            'required' => true,
            'type' => 'radio',
            'default' => '',
            'label' => __("Is your website included in a COPPA Safe Harbor Certification Program?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'condition' => array(
                'targets-children' => 'yes'
            ),
        ),

        'children-name-safe-harbor' => array(
            'step' => STEP_COMPANY,
            'section' => 12,
            'page' => 'wizard',
            'required' => true,
            'type' => 'text',
            'default' => '',
            'label' => __("What is the name of the program?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'condition' => array(
                'children-safe-harbor' => 'yes'
            ),
        ),

        'children-url-safe-harbor' => array(
            'step' => STEP_COMPANY,
            'section' => 12,
            'page' => 'wizard',
            'required' => true,
            'type' => 'url',
            'default' => '',
            'label' => __("What is the URL of the program?", 'complianz'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
            ),
            'condition' => array(
                'children-safe-harbor' => 'yes'
            ),
        ),

        'children-no-safe-harbor-notice' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => false,
            'type' => 'notice',
            'default' => '',
            'label' => sprintf(__("Your have indicated your website is not included in a COPPA Safe Harbor Certification Program. We recommend to check out %sPRIVO%s, as you target children on your website.", 'complianz'),'<a href="https://www.privo.com/">','</a>'),
            'options' => $this->yes_no,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'targets-children' => 'yes',
                'regions' => 'us',
                'children-safe-harbor' => 'no',
            ),
        ),

        'children-what-purposes' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("For what potential activities on your website do you collect personal information from a child?", 'complianz'),
            'options' => array(
                'registration' => __('Registration','complianz'),
                'content-created-by-child' => __('Content created by a child and publicly shared','complianz'),
                'chat' => __('Chat/messageboard','complianz'),
                'email' => __('Email contact','complianz'),
            ),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'targets-children' => 'yes',
            ),
        ),

        'children-what-information-registration' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Information collected for registration ", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'options' => $this->collected_info_children,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'targets-children' => 'yes',

            ),
            'condition' => array(
                'children-what-purposes' => 'registration'),
        ),

        'children-what-information-content' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Information collected for content created by a child", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'options' => $this->collected_info_children,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'targets-children' => 'yes',
            ),
            'condition' => array(
                'children-what-purposes' => 'content-created-by-child'
            ),
        ),

        'children-what-information-chat' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Information collected for chat/messageboard", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'options' => $this->collected_info_children,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'targets-children' => 'yes',

            ),
            'condition' => array(
                'children-what-purposes' => 'chat'
            ),
        ),
        'children-what-information-email' => array(
            'step' => STEP_COMPANY,
            'section' => 13,
            'page' => 'wizard',
            'required' => true,
            'type' => 'multicheckbox',
            'default' => '',
            'label' => __("Information collected for email contact", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'options' => $this->collected_info_children,
            'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'us',
                'targets-children' => 'yes',

            ),
            'condition' => array(
                'children-what-purposes' => 'email'),
        ),



        //DISCLAIMER
        'themes' => array(
            'step' => STEP_COMPANY,
            'section' => 14,
            'page' => 'wizard',
            'type' => 'multicheckbox',
            'default' => '1',
            'label' => __("Which themes would you like to include in your disclaimer?", 'complianz'),
            'options' => array(
                '1' => __('Liability', 'complianz'),
                '2' => __('Reference to terms of use', 'complianz'),
                '3' => __('How you will answer inquiries', 'complianz'),
                '4' => __('Privacy and reference to the privacy statement', 'complianz'),
                '5' => __('Not liable when security is breached', 'complianz'),
                '6' => __('Not liable for third party content', 'complianz'),
                '7' => __('Accessibility of the website for the disabled', 'complianz'),
            ),
            'callback_condition' => array('disclaimer' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => true,
        ),
        'terms_of_use_link' => array(
            'step' => STEP_COMPANY,
            'section' => 14,
            'page' => 'wizard',
            'type' => 'url',
            'default' => '',
            'label' => __("What is the URL of the Terms of Use?", 'complianz'),
            'condition' => array('themes' => '2'),
            'callback_condition' => array('disclaimer' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => true,
        ),

        'wcag' => array(
            'step' => STEP_COMPANY,
            'section' => 14,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => 'The WCAG documents explain how to make web content more accessible to people with disabilities.',
            'label' => __("Is your website built according to WCAG 2.0 level AA guidelines?", 'complianz'),
            'options' => $this->yes_no,
            'condition' => array('themes' => '7'),
            'callback_condition' => array('disclaimer' => 'yes'),
            'required' => true,
            'time' => CMPLZ_MINUTES_PER_QUESTION,
        ),

        // AUTEURSRECHTEN disclaimer
        'development' => array(
            'step' => STEP_COMPANY,
            'section' => 14,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'label' => __("Who made the content of the website?", 'complianz'),
            'options' => array(
                '1' => __('The content is being developed by ourselves', 'complianz'),
                '2' => __('The content is being developed or posted by third parties', 'complianz'),
                '3' => __('The content is being developed by ourselves and other parties', 'complianz'),
            ),
            'callback_condition' => array('disclaimer' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => true,
        ),

        'ip-claims' => array(
            'step' => STEP_COMPANY,
            'section' => 14,
            'page' => 'wizard',
            'type' => 'radio',
            'default' => '',
            'required' => true,
            'label' => __("What do you want to do with any intellectual property claims?", 'complianz'),
            'options' => array(
                '1' => __('All rights reserved', 'complianz'),
                '2' => __('No rights reserved', 'complianz'),
                '3' => __('Creative Commons - Attribution', 'complianz'),
                '4' => __('Creative Commons - Share a like', 'complianz'),
                '5' => __('Creative Commons - No derivatives', 'complianz'),
                '6' => __('Creative Commons - Noncommercial', 'complianz'),
                '7' => __('Creative Commons - Share a like, noncommercial', 'complianz'),
            ),
            'callback_condition' => array('disclaimer' => 'yes'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'help' => __("Creative Commons (CC) is an American non-profit organization devoted to expanding the range of creative works available for others to build upon legally and to share.", 'complianz'),
        ),

    );


$this->fields = $this->fields + array(
        'wp_privacy_policies' => array(
            'step' => STEP_PLUGINS,
            'section' => 1,
            'page' => 'wizard',
            'type' => 'multiple',
            'label' => __("Do you want to add a disclaimer on your site?", 'complianz'),
            'callback' => 'wp_privacy_policies',
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => false,
        ),
        'custom_privacy_policy_text' => array(
            'step' => STEP_PLUGINS,
            'section' => 1,
            'translatable' => true,
            'page' => 'wizard',
            'type' => 'editor',
            'label' => __("", 'complianz'),
            'time' => CMPLZ_MINUTES_PER_QUESTION,
            'required' => false,
            'media'=>false,
        ),
    );
