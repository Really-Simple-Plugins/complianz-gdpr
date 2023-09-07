import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import produce from "immer";
import {toast} from "react-toastify";
import {__} from '@wordpress/i18n';

export const UseMenuData = create(( set, get ) => ({
	menuDataLoaded: false,
	saving: false,
	menu: [],
	menuChanged:false,
	changedMenuType:'per_document',
	emptyMenuLink:'#',
	requiredDocuments:[],
	createdDocuments:[],
	genericDocuments:[],
	documentsNotInMenu:[],
	pageTypes:[],
	regions:[],
	fetchMenuData: async () => {
		const response = await fetchMenuData(false);
		let createdDocuments = response.required_documents.filter( document => document.page_id );

		set({
			menuDataLoaded:true,
			emptyMenuLink: response.empty_menu_link,
			menu: response.menu,
			requiredDocuments: response.required_documents,
			genericDocuments: response.generic_documents_list,
			createdDocuments: createdDocuments,
			pageTypes: response.page_types,
			documentsNotInMenu: response.documents_not_in_menu,
			regions: response.regions,
		});
	},
	updateMenu: (page_id, menu_id) => {
		let menuType = isNaN(page_id) ? 'per_type' : 'per_document';
		set({ menuType:menuType });
		if (menuType==='per_type') {
			set(
				produce((state) => {
					let genIndex = state.genericDocuments.findIndex(function(page, i) {
						return page.page_id === page_id || page.type === page_id;
					});
					let createdIndex = state.createdDocuments.findIndex(function(page, i) {
						return page.page_id === page_id || page.type === page_id;
					});
					if ( genIndex!==-1 ) {
						state.genericDocuments[genIndex].menu_id = menu_id;
						if (createdIndex!==-1) state.createdDocuments[createdIndex].menu_id = -1;
						state.menuChanged = true;
					}

				})
			)
		} else {
			set(
				produce((state) => {
					let genIndex = state.genericDocuments.findIndex(function(page, i) {
						return page.page_id === page_id || page.type === page_id;
					});
					let createdIndex = state.createdDocuments.findIndex(function(page, i) {
						return page.page_id === page_id || page.type === page_id;
					});;
					if ( createdIndex!==-1 ) {
						state.createdDocuments[createdIndex].menu_id = menu_id;
						if (genIndex!==-1) state.genericDocuments[genIndex].menu_id = -1;
						state.menuChanged = true;
					}
				})
			)
		}
	},
	saveDocumentsMenu: async (hasChangedFields, showNotice) => {
		set({saving:true });
		let menuChanged = get().menuChanged;
		if ( menuChanged || hasChangedFields ) {
			let data = {};
			//post for generic documents only the redirected ones.
			data.genericDocuments = get().genericDocuments.filter(document => document.can_region_redirect);
			data.createdDocuments = get().createdDocuments;
			const response = cmplz_api.doAction('save_documents_menu_data', data).then((response) => {
				set({saving:false });
				return response;
			}).catch((error) => {
				console.error(error);
			});
			showNotice && toast.promise(
				response,
				{
					pending: __('Saving menu...', 'complianz-gdpr'),
					success: __('Menu saved', 'complianz-gdpr'),
					error: __('Something went wrong', 'complianz-gdpr'),
				}
			);
		} else {
			showNotice && toast.info(__('Settings have not been changed','complianz-gdpr'));
		}

	},
}));

const fetchMenuData = () => {
	let data = {};
	data.generate = false;
	return cmplz_api.doAction('documents_menu_data', data).then((response) => {
		return response;
	}).catch((error) => {
		console.error(error);
	});
}



