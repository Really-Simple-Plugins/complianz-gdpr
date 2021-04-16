<?php
/**
 * We need this function also when the consent API is not active, so we build our own.
 * @param $plugin
 *
 * @return bool
 */
function cmplz_consent_api_registered( $plugin ) {

	//we don't need a recommended notice for Complianz or the consent API.
	if (strpos($plugin, 'wp-consent-api.php') !== FALSE) return false;
	if (strpos($plugin, 'complianz') !== FALSE) return false;

	return apply_filters( "wp_consent_api_registered_$plugin", false );
}

/**
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for plugin Complianz GDPR
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the CMPLZ_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

/**
 * The 'x' in the TGMPA notice does not dismiss it, which is annoying.
 */
function cmplz_fix_TGM_dismiss() {
	if ( cmplz_show_terms_conditions_notice() ){
		?>
		<script>
			jQuery(document).ready(function ($) {
				$(document).on('click', '#setting-error-tgmpa .notice-dismiss', function(e){
					e.preventDefault();
					window.location.replace($('#setting-error-tgmpa .dismiss-notice').attr('href'));
				});
			});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'cmplz_fix_TGM_dismiss' );

/**
 * Check if the notice should be shown
 *
 * @return bool
 */
function cmplz_show_terms_conditions_notice(){
	//for testing:
	//	update_option( 'cmplz_show_terms_conditions_notice', strtotime( "-2 weeks" ) );
	//	update_user_meta(get_current_user_id(),'tgmpa_dismissed_notice_complianz-gdpr', false);
	//	return true;
	//check if the tgmpa notice already was dismissed.
	if ( get_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_complianz-gdpr' , true ) ) {
		return false;
	}

	$tc_timestamp = get_option( 'cmplz_show_terms_conditions_notice' );
	if ( !defined( 'cmplz_tc_version' ) && $tc_timestamp < strtotime( '-1 week' )){
		return true;
	}
	return false;
}

/**
 * Register the required plugins for this theme.
 *
 * This function is hooked into `cmplz_register`, which is fired on the WP `init` action on priority 10.
 */
function cmplz__register_required_plugins() {
	$plugins                  = get_option( 'active_plugins' );
	$registered               = array();
	$plugins_with_registration = false;

	foreach ( $plugins as $plugin ) {
		if ( cmplz_consent_api_registered( $plugin ) ) {
			$registered[]             = $plugin;
			$plugins_with_registration = true;
		}
	}

	if ($plugins_with_registration) {
		$plugins = array(
			array(
				'name'      => 'WP Consent API',
				'slug'      => 'wp-consent-api',
				'source'    => 'https://wordpress.org/plugins/wp-consent-api/',
				'required'  => false, // If false, the plugin is only 'recommended' instead of required.
			),
		);
	}

	if ( cmplz_show_terms_conditions_notice() ){
		$plugins[] = array(
			'name'      => 'Complianz - Terms & Conditions',
			'slug'      => 'complianz-terms-conditions',
			'source'    => 'https://wordpress.org/plugins/complianz-terms-conditions/',
			'required'  => false, // If false, the plugin is only 'recommended' instead of required.
		);
	}



	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'complianz-gdpr',        // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'cmplz-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',         // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). */
				'Complianz GDPR/CCPA recommends to activate the following plugin: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'complianz-gdpr'
			),
			'notice_can_install_recommended'  => _n_noop(
			/* translators: 1: plugin name(s). */
				'Complianz GDPR/CCPA recommends the following plugin: %1$s.',
				'Complianz GDPR/CCPA recommends the following plugins: %1$s.',
				'complianz-gdpr'
			),
		),

	);

	cmplz_tgmpa( $plugins, $config );
}
add_action( 'cmplz_register', 'cmplz__register_required_plugins' );
