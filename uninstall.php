<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$delete_options = array(
    "cmplz_legal_version",
    "cmplz_plugin_new_features",
    "cmplz_wizard_completed_once",
    'complianz_options_dataleak',
    'complianz_options_processing',
    'complianz_options_settings',
    'complianz_options_wizard',
    'complianz_options_cookie_settings',
    'complianz_active_policy_id',
    'cmplz_reported_cookies',
    'complianz_scan_token',
    'cmplz_license_key',
    'cmplz_license_status',
    'cmplz_changed_cookies',
    'cmplz_processed_pages_list',
    'cmplz_detected_cookies',
    'cmplz_plugins_changed',
    'cmplz_plugins_updated',
    'cmplz_detected_social_media',
    'cmplz_deleted_cookies',
    'cmplz_db_version',
    'cmplz_detected_thirdparty_services',
    'cmplz_upgrade_from_free',
    'cmplz_detected_forms',
    'cmplz_enabled_best_performer',
    'cmplz_tracking_ab_started',
    'cmplz_deactivated',
    'cmplz_license_expires',
    'cmplz_license_notice_dismissed',
);

if (!defined('cmplz_premium') && !defined('cmplz_premium_multisite')) delete_all_options($delete_options);

function delete_all_options($options) {
    foreach ($options as $option_name){
        delete_option( $option_name );
        delete_site_option( $option_name );
    }
}

global $wpdb;
$sql = "DROP TABLE IF EXISTS cmplz_statistics";
$wpdb->query($sql);



