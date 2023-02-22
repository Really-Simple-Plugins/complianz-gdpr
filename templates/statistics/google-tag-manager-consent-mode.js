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
	function gtag(){
		dataLayer.push(arguments);
	}
	var consentedCategories = e.detail.categories;
	let preferences = 'denied';
	let statistics = 'denied';
	let marketing = 'denied';

	if (cmplz_in_array( 'preferences', consentedCategories )) {
		preferences = 'granted';
	}

	if (cmplz_in_array( 'statistics', consentedCategories )) {
		statistics = 'granted';
	}

	if (cmplz_in_array( 'marketing', consentedCategories )) {
		marketing = 'granted';
	}

	gtag('consent', 'update', {
		'security_storage': "granted",
		'functionality_storage': "granted",
		'personalization_storage': preferences,
		'analytics_storage': statistics,
		'ad_storage': marketing,
	});
});

(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{GTM_code}');

document.addEventListener("cmplz_revoke", function (e) {
	gtag('consent', 'update', {
		'security_storage': "granted",
		'functionality_storage': "granted",
		'personalization_storage': "denied",
		'analytics_storage': 'denied',
		'ad_storage': "denied",
	});
});
