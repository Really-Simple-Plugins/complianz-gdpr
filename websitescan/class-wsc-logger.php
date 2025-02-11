<?php

defined('ABSPATH') or die();

if (!class_exists("cmplz_wsc_logger")) {

	class cmplz_wsc_logger
	{
		public function init_hooks()
		{
			add_action( 'cmplz_every_month_hook', array( $this, 'clear_errors_log') );
		}

		/**
		 * Logs an error related to a specific $context and stores it in a WordPress option.
		 *
		 * @param string $context The $context name or identifier related to the error.
		 * @param string $error_message The error message to log.
		 */
		public static function log_errors(string $context, string $error_message = ''): void
		{
			// If WP_DEBUG is enabled, log the error to the error log
			if (defined('WP_DEBUG') && WP_DEBUG) {
				error_log("COMPLIANZ: error in context '$context': " . $error_message);
			}

			// Get existing errors from the options table
			$errors = get_option('cmplz_wsc_logs', array());

			$sanitized_context = sanitize_text_field($context);
			$sanitized_error_message = sanitize_text_field($error_message);
			$sanitized_timestamp = current_time('mysql');

			// Append the new error
			$errors[] = array(
				'context' => $sanitized_context,
				'error_message' => $sanitized_error_message,
				'timestamp' => $sanitized_timestamp
			);
			$sanitized_errors = array_map( function( $error ) {
				return array(
					'context' => sanitize_text_field( $error['context'] ),
					'error_message' => sanitize_text_field( $error['error_message'] ),
					'timestamp' => sanitize_text_field( $error['timestamp'] ),
				);
			}, $errors );

			// Update the option with the new error log
			update_option('cmplz_wsc_logs', $sanitized_errors, false);
		}

		/**
		 * Retrieves all logged errors from the WordPress options.
		 *
		 * @return array The array of logged errors.
		 */
		public function get_errors_log(): array
		{
			// Retrieve the errors stored in the WordPress option
			return get_option('cmplz_wsc_logs', array());
		}

		/**
		 * Clears all logged errors from the WordPress options.
		 */
		public function clear_errors_log(): void
		{
			// Delete the option to clear all stored errors
			delete_option('cmplz_wsc_logs');
		}
	}
}
