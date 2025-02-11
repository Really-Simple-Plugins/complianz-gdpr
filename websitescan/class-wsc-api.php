<?php
defined('ABSPATH') or die("you do not have access to this page!");

if (!class_exists("cmplz_wsc_api")) {
	class cmplz_wsc_api
	{
		private static $_this;

		function __construct()
		{
			if (isset(self::$_this))
				wp_die(sprintf('%s is a singleton class and you cannot create a second instance.', get_class($this)));

			self::$_this = $this;
			add_action('rest_api_init', array($this, 'wsc_scan_enable_webhook_api'));
		}

		static function this()
		{
			return self::$_this;
		}

		/**
		 * Register the REST API route for the WSC scan.
		 *
		 * This function registers a custom REST API route for the WSC scan. The route
		 * accepts only POST requests and uses the `wsc_scan_callback` method as the
		 * callback function.
		 *
		 * @return void
		 */
		public function wsc_scan_enable_webhook_api(): void
		{
			register_rest_route('complianz/v1', 'wsc-scan', array(
				'methods' => 'POST', // Accept only POST requests
				'callback' => array($this, 'wsc_scan_webhook_callback'),
				'permission_callback' => '__return_true',
			));
		}


		/**
		 * Process the WSC scan webhook callback.
		 *
		 * This function processes the WSC scan webhook callback. It validates the request
		 * and then processes the scan results. If the request is invalid, an error is returned.
		 *
		 * @param WP_REST_Request $request The REST API request object.
		 * @return WP_REST_Response|WP_Error The REST API response object or an error object.
		 */
		public function wsc_scan_webhook_callback(WP_REST_Request $request)
		{
			$error = self::wsc_scan_validate_request($request);
			$is_valid_request = empty($error); // if the array is empty, the request is valid

			if (!$is_valid_request) { // if the array is not empty, contains an error and the request is invalid
				return new WP_Error(
					$error['code'],
					$error['message'],
					array('status' => $error['status'])
				);
			}

			// start the processing of the request
			$result = json_decode($request->get_body());

			if (!$result->data->result->trackers || count($result->data->result->trackers) === 0) {
				return new WP_REST_Response('No cookies found in the result.', 200);
			}

			$current_wsc_status = get_option('cmplz_wsc_scan_status');
			// if the scan is already completed, exit
			if ($current_wsc_status === 'completed') {
				return new WP_REST_Response('Scan already completed.', 200);
			}

			update_option('cmplz_wsc_scan_status', 'completed', false);
			COMPLIANZ::$wsc_scanner->wsc_scan_store_cookies($result->data->result->trackers);

			return new WP_REST_Response('Cookies updated!', 200);

		}

		/**
		 * Validate the WSC scan webhook request.
		 *
		 * This function validates the WSC scan webhook request. It checks if the request
		 * is valid and contains the necessary information to process the scan results.
		 *
		 * @param WP_REST_Request $request The REST API request object.
		 * @return array If the request is invalid an array containing the error details, otherwise an empty array.
		 */
		public static function wsc_scan_validate_request(WP_REST_Request $request): array
		{
			// check the body
			if (empty($request->get_body())) {
				return [
					'code' => 'invalid_request',
					'message' => 'Request blocked: missing request.',
					'status' => 400
				];
			}

			// Get options for permission check
			$scan_id = get_option('cmplz_wsc_scan_id', false);
			$scan_created_at = get_option('cmplz_wsc_scan_createdAt', false);
			// Check if there is an active scan
			if (!$scan_id || !$scan_created_at) {
				return [
					'code' => 'invalid_wsc_scan',
					'message' => 'No active scan found.',
					'status' => 400
				];
			}

			// Check the user agent
			$user_agent = $request->get_header('User-Agent');
			if (strpos($user_agent, 'radar') === false) {
				return [
					'code' => 'invalid_user_agent',
					'message' => 'Request blocked: unauthorized User-Agent.',
					'status' => 400
				];
			}

			// Verify scan status event in the request body
			$data = json_decode($request->get_body());
			if (!isset($data->event) || $data->event !== 'scan-completed') {
				return [
					'code' => 'invalid_event',
					'message' => 'Request blocked: missing or invalid scan status.',
					'status' => 400
				];
			}

			// Return the errors array if any errors are found, or an empty array if all checks pass
			return [];
		}
	}
}
