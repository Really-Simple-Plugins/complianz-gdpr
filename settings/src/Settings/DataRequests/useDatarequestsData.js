import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import produce from "immer";
const useDatarequestsData = create(( set, get ) => ({
	recordsLoaded: false,
	searchValue: '',
	setSearchValue: (value) => set({searchValue: value}),
	fetching:false,
	generating:false,
	progress:false,
	records: [],
	totalRecords:0,
	totalOpen:0,
	exportLink: '',
	noData:false,
	deleteRecords: async (ids) => {
		//get array of records to delete
		let deleteRecords = get().records.filter(record => ids.includes(record.ID));
		//remove the ids from the records array
		set((state) => ({
			records: state.records.filter(record => !ids.includes(record.ID)),
		}));
		let data = {};
		data.records = deleteRecords;
		await cmplz_api.doAction('delete_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
	},
	resolveRecords: async (ids) => {
		//get array of records to resolve
		set(
			produce((state) => {
				state.records.forEach(function(record, i) {
					if (ids.includes(record.ID) ){
						state.records[i].resolved = true;
					}
				});
			})
		)
		let data = {};
		data.records = get().records.filter(record => ids.includes(record.ID));
		await cmplz_api.doAction('resolve_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
	},
	fetchData: async (perPage, page, orderBy, order ) => {
		if (get().fetching) return;
		set({fetching:true});
		let data = {}
		data.per_page = perPage;
		data.page = page;
		data.order = order.toUpperCase();
		data.orderBy = orderBy;
		data.search = get().searchValue;
		const { records, totalRecords, totalOpen} = await cmplz_api.doAction('get_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});

		set(() => ({
			recordsLoaded: true,
			records: records,
			totalRecords: totalRecords,
			totalOpen:totalOpen,
			fetching:false,
		}));
	},
	startExport: async () => {
		set({
			generating: true,
			progress:0,
			exportLink: '',
		})
	},
	fetchExportDatarequestsProgress: async (statusOnly, startDate, endDate) => {
		statusOnly = typeof statusOnly !== 'undefined' ? statusOnly : false;
		if (!statusOnly) {
			set({ generating:true });
		}

		let data = {};
		data.startDate = startDate;
		data.endDate = endDate;
		data.statusOnly = statusOnly;
		const {progress, exportLink, noData} = await cmplz_api.doAction('export_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		let generating = false;
		if (progress<100 ){
			generating = true;
		}
		set({progress:progress, exportLink:exportLink, generating:generating, noData:noData});

	},
}));

export default useDatarequestsData;
