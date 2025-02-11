<?php
defined('ABSPATH') or die();


if (!class_exists("cmplz_wsc_scanner")) {

	class cmplz_wsc_scanner
	{
		private static $_this;

		const WSC_SCANNER_ENDPOINT = 'https://scan.complianz.io';
		const WSC_SCANNER_DEPTH = 1;
		const WSC_SCANNER_VISIT = 1;
		const WSC_SCANNER_SOURCE = 'complianz-scan';
		const WSC_SCANNER_WEBHOOK_PATH = 'complianz/v1/wsc-scan';

		/**
		 * Class constructor for the WSC scanner class.
		 *
		 * Initializes the WSC scanner class and sets it as a singleton class.
		 */
		function __construct()
		{
			if (isset(self::$_this)) {
				wp_die(sprintf('%s is a singleton class and you cannot create a second instance.',
					get_class($this)));
			}
			self::$_this = $this;
			$this->init_hooks();
		}


		/**
		 * Retrieve the instance of the class.
		 *
		 * @return object The instance of the class.
		 */
		static function this(): object
		{
			return self::$_this;
		}


		/**
		 * Initialize the hooks for the WSC scanner class.
		 *
		 * This function initializes the hooks for the WSC scanner class by adding an action
		 * to the `cmplz_remote_cookie_scan` hook.
		 *
		 * @return void
		 */
		private function init_hooks(): void
		{
			add_action('cmplz_remote_cookie_scan', array($this, 'wsc_scan_process'));
			add_action('admin_init', array($this, 'wsc_scan_init'));
		}


		/**
		 * Check if the WSC scan is enabled.
		 *
		 * This function verifies several conditions to determine if the WSC scan is enabled:
		 * - If the site URL is 'localhost', the scan is disabled.
		 * - If the server address is 'localhost', the scan is disabled.
		 * - If the WSC scan circuit breaker is open, the scan is disabled.
		 * - If the hybrid scan is disabled, the scan is disabled.
		 * - If there is no token, the scan is disabled.
		 * If all conditions are met, the scan is enabled.
		 *
		 * @return bool True if the WSC scan is enabled, false otherwise.
		 */
		public function wsc_scan_enabled(): bool
		{
			// if localhost, return false
			$site_url = site_url();
			$host = parse_url($site_url, PHP_URL_HOST);

			if ($host === 'localhost') {
				return false;
			}

			// if server addr is localhost, return false
			if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
				return false;
			}

			// if the wsc scan is enabled by the user through the APIs settings
			if (get_option('cmplz_wsc_status') !== 'enabled') {
				return false;
			}

			// circuit breaker
			if (!$this->wsc_check_cb()) {
				return false;
			}

			// if no token, return false
			if (!cmplz_wsc_auth::get_token()) {
				return false;
			}

			return true;
		}


		/**
		 * Check if the WSC scan circuit breaker is open.
		 *
		 * This function checks the status of the WSC scan circuit breaker by retrieving the value from the transient cache.
		 * If the value is empty, it retrieves the status from the WSC API and stores it in the transient cache for 5 minutes.
		 *
		 * @return bool True if the WSC scan circuit breaker is open, false otherwise.
		 */
		public function wsc_check_cb(): bool
		{
			$cb = cmplz_get_transient('wsc_scanner_cb_enabled'); // check the status of the cb
			if (empty($cb)) {
				$cb = cmplz_wsc_auth::wsc_api_open('scanner');
				cmplz_set_transient('wsc_scanner_cb_enabled', $cb, 300); // store the status for 5 minutes
			}
			return $cb;
		}


		/**
		 * Start the WSC scan.
		 *
		 * This function initiates the WSC scan process by sending a request to the WSC API.
		 * It retrieves the scan URL, depth, visit, source, and token from the options and sends them in the request body.
		 * If the request is successful, it stores the scan ID, created at, and status in the wp options.
		 *
		 * @return void
		 */
		private function wsc_scan_start(): void
		{
			// retrieve the token
			$token = cmplz_wsc_auth::get_token(true); // get a new token

			if (!$token) {
				cmplz_wsc_logger::log_errors('wsc_scan_start', 'COMPLIANZ: no token');
				return;
			}

			$url = esc_url_raw($this->wsc_scan_get_url());

			$depth = self::WSC_SCANNER_DEPTH;
			$visit = self::WSC_SCANNER_VISIT;
			$source = self::WSC_SCANNER_SOURCE;

			$body = array(
				'url' => $url,
				'visit' => $visit, // max n of links to crawl
				'depth' => $depth, // max depth of the pages to crawl
				'acceptBanner' => 'true', // accept the banner if present
				'getTrackers' => 'true', // enable trackers detect as cookies, webstorage, etc
				'source' => $source, // complianz
				"detectTechnologies" => 'false',
				"detectTcf" => 'false',
				"detectLanguages" => 'false',
				"detectGoogleConsentMode" => 'false',
			);

			// use the webhook only with ssl
			$webhook_endpoint = esc_url_raw(get_rest_url(null, self::WSC_SCANNER_WEBHOOK_PATH));
			$parsed_webhook_endpoint = parse_url($webhook_endpoint);
			if (isset($parsed_webhook_endpoint['scheme']) && $parsed_webhook_endpoint['scheme'] === 'https') {
				$body['webhook'] = $webhook_endpoint;
			}

			$request = wp_remote_post(
				self::WSC_SCANNER_ENDPOINT . '/api/v1/scan',
				array(
					'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'oauth-' . $token
					),
					'timeout' => 15,
					'sslverify' => true,
					'body' => json_encode($body)
				)
			);

			if (is_wp_error($request)) {
				cmplz_wsc_logger::log_errors('wsc_scan_start', 'COMPLIANZ: scan request failed, error: ' . $request->get_error_message());
				return;
			}

			$response = json_decode(wp_remote_retrieve_body($request));

			if (!isset($response->id)) {
				cmplz_wsc_logger::log_errors('wsc_scan_start', 'COMPLIANZ: no id in response');
				return;
			}

			// use these options for the webhooks
			update_option('cmplz_wsc_scan_id', $response->id, false);
			update_option('cmplz_wsc_scan_createdAt', $response->createdAt, false);
			update_option('cmplz_wsc_scan_status', 'progress', false);
		}


		/**
		 * Process the WSC scan.
		 *
		 * Initiates the WSC scan process by checking if the scan is enabled.
		 * If enabled, it retrieves the scan status and processes the scan accordingly.
		 * The process includes starting the scan, checking the scan status, and retrieving cookies.
		 * Updates the scan status and progress based on the scan results.
		 * Repeats the process until the scan is completed or the maximum number of iterations is reached.
		 * The process is triggered by the `cmplz_remote_cookie_scan` action and stops if the scan is not enabled,
		 * the maximum iterations are reached, or the scan status is 'failed'.
		 *
		 * @return void
		 */
		public function wsc_scan_process(): void
		{
			if (!$this->wsc_scan_enabled()) {
				cmplz_wsc_logger::log_errors('wsc_scan_process', 'COMPLIANZ: wsc scan is not enabled');
				return;
			}

			$status = 'not-started';
			$max_iterations = 25;
			$cookies = [];

			$iteration = (int)get_option("cmplz_wsc_scan_iteration");
			$iteration++;

			// reached the max iterations the scan is completed
			if ($iteration > $max_iterations) {
				update_option('cmplz_wsc_scan_status', 'completed', false);
				update_option('cmplz_wsc_scan_progress', 100);
				return;
			}

			update_option("cmplz_wsc_scan_iteration", $iteration, false);

			// if the scan is not yet started
			if (!get_option('cmplz_wsc_scan_id')) {
				$this->wsc_scan_start(); // start the scan and store the scan id and scan status
				update_option('cmplz_wsc_scan_progress', 25); // set the progress to 25%
			}

			// once we have the scan id, we can check the status
			if (get_option('cmplz_wsc_scan_id') !== false) {
				$sleep = 6;
				sleep($sleep);
				update_option('cmplz_wsc_scan_progress', 25 + $iteration * 5);
				$status = $this->wsc_scan_get_status(); // check the status of the scan
			}

			if ($status === 'completed') { // if the status is completed
				// if is already completed by webhook and progress is 100
				$is_webhook_result = $this->wsc_scan_completed();

				if (!$is_webhook_result) { // if we don't have the results yet from the webhook
					update_option('cmplz_wsc_scan_progress', 100); // set to complete - 100%
					$cookies = $this->wsc_scan_get_cookies(); // get and store the cookies
				}
			}

			// if failed, stop scan and mark as completed for now
			if ($status === 'failed') {
				update_option('cmplz_wsc_scan_status', 'completed', false);
				update_option('cmplz_wsc_scan_progress', 100, false);
			}

			//check if we have results
			if (count($cookies) > 0) {
				// store the cookies
				$this->wsc_scan_store_cookies($cookies);
			}
		}


		/**
		 * Store cookies retrieved from the WSC scan.
		 *
		 * This function processes an array of cookies, filters out non-cookie and non-webStorage types,
		 * and stores the remaining cookies using the CMPLZ_COOKIE class.
		 *
		 * @param array $cookies An array of cookie objects retrieved from the WSC scan.
		 */
		public static function wsc_scan_store_cookies(array $cookies): void
		{
			foreach ($cookies as $key => $c) {
				// Skip if the type is not 'webStorage' or 'cookie'
				if ($c->type !== 'webStorage' && $c->type !== 'cookie') {
					continue;
				}

				$cookie = new CMPLZ_COOKIE();
				// Set the cookie type to 'localstorage' if it's 'webStorage', otherwise 'cookie'
				$cookie->type = $c->type === 'webStorage' ? 'localstorage' : 'cookie';
				// Set the domain to 'self' if it's 'webStorage', otherwise use the cookie's domain
				$cookie->domain = $c->type === 'cookie' ? $c->domain : 'self';
				// Add the cookie name and supported languages to the cookie object
				$cookie->add($c->name, COMPLIANZ::$banner_loader->get_supported_languages());
				// Save the cookie object
				$cookie->save(true);
			}
		}


		/**
		 * Reset the WSC scan options.
		 *
		 * This function deletes the options related to the WSC scan, effectively resetting the scan state.
		 * It removes the scan ID, scan status, scan progress, and scan iteration count from the WordPress options.
		 *
		 * @return void
		 */
		public static function wsc_scan_reset(): void
		{
			delete_option('cmplz_wsc_scan_id');
			delete_option('cmplz_wsc_scan_status');
			delete_option('cmplz_wsc_scan_progress');
			delete_option('cmplz_wsc_scan_iteration');
			delete_option('cmplz_wsc_scan_createdAt');
		}


		/**
		 * Check if the WSC scan is completed.
		 *
		 * This function checks if the WSC scan is enabled and then verifies if the scan progress
		 * has reached 100%, indicating that the scan is completed.
		 *
		 * @return bool True if the WSC scan is completed, false otherwise.
		 */
		public function wsc_scan_completed(): bool
		{
			if (!$this->wsc_scan_enabled()) { // force true
				cmplz_wsc_logger::log_errors('wsc_scan_completed', 'COMPLIANZ: wsc scan not enabled');
				return true;
			}
			return get_option('cmplz_wsc_scan_progress') === 100;
		}


		/**
		 * Get the progress of the WSC scan.
		 *
		 * This function checks if the WSC scan is enabled. If it is not enabled, it returns 100,
		 * indicating that the scan is complete. Otherwise, it retrieves the current scan progress
		 * from the WordPress options.
		 *
		 * @return int The current progress of the WSC scan, or 100 if the scan is not enabled.
		 */
		public function wsc_scan_progress(): int
		{
			if (!$this->wsc_scan_enabled()) {
				return 100;
			}
			return (int)get_option('cmplz_wsc_scan_progress');
		}


		/**
		 * Get the URL for the WSC scan.
		 *
		 * This function returns the URL to be used for the WSC scan. If the `SCRIPT_DEBUG` constant is defined,
		 * it returns the Complianz URL. Otherwise, it returns the site URL.
		 * @return string The URL to be used for the WSC scan.
		 */
		private function wsc_scan_get_url(): string
		{
			return site_url();
		}


		/**
		 * Retrieve the cookies found during the WSC scan.
		 *
		 * This function retrieves the cookies found during the WSC scan by making a request to the WSC API.
		 * It returns an array of cookie objects if the request is successful, or an empty array otherwise.
		 *
		 * @return array An array of cookie objects found during the WSC scan.
		 */
		private function wsc_scan_get_cookies(): array
		{
			$response = $this->wsc_scan_retrieve_scan();
			$cookies = [];

			if (is_wp_error($response)) {
				cmplz_wsc_logger::log_errors('wsc_scan_get_cookies', 'COMPLIANZ: error retrieving scan, error: ' . $response->get_error_message());
				return $cookies;
			}

			$data = json_decode(wp_remote_retrieve_body($response));
			if (isset($data->status) && $data->status === 'completed') {
				if (isset($data->result->trackers)) {
					$cookies = $data->result->trackers;
				}
				update_option('cmplz_wsc_scan_status', 'completed', false);
			}

			return $cookies;
		}


		/**
		 * Retrieve the status of the WSC scan.
		 *
		 * This function retrieves the status of the WSC scan by making a request to the WSC API.
		 * It returns the status if the request is successful, or the default status otherwise.
		 *
		 * @param int $iteration The current iteration of the status retrieval process.
		 * @return string The status of the WSC scan.
		 */
		private function wsc_scan_get_status(int $iteration = 0): string
		{
			$defaultStatus = get_option('cmplz_wsc_scan_status', 'not-started');
			$response = $this->wsc_scan_retrieve_scan(); // array or wp_error

			// Early exit if response is an error or if recurring and iteration is >= 2
			if (is_wp_error($response) || $iteration >= 2) {
				cmplz_wsc_logger::log_errors('wsc_scan_get_status', 'COMPLIANZ: error retrieving scan status');
				return $defaultStatus;
			}

			// Decode the response body
			$data = json_decode(wp_remote_retrieve_body($response));

			// Check if there was an error in the scan process
			if (isset($data->is_processed) && $data->is_processed === 'error' && isset($data->skipped_urls) && is_array($data->skipped_urls)) {
				foreach ($data->skipped_urls as $skipped) {
					if ($skipped->reason === 'PageNotLoadedError' && $skipped->url === $this->wsc_scan_get_url()) {
						cmplz_wsc_logger::log_errors('wsc_scan_get_status', 'COMPLIANZ: error in scan process');
						return 'failed';
					}
				}
			}

			// If an error occurred in the response, restart the scan and retry
			if (isset($data->error)) {
				$this->wsc_scan_start();
				return $this->wsc_scan_get_status($iteration + 1); // Retry with incremented iteration
			}

			// If a status is provided, update it and return
			if (isset($data->status)) {
				update_option('cmplz_wsc_scan_status', $data->status, false);
				return $data->status;
			}

			// Default return
			return $defaultStatus;
		}


		/**
		 * Retrieve the WSC scan.
		 *
		 * This function retrieves the WSC scan data by making a request to the WSC API.
		 * It returns the response if the request is successful, or an empty array otherwise.
		 *
		 * @return array|WP_Error The response from the WSC API.
		 */
		private function wsc_scan_retrieve_scan()
		{
			$id = sanitize_text_field(get_option('cmplz_wsc_scan_id'));

			$endpoint = self::WSC_SCANNER_ENDPOINT . '/api/v1/scans/' . $id;
			$token = cmplz_wsc_auth::get_token();

			return wp_remote_get($endpoint, array(
					'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'oauth-' . $token
					),
					'timeout' => 15,
					'sslverify' => true
				)
			);
		}

		/**
		 * Initialize the WSC scan.
		 *
		 * This function resets the old scanner to start the WSC scan if the WSC scan status is already enabled.
		 *
		 * @return void
		 */
		public function wsc_scan_init(): void
		{
			if (get_option('cmplz_wsc_status') !== 'enabled') {
				return;
			}

			if (get_option('cmplz_wsc_scan_first_run', false)) {
				return;
			}

			$processed_pages_list = get_transient('cmplz_processed_pages_list');

			if (!is_array($processed_pages_list) || !in_array("remote", $processed_pages_list)) {
				return;
			}

			$processed_pages_list = array_diff($processed_pages_list, ["remote"]);
			set_transient('cmplz_processed_pages_list', $processed_pages_list, MONTH_IN_SECONDS);
			update_option('cmplz_wsc_scan_first_run', true, false);
		}
	}
}
