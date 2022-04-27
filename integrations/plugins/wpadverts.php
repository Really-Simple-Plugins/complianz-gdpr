<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( cmplz_uses_thirdparty('google-maps') ) {

  function is_adverts_add_page(){
    global $post;
    if ( $post && has_shortcode($post->post_content, 'adverts_add')) {
        return true;
    }
    return false;
  }

  function cmplz_wpadverts_reload_after_consent() {
      ?>
      <script>
      if ( document.querySelector('.wpadverts-mal-full-map-container') ) {

          document.addEventListener('cmplz_status_change', function (e) {
              if (e.detail.category === 'marketing' && e.detail.value==='allow') {
                  location.reload();
              }
          });
          document.addEventListener('cmplz_status_change_service', function (e) {
              if ( e.detail.value ) {
                  location.reload();
              }
          });
  }
      </script>
      <?php
  }
  add_action( 'wp_footer', 'cmplz_wpadverts_reload_after_consent' );

  function cmplz_custom_wpadverts_googlemaps_script( $tags ) {
  	if( is_singular( "advert" ) ) {
  		// if the map is on the ad details page, use map-single
  		$tags[] = array(
  			'name' => 'google-maps',
  			'category' => 'marketing',
  			'placeholder' => 'google-maps',
  			'urls' => array(
  				'maps.googleapis.com',
  				'map-single.js',
  			),
  			'enable_placeholder' => '1',
  			'placeholder_class' => 'adverts-single-grid-details',
  			'enable_dependency' => '1',
  			'dependency' => [
  				//'wait-for-this-script' => 'script-that-should-wait'
  				'maps.googleapis.com' => 'map-single.js',
  			],
  		);
  		return $tags;
  } else if ( is_adverts_add_page() ){
  		// adverts add page, only block maps api and autocomplete
  		$tags[] = array(
  			'name' => 'google-maps',
  			'category' => 'marketing',
  			'placeholder' => 'google-maps',
  			'urls' => array(
              	'maps.googleapis.com',
  				'locate-autocomplete.js',
  			),
  			'enable_placeholder' => '0',
  			'placeholder_class' => 'wpadverts-mal-map',
  			'enable_dependency' => '1',
  			'dependency' => [
  				//'wait-for-this-script' => 'script-that-should-wait'
  				'maps.googleapis.com' => 'locate-autocomplete.js',
  			],
  		);
  	return $tags;
  	} else {
  		// other page, the multi marker map.
    		// in this case we reload after consent, due to multiple dependencies.
          $tags[] = array(
  			'name' => 'google-maps',
  			'category' => 'marketing',
  			'placeholder' => 'google-maps',
  			'urls' => array(
  				'maps.googleapis.com',
  				'map-icons.js',
  				'infobox.js',
  				'map-complete.js',
  				'wpadverts_mal_locate',
  			),
  			'enable_placeholder' => '1',
  			'placeholder_class' => 'wpadverts-mal-map',
  			'enable_dependency' => '1',
  			'dependency' => [
  				//'wait-for-this-script' => 'script-that-should-wait'
  				'maps.googleapis.com' => 'map-icons.js',
  				'map-icons.js' => 'infobox.js',
  				'infobox.js' => 'map-complete.js',
  			],
  		);
  		return $tags;
  	}
  }
  add_filter( 'cmplz_known_script_tags', 'cmplz_custom_wpadverts_googlemaps_script' );
}
