"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Documents_DocumentsHeader_js"],{

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

/***/ "./src/Dashboard/Documents/DocumentsHeader.js":
/*!****************************************************!*\
  !*** ./src/Dashboard/Documents/DocumentsHeader.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _DocumentsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./DocumentsData */ "./src/Dashboard/Documents/DocumentsData.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);






const DocumentsHeader = props => {
  const {
    getFieldValue,
    fieldsLoaded
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const {
    getRegion,
    setRegion,
    region
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    getRegion();
  }, []);
  if (!fieldsLoaded) {
    return null;
  }
  let regions = getFieldValue('regions');
  if (!Array.isArray(regions)) regions = [regions];
  if (regions.length === 0) regions = ['eu'];
  if (!regions) regions = [];
  //get labels from regions
  let regionsOptions = [];
  for (const region of regions) {
    if (!cmplz_settings.regions.hasOwnProperty(region)) {
      continue;
    }
    let item = {};
    item.label = cmplz_settings.regions[region]['label_full'];
    item.value = region;
    regionsOptions.push(item);
  }
  let item = {};
  item.label = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("General", "complianz-gdpr");
  item.value = 'all';
  regionsOptions.push(item);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Documents", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.SelectControl, {
    onChange: fieldValue => setRegion(fieldValue),
    value: region,
    options: regionsOptions
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DocumentsHeader);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Documents_DocumentsHeader_js.js.map