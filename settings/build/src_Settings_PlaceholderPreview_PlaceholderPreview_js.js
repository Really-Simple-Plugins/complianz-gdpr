"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_PlaceholderPreview_PlaceholderPreview_js"],{

/***/ "./src/Settings/PlaceholderPreview/PlaceholderPreview.js":
/*!***************************************************************!*\
  !*** ./src/Settings/PlaceholderPreview/PlaceholderPreview.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _PlaceholderPreview_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./PlaceholderPreview.scss */ "./src/Settings/PlaceholderPreview/PlaceholderPreview.scss");




const PlaceholderPreview = props => {
  const {
    fields,
    getFieldValue
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [placeholderStyle, setPlaceholderStyle] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(getFieldValue('placeholder_style'));
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    let style = getFieldValue('placeholder_style');
    if (style === '') style = 'minimal';
    setPlaceholderStyle(style);
  }, [getFieldValue('placeholder_style')]);
  const url = cmplz_settings.plugin_url + 'assets/images/placeholders/default-' + placeholderStyle + '.jpg';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-placeholder-preview"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: url
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(PlaceholderPreview));

/***/ }),

/***/ "./src/Settings/PlaceholderPreview/PlaceholderPreview.scss":
/*!*****************************************************************!*\
  !*** ./src/Settings/PlaceholderPreview/PlaceholderPreview.scss ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_PlaceholderPreview_PlaceholderPreview_js.js.map