import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import produce from "immer";
import {useState} from "@wordpress/element";
const useDatarequestsData = create(( set, get ) => ({
	recordsLoaded: false,
	searchValue: '',
	setSearchValue: (value) => set({searchValue: value}),
	status: 'open',
	setStatus: (value) => set({status: value}),
	selectedRecords:[],
	setSelectedRecords: (value) => set({selectedRecords: value}),
    fetching:false,
	generating:false,
	progress:false,
	records: [],
	totalRecords:0,
	totalOpen:0,
	exportLink: '',
	noData:false,
	indeterminate:false,
	setIndeterminate: (value) => set({indeterminate: value}),
    paginationPerPage: 10,
	pagination:{ currentPage: 1 },
	setPagination: (value) => set({pagination: value}),
	orderBy: 'ID',
    setOrderBy: (value) => set({orderBy: value}),
	order: 'DESC',
	setOrder: (value) => set({order: value}),
	deleteRecords: async (ids) => {
        let data = {}
        data.per_page = get().paginationPerPage;
        data.page = get().pagination.currentPage;
        data.order = get().order.toUpperCase();
        data.orderBy = get().orderBy;
        data.search = get().searchValue;
        data.status = get().status;
		//get array of records to delete
		let deleteRecords = get().records.filter(record => ids.includes(record.ID));
		//remove the ids from the records array
		set((state) => ({
			records: state.records.filter(record => !ids.includes(record.ID)),
		}));
		data.records = deleteRecords;
		await cmplz_api.doAction('delete_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		await get().fetchData();
		get().setSelectedRecords([]);
		get().setIndeterminate(false);
	},
	resolveRecords: async (ids) => {
        let data = {}
        data.per_page = get().paginationPerPage;
        data.page = get().pagination.currentPage;
        data.order = get().order.toUpperCase();
        data.orderBy = get().orderBy;
        data.search = get().searchValue;
        data.status = get().status;
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
		data.records = get().records.filter(record => ids.includes(record.ID));
		await cmplz_api.doAction('resolve_datarequests', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		await get().fetchData();
        get().setSelectedRecords([]);
		get().setIndeterminate(false);
	},
	fetchData: async () => {
		if (get().fetching) return;
		set({fetching:true});
		let data = {}
		data.per_page = get().paginationPerPage;
		data.page = get().pagination.currentPage;
		data.order = get().order.toUpperCase();
		data.orderBy = get().orderBy;
		data.search = get().searchValue;
		data.status = get().status;
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
