<?php
defined('ABSPATH') or die("you do not have acces to this page!");

function cmplz_consent_box_required_on_form()
{
    $contact = cmplz_forms_used_on_sites();
    $permission_needed = (cmplz_get_value('contact_processing_data_lawfull') === '1') ? true : false;
    return ($contact && $permission_needed);
}

function cmplz_consent_box_implemented_on_forms()
{
    return (cmplz_get_value('consent_implemented_on_forms') === 'yes');
}

function cmplz_forms_used_on_sites()
{
    $purpose = cmplz_get_value('purpose_personaldata');
    if (isset($purpose['contact']) && $purpose['contact'] == 1) return true;
    return false;
}

function cmplz_site_uses_contact_forms()
{

    if (count(get_option('cmplz_detected_forms')) > 0) return true;

    return false;
}


if (!class_exists("cmplz_form")) {
    class cmplz_form
    {
        private static $_this;
        public $label, $label_no_link;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;
            add_action('cmplz_wizard_wizard', array($this, 'maybe_add_consent_checkbox'), 10, 4);
            $this->label_no_link = __('To submit this form, you need to accept our privacy policy','complianz');

        }

        static function this()
        {
            return self::$_this;

        }

        public function get_forms(){
            $forms = $this->get_cf7_forms() + $this->get_gravityforms_forms();

            return $forms;
        }


        /*
         * Do stuff after a page from the wizard is saved.
         *
         * */

        public function maybe_add_consent_checkbox()
        {
            //preload form options. Otherwise we could get conflicts with custom form fields
            update_option('cmplz_detected_forms',  $this->get_forms());

            $forms = cmplz_get_value('add_consent_to_forms');
            if (!$forms || !is_array($forms)) return;

            $forms = array_filter($forms, function ($el) {return ($el==1);});
            foreach ($forms as $form_id => $checked){
                $cf7_id = $this->cf7_id($form_id);
                if ($cf7_id) $this->add_consent_checkbox_cf7($cf7_id);

                $gf_id = $this->gf_id($form_id);
                if ($gf_id) $this->add_consent_checkbox_gf($gf_id);
            }
        }

        public function cf7_id($form_id){
            if (strpos($form_id, 'cf7_')===false) return false;
            return str_replace('cf7_', '', $form_id);

        }

        public function gf_id($form_id){
            if (strpos($form_id, 'gf_')===false) return false;

            return str_replace('gf_', '', $form_id);

        }

        public function get_gravityforms_forms(){
            if (!$this->gravity_forms_active()) return array();

            $forms = GFAPI::get_forms();
            $forms = wp_list_pluck($forms, "title","id");
            foreach($forms as $id => $title){
                $forms['gf_'.$id] = $title." ".__('(Gravity Forms)','complianz');
                unset($forms[$id]);
            }

            return $forms;
        }


        public function get_cf7_forms(){
            if (!$this->cf7_active()) return array();

            $forms = get_posts(array('post_type' => 'wpcf7_contact_form'));
            $forms = wp_list_pluck($forms, "post_title","ID");
            foreach($forms as $id => $title){
                $forms['cf7_'.$id] = $title." ".__('(Contact form 7)','complianz');
                unset($forms[$id]);
            }
            return $forms;
        }

        private function add_consent_checkbox_gf($form_id){
            $form = GFAPI::get_form( $form_id );
            $new_field_id = 1;
            $complianz_field_exists = false;

            foreach ($form['fields'] as $field){
                $field_id = $field->id;
                if ($field_id>$new_field_id) $new_field_id = $field_id;
                if ($field->inputName == 'complianz_consent'){
                    $complianz_field_exists = true;
                };
            }
            $new_field_id++;

            if (!$complianz_field_exists) {
                $consent_box = new GF_Field_Checkbox();
                $consent_box->label = $this->label_no_link;
                $consent_box->inputName = 'complianz_consent';
                $consent_box->id = $new_field_id;
                $consent_box->isRequired = true;
                $consent_box->choices = array(array('text' => __('Accept', 'complianz'), 'value' => 'Accept', 'isSelected' => false));
                $consent_box->inputs = array();
                $consent_box->conditionalLogic = false;
                $form['fields'][] = $consent_box;

                GFAPI::update_form($form);
            }

        }

        private function add_consent_checkbox_cf7($form_id){
            if (!$this->cf7_active()) return;


            $warning = 'acceptance_as_validation: on';
            $this->label = sprintf(__('To submit this form, you need to accept our %sprivacy policy%s','complianz'),'<a href="'.COMPLIANZ()->document->get_permalink('privacy-statement').'">', '</a>');

            $tag = "\n".'[acceptance cmplz-acceptance]'.$this->label.'[/acceptance]'."\n\n";

            $contact_form = wpcf7_contact_form( $form_id );

            $properties = $contact_form->get_properties();

            //check if it's already there
            if (strpos($properties['form'], '[acceptance')===false) {
                $properties['form'] = str_replace('[submit', $tag . '[submit', $properties['form']);
            }

            if (strpos($properties['additional_settings'], $warning)===false){
                $properties['additional_settings'] .= "\n".$warning;
            }

            //replace [submit
            $args = array(
                'id' => $form_id,
                'form' => $properties['form'],
                'additional_settings' => $properties['additional_settings'],
            );
             wpcf7_save_contact_form($args);
        }


        public function gravity_forms_active(){
            if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
                return true;
            }

            return false;

        }

        public function cf7_active(){
            if (defined('WPCF7_VERSION')){
                return true;
            }
            return false;
        }

    }
}