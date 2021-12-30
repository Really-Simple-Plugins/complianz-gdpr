<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.7
 */
if ( !class_exists('rsp_upgrade_to_pro')){
class rsp_upgrade_to_pro {

    // Version for css and js files
    private $version     = "1.0";

    // Change these values to correspond to values for this plugin
    private $api_url     = "";
    private $license     = "";
    private $item_id     = "";
    private $slug        = "";
    private $health_check_timeout = 5;
    private $plugin_name = "";
	private $steps;

    /**
     * Class constructor.
     *
     */
    public function __construct() {

        if ( isset($_GET['license']) ) {
            $this->license = sanitize_title($_GET['license']);
        }

        if ( isset($_GET['item_id']) ) {
            $this->item_id = sanitize_title($_GET['item_id']);
        }

        if ( isset($_GET['api_url']) ) {
            $this->api_url = esc_url_raw($_GET['api_url']);
        }

        if ( isset($_GET['plugin']) ) {
            $plugin = sanitize_title($_GET['plugin']);
            switch ($plugin) {
                case "rsssl_pro":
                    $this->slug = "really-simple-ssl-pro/really-simple-ssl-pro.php";
                    $this->plugin_name = "Really Simple SSL Pro";
                    break;
                case "cmplz_pro":
                    $this->slug = "complianz-gdpr-premium/complianz-gpdr-premium.php";
                    $this->plugin_name = "Complianz";
					break;
                case "brst_pro":
                    $this->slug = "burst";
	                $this->plugin_name = "Burst";
	                break;
            }
        }

		$this->steps = array(
			array(
				'action' => 'rsp_upgrade_destination_clear',
				'doing' => __("Checking if plugin folder exists...", "complianz-gdpr"),
				'success' => __("Able to create destination folder", "complianz-gdpr"),
				'error' => __("Destination folder already exists", "complianz-gdpr")
			),
			array(
				'action' => 'rsp_upgrade_activate_license',
				'doing' => __("Validating license...", "complianz-gdpr"),
				'success' => __("License valid", "complianz-gdpr"),
				'error' => __("License invalid", "complianz-gdpr")
			),
			array(
				'action' => 'rsp_upgrade_package_information',
				'doing' => __("Retrieving package information...", "complianz-gdpr"),
				'success' => __("Package information retrieved", "complianz-gdpr"),
				'error' => __("Failed to gather package information", "complianz-gdpr")
			),
			array(
				'action' => 'rsp_upgrade_install_plugin',
				'doing' => __("Installing plugin...", "complianz-gdpr"),
				'success' => __("Plugin installed", "complianz-gdpr"),
				'error' => __("Failed to install plugin", "complianz-gdpr")
			),
			array(
				'action' => 'rsp_upgrade_activate_plugin',
				'doing' => __("Activating plugin...", "complianz-gdpr"),
				'success' => __("Plugin activated", "complianz-gdpr"),
				'error' => __("Failed to activate plugin", "complianz-gdpr")
			)
		);

        // Set up hooks.
        $this->init();
    }

    /**
     * Set up WordPress filters to hook into WP's update process.
     *
     * @uses add_filter()
     *
     * @return void
     */
    public function init() {
        add_action( 'admin_footer', array( $this, 'print_install_modal' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets') );
        add_action( 'wp_ajax_rsp_upgrade_destination_clear', array($this, 'process_ajax_destination_clear') );
        add_action( 'wp_ajax_rsp_upgrade_activate_license', array($this, 'process_ajax_activate_license') );
        add_action( 'wp_ajax_rsp_upgrade_package_information', array($this, 'process_ajax_package_information') );
        add_action( 'wp_ajax_rsp_upgrade_install_plugin', array($this, 'process_ajax_install_plugin') );
        add_action( 'wp_ajax_rsp_upgrade_activate_plugin', array($this, 'process_ajax_activate_plugin') );
    }

    /**
     * Enqueue javascript
     * @todo minification
     */
    public function enqueue_assets( $hook ) {
        if ( $hook === "plugins.php" && isset($_GET['install-pro']) ) {
	        $minified = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	        wp_register_style( 'rsp-upgrade-css', plugin_dir_url(__FILE__) . "upgrade-to-pro$minified.css", false, $this->version.time() );
            wp_enqueue_style( 'rsp-upgrade-css' );

            wp_enqueue_script( 'rsp-ajax-js', plugin_dir_url(__FILE__) . "ajax$minified.js", array(), $this->version.time(), true );
            wp_enqueue_script( 'rsp-upgrade-js', plugin_dir_url(__FILE__) . "upgrade-to-pro.js", array(), $this->version.time(), true );
            wp_localize_script(
                'rsp-upgrade-js',
                'rsp_upgrade',
                array(
					'steps' => $this->steps,
                    'admin_url' => admin_url( 'admin-ajax.php' ),
                    'token'     => wp_create_nonce( 'upgrade_to_pro_nonce'),
                    'cmplz_nonce'     => wp_create_nonce( 'complianz_save'),
                )
            );
        }
    }

    /**
     * Calls the API and, if successfull, returns the object delivered by the API.
     *
     * @uses get_bloginfo()
     * @uses wp_remote_post()
     * @uses is_wp_error()
     *
     * @return false|object
     */
    private function api_request() {
        if ( !current_user_can('manage_options') ) {
            return false;
        }
        global $edd_plugin_url_available;

        $verify_ssl = $this->verify_ssl();

        // Do a quick status check on this domain if we haven't already checked it.
        $store_hash = md5( $this->api_url );
        if ( ! is_array( $edd_plugin_url_available ) || ! isset( $edd_plugin_url_available[ $store_hash ] ) ) {
            $test_url_parts = parse_url( $this->api_url );

            $scheme = ! empty( $test_url_parts['scheme'] ) ? $test_url_parts['scheme']     : 'http';
            $host   = ! empty( $test_url_parts['host'] )   ? $test_url_parts['host']       : '';
            $port   = ! empty( $test_url_parts['port'] )   ? ':' . $test_url_parts['port'] : '';

            if ( empty( $host ) ) {
                $edd_plugin_url_available[ $store_hash ] = false;
            } else {
                $test_url = $scheme . '://' . $host . $port;
                $response = wp_remote_get( $test_url, array( 'timeout' => $this->health_check_timeout, 'sslverify' => $verify_ssl ) );
                $edd_plugin_url_available[ $store_hash ] = is_wp_error( $response ) ? false : true;
            }
        }

        if ( false === $edd_plugin_url_available[ $store_hash ] ) {
            return false;
        }

        if( $this->api_url == trailingslashit ( home_url() ) ) {
            return false; // Don't allow a plugin to ping itself
        }

        $api_params = array(
            'edd_action' => 'get_version',
            'license'    => ! empty( $this->license ) ? $this->license : '',
            'item_id'    => isset( $this->item_id ) ? $this->item_id : false,
            'url'        => home_url(),
        );

        $request    = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => $verify_ssl, 'body' => $api_params ) );

        if ( ! is_wp_error( $request ) ) {
            $request = json_decode( wp_remote_retrieve_body( $request ) );
        }

        if ( $request && isset( $request->sections ) ) {
            $request->sections = maybe_unserialize( $request->sections );
        } else {
            $request = false;
        }

        if ( $request && isset( $request->banners ) ) {
            $request->banners = maybe_unserialize( $request->banners );
        }

        if ( $request && isset( $request->icons ) ) {
            $request->icons = maybe_unserialize( $request->icons );
        }

        if( ! empty( $request->sections ) ) {
            foreach( $request->sections as $key => $section ) {
                $request->$key = (array) $section;
            }
        }

        return $request;
    }


    /**
     * Returns if the SSL of the store should be verified.
     *
     * @since  1.6.13
     * @return bool
     */
    private function verify_ssl() {
        return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
    }


    /**
     * Prints a modal with bullets for each step of the install process
     */
    public function print_install_modal()
    {
	    if ( !current_user_can('manage_options') ) {
		    return false;
	    }

        if ( is_admin() && isset($_GET['install-pro']) && isset($_GET['license']) && isset($_GET['item_id']) && isset($_GET['api_url']) && isset($_GET['plugin']) ) {

            $dashboard_url = add_query_arg(["page" => "complianz"], admin_url( "admin.php" ));
            $plugins_url = admin_url( "plugins.php" );

            ?>
			<div id="rsp-step-template">
				<div class="rsp-install-step {step}">
					<div class="rsp-step-color">
						<div class="rsp-grey rsp-bullet"></div>
					</div>
					<div class="rsp-step-text">
						<span>{doing}</span>
					</div>
				</div>
			</div>
			<div id="rsp-plugin-suggestion-template">
				<div class="rsp-plugin-suggestion">
					<div class="rsp-icon"><img alt="suggested plugin icon" src="{icon_url}"></div>
					<div class="rsp-description">
						<div class="rsp-title">{suggested_plugin_title}</div>
						<div class="rsp-description_short">{suggested_plugin_description_short}</div>
						<div class="rsp-rating">{suggested_plugin_rating}</div>
					</div>
					<div class="rsp-description">{suggested_plugin_description}</div>
					<div class="rsp-install-button"><a class="button-secondary" href="{suggested_plugin_install_url}"><?php _e("Install", "complianz-gdpr")?></a></div>
				</div>
			</div>
            <div class="rsp-modal-transparent-background">
                <div class="rsp-install-plugin-modal">
                    <h3><?php echo __("Installing", "complianz-gdpr") . " " . $this->plugin_name ?></h3>
                    <div class="rsp-progress-bar-container">
                        <div class="rsp-progress rsp-grey">
                            <div class="rsp-bar rsp-green" style="width:0%"></div>
                        </div>
                    </div>
                    <div class="rsp-install-steps">

                    </div>
                    <a href="<?php echo $dashboard_url ?>" role="button" class="button-primary rsp-yellow rsp-hidden rsp-btn rsp-visit-dashboard">
                        <?php echo __("Visit Dashboard", "complianz-gdpr") ?>
                    </a>
                    <a href="<?php echo $plugins_url ?>" role="button" class="button-primary rsp-red rsp-hidden rsp-btn rsp-cancel">
                        <?php echo __("Cancel", "complianz-gdpr") ?>
                    </a>
                </div>
            </div>
            <?php
        }
    }


    /**
     * Ajax GET request
     *
     * Checks if the destination folder already exists
     *
     * Requires from GET:
     * - 'token' => wp_nonce 'upgrade_to_pro_nonce'
     * - 'plugin' (This will set $this->slug (Ex. 'really-simple-ssl-pro/really-simple-ssl-pro.php'), based on which plugin)
     *
     * Echoes array [success]
     */
    public function process_ajax_destination_clear()
    {
		$error = false;
		$response = [
				'success' => false,
		];
	    if ( !current_user_can('manage_options') ) {
		    $error = true;
	    }

        if ( !$error && isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['plugin']) ) {
            if ( !file_exists(WP_PLUGIN_DIR . '/' . $this->slug) ) {
                $response = [
                    'success' => true,
                ];
            }
        }

		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
    }


    /**
     * Ajax GET request
     *
     * Links the license on the website 'api_url' to this site
     *
     * Requires from GET:
     * - 'token' => wp_nonce 'upgrade_to_pro_nonce'
     * - 'license'
     * - 'item_id'
     * - 'api_url'
     *
     * (Without this link you cannot download the pro package from the website)
     *
     * Echoes array [license status, response message]
     */
    public function process_ajax_activate_license()
    {
		$error = false;
		$response = [
				'success' => false,
				'message' => '',
		];

		if ( !current_user_can('manage_options') ) {
			$error = true;
		}

        if (!$error && isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['license']) && isset($_GET['item_id']) && isset($_GET['api_url']) ) {
            $license  = sanitize_title($_GET['license']);
			$item_id = intval($_GET['item_id']);
			$response = $this->validate($license, $item_id);
			update_site_option('rsp_auto_installed_license', $license);
        }

		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
    }


	/**
	 * Activate the license on the websites url at EDD
	 *
	 * Stores values in database:
	 * - {$this->pro_prefix}license_activations_left
	 * - {$this->pro_prefix}license_expires
	 * - {$this->pro_prefix}license_activation_limit
	 *
	 * @param $license
	 * @param $item_id
	 *
	 * @return array [license status, response message]
	 */

	private function validate( $license, $item_id ) {
		$message = "";
		$success = false;

		if ( !current_user_can('manage_options') ) {
			return [
				'success' => $success,
				'message' => $message,
			];
		}

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => $item_id,
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', "complianz-gdpr");
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
								__( 'Your license key expired on %s.', 'complianz-gdpr'),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'disabled' :
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'complianz-gdpr');
						break;
					case 'missing' :
						$message = __('Missing license.', 'complianz-gdpr');
						break;
					case 'invalid' :
						$message = __( 'Invalid license.', 'complianz-gdpr');
						break;
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'complianz-gdpr' );
						break;
					case 'item_name_mismatch' :
						$message = __( 'This appears to be an invalid license key for this plugin.', 'complianz-gdpr' );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'complianz-gdpr');
						break;
					default :
						$message = __( 'An error occurred, please try again.', 'complianz-gdpr' );
						break;
				}
			} else {
				$success = $license_data->license === 'valid';
			}
		}

		$response = [
				'success' => $success,
				'message' => $message,
		];

		return $response;
	}


    /**
     * Ajax GET request
     *
     * Do an API request to get the download link where to download the pro package
     *
     * Requires from GET:
     * - 'token' => wp_nonce 'upgrade_to_pro_nonce'
     * - 'license'
     * - 'item_id'
     * - 'api_url'
     *
     * Echoes array [success, download_link]
     */
    public function process_ajax_package_information()
    {
	    if ( !current_user_can('manage_options') ) {
		    return false;
	    }

        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['license']) && isset($_GET['item_id']) && isset($_GET['api_url']) ) {
            $api = $this->api_request();
            if ( $api && isset($api->download_link) ) {
                $response = [
                    'success' => true,
                    'download_link' => $api->download_link,
                ];
            } else {
                $response = [
                    'success' => false,
                    'download_link' => "",
                ];
            }
            $response = json_encode($response);
            header("Content-Type: application/json");
            echo $response;
            exit;

        }
    }


    /**
     * Ajax GET request
     *
     * Download and install the plugin
     *
     * Requires from GET:
     * - 'token' => wp_nonce 'upgrade_to_pro_nonce'
     * - 'download_link'
     * (Linked license on the website 'api_url' to this site)
     *
     * Echoes array [success]
     */
    public function process_ajax_install_plugin()
    {
		$message = '';

	    if ( !current_user_can('manage_options') ) {
		    return [
			    'success' => false,
			    'message' => $message,
		    ];
	    }

        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['download_link']) ) {

            $download_link = esc_url_raw($_GET['download_link']);
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            $skin     = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            $result   = $upgrader->install( $download_link );

            if ( $result ) {
                $response = [
                    'success' => true,
                ];
            } else {
	            if ( is_wp_error($result) ){
		            $message = $result->get_error_message();
	            }
                $response = [
                    'success' => false,
	                'message' => $message,
                ];
            }

            $response = json_encode($response);
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
    }


    /**
     * Ajax GET request
     *
     * Do an API request to get the download link where to download the pro package
     *
     * Requires from GET:
     * - 'token' => wp_nonce 'upgrade_to_pro_nonce'
     * - 'plugin' (This will set $this->slug (Ex. 'really-simple-ssl-pro/really-simple-ssl-pro.php'), based on which plugin)
     *
     * Echoes array [success]
     */
    public function process_ajax_activate_plugin()
    {
	    if ( !current_user_can('manage_options') ) {
		    return false;
	    }

        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['plugin']) ) {

            $result = activate_plugin( $this->slug );

            if ( !is_wp_error($result) ) {
                $response = [
                    'success' => true,
                ];
            } else {
                $response = [
                    'success' => false,
                ];
            }
            $response = json_encode($response);
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
    }

}
}
