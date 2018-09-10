'use strict';

function complianz_deleteAllCookies() {
    document.cookie.split(";").forEach(
    function (c) {

        if (c.indexOf('cmplz_stats') === -1 && c.indexOf('cmplz_id') === -1 && c.indexOf('complianz_consent_status') === -1 && c.indexOf('complianz_policy_id') === -1) {
            document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        }
    }
    );
}

jQuery(document).ready(function ($) {

    function complianz_enable_scripts(){
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
        $('.cmplz-script').each(function(i, obj) {
            if ($(this).text().length){
                var str = $(this).text();
                if(str.indexOf('analytics.js') !== -1 || str.indexOf('ga.js') !== -1) {
                    $('<script>')
                        .attr('type', 'text/javascript')
                        .text(str)
                        .appendTo($(this).parent());
                    $(this).remove();
                }
            }
        });
    }

    /*
    * We use ajax to check the country, otherwise caching could prevent the user specific warning
    *
    * */

    var user_data = [];
    //check if it's already stored
    if (typeof(Storage) !== "undefined" && sessionStorage.cmplz_user_data) {
        user_data = JSON.parse(sessionStorage.cmplz_user_data);
    }

    //if not, reload
    if (user_data.length==0) {
        console.log('reloading data');
        $.ajax({
            type: "GET",
            url: complianz.url,
            dataType: 'json',
            data: ({
                action: 'cmplz_user_settings'
            }),
            success: function (response) {
                user_data = response;
                sessionStorage.cmplz_user_data = JSON.stringify(user_data);
                conditionally_show_warning(user_data);
            }
        });
    } else {
        conditionally_show_warning(user_data);
    }

    function conditionally_show_warning(user){
        if (!user.do_not_track) {
            if (user.is_eu) {
                console.log('eu');
                cmplz_cookie_warning();
            } else {
                console.log('not eu');
                complianz_enable_cookies();
            }
        }
    }

    var ccStatus;
    var ccName;

    function cmplz_cookie_warning(){
        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_consent_status',
                expiryDays: complianz.cookie_expiry
            },
            onInitialise: function (status) {
                ccStatus = status;
                if (status === 'allow' && this.hasConsented()) {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                    complianz_accept();
                }
                if (cmplzGetCookie('cmplz_stats')==='allow'){
                    complianz_enable_stats();
                }
            },
            onStatusChange: function (status, chosenBefore) {
                ccStatus = status;
                if (status === 'allow') {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                    complianz_accept();
                } else if (ccStatus != status) {
                    if (complianz.use_categories) {
                        complianz_deleteAllCookies();
                        location.reload();
                    }
                }
            },
            onRevokeChoice: function () {
                if (!complianz.use_categories && ccStatus==='allow') {
                    complianz_deleteAllCookies();
                    location.reload();
                }
            },
            "revokeBtn": '<div class="cc-revoke cc-bottom {{classes}}">' + complianz.revoke + '</div>',
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
            if ($('#cmplz_all').length) {
                if (ccStatus === 'allow') $('#cmplz_all').prop('checked', true);
                if ($('#cmplz_stats').length && cmplzGetCookie('cmplz_stats')==='allow') $('#cmplz_stats').prop('checked', true);
                $('#cmplz_functional:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('#cmplz_all:checked + .cc-check svg').css({"stroke": complianz.popup_text_color});
                $('.cc-save').css({"border-color" : complianz.border_color ,"background-color": complianz.button_background_color, "color" : complianz.button_text_color});
                $('.cc-check svg').css({"stroke": complianz.popup_text_color});
            }
        });
    }

    $(document).on('click', '.cc-save', function(){
        var statsAllowed = cmplzGetCookie('cmplz_stats');
        var newStatsAllowed = $('#cmplz_stats').is(":checked");
        var allAllowed = ccStatus;
        var newAllAllowed = $('#cmplz_all').is(":checked");

        if (newStatsAllowed) {
            cmplzSetCookie('cmplz_stats', 'allow', complianz.cookie_expiry);
            complianz_enable_stats();
        } else {
            cmplzSetCookie('cmplz_stats', 'deny', complianz.cookie_expiry);
        }

        if (newAllAllowed) {
            console.log('set allow');
            ccName.setStatus(cookieconsent.status.allow);
        } else {
            //if we are currently allowed, we should now reload.
            ccName.setStatus(cookieconsent.status.dismiss);
        }

        //reload if we changed from all to stats, or from stats to functional
        if ((statsAllowed && !newStatsAllowed) || (allAllowed && !newAllAllowed) ){
            complianz_deleteAllCookies();
            location.reload();
        }
        ccName.close();
        $('.cc-revoke').fadeIn();

    });

    function complianz_accept(){
        console.log('tracking accepting cookies');
        $.ajax({
            type: "GET",
            url: complianz.url,
            dataType: 'json',
            data: ({
                action: 'cmplz_accept'
            })
        });
    }

    //optional method to revoke cookie acceptance from a custom link
    $(document).on('click', '.cc-revoke-custom', function(){$('.cc-revoke').click();});

    function cmplzSetCookie(cname,cvalue,exdays) {
        console.log('set cookie to '+cvalue);
        var d = new Date(); //Create an date object
        d.setTime(d.getTime() + (exdays*1000*60*60*24)); //Set the time to exdays from the current date in milliseconds. 1000 milliseonds = 1 second
        var expires = "expires=" + d.toGMTString(); //Compose the expirartion date
        window.document.cookie = cname+"="+cvalue+"; "+expires;//Set the cookie with value and the expiration date
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
});