<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

/**
 * WP Google Maps, should not be blocked the usual way as we use it's integrated GDPR feature
 *
 * This is server side, so doesn't play nice with caching
 */

/**
 * replace the wp google maps gdpr notice with our own, nice looking one.
 *
 * @param $html
 *
 * @return string
 */

function cmplz_wp_google_maps_replace_gdpr_notice( $html ) {
	$img = cmplz_default_placeholder( 'google-maps' );
	$msg = '<div style="text-align:center;margin-bottom:15px">' . __( 'To enable Google Maps cookies, click on I Agree', "complianz-gdpr" ) . '</div>';
	return apply_filters( 'cmplz_wp_google_maps_html', '<img src="' . $img . '" style="margin-bottom:15px">' . $msg );
}
add_filter( 'wpgmza_gdpr_notice_html', 'cmplz_wp_google_maps_replace_gdpr_notice' );

function cmplz_wp_google_maps_whitelist($tags){
	$tags[] = 'WPGMZA_localized_data';
	$tags[] = 'maps.google.com';

	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_wp_google_maps_whitelist');

/**
 * Declare a placeholder
 *
 */
function cmplz_wp_google_maps_placeholder(){}
/**
 * Add some custom css for the placeholder
 */

add_action( 'cmplz_banner_css', 'cmplz_wp_google_maps_css' );
function cmplz_wp_google_maps_css() {
	?>
		.wpgmza-gdpr-compliance {
			text-align: center;
		}
	<?php
}

/**
 * v: 6.0
 * reload after consent.
 */
function cmplz_reload_after_consent() {
	?>
	<script>
		document.addEventListener('cmplz_status_change', function (e) {
			if ( document.querySelector('.wpgmza-api-consent') ) {
				if (e.detail.category === 'marketing' && e.detail.value === 'allow') {
					location.reload();
				}
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_reload_after_consent' );

/**
 * Make sure the agree button accepts the complianz banner
 */

function cmplz_wp_google_maps_js() {
		ob_start();
		?>
		<script>
			setTimeout(function () {
				if ( document.querySelector('.wpgmza-api-consent') ) {
					document.querySelector('.wpgmza-api-consent').classList.add('cmplz-accept-marketing');
				}
			}, 2000);
		</script>
		<?php
		$script = ob_get_clean();
		$script = str_replace(array('<script>', '</script>'), '', $script);
		wp_add_inline_script( 'cmplz-cookiebanner', $script);
}
add_action( 'wp_enqueue_scripts', 'cmplz_wp_google_maps_js',PHP_INT_MAX );

/**
 * Force the GDPR option to be enabled
 *
 * @param $settings
 *
 * @return mixed
 */
function cmplz_wp_google_maps_settings() {
	if ( is_admin() && current_user_can( 'manage_options' ) ) {
		$settings = json_decode( get_option( 'wpgmza_global_settings' ) );
		if ( property_exists($settings, 'wpgmza_gdpr_require_consent_before_load') && $settings->wpgmza_gdpr_require_consent_before_load === 'on' ) {
			return;
		}
		$settings->wpgmza_gdpr_require_consent_before_load = 'on';
		update_option( 'wpgmza_global_settings', json_encode( $settings ) );
	}
}
add_action( 'admin_init', 'cmplz_wp_google_maps_settings' );


/**
 * Add cookie that should be set on consent
 *
 * @param $cookies
 *
 * @return mixed
 */


function cmplz_wp_google_maps_add_cookie( $cookies ) {
	$cookies['wpgmza-api-consent-given'] = array( '1', 0 );
	return $cookies;
}
add_filter( 'cmplz_set_cookies_on_consent', 'cmplz_wp_google_maps_add_cookie' );

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_wp_google_maps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_wp_google_maps_detected_services' );


