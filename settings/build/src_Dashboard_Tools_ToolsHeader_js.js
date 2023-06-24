"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Tools_ToolsHeader_js"],{

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
//# sourceMappingURL=src_Dashboard_Tools_ToolsHeader_js.js.map