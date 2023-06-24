"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataRequests_DatarequestsControl_js"],{

/***/ "./src/Settings/DataRequests/DatarequestsControl.js":
/*!**********************************************************!*\
  !*** ./src/Settings/DataRequests/DatarequestsControl.js ***!
  \**********************************************************/
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
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);





const progressComponent = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-integrations-placeholder"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null)));
};
const DatarequestsControl = () => {
  const paginationPerPage = 10;
  const [pagination, setPagination] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [indeterminate, setIndeterminate] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [orderBy, setOrderBy] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('ID');
  const [order, setOrder] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('DESC');
  const [entirePageSelected, setEntirePageSelected] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [timer, setTimer] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const {
    records,
    searchValue,
    setSearchValue,
    deleteRecords,
    recordsLoaded,
    fetchData,
    resolveRecords,
    totalRecords,
    fetching
  } = (0,_useDatarequestsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [btnDisabled, setBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [selectedRecords, setSelectedRecords] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [DataTable, setDataTable] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    __webpack_require__.e(/*! import() */ "vendors-node_modules_react-data-table-component_dist_index_cjs_js").then(__webpack_require__.bind(__webpack_require__, /*! react-data-table-component */ "./node_modules/react-data-table-component/dist/index.cjs.js")).then(_ref => {
      let {
        default: DataTable
      } = _ref;
      setDataTable(() => DataTable);
    });
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!recordsLoaded) fetchData(paginationPerPage, 1, orderBy, order);
  }, [recordsLoaded]);
  const customStyles = {
    headCells: {
      style: {
        paddingLeft: '0',
        paddingRight: '0'
      }
    },
    cells: {
      style: {
        paddingLeft: '0',
        paddingRight: '0'
      }
    }
  };
  const onDeleteRecords = async ids => {
    setSelectedRecords([]);
    await deleteRecords(ids);
  };
  const handleSelectEntirePage = e => {
    let selected = e.target.checked;
    if (selected) {
      setEntirePageSelected(true);
      //add all records on this page to the selectedRecords array
      let currentPage = pagination.currentPage ? pagination.currentPage : 1;
      let recordsOnPage = records.slice((currentPage - 1) * paginationPerPage, currentPage * paginationPerPage);
      setSelectedRecords(recordsOnPage.map(record => record.ID));
    } else {
      setEntirePageSelected(false);
      setSelectedRecords([]);
    }
    setIndeterminate(false);
  };
  const handleSearch = search => {
    clearTimeout(timer);
    setSearchValue(search);
    const newTimer = setTimeout(() => {
      fetchData(paginationPerPage, 1, orderBy, order);
    }, 500);
    setTimer(newTimer);
  };
  const handlePerRowsChange = async (newPerPage, page) => {
    setPagination({
      ...pagination,
      currentPage: page
    });
    fetchData(newPerPage, page, orderBy, order);
  };
  const handlePageChange = page => {
    setPagination({
      ...pagination,
      currentPage: page
    });
    fetchData(paginationPerPage, pagination.currentPage, orderBy, order);
  };
  const handleSort = async (orderBy, order) => {
    setOrderBy(orderBy.orderId);
    setOrder(order);
    fetchData(paginationPerPage, pagination.currentPage, orderBy.orderId, order);
  };
  const onSelectRecord = (e, id) => {
    let selected = e.target.checked;
    let docs = [...selectedRecords];
    if (selected) {
      if (!docs.includes(id)) {
        docs.push(id);
        setSelectedRecords(docs);
      }
    } else {
      //remove the record from the selected records
      docs = [...selectedRecords.filter(recordId => recordId !== id)];
      setSelectedRecords(docs);
    }
    //check if all records on this page are selected
    let currentPage = pagination.currentPage ? pagination.currentPage : 1;
    let recordsOnPage = records.slice((currentPage - 1) * paginationPerPage, currentPage * paginationPerPage);
    let allSelected = true;
    let hasOneSelected = false;
    recordsOnPage.forEach(record => {
      if (!docs.includes(record.id)) {
        allSelected = false;
      } else {
        hasOneSelected = true;
      }
    });
    if (allSelected) {
      setEntirePageSelected(true);
      setIndeterminate(false);
    } else if (!hasOneSelected) {
      setIndeterminate(false);
    } else {
      setEntirePageSelected(false);
      setIndeterminate(true);
    }
  };
  const columns = [{
    name: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      className: indeterminate ? 'indeterminate' : '',
      checked: entirePageSelected,
      onChange: e => handleSelectEntirePage(e)
    }),
    selector: row => row.selectControl,
    orderId: 'select'
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('User ID', "complianz-gdpr"),
    selector: row => row.ID,
    sortable: true,
    orderId: 'ID'
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Name', "complianz-gdpr"),
    selector: row => row.name,
    sortable: true,
    orderId: 'name'
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('E-mail', "complianz-gdpr"),
    selector: row => row.email,
    sortable: true,
    orderId: 'email'
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Status', "complianz-gdpr"),
    selector: row => row.resolved == 1 ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Resolved', "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Open', "complianz-gdpr"),
    sortable: true,
    orderId: 'resolved'
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Region', "complianz-gdpr"),
    selector: row => row.region ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      alt: "region",
      width: "20px",
      height: "20px",
      src: cmplz_settings.plugin_url + 'assets/images/' + row.region + '.svg'
    }) : '',
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date', "complianz-gdpr"),
    selector: row => row.request_date,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Data Request', "complianz-gdpr"),
    selector: row => row.type ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      target: "_blank",
      href: "https://complianz.io/" + row.type.slug
    }, row.type.short) : '',
    sortable: true,
    orderId: 'resolved'
  }];
  let filteredRecords = [...records];

  //add the controls to the plugins
  let data = [];
  filteredRecords.forEach(record => {
    let recordCopy = {
      ...record
    };
    recordCopy.selectControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      checked: selectedRecords.includes(recordCopy.ID),
      onChange: e => onSelectRecord(e, recordCopy.ID)
    });
    data.push(recordCopy);
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    className: "cmplz-datatable-search",
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Search", "complianz-gdpr"),
    value: searchValue,
    onChange: e => handleSearch(e.target.value)
  }))), selectedRecords.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, selectedRecords.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("%s items selected", "complianz-gdpr").replace("%s", selectedRecords.length), selectedRecords.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("1 item selected", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document-controls"
  }, records.filter(record => {
    return selectedRecords.includes(record.ID) && record.resolved != 1;
  }).length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: btnDisabled,
    className: "button button-default",
    onClick: () => resolveRecords(selectedRecords)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Mark as resolved", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default cmplz-reset-button",
    onClick: () => onDeleteRecords(selectedRecords)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Delete", "complianz-gdpr")))), DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    columns: columns,
    data: data,
    dense: true,
    progressPending: fetching,
    progressComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("progressComponent", null),
    pagination: true,
    paginationServer: true,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No records", "really-simple-ssl")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles,
    paginationPerPage: paginationPerPage,
    onChangePage: handlePageChange,
    paginationState: pagination,
    paginationTotalRows: totalRecords,
    onChangeRowsPerPage: handlePerRowsChange,
    onSort: handleSort,
    sortServer: true
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(DatarequestsControl));

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
//# sourceMappingURL=src_Settings_DataRequests_DatarequestsControl_js.js.map