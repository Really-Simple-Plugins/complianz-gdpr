"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_SecurityMeasures_SecurityMeasures_js"],{

/***/ "./src/Settings/InstallPlugin/InstallPluginData.js":
/*!*********************************************************!*\
  !*** ./src/Settings/InstallPlugin/InstallPluginData.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseInstallPluginData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  apiRequestActive: false,
  pluginAction: 'status',
  wordPressUrl: '#',
  rating: [],
  statusLoaded: false,
  startPluginAction: (slug, action) => {
    let data = {};
    set({
      apiRequestActive: true
    });
    data.pluginAction = typeof action !== 'undefined' ? action : get().pluginAction;
    data.slug = slug;
    let nextAction = false;
    if (data.pluginAction === 'download') {
      nextAction = 'activate';
    }
    _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('plugin_actions', data).then(response => {
      set({
        pluginAction: response.pluginAction,
        wordPressUrl: response.wordpress_url
      }); //'installed', 'download', 'activate', 'upgrade-to-premium'

      //convert to percentage
      let p = Math.round(response.star_rating.rating / 10, 0) / 2;
      set({
        rating: p,
        ratingCount: response.star_rating.rating_count,
        apiRequestActive: false,
        statusLoaded: true
      });
      //if the plugin is installed, go ahead and activate as well
      if (nextAction === 'activate' && response.pluginAction !== 'installed') {
        get().startPluginAction(response.pluginAction);
      }
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseInstallPluginData);

/***/ }),

/***/ "./src/Settings/SecurityMeasures/SecurityMeasures.js":
/*!***********************************************************!*\
  !*** ./src/Settings/SecurityMeasures/SecurityMeasures.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _measures_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./measures.scss */ "./src/Settings/SecurityMeasures/measures.scss");
/* harmony import */ var _useSecurityMeasuresData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./useSecurityMeasuresData */ "./src/Settings/SecurityMeasures/useSecurityMeasuresData.js");
/* harmony import */ var _InstallPlugin_InstallPluginData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../InstallPlugin/InstallPluginData */ "./src/Settings/InstallPlugin/InstallPluginData.js");







const SecurityMeasures = () => {
  const {
    measuresDataLoaded,
    measures,
    has_7,
    getMeasuresData
  } = (0,_useSecurityMeasuresData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    statusLoaded,
    startPluginAction,
    pluginAction
  } = (0,_InstallPlugin_InstallPluginData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const slug = 'really-simple-ssl';
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    //get initial data
    if (!statusLoaded) {
      startPluginAction(slug);
    }
  }, []);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!measuresDataLoaded) {
      getMeasuresData();
    }
  }, []);
  let status = statusLoaded ? pluginAction : 'loading';
  if (statusLoaded && status !== 'installed' && status !== 'upgrade-to-premium') {
    let notice = status === 'activate' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please activate Really Simple SSL to unlock this feature.", "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please install Really Simple SSL to unlock this feature.", "complianz-gdpr");
    if (status === 'loading') {
      notice = '...';
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-locked"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-locked-overlay"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "cmplz-task-status cmplz-warning"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Not installed", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, notice)));
  }
  const measuresList = {
    vulnerability_detection: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Vulnerability detection", 'complianz-gdpr'),
    recommended_headers: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("HTTP Strict Transport Security and related security headers", 'complianz-gdpr'),
    ssl: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("TLS / SSL", 'complianz-gdpr'),
    hardening: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Recommended site hardening features", 'complianz-gdpr')
  };
  const Measure = _ref => {
    let {
      measure
    } = _ref;
    //get properties of the measure
    let enabledClass = measure.enabled ? 'cmplz-measure-enabled' : 'cmplz-measure-disabled';
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
      className: "cmplz-measure"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
      className: "cmplz-measure-description " + enabledClass
    }, measuresList[measure.id]));
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-measures-container"
  }, measuresDataLoaded && has_7 && measures.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("We are committed to the security of personal data. We take appropriate security measures to limit abuse of and unauthorized access to personal data. This ensures that only the necessary persons have access to your data, that access to the data is protected, and that our security measures are regularly reviewed.", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("The security measures we use consist of, but are not limited to:", 'complianz-gdpr')), measures.map((measure, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Measure, {
    key: index,
    measure: measure
  }))), measuresDataLoaded && measures.length === 0 && has_7 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No security measures enabled in Really Simple SSL", 'complianz-gdpr'), measuresDataLoaded && !has_7 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please upgrade Really Simple SSL to the latest version to unlock this feature.", 'complianz-gdpr'), !measuresDataLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, "..."));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(SecurityMeasures));

/***/ }),

/***/ "./src/Settings/SecurityMeasures/useSecurityMeasuresData.js":
/*!******************************************************************!*\
  !*** ./src/Settings/SecurityMeasures/useSecurityMeasuresData.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const useSecurityMeasuresData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  measures: {},
  has_7: false,
  measuresDataLoaded: false,
  getMeasuresData: async () => {
    const {
      measures,
      has_7
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_security_measures_data', {}).then(response => {
      return response;
    });
    set(state => ({
      measuresDataLoaded: true,
      measures: measures,
      has_7: has_7
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useSecurityMeasuresData);

/***/ }),

/***/ "./src/Settings/SecurityMeasures/measures.scss":
/*!*****************************************************!*\
  !*** ./src/Settings/SecurityMeasures/measures.scss ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_SecurityMeasures_SecurityMeasures_js.js.map