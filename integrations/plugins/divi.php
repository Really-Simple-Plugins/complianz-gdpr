<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

add_filter( 'cmplz_known_script_tags', 'cmplz_divi_map_script' );
function cmplz_divi_map_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'maps.googleapis.com',
			'cmplz_divi_init_map'
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'et_pb_map',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
				'maps.googleapis.com' => 'cmplz_divi_init_map',
		],
	);
	return $tags;
}

/**
 */

function cmplz_divi_whitelist($tags){
	$tags[] = 'et_animation_data';
	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_divi_whitelist');

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_divi_map_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_divi_map_detected_services' );

/**
 * Initialize Novo Map
 *
 */

function cmplz_divi_init_maps() {
	ob_start();
	?>
	<script>
			cmplz_divi_init_map();
			function cmplz_divi_init_map() {
				if ('undefined' === typeof window.jQuery || 'undefined' === typeof window.et_pb_map_init ) {
					setTimeout(cmplz_divi_fire_domContentLoadedEvent, 500);
				} else {
					let map_container = jQuery(".et_pb_map_container");
					map_container.each(function () {
						window.et_pb_map_init(jQuery(this));
					})
				}
			}
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script );
}
add_action( 'wp_enqueue_scripts', 'cmplz_divi_init_maps',PHP_INT_MAX );
