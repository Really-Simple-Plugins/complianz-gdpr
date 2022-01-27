<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( !defined('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE') ) define('CMPLZ_GOOGLE_MAPS_INTEGRATION_ACTIVE', true);

add_filter( 'cmplz_known_script_tags', 'cmplz_flexible_map_script' );
function cmplz_flexible_map_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'maps.google.com',
			'flexible-map.min.js',
			'flexible-map.js',
			'FlexibleMap'
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'flxmap-container',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.google.com' => 'flexible-map',
			'flexible-map' => 'FlexibleMap',
		],
	);
	return $tags;
}

/**
 * Initialize Novo Map
 *
 */

function cmplz_flexible_map_initDomContentLoaded() {
	ob_start();
	?>
	<script>
		document.addEventListener("cmplz_run_after_all_scripts", cmplz_novo_fire_domContentLoadedEvent);
		function cmplz_novo_fire_domContentLoadedEvent() {
			window.document.dispatchEvent(new Event("DOMContentLoaded", {
				bubbles: true,
				cancelable: true
			}));
		}
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script );
}
add_action( 'wp_enqueue_scripts', 'cmplz_flexible_map_initDomContentLoaded',PHP_INT_MAX );

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_flexible_map_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_flexible_map_detected_services' );
