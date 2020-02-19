jQuery(document).ready(function ($) {

    var ccName;

    $('.cmplz-color-picker').wpColorPicker({
        change:
            function (event, ui) {
                var container_id = $(event.target).data('hidden-input');
                $('#' + container_id).val(ui.color.toString());
                cmplz_cookie_warning()
            }
        }
    );

    var settingConsentType = ccConsentType;
    if (ccConsentType === 'optinstats') settingConsentType = 'optin';


    $(document).on('keyup', 'input[name=cmplz_dismiss]', function () {
        $(".cc-dismiss").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_accept]', function () {
        $(".cc-allow").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_accept_optinstats]', function () {
        $(".cc-allow").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_accept_informational]', function () {
        $(".cc-dismiss").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_revoke]', function () {
        $(".cc-revoke").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_readmore_privacy]', function () {
        $(".cc-link.privacy-policy").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_view_preferences]', function () {
        $(".cc-revoke").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_save_preferences]', function () {
        $(".cc-save").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_category_functional]', function () {
        $(".cc-functional").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_category_stats]', function () {
        //keep up to date with other tabs
        $('input[name=cmplz_category_stats]').val($(this).val());
        $(".cc-stats").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_category_prefs]', function () {
        //keep up to date with other tabs
        $('input[name=cmplz_category_prefs]').val($(this).val());
        $(".cc-prefs").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_category_all]', function () {
        //keep up to date with other tabs
        $('input[name=cmplz_category_all]').val($(this).val());
        $(".cc-all").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_readmore_optin]', function () {
        $(".cc-link.cookie-policy").html($(this).val());
    });
    $(document).on('keyup', 'input[name=cmplz_readmore_optinstats]', function () {
        $(".cc-link.cookie-policy").html($(this).val());
    });


    $(document).on('keyup', 'input[name=cmplz_readmore_optout]', function () {
        $(".cc-link.cookie-policy").html($(this).val());
    });

	$(document).on('keyup', 'input[name=cmplz_readmore_optout_dnsmpi]', function () {
		$(".cc-link.cookie-policy").html($(this).val());
	});


    $(document).on('keyup', 'textarea[name=cmplz_tagmanager_categories]', function () {
        //keep up to date with other tabs
        $('input[name=cmplz_save_tagmanager_categories]').val($(this).val());
        cmplz_cookie_warning();
    });

    setTimeout(function () {
		if (typeof tinymce !== 'undefined' ) {
			for (var i = 0; i < tinymce.editors.length; i++) {
				tinymce.editors[i].on('NodeChange keyup', function (ed, e) {
					var content;
					var link = $(".cc-message").find('a').html();
					var editor_id = 'cmplz_message_' + settingConsentType;
					var textarea_id = 'cmplz_message';
					if (typeof editor_id == 'undefined') editor_id = wpActiveEditor;
					if (typeof textarea_id == 'undefined') textarea_id = editor_id;

					if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
						content = tinyMCE.get(editor_id).getContent();
					} else {
						content = jQuery('#' + textarea_id).val();
					}
					content = content.replace(/<[\/]{0,1}(p)[^><]*>/ig, "");
					$(".cc-message").html(content + '<a href="#" class="cc-link cookie-policy">' + link + '</a>');
					// Update HTML view textarea (that is the one used to send the data to server).
				});
			}
		}

    }, 1500);

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
        cmplz_apply_style();
    });

    $(document).on('click', '.region-link', function () {
        ccConsentType = $(this).data('tab');
        settingConsentType = ccConsentType;
        if (ccConsentType === 'optinstats') settingConsentType = 'optin';
        cmplz_cookie_warning();
    });

    $(document).on('change', 'input[name=cmplz_use_custom_cookie_css]', function () {
        cmplz_apply_style();
    });

    cmplz_apply_style();
    function cmplz_apply_style(){
        var checked = $('input[name=cmplz_use_custom_cookie_css]').is(':checked');
        if (checked){
            $('<style id="cmplz-cookie-inline-css">')
                .prop("type", "text/css")
                .html($('textarea[name="cmplz_custom_css"]').val()).appendTo("head");
        } else {
            $("#cmplz-cookie-inline-css").remove();
        }

    }

    $(document).on('change', 'input[name=cmplz_use_categories]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'input[name=cmplz_use_categories_optinstats]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'input[name=cmplz_hide_revoke]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'input[name=cmplz_soft_cookiewall]', function () {
        cmplz_cookie_warning();
    });

    $(document).on('change', 'input[name=cmplz_use_tagmanager_categories]', function () {
        cmplz_cookie_warning();
    });

    reRenderConditionQuestions();

    function reRenderConditionQuestions(){
        $('#optinstats [data-condition-question="use_categories"]').data('condition-question','use_categories_optinstats');
        $.event.trigger({
            type: "cmplzRenderConditions"
        });

        //when there is more than one optin type, optin and optinstats, and both use_cats settings are the same, hide the fields on optinstats
        if ($('#optin').length && $('#optinstats').length){

            var use_cats = $('input[name=cmplz_use_categories]').is(':checked');
            var use_cats_optinstats = $('input[name=cmplz_use_categories_optinstats]').is(':checked');

            if (use_cats === use_cats_optinstats){

                $('#optinstats .field-group').each(function(){
                    $(this).hide();
                });
            }
        }
        //show always this field
        $('#optinstats [data-condition-question="show_always"]').show();
    }

    /**
     * if both EU and UK are active, we might have some double input fields. Remove all double fields from the form to
     * prevent settings not being saved
     */

    $(document).on('submit', '#cookie-settings',function(e){
        var inputs = $('#cookie-settings :input');

        if ($('#optin').length && $('#optinstats').length){
            inputs.each(function(){
                var name = $(this).attr("name");
                if (typeof name !== 'undefined' && name.indexOf('cmplz_')!==-1){
                    //console.log('name '+name);
                    if ($(this).closest('tr').is(":hidden")){
                        console.log(name +' is hidden');
                        $(this).remove();
                    }
                }


            });
        }
    });

    cmplz_cookie_warning();
    function cmplz_cookie_warning() {

        var ccDismiss;

        if (ccName) {
            ccName.fadeOut();
            ccName.destroy();
        }

        if (ccConsentType === 'optin'){
            var ccCategories = $('input[name=cmplz_use_categories]').is(':checked');
        } else {
            var ccCategories = $('input[name=cmplz_use_categories_optinstats]').is(':checked');
        }
        reRenderConditionQuestions();
        if (settingConsentType === 'optin'){
            ccDismiss = $('input[name=cmplz_dismiss]').val();
        } else {
            ccDismiss = $('input[name=cmplz_accept_informational]').val();
        }


        var ccHideRevoke = $('input[name=cmplz_hide_revoke]').is(':checked');

        if (ccHideRevoke) {
            ccHideRevoke = 'cc-hidden';
        } else {
            ccHideRevoke = '';
        }

        var ccMessage = $('textarea[name=cmplz_message_'+settingConsentType + ']').val();

		var ccAllow = $('input[name=cmplz_accept]').val();
		if ($('input[name=cmplz_readmore_'+settingConsentType + '_dnsmpi]').length){
			var ccLink = $('input[name=cmplz_readmore_'+settingConsentType + '_dnsmpi]').val();
		} else {
			var ccLink = $('input[name=cmplz_readmore_'+settingConsentType + ']').val();
		}
        var ccStatic = false;
        var ccBorder = $('input[name=cmplz_border_color]').val();
        var ccPosition = $('select[name=cmplz_position]').val();
        var ccType = 'opt-in';
        var ccPrivacyLink = '';

        if (ccConsentType==='optout') {
            ccType = 'opt-out';
            ccCategories = false;
            if ($('input[name=cmplz_readmore_privacy]').length)
                ccPrivacyLink = '<span class="cc-divider">&nbsp;-&nbsp;</span><a aria-label="learn more about privacy" tabindex="0" class="cc-link privacy-policy" href="#">' + $('input[name=cmplz_readmore_privacy]').val() + '</a>';
        }

        var ccTheme = $('select[name=cmplz_theme]').val();
        var ccLayout = 'basic';
        var ccPopupTextColor = $('input[name=cmplz_popup_text_color]').val();
        var ccButtonBackgroundColor = $('input[name=cmplz_button_background_color]').val();
        var ccButtonTextColor = $('input[name=cmplz_button_text_color]').val();
        var ccSavePreferences = $('input[name=cmplz_save_preferences]').val();
        var ccViewPreferences = $('input[name=cmplz_view_preferences]').val();
        var ccCategoryStats = $('input[name=cmplz_category_stats]').val();
        var ccCategoryPrefs = $('input[name=cmplz_category_prefs]').val();
        var ccRevokeText = $('input[name=cmplz_revoke]').val();
        var ccCheckboxes='';
        var ccCategoryFunctional = '';
        var ccCategoryAll = '';
        if (ccPosition==='bottom-left'){
            $('.cmplz-cookiebanner-save-button').css({"textAlign": "right"});
        } else {
            $('.cmplz-cookiebanner-save-button').css({"textAlign": "left"});
        }

        if (ccCategories) {
            var ccUseTagManagerCategories = $('textarea[name=cmplz_tagmanager_categories]').length;
            var ccTagManagerCategories = $('textarea[name=cmplz_tagmanager_categories]').val();
            var ccHasStatsCategory;
            if (!ccUseTagManagerCategories) {
                if (ccConsentType === 'optin') {
                    ccHasStatsCategory = $('input[name=cmplz_cookie_warning_required_stats]').val();
                } else if (ccConsentType === 'optinstats') {
                    ccHasStatsCategory = true;
                }
            }

            ccCategoryFunctional = $('input[name=cmplz_category_functional]').val();
            ccCategoryAll = $('input[name=cmplz_category_all]').val();

            var ccCheckboxBase = '<input type="checkbox" id="cmplz_all" style="display: none;"><label for="cmplz_all" class="cc-check"><svg width="16px" height="16px" viewBox="0 0 18 18"> <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path> <polyline points="1 9 7 14 15 4"></polyline></svg></label>';

            //minimum
            var ccCheckboxFunctional = ccCheckboxBase.replace('type', 'checked disabled type');
            ccCheckboxFunctional = ccCheckboxFunctional.replace(/cmplz_all/g, 'cmplz_functional');
            var ccCheckboxPrefs = ccCheckboxBase.replace(/cmplz_all/g, 'cmplz_prefs');
            ccCheckboxes = '<label>' + ccCheckboxFunctional + '<span class="cc-functional cc-category">{{categoryfunctional}}</span></label>';
            if (cmplzUsePreferences) ccCheckboxes += '<label>' + ccCheckboxPrefs + '<span class="cc-prefs cc-category">'+ccCategoryPrefs+'</span></label>';

            ccType = 'categories';
            ccLayout = 'categories-layout';

            ccRevokeText =ccViewPreferences;
            if (ccHasStatsCategory){
                ccCheckboxes += '<label>' + ccCheckboxBase.replace(/cmplz_all/g, 'cmplz_stats') + '<span class="cc-stats cc-category">'+ccCategoryStats+'</span></label>';
            }

            if (ccUseTagManagerCategories){
                var tmCatsKV = ccTagManagerCategories.split(",");
                tmCatsKV.forEach(function(category, i) {
                    if (category.length > 0) {
                        var tmp = ccCheckboxBase.replace(/cmplz_all/g, 'cmplz_' + i);
                        ccCheckboxes += '<label>' + tmp + '<span class="cc-tm cc-category">' + category.trim() + '</span></label>';
                    }
                });
                ccCheckboxes += '<label>' + ccCheckboxBase + '<span class="cc-all cc-category">{{categoryall}}</span></label>';

            } else {
                ccCheckboxes += '<label>' + ccCheckboxBase + '<span class="cc-all cc-category">{{categoryall}}</span></label>';
            }
        }

        if (ccPosition === 'static') {
            ccStatic = true;
            ccPosition = 'top';
        }

        if (ccTheme === 'edgeless') {
            ccBorder = false;
        }

        var ccStatus;
        window.cookieconsent.initialise({
            cookie: {
                name: 'complianz_config',
                expiryDays: 1
            },
            "revokeBtn": '<div class="cc-revoke ' + ccHideRevoke + ' {{classes}}">' + ccRevokeText + '</div>',
            "palette": {
                "popup": {
                    "background": $('input[name=cmplz_popup_background_color]').val(),
                    "text": ccPopupTextColor,
                },
                "button": {
                    "background": ccButtonBackgroundColor,
                    "text": ccButtonTextColor,
                    "border": ccBorder
                }
            },
            "layout": ccLayout,
            "layouts": {
                'categories-layout': '{{messagelink}}{{categories-checkboxes}}{{compliance}}',
            },
            "elements": {
                "categories-checkboxes": ccCheckboxes,
                "save": '<a aria-label="save cookies" tabindex="0" class="cc-btn cc-save">{{save_preferences}}</a>',
                "messagelink": '<span id="cookieconsent:desc" class="cc-message">{{message}} <a aria-label="learn more about cookies" tabindex="0" class="cc-link cookie-policy" href="{{href}}" target="_blank">{{link}}</a>' + ccPrivacyLink + '</span>',
            },
            "type": ccType,
            "compliance": {
                'categories': '<div class="cc-compliance cc-highlight">{{save}}</div>',
            },
            "theme": ccTheme,
            "static": ccStatic,
            "position": ccPosition,
            "content": {
                "save_preferences" : ccSavePreferences,
                "message": ccMessage,
                "dismiss": ccDismiss,
                "deny": '',
                "allow": ccAllow,
                "link": ccLink,
                "href": '#',
                "categoryfunctional": ccCategoryFunctional,
                "categoryall": ccCategoryAll
            },
            onInitialise: function (status) {
                ccStatus = status;
            }
        }, function (popup) {
            ccName = popup;
            var ccSoftCookieWall = $('input[name=cmplz_soft_cookiewall]').is(':checked');
            if (ccSoftCookieWall){
                //disabled, because it prevents editing options
                //$(".cc-window").wrap("<div class='cmplz-soft-cookiewall preview'></div>" );
            }
            ccName.open();
            if ($('#cmplz_functional').length) {
                if (ccStatus === 'allow') $('#cmplz_all').prop('checked', true);
                $('#cmplz_functional:checked + .cc-check svg').css({"stroke": ccPopupTextColor});
                $('#cmplz_all:checked + .cc-check svg').css({"stroke": ccPopupTextColor});
                for (i = 0; i < 5; i++) {
                    $('#cmplz_'+i+':checked + .cc-check svg').css({"stroke": ccPopupTextColor});
                }
                $('.cc-save').css({"border-color" : ccBorder, "background-color": ccButtonBackgroundColor, "color" : ccButtonTextColor});
                $('.cc-check svg').css({"stroke": ccPopupTextColor});
            }

        });

        $(document).on('click', '.cc-save', function(){
            if ($('#cmplz_all').is(":checked")) {
                ccName.setStatus(cookieconsent.status.allow);
            } else {
                ccName.setStatus(cookieconsent.status.dismiss);
            }
            ccName.close();
            $('.cc-revoke').fadeIn();
        });

        if (ccPosition === 'top') {
            $('.cc-window').css({'top': '30px'});
        }

        //make it float over the wp menu
        if (ccPosition === 'static') {
            $('.cc-grower').css('z-index', 10);
            $('.cc-grower').css('position', 'relative');
        }
    }

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }

        return false;
    }

});
