"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ProofOfConsent_CreateProofOfConsent_js"],{

/***/ "./src/Settings/ProofOfConsent/CreateProofOfConsent.js":
/*!*************************************************************!*\
  !*** ./src/Settings/ProofOfConsent/CreateProofOfConsent.js ***!
  \*************************************************************/
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
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);





const CreateProofOfConsent = _ref => {
  let {
    label,
    field
  } = _ref;
  const {
    generateProofOfConsent,
    generating
  } = (0,_useProofOfConsentData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-field-button'
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Create Proof of Consent", "complianz-gdpr"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: generating,
    className: "button button-default cmplz-field-button",
    onClick: () => generateProofOfConsent()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Generate", "complianz-gdpr"), generating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "loading",
    color: "grey"
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(CreateProofOfConsent));

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_ProofOfConsent_CreateProofOfConsent_js.js.map