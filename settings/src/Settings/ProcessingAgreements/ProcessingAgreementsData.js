import {create} from 'zustand';
import produce from 'immer';
import * as cmplz_api from "../../utils/api";
import {updateFieldsListWithConditions} from "../../utils/updateFieldsListWithConditions";
const useProcessingAgreementsData = create(( set, get ) => ({
	documentsLoaded: false,
	region: '',
	fileName: '',
	serviceName: '',
	fetching:false,
	updating:false,
	loadingFields:false,
	documents: [],
	regions: [],
	fields: [],
	editDocumentId:false,
	resetEditDocumentId: (id) => {
		set({editDocumentId:false, region:'', serviceName:''});
	},
	editDocument: async (id) => {
		set({updating:true});
		await cmplz_api.doAction('load_processing_agreement', {id: id}).then((response) => {
			set({fields:response.fields,region:response.region,serviceName:response.serviceName,updating:false, fileName:response.file_name});
		}).catch((error) => {
			console.error(error);
		});
		set({editDocumentId:id});
	},
	setRegion: (region) => {
		set({region:region});
	},
	setServiceName: (serviceName) => {
		set({serviceName:serviceName});
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
	save: async ( region, serviceName ) => {
		set({updating:true});
		let postId = get().editDocumentId;
		await cmplz_api.doAction('save_processing_agreement', {fields: get().fields, region: region, serviceName: serviceName, post_id:postId}).then((response) => {
			set({updating:false});

			return response;
		}).catch((error) => {
			console.error(error);
		});
		get().fetchData();
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
		await cmplz_api.doAction('delete_processing_agreement', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
	},
	fetchData: async ( ) => {
		if (get().fetching) return;
		set({fetching:true});
		let data = {}
		const { documents,regions } = await cmplz_api.doAction('get_processing_agreements', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		set(() => ({
			documentsLoaded: true,
			documents: documents,
			regions: regions,
			fetching:false,
		}));
	},
	fetchFields: async ( region ) => {
		let data = {region:region}
		set({loadingFields:true});
		const { fields } = await cmplz_api.doAction('get_processing_agreement_fields', data).then((response) => {
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

export default useProcessingAgreementsData;
