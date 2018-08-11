function complianz_deleteAllCookies() {
    document.cookie.split(";").forEach(
    function (c) {
        if (c.indexOf('cmplz_id') === -1 && c.indexOf('complianz_consent_status') === -1 && c.indexOf('complianz_policy_id') === -1) {
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

    /*
    * We use ajax to check the country, otherwise caching could prevent the user specific warning
    *
    * */

    $.ajax({
        type: "GET",
        url: complianz.url,
        dataType: 'json',
        data: ({
            action: 'cmplz_user_settings'
        }),
        success: function (response) {
            if (!response.do_not_track) {
                if (response.is_eu) {
                    console.log('eu');
                    cmplz_cookie_warning();
                } else {
                    console.log('not eu');
                    complianz_enable_cookies();
                }
            }
        }
    });

    function cmplz_cookie_warning(){
        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_consent_status',
                expiryDays: complianz.cookie_expiry
            },
            onInitialise: function (status) {
                if (status == 'allow' && this.hasConsented()) {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                    complianz_accept();
                }
            },
            onStatusChange: function (status, chosenBefore) {
                if (status == 'allow') {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                    complianz_accept();
                } else {
                    //complianz_deleteAllCookies();
                }
            },
            onRevokeChoice: function () {
                complianz_deleteAllCookies();
                location.reload();
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
            "type": "opt-in",
            "content": {
                "message": complianz.message,
                "dismiss": complianz.dismiss,
                "allow": complianz.accept,
                "link": complianz.readmore,
                "href": complianz.readmore_url
            }
        });
    }

    function complianz_accept(){
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


});