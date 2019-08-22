<?php
defined('ABSPATH') or die("you do not have acces to this page!");

 class cmplz_contact_form_7
    {

        function __construct()
        {
            add_filter('cmplz_get_forms', array($this, 'get_plugin_forms'));
            add_action("cmplz_add_consent_box_contact-form-7", array($this, 'add_consent_checkbox'));
        }




        public function get_plugin_forms($input_forms)
        {
            $forms = get_posts(array('post_type' => 'wpcf7_contact_form'));
            $forms = wp_list_pluck($forms, "post_title", "ID");
            foreach ($forms as $id => $title) {
                $input_forms['cf7_' . $id] = $title . " " . __('(Contact form 7)', 'complianz-gdpr');
            }
            return $input_forms;
        }

        public function add_consent_checkbox($form_id)
        {
            $form_id = str_replace('cf7_', '', $form_id);

            $warning = 'acceptance_as_validation: on';

            $tag = "\n" . '[acceptance cmplz-acceptance]' . COMPLIANZ()->form->label . '[/acceptance]' . "\n\n";

            $contact_form = wpcf7_contact_form($form_id);

            if (!$contact_form) return;

            $properties = $contact_form->get_properties();
            $title = $contact_form->title();
            $locale = $contact_form->locale();

            //check if it's already there
            if (strpos($properties['form'], '[acceptance') === false) {
                $properties['form'] = str_replace('[submit', $tag . '[submit', $properties['form']);
            }

            if (strpos($properties['additional_settings'], $warning) === false) {
                $properties['additional_settings'] .= "\n" . $warning;
            }

            //replace [submit
            $args = array(
                'id' => $form_id,
                'title' => $title,
                'locale' => $locale,
                'form' => $properties['form'],
                'mail' => $properties['mail'],
                'mail_2' => $properties['mail_2'],
                'messages' => $properties['messages'],
                'additional_settings' => $properties['additional_settings'],
            );
            wpcf7_save_contact_form($args);
        }

    }
    $cmplz_contact_form_7 = new cmplz_contact_form_7;
