"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_License_License_js"],{

/***/ "./src/Dashboard/TaskElement.js":
/*!**************************************!*\
  !*** ./src/Dashboard/TaskElement.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_sleeper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/sleeper */ "./src/utils/sleeper.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Progress/ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");









const TaskElement = props => {
  const {
    dismissNotice,
    fetchProgressData
  } = (0,_Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__["default"])();
  const {
    getField,
    setHighLightField,
    fetchFieldsData
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const {
    setSelectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  const handleClick = async () => {
    setHighLightField(props.notice.highlight_field_id);
    let highlightField = getField(props.notice.highlight_field_id);
    await setSelectedSubMenuItem(highlightField.menu_id);
  };
  const handleClearCache = async cache_id => {
    let data = {};
    data.cache_id = cache_id;
    _utils_api__WEBPACK_IMPORTED_MODULE_4__.doAction('clear_cache', data).then(async response => {
      const notice = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').createNotice('success', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Re-started test', 'complianz-gdpr'), {
        __unstableHTML: true,
        id: 'cmplz_clear_cache',
        type: 'snackbar',
        isDismissible: true
      }).then((0,_utils_sleeper__WEBPACK_IMPORTED_MODULE_5__["default"])(3000)).then(response => {
        (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').removeNotice('rsssl_clear_cache');
      });
      await fetchFieldsData();
      await fetchProgressData();
    });
  };
  let notice = props.notice;
  let premium = notice.icon === 'premium';
  //treat links to complianz.io and internal links different.
  let urlIsExternal = notice.url && notice.url.indexOf('complianz.io') !== -1;
  let statusNice = notice.status.charAt(0).toUpperCase() + notice.status.slice(1);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: 'cmplz-task-status cmplz-' + notice.status
  }, statusNice), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "cmplz-task-message",
    dangerouslySetInnerHTML: {
      __html: notice.message
    }
  }), urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    target: "_blank",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("More info", "complianz-gdpr")), notice.clear_cache_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClearCache(notice.clear_cache_id)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Re-check", "complianz-gdpr")), !premium && !urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "cmplz-task-enable button button-secondary",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), !premium && notice.highlight_field_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClick()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), notice.plusone && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-plusone"
  }, "1"), notice.dismissible && notice.status !== 'completed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-dismiss"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    onClick: e => dismissNotice(notice.id)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "times"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TaskElement);

/***/ }),

/***/ "./src/Settings/License/License.js":
/*!*****************************************!*\
  !*** ./src/Settings/License/License.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Dashboard_TaskElement__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Dashboard/TaskElement */ "./src/Dashboard/TaskElement.js");
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _LicenseData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./LicenseData */ "./src/Settings/License/LicenseData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);








const License = props => {
  const {
    fields,
    setChangedField,
    updateField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    licenseStatus,
    licenseNotices,
    getLicenseNotices,
    noticesLoaded,
    activateLicense,
    deactivateLicense,
    processing
  } = (0,_LicenseData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!noticesLoaded) {
      getLicenseNotices();
    }
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    getLicenseNotices();
  }, [fields]);
  const onChangeHandler = fieldValue => {
    setChangedField(field.id, fieldValue);
    updateField(field.id, fieldValue);
  };
  const toggleActivation = () => {
    if (licenseStatus === 'valid') {
      deactivateLicense();
    } else {
      activateLicense(props.field.value);
    }
  };
  let field = props.field;
  /**
   * There is no "PasswordControl" in WordPress react yet, so we create our own
   * license field.
   */
  let processingClass = processing ? 'cmplz-processing' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-base-control"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-license-field"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    className: "components-text-control__input",
    type: "password",
    id: field.id,
    value: field.value,
    onChange: e => onChangeHandler(e.target.value)
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default",
    disabled: processing,
    onClick: () => toggleActivation()
  }, licenseStatus === 'valid' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Deactivate', 'really-simple-ssl')), licenseStatus !== 'valid' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Activate', 'really-simple-ssl')))), !noticesLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__["default"], null), noticesLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: processingClass
  }, licenseNotices.map((notice, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Dashboard_TaskElement__WEBPACK_IMPORTED_MODULE_1__["default"], {
    key: i,
    index: i,
    notice: notice,
    highLightField: ""
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_6__.memo)(License));

/***/ }),

/***/ "./src/Settings/License/LicenseData.js":
/*!*********************************************!*\
  !*** ./src/Settings/License/LicenseData.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseLicenseData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  licenseStatus: cmplz_settings.licenseStatus,
  processing: false,
  licenseNotices: [],
  noticesLoaded: false,
  getLicenseNotices: async () => {
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('license_notices', {}).then(response => {
      return response;
    });
    set(state => ({
      noticesLoaded: true,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  activateLicense: async license => {
    let data = {};
    data.license = license;
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('activate_license', data);
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  deactivateLicense: async () => {
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('deactivate_license');
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseLicenseData);

/***/ }),

/***/ "./src/utils/sleeper.js":
/*!******************************!*\
  !*** ./src/utils/sleeper.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/*
 * helper function to delay after a promise
 * @param ms
 * @returns {function(*): Promise<unknown>}
 */
const sleeper = ms => {
  return function (x) {
    return new Promise(resolve => setTimeout(() => resolve(x), ms));
  };
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (sleeper);

/***/ })

}]);
//# sourceMappingURL=src_Settings_License_License_js.js.map