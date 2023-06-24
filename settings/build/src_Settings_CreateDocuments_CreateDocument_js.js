"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CreateDocuments_CreateDocument_js"],{

/***/ "./src/Settings/CreateDocuments/CreateDocument.js":
/*!********************************************************!*\
  !*** ./src/Settings/CreateDocuments/CreateDocument.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _DocumentsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DocumentsData */ "./src/Settings/CreateDocuments/DocumentsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);






/**
 * Render a help notice in the sidebar
 */
const CreateDocument = props => {
  const {
    saving,
    updateDocument
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_1__.UseDocumentsData)();
  const {
    showSavedSettingsNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const onChangeHandler = (e, id) => {
    updateDocument(id, e.target.value);
  };
  const onClickhandler = (e, shortcode) => {
    let success;
    e.target.classList.add('cmplz-click-animation');
    let temp = document.createElement("input");
    document.getElementsByTagName("body")[0].appendChild(temp);
    temp.value = shortcode;
    temp.select();
    try {
      success = document.execCommand("copy");
    } catch (e) {
      success = false;
    }
    temp.parentElement.removeChild(temp);
    if (success) {
      showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Copied shortcode", "complianz-gdpr"));
    }
  };
  let isCreated = !!props.page.page_id;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-create-document"
  }, isCreated && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "success",
    color: "green"
  }), !isCreated && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "times"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: saving,
    onChange: e => onChangeHandler(e, props.page.page_id),
    type: "text",
    value: props.page.title
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-shortcode-container",
    onClick: e => onClickhandler(e, props.page.shortcode)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "shortcode"
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CreateDocument);

/***/ })

}]);
//# sourceMappingURL=src_Settings_CreateDocuments_CreateDocument_js.js.map