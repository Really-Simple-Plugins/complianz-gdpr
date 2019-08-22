'use strict';

/*
* Opt in (e.g. EU):
* default all scripts disabled.
* cookie banner
*
* Opt out (e.g. US):
* default all scripts enabled
* information cookie banner
*
* Other:
* default all scripts enabled
* no banner
*
* */

/*
    //to hook into the event that fires when the scripts are enabled, use script like this:
    $(document).on("cmplzEnableScripts", myScriptHandler);
    function myScriptHandler(consentLevel) {
        //your code here
        console.log('run enable scripts event ' +consentLevel);
    }

* */


jQuery(document).ready(function($) {

    var ccStatus;
    var ccName;
    var ccStatsEnabled = false;
    var ccAllEnabled = false;
    var ccPrivacyLink = '';
    var waitingInlineScripts = [];
    var waitingScripts = [];
    var placeholderClassIndex = 0;
    var curClass = '';

    /**
     * prevent scroll to top behaviour because of missing href tag
     */

    $(document).on('click','.cc-btn', function(e){
        e.preventDefault();
    });

    /**
     *
     */

    function setStatusAsBodyClass(status){
        var ccBody = $('body');
        ccBody.removeClass(curClass);
        ccBody.addClass('cmplz-status-'+status);
        curClass = 'cmplz-status-' + status;
    }

    /**
    * Set placeholder image as background on the parent div, set notice, and handle height.
    *
    * */


    setBlockedContentContainer();
    function setBlockedContentContainer() {

        $('.cmplz-iframe').each(function() {
            //we set the first parent div as container with placeholder image
            var blockedContentContainer = $(this).parent();
            placeholderClassIndex++;
            blockedContentContainer.addClass('cmplz-placeholder-' + placeholderClassIndex);
            blockedContentContainer.addClass('cmplz-blocked-content-container');
            blockedContentContainer.data('placeholderClassIndex', placeholderClassIndex);
            var placeholderText = $(this).data('placeholder-text');

            //insert placeholder text
            if (typeof placeholderText !== 'undefined' )blockedContentContainer.append('<div class="cmplz-blocked-content-notice cmplz-accept-cookies">'+placeholderText+'</div>');

            //handle image size for video
            var src = $(this).data('placeholder-image');
            if (typeof src !== 'undefined' && src.length) {
                src = src.replace('url(', '').replace(')', '').replace(/\"/gi, "");
                $('head').append('<style>.cmplz-placeholder-' + placeholderClassIndex + ' {background-image: url(' + src + ');}</style>');
                setBlockedContentContainerAspectRatio();
            }

        });
    }

    /**
    * Set the height of an image relative to the width, depending on the image widht/height aspect ratio.
    *
    *
    * */

    function setBlockedContentContainerAspectRatio(){
        $('.cmplz-iframe').each(function() {
            //we set the first parent div as container with placeholder image
            var blockedContentContainer = $(this).parent();

            //handle image size for video
            var src = $(this).data('placeholder-image');
            if (src.length) {
                src = src.replace('url(', '').replace(')', '').replace(/\"/gi, "");

                var img = new Image();
                img.addEventListener("load", function () {
                    var imgWidth = this.naturalWidth;
                    var imgHeight = this.naturalHeight;

                    //prevent division by zero.
                    if (imgWidth === 0) imgWidth = 1;
                    var w = blockedContentContainer.width();
                    var h = imgHeight * (w / imgWidth);

                    var heightCSS = '';
                    if (src.indexOf('placeholder.jpg')===-1) heightCSS = 'height:' + h + 'px;';

                    var cssIndex = blockedContentContainer.data('placeholderClassIndex');
                    $('head').append('<style>.cmplz-placeholder-' + cssIndex + ' {'+heightCSS+'}</style>');
                });
                if (src && src.length) img.src = src;
            }

        });
    }

    /**
    * Keep window aspect ratio in sync when window resizes
    * To lower the number of times this code is executed, it is done with a timeout.
    *
    * */

    $(window).bind('resize', function(e){
        //window.resizeEvt;
        $(window).resize(function(){
            clearTimeout(window.resizeEvt);
            window.resizeEvt = setTimeout(function(){
                setBlockedContentContainerAspectRatio();
            }, 100);
        });
    });

    /**
    * Enable scripts that were blocked
    *
    * */

    function complianz_enable_scripts() {

        //check if the stats were already running. Don't enable in case of categories, as it should be handled by cmplzFireCategories
        if (!complianz.use_categories && !ccStatsEnabled) {
            complianz_enable_stats();
        }

        //make sure it doesn't run twice
        if (ccAllEnabled) return;

        //enable integrations
        cmplzIntegrationsConsent();

        //styles
        $('.cmplz-style-element').each(function (i, obj) {
            var src = $(this).data('href');
            $('head').append('<link rel="stylesheet" type="text/css" href="'+src+'">');
        });

        //remove accept cookie notice overlay
        $('.cmplz-blocked-content-notice').each(function () {
            $(this).remove();
        });

        //iframes and video's
        $('.cmplz-iframe').each(function (i, obj) {
            //we get the closest, not the parent, because a script could have inserted a div in the meantime.
            var blockedContentContainer = $(this).closest('.cmplz-blocked-content-container');
            //remove the added classes
            var cssIndex = blockedContentContainer.data('placeholderClassIndex');
            blockedContentContainer.removeClass('cmplz-placeholder-'+cssIndex);
            blockedContentContainer.removeClass('cmplz-blocked-content-container');
            $(this).removeClass('cmplz-iframe-styles');

			//in some cases the videowrap gets added to the iframe
			$( this ).removeClass( 'video-wrap');

            //activate the video.
            var src = $(this).data('src-cmplz');
            console.log(src);
            //check if there's an autoplay value we need to pass on
            var autoplay = cmplzGetUrlParameter($(this).attr('src'), 'autoplay');
            if (autoplay==='1') src = src+'&autoplay=1';
            $(this).attr('src', src);

        });

        //scripts: set "cmplz-script classes to type="text/javascript"
        $('.cmplz-script').each(function (i, obj) {

            //do not run stats scripts yet. We leave that to the dedicated stats function cmplz_enable_stats()
            if ($(this).hasClass('cmplz-stats')) return true;

            var src = $(this).attr('src');

            if (src && src.length) {
                if (src.indexOf('recaptcha.js')!==-1){
                    waitingScripts['recaptcha'] = src;
                } else {
                    if (typeof $(this).data('post_scribe_id') !== 'undefined') {
                        var psID = '#' + $(this).data('post_scribe_id');
                        if ($(psID).length) {
                            $(psID).html('');
                            $(function () {
                                postscribe(psID, '<script src=' + src + '></script>');
                            });
                        }
                    } else {
                        $(this).attr('type', 'text/javascript');
                        $.getScript(src).done(function (script, textStatus) {
                            //check if we have waiting scripts
                            for (var key in waitingInlineScripts) {
                                if (waitingInlineScripts.hasOwnProperty(key)) {
                                    //if the key is part of the src string, we run the waiting script. E.g. recaptcha as key
                                    if (src.indexOf(key) !== -1) {
                                        cmplzRunInlineScript(waitingInlineScripts[key]);
                                    }
                                }
                            }

                            for (var key in waitingScripts) {
                                if (waitingScripts.hasOwnProperty(key)) {
                                    //if the key is part of the src string, we run the waiting script. E.g. recaptcha as key
                                    if (src.indexOf(key) !== -1) {
                                        $.getScript(waitingScripts[key]).done(function(script, textStatus){
                                            if ($('.happyforms-form').length) $('.happyforms-form' ).happyForm();
                                        });
                                    }
                                }
                            }
                        });
                    }
                }
            } else if ($(this).text().length) {
                if ($(this).text().indexOf('grecaptcha')!==-1){
                    waitingInlineScripts['recaptcha'] = $(this);
                } else {
                    cmplzRunInlineScript($(this));
                }
            }
        });

        //fire an event so custom scripts can hook into this.
        $.event.trigger({
            type: "cmplzEnableScripts",
            consentLevel: "all"
        });

        ccAllEnabled = true;
    }

    function cmplzRunInlineScript(script){
        $('<script>')
            .attr('type', 'text/javascript')
            .text(script.text())
            .appendTo(script.parent());
        script.remove();
    }

    function complianz_enable_stats() {
        console.log('fire stats');
        $('.cmplz-script.cmplz-stats').each(function (i, obj) {
            if ($(this).text().length) {
                var str = $(this).text();
                // str = str.replace("anonymizeIp': true", "anonymizeIp': false");
                //if it's analytics.js, add remove anonymize ip
                $('<script>')
                    .attr('type', 'text/javascript')
                    .text(str)
                    .appendTo($(this).parent());
                $(this).remove();
            }
            ccStatsEnabled = true;
        });

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
        if (cmplzFiredEvents.indexOf(event)===-1) {
            console.log('fire ' + event);
            cmplzFiredEvents.push(event);
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': event
            });
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
    if (complianz.geoip && (cmplz_user_data.length === 0 || (cmplz_user_data.version !== complianz.version) || (cmplz_user_data.banner_version !== complianz.banner_version))) {
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
        cmplzIntegrationsInit();
        cmplzCheckCookiePolicyID();

        $.event.trigger({
            type: "cmplzCookieBannerData",
            data: complianz
        });

        //if no status was saved before, we do it now
        if (cmplzGetCookie('cmplz_choice') !== 'set') {
            //for Non optin/optout visitors, and DNT users, we just track the no-warning option
            if (complianz.do_not_track || (complianz.consenttype !== 'optin' && complianz.consenttype !== 'optout')) {
                complianz_track_status('no-warning');
            } else if (complianz.consenttype === 'optout') {
                //for opt out visitors, so consent by default
                complianz_track_status('all');
            } else {
                //all others (eu): no choice yet.
                complianz_track_status('no-choice');
            }
        }

        if (!complianz.do_not_track) {
            if (complianz.consenttype === 'optin') {
                setStatusAsBodyClass('deny');
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
                complianz.readmore_url = complianz.readmore_url_us;
                complianz.readmore = complianz.readmore_optout;
                complianz.dismiss = complianz.accept_informational;
                complianz.message = complianz.message_optout;
                ccPrivacyLink= complianz.privacy_link;
                cmplz_cookie_warning();
            } else {
                console.log('other consenttype, no cookie warning');
                //on other consenttypes, all scripts are enabled by default.
                cmplzAcceptAllCookies();
            }
        } else {
            setStatusAsBodyClass('deny');
        }

    }

    /*
    * Run the actual cookie warning
    *
    * */

    function cmplz_cookie_warning() {
        //apply custom css
        if (complianz.use_custom_cookie_css) $('<style>').prop("type", "text/css").html(complianz.custom_css).appendTo("head");

        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_consent_status',
                expiryDays: complianz.cookie_expiry
            },
            onInitialise: function (status) {
                //runs only when dismissed or accepted
                ccStatus = status;
                /*
                * This runs when the banner is dismissed or accepted.
                * When status = allow, it's accepted, and we want to run all scripts.
                *
                * */
                if (status === 'allow') {
                    cmplzAcceptAllCookies();
                }
            },
            onStatusChange: function (status, chosenBefore) {
                //opt out cookie banner can be dismissed on scroll or on timeout.
                //As cookies are consented by default, it does not have to be tracked, and cookies do not have to be saved.
                if ((complianz.dismiss_on_scroll || complianz.dismiss_on_timeout) && (status==='dismiss' || status==='allow')) {
                    cmplzSetCookie('complianz_consent_status', 'allow', complianz.cookie_expiry);
                    ccName.close();
                    $('.cc-revoke').fadeIn();
                }

                /*
                * This runs when the status is changed
                * When status = allow, it's accepted, and we want to run all scripts.
                *
                * */
                ccStatus = status;

                //track here only for non category style, the default one is tracked on save.
                if (!complianz.use_categories) {
                    complianz_track_status();
                }

                if (status === 'allow') {
                    cmplzAcceptAllCookies();
                }

                if (status === 'deny' && complianz.consenttype === 'optout') {
                    cmplzRevoke();
                    complianz_track_status();
                    location.reload();
                }
            },
            onRevokeChoice: function () {
                //when the revoke button is clicked, the status is still 'allow'
                if (!complianz.use_categories && ccStatus === 'allow') {
                    cmplzRevoke();
                    complianz_track_status();
                    location.reload();
                }
            },
            "dismissOnTimeout": parseInt(complianz.dismiss_on_timeout),
            "dismissOnScroll" : parseInt(complianz.dismiss_on_scroll),
            "dismissOnWindowClick":true,
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
                "dismiss": '<a aria-label="dismiss cookie message" href="#" role="button" tabindex="0" class="cc-btn cc-dismiss">{{dismiss}}</a>',
                "allow": '<a aria-label="allow cookies" href="#" role="button" tabindex="0" class="cc-btn cc-allow">{{allow}}</a>',
                "save": '<a aria-label="save cookies" href="#" tabindex="0" class="cc-btn cc-save">{{save_preferences}}</a>',
                "categories-checkboxes": complianz.categories,
                "messagelink": '<span id="cookieconsent:desc" class="cc-message">{{message}} <a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}">{{link}}</a>' + ccPrivacyLink + '</span>',
            },
            "content": {
                "save_preferences": complianz.save_preferences,
                "deny": '',
                "message": complianz.message,
                "dismiss": complianz.dismiss,
                "allow": complianz.accept,
                "link": complianz.readmore,
                "href": complianz.readmore_url,
            }
        }, function (popup) {
            ccName = popup;
            //this code always runs

            /*
            * If this is not opt out, and site is using categories, we need to apply some styling, sync the checkboxes, and fire the currently selected categories.
            *
            * */
            if (complianz.consenttype !== 'optout' && complianz.use_categories) {
                //make sure the checkboxes show the correct settings
                cmplzSyncCategoryCheckboxes();

                //some styles
                if (complianz.tm_categories) {
                    for (var i = 0; i < complianz.cat_num; i++) {
                        $('#cmplz_' + i + ':checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                    }
                }

                $('#cmplz_functional:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('#cmplz_all:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('.cc-save').css({
                    "border-color": complianz.border_color,
                    "background-color": complianz.button_background_color,
                    "color": complianz.button_text_color
                });
                $('.cc-check svg').css({"stroke": complianz.popup_text_color});

                cmplzFireCategories();

            }

            /* We cannot run this on the initialize, as that hook runs only after a dismiss or accept choice
            *
            * If this is opt out, and cookies have not been denied, we run all cookies.
            *
            *
            * */
            if (complianz.consenttype === 'optout' && cmplzGetCookie('complianz_consent_status') !== 'deny') {
                cmplzAcceptAllCookies();
            }
        });
    }

    /**
    * Save the preferences after user has changed the settings in the popup
    *
    *
    * */

    $(document).on('click', '.cc-save', function () {
        cmplzSaveCategoriesSelection();
    });

    /**
    * Accept all cookies for this user.
    *
    * */

    function cmplzAcceptAllCookies(){
        setStatusAsBodyClass('allow');
        cmplzSetAcceptedCookiePolicyID();
        if (complianz.use_categories) {
            cmplzFireCategories(true);
        } else {
            complianz_enable_cookies();
            complianz_enable_scripts();
        }
    }


    /**
    * Save the current selected categories, and dismiss the banner
    *
    * */

    function cmplzSaveCategoriesSelection(){
        //dismiss the banner after saving, so it won't show on next page load
        ccName.setStatus('dismiss');
        //check if status is changed from 'allow' to 'revoked'
        var reload = false;
        if ($('#cmplz_all').length) {
            //'all' checkbox is not checked, and previous value was allow. reload.
            if (!$('#cmplz_all').is(":checked") && (cmplzGetCookie('cmplz_all') === 'allow')) {
                reload = true;
            }
        }

        //save the categories

        //using TM categories
        if (complianz.tm_categories) {
            for (var i = 0; i < complianz.cat_num; i++) {
                if ($('#cmplz_' + i).is(":checked")) {
                    cmplzSetCookie('cmplz_event_' + i, 'allow', complianz.cookie_expiry);
                } else {
                    cmplzSetCookie('cmplz_event_' + i, 'deny', complianz.cookie_expiry);
                }
            }
        }

        //statistics acceptance
        if ($('#cmplz_stats').length) {
            if ($('#cmplz_stats').is(":checked")) {
                cmplzSetCookie('cmplz_stats', 'allow', complianz.cookie_expiry);
            } else {
                cmplzSetCookie('cmplz_stats', 'deny', complianz.cookie_expiry);
            }
        }

        //marketing cookies acceptance
        if ($('#cmplz_all').length) {
            if ($('#cmplz_all').is(":checked")) {
                cmplzSetCookie('cmplz_all', 'allow', complianz.cookie_expiry);
            } else {
                cmplzSetCookie('cmplz_all', 'deny', complianz.cookie_expiry);
            }
        }

        cmplzFireCategories();

        //track status on saving of settings.
        complianz_track_status();

        if (cmplzGetHighestAcceptance() === 'no-choice' || cmplzGetHighestAcceptance() === 'functional') {
            cmplzRevoke();
        } else {
            cmplzSetAcceptedCookiePolicyID();
        }

        ccName.close();
        $('.cc-revoke').fadeIn();

        if (reload) location.reload();
    }


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

    /*

        optional method to revoke cookie acceptance from a custom link

    */
    $(document).on('click', '.cc-revoke-custom', function () {
        if (complianz.consenttype === 'optin') {
            $('.cc-revoke').click();
        } else {
            //if it's already denied, show the accept option again.
            if (cmplzGetCookie('complianz_consent_status') === 'deny') {
                $('.cc-revoke').click();
                complianz_track_status('all');
            } else {
                cmplzSetCookie('complianz_consent_status', 'deny', complianz.cookie_expiry);
                complianz_track_status('functional');
                ccName.close();

                $('.cc-revoke').fadeIn();
            }
            cmplzUpdateStatusCustomLink();
        }
    });

    /**
     *       Accept cookies by clicking any other link cookie acceptance from a custom link
     */

    $(document).on('click', '.cmplz-accept-cookies', function () {

        if (complianz.use_categories) {
            //set to highest level
            cmplzSetCookie('cmplz_all', 'allow', complianz.cookie_expiry);
            //sync with checkboxes in banner
            cmplzSyncCategoryCheckboxes();
            //save all new selections, and run scripts
            cmplzSaveCategoriesSelection();
        } else {
            //cmplzSetCookie('complianz_consent_status', 'allow', complianz.cookie_expiry);
            //dismiss the banner after saving, so it won't show on next page load
            ccName.setStatus(cookieconsent.status.allow);

            cmplzAcceptAllCookies();

            ccName.close();
            $('.cc-revoke').fadeIn();
        }
    });

    //show current status on custom revoke link
    cmplzUpdateStatusCustomLink();
    function cmplzUpdateStatusCustomLink() {
        if ($('.cc-revoke-custom').length) {
            if (complianz.consenttype === 'optout') {
                var accepted = $('#cmplz-document').find('.cmplz-status-accepted');
                var denied = $('#cmplz-document').find('.cmplz-status-denied');
                if (cmplzGetCookie('complianz_consent_status') === 'deny') {
                    accepted.hide();
                    denied.show();
                } else {
                    accepted.show();
                    denied.hide();
                }
            }
        }
    }

    function cmplzSetCookie(name, value, days) {
        var secure = ";secure";

        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = ";expires=" + date.toGMTString();

        if (window.location.protocol !== "https:") secure = '';

        document.cookie = name + "=" + value + secure + expires + ";path=/";
    }

    function cmplzGetCookie(cname) {
        var name = cname + "="; //Create the cookie name variable with cookie name concatenate with = sign
        var cArr = window.document.cookie.split(';'); //Create cookie array by split the cookie by ';'

        //Loop through the cookies and return the cooki value if it find the cookie name
        for (var i = 0; i < cArr.length; i++) {
            var c = cArr[i].trim();
            //If the name is the cookie string at position 0, we found the cookie and return the cookie value
            if (c.indexOf(name) == 0)
                return c.substring(name.length, c.length);
        }

        //If we get to this point, that means the cookie wasn't found, we return an empty string.
        return "";
    }

    /*
    * Retrieve the highes level of consent that has been given
    *
    * */

    function cmplzGetHighestAcceptance() {

        //if all is selected, it's automatically the highest
        var status = cmplzGetCookie('complianz_consent_status');

        if (complianz.use_categories) {

            if (cmplzGetCookie('cmplz_all') === 'allow') {
                return 'all';
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

        } else {
            if (status === 'allow' || (status === 'dismiss' && complianz.consenttype === 'optout')) {
                return 'all';
            }
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

    function cmplzFireCategories(all) {
        all = typeof all !== 'undefined' ? all : false;
        //always functional
        if (complianz.tm_categories) {
            cmplzRunTmEvent('cmplz_event_functional');
        }

        //using TM categories
        if (complianz.tm_categories) {
            for (var i = 0; i < complianz.cat_num; i++) {
                if (all || $('#cmplz_' + i).is(":checked")) {
                    cmplzRunTmEvent('cmplz_event_' + i);
                }
            }
        }

        //statistics acceptance
        if ($('#cmplz_stats').length && $('#cmplz_stats').is(":checked")) {
            complianz_enable_stats();
        }

        //marketing cookies acceptance
        if (all || ($('#cmplz_all').length && $('#cmplz_all').is(":checked")) ) {
            if (complianz.tm_categories) cmplzRunTmEvent('cmplz_event_all');
            complianz_enable_cookies();
            complianz_enable_scripts();
        }
    }



    /*
    * Enable the checkbox for each category which was enabled
    *
    *
    * */

    function cmplzSyncCategoryCheckboxes() {
        //tag manager
        if (complianz.tm_categories) {
            for (var i = 0; i < complianz.cat_num; i++) {
                if (cmplzGetCookie('cmplz_event_' + i) === 'allow') $('#cmplz_' + i).prop('checked', true);
            }
        }

        if (cmplzGetCookie('cmplz_all') === 'allow') $('#cmplz_all').prop('checked', true);
        if (cmplzGetCookie('cmplz_stats') === 'allow') $('#cmplz_stats').prop('checked', true);
    }

    /*
    * Merge two objects
    *
    * */

    function cmplzMergeObject(userdata, ajax_data) {

        var output = [];

        for (var key in userdata) {
            output[key] = userdata[key];
        }

        for (var key in ajax_data) {
            output[key] = ajax_data[key];
        }

        return output;
    }


    /*
     * If current cookie policy has changed, reset cookie consent
     *
     * */

    function cmplzCheckCookiePolicyID() {
        var user_policy_id = cmplzGetCookie('complianz_policy_id');
        if (user_policy_id && (complianz.current_policy_id != user_policy_id)) {
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

    function cmplzIntegrationsInit(){
        cmplzIntegrationsRevoke();
    }

    /**
     * For supported integrations, revoke consent
     *
     * */
    function cmplzIntegrationsRevoke(){
        var cookiesToSet = complianz.set_cookies;
        //check if we have waiting scripts
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

    function cmplzIntegrationsConsent(){
        var cookiesToSet = complianz.set_cookies;
        //check if we have waiting scripts
        for (var key in cookiesToSet) {
            if (cookiesToSet.hasOwnProperty(key)) {
                cmplzSetCookie(key, cookiesToSet[key][0], complianz.cookie_expiry);
            }
        }

    }

    /*
    *  Revoke consent
    *
    *
    * */

    function cmplzRevoke(){
        //delete all cookies
        document.cookie.split(";").forEach(
            function (c) {
                if (c.indexOf('cmplz_stats') === -1 && c.indexOf('cmplz_') === -1 && c.indexOf('complianz_consent_status') === -1 && c.indexOf('complianz_policy_id') === -1) {
                    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                }
            }
        );

        //we run it after the deletion of cookies, as there are cookies to be set.
        cmplzIntegrationsRevoke();
    }

    function cmplzGetUrlParameter(sPageURL, sParam) {
        var sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

});