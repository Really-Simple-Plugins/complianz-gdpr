"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ButtonControl_js"],{

/***/ "./src/Settings/ButtonControl.js":
/*!***************************************!*\
  !*** ./src/Settings/ButtonControl.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Inputs_Button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Settings/Inputs/Button */ "./src/Settings/Inputs/Button.js");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);







const ButtonControl = _ref => {
  let {
    label,
    field,
    disabled
  } = _ref;
  const {
    fetchFieldsData,
    showSavedSettingsNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const {
    selectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const [isOpen, setIsOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  let text = field.button_text ? field.button_text : field.label;
  if (field.action) {
    const clickHandler = async e => {
      if (field.warn) {
        setIsOpen(true);
      } else {
        await executeAction();
      }
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
      await _utils_api__WEBPACK_IMPORTED_MODULE_2__.doAction(field.action, data).then(response => {
        if (response.success) {
          fetchFieldsData(selectedSubMenuItem);
          showSavedSettingsNotice(response.message);
        }
      });
    };
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Settings_Inputs_Button__WEBPACK_IMPORTED_MODULE_1__["default"], {
      text: text,
      style: 'secondary',
      disabled: disabled,
      onClick: e => clickHandler(e)
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.__experimentalConfirmDialog, {
      isOpen: isOpen,
      onConfirm: handleConfirm,
      onCancel: handleCancel
    }, field.warn));
  } else {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Settings_Inputs_Button__WEBPACK_IMPORTED_MODULE_1__["default"], {
      style: "secondary",
      label: text,
      disabled: disabled,
      href: field.url
    });
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ButtonControl);

/***/ }),

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

/***/ "./src/Settings/Inputs/Buttons.scss":
/*!******************************************!*\
  !*** ./src/Settings/Inputs/Buttons.scss ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_ButtonControl_js.js.map