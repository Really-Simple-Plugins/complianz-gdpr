<?php
defined( 'ABSPATH' ) or die();
add_action( 'init', 'cmplz_check_upgrade', 10, 2 );

/**
 * Run an upgrade procedure if the version has changed
 */
function cmplz_check_upgrade() {

	$prev_version = get_option( 'cmplz-current-version', false );
	if ( $prev_version === cmplz_version ) {
		return;
	}

	/**
	 * Set a "first version" variable, so we can check if some notices need to be shown
	 */
	if ( ! $prev_version ) {
		update_option( 'cmplz_first_version', cmplz_version );
	}

	/*
	 * change googlemaps into google-maps
	 * */
	if ( $prev_version
	     && version_compare( $prev_version, '4.0.0', '<' )
	) {
		$wizard_settings = get_option( 'complianz_options_wizard' );
		if ( isset( $wizard_settings['thirdparty_services_on_site']['googlemaps'] )
		     && $wizard_settings['thirdparty_services_on_site']['googlemaps']
		        == 1
		) {
			unset( $wizard_settings['thirdparty_services_on_site']['googlemaps'] );
			$wizard_settings['thirdparty_services_on_site']['google-maps'] = 1;
			update_option( 'complianz_options_wizard', $wizard_settings );
		}
	}

	/**
	 * upgrade existing eu and uk settings to separate uk optinstats
	 */

	if ( $prev_version
	     && version_compare( $prev_version, '4.0.0', '<' )
	) {
		if ( cmplz_has_region( 'eu' ) && cmplz_has_region( 'uk' ) ) {
			$banners = cmplz_get_cookiebanners();
			foreach ( $banners as $banner ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner->ID );
				$banner->use_categories_optinstats
				        = $banner->use_categories;
				$banner->save();
			}
		}

	}

	/**
	 * migrate to anonymous if anonymous settings are selected
	 */

	if ( $prev_version
	     && version_compare( $prev_version, '4.0.4', '<' )
	) {
		$selected_stat_service = cmplz_get_value( 'compile_statistics' );
		if ( $selected_stat_service === 'google-analytics'
		     || $selected_stat_service === 'matomo'
		     || $selected_stat_service === 'google-tag-manager'
		) {
			$service_name
				= COMPLIANZ::$cookie_admin->convert_slug_to_name( $selected_stat_service );

			//check if we have ohter types of this service, to prevent double services here.
			$service_anonymized = new CMPLZ_SERVICE( $service_name . ' (anonymized)' );
			$service            = new CMPLZ_SERVICE( $service_name );

			//check if we have two service types. If so, just delete the anonymized one
			if ( $service_anonymized->ID && $service->ID ) {
				$service_anonymized->delete();
			} else if ( $service_anonymized->ID && ! $service->ID ) {
				//just one. If it's the anonymous service, rename, and save it.
				$service_anonymized->name = $service_name;
				$service_anonymized->save();
			}
		}
	}

	/**
	 * ask consent for cookiedatabase sync and reference, and start sync and scan
	 */

	if ( $prev_version
	     && version_compare( $prev_version, '4.0.4', '<' )
	) {

		//upgrade option to transient
		if ( ! get_transient( 'cmplz_processed_pages_list' ) ) {
			set_transient( 'cmplz_processed_pages_list',
				get_option( 'cmplz_processed_pages_list' ),
				MONTH_IN_SECONDS );
		}

		//reset scan, delayed
		COMPLIANZ::$cookie_admin->reset_pages_list( true );
		//initialize a sync
		update_option( 'cmplz_run_cdb_sync_once', true );
	}

	/**
	 * upgrade publish date to more generic unix
	 */

	if ( $prev_version
	     && version_compare( $prev_version, '4.2', '<' )
	) {
		$publish_date = strtotime( get_option( 'cmplz_publish_date' ) );
		if ( intval( $publish_date ) > 0 ) {
			update_option( 'cmplz_publish_date',
				intval( $publish_date ) );
		}
	}

	/**
	 * upgrade to new custom and generated document settings
	 */
	if ( $prev_version
	     && version_compare( $prev_version, '4.4.0', '<' )
	) {
		//upgrade cookie policy setting to new field
		$wizard_settings = get_option( 'complianz_options_wizard' );
		if ( isset( $wizard_settings["cookie-policy-type"] ) ) {
			$value = $wizard_settings["cookie-policy-type"];
			unset( $wizard_settings["cookie-policy-type"] );
			//upgrade cookie policy custom url
			if ( $value === 'custom' ) {
				$url = cmplz_get_value( 'custom-cookie-policy-url' );
				update_option( "cmplz_cookie-statement_custom_page", $url );
				unset( $wizard_settings["custom-cookie-policy-url"] );
			} else {
				$value = 'generated';
			}
		} else {
			$value = 'generated';
		}

		$wizard_settings['cookie-statement'] = $value;
		$wizard_settings['impressum']        = 'none';

		//upgrade privacy statement settings
		$value = $wizard_settings["privacy-statement"];

		if ( $value === 'yes' ) {
			$value = 'generated';
		} else {
			$wp_privacy_policy = get_option( 'wp_page_for_privacy_policy' );
			if ( $wp_privacy_policy ) {
				$value = 'custom';
				update_option( "cmplz_privacy-statement_custom_page", $wp_privacy_policy );
			} else {
				$value = 'none';
			}
		}
		$wizard_settings['privacy-statement'] = $value;

		//upgrade disclaimer settings
		$value = $wizard_settings["disclaimer"];
		if ( $value === 'yes' ) {
			$value = 'generated';
		} else {
			$value = 'none';
		}
		$wizard_settings['disclaimer'] = $value;

		//save the data
		update_option( 'complianz_options_wizard', $wizard_settings );
	}

	/**
	 * upgrade to new category field
	 */
	if ( $prev_version
	     && version_compare( $prev_version, '4.6.0', '<' )
	) {

		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID, false );
				$banner->banner_version ++;
				if ( $banner->use_categories ) {
					$banner->use_categories = 'legacy';
				} else {
					$banner->use_categories = 'no';
				}
				if ( $banner->use_categories_optinstats ) {
					$banner->use_categories_optinstats = 'legacy';
				} else {
					$banner->use_categories_optinstats = 'no';
				}
				//also set the deny button to banner color, to make sure users start with correct colors
				$banner->functional_background_color = $banner->colorpalette_background['color'];
				$banner->functional_border_color     = $banner->colorpalette_background['border'];
				$banner->functional_text_color       = $banner->colorpalette_text['color'];
				$banner->save();
			}
		}
	}

	/**
	 * migrate policy id to network option for multisites
	 */

	if ( $prev_version && version_compare( $prev_version, '4.6.7', '<' )
	) {
		if ( is_multisite() ) {
			update_site_option( 'complianz_active_policy_id', get_option( 'complianz_active_policy_id', 1 ) );
		}
	}

	/**
	 * migrate odd numbers
	 */
	if ( $prev_version && version_compare( $prev_version, '4.6.8', '<' )
	) {
		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID );
				if ( $banner->banner_width % 2 == 1 ) {
					$banner->banner_width ++;
				}
				$banner->save();
			}
		}
	}

	if (  $prev_version
	      && version_compare( $prev_version, '4.7.1', '<' )
	) {
		//upgrade cookie policy setting to new field
		$wizard_settings                            = get_option( 'complianz_options_wizard' );
		$wizard_settings['block_recaptcha_service'] = 'yes';
		update_option( 'complianz_options_wizard', $wizard_settings );
	}

	if ( $prev_version
	     && version_compare( $prev_version, '4.9.6', '<' )
	) {
		//this branch aims to revoke consent and clear all cookies. We increase the policy id to do this.
		COMPLIANZ::$cookie_admin->upgrade_active_policy_id();
	}

	if ( $prev_version
	     && version_compare( $prev_version, '4.9.7', '<' )
	) {
		update_option( 'cmplz_show_terms_conditions_notice', time() );
	}

	/**
	 * upgrade to new cookie banner, and 5.0 message option
	 */

	if ( $prev_version && version_compare( $prev_version, '5.0.0', '<' ) ) {
		update_option( 'cmplz_upgraded_to_five', true );

		//clear notices cache, as the array structure has changed
		delete_transient( 'complianz_warnings' );
		global $wpdb;

		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID, false );
				$sql    = "select * from {$wpdb->prefix}cmplz_cookiebanners where ID = {$banner_item->ID}";
				$result = $wpdb->get_row( $sql );

				if ( $result ) {
					//reset to arrays
					if (!is_array($banner->colorpalette_background)) {
						$banner->colorpalette_background = array();
					}
					if (!is_array($banner->colorpalette_text)) {
						$banner->colorpalette_text = array();
					}
					if (!is_array($banner->colorpalette_toggles)) {
						$banner->colorpalette_toggles = array();
					}
					if (!is_array($banner->colorpalette_button_accept)) {
						$banner->colorpalette_button_accept = array();
					}
					if (!is_array($banner->colorpalette_button_deny)) {
						$banner->colorpalette_button_deny = array();
					}
					if (!is_array($banner->colorpalette_button_settings)) {
						$banner->colorpalette_button_settings = array();
					}
					$banner->colorpalette_background['color']   = empty( $result->popup_background_color ) ? '#f1f1f1' : $result->popup_background_color;
					$banner->colorpalette_background['border']  = empty( $result->popup_background_color ) ? '#f1f1f1' : $result->popup_background_color;
					$banner->colorpalette_text['color']         = empty( $result->popup_text_color ) ? '#191e23' : $result->popup_text_color;
					$banner->colorpalette_text['hyperlink']     = empty( $result->popup_text_color ) ? '#191e23' : $result->popup_text_color;
					$banner->colorpalette_toggles['background'] = empty( $result->slider_background_color ) ? '#21759b' : $result->slider_background_color;
					$banner->colorpalette_toggles['bullet']     = empty( $result->slider_bullet_color ) ? '#ffffff' : $result->slider_bullet_color;
					$banner->colorpalette_toggles['inactive']   = empty( $result->slider_background_color_inactive ) ? '#F56E28' : $result->slider_background_color_inactive;

					$consenttypes = cmplz_get_used_consenttypes();
					$optout_only  = false;
					if ( in_array( 'optout', $consenttypes ) && count( $consenttypes ) === 1 ) {
						$optout_only = true;
					}

					if ( $banner->use_categories === 'no' || $optout_only ) {
						$banner->colorpalette_button_accept['background'] = empty( $result->button_background_color ) ? '#21759b' : $result->button_background_color;
						$banner->colorpalette_button_accept['border']     = empty( $result->border_color ) ? '#21759b' : $result->border_color;
						$banner->colorpalette_button_accept['text']       = empty( $result->button_text_color ) ? '#ffffff' : $result->button_text_color;
					} else {
						$banner->colorpalette_button_accept['background'] = empty( $result->accept_all_background_color ) ? '#21759b' : $result->accept_all_background_color;
						$banner->colorpalette_button_accept['border']     = empty( $result->accept_all_border_color ) ? '#21759b' : $result->accept_all_border_color;
						$banner->colorpalette_button_accept['text']       = empty( $result->accept_all_text_color ) ? '#ffffff' : $result->accept_all_text_color;
					}
					$banner->colorpalette_button_deny['background'] = empty( $result->functional_background_color ) ? '#f1f1f1' : $result->functional_background_color;
					$banner->colorpalette_button_deny['border']     = empty( $result->functional_border_color ) ? '#f1f1f1' : $result->functional_border_color;
					$banner->colorpalette_button_deny['text']       = empty( $result->functional_text_color ) ? '#21759b' : $result->functional_text_color;

					$banner->colorpalette_button_settings['background'] = empty( $result->button_background_color ) ? '#f1f1f1' : $result->button_background_color;
					$banner->colorpalette_button_settings['border']     = empty( $result->border_color ) ? '#21759b' : $result->border_color;
					$banner->colorpalette_button_settings['text']       = empty( $result->button_text_color ) ? '#21759b' : $result->button_text_color;
					if ( $banner->theme === 'edgeless' ) {
						$banner->buttons_border_radius = array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
							'type'   => 'px',
						);
					}

					$banner->save();
				}

			}
		}
		/**
		 * Move custom scripts from 'wizard' to 'custom-scripts'
		 */
		//upgrade cookie policy setting to new field
		$wizard_settings = get_option( 'complianz_options_wizard' );
		$custom_scripts  = array();
		if ( isset( $wizard_settings['statistics_script'] ) ) {
			$custom_scripts['statistics_script'] = $wizard_settings['statistics_script'];
		}
		if ( isset( $wizard_settings['cookie_scripts'] ) ) {
			$custom_scripts['cookie_scripts'] = $wizard_settings['cookie_scripts'];
		}
		if ( isset( $wizard_settings['cookie_scripts_async'] ) ) {
			$custom_scripts['cookie_scripts_async'] = $wizard_settings['cookie_scripts_async'];
		}
		if ( isset( $wizard_settings['thirdparty_scripts'] ) ) {
			$custom_scripts['thirdparty_scripts'] = $wizard_settings['thirdparty_scripts'];
		}
		if ( isset( $wizard_settings['thirdparty_iframes'] ) ) {
			$custom_scripts['thirdparty_iframes'] = $wizard_settings['thirdparty_iframes'];
		}
		unset( $wizard_settings['statistics_script'] );
		unset( $wizard_settings['cookie_scripts'] );
		unset( $wizard_settings['cookie_scripts_async'] );
		unset( $wizard_settings['thirdparty_scripts'] );
		unset( $wizard_settings['thirdparty_iframes'] );
		update_option( 'complianz_options_custom-scripts', $custom_scripts );
		update_option( 'complianz_options_wizard', $wizard_settings );

		/**
		 * we dismiss the integrations enabled notices
		 */

		$dismissed_warnings = get_option( 'cmplz_dismissed_warnings', array() );
		$fields             = COMPLIANZ::$config->fields( 'integrations' );
		foreach ( $fields as $warning_id => $field ) {
			if ( $field['disabled'] ) {
				continue;
			}
			if ( ! in_array( $warning_id, $dismissed_warnings ) ) {
				$dismissed_warnings[] = $warning_id;
			}
		}
		update_option( 'cmplz_dismissed_warnings', $dismissed_warnings );
	}

	if ( $prev_version && version_compare( $prev_version, '5.1.0', '<' ) ) {
		update_option( 'cmplz_first_version', '5.0.0' );
	}

	/**
	 * restore dropshadow in TCF banner.
	 */
	if ( $prev_version
	     && version_compare( $prev_version, '5.1.2', '<' )
	) {
		if ( cmplz_tcf_active() ) {
			$banners = cmplz_get_cookiebanners();
			if ( $banners ) {
				foreach ( $banners as $banner_item ) {
					$banner                 = new CMPLZ_COOKIEBANNER( $banner_item->ID, false );
					$banner->use_box_shadow = true;
					$banner->save();
				}
			}
		}
	}

	if ( $prev_version
	     && version_compare( $prev_version, '5.2.0', '<' )
	) {
		if ( cmplz_tcf_active() ) {
			$banners = cmplz_get_cookiebanners();
			if ( $banners ) {
				foreach ( $banners as $banner_item ) {
					$banner                               = new CMPLZ_COOKIEBANNER( $banner_item->ID, false );
					$banner->colorpalette_button_accept   = array(
						'background' => '#333',
						'border'     => '#333',
						'text'       => '#fff',
					);
					$banner->colorpalette_button_settings = array(
						'background' => '#fff',
						'border'     => '#333',
						'text'       => '#333',
					);
					$banner->save();
				}
			}
		}
	}

	if ( $prev_version
	     && version_compare( $prev_version, '5.2.6.1', '<' )
	) {
		if ( cmplz_tcf_active() ) {
			delete_transient( 'cmplz_vendorlist_downloaded_once' );
		}
	}

	/**
	 * Change metakeys for eu dataleaks from '{metakey}' to '{metakey}-eu' for consistency between dataleaks .
	 */
	if ( $prev_version
	     && version_compare( $prev_version, '5.4.0', '<' )
	) {
		$args = array(
			'numberposts' => - 1,
			'post_type'   => 'cmplz-dataleak',
			'tax_query'   => array(
				array(
					'taxonomy' => 'cmplz-region',
					'field'    => 'slug',
					'terms'    => 'eu',
				),
			),
		);

		$posts     = get_posts( $args );
		$meta_keys = array(
			'security-incident-occurred',
			'type-of-dataloss',
			'reach-of-dataloss',
			'risk-of-data-loss',
			'what-occurred',
			'consequences',
			'measures',
			'measures_by_person_involved',
			'conclusion',
		);
		foreach ( $posts as $post ) {
			foreach ( $meta_keys as $meta_key ) {
				$value = get_post_meta( $post->ID, $meta_key, true );
				if ( $value ) {
					update_post_meta( $post->ID, $meta_key . '-eu', $value );
				}
			}
		}

		$wizard_settings = get_option( 'complianz_options_wizard' );
		//upgrade to checkboxes structure.
		$value_eu = $value_uk = false;
		if ( isset( $wizard_settings['dpo_or_gdpr'] ) ) {
			$value_eu = $wizard_settings['dpo_or_gdpr'];
		}

		if ( isset( $wizard_settings['dpo_or_uk_gdpr'] ) ) {
			$value_uk = $wizard_settings['dpo_or_uk_gdpr'];
		}
		if ( ! is_array( $value_eu ) ) {
			$new_value = array(
				'dpo'         => 0,
				'dpo_uk'      => 0,
				'gdpr_rep'    => 0,
				'uk_gdpr_rep' => 0,
			);
			if ( $value_eu ) {
				$new_value[ $value_eu ] = 1;
			}
			if ( $value_uk ) {
				if ( $value_uk === 'dpo' ) {
					$value_uk = 'dpo_uk';
				}
				$new_value[ $value_uk ] = 1;
			}
			//none is not applicable anymore, as it's  multischeckbox
			unset( $new_value['none'] );

			$wizard_settings['dpo_or_gdpr'] = $new_value;
			unset( $wizard_settings['dpo_or_uk_gdpr'] );

			if ( isset( $wizard_settings['ca_name_address_accountable_person'] ) ) {
				$address = preg_split( '#\n(?!s)#', $wizard_settings['ca_name_address_accountable_person'] );
				$name    = isset( $address[0] ) ? $address[0] : '';
				unset( $address[0] );
				$address                                          = implode( "\n", $address );
				$wizard_settings['ca_name_accountable_person']    = $name;
				$wizard_settings['ca_address_accountable_person'] = $address;
			}

			update_option( 'complianz_options_wizard', $wizard_settings );
		}
	}

	if ( $prev_version
	     && version_compare( $prev_version, '5.5.0', '<' )
	) {
		$wizard_settings   = get_option( 'complianz_options_wizard' );
		$settings_settings = get_option( 'complianz_options_settings' );
		if ( isset( $wizard_settings['use_cdb_api'] ) ) {
			$settings_settings['use_cdb_api']   = $wizard_settings['use_cdb_api'];
			$settings_settings['use_cdb_links'] = $wizard_settings['use_cdb_links'];
		}
		unset( $wizard_settings['use_cdb_api'] );
		unset( $wizard_settings['use_cdb_links'] );
		update_option( 'complianz_options_wizard', $wizard_settings );
		update_option( 'complianz_options_settings', $settings_settings );
	}

	if ( $prev_version
	     && version_compare( $prev_version, '5.5.0', '<' )
	) {
		$wizard_settings = get_option( 'complianz_options_wizard' );

		$share_data_us = $share_data_eu = 2;
		if ( isset($wizard_settings['share_data_other_us']) ) {
			$share_data_us = intval($wizard_settings['share_data_other_us']);
		}
		if ( isset($wizard_settings['share_data_other']) ) {
			$share_data_eu = intval($wizard_settings['share_data_other']);
		}
		//share data other parties: indien een van beide "yes", nieuwe yes. Indien een van beide limited, nieuwe "limited". anders no.
		if ($share_data_us===1 || $share_data_eu ===1) {
			$share_data = 1;
		} else if ($share_data_us===3 || $share_data_eu ===3){
			$share_data = 3;
		} else {
			$share_data = 2;
		}
		$wizard_settings['share_data_other'] = $share_data;
		$us_processors = isset($wizard_settings['processor_us'] ) ? $wizard_settings['processor_us'] : array();
		$eu_processors = isset($wizard_settings['processor']) ? $wizard_settings['processor'] : array();
		foreach ( $us_processors as $us_processor ) {
			//check if it's already in the list
			$key = array_search($us_processor['name'], array_column($eu_processors, 'name'));
			if ( $key !== false ) unset($us_processors[ $key ]);
		}

		//now add the remaining values to the EU list
		$eu_processors = array_merge($eu_processors, $us_processors);
		$wizard_settings['processor'] = $eu_processors;

		$us_thirdparties = isset($wizard_settings['thirdparty_us'] ) ? $wizard_settings['thirdparty_us'] : array();
		$eu_thirdparties = isset($wizard_settings['thirdparty']) ? $wizard_settings['thirdparty'] : array();
		foreach ( $us_thirdparties as $us_thirdparty ) {
			//check if it's already in the list
			$key = array_search($us_thirdparty['name'], array_column($eu_thirdparties, 'name'));
			if ( $key !== false ) unset($us_thirdparties[ $key ]);
		}
		//now add the remaining values to the EU list
		$eu_thirdparties = array_merge($eu_thirdparties, $us_thirdparties);
		$wizard_settings['thirdparty'] = $eu_thirdparties;
		unset($wizard_settings['thirdparty_us']);
		unset($wizard_settings['processor_us']);

		update_option( 'complianz_options_wizard', $wizard_settings );
	}

	if ( $prev_version && version_compare( $prev_version, '6.0.0', '<' ) ) {
		$custom_scripts = get_option( 'complianz_options_custom-scripts' );

		$scripts['add_script']       = [];
		$scripts['block_script']     = [];
		$scripts['whitelist_script'] = [];

		if ( ! empty( $custom_scripts['cookie_scripts'] ) ) {
			$scripts['add_script'][] = [
				'name'                 => 'Scripts',
				'editor'               => $custom_scripts['cookie_scripts'],
				'async'                => '0',
				'category'             => 'marketing',
				'enable_placeholder'   => '0',
				'placeholder_class' => '',
				'placeholder'          => '',
				'enable_dependency'    => '0',
				'dependency'           => '',
				'enable'               => '1',
			];
		}

		if ( ! empty( $custom_scripts['cookie_scripts_async'] ) ) {
			$scripts['add_script'][] = [
				'name'                 => 'Async scripts',
				'editor'               => $custom_scripts['cookie_scripts_async'],
				'async'                => '1',
				'category'             => 'marketing',
				'enable_placeholder'   => '0',
				'placeholder_class' => '',
				'placeholder'          => '',
				'enable_dependency'    => '0',
				'dependency'           => '',
				'enable'               => '1',
			];
		}

		if ( ! empty( $custom_scripts['statistics_script'] ) ) {
			$scripts['add_script'][] = [
				'name'                 => 'Statistics scripts',
				'editor'               => $custom_scripts['statistics_script'],
				'async'                => '0',
				'category'             => 'statistics',
				'enable_placeholder'   => '0',
				'placeholder_class' => '',
				'placeholder'          => '',
				'enable_dependency'    => '0',
				'dependency'           => '',
				'enable'               => '1',
			];
		}

		if ( ! empty( $custom_scripts['thirdparty_scripts'] ) ) {
			$scripts['block_script'][] = [
				'name'                 => 'Third party scripts',
				'urls'                 => explode( ',', $custom_scripts['thirdparty_scripts'] ),
				'category'             => 'marketing',
				'enable_placeholder'   => '0',
				'placeholder_class' => '',
				'placeholder'          => '',
				'enable'               => '1',
			];
		}

		if ( ! empty( $custom_scripts['thirdparty_iframes'] ) ) {
			$scripts['block_script'][] = [
				'name'                 => 'Third party iframes',
				'urls'                 => explode( ',', $custom_scripts['thirdparty_iframes'] ),
				'category'             => 'marketing',
				'enable_placeholder'   => '0',
				'placeholder_class' => '',
				'placeholder'          => '',
				'enable'               => '1',
			];
		}
		update_option( 'complianz_options_custom-scripts', $scripts );

		$general_settings                      = get_option( 'complianz_options_settings' );
		$general_settings['enable_migrate_js'] = true;
		update_option( 'complianz_options_settings', $general_settings );

		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner              = new CMPLZ_COOKIEBANNER( $banner_item->ID );
				switch ( $banner->use_categories ) {
					case 'no':
						$banner->use_categories = 'no';
						break;
					case 'legacy':
					case 'visible':
						$banner->use_categories = 'save-preferences';
						break;
					default:
						$banner->use_categories = 'view-preferences';
				}

				switch ( $banner->position ) {
					case 'top':
						$banner->position = 'bottom';
						break;
					case 'bottom':
					case 'bottom-left':
					case 'bottom-right':
						break;
					default:
						$banner->position = 'center';
				}

				switch ( $banner->checkbox_style ) {
					case 'classic':
						$banner->checkbox_style = 'classic';
						break;
					default:
						$banner->checkbox_style = 'slider';
				}

				if ( !is_serialized($banner->border_width) ) {
					$banner->border_width = array(
						'top'    => 0,
						'right'  => 0,
						'bottom' => 0,
						'left'   => 0,
					);
				}

				if ( !isset($banner->border_width['top']) ) $banner->border_width['top'] = 0;
				if ( !isset($banner->border_width['right']) ) $banner->border_width['right'] = 0;
				if ( !isset($banner->border_width['bottom']) ) $banner->border_width['bottom'] = 0;
				if ( !isset($banner->border_width['left']) ) $banner->border_width['left'] = 0;

				if (!is_serialized($banner_item->dismiss)) {
					$banner->revoke = array(
						'text' => $banner_item->revoke,
						'show' => ! $banner_item->hide_revoke,
					);
				}

				if (!is_serialized($banner_item->header) ) {
					if ( strlen($banner_item->header)<4 ) {
						$banner->header = array(
							'text' => '',
							'show' => false,
						);
					} else {
						$banner->header = array(
							'text' => $banner_item->header,
							'show' => true,
						);
					}
				}

				if (!is_serialized($banner_item->accept_informational)) {
					$banner->accept_informational = array(
						'text' => $banner_item->accept_informational,
						'show' => true,
					);
				}

				if (!is_serialized($banner_item->category_prefs)) {
					$banner->category_prefs       = array(
						'text' => $banner_item->category_prefs,
						'show' => true,
					);
				}

				if (!is_serialized($banner_item->category_stats)) {
					$banner->category_stats = array(
						'text' => $banner_item->category_stats,
						'show' => true,
					);
				}

				if (!is_serialized($banner_item->category_all)) {
					$banner->category_all = array(
						'text' => $banner_item->category_all,
						'show' => true,
					);
				}

				$banner->use_box_shadow = true;
				$banner->use_logo = 'hide';
				$banner->close_button = false;
				$banner->save();
			}
		}

		$wizard_settings = get_option( 'complianz_options_wizard' );
		if ( isset( $wizard_settings['compile_statistics'] ) && $wizard_settings['compile_statistics'] === 'yes-anonymous' ) {
			$wizard_settings['compile_statistics'] = 'yes';
			update_option( 'complianz_options_wizard', $wizard_settings );
		}
	}

	if ( $prev_version && version_compare( $prev_version, '6.0.0', '>=' ) ) {
		$warning_id = 'upgraded_to_6';
		$dismissed_warnings = get_option( 'cmplz_dismissed_warnings', array() );
		if ( !in_array($warning_id, $dismissed_warnings) ) {
			$dismissed_warnings[] = $warning_id;
			update_option('cmplz_dismissed_warnings', $dismissed_warnings );
			delete_transient('complianz_warnings');
			delete_transient('complianz_warnings_admin_notices');
		}
	}

	if ( $prev_version && version_compare( $prev_version, '6.0.2', '<' ) ) {
		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID );
				if ( $banner->banner_width == 476 ) {
					$banner->banner_width = 526;
					$banner->save();
				}
			}
		}
	}
	if ( $prev_version && version_compare( $prev_version, '6.0.5', '<' ) ) {
		update_option('complianz_enable_dismissible_premium_warnings', true);
	}

	if ( $prev_version && version_compare( $prev_version, '6.0.8', '<' ) ) {
		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID );
				if ( $banner->use_categories === 'hidden' ) {
					$banner->use_categories = 'view-preferences';
					$banner->save();
				}
			}
		}
	}

	if ( $prev_version && version_compare( $prev_version, '6.0.4', '<' ) ) {
		set_transient( 'cmplz_vendorlist_downloaded_once', true, HOUR_IN_SECONDS );
	}

	if ( $prev_version && version_compare( $prev_version, '6.1.0', '<' ) ) {
		$banners = cmplz_get_cookiebanners();
		if ( $banners ) {
			foreach ( $banners as $banner_item ) {
				$banner = new CMPLZ_COOKIEBANNER( $banner_item->ID );
				$banner->legal_documents = true;
				$banner->save();
			}
		}
	}

	//always clear warnings cache on update
	delete_transient('complianz_warnings');
	delete_transient('complianz_warnings_admin_notices');
	do_action( 'cmplz_upgrade', $prev_version );
	update_option( 'cmplz-current-version', cmplz_version );
}





