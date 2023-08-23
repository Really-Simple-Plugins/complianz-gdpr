	console.log("running site scan");
	/**
	 * Script to test site for cookies. Never inserted for visitors, only for admin.
	 */
	document.addEventListener("cmplz_cookie_warning_loaded", function (consentData) {
		let acceptBtn = document.querySelector('.cmplz-accept');
		if ( acceptBtn ) {
			acceptBtn.click();
		}
	});
	let cmplz_cookies = get_cookies_array();
	let cmplz_lstorage = get_localstorage_array();
	let cmplz_request = new XMLHttpRequest();

	cmplz_request.open('POST', '{admin_url}' + 'store_cookies', true);
	cmplz_request.setRequestHeader('X-WP-Nonce', '{nonce}');

	var cmplz_data = {
		'cookies': cmplz_cookies,
		'lstorage': cmplz_lstorage,
		'token': '{token}',
		'complianz_id': '{id}'
	};

	cmplz_request.setRequestHeader('Content-type', 'application/json');
	cmplz_request.send(JSON.stringify(cmplz_data));

	function get_localstorage_array() {
		let lstorage = {};
		for (let i = 0; i < localStorage.length; i++) {
			lstorage[localStorage.key(i)] = localStorage.key(i);
		}
		for (let j = 0; j < sessionStorage.length; j++) {
			lstorage[sessionStorage.key(j)] = sessionStorage.key(j);
		}
		return lstorage;
	}

	function get_cookies_array() {
		let cookies = {};
		if ( document.cookie && document.cookie !== '' ) {
			let split = document.cookie.split(';');
			for (let i = 0; i < split.length; i++) {
				var name_value = split[i].split("=");
				name_value[0] = name_value[0].replace(/^ /, '');
				cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
			}
		}
		return cookies;
	}

	function cmplz_function_exists(function_name) {
		if (typeof function_name == 'string') {
			return (typeof window[function_name] == 'function');
		} else {
			return (function_name instanceof Function);
		}
	}

	function deleteAllCookies() {
		document.cookie.split(";").forEach(function (c) {
			document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
		});
	}
