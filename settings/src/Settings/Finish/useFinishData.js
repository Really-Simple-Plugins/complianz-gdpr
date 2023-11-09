import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useFinishData = create(( set, get ) => ({
	cookiebannerRequired:false,
	loading:false,
	getCookieBannerRequired: async () => {
		if (get().loading) {
			return;
		}
		set({
			loading:true,
		});
		let data = {};
		const {required} = await cmplz_api.doAction( 'get_cookiebanner_required', data).then( ( response ) => {
			return response;
		});
		set({
			cookiebannerRequired:required,
			loading:false,
		});
	},
}));

export default useFinishData;

