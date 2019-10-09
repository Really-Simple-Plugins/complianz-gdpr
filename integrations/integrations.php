<?php
defined('ABSPATH') or die("you do not have acces to this page!");
require_once('forms.php');
global $cmplz_integrations_list;
$cmplz_integrations_list= apply_filters('cmplz_integrations', array(
    //user registration plugin
    'addtoany' => array(
        'constant_or_function' => 'A2A_SHARE_SAVE_init',
        'label' => 'Add To Any',
    ),
    'user-registration' => array(
        'constant_or_function' => 'UR',
        'label' => 'User Registration',
    ),
    'contact-form-7' => array(
        'constant_or_function' => 'WPCF7_VERSION',
        'label' => 'Contact Form 7',
    ),
    'monsterinsights' => array(
        'constant_or_function' => 'MonsterInsights',
        'label' => 'MonsterInsights',
    ),

    'caos-host-analytics-local' => array(
        'constant_or_function' => 'CAOS_STATIC_VERSION',
        'label' => 'CAOS host analytics locally',

    ),
    //WP Google Maps plugin
    'wp-google-maps' => array(
        'constant_or_function' => 'WPGMZA_VERSION',
        'label' => 'WP Google Maps',

    ),

    //Geo My WP plugin
    'geo-my-wp' => array(
        'constant_or_function' => 'GMW_VERSION',
        'label' => 'Geo My WP',

    ),
    //WP Do Not Track
    'wp-donottrack' => array(
        'constant_or_function' => 'wp_donottrack_config',
        'label' => 'WP Do Not Track',

    ),

    //Pixel Caffeine
    'pixel-caffeine' => array(
        'constant_or_function' => 'AEPC_PIXEL_VERSION',
        'label' => 'Pixel Caffeine',

    ),


    //Super Socializer
    'super-socializer' => array(
        'constant_or_function' => 'THE_CHAMP_SS_VERSION',
        'label' => 'Super Socializer',

    ),

    //Tidio Live Chat
    'tidio-live-chat' => array(
        'constant_or_function' => 'TIDIOCHAT_VERSION',
        'label' => 'Tidio Live Chat',

    ),
    //Instagram feed / Smash balloon social photo feed
    'instagram-feed' => array(
        'constant_or_function' => 'SBIVER',
        'label' => 'Instagram Feed',
    ),

    //Sumo
    'sumo' => array(
        'constant_or_function' => 'SUMOME__PLUGIN_DIR',
        'label' => 'Sumo – Boost Conversion and Sales',
    ),

    //WP Forms
    'wpforms' => array(
        'constant_or_function' => 'wpforms',
        'label' => 'WP Forms',
        'condition' => array('privacy-statement' => 'yes'),
    ),

    //Gravity Forms
    'gravity-forms' => array(
        'constant_or_function' => 'GF_MIN_WP_VERSION',
        'label' => 'Gravity Forms',
        'callback_condition' => array(
                'privacy-statement' => 'yes',
                'regions' => 'eu',
            ),
        ),
));


require_once('fields.php');

/**
 * Wordpress, include always
 */
require_once('wordpress/wordpress.php');

/**
 * code loaded without privileges to allow integrations between plugins and services, when enabled.
 */

function cmplz_integrations(){

    global $cmplz_integrations_list;
    foreach($cmplz_integrations_list as $plugin => $details){
        if ((defined($details['constant_or_function']) || function_exists($details['constant_or_function'])) && (cmplz_get_value($plugin, false, 'integrations')==1)){
            $file = apply_filters('cmplz_integration_path', cmplz_path."integrations/plugins/$plugin.php", $plugin);
            if (file_exists($file)){
                require_once($file);
            } else {
                error_log("searched for $plugin integration at $file, but did not find it");
            }
        }
    }

    /**
     * Services
     */

    $services = COMPLIANZ()->config->thirdparty_service_markers;
    $services = array_keys($services);

    foreach($services as $service){
        if (cmplz_uses_thirdparty($service)){
            if (file_exists(cmplz_path."integrations/services/$service.php")) {
                require_once("services/$service.php");
            }
        }
    }

    $services = COMPLIANZ()->config->social_media_markers;
    $services = array_keys($services);

    foreach($services as $service){
        if (cmplz_uses_thirdparty($service)){
            if (file_exists(cmplz_path."integrations/services/$service.php")) {
                require_once("services/$service.php");
            }
        }
    }

    /**
     * advertising
     */

    if (cmplz_get_value('uses_ad_cookies')){
        require_once('services/advertising.php');
    }

    /**
     * statistics
     */

    $statistics = cmplz_get_value('compile_statistics');
    if ($statistics === 'google-analytics' && !defined('CAOS_STATIC_VERSION')) {
        require_once('statistics/google-analytics.php');
    }elseif ($statistics === 'google-tag-manager') {
        require_once('statistics/google-tagmanager.php');
    }

}
add_action('plugins_loaded', 'cmplz_integrations', 20);


/**
 * Check if a third party is used on this site
 * @param string $name
 * @return bool uses_thirdparty
 */

function cmplz_uses_thirdparty($name){
    $thirdparty = (cmplz_get_value('uses_thirdparty_services') === 'yes') ? true : false;
    if ($thirdparty) {
        $thirdparty_types = cmplz_get_value('thirdparty_services_on_site');
        if (isset($thirdparty_types[$name]) && $thirdparty_types[$name] == 1) {
            return true;
        }
    }

    $social_media = (cmplz_get_value('uses_social_media') === 'yes') ? true : false;
    if ($social_media) {
        $social_media_types = cmplz_get_value('socialmedia_on_site');
        if (isset($social_media_types[$name]) && $social_media_types[$name] == 1) return true;
    }

    return false;
}

/**
 * Handle saving of integrations services
 */

function process_integrations_services_save(){
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['cmplz_save_integrations_type'])){
        if (!isset($_POST['complianz_nonce']) || !wp_verify_nonce($_POST['complianz_nonce'], 'complianz_save')) return;

        $thirdparty_services = COMPLIANZ()->config->thirdparty_services;
        unset($thirdparty_services['google-fonts']);

        $active_services = cmplz_get_value('thirdparty_services_on_site');

        foreach($thirdparty_services as $service => $label){
            if (isset($_POST['cmplz_'.$service]) && $_POST['cmplz_'.$service]==1){
                $active_services[$service]=1;
            }else {
                $active_services[$service]=0;
            }
        }

        cmplz_update_option('wizard', 'thirdparty_services_on_site', $active_services);

        $socialmedia = COMPLIANZ()->config->thirdparty_socialmedia;
        $active_socialmedia = cmplz_get_value('socialmedia_on_site');
        foreach($socialmedia as $service => $label){
            if (isset($_POST['cmplz_'.$service]) && $_POST['cmplz_'.$service]==1){
                $active_socialmedia[$service]=1;
            } else {
                $active_socialmedia[$service]=0;
            }
        }

        cmplz_update_option('wizard', 'socialmedia_on_site', $active_socialmedia);
    }

}
add_action('admin_init','process_integrations_services_save');
