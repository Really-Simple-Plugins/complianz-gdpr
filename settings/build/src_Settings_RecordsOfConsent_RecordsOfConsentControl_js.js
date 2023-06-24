"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_RecordsOfConsent_RecordsOfConsentControl_js"],{

/***/ "./src/Settings/RecordsOfConsent/RecordsOfConsentControl.js":
/*!******************************************************************!*\
  !*** ./src/Settings/RecordsOfConsent/RecordsOfConsentControl.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _useRecordsOfConsentData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./useRecordsOfConsentData */ "./src/Settings/RecordsOfConsent/useRecordsOfConsentData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);





const RecordsOfConsentControl = () => {
  const paginationPerPage = 10;
  const [searchValue, setSearchValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [pagination, setPagination] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [indeterminate, setIndeterminate] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [entirePageSelected, setEntirePageSelected] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const handlePageChange = page => {
    setPagination({
      ...pagination,
      currentPage: page
    });
  };
  const {
    records,
    downloadUrl,
    deleteRecords,
    recordsLoaded,
    fetchData
  } = (0,_useRecordsOfConsentData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [btnDisabled, setBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [selectedRecords, setSelectedRecords] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const disabled = !cmplz_settings.is_premium;
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
    if (!recordsLoaded && cmplz_settings.is_premium) fetchData();
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
  const downloadRecords = async () => {
    let selectedRecordsCopy = records.filter(record => {
      return selectedRecords.includes(record.id) && record.poc_url !== '';
    });
    setSelectedRecords([]);
    const downloadNext = async () => {
      if (selectedRecordsCopy.length > 0) {
        const record = selectedRecordsCopy.shift();
        const url = downloadUrl + '/' + record.poc_url;
        ;
        setBtnDisabled(true);
        try {
          let request = new XMLHttpRequest();
          request.responseType = 'blob';
          request.open('get', url, true);
          request.send();
          request.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
              let obj = window.URL.createObjectURL(this.response);
              let element = window.document.createElement('a');
              element.setAttribute('href', obj);
              element.setAttribute('download', record.filename);
              window.document.body.appendChild(element);
              //onClick property
              element.click();
              setTimeout(function () {
                window.URL.revokeObjectURL(obj);
              }, 60 * 1000);
            }
          };
          await downloadNext();
        } catch (error) {
          console.error(error);
          setBtnDisabled(false);
        }
      }
    };
    await downloadNext();
    setBtnDisabled(false);
  };
  const handleSelectEntirePage = e => {
    let selected = e.target.checked;
    if (selected) {
      setEntirePageSelected(true);
      //add all records on this page to the selectedRecords array
      let currentPage = pagination.currentPage ? pagination.currentPage : 1;
      let filtered = handleFiltering(records);
      let recordsOnPage = filtered.slice((currentPage - 1) * paginationPerPage, currentPage * paginationPerPage);
      setSelectedRecords(recordsOnPage.map(record => record.id));
    } else {
      setEntirePageSelected(false);
      setSelectedRecords([]);
    }
    setIndeterminate(false);
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
    let filtered = handleFiltering(records);
    let recordsOnPage = filtered.slice((currentPage - 1) * paginationPerPage, currentPage * paginationPerPage);
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
  const consentLabels = {
    'optin': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Opt-in', 'complianz-gdpr'),
    'optout': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Opt-out', 'complianz-gdpr'),
    'other': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Other', 'complianz-gdpr')
  };
  const handleFiltering = records => {
    // //sort the plugins alphabetically by filename
    records = records.sort((a, b) => {
      if (a.file < b.file) {
        return -1;
      }
      if (a.file > b.file) {
        return 1;
      }
      return 0;
    });

    //filter the plugins by search value
    records = records.filter(document => {
      return document.ip.toLowerCase().includes(searchValue.toLowerCase()) || document.services.toLowerCase().includes(searchValue.toLowerCase()) || document.id.toLowerCase().includes(searchValue.toLowerCase());
    });
    return records;
  };
  const getCategories = row => {
    let availableCategories = {
      do_not_track: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("DNT/GPC", "complianz-gdpr"),
      no_choice: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No Choice", "complianz-gdpr"),
      no_warning: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No Warning", "complianz-gdpr"),
      functional: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Functional", "complianz-gdpr"),
      preferences: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Preferences", "complianz-gdpr"),
      statistics: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Statistics", "complianz-gdpr"),
      marketing: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Marketing", "complianz-gdpr")
    };
    let categories = [];
    //for each availableCategories item, check if  row.category is set to true
    Object.keys(availableCategories).forEach(category => {
      if (parseInt(row[category]) === 1) {
        categories.push(availableCategories[category]);
      }
    });
    return categories.join(', ');
  };
  const columns = [{
    name: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      className: indeterminate ? 'indeterminate' : '',
      checked: entirePageSelected,
      onChange: e => handleSelectEntirePage(e)
    }),
    selector: row => row.selectControl
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('User ID', "complianz-gdpr"),
    selector: row => row.id,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('IP Adress', "complianz-gdpr"),
    selector: row => row.ip,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Region', "complianz-gdpr"),
    selector: row => row.region !== '' ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      alt: "region",
      width: "20px",
      height: "20px",
      src: cmplz_settings.plugin_url + 'assets/images/' + row.region + '.svg'
    }) : '',
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Services', "complianz-gdpr"),
    selector: row => row.services,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Consent', "complianz-gdpr"),
    selector: row => row.consenttype ? consentLabels[row.consenttype] : '',
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Categories', "complianz-gdpr"),
    selector: row => getCategories(row),
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date', "complianz-gdpr"),
    selector: row => row.time,
    sortable: true
  }];
  let filteredRecords = [...records];
  filteredRecords = handleFiltering(filteredRecords);

  //add the controls to the plugins
  let data = [];
  filteredRecords.forEach(record => {
    let recordCopy = {
      ...record
    };
    recordCopy.selectControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      checked: selectedRecords.includes(recordCopy.id),
      onChange: e => onSelectRecord(e, recordCopy.id)
    });
    data.push(recordCopy);
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, disabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay-message"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    className: "cmplz-datatable-search",
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Search", "complianz-gdpr"),
    value: searchValue,
    onChange: e => setSearchValue(e.target.value)
  }))), selectedRecords.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, selectedRecords.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("%s items selected", "complianz-gdpr").replace("%s", selectedRecords.length), selectedRecords.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("1 item selected", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document-controls"
  }, records.filter(record => {
    return selectedRecords.includes(record.id) && record.poc_url !== '';
  }).length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: btnDisabled,
    className: "button button-default cmplz-btn-reset",
    onClick: () => downloadRecords()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Download Proof of Consent", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default cmplz-reset-button",
    onClick: () => onDeleteRecords(selectedRecords)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Delete", "complianz-gdpr")))), !disabled && DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    columns: columns,
    data: data,
    dense: true,
    pagination: true,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No records", "really-simple-ssl")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles,
    paginationPerPage: paginationPerPage,
    onChangePage: handlePageChange,
    paginationState: pagination
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(RecordsOfConsentControl));

/***/ }),

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
//# sourceMappingURL=src_Settings_RecordsOfConsent_RecordsOfConsentControl_js.js.map