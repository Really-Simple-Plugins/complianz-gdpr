jQuery(document).ready(function ($) {
    'use strict';
	$(document).on('click','.cmplz-install-burst', function(){
		var btn =  $('button.cmplz-install-burst');
		var loader = '<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
		btn.html(loader);
		btn.attr('disabled', 'disabled');

		$.ajax({
			type: "GET",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				step: 'download',
				action: 'cmplz_install_plugin',
			}),
			success: function (response) {
				$.ajax({
					type: "GET",
					url: complianz_admin.admin_url,
					dataType: 'json',
					data: ({
						step: 'activate',
						action: 'cmplz_install_plugin',
					}),
					success: function (response) {
						let completed_text = $('.cmplz-completed-text').html();
						btn.html(completed_text);
					}
				});
			}
		});
	});

	// other_region_behaviour
	$(document).on('click', '.regions .cmplz-checkbox-container input', function(){
		cmplz_filter_other_region_options();
	});
	var region_field = $('.regions .cmplz-checkbox-container input')
	cmplz_filter_other_region_options(region_field);
	function cmplz_filter_other_region_options(){

		if ( !$('select[name=cmplz_other_region_behaviour]').length) {
			return;
		}

		$('.regions .cmplz-field').find(':checkbox').each(function (i) {
			var region = $(this).attr('name').replace(']','').replace('cmplz_regions[','');
			var option = $("select[name=cmplz_other_region_behaviour] option[value=" + region + "]");
			if ($(this).is(':checked')) {
				option.removeAttr('disabled');
			} else {
				option.attr('disabled', 'disabled');
			}
		});
	}

	$(document).on('click', '.cmplz-copy-shortcode', function () {
		var element_id = $(this).closest('.shortcode-container').find('.cmplz-shortcode').attr('id');
		var element = document.getElementById(element_id);
		var sel = window.getSelection();
		sel.removeAllRanges();
		var range = document.createRange();
		range.selectNodeContents(element);
		sel.addRange(range);
		var success;
		try {
			success = document.execCommand("copy");
		} catch (e) {
			success = false;
		}

		if (success) {
			var icon = $(this).find('.cmplz-tooltip-icon');
			icon.addClass('copied');
			setTimeout(function(){ icon.removeClass('copied') }, 1000);
		}
	});

	var cmplz_localstorage_selectors = $('.cmplz_save_localstorage');
	if ( cmplz_localstorage_selectors.length ) {
		cmplz_localstorage_selectors.each(function(){
			var name = $(this).attr('name');
			var value = window.localStorage.getItem(name);
			var curValue = $(this).val();
			//in case the option is removed (optin/optout), we check if the option that is found still exists
			if ( value == null || !$(this).find("option[value="+value+"]").length > 0){
				value = curValue;
				window.localStorage.setItem(name, value);
				$(this).val(value).change();
			}else if ( typeof value !== 'undefined' && value !== null  && value !== curValue ) {
				$(this).val(value).change();
			}
		});
	}

	$(document).on('change','.cmplz_save_localstorage', function(){
		const name = $(this).attr('name');
		const value = $(this).find(":selected").val();
		window.localStorage.setItem(name, value);
	});

	$(document).on('change', '.cmplz-download-document-selector', function(){
		var sel =  $(this);
		if ($(this).find(":selected").val().length!=0) {
			sel.closest('.cmplz-document').find('.cmplz-download-document').attr('disabled', false);
		} else {
			sel.closest('.cmplz-document').find('.cmplz-download-document').attr('disabled', true);
		}
	});

	$(document).on('click', '.cmplz-download-document', function () {
		var btn =  $(this);
		var oldBtnHtml = btn.html();
		var selectElement = $(this).closest('.cmplz-document').find('select');
		var url = selectElement.val();
		var fileTitle = $(this).closest('.cmplz-document').find('select option:selected').text();
		var loader = '<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
		btn.html(loader);
		btn.attr('disabled', 'disabled');

		var request = new XMLHttpRequest();
		request.responseType = 'blob';
		request.open('get', url, true);
		request.send();

		request.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var obj = window.URL.createObjectURL(this.response);

				var element = document.createElement('a');
				element.setAttribute('href',obj);
				element.setAttribute('download', fileTitle);
				document.body.appendChild(element);
				//onClick property
				element.click();
				setTimeout(function() {
					window.URL.revokeObjectURL(obj);
				}, 60 * 1000);
			}
		};

		request.onprogress = function(e) {
			btn.html(oldBtnHtml);
			btn.removeAttr("disabled");
		};

	});

    $(document).on('change', '.cmplz-grid-selector', function(){
    	var new_value = $(this).val();
    	var property_name = $(this).attr('id');
    	var url = window.location.href;
		var region = cmplzGetUrlParam(url, property_name);
		if (region !== false ) {
			url = url.replace('&'+property_name+'='+region, '' );
		}
		url += '&'+property_name+'='+new_value;
		window.location.replace(url);
	});

	function cmplzGetUrlParam(sPageURL, sParam) {
		if (typeof sPageURL === 'undefined') return false;

		var queryString = sPageURL.split('?');
		if (queryString.length == 1) return false;

		var sURLVariables = queryString[1].split('&'),
			sParameterName,
			i;
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
		return false;
	}

    //tabs
    $(document).on('click', '.cmplz-tablinks', function(){
        $(".cmplz-tablinks").removeClass('active');
        $(this).addClass('active');
        $(".cmplz-tabcontent").removeClass('active');
        $("#"+$(this).data('tab')).addClass('active');
        $('input[name=cmplz_active_tab]').val($(this).data('tab'));
    });

    //remove alerts
    window.setTimeout(function () {
        $(".cmplz-hide").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 2000);

    /*
    * open and close panels
    * */
    // $(document).on('click', '.cmplz-panel-toggle', function(){
    //     var content = $(this).closest('.cmplz-slide-panel').find('.cmplz-panel-content');
    //     var icon_toggle = $(this).closest('.cmplz-slide-panel').find('.cmplz-panel-toggle :first-child div');
    //     //close all open panels
	//
    //     if (content.is(':hidden')){
    //         icon_toggle.toggleClass('dashicons-arrow-down-alt2');
    //         icon_toggle.toggleClass('dashicons-arrow-right-alt2');
    //         content.slideDown("fast");
    //     } else {
    //         content.slideUp( 'fast');
    //         icon_toggle.toggleClass('dashicons-arrow-right-alt2');
    //         icon_toggle.toggleClass('dashicons-arrow-down-alt2');
    //     }
    // });

    $(document).on('click', '.cmplz-help-modal span', function(e){
        $(this).closest('.cmplz-help-modal').fadeOut();
    });

    //colorpicker in the wizard
    // $('.cmplz-color-picker').wpColorPicker({
    //         change:
    //             function (event, ui) {
    //                 var container_id = $(event.target).data('hidden-input');
    //                 $('#' + container_id).val(ui.color.toString());
    //             }
    //     }
    // );

	// Make wizard and settings fields selectable via the 'enter' key
	$('.cmplz-radio-container').keypress(function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			$(event.target).find(':radio').click();
		}
	});

	// Make checkboxes in wizard and settings fields selectable via the 'enter' key
	$('.cmplz-switch, .cmplz-checkbox-container').keypress(function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			$(event.target).find(':checkbox').click();
		}
	});



    /*
    *
    * On multiple fields, we check if all input type=text and textareas are filled
    *
    * */

    function cmplz_validate_multiple() {
        $('.multiple-field').each(function(){

            var completed=true;
            $(this).find('input[type=text]').each(function () {
               if ($(this).val()===''){
                   completed = false;
               }
            });

            $(this).find('textarea').each(function () {
                if ($(this).val()===''){
                    completed = false;
                }
            });

            var icon = $(this).closest('.cmplz-panel').find('.cmplz-multiple-field-validation i');
            if (completed){
                icon.removeClass('fa-times');
                icon.addClass('fa-check');
            } else {
                icon.addClass('fa-times');
                icon.removeClass('fa-check');
            }
        });
    }
    cmplz_validate_multiple()
    $(document).on('keyup', '.multiple-field input[type=text]', function () {
        cmplz_validate_multiple();
    });
    $(document).on('keyup', '.multiple-field textarea', function () {
        cmplz_validate_multiple();
    });


    //validation of checkboxes
    cmplz_validate_checkboxes();
    $(':checkbox').change(cmplz_validate_checkboxes);

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

    $(document).on('change', 'input', function (e) {
        check_conditions();
	});

    $(document).on('change', 'select', function (e) {
        check_conditions();
	});

    $(document).on('change', 'textarea', function (e) {
        check_conditions();
	});




    $(document).on("cmplzRenderConditions", check_conditions);

    /*conditional fields*/
    function check_conditions() {
        var value;
        var showIfConditionMet = true;

        $(".condition-check-1").each(function (e) {
            var i;
            for (i = 1; i < 4; i++) {
                var question = 'cmplz_' + $(this).data("condition-question-" + i);
                var condition_type = 'AND';

                if (question == 'cmplz_undefined') return;

                var condition_answer = $(this).data("condition-answer-" + i);

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
                var fieldName = $(this).data("fieldname");
                var conditionMet = false;
                condition_answers.forEach(function (condition_answer) {
                    value = get_input_value(question);

                    if ($('select[name="' + question + '"]').length) {
                        value = Array($('select[name=' + question + ']').val());
                    }

                    if ($("input[name='" + question + "[" + condition_answer + "]" + "']").length) {

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
                            container.removeClass("cmplz-hidden");
							$('.'+fieldName).removeClass("cmplz-hidden");
							// $(".condition-question-" + i).
                            //remove required attribute of child, and set a class.
                            if (input.hasClass('is-required')) input.prop('required', true);
                            //prevent further checks if it's an or/and statement
                            conditionMet = true;
                        } else {
							container.addClass("cmplz-hidden");
							$('.'+fieldName).addClass("cmplz-hidden");

							if (input.hasClass('is-required')) input.prop('required', false);
                        }
                    } else {
                        if (conditionMet || value.indexOf(condition_answer) != -1 || (value == condition_answer)) {
							container.addClass("cmplz-hidden");
							$('.'+fieldName).addClass("cmplz-hidden");

							if (input.hasClass('is-required')) input.prop('required', false);
                        } else {
							container.removeClass("cmplz-hidden");
							$('.'+fieldName).removeClass("cmplz-hidden");

							if (input.hasClass('is-required')) input.prop('required', true);
                            conditionMet = true;
                        }
                    }
                });
                if (!conditionMet) {
                    break;
                }
            }
        });

    }


    /**
        get checkbox values, array proof.
    */

    function get_input_value(fieldName) {

        if ($('input[name="' + fieldName + '"]').attr('type') == 'text') {
            return $('input[name^=' + fieldName + ']').val();
        } else {
            var checked_boxes = [];
            $('input[name="' + fieldName + '"]:checked').each(function () {
                checked_boxes[checked_boxes.length] = $(this).val();
            });
            return checked_boxes;
        }
    }


    /*cookie scan */
    var cmplz_interval = 10000;
    var progress = complianz_admin.progress;
    var progressBar = $('.cmplz-progress-bar');
    var cookieContainer = $(".detected-cookies");
    var previous_page;

    if ($("#cmplz-scan-progress").length){
        cmplz_interval = 3000;
    }

    function checkIframeLoaded() {
        // Get a handle to the iframe element
        var iframe = document.getElementById('cmplz_cookie_scan_frame');
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        if (!cookieContainer.find('.cmplz-loader').length && progress < 100) {
            // cookieContainer.html('<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');
            // cookieContainer.addClass('loader');
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
						var cookies = obj.cookies;
						$('.detected-cookies .cmplz-cookies-table').html(cookies.join("<br>"));
						$('.cmplz-scan-count').html(cookies.length);
                        progress = parseInt(obj['progress']);
                        var next_page = obj['next_page'];
                        if (progress >= 100) {
                            progress = 100;
                            progressBar.css({width: progress + '%'});
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

        // If we are here, it is not loaded. Set things up so we check the status again
        window.setTimeout(checkIframeLoaded, cmplz_interval);
    }

    if ($('#cmplz_cookie_scan_frame').length) {
        checkIframeLoaded();
    }

    progressBar.css({width: progress + '%'});

    /*Cookie Database sync*/
    var syncProgress = 0;
    var syncProgressBar = $('.cmplz-sync-progress-bar');
	var syncStatus = $('.cmplz-sync-status span');
	var syncButton = $('.cmplz-resync');
	syncStatus.hide();
    if ( $('#cmplz-sync-progress').length ) {
        var syncProgress = complianz_admin.syncProgress;
        if ( syncProgress<100 ) {
			syncButton.attr('disabled', 'disabled');
			syncStatus.show();
            syncProgressBar.css({width: syncProgress + '%'});
            syncCookieDatabase();
        }
    } else if ($('.cmplz-list-container').length){
        loadListItem();
    }

    /*restart sync*/
	$(document).on('click', '.cmplz-resync', function(){
		syncButton.attr('disabled', 'disabled');
		syncProgressBar.css({width: '0%'});
		syncStatus.show();
		syncCookieDatabase(true);
	});

    function syncCookieDatabase(restart) {
		restart = typeof restart !== 'undefined' ? restart : false;

		$.get(
            complianz_admin.admin_url,
            {
                action: 'cmplz_run_sync',
				restart: restart,
            },
            function (response) {
                var obj;
                if (response) {
                    obj = jQuery.parseJSON(response);

                    syncProgress = parseInt(obj['progress']);
                    var message = obj['message'];
                    if (typeof message !== 'undefined' && message.length>0){
                        $('#cmplz_action_error').removeClass('cmplz-hidden');
                        $('#cmplz_action_error .cmplz-panel').html(message);
                    }
                    if (syncProgress >= 100) {
                        syncProgress = 100;
                        $('#cmplz-sync-loader').html('');
						syncStatus.hide();
						syncButton.removeAttr("disabled");
						loadListItem();
                        syncProgressBar.css({width: syncProgress + '%'});
                    } else {
                        syncProgressBar.css({width: syncProgress + '%'});
                        window.setTimeout(syncCookieDatabase, 500);
                    }

                }
            }
        );
    }

    $(document).on('change', 'input[name=cmplz_show_deleted]', function(){
		loadListItem();
	});


    //custom text for policy
    $(document).on("click", ".cmplz-add-to-policy", function () {
        var title = $(this).closest('.cmplz-slide-panel').find('.cmplz-title').html();
        var text = $(this).closest('.cmplz-slide-panel').find('.cmplz-panel-content').html();

        var content = tmce_getContent('cmplz_custom_privacy_policy_text');
        tmce_setContent(content + '<h3>' + title + '</h3>' + text, 'cmplz_custom_privacy_policy_text');
        $(this).remove();
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

    /**
     * Keep personal data checkbox in sync with entry field
     */

    $(document).on('change', '.cmplz_isPersonalData', function(){
        cmplz_personalDataFieldVisibility($(this));
    });
    function cmplz_personalDataFieldVisibility(obj){
        var container = obj.closest('.cmplz-field');
        if (obj.is(":checked")) {
            container.find('.cmplz_collectedPersonalData').parent().show();
        } else {
            container.find('.cmplz_collectedPersonalData').parent().hide();
        }
    }

    /**
     * Keep thirdparty checkbox in sync with privacy policy url field
     */

    $(document).on('change', '.cmplz_thirdParty', function(){
        cmplz_privacyStatementUrlFieldVisibility($(this));
    });
    function cmplz_privacyStatementUrlFieldVisibility(obj){
        var container = obj.closest('.cmplz-field');
        if (obj.is(":checked")) {
            container.find('.cmplz_privacyStatementURL').parent().show();
        } else {
            container.find('.cmplz_privacyStatementURL').parent().hide();
        }
    }

    /**
     * Keep sync button in sync with disabled state for both cookies and services
     */
    $(document).on('change', '.cmplz_sync', function(){
        var container = $(this).closest('.cmplz-field');
		var checkbox = $(this);
        var disabled = false;
        if ( checkbox.is(":checked") ) disabled=true;
        container.find(':input').each(function () {
            if ($(this).attr('name')==='cmplz_remove_item'  ||
                $(this).attr('name')==='cmplz-save-item'    ||
                $(this).attr('name')==='cmplz_restore_item' ||
                $(this).attr('name')==='cmplz_showOnPolicy' ||
                $(this).attr('name')==='cmplz_sync') return;
            $(this).prop('disabled', disabled);
            if (disabled){
				$(this).closest('.cmplz-service-field div, .cmplz-cookie-field div').addClass('cmplz-disabled');
				$(this).closest('label').addClass('cmplz-disabled');
            } else {
				$(this).closest('.cmplz-service-field div, .cmplz-cookie-field div').removeClass('cmplz-disabled');
				$(this).closest('label').removeClass('cmplz-disabled');
            }
        });
    });

    /**
     * Keep use cdb in sync with sync button disabled state
     */

    $(document).on('change', '.cmplz_use_cdb_api', function(){
        var disabled = ($(this).val() === 'no') ? true : false;
        $('.cmplz-list-container').find(':input[name=cmplz_sync]').each(function () {
            var sync_checkbox = $(this).closest('label');
            if (disabled){
                sync_checkbox.find(':checkbox').prop('checked', false).change();
                sync_checkbox.addClass('cmplz-disabled');
            } else{
                sync_checkbox.removeClass('cmplz-disabled');
                sync_checkbox.find(':checkbox').prop('checked', true).change();
            }
        });
    });

    /**
     * Keep sync icon in sync.
     */

    $(document).on('change', '.cmplz_sync', function(){
        var container = $(this).closest('.cmplz-panel');

        if ($(this).is(":checked")) {
            container.find('.fa-sync-alt').removeClass('cmplz-disabled');

        } else {
            container.find('.fa-sync-alt').addClass('cmplz-disabled');
        }
    });

    /**
     * Keep show on policy icon in sync
     */

    $(document).on('change', '.cmplz_showOnPolicy', function(){
        var container = $(this).closest('.cmplz-panel');

        if ($(this).is(":checked")) {
            container.find('.fa-file').removeClass('cmplz-error');
        } else {
            container.find('.fa-file').addClass('cmplz-error');
        }
    });



    $(document).on('keyup', '.cmplz-panel input', function(){
        cmplzCheckIfCookieIsComplete($(this));
    });
    $(document).on('change', '.cmplz-panel select', function(){
        cmplzCheckIfCookieIsComplete($(this));
    });

    function cmplzCheckIfCookieIsComplete(obj){
        var isComplete = true;
        var container = obj.closest('.cmplz-panel');
        container.find(':input:not(.cmplz_cookieFunction)').each(function () {
            if (!$(this).is(':checkbox') && !$(this).is(':hidden') && $(this).prop("type")!=='button'){
                if ($(this).prop('nodeName')!=='SELECT' && $(this).val().length > 0) {
                    //text is complete
                } else if($(this).prop('nodeName')==='SELECT' && $(this).val()!=0){
                    //select is complete
                } else {
                    isComplete = false;
                }
            }
        });

        if (isComplete){
            var icon = container.find('.fa.fa-times');
            icon.removeClass('cmplz-error');
            icon.addClass('cmplz-success');
            icon.addClass('fa-check');
            icon.removeClass('fa-times');


        } else {
            var icon = container.find('.fa.fa-check');
            icon.addClass('cmplz-error');
            icon.removeClass('cmplz-success');
            icon.addClass('fa-times');
            icon.removeClass('fa-check');


        }
    }


    /**
     * handle language switch for cookies
     *
     **/

    if ($('#cmplz_language').length) {
        var syncProgress = complianz_admin.syncProgress;
        if (syncProgress==100) loadListItem();

        $(document).on('change', '#cmplz_language', function () {
            $('.cmplz-list-container').html('<div class="cmplz-skeleton"></div>');
            loadListItem();
        });
    }

    //select2 dropdown
    if ($('.cmplz-select2').length) {
        cmplzInitSelect2()
    }

    function cmplzInitSelect2() {
        $('.cmplz-select2').select2({
            tags: true,
            width:'400px',
        });

        $('.cmplz-select2-no-additions').select2({
            width:'400px',
        });
    }



    function loadListItem(){

        var language = $('#cmplz_language').val();
        var deleted = $('input[name=cmplz_show_deleted]').is(":checked");

		$('.cmplz-list-container').html('<div class="cmplz-skeleton"></div>');
        var type = $('#cmplz_language').data('type');
        $.ajax({
            type: "GET",
            url: complianz_admin.admin_url,
            dataType: 'json',
            data: ({
                language: language,
                action: 'cmplz_get_list',
                deleted: deleted,
                type: type,
            }),
            success: function (response) {
                if (response.success) {
                    $('.cmplz-list-container').html(response.html);

                    $('.cmplz_isPersonalData').each(function(){
                        cmplz_personalDataFieldVisibility($(this));
                    });

                    $('.cmplz_thirdParty').each(function(){
                        cmplz_privacyStatementUrlFieldVisibility($(this));
                    });

					cmpzlSyncDeleteRestoreButtons();

                    cmplzInitSelect2();
                }
            }
        });
    }

    function cmpzlSyncDeleteRestoreButtons(){
		$('.cmplz-panel').each(function(){
			if ($(this).hasClass('cmplz-deleted')){
				$(this).find('button[data-action="restore"]').show();
				$(this).find('button[data-action="delete"]').hide();
			} else {
				$(this).find('button[data-action="restore"]').hide();
				$(this).find('button[data-action="delete"]').show();
			}

		});
	}

    /**
    * add, Save and delete cookies
    *
    * */

    $(document).on('click', '.cmplz-edit-item', function(){
        var action = $(this).data('action');
        var btn = $(this);
        var type = btn.data('type');
        var container = $(this).closest('.cmplz-'+type+'-field');
        var panel = $(this).closest('.cmplz-panel.cmplz-slide-panel');
        var language = $('#cmplz_language').val();
        var btnHtml = btn.html();
        btn.html('<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');

        var item_id = container.data(type+'_id');
        var data = {};
        container.find(':input').each(function () {
            if ($(this).attr('type')==='button') return;

            if ($(this).attr('type')==='checkbox') {
                data[$(this).attr('name')] = $(this).is(":checked");
            } else {
                data[$(this).attr('name')] = $(this).val();
            }
        });

		if (action==='delete'){
			panel.addClass('cmplz-deleted');
		}

        $.ajax({
            type: "POST",
            url: complianz_admin.admin_url,
            dataType: 'json',
            data: ({
                item_id : item_id,
                type : type,
                data : JSON.stringify(data),
                cmplz_action : action,
                language:language,
                action: 'cmplz_edit_item',
            }),
            success: function (response) {
                if (response.success) {
                    if (action==='delete'){
						panel.remove();
                    }
					if (action==='restore'){
						panel.removeClass('cmplz-deleted');
                        container.find('input').each(function() {
                            $(this).removeAttr("disabled");
                        });
                        container.find('select').each(function() {
                            $(this).removeAttr("disabled");
                        });
                        container.children('div').removeClass('cmplz-disabled');
                        container.children('label').removeClass('cmplz-disabled');
                        container.find('button[name="cmplz-save-item"]').removeAttr("disabled");
                        container.find('.cmplz_sync').change();
					}
					cmpzlSyncDeleteRestoreButtons();
                    if (action==='add'){
                        var html = response.html;
                        var field = btn.closest('.cmplz-field');
                        var noservice = $('.cmplz-service-divider.no-service');
                        if (response.divider) {
                            if (noservice.length){
                                noservice.closest('.cmplz-service-cookie-list').append(html);
                            } else {
                                html = '<div class="cmplz-service-cookie-list">' + response.divider + html + '<div>';
                                field.find('.cmplz-list-container').append(html);
                            }
                            noservice = $('.cmplz-service-divider.no-service');
                            var disable_sync = $('.cmplz_use_cdb_api:checked').val() == 'no';
                            if (disable_sync) {
                                noservice.siblings(":last").find('.cmplz_sync').closest('label').addClass('cmplz-disabled');
                            }
                        } else {
                            field.find('.cmplz-list-container').append(html);
                        }

                    }
                    if (action==='save'){
                        var title = panel.find('.cmplz-title');
                        var name = container.find('.cmplz_name').val();
                        var new_title = title.text().replace(/\".*\"/, '"' + name + '"');
                        title.text(new_title);
                    }

                    btn.html(btnHtml);
                    cmplzInitSelect2();

                }
            }
        });
    });

    /**
     * add script
     * */
    $(document).on('click', '.cmplz_script_add', cmplz_script_add);
    function cmplz_script_add() {
        var btn = $(this);
        var btn_html = btn.html();
        var type = btn.data('type');
        btn.html('<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');

        $.ajax({
            type: "POST",
            url: complianz_admin.admin_url,
            data: ({
                action: 'cmplz_script_add',
                type: type,
            }),
            success: function (response) {
                if (response.success) {
                    btn.before(response.html);
                    btn.html(btn_html);
                }
            }
        });
    }

	/**
	 * Add URL
	 *
	 */
	$(document).on("click", '.cmplz_add_url', function(){
		let container = $(this).closest('div');
		let templ = $('.cmplz-url-template').get(0).innerHTML;
		container.append(templ);
	});
	$(document).on("click", '.cmplz_remove_url', function(){
		let container = $(this).closest('div');
		container.remove();
	});
    /**
     * add script
     * */
    $(document).on('click', '.cmplz_script_save', cmplz_script_save );
    function cmplz_script_save() {
        var btn = $(this);
        var btn_html = btn.html();

		var container = btn.closest('.cmplz-panel');
		var type = btn.data('type');
		var action = btn.data('action');
		var id = btn.data('id');
        if ( action == "save" || action == "remove" ) {
			btn.html('<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');
		}

        // Values
        var data = {};
        container.find(':input').each(function () {
            if ($(this).attr('type') === 'button') return;
            if ( typeof $(this).attr('name') === 'undefined') return;
			if (!$(this).data('name')) return;
            if ($(this).attr('type')==='checkbox' ) {
                data[$(this).data('name')] = $(this).is(":checked");
            } else if ( $(this).attr('type')==='radio' ) {
				if ($(this).is(":checked")) {
					data[$(this).data('name')] = $(this).val();
				}
			} else if ($(this).data('name')==='urls'){
				let curValue = data[$(this).data('name')];
				if (typeof curValue === 'undefined' ) curValue = [];
				curValue.push($(this).val());
				data[$(this).data('name')] = curValue;
			} else if ($(this).data('name')==='dependency'){
				//key value arrays with string keys aren't stringified to json.
				let curValue = data[$(this).data('name')];
				if (typeof curValue === 'undefined' ) curValue = [];
				curValue.push($(this).data('url')+'|:|'+$(this).val());
				data[$(this).data('name')] = curValue;
			} else {
                data[$(this).data('name')] = $(this).val();
            }
        });
		$.ajax({
            type: "POST",
            url: complianz_admin.admin_url,
            data: ({
                action: 'cmplz_script_save',
                'cmplz-save': true,
                type: type,
                button_action: action,
                id: id,
                data: JSON.stringify(data),
            }),
            success: function (response) {
                if (response.success) {
                    if ( action === 'save' ) {
                        btn.html(btn_html);
                    }
                    if ( action === 'remove' ) {
                        container.remove();
                        btn.html(btn_html);
                    }
                }
            }
        });
    }

    /**
    * Check for anonymous window, adblocker
    *
    * */

    function cmplz_check_cookie_blocking_services() {
        if ($('#cmplz_anonymous_window_warning').length) {
            var fs = window.RequestFileSystem || window.webkitRequestFileSystem;
            if (!fs) {
                return;
            }
            fs(window.TEMPORARY, 100, function (fs) {
            }, function (err) {
                $('#cmplz_anonymous_window_warning').show();
            });
        }

        if ($('#cmplz_adblock_warning').length) {
            if (window.canRunAds === undefined) {
                // adblocker detected, show fallback
                $("#cmplz_adblock_warning").show();
            }
        }
    }
    cmplz_check_cookie_blocking_services();


	/**
	 * hide and show custom url
	 */
	$(document).on('change', '.cmplz-document-input', function(){
		cmplz_update_document_field();
	});

	function cmplz_update_document_field(){
		if ($('.cmplz-document-field').length){
			$('.cmplz-document-field').each(function(){
				var fieldname = $(this).data('fieldname');
				var value = $('input[name='+fieldname+']:checked').val();
				var urlField = $(this).find('.cmplz-document-custom-url');
				var pageField = $(this).find('.cmplz-document-custom-page');

				if (value==='custom'){
					pageField.show();
					pageField.prop('required', true);
				} else {
					pageField.hide();
					pageField.prop('required', false);
				}

				if (value==='url'){
					urlField.show();
					urlField.prop('required', true);
				} else {
					urlField.hide();
					urlField.prop('required', false);
				}



			});
		}
	}

	/**
	 * Create missing pages
	 */
	$(document).on('click', '#cmplz-create_pages', function(){
		//init loader anim
		var btn = $('#cmplz-create_pages');
		btn.attr('disabled', 'disabled');
		var oldBtnHtml = btn.html();
		btn.html('<div class="cmplz-loader "><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');

		//get all page titles from the page
		var pageTitles = {};
		$('.cmplz-create-page-title').each(function(){
			if (pageTitles.hasOwnProperty($(this).data('region'))){
				region = pageTitles[$(this).data('region')];
			} else {
				var region = {};
			}
			region[$(this).attr('name')] = $(this).val();
			pageTitles[$(this).data('region')] = region;
		});

		$.ajax({
			type: "POST",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				pages: JSON.stringify(pageTitles),
				action: 'cmplz_create_pages'
			}),
			success: function (response) {
				if (response.success) {
					$('.cmplz-panel.cmplz-notification.cmplz-success.cmplz-hidden').removeClass('cmplz-hidden');
					$('.cmplz-create-page-title').each(function(){
						$(this).removeClass('cmplz-deleted-page').addClass('cmplz-valid-page');
						$(this).parent().find('.cmplz-icon').replaceWith(response.icon);
					});


					btn.html(response.new_button_text);
					btn.removeAttr('disabled');
				} else {
					btn.html(oldBtnHtml);

					$('.cmplz-page-created').removeClass('fa-times').addClass('fa-check');
					$('.cmplz-create-page-title').removeClass('cmplz-deleted-page');
				}
			}
		});
	});


    $(document).on('change', '.cmplz-region-select', function() {
        var _href = $('.cmplz-document-button').attr("href").slice(0,-2);
        $('.cmplz-document-button').attr('href', _href + $(this).val());
    });


	/**
	 * Start export to csv of records of consent
	 */

	var roc_progress = 0;
	var btn = $('.cmplz_export_roc_to_csv');
	$(document).on('click', '.cmplz_export_roc_to_csv', function(e){
		e.preventDefault();
		btn.html(roc_progress+' %');
		btn.prop('disabled', true);
		cmplzExportBatch();
	} );

	function cmplzExportBatch(){
		var btn = $('.cmplz_export_roc_to_csv');
		$.ajax({
			type: "GET",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				action: 'cmplz_export_roc_to_csv',
				order: cmplzGetUrlParameter('order'),
				cmplz_month_select: cmplzGetUrlParameter('cmplz_month_select'),
				cmplz_year_select: cmplzGetUrlParameter('cmplz_year_select'),
				orderby: cmplzGetUrlParameter('orderby'),
				s: cmplzGetUrlParameter('s'),
			}),
			success: function (response) {
				if ( response.success ) {
					if ( response.progress<100 ) {
						roc_progress = response.progress;
						btn.html(roc_progress+' %');
						cmplzExportBatch();
					} else {
						cmplzLoadDownloadBtn(response.link, roc_progress);
					}
				}

			}
		});
	}

	/**
	 * A slightly unnecessary function which shows a nicely increasing percentage
	 * If the download is ready in one go, the button would otherwise show 0%, then "download".
	 * @param link
	 * @param roc_progress
	 */
	function cmplzLoadDownloadBtn(link, roc_progress ) {
		setTimeout(function() {
			roc_progress = roc_progress+10;
			if (roc_progress < 100) {
				btn.html(roc_progress+' %');

				cmplzLoadDownloadBtn(link, roc_progress);
			} else {
				btn.replaceWith(link);
			}
		}, 100)
	}

	function cmplzGetUrlParameter(sParam) {
		var sPageURL = window.location.href;
		var queryString = sPageURL.split('?');
		if (queryString.length == 1) return false;

		var sURLVariables = queryString[1].split('&'),
			sParameterName,
			i;
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? '' : decodeURIComponent(sParameterName[1]);
			}
		}
		return false;
	}

    $(document).on('change', '.cmplz-region-select', function() {
        var _href = $('.cmplz-document-button').attr("href").slice(0,-2);
        $('.cmplz-document-button').attr('href', _href + $(this).val());
	});

    $(document).on('click', '.upload_button', function (e) {
        e.preventDefault();
        $('input[type=file]').click();
    });

    $(document).on('change', ':input[name="cmplz-upload-file"]', function () {
        $('.cmplz-file-chosen').text( $(this).val().split('\\').pop() );

    });

	/**
	 * Image uploader
	 */

	$(document).on( 'click','.cmplz-image-uploader, .cmplz-logo-preview.cmplz-clickable', function()
	{
		var btn = $(this);
		var container = btn.closest('.cmplz-field');
		var fieldname = btn.closest('.field-group').data('fieldname');
		var media_uploader = wp.media({
			frame:    "post",
			state:    "insert",
			multiple: false
		});

		media_uploader.on("insert", function(){
			var length = media_uploader.state().get("selection").length;
			var images = media_uploader.state().get("selection").models;

			for(var iii = 0; iii < length; iii++)
			{
				var thumbnail_id = images[iii].id;
				var image = false;
				image = images[iii].attributes.sizes['cmplz_banner_image'];
				if (!image) {
					image = images[iii].attributes.sizes['medium'];
				}
				if (!image) {
					image = images[iii].attributes.sizes['thumbnail'];
				}
				if (!image) {
					image = images[iii].attributes.sizes['full'];
				}

				if ( image ) {
					var image_url = image['url'];
					container.find('.cmplz-logo-preview img').attr('src',image_url);
					$('input[name=cmplz_'+fieldname+']').val(thumbnail_id);
					$('.cmplz-cookiebanner .cmplz-logo').html('<img>');
					$('.cmplz-cookiebanner .cmplz-logo img').attr('src',image_url);
				}

			}
		});
		media_uploader.open();
	});

});
