import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import Icon from "../../utils/Icon";
import produce from "immer";

const UseSyncData = create(( set, get ) => ({
	addedIds:[],
	loadingSyncData: false,
	syncDataLoaded:false,
	errorMessage: '',
	curlExists: true,
	language:'en',
	languages:['en'],
	hasSyncableData: true,
	purposesOptions:[],
	serviceTypeOptions:[],
	syncProgress: 0,
	cookies: [],
	fCookies: [],
	cookieCount: 1,
	services: [],
	fServices: [],
	saving:false,
	adding:false,
	servicesAndCookies:[],
	showDeletedCookies:false,
	setShowDeletedCookies: (showDeletedCookies) => set({showDeletedCookies}),
	setSyncProgress: (syncProgress) => set({ syncProgress }),
	setLanguage: (language) => set({ language }),
	fetchSyncProgressData: async () => {
		if ( get().loadingSyncData ) {
			return;
		}
		set({loadingSyncData:true,syncDataLoaded:false });
		if ( get().cookies.length === 0 ) {
			//set a placeholder
			let placeholderCookies = [];
			let placeholderCookie;
			for (let i = 1; i < 5; i++) {
				placeholderCookie = {
					ID: -i,
					service:<Icon name = "loading" color = 'grey' />,
					serviceID:-i,
					name: '',
					deleted:0,
					sharesData:0,
					isMembersOnly:0,
					showOnPolicy:0,
					retention:'',
					cookieFunction:'',
					purpose:'',
					sync:0,
					privacyStatementURL:'',
					language: 'en',
					slug:'loading-placeholder'
				};
				placeholderCookies.push(placeholderCookie);
			}
			set({cookies:placeholderCookies });
		}
		const {syncProgress, cookies, services, curlExists, hasSyncableData, purposesOptions, serviceTypeOptions, defaultLanguage, languages} = await fetchSyncProgressData(false, get().language);

		let language = get().language ? get().language : defaultLanguage;
		set({
			language: language,
			languages: languages,
			purposesOptions: purposesOptions,
			serviceTypeOptions: serviceTypeOptions,
			services: services,
			syncProgress: syncProgress,
			cookies: cookies,
			curlExists: curlExists,
			hasSyncableData: hasSyncableData,
			loadingSyncData: false,
			syncDataLoaded:true,
		});

		get().filterAndSort();
	},
	filterAndSort: () => {
		set(
			produce((state) => {
				let services = [...get().services]; // Create a copy of the services array
				let sortedServices = services.sort((a, b) => a.name.localeCompare(b.name))
				state.fServices = sortedServices;
				let cookies = [...get().cookies];
				let sortedCookies = cookies.filter(
					cookie => ( get().showDeletedCookies ||
							(!get().showDeletedCookies && cookie.deleted !== 1 && cookie.deleted !== true ))
				).sort((a, b) => a.name.localeCompare(b.name));
				state.fCookies = sortedCookies;
			})
		)
	},
	restart: async () => {
		if ( get().loadingSyncData ) {
			return;
		}
		set(() => ({loadingSyncData:true,syncDataLoaded:false }));
		const {syncProgress, cookieCount, cookies, services, curlExists, hasSyncableData, purposesOptions, serviceTypeOptions, defaultLanguage, languages, errorMessage} = await fetchSyncProgressData(true);
		let language = get().language ? get().language : defaultLanguage;
		set(() => ({
			loadingSyncData: false,
			language: language,
			languages: languages,
			purposesOptions: purposesOptions,
			serviceTypeOptions: serviceTypeOptions,
			services: services,
			syncProgress: syncProgress,
			cookies: cookies,
			cookieCount: cookieCount,
			curlExists: curlExists,
			hasSyncableData: hasSyncableData,
			errorMessage: errorMessage,
			syncDataLoaded:true,
		}));
		get().filterAndSort();
	},
	updateCookie: (id, type, value) => {
		set(
			produce((state) => {
				const cookieIndex = state.cookies.findIndex(cookie => {
					return cookie.ID===id;
				});
				if ( cookieIndex!==-1 ){
					state.cookies[cookieIndex][type] = value;
				}
			})
		)
		get().filterAndSort();
	},
	addCookie: async (serviceID, serviceName) => {
		set({adding:true });

		let data = {};
		data.service = serviceName;
		data.cookieName = get().cookies.length;//we pass a unique id.
		const {cookies} = await cmplz_api.doAction('add_cookie', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});

		set(
			produce((state) => {
				cookies.forEach(function(cookie, i) {
					if ( cookie.language === get().language ) {
						state.cookies.push(cookie);
					}
				});
			})
		)
		set({adding:false });
		get().filterAndSort();

	},
	saveCookie: async (id) => {
		if (get().saving){
			return;
		}

		set({saving:true });
		const cookieIndex = get().cookies.findIndex(cookie => {
			return cookie.ID===id;
		});

		if (cookieIndex!==-1) {
			let data = {};
			data.cookie = get().cookies[cookieIndex];
			const {cookies} = await cmplz_api.doAction('update_cookie', data).then((response) => {
				return response;
			}).catch((error) => {
				console.error(error);
			});

			set(
				produce((state) => {
					cookies.forEach(function(cookie, i) {
						//find the cookie in the state with the same ID as the cookie we just updated
						let cookieIndex = state.cookies.findIndex(stateCookie => {
							return stateCookie.ID===cookie.ID;
						});
						state.cookies[cookieIndex] = cookie;
					});
				})
			)
			set({saving: false});
		}
		get().filterAndSort();
	},
	addService: () => {
		let addedIds = get().addedIds;
		let newAddedIds = [...addedIds];
		let language = get().language;
		set(
			produce((state) => {
				//keep track of unique ID's for new added items with the newAddedIds array
				let nextId = getNextId(newAddedIds);
				newAddedIds.push(nextId);
				const service = {ID: nextId, name: 'New Service', slug: 'new_service',sync:false,privacyStatementURL:'',language:language};
				state.services.push(service);
				state.addedIds = newAddedIds;
			})
		)
		get().filterAndSort();
	},
	saveService: async (id) => {
		if (get().saving){
			return;
		}
		set({saving:true });
		let services = get().services;
		const serviceIndex = services.findIndex(service => {
			return service.ID===id;
		});

		if (serviceIndex!==-1){
			let data = {};
			data.service = services[serviceIndex];
			await cmplz_api.doAction('update_service', data).then((response) => {
				//check if the service was added new
				if (id<0) {
					let services = get().services;
					//update array
					let newServices = [];
					services.forEach(function(service, i) {
						const newService = {...service};
						if ( service.ID === id ){
							newService.ID=response.ID;
						}
						newServices.push(newService);
					});
					//set state
					set({services:newServices});
				}
				set({ saving:false});
				return response;
			}).catch((error) => {
				console.error(error);
			});
		}
		get().filterAndSort();
	},
	toggleDeleteCookie: async (ID) => {
		set(
			produce((state) => {
				const cookieIndex = state.cookies.findIndex(cookie => {
					return cookie.ID===ID;
				});

				if (cookieIndex!==-1){
					const newCookie = {...state.cookies[cookieIndex]};
					newCookie.deleted = newCookie.deleted==1 ? false: true;
					state.cookies[cookieIndex] = newCookie;
				}
			})
		)

		//update cookie value
		let data = {};
		data.id = ID;
		await cmplz_api.doAction('delete_cookie', data).then((response) => {

		}).catch((error) => {
			console.error(error);
		});
		get().filterAndSort();
	},
	deleteService: async (ID) => {
		let data = {};
		data.id = ID;
		await cmplz_api.doAction('delete_service', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});

		set(
			produce((state) => {
				state.cookies = state.cookies.filter(function(cookie) {
					return parseInt(cookie.serviceID) !== parseInt(ID);
				});

				state.services = state.services.filter(function(service) {
					return parseInt(service.ID) !== parseInt(ID);
				});
			})
		)
		get().filterAndSort();
	},
	updateService: (id, type, value) => {
		set(
			produce((state) => {
				const serviceIndex = state.services.findIndex(service => {
					return service.ID===id;
				});
				if ( serviceIndex!==-1 ){
					state.services[serviceIndex][type] = value;
					if (type==='sync' && !value){
						state.services[serviceIndex]['synced'] = value;
					}
				}
			})
		)
		get().filterAndSort();
	},
}));
export default UseSyncData;

const fetchSyncProgressData = (restart, language ) => {
	let data = {}
	data.scan_action = 'get_progress';
	data.language = language;
	if ( restart ) {
		data.scan_action = 'restart';
	}

	return cmplz_api.doAction('sync', data).then((response) => {
		let syncProgress = response.progress;
		let curlExists = response.curl_exists;
		let cookies = response.cookies;
		let services = response.services;
		let errorMessage = response.message;

		//decode strings which can contain encoded characters
		if (!cookies || cookies.length===0){
			cookies = [];
		}

		if ( !Array.isArray(cookies) ) {
			cookies = Object.values(cookies);
		}
		if ( !Array.isArray(services) ) {
			services = Object.values(services);
		}
		cookies.forEach(function(cookie, i) {
			cookies[i].name = htmlDecode(cookie.name);
			cookies[i].retention = htmlDecode(cookie.retention);
			cookies[i].cookieFunction = htmlDecode(cookie.cookieFunction);
		});

		let hasSyncableData = response.has_syncable_data;
		let purposesOptions = response.purposes_options;
		let serviceTypeOptions = response.serviceType_options;
		let defaultLanguage = response.default_language;
		let cookieCount = cookies.length;

		return {syncProgress, cookies, cookieCount, services, curlExists, hasSyncableData, purposesOptions, serviceTypeOptions, defaultLanguage, languages: response.languages, errorMessage};
	}).catch((error) => {
		console.error(error);
	});
}
const htmlDecode = (input) => {
	var doc = new DOMParser().parseFromString(input, "text/html");
	return doc.documentElement.textContent;
}
const getNextId = (newAddedIds) => {
	let nextId;
	if (newAddedIds.length===0) {
		nextId = 0;
	} else if ( newAddedIds.length===1 ) {
		nextId = newAddedIds[0];
	} else {
		nextId = Math.min(...newAddedIds);
	}
	return nextId - 1;
}

