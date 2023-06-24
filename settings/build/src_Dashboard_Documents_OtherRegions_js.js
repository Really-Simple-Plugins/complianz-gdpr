"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Documents_OtherRegions_js"],{

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

/***/ }),

/***/ "./src/Dashboard/Documents/OtherRegions.js":
/*!*************************************************!*\
  !*** ./src/Dashboard/Documents/OtherRegions.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _DocumentsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./DocumentsData */ "./src/Dashboard/Documents/DocumentsData.js");



const SingleDocument = _ref => {
  let {
    document
  } = _ref;
  const {
    region
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  let regions = document.regions.filter(docRegion => docRegion !== region);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document-other-regions"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: document.readmore,
    target: "_blank"
  }, document.title), regions.map((region, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i,
    className: "cmplz-region-indicator"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    alt: region,
    width: "16px",
    height: "16px",
    src: cmplz_settings.plugin_url + "/assets/images/" + region + ".svg"
  }))))));
};
const OtherRegions = props => {
  const documents = [{
    id: 'privacy-statement',
    title: "Privacy Statements",
    regions: ['eu', 'us', 'uk', 'ca', 'za', 'au', 'br'],
    readmore: 'https://complianz.io/definition/what-is-a-privacy-statement/'
  }, {
    id: 'cookie-statement',
    title: "Cookie Policy",
    regions: ['eu', 'us', 'uk', 'ca', 'za', 'au', 'br'],
    readmore: ' https://complianz.io/definition/what-is-a-cookie-policy/'
  }, {
    id: 'impressum',
    title: "Impressum",
    regions: ['eu'],
    readmore: 'https://complianz.io/definition/what-is-an-impressum/'
  }, {
    id: 'do-not-sell-my-info',
    title: "Opt-out preferences",
    regions: ['us'],
    readmore: 'https://complianz.io/definition/what-is-do-not-sell-my-personal-information/'
  }, {
    id: 'privacy-statement-for-children',
    title: "Privacy Statement for Children",
    regions: ['us', 'uk', 'ca', 'za', 'au', 'br'],
    readmore: 'https://complianz.io/definition/what-is-a-privacy-statement-for-children/'
  }];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-document-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Other regions")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "https://complianz.io/features/",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Read more", "complianz-gdpr"))), documents.map((document, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SingleDocument, {
    key: i,
    document: document
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OtherRegions);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Documents_OtherRegions_js.js.map