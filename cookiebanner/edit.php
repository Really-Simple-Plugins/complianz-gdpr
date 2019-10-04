<div class="wrap">

    <form id='cookie-settings' action="" method="post">
        <?php wp_nonce_field('complianz_save_cookiebanner', 'cmplz_nonce'); ?>

        <?php if (!$id) {?>
            <input type="hidden" value="1" name="cmplz_add_new">
        <?php } ?>
        <?php //some fields for the cookies categories ?>
        <input type="hidden" name="cmplz_cookie_warning_required_stats" value="<?php echo (COMPLIANZ()->cookie->cookie_warning_required_stats())?>">
        <?php
        /**
        * If Tag manager fires categories, enable use categories by default
        */

        if (cmplz_get_value('fire_scripts_in_tagmanager')==='yes') {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                //$('input:checkbox[name=cmplz_use_categories]').prop("checked", true);
            });
        </script>
        <?php
        }
        $active_tab = isset($_POST['cmplz_active_tab']) ? sanitize_title($_POST['cmplz_active_tab']) : 'general';
        $consent_types = cmplz_get_used_consenttypes();
        $regions = cmplz_get_regions();
        if (isset($_POST["cmplz_active_tab"]) && $_POST["cmplz_active_tab"]!=='general') {
            $single_consenttype = sanitize_title($_POST["cmplz_active_tab"]);
        } else {

            if (cmplz_multiple_regions()) {
                $single_consenttype = COMPLIANZ()->company->get_default_consenttype();
            } else {
                $single_region = $regions;
                reset($single_region);
                $single_region = key($single_region);
                $single_consenttype = cmplz_get_consenttype_for_region($single_region);
            }
        }?>
        <input type="hidden" name="cmplz_active_tab" value="<?php echo $active_tab?>">
        <script>
            ccConsentType ='<?php echo $single_consenttype?>';
        </script>

        <div class="cmplz-tab">
            <button class="cmplz-tablinks <?php if ($active_tab==='general') echo "active"?>" type="button" data-tab="general"><?php _e("General", 'complianz-gdpr')?></button>
            <?php foreach ($consent_types as $consent_type){
                $label = get_regions_for_consent_type($consent_type);
                ?>
                <button class="cmplz-tablinks region-link <?php if ($active_tab===$consent_type) echo "active"?>" type="button" data-tab="<?php echo $consent_type?>"><?php echo cmplz_consenttype_nicename($consent_type)?></button>
            <?php }?>
        </div>

        <!-- Tab content -->
        <div id="general" class="cmplz-tabcontent <?php if ($active_tab==='general') echo "active"?>">
            <h3><?php _e("General", 'complianz-gdpr')?></h3>
            <p>
            <table class="form-table">
                <?php
                COMPLIANZ()->field->get_fields('CMPLZ_COOKIEBANNER', 'general');?>
            </table>
            </p>
        </div>

        <?php foreach ($consent_types as $consent_type){

            ?>
            <div id="<?php echo $consent_type?>" class="cmplz-tabcontent region <?php if ($active_tab===$consent_type) echo "active"?>">
                <h3><?php echo get_regions_for_consent_type($consent_type);?></h3>
                <p>
                <table class="form-table">
                    <?php
                    COMPLIANZ()->field->get_fields('CMPLZ_COOKIEBANNER', $consent_type);?>
                </table>
                </p>
            </div>
        <?php }?>

       <div class="cmplz-cookiebanner-save-button">
           <button class="button button-primary" type="submit"><?php _e('Save', 'complianz-gdpr')?></button>
       </div>

    </form>
</div>