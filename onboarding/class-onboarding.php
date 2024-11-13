<?php
defined('ABSPATH') or die();
require_once(cmplz_path . 'class-installer.php');

class cmplz_onboarding {
	private static $_this;

	function __construct() {
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.', get_class( $this ) ) );
		}

		self::$_this = $this;
		add_filter( "cmplz_do_action", array($this, 'handle_onboarding_action'), 10, 3);
		add_filter( "admin_init", array($this, 'maybe_redirect_to_settings_page'));
	}

	static function this() {
		return self::$_this;
	}

	/**
	 * @param $data
	 * @param $action
	 * @param $request
	 *
	 * @return array
	 */
	public function handle_onboarding_action($data, $action, $request): array {
		if ( ! cmplz_user_can_manage() ) {
			return [];
		}
		switch( $action ){
			case 'get_recommended_plugins_status':
				$data = $request->get_json_params();
				$plugins = $data['plugins'] ?? [];
				$data = [
					'plugins' => $this->get_recommended_plugins_status($plugins),
					'isUpgrade' => get_option('cmplz_upgraded_to_7', false)
				];
				break;
			case 'install_plugin':
				$data = $request->get_json_params();
				$slug = $data['slug'] ?? [];
				$plugins = $data['plugins'] ?? [];
				require_once(cmplz_path . 'class-installer.php');
				$plugin = new cmplz_installer($slug);
				$plugin->download_plugin();
				$data = [
					'plugins' => $this->get_recommended_plugins_status($plugins),
				];
				break;
			case 'activate_plugin':
				$data = $request->get_json_params();
				$slug = $data['slug'] ?? [];
				$plugins = $data['plugins'] ?? [];
				require_once(cmplz_path . 'class-installer.php');
				$plugin = new cmplz_installer($slug);
				$plugin->activate_plugin();
				$data = [
					'plugins' => $this->get_recommended_plugins_status($plugins),
				];
				break;
			case 'update_email':
				$data = $request->get_json_params();
				$email = sanitize_email($data['email']);
				if  (is_email($email )) {
					cmplz_update_option_no_hooks('notifications_email_address', $email );
					cmplz_update_option_no_hooks('send_notifications_email', 1 );
					if ( $data['sendTestEmail'] ) {
						$mailer = new cmplz_mailer();
						$mailer->send_test_mail();
					}
					if ( $data['includeTips'] ) {
						$this->signup_for_mailinglist( $email );
					}
				}

				$data = [];
				break;
		}
		return $data;
	}

	public function get_recommended_plugins_status($plugins){
		foreach ($plugins as $index => $plugin ){
			$slug = sanitize_title($plugin['slug']);
			$premium = $plugin['premium'] ?? false;
			$premium = $premium ? sanitize_title($premium) : false;
			//check if plugin is downloaded
			$installer = new cmplz_installer($slug);
			if ( !$installer->plugin_is_downloaded() ) {
				$plugins[$index]['status'] = 'not-installed';
			} else if ($installer->plugin_is_activated()) {
				$plugins[$index]['status'] = 'activated';
			} else {
				$plugins[$index]['status'] = 'installed';
			}

			//If not found, check for premium
			//if free is activated, skip this step
			//don't update is the premium status is not-installed. Then we leave it as it is.
			if ( $premium && $plugins[$index]['status'] !== 'activated' ) {
				$installer = new cmplz_installer($premium);
				 if ($installer->plugin_is_activated()) {
					$plugins[$index]['status'] = 'activated';
				} else if ($installer->plugin_is_downloaded()) {
					$plugins[$index]['status'] = 'installed';
				}
			}
		}
		return $plugins;
	}

	/**
	 * Signup for Tips & Tricks from Really Simple Security
	 *
	 * @param string $email
	 *
	 * @return void
	 */
	public function signup_for_mailinglist( string $email): void {
		$license_key = '';
		if ( defined('rsssl_pro') ) {
			$license_key = COMPLIANZ::$license->license_key();
			$license_key = COMPLIANZ::$license->maybe_decode( $license_key );
		}

		$api_params = array(
			'has_premium' => defined('cmplz_premium'),
			'license' => $license_key,
			'email' => sanitize_email($email),
			'domain' => esc_url_raw( site_url() ),
		);
		wp_remote_post( 'https://mailinglist.complianz.io', array( 'timeout' => 15, 'sslverify' => true, 'body' => $api_params ) );
	}

	/**
	 * Redirect to settings page on activation, including a tour
	 * @return void
	 */

	public function maybe_redirect_to_settings_page() {
		if ( get_transient('cmplz_redirect_to_settings_page' ) ) {
			delete_transient('cmplz_redirect_to_settings_page' );
			if ( ! get_option('cmplz_onboarding_dismissed') && ! isset( $_GET['onboarding'] ) ) {
				update_option( 'cmplz_onboarding_dismissed', true, false );
				wp_redirect( add_query_arg( [ 'onboarding' => 1 ], cmplz_admin_url() ) );
				exit;
			}
			if ( !isset($_GET['page']) || $_GET['page'] !== 'complianz' ) {
				wp_redirect( add_query_arg( array( 'page' => 'complianz' ), cmplz_admin_url() ) );
				exit;
			}
		}
	}

}


