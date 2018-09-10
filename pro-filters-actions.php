<?php
defined('ABSPATH') or die("you do not have acces to this page!");
add_action('admin_init', 'cmplz_change_free_actions');
function cmplz_change_free_actions()
{
    remove_action("cmplz_dashboard_third_block", array(COMPLIANZ()->admin, 'dashboard_third_block'));
    add_action("cmplz_dashboard_third_block", 'cmplz_pro_dashboard_third_block');
    remove_action("cmplz_dashboard_second_block", array(COMPLIANZ()->admin, 'dashboard_second_block'));
    add_action("cmplz_dashboard_second_block", 'cmplz_pro_dashboard_second_block');


    remove_action('cmplz_dashboard_elements', array(COMPLIANZ()->admin, 'dashboard_elements'));
    add_action("cmplz_dashboard_elements", 'cmplz_add_dashboard_elements');
}

add_filter('cmplz_warning_count', 'cmplz_warning_count_pro');
function cmplz_warning_count_pro($count){
    //consent boxes
    if (COMPLIANZ()->document->page_required('privacy-statement') && cmplz_forms_used_on_sites() && cmplz_consent_box_required_on_form() && !cmplz_consent_box_implemented_on_forms()){
        $count++;
    }
    return $count;
}


/*
 * Premium should respect Do Not Track settings in browsers
 *
 *
 * */
add_filter('cmplz_dnt_enabled', 'cmplz_dnt_enabled');
function cmplz_dnt_enabled()
{
    return (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1);
}


function cmplz_add_dashboard_elements(){
    if (!COMPLIANZ()->document->page_exists('privacy-statement')) {
        COMPLIANZ()->admin->get_dashboard_element(__('You do not have a privacy policy yet.', 'complianz'), 'error');
    } else {
        COMPLIANZ()->admin->get_dashboard_element(__('Great, you have a privacy policy!', 'complianz'), 'success');
    }

}


/*
 * For premium, check if the license is valid before showing the wizard.
 *
 *
 * */

add_filter('cmplz_show_wizard_page', 'cmplz_show_wizard_page');
function cmplz_show_wizard_page($show){
    if (!COMPLIANZ()->license->license_is_valid()) {
        $show = false;
    }
    return $show;
}


/*
 * Add some warnings which are only needed in the premium plugin
 *
 *
 * */
add_filter('cmplz_warnings', 'cmplz_pro_warnings');
function cmplz_pro_warnings($warnings){
    if (COMPLIANZ()->document->page_required('privacy-statement') && cmplz_forms_used_on_sites() && cmplz_consent_box_required_on_form() && !cmplz_consent_box_implemented_on_forms()){
        $warnings[] = 'needs-consent-boxes';
    }

    if (WP_Privacy_Policy_Content::text_change_check()) {
        $warnings[] = 'suggested-policy-text-changed';
    }

    return $warnings;
}


/*
 * Add the menu pages for the premium plugin
 *
 *
 * */


add_action('cmplz_admin_menu', 'cmplz_admin_menu', 10, 1);
function cmplz_admin_menu()
{
    add_submenu_page(
        'edit.php?post_type=cmplz-processing',
        //'complianz',
        __('Add new', 'complianz'),
        __('Add new', 'complianz'),
        'manage_options',
        "cmplz-processing",
        array(COMPLIANZ()->processing, 'processing_agreement_page')
    );

    add_submenu_page(
        'edit.php?post_type=cmplz-dataleak',
        // 'complianz',
        __('Add new', 'complianz'),
        __('Add new', 'complianz'),
        'manage_options',
        "cmplz-dataleak",
        array(COMPLIANZ()->dataleak, 'dataleak_page')
    );

}

//Pro dashboard second block
function cmplz_pro_dashboard_second_block()
{
    ?>

    <div class="cmplz-support-top cmplz-dashboard-text">
        <div class="cmplz-dashboard-support-title"> <?php echo __('Tools', 'complianz'); ?> </div>
    </div>
    <?php
    cmplz_notice(COMPLIANZ()->admin->error_message);
    cmplz_notice_success(COMPLIANZ()->admin->success_message);
    COMPLIANZ()->admin->error_message = "";
    COMPLIANZ()->admin->success_message = "";
    ?>
    <div class="cmplz-dashboard-support-content cmplz-dashboard-text">
        <ul>
            <?php do_action('cmplz_tools') ?>
            <li><i class="fas fa-plus"></i><a
                    href="<?php echo admin_url('edit.php?post_type=cmplz-dataleak&page=cmplz-dataleak') ?>"><?php _e("Create dataleak report", "complianz"); ?></a>
            </li>
            <li><i class="fas fa-plus"></i><a
                    href="<?php echo admin_url('edit.php?post_type=cmplz-processing&page=cmplz-processing') ?>"><?php _e("Create processing agreement", "complianz"); ?></a>
            </li>
            <li><i class="fas fa-plus"></i><a href="#"
                                              id="cmplz-support-link"><?php _e("Need help? Quickly submit your support ticket", "complianz"); ?></a>
            </li>
        </ul>
        <form method="POST" id="cmplz-support-form" action="" class="hidden">
            <?php wp_nonce_field('cmplz_support', 'cmplz_nonce') ?>
            <input type="text" name="cmplz_support_subject"
                   required placeholder="<?php _e("Summarize your issue in a few words", "complianz") ?>">
            <input type="email" name="cmplz_support_email" required
                   placeholder="<?php _e('Your email address', 'complianz') ?>"
                   value="<?php echo get_bloginfo('admin_email') ?>">
            <textarea placeholder="<?php _e("Describe your issue", "complianz") ?>" name="cmplz_support_request"
                      required></textarea>
            <input type="submit"
                   value="<?php _e('Submit ticket', 'complianz') ?>">
        </form>
    </div>

    <?php
}

function cmplz_pro_dashboard_third_block()
{
    ?>
    <div class="cmplz-documents-top cmplz-dashboard-text"
         style="background-color: #F2F2F2; color: black; font-weight: 900;">
        <div class="cmplz-documents-title"> <?php echo __('GDPR Documents', 'complianz'); ?> </div>
    </div>
    <table class="cmplz-dashboard-documents-table cmplz-dashboard-text">
        <?php
        foreach (COMPLIANZ()->config->pages as $type => $page) {
            if (COMPLIANZ()->document->page_exists($type)) {
                $link = '<a href="' . get_permalink(COMPLIANZ()->document->get_shortcode_page_id($type)) . '">' . $page['title'] . '</a>';
                COMPLIANZ()->admin->get_dashboard_element(sprintf(__('A %s document has been generated', 'complianz'), $link), 'success');
            }
        }
        ?>
    </table>
    <?php
}

/*
 * Override logo text
 * */

add_filter('cmplz_logo_extension', 'cmplz_logo_extension');
function cmplz_logo_extension($str)
{
    $str = __("Premium", 'complianz');
    return $str;
}


/*
 * Some more details to be added
 *
 *
 * */

add_action('cmplz_warnings', 'cmplz_pro_warnings');
function cmplz_pro_warnings()
{
    if (!COMPLIANZ()->document->page_required('privacy-statement')) {
        COMPLIANZ()->admin->get_dashboard_element(__("You haven't enabled the privacy statement, so we can't determine if you need consent checkboxes", 'complianz'), 'warning');
    } else {
        if (cmplz_forms_used_on_sites()) {
            if (cmplz_consent_box_required_on_form()) {
                return; //handled by warnings array.
            } else {
                COMPLIANZ()->admin->get_dashboard_element(__('Your contact forms do not require a consent checkbox', 'complianz'), 'success');
            }
        } else {
            COMPLIANZ()->admin->get_dashboard_element(__('You have indicated no contact forms are used on the website', 'complianz'), 'success');
        }
    }
}


add_action('cmplz_register_translation', 'cmplz_register_translation', 10, 2);
function cmplz_register_translation($fieldname, $string)
{

    //polylang
    if (function_exists("pll_register_string")) {
        pll_register_string($fieldname, $string, 'complianz');
    }

    //wpml
    if (function_exists('icl_register_string')) {
        icl_register_string('complianz', $fieldname, $string);
    }
    do_action('wpml_register_single_string', 'complianz', $fieldname, $string);
}