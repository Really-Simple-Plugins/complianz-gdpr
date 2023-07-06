import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useFinishData = create(( set, get ) => ({
	cookiebannerRequired:false,
	getCookieBannerRequired: async () => {
		let data = {};
		const {required} = await cmplz_api.doAction( 'get_cookiebanner_required', data).then( ( response ) => {
			return response;
		});
		set({
			cookiebannerRequired:required,
		});
	},
}));

export default useFinishData;

