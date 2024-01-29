import {create} from 'zustand';
import * as cmplz_api from "../utils/api";
const emptyData = {
	'optin': {
		"labels": [
			"Functional",
			"Statistics",
			"Marketing",
			"Do Not Track",
			"No Choice",
			"No Warning",
		],
		"categories": [
			"functional",
			"statistics",
			"marketing",
			"do_not_track",
			"no_choice",
			"no_warning",
		],
		"datasets": [
			{
				"data": [
					"0",
					"0",
					"0",
					"0",
					"0",
					"0"
				],
				"backgroundColor": "rgba(46, 138, 55, 1)",
				"borderColor": "rgba(46, 138, 55, 1)",
				"label": "A (default)",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			},
			{
				"data": [
					"0",
					"0",
					"0",
					"0",
					"0",
					"0"
				],
				"backgroundColor": "rgba(244, 191, 62, 1)",
				"borderColor": "rgba(244, 191, 62, 1)",
				"label": "B",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			}
		],
		"max": 5
	},
	'optout': {
		"labels": [
			"Functional",
			"Statistics",
			"Marketing",
			"Do Not Track",
			"No Choice",
			"No Warning",
		],
		"categories": [
			"functional",
			"statistics",
			"marketing",
			"do_not_track",
			"no_choice",
			"no_warning",
		],
		"datasets": [
			{
				"data": [
					"0",
					"0",
					"0",
					"0",
					"0",
					"0"
				],
				"backgroundColor": "rgba(46, 138, 55, 1)",
				"borderColor": "rgba(46, 138, 55, 1)",
				"label": "A (default)",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			},
			{
				"data": [
					"0",
					"0",
					"0",
					"0",
					"0",
					"0"
				],
				"backgroundColor": "rgba(244, 191, 62, 1)",
				"borderColor": "rgba(244, 191, 62, 1)",
				"label": "B",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			}
		],
		"max": 5
	}
}
const defaultData = {
	'optin': {
		"labels": [
			"Functional",
			"Statistics",
			"Marketing",
			"Do Not Track",
			"No Choice",
			"No Warning",
		],
		"categories": [
			"functional",
			"statistics",
			"marketing",
			"do_not_track",
			"no_choice",
			"no_warning",
		],
		"datasets": [
			{
				"data": [
					"29",
					"747",
					"174",
					"292",
					"30",
					"10"
				],
				"backgroundColor": "rgba(46, 138, 55, 1)",
				"borderColor": "rgba(46, 138, 55, 1)",
				"label": "Demo A (default)",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			},
			{
				"data": [
					"3",
					"536",
					"240",
					"389",
					"45",
					"32"
				],
				"backgroundColor": "rgba(244, 191, 62, 1)",
				"borderColor": "rgba(244, 191, 62, 1)",
				"label": "Demo B",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			}
		],
		"max": 5
	},
	'optout': {
		"labels": [
			"Functional",
			"Statistics",
			"Marketing",
			"Do Not Track",
			"No Choice",
			"No Warning",
		],
		"categories": [
			"functional",
			"statistics",
			"marketing",
			"do_not_track",
			"no_choice",
			"no_warning",
		],
		"datasets": [
			{
				"data": [
					"29",
					"747",
					"174",
					"292",
					"30",
					"10"
				],
				"backgroundColor": "rgba(46, 138, 55, 1)",
				"borderColor": "rgba(46, 138, 55, 1)",
				"label": "A (default)",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			},
			{
				"data": [
					"3",
					"536",
					"240",
					"389",
					"45",
					"32"
				],
				"backgroundColor": "rgba(244, 191, 62, 1)",
				"borderColor": "rgba(244, 191, 62, 1)",
				"label": "Demo B",
				"fill": "false",
				"borderDash": [
					0,
					0
				]
			}
		],
		"max": 5
	}
}
const useStatistics = create(( set, get ) => ({
	consentType: 'optin',
	setConsentType: (consentType) => {
		set({consentType:consentType});
	},
	statisticsLoading:false,
	consentTypes: [],
	regions:[],
	defaultConsentType: 'optin',
	loaded:false,
	statisticsData: emptyData,
	emptyStatisticsData: emptyData,
	bestPerformerEnabled:false,
	daysLeft:'',
	abTrackingCompleted:false,
	labels:[],
	setLabels: (labels) => {
		set({labels:labels});
	},
	fetchStatisticsData: async () => {
		if (!cmplz_settings.is_premium ) {
			set({
				saving:false,
				loaded:true,
				consentType:'optin',
				consentTypes:['optin', 'optout'],
				statisticsData:defaultData,
				defaultConsentType:'optin',
				bestPerformerEnabled:false,
				regions:'eu',
				daysLeft:11,
				abTrackingCompleted:false,
			});
			return;
		}

		set({saving:true });
		let data = {};
		if (get().loaded) return;
		const {daysLeft, abTrackingCompleted, consentTypes, statisticsData, defaultConsentType, regions, bestPerformerEnabled} = await cmplz_api.doAction('get_statistics_data', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});

		set({
			saving:false,
			loaded:true,
			consentType:defaultConsentType,
			consentTypes:consentTypes,
			statisticsData:statisticsData,
			defaultConsentType:defaultConsentType,
			bestPerformerEnabled:bestPerformerEnabled,
			regions:regions,
			daysLeft:daysLeft,
			abTrackingCompleted:abTrackingCompleted,
		});
	},
}));
export default useStatistics;
