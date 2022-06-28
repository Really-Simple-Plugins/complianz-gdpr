<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_DNSMPD" ) ) {
	class cmplz_DNSMPD {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;

			if ( cmplz_dnsmpi_required() ) {
				add_shortcode( 'cmplz-dnsmpi-request', array($this, 'datarequest_form') );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'rest_api_init', array($this, 'register_rest_route') );
			add_action( 'admin_init', array( $this, 'process_delete' ) );
			add_action( 'admin_init', array( $this, 'process_resolve' ) );
			add_action( 'activated_plugin', array( $this, 'update_db_check' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'update_db_check' ), 10 );
			add_action( 'cmplz_admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'cmplz_datarequest_options', array( $this, 'datarequest_options' ), 20 );
			add_filter( 'cmplz_warning_types', array($this, 'new_datarequests_notice') );
			add_filter( 'cmplz_settings_items', array($this, 'add_settings_block') );
		}

		static function this() {
			return self::$_this;
		}

		public function add_settings_block($items){
			if (cmplz_dnsmpi_required() || cmplz_datarequests_active() ) {
				$items['data-requests'] = [
						'page' => 'settings',
						'name' => 'data-requests',
						'header' => __('Data Requests', 'complianz-gdpr'),
						'class' => 'medium',
						'index' => '13',
						'controls' => '',
				];
			}
			return $items;
		}

		/**
		 * Add new datarequests
		 *
		 * @param array $warnings
		 *
		 * @return array
		 */

		public function new_datarequests_notice($warnings){
			$warnings['new_datarequest'] = [
				'warning_condition'  => 'DNSMPD->has_open_requests',
				'include_in_progress' => true,
				'plus_one' => true,
				'open' => __( 'You have open data requests.', 'complianz-gdpr' ).'&nbsp;'.cmplz_sprintf(__( 'Please check the data requests <a href="%s">overview page</a>.', 'complianz-gdpr' ), add_query_arg(array('page'=>'cmplz-datarequests'),admin_url('admin.php'))),
				'dismissible' => false,
			];
			return $warnings;
		}

		/**
		 * Check if there are open requests
		 *
		 * @return bool
		 */

		public function has_open_requests(){
			$has_requests = false;
			if ( cmplz_dnsmpi_required() || cmplz_datarequests_active() ) {
				global $wpdb;
				$count        = $wpdb->get_var( "SELECT count(*) from {$wpdb->prefix}cmplz_dnsmpd WHERE NOT resolved = 1" );
				$has_requests = $count > 0;
			}
			return $has_requests;
		}

		/**
		 * Extend options with generic options
		 *
		 * @param array $options
		 *
		 * @return array
		 */

		public function datarequest_options( $options = [] ){
			$options = $options + [
				"global_optout"   => [
					'slug' => 'definition/what-is-global-opt-out',
					'short' => __( 'Global opt-out', 'complianz-gdpr' ),
					'long' => __( 'Global opt-out from selling and sharing my personal information and limiting the use or disclosure of sensitive personal information.', 'complianz-gdpr' ),
				],
				"cross_context"   => [
					'slug' => 'definition/what-is-cross-context-behavioral-advertising/',
					'short'  => __( 'Do not sell my info', 'complianz-gdpr' ),
					'long' => __( 'Do not sell my personal information for cross-context behavioral advertising', 'complianz-gdpr' ),
				],
				"limit_sensitive" => [
					'slug' => 'definition/what-is-limit-sensitive-data/',
					'short' => __( 'Limit sensitive data', 'complianz-gdpr' ),
					'long' => __( 'Limit the use of my sensitive personal information', 'complianz-gdpr' ),
				],
			];
			return $options;
		}
		/**
		 * Enqueue front-end assets
		 * @param $hook
		 */
		public function enqueue_assets( $hook ) {

			global $post;
			if ( $post && !COMPLIANZ::$document->is_complianz_page($post->ID ) ) {
				return;
			}

			if ( !cmplz_dnsmpi_required() && !cmplz_datarequests_active() ) {
				return;
			}

			wp_enqueue_script( 'cmplz-dnsmpd', cmplz_url . "DNSMPD/script.min.js", array( 'jquery' ), cmplz_version, true );
			wp_localize_script(
				'cmplz-dnsmpd',
				'cmplz_datarequests',
				array(
						'url' => get_rest_url().'complianz/v1/datarequests',
				)
			);
		}

		/**
		 * Add admin menu
		 * @return void
		 */
		public function admin_menu() {
			if ( ! cmplz_user_can_manage() ) {
				return;
			}

			if ( !cmplz_dnsmpi_required() && !cmplz_datarequests_active() ) {
				return;
			}

			add_submenu_page(
				'complianz',
				__( 'Data requests', 'complianz-gdpr' ),
				__( 'Data requests', 'complianz-gdpr' ),
				'manage_options',
				'cmplz-datarequests',
				array( $this, 'data_requests_overview' )
			);
		}

		/**
		 * Removed users overview
		 *
		 * @return void
		 */
		public function data_requests_overview() {
			ob_start();
			include( dirname( __FILE__ ) . '/class-DNSMPD-table.php' );
			$datarequests = new cmplz_DNSMPD_Table();
			$datarequests->prepare_items();
			?>
			<div class="cmplz-datarequests">
				<h1 class="wp-heading-inline"><?php _e( 'Data Requests', 'complianz-gdpr' ); ?>
					<a href="<?php echo esc_url_raw( cmplz_url . "DNSMPD/csv.php?nonce=" . wp_create_nonce( 'cmplz_csv_nonce' ) ) ?>" target="_blank" class="button button-primary"><?php _e("Export", "complianz-gdpr")?></a>
				</h1>
				<form id="cmplz-dnsmpd-filter" method="get" action="<?php echo admin_url( 'admin.php?page=cmplz-datarequests' ); ?>">
					<?php
						$datarequests->search_box( __( 'Search requests', 'complianz-gdpr' ), 'cmplz-datarequests' );
						$datarequests->resolved_select();
						$datarequests->display();
					?>
					<input type="hidden" name="page" value="cmplz-datarequests"/>
				</form>
			</div>
			<?php

			$content = ob_get_clean();
			$args = array(
					'page' => 'do-not-sell-my-personal-information',
					'content' => $content,
			);
			echo cmplz_get_template('admin_wrap.php', $args );
		}

		/**
		 * Get users
		 * @param array $args
		 *
		 * @return array
		 */
		public function get_requests( $args ) {
			global $wpdb;
			$sql        = "SELECT * from {$wpdb->prefix}cmplz_dnsmpd WHERE 1=1 ";
			if ( isset( $args['email'] ) && ! empty( $args['email'] ) && is_email( $args['email'] ) ) {
				$sql .= " AND email like '"."%" . sanitize_email( $args['email'] ) . "%"."'";
			}

			if ( isset( $args['name'] ) && ! empty( $args['name'] ) ) {
				$sql .= " AND name like '%" . sanitize_text_field( $args['name'] ) . "%'";
			}

			if ( isset( $args['resolved'] )) {
				$sql .= " AND resolved = " . intval($args['resolved']);
			}

			$sql .= " ORDER BY " . sanitize_title( $args['orderby'] ) . " " . sanitize_title( $args['order'] );
			if ( isset( $args['number'] ) ) {
				$sql .= " LIMIT " . intval( $args['number'] ) . " OFFSET " . intval( $args["offset"] );
			}
			return $wpdb->get_results( $sql );
		}

		/**
		 * Count number of users
		 * @param $args
		 *
		 * @return int
		 */
		public function count_requests( $args ) {
			unset( $args['number'] );
			$users = $this->get_requests( $args );
			return count( $users );
		}

		/**
		 * Handle  resolve request
		 */

		public function process_resolve() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'cmplz-datarequests' )
				 && isset( $_GET['action'] )
				 && $_GET['action'] == 'resolve'
				 && isset( $_GET['id'] )
			) {
				global $wpdb;
				$wpdb->update( $wpdb->prefix . 'cmplz_dnsmpd',
						array(
							'resolved' => 1
						),
						array( 'ID' => intval( $_GET['id'] ) )
				);
				$paged = isset( $_GET['paged'] ) ? 'paged=' . intval( $_GET['paged'] ) : '';
				wp_redirect( admin_url( 'admin.php?page=cmplz-datarequests' . $paged ) );
				exit;
			}
		}

		/**
		 * Handle delete request
		 */

		public function process_delete() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'cmplz-datarequests' )
			     && isset( $_GET['action'] )
			     && $_GET['action'] == 'delete'
			     && isset( $_GET['id'] )
			) {
				global $wpdb;
				$wpdb->delete( $wpdb->prefix . 'cmplz_dnsmpd', array( 'ID' => intval( $_GET['id'] ) ) );
				$paged = isset( $_GET['paged'] ) ? 'paged=' . intval( $_GET['paged'] ) : '';
				wp_redirect( admin_url( 'admin.php?page=cmplz-datarequests' . $paged ) );
			}
		}

		/**
		 * Check if the table needs to be created or updated
		 * @return void
		 */
		public function update_db_check() {
			if ( get_option( 'cmplz_dnsmpd_db_version' ) != cmplz_version ) {
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name = $wpdb->prefix . 'cmplz_dnsmpd';
				$sql        = "CREATE TABLE $table_name (
				  `ID` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `email` varchar(255) NOT NULL,
				  `region` TEXT NOT NULL,
				  `global_optout` int(11) NOT NULL,
				  `cross_context` int(11) NOT NULL,
				  `limit_sensitive` int(11) NOT NULL,
				  `request_date` int(11) NOT NULL,
				  `resolved` int(11) NOT NULL,
				  PRIMARY KEY  (ID)
				) $charset_collate;";

				dbDelta( $sql );
				update_option( 'cmplz_dnsmpd_db_version', cmplz_version );
			}
		}

		/**
		 * Send confirmation mail
		 *
		 * @param string $email
		 * @param string $name
		 *
		 * @return void
		 */
		private function send_confirmation_mail( $email, $name ) {
			$message = cmplz_get_value( 'notification_email_content' );
			$subject = cmplz_get_value( 'notification_email_subject' );
			$message = str_replace( '{name}', $name, $message );
			$message = str_replace( '{blogname}', get_bloginfo( 'name' ), $message );
			$this->send_mail( $email, $subject, $message );
		}

		/**
		 * Send confirmation mail
		 *
		 * @return void
		 */

		private function send_notification_mail(  ) {
			$email     = sanitize_email( get_option( 'admin_email' ) );
			$subject = cmplz_sprintf(__("You have received a new data request on %s", "complianz-gdpr") , get_bloginfo( 'name' ) );
			$message = $subject.'<br>'.cmplz_sprintf(__("Please check the data request on %s", "complianz-gdpr"), '<a href="'.site_url().'" target="_blank">'.site_url().'</a>');
			$this->send_mail( $email, $subject, $message );
		}

		/**
		 * Send an email
		 * @param string $email
		 * @param string $subject
		 * @param string $message
		 *
		 * @return bool
		 */
		private function send_mail( $email, $subject, $message ) {
			$headers = [];
			$from_name  = get_bloginfo( 'name' );
			$from_email = cmplz_get_value( 'notification_from_email' );
			add_filter( 'wp_mail_content_type', function ( $content_type ) {
				return 'text/html';
			} );

			if ( ! empty( $from_email ) ) {
				$headers[] = 'From: ' . $from_name . ' <' . $from_email . '>'
				             . "\r\n";
			}

			$success = true;
			if ( wp_mail( $email, $subject, $message, $headers ) === false ) {
				$success = false;
			}

			// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			return $success;
		}

		/**
		 * Register the rest route
		 *
		 * @return void
		 */
		public function register_rest_route()
		{
			register_rest_route('complianz/v1', 'datarequests/', array(
				'methods' => 'POST',
				'callback' => array($this, 'process_restapi_datarequest'),
				'args' => array(),
				'permission_callback' => '__return_true',
			));
		}

		/**
		 * Process the form submit
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 */
		public function process_restapi_datarequest( WP_REST_Request $request ) {

			$params = $request->get_json_params();
			$new_request = false;
			$error = false;
			$message = "";

			//check honeypot
			if ( isset($params['cmplz_datarequest_firstname']) && ! empty( $params['cmplz_datarequest_firstname'] ) ) {
				$error   = true;
				$message = __( "Sorry, it looks like you're a bot", 'complianz-gdpr' );
			}

			if ( ! isset($params['cmplz_datarequest_email']) || ! is_email( $params['cmplz_datarequest_email'] ) ) {
				$error   = true;
				$message = __( "Please enter a valid email address.", 'complianz-gdpr' );
			}

			if ( ! isset($params['cmplz_datarequest_name']) || strlen( $params['cmplz_datarequest_name'] ) == 0 ) {
				$error   = true;
				$message = __( "Please enter your name", 'complianz-gdpr' );
			}

			if ( strlen( $params['cmplz_datarequest_name'] ) > 100 ) {
				$error = true;
				$message = __( "That's a long name you got there. Please try to shorten the name.", 'complianz-gdpr' );
			}

			if ( ! isset($params['cmplz_datarequest_region']) || strlen( $params['cmplz_datarequest_region'] ) == 0 ) {
				$region = 'us';
			}

			if ( ! $error ) {
				$email = sanitize_email( $params['cmplz_datarequest_email'] );
				$name  = sanitize_text_field( $params['cmplz_datarequest_name'] );
				$region  = sanitize_title( $params['cmplz_datarequest_region'] );
				//check if this email address is already registered:
				global $wpdb;
				$options = apply_filters( 'cmplz_datarequest_options', [] );
				foreach ( $options as $fieldname => $label ) {
					$value = isset( $params['cmplz_datarequest_'.$fieldname] ) ? intval( $params['cmplz_datarequest_'.$fieldname] ) : false;
					if ( $value === 1 ) {
						$count = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) from {$wpdb->prefix}cmplz_dnsmpd WHERE email = %s and $fieldname=1", $email ) );
						if ( $count == 0 ) {
							$new_request = true;
							$wpdb->insert( $wpdb->prefix . 'cmplz_dnsmpd',
									array(
											'name'         => $name,
											'email'        => $email,
											'region'        => $region,
											$fieldname     => $value,
											'request_date' => time()
									)
							);
						}
					}
				}

				if ( $new_request ) {
					$this->send_confirmation_mail( $email, $name );
					$this->send_notification_mail();
					$message = __( "Your request has been processed successfully!", 'complianz-gdpr' );
				} else {
					$message = __( "Your email address was already registered!", 'complianz-gdpr' );
				}
			}

			$response = json_encode( array(
					'message' => $message,
					'success' => ! $error,
			) );
			header( "Content-Type: application/json" );
			echo $response;
			exit;
		}

		/**
		 * Render the form in the shortcode
		 *
		 * @return false|string
		 */
		public function datarequest_form() {
			ob_start();
			?>
			<div class="cmplz-datarequest cmplz-alert">
				<span class="cmplz-close">&times;</span>
				<span id="cmplz-message"></span>
			</div>
			<form id="cmplz-datarequest-form">
				<input type="hidden" required value="us" name="cmplz_datarequest_region" id="cmplz_datarequest_region">

				<label for="cmplz_datarequest_firstname" class="cmplz-first-name"><?php echo __('Name','complianz-gdpr')?><input type="search" class="dnsmpd-firstname" value="" placeholder="your first name" id="cmplz_datarequest_firstname" name="cmplz_datarequest_firstname"></label>
				<div>
					<label for="cmplz_datarequest_name"><?php echo __('Name','complianz-gdpr')?></label>
					<input type="text" required value="" placeholder="<?php echo __('Your name','complianz-gdpr')?>" id="cmplz_datarequest_name" name="cmplz_datarequest_name">
				</div>
				<div>
					<label for="cmplz_datarequest_email"><?php echo __('Email','complianz-gdpr')?></label>
					<input type="email" required value="" placeholder="<?php echo __('email@email.com','complianz-gdpr')?>" id="cmplz_datarequest_email" name="cmplz_datarequest_email">
				</div>

				<?php
				$options = $this->datarequest_options();
				foreach ( $options as $id => $label ) { ?>
					<div class="cmplz_datarequest cmplz_datarequest_<?php echo $id?>">
						<label for="cmplz_datarequest_<?php echo $id?>">
							<input type="checkbox" value="1" name="cmplz_datarequest_<?php echo $id?>" id="cmplz_datarequest_<?php echo $id?>"/>
							<?php echo $label['long']?>
						</label>
					</div>
				<?php } ?>
				<input type="button" id="cmplz-datarequest-submit" name="cmplz-datarequest-submit" value="<?php echo __('Send','complianz-gdpr')?>">
			</form>

			<style>
				/* first-name is honeypot */
				.cmplz-first-name {
					position: absolute !important;
					left: -5000px !important;
				}
			</style>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	} //class closure
}
