<?php
/*100% match*/

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_wizard")) {
    class cmplz_wizard
    {
        private static $_this;
        public $position;
        public $cookies = array();
        public $known_wizard_keys;
        public $total_steps;
        public $total_sections;
        public $page_url;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;

            add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

            //callback from settings
            add_action('cmplz_wizard_last_step', array($this, 'wizard_last_step_callback'), 10, 1);

            //link action to custom hook
            add_action('cmplz_wizard_wizard', array($this, 'wizard_after_last_step'), 10, 1);

            //process custom hooks
            add_action('admin_init', array($this, 'process_custom_hooks'));

            add_action('admin_init', array($this, 'start_wizard'), 10, 1);

            add_action('complianz_before_save_wizard_option', array($this, 'before_save_wizard_option'), 10, 4);

            //dataleaks:

            add_action('cmplz_is_wizard_completed', array($this, 'is_wizard_completed_callback'));


        }

        static function this()
        {
            return self::$_this;
        }


        public function is_wizard_completed_callback()
        {
            if (isset($_GET['page']) && ($_GET['page'] == 'cmplz-processing')) {
                $link = '<a href="' . admin_url('edit.php?post_type=cmplz-processing') . '">' . __('Processing agreements', 'complianz') . '</a>';
            }
            if (isset($_GET['page']) && ($_GET['page'] == 'cmplz-dataleak')) {
                $link = '<a href="' . admin_url('edit.php?post_type=cmplz-dataleak') . '">' . __('Dataleak reports', 'complianz') . '</a>';
            }
            if ($this->wizard_completed()) {
                echo __("Great, the wizard is completed. This means the general data is already in the system, and you can continue with the next question. This will start a new, empty document.", 'complianz');
            } else {
                $link = '<a href="' . admin_url('admin.php?page=cmplz-wizard') . '">';
                echo sprintf(__("The wizard isn't completed yet. If you have answered all required questions, you just need to click 'finish' to complete it. In the wizard some general data is entered which is needed for this document. %sPlease complete the wizard first%s.", 'complianz'), $link, "</a>");
            }
        }



        public function process_custom_hooks()
        {
            $wizard_type = (isset($_POST['wizard_type'])) ? sanitize_title($_POST['wizard_type']) : '';
            do_action("cmplz_wizard_$wizard_type");
        }

        public function initialize($page)
        {
            $this->total_steps = $this->total_steps($page);
            $this->total_sections = $this->total_sections($page, $this->step());
            $this->page_url = admin_url('admin.php?page=cmplz-' . $page);
            //if a post id was passed, we copy the contents of that page to the wizard settings.
            if (isset($_GET['post_id'])) {
                $post_id = intval($_GET['post_id']);
                //get all fields for this page
                $fields = COMPLIANZ()->config->fields($page);
                foreach ($fields as $fieldname => $field) {
                    $fieldvalue = get_post_meta($post_id, $fieldname, true);
                    if ($fieldvalue) {
                        if (!COMPLIANZ()->field->is_multiple_field($fieldname)) {
                            COMPLIANZ()->field->save_field($fieldname, $fieldvalue);
                        } else {
                            $field[$fieldname] = $fieldvalue;
                            COMPLIANZ()->field->save_multiple($field);
                        }
                    }

                }
            }
        }

        public function show_notices()
        {
            if (!is_user_logged_in()) return;
            if (cmplz_wp_privacy_version() && !current_user_can('manage_privacy_options')) return;

            if (COMPLIANZ()->cookie->cookies_changed()) {
                ?>
                <div id="message" class="error fade notice cmplz-wp-notice">
                    <h2><?php echo __("Changes in cookies detected", 'complianz'); ?></h2>
                </div>
                <?php
            }
        }


        public function wizard_last_step_callback()
        {
            $page = $this->wizard_type();

            if (!$this->all_required_fields_completed($page)) {
                _e("Not all required fields are completed yet. Please check the steps to complete all required questions", 'complianz');
            } else {
                printf(__("All steps have been completed. Click %s to complete the configuration. You can come back to change your configuration at any time.", 'complianz'), __("Finish", 'complianz'));
            }

        }


        /*
         * Process completion of setup
         *
         * */

        public function wizard_after_last_step()
        {

            if (!is_user_logged_in()) return;
            if (cmplz_wp_privacy_version() && !current_user_can('manage_privacy_options')) return;

            //create a page foreach page that is needed.
            $pages = COMPLIANZ()->config->pages;
            foreach ($pages as $type => $page) {
                if (!$page['public']) continue;

                if (COMPLIANZ()->document->page_required($page)) {
                    COMPLIANZ()->document->create_page($type);
                } else {
                    COMPLIANZ()->document->delete_page($type);
                }
            }


            if (isset($_POST['cmplz-finish'])) {

                //check if cookie warning should be enabled
                if (COMPLIANZ()->cookie->cookie_warning_required()) {
                    cmplz_update_option('cookie_settings', 'cookie_warning_enabled', true);
                } else {
                    cmplz_update_option('cookie_settings', 'cookie_warning_enabled', false);
                }

                $this->set_wizard_completed();
                COMPLIANZ()->cookie->reset_plugins_changed();
                COMPLIANZ()->cookie->reset_cookies_changed();
                COMPLIANZ()->cookie->reset_plugins_updated();

                //clear document cache
                foreach (COMPLIANZ()->config->pages as $type => $page) {
                    delete_transient("complianz_document_$type");
                }
                wp_redirect(admin_url('admin.php?page=complianz'));
                exit();
            }
        }

        /*
         * Process completion of setup
         *
         * */

        public function start_wizard()
        {
            //create a page foreach page that is needed.
            if (isset($_POST['cmplz-start'])) {
                $this->reset_wizard_completed();
                wp_redirect(add_query_arg(array("step" => "1", "section" => 1), admin_url('admin.php?page=cmplz-wizard')));
                exit();
            }

        }

        /*
         * Do stuff after a page from the wizard is saved.
         *
         * */

        public function before_save_wizard_option($fieldname, $fieldvalue, $prev_value, $type)
        {
            //we can check here if certain things have been updated,
            COMPLIANZ()->cookie->reset_cookies_changed();

            //if the fieldname is from the "revoke cookie consent on change" list, change the policy if it's changed
            $fields = COMPLIANZ()->config->fields;
            $field = $fields[$fieldname];
            if (($fieldvalue != $prev_value) && isset($field['revoke_consent_onchange']) && $field['revoke_consent_onchange']) {
                COMPLIANZ()->cookie->upgrade_active_policy_id();
            }

            //when the brand color is saved, update the cookie settings
            //only if nothing entered yet.

            if ($fieldname == 'brand_color'){
                $cookie_settings = get_option('complianz_options_cookie_settings' );
                error_log(print_r($cookie_settings, true));
                if (!isset($cookie_settings['popup_background_color']) || empty($cookie_settings['popup_background_color'])){
                    error_log("updating brand color");
                    cmplz_update_option('cookie_settings', 'popup_background_color', $fieldvalue);
                    cmplz_update_option('cookie_settings', 'button_text_color', $fieldvalue);
                }

            }
        }

        public function get_next_not_empty_step($page, $step)
        {
            if (!COMPLIANZ()->field->step_has_fields($page, $step)) {
                if ($step>=$this->total_steps($page)) return $step;
                $step++;
                $step = $this->get_next_not_empty_step($page, $step);
            }

            return $step;
        }

        public function get_next_not_empty_section($page, $step, $section)
        {
            $start_with = $section;
            if (!COMPLIANZ()->field->step_has_fields($page, $step, $section)) {
                $section++;
                if ($section >= 20) return false;
                $section = $this->get_next_not_empty_section($page, $step, $section);
            }

            return $section;
        }


        public function wizard($page)
        {
            if (!is_user_logged_in()) return;
            if (cmplz_wp_privacy_version() && !current_user_can('manage_privacy_options')) return;

            $this->initialize($page);
            $section = $this->section();
            $step = $this->step();

            if (isset($_POST['cmplz-next']) && !COMPLIANZ()->field->has_errors()) {
                if (COMPLIANZ()->config->has_sections($page, $step) && ($section < $this->total_sections)) {
                    $section = $section + 1;
                } else {
                    $step++;
                }
            }

            if (isset($_POST['cmplz-previous'])) {
                if (COMPLIANZ()->config->has_sections($page, $step) && $section > 1) {
                    $section--;
                } else {
                    $step--;
                }
            }

            $step = $this->get_next_not_empty_step($page, $step);
            $section = $this->get_next_not_empty_section($page, $step, $section);
            //if the last section is also empty, it will return false, so we need to skip the step too.
            if (!$section) {
                $step = $this->get_next_not_empty_step($page, $step + 1);
                $section = 1;
            }

            ?>


            <div id="cmplz-wizard">
                <div class="cmplz-header">

                    <div class="cmplz-wizard-steps">
                        <?php for ($i = 1; $i <= $this->total_steps; $i++) {
                            $active = ($i == $step) ? true : false;
                            $url = add_query_arg(array('step' => $i), $this->page_url);
                            if ($this->post_id()) $url = add_query_arg(array('post_id' => $this->post_id()), $url);
                            $step_completed = $this->required_fields_completed($page, $i, false) ? 'complete' : 'incomplete';
                            ?>
                            <div class="cmplz-step <?php if ($active) echo 'active' ?> <?php echo $step_completed ?>">
                                <div class="cmplz-step-wrap">
                                    <a href="<?php echo $url ?>">
                                        <span class="cmplz-step-count"><span><?php echo $i ?></span></span>
                                        <span class="cmplz-step-title"><?php echo COMPLIANZ()->config->steps[$page][$i]['title'] ?></span>
                                    </a>
                                </div>
                                <?php if ($active) { ?>
                                    <div class="cmplz-step-time"><?php printf(__('%s min', 'complianz'), $this->remaining_time($page, $step, $section)) ?></div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="cmplz-body">
                    <div class="cmplz-section-content">
                        <?php $this->get_content($page, $step, $section); ?>
                    </div>
                    <?php if (COMPLIANZ()->config->has_sections($page, $step)) { ?>
                        <div class="cmplz-section-menu">

                            <?php

                            for ($i = 1; $i <= $this->total_sections($page, $step); $i++) {
                                if (!$this->section_exists($page, $step, $i)) {
                                    continue;
                                }
                                $section_compare = $this->get_next_not_empty_section($page, $step, $i);

                                if ($i < $section_compare) continue;
                                $active = ($i == $section) ? true : false;
                                $icon = ($this->required_fields_completed($page, $step, $i)) ? "check" : "fw";
                                $url = add_query_arg(array('step' => $step, 'section' => $i), $this->page_url);
                                if ($this->post_id()) $url = add_query_arg(array('post_id' => $this->post_id()), $url);

                                if ($active) $icon = "angle-right";
                                ?>
                                <div class="cmplz-menu-item <?php echo ($this->required_fields_completed($page, $step, $i)) ? "cmplz-done" : "cmplz-to-do"; ?><?php if ($active) echo " active"; ?>">
                                    <i class="fa fa-<?php echo $icon ?>"></i>
                                    <a href="<?php echo $url ?>"><?php echo COMPLIANZ()->config->steps[$page][$step]['sections'][$i]['title'] ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php

        }

        /*
         * If a section does not contain any fields to be filled, just drop it from the menu.
         *
         *
         * */

        public function section_exists($page, $step, $section)
        {
            $section_compare = $this->get_next_not_empty_section($page, $step, $section);
            if ($section < $section_compare) {
                return false;
            }
            return true;
        }

        public function enqueue_assets($hook)
        {

            if ((strpos($hook, 'complianz') === FALSE) && strpos($hook, 'cmplz') === FALSE) return;

            wp_register_style('cmplz-wizard', cmplz_url . 'assets/css/wizard.css', false, cmplz_version);
            wp_enqueue_style('cmplz-wizard');

        }


        /*
         *
         * Foreach required field, check if it's been answered
         *
         * if section is false, check all fields of the step.
         *
         *
         * */


        public function required_fields_completed($page, $step, $section)
        {
            //get all required fields for this section, and check if they're filled in
            $fields = COMPLIANZ()->config->fields($page, $step, $section);
//            error_log("check reqired fields completed");
//            error_log(print_r($fields,true));
            //error_log("check $page, $step, $section");
            foreach ($fields as $fieldname => $args) {
                //serror_log("loop $fieldname");
                $default_args = COMPLIANZ()->field->default_args;
                $args = wp_parse_args($args, $default_args);
                if ($args['required']) {
                    //if a condition exists, only check for this field if the condition applies.
                    if (isset($args['condition']) && !COMPLIANZ()->field->condition_applies($args)) {
                        //error_log("condition does not apply");
                        continue;
                    }
                    $value = COMPLIANZ()->field->get_value($fieldname);
                    if (empty($value)) {
                        //error_log("$page is not complete");
                        return false;
                    } else {
                        //error_log("$fieldname is complete");
                    }
                }

            }

            //error_log("$page is complete");
            return true;
        }


//        public function count_fields($page, $step, $section)
//        {
//            //get all required fields for this section, and check if they're filled in
//            $fields = COMPLIANZ()->config->fields($page, $step, $section);
//            foreach ($fields as $fieldname => $args) {
//                $default_args = COMPLIANZ()->field->default_args;
//                $args = wp_parse_args($args, $default_args);
//                if ($args['required']) {
//                    //if a condition exists, only check for this field if the condition applies.
//                    if (isset($args['condition']) && !COMPLIANZ()->field->condition_applies($args)) {
//                        continue;
//                    }
//                    $value = COMPLIANZ()->field->get_value($fieldname);
//
//                    if (empty($value)) {
//                        return false;
//                    }
//                }
//
//            }
//            return true;
//        }


        /*
         * Check if all required fields are filled
         *
         *
         * */

        public function all_required_fields_completed($page)
        {
            for ($step = 1; $step <= $this->total_steps; $step++) {
                if (COMPLIANZ()->config->has_sections($page, $step)) {
                    error_log("has sections $page $step");
                    for ($section = 1; $section <= $this->total_sections($page, $step); $section++) {
                        if (!$this->required_fields_completed($page, $step, $section)) {
                            error_log("not completed $page $step $section");
                            return false;
                        }
                    }
                } else {
                    if (!$this->required_fields_completed($page, $step, false)) {
                        return false;
                    }
                }
            }
            return true;
        }

        public function post_id()
        {
            $post_id = false;
            if (isset($_GET['post_id']) || isset($_POST['post_id'])) {
                $post_id = (isset($_GET['post_id'])) ? intval($_GET['post_id']) : intval($_POST['post_id']);
            }
            return $post_id;
        }

        public function wizard_type()
        {
            $wizard_type = 'wizard';
            if (isset($_POST['wizard_type']) || isset($_POST['wizard_type'])) {
                $wizard_type = isset($_POST['wizard_type']) ? $_POST['wizard_type'] : $_GET['wizard_type'];

                //sanitize
                $types = array('wizard', 'processing', 'dataleak');
                if (!in_array($wizard_type, $types)) $wizard_type = 'wizard';
            } else {
                if (isset($_GET['page'])) {
                    $wizard_type = str_replace('cmplz-', '',$_GET['page']);
                }
            }
            return $wizard_type;
        }



        public function get_intro($page, $step, $section){
            //only show when in action
            if ($this->wizard_completed()) return;

            echo "<p>";
            if (COMPLIANZ()->config->has_sections($page, $step)){
                if (isset(COMPLIANZ()->config->steps[$page][$step]['sections'][$section]['intro'])) {
                    echo COMPLIANZ()->config->steps[$page][$step]['sections'][$section]['intro'];
                }
            } else {
                if (isset(COMPLIANZ()->config->steps[$page][$step]['intro'])) {
                    echo COMPLIANZ()->config->steps[$page][$step]['intro'];
                }
            }
            echo '</p>';
        }


        /*
         * Get content of wizard
         *
         *
         * */


        public function get_content($page, $step, $section = false)
        {

            if (isset($_POST['cmplz-save'])) {
                cmplz_notice_success( __("Changes saved successfully", 'complianz') );
            }
            if ($page != 'wizard') {
                $link = '<a href="' . admin_url('edit.php?post_type=cmplz-' . $page) . '">';
                if ($this->post_id()) {
                    $link_pdf = '<a href="' . admin_url("post.php?post=".$this->post_id()."&action=edit") . '">';
                    echo '<div class="cmplz-notice">' . sprintf(__('You are editing a saved draft of document "%s" (%sview%s). You can view existing documents on the %soverview page%s', 'complianz'), get_the_title($this->post_id()), $link_pdf, '</a>', $link, '</a>') . "</div>";
                } elseif ($this->step() == 1) {
                    delete_option('complianz_options_' . $page);
                    echo '<div class="cmplz-notice">' . sprintf(__("You are about to create a new document. To edit existing documents, view the %soverview page%s", 'complianz'), $link, '</a>') . "</div>";
                    if ($page=='processing'){
                        $about = __('processing agreements', 'complianz');
                        $link_article = '<a href="https://complianz.io/what-are-processing-agreements">';
                    } else{
                        $about = __('dataleak reports', 'complianz');
                        $link_article = '<a href="https://complianz.io/what-are-dataleak-reports">';
                    }

                    echo '<p >' . sprintf(__("To learn what %s are and what you need them for, please read this  %sarticle%s", 'complianz'), $about, $link_article, '</a>') . "</p>";

                }
            }

            ?>
            <?php $this->get_intro($page, $step, $section)?>
            <form action="<?php echo $this->page_url ?>" method="POST">
                <input type="hidden" value="<?php echo $page ?>" name="wizard_type">
                <?php if ($this->post_id()) { ?>
                    <input type="hidden" value="<?php echo $this->post_id() ?>" name="post_id">
                <?php } ?>

                <?php
                if (($page == 'dataleak') || ($page == 'processing') || !$this->wizard_completed()) {
                    COMPLIANZ()->field->get_fields($page, $step, $section);
                } else {
                    _e("The wizard has been completed. To start the wizard again, click 'Make changes'", 'complianz');

                }
                ?>

                <input type="hidden" value="<?php echo $step ?>" name="step">
                <input type="hidden" value="<?php echo $section ?>" name="section">
                <?php wp_nonce_field('complianz_save', 'complianz_nonce'); ?>
                <div class="cmplz-buttons-container">

                    <?php if ($page == 'wizard' && $this->wizard_completed()) { ?>
                        <div class="cmplz-button cmplz-next">
                                <span>
                        <input class="button" type="submit" name="cmplz-start"
                               value="<?php _e("Make changes", 'complianz') ?>">
                                </span>
                        </div>

                    <?php } else { ?>

                        <?php if ($step > 1 || $section > 1) { ?>
                            <div class="cmplz-button cmplz-previous icon">
                            <span>
                            <input class="fa button" type="submit"
                                   name="cmplz-previous"
                                   value="<?php _e("Previous", 'complianz') ?>">
                            </span>
                            </div>
                        <?php } ?>
                        <?php if ($step < $this->total_steps) { ?>
                            <div class="cmplz-button cmplz-next">
                            <span>
                                <input class="fa button" type="submit"
                                       name="cmplz-next"
                                       value="<?php _e("Next", 'complianz') ?>">
                            </span>
                            </div>
                        <?php } ?>

                        <?php
                        $hide_finish_button = false;
                        if (($page == 'dataleak') && !COMPLIANZ()->dataleak->dataleak_has_to_be_reported()) {
                            $hide_finish_button = true;
                        }
                        $label = ($page == 'dataleak' || $page == 'processing') ? __("View document", 'complianz') : __("Finish", 'complianz');
                        ?>
                        <?php if (!$hide_finish_button && ($step == $this->total_steps) && $this->all_required_fields_completed($page)) { ?>
                            <div class="cmplz-button cmplz-next">
                                <span>
                                <input class="button" type="submit" name="cmplz-finish"
                                       value="<?php echo $label ?>">
                                </span>
                            </div>
                        <?php } ?>

                        <?php if (($step > 1 || $page == 'wizard') && ($step < $this->total_steps) && !$hide_finish_button && ($page != "wizard" || !$this->wizard_completed())) { ?>
                            <div class="cmplz-button cmplz-save">
                            <span>
                                <input class="fa button" type="submit"
                                       name="cmplz-save"
                                       value="<?php _e("Save", 'complianz') ?>">
                            </span>
                            </div>
                        <?php } ?>

                    <?php } ?>
                </div>
            </form>
            <?php
        }

        public function wizard_completed()
        {
            $completed = false;
            $var = get_option('cmplz_wizard_completed');
            if ($var == 1) {
                $completed = true;
            }
            return $completed;
        }

        public function reset_wizard_completed()
        {
            update_option('cmplz_wizard_completed', -1);
        }

        public function set_wizard_completed()
        {
            update_option('cmplz_wizard_completed', 1);
        }

        public function step($page = false)
        {
            $step = 1;
            $total_steps = $page ? $this->total_steps($page) : $this->total_steps;

            if (isset($_GET["step"])) {
                $step = intval($_GET['step']);
            }

            if (isset($_POST["step"])) {
                $step = intval($_POST['step']);
            }

            if ($step > $total_steps) {
                $step = $total_steps;
            }

            if ($step <= 1) $step = 1;

            return $step;
        }

        public function section()
        {
            $section = 1;
            if (isset($_GET["section"])) {
                $section = intval($_GET['section']);
            }

            if (isset($_POST["section"])) {
                $section = intval($_POST['section']);
            }

            if ($section > $this->total_sections) {
                $section = $this->total_sections;
            }

            if ($section <= 1) $section = 1;

            return $section;
        }

        public function total_steps($page)
        {

            return count(COMPLIANZ()->config->steps[$page]);

        }

        public function total_sections($page, $step)
        {
            if (!isset(COMPLIANZ()->config->steps[$page][$step]["sections"])) return 0;

            return count(COMPLIANZ()->config->steps[$page][$step]["sections"]);
        }


        public function remaining_time($page, $step, $section = false)
        {
            //get remaining steps including this one
            $time = 0;
            $total_steps = $this->total_steps($page);
            for ($i = $total_steps; $i >= $step; $i--) {
                $sub = 0;

                //if we're on a step with sections, we should add the sections that still need to be done.
                if (($step == $i) && COMPLIANZ()->config->has_sections($page, $step)) {

                    for ($s = $this->total_sections($page, $i); $s >= $section; $s--) {
                        $subsub = 0;
                        $section_fields = COMPLIANZ()->config->fields($page, $step, $s);
                        foreach ($section_fields as $section_fieldname => $section_field) {
                            if (isset($section_field['time'])) {
                                $sub += $section_field['time'];
                                $subsub += $section_field['time'];
                                $time += $section_field['time'];
                            }
                        }
                    }
                } else {
                    $fields = COMPLIANZ()->config->fields($page, $i, false);

                    foreach ($fields as $fieldname => $field) {
                        if (isset($field['time'])) {
                            $sub += $field['time'];
                            $time += $field['time'];
                        }

                    }
                }
            }
            return round($time + 0.45);
        }


        public function all_fields_completed(){
            $total_fields = 0;
            $completed_fields = 0;
            $total_steps = $this->total_steps('wizard');
            for ($i = 1; $i <= $total_steps; $i++) {
                $fields = COMPLIANZ()->config->fields('wizard', $i, false);
                foreach ($fields as $fieldname => $field) {
                    //is field required
                    $required = isset($field['required']) ? $field['required'] : false;
                    if (isset($field['condition']) && !COMPLIANZ()->field->condition_applies($field)) $required = false;
                    if ($required){
                        $value = cmplz_get_value($fieldname);
                        $total_fields++;
                        $empty = empty($value);
                        if (!$empty){
                            $completed_fields++;
                        }
                    }
                }
            }

            return ($completed_fields==$total_fields);
        }

        public function wizard_percentage_complete()  //($page)
        {

            $total_fields = 0;
            $completed_fields = 0;
            $total_steps = $this->total_steps('wizard');
            for ($i = 1; $i <= $total_steps; $i++) {
                $fields = COMPLIANZ()->config->fields('wizard', $i, false);
                foreach ($fields as $fieldname => $field) {
                    //is field required
                    $required = isset($field['required']) ? $field['required'] : false;
                    if ((isset($field['condition']) || isset($field['callback_condition'])) && !COMPLIANZ()->field->condition_applies($field)) $required = false;
                    if ($required){
                        $value = cmplz_get_value($fieldname);
                        $total_fields++;
                        if (!empty($value)){
                            $completed_fields++;
                        }
                    }
                }
            }

            //we account for the warnings with one step
            $warning_count = COMPLIANZ()->admin->get_warnings();
            if (!COMPLIANZ()->document->page_exists('privacy-statement')){
                $warning_count++;
            }

            $warnings = (count($warning_count)!=0) ? true: false;
            $total_fields++;
            if (!$warnings) $completed_fields++;

            $percentage = round(100*($completed_fields/$total_fields) + 0.45);

            return $percentage;

        }
    }
} //class closure
