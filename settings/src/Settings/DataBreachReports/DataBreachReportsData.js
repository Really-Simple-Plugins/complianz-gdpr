import {create} from 'zustand';
import produce from 'immer';
import * as cmplz_api from "../../utils/api";
import {updateFieldsListWithConditions} from "../../utils/updateFieldsListWithConditions";

const useDataBreachReportsData = create(( set, get ) => ({
	documentsLoaded: false,
	savedDocument:{},
	conclusions:[],
	region: '',
	fileName: '',
	fetching:false,
	updating:false,
	loadingFields:false,
	documents: [],
	regions: [],
	fields: [],
	editDocumentId:false,
	resetEditDocumentId: (id) => {
		set({editDocumentId:false, region:''});
	},
	editDocument: async (id) => {
		set({updating:true});
		await cmplz_api.doAction('load_databreach_report', {id: id}).then((response) => {
			set({fields:response.fields,region:response.region,updating:false,fileName:response.file_name});
		}).catch((error) => {
			console.error(error);
		});
		set({editDocumentId:id});
	},
	setRegion: (region) => {
		set({region:region});
	},
	updateField: (id, value) => {
		let found=false;
		let index = false;
		set(
			produce((state) => {
				state.fields.forEach(function(fieldItem, i) {
					if (fieldItem.id === id ){
						index = i;
						found=true;
					}
				});
				if (index!==false) state.fields[index].value = value;
			})
		)
		let newFields = updateFieldsListWithConditions(get().fields);
		set({fields:newFields});
	},
	save: async ( region ) => {
		set({updating:true});
		let postId = get().editDocumentId;
		let savedDocumentId = 0;
		await cmplz_api.doAction('save_databreach_report', {fields: get().fields, region: region,post_id:postId}).then((response) => {
			savedDocumentId = response.post_id;
			set({updating:false,conclusions:response.conclusions});
			return response;
		}).catch((error) => {
			console.error(error);
		});
		await get().fetchData();
		let documents = get().documents;
		let savedDocuments = documents.filter(document => document.id === savedDocumentId);
		if (savedDocuments.length>0) {
			set({savedDocument:savedDocuments[0]})
		}

	},
	deleteDocuments: async ( ids ) => {
		//get array of documents to delete
		let deleteDocuments = get().documents.filter(document => ids.includes(document.id));
		//remove the ids from the documents array
		set((state) => ({
			documents: state.documents.filter(document => !ids.includes(document.id)),
		}));
		let data = {};
		data.documents = deleteDocuments;
		await cmplz_api.doAction('delete_databreach_report', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
	},
	fetchData: async ( ) => {
		if (get().fetching) return;
		set({fetching:true});
		let data = {}
		const { documents,regions } = await cmplz_api.doAction('get_databreach_reports', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		set((state) => ({
			documentsLoaded: true,
			documents: documents,
			regions: regions,
			fetching:false,
		}));
	},
	fetchFields: async ( region ) => {
		let data = {region:region}
		set({loadingFields:true});
		const { fields } = await cmplz_api.doAction('get_databreach_report_fields', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		let newFields = updateFieldsListWithConditions(fields);
		set((state) => ({
			fields: newFields,
			loadingFields:false,
		}));
	},
}));

export default useDataBreachReportsData;
