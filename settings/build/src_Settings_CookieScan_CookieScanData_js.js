"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CookieScan_CookieScanData_js"],{

/***/ "./src/Settings/CookieScan/CookieScanData.js":
/*!***************************************************!*\
  !*** ./src/Settings/CookieScan/CookieScanData.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   UseCookieScanData: () => (/* binding */ UseCookieScanData)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseCookieScanData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  initialLoadCompleted: false,
  iframeLoaded: false,
  loading: false,
  nextPage: false,
  progress: 0,
  cookies: [],
  lastLoadedIframe: '',
  setIframeLoaded: iframeLoaded => set({
    iframeLoaded
  }),
  setLastLoadedIframe: lastLoadedIframe => set(state => ({
    lastLoadedIframe
  })),
  setProgress: progress => set({
    progress
  }),
  fetchProgress: () => {
    let data = {};
    set({
      loading: true
    });
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_scan_progress', data).then(response => {
      set({
        initialLoadCompleted: true,
        loading: false,
        nextPage: response.next_page,
        progress: response.progress,
        cookies: response.cookies
      });
      return response;
    });
  }
}));

/***/ })

}]);
//# sourceMappingURL=src_Settings_CookieScan_CookieScanData_js.js.map