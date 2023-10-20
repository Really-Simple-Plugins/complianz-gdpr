import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";

export const UseCookieScanData = create((set, get) => ({
	initialLoadCompleted: false,
	setInitialLoadCompleted: (initialLoadCompleted) => set({initialLoadCompleted}),
	iframeLoaded: false,
	loading: false,
	nextPage: false,
	progress: 0,
	cookies: [],
	lastLoadedIframe: '',
	setIframeLoaded: (iframeLoaded) => set({iframeLoaded}),
	setLastLoadedIframe: (lastLoadedIframe) => set(state => ({lastLoadedIframe})),
	setProgress: (progress) => set({progress}),
	fetchProgress: () => {
		let data = {};
		set({loading:true});
		return cmplz_api.doAction( 'get_scan_progress', data).then( ( response ) => {
			set({
				initialLoadCompleted: true,
				loading: false,
				nextPage: response.next_page,
				progress: response.progress,
				cookies: response.cookies
			});
			return response;
		});
	}
}));



