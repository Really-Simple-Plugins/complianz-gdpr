"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_FinishControl_js"],{

/***/ "./src/Settings/FinishControl.js":
/*!***************************************!*\
  !*** ./src/Settings/FinishControl.js ***!
  \***************************************/
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
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);

// import Icon from "../utils/Icon";






/**
 * Render a help notice in the sidebar
 */
const FinishControl = () => {
  const {
    fields,
    updateField,
    setChangedField,
    updateFieldsData,
    addHelpNotice,
    fetchAllFieldsCompleted,
    allRequiredFieldsCompleted,
    notCompletedRequiredFields
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [cookiebannerRequired, setCookiebannerRequired] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let data = {};
    _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction('get_cookiebanner_required', data).then(response => {
      setCookiebannerRequired(response.required);
    });
  }, [fields]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    updateField('cookie_banner_required', cookiebannerRequired);
    setChangedField('cookie_banner_required', cookiebannerRequired);
    updateFieldsData();
  }, [cookiebannerRequired]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (cookiebannerRequired) {
      let explanation = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("The cookie banner and cookie blocker are required on your website.", "complianz-gdpr") + " " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("You can enable them both here, then you should check your website if your configuration is working properly.", "complianz-gdpr") + " " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Please read the below article to debug any issues while in safe mode. Safe mode is available under settings.", "complianz-gdpr") + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("You will find tips and tricks on your dashboard after you have configured your cookie banner.", 'complianz-gdpr');
      addHelpNotice('last-step-feedback', 'default', explanation, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('A consent banner is required', 'complianz-gdpr'), 'https://complianz.io/debugging-manual');
    } else {
      let explanation = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Your site does not require a cookie banner. If you think you need a cookie banner, please review your wizard settings.", "complianz-gdpr");
      addHelpNotice('last-step-feedback', 'warning', explanation, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('A consent banner is not required', 'complianz-gdpr'));
    }
  }, [cookiebannerRequired]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchAllFieldsCompleted();
  }, [fields]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, allRequiredFieldsCompleted && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Click '%s' to complete the configuration. You can come back to change your configuration at any time.", 'complianz-gdpr').replace('%s', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Finish", 'complianz-gdpr'))), !allRequiredFieldsCompleted && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, cookiebannerRequired && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("The cookie banner and the cookie blocker are now ready to be enabled.", "complianz-gdpr") + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Please check your website after finishing the wizard to verify that your configuration is working properly.", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Not all required fields are completed yet.", "complianz-gdpr") + " " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Please check the wizard to complete all required questions.", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("The following required fields have not been completed:", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", null, notCompletedRequiredFields.map((field, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    key: i
  }, field.parent_label ? field.parent_label : field.label)))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(FinishControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_FinishControl_js.js.map