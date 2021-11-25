/**
 * Script provided to improve backwards compatibility
 */

/**
 * Add an event
 * @param event
 * @param selector
 * @param callback
 * @param context
 */
function cmplz_migrate_add_event(event, selector, callback, context) {
	document.addEventListener(event, e => {
		if ( e.target == 'document' ) {
			callback(e);
		}

		if ( e.target.closest(selector) ) {
			callback(e);
		}
	});
}

document.querySelectorAll('.cmplz-cookiebanner').forEach(obj => {
	obj.classList.add('cc-window');
});

document.querySelectorAll('.cmplz-message').forEach(obj => {
	obj.classList.add('cc-message');
});
document.querySelectorAll('.cmplz-deny').forEach(obj => {
	obj.classList.add('cc-dismiss');
});
document.querySelectorAll('.cmplz-accept').forEach(obj => {
	obj.classList.add('cc-allow');
});
document.querySelectorAll('.cmplz-accept-marketing').forEach(obj => {
	obj.classList.add('cc-accept-all');
});
document.querySelectorAll('.cmplz-btn').forEach(obj => {
	obj.classList.add('cc-btn');
});
document.querySelectorAll('.cmplz-manage-consent').forEach(obj => {
	obj.classList.add('cc-revoke');
});
document.querySelectorAll('.cmplz-save-preferences').forEach(obj => {
	obj.classList.add('cc-save');
});
document.querySelectorAll('.cmplz-view-preferences').forEach(obj => {
	obj.classList.add('cc-show-settings');
});


document.addEventListener("cmplz_before_cookiebanner", function() {
	var event = new CustomEvent('cmplzRunBeforeAllScripts');
	document.dispatchEvent(event);
});

document.addEventListener("cmplz_run_after_all_scripts", function() {
	var event = new CustomEvent('cmplzRunAfterAllScripts');
	document.dispatchEvent(event);
});

document.addEventListener("cmplz_set_category_as_bodyclass", function() {
	let body = document.body;
	if (body.classList.contains('cmplz-marketing')) {
		body.classList.add('cmplz-status-marketing');
	}
	if (body.classList.contains('cmplz-statistics')) {
		body.classList.add('cmplz-status-statistics');
	}
	if ( !body.classList.contains('cmplz-marketing') && !body.classList.contains('cmplz-statistics')){
		body.classList.add('cmplz-status-deny');
	}
});

document.addEventListener("cmplz_tag_manager_event", function() {
	var event = new CustomEvent('cmplzTagManagerEvent');
	document.dispatchEvent(event);
});

document.addEventListener("cmplz_revoke", function() {
	var event = new CustomEvent('cmplzRevoke');
	document.dispatchEvent(event);
});

/**
 * Backward compatibility for the cmplzEnableScripts event
 */

document.addEventListener("cmplz_enable_category", function(consentData) {
	var category = consentData.detail.category;
	var event = new CustomEvent('cmplzEnableScripts', { detail: category });
	document.dispatchEvent(event);

	if (category==='marketing'){
		var event = new CustomEvent('cmplzAcceptAll', { detail: consentData });
		document.dispatchEvent(event);
		var event = new CustomEvent('cmplzEnableScriptsMarketing', { detail: consentData });
		document.dispatchEvent(event);
	}
});

document.addEventListener("cmplz_fire_categories", function(consentData) {
	var category = consentData.detail;
	var event = new CustomEvent('cmplzFireCategories', { detail: consentData });
	document.dispatchEvent(event);
});

document.addEventListener("cmplz_cookie_warning_loaded", function(consentData) {
	var category = consentData.detail;
	var event = new CustomEvent('cmplzCookieWarningLoaded', { detail: consentData });
	document.dispatchEvent(event);
});

var cmplzTMFiredEvents = [];
document.addEventListener("cmplz_tag_manager_event", function(data) {
	var category = data.detail;

	if (cmplzTMFiredEvents.indexOf(category) === -1) {
		var event;
		cmplzTMFiredEvents.push(category);
		if ( category === 'statistics' ){
			event = complianz.prefix + 'event_0';
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({
				'event': event
			});
		}

		//fire event 1 as marketing
		if ( category==='marketing' ) {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({
				'event': complianz.prefix + 'event_1'
			});
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({
				'event': complianz.prefix + 'event_all'
			});
		}
	}
});

/**
 * set a body class as previously done
 */

document.addEventListener("cmplz_track_status", function(data) {
	var category = cmplz_highest_accepted_category();
	document.body.classList.add('cmplz-status-' + category);
});

cmplz_migrate_add_event('click', '.cc-revoke-custom',function(event){
	event.preventDefault();
	cmplz_deny_all();
});

/**
 *  Accept all cookie categories by clicking any other link cookie acceptance from a custom link
 */

cmplz_migrate_add_event('click', '.cmplz-accept-cookies', function (event) {
	event.preventDefault();
	cmplz_accept_all();
});

cmplz_migrate_add_event('click', '.cmplz-save-settings', function (event) {
	event.preventDefault();
	document.querySelector('.cmplz-save-preferences').click();
});



