import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";

const useTools = create(( set, get ) => ({
	getDocuments: async () => {
		const {documents, processingAgreementOptions, proofOfConsentOptions,dataBreachOptions} = await cmplz_api.doAction('documents_block_data').then( ( response ) => {

			return response;
		});
		set(state => ({ documents:documents, processingAgreementOptions:processingAgreementOptions, proofOfConsentOptions:proofOfConsentOptions,dataBreachOptions:dataBreachOptions}));
	},

}));

export default useTools;

