import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const usePrivacyStatementData = create(( set, get ) => ({
	privacyStatementsLoaded: false,
	privacyStatements : [],

	fetchPrivacyStatementsData: async () => {
		const {privacyStatements}   = await cmplz_api.doAction('wp_privacy_policy_data' ).then( ( response ) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		set({privacyStatementsLoaded: true, privacyStatements:privacyStatements });
	}
}));

export default usePrivacyStatementData;
