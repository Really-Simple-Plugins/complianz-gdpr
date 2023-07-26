<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Install suggested plugins
 */

if ( !class_exists('cmplz_installer') ){
	class cmplz_installer {
		private $slug = '';
		public $action = '';

		public function __construct($slug) {
			if ( !current_user_can('install_plugins')) return;

			$this->slug = $slug;
			if ( !$this->plugin_is_downloaded() && !$this->plugin_is_activated() ) {
				$this->action = 'cmplz_download_plugin';
			}

			if ($this->plugin_is_downloaded() && !$this->plugin_is_activated() ) {
				$this->action = 'cmplz_activate_plugin';
			}

//
		}

		/**
		 * Check if plugin is downloaded
		 * @return bool
		 */

		public function plugin_is_downloaded(){
			return file_exists(trailingslashit(WP_PLUGIN_DIR).$this->get_activation_slug() );
		}
		/**
		 * Check if plugin is activated
		 * @return bool
		 */
		public function plugin_is_activated(){
			return is_plugin_active($this->get_activation_slug() );
		}

		/**
		 * Install plugin
		 * @param string $step
		 *
		 * @return void
		 */
		public function install($step){

			if ( !current_user_can('install_plugins')) return;

			if ( $step === 'download' ) {
				$this->download_plugin();
			}
			if ( $step === 'activate' ) {
				$this->activate_plugin();
			}
		}

		/**
		 * Get slug to activate plugin with
		 * @return string
		 */
		public function get_activation_slug(){
			$slugs = [
				'burst-statistics' => 'burst-statistics/burst.php',
			];
			return $slugs[$this->slug];
		}

		/**
		 * Cancel shepherd tour
		 * @return void
		 */
		public function cancel_tour(){
			$prefixes = [
				'burst-statistics' => 'burst',
			];
			$prefix = $prefixes[$this->slug];
			update_site_option( $prefix.'_tour_started', false );
			update_site_option( $prefix.'_tour_shown_once', true );
		}

		/**
		 * Download the plugin
		 * @return bool
		 */
		public function download_plugin() {
			if ( !current_user_can('install_plugins')) return;

			if ( !get_transient("cmplz_plugin_download_active") ) {
				set_transient("cmplz_plugin_download_active", 5 * MINUTE_IN_SECONDS );
				$info          = $this->get_plugin_info();
				$download_link = esc_url_raw( $info->versions['trunk'] );
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$skin = new WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader($skin);

				$result = $upgrader->install($download_link);

				if (is_wp_error($result)) {
					return false;
				}

				delete_transient("cmplz_plugin_download_active");
			}

			return true;
		}

		/**
		 * Activate the plugin
		 *
		 * @return bool
		 */
		public function activate_plugin(): bool
		{
			if (!current_user_can('install_plugins')) {
				return false;
			}

			$slug = $this->get_activation_slug();
			$plugin_file_path = trailingslashit(WP_PLUGIN_DIR) . $slug;

			// Make sure the plugin file exists before trying to activate it
			if (!file_exists($plugin_file_path)) {
				return false;
			}

			// Use plugin_basename to generate the correct slug, considering the WP_PLUGIN_DIR
			$plugin_slug = plugin_basename($plugin_file_path);

			$networkwide = is_multisite();

			if (!defined('DOING_CRON')) {
				define('DOING_CRON', true);
			}

			$result = activate_plugin($plugin_slug, '', $networkwide);
			if (is_wp_error($result)) {
				return false;
			}

			$this->cancel_tour();
			return true;
		}

		/**
		 * Get plugin info
		 * @return array|WP_Error
		 */
		public function get_plugin_info()
		{
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$plugin_info = get_transient('cmplz_'.$this->slug . '_plugin_info');
			if ( empty($plugin_info) ) {
				$plugin_info = plugins_api('plugin_information', array('slug' => $this->slug));
				if (!is_wp_error($plugin_info)) {
					set_transient('cmplz_'.$this->slug . '_plugin_info', $plugin_info, WEEK_IN_SECONDS);
				}
			}
			return $plugin_info;
		}
	}

}

