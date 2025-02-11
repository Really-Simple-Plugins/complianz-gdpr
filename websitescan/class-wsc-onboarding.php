<?php

defined('ABSPATH') or die();


if (!class_exists("cmplz_wsc_onboarding")) {

	class cmplz_wsc_onboarding
	{

		public function init_hooks()
		{
			add_action("cmplz_do_action", array($this, 'handle_onboarding_action'), 10, 3);
			add_action("admin_init", array($this, 'maybe_show_onboarding_modal'), 10);
			add_action("cmplz_every_week_hook", array($this, 'check_wsc_consent'), 20);
			// Check if the option already exists, if not, set it up
			if (!get_option('cmplz_wsc_onboarding_status')) {
				$this->set_onboarding_status_option();
			}
			add_action('cmplz_store_wsc_onboarding_consent', array($this, 'cmplz_store_wsc_onboarding_consent_handler'), 10, 2);
		}


		private function set_onboarding_status_option(): void
		{
			$cmplz_wsc_onboarding_status = [
				'terms' => false,
				'newsletter' => false,
				'plugins' => false,
			];
			update_option('cmplz_wsc_onboarding_status', $cmplz_wsc_onboarding_status, false);
		}


		/**
		 * Handles various onboarding actions.
		 *
		 * This method is responsible for handling different actions related to onboarding.
		 * It checks the user's capability to manage, and based on the action provided,
		 * performs the necessary operations such as sending authentication emails,
		 * resetting website scan, enabling or disabling the website scan, checking token status,
		 * and signing up for the newsletter.
		 *
		 * @param array $data The data associated with the action.
		 * @param string $action The action to be performed.
		 * @param WP_REST_Request $request The REST request object.
		 * @return array The updated data after performing the action.
		 */
		public function handle_onboarding_action(array $data, string $action, WP_REST_Request $request): array
		{
			if (!cmplz_user_can_manage()) {
				return [];
			}
			switch ($action) {
				// used on onboarding to sign up
				case 'signup_wsc':
					$posted_data = $request->get_json_params();
					$email = sanitize_email($posted_data['email']);
					if (is_email($email)) {
						cmplz_wsc_auth::send_auth_email($email);
						// Schedule storing onboarding consent asynchronously
						self::schedule_store_onboarding_consent('cmplz_store_wsc_onboarding_consent', 1000, ['wsc_consent', $posted_data]);
					}
					break;
				case 'get_wsc_terms':
					$data = $this->get_onboarding_doc('wsc_terms');
					break;
				case 'dismiss_wsc_onboarding':
					delete_option('cmplz_wsc_onboarding_start');
					update_option('cmplz_wsc_onboarding_dismissed', true, false);
					break;
				case 'get_newsletter_terms':
					$data = $this->get_onboarding_doc('newsletter');
					break;
				case 'get_recommended_plugins_status':
					$posted_data = $request->get_json_params();
					$plugins = $posted_data['plugins'] ?? [];
					$data = [
						'plugins' => $this->get_recommended_plugins_status($plugins),
						'isUpgrade' => get_option('cmplz_upgraded_to_7', false)
					];
					break;
				case 'install_plugin':
				case 'activate_plugin':
					$posted_data = $request->get_json_params();
					$data = [
						'plugins' => $this->process_plugin_action($action, $posted_data),
					];
					break;
			}
			return $data;
		}


		/**
		 * Check if the user should get the onboarding modal, for the signup process.
		 *
		 * @return void
		 */
		public function maybe_show_onboarding_modal(): void
		{
			if (!cmplz_user_can_manage()) {
				return;
			}

			if (!isset($_GET['websitescan']) && $this->should_onboard()) {
				wp_redirect(add_query_arg(['websitescan' => ''], cmplz_admin_url()));
				exit;
			}
		}


		/**
		 * Determines whether the user should be onboarded.
		 *
		 * This function checks various conditions to determine if the user should be onboarded.
		 * If the user can't manage, or if the 'cmplz_force_signup' parameter is set in the URL,
		 * the function returns true. If the onboarding process is already complete or if the
		 * WSC API is not open, the function returns false. Otherwise, it checks if the onboarding
		 * start date is set and if it is earlier than the current time, and returns the result.
		 *
		 * @return bool Returns true if the user should be onboarded, false otherwise.
		 */
		private function should_onboard(): bool
		{
			if (!cmplz_user_can_manage()) {
				return false;
			}

			// Force the signup, even if the user is already onboarded
			if (isset($_GET['cmplz_force_signup'])) {
				$cb_wsc_signup_status = cmplz_wsc_auth::wsc_api_open('signup');
				if (!$cb_wsc_signup_status) {
					cmplz_wsc_logger::log_errors('wsc_api_open', 'COMPLIANZ: WSC API is not open');
					return false;
				}
				return true;
			}

			// if already onboarded
			if (cmplz_wsc_auth::wsc_is_authenticated()) {
				return false;
			}

			// If the WSC has been either reset or dismissed during the onboarding.
			if (get_option('cmplz_wsc_reset_complete', false) || get_option('cmplz_wsc_onboarding_dismissed', false) ) {
				return false;
			}

			$onboarding_date = get_option('cmplz_wsc_onboarding_start');

			if (!$onboarding_date) {
				$this->set_onboarding_date();
				return false;
			}

			if ($onboarding_date < time()) {
				$cb_wsc_signup_status = cmplz_wsc_auth::wsc_api_open('signup');
				if (!$cb_wsc_signup_status) {
					cmplz_wsc_logger::log_errors('wsc_api_open', 'COMPLIANZ: the user can onboard but WSC API is not open');
					return false;
				}
				return true;
			}
			return false;
		}

		/**
		 * Sets the onboarding date for the WSC class.
		 *
		 * This method sets the onboarding date for the WSC class, which is responsible for managing the onboarding process.
		 * The onboarding date is calculated based on the release date of the update and is spread over a period of 6 months.
		 * If the user does not have the necessary credentials set up, or if they do not have the permission to manage the onboarding process,
		 * the method will return early and no onboarding date will be set.
		 *
		 * @return void
		 */
		public function set_onboarding_date(): void
		{
			if (!cmplz_user_can_manage()) {
				return;
			}

			//this should be release date of this update. We calculate form this point, so we have a clear cutoff point
			$update_unix_time = strtotime(cmplz_wsc::WSC_RELEASE_DATE);
			$onboarding_period = cmplz_wsc::WSC_ONBOARDING_PERIOD; //months to spread the onboarding over

			$now = time();
			$day = random_int(0, $onboarding_period * 30);
			$trigger_notice_date = $update_unix_time + $day * DAY_IN_SECONDS;
			//if the trigger date is already passed, we randomize over one month, starting from now
			if ($trigger_notice_date < $now) {
				$day = random_int(0, 30);
				$trigger_notice_date = $now + $day * DAY_IN_SECONDS;
			}
			update_option('cmplz_wsc_onboarding_start', $trigger_notice_date, false);
		}

		/**
		 * Retrieves the onboarding docs (terms and conditions or newsletter policy) from the cookiedatabase endpoint.
		 *
		 * @param string $type The type of document to retrieve (wsc_terms or newsletter_terms).
		 * @return array An array containing the terms and conditions.
		 */
		private function get_onboarding_doc(string $type): array
		{
			$current_user_locale = get_user_locale();
			$param = str_replace('_', '-', $current_user_locale);
			$endpoint = $type === 'wsc_terms' ? cmplz_wsc_auth::WSC_TERMS_ENDPOINT : cmplz_wsc_auth::NEWSLETTER_TERMS_ENDPOINT;
			$endpoint = base64_decode($endpoint);

			$request = wp_remote_get($endpoint . '/' . $param, array(
				'timeout' => 15,
				'sslverify' => true,
				'headers' => [
					'Accept' => 'application/json',
					'Content-Type' => 'application/json; charset=utf-8',
				],
			));

			// Check for errors
			if (is_wp_error($request)) {
				// If there's an error, get the error message
				$error_message = $request->get_error_message();
				return [
					'doc' => false,
					'error' => $error_message
				];
			}
			// Check for valid response code
			$response_code = wp_remote_retrieve_response_code($request);

			if ($response_code !== 200) {
				return [
					'doc' => false,
					'error' => 'COMPLIANZ: error retrieving terms and conditions'
				];
			}

			// Get the body of the response
			$body = wp_remote_retrieve_body($request);
			$decoded_body = json_decode($body);

			if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded_body->data)) {
				return [
					'doc' => false,
					'error' => 'COMPLIANZ: error processing the response'
				];
			}

			$output = json_decode($body)->data;

			return [
				'doc' => $output
			];
		}


		/**
		 * Processes the plugin actions.
		 *
		 * This method processes the plugin action, such as installing or activating a plugin.
		 * It downloads the plugin if the action is to install the plugin, or activates the plugin if the action is to activate the plugin.
		 *
		 * @param string $action The action to be performed.
		 * @param array $posted_data The data associated with the action.
		 * @return array The updated list of recommended plugins with their status.
		 */
		private function process_plugin_action(string $action, array $posted_data): array
		{
			require_once(cmplz_path . 'class-installer.php');

			$slug = $posted_data['slug'] ?? [];
			$plugins = $posted_data['plugins'] ?? [];

			$plugin = new cmplz_installer($slug);

			if ($action === 'install_plugin') {
				$plugin->download_plugin();
			} elseif ($action === 'activate_plugin') {
				$plugin->activate_plugin();
			}

			return $this->get_recommended_plugins_status($plugins);
		}


		/**
		 * Retrieves the status of the recommended plugins.
		 *
		 * This method retrieves the status of the recommended plugins, such as Complianz and its add-ons.
		 * It checks if the plugin is downloaded, activated, or installed, and returns the status.
		 *
		 * @param array $plugins The list of recommended plugins.
		 * @return array The updated list of recommended plugins with their status.
		 */
		public function get_recommended_plugins_status(array $plugins): array
		{
			require_once(cmplz_path . 'class-installer.php');

			$plugins_left = 0;

			foreach ($plugins as $index => $plugin) {
				$slug = sanitize_title($plugin['slug']);
				$premium = $plugin['premium'] ?? false;
				$premium = $premium ? sanitize_title($premium) : false;
				//check if plugin is downloaded
				$installer = new cmplz_installer($slug);
				if (!$installer->plugin_is_downloaded()) {
					// check for plugins to download/install
					$plugins[$index]['status'] = 'not-installed';
					$plugins_left++;
				} else if ($installer->plugin_is_activated()) {
					$plugins[$index]['status'] = 'activated';
				} else {
					$plugins[$index]['status'] = 'installed';
				}

				//If not found, check for premium
				//if free is activated, skip this step
				//don't update is the premium status is not-installed. Then we leave it as it is.
				if ($premium && $plugins[$index]['status'] !== 'activated') {
					$installer = new cmplz_installer($premium);
					if ($installer->plugin_is_activated()) {
						$plugins[$index]['status'] = 'activated';
					} else if ($installer->plugin_is_downloaded()) {
						$plugins[$index]['status'] = 'installed';
					}
				}
			}

			if (!$plugins_left) {
				$this->update_onboarding_status('plugins', true);
			}

			return $plugins;
		}


		public static function update_onboarding_status($step, $value)
		{
			$cmplz_wsc_onboarding_status = get_option('cmplz_wsc_onboarding_status', []);
			$cmplz_wsc_onboarding_status[$step] = $value;
			update_option('cmplz_wsc_onboarding_status', $cmplz_wsc_onboarding_status, false);

			// check the cmplz_wsc_omboarding_status array if 'terms', 'newsletter' and 'plugins' are true, set the onboarding complete flag
			if ($cmplz_wsc_onboarding_status['terms'] && $cmplz_wsc_onboarding_status['newsletter'] && $cmplz_wsc_onboarding_status['plugins']) {
				update_option('cmplz_wsc_onboarding_complete', true, false);
			}
		}


		/**
		 * Schedule a store onboarding consent event if not already scheduled.
		 *
		 * @param string $hook The action hook name.
		 * @param int $delay The delay in seconds for scheduling the event.
		 * @param array $posted_data The arguments to pass to the event.
		 * @return void
		 */
		public static function schedule_store_onboarding_consent(string $hook, int $delay, array $posted_data): void
		{
			if (wp_next_scheduled($hook, $posted_data)) {
				cmplz_wsc_logger::log_errors($hook, "COMPLIANZ: event '$hook' already scheduled");
				return;
			}

			$event = wp_schedule_single_event(time() + $delay, $hook, $posted_data);

			if (is_wp_error($event)) {
				cmplz_wsc_logger::log_errors($hook, "COMPLIANZ: error scheduling event '$hook': " . $event->get_error_message());
			}
		}


		/**
		 * Handles the wsc onboarding consent.
		 *
		 * This static method is responsible for storing the consent given by the user
		 * during the onboarding process for wsc.
		 *
		 * @param string $type The type of consent being stored.
		 * @param array $posted_data The data associated with the consent.
		 * @return void
		 */
		public static function cmplz_store_wsc_onboarding_consent_handler(string $type, array $posted_data): void
		{
			cmplz_wsc_auth::store_onboarding_consent($type, $posted_data);
		}

		/**
		 * Check and handle WSC consent.
		 *
		 * This static method checks if the user has executed the onboarding/authentication process.
		 * If the onboarding is complete but the consent is missing, it schedules an event to send the consent again.
		 *
		 * @return void
		 */
		public function check_wsc_consent(): void
		{
			$hook = 'cmplz_store_wsc_onboarding_consent';

			if ($this->cmplz_retrieve_scheduled_event_by_hook($hook)) { // If the wsc consent is already scheduled, exit
				return;
			}

			$signup_date = get_option('cmplz_wsc_signup_date');
			if (!$signup_date) { // exit if the onboarding/authentication is not complete
				return;
			}

			$consent = get_option('cmplz_consent_wsc_consent');
			if ($consent) { // exit if the consent already exists
				return;
			}

			$email_address = cmplz_get_option(cmplz_wsc::WSC_EMAIL_OPTION_KEY);
			if (!is_email($email_address)) { // exit if the email is not set
				return;
			}

			$timestamp = $signup_date * 1000; // convert seconds to milliseconds to match the javascript timestamp of the react app
			$url = add_query_arg('retry', 'true', site_url()); // pass the site_url adding retry=true to identify this is a missed consent

			$posted_data = [
				'email' => $email_address,
				'timestamp' => $timestamp,
				'url' => esc_url($url),
			];

			// schedule a single event to send the consent after 1000 seconds
			self::schedule_store_onboarding_consent($hook, 1000, ['wsc_consent', $posted_data]);
			cmplz_wsc_logger::log_errors('check_wsc_consent', 'COMPLIANZ: missed wsc_consent scheduled');
		}

		/**
		 * Retrieve the already scheduled event.
		 *
		 * This method retrieves the already scheduled event from the cron array.
		 *
		 * @param string $hook The action hook name.
		 * @return bool Returns true if the event is already scheduled, false otherwise.
		 */
		public function cmplz_retrieve_scheduled_event_by_hook($hook): bool {
			$cron_array = _get_cron_array();

			foreach ($cron_array as $timestamp => $events) {
				foreach ($events as $key => $event) {
					if ($key === $hook) {
						return true;
					}
				}
			}
			return false;
		}
	}
}
