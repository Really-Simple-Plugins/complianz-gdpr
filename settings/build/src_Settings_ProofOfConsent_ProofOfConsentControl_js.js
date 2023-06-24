"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ProofOfConsent_ProofOfConsentControl_js"],{

/***/ "./src/Settings/ProofOfConsent/ProofOfConsentControl.js":
/*!**************************************************************!*\
  !*** ./src/Settings/ProofOfConsent/ProofOfConsentControl.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _useProofOfConsentData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./useProofOfConsentData */ "./src/Settings/ProofOfConsent/useProofOfConsentData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _ProofOfConsentControl_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ProofOfConsentControl.scss */ "./src/Settings/ProofOfConsent/ProofOfConsentControl.scss");
/* harmony import */ var _Inputs_Input_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Inputs/Input.scss */ "./src/Settings/Inputs/Input.scss");





// import * as Checkbox from '@radix-ui/react-checkbox';
// import Icon from '../../utils/Icon';


const ProofOfConsentControl = () => {
  const {
    documents,
    downloadUrl,
    deleteDocuments,
    documentsLoaded,
    fetchData
  } = (0,_useProofOfConsentData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [btnDisabled, setBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [selectedDocuments, setSelectedDocuments] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const disabled = !cmplz_settings.is_premium;
  const paginationPerPage = 10;
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
    setSelectedDocuments([]);
    const downloadNext = async () => {
      if (selectedDocumentsCopy.length > 0) {
        const document = selectedDocumentsCopy.shift();
        const url = downloadUrl + '/' + document.file;
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
              element.setAttribute('download', document.filename);
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
  const onSelectDocument = (selected, id) => {
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
    // //sort the plugins alphabetically by filename
    documents.sort((a, b) => {
      if (a.file < b.file) {
        return -1;
      }
      if (a.file > b.file) {
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
    // name: (<div className={'cmplz-checkbox-group'}>
    // 	<div className={'cmplz-checkbox-group__item'} >
    // 		<Checkbox.Root
    // 			className={indeterminate? 'cmplz-checkbox-group__checkbox indeterminate' : 'cmplz-checkbox-group__checkbox'}
    // 			onCheckedChange={handleSelectEntirePage}
    // 		>
    // 			<Checkbox.Indicator className="cmplz-checkbox-group__indicator">
    // 				{indeterminate && <Icon className={'bullet'} size={14} color={'dark-blue'} />}
    // 				{!indeterminate && entirePageSelected && 	<Icon name={'check'} size={14} color={'dark-blue'} />}
    // 			</Checkbox.Indicator>
    // 		</Checkbox.Root>
    // 	</div>
    // </div>),
    selector: row => row.selectControl
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Document', "complianz-gdpr"),
    selector: row => row.file,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Region', "complianz-gdpr"),
    selector: row => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      alt: "region",
      width: "20px",
      height: "20px",
      src: cmplz_settings.plugin_url + 'assets/images/' + row.region + '.svg'
    }),
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Consent', "complianz-gdpr"),
    selector: row => row.consent,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date', "complianz-gdpr"),
    selector: row => row.time,
    sortable: true
  }];
  let filteredDocuments = [...documents];
  filteredDocuments = handleFiltering(filteredDocuments);

  //add the controls to the plugins
  let data = [];
  filteredDocuments.forEach(document => {
    let documentCopy = {
      ...document
    };
    documentCopy.selectControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "checkbox",
      checked: selectedDocuments.includes(documentCopy.id),
      onChange: e => onSelectDocument(!selectedDocuments.includes(documentCopy.id), documentCopy.id)
    });
    // documentCopy.selectControl = (<div className={'cmplz-checkbox-group'}>
    // 	<div className={'cmplz-checkbox-group__item'} >
    // 		<Checkbox.Root
    // 			className={indeterminate? 'cmplz-checkbox-group__checkbox indeterminate' : 'cmplz-checkbox-group__checkbox'}
    // 			onCheckedChange={() => onSelectDocument(selectedDocuments.includes(documentCopy.id), documentCopy.id)}
    // 		>
    // 			<Checkbox.Indicator className="cmplz-checkbox-group__indicator">
    // 				{indeterminate && <Icon className={'check'} size={14} color={'dark-blue'} />}
    // 			</Checkbox.Indicator>
    // 		</Checkbox.Root>
    // 	</div>
    // </div>);
    data.push(documentCopy);
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, disabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay-message"
  }))), selectedDocuments.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, selectedDocuments.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("%s items selected", "complianz-gdpr").replace("%s", selectedDocuments.length), selectedDocuments.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("1 item selected", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: btnDisabled,
    className: "button button-default cmplz-btn-reset",
    onClick: () => downloadDocuments()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Download Proof of Consent", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default cmplz-reset-button",
    onClick: () => onDeleteDocuments(selectedDocuments)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Delete", "complianz-gdpr")))), !disabled && DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    className: "cmplz-data-table",
    columns: columns,
    data: data,
    dense: true,
    pagination: true,
    paginationPerPage: paginationPerPage,
    onChangePage: handlePageChange,
    paginationState: pagination,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No documents", "really-simple-ssl")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(ProofOfConsentControl));

/***/ }),

/***/ "./src/Settings/ProofOfConsent/useProofOfConsentData.js":
/*!**************************************************************!*\
  !*** ./src/Settings/ProofOfConsent/useProofOfConsentData.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const useProofOfConsentData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  documentsLoaded: false,
  fetching: false,
  generating: false,
  documents: [],
  downloadUrl: '',
  regions: [],
  fields: [],
  deleteDocuments: async ids => {
    //get array of documents to delete
    let deleteDocuments = get().documents.filter(document => ids.includes(document.id));
    //remove the ids from the documents array
    set(state => ({
      documents: state.documents.filter(document => !ids.includes(document.id))
    }));
    let data = {};
    data.documents = deleteDocuments;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_proof_of_consent_documents', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  generateProofOfConsent: async () => {
    set({
      generating: true
    });
    let data = {};
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('generate_proof_of_consent', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    await get().fetchData();
    set({
      generating: false
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
      regions,
      download_url
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_proof_of_consent_documents', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(state => ({
      documentsLoaded: true,
      documents: documents,
      regions: regions,
      downloadUrl: download_url,
      fetching: false
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useProofOfConsentData);

/***/ }),

/***/ "./src/Settings/Inputs/Input.scss":
/*!****************************************!*\
  !*** ./src/Settings/Inputs/Input.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/ProofOfConsent/ProofOfConsentControl.scss":
/*!****************************************************************!*\
  !*** ./src/Settings/ProofOfConsent/ProofOfConsentControl.scss ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_ProofOfConsent_ProofOfConsentControl_js.js.map