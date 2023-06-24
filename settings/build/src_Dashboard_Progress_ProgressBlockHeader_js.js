"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Progress_ProgressBlockHeader_js"],{

/***/ "./src/Dashboard/Progress/ProgressBlockHeader.js":
/*!*******************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressBlockHeader.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _ProgressData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ProgressData */ "./src/Dashboard/Progress/ProgressData.js");




const ProgressHeader = () => {
  const {
    setFilter,
    filter,
    fetchFilter,
    notices,
    progressLoaded
  } = (0,_ProgressData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchFilter();
  }, []);
  let all_task_count = 0;
  let open_task_count = 0;
  all_task_count = progressLoaded ? notices.length : 0;
  let openNotices = notices.filter(function (notice) {
    return notice.status !== 'completed';
  });
  open_task_count = openNotices.length;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-grid-title cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Progress", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-switcher-container cmplz-active-filter-" + filter
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    className: "cmplz-task-switcher cmplz-all-tasks",
    onClick: () => setFilter('all'),
    "data-filter": "all"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("All tasks", "really-simple-ssl"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rsssl_task_count"
  }, "(", all_task_count, ")")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    className: "cmplz-task-switcher cmplz-remaining-tasks",
    onClick: () => setFilter('remaining'),
    "data-filter": "remaining"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Remaining tasks", "really-simple-ssl"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "rsssl_task_count"
  }, "(", open_task_count, ")")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressHeader);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Progress_ProgressBlockHeader_js.js.map