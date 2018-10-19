'use strict';

function complianz_deleteAllCookies() {
    console.log('clearing all cookies on own domain');
    document.cookie.split(";").forEach(
    function (c) {
        if (c.indexOf('cmplz_stats') === -1 && c.indexOf('cmplz_') === -1 && c.indexOf('complianz_consent_status') === -1 && c.indexOf('complianz_policy_id') === -1) {
            document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        }
    }
    );
}

jQuery(document).ready(function ($) {
    var ccStatus;
    var ccName;
    var ccStatsEnabled = false;

    function complianz_enable_scripts(){
        if (!ccStatsEnabled) complianz_enable_stats();

        //iframes
        $('.cmplz-iframe').each(function(i, obj) {
            var src = $(this).data('src-cmplz');
            $(this).attr('src', src);
        });

        //scripts: set "cmplz-script classes to type="text/javascript"
        $('.cmplz-script').each(function(i, obj) {
            var src = $(this).attr('src');
            if (src && src.length) {
                $(this).attr('type', 'text/javascript');
                $.getScript(src, function () {});
            }else if ($(this).text().length){
                $('<script>')
                    .attr('type', 'text/javascript')
                    .text($(this).text())
                    .appendTo($(this).parent());
                $(this).remove();
            }
        });
    }

    function complianz_enable_stats(){
        $('.cmplz-stats').each(function(i, obj) {
            if ($(this).text().length){
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
    }


    /*
    * Fire an event in Tag Manager
    *
    *
    * */

    function cmplzRunTmEvent(event){
        console.log('fire '+event);
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
    if (typeof(Storage) !== "undefined" && sessionStorage.cmplz_user_data) {
        cmplz_user_data = JSON.parse(sessionStorage.cmplz_user_data);
    }

    //if not, reload. As region is new, we also check for this feature, so we know which version of data we use.
    if (cmplz_user_data.length===0 || (typeof cmplz_user_data.version !== complianz.version)) {
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

    function conditionally_show_warning(){
        //merge userdata with complianz data, in case a b testing is used with user specific cookie banner data
        complianz = cmplzMergeObject(complianz, cmplz_user_data);
        cmplzCheckCookiePolicyID();

        //for Non eu/us visitors, and DNT users, we just track the no-warning option
        if (cmplz_user_data.do_not_track || (cmplz_user_data.region!=='eu' && cmplz_user_data.region!=='us')) {
            complianz_track_status('no-warning');
        } else {
            //if no status was saved before, we do it now
            if (cmplzGetCookie('cmplz_choice')!=='set') {
                complianz_track_status('no-choice');
            }
        }

        if (!cmplz_user_data.do_not_track) {
            if (cmplz_user_data.region==='eu') {
                console.log('eu, opt-in');
                cmplz_cookie_warning();
            } else if (cmplz_user_data.region==='us') {
                console.log('us, opt-out');
                complianz.type = 'opt-out';
                complianz.use_categories = false;
                complianz.layout = 'basic';
                complianz.readmore_url = complianz.readmore_url_us;
                complianz.dismiss = complianz.accept_informational;
                cmplz_cookie_warning();
            } else {
                console.log('other region, no cookie warning');
                complianz_enable_cookies();
                complianz_enable_scripts();
                //cookie blocker is default enabled, so all scripts need to be enabled.
            }
        }

    }

    function cmplz_cookie_warning(){
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
                if (status === 'allow' || (status === 'dismiss' && cmplz_user_data.region==='us')) {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                }

            },
            onStatusChange: function (status, chosenBefore) {

                ccStatus = status;
                //track here only for non category style, the default one is tracked on save.
                if (!complianz.use_categories) {
                    complianz_track_status();
                }

                if (status === 'allow' || (status === 'dismiss' && cmplz_user_data.region==='us') ) {
                    cmplzSetAcceptedCookiePolicyID();
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                }

                if (status === 'deny' && cmplz_user_data.region==='us') {
                    complianz_deleteAllCookies();
                    complianz_track_status();
                }
            },
            onRevokeChoice: function () {
                if (!complianz.use_categories && ccStatus==='allow') {
                    complianz_deleteAllCookies();
                    complianz_track_status();
                    //location.reload();
                }
            },
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
            "type" : complianz.type,
            "layout": complianz.layout,
            "layouts": {
                'categories-layout': '{{messagelink}}{{categories-checkboxes}}{{compliance}}',
            },
            "compliance": {
                'categories': '<div class="cc-compliance cc-highlight">{{save}}</div>',
            },
            "elements": {
                "save": '<a aria-label="save cookies" tabindex="0" class="cc-btn cc-save">{{save_preferences}}</a>',
                "categories-checkboxes": complianz.categories,
                "link": '<a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}">{{link}}</a>',
            },
            "content": {
                "save_preferences" : complianz.save_preferences,
                "deny": complianz.decline,
                "message": complianz.message,
                "dismiss": complianz.dismiss,
                "allow": complianz.accept,
                "link": complianz.readmore,
                "href": complianz.readmore_url
            }
        }, function (popup) {
            ccName = popup;
            //this function always runs
            if (complianz.use_categories) {
                //handle category checkboxes
                cmplzSetCategoryCheckboxes();

                //some styles
                if (complianz.tm_categories) {
                    for (var i = 0; i < complianz.cat_num; i++) {
                        $('#cmplz_' + i + ':checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                    }
                }

                $('#cmplz_functional:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('#cmplz_all:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('.cc-save').css({"border-color" : complianz.border_color ,"background-color": complianz.button_background_color, "color" : complianz.button_text_color});
                $('.cc-check svg').css({"stroke": complianz.popup_text_color});

                cmplzFireCategories();

            }

            /* We cannot run this on the initialize, as that hook runs only after a dismiss or accept choice */
            if (cmplz_user_data.region==='us' && cmplzGetCookie('complianz_consent_status')!=='deny') {
                complianz_enable_cookies();
                complianz_enable_scripts();
            }
        });
    }

    /*
    * Save the preferences after user has changed the settings in the popup
    *
    *
    * */

    $(document).on('click', '.cc-save', function(){
        //dismiss the banner after saving, so it won't show on next page load
        ccName.setStatus(cookieconsent.status.dismiss);

        //save AND run
        if (complianz.use_categories) {
            cmplzSaveCategories();
            cmplzFireCategories();
        }

        //track status on saving of settings.
        complianz_track_status();

        if (cmplzGetHighestAcceptance()==='no-choice' || cmplzGetHighestAcceptance() === 'functional'){
            complianz_deleteAllCookies();
        } else {
            cmplzSetAcceptedCookiePolicyID();
        }

        ccName.close();
        $('.cc-revoke').fadeIn();

    });

    function complianz_track_status(status){
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

    //optional method to revoke cookie acceptance from a custom link
    $(document).on('click', '.cc-revoke-custom', function(){$('.cc-revoke').click();});

    function cmplzSetCookie(name, value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    function cmplzGetCookie(cname) {
        var name = cname + "="; //Create the cookie name variable with cookie name concatenate with = sign
        var cArr = window.document.cookie.split(';'); //Create cookie array by split the cookie by ';'

        //Loop through the cookies and return the cooki value if it find the cookie name
        for(var i=0; i<cArr.length; i++) {
            var c = cArr[i].trim();
            //If the name is the cookie string at position 0, we found the cookie and return the cookie value
            if (c.indexOf(name) == 0)
                return c.substring(name.length, c.length);
        }

        //If we get to this point, that means the cookie wasn't find in the look, we return an empty string.
        return "";
    }


    function cmplzGetHighestAcceptance(){

        //if all is selected, it's automatically the highest
        var status = cmplzGetCookie('complianz_consent_status');
        if (complianz.use_categories){

            if (cmplzGetCookie('cmplz_all')==='allow'){
                return 'all';
            }

            if (complianz.tm_categories){
                for (var i = complianz.cat_num-1; i >= 0; i--) {
                    if (cmplzGetCookie('cmplz_event_'+i)==='allow'){
                        return 'cmplz_event_'+i;
                    }
                }
            }

            if (cmplzGetCookie('cmplz_stats')==='allow'){
                return 'stats';
            }

        } else {
            if (status === 'allow' || (status === 'dismiss' && cmplz_user_data.region==='us')) {
                return 'all';
            }
        }

        if (status === 'dismiss') {
            return 'functional';
        }

        return 'no-choice';
    }

    function cmplzFireCategories(all){
        all = typeof all !== 'undefined' ? all : false;

        //always functional
        if (complianz.tm_categories) {
            cmplzRunTmEvent('cmplz_event_functional');
        }

        //using TM categories
        if (complianz.tm_categories){
            for (var i = 0; i < complianz.cat_num; i++) {
                if (all || $('#cmplz_'+i).is(":checked")){
                    cmplzRunTmEvent('cmplz_event_'+i);
                }
            }
        }

        //statistics acceptance
        if ($('#cmplz_stats').length){
            if (all || $('#cmplz_stats').is(":checked")) {
                complianz_enable_stats();
            }
        }

        //marketing cookies acceptance
        if ($('#cmplz_all').length){
            if (all || $('#cmplz_all').is(":checked")) {
                if (complianz.tm_categories) cmplzRunTmEvent('cmplz_event_all');
                complianz_enable_cookies();
                complianz_enable_scripts();
            }
        }
    }


    /*
    * save category preferences
    *
    * */

    function cmplzSaveCategories(){

        //using TM categories
        if (complianz.tm_categories){
            for (var i = 0; i < complianz.cat_num; i++) {
                if ($('#cmplz_'+i).is(":checked")){
                    cmplzSetCookie('cmplz_event_'+i, 'allow', complianz.cookie_expiry);
                } else {
                    cmplzSetCookie('cmplz_event_'+i, 'deny', complianz.cookie_expiry);
                }
            }
        }

        //statistics acceptance
        if ($('#cmplz_stats').length){
            if ($('#cmplz_stats').is(":checked")) {
                cmplzSetCookie('cmplz_stats', 'allow', complianz.cookie_expiry);
            } else {
                cmplzSetCookie('cmplz_stats', 'deny', complianz.cookie_expiry);
            }
        }

        //marketing cookies acceptance
        if ($('#cmplz_all').length){
            if ($('#cmplz_all').is(":checked")) {
                cmplzSetCookie('cmplz_all', 'allow', complianz.cookie_expiry);
            } else {
                cmplzSetCookie('cmplz_all', 'deny', complianz.cookie_expiry);
            }
        }
    }


    /*
    * Enable the checkbox for each category which was enabled
    *
    *
    * */

    function cmplzSetCategoryCheckboxes(){
        //tag manager
        if (complianz.tm_categories) {
            for (var i = 0; i < complianz.cat_num; i++) {
                if (cmplzGetCookie('cmplz_event_' + i)==='allow') $('#cmplz_' + i).prop('checked', true);
            }
        }

        if (cmplzGetCookie('cmplz_all')==='allow') $('#cmplz_all').prop('checked', true);
        if (cmplzGetCookie('cmplz_stats')==='allow') $('#cmplz_stats').prop('checked', true);
    }

    function cmplzMergeObject(target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];
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

    function cmplzSetAcceptedCookiePolicyID(){
        cmplzSetCookie('complianz_policy_id', complianz.current_policy_id, complianz.cookie_expiry);

    }

});