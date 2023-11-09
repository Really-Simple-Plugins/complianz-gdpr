import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const UseInstallPluginData = create(( set, get ) => ({
	apiRequestActive:false,
	pluginAction: 'status',
	wordPressUrl: '#',
	upgradeUrl: '#',
	rating: [],
	statusLoaded:false,
	setStatusLoaded: (status) => {
		set({statusLoaded:status})
	},
	startPluginAction: (slug, action) => {
		let data = {};
		set({apiRequestActive:true});
		data.pluginAction = typeof action !== 'undefined' ? action : get().pluginAction;
		data.slug = slug;

		let nextAction = false;
		if ( data.pluginAction === 'download' ) {
			nextAction = 'activate';
		}

		cmplz_api.doAction('plugin_actions', data).then( ( response ) => {
			set({
				pluginAction:response.pluginAction,
				wordPressUrl:response.wordpress_url,
				upgradeUrl:response.upgrade_url,
			});//'installed', 'download', 'activate', 'upgrade-to-premium'
			//convert to percentage
			let p = Math.round( response.star_rating.rating / 10, 0 ) / 2;
			set({
				rating:p,
				ratingCount:response.star_rating.rating_count,
				apiRequestActive:false,
				statusLoaded:true,
			});
			//if the plugin is installed, go ahead and activate as well
			if ( nextAction === 'activate' && response.pluginAction!=='installed' ) {
				get().startPluginAction(slug, response.pluginAction);
			}

		});
	},
}));

export default UseInstallPluginData;

