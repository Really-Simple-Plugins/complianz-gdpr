"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataBreachReports_DataBreachReportsControl_js"],{

/***/ "./src/Settings/DataBreachReports/DataBreachReportsControl.js":
/*!********************************************************************!*\
  !*** ./src/Settings/DataBreachReports/DataBreachReportsControl.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _DataBreachReportsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DataBreachReportsData */ "./src/Settings/DataBreachReports/DataBreachReportsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);






const DataBreachReportsControl = () => {
  const {
    documents,
    documentsLoaded,
    fetchData,
    deleteDocuments,
    editDocument
  } = (0,_DataBreachReportsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [searchValue, setSearchValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [regionFilter] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [btnDisabled, setBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [selectedDocuments, setSelectedDocuments] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [downloading, setDownloading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const paginationPerPage = 5;
  const [pagination, setPagination] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [indeterminate, setIndeterminate] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [entirePageSelected, setEntirePageSelected] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const handlePageChange = page => {
    setPagination({
      ...pagination,
      currentPage: page
    });
  };
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
    if (!documentsLoaded) fetchData();
  }, [documentsLoaded]);
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
  const onDeleteDocuments = async ids => {
    setSelectedDocuments([]);
    await deleteDocuments(ids);
  };
  const downloadDocuments = async () => {
    let selectedDocumentsCopy = documents.filter(document => selectedDocuments.includes(document.id));
    setDownloading(true);
    setBtnDisabled(true);
    const downloadNext = async () => {
      if (selectedDocumentsCopy.length > 0) {
        const document = selectedDocumentsCopy.shift();
        const url = document.download_url;
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
              element.setAttribute('download', document.title);
              window.document.body.appendChild(element);
              //onClick property
              element.click();
              setSelectedDocuments(selectedDocumentsCopy);
              setDownloading(false);
              setBtnDisabled(false);
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
  };
  const handleSelectEntirePage = e => {
    let selected = e.target.checked;
    if (selected) {
      setEntirePageSelected(true);
      //add all records on this page to the selectedRecords array
      let currentPage = pagination.currentPage ? pagination.currentPage : 1;
      //get records from currentPage * paginationPerPage to (currentPage+1) * paginationPerPage
      let filtered = handleFiltering(documents);
      let recordsOnPage = filtered.slice((currentPage - 1) * paginationPerPage, currentPage * paginationPerPage);
      setSelectedDocuments(recordsOnPage.map(document => document.id));
    } else {
      setEntirePageSelected(false);
      setSelectedDocuments([]);
    }
    setIndeterminate(false);
  };
  const onSelectDocument = (e, id) => {
    let selected = e.target.checked;
    let docs = [...selectedDocuments];
    if (selected) {
      if (!docs.includes(id)) {
        docs.push(id);
        setSelectedDocuments(docs);
      }
    } else {
      //remove the document from the selected documents
      docs = [...selectedDocuments.filter(documentId => documentId !== id)];
      setSelectedDocuments(docs);
    }
    //check if all records on this page are selected
    let currentPage = pagination.currentPage ? pagination.currentPage : 1;
    //get records from currentPage * paginationPerPage to (currentPage+1) * paginationPerPage
    let filtered = handleFiltering(documents);
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
  const handleFiltering = documents => {
    let newDocuments = [...documents];
    if (regionFilter !== '') {
      newDocuments = newDocuments.filter(document => {
        return document.region === regionFilter;
      });
    }

    //sort the plugins alphabetically by title
    newDocuments.sort((a, b) => {
      if (a.title < b.title) {
        return -1;
      }
      if (a.title > b.title) {
        return 1;
      }
      return 0;
    });
    newDocuments.filter(document => {
      return document.title.toLowerCase().includes(searchValue.toLowerCase()) || document.service.toLowerCase().includes(searchValue.toLowerCase());
    });
    return newDocuments;
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
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Document', "complianz-gdpr"),
    selector: row => row.title,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Edit', "complianz-gdpr"),
    selector: row => row.editControl
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Region', "complianz-gdpr"),
    selector: row => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      alt: "region",
      width: "20px",
      height: "20px",
      src: cmplz_settings.plugin_url + 'assets/images/' + row.region + '.svg'
    }),
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Service', "complianz-gdpr"),
    selector: row => row.service,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Date', "complianz-gdpr"),
    selector: row => row.date,
    sortable: true
  }];

  //filter the plugins by search value
  let filteredDocuments = handleFiltering(documents);

  //add the controls to the plugins
  let data = [];
  filteredDocuments.forEach(document => {
    let documentCopy = {
      ...document
    };
    documentCopy.selectControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      checked: selectedDocuments.includes(documentCopy.id),
      onChange: e => onSelectDocument(e, documentCopy.id)
    });
    documentCopy.editControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: "#",
      onClick: e => editDocument(e, documentCopy.id)
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Edit", "complianz-gdpr"));
    data.push(documentCopy);
  });
  let showDownloadButton = selectedDocuments.length > 1;
  if (!showDownloadButton && selectedDocuments.length === 1) {
    let currentSelected = documents.filter(document => selectedDocuments.includes(document.id));
    showDownloadButton = currentSelected[0].download_url !== '';
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    className: "cmplz-datatable-search",
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Search", "complianz-gdpr"),
    value: searchValue,
    onChange: e => setSearchValue(e.target.value)
  }))), selectedDocuments.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, selectedDocuments.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("%s items selected", "complianz-gdpr").replace("%s", selectedDocuments.length), selectedDocuments.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("1 item selected", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document-controls"
  }, showDownloadButton && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: btnDisabled,
    className: "button button-default cmplz-btn-reset",
    onClick: () => downloadDocuments()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Download Data Breach Report", "complianz-gdpr"), downloading && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "loading",
    color: "grey"
  })), !showDownloadButton && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: true,
    className: "button button-default cmplz-btn-reset",
    onClick: () => downloadDocuments()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Reporting not required", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default cmplz-reset-button",
    onClick: () => onDeleteDocuments(selectedDocuments)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Delete", "complianz-gdpr")))), DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    columns: columns,
    data: data,
    dense: true,
    pagination: true,
    paginationPerPage: paginationPerPage,
    onChangePage: handlePageChange,
    paginationState: pagination,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No documents", "complianz-gdpr")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(DataBreachReportsControl));

/***/ }),

/***/ "./src/Settings/DataBreachReports/DataBreachReportsData.js":
/*!*****************************************************************!*\
  !*** ./src/Settings/DataBreachReports/DataBreachReportsData.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/updateFieldsListWithConditions */ "./src/utils/updateFieldsListWithConditions.js");




const useDataBreachReportsData = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  documentsLoaded: false,
  savedDocument: {},
  conclusions: [],
  region: '',
  fileName: '',
  fetching: false,
  updating: false,
  loadingFields: false,
  documents: [],
  regions: [],
  fields: [],
  editDocumentId: false,
  resetEditDocumentId: id => {
    set({
      editDocumentId: false,
      region: ''
    });
  },
  editDocument: async (e, id) => {
    e.preventDefault();
    set({
      updating: true
    });
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('load_databreach_report', {
      id: id
    }).then(response => {
      set({
        fields: response.fields,
        region: response.region,
        updating: false,
        fileName: response.file_name
      });
    }).catch(error => {
      console.error(error);
    });
    set({
      editDocumentId: id
    });
  },
  setRegion: region => {
    set({
      region: region
    });
  },
  updateField: (id, value) => {
    let found = false;
    let index = false;
    set((0,immer__WEBPACK_IMPORTED_MODULE_3__["default"])(state => {
      state.fields.forEach(function (fieldItem, i) {
        if (fieldItem.id === id) {
          index = i;
          found = true;
        }
      });
      if (index !== false) state.fields[index].value = value;
    }));
    let newFields = (0,_utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__.updateFieldsListWithConditions)(get().fields);
    set({
      fields: newFields
    });
  },
  save: async region => {
    set({
      updating: true
    });
    let postId = get().editDocumentId;
    let savedDocumentId = 0;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('save_databreach_report', {
      fields: get().fields,
      region: region,
      post_id: postId
    }).then(response => {
      savedDocumentId = response.post_id;
      set({
        updating: false,
        conclusions: response.conclusions
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
    await get().fetchData();
    let documents = get().documents;
    let savedDocuments = documents.filter(document => document.id === savedDocumentId);
    if (savedDocuments.length > 0) {
      set({
        savedDocument: savedDocuments[0]
      });
    }
  },
  deleteDocuments: async ids => {
    //get array of documents to delete
    let deleteDocuments = get().documents.filter(document => ids.includes(document.id));
    //remove the ids from the documents array
    set(state => ({
      documents: state.documents.filter(document => !ids.includes(document.id))
    }));
    let data = {};
    data.documents = deleteDocuments;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_databreach_report', data).then(response => {
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
      documents,
      regions
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_databreach_reports', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(state => ({
      documentsLoaded: true,
      documents: documents,
      regions: regions,
      fetching: false
    }));
  },
  fetchFields: async region => {
    let data = {
      region: region
    };
    set({
      loadingFields: true
    });
    const {
      fields
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_databreach_report_fields', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    let newFields = (0,_utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__.updateFieldsListWithConditions)(fields);
    set(state => ({
      fields: newFields,
      loadingFields: false
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDataBreachReportsData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_DataBreachReports_DataBreachReportsControl_js.js.map