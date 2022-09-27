<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Add activecampaign track event
 */
function cmplz_activecampaign_event() {
	 ?>
    <script>
        document.addEventListener("cmplz_fire_categories", function (e) {
            var consentedCategories = e.detail.categories;
                if (cmplz_in_array( 'marketing', consentedCategories )) {
                vgo('process', 'allowTracking');
        }
    });
</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_activecampaign_event' );


/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */
function cmplz_activecampaign_plugin_detected_services( $services ) {

	if ( ! in_array( 'activecampaign', $services ) ) {
		$services[] = 'activecampaign';
	}

	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_activecampaign_plugin_detected_services' );
