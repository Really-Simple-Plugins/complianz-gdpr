import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useProofOfConsentData = create(( set, get ) => ({
	documentsLoaded: false,
	fetching:false,
	generating:false,
	documents: [],
	downloadUrl: '',
	regions: [],
	fields: [],
	deleteDocuments: async (ids) => {
		//get array of documents to delete
		let deleteDocuments = get().documents.filter(document => ids.includes(document.id));
		//remove the ids from the documents array
		set((state) => ({
			documents: state.documents.filter(document => !ids.includes(document.id)),
		}));
		let data = {};
		data.documents = deleteDocuments;
		await cmplz_api.doAction('delete_proof_of_consent_documents', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});

	},
	generateProofOfConsent: async () => {
		set({generating:true});
		let data = {};
		await cmplz_api.doAction('generate_proof_of_consent', data).then((response) => {

			return response;
		}).catch((error) => {
			console.error(error);
		});
		await get().fetchData();
		set({generating:false});

	},
	fetchData: async ( ) => {
		if (get().fetching) return;
		set({fetching:true});
		let data = {}
		const { documents,regions, download_url} = await cmplz_api.doAction('get_proof_of_consent_documents', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		set((state) => ({
			documentsLoaded: true,
			documents: documents,
			regions: regions,
			downloadUrl: download_url,
			fetching:false,
		}));
	},
}));

export default useProofOfConsentData;
