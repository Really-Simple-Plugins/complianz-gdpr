"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_GridBlock_js"],{

/***/ "./src/Dashboard/Documents/DocumentsBlock.js":
/*!***************************************************!*\
  !*** ./src/Dashboard/Documents/DocumentsBlock.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _DocumentsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DocumentsData */ "./src/Dashboard/Documents/DocumentsData.js");
/* harmony import */ var _OtherRegions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OtherRegions */ "./src/Dashboard/Documents/OtherRegions.js");
/* harmony import */ var _OtherDocuments__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OtherDocuments */ "./src/Dashboard/Documents/OtherDocuments.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _OtherPlugins_OtherPluginsData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../OtherPlugins/OtherPluginsData */ "./src/Dashboard/OtherPlugins/OtherPluginsData.js");










const SingleDocument = props => {
  const {
    document
  } = props;
  const {
    showSavedSettingsNotice
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const {
    dataLoaded,
    pluginData,
    pluginActions,
    fetchOtherPluginsData,
    error
  } = (0,_OtherPlugins_OtherPluginsData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!dataLoaded) {
      fetchOtherPluginsData();
    }
  }, []);
  let missing = document.required && !document.exists;
  let syncColor = document.status === 'sync' ? 'green' : 'grey';
  let syncTooltip = document.status === 'sync' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Document is kept up to date by Complianz', 'complianz-gdpr') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Document is not kept up to date by Complianz', 'complianz-gdpr');
  let existsColor = document.exists ? 'green' : 'grey';
  let existsTooltip = document.exists ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Validated', 'complianz-gdpr') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Missing document', 'complianz-gdpr');
  let shortcodeTooltip = document.required ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Click to copy the document shortcode', 'complianz-gdpr') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Not enabled', 'complianz-gdpr');
  //if we have a not required document here, it exists, so is obsolete. Not existing docs are already filtered out.
  if (!document.required) {
    existsColor = syncColor = 'grey';
    existsTooltip = syncTooltip = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Not enabled', 'complianz-gdpr');
  }
  const onClickhandler = (e, shortcode) => {
    let success;
    e.target.classList.add('cmplz-click-animation');
    let temp = window.document.createElement("input");
    window.document.getElementsByTagName("body")[0].appendChild(temp);
    temp.value = shortcode;
    temp.select();
    try {
      success = window.document.execCommand("copy");
    } catch (e) {
      success = false;
    }
    temp.parentElement.removeChild(temp);
    if (success) {
      showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Copied shortcode", "complianz-gdpr"));
    }
  };
  let createLink = document.create_link ? document.create_link : '#wizard/manage-documents';
  let plugin = pluginData ? pluginData.find(plugin => plugin.slug === 'complianz-terms-conditions') : false;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document-title",
    key: 1
  }, document.permalink && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: document.permalink
  }, document.title), !document.permalink && document.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: 'sync',
    color: syncColor,
    tooltip: syncTooltip,
    size: 14,
    key: 2
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: 'circle-check',
    color: existsColor,
    tooltip: existsTooltip,
    size: 14,
    key: 3
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    onClick: e => onClickhandler(e, document.shortcode),
    key: 4
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: 'shortcode',
    color: existsColor,
    tooltip: shortcodeTooltip,
    size: 14
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document-generated",
    key: 5
  }, !document.install && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, document.readmore && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: document.readmore
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Read more", "complianz-gdpr"))), !document.readmore && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, !document.required && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Obsolete", "complianz-gdpr"), document.required && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, !missing && document.generated, missing && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: createLink
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Create", "complianz-gdpr"))))), plugin && document.install && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, plugin.pluginAction !== 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    onClick: e => pluginActions(plugin.slug, plugin.pluginAction, e)
  }, plugin.pluginActionNice), plugin.pluginAction === 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: plugin.create
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Create", "complianz-gdpr"))))));
};
const DocumentsBlock = () => {
  const {
    region,
    documentDataLoaded,
    getDocuments,
    documents
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [regionDocuments, setRegionDocuments] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const premiumDocuments = [{
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Privacy Statements", 'complianz-gdpr'),
    readmore: 'https://complianz.io/definition/what-is-a-privacy-statement/'
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Impressum", 'complianz-gdpr'),
    readmore: 'https://complianz.io/definition/what-is-an-impressum/'
  }];
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!documentDataLoaded) {
      getDocuments();
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let docs = documents.filter(document => document['region'] === region)[0];
    //filter out documents which do not exist AND are not required
    if (docs) {
      docs = docs['documents'];
      //docs = docs.filter( (document) => document.exists || document.required );
      if (!cmplz_settings.is_premium) {
        premiumDocuments.forEach(premiumDocument => {
          docs.push(premiumDocument);
        });
      }
      setRegionDocuments(docs);
    }
  }, [region, documents]);
  if (!documentDataLoaded) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_7__["default"], {
      lines: "3"
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, regionDocuments.map((document, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SingleDocument, {
    key: i,
    document: document
  })), !cmplz_settings.is_premium && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_OtherRegions__WEBPACK_IMPORTED_MODULE_2__["default"], null), cmplz_settings.is_premium && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_OtherDocuments__WEBPACK_IMPORTED_MODULE_3__["default"], null));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DocumentsBlock);

/***/ }),

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

/***/ "./src/Dashboard/Documents/DocumentsFooter.js":
/*!****************************************************!*\
  !*** ./src/Dashboard/Documents/DocumentsFooter.js ***!
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
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");



const DocumentsFooter = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'sync',
    color: "green",
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Synchronized', 'complianz-gdpr'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: "green",
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Validated', 'complianz-gdpr'))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DocumentsFooter);

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

/***/ }),

/***/ "./src/Dashboard/Documents/OtherDocuments.js":
/*!***************************************************!*\
  !*** ./src/Dashboard/Documents/OtherDocuments.js ***!
  \***************************************************/
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
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _SingleDocument__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SingleDocument */ "./src/Dashboard/Documents/SingleDocument.js");






const OtherDocuments = () => {
  const {
    getFieldValue,
    fields
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [recordsOfConsentEnabled, setRecordsOfConsentEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    setRecordsOfConsentEnabled(getFieldValue('records_of_consent'));
  }, [fields]);
  const {
    processingAgreementOptions,
    dataBreachOptions,
    proofOfConsentOptions
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Other documents")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SingleDocument__WEBPACK_IMPORTED_MODULE_4__["default"], {
    type: "processing-agreements",
    link: "#tools/processing-agreements",
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Processing Agreement", "complianz-gdpr"),
    options: processingAgreementOptions
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SingleDocument__WEBPACK_IMPORTED_MODULE_4__["default"], {
    type: "data-breaches",
    link: "#tools/data-breach-reports",
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Data Breach", "complianz-gdpr"),
    options: dataBreachOptions
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SingleDocument__WEBPACK_IMPORTED_MODULE_4__["default"], {
    type: "proof-of-consent",
    link: recordsOfConsentEnabled ? "#tools/records-of-consent" : "#tools/proof-of-consent",
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Proof of Consent", "complianz-gdpr"),
    options: proofOfConsentOptions
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OtherDocuments);

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

/***/ }),

/***/ "./src/Dashboard/Documents/SingleDocument.js":
/*!***************************************************!*\
  !*** ./src/Dashboard/Documents/SingleDocument.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);





const SingleDocument = props => {
  const [url, setUrl] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [options, setOptions] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let options = props.options;
    if (options.length === 0) {
      let emptyOption = {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Generate a %s", "complianz-gdpr").replace('%s', props.name),
        value: 0
      };
      options.unshift(emptyOption);
    } else {
      //if options does not include an option with value 0, add it.
      if (!options.filter(option => option.value === 0).length > 0) {
        let emptyOption = {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Select a %s", "complianz-gdpr").replace('%s', props.name),
          value: 0
        };
        options.unshift(emptyOption);
      }
    }
    setOptions(options);
  }, [props.options]);
  const Download = () => {
    if (disabled) return;
    setDisabled(true);
    let request = new XMLHttpRequest();
    request.responseType = 'blob';
    request.open('get', url, true);
    request.send();
    request.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var obj = window.URL.createObjectURL(this.response);
        var element = window.document.createElement('a');
        element.setAttribute('href', obj);
        element.setAttribute('download', options.filter(option => option.value === url)[0].label);
        window.document.body.appendChild(element);
        //onClick property
        element.click();
        setTimeout(function () {
          window.URL.revokeObjectURL(obj);
        }, 60 * 1000);
      }
    };
    request.onprogress = function (e) {
      setDisabled(true);
    };
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document-other-documents"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    onChange: fieldValue => setUrl(fieldValue),
    value: url,
    options: options
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    onClick: () => Download()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'file-download',
    color: url != 0 && !disabled ? 'black' : 'grey',
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Download file", "complianz-gdpr"),
    size: 14
  })), options.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: props.link
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-chevron-right',
    color: "black",
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Go to overview", "complianz-gdpr"),
    size: 14
  })), options.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: props.link
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'plus',
    color: "black",
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Create new", "complianz-gdpr"),
    size: 14
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SingleDocument);

/***/ }),

/***/ "./src/Dashboard/GridBlock.js":
/*!************************************!*\
  !*** ./src/Dashboard/GridBlock.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Progress_ProgressBlock__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Progress/ProgressBlock */ "./src/Dashboard/Progress/ProgressBlock.js");
/* harmony import */ var _Progress_ProgressBlockHeader__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Progress/ProgressBlockHeader */ "./src/Dashboard/Progress/ProgressBlockHeader.js");
/* harmony import */ var _Progress_ProgressFooter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Progress/ProgressFooter */ "./src/Dashboard/Progress/ProgressFooter.js");
/* harmony import */ var _Documents_DocumentsBlock__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Documents/DocumentsBlock */ "./src/Dashboard/Documents/DocumentsBlock.js");
/* harmony import */ var _Documents_DocumentsHeader__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Documents/DocumentsHeader */ "./src/Dashboard/Documents/DocumentsHeader.js");
/* harmony import */ var _Documents_DocumentsFooter__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Documents/DocumentsFooter */ "./src/Dashboard/Documents/DocumentsFooter.js");
/* harmony import */ var _Tools_Tools__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./Tools/Tools */ "./src/Dashboard/Tools/Tools.js");
/* harmony import */ var _Tools_ToolsHeader__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Tools/ToolsHeader */ "./src/Dashboard/Tools/ToolsHeader.js");
/* harmony import */ var _Tools_ToolsFooter__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./Tools/ToolsFooter */ "./src/Dashboard/Tools/ToolsFooter.js");
/* harmony import */ var _OtherPlugins_OtherPlugins__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./OtherPlugins/OtherPlugins */ "./src/Dashboard/OtherPlugins/OtherPlugins.js");
/* harmony import */ var _TipsTricks_TipsTricks__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./TipsTricks/TipsTricks */ "./src/Dashboard/TipsTricks/TipsTricks.js");
/* harmony import */ var _TipsTricks_TipsTricksFooter__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./TipsTricks/TipsTricksFooter */ "./src/Dashboard/TipsTricks/TipsTricksFooter.js");
/* harmony import */ var _OtherPlugins_OtherPluginsHeader__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./OtherPlugins/OtherPluginsHeader */ "./src/Dashboard/OtherPlugins/OtherPluginsHeader.js");
















/*
 * Mapping of components, for use in the config array
 * @type {{SslLabs: JSX.Element}}
 */
var dynamicComponents = {
  "ProgressBlock": _Progress_ProgressBlock__WEBPACK_IMPORTED_MODULE_2__["default"],
  "ProgressHeader": _Progress_ProgressBlockHeader__WEBPACK_IMPORTED_MODULE_3__["default"],
  "ProgressFooter": _Progress_ProgressFooter__WEBPACK_IMPORTED_MODULE_4__["default"],
  "DocumentsBlock": _Documents_DocumentsBlock__WEBPACK_IMPORTED_MODULE_5__["default"],
  "DocumentsHeader": _Documents_DocumentsHeader__WEBPACK_IMPORTED_MODULE_6__["default"],
  "DocumentsFooter": _Documents_DocumentsFooter__WEBPACK_IMPORTED_MODULE_7__["default"],
  "TipsTricks": _TipsTricks_TipsTricks__WEBPACK_IMPORTED_MODULE_12__["default"],
  "TipsTricksFooter": _TipsTricks_TipsTricksFooter__WEBPACK_IMPORTED_MODULE_13__["default"],
  "ToolsHeader": _Tools_ToolsHeader__WEBPACK_IMPORTED_MODULE_9__["default"],
  "ToolsFooter": _Tools_ToolsFooter__WEBPACK_IMPORTED_MODULE_10__["default"],
  "Tools": _Tools_Tools__WEBPACK_IMPORTED_MODULE_8__["default"],
  "OtherPluginsHeader": _OtherPlugins_OtherPluginsHeader__WEBPACK_IMPORTED_MODULE_14__["default"],
  "OtherPlugins": _OtherPlugins_OtherPlugins__WEBPACK_IMPORTED_MODULE_11__["default"]
};
const GridBlock = props => {
  const blockData = props.block;
  const className = "cmplz-grid-item " + blockData.class + " cmplz-" + blockData.id;
  const footer = props.block.footer ? props.block.footer.data : false;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: "block-" + blockData.id,
    className: className
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-header"
  }, blockData.header.type === 'text' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, blockData.header.data), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, blockData.controls && blockData.controls.type === 'url' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: blockData.controls.data
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Instructions", "complianz-gdpr")), blockData.controls && blockData.controls.type === 'react' && wp.element.createElement(dynamicComponents[blockData.controls.data]))), blockData.header.type === 'react' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, wp.element.createElement(dynamicComponents[blockData.header.data]))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-content"
  }, wp.element.createElement(dynamicComponents[props.block.content.data])), !footer && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-footer"
  }), footer && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-footer"
  }, wp.element.createElement(dynamicComponents[props.block.footer.data])));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (GridBlock);

/***/ }),

/***/ "./src/Dashboard/OtherPlugins/OtherPlugins.js":
/*!****************************************************!*\
  !*** ./src/Dashboard/OtherPlugins/OtherPlugins.js ***!
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
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _OtherPluginsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OtherPluginsData */ "./src/Dashboard/OtherPlugins/OtherPluginsData.js");





const OtherPlugins = () => {
  const {
    dataLoaded,
    pluginData,
    pluginActions,
    fetchOtherPluginsData,
    error
  } = (0,_OtherPluginsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!dataLoaded) {
      fetchOtherPluginsData();
    }
  }, []);
  const otherPluginElement = (plugin, i) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: "plugin" + i,
      className: "cmplz-other-plugins-element cmplz-" + plugin.slug
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: plugin.wordpress_url,
      target: "_blank",
      title: plugin.title
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-bullet"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-other-plugins-content"
    }, plugin.title)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-other-plugin-status"
    }, plugin.pluginAction === 'upgrade-to-premium' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      target: "_blank",
      href: plugin.upgrade_url
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Upgrade", "complianz-gdpr"))), plugin.pluginAction !== 'upgrade-to-premium' && plugin.pluginAction !== 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: "#",
      onClick: e => pluginActions(plugin.slug, plugin.pluginAction, e)
    }, plugin.pluginActionNice)), plugin.pluginAction === 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Installed", "complianz-gdpr"))));
  };
  if (!dataLoaded || error) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__["default"], {
      lines: "3",
      error: error
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-other-plugins-container"
  }, pluginData.map((plugin, i) => otherPluginElement(plugin, i))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OtherPlugins);

/***/ }),

/***/ "./src/Dashboard/OtherPlugins/OtherPluginsData.js":
/*!********************************************************!*\
  !*** ./src/Dashboard/OtherPlugins/OtherPluginsData.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);



const useOtherPlugins = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  error: false,
  dataLoaded: false,
  pluginData: [],
  updatePluginData: (slug, newPluginItem) => {
    let pluginData = get().pluginData;
    pluginData.forEach(function (pluginItem, i) {
      if (pluginItem.slug === slug) {
        pluginData[i] = newPluginItem;
      }
    });
    set(state => ({
      dataLoaded: true,
      pluginData: pluginData
    }));
  },
  getPluginData: slug => {
    let pluginData = get().pluginData;
    return pluginData.filter(pluginItem => {
      return pluginItem.slug === slug;
    })[0];
  },
  fetchOtherPluginsData: async () => {
    const {
      pluginData,
      error
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('otherpluginsdata').then(response => {
      let pluginData = [];
      pluginData = response.plugins;
      let error = response.error;
      if (!error) {
        pluginData.forEach(function (pluginItem, i) {
          pluginData[i].pluginActionNice = pluginActionNice(pluginItem.pluginAction);
        });
      }
      return {
        pluginData,
        error
      };
    });
    set(state => ({
      dataLoaded: true,
      pluginData: pluginData,
      error: error
    }));
  },
  pluginActions: (slug, pluginAction, e) => {
    if (e) e.preventDefault();
    let data = {};
    data.slug = slug;
    data.pluginAction = pluginAction;
    let pluginItem = get().getPluginData(slug);
    if (pluginAction === 'download') {
      pluginItem.pluginAction = "downloading";
    } else if (pluginAction === 'activate') {
      pluginItem.pluginAction = "activating";
    }
    pluginItem.pluginActionNice = pluginActionNice(pluginItem.pluginAction);
    get().updatePluginData(slug, pluginItem);
    if (pluginAction === 'installed' || pluginAction === 'upgrade-to-premium') {
      return;
    }
    _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('plugin_actions', data).then(response => {
      pluginItem = response;
      get().updatePluginData(slug, pluginItem);
      get().pluginActions(slug, pluginItem.pluginAction);
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useOtherPlugins);
const pluginActionNice = pluginAction => {
  const statuses = {
    'download': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Install", "really-simple-ssl"),
    'activate': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Activate", "really-simple-ssl"),
    'activating': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Activating...", "really-simple-ssl"),
    'downloading': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Downloading...", "really-simple-ssl"),
    'upgrade-to-premium': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Downloading...", "really-simple-ssl")
  };
  return statuses[pluginAction];
};

/***/ }),

/***/ "./src/Dashboard/OtherPlugins/OtherPluginsHeader.js":
/*!**********************************************************!*\
  !*** ./src/Dashboard/OtherPlugins/OtherPluginsHeader.js ***!
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


const OtherPluginsHeader = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Other Plugins", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "rsp-logo",
    href: "https://really-simple-plugins.com/"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: cmplz_settings.plugin_url + 'assets/images/really-simple-plugins.svg',
    alt: "Really Simple Plugins"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OtherPluginsHeader);

/***/ }),

/***/ "./src/Dashboard/Progress/ProgressBlock.js":
/*!*************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressBlock.js ***!
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
/* harmony import */ var _TaskElement__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../TaskElement */ "./src/Dashboard/TaskElement.js");
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _ProgressData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");







const ProgressBlock = () => {
  const {
    percentageCompleted,
    filter,
    notices,
    progressLoaded,
    fetchProgressData,
    error,
    addNotice
  } = (0,_ProgressData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    fetchAllFieldsCompleted,
    allRequiredFieldsCompleted,
    fields
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const run = async () => {
      if (!progressLoaded) {
        await fetchProgressData();
      }
      fetchAllFieldsCompleted();
    };
    run();
  }, [filter, fields]);
  const getStyles = () => {
    return Object.assign({}, {
      width: percentageCompleted + "%"
    });
  };
  let progressBarColor = '';
  if (percentageCompleted < 80) {
    progressBarColor += 'cmplz-orange';
  }
  if (!progressLoaded || error) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_3__["default"], {
      lines: "9",
      error: error
    });
  }
  let noticesOutput = notices;
  if (filter === 'remaining') {
    noticesOutput = noticesOutput.filter(function (notice) {
      return notice.status !== 'completed';
    });
  }
  if (!allRequiredFieldsCompleted && noticesOutput.filter(notice => notice.id === 'all_fields_completed').length === 0) {
    let notice = {
      id: 'all_fields_completed',
      status: 'urgent',
      message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz-gdpr')
    };
    noticesOutput.push(notice);
  }
  if (allRequiredFieldsCompleted) {
    noticesOutput = noticesOutput.filter(notice => notice.id !== 'all_fields_completed');
  }

  //sorting by status
  noticesOutput.sort(function (a, b) {
    if (a.status === b.status) {
      return 0;
    } else {
      return a.status < b.status ? 1 : -1;
    }
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-block"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-bar"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-bar ' + progressBarColor,
    style: getStyles()
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-text"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", {
    className: "cmplz-progress-percentage"
  }, percentageCompleted, "%"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", {
    className: "cmplz-progress-text-span"
  }, percentageCompleted < 100 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Consent Management is activated on your site.', 'complianz-gdpr') + ' ', percentageCompleted < 100 && notices.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('You still have 1 task open.', 'complianz-gdpr'), percentageCompleted < 100 && notices.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('You still have %s tasks open.', 'complianz-gdpr').replace('%s', notices.length), percentageCompleted === 100 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Well done! Your website is ready for your selected regions.', 'complianz-gdpr'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-scroll-container"
  }, noticesOutput.map((notice, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_TaskElement__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: i,
    notice: notice
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressBlock);

/***/ }),

/***/ "./src/Dashboard/Progress/ProgressBlockHeader.js":
/*!*******************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressBlockHeader.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _ProgressData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ProgressData */ "./src/Dashboard/Progress/ProgressData.js");




const ProgressHeader = () => {
  const {
    setFilter,
    filter,
    fetchFilter,
    notices,
    progressLoaded
  } = (0,_ProgressData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchFilter();
  }, []);
  let all_task_count = 0;
  let open_task_count = 0;
  all_task_count = progressLoaded ? notices.length : 0;
  let openNotices = notices.filter(function (notice) {
    return notice.status !== 'completed';
  });
  open_task_count = openNotices.length;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Progress", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-switcher-container cmplz-active-filter-" + filter
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    className: "cmplz-task-switcher cmplz-all-tasks",
    onClick: () => setFilter('all'),
    "data-filter": "all"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("All tasks", "really-simple-ssl"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rsssl_task_count"
  }, "(", all_task_count, ")")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    className: "cmplz-task-switcher cmplz-remaining-tasks",
    onClick: () => setFilter('remaining'),
    "data-filter": "remaining"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Remaining tasks", "really-simple-ssl"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rsssl_task_count"
  }, "(", open_task_count, ")")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressHeader);

/***/ }),

/***/ "./src/Dashboard/Progress/ProgressFooter.js":
/*!**************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressFooter.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");





const ProgressFooter = props => {
  // const {setShowOnBoardingModal} = useOnboardingData();
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [cookieBlockerColor, setCookieBlockerColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [placeholderColor, setPlaceholderColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [cookieBannerColor, setCookieBannerColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let color = getFieldValue('enable_cookie_blocker') === 'yes' ? 'green' : 'grey';
    setCookieBlockerColor(color);
    color = getFieldValue('dont_use_placeholders') == 1 ? 'grey' : 'green';
    setPlaceholderColor(color);
    color = getFieldValue('enable_cookie_banner') === 'yes' ? 'green' : 'grey';
    setCookieBannerColor(color);
  }, [fields]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#wizard",
    className: "button button-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Continue Wizard", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend cmplz-flex-push-right"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: cookieBlockerColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Cookie Blocker", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: placeholderColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Placeholders", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: cookieBannerColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Cookie Banner", "complianz-gdpr"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressFooter);

/***/ }),

/***/ "./src/Dashboard/TaskElement.js":
/*!**************************************!*\
  !*** ./src/Dashboard/TaskElement.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_sleeper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/sleeper */ "./src/utils/sleeper.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Progress/ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");









const TaskElement = props => {
  const {
    dismissNotice,
    fetchProgressData
  } = (0,_Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__["default"])();
  const {
    getField,
    setHighLightField,
    fetchFieldsData
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const {
    setSelectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  const handleClick = async () => {
    setHighLightField(props.notice.highlight_field_id);
    let highlightField = getField(props.notice.highlight_field_id);
    await setSelectedSubMenuItem(highlightField.menu_id);
  };
  const handleClearCache = async cache_id => {
    let data = {};
    data.cache_id = cache_id;
    _utils_api__WEBPACK_IMPORTED_MODULE_4__.doAction('clear_cache', data).then(async response => {
      const notice = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').createNotice('success', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Re-started test', 'complianz-gdpr'), {
        __unstableHTML: true,
        id: 'cmplz_clear_cache',
        type: 'snackbar',
        isDismissible: true
      }).then((0,_utils_sleeper__WEBPACK_IMPORTED_MODULE_5__["default"])(3000)).then(response => {
        (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').removeNotice('rsssl_clear_cache');
      });
      await fetchFieldsData();
      await fetchProgressData();
    });
  };
  let notice = props.notice;
  let premium = notice.icon === 'premium';
  //treat links to complianz.io and internal links different.
  let urlIsExternal = notice.url && notice.url.indexOf('complianz.io') !== -1;
  let statusNice = notice.status.charAt(0).toUpperCase() + notice.status.slice(1);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: 'cmplz-task-status cmplz-' + notice.status
  }, statusNice), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "cmplz-task-message",
    dangerouslySetInnerHTML: {
      __html: notice.message
    }
  }), urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    target: "_blank",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("More info", "complianz-gdpr")), notice.clear_cache_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClearCache(notice.clear_cache_id)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Re-check", "complianz-gdpr")), !premium && !urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "cmplz-task-enable button button-secondary",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), !premium && notice.highlight_field_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClick()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), notice.plusone && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-plusone"
  }, "1"), notice.dismissible && notice.status !== 'completed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-dismiss"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    onClick: e => dismissNotice(notice.id)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "times"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TaskElement);

/***/ }),

/***/ "./src/Dashboard/TipsTricks/TipsTricks.js":
/*!************************************************!*\
  !*** ./src/Dashboard/TipsTricks/TipsTricks.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const Tip = _ref => {
  let {
    link,
    content
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: link,
    target: "_blank",
    title: "{content}"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-icon"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    "aria-hidden": "true",
    focusable: "false",
    role: "img",
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 512 512",
    height: "15"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fill: "var(--rsp-grey-300)",
    d: "M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-content"
  }, content)));
};
const TipsTricks = () => {
  const items = [{
    content: "Styling your cookie notice and legal documents",
    link: 'https://complianz.io/docs/customization/'
  }, {
    content: "Why plugins are better in consent management",
    link: 'https://complianz.io/consent-management-wordpress-native-plugin-versus-cloud-solution/'
  }, {
    content: "Configure Tag Manager with Complianz",
    link: 'https://complianz.io/definitive-guide-to-tag-manager-and-complianz/'
  }, {
    content: "Self-hosting Google Fonts",
    link: 'https://complianz.io/self-hosting-google-fonts-for-wordpress/'
  }, {
    content: "Translating your cookie notice and legal documents",
    link: 'https://complianz.io/?s=translations&lang=en'
  }, {
    content: "Debugging issues with Complianz",
    link: 'https://complianz.io/debugging-issues/'
  }];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-container"
  }, items.map((item, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Tip, {
    key: "trick-" + i,
    link: item.link,
    content: item.content
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TipsTricks);

/***/ }),

/***/ "./src/Dashboard/TipsTricks/TipsTricksFooter.js":
/*!******************************************************!*\
  !*** ./src/Dashboard/TipsTricks/TipsTricksFooter.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


const TipsTricksFooter = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "https://complianz.io/docs/",
    className: "button button-default cmplz-flex-push-left",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('View all', 'complianz-gdpr'));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TipsTricksFooter);

/***/ }),

/***/ "./src/Dashboard/Tools/Statistics.js":
/*!*******************************************!*\
  !*** ./src/Dashboard/Tools/Statistics.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Statistics_StatisticsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Statistics/StatisticsData */ "./src/Statistics/StatisticsData.js");





const Statistics = () => {
  const [data, setData] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [total, setTotal] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(1);
  const [fullConsent, setFullConsent] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const [noConsent, setNoConsent] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const {
    consentType,
    statisticsData,
    loaded,
    fetchStatisticsData,
    labels,
    setLabels
  } = (0,_Statistics_StatisticsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!loaded && cmplz_settings.is_premium) {
      fetchStatisticsData();
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (consentType === '' || !loaded) {
      return;
    }
    if (!statisticsData || !statisticsData.hasOwnProperty(consentType)) {
      return;
    }
    let temp = [...statisticsData[consentType]['labels']];
    //get categories
    let categories = statisticsData[consentType]['categories'];

    //if it's optin, slice these indexes from the labels.
    if (consentType === 'optin') {
      categories = categories.filter(category => category === 'functional' || category === 'no_warning' || category === 'do_not_track');
    } else {
      //get array of indexes for categories functional, marketing, statistics, preferences
      categories = categories.filter(category => category === 'functional' || category === 'marketing' || category === 'statistics' || category === 'preferences');
    }

    //get indexes for these categories
    let categoryIndexes = categories.map(category => statisticsData[consentType]['categories'].indexOf(category));
    //remove these indexes from the labels array
    for (let i = categoryIndexes.length - 1; i >= 0; i--) {
      temp.splice(categoryIndexes[i], 1);
    }
    setLabels(temp);
  }, [loaded, consentType]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (consentType === '' || !loaded || !statisticsData) {
      return;
    }
    let data = statisticsData[consentType]['datasets'];
    //get the dataset with default flag
    let defaultDatasets = data.filter(dataset => dataset.default);
    if (defaultDatasets.length > 0) {
      let defaultDataset = defaultDatasets[0]['data'];
      //sum all values of the default dataset
      let total = defaultDataset.reduce((a, b) => parseInt(a) + parseInt(b), 0);
      total = total > 0 ? total : 1;
      setTotal(total);
      setFullConsent(defaultDatasets[0].full_consent);
      setNoConsent(defaultDatasets[0].no_consent);
      defaultDataset = defaultDataset.slice(2);
      setData(defaultDataset);
    }
  }, [loaded, consentType]);
  const getPercentage = value => {
    value = parseInt(value);
    return Math.round(value / total * 100);
  };
  const getRowIcon = index => {
    let name = 'dial-med-low-light';
    if (index === 1) {
      name = 'dial-med-light';
    } else if (index === 2) {
      name = 'dial-light';
    } else if (index === 3) {
      name = 'dial-off-light';
    } else if (index === 4) {
      name = 'dial-min-light';
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      name: name,
      color: "black"
    }));
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tools-statistics"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-statistics-container"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-main-consent"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-main-consent-count cmplz-full-consent"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: "dial-max-light",
    color: "green",
    size: "22"
  }), fullConsent, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Full Consent", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-main-consent-count  cmplz-no-consent"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: "dial-min-light",
    color: "red",
    size: "22"
  }), noConsent, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No Consent", "complianz-gdpr"))))), labels.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-icon"
  }, getRowIcon(0)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-name"
  }, "..."), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-data"
  }, "0%"))), labels.length > 0 && labels.map((label, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: index,
    className: "cmplz-details"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-icon"
  }, getRowIcon(index)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-name"
  }, label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-detail-data"
  }, data.hasOwnProperty(index) ? getPercentage(data[index]) : 0, "%")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Statistics);

/***/ }),

/***/ "./src/Dashboard/Tools/ToolItem.js":
/*!*****************************************!*\
  !*** ./src/Dashboard/Tools/ToolItem.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Settings_Integrations_IntegrationsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Settings/Integrations/IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../Settings/License/LicenseData */ "./src/Settings/License/LicenseData.js");







const ToolItem = props => {
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [fieldEnabled, setFieldEnabled] = (0,react__WEBPACK_IMPORTED_MODULE_4__.useState)(false);
  const {
    integrationsLoaded,
    plugins,
    fetchIntegrationsData
  } = (0,_Settings_Integrations_IntegrationsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const {
    licenseStatus
  } = (0,_Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  (0,react__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
    let item = props.item;
    if (item.field) {
      let enabled = getFieldValue(item.field.name) == item.field.value;
      setFieldEnabled(enabled);
    }
  }, [fields]);
  (0,react__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
    if (!integrationsLoaded) {
      fetchIntegrationsData();
    }
  }, []);
  let item = props.item;
  //linked to a plugin, e.g. woocommerce
  if (item.plugin) {
    let pluginActive = plugins.filter(plugin => plugin.id === item.plugin).length > 0;
    if (!pluginActive) return null;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-tool"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-tool-title"
    }, item.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-tool-link"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: item.link,
      target: "_blank"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      name: 'circle-chevron-right',
      color: "black",
      size: 14
    }))));
  }

  //not a plugin condition.
  let isPremiumUser = cmplz_settings.is_premium && licenseStatus === 'valid';
  let linkText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Read more", "complianz-gdpr");
  let link = item.link;
  if (isPremiumUser) {
    if (!fieldEnabled && item.enableLink) {
      linkText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Enable", "complianz-gdpr");
      link = item.enableLink;
    }
    if ((!item.field || fieldEnabled) && item.viewLink) {
      linkText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("View", "complianz-gdpr");
      link = item.viewLink;
    }
  }
  let isExternal = link.indexOf('https://') !== -1;
  let target = isExternal ? '_blank' : '_self';
  let icon = isExternal ? 'external-link' : 'circle-chevron-right';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tool"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tool-title"
  }, item.title, item.plusone && item.plusone), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tool-link"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: link,
    target: target
  }, linkText, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: icon,
    color: "black",
    size: 14
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ToolItem);

/***/ }),

/***/ "./src/Dashboard/Tools/Tools.js":
/*!**************************************!*\
  !*** ./src/Dashboard/Tools/Tools.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Settings_DataRequests_useDatarequestsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Settings/DataRequests/useDatarequestsData */ "./src/Settings/DataRequests/useDatarequestsData.js");
/* harmony import */ var _Statistics__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Statistics */ "./src/Dashboard/Tools/Statistics.js");
/* harmony import */ var _ToolItem__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./ToolItem */ "./src/Dashboard/Tools/ToolItem.js");

// import useTools from "../Tools/ToolsData";






const PlusOnes = props => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-plusone"
  }, props.count);
};
const Tools = () => {
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [consentStatisticsEnabled, setConsentStatisticsEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [abTestingEnabled, setAbTestingEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const {
    recordsLoaded,
    fetchData,
    totalOpen
  } = (0,_Settings_DataRequests_useDatarequestsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!recordsLoaded) {
      fetchData(10, 1, 'ID', 'ASC');
    }
  }, [recordsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let consentStats = getFieldValue('a_b_testing') == 1;
    setConsentStatisticsEnabled(consentStats);
    let ab = getFieldValue('a_b_testing_buttons') == 1;
    setAbTestingEnabled(ab);
  }, [fields]);
  const tools = [{
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Data requests", "complianz-gdpr"),
    viewLink: "#tools/data-requests",
    enableLink: "#wizard/security-consent",
    field: {
      name: "datarequest",
      value: 'yes'
    },
    link: "https://complianz.io/definition/what-is-a-data-request/",
    plusone: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PlusOnes, {
      count: totalOpen
    })
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Records of Consent", "complianz-gdpr"),
    viewLink: "#tools/records-of-consent",
    enableLink: "#wizard/security-consent",
    field: {
      name: "records_of_consent",
      value: 'yes'
    },
    link: "https://complianz.io/records-of-consent/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Processing agreements", "complianz-gdpr"),
    viewLink: "#tools/processing-agreements",
    link: "https://complianz.io/definition/what-is-a-processing-agreement/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Statistics", "complianz-gdpr"),
    viewLink: "#tools/ab-testing",
    link: "https://complianz.io/a-quick-introduction-to-a-b-testing/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("A/B Testing", "complianz-gdpr"),
    viewLink: "#tools/ab-testing",
    link: "https://complianz.io/a-quick-introduction-to-a-b-testing/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Documentation", "complianz-gdpr"),
    link: "https://complianz.io/support/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Premium Support", "complianz-gdpr"),
    viewLink: "https://complianz.io/support/",
    link: "https://complianz.io/about-premium-support/"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("WooCommerce", "complianz-gdpr"),
    plugin: "woocommerce",
    link: cmplz_settings.admin_url + 'admin.php?page=wc-settings&tab=account'
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Multisite", "complianz-gdpr"),
    link: "#tools/multisite",
    viewLink: "#tools/multisite"
  }, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Security", "complianz-gdpr"),
    link: "#tools/security",
    viewLink: "#tools/security"
  }];
  if (consentStatisticsEnabled) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Statistics__WEBPACK_IMPORTED_MODULE_4__["default"], {
      abTestingEnabled: abTestingEnabled
    }));
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, tools.map((item, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ToolItem__WEBPACK_IMPORTED_MODULE_5__["default"], {
    key: i,
    item: item
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tools);

/***/ }),

/***/ "./src/Dashboard/Tools/ToolsFooter.js":
/*!********************************************!*\
  !*** ./src/Dashboard/Tools/ToolsFooter.js ***!
  \********************************************/
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




const ToolsFooter = () => {
  return null;
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [abTestingEnabled, setAbTestingEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);

  // useEffect (() => {
  // 	let ab = getFieldValue('use_country')==1 && getFieldValue('a_b_testing_buttons')==1;
  // 	setAbTestingEnabled(ab);
  // },[fields])

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, abTestingEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("What does it mean? - ", "complianz-gdpr"), ";", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "https://really-simple-ssl.com/instructions/lorem-ipsum/",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Read more", "complianz-gdpr"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ToolsFooter);

/***/ }),

/***/ "./src/Dashboard/Tools/ToolsHeader.js":
/*!********************************************!*\
  !*** ./src/Dashboard/Tools/ToolsHeader.js ***!
  \********************************************/
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
/* harmony import */ var _Statistics_StatisticsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Statistics/StatisticsData */ "./src/Statistics/StatisticsData.js");





const ToolsHeader = () => {
  const {
    consentType,
    setConsentType,
    consentTypes,
    fetchStatisticsData,
    loaded
  } = (0,_Statistics_StatisticsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [consentStatisticsEnabled, setConsentStatisticsEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let consentStats = getFieldValue('a_b_testing') == 1;
    setConsentStatisticsEnabled(consentStats);
  }, [getFieldValue('a_b_testing')]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (consentStatisticsEnabled && !loaded) {
      fetchStatisticsData();
    }
  }, [consentStatisticsEnabled]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, consentStatisticsEnabled && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Statistics", 'complianz-gdpr'), !consentStatisticsEnabled && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Tools", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, consentStatisticsEnabled && consentTypes && consentTypes.length > 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    onChange: e => setConsentType(e.target.value),
    value: consentType
  }, consentTypes.map((type, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: i,
    value: type.id
  }, type.label)))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ToolsHeader);

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

/***/ }),

/***/ "./src/Settings/Integrations/IntegrationsData.js":
/*!*******************************************************!*\
  !*** ./src/Settings/Integrations/IntegrationsData.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");



const useIntegrations = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  integrationsLoaded: false,
  fetching: false,
  services: [],
  plugins: [],
  scripts: [],
  placeholders: [],
  blockedScripts: [],
  setScript: (script, type) => {
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      //update blocked scripts options list if new urls were added.
      if (type === 'block_script') {
        let options = state.blockedScripts;
        if (script.urls) {
          for (const [index, url] of Object.entries(script.urls)) {
            if (!url || url.length === 0) continue;
            //check if url exists in the options object
            let found = false;
            for (const [optionIndex, optionValue] of Object.entries(options)) {
              if (url === optionIndex) found = true;
            }
            if (!found) {
              options[url] = url;
            }
          }
          state.blockedScripts = options;
        }
      }
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      if (index !== -1) state.scripts[type][index] = script;
    }));
  },
  fetchIntegrationsData: async () => {
    if (get().fetching) return;
    set({
      fetching: true
    });
    const {
      services,
      plugins,
      scripts,
      placeholders,
      blocked_scripts
    } = await fetchData();
    let scriptsWithId = scripts;
    //add a unique id to each script
    scriptsWithId.block_script.forEach((script, i) => {
      script.id = i;
    });
    scriptsWithId.add_script.forEach((script, i) => {
      script.id = i;
    });
    scriptsWithId.whitelist_script.forEach((script, i) => {
      script.id = i;
    });
    set(() => ({
      integrationsLoaded: true,
      services: services,
      plugins: plugins,
      scripts: scriptsWithId,
      fetching: false,
      placeholders: placeholders,
      blockedScripts: blocked_scripts
    }));
  },
  addScript: type => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      state.scripts[type].push({
        'name': 'general',
        'id': state.scripts[type].length,
        'enable': true
      });
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  saveScript: (script, type) => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      if (index !== -1) state.scripts[type][index] = script;
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  deleteScript: (script, type) => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      //drop script with this index
      if (index !== -1) state.scripts[type].splice(index, 1);
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  updatePluginStatus: async (pluginId, enabled) => {
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.plugins.findIndex(plugin => {
        return plugin.id === pluginId;
      });
      if (index !== -1) state.plugins[index].enabled = enabled;
    }));
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_plugin_status', {
      plugin: pluginId,
      enabled: enabled
    }).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  updatePlaceholderStatus: async (id, enabled, isPlugin) => {
    if (isPlugin) {
      set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
        const index = state.plugins.findIndex(plugin => {
          return plugin.id === id;
        });
        if (index !== -1) state.plugins[index].placeholder = enabled ? 'enabled' : 'disabled';
      }));
    }
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_placeholder_status', {
      id: id,
      enabled: enabled
    }).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useIntegrations);
const fetchData = () => {
  return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_integrations_data', {}).then(response => {
    return response;
  }).catch(error => {
    console.error(error);
  });
};

/***/ }),

/***/ "./src/Settings/License/LicenseData.js":
/*!*********************************************!*\
  !*** ./src/Settings/License/LicenseData.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseLicenseData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  licenseStatus: cmplz_settings.licenseStatus,
  processing: false,
  licenseNotices: [],
  noticesLoaded: false,
  getLicenseNotices: async () => {
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('license_notices', {}).then(response => {
      return response;
    });
    set(state => ({
      noticesLoaded: true,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  activateLicense: async license => {
    let data = {};
    data.license = license;
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('activate_license', data);
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  deactivateLicense: async () => {
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('deactivate_license');
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseLicenseData);

/***/ }),

/***/ "./src/Statistics/StatisticsData.js":
/*!******************************************!*\
  !*** ./src/Statistics/StatisticsData.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");


const emptyData = {
  'optin': {
    "labels": ["Functional", "Statistics", "Marketing", "Do Not Track", "No choice", "No warning"],
    "categories": ["functional", "statistics", "marketing", "do_not_track", "no_choice", "no_warning"],
    "datasets": [{
      "data": ["0", "0", "0", "0", "0", "0"],
      "backgroundColor": "rgba(255, 99, 132, 1)",
      "borderColor": "rgba(255, 99, 132, 1)",
      "label": "A (default)",
      "fill": "false",
      "borderDash": [0, 0]
    }, {
      "data": ["0", "0", "0", "0", "0", "0"],
      "backgroundColor": "rgba(255, 159, 64, 1)",
      "borderColor": "rgba(255, 159, 64, 1)",
      "label": "B",
      "fill": "false",
      "borderDash": [0, 0]
    }],
    "max": 5
  },
  'optout': {
    "labels": ["Functional", "Statistics", "Marketing", "Do Not Track", "No choice", "No warning"],
    "categories": ["functional", "statistics", "marketing", "do_not_track", "no_choice", "no_warning"],
    "datasets": [{
      "data": ["0", "0", "0", "0", "0", "0"],
      "backgroundColor": "rgba(255, 99, 132, 1)",
      "borderColor": "rgba(255, 99, 132, 1)",
      "label": "A (default)",
      "fill": "false",
      "borderDash": [0, 0]
    }, {
      "data": ["0", "0", "0", "0", "0", "0"],
      "backgroundColor": "rgba(255, 159, 64, 1)",
      "borderColor": "rgba(255, 159, 64, 1)",
      "label": "B",
      "fill": "false",
      "borderDash": [0, 0]
    }],
    "max": 5
  }
};
const defaultData = {
  'optin': {
    "labels": ["Functional", "Statistics", "Marketing", "Do Not Track", "No choice", "No warning"],
    "categories": ["functional", "statistics", "marketing", "do_not_track", "no_choice", "no_warning"],
    "datasets": [{
      "data": ["29", "747", "174", "292", "30", "10"],
      "backgroundColor": "rgba(255, 99, 132, 1)",
      "borderColor": "rgba(255, 99, 132, 1)",
      "label": "Demo A (default)",
      "fill": "false",
      "borderDash": [0, 0]
    }, {
      "data": ["3", "536", "240", "389", "45", "32"],
      "backgroundColor": "rgba(255, 159, 64, 1)",
      "borderColor": "rgba(255, 159, 64, 1)",
      "label": "Demo B",
      "fill": "false",
      "borderDash": [0, 0]
    }],
    "max": 5
  },
  'optout': {
    "labels": ["Functional", "Statistics", "Marketing", "Do Not Track", "No choice", "No warning"],
    "categories": ["functional", "statistics", "marketing", "do_not_track", "no_choice", "no_warning"],
    "datasets": [{
      "data": ["29", "747", "174", "292", "30", "10"],
      "backgroundColor": "rgba(255, 99, 132, 1)",
      "borderColor": "rgba(255, 99, 132, 1)",
      "label": "A (default)",
      "fill": "false",
      "borderDash": [0, 0]
    }, {
      "data": ["3", "536", "240", "389", "45", "32"],
      "backgroundColor": "rgba(255, 159, 64, 1)",
      "borderColor": "rgba(255, 159, 64, 1)",
      "label": "Demo B",
      "fill": "false",
      "borderDash": [0, 0]
    }],
    "max": 5
  }
};
const useStatistics = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  consentType: 'optin',
  setConsentType: consentType => {
    set({
      consentType: consentType
    });
  },
  statisticsLoading: false,
  consentTypes: [],
  regions: [],
  defaultConsentType: 'optin',
  loaded: false,
  statisticsData: defaultData,
  emptyStatisticsData: emptyData,
  bestPerformerEnabled: false,
  daysLeft: '',
  abTrackingCompleted: false,
  labels: [],
  setLabels: labels => {
    set({
      labels: labels
    });
  },
  fetchStatisticsData: async () => {
    set({
      saving: true
    });
    let data = {};
    if (get().loaded) return;
    const {
      daysLeft,
      abTrackingCompleted,
      consentTypes,
      statisticsData,
      defaultConsentType,
      regions,
      bestPerformerEnabled
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_statistics_data', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set({
      saving: false,
      loaded: true,
      consentType: defaultConsentType,
      consentTypes: consentTypes,
      statisticsData: statisticsData,
      defaultConsentType: defaultConsentType,
      bestPerformerEnabled: bestPerformerEnabled,
      regions: regions,
      daysLeft: daysLeft,
      abTrackingCompleted: abTrackingCompleted
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useStatistics);

/***/ }),

/***/ "./src/utils/sleeper.js":
/*!******************************!*\
  !*** ./src/utils/sleeper.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/*
 * helper function to delay after a promise
 * @param ms
 * @returns {function(*): Promise<unknown>}
 */
const sleeper = ms => {
  return function (x) {
    return new Promise(resolve => setTimeout(() => resolve(x), ms));
  };
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (sleeper);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_GridBlock_js.js.map