"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CreateDocuments_CreateDocumentsControl_js"],{

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

/***/ }),

/***/ "./src/Settings/CreateDocuments/CreateDocumentsControl.js":
/*!****************************************************************!*\
  !*** ./src/Settings/CreateDocuments/CreateDocumentsControl.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _DocumentsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./DocumentsData */ "./src/Settings/CreateDocuments/DocumentsData.js");
/* harmony import */ var _CreateDocument__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./CreateDocument */ "./src/Settings/CreateDocuments/CreateDocument.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_7__);










/**
 * Render a help notice in the sidebar
 */
const CreateDocumentsControl = () => {
  const {
    saveDocuments,
    saving,
    documentsChanged,
    documentsDataLoaded,
    hasMissingPages,
    fetchDocumentsData,
    requiredPages
  } = (0,_DocumentsData__WEBPACK_IMPORTED_MODULE_2__.UseDocumentsData)();
  const {
    fields,
    addHelpNotice,
    showSavedSettingsNotice,
    setDocumentSettingsChanged
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchDocumentsData();
  }, [fields, documentsDataLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (documentsDataLoaded && requiredPages.length === 0) {
      let explanation = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("You haven't selected any legal documents to create.", "complianz-gdpr") + " " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("You can continue to the next step.", "complianz-gdpr");
      addHelpNotice('create-documents', 'warning', explanation, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('No required documents', 'complianz-gdpr'));
    }
  }, [requiredPages, documentsDataLoaded]);
  const onButtonClickHandler = async () => {
    saveDocuments().then(() => {
      setDocumentSettingsChanged(true);
      showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Documents updated!", "complianz-gdpr"));
    });
  };
  let intro;
  if (hasMissingPages) {
    intro = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("The pages marked with X should be added to your website. You can create these pages with a shortcode, a Gutenberg block or use the below \"Create missing pages\" button.", "complianz-gdpr");
  } else {
    intro = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("All necessary pages have been created already. You can update the page titles here if you want, then click the \"Update pages\" button.", "complianz-gdpr");
  }
  if (!documentsDataLoaded) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_5__["default"], {
      lines: "3"
    });
  }
  let disabled = !hasMissingPages && !documentsChanged;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, documentsDataLoaded && intro, documentsDataLoaded && requiredPages.map((page, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_CreateDocument__WEBPACK_IMPORTED_MODULE_3__["default"], {
    page: page,
    key: i
  })), requiredPages.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: disabled,
    onClick: () => onButtonClickHandler(),
    className: "button button-default"
  }, hasMissingPages ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Create missing pages", "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Update", "complianz-gdpr"), saving && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_6__["default"], {
    name: "loading",
    color: "grey"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_7__.memo)(CreateDocumentsControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_CreateDocuments_CreateDocumentsControl_js.js.map