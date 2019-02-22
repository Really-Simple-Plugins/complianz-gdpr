<?php
defined('ABSPATH') or die("you do not have acces to this page!");

function cmplz_consent_box_required_on_form()
{
    $contact = cmplz_forms_used_on_sites();
    $permission_needed = (cmplz_get_value('contact_processing_data_lawfull') === '1') ? true : false;

    return ($contact && $permission_needed);
}

function cmplz_forms_used_on_sites()
{
    $purpose = cmplz_get_value('purpose_personaldata');
    if (isset($purpose['contact']) && $purpose['contact'] == 1) return true;
    return false;
}

function cmplz_site_uses_contact_forms()
{
    if (get_option('cmplz_detected_forms') && is_array(get_option('cmplz_detected_forms')) && count(get_option('cmplz_detected_forms')) > 0) return true;

    return false;
}


if (!class_exists("cmplz_form")) {
    class cmplz_form
    {
        private static $_this;
        public $label, $label_no_link;
        public $forms;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

            self::$_this = $this;

            add_action('init', array($this, 'load'), 10);
        }

        static function this()
        {
            return self::$_this;
        }

        /**
         * Load
         *
         */

        public function load()
        {
            $this->label_no_link = __('To submit this form, you need to accept our privacy statement', 'complianz-gdpr');
            $this->label = sprintf(__('To submit this form, you need to accept our %sprivacy statement%s', 'complianz-gdpr'), '<a href="' . COMPLIANZ()->document->get_permalink('privacy-statement') . '">', '</a>');

            $forms_plugins = apply_filters('cmplz_load_formplugins', array(
                'contact-form-7',
                'gravity-forms',
                'wpforms',
                //'ninja-forms'
            ));

            foreach ($forms_plugins as $forms_plugin) {

                //only load active plugins
                if (!$this->is_active($forms_plugin)) continue;

                $forms_plugin = sanitize_file_name($forms_plugin);

                if (file_exists(cmplz_path . 'forms/class-' . $forms_plugin . '.php')) {
                    require_once cmplz_path . 'forms/class-' . $forms_plugin . '.php';
                }
            }

            add_action('cmplz_wizard_wizard', array($this, 'maybe_add_consent_checkbox'), 10, 1);

        }

        /*
         * Do stuff after a page from the wizard is saved.
         *
         * */

        public function maybe_add_consent_checkbox()
        {
            $this->forms = apply_filters('cmplz_get_forms', array());

            //preload form options. Otherwise we could get conflicts with custom form fields
            update_option('cmplz_detected_forms', $this->forms);

            $forms = cmplz_get_value('add_consent_to_forms');
            if (!$forms || !is_array($forms)) return;

            $forms = array_filter($forms, function ($el) {
                return ($el == 1);
            });

            foreach ($forms as $form_id => $checked) {
                $type = $this->get_form_type($form_id);
                do_action("cmplz_add_consent_box_$type", $form_id);
            }
        }


        /**
         * Get the type of a saved form
         * @param $form_id
         * @return bool|int;
         */


        private function get_form_type($form_id){
            if (strpos($form_id, 'gf_') !== false) {
                return 'gravity-forms';
            }

            if (strpos($form_id, 'cf7_') !== false) {
                return 'contact-form-7';
            }

            if (strpos($form_id, 'wpf_') !== false) {
                return 'wpforms';
            }

            if (strpos($form_id, 'ninja_') !== false) {
                return 'ninja-forms';
            }

            return false;

        }

        /**
         * Check if a forms plugin is active
         * @param $plugin
         * @return bool
         */

        private function is_active($plugin)
        {
            switch ($plugin) {
                case 'contact-form-7':
                    return defined('WPCF7_VERSION');
                case 'gravity-forms':
                    return is_plugin_active('gravityforms/gravityforms.php');
                case 'wpforms':
                    return function_exists('wpforms');
                case 'ninja-forms':
                    return function_exists('Ninja_Forms');
            }
        }

    }
}