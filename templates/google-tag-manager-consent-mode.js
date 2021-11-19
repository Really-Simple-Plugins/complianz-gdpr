window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('consent', 'default', {
	'security_storage': "granted",
	'functionality_storage': "granted",
	'personalization_storage': "denied",
	'analytics_storage': 'denied',
	'ad_storage': "denied",
});

dataLayer.push({
	'event': 'default_consent'
});

document.addEventListener("cmplz_fire_categories", function (e) {
	var consentedCategories = e.detail.categories;
	if (cmplz_in_array( 'preferences', consentedCategories )) {
		gtag('consent', 'update', {
			'personalization_storage': 'granted'
		});
	}

	if (cmplz_in_array( 'statistics', consentedCategories )) {
		gtag('consent', 'update', {
			'analytics_storage': 'granted',
		});
	}

	if (cmplz_in_array( 'marketing', consentedCategories )) {
		gtag('consent', 'update', {
			'ad_storage': 'granted',
			'analytics_storage': 'granted',
			'personalization_storage': 'granted'
		});
	} else if ( cmplz_in_array( 'statistics', consentedCategories ) ) {
		gtag('consent', 'update', {
			'analytics_storage': 'granted',
			'personalization_storage': 'granted',
		});
	} else if ( cmplz_in_array( 'preferences', consentedCategories ) ) {
		gtag('consent', 'update', {
			'personalization_storage': 'granted'
		});
	} else {
		gtag('consent', 'update', {
			'security_storage': "granted",
			'functionality_storage': "granted",
			'personalization_storage': "denied",
			'analytics_storage': 'denied',
			'ad_storage': "denied",
		});
	}
});

document.addEventListener("cmplz_cookie_warning_loaded", function (e) {
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','{GTM_code}');
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
