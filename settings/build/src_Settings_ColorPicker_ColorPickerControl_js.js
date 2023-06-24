"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ColorPicker_ColorPickerControl_js"],{

/***/ "./src/Settings/ColorPicker/ColorPickerControl.js":
/*!********************************************************!*\
  !*** ./src/Settings/ColorPicker/ColorPickerControl.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Inputs_ColorPicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Inputs/ColorPicker */ "./src/Settings/Inputs/ColorPicker.js");
/* harmony import */ var _radix_ui_react_popover__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @radix-ui/react-popover */ "./node_modules/@radix-ui/react-popover/dist/index.module.js");
/* harmony import */ var _ColorPicker_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ColorPicker.scss */ "./src/Settings/ColorPicker/ColorPicker.scss");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");






const ColorPickerElement = props => {
  const {
    updateField,
    setChangedField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  //parse value from field value
  const [anchorEl, setAnchor] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)();
  const colorValue = props.field.value.hasOwnProperty(props.item.fieldname) ? props.field.value[props.item.fieldname] : props.field.default[props.item.fieldname];
  const colorName = props.item.fieldname;
  const handleClick = e => {
    setAnchor(e.currentTarget);
  };
  const handleClose = e => {
    setAnchor(null);
  };
  const handleColorChange = (color, event) => {
    let valueCopy = {
      ...props.field.value
    };
    valueCopy[props.item.fieldname] = color.hex;
    updateField(props.field.id, valueCopy);
    setChangedField(props.field.id, valueCopy);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_popover__WEBPACK_IMPORTED_MODULE_5__.Root, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_popover__WEBPACK_IMPORTED_MODULE_5__.Trigger, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-color-picker-control-item",
    onClick: handleClick
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-color-picker-color",
    style: {
      backgroundColor: colorValue
    }
  }), colorName)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_popover__WEBPACK_IMPORTED_MODULE_5__.Portal, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_popover__WEBPACK_IMPORTED_MODULE_5__.Content, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_ColorPicker__WEBPACK_IMPORTED_MODULE_2__["default"], {
    colorValue: colorValue,
    onChangeComplete: handleColorChange
  })))));
};
const ColorPickerControl = props => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-color-picker-control"
  }, props.field.fields.map((item, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ColorPickerElement, {
    key: i,
    item: item,
    field: props.field
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(ColorPickerControl));

/***/ }),

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

/***/ }),

/***/ "./src/Settings/ColorPicker/ColorPicker.scss":
/*!***************************************************!*\
  !*** ./src/Settings/ColorPicker/ColorPicker.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_ColorPicker_ColorPickerControl_js.js.map