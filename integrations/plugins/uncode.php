<?php

/* Uncode theme Google Maps integration */

function cmplz_uncode_googlemaps_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'uncode.gmaps.min.js',
			'maps.googleapis.com'
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'uncode-map-wrapper',
		'enable_dependency' => '1',
		'dependency' => [
			'maps.googleapis.com' => 'uncode.gmaps.min.js',
		],
	);
	return $tags;
}

add_filter( 'cmplz_known_script_tags', 'cmplz_uncode_googlemaps_script' );

/**
 * Trigger the DomContentLoaded event
 * This is not always needed, but in a plugin initializes on document load or ready, the map won't show on consent because this event already ran.
 * This will re-trigger that.
 *
 */

function cmplz_uncode_maps_initDomContentLoaded() {
	ob_start();
	?>
	<script>
        document.addEventListener("cmplz_run_after_all_scripts", cmplz_fire_domContentLoadedEvent);
        function cmplz_fire_domContentLoadedEvent() {
            dispatchEvent(new Event('load'));
        }
	</script>
	<?php
	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script );
}
add_action( 'wp_enqueue_scripts', 'cmplz_uncode_maps_initDomContentLoaded',PHP_INT_MAX );


/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_uncode_maps_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_uncode_maps_detected_services' );
?>