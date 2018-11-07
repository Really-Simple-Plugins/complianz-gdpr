<?php
defined('ABSPATH') or die("you do not have acces to this page!");


add_filter('cmplz_fields', 'cmplz_filter_pro_fields', 10, 1);
function cmplz_filter_pro_fields($fields)
{

    /*
     * This overrides the free version of the geo ip option
     *
     * */

    $fields['use_country'] = array(
        'page' => 'cookie_settings',
        'type' => 'checkbox',
        'label' => __("Use geolocation", 'complianz'),
        'comment' => __('If enabled, the cookie warning will not show for countries without a cookie law.', 'complianz'),
        'table' => true,
        'disabled' => false,
        'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
    );

    /*
     * This overrides the free version of the a/b testing option
     *
     * */

    $fields['a_b_testing'] = array(
        'page' => 'cookie_settings',
        'type' => 'checkbox',
        'label' => __("Enable A/B testing", 'complianz'),
        'comment' => __('If enabled, the plugin will track which cookie warning has the best conversion rate.', 'complianz'),
        'table' => true,
        'disabled' => false,
        'default' => false, //setting this to true will set it always to true, as the get_cookie settings will see an empty value
    );

    /*
     * This overrides the free version of the regions option
     *
     *
     *
     */

    if (!cmplz_get_value('use_country', false, 'cookie_settings')){
        $fields['regions']['comment'] = sprintf(__('To be able to select multiple countries, you should enable Geo IP in the %scookie settings%s','complianz'),'<a href="'.admin_url('admin.php?page=cmplz-cookie-warning').'">','</a>');
    } else {
        $fields['regions']['type'] = 'multicheckbox';
        $fields['regions']['comment'] = '';
    }

    /*
     * This overrides the free version of the import/export options
     *
     *
     *
     */

    $fields['export_settings'] = array(
        'page' => 'settings',
        'type' => 'button',
        'action' => 'cmplz_export_settings',
        'post_get' => 'get',
        'label' => __("Export settings", 'complianz'),
        'table' => true,
        'comment' => __('You can use this to export your settings to another site', 'complianz'),
    );

    $fields['import_settings'] = array(
        'page' => 'settings',
        'type' => 'upload',
        'action' => 'cmplz_import_settings',
        'label' => __("Import settings", 'complianz'),
        'table' => true,
        'comment' => __('You can use this to import your settings from another site', 'complianz'),
    );

    /*
     * Add dynamic purposes
     *
     * */

    if (cmplz_has_region('eu')) {
        foreach (COMPLIANZ()->config->purposes as $key => $label) {

            //selling data third parties is only for US
            if ($key == 'selling-data-thirdparty') continue;

            $fields = $fields + array(
                    $key . '_data_purpose' => array(
                        'master_label' => __("Purpose:", 'complianz') . " " . $label,
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'multicheckbox',
                        'default' => '',
                        'label' => __("What data do you collect for this purpose?", 'complianz'),
                        'required' => true,
                        'callback_condition' => array(
                            'privacy-statement' => 'yes',
                            'purpose_personaldata' => $key
                        ),
                        'options' => array(
                            '1' => __('Name, Address and City', 'complianz'),
                            '2' => __('Marital status', 'complianz'),
                            '3' => __('E-mail address', 'complianz'),
                            '4' => __('Financial data', 'complianz'),
                            '5' => __('Birth date', 'complianz'),
                            '6' => __('Username, passwords and other account specific data', 'complianz'),
                            '7' => __('Sex', 'complianz'),
                            '8' => __('IP Address', 'complianz'),
                            '9' => __('Location', 'complianz'),
                            '10' => __('Medical data', 'complianz'),
                            '11' => __('Visitor behavior', 'complianz'),
                            '12' => __('Photos', 'complianz'),
                            '13' => __('Social media accounts', 'complianz'),
                            '14' => __('Criminal or legal data', 'complianz'),
                            '15' => __('Telephone number', 'complianz'),
                            '16' => __('Other:', 'complianz'),
                        ),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                    ),

                    $key . '_specify_data_purpose' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'text',
                        'default' => '',
                        'required' => true,
                        'label' => __("Specify the purpose", 'complianz'),
                        'condition' => array($key . '_data_purpose' => 16),
                        'callback_condition' => array('privacy-statement' => 'yes', 'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                    ),

                    $key . '_retain_data' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'radio',
                        'default' => '',
                        'required' => true,
                        'label' => __("How long will you retain data for this specific purpose?", 'complianz'),
                        'options' => array(
                            '1' => __('When the services are terminated or completed', 'complianz'),
                            '2' => __('When the services are terminated or completed, plus the number of months mentioned below', 'complianz'),
                            '3' => __('Other period', 'complianz'),
                            '4' => __("I determine the retention period according to fixed objective criteria", 'complianz'),
                        ),
                        'callback_condition' => array('privacy-statement' => 'yes', 'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,

                    ),
                    $key . '_retain_wmy' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'text',
                        'default' => '',
                        'required' => true,
                        'label' => __("Retention period in weeks, months or years:", 'complianz'),
                        'condition' => array($key . '_retain_data' => '3'),
                        'callback_condition' => array('privacy-statement' => 'yes', 'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,

                    ),
                    $key . '_retention_period_months' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'text',
                        'default' => '',
                        'required' => true,
                        'placeholder' => __('Retention period in months', 'complianz'),
                        'label' => __("Necessary retention period in months after completion:", 'complianz'),
                        'condition' => array($key . '_retain_data' => '2'),
                        'callback_condition' => array(
                            'privacy-statement' => 'yes',
                            'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,

                    ),

                    $key . '_description_criteria_retention' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'text',
                        'default' => '',
                        'required' => true,
                        'label' => __("Describe these criteria in understandable terms:", 'complianz'),
                        'condition' => array($key . '_retain_data' => '4'),
                        'callback_condition' => array('privacy-statement' => 'yes', 'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                    ),

                    $key . '_processing_data_lawfull' => array(
                        'step' => 1,
                        'section' => 6,
                        'page' => 'wizard',
                        'type' => 'radio',
                        'default' => '',
                        'required' => true,
                        'options' => array(
                            '1' => __('I obtain permission from the person concerned', 'complianz'),
                            '2' => __('It is necessary for the execution of an agreement with the person concerned', 'complianz'),
                            '3' => __('I am obligated by law', 'complianz'),
                            '4' => __('It is necessary to fulfilll a task concerning public law.', 'complianz'),
                            '5' => __('It is necessary for my own legitimate interest, and that interest outweighs the interest of the person concerned.', 'complianz'),
                        ),
                        'label' => __("The processing of personal data always requires a lawfull basis, which do you use?", 'complianz'),
                        'callback_condition' => array('privacy-statement' => 'yes', 'purpose_personaldata' => $key),
                        'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                        'comment' => sprintf(__('For help on how to answer these questions, please see this %sarticle%s', 'complianz'), '<a href="https://complianz.io/what-lawful-basis-for-data-processing">', '</a>'),

                    ),


                );

        }
    }

    return $fields;

}