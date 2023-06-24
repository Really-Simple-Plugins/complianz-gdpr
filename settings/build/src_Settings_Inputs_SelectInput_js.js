"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Inputs_SelectInput_js"],{

/***/ "./src/Settings/Inputs/SelectInput.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @radix-ui/react-select */ "./node_modules/@radix-ui/react-select/dist/index.module.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");
/* harmony import */ var _SelectInput_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SelectInput.scss */ "./src/Settings/Inputs/SelectInput.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);







const SelectInput = _ref => {
  let {
    value = false,
    onChange,
    required,
    defaultValue,
    disabled,
    options = {},
    innerRef
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-select-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Root, {
    //ref={innerRef}
    value: value,
    defaultValue: defaultValue,
    onValueChange: onChange,
    required: required,
    disabled: disabled && !Array.isArray(disabled)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Trigger, {
    className: "cmplz-select-group__trigger"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Value, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Content, {
    className: "cmplz-select-group__content",
    position: "popper"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollUpButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-up'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Viewport, {
    className: "cmplz-select-group__viewport"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Group, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
    className: 'cmplz-select-group__item',
    key: 0,
    value: ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Select an option", "complianz-gdpr"))), Object.entries(options).map(_ref2 => {
    let [optionValue, optionText] = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
      disabled: Array.isArray(disabled) && disabled.includes(optionValue),
      className: 'cmplz-select-group__item',
      key: optionValue,
      value: optionValue
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, optionText));
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollDownButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(SelectInput));

/***/ }),

/***/ "./src/Settings/Inputs/Input.scss":
/*!****************************************!*\
  !*** ./src/Settings/Inputs/Input.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Inputs/SelectInput.scss":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Inputs_SelectInput_js.js.map