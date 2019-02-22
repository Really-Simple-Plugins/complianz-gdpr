<?php
defined('ABSPATH') or die("you do not have acces to this page!");

class cmplz_wpform
{
    function __construct()
    {
        add_filter('cmplz_get_forms', array($this, 'get_plugin_forms'), 10, 1);
        add_action("cmplz_add_consent_box_wpforms", array($this, 'add_consent_checkbox'));
    }


    public function get_plugin_forms($input_forms)
    {
        $forms = wpforms()->form->get();

        $forms = wp_list_pluck($forms, "post_title", "ID");
        foreach ($forms as $id => $title) {
            $input_forms['wpf_' . $id] = $title . " " . __('(WP Forms)', 'complianz-gdpr');
        }

        return $input_forms;
    }

    public function add_consent_checkbox($form_id)
    {
        $form_id = str_replace('wpf_', '', $form_id);

        $form = wpforms()->form->get($form_id, array(
            'content_only' => true,
        ));
        //enable GDPR settings
        $wpforms_settings = get_option('wpforms_settings', array());
        $wpforms_settings['gdpr'] = true;
        update_option('wpforms_settings', $wpforms_settings);

        if (!wpforms_has_field_type('gdpr-checkbox', $form)) {
            $field_id = wpforms()->form->next_field_id($form_id);

            $fields = $form['fields'];
            $fields[] = array(
                'id' => $field_id,
                'type' => 'gdpr-checkbox',
                'required' => 1,
                'choices' => array(
                    array(
                        'label' => COMPLIANZ()->form->label,
                        'value' => '',
                        'image' => '',
                    ),
                ),
            );
            $form['fields'] = $fields;
            wpforms()->form->update($form_id, $form);

        }
    }

}
$cmplz_wpform = new cmplz_wpform;
