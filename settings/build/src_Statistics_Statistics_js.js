"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Statistics_Statistics_js"],{

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

/***/ "./src/Statistics/Statistics.js":
/*!**************************************!*\
  !*** ./src/Statistics/Statistics.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _StatisticsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./StatisticsData */ "./src/Statistics/StatisticsData.js");
/* harmony import */ var _Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Settings/License/LicenseData */ "./src/Settings/License/LicenseData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);






// import { useMemo } from 'react';
const Statistics = () => {
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [abTestingEnabled, setAbTestingEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [data, setData] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const {
    consentType,
    setConsentType,
    statisticsData,
    emptyStatisticsData,
    consentTypes,
    loaded,
    fetchStatisticsData,
    statisticsLoading
  } = (0,_StatisticsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [Bar, setBar] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const {
    licenseStatus
  } = (0,_Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    __webpack_require__.e(/*! import() */ "vendors-node_modules_chart_js_dist_chart_js").then(__webpack_require__.bind(__webpack_require__, /*! chart.js */ "./node_modules/chart.js/dist/chart.js")).then(_ref => {
      let {
        Chart,
        CategoryScale,
        LinearScale,
        BarElement,
        Title,
        Tooltip,
        Legend
      } = _ref;
      Chart.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);
    });
    Promise.all(/*! import() */[__webpack_require__.e("vendors-node_modules_chart_js_dist_chart_js"), __webpack_require__.e("node_modules_react-chartjs-2_dist_index_js")]).then(__webpack_require__.bind(__webpack_require__, /*! react-chartjs-2 */ "./node_modules/react-chartjs-2/dist/index.js")).then(_ref2 => {
      let {
        Bar
      } = _ref2;
      setBar(() => Bar);
    });
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!loaded && abTestingEnabled) {
      fetchStatisticsData();
    }
  }, [loaded, abTestingEnabled]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (getFieldValue('a_b_testing') == 1) {
      setAbTestingEnabled(true);
    } else {
      setAbTestingEnabled(false);
    }
  }, [fields]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    //initial values
    if (licenseStatus === 'valid' && abTestingEnabled) {
      setData(emptyStatisticsData[consentType]);
    } else {
      setData(statisticsData[consentType]);
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (abTestingEnabled) {
      setData(statisticsData[consentType]);
    }
  }, [consentType, abTestingEnabled, loaded]);
  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    }
  };
  const loadingClass = statisticsLoading ? 'cmplz-loading' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, consentTypes.length > 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    value: consentType,
    onChange: e => setConsentType(e.target.value)
  }, consentTypes.map((type, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: i,
    value: type.id
  }, type.label))), data && Bar && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Bar, {
    className: `burst-loading-container ${loadingClass}`,
    options: options,
    data: data
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(Statistics));

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
//# sourceMappingURL=src_Statistics_Statistics_js.js.map