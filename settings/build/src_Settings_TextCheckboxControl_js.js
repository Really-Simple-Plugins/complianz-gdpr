"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_TextCheckboxControl_js"],{

/***/ "./src/Settings/TextCheckboxControl.js":
/*!*********************************************!*\
  !*** ./src/Settings/TextCheckboxControl.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");



const TextCheckboxControl = props => {
  const {
    setChangedField,
    updateField
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const onChangeHandlerText = value => {
    let curValue = {
      ...props.field.value
    };
    curValue['text'] = value;
    updateField(props.field.id, curValue);
    setChangedField(props.field.id, curValue);
  };
  const onChangeHandlerCheckbox = value => {
    let curValue = {
      ...props.field.value
    };
    curValue['show'] = value;
    updateField(props.field.id, curValue);
    setChangedField(props.field.id, curValue);
  };
  let textValue = props.field.value.hasOwnProperty('text') ? props.field.value['text'] : '';
  let checkboxValue = props.field.value.hasOwnProperty('show') ? props.field.value['show'] : false;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-text-control"
  }, props.label, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-text-control__field"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    placeholder: props.field.placeholder,
    onChange: fieldValue => onChangeHandlerText(fieldValue),
    value: textValue,
    disabled: props.disabled
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ToggleControl, {
    disabled: props.disabled,
    checked: checkboxValue == 1,
    onChange: fieldValue => onChangeHandlerCheckbox(fieldValue)
  })), props.field.comment && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    dangerouslySetInnerHTML: {
      __html: props.field.comment
    }
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TextCheckboxControl);

/***/ })

}]);
//# sourceMappingURL=src_Settings_TextCheckboxControl_js.js.map