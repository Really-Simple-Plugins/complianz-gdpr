"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Tools_Tools_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Tools_Tools_js.js.map