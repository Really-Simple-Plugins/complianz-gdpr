"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Modal_Modal_js"],{

/***/ "./src/Modal/Modal.js":
/*!****************************!*\
  !*** ./src/Modal/Modal.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");





class Modal extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {
  constructor() {
    super(...arguments);
    this.state = {
      data: [],
      buttonsDisabled: false
    };
  }
  dismissModal(dropItem) {
    this.props.handleModal(false, null, dropItem);
  }
  componentDidMount() {
    this.setState({
      data: this.props.data,
      buttonsDisabled: false
    });
  }
  handleFix(e) {
    //set to disabled
    let action = this.props.data.action;
    this.setState({
      buttonsDisabled: true
    });
    _utils_api__WEBPACK_IMPORTED_MODULE_2__.doAction(action, 'refresh', this.props.data).then(response => {
      this.props.data;
      let {
        data
      } = this.state;
      data.description = response.msg;
      data.subtitle = '';
      this.setState({
        data: data
      });
      let item = this.props.data;
      if (response.success) {
        this.dismissModal(this.props.data);
      }
    });
  }
  render() {
    const {
      data,
      buttonsDisabled
    } = this.state;
    let disabled = buttonsDisabled ? 'disabled' : '';
    let description = data.description;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal-backdrop",
      onClick: e => this.dismissModal(e)
    }, "\xA0"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal",
      id: "{id}"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal-header"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
      className: "modal-title"
    }, data.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      type: "button",
      className: "cmplz-modal-close",
      "data-dismiss": "modal",
      "aria-label": "Close",
      onClick: e => this.dismissModal(e)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      name: "times"
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal-content"
    }, data.subtitle && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal-subtitle"
    }, data.subtitle), Array.isArray(description) && description.map((s, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "cmplz-modal-description"
    }, s))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-modal-footer"
    }, data.edit && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: data.edit,
      target: "_blank",
      className: "button button-secondary"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Edit", "complianz-gdpr")), data.help && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: data.help,
      target: "_blank",
      className: "button cmplz-button-help"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Help", "complianz-gdpr")), !data.ignored && data.action === 'ignore_url' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      disabled: disabled,
      className: "button button-primary",
      onClick: e => this.handleFix(e)
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Ignore", "complianz-gdpr")), data.action !== 'ignore_url' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      disabled: disabled,
      className: "button button-primary",
      onClick: e => this.handleFix(e)
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")))));
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Modal);

/***/ })

}]);
//# sourceMappingURL=src_Modal_Modal_js.js.map