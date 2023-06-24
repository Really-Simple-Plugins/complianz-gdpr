"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ProofOfConsent_useProofOfConsentData_js"],{

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
//# sourceMappingURL=src_Settings_ProofOfConsent_useProofOfConsentData_js.js.map