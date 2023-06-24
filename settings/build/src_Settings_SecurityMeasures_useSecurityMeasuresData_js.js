"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_SecurityMeasures_useSecurityMeasuresData_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_SecurityMeasures_useSecurityMeasuresData_js.js.map