"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Inputs_ColorPicker_js"],{

/***/ "./src/Settings/Inputs/ColorPicker.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/ColorPicker.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_color__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-color */ "./node_modules/react-color/es/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);



const ColorPicker = _ref => {
  let {
    colorValue,
    onChangeComplete
  } = _ref;
  const [color, setColor] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(colorValue);
  const onChange = color => {
    setColor(color.hex);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(react_color__WEBPACK_IMPORTED_MODULE_1__.ChromePicker, {
    color: color,
    onChange: onChange,
    onChangeComplete: onChangeComplete,
    disableAlpha: true
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_2__.memo)(ColorPicker));

/***/ })

}]);
//# sourceMappingURL=src_Settings_Inputs_ColorPicker_js.js.map