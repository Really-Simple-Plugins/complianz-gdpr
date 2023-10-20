import {create} from 'zustand';
import {produce} from 'immer';
import { __ } from '@wordpress/i18n';

import * as cmplz_api from "../utils/api";
const useOnboardingData = create(( set, get ) => ({
	loaded:false,
	plugins: [
		{
			'slug' :'complianz-terms-conditions',
			'description':__("Need Terms & Conditions? Configure now.","complianz-gdpr"),
			'status': 'not-installed',
			'processing': false,
		},
		{
			'slug' :'burst-statistics',
			'premium' :'burst-pro',
			'description':__("Privacy-Friendly Analytics? Here you go!","complianz-gdpr"),
			'status': 'not-installed',
			'processing': false,
		},
		 {
			'slug' :'really-simple-ssl',
			 'description':__("Really Simple Security? Install now!","complianz-gdpr"),
			 'status': 'not-installed',
			 'processing': false,
		 },
	],
	isUpgrade:false,
	processing: true,
	email: '',
	includeTips:false,
	sendTestEmail:true,
	actionStatus: '',
	modalVisible:true,
	setIncludeTips: (includeTips) => {
		set(state => ({ includeTips }))
	},
	setSendTestEmail: (sendTestEmail) => {
		set(state => ({ sendTestEmail }))
	},
	setEmail: (email) => {
		set(state => ({ email }))
	},
	dismissModal: () => {
		const url = new URL(window.location.href);
		url.searchParams.delete('onboarding');
		window.history.pushState({}, '', url.href);
		set(state => ({ modalVisible:false }))

	},
	saveEmail: async () => {
		let data={};
		data.email = get().email;
		data.includeTips = get().includeTips;
		data.sendTestEmail = get().sendTestEmail;
		set((state) => ({processing:true}));
		await cmplz_api.doAction('update_email', data).then(( response ) => {
			return response;
		});
		set(() => ({processing:false}));
	},
	getRecommendedPluginsStatus: async () => {
		const data = {};
		data.plugins = get().plugins;
		const {plugins, isUpgrade} = await cmplz_api.doAction('get_recommended_plugins_status', data).then( async ( response ) => {
			return response
		});
		set({processing:false, plugins: plugins, isUpgrade:isUpgrade,loaded:true});
	},
	setProcessing: (slug, processing) => {
		set(
			produce((state) => {
				const pluginIndex = state.plugins.findIndex(plugin => {
					return plugin.slug===slug;
				});

				if ( pluginIndex!==-1 ){
					state.plugins[pluginIndex].processing = processing;
				}
			})
		)
	},
	pluginAction: async (slug, action) => {
		const data = {};
		data.slug = slug;
		data.plugins = get().plugins;
		get().setProcessing(slug, true);
		const {plugins} = await cmplz_api.doAction(action, data).then( async ( response ) => {
			return response
		});
		set({plugins: plugins});
	},
}));


export default useOnboardingData;
