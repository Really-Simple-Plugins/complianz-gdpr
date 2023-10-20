<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class to send an e-mail
 */

if ( !class_exists('cmplz_mailer') ) {
	class cmplz_mailer {

		public $to;
		public $title;
		public $headers;
		public $message;
		public $subject;
        public $change_text;
        public $sent_to_text;
        public $what_now_text;
        public $sent_by_text;
		public $warning_blocks;
		public $error = '';

		public function __construct() {
			$this->sent_by_text = __("This email is part of the Complianz Notification System", "complianz-gdpr");
			$this->subject = __("Notification by Complianz", "complianz-gdpr");
			$this->title = __("Learn more about our features!", "complianz-gdpr");
			$this->sent_to_text = __("This email was sent to", "complianz-gdpr");
			$this->what_now_text = __( "What now?", "complianz-gdpr");
			$this->change_text = __("I didn't change any settings in the plugin.", "complianz-gdpr");
			$domain = '<a href="'.site_url().'">'.site_url().'</a>';
			$this->message = sprintf(__("You have enabled a feature on %s. We think it's important to let you know a little bit more about this feature so you can use it without worries.","complianz-gdpr"), $domain);

			add_action('wp_mail_failed', array($this, 'log_mailer_errors'), 10, 1);
		}

		/**
		 * Send a test email
		 * @return array
		 */
		public function send_test_mail(){
			if ( !cmplz_user_can_manage() ) {
				return ['success' => false, 'message' => 'Not allowed'];
			}
			$this->to = cmplz_get_option('notifications_email_address' );
			if ( !is_email($this->to) ) {
				$this->to = get_bloginfo('admin_email');
			}

			if ( !is_email($this->to) ){
				return ['success' => false, 'message' => __('Email address not valid',"complianz-gdpr")];
			}
			$this->title = __("Complianz - Notification Test", "complianz-gdpr");
			$this->message = __("This email is confirmation that any email notices are likely to reach your inbox.", "complianz-gdpr");

			$this->warning_blocks = [
				[
					'title' => __("About notifications","complianz-gdpr"),
					'message' => __("Email notifications are only sent for important updates, changes or when certain features are enabled.","complianz-gdpr"),
					'url' => 'https://complianz.io/instructions/about-email-notifications/',
				]
			];
			$success = $this->send_mail(true);
			if ($success) {
				return ['success' => true, 'message' => __('Email sent! Please check your mail', "complianz-gdpr")];
			}

			if (empty($this->error)) {
				$this->error = __('Email could not be sent.', "complianz-gdpr");
			} else {
				$this->error = __('An error occurred:', "complianz-gdpr").'<br>'.$this->error;
			}
			return ['success' => false, 'message' => $this->error];
		}

		public function log_mailer_errors( $wp_error ){
			if (is_wp_error($wp_error)) {
				$this->error = $wp_error->get_error_message();
			}
		}
		/**
		 * Send an e-mail with the correct login URL
		 *
		 * @param bool $override_rate_limit
		 *
		 * @return bool
		 */
		public function send_mail($override_rate_limit = false) {
			if ( empty($this->message) || empty($this->subject) ) {
				return false;
			}

			$this->to = cmplz_get_option('notifications_email_address' );
			if ( !is_email($this->to) ) {
				$this->to = get_bloginfo('admin_email');
			}

			$template = file_get_contents(__DIR__.'/templates/email.html');
			$block_html = '';
			if (is_array($this->warning_blocks) && count($this->warning_blocks)>0) {
				$block_template = file_get_contents(__DIR__.'/templates/block.html');
				foreach ($this->warning_blocks as $warning_block){
					$block_html .= str_replace(
						['{title}','{message}','{url}'],
						[ sanitize_text_field($warning_block['title']), wp_kses_post($warning_block['message']), esc_url_raw($warning_block['url']) ],
						$block_template);
				}
			}
			$body = str_replace(
				[
					'{title}',
					'{message}',
					'{warnings}',
					'{email-address}',
					'{learn-more}',
					'{site_url}',
                    '{change_text}',
                    '{what_now}',
                    '{sent_to_text}',
                    '{sent_by_text}'
				],
				[
					sanitize_text_field( $this->title ),
					wp_kses_post( $this->message ),
					$block_html,
					$this->to,
					__( "Learn more", "complianz-gdpr" ),
					site_url(),
                    $this->change_text,
                    $this->what_now_text,
                    $this->sent_to_text,
                    $this->sent_by_text
				], $template );
			$success = wp_mail( $this->to, sanitize_text_field($this->subject), $body, array('Content-Type: text/html; charset=UTF-8') );
			set_transient('cmplz_email_recently_sent', true, 5 * MINUTE_IN_SECONDS );
			return $success;
		}

	}
}
