jQuery(document).ready(function ($) {
	'use strict';
	var cmplz_loader = '<div class="cmplz-loader"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';


	$(document).on('click', '.cmplz-dismiss-warning', function(){
		var warning_id = $(this).data('warning_id');
		var btn = $(this);
		btn.attr('disabled', 'disabled');
		var task_count = parseInt($('.cmplz-task-switcher-count').html());
		var container = $(this).closest('.cmplz-progress-warning-container');
		container.animate({
			position: 'relative',
			right: '1000px'
		}, 500, function(){
			container.remove();
		});
		$.ajax({
			type: "POST",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				action: 'cmplz_dismiss_warning',
				id: warning_id,
			}),
			success: function (response) {
				btn.removeAttr('disabled');
				if (response.success) {
					// container.remove();
					var remainingContainer = $('.cmplz-task-switcher-count.cmplz-remaining');
					var curValue = parseInt( remainingContainer.html() );
					remainingContainer.html(curValue-1)

					var remainingContainer = $('.cmplz-task-switcher-count.cmplz-all');
					var curValue = parseInt( remainingContainer.html() );
					remainingContainer.html(curValue-1)
				}
			}
		});
	});

	$(document).on('change', '[name=cmplz_selected_region]', function(){
		var data = {};
		data['region'] = $('#cmplz_selected_region').val();
		cmplzLoadGridBlock(data, $(this));
	});

	function cmplzLoadGridBlock(data, obj) {
		var template = obj.closest('.cmplz-grid-container').data('template');
		var container = obj.closest('.cmplz-grid-container').find('.cmplz-grid-content');
		data['action'] = 'cmplz_load_gridblock';
		data['template'] = template;

		container.html('<div class="cmplz-skeleton"></div>' );
		$.ajax({
			type: "GET",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: data,
			success: function (response) {
				if (response.success) {
					container.html(response.html);
				}
			}
		});
	}

	$(document).on('click', '.cmplz-task-switcher', function(){
		var status = 'remaining';
		if ($(this).find('.cmplz-task-switcher-count').hasClass('cmplz-all')) {
			status = 'all';
		}
		if ( $('.cmplz-'+status).closest('.cmplz-task-switcher').hasClass('active')) return;
		var container = $(this).closest('.cmplz-grid-container').find('.cmplz-grid-content');

		//container.html(cmplz_loader );
		if (status === 'all') {
			$('.cmplz-all').closest('.cmplz-task-switcher').addClass('active');
			$('.cmplz-remaining').closest('.cmplz-task-switcher').removeClass('active');
		} else {
			$('.cmplz-all').closest('.cmplz-task-switcher').removeClass('active');
			$('.cmplz-remaining').closest('.cmplz-task-switcher').addClass('active');
		}
		container.html('<div class="cmplz-skeleton"></div>' );
		$.ajax({
			type: "GET",
			url: complianz_admin.admin_url,
			dataType: 'json',
			data: ({
				action: 'cmplz_load_warnings',
				status: status,
			}),
			success: function (response) {
				if (response.success) {
					container.html(response.html);
					//fire this to trigger the scroll plugin
					window.document.dispatchEvent(new Event("DOMContentLoaded", {
						bubbles: true,
						cancelable: true
					}));
				}
			}
		});
	});

	// Color bullet in support forum block
	$(".cmplz-trick a").hover(function() {
		$(this).find('.cmplz-bullet').css("background-color","#009fff");
	}, function() {
		$(this).find('.cmplz-bullet').css("background-color",""); //to remove property set it to ''
	});

});
