"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Modal_ModalControl_js"],{

/***/ "./src/Modal/ModalControl.js":
/*!***********************************!*\
  !*** ./src/Modal/ModalControl.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


class ModalControl extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {
  constructor() {
    super(...arguments);
  }
  componentDidMount() {
    this.onClickHandler = this.onClickHandler.bind(this);
  }
  onClickHandler() {
    this.props.handleModal(true, this.props.modalData);
  }
  render() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button button-" + this.props.btnStyle,
      onClick: e => this.onClickHandler(e)
    }, this.props.btnText);
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ModalControl);

/***/ })

}]);
//# sourceMappingURL=src_Modal_ModalControl_js.js.map