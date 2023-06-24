"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Documents_DocumentsData_js"],{

/***/ "./src/Dashboard/Documents/DocumentsData.js":
/*!**************************************************!*\
  !*** ./src/Dashboard/Documents/DocumentsData.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const useDocuments = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  documents: [],
  documentDataLoaded: false,
  processingAgreementOptions: [],
  proofOfConsentOptions: [],
  dataBreachOptions: [],
  region: '',
  setRegion: region => {
    if (typeof Storage !== "undefined") {
      sessionStorage.cmplzSelectedRegion = region;
    }
    set(state => ({
      region: region
    }));
  },
  getRegion: () => {
    let region = 'all';
    if (typeof Storage !== "undefined") {
      if (sessionStorage.cmplzSelectedRegion) {
        region = sessionStorage.cmplzSelectedRegion;
      }
    }
    set(state => ({
      region: region
    }));
  },
  getDocuments: async () => {
    const {
      documents,
      processingAgreementOptions,
      proofOfConsentOptions,
      dataBreachOptions
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('documents_block_data').then(response => {
      return response;
    });
    set(state => ({
      documentDataLoaded: true,
      documents: documents,
      processingAgreementOptions: processingAgreementOptions,
      proofOfConsentOptions: proofOfConsentOptions,
      dataBreachOptions: dataBreachOptions
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDocuments);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Documents_DocumentsData_js.js.map