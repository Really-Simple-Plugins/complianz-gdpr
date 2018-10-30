<?php
defined('ABSPATH') or die("you do not have acces to this page!");
add_filter('cmplz_fields', 'cmplz_filter_fields', 10, 1);
function cmplz_filter_fields($fields)
{

    /*
     * Add dynamic purposes
     *
     * */

    $options = get_option('complianz_options_wizard');
    $purposes = isset($options['purpose_personaldata']) ? $options['purpose_personaldata'] : array();


    if (cmplz_has_region('us')) {

        foreach ($purposes as $key => $value) {
            if ($value != 1) continue;

            if (!isset($fields['purpose_personaldata']['options'][$key])) continue;

            $label = $fields['purpose_personaldata']['options'][$key];
            if (!empty(COMPLIANZ()->config->details_per_purpose_us)) {
                $fields = $fields + array(
                        $key . '_data_purpose_us' => array(
                            'master_label' => __("Purpose:", 'complianz') . " " . $label,
                            'step' => 1,
                            'section' => 7,
                            'page' => 'wizard',
                            'type' => 'multicheckbox',
                            'default' => '',
                            'label' => __("What data do you collect for this purpose?", 'complianz'),
                            'required' => true,
                            'callback_condition' => array(
                                'privacy-statement' => 'yes',
                                'purpose_personaldata' => $key
                            ),

                            'options' => COMPLIANZ()->config->details_per_purpose_us,
                            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                        ),

                    );

            }

        }

    }

    return $fields;

}
