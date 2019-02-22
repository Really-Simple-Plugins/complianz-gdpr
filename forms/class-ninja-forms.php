<?php
defined('ABSPATH') or die("you do not have acces to this page!");

 class cmplz_ninja_forms
    {

        function __construct()
        {
            add_filter('cmplz_get_forms', array($this, 'get_plugin_forms'));
            add_action("cmplz_add_consent_box_ninja-forms", array($this, 'add_consent_checkbox'));
        }

        public function get_plugin_forms($input_forms)
        {
            $forms = Ninja_Forms()->form()->get_forms();
            if ( ! empty( $forms ) ) {
                foreach ( $forms as $form ) {

                    if ( ! $form instanceof NF_Database_Models_Form ) {
                        continue;
                    }

                    $input_forms['ninja_' . $form->get_id()] = $form->get_setting( 'title' ) . " " . __('(Ninja form)', 'complianz-gdpr');
                }
            }

            return $input_forms;
        }

        public function add_consent_checkbox($form_id)
        {
            $form_id = str_replace('ninja_', '', $form_id);
            $fields = Ninja_Forms()->form( $form_id )->get_fields();
            $highest_order = 0;
            foreach( $fields as $field ){
                $settings = $field->get_settings();
                $order = $settings['order'];
                if ($order>$highest_order) $highest_order = $order;
            }
            $highest_order++;

            $field = Ninja_Forms()->form( $form_id )->field()->get();
            /*
             * Create a Field with an array of Settings
             */
            $field_key = 'cmplz_consent_checkbox_'.time();
            $settings = array(
                'type' => 'checkbox',
//                'label' => COMPLIANZ()->form->label,
                'field_label' => COMPLIANZ()->form->label,
//                'key' => $field_key,
                'field_key' => $field_key,
                'required' => 1,
                'label_pos' => 'right',
                'order' => $highest_order,
                'default_value' => 'unchecked',
                'checked_value' => __('Checked', 'complianz-gdpr'),
                'unchecked_value' => __('Unchecked', 'complianz-gdpr'),
                'objectType' => 'Field',
                'objectDomain' => 'fields',
            );

            $field->update_settings( $settings )->save();

        }

    }
$cmplz_ninja_forms = new cmplz_ninja_forms;
