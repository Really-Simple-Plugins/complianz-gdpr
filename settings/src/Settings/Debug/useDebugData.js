import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useDebugData = create(( set, get ) => ({
	debugData: [],
	debugDataLoaded: false,
	scriptDebugEnabled:false,
	getDebugData: async () => {
		const {debug_data, script_debug_enabled } = await cmplz_api.doAction('get_debug_data', {}).then( ( response ) => {
			return response;
		});
		set(state => ({ debugDataLoaded:true,debugData:debug_data, scriptDebugEnabled:script_debug_enabled}));
	},
}));
export default useDebugData;

