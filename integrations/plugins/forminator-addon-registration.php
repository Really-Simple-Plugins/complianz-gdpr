<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
add_action( 'forminator_addons_loaded', 'cmplz_load_forminator_addon' );
function cmplz_load_forminator_addon() {

	if ( class_exists( 'Forminator_Integration_Loader' ) ) {
		require_once dirname( __FILE__ ) . '/forminator-addon-class-v2.php';

		Forminator_Integration_Loader::get_instance()
		                             ->register( 'CMPLZ_Forminator_Addon_V2' );
	} elseif ( class_exists( 'Forminator_Addon_Loader' ) ) {
		require_once dirname( __FILE__ ) . '/forminator-addon-class.php';

		Forminator_Addon_Loader::get_instance()
		                       ->register( 'CMPLZ_Forminator_Addon' );
	}
}
