import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";

const useDocuments = create(( set, get ) => ({
	documents:[],
	documentDataLoaded:false,
	processingAgreementOptions:[],
	proofOfConsentOptions:[],
	dataBreachOptions:[],
	region:'',
	setRegion: (region) => {
		if (typeof (Storage) !== "undefined" ) {
			sessionStorage.cmplzSelectedRegion = region;
		}
		set(state => ({ region:region}));
	},
	getRegion: () => {
		let region = 'all';
		if (typeof (Storage) !== "undefined"){
			if (sessionStorage.cmplzSelectedRegion) {
				region = sessionStorage.cmplzSelectedRegion;
			}
		}
		set(state => ({ region:region}));
	},
	getDocuments: async () => {
		const {documents, processingAgreementOptions, proofOfConsentOptions,dataBreachOptions} = await cmplz_api.doAction('documents_block_data').then( ( response ) => {
			return response;
		});
		set(state => ({ documentDataLoaded:true,documents:documents, processingAgreementOptions:processingAgreementOptions, proofOfConsentOptions:proofOfConsentOptions,dataBreachOptions:dataBreachOptions}));
	},

}));

export default useDocuments;

