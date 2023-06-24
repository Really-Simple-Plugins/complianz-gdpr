"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ChangeStatus_js"],{

/***/ "./src/Settings/ChangeStatus.js":
/*!**************************************!*\
  !*** ./src/Settings/ChangeStatus.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


const ChangeStatus = props => {
  let statusClass = props.item.status == 1 ? 'button button-primary cmplz-status-allowed' : 'button button-default cmplz-status-revoked';
  let label = props.item.status == 1 ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Revoke", "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Allow", "complianz-gdpr");
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: () => props.onChangeHandlerDataTableStatus(props.item.status, props.item, 'status'),
    className: statusClass
  }, label);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ChangeStatus);

/***/ })

}]);
//# sourceMappingURL=src_Settings_ChangeStatus_js.js.map