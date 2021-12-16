<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Allows plugins to use their own update API.
 *
 * @author Easy Digital Downloads
 * @version 1.7
 */
class rsp_upgrade_to_pro {

    // Version for css and js files
    private $version     = "1.0";

    // Change these values to correspond to values for this plugin
    private $api_url     = "";
    private $license     = "";
    private $item_id     = "";
    private $pro_prefix  = "";
    private $slug        = "";
    private $health_check_timeout = 5;

    /**
     * Class constructor.
     *
     */
    public function __construct() {

        if ( isset($_GET['license']) ) {
            $this->license = esc_html(sanitize_title($_GET['license']));
        }

        if ( isset($_GET['item_id']) ) {
            $this->item_id = esc_html(sanitize_title($_GET['item_id']));
        }

        if ( isset($_GET['api_url']) ) {
            $this->api_url = esc_url_raw($_GET['api_url']);
        }

        if ( isset($_GET['plugin']) ) {
            $plugin = esc_html(sanitize_title($_GET['plugin']));
            switch ($plugin) {
                case "rsssl_pro":
                    $this->pro_prefix = "rsssl_pro_";
                    $this->slug = "really-simple-ssl-pro/really-simple-ssl-pro.php";
                    break;
                case "cmplz_pro":
                    $this->pro_prefix = "cmplz_";
                    $this->slug = "complianz/complianz-gpdr-premium.php";
                    break;
                case "brst_pro":
                    $this->pro_prefix = "brst_pro_";
                    $this->slug = "burst";
                    break;
            }
        }

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
            wp_register_style( 'rsp-upgrade-css', plugin_dir_url(__FILE__) . 'upgrade-to-pro.css', false, $this->version.time() );
            wp_enqueue_style( 'rsp-upgrade-css' );

            wp_enqueue_script( 'rsp-ajax-js', plugin_dir_url(__FILE__) . "ajax.js", array(), $this->version.time(), true );
            wp_enqueue_script( 'rsp-upgrade-js', plugin_dir_url(__FILE__) . "upgrade-to-pro.js", array(), $this->version.time(), true );
            wp_localize_script(
                'rsp-upgrade-js',
                'rsp_upgrade',
                array(
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
            return;
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
        if ( is_admin() && isset($_GET['install-pro']) && isset($_GET['license']) && isset($_GET['item_id']) && isset($_GET['api_url']) && isset($_GET['plugin']) ) {
            ?>
            <div
                class="install-pro-steps"
                style="
                max-height: calc(100vh - 20px);
                position: fixed;
                left: 50%;
                top: 50%;
                -ms-transform: translateX(-50%) translateY(-50%);
                transform: translateX(-50%) translateY(-50%);
                width: 400px;
                height: 400px;
                padding: 10px;
                background-color: white;
                border: 1px solid black;
                ">
                <span>Installing Pro ...</span>
                <div class="progress-bar-container">
                    <div class="progress rsp-grey">
                        <div class="bar rsp-green" style="width:0%"></div>
                    </div>
                </div>
                <div class="install-step step-destination-clear">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Checking if destination for plugin is clear", "really-simple-ssl") ?></span>
                    </div>
                </div>
                <div class="install-step step-activate-license">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Activate license", "really-simple-ssl") ?></span>
                    </div>
                </div>
                <div class="install-step step-package-information">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Get package information", "really-simple-ssl") ?></span>
                    </div>
                </div>
                <div class="install-step step-install-plugin">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Install plugin", "really-simple-ssl") ?></span>
                    </div>
                </div>
                <div class="install-step step-activate-plugin">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Activate plugin", "really-simple-ssl") ?></span>
                    </div>
                </div>
                <div class="install-step step-activate-license-plugin">
                    <div class="step-color">
                        <div class="rsp-grey rsp-bullet"></div>
                    </div>
                    <div class="step-text">
                        <span><?php echo __("Activate license plugin", "really-simple-ssl") ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
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
    function activate_license( $license, $item_id ) {

        $message = "";

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
                $message = __( 'An error occurred, please try again.' );
            }

        } else {

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if ( false === $license_data->success ) {

                switch( $license_data->error ) {

                    case 'expired' :

                        $message = sprintf(
                            __( 'Your license key expired on %s.' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                        );
                        break;

                    case 'disabled' :
                    case 'revoked' :

                        $message = __( 'Your license key has been disabled.' );
                        break;

                    case 'missing' :

                        $message = __( 'Invalid license.' );
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __( 'Your license is not active for this URL.' );
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), EDD_SAMPLE_ITEM_NAME );
                        break;

                    case 'no_activations_left':

                        update_site_option("{$this->pro_prefix}license_activations_left", 0);
                        $message = __( 'Your license key has reached its activation limit.' );
                        break;

                    default :

                        $message = __( 'An error occurred, please try again.' );
                        break;
                }

            } else {

                if ( isset($license_data->expires) ) {
                    $date = $license_data->expires;
                    if ( $date !== 'lifetime' ) {
                        if (!is_numeric($date)) $date = strtotime($date);
                        $date = date(get_option('date_format'), $date);
                    }
                    update_site_option("{$this->pro_prefix}license_expires", $date);
                }

                if ( isset($license_data->license_limit) ) update_site_option("{$this->pro_prefix}license_activation_limit", $license_data->license_limit);
                if ( isset($license_data->activations_left) ) update_site_option("{$this->pro_prefix}license_activations_left", $license_data->activations_left);

            }

        }

        if ( empty($message) ) {
            $response = [
                'status' => $license_data->license,
                'message' => "",
            ];

            set_site_transient("{$this->pro_prefix}license_status", $license_data->license, WEEK_IN_SECONDS);
        } else {
            $response = [
                'status' => "error",
                'message' => $message,
            ];

            set_site_transient("{$this->pro_prefix}license_status", 'error', WEEK_IN_SECONDS);
        }

        return $response;
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
        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['plugin']) ) {

            if ( !file_exists(WP_PLUGIN_DIR . '/' . $this->slug) ) {
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
        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['license']) && isset($_GET['item_id']) && isset($_GET['api_url']) ) {

            $license  = sanitize_title($_GET['license']);
            $item_id  = intval($_GET['item_id']);

            $response = $this->activate_license($license, $item_id);

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
     * - 'license'
     * - 'item_id'
     * - 'api_url'
     *
     * Echoes array [success, download_link]
     */
    public function process_ajax_package_information()
    {
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
        if ( isset($_GET['token']) && wp_verify_nonce($_GET['token'], 'upgrade_to_pro_nonce') && isset($_GET['plugin']) ) {

            $result = activate_plugin( $this->slug );

            error_log(print_r($result, true));
            error_log("Plugins");
            error_log(print_r(get_plugins(), true));

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