<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );
if ( is_admin() ) {
	require_once( 'integrations-menu.php' );
}
require_once( 'forms.php' );

if ( is_admin() ) {
	require_once( 'TGM/required.php' );
}

global $cmplz_integrations_list;
$cmplz_integrations_list = apply_filters( 'cmplz_integrations', array(

	'nudgify'          => array(
		'constant_or_function' => 'NUDGIFY_PLUGIN_VERSION',
		'label'                => 'Nudgify',
		'firstparty_marketing' => false,
	),

	'trustpulse'          => array(
		'constant_or_function' => 'TRUSTPULSE_PLUGIN_VERSION',
		'label'                => 'TrustPulse',
		'firstparty_marketing' => false,
	),

	'addtoany'          => array(
		'constant_or_function' => 'A2A_SHARE_SAVE_init',
		'label'                => 'Add To Any',
		'firstparty_marketing' => false,
	),

	'amp'               => array(
		'constant_or_function' => 'AMP__VERSION',
		'label'                => 'AMP (official AMP plugin for WordPress)',
		'firstparty_marketing' => false,
	),

	'podcast-player'         => array(
		'constant_or_function' => 'PODCAST_PLAYER_VERSION',
		'label'                => 'Podcast Player',
		'firstparty_marketing' => false,
	),

	'google-maps-easy'               => array(
		'constant_or_function' => 'toeGetClassNameGmp',
		'label'                => 'Google Maps Easy',
		'firstparty_marketing' => false,
	),

	'activecampaign'               => array(
		'constant_or_function' => 'ACTIVECAMPAIGN_URL',
		'label'                => 'Active Campaign',
		'firstparty_marketing' => true,
	),

	'google-site-kit'               => array(
		'constant_or_function' => 'GOOGLESITEKIT_VERSION',
		'label'                => 'Google Site Kit',
	),

	'beehive'           => array(
		'constant_or_function' => 'BEEHIVE_PRO',
		'label'                => 'Beehive',
		'firstparty_marketing' => false,
	),

	'simple-business-directory' => array(
		'constant_or_function' => 'QCSBD_DIR',
		'label'                => 'Simple Business Directory',
		'firstparty_marketing' => false,
	),

	'acf'           => array(
		'constant_or_function' => 'ACF',
		'label'                => 'Advanced Custom Fields',
		'firstparty_marketing' => false,
	),

	'pixelyoursite'     => array(
		'constant_or_function' => 'PYS_FREE_VERSION',
		'label'                => 'PixelYourSite',
		'firstparty_marketing' => false,
	),

	'user-registration' => array(
		'constant_or_function' => 'UR',
		'label'                => 'User Registration',
		'firstparty_marketing' => false,
	),

	'contact-form-7'    => array(
		'constant_or_function' => 'WPCF7_VERSION',
		'label'                => 'Contact Form 7',
		'firstparty_marketing' => false,
	),

	'facebook-for-wordpress' => array(
		'constant_or_function' => 'FacebookPixelPlugin\\FacebookForWordpress',
		'label'                => 'Official Facebook Pixel',
		'firstparty_marketing' => false,
	),

	'facebook-for-woocommerce' => array(
		'constant_or_function' => 'facebook_for_woocommerce',
		'label'                => 'Facebook for WooCommerce',
		'firstparty_marketing' => false,
	),

	'google-tagmanager-for-wordpress' => array(
		'constant_or_function' => 'GTM4WP_VERSION',
		'label'                => 'Google Tag Manager for WordPress',
		'firstparty_marketing' => false,
	),

	'jetpack' => array(
		'constant_or_function' => 'JETPACK__VERSION',
		'label'                => 'JetPack',
		'firstparty_marketing' => false,
	),

	'g1-gmaps' => array(
		'constant_or_function' => 'G1_GMaps',
		'label'                => 'G1 GMAPS',
		'firstparty_marketing' => false,
	),

	'monsterinsights' => array(
		'constant_or_function' => 'MonsterInsights',
		'label'                => 'MonsterInsights',
		'firstparty_marketing' => false,
	),

	'mappress' => array(
		'constant_or_function' => 'Mappress',
		'label'                => 'MapPress Maps for WordPress',
		'firstparty_marketing' => false,
	),

	'map-multi-marker' => array(
		'constant_or_function' => 'MapMultiMarker',
		'label'                => 'Map Multi Marker',
		'firstparty_marketing' => false,
	),

	'caos-host-analytics-local' => array(
		'constant_or_function' => 'CAOS_STATIC_VERSION',
		'label'                => 'CAOS host analytics locally',

		'firstparty_marketing' => false,
	),
	'wp-google-maps'            => array(
		'constant_or_function' => 'WPGMZA_VERSION',
		'label'                => 'WP Google Maps',
		'firstparty_marketing' => false,
	),

	'geo-my-wp' => array(
		'constant_or_function' => 'GMW_VERSION',
		'label'                => 'Geo My WP',
		'firstparty_marketing' => false,
	),

	'google-analytics-dashboard-for-wp' => array(
		'constant_or_function' => 'EXACTMETRICS_VERSION',
		'label'                => 'Google Analytics Dashboard for WP',
		'firstparty_marketing' => false,
	),

	'wp-google-maps-widget' => array(
		'constant_or_function' => 'GMW_PLUGIN_DIR',
		'label'                => 'Maps Widget for Google Maps',
		'firstparty_marketing' => false,
	),

	'wp-donottrack' => array(
		'constant_or_function' => 'wp_donottrack_config',
		'label'                => 'WP Do Not Track',
		'firstparty_marketing' => false,
	),

	'pixel-caffeine'   => array(
		'constant_or_function' => 'AEPC_PIXEL_VERSION',
		'label'                => 'Pixel Caffeine',
		'firstparty_marketing' => false,
	),

	'rate-my-post'   => array(
		'constant_or_function' => 'RATE_MY_POST_VERSION',
		'label'                => 'Rate My Post',
		'firstparty_marketing' => false,
	),

	'super-socializer' => array(
		'constant_or_function' => 'THE_CHAMP_SS_VERSION',
		'label'                => 'Super Socializer',
		'firstparty_marketing' => false,
	),

	'tidio-live-chat'  => array(
		'constant_or_function' => 'TIDIOCHAT_VERSION',
		'label'                => 'Tidio Live Chat',
		'firstparty_marketing' => false,
	),

	'instagram-feed'   => array(
		'constant_or_function' => 'SBIVER',
		'label'                => 'Smash Balloon Instagram Feed',
		'firstparty_marketing' => false,
	),

	'facebook-feed'   => array(
		'constant_or_function' => 'CFFVER',
		'label'                => 'Smash Balloon Facebook Feed',
		'firstparty_marketing' => false,
	),

	'twitter-feed'   => array(
		'constant_or_function' => 'CTF_VERSION',
		'label'                => 'Smash Balloon Twitter Feed',
		'firstparty_marketing' => false,
	),

	'sumo'             => array(
		'constant_or_function' => 'SUMOME__PLUGIN_DIR',
		'label'                => 'Sumo – Boost Conversion and Sales',
		'firstparty_marketing' => false,
	),

	'wpforms'          => array(
		'constant_or_function' => 'wpforms',
		'label'                => 'WP Forms',
		'firstparty_marketing' => false,
	),

	'wp-rocket' => array(
		'constant_or_function' => 'WP_ROCKET_VERSION',
		'label'                => 'WP Rocket',
		'firstparty_marketing' => false,
	),

	'forminator' => array(
		'constant_or_function' => 'FORMINATOR_VERSION',
		'label'                => 'Forminator',
		'early_load'           => 'forminator-addon-registration.php',
		'callback_condition'   => array(
			'regions' => array( 'eu', 'uk' ),
		),
		'firstparty_marketing' => false,

	),

	'happyforms' => array(
		'constant_or_function' => 'HAPPYFORMS_VERSION',
		'label'                => 'Happy Forms',
		'firstparty_marketing' => false,
	),

	'osm' => array(
		'constant_or_function' => 'OSM_PLUGIN_URL',
		'label'                => 'OSM - OpenStreetMap',
		'firstparty_marketing' => false,
	),

	'so-widgets-bundle' => array(
		'constant_or_function' => 'SOW_BUNDLE_VERSION',
		'label'                => 'SiteOrigin Widgets Bundle',
		'firstparty_marketing' => false,
	),

	'gravity-forms' => array(
		'constant_or_function' => 'GF_MIN_WP_VERSION',
		'label'                => 'Gravity Forms',
		'callback_condition'   => array(
			'privacy-statement' => 'generated',
			'regions'           => 'eu',
		),
		'firstparty_marketing' => false,
	),
) );


require_once( 'fields.php' );

/**
 * Wordpress, include always
 */
require_once( 'wordpress/wordpress.php' );


foreach ( $cmplz_integrations_list as $plugin => $details ) {

	if ( ! isset( $details['early_load'] ) ) {
		continue;
	}
	if ( ! file_exists( WP_PLUGIN_DIR . "/" . $plugin . "/" . $plugin
	                    . ".php" )
	) {
		continue;
	}

	$early_load = $details['early_load'];
	$file       = apply_filters( 'cmplz_early_load_path',
		cmplz_path . "integrations/plugins/$early_load", $details );

	if ( file_exists( $file ) ) {
		require_once( $file );
	} else {
		error_log( "searched for $plugin integration at $file, but did not find it" );
	}
}


/**
 * Check if this plugin's integration is enabled
 *
 * @return bool
 */
function cmplz_is_integration_enabled( $plugin_name ) {
	global $cmplz_integrations_list;
	if ( ! array_key_exists( $plugin_name, $cmplz_integrations_list ) ) {
		return false;
	}
	$fields = get_option( 'complianz_options_integrations' );
	//default enabled, which means it's enabled when not set.
	if ( isset( $fields[ $plugin_name ] ) && $fields[ $plugin_name ] != 1 ) {
		return false;
	}

	return true;
}

/**
 * Check if a plugin from the integrations list is active
 * @param $plugin
 *
 * @return bool
 */
function cmplz_integration_plugin_is_active( $plugin ){

	global $cmplz_integrations_list;
	if ( !isset($cmplz_integrations_list[ $plugin ]) ) return false;

	//because we need a default, we don't use the get_value from complianz. The fields array is not loaded yet, so there are no defaults
	$fields = get_option( 'complianz_options_integrations' );
	$details = $cmplz_integrations_list[ $plugin ];
	$enabled = isset( $fields[ $plugin ] ) ? $fields[ $plugin ] : true;
	if ( ( defined( $details['constant_or_function'] )
	       || function_exists( $details['constant_or_function'] )
	       || class_exists( $details['constant_or_function'] ) )
	     && $enabled
	) {
		return true;
	}

	return false;
}

/**
 * code loaded without privileges to allow integrations between plugins and services, when enabled.
 */

function cmplz_integrations() {

	global $cmplz_integrations_list;
	foreach ( $cmplz_integrations_list as $plugin => $details ) {
		if ( cmplz_integration_plugin_is_active( $plugin ) ) {
			$file = apply_filters( 'cmplz_integration_path', cmplz_path . "integrations/plugins/$plugin.php", $plugin );
			if ( file_exists( $file ) ) {
				require_once( $file );
			} else {
				error_log( "searched for $plugin integration at $file, but did not find it" );
			}
		}
	}

	/**
	 * Services
	 */

	$services = COMPLIANZ::$config->thirdparty_service_markers;
	$services = array_keys( $services );

	foreach ( $services as $service ) {
		if ( cmplz_uses_thirdparty( $service ) ) {
			if ( file_exists( cmplz_path
			                  . "integrations/services/$service.php" )
			) {
				require_once( "services/$service.php" );
			}
		}
	}

	$services = COMPLIANZ::$config->social_media_markers;
	$services = array_keys( $services );

	foreach ( $services as $service ) {
		if ( cmplz_uses_thirdparty( $service ) ) {
			if ( file_exists( cmplz_path
			                  . "integrations/services/$service.php" )
			) {
				require_once( "services/$service.php" );
			}
		}
	}

	/**
	 * advertising
	 */

	if ( cmplz_get_value( 'uses_ad_cookies' ) === 'yes' ) {
		require_once( 'services/advertising.php' );
	}

	/**
	 * statistics
	 */

	$statistics = cmplz_get_value( 'compile_statistics' );
	if ( $statistics === 'google-analytics' ) {
		require_once( 'statistics/google-analytics.php' );
	} elseif ( $statistics === 'google-tag-manager' ) {
		require_once( 'statistics/google-tagmanager.php' );
	}

}

add_action( 'plugins_loaded', 'cmplz_integrations', 10 );


/**
 * Check if a third party is used on this site
 *
 * @param string $name
 *
 * @return bool uses_thirdparty
 */

function cmplz_uses_thirdparty( $name ) {
	$thirdparty = ( cmplz_get_value( 'uses_thirdparty_services' ) === 'yes' )
		? true : false;
	if ( $thirdparty ) {
		$thirdparty_types = cmplz_get_value( 'thirdparty_services_on_site' );
		if ( isset( $thirdparty_types[ $name ] )
		     && $thirdparty_types[ $name ] == 1
		) {
			return true;
		}
	}

	$social_media = ( cmplz_get_value( 'uses_social_media' ) === 'yes' ) ? true
		: false;
	if ( $social_media ) {
		$social_media_types = cmplz_get_value( 'socialmedia_on_site' );
		if ( isset( $social_media_types[ $name ] )
		     && $social_media_types[ $name ] == 1
		) {
			return true;
		}
	}

	return false;
}


add_action( 'complianz_after_field', 'cmplz_add_placeholder_checkbox', 9, 1 );
function cmplz_add_placeholder_checkbox( $args ) {
	if ( ! isset( $args['fieldname'] ) || ! isset( $args["type"] )
	     || $args["type"] !== 'checkbox'
	) {
		return;
	}

	if ( isset( $_GET["page"] ) && $_GET["page"] === 'cmplz-script-center' ) {

		$fieldname     = str_replace( "-", "_",
			sanitize_text_field( $args['fieldname'] ) );
		$function_name = $fieldname;

		$has_placeholder
			                   = ( function_exists( "cmplz_{$function_name}_placeholder" ) );
		$disabled_placeholders = get_option( 'cmplz_disabled_placeholders',
			array() );
		$value                 = ! in_array( $fieldname,
			$disabled_placeholders );

		$disabled  = ! $has_placeholder;
		$fieldname = 'cmplz_placeholder_' . $fieldname;

		if ( $args['table'] ) {
			echo '</td><td style="width:70%">';
		} else {
			echo '</div>';
		}
		if ( ! $has_placeholder ) {
			?>
			<label class="cmplz-switch">
				<span class="cmplz-slider-na cmplz-round"></span>
			</label>
			<?php
		} else {
			?>
			<label class="cmplz-switch">
				<input name="<?php echo esc_html( $fieldname ) ?>" type="hidden"
				       value=""/ <?php if ( $disabled ) {
					echo 'disabled';
				} ?>>

				<input name="<?php echo esc_html( $fieldname ) ?>" size="40"
				       type="checkbox"
					<?php if ( $disabled ) {
						echo 'disabled';
					} ?>
					   class="<?php if ( $args['required'] ) {
						   echo 'is-required';
					   } ?>"
					   value="1" <?php checked( 1, $value, true ) ?> />
				<span class="cmplz-slider cmplz-round"></span>
			</label>
			<?php
		}
		if ( $args['table'] ) {
			echo '</td></tr>';
		} else {
			echo '</div>';
		}
	}
}

/**
 * placeholders that are disabled will be removed by hook here.
 *
 * This only applies to the non iframe placeholders. Other placeholders are blocked using the cmplz_placeholder_disabled function
 */

add_action( "plugins_loaded", 'cmplz_unset_placeholder_hooks' );
function cmplz_unset_placeholder_hooks() {
	$disabled_placeholders = get_option( 'cmplz_disabled_placeholders',
		array() );
	foreach ( $disabled_placeholders as $service ) {
		$has_placeholder
			= ( function_exists( "cmplz_{$service}_placeholder" ) );
		if ( $has_placeholder ) {
			remove_filter( 'cmplz_placeholder_markers',
				"cmplz_{$service}_placeholder" );
		}
	}


}

/**
 * check if the placeholder for a service is disabled
 *
 * @param string $service
 *
 * @return bool $disabled
 */

function cmplz_placeholder_disabled( $service ) {
	$disabled_placeholders = get_option( 'cmplz_disabled_placeholders',
		array() );

	if ( in_array( $service, $disabled_placeholders ) ) {
		return true;
	}
	//check also other variation
	$service = str_replace( '-', "_", $service );
	if ( in_array( $service, $disabled_placeholders ) ) {
		return true;
	}

	return false;

}

/**
 * check if we should use placeholders
 *
 * @param string|bool $src
 *
 * @return bool
 */

function cmplz_use_placeholder( $src = false ) {
	if ( cmplz_get_value( 'dont_use_placeholders' ) ) {
		return false;
	}

	if ( ! $src ) {
		return true;
	}


	//no placeholder on facebook like button
	if ( strpos( $src, 'like.php' ) !== false ) {
		return false;
	}

	//get service from $src
	$service = cmplz_get_service_by_src( $src );
	if ( cmplz_placeholder_disabled( $service ) ) {
		return false;
	}

	return true;
}

/**
 * Get a service by string, looking at the src of a frame or script
 *
 * @param string $src
 *
 * @return bool|string
 */

function cmplz_get_service_by_src( $src ) {
	$type = false;

	if ( strpos( $src, 'youtube' ) !== false ) {
		$type = 'youtube';
	} else if ( strpos( $src, 'facebook' ) !== false ) {
		$type = 'facebook';
	} else if ( strpos( $src, 'vimeo' ) !== false ) {
		$type = 'vimeo';
	} else if ( strpos( $src, 'dailymotion' ) !== false ) {
		$type = 'dailymotion';
	} else if ( strpos( $src, 'maps.googleapis' ) !== false ) {
		$type = 'google-maps';
	} else if ( strpos( $src, 'openstreetmaps.org' ) !== false ) {
		$type = 'openstreetmaps';
	} else if ( strpos( $src, 'spotify' ) !== false ) {
		$type = 'spotify';
	} else if ( strpos( $src, 'pinterest' ) !== false ) {
		$type = 'pinterest';
	} else if ( strpos( $src, 'soundcloud' ) !== false ) {
		$type = 'soundcloud';
	} else if ( strpos( $src, 'twitter' ) !== false ) {
		$type = 'twitter';
	} else if ( strpos( $src, 'calendly' ) !== false ) {
		$type = 'calendly';
	}

	if ( ! $type ) {
		$type = COMPLIANZ::$cookie_admin->parse_for_social_media( $src, true );
		if ( ! $type ) {
			$type
				= COMPLIANZ::$cookie_admin->parse_for_thirdparty_services( $src,
				true );
		}
	}

	return $type;
}
