"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Inputs_CheckboxGroup_js"],{

/***/ "./src/Settings/Inputs/Button.js":
/*!***************************************!*\
  !*** ./src/Settings/Inputs/Button.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Buttons_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Buttons.scss */ "./src/Settings/Inputs/Buttons.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);







// import AreYouSureModal from '../AreYouSureModal';

const Button = _ref => {
  let {
    type = 'action',
    style = 'tertiary',
    label,
    onClick,
    href = '',
    target = '',
    disabled,
    action,
    field,
    children
  } = _ref;
  if (!label && !children) return null;
  const buttonLabel = field && field.button_text ? field.button_text : label;
  const content = buttonLabel ? buttonLabel : children;
  const {
    fetchFieldsData,
    showSavedSettingsNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    selectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const [isOpen, setIsOpen] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(false);
  const classes = `button cmplz-button button--${style} button-${type}`;
  const clickHandler = async e => {
    if (type === 'action' && onClick) {
      onClick(e);
      return;
    }
    if (type === 'action' && action) {
      if (field && field.warn) {
        setIsOpen(true);
      } else {
        await executeAction();
      }
      return;
    }
    window.location.href = field.url;
  };
  const handleConfirm = async () => {
    setIsOpen(false);
    await executeAction();
  };
  const handleCancel = () => {
    setIsOpen(false);
  };
  const executeAction = async e => {
    let data = {};
    await _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction(field.action, data).then(response => {
      if (response.success) {
        fetchFieldsData(selectedSubMenuItem);
        showSavedSettingsNotice(response.message);
      }
    });
  };
  const warningText = field && field.warn ? field.warn : '';
  if (type === 'action') {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__.__experimentalConfirmDialog, {
      isOpen: isOpen,
      onConfirm: handleConfirm,
      onCancel: handleCancel
    }, warningText), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: classes,
      onClick: clickHandler,
      disabled: disabled
    }, content));
  }
  if (type === 'link') {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      className: classes,
      href: href,
      target: target
    }, content);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_2__.memo)(Button));

/***/ }),

/***/ "./src/Settings/Inputs/CheckboxGroup.js":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/CheckboxGroup.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _radix_ui_react_checkbox__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @radix-ui/react-checkbox */ "./node_modules/@radix-ui/react-checkbox/dist/index.module.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _CheckboxGroup_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./CheckboxGroup.scss */ "./src/Settings/Inputs/CheckboxGroup.scss");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Inputs_Button__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Inputs/Button */ "./src/Settings/Inputs/Button.js");







const CheckboxGroup = _ref => {
  let {
    label,
    value,
    id,
    onChange,
    required,
    disabled,
    options = {}
  } = _ref;
  let valueValidated = value;
  if (!Array.isArray(valueValidated)) {
    valueValidated = valueValidated === '' ? [] : [valueValidated];
  }
  const selected = valueValidated;
  const loadMoreCount = 10;
  const [loadMoreExpanded, setLoadMoreExpanded] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(false);

  // check if there are more options than the loadmore count
  let loadMoreEnabled = false;
  if (Object.keys(options).length > loadMoreCount) {
    loadMoreEnabled = true;
  }
  const handleCheckboxChange = (e, option) => {
    const newSelected = selected.includes(option) ? selected.filter(item => item !== option) : [...selected, option];
    onChange(newSelected);
  };
  const isEnabled = id => {
    return selected.includes(id);
  };
  const loadMoreHandler = () => {
    setLoadMoreExpanded(!loadMoreExpanded);
  };
  let allDisabled = disabled && !Array.isArray(disabled);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-checkbox-group'
  }, Object.entries(options).map((_ref2, i) => {
    let [key, optionLabel] = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: key,
      className: `cmplz-checkbox-group__item${!loadMoreExpanded && i > loadMoreCount ? ' cmplz-hidden' : ''}`
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_checkbox__WEBPACK_IMPORTED_MODULE_6__.Root, {
      className: "cmplz-checkbox-group__checkbox",
      id: id + '_' + key,
      checked: isEnabled(key),
      "aria-label": label,
      disabled: allDisabled || Array.isArray(disabled) && disabled.includes(key),
      required: required,
      onCheckedChange: e => handleCheckboxChange(e, key)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_checkbox__WEBPACK_IMPORTED_MODULE_6__.Indicator, {
      className: "cmplz-checkbox-group__indicator"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
      name: 'check',
      size: 14,
      color: 'dark-blue'
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      className: "cmplz-checkbox-label",
      htmlFor: id + '_' + key
    }, optionLabel));
  }), !loadMoreExpanded && loadMoreEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_Button__WEBPACK_IMPORTED_MODULE_5__["default"], {
    onClick: loadMoreHandler
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Show more', 'complianz-gdpr')), loadMoreExpanded && loadMoreEnabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_Button__WEBPACK_IMPORTED_MODULE_5__["default"], {
    onClick: loadMoreHandler
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Show less', 'complianz-gdpr')));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(CheckboxGroup));

/***/ }),

/***/ "./src/Settings/Inputs/Buttons.scss":
/*!******************************************!*\
  !*** ./src/Settings/Inputs/Buttons.scss ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Inputs/CheckboxGroup.scss":
/*!************************************************!*\
  !*** ./src/Settings/Inputs/CheckboxGroup.scss ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Inputs_CheckboxGroup_js.js.map