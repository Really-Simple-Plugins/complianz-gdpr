<?php
defined('ABSPATH') or die("you do not have access to this page!");

add_filter('cmplz_known_script_tags', 'cmplz_microsoftads_script');
function cmplz_microsoftads_script($tags)
{
	$tags[] = 'bat.bing.com';

	return $tags;
}


add_filter('cmplz_known_script_tags', 'cmplz_microsoftads_iframetags');
function cmplz_microsoftads_iframetags($tags)
{
	$tags[] = 'bing.com';

	return $tags;
}


function cmplz_microsoftads_uet_consent()
{
	$thirdparty_services = cmplz_get_option('thirdparty_services_on_site');
	$is_microsoft_ads_enabled = in_array('microsoftads', $thirdparty_services);

	if ($is_microsoft_ads_enabled) {
		$script = "
		window.uetq = window.uetq || [];
		window.uetq.push('consent', 'default', {
			'ad_storage': 'denied'
		});

		document.addEventListener('cmplz_fire_categories', function(e) {
			var consentedCategories = e.detail.categories;
			let marketing = 'denied';
			if (cmplz_in_array('marketing', consentedCategories)) {
				marketing = 'granted';
			}
			window.uetq.push('consent', 'update', {
				'ad_storage': marketing
			});
		});

		document.addEventListener('cmplz_revoke', function(e) {
			window.uetq.push('consent', 'update', {
				'ad_storage': 'denied'
			});
		});
	";

		wp_add_inline_script('cmplz-cookiebanner', $script);
	}
};

add_action('wp_enqueue_scripts', 'cmplz_microsoftads_uet_consent', PHP_INT_MAX);
