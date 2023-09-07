<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_hcaptcha_script' );
function cmplz_hcaptcha_script( $tags ) {
	$tags[] = array(
		'name' => 'hcaptcha',
		'category' => 'marketing',
		'urls' => array(
			'hcaptcha.com',
			'hcaptcha.js',
		),
	);

	return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_hcaptcha_detected_services( $services ) {
	if ( ! in_array( 'hcaptcha', $services ) ) {
		$services[] = 'hcaptcha';
	}

	return $services;
}

add_filter( 'cmplz_detected_services',
	'cmplz_hcaptcha_detected_services' );


if (cmplz_integration_plugin_is_enabled( 'wpforms' )) {

add_action( 'cmplz_banner_css', 'cmplz_hcaptcha_css' );
function cmplz_hcaptcha_css() {
?>
div.wpforms-container-full .wpforms-form .h-captcha[data-size="normal"], .h-captcha[data-size="normal"] {
display: none;
}
.cmplz-marketing .h-captcha {
display: block!important;
}
<?php
}
}
