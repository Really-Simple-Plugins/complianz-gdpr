import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const UseCopyMultisiteData = create(( set, get ) => ({
	progress:0,
	total:0,
	start:0,
	next:0,
	active:false,
	copySites: async (restart) => {
		let data = {restart:restart};
		set({
			active:true,
		});
		const {start, next, total} = await cmplz_api.doAction('copy_multisite', data).then( ( response ) => {
			return response});
		let progress = Math.round((next/total)*100);
		set({
			progress:progress,
			start:start,
			next:next,
			total:total,
		});
		if (progress>=100) {
			set({active:false});
		}
	},
}));

export default UseCopyMultisiteData

