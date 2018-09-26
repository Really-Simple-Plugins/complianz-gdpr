'use strict';

function complianz_deleteAllCookies() {
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

    //if not, reload
    if (cmplz_user_data.length===0) {
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
                conditionally_show_warning(cmplz_user_data);
            }
        });
    } else {
        conditionally_show_warning(cmplz_user_data);
    }

    function conditionally_show_warning(user){
        //for Non eu visitors, and DNT users, we just track the no-warning option
        if (user.do_not_track || !user.is_eu) {
            complianz_track_status('no-warning');
        } else {
            //if no status was saved before, we do it noW
            if (cmplzGetCookie('cmplz_choice')!=='set') {
                complianz_track_status('no-choice');
                cmplzSetCookie('cmplz_choice', 'set', complianz.cookie_expiry);
            }
        }


        if (!user.do_not_track) {
            if (user.is_eu) {
                console.log('eu');
                cmplz_cookie_warning();
            } else {
                console.log('not eu');
                complianz_enable_cookies();
                complianz_enable_scripts();
                //cookie blocker is default enabled, so all scripts need to be enabled.
            }
        }

    }

    function cmplz_cookie_warning(){
        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_consent_status',
                expiryDays: complianz.cookie_expiry
            },
            onInitialise: function (status) {
                //runs only when dismissed or accepted
                ccStatus = status;
                if (status === 'allow' && this.hasConsented()) {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                }

            },
            onStatusChange: function (status, chosenBefore) {
                ccStatus = status;
                //track here only for non categorie style, the default one is tracked on save.
                if (!complianz.use_categories) {
                    complianz_track_status();
                }

                if (status === 'allow') {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                }
            },
            onRevokeChoice: function () {
                if (!complianz.use_categories && ccStatus==='allow') {
                    //complianz_deleteAllCookies();
                    complianz_track_status();
                    //location.reload();
                }
            },
             "revokeBtn": '<div class="cc-revoke {{classes}}">' + complianz.revoke + '</div>',
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
                "message": complianz.message,
                "dismiss": complianz.dismiss,
                "allow": complianz.accept,
                "link": complianz.readmore,
                "href": complianz.readmore_url
            }
        }, function (popup) {
            ccName = popup;
            //this function always runs
            if (cmplzUsesCategories()) {
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
        if (cmplzUsesCategories()) {
            cmplzSaveCategories();
            cmplzFireCategories();
        }

        //track status on saving of settings.
        complianz_track_status();

        ccName.close();
        $('.cc-revoke').fadeIn();

    });

    function complianz_track_status(status){
        status = typeof status !== 'undefined' ? status : false;

        if (!status) status = cmplzGetHighestAcceptance();
        $.ajax({
            type: "GET",
            url: complianz.url,
            dataType: 'json',
            data: ({
                action: 'cmplz_accept',
                status: status
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
        if (cmplzUsesCategories()){

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
            var status = cmplzGetCookie('complianz_consent_status');
            if (status === 'allow') {
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

    /*
    * check if categories are used
    *
    * */
    function cmplzUsesCategories(){
        var cats =  $('#cmplz_functional').length;
        return cats;
    }
});