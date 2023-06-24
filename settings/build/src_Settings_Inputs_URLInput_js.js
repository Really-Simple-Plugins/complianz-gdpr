"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Inputs_URLInput_js"],{

/***/ "./src/Settings/Inputs/URLInput.js":
/*!*****************************************!*\
  !*** ./src/Settings/Inputs/URLInput.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");



const URLInput = _ref => {
  let {
    value,
    onChange,
    onError,
    required,
    defaultValue,
    disabled,
    id,
    name
  } = _ref;
  const inputId = id || name;
  const [inputValue, setInputValue] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)('');
  const isValidURL = string => {
    var res = string.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/);
    return res !== null;
  };

  //ensure that the initial value is set
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    setInputValue(value);
  }, []);

  //because an update on the entire Fields array is costly, we only update after the user has stopped typing
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const typingTimer = setTimeout(() => {
      onChange(inputValue);
      if (!isValidURL(inputValue)) {
        onError('invalid_url');
      }
    }, 500);
    return () => {
      clearTimeout(typingTimer);
    };
  }, [inputValue]);
  const handleChange = value => {
    setInputValue(value);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-url-input-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "url",
    id: inputId,
    name: name,
    value: inputValue,
    onChange: event => handleChange(event.target.value),
    required: required,
    disabled: disabled,
    className: "cmplz-url-input-group__input"
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(URLInput));

/***/ }),

/***/ "./src/Settings/Inputs/Input.scss":
/*!****************************************!*\
  !*** ./src/Settings/Inputs/Input.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Inputs_URLInput_js.js.map