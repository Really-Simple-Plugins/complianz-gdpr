<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );
/**
 * Add notice to tell a user to choose Analytics
 *
 * @param $args
 */

function cmplz_google_site_kit_show_compile_statistics_notice( $args ) {
	cmplz_notice( sprintf( __( "Because you're using %s, you can choose which plugin should insert the relevant snippet.",
		'complianz-gdpr' ),
			__( "Google Sit Kit", "complianz-gdpr" ) )
								. cmplz_read_more( "https://complianz.io/configuring-google-site-kit/" ),
		'warning' );
}

add_action( 'cmplz_notice_compile_statistics',
	'cmplz_google_site_kit_show_compile_statistics_notice', 10, 1 );
