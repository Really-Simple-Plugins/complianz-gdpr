"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Debug_DebugDataControl_js"],{

/***/ "./src/Settings/Debug/DebugDataControl.js":
/*!************************************************!*\
  !*** ./src/Settings/Debug/DebugDataControl.js ***!
  \************************************************/
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
/* harmony import */ var _debug_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./debug.scss */ "./src/Settings/Debug/debug.scss");
/* harmony import */ var _useDebugData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./useDebugData */ "./src/Settings/Debug/useDebugData.js");






const DebugDataControl = () => {
  const {
    debugDataLoaded,
    scriptDebugEnabled,
    debugData,
    getDebugData
  } = (0,_useDebugData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!debugDataLoaded) {
      getDebugData();
    }
  }, []);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-debug-data-container"
  }, debugDataLoaded && !scriptDebugEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("To view possible script conflicts on your site, set the SCRIPT_DEBUG constant in your wp-config.php, or install the plugin WP Debugging", "complianz-gpdr")), debugDataLoaded && scriptDebugEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Detected Script errors on your site:", "complianz-gpdr"), "\xA0", debugData, debugData.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No script errors detected", "complianz-gpdr"))), !debugDataLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, "..."));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(DebugDataControl));

/***/ }),

/***/ "./src/Settings/Debug/useDebugData.js":
/*!********************************************!*\
  !*** ./src/Settings/Debug/useDebugData.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const useDebugData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  debugData: [],
  debugDataLoaded: false,
  scriptDebugEnabled: false,
  getDebugData: async () => {
    const {
      debug_data,
      script_debug_enabled
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_debug_data', {}).then(response => {
      return response;
    });
    set(state => ({
      debugDataLoaded: true,
      debugData: debug_data,
      scriptDebugEnabled: script_debug_enabled
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDebugData);

/***/ }),

/***/ "./src/Settings/Debug/debug.scss":
/*!***************************************!*\
  !*** ./src/Settings/Debug/debug.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Debug_DebugDataControl_js.js.map