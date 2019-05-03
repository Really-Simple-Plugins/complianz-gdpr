<?php
defined('ABSPATH') or die("you do not have acces to this page!");
add_filter('cmplz_fields', 'cmplz_filter_fields', 10, 1);
function cmplz_filter_fields($fields)
{

        /*
         * Add dynamic purposes
         *
         * */

    if (cmplz_has_region('us')) {
        foreach (COMPLIANZ()->config->purposes as $key => $label) {

            if (!empty(COMPLIANZ()->config->details_per_purpose_us)) {
                $fields = $fields + array(
                        $key . '_data_purpose_us' => array(
                            'master_label' => __("Purpose:", 'complianz-gdpr') . " " . $label,
                            'step' => 1,
                            'section' => 7,
                            'source' => 'wizard',
                            'type' => 'multicheckbox',
                            'default' => '',
                            'label' => __("What data do you collect for this purpose?", 'complianz-gdpr'),
                            'required' => true,
                            'callback_condition' => array(
                                'purpose_personaldata' => $key
                            ),

                            'options' => COMPLIANZ()->config->details_per_purpose_us,
                            'time' => CMPLZ_MINUTES_PER_QUESTION_QUICK,
                        ),

                    );

            }

        }

    }

    /*
     * If it's not possible to configure the stats manually, because the three conditions are not met (anonymized ip, etc)
     * we unset the condition that makes these dependent of the manual config selection
     *
     * */

    if (!cmplz_manual_stats_config_possible()){
        unset($fields['GTM_code']['condition']);
        unset($fields['UA_code']['condition']);
        unset($fields['matomo_site_id']['condition']);
        unset($fields['matomo_url']['condition']);
    }

    return $fields;

}
