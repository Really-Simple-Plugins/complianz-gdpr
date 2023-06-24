"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_PluginsPrivacyStatements_PluginsPrivacyStatementsControl_js"],{

/***/ "./src/Settings/Panel.js":
/*!*******************************!*\
  !*** ./src/Settings/Panel.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");


const Panel = props => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item",
    key: props.id,
    style: props.style ? props.style : {}
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("details", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("summary", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__title"
  }, props.summary), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__comment"
  }, props.comment), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__icons"
  }, props.icons), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: 'chevron-down',
    size: 18
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__details"
  }, props.details))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Panel);

/***/ }),

/***/ "./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsControl.js":
/*!**********************************************************************************!*\
  !*** ./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsControl.js ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _PluginsPrivacyStatementsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PluginsPrivacyStatementsData */ "./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsData.js");
/* harmony import */ var _SinglePrivacyStatement__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./SinglePrivacyStatement */ "./src/Settings/PluginsPrivacyStatements/SinglePrivacyStatement.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);








const PluginsPrivacyStatementsControl = () => {
  const [privacyStatementGenerated, setPrivacyStatementGenerated] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const {
    getFieldValue,
    fields
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const {
    privacyStatementsLoaded,
    fetchPrivacyStatementsData,
    privacyStatements
  } = (0,_PluginsPrivacyStatementsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let generated = getFieldValue('privacy-statement') === 'generated';
    setPrivacyStatementGenerated(generated);
    if (!privacyStatementsLoaded && generated) {
      fetchPrivacyStatementsData();
    }
  }, [fields]);
  if (!privacyStatementsLoaded && privacyStatementGenerated) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_5__["default"], {
      lines: "3"
    }));
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, privacyStatementGenerated && privacyStatements.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("No plugins with suggested statements found.", 'complianz-gdpr')), !privacyStatementGenerated && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("You have chosen to generate your own Privacy Statement, which means the option to add custom text to it is not applicable.", 'complianz-gdpr')), privacyStatementGenerated && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, Array.isArray(privacyStatements) && privacyStatements.map((plugin, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SinglePrivacyStatement__WEBPACK_IMPORTED_MODULE_3__["default"], {
    key: i,
    plugin: plugin
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_6__.memo)(PluginsPrivacyStatementsControl));

/***/ }),

/***/ "./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsData.js":
/*!*******************************************************************************!*\
  !*** ./src/Settings/PluginsPrivacyStatements/PluginsPrivacyStatementsData.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const usePrivacyStatementData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  privacyStatementsLoaded: false,
  privacyStatements: [],
  fetchPrivacyStatementsData: async () => {
    const {
      privacyStatements
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('wp_privacy_policy_data').then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set({
      privacyStatementsLoaded: true,
      privacyStatements: privacyStatements
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (usePrivacyStatementData);

/***/ }),

/***/ "./src/Settings/PluginsPrivacyStatements/SinglePrivacyStatement.js":
/*!*************************************************************************!*\
  !*** ./src/Settings/PluginsPrivacyStatements/SinglePrivacyStatement.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");






/**
 * Render a help notice in the sidebar
 */
const SinglePrivacyStatement = props => {
  const {
    updateField,
    setChangedField,
    getFieldValue
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const Details = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row",
      dangerouslySetInnerHTML: {
        __html: props.plugin.policy_text
      }
    }));
  };
  const addPolicyHandler = (text, e) => {
    e.preventDefault();
    let newText = getFieldValue('custom_privacy_policy_text');
    newText += text;
    updateField('custom_privacy_policy_text', newText);
    setChangedField('custom_privacy_policy_text', newText);
  };
  const Icons = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: "#",
      onClick: e => addPolicyHandler(props.plugin.policy_text, e)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Add to annex of Privacy Statement", "complianz-gdpr"),
      name: "plus"
    })), props.plugin.consent_api !== 'na' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, !props.plugin.consent_api && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Does not conform with the Consent API", "complianz-gdpr"),
      name: "circle",
      color: "red"
    }), props.plugin.consent_api && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Conforms to the Consent API", "complianz-gdpr"),
      name: "circle",
      color: "green"
    })));
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_1__["default"], {
    summary: props.plugin.plugin_name,
    icons: Icons(),
    details: Details()
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(SinglePrivacyStatement));

/***/ })

}]);
//# sourceMappingURL=src_Settings_PluginsPrivacyStatements_PluginsPrivacyStatementsControl_js.js.map