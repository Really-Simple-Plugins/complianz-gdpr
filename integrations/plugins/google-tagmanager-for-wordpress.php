<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Set analytics as suggested stats tool in the wizard
 */
add_filter( 'cmplz_default_value', 'cmplz_gtm4wp_set_default', 20, 3 );
function cmplz_gtm4wp_set_default( $value, $fieldname, $field ) {
	if ( $fieldname === 'compile_statistics' ) {
		return "google-tag-manager";
	}
	return $value;
}

/**
 * Remove stats
 *
 * */
function cmplz_gtm4wp_show_compile_statistics_notice($notices) {
	$text = '';
	if ( cmplz_no_ip_addresses() ) {
		$text .=  cmplz_sprintf( __( "You have selected you anonymize IP addresses. This setting is now enabled in %s.", 'complianz-gdpr' ), 'Google Tag Manager for WordPress' ) ;
	}
	if ( cmplz_statistics_no_sharing_allowed() ) {
		$text .= cmplz_sprintf( __( "You have selected you do not share data with third-party networks. Remarketing is now disabled in %s.", 'complianz-gdpr' ), 'Google Tag Manager for WordPress' ) ;
	}
	//find notice with field_id 'compile_statistics' and replace it with our own
	$found_key = false;
	foreach ($notices as $key=>$notice) {
		if ($notice['field_id']==='compile_statistics') {
			$found_key = $key;
		}
	}

	$notice = [
		'field_id' => 'compile_statistics',
		'label'    => 'default',
		'title'    => __( "Statistics plugin detected", 'complianz-gdpr' ),
		'text'     => cmplz_sprintf( __( "You use %s, which means the answer to this question should be Google Tag Manager.", 'complianz-gdpr' ), 'Google Tag Manager for WordPress' )
		              .' '.$text,
	];

	if ($found_key){
		$notices[$found_key] = $notice;
	} else {
		$notices[] = $notice;
	}
	return $notices;

}
add_filter( 'cmplz_field_notices', 'cmplz_gtm4wp_show_compile_statistics_notice' );


/**
 * Configure options for GTM4WP
 */
function cmplz_gtm4wp_options() {
	if ( !defined('GTM4WP_OPTIONS')) {
		return;
	}

	$storedoptions = (array) get_option( GTM4WP_OPTIONS );
	$save          = false;

	if ( defined('GTM4WP_OPTION_INCLUDE_VISITOR_IP' ) ) {
		if ( isset( $storedoptions[ GTM4WP_OPTION_INCLUDE_VISITOR_IP ] ) ) {
			if ( cmplz_no_ip_addresses() && $storedoptions[ GTM4WP_OPTION_INCLUDE_VISITOR_IP ]
			) {
				$storedoptions[ GTM4WP_OPTION_INCLUDE_VISITOR_IP ] = false;
				$save                                              = true;
			} elseif ( ! cmplz_no_ip_addresses() && ! ! $storedoptions[ GTM4WP_OPTION_INCLUDE_VISITOR_IP ]
			) {
				$save                                              = true;
				$storedoptions[ GTM4WP_OPTION_INCLUDE_VISITOR_IP ] = true;
			}
		}
	}

	//handle sharing of data
	//since 1.15.1 remarketing constant has been removed
	if (defined('GTM4WP_OPTION_INCLUDE_REMARKETING')) {
		if ( isset( $storedoptions[ GTM4WP_OPTION_INCLUDE_REMARKETING ] ) ) {
			if ( cmplz_statistics_no_sharing_allowed()
			     && $storedoptions[ GTM4WP_OPTION_INCLUDE_REMARKETING ]
			) {
				$save                                               = true;
				$storedoptions[ GTM4WP_OPTION_INCLUDE_REMARKETING ] = false;

			} elseif ( ! cmplz_statistics_no_sharing_allowed()
			           && ! $storedoptions[ GTM4WP_OPTION_INCLUDE_REMARKETING ]
			) {
				$save                                               = true;
				$storedoptions[ GTM4WP_OPTION_INCLUDE_REMARKETING ] = true;
			}
		}
	}


	if ( $save ) {
		update_option( GTM4WP_OPTIONS, $storedoptions );
	}
}
add_action( 'admin_init', 'cmplz_gtm4wp_options' );

/**
 * Make sure there's no warning about configuring GA anymore
 *
 * @param $warnings
 *
 * @return mixed
 */

function cmplz_gtm4wp_filter_warnings( $warnings ) {
	unset($warnings['gtm-needs-configuring']);
	return $warnings;
}

add_filter( 'cmplz_warning_types', 'cmplz_gtm4wp_filter_warnings' );

/**
 * Hide the stats configuration options when gtm4wp is enabled.
 *
 * @param $fields
 *
 * @return mixed
 */

function cmplz_gtm4wp_filter_fields( $fields ) {
	$index = cmplz_get_field_index('compile_statistics_more_info_tag_manager', $fields);
	if ( $index!==false ) {
		unset($fields[$index]['help']);
	}

	return cmplz_remove_field( $fields,
		[
			'configuration_by_complianz',
			'ua_code',
			'aw_code',
			'additional_gtags_stats',
			'additional_gtags_marketing',
			'consent-mode',
			'gtag-basic-consent-mode',
			'cmplz-gtag-urlpassthrough',
			'cmplz-gtag-ads_data_redaction',
			'gtm_code',
			'cmplz-tm-template'
		]);
}

add_filter( 'cmplz_fields', 'cmplz_gtm4wp_filter_fields', 2000, 1 );
