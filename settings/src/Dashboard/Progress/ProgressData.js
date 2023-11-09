import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const useProgress = create(( set, get ) => ({
    filter:'all',
    notices: [],
    error:false,
    percentageCompleted:0,
    progressLoaded:false,
    progressLoading:false,
	setProgressLoaded(loaded) {
		set(state => ({ progressLoaded:loaded }));
	},
	showCookieBanner:false,
    setFilter: (filter) => {
        sessionStorage.cmplz_task_filter = filter;
        set(state => ({ filter }))
    },
    fetchFilter: () => {
        if ( typeof (Storage) !== "undefined" && sessionStorage.cmplz_task_filter  ) {
            let filter = sessionStorage.cmplz_task_filter;
            set(state => ({ filter:filter }))
        }
    },
    fetchProgressData: async () => {
		if ( get().progressLoading ) {
			return;
		}
		set({progressLoading:true});
        const {notices, show_cookiebanner} = await cmplz_api.doAction('get_notices').then( ( response ) => {
            return response;
        });
		set(state => ({ progressLoading:false, notices:notices, percentageCompleted:get().getPercentageCompleted(notices),showCookieBanner:show_cookiebanner,progressLoaded:true}));
    },
	updateProgressData: async (notices, showCookieBanner) => {
		set(state => ({ notices:notices, percentageCompleted:get().getPercentageCompleted(notices),showCookieBanner:showCookieBanner,progressLoaded:true}));
	},
    dismissNotice: async (noticeId) => {
        let notices = get().notices;
        notices = notices.filter(function (notice) {
            return notice.id !== noticeId;
        });
        set(state => ({ notices:notices }))
		let data = {};
		data.id = noticeId;
		await cmplz_api.doAction('dismiss_task', data);
		set(state => ({ notices:notices, percentageCompleted:get().getPercentageCompleted(notices)}));
    },
	addNotice: (id, status, message, show_with_options) => {
		let notices = get().notices;

		let exists = notices.filter( (notice) => {
			return notice.id === id;
		}).length;

		if (!exists) {
			show_with_options = [show_with_options];
			notices.push({id:id, status:status, label:status, message:message, show_with_options:show_with_options});
			set(state => ({ notices:notices, percentageCompleted:get().getPercentageCompleted(notices)}));
		}
	},
	getPercentageCompleted: (notices) => {
		notices = typeof notices ==='undefined' ? get().notices : notices;
		let total = notices.length;
		let complete = notices.filter( (notice) => {
			return notice.status === 'completed';
		}).length;
		let percentage = complete/total*100;
		return Math.round(percentage);
	}
}));

export default useProgress;

