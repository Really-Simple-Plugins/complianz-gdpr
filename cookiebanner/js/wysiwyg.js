'use strict';
jQuery(document).ready(function ($) {
	let reset_button = document.querySelector('.reset_cookie_banner');
	let reset_button_html = reset_button.innerHTML;
	var bannerVisible = true;
	var cssGenerationActive = false;
	var processingReset = false;
	var cssIndex = 0;
	var bannerInitialized = false;
	var consenttype = $('select[name=cmplz_consenttype]').val();
	var typingTimer;
	var doneTypingInterval = 1000;
	var hideBanner = $('input[name=cmplz_hide_preview]').is(':checked') || $('input[name=cmplz_disable_cookiebanner]').is(':checked');
	var banner_id = $('input[name=cmplz_banner_id]').val();
	var manageConsent = $('#cmplz-manage-consent .cmplz-manage-consent.manage-consent-'+banner_id);
	cmplz_apply_style();
	cmplzUpdateLinks();
	/**
	 * Make sure the banner is loaded after the css has loaded, but only once.
	 */
	$(document).on("cmplzCssLoaded", cmplzAfterCssLoaded);
	function cmplzAfterCssLoaded(){
		if ( !bannerInitialized ) {
			bannerInitialized = true;
			//TCF compatibility
			var event = new CustomEvent('wp_consent_type_defined');
			document.dispatchEvent( event );
			var event = new CustomEvent('cmplz_cookie_warning_loaded', {detail: complianz.region});
			document.dispatchEvent(event);

			if ( hideBanner ) {
				return;
			}
			cmplzShowBanner();
			cmplz_validate_banner_width();
		}
	}



	/**
	 * The function which handles the localstorage, always triggers a change event on the consenttype field.
	 */

	$(document).on('change', 'select[name=cmplz_consenttype]', function () {
		var newConsentType = $('select[name=cmplz_consenttype]').val();
		if ( consenttype !== newConsentType ) {
			$('.cmplz-cookiebanner.'+consenttype).addClass('cmplz-hidden');
			if (!hideBanner) {
				$('.cmplz-cookiebanner.'+newConsentType).removeClass('cmplz-hidden');
				$('.cmplz-cookiebanner.'+newConsentType).addClass('cmplz-show');
			}
			consenttype = newConsentType;
			//make sure the categories are hidden again
			$('.cmplz-categories').removeClass('cmplz-fade-in');
			cmplz_apply_style();
		}
	});

	function cmplzUpdateLinks(){
		var region;
		var pageLinks;
		$('.cmplz-cookiebanner').each(function(){
			if ($(this).hasClass('optout')) {
				region = complianz['regions']['optout'];
				pageLinks = complianz.page_links[region];
			} else {
				region = complianz['regions']['optin'];
				pageLinks = complianz.page_links[region];
			}
			$(this).find('.cmplz-links a:not(.cmplz-external), .cmplz-buttons a:not(.cmplz-external)').each( function(){
				var docElement = $(this);
				docElement.addClass('cmplz-hidden');
				for (var pageType in pageLinks) {
					if ( docElement.hasClass(pageType) ){

						docElement.attr('href', pageLinks[pageType]['url']+docElement.data('relative_url') );
						if ( docElement.html() === '{title}') {
							docElement.html(pageLinks[pageType]['title']);
						}
						docElement.removeClass('cmplz-hidden');
					}
				}
			});
		});
	}

	/**
	 * We want to apply the current settings, then recalculate the banner width, then apply the settings again.
	 */

	function cmplz_validate_banner_width(){
		cmplz_apply_style(cmplz_validate_banner_width_after);
	}

	function cmplz_validate_banner_width_after(){
		if ($('select[name=cmplz_position]').val() === 'bottom' ) {
			return;
		}

		if ($('#cmplz-tcf-js').length ) {
			return;
		}

		if ( $('input[name="cmplz_disable_width_correction"]').is(':checked') ) {
			return;
		}

		//check if cats width is ok
		let cats_width = document.querySelector('.cmplz-categories').offsetWidth;
		let message_width = document.querySelector('.cmplz-message').offsetWidth;
		let banner_width = document.querySelector('.cmplz-cookiebanner').offsetWidth;
		let max_banner_change = banner_width * 1.3;
		let new_width_cats = 0;
		let new_width_btns = 0;
		let banner_padding= false;
		let padding_left = window.getComputedStyle(document.querySelector('.cmplz-cookiebanner'), null).getPropertyValue('padding-left');
		let padding_right = window.getComputedStyle(document.querySelector('.cmplz-cookiebanner'), null).getPropertyValue('padding-left');

		//check if the banner padding is in px, and if so get it as int
		if (padding_left.indexOf('px')!=-1 && padding_right.indexOf('px')!=-1){
			banner_padding = parseInt(padding_left.replace('px', '')) + parseInt(padding_right.replace('px', ''));
		}

		if ( cats_width>0 && banner_padding ){
			if ( banner_width-banner_padding > cats_width ) {
				let difference = banner_width-42 - cats_width;
				new_width_cats =  parseInt(banner_width) + parseInt(difference);
			}
		}

		let btn_width = 0;
		btn_width = document.querySelectorAll('.cmplz-buttons .cmplz-btn').offsetWidth;
		if (btn_width > message_width) {
			let difference = btn_width - 42 - message_width;
			new_width_btns = parseInt(btn_width) + parseInt(difference);
		}

		let new_width = 0;
		if (new_width_btns > new_width_cats ) {
			new_width = new_width_btns;
		} else {
			new_width = new_width_cats;
		}

		if ( new_width > banner_width && new_width < max_banner_change ) {

			if(new_width % 2 != 0) new_width++;
			document.querySelector('input[name=cmplz_banner_width]').value = new_width;
		}

		cmplz_apply_style();
	}

	/**
	 * apply the banner styles
	 * @param callback
	 */
	function cmplz_apply_style(callback){
		if (processingReset || cssGenerationActive) {
			return;
		}
		cssGenerationActive = true;
		$('.cmplz-cookiebanner').addClass('reloading');

		$.ajax({
			type: 'POST',
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				id: banner_id,
				formData: $('#cmplz-form').serialize(),
				action: 'cmplz_generate_preview_css',
			}),
			success: function (response) {
				$('.cmplz-cookiebanner').removeClass('reloading');

				if (response.success) {

					var link = document.createElement("link");
					var css_file = complianz.css_file;
					css_file = css_file+Math.random();
					css_file = css_file.replace('banner-', 'banner-preview-');
					if (banner_id==='') banner_id = 'new';

					$('.cmplz-cookiebanner').each(function(){
						$(this).removeClass('cmplz-center');
						$(this).removeClass('cmplz-bottom');
						$(this).removeClass('cmplz-bottom-left');
						$(this).removeClass('cmplz-bottom-right');
						$(this).addClass('cmplz-'+$('select[name=cmplz_position]').val());
						if ($('#cmplz-tcf-js').length ) {
							$(this).addClass('tcf');
						}

						$(this).removeClass('cmplz-categories-type-no');
						$(this).removeClass('cmplz-categories-type-view-preferences');
						$(this).removeClass('cmplz-categories-type-save-preferences');
						$(this).addClass('cmplz-categories-type-'+$('select[name=cmplz_use_categories]').val());
					});

					css_file = css_file.replace('{type}', consenttype ).replace('{banner_id}', banner_id);
					link.href = css_file;
					link.type = "text/css";
					link.rel = "stylesheet";
					var newCssIndex = cssIndex+1;
					link.classList.add('cmplz-banner-css-'+newCssIndex);
					document.getElementsByTagName("head")[0].appendChild(link);
					link.onload = function () {
						//remove old css
						$('.cmplz-banner-css-'+cssIndex).remove();

						var event = new CustomEvent('cmplzCssLoaded');
						document.dispatchEvent(event);
						cssIndex++;
						if (typeof callback == "function") callback();
					}
					reset_button.disabled = false;
					reset_button.innerHTML = reset_button_html;
				}
				cssGenerationActive = false;
			}
		});
	}

	$(document).on('change',
		'input[name=cmplz_hide_preview], ' +
		'input[name=cmplz_disable_cookiebanner]'
		, function () {
		if ( $(this).is(':checked') ) {
			hideBanner = true;
			cmplzHideBanner();
		} else {
			hideBanner = false;
			//check for TCF integration
			//we need to reload if it exists
			if ($('#cmplz-tcf-js').length) {
				$('#cmplz-form').submit();
			} else {
				cmplzShowBanner();
			}
		}
	});

	window.cmplz_set_cookie = function(name, value) {

	}

	$(document).on('change',
		'input[name=cmplz_soft_cookiewall]', function () {
		if ( $(this).is(':checked') ) {
			$('#cmplz-cookiebanner-container').addClass('cmplz-soft-cookiewall');
			$.when(cmplz_apply_style()).done(
				setTimeout(function(){
					$('#cmplz-cookiebanner-container').removeClass('cmplz-soft-cookiewall');
				}, 3000)
			);
		}
	});

	$(document).on('keyup', "input[name='cmplz_header[text]']", function () {
		$(".cmplz-header .cmplz-title").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	})

	$(document).on('keyup', "input[name='cmplz_accept']", function () {
		$(".optin button.cmplz-accept").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});
	$(document).on('keyup', "input[name='cmplz_accept_informational[text]']", function () {
		$(".optout button.cmplz-accept").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});
	$(document).on('keyup', "input[name='cmplz_dismiss[text]']", function () {
		$("button.cmplz-deny").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});
	$(document).on('keyup', "input[name='cmplz_revoke[text]']", function () {
		$("button.cmplz-manage-consent").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_view_preferences']", function () {
		$("button.cmplz-view-preferences").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});
	$(document).on('keyup', "input[name='cmplz_save_preferences']", function () {
		$("button.cmplz-save-preferences").html($(this).val());
		clearTimeout(typingTimer);
		typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});
	$(document).on('keyup', "input[name='cmplz_category_functional']", function () {
		$(".cmplz-functional .cmplz-category-title").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_category_stats[text]']", function () {
		$(".cmplz-statistics .cmplz-category-title").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_category_prefs[text]']", function () {
		$(".cmplz-preferences .cmplz-category-title").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_category_all[text]']", function () {
		$(".cmplz-marketing .cmplz-category-title").html($(this).val());
	});

	$(document).on('keyup', "input[name='cmplz_functional_text[text]']", function () {
		$(".cmplz-functional .cmplz-description-functional").html($(this).val());
	});

	$(document).on('keyup', "input[name='cmplz_preferences_text[text]']", function () {
		$(".cmplz-preferences .cmplz-description-preferences").html($(this).val());
	});

	$(document).on('keyup', "input[name='cmplz_statistics_text[text]']", function () {
		$(".cmplz-statistics .cmplz-description-statistics").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_statistics_text_anonymous[text]']", function () {
		$(".cmplz-statistics .cmplz-description-statistics-anonymous").html($(this).val());
	});
	$(document).on('keyup', "input[name='cmplz_marketing_text[text]']", function () {
		$(".cmplz-marketing .cmplz-description-marketing").html($(this).val());
	});
	$(document).on('keyup', "textarea.wp-editor-area", function () {
		$(".cmplz-message").html($(this).val());
	});

	//on keydown, clear the countdown
	$('input[type=text], input[type=number]').on('keydown', function () {
		clearTimeout(typingTimer);
	});

	$(document).on('keyup',
		'input[name=cmplz_banner_width], ' +
		'#cmplz_custom_csseditor,' +
		'input[type=number].cmplz-border-width, ' +
		'input[type=number].cmplz-border-radius'
		, function () {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(cmplz_validate_banner_width, doneTypingInterval);
	});

	$(document).on('change',
		'select[name=cmplz_position], ' +
		'select[name=cmplz_checkbox_style], ' +
		'input[name=cmplz_close_button], ' +
		'input[name=cmplz_legal_documents], ' +
		'input[name=cmplz_font_size], ' +
		'input[name="cmplz_header[show]"], ' +
		'input[name="cmplz_dismiss[show]"], ' +
		'input[name="cmplz_accept_informational[show]"], ' +
		'input[name="cmplz_functional_text[show]"], ' +
		'input[name="cmplz_statistics_text[show]"], ' +
		'input[name="cmplz_statistics_text_anonymous[show]"], ' +
		'input[name="cmplz_preferences_text[show]"], ' +
		'input[name="cmplz_marketing_text[show]"], ' +
		'input[name="cmplz_category_stats[show]"], ' +
		'input[name="cmplz_category_prefs[show]"], ' +
		'input[name="cmplz_category_all[show]"], ' +
		'input[name=cmplz_use_box_shadow], ' +
		'input[name=cmplz_header_footer_shadow], ' +
		'input[name=cmplz_use_custom_cookie_css], ' +
		'select[name=cmplz_use_categories], ' +
		'select[name=cmplz_animation], ' +
		'input[name="cmplz_revoke[show]"]'
		, function () {
			cmplz_apply_style();
	});

	/**
	 * Number fields: as the user can very quickly click the up and down arrows,
	 * we need to add a delay to prevent issues
	 */
	$(document).on('change',
		'input[name=cmplz_banner_width], ' +
		'input[type=number].cmplz-border-radius,' +
		'input[type=number].cmplz-border-width'
		, delay(function (e) {
				cmplz_apply_style();
		}, 500));

	function delay(callback, ms) {
		var timer = 0;
		return function() {
			var context = this, args = arguments;
			clearTimeout(timer);
			timer = setTimeout(function () {
				callback.apply(context, args);
			}, ms || 0);
		};
	}

	/**
	 * change link on theme logo
	 */

	var customizer_url = $('input[name=cmplz-customizer-url]').val();
	$('.cmplz-logo-preview.cmplz-theme-image a').attr('href', customizer_url);

	$(document).on('change',
		'select[name=cmplz_use_logo]'
		, function () {
			var logo_type = $('select[name=cmplz_use_logo]').val();
			if (logo_type!=='hide') {
				var new_logo = complianz.logo_options[logo_type];
				$('.cmplz-cookiebanner .cmplz-logo').html(new_logo);
			}
			cmplz_apply_style();
		});

	$('.cmplz-color-picker').wpColorPicker({
		change:
			function (event, ui) {
				var container_id = $(event.target).data('hidden-input');
				$('#' + container_id).val(ui.color.toString());
				cmplz_apply_style();
			}
		}
	);


	function cmplz_set_disabled(callback){
		reset_button.setAttribute("disabled", "disabled");
		reset_button.innerHTML = '<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';

		// var observer = new MutationObserver(function(mutations) {
		// 	mutations.forEach(function(mutation) {
		// 		if (mutation.type == "attributes") {
		// 			callback()
		// 		}
		// 	});
		// });
		// observer.observe(reset_button, {
		// 	attributes: true //configure it to listen to attribute changes
		// });
		callback();
	}


	$(document).on( 'click', '.reset_cookie_banner', function(){
		if (processingReset) return;
		processingReset = true;
		reset_button.setAttribute("disabled", "disabled");
		reset_button.innerHTML = '<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';

		//cmplz_set_disabled(function(){
			cmplz_load_defaults(function(){
				cmplzUpdatePreviewFields( function(){
					processingReset = false;
					cmplz_apply_style();
				});
			});
		// });
	});

	function cmplz_load_defaults(callback){
		var defaults = complianz.defaults;
		for (var default_field in defaults) {
			var fieldGroup = $(".field-group[data-fieldname="+default_field+"]");
			if (fieldGroup.hasClass('cmplz-colorpicker') ) {
				if (defaults[default_field].hasOwnProperty("color")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_color]').wpColorPicker('color', defaults[default_field]['color']);
					var inputField = $("input[name='cmplz_" + default_field + "[color]']");
					inputField.val(defaults[default_field]['color']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['color']);
				}
				if (defaults[default_field].hasOwnProperty("background")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_background]').wpColorPicker('color', defaults[default_field]['background']);
					var inputField = $("input[name='cmplz_" + default_field + "[background]']");
					inputField.val(defaults[default_field]['background']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['background']);
				}
				if (defaults[default_field].hasOwnProperty("border")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_border]').wpColorPicker('color', defaults[default_field]['border']);
					var inputField = $("input[name='cmplz_" + default_field + "[border]']");
					inputField.val(defaults[default_field]['border']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['border']);
				}
				if (defaults[default_field].hasOwnProperty("text")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_text]').wpColorPicker('color', defaults[default_field]['text']);
					var inputField = $("input[name='cmplz_" + default_field + "[text]']");
					inputField.val(defaults[default_field]['text']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['text']);
				}
				if (defaults[default_field].hasOwnProperty("bullet")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_bullet]').wpColorPicker('color', defaults[default_field]['bullet']);
					var inputField = $("input[name='cmplz_" + default_field + "[bullet]']");
					inputField.val(defaults[default_field]['bullet']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['bullet']);
				}
				if (defaults[default_field].hasOwnProperty("inactive")) {
					fieldGroup.find('.cmplz-color-picker data[hidden-input=cmplz_'+default_field+'_inactive]').wpColorPicker('color', defaults[default_field]['inactive']);
					var inputField = $("input[name='cmplz_" + default_field + "[inactive]']");
					inputField.val(defaults[default_field]['inactive']);
					inputField.closest('.cmplz-color-picker-wrap').find('.wp-color-result').css('background-color', defaults[default_field]['inactive']);
				}
			} else if (fieldGroup.hasClass('cmplz-text_checkbox') ) {
				$(".field-group[data-fieldname="+default_field+'] input[type=text]').val( defaults[default_field]['text'] );
				$(".field-group[data-fieldname="+default_field+'] input[type=checkbox]').prop('checked', defaults[default_field]['show'] );
			} else if ( fieldGroup.hasClass('cmplz-text') ) {
				$(".field-group[data-fieldname="+default_field+'] input').val( defaults[default_field] );
			} else if (fieldGroup.hasClass('cmplz-borderradius') || fieldGroup.hasClass('cmplz-borderwidth')) {
				$("input[name='cmplz_"+default_field+"[top]']").val(defaults[default_field]['top']);
				$("input[name='cmplz_"+default_field+"[right]']").val(defaults[default_field]['right']);
				$("input[name='cmplz_"+default_field+"[bottom]']").val(defaults[default_field]['bottom']);
				$("input[name='cmplz_"+default_field+"[left]']").val(defaults[default_field]['left']);
				if (defaults[default_field].hasOwnProperty("type")) {
					$("input[name='cmplz_"+default_field+"[type]']").val(defaults[default_field]['type']);
				}
			} else if (fieldGroup.hasClass('cmplz-number')){
				$(".field-group[data-fieldname="+default_field+'] input').val( defaults[default_field] );
			} else if (fieldGroup.hasClass('cmplz-checkbox')) {
				var checked = defaults[default_field] ? 'checked' : '';
				$(".field-group[data-fieldname="+default_field+'] input').prop('checked', checked);
			} else if (fieldGroup.hasClass('cmplz-select')){
				if (default_field!=='position') {
					$(".field-group[data-fieldname="+default_field+'] select').val( defaults[default_field] );
				}
			} else if (fieldGroup.hasClass('cmplz-editor')){
				var editor_id = 'cmplz_message_' + consenttype;
				var textarea_id = 'cmplz_message_' + consenttype;
				$(".cmplz-message").html(defaults[default_field] );
				if ($('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
					tinyMCE.get(editor_id).setContent(defaults[default_field]);
				} else {
					$('#' + textarea_id).val(defaults[default_field]);
				}
			}
			$('select[name=cmplz_use_logo]').trigger('change');
		}
		callback();
	}

	/**
	 * TinyMCE Editor
	 */

	setTimeout(function () {
		if (typeof tinymce !== 'undefined' ) {
			for (var i = 0; i < tinymce.editors.length; i++) {
				tinymce.editors[i].on('NodeChange keyup', function (ed, e) {
					var content;
					var editor_id = 'cmplz_message_' + consenttype;
					var textarea_id = 'cmplz_message_' + consenttype;

					if ($('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
						content = tinyMCE.get(editor_id).getContent();
					} else {
						content = $('#' + textarea_id).val();
					}
					content = content.replace(/<[\/]{0,1}(p)[^><]*>/ig, "");
					$(".cmplz-message").html(content );
					// Update HTML view textarea (that is the one used to send the data to server).
				});
			}
		}

	}, 1500);


	/**
	 * Banner controls
	 */

	$(document).on('click', '.cmplz-close, .cmplz-accept, .cmplz-deny, .cmplz-save-preferences', function(){
		cmplzHideBanner();
	});

	$(document).on('click', '.cmplz-manage-consent', function(){
		cmplzShowBanner();
	});

	$(document).on('click', '.cmplz-view-preferences', function(){
		var banner = $(this).closest('.cmplz-cookiebanner');
		if ( $('.cmplz-categories').hasClass('cmplz-fade-in')) {
			banner.remove('cmplz-categories-visible');
			banner.find('.cmplz-categories').removeClass('cmplz-fade-in');
			banner.find('.cmplz-view-preferences').show();
			banner.find('.cmplz-save-preferences').hide();
		} else {
			banner.addClass('cmplz-categories-visible');
			banner.find('.cmplz-categories').addClass('cmplz-fade-in');
			banner.find('.cmplz-view-preferences').hide();
			banner.find('.cmplz-save-preferences').show();
		}
	});

	function cmplzHideBanner(){
		$('.cmplz-cookiebanner.' + consenttype).addClass('cmplz-hidden');
		$('.cmplz-cookiebanner.' + consenttype).removeClass('cmplz-show');
		manageConsent.removeClass('cmplz-hidden');
		bannerVisible = false;
	}

	function cmplzShowBanner(){
		$('.cmplz-cookiebanner.' + consenttype).removeClass('cmplz-hidden');
		$('.cmplz-cookiebanner.' + consenttype).addClass('cmplz-show');
		manageConsent.addClass('cmplz-hidden');
		bannerVisible = true;
	}

	$(document).on('click', '.cmplz-border-input-type-pixel', function() {
		var hidden_field = $(this).closest('.cmplz-border-input-type-wrap');
		hidden_field.find('.cmplz-border-input-type').val('px');
		hidden_field.find('.cmplz-border-input-type-percent').addClass('cmplz-grey');
		$(this).removeClass('cmplz-grey');
		cmplz_apply_style();
	});

	$(document).on('click', '.cmplz-border-input-type-percent', function() {
		var hidden_field = $(this).closest('.cmplz-border-input-type-wrap');
		hidden_field.find('.cmplz-border-input-type').val('%');
		hidden_field.find('.cmplz-border-input-type-pixel').addClass('cmplz-grey');
		$(this).removeClass('cmplz-grey');
		cmplz_apply_style();
	});

	function cmplzUpdatePreviewFields(callback){
		$(".cmplz-header .cmplz-title").html($("input[name='cmplz_header[text]']").val());
		$(".cmplz-manage-consent").html($("input[name='cmplz_revoke[text]']").val());
		$(".optin button.cmplz-accept").html($("input[name='cmplz_accept']").val());
		$(".optout button.cmplz-accept").html($("input[name='cmplz_accept_informational[text]']").val());
		$("button.cmplz-deny").html($("input[name='cmplz_dismiss[text]']").val());
		$("button.cmplz-view-preferences").html($("input[name='cmplz_view_preferences']").val());
		$(".cmplz-functional .cmplz-category-title").html($("input[name='cmplz_category_functional']").val());
		$(".cmplz-statistics .cmplz-category-title").html($("input[name='cmplz_category_stats[text]']").val());
		$(".cmplz-preferences .cmplz-category-title").html($("input[name='cmplz_category_prefs[text]']").val());
		$(".cmplz-marketing .cmplz-category-title").html($("input[name='cmplz_category_all[text]']").val());
		$(".cmplz-functional .cmplz-description-functional").html($("input[name='cmplz_functional_text[text]']").val());
		$(".cmplz-preferences .cmplz-description-preferences").html($("input[name='cmplz_preferences_text[text]']").val());
		$(".cmplz-statistics .cmplz-description-statistics").html($("input[name='cmplz_statistics_text[text]']").val());
		$(".cmplz-statistics .cmplz-description-statistics-anonymous").html($("input[name='cmplz_statistics_text_anonymous[text]']").val());
		$(".cmplz-marketing .cmplz-description").html($("input[name='cmplz_marketing_text[text]']").val());
		callback();
	}

	/**
	 * Helper functions
	 */

	/**
	 * Check if needle occurs in the haystack
	 * @param needle
	 * @param haystack
	 * @returns {boolean}
	 */
	window.cmplz_in_array = function(needle, haystack) {
		var length = haystack.length;
		for(var i = 0; i < length; i++) {
			if(haystack[i] == needle) return true;
		}
		return false;
	}

	/**
	 * Get a cookie by name. Used for the TCF banner
	 * @param name
	 * @returns {string}
	 */

	window.cmplz_get_cookie = function(name) {
		if (typeof document === 'undefined') {
			return '';
		}
		name = complianz.prefix+name + "=";
		var cArr = document.cookie.split(';');
		for (var i = 0; i < cArr.length; i++) {
			var c = cArr[i].trim();
			if (c.indexOf(name) == 0)
				return c.substring(name.length, c.length);
		}

		return "";
	}

});


