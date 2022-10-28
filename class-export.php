<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_export_settings" ) ) {
	class cmplz_export_settings {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;

			add_action( 'admin_init', array( $this, 'process_export_action' ),
				10, 1 );
		}

		static function this() {
			return self::$_this;
		}

		public function process_export_action() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( isset( $_GET['action'] )
			     && $_GET['action'] === 'cmplz_export_settings'
			) {
				$settings = get_option( 'complianz_options_settings' );
				$wizard   = get_option( 'complianz_options_wizard' );
				//disable A/B testing
				$settings['a_b_testing'] = false;
				$settings['a_b_testing_buttons'] = false;
				unset( $wizard['used_cookies'] );

				if (isset($_GET['export_type']) && $_GET['export_type']==='cookiebanner') {
					$banner_id = intval($_GET['id']);
					$args = array(
						'banners'  => cmplz_get_cookiebanners(array('ID'=>$banner_id)),
					);
				} else {
					$args = array(
						'settings' => $settings,
						'wizard'   => $wizard,
						'banners'  => cmplz_get_cookiebanners(),
						'errors'  => cmplz_get_console_errors(),
					);
				}
				$json = json_encode($args);
				$json = $json . '#--COMPLIANZ--#' . strlen( utf8_decode( $json ) );

				header( 'Content-disposition: attachment; filename=complianz-export.json' );
				header( 'Content-type: application/json' );
				echo $json;
				die();
			}
		}
	}
}
