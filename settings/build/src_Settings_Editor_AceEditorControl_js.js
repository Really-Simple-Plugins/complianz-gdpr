"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Editor_AceEditorControl_js"],{

/***/ "./src/Settings/Editor/AceEditorControl.js":
/*!*************************************************!*\
  !*** ./src/Settings/Editor/AceEditorControl.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_ace__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-ace */ "./node_modules/react-ace/lib/index.js");
/* harmony import */ var ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ace-builds/src-noconflict/mode-css */ "./node_modules/ace-builds/src-noconflict/mode-css.js");
/* harmony import */ var ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ace-builds/src-noconflict/theme-monokai */ "./node_modules/ace-builds/src-noconflict/theme-monokai.js");
/* harmony import */ var ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ace-builds/src-noconflict/ext-language_tools */ "./node_modules/ace-builds/src-noconflict/ext-language_tools.js");
/* harmony import */ var ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5__);






const AceEditorControl = props => {
  let mode = props.mode ? props.mode : 'css';
  let height = props.height ? props.height : '200px';
  let placeholder = props.field && props.field.default ? props.field.default : props.placeholder;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(react_ace__WEBPACK_IMPORTED_MODULE_2__["default"], {
    disabled: props.disabled,
    placeholder: placeholder,
    mode: mode,
    theme: "monokai",
    width: "100%",
    height: height,
    onChange: value => props.onChangeHandler(value),
    fontSize: 14,
    showPrintMargin: true,
    showGutter: true,
    highlightActiveLine: true,
    value: props.value,
    setOptions: {
      enableBasicAutocompletion: false,
      enableLiveAutocompletion: false,
      enableSnippets: false,
      showLineNumbers: true,
      tabSize: 2,
      useWorker: false
    }
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(AceEditorControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_Editor_AceEditorControl_js.js.map