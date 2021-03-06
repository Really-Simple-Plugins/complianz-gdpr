jQuery(document).ready(function($) {
	(function($) {
		var pagify = {
			items: {},
			container: null,
			totalPages: 1,
			perPage: 3,
			currentPage: 0,
			createNavigation: function() {
				//var footer = this.container.closest()
				this.totalPages = Math.ceil(this.items.length / this.perPage);
				$('.cmplz-pagination', this.container.parent()).remove();
				var paging_prev_icon = $('.cmplz-paging-prev-icon').html();
				var paging_next_icon = $('.cmplz-paging-next-icon').html();
				var pagination = $('<div class="cmplz-pagination"></div>').append('<a class="cmplz-nav cmplz-prev cmplz-disabled" data-next="false">'+paging_prev_icon+'</a>');
				for (var i = 0; i < this.totalPages; i++) {
					var pageElClass = "cmplz-page";
					if (!i)
						pageElClass = "cmplz-page cmplz-current";
					var pageEl = '<a class="' + pageElClass + '" data-page="' + (
						i + 1) + '">' + (
						i + 1) + "</a>";
					pagination.append(pageEl);
				}

				pagination.append('<a class="cmplz-nav cmplz-next" data-next="true">'+paging_next_icon+'</a>');

				this.container.find('.cmplz-footer-contents').append(pagination );

				var that = this;
				$("body").off("click", ".cmplz-nav");
				this.navigator = $("body").on("click", ".cmplz-nav", function() {
					var el = $(this);
					that.navigate(el.data("next"));
				});

				$("body").off("click", ".cmplz-page");
				this.pageNavigator = $("body").on("click", ".cmplz-page", function() {
					var el = $(this);
					that.goToPage(el.data("page"));
				});
			},
			navigate: function(next) {
				// default perPage to 5
				if (isNaN(next) || next === undefined) {
					next = true;
				}
				$(".cmplz-pagination .cmplz-nav").removeClass("cmplz-disabled");
				if (next) {
					this.currentPage++;
					if (this.currentPage > (this.totalPages - 1))
						this.currentPage = (this.totalPages - 1);
					if (this.currentPage == (this.totalPages - 1))
						$(".cmplz-pagination .cmplz-nav.cmplz-next").addClass("cmplz-disabled");
				}
				else {
					this.currentPage--;
					if (this.currentPage < 0)
						this.currentPage = 0;
					if (this.currentPage == 0)
						$(".cmplz-pagination .cmplz-nav.cmplz-prev").addClass("cmplz-disabled");
				}
				this.showItems();
			},
			updateNavigation: function() {
				var pages = $(".cmplz-pagination .cmplz-page");
				pages.removeClass("cmplz-current");
				$('.cmplz-pagination .cmplz-page[data-page="' + (
					this.currentPage + 1) + '"]').addClass("cmplz-current");
			},
			goToPage: function(page) {

				this.currentPage = page - 1;

				$(".pagination .nav").removeClass("cmplz-disabled");
				if (this.currentPage == (this.totalPages - 1))
					$(".pagination .nav.next").addClass("cmplz-disabled");

				if (this.currentPage == 0)
					$(".pagination .nav.prev").addClass("cmplz-disabled");
				this.showItems();
			},
			showItems: function() {
				this.items.hide();
				var base = this.perPage * this.currentPage;
				this.items.slice(base, base + this.perPage).show();
				this.updateNavigation();
			},
			init: function(container, items, perPage) {
				this.container = container;
				this.currentPage = 0;
				this.totalPages = 1;
				this.perPage = perPage;
				this.items = items;
				this.createNavigation();
				this.showItems();
			}
		};

		// stuff it all into a jQuery method!
		$.fn.pagify = function(perPage, itemSelector) {
			var el = $(this);
			var items = el.find(itemSelector);
			// default perPage to 5
			if (isNaN(perPage) || perPage === undefined) {
				perPage = 3;
			}

			// don't fire if fewer items than perPage
			if (items.length <= perPage) {
				return true;
			}

			pagify.init(el, items, perPage);
		};
	})(jQuery);

	$(".cmplz-settings-item").each(function(){
		$(this).pagify(6, ".field-group")
	});
});


