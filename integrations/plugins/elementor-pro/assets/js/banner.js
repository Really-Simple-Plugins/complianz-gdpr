jQuery(document).ready(function ($) {
	//initially hidden with css.
	//after initializing, show banners.
	var countedElements = 0;
	var completed = false;
	var bannerStatus = cmplz_get_cookie('banner-status');
	if ( !bannerStatus ) bannerStatus = 'show';

	elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
		var elementorElCount = document.querySelectorAll('.elementor-element').length;
		countedElements++;
		let manage_consent_button = document.querySelector('.cmplz-manage-consent');
		let banner_button = document.querySelector('.cmplz-accept') || document.querySelector('.cmplz-deny');
		if ( !completed && banner_button && manage_consent_button ) {
			completed = true;
			run_loaded_event();
		}

		//fallback, if the manage consent button does not exist.
		if ( elementorElCount >= countedElements ) {
			setTimeout(function(){
				if (!completed){
					run_loaded_event();
				}
			}, 1000);
		}
	});

	function run_loaded_event(){
		var event = new CustomEvent( 'cmplz_elementor_loaded' );
		document.dispatchEvent(event);
		cmplz_force_visible();
	}
	let banner_id;
	let manage_consent_banner_id;
	document.addEventListener('cmplz_elementor_loaded', function(e){
		document.querySelectorAll('.elementor-location-popup').forEach(obj => {
			if ( obj.querySelector('.cmplz-accept') || obj.querySelector('.cmplz-deny') ) {
				banner_id = obj.getAttribute('data-elementor-id');
				localStorage.setItem('cmplz_elementor_banner_id', banner_id);

				let btn_preferences = obj.querySelector('.cmplz-view-preferences');
				if (btn_preferences) {
					obj.classList.add('view-save-preferences');
				}
				var link = document.createElement("link");
				var pageLinks = complianz.page_links[complianz.region];
				obj.querySelectorAll('.cmplz-links a:not(.cmplz-external), .cmplz-buttons a:not(.cmplz-external, .cmplz-accept, .cmplz-deny, .cmplz-view-preferences, .cmplz-save-preferences)').forEach(obj => {
					var docElement = obj;
					docElement.classList.add('cmplz-hidden');
					for (var pageType in pageLinks) {
						if (docElement.classList.contains(pageType)) {
							let relativeUrl = docElement.getAttribute('data-relative_url') ? docElement.getAttribute('data-relative_url') : '';
							docElement.setAttribute('href', pageLinks[pageType]['url']+relativeUrl );
							if (docElement.innerText === '{title}') {
								docElement.innerText=pageLinks[pageType]['title'];
							}
							docElement.classList.remove('cmplz-hidden');
						}
					}
				});
			}
			/**
			 * Look for manage consent button
			 */
			let manage_consent_button_el = obj.querySelector('.cmplz-manage-consent');
			if ( manage_consent_button_el ) {
				let manage_consent_button = manage_consent_button_el.closest('.dialog-widget-content').classList.add('cmplz-elementor-manage-consent-container');
				manage_consent_banner_id = obj.getAttribute('data-elementor-id');
				localStorage.setItem('cmplz_elementor_manage_consent_banner_id', manage_consent_banner_id);
			}
			if ( !banner_id ) {
				banner_id = localStorage.getItem('cmplz_elementor_banner_id');
			}
			if ( !manage_consent_banner_id ) {
				manage_consent_banner_id = localStorage.getItem('cmplz_elementor_manage_consent_banner_id');
			}

			/**
			 * if necessary, toggle banners
			 */
			if ( bannerStatus === 'show' ) {
				show_popup(banner_id);
				hide_popup(manage_consent_banner_id);
			} else {
				hide_popup(banner_id);
				show_popup(manage_consent_banner_id)
			}
			cmplz_force_visible();
		});
	});

	document.addEventListener("cmplz_revoke", function() {
		bannerStatus = 'show';
		var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
		if ( popup_id ) {
			let st = localStorage.getItem('elementor');
			let data = JSON.parse(st);
			data['popup_'+popup_id+'_disable'] = false;
			st = JSON.stringify(data);
			localStorage.setItem('elementor', st);
		} else {
			localStorage.removeItem('elementor');
		}
	});

	$( document ).on( 'elementor/popup/hide', ( event, id, instance ) => {
		var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
		var manage_consent_popup_id = localStorage.getItem('cmplz_elementor_manage_consent_banner_id');

		if ( id == popup_id ) {
			bannerStatus = 'dismiss';
			cmplz_set_cookie('banner-status', bannerStatus);
			show_popup(manage_consent_popup_id);
		}

		if ( id == manage_consent_popup_id ) {
			bannerStatus = 'show';
			cmplz_set_cookie('banner-status', bannerStatus);
			show_popup(popup_id);
		}
		cmplz_force_visible();
	} );

	$( document ).on( 'elementor/popup/show', ( event, id, instance ) => {
		//not in preview mode
		if ( document.querySelector('.elementor-editor-active')) return;

		if (document.body.classList.contains('cmplz-optin')) {
			var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
			var manage_consent_popup_id = localStorage.getItem('cmplz_elementor_manage_consent_banner_id');
			if ( id == popup_id && $('.elementor-widget-cmplz-category').length ) {
				bannerStatus = 'show';
				cmplz_set_cookie('banner-status', 'show');
				$('.elementor-widget-cmplz-category').hide();
				$('.cmplz-view-preferences').css("display", "inline-block");
				$('.cmplz-save-preferences').hide();
			}
		}
		cmplz_force_visible();
	} );

	$( document ).on('click', '.cmplz-manage-consent', function(e){
		var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
		show_popup(popup_id);
		bannerStatus = 'show';
		cmplz_force_visible();
	});

	$( document ).on('click', '.cmplz-manage-consent .dialog-close-button, .cmplz-cookiebanner .dialog-close-button', function(e){
		e.preventDefault();
		cmplz_set_cookie('banner-status', 'dismiss');

		var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
		var manage_consent_popup_id = localStorage.getItem('cmplz_elementor_manage_consent_banner_id');

		show_popup(manage_consent_popup_id);
		hide_popup(popup_id);
		cmplz_force_visible();
	});
	function is_visible(id){
		return $('#elementor-popup-modal-'+id).is(":visible");
	}

	function hide_popup(id){
		if ( is_visible(id)) {
			const document = elementorFrontend.documentsManager.documents[id];
			document.getModal().hide();
		}
	}

	function show_popup(id){
		if ( !is_visible(id) ) {
			elementorProFrontend.modules.popup.showPopup( {id:id} );
		}
	}

	/**
	 * if a popup is set to dismiss:true, it will not laod in the dom. Therefore we need to force it to dismiss:false, which allows us to manipulate the popup
	 */
	function cmplz_force_visible(){
		let st = localStorage.getItem('elementor');
		let data = JSON.parse(st);

		var popup_id = localStorage.getItem('cmplz_elementor_banner_id');
		if (typeof popup_id !== 'undefined' && popup_id ) {
			data['popup_'+popup_id+'_disable'] = false;
		}

		var manage_consent_popup_id = localStorage.getItem('cmplz_elementor_manage_consent_banner_id');
		if (typeof manage_consent_popup_id !== 'undefined' && popup_id ) {
			data['popup_'+manage_consent_popup_id+'_disable'] = false;
		}

		st = JSON.stringify(data);
		localStorage.setItem('elementor', st);
	}
})



