"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CheckboxControl_js"],{

/***/ "./src/Settings/CheckboxControl.js":
/*!*****************************************!*\
  !*** ./src/Settings/CheckboxControl.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

/*
* The tooltip can't be included in the native toggleControl, so we have to build our own.
*/

const CheckboxControl = props => {
  const onChangeHandler = e => {
    let fieldValue = !props.field.value;
    props.onChangeHandler(fieldValue);
  };
  const handleKeyDown = e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      onChangeHandler(true);
    }
  };
  let field = props.field;
  let is_checked = field.value ? 'is-checked' : '';
  let is_disabled = props.disabled ? 'is-disabled' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-base-control components-toggle-control"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-base-control__field"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    "data-wp-component": "HStack",
    className: "components-flex components-h-stack"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "components-form-toggle " + is_checked + ' ' + is_disabled
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    onKeyDown: e => handleKeyDown(e),
    checked: field.value,
    className: "components-form-toggle__input",
    onChange: e => onChangeHandler(e),
    id: field.id,
    type: "checkbox",
    disabled: props.disabled
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "components-form-toggle__track"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "components-form-toggle__thumb"
  })), props.label))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CheckboxControl);

/***/ })

}]);
//# sourceMappingURL=src_Settings_CheckboxControl_js.js.map