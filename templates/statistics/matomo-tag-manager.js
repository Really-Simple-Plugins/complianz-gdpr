var _mtm = window._mtm = window._mtm || [];
_mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
g.type='text/javascript'; g.async=true; g.src='{matomo_url}container_{container_id}.js'; s.parentNode.insertBefore(g,s);
_mtm.push({"event": "cmplz_event_functional"});
document.addEventListener("cmplz_status_change", function (e) {
	if ( e.detail.category === 'preferences' && e.detail.value === 'allow' ) {
		_mtm.push({"event": "cmplz_event_preferences"});
	}

	if ( e.detail.category === 'statistics' && e.detail.value === 'allow' ) {
		_mtm.push({"event": "cmplz_event_statistics"});
	}

	if ( e.detail.category === 'marketing' && e.detail.value === 'allow' ) {
		_mtm.push({"event": "cmplz_event_marketing"});
	}
});
