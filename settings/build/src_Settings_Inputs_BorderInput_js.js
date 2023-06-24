"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Inputs_BorderInput_js"],{

/***/ "./src/Settings/Inputs/BorderInput.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/BorderInput.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _BorderInput_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./BorderInput.scss */ "./src/Settings/Inputs/BorderInput.scss");





const BorderInput = _ref => {
  let {
    label,
    id,
    value,
    onChange,
    required,
    defaultValue,
    disabled,
    options = {},
    units = ['px']
  } = _ref;
  const defaultUnit = defaultValue.type || value.type || units[0];
  const [unit, setUnit] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(defaultUnit);
  const [link, setLink] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(false);

  // make an array of the sides with key and label
  const sides = {
    top: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Top', 'complianz-gdpr'),
    right: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Right', 'complianz-gdpr'),
    bottom: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Bottom', 'complianz-gdpr'),
    left: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Left', 'complianz-gdpr')
  };
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    // set link based on if all values are equal
    if (value['top'] === value['right'] && value['top'] === value['bottom'] && value['top'] === value['left']) {
      setLink(true);
    }
  }, []);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!link) return;
    handleChange(value['top'], 'top');
  }, [link]);
  const handleChange = (changedValue, key) => {
    let valueCopy = {
      ...value
    };
    if (link) {
      valueCopy = updateAllValues(changedValue);
    } else {
      valueCopy[key] = changedValue;
    }
    onChange(valueCopy);
  };
  const updateAllValues = newValue => {
    let valueCopy = {
      ...value
    };
    valueCopy['top'] = newValue;
    valueCopy['right'] = newValue;
    valueCopy['bottom'] = newValue;
    valueCopy['left'] = newValue;
    return valueCopy;
  };
  const handleUnitChange = newUnit => {
    setUnit(newUnit);
    let valueCopy = {
      ...value
    };
    valueCopy.type = newUnit;
    onChange(valueCopy);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-border-input'
  }, Object.keys(sides).map(key => {
    const side = sides[key];
    const sideValue = value.hasOwnProperty(key) ? value[key] : defaultValue[key];
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      className: 'cmplz-border-input-side',
      type: "number",
      key: key,
      onChange: e => handleChange(e.target.value, key),
      value: sideValue
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
      className: 'cmplz-border-input-side-label'
    }, side));
  }), link && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: 'cmplz-border-input-link linked',
    onClick: () => setLink(!link)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: 'linked',
    size: 16,
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Unlink values', 'complianz-gdpr')
  })), !link && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: 'cmplz-border-input-link',
    onClick: () => setLink(!link)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: 'unlinked',
    size: 16,
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Link values together', 'complianz-gdpr')
  })), units.length > 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-border-input-unit'
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    value: unit,
    onChange: e => handleUnitChange(e.target.value)
  }, units.map((unitItem, i) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
      key: i,
      value: unitItem
    }, unitItem);
  }))), units.length === 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-border-input-unit'
  }, unit));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(BorderInput));

/***/ }),

/***/ "./src/Settings/Inputs/BorderInput.scss":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/BorderInput.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Inputs_BorderInput_js.js.map