'use strict';

/**
 * consent management plugin sets cookie when consent category value changes
 *
 */
// wp_set_consent('marketing', 'allow');
/*
* Opt in (e.g. EU):
* default all scripts disabled.
* cookie banner
*
* Opt out (e.g. US):
* default all scripts enabled
* information cookie banner
*
* Other regions:
* default all scripts enabled
* no banner
*
* */

/*
    //to hook into the event that fires when the scripts are enabled, use script like this:
    $(document).on("cmplzEnableScripts", myScriptHandler);
    function myScriptHandler(consentData) {
        //your code here
        console.log(consentData.consentLevel);
        if (consentData.consentLevel==='marketing'){
            //do something with level all
        }
    }
cmplzCookieWarningLoaded
    //do CSS change on specific consenttypes

    $(document).on("cmplzCookieWarningLoaded", myScriptHandler);
    function myScriptHandler(consentData) {
        if (consentData.consentType==='optout'){
            $('#cc-banner-wrap').removeClass('.cmplz-soft-cookiewall');
        }
    }
* */

jQuery(document).ready(function ($) {
	var ccStatus;
	var ccName;
	var ccStatsEnabled = false;
	var ccAllEnabled = false;
	var waitingInlineScripts = [];
	var waitingScripts = [];
	var placeholderClassIndex = 0;
	var curClass = '';
	var cmplzAllScriptsHookFired = false;
	var cmplzLocalhostWarningShown = false;
	var cmplzCategories = [
		'cmplz_marketing',
		'cmplz_stats',
		'cmplz_prefs'
	];

	/**
	 * prevent scroll to top behaviour because of missing href tag
	 */

	$(document).on('click', '.cc-btn-no-href', function (e) {
		e.preventDefault();
	});

	/**
	 * Sets the current status as body class
	 */

	function setStatusAsBodyClass(status) {
		var ccBody = $('body');
		ccBody.removeClass(curClass);
		ccBody.addClass('cmplz-status-' + status);
		curClass = 'cmplz-status-' + status;
	}

	/**
	 * Set placeholder image as background on the parent div, set notice, and handle height.
	 *
	 * */

	function setBlockedContentContainer() {
		//force = typeof force !== 'undefined' ? force : false;
		//to prevent this function to run when cookies are accepted, we check for accepted status here
		//this is not the same as getHighestAcceptance
		// if (!ccAllEnabled) return;

		$('.cmplz-placeholder-element').each(function () {
			//we set this element as container with placeholder image
			var blockedContentContainer;
			if ($(this).hasClass('cmplz-iframe')) {

				//handle browser native lazy load feature
				if ($(this).attr('loading') === 'lazy' ) {
					$(this).removeAttr('loading');
					$(this).data('deferlazy', 1);
				}

				blockedContentContainer = $(this).parent();
			} else {
				blockedContentContainer = $(this);
			}
			var curIndex = blockedContentContainer.data('placeholderClassIndex');
			//if the blocked content container class is already added, don't add it again
			if (typeof curIndex === 'undefined') {
				placeholderClassIndex++;
				blockedContentContainer.addClass('cmplz-placeholder-' + placeholderClassIndex);
				blockedContentContainer.addClass('cmplz-blocked-content-container');
				blockedContentContainer.data('placeholderClassIndex', placeholderClassIndex);

				//insert placeholder text
				if (cmplzGetHighestAcceptance() !== 'marketing' && !blockedContentContainer.find(".cmplz-blocked-content-notice").length) {
					var placeholderText = complianz.placeholdertext;
					if (typeof placeholderText !== 'undefined') blockedContentContainer.append('<button tabindex=0 class="cmplz-blocked-content-notice cmplz-accept-marketing">' + placeholderText + '</button>');
				}

				//handle image size for video
				var src = $(this).data('placeholder-image');
				if (typeof src !== 'undefined' && src.length) {
					src = src.replace('url(', '').replace(')', '').replace(/\"/gi, "");
					$('head').append('<style>.cmplz-placeholder-' + placeholderClassIndex + ' {background-image: url(' + src + ') !important;}</style>');
					setBlockedContentContainerAspectRatio($(this), src, placeholderClassIndex);
				}
			}
		});

		/**
		 * In some cases, like ajax loaded content, the placeholders are initialized again. In that case, the scripts may need to be fired again as well.
		 * We're assuming that statistics scripts won't be loaded with ajax, so we only load marketing level scripts
		 */
		if ( cmplzGetHighestAcceptance() === 'marketing') {
			cmplzEnableMarketing();
		}

	}

	/**
	 * Set the height of an image relative to the width, depending on the image widht/height aspect ratio.
	 *
	 *
	 * */

	function setBlockedContentContainerAspectRatio(container, src, placeholderClassIndex) {

		if (typeof container === 'undefined') return;

		//we set the first parent div as container with placeholder image
		var blockedContentContainer = container.parent();

		//handle image size for video
		var img = new Image();
		img.addEventListener("load", function () {
			var imgWidth = this.naturalWidth;
			var imgHeight = this.naturalHeight;

			//prevent division by zero.
			if (imgWidth === 0) imgWidth = 1;
			var w = blockedContentContainer.width();
			var h = imgHeight * (w / imgWidth);

			var heightCSS = '';
			if (src.indexOf('placeholder.jpg') === -1) {
				heightCSS = 'height:' + h + 'px;';
			}

			$('head').append('<style>.cmplz-placeholder-' + placeholderClassIndex + ' {' + heightCSS + '}</style>');
		});
		img.src = src;
	}

	/**
	 * Keep window aspect ratio in sync when window resizes
	 * To lower the number of times this code is executed, it is done with a timeout.
	 *
	 * */

	$(window).bind('resize', function (e) {
		if (cmplzGetHighestAcceptance() !== 'marketing') {
			//window.resizeEvt;
			$(window).resize(function () {
				clearTimeout(window.resizeEvt);
				window.resizeEvt = setTimeout(function () {
					setBlockedContentContainer();
				}, 100);
			});
		}
	});

	/**
	 * 	we run this function also on an interval, because with ajax loaded content, the placeholders would otherwise not be handled.
	 */
	if ( complianz.block_ajax_content == 1 ) {
		setInterval(function () {
			setBlockedContentContainer();
		}, 2000);
	}

	/**
	 * Enable scripts that were blocked
	 *
	 * */

	function cmplzEnableMarketing() {

		$.event.trigger({
			type: "cmplzRunBeforeAllScripts"
		});

		//check if the stats were already running. Don't enable in case of categories, as it should be handled by cmplzFireCategories
		if (complianz.use_categories === 'no' && !ccStatsEnabled) {
			cmplzEnableStats();
		}

		//make sure it doesn't run twice
		if (!ccAllEnabled) {
			//enable integrations
			cmplzIntegrationsConsent();

			//styles
			$('.cmplz-style-element').each(function (i, obj) {
				var src = $(this).data('href');
				$('head').append('<link rel="stylesheet" type="text/css" href="' + src + '">');
			});

			//remove accept cookie notice overlay
			$('.cmplz-blocked-content-notice').each(function () {
				$(this).remove();
			});
		}

		//iframes and video's
		$('.cmplz-iframe').each(function (i, obj) {
			var src = $(this).data('src-cmplz');

			//check if there's an autoplay value we need to pass on
			var autoplay = cmplzGetUrlParameter($(this).attr('src'), 'autoplay');
			if (autoplay === '1') src = src + '&autoplay=1';
			$(this).attr('src', src).on( 'load' , function () {
				//fitvids integration, a.o. Beaverbuilder
				if (typeof $(this).parent().fitVids == 'function') {
					$(this).parent().fitVids();
				}
				var curElement = $(this);

				//handle browser native lazy load feature
				if ($(this).data('deferlazy')) {
					$(this).attr('loading', 'lazy');
				}

				//we get the closest, not the parent, because a script could have inserted a div in the meantime.
				var blockedContentContainer = $(this).closest('.cmplz-blocked-content-container');
				//now remove the added classes
				blockedContentContainer.animate({"background-image": "url('')"}, 400, function () {
					var cssIndex = blockedContentContainer.data('placeholderClassIndex');
					blockedContentContainer.removeClass('cmplz-blocked-content-container');
					blockedContentContainer.removeClass('cmplz-placeholder-' + cssIndex);
					curElement.removeClass('cmplz-iframe-styles');
					curElement.removeClass('cmplz-iframe');

					//in some cases the videowrap gets added to the iframe
					curElement.removeClass('video-wrap');
					curElement.removeClass('cmplz-hidden');
				});
			});
		});

		//other services, no iframe, with placeholders
		$('.cmplz-noframe').each(function (i, obj) {
			var blockedContentContainer = $(this);
			//remove the added classes
			var cssIndex = blockedContentContainer.data('placeholderClassIndex');
			blockedContentContainer.removeClass('cmplz-blocked-content-container');
			blockedContentContainer.removeClass('cmplz-noframe');
			blockedContentContainer.removeClass('cmplz-placeholder-' + cssIndex);
		});

		if (!ccAllEnabled) {

			//first, create list of waiting scripts
			var scriptElements = $('.cmplz-script');
			scriptElements.each(function (i, obj) {
				var waitfor = $(this).data('waitfor');
				var src = $(this).attr('src');

				if (src && src.length) {
					if (typeof (waitfor) !== "undefined") {
						waitingScripts[waitfor] = src;
					}
				} else if ($(this).text().length) {
					if (typeof (waitfor) !== "undefined") {
						waitingInlineScripts[waitfor] = $(this);
					}
				}
			});

			//scripts: set "cmplz-script classes to type="text/javascript"
			scriptElements.each(function (i, obj) {
				//do not run stats scripts yet. We leave that to the dedicated stats function cmplzEnableStats()
				if ($(this).hasClass('cmplz-stats')) return true;

				var src = $(this).attr('src');
				if (src && src.length) {
					$(this).attr('type', 'text/javascript');

					//check if this src or txt is in a waiting script. If so, skip.
					if (cmplzIsWaitingScript(waitingScripts, src)) {
						return;
					}

					if (typeof $(this).data('post_scribe_id') !== 'undefined') {
						var psID = '#' + $(this).data('post_scribe_id');
						if ($(psID).length) {
							$(psID).html('');
							$(function () {
								postscribe(psID, '<script src=' + src + '></script>');
							});
						}
					} else {
						$.getScript(src)
							.done(function (s, Status) {
								//check if we have waiting scripts
								var waitingScript = cmplzGetWaitingScript(waitingScripts, src);
								if (waitingScript) {
									$.getScript(waitingScript).done(function (script, textStatus) {
										cmplzRunAfterAllScripts();
									}).fail(function (jqxhr, settings, exception) {
										console.warn("Something went wrong " + exception);
									});
								}

								var waitingInlineScript = cmplzGetWaitingScript(waitingInlineScripts, src);
								if (waitingInlineScript) {
									cmplzRunInlineScript(waitingInlineScript);
								}

								//maybe all scripts are already done
								cmplzRunAfterAllScripts();
							})
							.fail(function (jqxhr, settings, exception) {
								console.warn("Something went wrong " + exception);
							});
					}

				} else if ($(this).text().length) {
					//check if this src or txt is in a waiting script. If so, skip.
					if (cmplzIsWaitingScript(waitingInlineScripts, $(this).text())) {
						return;
					}
					cmplzRunInlineScript($(this));
					//get scripts that are waiting for this inline script
					var waitingScript = cmplzGetWaitingScript(waitingScripts, $(this).text());
					if (waitingScript !== false) {
						$.getScript(waitingScript)
							.done(function (s, Status) {
								//maybe all scripts are already done
								cmplzRunAfterAllScripts();
							})
							.fail(function (jqxhr, settings, exception) {
								console.warn("Something went wrong " + exception);
							});
					}
				}
			});
		}

		if (!ccAllEnabled) {
			//fire an event so custom scripts can hook into this.
			$.event.trigger({
				type: "cmplzEnableScripts",
				consentLevel: "marketing"
			});

			//backward compatiblity: deprecating the 'all' event
			$.event.trigger({
				type: "cmplzEnableScripts",
				consentLevel: "all"
			});

			//javascript event
			var event = document.createEvent('Event');
			event.initEvent('cmplzEnableScriptsMarketing', true, true);
			document.dispatchEvent(event);
			//if there are no blockable scripts at all, we still want to provide a hook
			//in most cases, this script fires too early, and won't run yet. In that
			//case it's run from the script activation callbacks.
			cmplzRunAfterAllScripts();
			ccAllEnabled = true;
		}
	}

	/**
	 * check if the passed source has a waiting script that should be executed, and return it if so.
	 * @param waitingScripts
	 * @param src
	 * @returns {*}
	 */

	function cmplzGetWaitingScript(waitingScripts, src) {
		for (var waitfor in waitingScripts) {
			var waitingScript;//recaptcha/api.js, waitfor="gregaptcha"

			if (waitingScripts.hasOwnProperty(waitfor)) {
				waitingScript = waitingScripts[waitfor];
				if (typeof waitingScript !== 'string') waitingScript = waitingScript.text();
				if (src.indexOf(waitfor) !== -1) {

					var output = waitingScripts[waitfor];
					delete waitingScripts[waitfor];

					return output;
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
	function cmplzArrayIsEmpty(arr) {
		for (var key in arr) {
			if (arr.hasOwnProperty(key)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if the passed src or script is waiting for another script and should not execute
	 * @param waitingScripts
	 * @param srcOrScript
	 */

	function cmplzIsWaitingScript(waitingScripts, srcOrScript) {
		for (var waitfor in waitingScripts) {
			if (waitingScripts.hasOwnProperty(waitfor)) {
				var waitingScript = waitingScripts[waitfor];
				if (typeof waitingScript !== 'string') waitingScript = waitingScript.text();
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

	function cmplzRunAfterAllScripts() {
		if (!cmplzAllScriptsHookFired && cmplzArrayIsEmpty(waitingInlineScripts) && cmplzArrayIsEmpty(waitingScripts) ) {
			//hook
			//fire an event so custom scripts can hook into this.
			$.event.trigger({
				type: "cmplzRunAfterAllScripts"
			});

			cmplzAllScriptsHookFired = true;
		}
	}

	/**
	 * run inline script
	 * @param script
	 */

	function cmplzRunInlineScript(script) {
		$('<script>')
			.attr('type', 'text/javascript')
			.text(script.text())
			.appendTo(script.parent());
		script.remove();
	}

	/**
	 * Enable statistics scripts, scripts marked with the cmplz-stats class
	 */

	function cmplzEnableStats() {
		if (ccStatsEnabled) return;

		//if native class is included, it isn't blocked.
		if ($(this).hasClass('cmplz-native')) return true;

		console.log('fire cmplz_event_statistics');
		$('.cmplz-stats').each(function (i, obj) {
			var src = $(this).attr('src');
			if (src && src.length) {
				$(this).attr('type', 'text/javascript');
				$.getScript(src)
					.done(function (s, Status) {

					})
					.fail(function (jqxhr, settings, exception) {
						console.warn("Something went wrong " + exception);
					});

			} else if ($(this).text().length) {
				var str = $(this).text();
				// str = str.replace("anonymizeIp': true", "anonymizeIp': false");
				//if it's analytics.js, add remove anonymize ip
				$('<script>')
					.attr('type', 'text/javascript')
					.text(str)
					.appendTo($(this).parent());
				$(this).remove();
			}
		});
		ccStatsEnabled = true;
		//fire an event so custom scripts can hook into this.
		$.event.trigger({
			type: "cmplzEnableScripts",
			consentLevel: "statistics"
		});
	}

	/**
	 * Fire an event in Tag Manager
	 *
	 *
	 * */

	var cmplzFiredEvents = [];
	function cmplzRunTmEvent(event) {
		if (cmplzFiredEvents.indexOf(event) === -1) {
			//for TM events, we show a warning to tell users to stop using the event_all trigger. But we'll run it for now.
			if (event === 'cmplz_event_marketing' && complianz.tm_categories ){
				console.log( 'fire ' + event );
				cmplzFiredEvents.push('cmplz_event_marketing');
				window.dataLayer.push({
					'event': 'cmplz_event_marketing'
				});

				console.log('Deprecated event: The cmplz_event_all will be deprecated in the future.');
				cmplzFiredEvents.push('cmplz_event_all');
				window.dataLayer = window.dataLayer || [];
				window.dataLayer.push({
					'event': 'cmplz_event_all'
				});
			} else {
				console.log( 'fire ' + event );
				cmplzFiredEvents.push(event);
				window.dataLayer = window.dataLayer || [];
				window.dataLayer.push({
					'event': event
				});
			}
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
	if ( complianz.geoip == 1 && (cmplz_user_data.length === 0 || (cmplz_user_data.version !== complianz.version) || (cmplz_user_data.banner_version !== complianz.banner_version)) ) {
		$.ajax({
			type: "GET",
			url: complianz.url,
			dataType: 'json',
			data: ({
				action: 'cmplz_user_settings'
			}),
			success: function (response) {
				cmplz_user_data = response;
				sessionStorage.cmplz_user_data = JSON.stringify(cmplz_user_data);
				conditionally_show_warning();
			}
		});
	} else {
		conditionally_show_warning();
	}

	function conditionally_show_warning() {
		//merge userdata with complianz data, in case a b testing is used with user specific cookie banner data
		//objects are merged so user_data will override data in complianz object
		complianz = cmplzMergeObject(complianz, cmplz_user_data);

		//check if we need to redirect to another legal document, for a specific region
		cmplzMaybeAutoRedirect();

		setBlockedContentContainer();

		/**
		 * Integration with WordPress, tell what kind of consenttype we're using, then fire an event
		 */

		window.wp_consent_type = complianz.consenttype;
		$.event.trigger({
			type: "wp_consent_type_defined"
		});
		var event = new CustomEvent('wp_consent_type_defined');
		document.dispatchEvent(event);

		//uk has it's own use_category setting
		if (complianz.consenttype === 'optinstats') {
			complianz.use_categories = complianz.use_categories_optinstats;
			//use the optin mechanisms
			complianz.consenttype = 'optin';
		}
		if ( complianz.use_categories !== 'no' ) {
			complianz.type = 'categories';
			complianz.layout = 'categories-layout';
		}

		cmplzIntegrationsInit();
		cmplzCheckCookiePolicyID();
		complianz.readmore_url = complianz.readmore_url[complianz.region];

		$.event.trigger({
			type: "cmplzCookieBannerData",
			data: complianz
		});
		var cbd_event = new CustomEvent('cmplzCookieBannerData');
		document.dispatchEvent(cbd_event);

		//if no status was saved before, we do it now
		if (cmplzGetCookie('cmplz_choice') !== 'set') {
			//for Non optin/optout visitors, and DNT users, we just track the no-warning option
			if (complianz.do_not_track || (complianz.consenttype !== 'optin' && complianz.consenttype !== 'optout')) {
				complianz_track_status('no-warning');
			} else if (complianz.consenttype === 'optout') {
				//for opt out visitors, so consent by default
				complianz_track_status('marketing');
			} else {
				//all others (eu): no choice yet.
				complianz_track_status('no-choice');
			}
		}

		if (!complianz.do_not_track) {
			if (complianz.consenttype === 'optin') {
				setStatusAsBodyClass('deny');

				//if this website also targets UK, stats are blocked by default but can be enabled in the eu before consent
				//but only if EU does not require consent.

				if (complianz.forceEnableStats) {
					complianz.categories = cmplzRemoveStatisticsCategory(complianz.categories);
					var revokeContainer = $('.cmplz-manage-consent-container');
					if (revokeContainer.length){
						revokeContainer.html( cmplzRemoveStatisticsCategory(revokeContainer.html()) );
					}
					cmplzEnableStats();
				}

				//disable auto dismiss
				complianz.dismiss_on_scroll = false;
				complianz.dismiss_on_timeout = false;
				complianz.readmore = complianz.readmore_optin;
				complianz.message = complianz.message_optin;
				console.log('opt-in');
				cmplz_cookie_warning();
			} else if (complianz.consenttype === 'optout') {
				console.log('opt-out');
				setStatusAsBodyClass('allow');
				complianz.type = 'opt-out';
				complianz.layout = 'basic';

				if ( cmplzGetCookie('complianz_consent_status') == 'deny' ){
					cmplzDisableAutoDismiss();
				}
				complianz.readmore = complianz.readmore_optout;
				complianz.dismiss = complianz.accept_informational;
				complianz.message = complianz.message_optout;
				cmplz_cookie_warning();
			} else {
				console.log('other consenttype, no cookie warning');
				//on other consenttypes, all scripts are enabled by default.
				cmplzFireCategories('all', true);
			}
		} else {
			setStatusAsBodyClass('deny');
			//the load revoke container loads the notification for the user that DNT has been enabled.
			cmplzLoadRevokeContainer()
		}

	}

	/**
	 * Disable auto dismiss options, when user has manually revoked the consent in optout regions
	 */
	function cmplzDisableAutoDismiss(){
		complianz.dismiss_on_scroll = false;
		complianz.dismiss_on_timeout = false;
	}


	/**
	 * Run the actual cookie warning
	 *
	 * */

	function cmplz_cookie_warning() {
		//apply custom css
		var css = '';

		if (complianz.use_categories === 'hidden' || complianz.use_categories === 'visible' || complianz.use_custom_cookie_css) {

			css += '#cc-window.cc-window .cc-compliance .cc-btn.cc-accept-all {color:' + complianz.accept_all_text_color + ';background-color:' + complianz.accept_all_background_color + ';border-color:' + complianz.accept_all_border_color + '}';
			css += '#cc-window.cc-window .cc-compliance .cc-btn.cc-accept-all:hover{background-color:' + getHoverColour(complianz.accept_all_background_color) + '}';

		}

		css += '#cc-window.cc-window .cc-compliance .cc-btn.cc-dismiss{color:' + complianz.functional_text_color + ';background-color:' + complianz.functional_background_color + ';border-color:' + complianz.functional_border_color + '}';
		css += '#cc-window.cc-window .cc-compliance .cc-btn.cc-dismiss:hover{background-color:' + getHoverColour(complianz.functional_background_color) + '}';

		if ( complianz.banner_width !== 468 ) {
			css += "#cc-window.cc-floating {max-width:" + complianz.banner_width + "px;}";
		}

		if (complianz.use_custom_cookie_css) {
			css += complianz.custom_css;
		}

		if ( css.length > 0 ) {
			$('<style>').prop("type", "text/css").html(css).appendTo("head");
		}

		var save_button = '<a aria-label="{{save_preferences}}" href="#" class="cc-btn cc-save cc-save-settings">{{save_preferences}}</a>';
		if (complianz.use_categories === 'hidden' ) {
			if (complianz.tcf_active) {
				save_button = '<a aria-label="{{settings}}" href="#" class="cc-btn cc-save cc-show-settings">{{settings}}</a><a aria-label="{{dismiss}}" href="#" class="cc-btn cc-dismiss">{{dismiss}}</a>';
			} else {
				save_button = '<a aria-label="{{dismiss}}" href="#" class="cc-btn cc-dismiss">{{dismiss}}</a><a aria-label="{{settings}}" href="#" class="cc-btn cc-save cc-show-settings">{{settings}}</a>';
			}
		}

		if (complianz.use_categories === 'visible' || complianz.use_categories === 'hidden' ) {
			save_button = '<a aria-label="{{accept_all}}" href="#" class="cc-btn cc-accept-all">{{accept_all}}</a>'+save_button;
		}

		var dismiss_button = '<a aria-label="{{dismiss}}" href="#" role="button" class="cc-btn cc-dismiss">{{dismiss}}</a>';
		var allow_button = '<a aria-label="{{allow}}" href="#" role="button" class="cc-btn cc-save cc-allow">{{allow}}</a>';
		if (complianz.consenttype === 'optout' ) {
			dismiss_button ='<a aria-label="{{dismiss}}" href="#" role="button" class="cc-btn cc-allow">{{dismiss}}</a>';
		}

		//allow to set cookies on the root domain
		var domain = cmplzGetCookieDomain();

		//to be able to hide the banner on the cookie policy
		var autoOpen = $('.cmplz-tcf-container').length && complianz.tcf_active ? false : true;
		window.cookieconsent.initialise({
			cookie: {
				name: 'complianz_consent_status',
				expiryDays: complianz.cookie_expiry,
				domain: domain
			},
			onInitialise: function (status) {
				//runs only when dismissed or accepted
				ccStatus = status;
				if (complianz.soft_cookiewall && (status === 'allow' || status === 'dismiss')) {
					dismissCookieWall();
				}

				/**
				* This runs when the banner is dismissed or accepted.
				* When status = allow, it's accepted, and we want to run all scripts.
				*
				* */
				if (status === 'allow') {
					var all = false;
					if (complianz.use_categories === 'no') all = 'all';
					cmplzFireCategories(all, true);
				}
			},
			onStatusChange: function (status, chosenBefore) {
				//remove the banner wrap class to dismiss cookie wall styling
				if (complianz.soft_cookiewall && (status === 'allow' || status === 'dismiss')) {
					dismissCookieWall();
				}

				//opt out cookie banner can be dismissed on scroll or on timeout.
				//As cookies are consented by default, it does not have to be tracked, and cookies do not have to be saved.
				if ((complianz.dismiss_on_scroll || complianz.dismiss_on_timeout) && (status === 'dismiss' || status === 'allow')) {
					cmplzFireCategories('all', true);
					ccName.close();
					$('.cc-revoke').fadeIn();
				}
				cmplzUpdateStatusCustomLink(false);

				/**
				 * This runs when the status is changed
				 * When status = allow, it's accepted, and we want to run all scripts.
				 *
				 * */
				ccStatus = status;
				//track here only for non category style, the default one is tracked on save.
				if (complianz.use_categories === 'no' ) {
					complianz_track_status();
				}

				if (status === 'allow') {
					if (complianz.use_categories === 'no' ) {
						cmplzFireCategories('all', true);
					} else {
						cmplzFireCategories(false, true);
					}
				}

				if (status === 'deny' && complianz.consenttype === 'optout') {
					cmplzRevoke();
				}
			},
			onRevokeChoice: function () {
				if (complianz.soft_cookiewall) {
					activateCookieWall();
				}

				//when the revoke button is clicked, the status is still 'allow'
				if (complianz.use_categories === 'no' && ccStatus === 'allow') {
					cmplzRevoke();
					$('.cc-revoke').fadeOut();
					ccName.open();
				}
			},
			"autoOpen" : autoOpen,
			"dismissOnTimeout": parseInt(complianz.dismiss_on_timeout),
			"dismissOnScroll": parseInt(complianz.dismiss_on_scroll),
			"dismissOnWindowClick": true,
			"revokeBtn": '<div class="cc-revoke ' + complianz.hide_revoke + ' {{classes}}">' + complianz.revoke + '</div>',
			"palette": {
				"popup": {
					"background": complianz.popup_background_color,
					"text": complianz.popup_text_color
				},
				"button": {
					"background": complianz.button_background_color,
					"text": complianz.button_text_color,
					"border": complianz.border_color
				}
			},
			"theme": complianz.theme,
			"static": complianz.static,
			"position": complianz.position,
			"type": complianz.type,
			"layout": complianz.layout,
			"layouts": {
				'categories-layout': '{{messagelink}}{{categories-checkboxes}}{{compliance}}',
				'compliance': '{{messagelink}}{{compliance}}',
			},
			"compliance": {
				'categories': '<div class="cc-compliance cc-highlight">{{save}}</div>',
			},
			"elements": {
				"dismiss": dismiss_button,
				"allow": allow_button,
				"save": save_button,
				"categories-checkboxes": complianz.categories,
				"messagelink": '<span id="cookieconsent:desc" class="cc-message">{{message}} <a aria-label="{{link}}" class="cc-link" href="{{href}}">{{link}}</a>' + complianz.privacy_link[complianz.region] + '</span>',
			},
			"content": {
				"save_preferences": complianz.save_preferences,
				"deny": '',
				"message": complianz.message,
				"dismiss": complianz.dismiss,
				"allow": complianz.accept,
				"link": complianz.readmore,
				"href": complianz.readmore_url,
				"accept_all": complianz.accept_all,
				"settings": complianz.view_preferences,
			}
		}, function (popup) {
			ccName = popup;
			//this code always runs
			//soft cookie wall
			if (complianz.soft_cookiewall) $(".cc-window").wrap('<div id="cc-banner-wrap"></div>');
			if (complianz.soft_cookiewall && (ccStatus == undefined)) {
				activateCookieWall();
			}

			//we don't want the cc-btn to trigger a click, scrolling the window. But in some cases we do. So we add a separate class here which we can remove later, without changin the styling
			$('.cc-btn').each(function(){
				$(this).addClass('cc-btn-no-href');
			});

			//hidden categories
			if (complianz.use_categories === 'hidden') {
				$('.cmplz-categories-wrap').hide();

				$(document).on('click', '.cc-show-settings', function(){
					var catsContainer = $('.cmplz-categories-wrap');
					if (catsContainer.is(":visible")){
						catsContainer.fadeOut(800);
						var showSettingsBtn = $(".cc-save-settings");
						showSettingsBtn.html(complianz.view_preferences);
						showSettingsBtn.removeClass('cc-show-settings');
						showSettingsBtn.addClass('cc-save-settings');
					} else{
						catsContainer.fadeIn(1600);
						var showSettingsBtn = $(".cc-show-settings");
						showSettingsBtn.html(complianz.save_preferences);
						showSettingsBtn.addClass('cc-save-settings');
						showSettingsBtn.removeClass('cc-show-settings');
					}
				});

				$(document).on('click', '.cc-dismiss', function(){
					cmplzRevoke();
				});
			}

			$('.cc-window').addClass('cmplz-categories-'+complianz.use_categories);

			/*
			* If this is not opt out, and site is using categories, we need to apply some styling, sync the checkboxes, and fire the currently selected categories.
			*
			* */

			if (complianz.consenttype !== 'optout' && complianz.use_categories !== 'no') {
				//make sure the checkboxes show the correct settings
				cmplzSyncCategoryCheckboxes();
			}

			cmplzFireCategories();

			/** We cannot run this on the initialize, as that hook runs only after a dismiss or accept choice
			 *
			 * If this is opt out, and cookies have not been denied, we run all cookies.
			 *
			 *
			 * */

			if (complianz.consenttype === 'optout' && cmplzGetCookie('complianz_consent_status') !== 'deny') {
				cmplzFireCategories('all');
			}

			//if we're on the cookie policy page, we dynamically load the applicable revoke checkbox
			cmplzLoadRevokeContainer();

			//fire an event so custom scripts can hook into this.
			$.event.trigger({
				type: "cmplzCookieWarningLoaded",
				consentType: complianz.consenttype
			});
			//javascript event
			var event = new CustomEvent('cmplzCookieWarningLoaded', { detail: complianz.region });
			document.dispatchEvent(event);
		});
	}

	/**
	 * Save the preferences after user has changed the settings in the popup
	 *
	 *
	 **/

	$(document).on('click', '.cc-save-settings', function () {
		cmplzSaveCategoriesSelection();
		cmplzFireCategories(false, true);
	});

	/**
	 * For UK, cats are always needed. If this user is EU, and does not need consent for stats, we can remove the stats category
	 * @param categories
	 * @returns {*}
	 */

	function cmplzRemoveStatisticsCategory(categories) {
		if (complianz.use_categories !== 'no' && complianz.forceEnableStats) {
			return categories.replace(/(.*)(<div class="cmplz-categories-wrap"><label.*?>.*?<input.*?class=".*?cmplz_stats.*?<\/label><\/div>)(.*)/g, function (a, b, c, d) {
				return b + d;
			});
		}

		return categories;
	}

	/**
	 * Save the current selected categories, and dismiss the banner
	 * Should be run always after the checkboxes have been checked.
	 *
	 * */

	function cmplzSaveCategoriesSelection() {

		if (complianz.soft_cookiewall) {
			dismissCookieWall();
		}
		// //dismiss the banner after saving, so it won't show on next page load
		if ( complianz.use_categories ) ccName.setStatus('dismiss');

		//check if status is changed from 'allow' to 'revoked'
		var reload = false;
		if ($('.cmplz_marketing').length ) {
			//'marketing' checkbox is not checked, and previous value was allow. reload.
			if (!$('.cmplz_marketing').is(":checked") && cmplzGetCookie('cmplz_marketing') === 'allow' ) {
				reload = true;
			//marketing checkbox not che3cked, optout region, previous value empty, also reload
			} else if (!$('.cmplz_marketing').is(":checked") &&  complianz.consenttype === 'optout' && cmplzGetCookie('cmplz_marketing') === '' ) {
				reload = true;
			}
		}

		if (complianz.consenttype !== 'optinstats') cmplz_wp_set_consent('statistics-anonymous', 'allow');

		//save the categories

		//using TM categories
		if (complianz.tm_categories) {
			for (var i = 0; i < complianz.cat_num; i++) {
				if ($('.cmplz_' + i).is(":checked")) {
					cmplzSetCookie('cmplz_event_' + i, 'allow', complianz.cookie_expiry);
					cmplz_wp_set_consent('cmplz_event_' + i, 'allow');
				} else {
					cmplzSetCookie('cmplz_event_' + i, 'deny', complianz.cookie_expiry);
					cmplz_wp_set_consent('cmplz_event_' + i, 'deny');
				}
			}
		}

		//statistics acceptance
		if ($('.cmplz_stats').length) {
			if ($('.cmplz_stats').is(":checked")) {
				cmplzSetCookie('cmplz_stats', 'allow', complianz.cookie_expiry);
				cmplz_wp_set_consent('statistics', 'allow');
				cmplz_wp_set_consent('statistics-anonymous', 'allow');
			} else {
				cmplzSetCookie('cmplz_stats', 'deny', complianz.cookie_expiry);
				cmplz_wp_set_consent('statistics', 'deny');
				if (complianz.consenttype === 'optinstats') cmplz_wp_set_consent('statistics-anonymous', 'deny');
			}
		}

		//preferences acceptance
		if ($('.cmplz_prefs').length) {
			if ($('.cmplz_prefs').is(":checked")) {
				cmplzSetCookie('cmplz_prefs', 'allow', complianz.cookie_expiry);
				cmplz_wp_set_consent('preferences', 'allow');
			} else {
				cmplzSetCookie('cmplz_prefs', 'deny', complianz.cookie_expiry);
				cmplz_wp_set_consent('preferences', 'deny');
			}
		}

		//marketing cookies acceptance
		if ($('.cmplz_marketing').length) {
			if ($('.cmplz_marketing').is(":checked")) {
				ccName.setStatus(cookieconsent.status.allow);
				cmplzSetCookie('cmplz_marketing', 'allow', complianz.cookie_expiry);
				cmplz_wp_set_consent('marketing', 'allow');
			} else {
				cmplzSetCookie('cmplz_marketing', 'deny', complianz.cookie_expiry);
				cmplzSetCookie('complianz_consent_status', 'deny', complianz.cookie_expiry);
				cmplz_wp_set_consent('marketing', 'deny');
			}
		}

		//track status on saving of settings.
		complianz_track_status();

		if (complianz.use_categories !== 'no') {
			if (cmplzGetHighestAcceptance() === 'no-choice' || cmplzGetHighestAcceptance() === 'functional') {
				cmplzRevoke();
			}
		}

		cmplzUpdateStatusCustomLink(false);
		ccName.close();
		$('.cc-revoke').fadeIn();

		if (reload) {
			location.reload();
		}
	}

	/**
	 * Track the status of current consent
	 * @param status
	 */

	function complianz_track_status(status) {
		status = typeof status !== 'undefined' ? status : false;
		if (!status) status = cmplzGetHighestAcceptance();

		if (status) setStatusAsBodyClass(status);

		if (!complianz.a_b_testing) return;

		//keep track of the fact that the status was saved at least once, for the no choice status
		cmplzSetCookie('cmplz_choice', 'set', complianz.cookie_expiry);
		$.ajax({
			type: "GET",
			url: complianz.url,
			dataType: 'json',
			data: ({
				action: 'cmplz_track_status',
				status: status,
				consenttype: complianz.consenttype
			})
		});
	}

	/**
	  optional method to revoke cookie acceptance from the consent area
	 */

	var cmplzUpdateCheckbox = true;
	$(document).on('change', '.cmplz-consent-checkbox', function () {
		//when checkbox is clicked, don't update again
		cmplzUpdateCheckbox = false;
		var category = $(this).data('category');
		var checked = $(this).is(":checked");
		$('.'+category).each(function(){
			$(this).prop('checked',checked);
		});

		//the following steps should not run when the clicked checkbox is in the banner
		if (!$(this).closest('.cc-window').length) {
			cmplzFireCategories();
			cmplzDisableAutoDismiss();
			cmplzSaveCategoriesSelection();
		}

	});

	/**
		optional method to revoke cookie acceptance from a custom link
	 */

	$(document).on('click', '.cc-revoke-custom', function () {
		if (complianz.consenttype === 'optin' || complianz.consenttype === 'optinstats') {
			//tag manager
			if (complianz.tm_categories) {
				for (var i = 0; i < complianz.cat_num; i++) {
					$('.cmplz_' + i).each(function(){
						$(this).prop('checked', false);
					});
				}
			}

			var cmplzCategories = [
				'cmplz_marketing',
				'cmplz_stats',
				'cmplz_prefs'
			];

			for (var key in cmplzCategories) {
				if (cmplzCategories.hasOwnProperty(key)) {
					var cat = cmplzCategories[key];
					$('.'+cat).each(function(){
						$(this).prop('checked', false);
					});

				}
			}
		}

		//if it's already denied, show the accept option again.
		//if not denied, do a full revoke
		if ( cmplzGetCookie('complianz_consent_status') === 'deny' ) {
			$('.cc-revoke').click();
			$('.cc-revoke').fadeOut();
		} else {
			cmplzRevoke();
		}
	});

	/**
	 *  Accept all cookie categories by clicking any other link cookie acceptance from a custom link
	 */

	$(document).on('click', '.cmplz-accept-cookies', function (event) {
		event.preventDefault();
		cmplzAcceptAllCookies();
	});

	/**
	 *  Accept marketing cookies by clicking any other link cookie acceptance from a custom link
	 */

	$(document).on('click', '.cmplz-accept-marketing', function (event) {
		event.preventDefault();
		$('.cmplz_marketing').each(function(){
			$(this).prop('checked', true);
		});
		cmplzFireCategories('marketing', true);
		cmplzSaveCategoriesSelection();
		cmplzEnableMarketing();
	});

	/**
	 * Accept all cookies when accept all is clicked.
	 */

	$(document).on('click', '.cc-accept-all', function (event) {
		cmplzAcceptAllCookies();
	});

	/**
	 * Accept all cookies
	 */

	function cmplzAcceptAllCookies(){
		ccName.setStatus(cookieconsent.status.allow);
		//sync with checkboxes in banner
		cmplzSyncCategoryCheckboxes(true);
		cmplzFireCategories('all', true);
		cmplzSaveCategoriesSelection();
		//dispatch event
		var details = new Object();
		details.region = complianz.region;
		var event = new CustomEvent('cmplzAcceptAll', { detail: details });
		document.dispatchEvent(event);
		ccName.close();
		$('.cc-revoke').fadeIn();
	}

	/**
	 * Handle hidden categories
	 */

	$('.cmplz-hide-cats').show();

	/**
	 * When consent status changes, keep the consent management checkboxes on the site in sync
	 */

	function cmplzUpdateStatusCustomLink(updateCheckbox) {
		updateCheckbox = typeof updateCheckbox !== 'undefined' ? updateCheckbox : true;
		if (!updateCheckbox) cmplzUpdateCheckbox = false;

		if (cmplzUpdateCheckbox) {
			cmplzSyncCategoryCheckboxes();
		}

		if ($('.cc-revoke-custom').length) {
			var accepted = $('.cmplz-status-accepted');
			var denied = $('.cmplz-status-denied');
			if (complianz.consenttype === 'optout') {
				if (cmplzGetCookie('complianz_consent_status') === 'deny') {
					accepted.hide();
					denied.show();
				} else {
					accepted.show();
					denied.hide();
				}
			} else {
				if (cmplzGetHighestAcceptance() === 'marketing') {
					accepted.show();
					denied.hide();
				} else {
					accepted.hide();
					denied.show();
				}
			}

		}
	}
	cmplzUpdateStatusCustomLink();


	/**
	 * set a cookie
	 * @param name
	 * @param value
	 * @param days
	 */

	function cmplzSetCookie(name, value, days) {
		var secure = ";secure";
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = ";expires=" + date.toGMTString();

		if (window.location.protocol !== "https:") secure = '';

		var domain = cmplzGetCookieDomain();
		if (domain.length > 0) {
			domain = ";domain=." + complianz.cookie_domain;
		}

		document.cookie = name + "=" + value + ";SameSite=Lax" + secure + expires + domain + ";path=/";
	}

	/**
	 * retrieve domain to set the cookies on
	 * @returns {string}
	 */
	function cmplzGetCookieDomain(){
		var domain = '';
		if ( complianz.set_cookies_on_root == 1 && complianz.cookie_domain.length>3){
			domain = complianz.cookie_domain;
		}

		if (domain.indexOf('localhost') !== -1 ) {
			domain = '';
			if (!cmplzLocalhostWarningShown) console.log("Configuration warning: cookies can't be set on root domain on localhost setups");
			cmplzLocalhostWarningShown = true;
		}
		return domain;
	}

	/**
	 * Get a cookie by name
	 * @param cname
	 * @returns {string}
	 */
	function cmplzGetCookie(cname) {
		var name = cname + "="; //Create the cookie name variable with cookie name concatenate with = sign
		var cArr = window.document.cookie.split(';'); //Create cookie array by split the cookie by ';'

		//Loop through the cookies and return the cookie value if it find the cookie name
		for (var i = 0; i < cArr.length; i++) {
			var c = cArr[i].trim();
			//If the name is the cookie string at position 0, we found the cookie and return the cookie value
			if (c.indexOf(name) == 0)
				return c.substring(name.length, c.length);
		}

		return "";
	}

	/**
	 * Dismiss the soft cookie wall
	 */

	function dismissCookieWall(){
		$('#cc-banner-wrap').removeClass('cmplz-soft-cookiewall');
	}

	function activateCookieWall(){
		$('#cc-banner-wrap').addClass('cmplz-soft-cookiewall');
	}

	/**
	 * Retrieve the highest level of consent that has been given
	 *
	 * */

	function cmplzGetHighestAcceptance() {
		//if all is selected, it's automatically the highest
		var status = cmplzGetCookie('complianz_consent_status');
		//optout has different rules
		if (status === 'allow' || (status !== 'deny' && complianz.consenttype === 'optout')) {
			return 'marketing';
		}

		//the below blocks apply only to categories, but as categories are also used in the cookie policy
		//we check them also in case of accept/deny
		if (cmplzGetCookie('cmplz_marketing') === 'allow') {
			return 'marketing';
		}

		if (complianz.tm_categories) {
			for (var i = complianz.cat_num - 1; i >= 0; i--) {
				if (cmplzGetCookie('cmplz_event_' + i) === 'allow') {
					return 'cmplz_event_' + i;
				}
			}
		}

		if (cmplzGetCookie('cmplz_stats') === 'allow') {
			return 'stats';
		}

		if (cmplzGetCookie('cmplz_prefs') === 'allow') {
			return 'prefs';
		}



		if (status === 'dismiss') {
			return 'functional';
		}

		return 'no-choice';
	}

	/**
	 * Fire the categories events which have been accepted.
	 * Fires Tag Manager events.
	 *
	 * */

	function cmplzFireCategories(category, save) {
		cmplzSetAcceptedCookiePolicyID();
		category = typeof category !== 'undefined' ? category : false;
		var forceMarketing = category === 'all' || category === 'marketing';
		var forceStatistics = category === 'all' || category === 'statistics';
		var forcePreferences = category === 'all' || category === 'preferences';

		var highestCategoryAccepted = 'functional';
		save = typeof save !== 'undefined' ? save : false;
		//always functional
		cmplz_wp_set_consent('functional', 'allow');
		if (complianz.consenttype !== 'optinstats') cmplz_wp_set_consent('statistics-anonymous', 'allow');
		cmplzRunTmEvent('cmplz_event_functional');
		//using TM categories
		if (complianz.tm_categories) {
			for (var i = 0; i < complianz.cat_num; i++) {
				if (category === 'all' || $('.cmplz_' + i).is(":checked")) {
					cmplzRunTmEvent('cmplz_event_' + i);
				}
			}
		} else {
			if (forcePreferences || ($('.cmplz_prefs').length && $('.cmplz_prefs').is(":checked"))) {
				cmplz_wp_set_consent('preferences', 'allow');
				highestCategoryAccepted = 'preferences';
			}

			if (forceStatistics || ($('.cmplz_stats').length && $('.cmplz_stats').is(":checked"))) {
				cmplz_wp_set_consent('statistics', 'allow');
				cmplz_wp_set_consent('statistics-anonymous', 'allow');
				cmplzEnableStats();
				highestCategoryAccepted = 'statistics';
			}
		}

		//marketing cookies acceptance
		if (forceMarketing || ($('.cmplz_marketing').length && $('.cmplz_marketing').is(":checked"))) {
			setStatusAsBodyClass('allow');
			cmplz_wp_set_consent('marketing', 'allow');
			cmplzRunTmEvent('cmplz_event_marketing');
			cmplzEnableMarketing();
			highestCategoryAccepted = 'marketing';
		}

		//dispatch event
		var details = new Object();
		details.category = highestCategoryAccepted;
		details.region = complianz.region;
		var event = new CustomEvent('cmplzFireCategories', { detail: details });
		document.dispatchEvent(event);

		//marketing cookies acceptance
		if (save) {
			if (forceMarketing || ($('.cmplz_marketing').length && $('.cmplz_marketing').is(":checked"))) {
				cmplzSetCookie('complianz_consent_status', 'allow', complianz.cookie_expiry);
			}
		}
	}


	/**
	 * Enable the checkbox for each category which was enabled
	 * Pass force=true to enable all categories regardless of cookies
	 *
	 * */

	function cmplzSyncCategoryCheckboxes(force) {

		force = typeof force !== 'undefined' ? force : false;
		//tag manager

		if (complianz.tm_categories) {
			for (var i = 0; i < complianz.cat_num; i++) {
				if (cmplzGetCookie('cmplz_event_' + i) === 'allow' || force) {
					$('.cmplz_' + i).each(function(){
						$(this).prop('checked', true);
					});
				} else {
					$('.cmplz_' + i).each(function(){
						$(this).prop('checked', false);
					});
				}
			}
		}

		for (var key in cmplzCategories) {
			if (cmplzCategories.hasOwnProperty(key)) {
				var cat = cmplzCategories[key];
				if (cmplzGetCookie(cat) === 'allow' || force) {
					$('.'+cat).each(function(){
						$(this).prop('checked', true);
					});
				} else {
					$('.'+cat).each(function(){
						$(this).prop('checked', false);
					});
				}
			}
		}

		//for the deny/accept banner
		if ( cmplzGetHighestAcceptance() === 'marketing'  || force ) {
			$('.cmplz_marketing').each(function(){
				$(this).prop('checked', true);
			});
		} else {
			$('.cmplz_marketing').each(function(){
				$(this).prop('checked', false);
			});
		}
	}

	/**
	 * Merge two objects
	 *
	 * */

	function cmplzMergeObject(userdata, ajax_data) {

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

	function cmplzCheckCookiePolicyID() {
		var user_policy_id = cmplzGetCookie('complianz_policy_id');
		if (user_policy_id && (complianz.current_policy_id !== user_policy_id)) {
			cmplzSetCookie("complianz_consent_status", "", 0);
		}
	}

	/**
	 *
	 * If a policy is accepted, save this in the user policy id
	 *
	 * */

	function cmplzSetAcceptedCookiePolicyID() {
		cmplzSetCookie('complianz_policy_id', complianz.current_policy_id, complianz.cookie_expiry);
	}

	/**
	 * For supported integrations, initialize the not consented state
	 *
	 * */

	function cmplzIntegrationsInit() {
		var cookiesToSet = complianz.set_cookies;
		//check if we have scripts that need to be set to true on init.
		for (var key in cookiesToSet) {
			if (cookiesToSet.hasOwnProperty(key) && cookiesToSet[key][1] === '1') {
				cmplzSetCookie(key, cookiesToSet[key][1], 0);
			}
		}
	}

	/**
	 * For supported integrations, revoke consent
	 *
	 * */
	function cmplzIntegrationsRevoke() {
		var cookiesToSet = complianz.set_cookies;
		for (var key in cookiesToSet) {
			if (cookiesToSet.hasOwnProperty(key)) {
				cmplzSetCookie(key, cookiesToSet[key][1], 0);
			}
		}
	}

	/**
	 * For supported integrations, revoke consent
	 *
	 * */

	function cmplzIntegrationsConsent() {
		var cookiesToSet = complianz.set_cookies;
		for (var key in cookiesToSet) {
			if (cookiesToSet.hasOwnProperty(key)) {
				cmplzSetCookie(key, cookiesToSet[key][0], complianz.cookie_expiry);
			}
		}
	}

	/**
	 *  Revoke consent
	 *
	 *
	 * */

	function cmplzRevoke() {
		var consentLevel = cmplzGetHighestAcceptance();
		var reload = false;

		if (consentLevel !== 'no-choice' && consentLevel !== 'functional' ) {
			reload = true;
		}

		//accept deny variant should reload if current level is no choice.
		if (consentLevel === 'no-choice' && complianz.use_categories === 'no' ) {
			reload = true;
		}

		//using TM categories
		if (complianz.tm_categories) {
			for (var i = 0; i < complianz.cat_num; i++) {
				cmplzSetCookie('cmplz_event_' + i, 'deny', complianz.cookie_expiry);
				cmplz_wp_set_consent('cmplz_event_' + i, 'deny');
			}
		}

		//statistics acceptance
		if ($('.cmplz_stats').length) {
			cmplzSetCookie('cmplz_stats', 'deny', complianz.cookie_expiry);
			cmplz_wp_set_consent('statistics', 'deny');
			if (complianz.consenttype === 'optinstats') cmplz_wp_set_consent('statistics-anonymous', 'deny');

		}

		//preferences acceptance
		if ($('.cmplz_prefs').length) {
			cmplzSetCookie('cmplz_prefs', 'deny', complianz.cookie_expiry);
			cmplz_wp_set_consent('preferences', 'deny');
		}

		//marketing cookies acceptance
		if ($('.cmplz_marketing').length) {
			cmplzSetCookie('cmplz_marketing', 'deny', complianz.cookie_expiry);
			cmplz_wp_set_consent('marketing', 'deny');
		}

		cmplzSetCookie('complianz_consent_status', 'deny', complianz.cookie_expiry);

		//we run it after the deletion of cookies, as there are cookies to be set.
		cmplzIntegrationsRevoke();
		cmplzSyncCategoryCheckboxes();
		cmplzFireCategories();
		complianz_track_status();

		var event = new CustomEvent('cmplzRevoke', { detail: reload });
		document.dispatchEvent(event);
		cmplzUpdateStatusCustomLink();

		//we need to let the iab extension handle the reload, otherwise the consent revoke might not be ready yet.
		if (!complianz.tcf_active && reload) {
			location.reload();
		}
	}

	function cmplzGetUrlParameter(sPageURL, sParam) {
		if (typeof sPageURL === 'undefined') return false;

		var queryString = sPageURL.split('?');
		if (queryString.length == 1) return false;

		var sURLVariables = queryString[1].split('&'),
			sParameterName,
			i;
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
		return false;
	}

	function cmplzInArray(needle, haystack) {
		var length = haystack.length;
		for (var i = 0; i < length; i++) {
			if (haystack[i].indexOf(needle) !== -1) return true;
		}
		return false;
	}


	/**
	 * If the parameter cmplz_region_redirect =true is passed, find the user's region, and redirect.
	 */
	function cmplzMaybeAutoRedirect() {
		var redirect = cmplzGetUrlParameter(window.location.href, 'cmplz_region_redirect');
		var region = cmplzGetUrlParameter(window.location.href, 'region');
		//check if we have a URL that could use a region redirect
		// if ($('a.cmplz-region-redirect').length){
		//     $('a.cmplz-region-redirect').each(function(){
		//         var src = $(this).attr('src');
		//         var append = '?';
		//         if (src.indexOf('?')!==-1){
		//             append = '&';
		//         }
		//         append += 'region='+complianz.region;
		//         $(this).attr('src', src+append);
		//     });
		// }

		if (redirect && !region) {
			window.location.href = window.location.href + '&region=' + complianz.region;
		}
	}

	/**
	 * wrapper to set consent for wp consent API. If consent API is not active, do nothing
	 * @param type
	 * @param value
	 */
	function cmplz_wp_set_consent(type, value) {
		if (typeof wp_set_consent == 'function') {
			wp_set_consent(type, value);
		}
	}

	/**
	 * Load revoke options
	 */

	function cmplzLoadRevokeContainer() {
		if ($('.cmplz-manage-consent-container').length) {
			$.ajax({
				type: 'GET',
				url: complianz.url,
				dataType: 'json',
				data: ({
					action: 'cmplz_manage_consent_html_ajax'
				}),
				success: function (response) {
					$('.cmplz-manage-consent-container').html(response.html);
					cmplzSyncCategoryCheckboxes();
					//in case of revoke, update the accepted/deny settings
					cmplzUpdateStatusCustomLink();
				}
			});
		}
	}

    /**
     * Toggle service
     */

    $(document).on('click', '.cmplz-service-header', function () {
        var item = $(this).next();

        if (item.hasClass('cmplz-service-hidden')) {
            $(this).addClass('cmplz-service-open');
            item.removeClass('cmplz-service-hidden');
        } else {
            $(this).removeClass('cmplz-service-open');
            item.addClass('cmplz-service-hidden');
        }
    });

	function getHoverColour(hex){
		if (hex[0] == '#') {
			hex = hex.substr(1);
		}
		if (hex.length == 3) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}

		// for black buttons
		if (hex == '000000') {
			return '#222';
		}
		var num = parseInt(hex, 16),
			amt = 38,
			R = (num >> 16) + amt,
			B = (num >> 8 & 0x00FF) + amt,
			G = (num & 0x0000FF) + amt;
		var newColour = (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (B<255?B<1?0:B:255)*0x100 + (G<255?G<1?0:G:255)).toString(16).slice(1);
		return '#'+newColour;
	}

});
