window['gtag_enable_tcf_support'] = {enable_tcf_support};
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('consent', 'default', {
	'security_storage': "granted",
	'functionality_storage': "granted",
	'personalization_storage': "denied",
	'analytics_storage': 'denied',
	'ad_storage': "denied",
});

document.addEventListener("cmplz_fire_categories", function (e) {
	var consentedCategories = e.detail.categories;
	if (cmplz_in_array( 'preferences', consentedCategories )) {
		gtag('consent', 'update', {
			'ad_storage': 'granted',
			'analytics_storage': 'granted',
			'personalization_storage': 'granted'
		});
	}

	if (cmplz_in_array( 'statistics', consentedCategories )) {
		gtag('consent', 'update', {
			'analytics_storage': 'granted',
			'personalization_storage': 'granted',
		});
	}

	if (cmplz_in_array( 'marketing', consentedCategories )) {
		gtag('consent', 'update', {
			'ad_storage': 'granted',
		});
	}
});

document.addEventListener("cmplz_cookie_warning_loaded", function (e) {
	gtag('js', new Date());
	gtag('config', '{G_code}', {
		cookie_flags:'secure;samesite=none',
	{anonymize_ip}
	});
});

document.addEventListener("cmplz_revoke", function (e) {
	gtag('consent', 'update', {
		'security_storage': "granted",
		'functionality_storage': "granted",
		'personalization_storage': "denied",
		'analytics_storage': 'denied',
		'ad_storage': "denied",
	});
});
