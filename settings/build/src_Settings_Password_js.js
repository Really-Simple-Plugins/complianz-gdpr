"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Password_js"],{

/***/ "./src/Settings/Password.js":
/*!**********************************!*\
  !*** ./src/Settings/Password.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


class Password extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {
  constructor() {
    super(...arguments);
  }
  onChangeHandler(fieldValue) {
    let fields = this.props.fields;
    let field = this.props.field;
    fields[this.props.index]['value'] = fieldValue;
    setChangedField(field.id, fieldValue);
    this.setState({
      fields: fields
    });
  }
  render() {
    let field = this.props.field;
    let fieldValue = field.value;
    let fields = this.props.fields;

    /**
     * There is no "PasswordControl" in WordPress react yet, so we create our own license field.
     */
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "components-base-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "components-base-control__field"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      className: "components-base-control__label",
      htmlFor: field.id
    }, field.label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      className: "components-text-control__input",
      type: "password",
      id: field.id,
      value: fieldValue,
      onChange: e => this.onChangeHandler(e.target.value)
    })));
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Password);

/***/ })

}]);
//# sourceMappingURL=src_Settings_Password_js.js.map