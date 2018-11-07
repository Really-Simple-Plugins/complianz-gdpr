<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_document_elements', 'cmplz_dynamic_pro_document_elements', 10, 2);
function cmplz_dynamic_pro_document_elements($elements, $fields)
{
    $options = get_option('complianz_options_wizard');

    $purposes = isset($options['purpose_personaldata']) ? $options['purpose_personaldata'] : array();

    $purpose_elements = array(
        'purpose' => array(
            'p' => false,
            'numbering' => true,
            'title' => __("Purpose, data and retention period", 'complianz'),
            'content' => __('', 'complianz'),
        )
    );
    foreach ($purposes as $key => $value) {
        if ($value != 1) continue;

        //a key might not exist if we just disabled US, and had selected an option which is not available in the EU.
        if (!isset($fields['purpose_personaldata']['options'][$key])) continue;

        $label = $fields['purpose_personaldata']['options'][$key];
        $purpose_elements = $purpose_elements +
            array(
                $key . '_title' => array(
                    'p' => false,
                    'subtitle' => __('We use your data for the following purpose:', 'complianz'),
                    'content' => $label,
                    'numbering' => true,
                    'condition' => array('purpose_personaldata' => $key)
                ),
                $key . '_gegevens' => array(
                    'p' => false,
                    'subtitle' => __('For this purpose we use the following data:', 'complianz'),
                    'numbering' => false,
                    'content' => '[' . $key . '_data_purpose]',
                    'condition' => array('purpose_personaldata' => $key),
                ),
                $key . '_gegevens_other' => array(
                    'p' => false,
                    'numbering' => false,
                    'content' => '[' . $key . '_specify_data_purpose]',
                    'condition' => array($key . '_data_purpose' => 16),
                ),

                $key . '_processing_data_lawfull' => array(
                    'p' => false,
                    'subtitle' => __('The basis on which we may process these data is:', 'complianz'),
                    'numbering' => false,
                ),
                $key . '_lawful-basis-2' => array(
                    'p' => false,
                    'content' => __('Consent obtained', 'complianz'),
                    'numbering' => false,
                    'list' => true,
                    'condition' => array($key . '_processing_data_lawfull' => '1'),
                ),
                $key . '_lawful-basis-3' => array(
                    'p' => false,
                    'content' => __('Performance of an agreement  ', 'complianz'),
                    'numbering' => false,
                    'list' => true,
                    'condition' => array($key . '_processing_data_lawfull' => '2'),
                ),
                $key . '_lawful-basis-4' => array(
                    'p' => false,
                    'content' => __('Legal obligation', 'complianz'),
                    'numbering' => false,
                    'list' => true,
                    'condition' => array($key . '_processing_data_lawfull' => '3'),
                ),
                $key . '_lawful-basis-5' => array(
                    'p' => false,
                    'content' => __('Performance of a public task', 'complianz'),
                    'numbering' => false,
                    'list' => true,
                    'condition' => array($key . '_processing_data_lawfull' => '4'),
                ),
                $key . '_lawful-basis-6' => array(
                    'p' => false,
                    'content' => __('A legitimate interest of our own', 'complianz'),
                    'numbering' => false,
                    'list' => true,
                    'condition' => array($key . '_processing_data_lawfull' => '5'),
                ),

                $key . '_retain_data' => array(
                    'p' => false,
                    'subtitle' => __('Retention period', 'complianz'),
                    'list' => true,
                    'numbering' => false,
                    'condition' => array(
                        'purpose_personaldata' => $key),
                ),

                $key . '_retain_until_terminated' => array(
                    'p' => false,
                    'numbering' => false,
                    'list' => true,
                    'content' => __("We retain this data until the service is terminated.", 'complianz'),
                    'condition' => array($key . '_retain_data' => '1'),
                ),

                $key . '_retain_until_terminated_nr_months' => array(
                    'p' => false,
                    'numbering' => false,
                    'list' => true,
                    'content' => sprintf(__("We retain this data upon termination of the service for the following number of months: %s", 'complianz'), '[' . $key . '_retention_period_months]'),
                    'condition' => array($key . '_retain_data' => '2'),
                ),

                $key . '_retain_until_terminated_period' => array(
                    'p' => false,
                    'numbering' => false,
                    'content' => sprintf(__("Upon termination of the service we retain this data for the following period: %s.", 'complianz'), '[' . $key . '_retain_wmy]'),
                    'condition' => array($key . '_retain_data' => '3'),
                ),

                $key . '_description_criteria_retention' => array(
                    'p' => false,
                    'numbering' => false,

                    'content' => sprintf(__('We determine the retention period according to fixed objective criteria: %s', 'complianz'), '[' . $key . '_description_criteria_retention]'),
                    'condition' => array($key . 'retention_criteria' => 'yes'),
                ),

            );
    }

    if (count($purpose_elements) > 1) {
        $privacy_statement = $elements['privacy-statement'];

        $privacy_statement = array_slice($privacy_statement, 0, 2, true) +
            $purpose_elements +
            array_slice($privacy_statement, 2, count($privacy_statement) - 2, true);

        $elements['privacy-statement'] = $privacy_statement;
    }

    /*
     * US: intentionally not translatable
     *
     * */

    $purpose_elements_us = array(
        'purpose' => array(
            'p' => false,
            'numbering' => true,
            'title' => "Purpose and categories of data",
            'content' => 'Categories of personal information to be collected and the purpose for which the categories shall be used.',
        )
    );
    foreach ($purposes as $key => $value) {
        if ($value != 1) continue;

        //a key might not exist if we just disabled US, and had selected an option which is not available in the EU.
        if (!isset($fields['purpose_personaldata']['options'][$key])) continue;

        $label = $fields['purpose_personaldata']['options'][$key];
        $purpose_elements_us = $purpose_elements_us +
            array(
                $key . '_us_title' => array(
                    'p' => false,
                    'subtitle' => 'We use your data for the following purpose:',
                    'content' => $label,
                    'numbering' => true,
                    'condition' => array('purpose_personaldata' => $key)
                ),
                $key . '_us_categories' => array(
                    'p' => false,
                    'subtitle' => 'The following categories of data is collected',
                    'content' => '['.$key . '_data_purpose_us]',
                    'numbering' => false,
                    'condition' => array('purpose_personaldata' => $key)
                ),
            );
    }

    if (count($purpose_elements_us) > 1) {
        $privacy_statement_us = $elements['privacy-statement-us'];

        $privacy_statement_us = array_slice($privacy_statement_us, 0, 2, true) +
            $purpose_elements_us +
            array_slice($privacy_statement_us, 2, count($privacy_statement_us) - 2, true);

        $elements['privacy-statement-us'] = $privacy_statement_us;
    }


    return $elements;
}