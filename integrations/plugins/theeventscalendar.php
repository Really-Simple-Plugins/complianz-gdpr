<?php
add_filter( 'cmplz_known_script_tags', 'cmplz_theeventscalendar_script' );
function cmplz_theeventscalendar_script( $tags ) {
	$tags[] = array(
		'name' => 'google-maps',
		'category' => 'marketing',
		'placeholder' => 'google-maps',
		'urls' => array(
			'the-events-calendar/src/resources/js/embedded-map.',
		),
		'enable_placeholder' => '1',
		'placeholder_class' => 'tribe-events-venue-map',
		'enable_dependency' => '1',
		'dependency' => [
			//'wait-for-this-script' => 'script-that-should-wait'
			'maps.googleapis.com' => 'the-events-calendar/src/resources/js/embedded-map.',
		],
	);
	return $tags;
}
