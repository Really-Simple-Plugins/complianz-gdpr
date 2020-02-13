<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_googletagmanager_script');
function cmplz_googletagmanager_script($tags){

	if (!COMPLIANZ::$cookie_admin->tagmamanager_fires_scripts()) {
		$tags[] = 'googletagmanager.com/gtag/js';
		$tags[] = 'gtm.js';
		$tags[] = '_getTracker';
		$tags[] = 'apis.google.com/js/platform.js';
	}

    return $tags;
}
