"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_BorderWidthControl_js"],{

/***/ "./src/Settings/BorderWidthControl.js":
/*!********************************************!*\
  !*** ./src/Settings/BorderWidthControl.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");



const BorderWidthControl = props => {
  const {
    updateField,
    setChangedField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const handleChange = (key, value) => {
    let valueCopy = {
      ...props.field.value
    };
    valueCopy[key] = value;
    updateField(props.field.id, valueCopy);
    setChangedField(props.field.id, valueCopy);
  };
  const top = props.field.value.hasOwnProperty('top') ? props.field.value['top'] : props.field.default['top'];
  const right = props.field.value.hasOwnProperty('right') ? props.field.value['right'] : props.field.default['right'];
  const bottom = props.field.value.hasOwnProperty('bottom') ? props.field.value['bottom'] : props.field.default['bottom'];
  const left = props.field.value.hasOwnProperty('left') ? props.field.value['left'] : props.field.default['left'];
  const type = props.field.value.hasOwnProperty('type') ? props.field.value['type'] : props.field.default['type'];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-label"
  }, props.label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-control"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element-label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Top", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "number",
    key: "1",
    onChange: e => handleChange('top', e.target.value),
    value: top
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element-label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Right", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "number",
    key: "2",
    onChange: e => handleChange('right', e.target.value),
    value: right
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element-label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Bottom", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "number",
    key: "3",
    onChange: e => handleChange('bottom', e.target.value),
    value: bottom
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-element-label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Left", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "number",
    key: "4",
    onChange: e => handleChange('left', e.target.value),
    value: left
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-inputtype"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-borderradius-inputtype-pixel "
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("px", "complianz-gdpr")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BorderWidthControl);

/***/ })

}]);
//# sourceMappingURL=src_Settings_BorderWidthControl_js.js.map