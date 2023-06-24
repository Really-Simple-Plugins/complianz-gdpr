"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_RecordsOfConsent_useRecordsOfConsentData_js"],{

/***/ "./src/Settings/RecordsOfConsent/useRecordsOfConsentData.js":
/*!******************************************************************!*\
  !*** ./src/Settings/RecordsOfConsent/useRecordsOfConsentData.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const useRecordsOfConsentData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  recordsLoaded: false,
  fetching: false,
  generating: false,
  progress: false,
  records: [],
  exportLink: '',
  downloadUrl: '',
  regions: [],
  fields: [],
  noData: false,
  deleteRecords: async ids => {
    //get array of records to delete
    let deleteRecords = get().records.filter(record => ids.includes(record.id));
    //remove the ids from the records array
    set(state => ({
      records: state.records.filter(record => !ids.includes(record.id))
    }));
    let data = {};
    data.records = deleteRecords;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_records_of_consent', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  fetchData: async () => {
    if (get().fetching) return;
    set({
      fetching: true
    });
    let data = {};
    const {
      records,
      regions,
      download_url
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_records_of_consent', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(() => ({
      recordsLoaded: true,
      records: records,
      regions: regions,
      downloadUrl: download_url,
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
  fetchExportRecordsOfConsentProgress: async (statusOnly, startDate, endDate) => {
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
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('export_records_of_consent', data).then(response => {
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useRecordsOfConsentData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_RecordsOfConsent_useRecordsOfConsentData_js.js.map