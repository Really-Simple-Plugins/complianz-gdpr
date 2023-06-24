"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Statistics_StatisticsFeedback_js"],{

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

/***/ "./src/Statistics/StatisticsFeedback.js":
/*!**********************************************!*\
  !*** ./src/Statistics/StatisticsFeedback.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _StatisticsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./StatisticsData */ "./src/Statistics/StatisticsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);







const StatisticsFeedback = () => {
  const {
    fields,
    getFieldValue,
    addHelpNotice
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const {
    regions,
    abTrackingCompleted,
    daysLeft,
    bestPerformerEnabled,
    loaded,
    fetchStatisticsData
  } = (0,_StatisticsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [consentStatisticsEnabled, setConsentStatisticsEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [abTestingEnabled, setAbTestingEnabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!loaded) {
      fetchStatisticsData();
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let consentStats = getFieldValue('a_b_testing') == 1;
    setConsentStatisticsEnabled(consentStats);
    let ab = getFieldValue('a_b_testing_buttons') == 1;
    setAbTestingEnabled(ab);
  }, [fields]);
  const Notice = (icon, color, text) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-statistics-status"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      name: icon,
      color: color
    }), text);
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let notice = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('The conversion graph shows the ratio for the different choices users have. When a user has made a choice, this will be counted as either a converted user, or a not converted. If no choice is made, the user will be listed in the "No choice" category.', 'complianz-gdpr');
    notice += '&nbsp;';
    if (getFieldValue('use_country') == 1 && regions.length > 0) {
      const enabled_regions = regions.filter(region => region.value !== 'label').map(region => region.label);
      notice += (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('As you have enabled geoip, there are several regions in which a banner is shown, in different ways. In regions apart from %s no banner is shown at all.', 'complianz-gdpr').replace('%s', enabled_regions.join(', '));
    }
    addHelpNotice('a_b_testing', 'warning', notice, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Banners in different regions', 'complianz-gdpr'));
  }, [regions]);
  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    }
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, bestPerformerEnabled && Notice('circle-check', 'green', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('The cookie banner with the best results has been enabled as default banner.', 'complianz-gdpr')), !bestPerformerEnabled && consentStatisticsEnabled && !abTestingEnabled && Notice('circle-times', 'grey', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('A/B testing is disabled. Previously made progress is saved.', 'complianz-gdpr')), !bestPerformerEnabled && abTestingEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, !abTrackingCompleted && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, daysLeft > 1 && Notice('circle-check', 'green', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('A/B is enabled and will end in %s days.', 'complianz-gdpr').replace('%s', daysLeft)), daysLeft === 1 && Notice('circle-check', 'green', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('A/B is enabled and will end in 1 day.', 'complianz-gdpr').replace('%s', daysLeft)), daysLeft === 0 && Notice('circle-check', 'green', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('A/B is enabled and will end today.', 'complianz-gdpr'))), abTrackingCompleted && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, Notice('circle-check', 'green', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('The A/B tracking period has ended, the best performer will be enabled on the next scheduled check.', 'complianz-gdpr')))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(StatisticsFeedback));

/***/ })

}]);
//# sourceMappingURL=src_Statistics_StatisticsFeedback_js.js.map