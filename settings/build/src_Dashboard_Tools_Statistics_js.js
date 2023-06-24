"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Tools_Statistics_js"],{

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
//# sourceMappingURL=src_Dashboard_Tools_Statistics_js.js.map