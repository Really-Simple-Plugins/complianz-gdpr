"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_TipsTricks_TipsTricks_js"],{

/***/ "./src/Dashboard/TipsTricks/TipsTricks.js":
/*!************************************************!*\
  !*** ./src/Dashboard/TipsTricks/TipsTricks.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const Tip = _ref => {
  let {
    link,
    content
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: link,
    target: "_blank",
    title: "{content}"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-icon"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    "aria-hidden": "true",
    focusable: "false",
    role: "img",
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 512 512",
    height: "15"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fill: "var(--rsp-grey-300)",
    d: "M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-content"
  }, content)));
};
const TipsTricks = () => {
  const items = [{
    content: "Styling your cookie notice and legal documents",
    link: 'https://complianz.io/docs/customization/'
  }, {
    content: "Why plugins are better in consent management",
    link: 'https://complianz.io/consent-management-wordpress-native-plugin-versus-cloud-solution/'
  }, {
    content: "Configure Tag Manager with Complianz",
    link: 'https://complianz.io/definitive-guide-to-tag-manager-and-complianz/'
  }, {
    content: "Self-hosting Google Fonts",
    link: 'https://complianz.io/self-hosting-google-fonts-for-wordpress/'
  }, {
    content: "Translating your cookie notice and legal documents",
    link: 'https://complianz.io/?s=translations&lang=en'
  }, {
    content: "Debugging issues with Complianz",
    link: 'https://complianz.io/debugging-issues/'
  }];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-tips-tricks-container"
  }, items.map((item, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Tip, {
    key: "trick-" + i,
    link: item.link,
    content: item.content
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TipsTricks);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_TipsTricks_TipsTricks_js.js.map