import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useSecurityMeasuresData = create(( set, get ) => ({
	measures: {},
	has_7: false,
	measuresDataLoaded:false,
	getMeasuresData: async () => {
		const { measures,has_7 } = await cmplz_api.doAction('get_security_measures_data', {}).then( ( response ) => {
			return response;
		});
		set(state => ({ measuresDataLoaded:true, measures:measures, has_7:has_7}));
	},
}));
export default useSecurityMeasuresData;

