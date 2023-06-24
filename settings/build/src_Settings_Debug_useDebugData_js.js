"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Debug_useDebugData_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_Debug_useDebugData_js.js.map