<?php
# No need for the template engine
define( 'WP_USE_THEMES', false );

#find the base path
define( 'BASE_PATH', find_wordpress_base_path() . "/" );

# Load WordPress Core
require_once( BASE_PATH . 'wp-load.php' );
require_once( BASE_PATH . 'wp-includes/class-phpass.php' );
require_once( BASE_PATH . 'wp-admin/includes/image.php' );

if ( isset( $_GET['nonce'] ) ) {
	$nonce = $_GET['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'cmplz_csv_nonce' ) ) {
		die( "1 invalid command" );
	}
} else {
	die( "2 invalid command" );
}

if ( ! is_user_logged_in() ) {
	die( "no permission here, invalid command" );
}

function array_to_csv_download(
	$array, $filename = "export.csv", $delimiter = ";"
) {
	header( 'Content-Type: application/csv;charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
	//fix ö ë etc character encoding issues:
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	// open the "output" stream
	// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
	$f = fopen( 'php://output', 'w' );

	foreach ( $array as $line ) {
		fputcsv( $f, $line, $delimiter );
	}
}

$file_title = "cmplz-export-" . date( "j" ) . " " . __( date( "F" ) ) . " "
              . date( "Y" );
array_to_csv_download(
	export_array(),
	$file_title . ".csv"
);

function export_array() {

	$users = COMPLIANZ::$DNSMPD->get_users( array(
		'orderby' => 'ID',
		'order'   => 'DESC'
	) );

	$output = array();
	$output[] = array(
		__( "Name", 'complianz-gdpr' ),
		__( "Email", 'complianz-gdpr' )
	);
	foreach ( $users as $user ) {
		$output[] = array( $user->name, $user->email );
	}

	return $output;
}


function find_wordpress_base_path() {
	$dir = dirname( __FILE__ );
	do {
		//it is possible to check for other files here
		if ( file_exists( $dir . "/wp-config.php" ) ) {
			return $dir;
		}
	} while ( $dir = realpath( "$dir/.." ) );

	return null;
}
