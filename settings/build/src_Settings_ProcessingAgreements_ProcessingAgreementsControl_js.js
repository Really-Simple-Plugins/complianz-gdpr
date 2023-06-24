"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ProcessingAgreements_ProcessingAgreementsControl_js"],{

/***/ "./src/Settings/ProcessingAgreements/ProcessingAgreementsControl.js":
/*!**************************************************************************!*\
  !*** ./src/Settings/ProcessingAgreements/ProcessingAgreementsControl.js ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _ProcessingAgreementsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProcessingAgreementsData */ "./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);






const ProcessingAgreementsControl = () => {
  const {
    documents,
    documentsLoaded,
    fetchData,
    deleteDocuments,
    editDocument
  } = (0,_ProcessingAgreementsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [searchValue, setSearchValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
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
  const [btnDisabled, setBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [selectedDocuments, setSelectedDocuments] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [downloading, setDownloading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [DataTable, setDataTable] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    __webpack_require__.e(/*! import() */ "vendors-node_modules_react-data-table-component_dist_index_cjs_js").then(__webpack_require__.bind(__webpack_require__, /*! react-data-table-component */ "./node_modules/react-data-table-component/dist/index.cjs.js")).then(_ref => {
      let {
        default: DataTable
      } = _ref;
      setDataTable(() => DataTable);
    });
  }, []);
  const disabled = !cmplz_settings.is_premium;
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
    const downloadNext = async () => {
      if (selectedDocumentsCopy.length > 0) {
        const document = selectedDocumentsCopy.shift();
        const url = document.download_url;
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
              element.setAttribute('download', document.title);
              window.document.body.appendChild(element);
              //onClick property
              element.click();
              setSelectedDocuments(selectedDocumentsCopy);
              setDownloading(false);
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
    //search
    documents = documents.filter(document => {
      return document.title.toLowerCase().includes(searchValue.toLowerCase()) || document.service.toLowerCase().includes(searchValue.toLowerCase());
    });
    //sort
    documents.sort((a, b) => {
      if (a.title < b.title) {
        return -1;
      }
      if (a.title > b.title) {
        return 1;
      }
      return 0;
    });
    return documents;
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
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: btnDisabled,
    className: "button button-default cmplz-btn-reset",
    onClick: () => downloadDocuments()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Download Processing Agreement", "complianz-gdpr"), downloading && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "loading",
    color: "grey"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default cmplz-reset-button",
    onClick: () => onDeleteDocuments(selectedDocuments)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Delete", "complianz-gdpr")))), DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    columns: columns,
    data: data,
    dense: true,
    pagination: true,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No documents", "really-simple-ssl")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles,
    paginationPerPage: paginationPerPage,
    onChangePage: handlePageChange,
    paginationState: pagination
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(ProcessingAgreementsControl));

/***/ }),

/***/ "./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js":
/*!***********************************************************************!*\
  !*** ./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/updateFieldsListWithConditions */ "./src/utils/updateFieldsListWithConditions.js");




const useProcessingAgreementsData = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  documentsLoaded: false,
  region: '',
  fileName: '',
  serviceName: '',
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
      region: '',
      serviceName: ''
    });
  },
  editDocument: async (e, id) => {
    e.preventDefault();
    set({
      updating: true
    });
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('load_processing_agreement', {
      id: id
    }).then(response => {
      set({
        fields: response.fields,
        region: response.region,
        serviceName: response.serviceName,
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
  setServiceName: serviceName => {
    set({
      serviceName: serviceName
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
  save: async (region, serviceName) => {
    set({
      updating: true
    });
    let postId = get().editDocumentId;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('save_processing_agreement', {
      fields: get().fields,
      region: region,
      serviceName: serviceName,
      post_id: postId
    }).then(response => {
      set({
        updating: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
    get().fetchData();
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
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_processing_agreement', data).then(response => {
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
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_processing_agreements', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(() => ({
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
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_processing_agreement_fields', data).then(response => {
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useProcessingAgreementsData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_ProcessingAgreements_ProcessingAgreementsControl_js.js.map