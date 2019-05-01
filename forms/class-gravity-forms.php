<?php
defined('ABSPATH') or die("you do not have acces to this page!");

 class cmplz_gravity_form
    {
        function __construct()
        {
            add_filter('cmplz_get_forms', array($this, 'get_plugin_forms'));
            add_action("cmplz_add_consent_box_gravity-forms", array($this, 'add_consent_checkbox'));
        }


        public function get_plugin_forms($input_forms)
        {
            $forms = GFAPI::get_forms();
            $forms = wp_list_pluck($forms, "title", "id");
            foreach ($forms as $id => $title) {
                $input_forms['gf_' . $id] = $title . " " . __('(Gravity Forms)', 'complianz-gdpr');
            }

            return $input_forms;
        }

        public function add_consent_checkbox($form_id)
        {

            $form_id = str_replace('gf_', '', $form_id);

            $form = GFAPI::get_form($form_id);
            $new_field_id = 1;
            $complianz_field_exists = false;
            foreach ($form['fields'] as $field) {
                $field_id = $field->id;
                if ($field_id > $new_field_id) $new_field_id = $field_id;
                if ($field->adminLabel == 'complianz_consent') {
                    $complianz_field_exists = true;
                };
            }
            $new_field_id++;

            if (!$complianz_field_exists) {
                $inputs = array(
                    array(
                        'id'    => $new_field_id.'.1',
                        'label' => __('Accept', 'complianz-gdpr'),
                    ),
                );
                $choices = array(
                    array(
                        'text'       => __('Accept', 'complianz-gdpr'),
                        'value'      => __('Accept', 'complianz-gdpr'),
                        'isSelected' => false,
                    ),
                );

                $consent_box = new GF_Field_Checkbox();
                $consent_box->label = COMPLIANZ()->form->label_no_link;
                $consent_box->adminLabel = 'complianz_consent';
                $consent_box->id = $new_field_id;
                $consent_box->description = '<a href="' . COMPLIANZ()->document->get_permalink('privacy-statement') . '">'.__("Privacy statement","complianz-gdpr").'</a>';
                $consent_box->isRequired = true;
                $consent_box->choices = $choices;
                $consent_box->inputs = $inputs;
                $consent_box->conditionalLogic = false;
                $form['fields'][] = $consent_box;

                GFAPI::update_form($form);
            }
        }

    }
    $cmplz_gravity_form = new cmplz_gravity_form;
