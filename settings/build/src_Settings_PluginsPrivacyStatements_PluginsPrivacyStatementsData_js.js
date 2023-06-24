"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_PluginsPrivacyStatements_PluginsPrivacyStatementsData_js"],{

/***/ "./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsData.js":
/*!*******************************************************************************!*\
  !*** ./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsData.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const usePrivacyStatementData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  privacyStatementsLoaded: false,
  privacyStatements: [],
  fetchPrivacyStatementsData: async () => {
    const {
      privacyStatements
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('wp_privacy_policy_data').then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set({
      privacyStatementsLoaded: true,
      privacyStatements: privacyStatements
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (usePrivacyStatementData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_PluginsPrivacyStatements_PluginsPrivacyStatementsData_js.js.map