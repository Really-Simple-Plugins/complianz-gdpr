<?php
/*100% match*/

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_field")) {
    class cmplz_field
    {
        private static $_this;
        public $position;
        public $fields;
        public $default_args;
        public $form_errors = array();

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            add_action('plugins_loaded', array($this, 'process_save'), 10);
            add_action('complianz_before_label', array($this, 'before_label'), 10, 1);
            add_action('complianz_before_label', array($this, 'show_errors'), 10, 1);
            add_action('complianz_after_label', array($this, 'after_label'), 10, 1);
            add_action('complianz_after_field', array($this, 'after_field'), 10, 1);
            $this->load();
        }

        static function this()
        {
            return self::$_this;
        }

        public function load()
        {
            $this->default_args = array(
                "fieldname" => '',
                "type" => 'text',
                "required" => false,
                'default' => '',
                'label' => '',
                'table' => false,
                'callback_condition' => false,
                'condition' => false,
                'callback' => false,
                'placeholder' => '',
                'notice_callback' => false,
                'optional' => false,
                'disabled' => false,
            );


        }

        public function process_save()
        {

            if (isset($_POST['complianz_nonce'])) {
                //check nonce
                if (!isset($_POST['complianz_nonce']) || !wp_verify_nonce($_POST['complianz_nonce'], 'complianz_save')) return;

                $fields = COMPLIANZ()->config->fields();

                //remove multiple field
                if (isset($_POST['cmplz_remove_multiple'])) {
                    $fieldnames = array_map(function ($el) {
                        return sanitize_title($el);
                    }, $_POST['cmplz_remove_multiple']);

                    foreach ($fieldnames as $fieldname => $key) {

                        $page = $fields[$fieldname]['page'];
                        $options = get_option('complianz_options_' . $page);

                        $multiple_field = $this->get_value($fieldname, array());
                        //in case of cookies, store the deleted ones, otherwise
                        if ($fieldname === 'used_cookies') {

                            $cookie_key = $multiple_field[$key]['key'];
                            $deleted_cookies = get_option('cmplz_deleted_cookies');
                            if (!is_array($deleted_cookies)) $deleted_cookies = array();
                            if (!in_array($cookie_key, $deleted_cookies)) $deleted_cookies[] = $cookie_key;
                            update_option('cmplz_deleted_cookies', $deleted_cookies);
                        }
                        unset($multiple_field[$key]);


                        $options[$fieldname] = $multiple_field;
                        update_option('complianz_options_' . $page, $options);
                    }
                }

                //add multiple field
                if (isset($_POST['cmplz_add_multiple'])) {
                    $fieldname = $this->sanitize_fieldname($_POST['cmplz_add_multiple']);
                    $this->add_multiple_field($fieldname);
                }

                //save multiple field
                if ((isset($_POST['cmplz-save']) || isset($_POST['cmplz-next'])) && isset($_POST['cmplz_multiple'])) {
                    $fieldnames = $this->sanitize_array($_POST['cmplz_multiple']);
                    $this->save_multiple($fieldnames);
                }

                //save data
                $posted_fields = array_filter($_POST, array($this, 'filter_complianz_fields'), ARRAY_FILTER_USE_KEY);

                foreach ($posted_fields as $fieldname => $fieldvalue) {
                    $this->save_field($fieldname, $fieldvalue);
                }

                //we're assuming the page is the same for all fields here, as it's all on the same page (or should be)

            }
        }

        public function sanitize_array($array)
        {
            foreach ($array as &$value) {
                if (!is_array($value))
                    $value = sanitize_text_field($value);
                else
                    $this->sanitize_array($value);
            }

            return $array;

        }

        public function is_conditional($fieldname)
        {
            $fields = COMPLIANZ()->config->fields();
            if (isset($fields[$fieldname]['condition']) && $fields[$fieldname]['condition']) return true;

            return false;
        }


        public function is_multiple_field($fieldname)
        {
            $fields = COMPLIANZ()->config->fields();
            if (isset($fields[$fieldname]['type']) && ($fields[$fieldname]['type'] == 'thirdparties')) return true;

            return false;
        }


        public function save_multiple($fieldnames)
        {
            $fields = COMPLIANZ()->config->fields();
            foreach ($fieldnames as $fieldname => $saved_fields) {
                if (!isset($fields[$fieldname])) return;

                $page = $fields[$fieldname]['page'];
                $type = $fields[$fieldname]['type'];
                $options = get_option('complianz_options_' . $page);
                $multiple_field = $this->get_value($fieldname, array());

                foreach ($saved_fields as $key => $value) {
                    $value = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
                    $multiple_field[$key] = $value;

                    //make cookies and thirdparties translatable
                    if ($type==='cookies' || $type==='thirdparties' || $type==='editor'){
                        if (is_string($value) && isset($fields[$fieldname]['translatable']) && $fields[$fieldname]['translatable']) {
                            do_action('cmplz_register_translation', $fieldname . "_" . $key, $value);
                        }
                    }
                }

                $options[$fieldname] = $multiple_field;
                update_option('complianz_options_' . $page, $options);
            }
        }


        public function save_field($fieldname, $fieldvalue)
        {

            $fields = COMPLIANZ()->config->fields();
            $fieldname = str_replace("cmplz_", '', $fieldname);

            //do not save callback fields
            if (isset($fields[$fieldname]['callback'])) return;

            $type = $fields[$fieldname]['type'];
            $page = $fields[$fieldname]['page'];
            $required = isset($fields[$fieldname]['required']) ? $fields[$fieldname]['required'] : false;

            $fieldvalue = $this->sanitize($fieldvalue, $type);

            if (!$this->is_conditional($fieldname) && $required && empty($fieldvalue)) {
                $this->form_errors[] = $fieldname;
            }

            //make translatable
            if ($type == 'text' || $type == 'textarea' || $type == 'editor') {
                if (isset($fields[$fieldname]['translatable']) && $fields[$fieldname]['translatable']) {
                    do_action('cmplz_register_translation', $fieldname, $fieldvalue);
                }
            }

            $options = get_option('complianz_options_' . $page);

            $prev_value = isset($options[$fieldname]) ? $options[$fieldname] : false;

            do_action("complianz_before_save_" . $page . "_option", $fieldname, $fieldvalue, $prev_value, $type);
            $options[$fieldname] = $fieldvalue;

            update_option('complianz_options_' . $page, $options);
        }


        public function add_multiple_field($fieldname, $cookie_type = false)
        {
            $fields = COMPLIANZ()->config->fields();

            $page = $fields[$fieldname]['page'];
            $options = get_option('complianz_options_' . $page);

            $multiple_field = $this->get_value($fieldname, array());
            if ($fieldname === 'used_cookies' && !$cookie_type) $cookie_type = 'custom_' . time();
            if (!is_array($multiple_field)) $multiple_field = array($multiple_field);
            if ($cookie_type) {
                //prevent key from being added twice
                foreach ($multiple_field as $index => $cookie) {
                    if ($cookie['key'] === $cookie_type) return;
                }
                $multiple_field[] = array('key' => $cookie_type);
            } else {
                $multiple_field[] = array();
            }

            $options[$fieldname] = $multiple_field;

            update_option('complianz_options_' . $page, $options);
        }

        public function sanitize($value, $type)
        {

            switch ($type) {
                case 'colorpicker':
                    return sanitize_hex_color($value);
                case 'text':
                    return sanitize_text_field($value);
                case 'multicheckbox':
                    if (!is_array($value)) $value = array($value);
                    return array_map('sanitize_text_field', $value);
                case 'phone':
                    $value = sanitize_text_field($value);
                    return $value;
                case 'email':
                    return sanitize_email($value);
                case 'url':
                    return esc_url_raw($value);
                case 'number':
                    return intval($value);
                case 'editor':
                    return wp_kses_post($value);
            }
            return sanitize_text_field($value);
        }

        /**/

        private
        function filter_complianz_fields($fieldname)
        {
            return (strpos($fieldname, 'cmplz_') !== FALSE && isset(COMPLIANZ()->config->fields[str_replace('cmplz_', '', $fieldname)]));
        }

        public
        function before_label($args)
        {

            $condition = false;
            $condition_question = '';
            $condition_answer = '';

            if (!empty($args['condition'])) {
                $condition = true;
                $condition_answer = reset($args['condition']);
                $condition_question = key($args['condition']);
            }
            $condition_class = $condition ? 'condition-check' : '';

            $this->get_master_label($args);

            if ($args['table']) {
                echo '<tr class="' . $condition_class . '"';
                echo $condition ? 'data-condition-question="' . esc_attr($condition_question) . '" data-condition-answer="' . esc_attr($condition_answer) . '"' : '';
                echo '><th scope="row">';
            } else {
                echo '<div class="field-group ' . esc_attr($condition_class) . '" ';
                echo $condition ? 'data-condition-question="' . esc_attr($condition_question) . '" data-condition-answer="' . esc_attr($condition_answer) . '"' : '';
                echo '><div class="cmplz-label">';
            }
        }

        public function get_master_label($args)
        {
            if (!isset($args['master_label'])) return;
            ?>
            <div class="cmplz-master-label"><?php echo esc_html($args['master_label']) ?></div>
            <hr>
            <?php

        }

        public
        function show_errors($args)
        {
            if (in_array($args['fieldname'], $this->form_errors)) {
                ?>
                <div class="cmplz-form-errors">
                    <?php _e("This field is required. Please complete the question before continuing", 'complianz') ?>
                </div>
                <?php
            }
        }

        public
        function after_label($args)
        {
            if ($args['optional']) {
                echo '<span class="cmplz-optional">' . __("(Optional)", 'complianz') . '</span>';
            }
            if ($args['table']) {
                echo '</th><td>';
            } else {
                echo '</div><div class="cmplz-field">';
            }

            if ($args['notice_callback']) {
                do_action('cmplz_notice_' . $args['notice_callback'], $args);
            }
        }

        public
        function after_field($args)
        {
            $this->get_comment($args);
            $this->get_help_tip($args);
            if ($args['table']) {
                echo '</td></tr>';
            } else {
                echo '</div></div>';
            }
        }


        public
        function text($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input <?php if ($args['required']) echo 'required'; ?>
                    class="validation <?php if ($args['required']) echo 'is-required'; ?>"
                    placeholder="<?php echo esc_html($args['placeholder']) ?>"
                    type="text"
                    value="<?php echo esc_html($value) ?>"
                    name="<?php echo esc_html($fieldname) ?>">
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function url($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input <?php if ($args['required']) echo 'required'; ?>
                    class="validation <?php if ($args['required']) echo 'is-required'; ?>"
                    placeholder="<?php echo esc_html($args['placeholder']) ?>"
                    type="text"
                    pattern="^(http(s)?(:\/\/))?(www\.)?[a-zA-Z0-9-_\.\/]+"
                    value="<?php echo esc_html($value) ?>"
                    name="<?php echo esc_html($fieldname) ?>">
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function email($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input <?php if ($args['required']) echo 'required'; ?>
                    class="validation <?php if ($args['required']) echo 'is-required'; ?>"
                    placeholder="<?php echo esc_html($args['placeholder']) ?>"
                    type="email"
                    value="<?php echo esc_html($value) ?>"
                    name="<?php echo esc_html($fieldname) ?>">
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function phone($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input autocomplete="tel" <?php if ($args['required']) echo 'required'; ?>
                   class="validation <?php if ($args['required']) echo 'is-required'; ?>"
                   placeholder="<?php echo esc_html($args['placeholder']) ?>"
                   type="text" pattern="^[+|-|0-9|(|)]{8,16}$"
                   value="<?php echo esc_html($value) ?>"
                   name="<?php echo esc_html($fieldname) ?>">
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function number($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input <?php if ($args['required']) echo 'required'; ?>
                    class="validation <?php if ($args['required']) echo 'is-required'; ?>"
                    placeholder="<?php echo esc_html($args['placeholder']) ?>"
                    type="number"
                    value="<?php echo esc_html($value) ?>"
                    name="<?php echo esc_html($fieldname) ?>">
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }


        public
        function checkbox($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);

            if (!$this->show_field($args)) return;
            ?>
            <?php do_action('complianz_before_label', $args); ?>

            <label for="<?php echo esc_html($fieldname) ?>"><?php echo $args['label'] ?></label>

            <?php do_action('complianz_after_label', $args); ?>

            <input name="<?php echo esc_html($fieldname) ?>" type="hidden" value=""/>

            <input name="<?php echo esc_html($fieldname) ?>" size="40" type="checkbox"
                <?php if ($args['disabled']) echo 'disabled'; ?>
                   class="<?php if ($args['required']) echo 'is-required'; ?>"
                   value="1" <?php checked(1, $value, true) ?> />

            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function multicheckbox($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname']);
            $default_index = $args['default'];

            if (!$this->show_field($args)) return;

            ?>
            <?php do_action('complianz_before_label', $args); ?>

            <label for="<?php echo esc_html($fieldname) ?>"><?php echo $args['label'] ?></label>

            <?php do_action('complianz_after_label', $args); ?>
            <?php if (!empty($args['options'])) {?>
            <?php foreach ($args['options'] as $option_key => $option_label) {

            $sel_key = (isset($value[$option_key]) && $value[$option_key]) ? $option_key : $default_index;
            ?>
            <div class="cmplz-validate-multicheckbox">
                <input name="<?php echo esc_html($fieldname) ?>[<?php echo $option_key ?>]" type="hidden" value=""/>
                <input class="<?php if ($args['required']) echo 'is-required'; ?>"
                       name="<?php echo esc_html($fieldname) ?>[<?php echo $option_key ?>]" size="40" type="checkbox"
                       value="1" <?php echo ((string)($sel_key == (string)$option_key)) ? "checked" : "" ?> >
                <label>
                    <?php echo esc_html($option_label) ?>
                </label>
            </div>
            <?php } ?>
        <?php } else {
                cmplz_notice(__('No options found', 'complianz'));
        } ?>

            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function radio($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            $options = $args['options'];

            if (!$this->show_field($args)) return;

            ?>
            <?php do_action('complianz_before_label', $args); ?>

            <label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?></label>

            <?php do_action('complianz_after_label', $args); ?>

            <?php
            if (!empty($options)) {
                foreach ($options as $option_value => $option_label) {
                    ?>
                    <input <?php if ($args['required']) echo 'required'; ?>
                            type="radio"
                            id="<?php echo esc_html($fieldname) ?>"
                            name="<?php echo esc_html($fieldname) ?>"
                            value="<?php echo esc_html($option_value); ?>" <?php if ($value == $option_value) echo "checked" ?>>
                    <label class="">
                        <?php echo esc_html($option_label); ?>
                    </label>
                    <div class="clear"></div>
                <?php }
            }
            ?>

            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function show_field($args)
        {
            return ($this->condition_applies($args, 'callback_condition'));
        }

        public
        function condition_applies($args, $type = false)
        {
            $default_args = $this->default_args;
            $args = wp_parse_args($args, $default_args);

            if (!$type) {
                if ($args['condition']) {
                    $type = 'condition';
                } elseif ($args['callback_condition']) {
                    $type = 'callback_condition';
                }
            }
            if (!$type || !is_array($args[$type])) {
                return true;
            }

            $condition = $args[$type];

            foreach ($condition as $c_fieldname => $c_value_content) {
                $c_values = array($c_value_content);
                if (strpos($c_value_content, ',') !== FALSE) {
                    $c_values = explode(',', $c_value_content);
                }
                //if ($c_fieldname=='contact_processing_data_lawfull') _log("lawfull ".$c_value_content);
                foreach ($c_values as $c_value) {
                    $actual_value = cmplz_get_value($c_fieldname);
                    $fieldtype = $this->get_field_type($c_fieldname);

                    if ($fieldtype == 'multicheckbox') {
                       // _log($c_fieldname);

                        if (!is_array($actual_value)) $actual_value = array($actual_value);
                        //get all items that are set to true
                        $actual_value = array_filter($actual_value, function ($item) {
                            return $item == 1;
                        });
                        $actual_value = array_keys($actual_value);

                        if (strpos($c_value, 'NOT ') === FALSE) {
                            if (!in_array($c_value, $actual_value)) {
                                return false;
                            }
                        } else {
                            $c_value = str_replace("NOT ", "", $c_value);
                            if (in_array($c_value, $actual_value)) {
                                return false;
                            }
                        }
                    } else {
                        if (strpos($c_value, 'NOT ') === FALSE) {
                            if ($c_value !== $actual_value) {
                                return false;
                            }
                        } else {
                            $c_value = str_replace("NOT ", "", $c_value);
                            if ($c_value === $actual_value) {
                                return false;
                            }
                        }
                    }

                }
            }

            return true;
        }

        public function get_field_type($fieldname)
        {
            if (!isset(COMPLIANZ()->config->fields[$fieldname])) return false;

            return COMPLIANZ()->config->fields[$fieldname]['type'];
        }

        public
        function textarea($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <textarea name="<?php echo esc_html($fieldname) ?>"
                      placeholder="<?php echo esc_html($args['placeholder']) ?>"><?php echo esc_html($value) ?></textarea>
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function editor($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <?php
            $settings = array(
                'media_buttons' => false,
            );
            wp_editor($value, $fieldname, $settings); ?>
            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }

        public
        function javascript($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;
            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo $args['fieldname'] ?>"><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <div id="<?php echo esc_html($fieldname) ?>editor"
                 style="height: 200px; width: 100%"><?php echo $value ?></div>
            <?php do_action('complianz_after_field', $args); ?>
            <script>
                var <?php echo esc_html($fieldname)?> =
                ace.edit("<?php echo esc_html($fieldname)?>editor");
                <?php echo esc_html($fieldname)?>.setTheme("ace/theme/monokai");
                <?php echo esc_html($fieldname)?>.session.setMode("ace/mode/javascript");
                jQuery(document).ready(function ($) {
                    var textarea = $('textarea[name="<?php echo esc_html($fieldname)?>"]');
                    <?php echo esc_html($fieldname)?>.
                    getSession().on("change", function () {
                        textarea.val(<?php echo esc_html($fieldname)?>.getSession().getValue()
                    )
                        ;
                    });
                });
            </script>
            <textarea style="display:none" name="<?php echo esc_html($fieldname) ?>"><?php echo $value ?></textarea>
            <?php
        }


        public
        function colorpicker($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;


            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo esc_html($fieldname) ?>"><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <input type="hidden" name="<?php echo esc_html($fieldname) ?>" id="<?php echo esc_html($fieldname) ?>"
                   value="<?php echo esc_html($value) ?>" class="cmplz-color-picker-hidden">
            <input type="text" name="color_picker_container" data-hidden-input='<?php echo esc_html($fieldname) ?>'
                   value="<?php echo esc_html($value) ?>" class="cmplz-color-picker"
                   data-default-color="<?php echo esc_html($args['default']) ?>">
            <?php do_action('complianz_after_field', $args); ?>

            <?php
        }


        public
        function step_has_fields($page, $step = false, $section = false)
        {
            $fields = COMPLIANZ()->config->fields($page, $step, $section);
            foreach ($fields as $fieldname => $args) {
                $default_args = $this->default_args;
                $args = wp_parse_args($args, $default_args);

                $type = ($args['callback']) ? 'callback' : $args['type'];
                $args['fieldname'] = $fieldname;

                if ($type == 'callback') {
                    return true;
                } else {
                    if ($this->show_field($args)) {
                        return true;
                    }
                }
            }
            return false;
        }

        public
        function get_fields($page, $step = false, $section = false)
        {
            $fields = COMPLIANZ()->config->fields($page, $step, $section);
            foreach ($fields as $fieldname => $args) {
                $default_args = $this->default_args;
                $args = wp_parse_args($args, $default_args);

                $type = ($args['callback']) ? 'callback' : $args['type'];
                $args['fieldname'] = $fieldname;

                switch ($type) {
                    case 'callback':
                        $this->callback($args);
                        break;
                    case 'text':
                        $this->text($args);
                        break;
                    case 'url':
                        $this->url($args);
                        break;
                    case 'select':
                        $this->select($args);
                        break;
                    case 'colorpicker':
                        $this->colorpicker($args);
                        break;
                    case 'checkbox':
                        $this->checkbox($args);
                        break;
                    case 'textarea':
                        $this->textarea($args);
                        break;
                    case 'cookies':
                        $this->cookies($args);
                        break;
                    case 'multiple':
                        $this->multiple($args);
                        break;
                    case 'radio':
                        $this->radio($args);
                        break;
                    case 'multicheckbox':
                        $this->multicheckbox($args);
                        break;
                    case 'javascript':
                        $this->javascript($args);
                        break;
                    case 'email':
                        $this->email($args);
                        break;
                    case 'phone':
                        $this->phone($args);
                        break;
                    case 'thirdparties':
                        $this->thirdparties($args);
                        break;
                    case 'number':
                        $this->number($args);
                        break;
                    case 'notice':
                        $this->notice($args);
                        break;
                    case 'editor':
                        $this->editor($args);
                        break;
                }
            }

        }

        public
        function callback($args)
        {
            $callback = $args['callback'];
            do_action("cmplz_$callback", $args);
        }

        public
        function notice($args)
        {
            if (!$this->show_field($args)) return;
            cmplz_notice($args['label']);

        }

        public
        function select($args)
        {
            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value($args['fieldname'], $args['default']);
            if (!$this->show_field($args)) return;

            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label for="<?php echo esc_html($fieldname) ?>"><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <select <?php if ($args['required']) echo 'required'; ?> name="<?php echo esc_html($fieldname) ?>">
                <option value=""><?php _e("Choose an option", 'complianz') ?></option>
                <?php foreach ($args['options'] as $option_key => $option_label) { ?>
                    <option value="<?php echo esc_html($option_key) ?>" <?php echo ($option_key == $value) ? "selected" : "" ?>><?php echo esc_html($option_label) ?></option>
                <?php } ?>
            </select>

            <?php do_action('complianz_after_field', $args); ?>
            <?php
        }


        public
        function save_button()
        {
            wp_nonce_field('complianz_save', 'complianz_nonce');
            ?>
            <th></th>
            <td>
                <input class="button button-primary" type="submit" name="cmplz-save"
                       value="<?php _e("Save", 'complianz') ?>">

            </td>
            <?php
        }


        public
        function multiple($args)
        {
            $values = $this->get_value($args['fieldname']);
            if (!$this->show_field($args)) return;
            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label><?php echo esc_html($args['label']) ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <button class="button" type="submit" name="cmplz_add_multiple"
                    value="<?php echo esc_html($args['fieldname']) ?>"><?php _e("Add new", 'complianz') ?></button>
            <br><br>
            <?php
            if ($values) {
                foreach ($values as $key => $value) {
                    ?>

                    <div>
                        <div>
                            <label><?php _e('Description', 'complianz') ?></label>
                        </div>
                        <div>
                        <textarea class="cmplz_multiple"
                                  name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][description]"><?php if (isset($value['description'])) echo esc_html($value['description']) ?></textarea>
                        </div>

                    </div>
                    <button class="button cmplz-remove" type="submit"
                            name="cmplz_remove_multiple[<?php echo esc_html($args['fieldname']) ?>]"
                            value="<?php echo $key ?>"><?php _e("Remove", 'complianz') ?></button>
                    <?php
                }
            }
            ?>
            <?php do_action('complianz_after_field', $args); ?>
            <?php

        }


        public
        function cookies($args)
        {
            $values = $this->get_value($args['fieldname']);
            //move "complianz" cookie to the top.

            foreach ($values as $key => $cookie) {
                if ($cookie['key'] === 'complianz') {
                    $temp = $values[$key];
                    unset($values[$key]);
                    $new_arr = array();
                    $new_arr[$key] = $cookie;
                    $values = $new_arr + $values;
                }
            }


            if (!$this->show_field($args)) return;


            ?>

            <?php do_action('complianz_before_label', $args); ?>
            <label><?php _e("Cookies", 'complianz') ?></label>
            <?php do_action('complianz_after_label', $args); ?>

            <?php
            if ($values) {
                $index = 0;
                foreach ($values as $key => $value) {
                    $value_key = (isset($value['key'])) ? $value['key'] : false;
                    $default_index = array(
                        'label' => COMPLIANZ()->cookie->get_default_value('label', $value_key),
                        'used_names' => COMPLIANZ()->cookie->get_default_value('used_names', $value_key),
                        'purpose' => COMPLIANZ()->cookie->get_default_value('purpose', $value_key),
                        'privacy_policy_url' => COMPLIANZ()->cookie->get_default_value('privacy_policy_url', $value_key),
                        'storage_duration' => COMPLIANZ()->cookie->get_default_value('storage_duration', $value_key),
                        'description' => COMPLIANZ()->cookie->get_default_value('description', $value_key),
                        'key' => COMPLIANZ()->cookie->get_default_value('key', $value_key),
                        'functional' => COMPLIANZ()->cookie->get_default_value('functional', $value_key),
                        'show' => COMPLIANZ()->cookie->get_default_value('show', $value_key),
                    );
                    $index++;
                    $value = wp_parse_args($value, $default_index);
                    //first, we try if there's a fieldname.
                    if (!empty($value['label'])) {
                        $cookiename = $value['label'];
                    } elseif (isset(COMPLIANZ()->config->known_cookie_keys[$value['key']])) {
                        $cookiename = COMPLIANZ()->config->known_cookie_keys[$value['key']]['label'];
                    } elseif (!empty($value['key'])) {
                        $cookiename = $value['key'];
                    } else {
                        $cookiename = __("Not recognized", 'complianz');
                    }
                    ?>

                    <div class="cmplz-cookie-field">
                        <div class="cmplz-cookie-header">
                            <label><?php echo sprintf(__('Information for the cookie "%s"', 'complianz'), $cookiename) ?></label>
                        </div>
                        <div>
                            <div><label><?php _e('Name', 'complianz')?></label></div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][label]"
                                   value="<?php echo esc_html($value['label']) ?>">
                            <input type="hidden"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][key]"
                                   value="<?php echo esc_html($value['key']) ?>">
                        </div>
                        <div>

                        </div>
                        <div>
                            <label>
                                <input type="hidden"
                                       name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][functional]"
                                       value="">
                                <input type="checkbox"
                                       name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][functional]"
                                    <?php if ($value['functional']) echo "checked" ?>>
                                <?php _e('This is a functional cookie', 'complianz') ?></label>
                        </div>
                        <div>
                            <label>
                                <input type="hidden"
                                       name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][show]"
                                       value="">
                                <input type="checkbox"
                                       name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo $key ?>][show]"
                                    <?php if ($value['show']) echo "checked" ?>>
                                <?php _e('Add this cookie to the cookie policy', 'complianz') ?></label>
                        </div>
                        <br>
                        <div>
                            <label><?php _e('Used names', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][used_names]"
                                   value="<?php echo esc_html($value['used_names']) ?>">
                        </div>
                        <div>
                            <label><?php _e('Privacy policy URL', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][privacy_policy_url]"
                                   value="<?php echo esc_html($value['privacy_policy_url']) ?>">
                        </div>
                        <div>
                            <label><?php _e('Purpose', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][purpose]"
                                   value="<?php echo esc_html($value['purpose']) ?>">
                        </div>
                        <div>
                            <label><?php _e('Retention period', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][storage_duration]"
                                   value="<?php echo esc_html($value['storage_duration']) ?>">
                        </div>
                        <div>
                            <label><?php _e('Description', 'complianz') ?></label>
                        </div>
                        <div>
                        <textarea class="cmplz_multiple"
                                  name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][description]"><?php echo esc_html($value['description']) ?></textarea>
                        </div>


                    <button class="button cmplz-remove" type="submit"
                            name="cmplz_remove_multiple[<?php echo esc_html($args['fieldname']) ?>]"
                            value="<?php echo esc_html($key) ?>"><?php _e("Remove", 'complianz') ?></button>
                    </div>
                    <?php
                }
            }
            ?>
            <br><br>
            <button class="button" type="submit" class="cmplz-add-new-cookie" name="cmplz_add_multiple"
                    value="<?php echo esc_html($args['fieldname']) ?>"><?php _e("Add new cookie", 'complianz') ?></button>

            <?php do_action('complianz_after_field', $args); ?>
            <?php

        }

        public
        function thirdparties($args)
        {
            $values = $this->get_value($args['fieldname']);
            if (!is_array($values)) $values = array();
            if (!$this->show_field($args)) return;
            ?>
            <?php do_action('complianz_before_label', $args); ?>
            <label><?php echo $args["label"] ?></label>
            <?php do_action('complianz_after_label', $args); ?>
            <button class="button" type="submit" class="cmplz-add-new-thirdparty" name="cmplz_add_multiple"
                    value="<?php echo esc_html($args['fieldname']) ?>"><?php _e("Add new thirdparty", 'complianz') ?></button>
            <br><br>
            <?php
            if ($values) {
                foreach ($values as $key => $value) {
                    $default_index = array(
                        'name' => '',
                        'country' => '',
                        'purpose' => '',
                        'data' => '',
                    );

                    $value = wp_parse_args($value, $default_index);
                    ?>
                    <div>
                        <div>
                            <label><?php _e("What is the name of the third party with whom you share the data?", 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][name]"
                                   value="<?php echo esc_html($value['name']) ?>">
                        </div>
                        <div>
                            <label><?php _e('Third party country', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][country]"
                                   value="<?php echo esc_html($value['country']) ?>">
                        </div>


                        <div>
                            <label><?php _e('Purpose', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][purpose]"
                                   value="<?php echo esc_html($value['purpose']) ?>">
                        </div>
                        <div>
                            <label><?php _e('What type of data is shared', 'complianz') ?></label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple[<?php echo esc_html($args['fieldname']) ?>][<?php echo esc_html($key) ?>][data]"
                                   value="<?php echo esc_html($value['data']) ?>">
                        </div>

                    </div>
                    <button class="button cmplz-remove" type="submit"
                            name="cmplz_remove_multiple[<?php echo esc_html($args['fieldname']) ?>]"
                            value="<?php echo esc_html($key) ?>"><?php _e("Remove", 'complianz') ?></button>
                    <?php
                }
            }
            ?>
            <?php do_action('complianz_after_field', $args); ?>
            <?php

        }


        public
        function get_value($fieldname, $default = '')
        {
            $fields = COMPLIANZ()->config->fields();
            $page = $fields[$fieldname]['page'];
            $options = get_option('complianz_options_' . $page);

            $value = isset($options[$fieldname]) ? $options[$fieldname] : apply_filters('complianz_default_value', $default, $fieldname);
            return $value;
        }

        public
        function sanitize_fieldname($fieldname)
        {
            $fields = COMPLIANZ()->config->fields();
            if (array_key_exists($fieldname, $fields)) return $fieldname;

            return false;
        }

        public
        function get_help_tip($args)
        {
            if (!isset($args['help'])) return;
            ?>
            <span class="cmplz-tooltip-right tooltip-right" data-cmplz-tooltip="<?php echo $args['help'] ?>">
              <span class="dashicons dashicons-editor-help"></span>
            </span>
            <?php
        }

        public
        function get_comment($args)
        {
            if (!isset($args['comment'])) return;
            ?>
            <div class="cmplz-comment"><?php echo $args['comment'] ?></div>
            <?php
        }


        /*
         * Check if all required fields are answered
         *
         *
         *
         * */

        public
        function step_complete($step)
        {

        }


        /*
         * Check if all required fields in a section are answered
         *
         *
         * */

        public
        function section_complete($section)
        {

        }


        public
        function has_errors()
        {
            if (count($this->form_errors) > 0) {
                return true;
            }


            return false;
        }


    }
} //class closure
