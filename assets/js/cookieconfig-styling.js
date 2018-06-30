jQuery(document).ready(function ($) {
    var ccName = false;
    $('.cmplz-color-picker').wpColorPicker({
            change:
                function (event, ui) {
                    var container_id = $(event.target).data('hidden-input');
                    $('#' + container_id).val(ui.color.toString());
                    cmplz_cookie_warning()
                }
        }
    );

    $(document).on('keyup', 'input[name=cmplz_dismiss]', function () {
        $(".cc-dismiss").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_accept]', function () {
        $(".cc-allow").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_revoke]', function () {
        $(".cc-revoke").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_readmore]', function () {
        $(".cc-link").html($(this).val());
    });

    $(document).on('keyup', 'textarea[name=cmplz_message]', function () {
        var link = $(".cc-message").find('a').html();
        $(".cc-message").html($(this).val() + '<a href="#" class="cc-link">' + link + '</a>');
    });

    $(document).on('change', 'select[name=cmplz_static]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'select[name=cmplz_position]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'select[name=cmplz_theme]', function () {
        cmplz_cookie_warning();
    });

    cmplz_cookie_warning();

    function cmplz_cookie_warning(){

        if (ccName){
            ccName.fadeOut();
        }

        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_config',
                expiryDays: 1
            },
            "revokeBtn": '<div class="cc-revoke {{classes}}">' + $('input[name=cmplz_revoke]').val() + '</div>',
            "palette": {
                "popup": {
                    "background": $('input[name=cmplz_popup_background_color]').val(),
                    "text": $('input[name=cmplz_popup_text_color]').val(),
                },
                "button": {
                    "background": $('input[name=cmplz_button_background_color]').val(),
                    "text": $('input[name=cmplz_button_text_color]').val(),
                }
            },

            "theme": $('select[name=cmplz_theme]').val(),
            //"static": $('select[name=cmplz_static]').val(),
            "position": $('select[name=cmplz_position]').val(),
            "type": "opt-in",
            "content": {
                "message": $('textarea[name=cmplz_message]').val(),
                "dismiss": $('input[name=cmplz_dismiss]').val(),
                "allow": $('input[name=cmplz_accept]').val(),
                "link": $('input[name=cmplz_readmore]').val(),
                "href": '#'
            }
        }, function (popup) {
            ccName = popup.open();
        });

        if ($('select[name=cmplz_position]').val()==='top') {
            $('.cc-window').css({'top': '30px'});
        }
    }



});