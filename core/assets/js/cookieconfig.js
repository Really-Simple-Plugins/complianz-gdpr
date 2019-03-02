'use strict';

/*
* EU:
* default all scripts disabled.
* cookie banner
*
* US:
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

    document.addEventListener("cmplzEnableScripts", cmplzAddIframeDiv, false);
    function cmplzAddIframeDiv(e) {
        //your code here
        console.log('run enable scripts event');
    }

* */

jQuery(document).ready(function ($) {
    var ccStatus;
    var ccName;
    var ccStatsEnabled = false;
    var ccAllEnabled = false;
    var ccPrivacyLink = '';
    var waitingScripts = [];

    /**
     * Get actual css style from an element
     * @param el
     * @param property
     * @returns {string}
     */

    function getActualCSS(el, property) {
        var domNode = el[0];

        var parent = domNode.parentNode;
        if(parent) {
            var originalDisplay = parent.style.display;
            parent.style.display = 'none';
        }
        var computedStyles = getComputedStyle(domNode);
        var result = computedStyles[property];


        if(parent) {
            parent.style.display = originalDisplay;
        }

        return result;
    }

    /**
     * Checks if this padding is 56%, which is a padding to make video's responsive
     * @param padding
     * @returns {boolean}
     */

    function isVideoPadding(padding){
        //video padding contains %.
        if (padding.indexOf('%')===-1) return false;

        //video padding is about 56%.
        if (parseInt(padding.replace('%',''))===56){
            return true;
        } else {
            return false;
        }
    }

    /*
    * Set height of blocked content div to placeholder img aspect ratio's
    *
    * */
    setBlockedContentContainerAspectRatio();
    function setBlockedContentContainerAspectRatio() {
        $('.cmplz-video').each(function() {
            var resetPadding = false;

            // //in some theme's, we have a wrapper div with a padding for the video responsiveness. We need to temporarily disalbe this
            var grandParent = $(this).parent().parent();
            var gpPadding = getActualCSS(grandParent, 'paddingTop');

            if (isVideoPadding(gpPadding) && grandParent.children().length==1) {
                resetPadding = gpPadding;
                grandParent.css('padding-top', '10px');
            }

            var parent = $(this).parent();
            var pPadding = getActualCSS(parent, 'paddingTop');
            if (isVideoPadding(pPadding) && parent.children().length) {
                if (!resetPadding) resetPadding = pPadding;
                parent.css('padding-top', '10px');
            }

            var blockedContentContainer = $(this);
            var src = $(this).css('background-image');
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
                    if (resetPadding) {
                        blockedContentContainer.css('padding-top', resetPadding);
                    } else {
                        blockedContentContainer.height(h);
                    }
                });
                img.src = src;
            }

        });
    }

    /*
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

    /*
    * Enable scripts that were blocked
    *
    * */

    function complianz_enable_scripts() {

        //check if the stats were already running
        if (!ccStatsEnabled) complianz_enable_stats();

        //make sure it doesn't run twice
        if (ccAllEnabled) return;

        //enable integrations
        cmplzIntegrationsConsent();

        //remove accept cookie notice overlay
        $('.cmplz-blocked-content-notice').each(function () {
            $(this).parent().css('background-image', '');
            $(this).remove();
        });

        $('.cmplz-video').each(function (i, obj) {
            //reset video height adjustments
            $(this).height('inherit');

        });

        //iframes
        $('.cmplz-iframe').each(function (i, obj) {
            var src = $(this).data('src-cmplz');
            $(this).attr('src', src);

            // //fitvids needs to be reinitialized, if it is used.
            if (jQuery.fn.fitVids && $(this).parent().hasClass('cmplz-video')) {
                $(this).parent().fitVids();
            }
        });

        //scripts: set "cmplz-script classes to type="text/javascript"
        $('.cmplz-script').each(function (i, obj) {
            var src = $(this).attr('src');
            if (src && src.length) {
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
                    $.getScript(src).done(function(script, textStatus) {
                        //check if we have waiting scripts
                        for (var key in waitingScripts){
                            if (waitingScripts.hasOwnProperty(key)) {
                                //if the key is part of the src string, we run the waiting script. E.g. recaptcha as key
                                if (src.indexOf(key)!==-1){
                                    cmplzRunInlineScript(waitingScripts[key]);
                                }
                            }
                        }
                    });
                }
            } else if ($(this).text().length) {
                if ($(this).text().indexOf('grecaptcha')!==-1){
                    waitingScripts['recaptcha'] = $(this);
                } else {
                    cmplzRunInlineScript($(this));
                }
            }
        });

        //fire an event so custom scripts can hook into this.
        if (!cmplzIsIE()) {
            var event = new CustomEvent("cmplzEnableScripts");
            document.dispatchEvent(event);
        }

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
        $('.cmplz-stats').each(function (i, obj) {
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
        if (!cmplzIsIE()) {
            var event = new CustomEvent("cmplzEnableStats");
            document.dispatchEvent(event);
        }
    }


    /*
    * Fire an event in Tag Manager
    *
    *
    * */

    function cmplzRunTmEvent(event) {
        console.log('fire ' + event);
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': event
        });
    }

    /*
    * We use ajax to check the country, otherwise caching could prevent the user specific warning
    *
    * */

    var cmplz_user_data = [];
    //check if it's already stored
    if (typeof (Storage) !== "undefined" && sessionStorage.cmplz_user_data) {
        cmplz_user_data = JSON.parse(sessionStorage.cmplz_user_data);
    }

    //if not, reload. As region is new, we also check for this feature, so we know which version of data we use.
    if (cmplz_user_data.length === 0 || (cmplz_user_data.version !== complianz.version)) {
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
        //the IDE will give a warning about the complianz var here, but it's inserted by wordpress
        complianz = cmplzMergeObject(complianz, cmplz_user_data);

        cmplzIntegrationsInit();

        cmplzCheckCookiePolicyID();

        //if no status was saved before, we do it now
        if (cmplzGetCookie('cmplz_choice') !== 'set') {
            //for Non eu/us visitors, and DNT users, we just track the no-warning option
            if (cmplz_user_data.do_not_track || (cmplz_user_data.region !== 'eu' && cmplz_user_data.region !== 'us')) {
                complianz_track_status('no-warning');
            } else if (cmplz_user_data.region === 'us') {
                //for US visitors are opt out, so consent by default
                complianz_track_status('all');
            } else {
                //all others (eu): no choice yet.
                complianz_track_status('no-choice');
            }
        }

        if (!cmplz_user_data.do_not_track) {
            if (cmplz_user_data.region === 'eu') {
                //disable auto dismiss
                complianz.dismiss_on_scroll = false;
                complianz.dismiss_on_timeout = false;
                console.log('EU, opt-in');
                cmplz_cookie_warning();
            } else if (cmplz_user_data.region === 'us') {
                console.log('US, opt-out');
                complianz.type = 'opt-out';
                // complianz.use_categories = false;
                complianz.layout = 'basic';
                complianz.readmore_url = complianz.readmore_url_us;
                complianz.readmore = complianz.readmore_us;
                complianz.dismiss = complianz.accept_informational;
                complianz.message = complianz.message_us;
                ccPrivacyLink= complianz.privacy_link;
                cmplz_cookie_warning();
            } else {
                console.log('other region, no cookie warning');
                //on other regions, all scripts are enabled by default.
                cmplzAcceptAllCookies();
            }
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
                //US cookie banner can be dismissed on scroll or on timeout.
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

                if (status === 'deny' && cmplz_user_data.region === 'us') {
                    cmplzRevoke();
                    complianz_track_status();
                }
            },
            onRevokeChoice: function () {
                //when the revoke button is clicked, the status is still 'allow'
                if (!complianz.use_categories && ccStatus === 'allow') {
                    cmplzRevoke();
                    complianz_track_status();
                    //location.reload();
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
                "save": '<a aria-label="save cookies" tabindex="0" class="cc-btn cc-save">{{save_preferences}}</a>',
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
            * If this is not the EU, and is running categories, we need to apply some styling, sync the checkboxes, and fire the currently selected categories.
            *
            * */
            if (cmplz_user_data.region !== 'us' && complianz.use_categories) {
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
            * If this is the US, and cookies have not been denied, we run all cookies.
            *
            *
            * */
            if (cmplz_user_data.region === 'us' && cmplzGetCookie('complianz_consent_status') !== 'deny') {
                cmplzAcceptAllCookies();
            }
        });
    }

    /*
    * Save the preferences after user has changed the settings in the popup
    *
    *
    * */

    $(document).on('click', '.cc-save', function () {
        cmplzSaveCategoriesSelection();
    });

    /*
    * Accept all cookies for this user.
    *
    * */

    function cmplzAcceptAllCookies(){
        cmplzSetAcceptedCookiePolicyID();
        if (complianz.use_categories) {
            cmplzFireCategories(true);
        } else {
            complianz_enable_cookies();
            complianz_enable_scripts();
        }
    }


    /*
    * Save the current selected categories, and dismiss the banner
    *
    * */

    function cmplzSaveCategoriesSelection(){
        //dismiss the banner after saving, so it won't show on next page load
        ccName.setStatus('dismiss');

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
    }


    function complianz_track_status(status) {
        status = typeof status !== 'undefined' ? status : false;

        //keep track of the fact that the status was saved at least once, for the no choice status
        cmplzSetCookie('cmplz_choice', 'set', complianz.cookie_expiry);

        if (!status) status = cmplzGetHighestAcceptance();

        $.ajax({
            type: "GET",
            url: complianz.url,
            dataType: 'json',
            data: ({
                action: 'cmplz_track_status',
                status: status,
                region: cmplz_user_data.region
            })
        });
    }

    /*

        optional method to revoke cookie acceptance from a custom link

    */
    $(document).on('click', '.cc-revoke-custom', function () {
        if (cmplz_user_data.region === 'eu') {
            $('.cc-revoke').click();
        } else {
            //if it's already denied, show the accept option again.
            if (cmplzGetCookie('complianz_consent_status') === 'deny') {
                $('.cc-revoke').click();
            } else {
                cmplzSetCookie('complianz_consent_status', 'deny', complianz.cookie_expiry);
                ccName.close();
                $('.cc-revoke').fadeIn();
            }
            cmplzUpdateStatusCustomLink();
        }
    });

    /*
            Accept cookies by clicking any other link cookie acceptance from a custom link
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
            if (cmplz_user_data.region === 'us') {
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
        var expires = "";
        var secure = ";secure";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = ";expires=" + date.toGMTString();
        }

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
            if (status === 'allow' || (status === 'dismiss' && cmplz_user_data.region === 'us')) {
                return 'all';
            }
        }

        if (status === 'dismiss') {
            return 'functional';
        }

        return 'no-choice';
    }

    /*
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
        if (all || ($('#cmplz_stats').length && $('#cmplz_stats').is(":checked"))) {
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

    function cmplzMergeObject(target, userdata) {
        for (var i = 1; i < userdata.length; i++) {
            var source = userdata;

            for (var key in source) {
                if (source.hasOwnProperty(key)) {
                    target[key] = source[key];
                }
            }
        }
        return target;
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

    /*
    *
    * If a policy is accepted, save this in the user policy id
    *
    * */

    function cmplzSetAcceptedCookiePolicyID() {
        cmplzSetCookie('complianz_policy_id', complianz.current_policy_id, complianz.cookie_expiry);

    }

    /*
    * For supported integrations, initialize the not consented state
    *
    * */

    function cmplzIntegrationsInit(){
        cmplzIntegrationsRevoke();
    }

    /*
    * For supported integrations, revoke consent
    *
    * */
    function cmplzIntegrationsRevoke(){
        //compatiblity with https://wordpress.org/plugins/wp-donottrack/
        cmplzSetCookie('dont_track_me', '1', complianz.cookie_expiry);
    }

    /*
    * For supported integrations, revoke consent
    *
    * */

    function cmplzIntegrationsConsent(){
        //compatiblity with https://wordpress.org/plugins/wp-donottrack/
        cmplzSetCookie('dont_track_me', '0', complianz.cookie_expiry);

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

    /*
    *
    * Check if browser is IE <=11, which doesn't support the customEvent
    * */

    function cmplzIsIE() {

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0) {
            // IE 10 or older => return version number
            return true;
        }

        var trident = ua.indexOf('Trident/');
        if (trident > 0) {
            // IE 11 => return version number
            return true;
        }

        return false;
    }

});