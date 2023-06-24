"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_DateRange_useDateStore_js"],{

/***/ "./src/DateRange/useDateStore.js":
/*!***************************************!*\
  !*** ./src/DateRange/useDateStore.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   useDate: () => (/* binding */ useDate)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/format/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/startOfDay/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/subDays/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/endOfDay/index.js");



// define the store
const useDate = (0,zustand__WEBPACK_IMPORTED_MODULE_0__.create)(set => ({
  startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_1__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_2__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_3__["default"])(new Date(), 7)), 'yyyy-MM-dd'),
  setStartDate: startDate => set(state => ({
    startDate
  })),
  endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_1__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_4__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_3__["default"])(new Date(), 1)), 'yyyy-MM-dd'),
  setEndDate: endDate => set(state => ({
    endDate
  })),
  range: 'last-7-days',
  setRange: range => set(state => ({
    range
  }))
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDate);

/***/ })

}]);
//# sourceMappingURL=src_DateRange_useDateStore_js.js.map