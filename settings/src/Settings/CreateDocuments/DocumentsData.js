import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import produce from "immer";

export const UseDocumentsData = create(( set, get ) => ({
	documentsDataLoaded: false,
	documentsDataLoading: false,
	saving: false,
	hasMissingPages: false,
	requiredPages: [],
	documentsChanged:false,
	fetchDocumentsData: async () => {
		if ( get().documentsDataLoading )  {
			return;
		}

		set({documentsDataLoading:true});
		const response = await fetchDocumentsData(false);
		let hasMissingPages = false;
		let requiredPages = response.required_pages;
		requiredPages.forEach(function(page, i) {
			if (!page.page_id) {
				hasMissingPages = true;
			}
		});

		set({
			documentsDataLoaded:true,
			hasMissingPages: hasMissingPages,
			requiredPages:requiredPages,
			documentsDataLoading:false,
		});
	},
	updateDocument: (page_id, title) => {
		set(
			produce((state) => {
				let index = false;
				state.requiredPages.forEach(function(page, i) {
					if (page.page_id === page_id ){
						index = i;
					}
				});
				if (index!==false) {
					state.requiredPages[index].title = title;
					state.documentsChanged = true;
				}
			})
		)
	},
	saveDocuments: async () => {
		set({saving:true });
		let documentsChanged = get().documentsChanged;
		let hasMissingPages = get().hasMissingPages;
		let requiredPages = get().requiredPages;
		if (documentsChanged || hasMissingPages ){
			let data = {};
			data.documents = requiredPages;
			data.generate = true;
			return cmplz_api.doAction('documents_data', data).then((response) => {
				let hasMissingPages = false;
				let requiredPages = response.required_pages;
				requiredPages.forEach(function(page, i) {
					if (!page.page_id) {
						hasMissingPages = true;
					}
				});

				set({
					documentsDataLoaded:true,
					hasMissingPages: hasMissingPages,
					requiredPages:requiredPages,
					saving:false,
				});
				return true;
			}).catch((error) => {
				console.error(error);
			});
		} else {
			return true;
		}

	},
}));

const fetchDocumentsData = () => {
	let data = {};
	data.generate = false;
	return cmplz_api.doAction('documents_data', data).then((response) => {
		return response;
	}).catch((error) => {
		console.error(error);
	});
}



