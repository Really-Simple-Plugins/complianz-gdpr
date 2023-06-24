"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Editor_Editor_js"],{

/***/ "./src/Settings/Editor/Editor.js":
/*!***************************************!*\
  !*** ./src/Settings/Editor/Editor.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");



let SimpleRichTextEditor;
const toolbarConfig = {
  display: ['INLINE_STYLE_BUTTONS', 'BLOCK_TYPE_BUTTONS', 'HISTORY_BUTTONS'],
  INLINE_STYLE_BUTTONS: [{
    label: 'Bold',
    style: 'BOLD',
    className: 'custom-css-class'
  }, {
    label: 'Italic',
    style: 'ITALIC'
  }, {
    label: 'Underline',
    style: 'UNDERLINE'
  }],
  BLOCK_TYPE_BUTTONS: [{
    label: 'UL',
    style: 'unordered-list-item'
  }, {
    label: 'OL',
    style: 'ordered-list-item'
  }]
};
const Editor = _ref => {
  let {
    value,
    field,
    label
  } = _ref;
  const [editorState, setEditorState] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [isEditorLoaded, setEditorLoaded] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    __webpack_require__.e(/*! import() */ "vendors-node_modules_react-rte_dist_react-rte_js").then(__webpack_require__.t.bind(__webpack_require__, /*! react-rte */ "./node_modules/react-rte/dist/react-rte.js", 23)).then(_ref2 => {
      let {
        default: loadedSimpleRichTextEditor
      } = _ref2;
      SimpleRichTextEditor = loadedSimpleRichTextEditor;
      setEditorState(loadedSimpleRichTextEditor.createValueFromString(value, 'html'));
      setEditorLoaded(true);
    });
  }, []);
  const {
    changedFields,
    updateField,
    setChangedField
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (isEditorLoaded) {
      setEditorState(SimpleRichTextEditor.createValueFromString(value, 'html'));
    }
  }, [changedFields, isEditorLoaded]);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    setChangedField(field.id, value);
  }, []);
  function editorChangeHandler(editorValue) {
    setEditorState(editorValue);
    updateField(field.id, editorValue.toString('html'));
  }
  if (!isEditorLoaded) {
    return null; // or return a loader
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SimpleRichTextEditor, {
    value: editorState,
    onChange: editorChangeHandler,
    toolbarConfig: toolbarConfig
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(Editor));

/***/ })

}]);
//# sourceMappingURL=src_Settings_Editor_Editor_js.js.map