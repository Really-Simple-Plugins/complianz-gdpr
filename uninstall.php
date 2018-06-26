<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
$delete_options = array(
    "complianz_options_wizard",
    'complianz_options_cookie_settings',
    'complianz_options_dataleak',
    'complianz_options_processing',
    'complianz_active_policy_id',
    'complianz_scan_token',
    'cmplz_license_notice_dismissed',
    'cmplz_license_key',
    'cmplz_license_status',
    'cmplz_changed_cookies',
    'cmplz_processed_pages_list',
    'cmplz_license_notice_dismissed',
    'cmplz_processed_pages_list',
    'cmplz_detected_cookies',
    'cmplz_plugins_changed',
    'cmplz_detected_social_media',
    'cmplz_deleted_cookies',
);

delete_all_options($delete_options);


function delete_all_options($options) {
    foreach ($options as $option_name){
        delete_option( $option_name );
        delete_site_option( $option_name );
    }

}

