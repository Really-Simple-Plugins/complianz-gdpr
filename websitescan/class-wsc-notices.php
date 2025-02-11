<?php

defined('ABSPATH') or die();

if (!class_exists("cmplz_wsc_notices")) {

	class cmplz_wsc_notices
	{

		public function init_hooks()
		{
			// If any hooks are needed for notices
			add_filter('cmplz_field_notices', array($this, 'notices'));
			add_filter('cmplz_warning_types', array($this, 'wsc_scan_add_warnings'));
		}

		/**
		 * Push some error messages if we find any
		 *
		 * @param array $notices
		 *
		 * @return array
		 */
		public function notices(array $notices): array
		{
			if (!cmplz_user_can_manage()) {
				return $notices;
			}
			if (get_option('cmplz_wsc_error_email_mismatch')) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("E-mail mismatch", 'complianz-gdpr'),
					'text'     => __("The e-mail that you are authenticating does not match the e-mail stored in your settings currently. Please clear the e-mail, save, then enter your e-mail address again.", 'complianz-gdpr'),
				];
			}

			if (get_option('cmplz_wsc_error_missing_token')) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("Missing token", 'complianz-gdpr'),
					'text'     => __("The token is missing from the URL which you are using to authenticate.", 'complianz-gdpr'),
					'url'      => 'https://complianz.io/authentication-failed',
				];
			}
			if (get_option('cmplz_wsc_error_email_auth_failed')) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("Authentication failed", 'complianz-gdpr'),
					'text'     => __("The authentication of your e-mail address failed. Please try again later.", 'complianz-gdpr'),
					'url'      => 'https://complianz.io/authentication-failed',
				];
			}

			if (get_option('cmplz_wsc_error_token_api')) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("Token not retrieved", 'complianz-gdpr'),
					'text'     => __("The token for the api could not be retrieved.", 'complianz-gdpr'),
					'url'      => 'https://complianz.io/authentication-failed',
				];
			}
			if (get_option('cmplz_wsc_error_email_not_sent')) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("E-mail verification not sent", 'complianz-gdpr'),
					'text'     => __("The e-mail to verify your e-mail address could not be sent. Please check your e-mail address or try again later.", 'complianz-gdpr'),
					'url'      => 'https://complianz.io/authentication-failed',
				];
			}
/*			if (!cmplz_wsc_auth::wsc_is_authenticated()) {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label'    => 'warning',
					'title'    => __("Try our new Website Scan!", 'complianz-gdpr'),
					'text'     => __("In the latest release of Complianz, we introduce our newest Website Scan. This scan will not only retrieve services and cookies but also help you configure our plugin and keep you up-to-date if changes are made that might need legal changes.", 'complianz-gdpr'),
					'url'      => 'https://complianz.io/about-the-website-scan'
				];
			}
			if (!cmplz_wsc_auth::wsc_is_authenticated()) {
				$notices[] = [
					'field_id' => 'cookie_scan',
					'label'    => 'warning',
					'title'    => __("Try our new Website Scan!", 'complianz-gdpr'),
					'text'     => __("In the latest release of Complianz, we introduce our newest Website Scan. This scan will not only retrieve services and cookies but also help you configure our plugin and keep you up-to-date if changes are made that might need legal changes.", 'complianz-gdpr'),
					'url' => '#settings/settings-cd',
				];
			}*/
			if (get_option('cmplz_wsc_signup_status') === 'pending') {
				$notices[] = [
					'field_id' => cmplz_wsc::WSC_EMAIL_OPTION_KEY,
					'label' => 'warning',
					'title' => __("Check your email!", 'complianz-gdpr'),
					'text' => __("Your authentication is still on pending, check your emails for a confirmation.", 'complianz-gdpr'),
					'url' => 'https://complianz.io/about-the-website-scan#pending'
				];
			}
			return $notices;
		}


		/**
		 * Add a warning that integrations changed.
		 *
		 * @param array $warnings
		 *
		 * @return array
		 */
		function wsc_scan_add_warnings(array $warnings): array
		{
			// if not authenticated
			if (cmplz_wsc_auth::wsc_is_authenticated()){
				return $warnings;
			}

			$warnings['wsc-scan'] = array(
				'plus_one' => true,
				'dismissible' => true,
				'warning_condition' => '_true_',
				'open' => __('You have a new feature! To enable the new and improved Website Scan you need to authenticate your website.', 'complianz-gdpr'),
				'url' => '#settings/settings-cd',
			);

			return $warnings;
		}
	}
}
