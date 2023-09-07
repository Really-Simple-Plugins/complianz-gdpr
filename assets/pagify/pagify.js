jQuery(document).ready(function($) {
	(function () {
		"use strict";
		function Pagination() {
			let records_per_page = 5;

			this.init = function (container_obj) {
				let totalPages;
				let container;
				let current_page = 1;

				let fields;
				container = container_obj;
				fields = container.querySelectorAll('.field-group');
				totalPages = Math.ceil(fields.length / records_per_page);
				if (totalPages<=1) return;

				container.classList.add('cmplz-nav-container');
				container.setAttribute('data-total_pages', totalPages);
				container.setAttribute('data-current_page', current_page);
				let paging_prev_icon = document.createElement('div');
				paging_prev_icon.classList.add('cmplz-tooltip-icon');
				paging_prev_icon.classList.add('dashicons-before');
				paging_prev_icon.classList.add('cmplz-icon');
				paging_prev_icon.classList.add('cmplz-success');
				paging_prev_icon.classList.add('dashicons-arrow-left-alt2');

				let paging_next_icon = document.createElement('div');
				paging_next_icon.classList.add('cmplz-tooltip-icon');
				paging_next_icon.classList.add('dashicons-before');
				paging_next_icon.classList.add('cmplz-icon');
				paging_next_icon.classList.add('cmplz-success');
				paging_next_icon.classList.add('dashicons-arrow-right-alt2');

				let pagination = document.createElement('div');
				pagination.classList.add('cmplz-pagination');
				let start = document.createElement('a');
				start.classList.add('cmplz-nav');
				start.classList.add('cmplz-prev');
				start.classList.add('cmplz-disabled');
				start.setAttribute('next', false);
				start.appendChild(paging_prev_icon);
				pagination.appendChild(start)
				for (var i = 0; i < totalPages; i++) {
					let pageEl = create_link(i);
					pagination.appendChild( pageEl );
				}

				let end = document.createElement('a');
				end.classList.add('cmplz-nav');
				end.classList.add('cmplz-next');
				end.setAttribute('next', true);
				end.appendChild(paging_next_icon);
				pagination.appendChild(end);

				let footer = container.querySelector('.cmplz-footer-contents');
				if (footer) {
					footer.appendChild(pagination);
				}
				show_page(current_page, container);
				container.querySelectorAll('.cmplz-page').forEach(obj => {
					obj.addEventListener('click', function(event){
						let page = obj.getAttribute('data-page');
						show_page(page, obj.closest('.cmplz-nav-container'));
					});
				});

				container.querySelectorAll('.cmplz-prev').forEach(obj => {
					obj.addEventListener('click', function(event){
						current_page = container.getAttribute('data-current_page');
						if (current_page==1) return;
						show_page(parseInt(current_page)-1, obj.closest('.cmplz-nav-container'));
					});
				});
				container.querySelectorAll('.cmplz-next').forEach(obj => {
					obj.addEventListener('click', function(event){
						let total = obj.closest('.cmplz-nav-container').getAttribute('data-total_pages');
						current_page = container.getAttribute('data-current_page');
						if (current_page==total) return;
						show_page(parseInt(current_page)+1, obj.closest('.cmplz-nav-container'));
					});
				});
			}

			function create_link(i){
				let index = parseInt(i)+1;
				let pageEl = document.createElement('a');
				pageEl.innerText = index;
				pageEl.classList.add("cmplz-page");
				if (!i) pageEl.classList.add("cmplz-current");

				pageEl.setAttribute('data-page', index );
				return pageEl;
			}

			function show_page(page, container){
				let current_page = container.getAttribute('data-current_page');
				container.querySelectorAll('[data-page="'+current_page+'"]').forEach(obj => {
					obj.classList.remove('cmplz-current');
				});
				current_page = page;
				container.setAttribute('data-current_page', current_page);

				container.querySelectorAll('.cmplz-page[data-page="'+page+'"]').forEach(obj => {
					obj.classList.add('cmplz-current');
				});

				let lowest_index = (parseInt(page)-1) * records_per_page;
				let highest_index = lowest_index + records_per_page;
				let index = 0;
				container.querySelectorAll('.field-group').forEach(field => {
					if (index>=lowest_index && index<highest_index) {
						field.style.display = 'grid';
					} else {
						field.style.display = 'none';
					}
					index++;
				});
			}
		}

		let pagination = new Pagination();
		document.querySelectorAll('.integrations').forEach(block => {
			pagination.init(block);
		});
	})();
});
