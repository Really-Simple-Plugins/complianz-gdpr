function deleteAllCookies() {
    document.cookie.split(";").forEach(
        function (c) {
            if (c.indexOf('complianz_consent_status') === -1 && c.indexOf('complianz_policy_id') === -1) {
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
            $(this).attr('type', 'text/javascript');
            var src = $(this).attr('src');
            if (src && src.length) {
                $.getScript(src, function () {});
            }
            if ($(this).text().length && $(this).text().length>40){
                eval($(this).text());
            }

        });

    }

    var use_country = complianz.use_country;
    var country_code = '';
    //if use country is enabled, we check the users's country to see if the cookie warning applies.
    if (use_country) {
        $.ajax({
            type: "GET",
            url: complianz.url,
            dataType: 'json',
            data: ({
                action: 'get_country_code',
            }),
            success: function (response) {
                if (!response.success) {
                    use_country = false;
                } else {
                    country_code = response.country;
                }
                cmplz_cookie_warning();
            }
        });
    } else {
        cmplz_cookie_warning();
    }

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
                }
            },
            onStatusChange: function (status, chosenBefore) {
                if (status == 'allow') {
                    complianz_enable_cookies();
                    complianz_enable_scripts();
                } else {
                    //complianz_disable_cookies();
                }
            },
            onRevokeChoice: function () {
                complianz_disable_cookies();
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
            "regionalLaw": true,
            "law": {
                countryCode: country_code,
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

    //optional method to revoke cookie acceptance from a custom link
    $(document).on('click', '.cc-revoke-custom', function(){$('.cc-revoke').click();});


});