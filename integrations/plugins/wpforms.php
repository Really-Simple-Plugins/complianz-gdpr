<?php
defined('ABSPATH') or die("you do not have acces to this page!");
function cmplz_wpforms_form_types($formtypes){
    $formtypes['wpf_'] = 'wpforms';
    return $formtypes;
}
add_filter('cmplz_form_types', 'cmplz_wpforms_form_types');

function cmplz_wpforms_get_plugin_forms($input_forms)
{
    $forms = wpforms()->form->get();

    $forms = wp_list_pluck($forms, "post_title", "ID");
    foreach ($forms as $id => $title) {
        $input_forms['wpf_' . $id] = $title . " " . __('(WP Forms)', 'complianz-gdpr');
    }

    return $input_forms;
}

add_filter('cmplz_get_forms', 'cmplz_wpforms_get_plugin_forms', 10, 1);


function cmplz_wpforms_add_consent_checkbox($form_id)
{
    $form_id = str_replace('wpf_', '', $form_id);

    $form = wpforms()->form->get($form_id, array(
        'content_only' => true,
    ));
    //enable GDPR settings
    $wpforms_settings = get_option('wpforms_settings', array());
    $wpforms_settings['gdpr'] = true;
    update_option('wpforms_settings', $wpforms_settings);
    $label = sprintf(__('To submit this form, you need to accept our %sprivacy statement%s', 'complianz-gdpr'), '<a href="' . COMPLIANZ()->document->get_permalink('privacy-statement') . '">', '</a>');

    if (!wpforms_has_field_type('gdpr-checkbox', $form)) {
        $field_id = wpforms()->form->next_field_id($form_id);

        $fields = $form['fields'];
        $fields[] = array(
            'id' => $field_id,
            'type' => 'gdpr-checkbox',
            'required' => 1,
            'choices' => array(
                array(
                    'label' => $label,
                    'value' => '',
                    'image' => '',
                ),
            ),
        );
        $form['fields'] = $fields;
        wpforms()->form->update($form_id, $form);

    }
}

add_action("cmplz_add_consent_box_wpforms", 'cmplz_wpforms_add_consent_checkbox');
