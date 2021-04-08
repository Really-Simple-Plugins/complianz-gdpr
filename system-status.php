<?php
# No need for the template engine
define( 'WP_USE_THEMES', false );
//we set wp admin to true, so the backend features get loaded.
if (!defined('CMPLZ_DOING_SYSTEM_STATUS')) define( 'CMPLZ_DOING_SYSTEM_STATUS' , true);

#find the base path
define( 'BASE_PATH', find_wordpress_base_path()."/" );

# Load WordPress Core
require_once( BASE_PATH . 'wp-load.php' );
require_once( BASE_PATH . 'wp-includes/class-phpass.php' );
require_once( BASE_PATH . 'wp-admin/includes/image.php' );
require_once( BASE_PATH . 'wp-admin/includes/plugin.php');

if ( current_user_can( 'manage_options' ) ) {

	ob_start();

	echo 'Domain:' . esc_url_raw( site_url() ) . "\n";
	echo 'jQuery: ' . get_option('cmplz_detected_missing_jquery') ? 'No jquery found on site' : 'Successfully detected jquery';
	echo "\n";
	$console_errors = cmplz_get_console_errors();
	if (empty($console_errors)) $console_errors = "none found";
	echo 'Detected console errors: ' . $console_errors . "\n". "\n";

	echo "General\n";
	echo "Plugin version: " . cmplz_version . "\n";
	global $wp_version;
	echo "WordPress version: " . $wp_version . "\n";
	echo "PHP version: " . PHP_VERSION . "\n";
	echo "Server: " . cmplz_get_server() . "\n";
	$multisite = is_multisite() ? 'yes' : 'no';
	echo "Multisite: " . $multisite . "\n";
	echo "\n";
	$plugins         = get_option( 'active_plugins' );
	echo "Active plugins: " . "\n";
	echo implode( "\n", $plugins ) . "\n";

	$settings = get_option( 'complianz_options_settings' );
	echo "\n"."General settings" . "\n";
	echo implode_array_recursive($settings);

	$wizard   = get_option( 'complianz_options_wizard' );
	echo "\n\n"."Wizard settings" . "\n";
	$t = array_keys($wizard);
	echo implode_array_recursive($wizard);
	do_action( "cmplz_system_status" );

	$content = ob_get_clean();


	if ( function_exists( 'mb_strlen' ) ) {
		$fsize = mb_strlen( $content, '8bit' );
	} else {
		$fsize = strlen( $content );
	}
	$file_name = 'complianz-system-status.txt';
	header( "Content-type: application/octet-stream" );

	//direct download
	header( "Content-Disposition: attachment; filename=\"" . $file_name . "\"" );

	//open in browser
	header( "Content-length: $fsize" );
	header( "Cache-Control: private", false ); // required for certain browsers
	header( "Pragma: public" ); // required
	header( "Expires: 0" );
	header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
	header( "Content-Transfer-Encoding: binary" );

	echo $content;

} else {
	//should not be here, so redirect to home
	wp_redirect( home_url() );
	exit;
}

/**
 * Get the WP Base path
 * @return false|string|null
 */
function find_wordpress_base_path() {
	$dir = dirname(__FILE__);
	do {
		if( file_exists($dir."/wp-config.php") ) {
			if (file_exists($dir."/current")){
				return $dir.'/current';
			} else {
				return $dir;
			}
		}
	} while( $dir = realpath("$dir/..") );
	return null;
}

/**
 * Generate a readable string from an array
 * @param array $array
 *
 * @return string
 */

function implode_array_recursive($array) {

	return implode("\n", array_map(
		function ($v, $k) {
			if (is_array($v)){
				$output = implode_array_recursive($v);
			} else {
				$output = sprintf("%s : %s", $k, $v);
			}
			return $output;
		},
		$array,
		array_keys($array)
	));
}
