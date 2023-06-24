"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataRequests_ExportDatarequests_js"],{

/***/ "./src/DateRange/useDateStore.js":
/*!***************************************!*\
  !*** ./src/DateRange/useDateStore.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   useDate: () => (/* binding */ useDate)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/format/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/startOfDay/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/subDays/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/endOfDay/index.js");



// define the store
const useDate = (0,zustand__WEBPACK_IMPORTED_MODULE_0__.create)(set => ({
  startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_1__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_2__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_3__["default"])(new Date(), 7)), 'yyyy-MM-dd'),
  setStartDate: startDate => set(state => ({
    startDate
  })),
  endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_1__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_4__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_3__["default"])(new Date(), 1)), 'yyyy-MM-dd'),
  setEndDate: endDate => set(state => ({
    endDate
  })),
  range: 'last-7-days',
  setRange: range => set(state => ({
    range
  }))
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDate);

/***/ }),

/***/ "./src/Settings/DataRequests/ExportDatarequests.js":
/*!*********************************************************!*\
  !*** ./src/Settings/DataRequests/ExportDatarequests.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _useDatarequestsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./useDatarequestsData */ "./src/Settings/DataRequests/useDatarequestsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _DateRange_useDateStore__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../DateRange/useDateStore */ "./src/DateRange/useDateStore.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);







const ExportDatarequests = () => {
  const {
    noData,
    startExport,
    exportLink,
    fetchExportDatarequestsProgress,
    generating,
    progress
  } = (0,_useDatarequestsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [DateRange, setDateRange] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const {
    startDate,
    endDate
  } = (0,_DateRange_useDateStore__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    Promise.all(/*! import() */[__webpack_require__.e("vendors-node_modules_mui_material_Modal_Modal_js-node_modules_mui_material_Paper_Paper_js"), __webpack_require__.e("vendors-node_modules_mui_material_Popover_Popover_js-node_modules_date-fns_esm_endOfYear_inde-acb6b5"), __webpack_require__.e("src_DateRange_DateRange_js-_3ed40")]).then(__webpack_require__.bind(__webpack_require__, /*! ../../DateRange/DateRange */ "./src/DateRange/DateRange.js")).then(_ref => {
      let {
        default: DateRange
      } = _ref;
      setDateRange(() => DateRange);
    });
  }, []);

  //check if there's an export running
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchExportDatarequestsProgress(true);
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    //startDate, endDate
    if (progress < 100 && generating) {
      fetchExportDatarequestsProgress(false, startDate, endDate);
    }
  }, [progress]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, DateRange && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DateRange, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: generating,
    className: "button button-default cmplz-field-button",
    onClick: () => startExport()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Export to CSV", "complianz-gdpr"), generating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "loading",
    color: "grey"
  }), "\xA0", progress, "%"))), progress >= 100 && (exportLink !== '' || noData) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, !noData && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Your Data Requests Export has been completed.", "complianz-gdpr"), noData && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Your selection does not contain any data.", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document-controls"
  }, !noData && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "button button-default",
    href: exportLink
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Download", "complianz-gdpr")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(ExportDatarequests));

/***/ }),

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
//# sourceMappingURL=src_Settings_DataRequests_ExportDatarequests_js.js.map