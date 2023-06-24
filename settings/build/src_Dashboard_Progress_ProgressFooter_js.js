"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Progress_ProgressFooter_js"],{

/***/ "./src/Dashboard/Progress/ProgressFooter.js":
/*!**************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressFooter.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");





const ProgressFooter = props => {
  // const {setShowOnBoardingModal} = useOnboardingData();
  const {
    fields,
    getFieldValue
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [cookieBlockerColor, setCookieBlockerColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [placeholderColor, setPlaceholderColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [cookieBannerColor, setCookieBannerColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let color = getFieldValue('enable_cookie_blocker') === 'yes' ? 'green' : 'grey';
    setCookieBlockerColor(color);
    color = getFieldValue('dont_use_placeholders') == 1 ? 'grey' : 'green';
    setPlaceholderColor(color);
    color = getFieldValue('enable_cookie_banner') === 'yes' ? 'green' : 'grey';
    setCookieBannerColor(color);
  }, [fields]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#wizard",
    className: "button button-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Continue Wizard", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend cmplz-flex-push-right"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: cookieBlockerColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Cookie Blocker", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: placeholderColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Placeholders", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-legend"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-check',
    color: cookieBannerColor,
    size: 14
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Cookie Banner", "complianz-gdpr"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressFooter);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Progress_ProgressFooter_js.js.map