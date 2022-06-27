<?php defined( 'ABSPATH' ) or die();
/**
 * Make options translatable in qtranslate
 * @param array $options
 *
 * @return array
 */
function cmplz_qtranslatex_options($options){
	$keys = array(
		'header',
		'categories',
		'accept_optin',
		'accept_optout',
		'manage_consent',
		'manage_options',
		'save_settings',
		'dismiss',
		'message_optout',
		'message_optin',
		'category_functional',
		'category_preferences',
		'category_statistics',
		'functional_text',
		'statistics_text',
		'statistics_text_anonymous',
		'preferences_text',
		'marketing_text',
		'category_marketing',
	);

	foreach($keys as $key){
		if( isset($options[$key]) ){
			$options[$key] = __($options[$key]);
		}
	}
	return $options;
}
add_filter('cmplz_cookiebanner_settings_html','cmplz_qtranslatex_options',10,1);

