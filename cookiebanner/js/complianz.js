'use strict';
/**
 * Opt in (e.g. EU):
 * default all scripts disabled.
 * cookie banner
 *
 * Opt out (e.g. US):
 * default all scripts enabled
 * opt out cookie banner
 *
 * Other regions:
 * default all scripts enabled
 * no banner
 *
 *
 * For examples to edit the behaviour of the banner, please see https://github.com/really-Simple-Plugins/complianz-integrations
 * */

/**
 * Create an element
 * @param el
 * @param content
 * @returns {*}
 */
function cmplz_create_element(el, content) {
	let obj = document.createElement(el);
	obj.innerHtml = content;
	return obj;
}

/**
 * Add an event
 * @param event
 * @param selector
 * @param callback
 * @param context
 */
function cmplz_add_event(event, selector, callback ) {
	document.addEventListener(event, e => {
		if ( e.target.closest(selector) ) {
			callback(e);
		}
	});
}

/**
 * Check if the element is hidden
 * @param el
 * @returns {boolean}
 */
function cmplz_is_hidden(el) {
	return (el.offsetParent === null)
}

function cmplz_html_decode(input) {
	var doc = new DOMParser().parseFromString(input, "text/html");
	return doc.documentElement.textContent;
}
/**
 * If an anchor is passed for an element which may load only after an ajax call, make sure it will scroll into view.
 */
document.addEventListener('cmplz_manage_consent_container_loaded', function(e){
	let url = window.location.href;
	if ( url.indexOf('#') != -1 ) {
		let end_pos = url.lastIndexOf("?") != -1 ? url.lastIndexOf("?") : undefined;
		var anchor = url.substring(url.indexOf("#") + 1, end_pos);
		const element = document.getElementById(anchor);
		if (element) {
			const y = element.getBoundingClientRect().top + window.pageYOffset - 200;
			window.scrollTo({top: y, behavior: 'smooth'});
		}
	}
});

/**
 * prevent caching of the WP Rest API by varnish or other caching tools
 */
complianz.locale = complianz.locale + '&token='+Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);

/**
 * CustomEvent() polyfill
 * https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent#Polyfill
 */
(function () {
	if (typeof window.CustomEvent === 'function') return false;
	function CustomEvent(event, params) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var evt = document.createEvent('CustomEvent');
		evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
		return evt;
	}
	CustomEvent.prototype = window.Event.prototype;
	window.CustomEvent = CustomEvent;
})();

let cmplz_banner;//look this one up when the cookiebanner loads.
let cmplz_banner_container = document.getElementById('cmplz-cookiebanner-container');
let cmplz_manage_consent_button;
let cmplz_waiting_inline_scripts = [];
let cmplz_waiting_scripts = [];
let cmplz_fired_scripts = [];
let cmplz_placeholder_class_index = 0;
let cmplz_all_scripts_hook_fired = false;
let cmplz_consent_stored_once = false;
let cmplz_categories = [
	'functional',
	'preferences',
	'statistics',
	'marketing',
];

/**
 * Get a cookie by name
 * @param name
 * @returns {string}
 */

window.cmplz_get_cookie = function(name) {
	if (typeof document === 'undefined') {
		return '';
	}
	name = complianz.prefix+name + "=";
	let cArr = document.cookie.split(';');
	for (let i = 0; i < cArr.length; i++) {
		let c = cArr[i].trim();
		if (c.indexOf(name) == 0)
			return c.substring(name.length, c.length);
	}

	return "";
}

/**
 * set a cookie
 * @param name
 * @param value
 * @param use_prefix
 */

window.cmplz_set_cookie = function(name, value, use_prefix) {
	if (typeof document === 'undefined') {
		return;
	}
	if (typeof use_prefix === 'undefined') {
		use_prefix = true;
	}
	let secure = ";secure";
	let date = new Date();
	date.setTime(date.getTime() + (complianz.cookie_expiry * 24 * 60 * 60 * 1000));
	let expires = ";expires=" + date.toGMTString();

	if (window.location.protocol !== "https:") secure = '';

	let domain = cmplz_get_cookie_domain();
	if (domain.length > 0) {
		domain = ";domain=" + domain;
	}
	let prefix = '';
	if ( use_prefix ) {
		prefix = complianz.prefix;
	}
	document.cookie = prefix+name + "=" + value + ";SameSite=Lax" + secure + expires + domain + ";path="+cmplz_get_cookie_path();
}

/**
 * Check if needle occurs in the haystack
 * @param needle
 * @param haystack
 * @returns {boolean}
 */
window.cmplz_in_array = function(needle, haystack) {
	let length = haystack.length;
	for(let i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

/**
 * Retrieve the highest level of consent that has been given
 *
 * */

window.cmplz_highest_accepted_category = function() {
	var consentedCategories = cmplz_accepted_categories();
	if (cmplz_in_array( 'marketing', consentedCategories )) {
		return 'marketing';
	}

	if (cmplz_in_array( 'statistics', consentedCategories )) {
		return 'statistics';
	}

	if (cmplz_in_array( 'preferences', consentedCategories )) {
		return 'preferences';
	}

	return 'functional';
}

/**
 * Accept all categories
 */
window.cmplz_accept_all = function(){
	for (var key in cmplz_categories) {
		if ( cmplz_categories.hasOwnProperty(key) ) {
			cmplz_set_consent(cmplz_categories[key], 'allow');
		}
	}
}

/**
 * Sets all accepted categories as class in body
 */

function cmplz_set_category_as_body_class() {
	let classList = document.body.className.split(/\s+/);
	for (let i = 0; i < classList.length; i++) {
		if ( classList[i].indexOf('cmplz-') !== -1 && classList[i] !== 'cmplz-document' ) {
			document.body.classList.remove( classList[i] );
		}
	}

	let cats = cmplz_accepted_categories();
	for (let i in cats) {
		if ( cats.hasOwnProperty(i) ) {
			document.body.classList.add('cmplz-' + cats[i]);
		}
	}

	let services = cmplz_get_all_service_consents();
	for (let service in services) {
		if ( services.hasOwnProperty(service) && services[service]) {
			document.body.classList.add('cmplz-' + service);
		}
	}

	document.body.classList.add('cmplz-' + complianz.region);
	document.body.classList.add('cmplz-' + complianz.consenttype);
	let event = new CustomEvent('cmplz_set_category_as_bodyclass');
	document.dispatchEvent(event);
}

function cmplz_append_css(css){
	let head = document.getElementsByTagName('head')[0];
	let style = document.createElement('style');
	style.setAttribute('type', 'text/css');
	if (style.styleSheet) {   // IE
		style.styleSheet.cssText = css;
	} else {                // the world
		style.appendChild(document.createTextNode(css));
	}
	head.appendChild(style);
}

function cmplz_load_css( path ) {
	let fileref = document.createElement("link")
	fileref.setAttribute("rel", "stylesheet")
	fileref.setAttribute("type", "text/css")
	fileref.setAttribute("href", path)
	document.getElementsByTagName("head")[0].appendChild(fileref)
}

/**
 * Run script, src or inline
 * @param script //src or inline script
 * @param category
 */

function cmplz_run_script( script, category, type ) {
	let fileref = document.createElement("script");
	fileref.setAttribute("type", "text/javascript");
	if ( type !== 'inline' ) {
		fileref.setAttribute("src", script);
	} else {
		if (typeof script !== 'string') {
			script = script.innerHTML;
		}
		fileref.innerHTML = script;
	}
	//check if already fired
	if ( cmplz_in_array( script, cmplz_fired_scripts) ) {
		return;
	}

	fileref.onload = function () {
		cmplz_maybe_run_waiting_scripts(script, category);
		cmplz_run_after_all_scripts(category);
	}

	try {
		document.getElementsByTagName("head")[0].appendChild(fileref);
		cmplz_fired_scripts.push(script);
	} catch(exception) {
		throw "Something went wrong " + exception + " while loading "+path;
	}
	cmplz_run_after_all_scripts(category);
}


/**
 * Check if there are waiting scripts, and if so, run them.
 * @param script //src or inline script
 * @param category
 */

function cmplz_maybe_run_waiting_scripts( script, category ){
	let waitingScript = cmplz_get_waiting_script(cmplz_waiting_scripts, script);
	if ( waitingScript ) {
		cmplz_run_script( waitingScript, category, 'src' );
	}

	let waiting_inline_script = cmplz_get_waiting_script(cmplz_waiting_inline_scripts, script);
	if (waiting_inline_script) {
		cmplz_run_script(waiting_inline_script, category, 'inline');
	}

	cmplz_run_after_all_scripts(category);
}

/**
 * Set placeholder image as background on the parent div, set notice, and handle height.
 *
 * */

function cmplz_set_blocked_content_container() {
	//to prevent this function to twice run on an element, we add an attribute to each element that has been processed.
	//then skip elements with that element.
	document.querySelectorAll('.cmplz-image').forEach(obj => {
		if ( obj.classList.contains('cmplz-processed') ) {
			return;
		}
		obj.classList.add('cmplz-processed' );
		let service = obj.getAttribute('data-service');
		let blocked_image_container = obj.parentElement;
		blocked_image_container.classList.add('cmplz-blocked-content-container');
		let curIndex = blocked_image_container.getAttribute('data-placeholder_class_index');
		//handle browser native lazy load feature
		if (obj.getAttribute('loading') === 'lazy' ) {
			obj.removeAttribute('loading');
			obj.setAttribute('deferlazy', 1);
		}

		if ( curIndex == null ) {
			cmplz_placeholder_class_index++;
			blocked_image_container.classList.add('cmplz-placeholder-' + cmplz_placeholder_class_index);
			blocked_image_container.classList.add('cmplz-blocked-content-container');
			blocked_image_container.setAttribute('data-placeholder_class_index', cmplz_placeholder_class_index);
			//insert placeholder text
			if ( blocked_image_container.querySelector(".cmplz-blocked-content-notice" ) == null) {
				let placeholderText = complianz.placeholdertext;
				if (typeof placeholderText !== 'undefined') {
					let btn = cmplz_create_element('button', '');
					btn.innerText = placeholderText;
					btn.classList.add('cmplz-blocked-content-notice');
					btn.classList.add('cmplz-accept-marketing');
					btn.setAttribute('data-service', service );
					btn.setAttribute('aria-label', service );
					blocked_image_container.appendChild( btn );
				}
			}
		}


	});

	document.querySelectorAll('.cmplz-placeholder-element').forEach(obj => {
		if ( obj.classList.contains('cmplz-processed') ) {
			return;
		}
		obj.classList.add('cmplz-processed' );
		let service = obj.getAttribute('data-service');

		//we set this element as container with placeholder image
		let blocked_content_container;
		if ( obj.classList.contains('cmplz-iframe')) {
			//handle browser native lazy load feature
			if ( obj.getAttribute('loading') === 'lazy' ) {
				obj.removeAttribute('loading');
				obj.setAttribute('deferlazy', 1);
			}
			blocked_content_container = obj.parentElement;
		} else {
			blocked_content_container = obj;
		}
		let curIndex = blocked_content_container.getAttribute('data-placeholder_class_index');
		//if the blocked content container class is already added, don't add it again
		if ( curIndex == null ) {
			cmplz_placeholder_class_index++;
			blocked_content_container.classList.add('cmplz-placeholder-' + cmplz_placeholder_class_index);
			blocked_content_container.classList.add('cmplz-blocked-content-container');
			blocked_content_container.setAttribute('data-placeholder_class_index', cmplz_placeholder_class_index);
			//insert placeholder text
			if ( blocked_content_container.querySelector( ".cmplz-blocked-content-notice" ) == null ) {
				let placeholderText = complianz.placeholdertext;
				if (typeof placeholderText !== 'undefined') {
					let btn = cmplz_create_element('button', '');
					btn.innerText = placeholderText;
					btn.classList.add('cmplz-blocked-content-notice');
					btn.classList.add('cmplz-accept-marketing');
					btn.setAttribute('data-service', service );
					btn.setAttribute('aria-label', service );
					blocked_content_container.appendChild( btn );
				}
			}

			//handle image size for video
			let src = obj.getAttribute('data-placeholder-image');
			if (typeof src !== 'undefined' && src.length ) {
				src = src.replace('url(', '').replace(')', '').replace(/\"/gi, "");
				cmplz_append_css('.cmplz-placeholder-' + cmplz_placeholder_class_index + ' {background-image: url(' + src + ') !important;}');
				cmplz_set_blocked_content_container_aspect_ratio(obj, src, cmplz_placeholder_class_index);
			}
		}
	});

	/**
	 * In some cases, like ajax loaded content, the placeholders are initialized again. In that case, the scripts may need to be fired again as well.
	 * We're assuming that statistics scripts won't be loaded with ajax, so we only load marketing level scripts
	 *
	 */
	if ( cmplz_has_consent('marketing') ) {
		cmplz_enable_category('marketing');
	}

}

/**
 * Set the height of an image relative to the width, depending on the image widht/height aspect ratio.
 *
 *
 * */

function cmplz_set_blocked_content_container_aspect_ratio(container, src, placeholder_class_index) {
	if ( container == null ) return;

	//we set the first parent div as container with placeholder image
	let blocked_content_container = container.parentElement;

	//handle image size for video
	let img = new Image();
	img.addEventListener("load", function () {
		let imgWidth = this.naturalWidth;
		let imgHeight = this.naturalHeight;

		//prevent division by zero.
		if (imgWidth === 0) imgWidth = 1;
		let w = blocked_content_container.clientWidth;
		let h = imgHeight * (w / imgWidth);

		let heightCSS = '';
		if (src.indexOf('placeholder.jpg') === -1) {
			heightCSS = 'height:' + h + 'px;';
		}
		cmplz_append_css('.cmplz-placeholder-' + placeholder_class_index + ' {' + heightCSS + '}');
	});
	img.src = src;
}
/**
 * Keep window aspect ratio in sync when window resizes
 * To lower the number of times this code is executed, it is done with a timeout.
 *
 * */

var cmplzResizeTimer;
window.addEventListener('resize', function(event) {
	clearTimeout(cmplzResizeTimer);
	cmplzResizeTimer = setTimeout( cmplz_set_blocked_content_container, 500);
}, true);

/**
 * 	we run this function also on an interval, because with ajax loaded content, the placeholders would otherwise not be handled.
 */
if ( complianz.block_ajax_content == 1 ) {
	setInterval(function () {
		cmplz_set_blocked_content_container();
	}, 2000);
}

/**
 * Enable scripts that were blocked
 *
 * */

function cmplz_enable_category(category, service) {
	if ( complianz.tm_categories == 1 && category !== '') {
		cmplz_run_tm_event(category);
	}

	service = typeof service !== 'undefined' ? service : 'do_not_match';
	if ( category === '' ) category = 'do_not_match';

	if ( category === 'functional' ) {
		return;
	}
	//enable cookies for integrations
	if ( category === 'marketing' ) {
		cmplz_set_integrations_cookies();
	}

	//remove accept cookie notice overlay
	document.querySelectorAll('.cmplz-blocked-content-notice.cmplz-accept-'+category+', .cmplz-blocked-content-notice[data-service='+service+']').forEach(obj => {
		obj.parentNode.removeChild(obj);
	});

	document.querySelectorAll('[data-category='+category+'], [data-service='+service+']').forEach(obj => {
		//if a category is activated, but this specific service is denied, skip.
		let elementService = obj.getAttribute('data-service');
		if ( cmplz_is_service_denied(elementService)) {
			return;
		}

		//if native class is included, it isn't blocked, so will have run already
		if ( obj.getAttribute('data-category') === 'functional' ) {
			return;
		}

		if ( obj.classList.contains('cmplz-activated') ) {
			return;
		}

		let tagName = obj.tagName;
		if (tagName==='STYLE'){
			obj.classList.add('cmplz-activated' );
			let src = obj.getAttribute('data-href');
			cmplz_load_css( src, category);
		} else if (tagName ==='IMG'){
			obj.classList.add('cmplz-activated' );
			let src = obj.getAttribute('data-src-cmplz');
			obj.setAttribute('src', src);
			//handle browser native lazy load feature
			if ( obj.getAttribute('data-deferlazy') ) {
				obj.setAttribute('loading', 'lazy');
			}
			let blocked_content_container = obj.closest('.cmplz-blocked-content-container');
			let cssIndex = blocked_content_container.getAttribute('data-placeholder_class_index');
			blocked_content_container.classList.remove('cmplz-blocked-content-container');
			blocked_content_container.classList.remove('cmplz-placeholder-' + cssIndex);
		} else if (tagName==='IFRAME'){
			obj.classList.add('cmplz-activated' );
			let src = obj.getAttribute('data-src-cmplz');
			//check if there's an autoplay value we need to pass on
			let autoplay = cmplz_get_url_parameter(obj.getAttribute('src'), 'autoplay');
			if ( autoplay === '1' ) src = src + '&autoplay=1';

			obj.addEventListener('load', (event) => {
				//handle browser native lazy load feature
				if ( obj.getAttribute('data-deferlazy') ) {
					obj.setAttribute('loading', 'lazy');
				}

				//we get the closest, not the parent, because a script could have inserted a div in the meantime.
				let blocked_content_container = obj.closest('.cmplz-blocked-content-container');
				let cssIndex = blocked_content_container.getAttribute('data-placeholder_class_index');
				blocked_content_container.classList.remove('cmplz-blocked-content-container');
				blocked_content_container.classList.remove('cmplz-placeholder-' + cssIndex);
				obj.classList.remove('cmplz-iframe-styles');
				obj.classList.remove('cmplz-iframe');
				obj.classList.remove('video-wrap');
			});
			obj.setAttribute('src', src);
		} else if (obj.classList.contains('cmplz-placeholder-element')) {
			obj.classList.add('cmplz-activated' );
			//other services, no iframe, with placeholders
			//remove the added classes
			let cssIndex = obj.getAttribute('data-placeholder_class_index');
			obj.classList.remove('cmplz-blocked-content-container');
			obj.classList.remove('cmplz-placeholder-' + cssIndex);
		}

		let details = new Object();
		details.category = category;
		details.service = service;
		let event = new CustomEvent('cmplz_category_enabled', { detail: details });
		document.dispatchEvent(event);
	});

	/**
	 * Let's activate the scripts
	 */

		//create list of waiting scripts
	let scriptElements = document.querySelectorAll('script[data-category='+category+'], script[data-service='+service+']');
	scriptElements.forEach(obj => {
		let waitfor = obj.getAttribute('data-waitfor');
		let src = obj.getAttribute('src');
		if ( waitfor ) {
			if ( src ) {
				cmplz_waiting_scripts[waitfor] = src;
			} else if ( obj.innerText.length > 0 ) {
				cmplz_waiting_inline_scripts[waitfor] = obj;
			}
		}
	});

	//scripts: set to text/javascript
	scriptElements.forEach(obj => {
		if ( obj.classList.contains('cmplz-activated') ) {
			return;
		}
		obj.classList.add('cmplz-activated' );
		let src = obj.getAttribute('src');
		if ( src ) {
			obj.setAttribute('type', 'text/javascript');
			//check if this src or txt is in a waiting script. If so, skip.
			if ( cmplz_is_waiting_script(cmplz_waiting_scripts, src) ) {
				return;
			}

			if ( obj.getAttribute('data-post_scribe_id') ) {
				let psID = '#' + obj.getAttribute('data-post_scribe_id');
				let postScribeObj = document.querySelector(psID);
				if ( postScribeObj ) {
					postScribeObj.innerHtml('');
					postscribe(psID, '<script src=' + src + '></script>');
				}
			} else {
				cmplz_run_script(src, category, 'src' );
			}

		} else if (obj.innerText.length > 0 ) {
			//check if this src or txt is in a waiting script. If so, skip.
			if (cmplz_is_waiting_script(cmplz_waiting_inline_scripts, obj.innerText )) {
				return;
			}

			cmplz_run_script( obj.innerText, category, 'inline' );
		}
	});

	//fire an event so custom scripts can hook into this.
	let details = new Object();
	details.category = category;
	details.categories = cmplz_accepted_categories();
	details.services = cmplz_get_all_service_consents();
	details.region = complianz.region;
	let event = new CustomEvent('cmplz_enable_category', { detail: details });
	document.dispatchEvent(event);

	//if there are no blockable scripts at all, we still want to provide a hook
	//in most cases, this script fires too early, and won't run yet. In that
	//case it's run from the script activation callbacks.
	cmplz_run_after_all_scripts(category);
}



/**
 * check if the passed source has a waiting script that should be executed, and return it if so.
 * @param waiting_scripts
 * @param src
 * @returns {*}
 */

function cmplz_get_waiting_script(waiting_scripts, src) {
	for (let waitfor in waiting_scripts) {
		if ( waiting_scripts.hasOwnProperty(waitfor)) {
			let waitingScript;//recaptcha/api.js, waitfor="gregaptcha"
			if (waiting_scripts.hasOwnProperty(waitfor)) {
				waitingScript = waiting_scripts[waitfor];
				if (typeof waitingScript !== 'string') {
					waitingScript = waitingScript.innerText;
				}
				if (src.indexOf(waitfor) !== -1) {
					let output = waiting_scripts[waitfor];
					delete waiting_scripts[waitfor];
					return output;
				}
			}
		}
	}

	return false;
}

/**
 * Because we need a key=>value array in javascript, the .length check for an empty array doesn't work.
 * @param arr
 * @returns {boolean}
 */
function cmplz_array_is_empty(arr) {
	for (let key in arr) {
		if (arr.hasOwnProperty(key)) {
			return false;
		}
	}

	return true;
}

/**
 * Check if the passed src or script is waiting for another script and should not execute
 * @param waiting_scripts
 * @param srcOrScript
 */

function cmplz_is_waiting_script(waiting_scripts, srcOrScript) {
	for (let waitfor in waiting_scripts) {
		if ( waiting_scripts.hasOwnProperty(waitfor) ) {
			let waitingScript = waiting_scripts[waitfor];
			if (typeof waitingScript !== 'string') waitingScript = waitingScript.innerText;

			if (srcOrScript.indexOf(waitingScript) !== -1 || waitingScript.indexOf(srcOrScript) !== -1) {
				return true;
			}
		}
	}
	return false;
}

/**
 * if all scripts have been executed, fire a hook.
 */

function cmplz_run_after_all_scripts(category) {
	if (!cmplz_all_scripts_hook_fired && cmplz_array_is_empty(cmplz_waiting_inline_scripts) && cmplz_array_is_empty(cmplz_waiting_scripts) ) {
		let event = new CustomEvent('cmplz_run_after_all_scripts', { detail: category });
		document.dispatchEvent(event);
		cmplz_all_scripts_hook_fired = true;
	}
}

/**
 * Fire an event in Tag Manager
 *
 *
 * */

let cmplz_fired_events = [];
function cmplz_run_tm_event(category) {
	if (cmplz_fired_events.indexOf(category) === -1) {
		cmplz_fired_events.push(category);
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
			'event': complianz.prefix+'event_'+category
		});
		let event = new CustomEvent('cmplz_tag_manager_event', { detail: category });
		document.dispatchEvent(event);
	}
}

window.conditionally_show_banner = function() {
	//merge userdata with complianz data, in case a b testing is used with user specific cookie banner data
	//objects are merged so user_data will override data in complianz object
	complianz = cmplz_merge_object( complianz, cmplz_user_data );
	//check if we need to redirect to another legal document, for a specific region
	cmplz_maybe_auto_redirect();
	cmplz_set_blocked_content_container();

	/**
	 * Integration with WordPress, tell what kind of consent type we're using, then fire an event
	 */

	window.wp_consent_type = complianz.consenttype;
	let event = new CustomEvent('wp_consent_type_defined');
	document.dispatchEvent( event );
	event = new CustomEvent('cmplz_before_cookiebanner' );
	document.dispatchEvent(event);
	if ( complianz.forceEnableStats == 1 ) {
		cmplz_set_consent('statistics', 'allow');
	}

	let rev_cats = cmplz_categories.reverse();
	for (let key in rev_cats) {
		if ( rev_cats.hasOwnProperty(key) ) {
			let category = cmplz_categories[key];
			if (cmplz_has_consent(category)) {
				cmplz_enable_category(category);
			}
		}
	}

	if ( cmplz_exists_service_consent() ) {
		//if any service is enabled, allow the general services also, because some services are partially 'general'
		cmplz_enable_category('', 'general');
		let services = cmplz_get_services_on_page();
		for (let key in services) {
			if ( services.hasOwnProperty(key) ) {
				let service = services[key];
				if (service.length == 0) continue;
				if (cmplz_has_service_consent(service)) {
					document.querySelectorAll('.cmplz-accept-service[data-service=' + service + ']').forEach(obj => {
						obj.checked = true;
					});

					cmplz_enable_category('', service);
				}
			}
		}
	}

	cmplz_sync_category_checkboxes();
	cmplz_integrations_init();
	cmplz_check_cookie_policy_id();
	cmplz_set_up_auto_dismiss();

	//if we're on the cookie policy page, we dynamically load the applicable revoke checkbox
	cmplz_load_manage_consent_container();

	event = new CustomEvent('cmplz_cookie_banner_data', { detail: complianz });
	document.dispatchEvent(event);

	//if no categories were saved before, we do it now
	if ( cmplz_get_cookie('saved_categories') === '' ) {
		//for Non optin/optout visitors, and DNT users, we just track the no-warning option
		if ( complianz.consenttype !== 'optin' && complianz.consenttype !== 'optout' ) {
			cmplz_track_status( 'no_warning' );
		} else if ( complianz.do_not_track ) {
			cmplz_track_status('do_not_track' );
		}
	}

	cmplz_set_category_as_body_class();
	//fire cats event, but do not fire a track, as we do this on exit.
	cmplz_fire_categories_event();
	if (!complianz.do_not_track) {
		if (complianz.consenttype === 'optin') {
			if (complianz.forceEnableStats) {
				cmplz_enable_category('statistics');
			}
			console.log('opt-in');
			show_cookie_banner();
		} else if (complianz.consenttype === 'optout') {
			console.log('opt-out');
			show_cookie_banner();
		} else {
			console.log('other consent type, no cookie warning');
			//on other consent types, all scripts are enabled by default.
			cmplz_accept_all();
		}

	} else {
		cmplz_track_status( 'do_not_track' );
	}
}

/**
 * Get list of services active on the page
 * @returns {*[]}
 */
function cmplz_get_services_on_page(){
	let services=[];
	document.querySelectorAll('[data-service]').forEach(obj => {
		let service = obj.getAttribute('data-service');
		if ( services.indexOf(service)==-1 ) {
			services.push(service);
		}
	});
	return services;
}

/**
 * Run the actual cookie warning
 *
 * */


window.show_cookie_banner = function () {
	let disableCookiebanner = complianz.disable_cookiebanner || cmplz_is_speedbot();
	//do not show banner when manage consent area on cookie policy is visible
	//when users use only the shortcode, the manage consent container is not active, but the dropdown cookie policy class is.
	//when the complianz shortcode is used, the dropdown cookie policy class is loaded late because it's loaded with javascript.
	let tmpDismissCookiebanner = false;
	if ( document.querySelector('#cmplz-manage-consent-container') || document.querySelector('.cmplz-dropdown-cookiepolicy') ) {
		tmpDismissCookiebanner = true;
	}

	var fragment = document.createDocumentFragment();
	let container = document.getElementById('cmplz-cookiebanner-container');
	if (container) {
		fragment.appendChild(container);
		document.body.prepend(fragment);
	}

	let link = document.createElement("link");
	let pageLinks = complianz.page_links[complianz.region];
	//get correct banner, based on banner_id
	cmplz_banner = document.querySelector('.cmplz-cookiebanner.banner-'+complianz.user_banner_id+'.'+complianz.consenttype);
	cmplz_manage_consent_button = document.querySelector('#cmplz-manage-consent .cmplz-manage-consent.manage-consent-'+complianz.user_banner_id);
	let css_file_url = complianz.css_file.replace('type', complianz.consenttype ).replace('banner_id', complianz.user_banner_id);
	if ( complianz.css_file.indexOf('cookiebanner/css/defaults/banner') != -1 ) {
		console.log('Fallback default css file used. Please re-save banner settings, or check file writing permissions in uploads directory');
	}
	link.href = css_file_url;
	link.type = "text/css";
	link.rel = "stylesheet";
	link.onload = function () {
		if ( !disableCookiebanner ) {
			cmplz_banner.classList.remove('cmplz-hidden');
			cmplz_manage_consent_button.classList.remove('cmplz-hidden');
		}
	}
	document.getElementsByTagName("head")[0].appendChild(link);
	if ( cmplz_banner && !disableCookiebanner ) {
		cmplz_banner.querySelectorAll('.cmplz-links a:not(.cmplz-external), .cmplz-buttons a:not(.cmplz-external)').forEach(obj => {
			let docElement = obj;
			docElement.classList.add('cmplz-hidden');
			for (let pageType in pageLinks) {
				if (pageLinks.hasOwnProperty(pageType) && docElement.classList.contains(pageType)) {
					docElement.setAttribute('href', pageLinks[pageType]['url'] + docElement.getAttribute('data-relative_url'));
					if (docElement.innerText === '{title}') {
						docElement.innerText = cmplz_html_decode(pageLinks[pageType]['title']);
					}
					docElement.classList.remove('cmplz-hidden');
				}
			}
		});

		cmplz_set_banner_status();
		//we don't use the setBannerStatus function here, as we don't want to save it in a cookie now.
		if ( tmpDismissCookiebanner ) {
			cmplz_banner.classList.remove('cmplz-show');
			cmplz_banner.classList.add('cmplz-dismissed');
			cmplz_manage_consent_button.classList.remove('cmplz-dismissed');
			cmplz_manage_consent_button.classList.add('cmplz-show');
		}
	}

	let event = new CustomEvent('cmplz_cookie_warning_loaded', {detail: complianz.region});
	document.dispatchEvent(event);
}
/**
 * Get the status of the banner: dismissed | show
 * @returns {string}
 */
window.cmplz_get_banner_status = function (){
	return cmplz_get_cookie('banner-status');
}

/**
 * Set the banner status so it will be either shown or dismissed, and store it in a cookie.
 * @param status (optional)
 */

window.cmplz_set_banner_status = function ( status ){
	let prevStatus = cmplz_get_cookie('banner-status');
	status = typeof status !== 'undefined' ? status : prevStatus;
	if ( status !== prevStatus ) {
		cmplz_set_cookie( 'banner-status', status );
	}
	if (status.length===0){
		status = 'show';
	}

	if (status==='show') {
		prevStatus = 'dismissed';
	} else {
		prevStatus = 'show';
	}

	if ( cmplz_banner && status.length>0 ) {
		cmplz_banner.classList.remove('cmplz-'+prevStatus);
		cmplz_banner.classList.add('cmplz-'+status );
		if ( cmplz_manage_consent_button ) {
			cmplz_manage_consent_button.classList.add('cmplz-'+prevStatus);
			cmplz_manage_consent_button.classList.remove('cmplz-'+status);
		}
	}

	if ( cmplz_banner_container && complianz.soft_cookiewall ) {
		cmplz_banner_container.classList.remove('cmplz-'+prevStatus);
		cmplz_banner_container.classList.add('cmplz-'+status );
		cmplz_banner_container.classList.add('cmplz-soft-cookiewall');
	}
	var event = new CustomEvent('cmplz_banner_status', { detail: status });
	document.dispatchEvent(event);
}

/**
 * Check if current visitor is a bot
 *
 * @returns {boolean}
 */
function cmplz_is_bot(){
	var botPattern = "(googlebot\/|Googlebot-Mobile|Googlebot-Image|Google favicon|Mediapartners-Google|bingbot|slurp|java|wget|curl|Commons-HttpClient|Python-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail.RU_Bot|discobot|heritrix|findthatfile|europarchive.org|NerdByNature.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web-archive-net.com.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks-robot|it2media-domain-crawler|ip-web-crawler.com|siteexplorer.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e.net|GrapeshotCrawler|urlappendbot|brainobot|fr-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf.fr_bot|A6-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j-asr|Domain Re-Animator Bot|AddThis)";
	var reBot = new RegExp(botPattern, 'i');
	var userAgent = navigator.userAgent;
	if ( reBot.test(userAgent) ) {
		return true;
	} else {
		return false;
	}
}
/**
 * Check if current visitor is a speedbot
 *
 * @returns {boolean}
 */
function cmplz_is_speedbot(){
	var userAgent = navigator.userAgent;
	var speedBotPattern = "(GTmetrix|pingdom|pingbot|Lighthouse)";
	var speedBot = new RegExp(speedBotPattern, 'i');

	if ( speedBot.test(userAgent) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if there is consent for a category or service
 * @param category
 * @returns {boolean}
 */
window.cmplz_has_consent = function ( category ){
	if ( cmplz_is_bot() ) {
		return true;
	}

	if ( category === 'functional' ) return true;
	var has_consent, value;

	/**
	 * categories
	 */
	value = cmplz_get_cookie(category);
	if (complianz.consenttype === 'optout' && value === '') {
		//if it's opt out and no cookie is set we should return true
		has_consent = true;
	} else {
		//all other situations, return only true if value is allow
		has_consent = (value === 'allow');
	}

	return has_consent;
}

/**
 * Check if a service has consent
 * @param service
 * @returns {boolean|*}
 */
window.cmplz_is_service_denied = function ( service ) {
	//in opt out, there's no consent per service. so it's always true.
	if ( complianz.consenttype === 'optout' ) {
		return false;
	}

	//Check if it's in the consented services cookie
	var consented_services_json = cmplz_get_cookie('consented_services');
	var consented_services;
	try {
		consented_services = JSON.parse(consented_services_json);
	} catch (e) {
		consented_services = {};
	}

	if ( !consented_services.hasOwnProperty( service ) ){
		return false;
	} else {
		return !consented_services[service];
	}
}

/**
 * Check if a service has consent
 * @param service
 * @returns {boolean|*}
 */
window.cmplz_has_service_consent = function ( service ) {
	//in opt out, there's no consent per service. so it's always true.
	if ( complianz.consenttype === 'optout' ) {
		return true;
	}

	//Check if it's in the consented services cookie
	var consented_services_json = cmplz_get_cookie('consented_services');
	var consented_services;
	try {
		consented_services = JSON.parse(consented_services_json);
	} catch (e) {
		consented_services = {};
	}

	if ( !consented_services.hasOwnProperty( service ) ){
		return false;
	} else {
		return consented_services[service];
	}
}

/**
 * check if there's at least one service with consent
 * @returns {boolean}
 */
function cmplz_exists_service_consent(){
	var consented_services_json = cmplz_get_cookie('consented_services');
	var consented_services;
	try {
		consented_services = JSON.parse(consented_services_json);
		for (const key in consented_services) {
			if ( consented_services.hasOwnProperty(key) ) {
				if (consented_services[key] == true) {
					return true;
				}
			}
		}
	} catch (e) {
		return false;
	}
	return false;
}

/**
 * Set consent for a service
 * @param service
 * @param consented
 */
function cmplz_set_service_consent( service, consented ){
	var consented_services_json = cmplz_get_cookie('consented_services');
	var consented_services;
	try {
		consented_services = JSON.parse(consented_services_json);
	} catch (e) {
		consented_services = {};
	}
	consented_services[service] = consented;
	cmplz_set_cookie('consented_services', JSON.stringify(consented_services) );
}

/**
 * Remove all service consents
 */
function cmplz_clear_all_service_consents(){
	cmplz_set_cookie('consented_services', '');
}

/**
 * Get all consented or denied services
 */

function cmplz_get_all_service_consents(){
	var consented_services_json = cmplz_get_cookie('consented_services');
	var consented_services;
	try {
		consented_services = JSON.parse(consented_services_json);
	} catch (e) {
		consented_services = {};
	}
	return consented_services;
}
/**
 * Get cookie path
 * @returns {*}
 */
function cmplz_get_cookie_path(){
	return typeof complianz.cookie_path !== 'undefined' && complianz.cookie_path !== '' ? complianz.cookie_path : '/';
}

/**
 * retrieve domain to set the cookies on
 * @returns {string}
 */
function cmplz_get_cookie_domain(){
	var domain = '';
	if ( complianz.set_cookies_on_root == 1 && complianz.cookie_domain.length>3){
		domain = complianz.cookie_domain;
	}
	if (domain.indexOf('localhost') !== -1 ) {
		domain = '';
	}
	return domain;
}

/**
 * Set consent for a category
 * @param category
 * @param value
 */
window.cmplz_set_consent = function (category, value){
	cmplz_set_accepted_cookie_policy_id();
	var previous_value = cmplz_get_cookie(category);
	//do not trigger a change event if nothing has changed.
	if ( previous_value === value ) {
		return;
	}

	//keep checkboxes in banner and on cookie policy in sync
	var checked = value === 'allow';
	document.querySelectorAll('input.cmplz-'+category).forEach(obj => {
		obj.checked = checked;
	});

	cmplz_set_cookie(category, value);
	if ( value === 'allow' ) {
		cmplz_enable_category(category);
	}

	cmplz_wp_set_consent(category, value);
	if ( category === 'statistics' ) {
		cmplz_wp_set_consent('statistics-anonymous', 'allow');
	}

	var details = new Object();
	details.category = category;
	details.value = value;
	details.region = complianz.region;
	details.categories = cmplz_accepted_categories();
	var event = new CustomEvent('cmplz_status_change', { detail: details });
	document.dispatchEvent(event);

	if ( category === 'marketing' && value === 'deny' ) {
		cmplz_integrations_revoke();
		//give the code some time to finish, so our track status code can send a signal to the backend.
		setTimeout(function(){
			location.reload()
		}, 500);
	}
}

/**
 * We use ajax to check the consenttype based on region, otherwise caching could prevent the user specific warning
 *
 * */

var cmplz_user_data = [];
//check if it's already stored
if (typeof (Storage) !== "undefined" && sessionStorage.cmplz_user_data) {
	cmplz_user_data = JSON.parse(sessionStorage.cmplz_user_data);
}

//if not stored yet, load. As features in the user object can be changed on updates, we also check for the version
if ( complianz.geoip == 1 && (cmplz_user_data.length == 0 || (cmplz_user_data.version !== complianz.version) || (cmplz_user_data.banner_version !== complianz.banner_version)) ) {
	var request = new XMLHttpRequest();
	request.open('GET', complianz.url+'banner?'+complianz.locale, true);
	request.setRequestHeader('Content-type', 'application/json');
	request.send();
	request.onload = function() {
		cmplz_user_data = JSON.parse(request.response);
		sessionStorage.cmplz_user_data = JSON.stringify(cmplz_user_data);
		conditionally_show_banner();
	};
} else {
	conditionally_show_banner();
}

/**
 *  when ab testing, or using records of consent, we want to keep track of the unique user id
 */

if ( complianz.store_consent == 1 ) {
	var cmplz_id_cookie = cmplz_get_cookie('id');
	var cmplz_id_session = '';
	var cmplz_id = '';
	if (typeof (Storage) !== "undefined" && sessionStorage.cmplz_id) {
		cmplz_id_session = JSON.parse(sessionStorage.cmplz_id);
	}

	if ( cmplz_id_cookie.length == 0 && cmplz_id_session.length > 0 ) {
		cmplz_id = cmplz_id_session;
		cmplz_set_cookie('id', cmplz_id );
	}

	if ( cmplz_id_cookie.length > 0 && cmplz_id_session.length == 0 ) {
		cmplz_id = cmplz_id_cookie;
	}

	if ( typeof (Storage) !== "undefined" ) {
		sessionStorage.cmplz_id = JSON.stringify(cmplz_id);
	}
}

// visibilitychange and pagehide work in more browsers hence we check if they are supported and try to use them
document.addEventListener('visibilitychange', function () {
	if ( document.visibilityState === 'hidden' ) {
		cmplz_track_status_end();
	}
});
window.addEventListener("pagehide", cmplz_track_status_end, false );
window.addEventListener("beforeunload", cmplz_track_status_end, false );

function cmplz_track_status_end(){
	if ( !cmplz_consent_stored_once ) {
		cmplz_track_status();
	}
}

/**
 * This creates an API which devs can use to trigger actions in complianz.
 */
document.addEventListener('cmplz_consent_action', function (e) {
	cmplz_set_consent( e.detail.category , 'allow' );
	cmplz_fire_categories_event();
	cmplz_track_status();
});

/**
 * Deny all categories, and reload if needed.
 */
window.cmplz_deny_all = function(){
	for (var key in cmplz_categories) {
		if ( cmplz_categories.hasOwnProperty(key) ) {
			cmplz_set_consent(cmplz_categories[key], 'deny');
		}
	}
	var consentLevel = cmplz_highest_accepted_category();
	var reload = false;

	if (consentLevel !== 'functional' || cmplz_exists_service_consent() ) {
		reload = true;
	}
	if ( cmplz_clear_all_complianz_cookies('cmplz_service') ) {
		reload = true;
	}

	//has to be after the check if should be reloaded, otherwise that check won't work.
	cmplz_clear_all_service_consents();
	cmplz_integrations_revoke();
	cmplz_fire_categories_event();
	cmplz_track_status();

	var event = new CustomEvent('cmplz_revoke', { detail: reload });
	document.dispatchEvent(event);

	//we need to let the iab extension handle the reload, otherwise the consent revoke might not be ready yet.
	if ( !complianz.tcf_active && reload ) {
		location.reload();
	}
}

/**
 * For both opt-in and opt-out, clicking cmplz-accept should result in accepting all categories
 */
cmplz_add_event('click', '.cmplz-accept', function(e){
	e.preventDefault();
	cmplz_accept_all();
	cmplz_set_banner_status('dismissed');
	cmplz_fire_categories_event();
	cmplz_track_status();
});

/**
 *  Accept marketing cookies by clicking any other link cookie acceptance from a custom link
 */

cmplz_add_event('click', '.cmplz-accept-marketing', function(e){
	e.preventDefault();
	let obj = e.target;
	var service = obj.getAttribute('data-service');
	if ( complianz.clean_cookies == 1 && typeof service !== 'undefined' && service ){
		cmplz_set_service_consent(service, true);
		cmplz_enable_category('', 'general');
		cmplz_enable_category('', service);
	} else {
		cmplz_enable_category('', 'general');
		cmplz_set_consent('marketing', 'allow' );
	}
	cmplz_set_banner_status('dismissed');
	cmplz_fire_categories_event();
	cmplz_track_status();
});



/**
 * Accept a specific service
 */
cmplz_add_event('click', '.cmplz-accept-service', function(e){
	e.preventDefault();
	let obj = e.target;
	//that is for the change event, for input checkboxes
	let tagName = obj.tagName;
	if ( tagName === 'INPUT' ) return;
	let service = obj.getAttribute('data-service');
	if ( typeof service !== 'undefined' ){
		cmplz_set_service_consent(service, true);
		cmplz_enable_category('', 'general');
		cmplz_enable_category('', service);
	}
	cmplz_fire_categories_event();
	cmplz_track_status();
});

/**
 * Accept a specific service
 */
cmplz_add_event('change', '.cmplz-accept-service', function(e){
	let obj = e.target;
	let service = obj.getAttribute('data-service');
	if ( typeof service !== 'undefined' ){
		if ( obj.checked ){
			cmplz_set_service_consent(service, true);
			cmplz_enable_category('', service);
		} else {
			cmplz_set_service_consent(service, false);
			//give our track status time to finish
			setTimeout(function(){
				location.reload()
			}, 500);
		}
	}
	cmplz_fire_categories_event();
	cmplz_track_status();
});



/**
 * On the banner, clicking a category should fire the category only after the save button is clicked.
 *
 */
cmplz_add_event('click', '.cmplz-save-preferences', function(e){
	let obj = e.target;
	cmplz_banner = obj.closest('.cmplz-cookiebanner');
	for (var key in cmplz_categories) {
		if ( cmplz_categories.hasOwnProperty(key) ) {
			var category = cmplz_categories[key];
			var categoryElement = cmplz_banner.querySelector('input.cmplz-' + category);
			if (categoryElement) {
				if (categoryElement.checked) {
					cmplz_set_consent(category, 'allow');
				} else {
					cmplz_set_consent(category, 'deny');
				}
			}
		}
	}
	cmplz_set_banner_status('dismissed');
	cmplz_fire_categories_event();
	cmplz_track_status();
});

cmplz_add_event('click', '.cmplz-close', function(e){
	cmplz_set_banner_status('dismissed');
});

cmplz_add_event('click', '.cmplz-view-preferences', function(e){
	let obj = e.target;
	cmplz_banner = obj.closest('.cmplz-cookiebanner');
	if ( cmplz_banner.querySelector('.cmplz-categories').classList.contains('cmplz-fade-in')) {
		cmplz_banner.classList.remove('cmplz-categories-visible');
		cmplz_banner.querySelector('.cmplz-categories' ).classList.remove('cmplz-fade-in');
		cmplz_banner.querySelector('.cmplz-view-preferences' ).style.display = 'block';
		cmplz_banner.querySelector('.cmplz-save-preferences' ).style.display = 'none';
	} else {
		cmplz_banner.classList.add('cmplz-categories-visible');
		cmplz_banner.querySelector('.cmplz-categories' ).classList.add('cmplz-fade-in');
		cmplz_banner.querySelector('.cmplz-view-preferences' ).style.display = 'none';
		cmplz_banner.querySelector('.cmplz-save-preferences' ).style.display = 'block';
	}
});
/**
 * On the cookie policy, clicking a category should fire the category immediately
 *
 */
cmplz_add_event('change', '.cmplz-manage-consent-container .cmplz-category', function(e){
	for (var key in cmplz_categories) {
		if ( cmplz_categories.hasOwnProperty(key) ) {
			var category = cmplz_categories[key];
			var categoryElement = document.querySelector('.cmplz-manage-consent-container input.cmplz-' + category);
			if (categoryElement) {
				if (categoryElement.checked) {
					cmplz_set_consent(category, 'allow');
				} else {
					cmplz_set_consent(category, 'deny');
				}
				cmplz_set_banner_status('dismissed');
				cmplz_fire_categories_event();
				cmplz_track_status();
			}
		}
	}

});

cmplz_add_event('click', '.cmplz-deny', function(e){
	e.preventDefault();
	cmplz_set_banner_status('dismissed');
	cmplz_deny_all();

});

cmplz_add_event('click', 'button.cmplz-manage-settings', function(e){
	e.preventDefault();
	var catsContainer = document.querySelector('.cmplz-cookiebanner .cmplz-categories');
	var saveSettings = document.querySelector('.cmplz-save-settings');
	var manageSettings = document.querySelector('button.cmplz-manage-settings');
	if ( !cmplz_is_hidden(catsContainer) ){
		saveSettings.style.display='none';
		manageSettings.style.display = 'block';
		catsContainer.style.display='none';
	} else {
		saveSettings.style.display = 'block';
		manageSettings.style.display='none';
		catsContainer.style.display = 'block';
	}
});

cmplz_add_event('click', 'button.cmplz-manage-consent', function(e){
	e.preventDefault();
	cmplz_set_banner_status('show');
});

/**
 * Handle dismiss on scroll and dismiss on timeout
 */
function cmplz_set_up_auto_dismiss() {
	if ( complianz.consenttype === 'optout' ) {
		if ( complianz.dismiss_on_scroll==1 ) {
			var onWindowScroll = function(evt) {
				if (window.pageYOffset > Math.floor(400)) {
					cmplz_set_banner_status('dismissed');
					cmplz_fire_categories_event();
					cmplz_track_status();
					window.removeEventListener('scroll', onWindowScroll);
					this.onWindowScroll = null;
				}
			};
			window.addEventListener('scroll', onWindowScroll);
		}

		var delay = parseInt(complianz.dismiss_timeout);
		if ( delay > 0 ) {
			var cmplzDismissTimeout = window.setTimeout(function () {
				cmplz_set_banner_status('dismissed');
				cmplz_fire_categories_event();
				cmplz_track_status();
			}, Math.floor(delay));
		}
	}
}

function cmplz_fire_categories_event(){
	let details = new Object();
	details.category = cmplz_highest_accepted_category();
	details.categories = cmplz_accepted_categories();
	details.region = complianz.region;
	event = new CustomEvent('cmplz_fire_categories', { detail: details });
	document.dispatchEvent(event);
}
/**
 * Track the status of current consent
 * @param status
 */

function cmplz_track_status( status ) {
	var cats = [];
	status = typeof status !== 'undefined' ? status : false;

	var event = new CustomEvent('cmplz_track_status', { detail: status });
	document.dispatchEvent(event);

	if ( !status ) {
		cats = cmplz_accepted_categories();
	} else {
		cats = [status];
	}
	cmplz_set_category_as_body_class();
	var saved_cats, saved_services;
	try {saved_cats = JSON.parse(cmplz_get_cookie('saved_categories'));} catch (e) {saved_cats = {};}
	try {saved_services = JSON.parse(cmplz_get_cookie('saved_services'));} catch (e) {saved_services = {};}
	var consented_services = cmplz_get_all_service_consents();

	//compare current cats with saved cats. When there are no changes, do nothing.
	if (cmplz_equals(saved_cats, cats) && cmplz_equals(saved_services, consented_services)) {
		return;
	}

	if ( complianz.store_consent != 1 ) {
		return;
	}

	//keep track of the fact that the status was saved at least once
	cmplz_set_cookie('saved_categories', JSON.stringify(cats));
	cmplz_set_cookie('saved_services', JSON.stringify(consented_services));
	cmplz_consent_stored_once = true;
	var request = new XMLHttpRequest();
	request.open('POST', complianz.url+'track', true);
	var data = {
		'consented_categories': cats,
		'consented_services':consented_services,
		'consenttype': window.wp_consent_type,//store the source consenttype, as our complianz.consenttype will not include optinstats.
	};

	request.setRequestHeader('Content-type', 'application/json');
	request.send(JSON.stringify(data));

}

/**
 * Get accepted categories
 *
 * @returns {string}
 */
function cmplz_accepted_categories() {
	var consentedCategories = cmplz_categories;
	var consentedTemp = [];

	//because array filter changes keys, we make a temp arr
	for (var key in consentedCategories) {
		if ( consentedCategories.hasOwnProperty(key) ) {
			var category = consentedCategories[key];
			if (cmplz_has_consent(category)) {
				consentedTemp.push(category);
			}
		}
	}

	consentedCategories = consentedCategories.filter(function(value, index, consentedCategories){
		return cmplz_in_array(value, consentedTemp);
	});
	return consentedCategories;
}

/**
 * Enable the checkbox for each category which was enabled
 *
 * */

function cmplz_sync_category_checkboxes() {
	for ( var key in cmplz_categories ) {
		if ( cmplz_categories.hasOwnProperty(key) ) {
			var category = cmplz_categories[key];
			if (cmplz_has_consent(category) || category === 'functional') {
				document.querySelectorAll('input.cmplz-' + category).forEach(obj => {
					obj.checked = true;
				});
			} else {
				document.querySelectorAll('input.cmplz-' + category).forEach(obj => {
					obj.checked = false;
				});
			}
		}
	}
}

/**
 * Merge two objects
 *
 * */

function cmplz_merge_object(userdata, ajax_data) {
	var output = [];
	//first, we fill the important data.
	for (key in ajax_data) {
		if (ajax_data.hasOwnProperty(key)) output[key] = ajax_data[key];
	}

	//conditionally add static data
	for (var key in userdata) {
		//only add if not in ajax_data
		if (!ajax_data.hasOwnProperty(key) || typeof ajax_data[key] === 'undefined') {
			if (userdata.hasOwnProperty(key)) output[key] = userdata[key];
		}
	}

	return output;
}

/**
 * If current cookie policy has changed, reset cookie consent
 *
 * */

function cmplz_check_cookie_policy_id() {
	var user_policy_id = cmplz_get_cookie('policy_id');
	if (user_policy_id && (complianz.current_policy_id !== user_policy_id) ) {
		cmplz_clear_all_complianz_cookies('cmplz');
	}
}

/**
 * Clear all our own cookies, to make sure path issues are resolved.
 *
 *
 */
function cmplz_clear_all_complianz_cookies(cookie_part){
	var foundCookie = false;

	if (typeof document === 'undefined') {
		return foundCookie;
	}
	var secure = ";secure";
	var date = new Date();
	date.setTime(date.getTime() - (24 * 60 * 60 * 1000));
	var expires = ";expires=" + date.toGMTString();
	if (window.location.protocol !== "https:") secure = '';
	(function () {
		var cookies = document.cookie.split("; ");
		for (var c = 0; c < cookies.length; c++) {
			var d = window.location.hostname.split(".");
			//if we have more than one result in the array, we can skip the last one, as it will be the .com/.org extension
			var skip_last = d.length > 1;
			while (d.length > 0) {
				var cookieName = cookies[c].split(";")[0].split("=")[0];
				var p = location.pathname;
				p = p.replace(/^\/|\/$/g, '').split('/');
				if ( cookieName.indexOf(cookie_part) !==-1 ) {
					foundCookie = true;
					var cookieBase = encodeURIComponent(cookieName) + '=;SameSite=Lax' + secure + expires +';domain=.' + d.join('.') + ';path=';
					var cookieBaseDomain = encodeURIComponent(cookieName) + '=;SameSite=Lax' + secure + expires +';domain=;path=';
					document.cookie = cookieBaseDomain + '/';
					document.cookie = cookieBase+ '/';
					while (p.length > 0) {
						var path = p.join('/');
						if ( path.length>0 ) {
							document.cookie = cookieBase + '/' + path;
							document.cookie = cookieBaseDomain + '/' + path;
							document.cookie = cookieBase + '/' + path + '/';
							document.cookie = cookieBaseDomain + '/' + path + '/';
						}
						p.pop();
					};
				}
				d.shift();
				//prevents setting cookies on .com/.org
				if (skip_last && d.length==1) d.shift();
			}
		}
	})();

	//to prevent a double reload, we preserve the cookie policy id.
	cmplz_set_accepted_cookie_policy_id();
	return foundCookie;
}

/**
 *
 * If a policy is accepted, save this in the user policy id
 *
 * */

function cmplz_set_accepted_cookie_policy_id() {
	cmplz_set_cookie('policy_id', complianz.current_policy_id);
}

/**
 * For supported integrations, initialize the not consented state
 *
 * */

function cmplz_integrations_init() {
	var cookiesToSet = complianz.set_cookies;
	//check if we have scripts that need to be set to true on init.
	for (var key in cookiesToSet) {
		if (cookiesToSet.hasOwnProperty(key) && cookiesToSet[key][1] === '1') {
			cmplz_set_cookie(key, cookiesToSet[key][1], false);
		}
	}
}

/**
 * For supported integrations, revoke consent
 *
 * */
function cmplz_integrations_revoke() {
	var cookiesToSet = complianz.set_cookies;
	for (var key in cookiesToSet) {
		if (cookiesToSet.hasOwnProperty(key)) {
			cmplz_set_cookie(key, cookiesToSet[key][1], false);
			if ( cookiesToSet[key][1] == false ){
				cmplz_clear_all_complianz_cookies(key);
			}
		}
	}
}

/**
 * For supported integrations, set consent
 *
 * */

function cmplz_set_integrations_cookies() {
	var cookiesToSet = complianz.set_cookies;
	for (var key in cookiesToSet) {
		if (cookiesToSet.hasOwnProperty(key)) {
			cmplz_set_cookie(key, cookiesToSet[key][0], false);
		}
	}
}


function cmplz_get_url_parameter(sPageURL, sParam) {
	if (typeof sPageURL === 'undefined') return false;

	var queryString = sPageURL.split('?');
	if (queryString.length == 1) return false;

	var sURLVariables = queryString[1].split('&'),
		sParameterName,
		i;
	for (i = 0; i < sURLVariables.length; i++) {
		if ( sURLVariables.hasOwnProperty(i) ) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
	}
	return false;
}

/**
 * If the parameter cmplz_region_redirect =true is passed, find the user's region, and redirect.
 */
function cmplz_maybe_auto_redirect() {
	var redirect = cmplz_get_url_parameter(window.location.href, 'cmplz_region_redirect');
	var region = cmplz_get_url_parameter(window.location.href, 'cmplz-region');
	if (redirect && !region) {
		window.location.href = window.location.href + '&cmplz-region=' + complianz.region;
	}
}

/**
 * wrapper to set consent for wp consent API. If consent API is not active, do nothing
 * @param type
 * @param value
 */
function cmplz_wp_set_consent(type, value) {
	//wp consent api integration
	if (typeof wp_set_consent == 'function') {
		wp_set_consent(type, value);
	}
}

/**
 * Load revoke options
 */

function cmplz_load_manage_consent_container() {
	let manage_consent_container = document.querySelector('.cmplz-manage-consent-container');
	if ( manage_consent_container ) {
		var request = new XMLHttpRequest();
		request.open('GET', complianz.url+'manage_consent_html?'+complianz.locale, true);
		request.setRequestHeader('Content-type', 'application/json');
		request.send();
		request.onload = function() {
			let html = JSON.parse(request.response);
			manage_consent_container.insertAdjacentHTML( 'beforeend', html );
			cmplz_sync_category_checkboxes();
			let nojavascript = document.querySelector('#cmplz-manage-consent-container-nojavascript')
			nojavascript.style.display = 'none';
			manage_consent_container.style.display = 'block';
			event = new CustomEvent('cmplz_manage_consent_container_loaded');
			document.dispatchEvent(event);
		};
	}
}

/**
 * Make slider radio's tabable
 */

cmplz_add_event('keypress', '.cmplz-banner-slider label', function(e){
	var keycode = (e.keyCode ? e.keyCode : e.which);
	if (keycode == '32') {
		document.activeElement.click();
	}
});

/**
 * Compare two arrays
 * @param array
 * @returns {boolean}
 */
function cmplz_equals (array_1, array_2) {
	if ( !Array.isArray(array_1) ) {
		array_1 = Object.keys(array_1);
		array_2 = Object.keys(array_2);
	}
	// if the other array is a falsy value, return
	if (!array_1 || !array_2)
		return false;

	// compare lengths - can save a lot of time
	if (array_1.length != array_2.length)
		return false;

	for (var i = 0, l=array_1.length; i < l; i++) {
		// Check if we have nested arrays
		if (array_1[i] instanceof Array && array_2[i] instanceof Array) {
			// recurse into the nested arrays
			if (!cmplz_equals(array_1[i], array_2[i]))
				return false;
		}
		else if (array_1[i] != array_2[i]) {
			// Warning - two different object instances will never be equal: {x:20} != {x:20}
			return false;
		}
	}
	return true;
}



/**
 * Hooked into jquery
 */
if ('undefined' != typeof window.jQuery) {
	// jQuery present
	jQuery(document).ready(function ($) {
		/**
		 * Activate fitvids on the parent element if active
		 *  a.o. Beaverbuilder
		 */
		$(document).on("cmplz_category_enabled", cmplz_enable_fitvids);
		function cmplz_enable_fitvids(data) {
			document.querySelectorAll('.cmplz-video').forEach(obj => {
				//turn obj into jquery object
				let $obj = $(obj);
				if (typeof $obj.parent().fitVids == 'function') {
					$obj.parent().fitVids();
				}
			});
		}
	});
}

