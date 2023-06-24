"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataRequests_useDatarequestsData_js"],{

/***/ "./src/Settings/DataRequests/useDatarequestsData.js":
/*!**********************************************************!*\
  !*** ./src/Settings/DataRequests/useDatarequestsData.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");



const useDatarequestsData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  recordsLoaded: false,
  searchValue: '',
  setSearchValue: value => set({
    searchValue: value
  }),
  fetching: false,
  generating: false,
  progress: false,
  records: [],
  totalRecords: 0,
  totalOpen: 0,
  exportLink: '',
  noData: false,
  deleteRecords: async ids => {
    //get array of records to delete
    let deleteRecords = get().records.filter(record => ids.includes(record.ID));
    //remove the ids from the records array
    set(state => ({
      records: state.records.filter(record => !ids.includes(record.ID))
    }));
    let data = {};
    data.records = deleteRecords;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_datarequests', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  resolveRecords: async ids => {
    //get array of records to resolve
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      state.records.forEach(function (record, i) {
        if (ids.includes(record.ID)) {
          state.records[i].resolved = true;
        }
      });
    }));
    let data = {};
    data.records = get().records.filter(record => ids.includes(record.ID));
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('resolve_datarequests', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  fetchData: async (perPage, page, orderBy, order) => {
    if (get().fetching) return;
    set({
      fetching: true
    });
    let data = {};
    data.per_page = perPage;
    data.page = page;
    data.order = order.toUpperCase();
    data.orderBy = orderBy;
    data.search = get().searchValue;
    const {
      records,
      totalRecords,
      totalOpen
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_datarequests', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(() => ({
      recordsLoaded: true,
      records: records,
      totalRecords: totalRecords,
      totalOpen: totalOpen,
      fetching: false
    }));
  },
  startExport: async () => {
    set({
      generating: true,
      progress: 0,
      exportLink: ''
    });
  },
  fetchExportDatarequestsProgress: async (statusOnly, startDate, endDate) => {
    statusOnly = typeof statusOnly !== 'undefined' ? statusOnly : false;
    if (!statusOnly) {
      set({
        generating: true
      });
    }
    let data = {};
    data.startDate = startDate;
    data.endDate = endDate;
    data.statusOnly = statusOnly;
    const {
      progress,
      exportLink,
      noData
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('export_datarequests', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    let generating = false;
    if (progress < 100) {
      generating = true;
    }
    set({
      progress: progress,
      exportLink: exportLink,
      generating: generating,
      noData: noData
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDatarequestsData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_DataRequests_useDatarequestsData_js.js.map