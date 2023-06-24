"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_InstallPlugin_InstallPluginData_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_InstallPlugin_InstallPluginData_js.js.map