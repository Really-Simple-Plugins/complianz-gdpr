<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Add notice for Burst Statistics
 *
 */

function cmplz_burst_statistics_integration_show_compile_statistics_notice() {
	cmplz_sidebar_notice( __("Burst Statistics will be configured automatically. If you want to whitelist Burst Statistics, please use our Script Center.", 'complianz-gdpr' ) );
}
add_action( 'cmplz_notice_compile_statistics', 'cmplz_burst_statistics_integration_show_compile_statistics_notice' );
