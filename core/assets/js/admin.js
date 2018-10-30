jQuery(document).ready(function ($) {
    'use strict';

    //colorpicker in the wizard
    $('.cmplz-color-picker').wpColorPicker({
            change:
                function (event, ui) {
                    var container_id = $(event.target).data('hidden-input');
                    $('#' + container_id).val(ui.color.toString());
                }
        }
    );

    /*
    * show and hide support form
    *
    * */

    $("#cmplz-support-form.hidden").hide().removeClass("hidden");
    $(document).on('click', '#cmplz-support-link', function (e) {
        e.preventDefault();

        var form = $('#cmplz-support-form');
        if (form.is(":visible")) {
            form.slideUp();
        } else {
            form.slideDown();
        }
    });

    //validation of checkboxes
    cmplz_validate_checkboxes();
    $('.cmplz-validate-multicheckbox .is-required:checkbox').change(cmplz_validate_checkboxes);

    function cmplz_validate_checkboxes() {
        $('.cmplz-validate-multicheckbox').each(function (i) {
            var set_required = [];
            var all_unchecked = true;
            $(this).find(':checkbox').each(function (i) {

                set_required.push($(this));

                if ($(this).is(':checked')) {
                    all_unchecked = false;
                }
            });
            var container = $(this).closest('.field-group').find('.cmplz-label');
            if (all_unchecked) {
                container.removeClass('valid-multicheckbox');
                container.addClass('invalid-multicheckbox');
                $.each(set_required, function (index, item) {
                    item.prop('required', true);
                    item.addClass('is-required');
                });

            } else {
                container.removeClass('invalid-multicheckbox');
                container.addClass('valid-multicheckbox');
                $.each(set_required, function (index, item) {
                    item.prop('required', false);
                    item.removeClass('is-required');
                });
            }

        });


        //now apply the required.

        check_conditions();
    }


    //validation of checkboxes
    cmplz_validate_radios();
    $('input:radio').attr('required',true).click(cmplz_validate_radios);
    //$(document).on('click','input:radio:required', cmplz_validate_radios );
    function cmplz_validate_radios() {
        $('.cmplz-validate-radio').each(function (i) {
            var set_required = [];
            var all_unchecked = true;

            $(this).find('input:radio').each(function (i) {
                set_required.push($(this));
                if ($(this).is(':checked')) {
                    all_unchecked = false;
                }
            });
            var container = $(this).closest('.field-group').find('.cmplz-label');
            if (all_unchecked) {
                container.removeClass('valid-radio');
                container.addClass('invalid-radio');
            } else {
                container.removeClass('invalid-radio');
                container.addClass('valid-radio');
            }

        });


        //now apply the required.

        check_conditions();
    }

    check_conditions();
    $("input").change(function (e) {
        check_conditions();
    });

    $("select").change(function (e) {
        check_conditions();
    });

    $("textarea").change(function (e) {
        check_conditions();
    });

    /*conditional fields*/
    function check_conditions() {
        var value;
        var showIfConditionMet = true;

        $(".condition-check").each(function (e) {
            var question = 'cmplz_' + $(this).data("condition-question");
            var condition_type = 'AND';

            if (question == undefined) return;

            var condition_answer = $(this).data("condition-answer");

            //remove required attribute of child, and set a class.
            var input = $(this).find('input[type=checkbox]');
            if (!input.length) {
                input = $(this).find('input');
            }
            if (!input.length) {
                input = $(this).find('textarea');
            }
            if (!input.length) {
                input = $(this).find('select');
            }

            if (input.length && input[0].hasAttribute('required')) {
                input.addClass('is-required');
            }

            //cast into string
            condition_answer += "";

            if (condition_answer.indexOf('NOT ') !== -1) {
                condition_answer = condition_answer.replace('NOT ', '');
                showIfConditionMet = false;
            } else {
                showIfConditionMet = true;
            }
            var condition_answers = [];
            if (condition_answer.indexOf(' OR ') !== -1) {
                condition_answers = condition_answer.split(' OR ');
                condition_type = 'OR';
            } else {
                condition_answers = [condition_answer];
            }

            var container = $(this);
            var conditionMet = false;
            condition_answers.forEach(function (condition_answer) {
                value = get_input_value(question);

                if ($('select[name=' + question + ']').length) {
                    value = Array($('select[name=' + question + ']').val());
                }

                if ($("input[name='" + question + "[" + condition_answer + "]" + "']").length){
                    if ($("input[name='" + question + "[" + condition_answer + "]" + "']").is(':checked')) {
                        conditionMet = true;
                        value = [];
                    } else {
                        conditionMet = false;
                        value = [];
                    }
                }

                if (showIfConditionMet) {

                    //check if the index of the value is the condition, or, if the value is the condition
                    if (conditionMet || value.indexOf(condition_answer) != -1 || (value == condition_answer)) {

                        container.removeClass("hidden");
                        //remove required attribute of child, and set a class.
                        if (input.hasClass('is-required')) input.prop('required', true);
                        //prevent further checks if it's an or statement
                        if (condition_type === 'OR') conditionMet = true;

                    } else {
                        container.addClass("hidden");
                        if (input.hasClass('is-required')) input.prop('required', false);
                        //prevent further checks if it's an or statement
                        if (condition_type === 'OR') return;
                    }
                } else {

                    if (conditionMet || value.indexOf(condition_answer) != -1 || (value == condition_answer)) {
                        container.addClass("hidden");
                        if (input.hasClass('is-required')) input.prop('required', false);

                    } else {
                        container.removeClass("hidden");
                        if (input.hasClass('is-required')) input.prop('required', true);
                    }
                }
            });

        });
    }


    /*
    get checkbox values, array proof.
*/

    function get_input_value(fieldName) {

        if ($('input[name^=' + fieldName + ']').attr('type') == 'text') {
            return $('input[name^=' + fieldName + ']').val();
        } else {
            var checked_boxes = [];
            $('input[name^=' + fieldName + ']:checked').each(function () {
                checked_boxes[checked_boxes.length] = $(this).val();
            });
            return checked_boxes;
        }
    }


    /*cookie scan */


    var cmplz_interval = 1000;
    var progress = complianz_admin.progress;
    var progressBar = $('.cmplz-progress-bar');
    var cookieContainer = $(".detected-cookies");
    var previous_page;

    function checkIframeLoaded() {
        // Get a handle to the iframe element
        var iframe = document.getElementById('cmplz_cookie_scan_frame');
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        if (!cookieContainer.find('.cmplz-loader').length && progress < 100) {
            cookieContainer.html('<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');
            cookieContainer.addClass('loader');
        }
        // Check if loading is complete
        iframe.onload = function () {
            // The loading is complete, call the function we want executed once the iframe is loaded
            if (progress >= 100) return;

            $.get(
                complianz_admin.admin_url,
                {
                    action: 'cmplz_get_scan_progress'
                },
                function (response) {
                    var obj;
                    if (response) {
                        obj = jQuery.parseJSON(response);

                        progress = parseInt(obj['progress']);
                        var next_page = obj['next_page'];
                        if (progress >= 100) {
                            progress = 100;
                            progressBar.css({width: progress + '%'});
                            $.ajax({
                                type: "GET",
                                url: complianz_admin.admin_url,
                                dataType: 'json',
                                data: ({
                                    action: 'load_detected_cookies',
                                }),
                                success: function (response) {
                                    if (response.success) {
                                        $('.detected-cookies').html(response.cookies);
                                        $('.detected-cookies.loader').removeClass('loader');
                                    }
                                }
                            });

                        } else {
                            progressBar.css({width: progress + '%'});

                            $("#cmplz_cookie_scan_frame").attr('src', next_page);

                            window.setTimeout(checkIframeLoaded, cmplz_interval);
                        }
                    }
                }
            );
            return;
        }

        // If we are here, it is not loaded. Set things up so we check   the status again
        window.setTimeout(checkIframeLoaded, cmplz_interval);
    }

    if ($('#cmplz_cookie_scan_frame').length) {
        checkIframeLoaded();
    }

    progressBar.css({width: progress + '%'});


    //custom text for policy
    $(document).on("click", ".cmplz-add-to-policy", function () {
        var title = $(this).closest('.cmplz-custom-privacy-text-container').find('.cmplz-custom-privacy-header').html();
        var text = $(this).closest('.cmplz-custom-privacy-text-container').find('.cmplz-custom-privacy-text').html();

        var content = tmce_getContent('cmplz_custom_privacy_policy_text');
        tmce_setContent(content + '<h3>' + title + '</h3>' + text, 'cmplz_custom_privacy_policy_text');
    });

    function tmce_getContent(editor_id, textarea_id) {
        if (typeof editor_id == 'undefined') editor_id = wpActiveEditor;
        if (typeof textarea_id == 'undefined') textarea_id = editor_id;

        if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).getContent();
        } else {
            return jQuery('#' + textarea_id).val();
        }
    }

    function tmce_setContent(content, editor_id, textarea_id) {
        if (typeof editor_id == 'undefined') editor_id = wpActiveEditor;
        if (typeof textarea_id == 'undefined') textarea_id = editor_id;

        if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).setContent(content);
        } else {
            return jQuery('#' + textarea_id).val(content);
        }
    }

    function tmce_focus(editor_id, textarea_id) {
        if (typeof editor_id == 'undefined') editor_id = wpActiveEditor;
        if (typeof textarea_id == 'undefined') textarea_id = editor_id;

        if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).focus();
        } else {
            return jQuery('#' + textarea_id).focus();
        }
    }

    //remove alerts
    window.setTimeout(function () {
        $(".cmplz-notice.cmplz-success").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2000);


    //statistics, handle graphs visibility

    var cmplz_visible_stat = '#bar_pct_all_container';
    $(cmplz_visible_stat).show();
    $(document).on('change', 'select[name=cmplz_region]', function () {

        $(cmplz_visible_stat).hide();
        var region = $('select[name=cmplz_region]').val();
        var type = $('select[name=stats_type]').val();
        cmplz_visible_stat = '#bar_' + type + '_' + region + '_container';
        $(cmplz_visible_stat).fadeIn();
    });

    $(document).on('change', 'select[name=stats_type]', function () {
        $(cmplz_visible_stat).hide();
        var region = 'eu';
        if ($('select[name=cmplz_region]').length) region = $('select[name=cmplz_region]').val();
        var type = $('select[name=stats_type]').val();
        cmplz_visible_stat = '#bar_' + type + '_' + region + '_container';
        $(cmplz_visible_stat).fadeIn();
    });

    /*
    * report unknown cookies
    *
    * */

    $(document).on('click', '#cmplz-report-unknown-cookies', function(){
        $.ajax({
            type: "POST",
            url: complianz_admin.admin_url,
            dataType: 'json',
            data: ({
                action: 'cmplz_report_unknown_cookies',
            }),
            success: function (response) {
                console.log(response);
                if (response.success) {
                    $('#cmplz-report-unknown-cookies').hide();
                    $('#cmplz-report-confirmation').show();
                }
            }
        });
    });

});