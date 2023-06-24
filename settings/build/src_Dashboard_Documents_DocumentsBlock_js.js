"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Documents_DocumentsBlock_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Documents_DocumentsBlock_js.js.map