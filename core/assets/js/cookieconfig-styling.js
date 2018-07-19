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

    setTimeout(function () {
        for (var i = 0; i < tinymce.editors.length; i++) {
            tinymce.editors[i].on('NodeChange keyup', function (ed, e) {

                var link = $(".cc-message").find('a').html();
                var editor_id = 'cmplz_message';
                var textarea_id = 'cmplz_message';
                if (typeof editor_id == 'undefined') editor_id = wpActiveEditor;
                if (typeof textarea_id == 'undefined') textarea_id = editor_id;

                if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
                    var content = tinyMCE.get(editor_id).getContent();
                } else {
                    var content = jQuery('#' + textarea_id).val();
                }
                content = content.replace(/<[\/]{0,1}(p)[^><]*>/ig,"");
                $(".cc-message").html(content + '<a href="#" class="cc-link">' + link + '</a>');
                // Update HTML view textarea (that is the one used to send the data to server).

            });
        }
    }, 1000);


    $(document).on('change', 'select[name=cmplz_static]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'select[name=cmplz_position]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'select[name=cmplz_theme]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('keyup', '#cmplz_custom_csseditor', function () {

        $("<style>")
            .prop("type", "text/css")
            .html($('textarea[name="cmplz_custom_css"]').val()).appendTo("head");
    });




    cmplz_cookie_warning();

    function cmplz_cookie_warning(){

        if (ccName){
            ccName.fadeOut();
        }

        var static = false;
        var border = $('input[name=cmplz_border_color]').val();
        var position = $('select[name=cmplz_position]').val();
        if ($('select[name=cmplz_position]').val() == 'static'){
            static = true;
            position = 'top';
        }

        if ($('select[name=cmplz_theme]').val()=='edgeless'){
           border = false;
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
                    "border": border,
                }
            },

            "theme": $('select[name=cmplz_theme]').val(),
            "static": static,
            "position": position,
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

        //make it float over the wp menu
        if ($('select[name=cmplz_position]').val() == 'static'){
            $('.cc-grower').css('z-index', 10);
            $('.cc-grower').css('position', 'relative');
        }
    }



});