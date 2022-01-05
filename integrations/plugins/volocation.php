<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_volocation_script' );
function cmplz_volocation_script( $tags ) {
	$tags[] = array(
			'name' => 'google-maps',
			'category' => 'marketing',
			'placeholder' => 'google-maps',
			'urls' => array(
					'maps.googleapis.com',
					'markerclusterer.js',
					'locator.js',
			),
			'enable_placeholder' => '1',
			'placeholder_class' => 'voslpmapcontainer',
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

function cmplz_volocation_detected_services( $services ) {
	if ( ! in_array( 'google-maps', $services ) ) {
		$services[] = 'google-maps';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_volocation_detected_services' );

/**
 * Hide element based on consent
 */

add_action( 'cmplz_banner_css', 'cmplz_volocation_css' );
function cmplz_volocation_css() {
	?>
		.cmplz-functional .voslpsearch,  .cmplz-functional .col-lg-8,
		.cmplz-functional #maplist .col-lg-3.overflowscroll {
			display:none;
		}
		.cmplz-functional.cmplz-google-maps .voslpsearch,  .cmplz-functional.cmplz-google-maps .col-lg-8 ,
	    .cmplz-functional.cmplz-google-maps #maplist .col-lg-3.overflowscroll {
				display:block;
			}

		.cmplz-functional.cmplz-marketing .voslpsearch,  .cmplz-marketing .col-lg-8 ,
		.cmplz-functional.cmplz-marketing #maplist .col-lg-3.overflowscroll {
			display:none;
		}
	<?php
}
