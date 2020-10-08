<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );


add_action( 'cmplz_integrations_menu', 'cmplz_add_integrations_menu' );
function cmplz_add_integrations_menu() {
	add_submenu_page(
		'complianz',
		__( 'Integrations', 'complianz-gdpr' ),
		__( 'Integrations', 'complianz-gdpr' ),
		'manage_options',
		"cmplz-script-center",
		'cmplz_integrations_page'
	);
}


/**
 * Show the integrations page
 *
 */

function cmplz_integrations_page() {
	$active_tab = 'services';
	if ( isset( $_POST['cmplz_save_integrations_type'] ) ) {
		$active_tab = sanitize_title( $_POST['cmplz_save_integrations_type'] );
	}
	?>
	<div class="wrap cmplz-settings cmplz-scriptcenter">
		<div class="cmplz-tab">
			<button class="cmplz-tablinks <?php echo $active_tab === 'services'
				? 'active' : '' ?>" type="button"
			        data-tab="services"><?php _e( "Services",
					"complianz-gdpr" ) ?></button>
			<button class="cmplz-tablinks <?php echo $active_tab === 'plugins'
				? 'active' : '' ?>" type="button"
			        data-tab="plugins"><?php _e( "Plugins",
					"complianz-gdpr" ) ?></button>
			<button class="cmplz-tablinks <?php echo $active_tab === 'scripts'
				? 'active' : '' ?>" type="button"
			        data-tab="scripts"><?php _e( "Script Center",
					'complianz-gdpr' ) ?></button>

		</div>
		<div id="scripts"
		     class="cmplz-tabcontent <?php echo $active_tab === 'scripts'
			     ? 'active' : '' ?>">
			<form action="" method="post" class="cmplz-body">
				<?php
				cmplz_notice( _x( "The script center should be used to add and block third-party scripts and iFrames before consent is given, or when consent is revoked. For example Hotjar and embedded video’s.",
					'intro script center', 'complianz-gdpr' ) )
				?>
				<table class="form-table">

					<tr>
						<th></th>
						<td><?php COMPLIANZ::$field->get_fields( 'wizard',
								STEP_COOKIES, 8 ); ?>
						</td>
					</tr>
				</table>
				<input type="hidden" name="cmplz_save_integrations_type"
				       value="scripts">

				<?php COMPLIANZ::$field->save_button(); ?>
			</form>
		</div>

		<div id="services"
		     class="cmplz-tabcontent <?php echo $active_tab === 'services'
			     ? 'active' : '' ?>">
			<form action="" method="post" class="cmplz-body">
				<?php

				$thirdparty_active
					                = ( cmplz_get_value( 'uses_thirdparty_services' )
					                    === 'yes' ) ? true : false;
				$socialmedia_active = ( cmplz_get_value( 'uses_social_media' )
				                        === 'yes' ) ? true : false;
				if ( ! $thirdparty_active && ! $socialmedia_active ) {
					$not_used = __( 'Third-party services and social media',
						'complianz-gdpr' );
					$link     = '<a href="' . add_query_arg( array(
							'page'    => 'cmplz-wizard',
							'step'    => STEP_COOKIES,
							'section' => 4
						), admin_url( 'admin.php' ) ) . '">';
					cmplz_notice( sprintf( __( '%s are marked as not being used on your website in the %swizard%s.',
						'complianz-gdpr' ), $not_used, $link, '</a>' ),
						'warning' );
				}

				if ( $thirdparty_active || $socialmedia_active ) {
					cmplz_notice( sprintf( __( "Enabled %s will be blocked on the front-end of your website until the user has given consent (opt-in), or after the user has revoked consent (opt-out). When possible a placeholder is activated. You can also disable or configure the placeholder to your liking.",
							'complianz-gdpr' ),
								__( "services", "complianz-gdpr" ) )
						              . cmplz_read_more( "https://complianz.io/blocking-recaptcha-manually/" ),
							'warning' );

					if (cmplz_get_value('block_recaptcha_service') === 'yes'){
						if ( defined( 'cmplz_free' ) && cmplz_free ) {
							cmplz_notice( sprintf( __( "reCaptcha is connected and will be blocked before consent. To change your settings, please visit %sIntegrations%s in the wizard. ",
									'complianz-gdpr' ),
									'<a href="' . admin_url( 'admin.php?page=cmplz-wizard&step=2&section=4' ) . '">',
									'</a>' ),
									'warning' );
						} else {
							cmplz_notice( sprintf( __( "reCaptcha is connected and will be blocked before consent. To change your settings, please visit %sIntegrations%s in the wizard. ",
									'complianz-gdpr' ),
									'<a href="' . admin_url( 'admin.php?page=cmplz-wizard&step=3&section=4' ) . '">',
									'</a>' ),
									'warning' );
						}
					}
				}

				?>
				<input type="hidden" name="cmplz_save_integrations_type"
				       value="services">

				<table class="form-table">
					<tr>
						<th></th>
						<th><?php _e( "Connected", "complianz-gdpr" ) ?></th>
						<th><?php _e( "Placeholder active",
								"complianz-gdpr" ) ?></th>
					</tr>
					<?php

					if ( $thirdparty_active ) {
						$thirdparty_services = COMPLIANZ::$config->thirdparty_services;
						unset( $thirdparty_services['google-fonts'] );

				        if (cmplz_get_value('block_recaptcha_service') !== 'yes'){
							unset( $thirdparty_services['google-recaptcha'] );
						}

						$active_services
							= cmplz_get_value( 'thirdparty_services_on_site' );
						foreach ( $thirdparty_services as $service => $label ) {
							$active = ( isset( $active_services[ $service ] )
							            && $active_services[ $service ] == 1 )
								? true : false;
							$args   = array(
								'first'     => false,
								"fieldname" => $service,
								"type"      => 'checkbox',
								"required"  => false,
								'default'   => '',
								'label'     => $label,
								'table'     => true,
								'disabled'  => false,
								'hidden'    => false,
								'cols'    => false,
							);

							COMPLIANZ::$field->checkbox( $args, $active );
						}
					}

					if ( $socialmedia_active ) {
						$socialmedia
							= COMPLIANZ::$config->thirdparty_socialmedia;
						$active_socialmedia
							= cmplz_get_value( 'socialmedia_on_site' );
						foreach ( $socialmedia as $service => $label ) {
							$active = ( isset( $active_socialmedia[ $service ] )
							            && $active_socialmedia[ $service ]
							               == 1 ) ? true : false;

							$args = array(
								'first'     => false,
								"fieldname" => $service,
								"type"      => 'checkbox',
								"required"  => false,
								'default'   => '',
								'label'     => $label,
								'table'     => true,
								'disabled'  => false,
								'hidden'    => false,
								'cols'    => false,
							);

							COMPLIANZ::$field->checkbox( $args, $active );
						}
					}

					$uses_ad_cookies = cmplz_get_value( 'uses_ad_cookies' )
					                   === 'yes';

					$args = array(
						'first'     => false,
						"fieldname" => 'advertising',
						"type"      => 'checkbox',
						"required"  => false,
						'default'   => '',
						'label'     => 'Google Ads/DoubleClick',
						'table'     => true,
						'disabled'  => false,
						'hidden'    => false,
						'cols'    => false,
					);

					COMPLIANZ::$field->checkbox( $args, $uses_ad_cookies );
					?>
				</table>
				<?php COMPLIANZ::$field->save_button(); ?>
			</form>
		</div>


		<div id="plugins"
		     class="cmplz-tabcontent <?php echo $active_tab === 'plugins'
			     ? 'active' : '' ?>">

			<form action="" method="post" class="cmplz-body">
				<input type="hidden" name="cmplz_save_integrations_type"
				       value="plugins">
				<?php
				cmplz_notice( __( 'Below you will find the plugins currently detected and integrated with Complianz. Most plugins work by default, but you can also add a plugin to the script center or add it to the integration list.',
						'complianz-gdpr' )
				              . cmplz_read_more( 'https://complianz.io/developers-guide-for-third-party-integrations' ) );
				cmplz_notice( sprintf( __( "Enabled %s will be blocked on the front-end of your website until the user has given consent (opt-in), or after the user has revoked consent (opt-out). When possible a placeholder is activated. You can also disable or configure the placeholder to your liking.",
						'complianz-gdpr' ), __( "plugins", "complianz-gdpr" ) )
				              . cmplz_read_more( "https://complianz.io/blocking-recaptcha-manually/" ),
					'warning' );

				$fields = COMPLIANZ::$config->fields( 'integrations' );
				if ( count( $fields ) == 0 ) {
					cmplz_notice( __( 'No active plugins detected in the integrations list.',
						'complianz-gdpr' ), 'warning' );

				}
				?>
				<table class="form-table">
					<tr>
						<th></th>
						<th><?php _e( "Connected", "complianz-gdpr" ) ?></th>
						<th><?php _e( "Placeholder active",
								"complianz-gdpr" ) ?></th>
					</tr>
					<?php

					COMPLIANZ::$field->get_fields( 'integrations' );
					?>
				</table>
				<?php COMPLIANZ::$field->save_button(); ?>
			</form>
		</div>

	</div>
	<?php
}


/**
 * Handle saving of integrations services
 */

function process_integrations_services_save() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['cmplz_save_integrations_type'] ) ) {
		if ( ! isset( $_POST['complianz_nonce'] )
		     || ! wp_verify_nonce( $_POST['complianz_nonce'], 'complianz_save' )
		) {
			return;
		}

		if ( $_POST["cmplz_save_integrations_type"] === 'services' ) {
			$thirdparty_services = COMPLIANZ::$config->thirdparty_services;
			unset( $thirdparty_services['google-fonts'] );

			$active_services = cmplz_get_value( 'thirdparty_services_on_site' );
			foreach ( $thirdparty_services as $service => $label ) {
				if ( isset( $_POST[ 'cmplz_' . $service ] )
				     && $_POST[ 'cmplz_' . $service ] == 1
				) {
					$active_services[ $service ] = 1;
					$service_obj                 = new CMPLZ_SERVICE();
					$service_obj->add( $label,
						COMPLIANZ::$cookie_admin->get_supported_languages(),
						false, 'utility' );
				} else {
					$active_services[ $service ] = 0;
				}

			}

			cmplz_update_option( 'wizard', 'thirdparty_services_on_site',
				$active_services );

			$socialmedia        = COMPLIANZ::$config->thirdparty_socialmedia;
			$active_socialmedia = cmplz_get_value( 'socialmedia_on_site' );
			foreach ( $socialmedia as $service => $label ) {
				if ( isset( $_POST[ 'cmplz_' . $service ] )
				     && $_POST[ 'cmplz_' . $service ] == 1
				) {
					$active_socialmedia[ $service ] = 1;
					$service_obj                    = new CMPLZ_SERVICE();
					$service_obj->add( $label,
						COMPLIANZ::$cookie_admin->get_supported_languages(),
						false, 'social' );
				} else {
					$active_socialmedia[ $service ] = 0;
				}

			}
			cmplz_update_option( 'wizard', 'socialmedia_on_site',
				$active_socialmedia );

			if ( $_POST['cmplz_advertising'] == 1 ) {
				cmplz_update_option( 'wizard', 'uses_ad_cookies', 'yes' );
			} else {
				cmplz_update_option( 'wizard', 'uses_ad_cookies', 'no' );
			}
		}

		$disabled_placeholders = get_option( 'cmplz_disabled_placeholders',
			array() );

		foreach ( $_POST as $post_key => $value ) {
			if ( strpos( $post_key, 'cmplz_placeholder' ) !== false ) {
				$plugin = str_replace( array( 'cmplz_placeholder_' ),
					array( '' ), $post_key );

				if ( intval( $_POST[ $post_key ] ) == 1 ) {
					$key = array_search( $plugin, $disabled_placeholders );
					if ( $key !== false ) {
						unset( $disabled_placeholders[ $key ] );
					}
				} elseif ( intval( $_POST[ $post_key ] ) == 0 ) {
					if ( ! in_array( $plugin, $disabled_placeholders ) ) {
						$disabled_placeholders[] = $plugin;
					}
				}

			}
		}


		update_option( 'cmplz_disabled_placeholders', $disabled_placeholders );

	}

}

add_action( 'plugins_loaded', 'process_integrations_services_save' );
