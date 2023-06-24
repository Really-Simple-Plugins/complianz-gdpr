"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Export_ImportControl_js"],{

/***/ "./src/Settings/Export/ImportControl.js":
/*!**********************************************!*\
  !*** ./src/Settings/Export/ImportControl.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _utils_upload__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../utils/upload */ "./src/utils/upload.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _Import_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Import.scss */ "./src/Settings/Export/Import.scss");









function ImportControl() {
  const {
    removeHelpNotice,
    addHelpNotice,
    showSavedSettingsNotice
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [file, setFile] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [uploading, setUploading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!file) return;
    if (file.type !== 'application/json') {
      setDisabled(true);
      addHelpNotice('import_settings', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("You can only upload .json files", "complianz-gdpr"), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Incorrect extension", "complianz-gdpr"), false);
    } else {
      setDisabled(false);
      removeHelpNotice('import_settings');
    }
  }, [file]);
  const onClickHandler = e => {
    setDisabled(true);
    setUploading(true);
    (0,_utils_upload__WEBPACK_IMPORTED_MODULE_5__.upload)('import_settings', file).then(response => {
      if (response.data.success) {
        showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Settings imported", "complianz-gdpr"));
      } else {
        addHelpNotice('import_settings', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("You can only upload .json files", "complianz-gdpr"), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Incorrect extension", "complianz-gdpr"), false);
      }
      setUploading(false);
      setFile(false);
      return true;
    }).catch(error => {
      console.error(error);
    });
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-import-form"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-import-button-container"
  }, file && file.name, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormFileUpload, {
    accept: "",
    icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
      name: "upload",
      color: "black"
    }) //formfile upload overrides size prop. We override that in the icon component
    ,
    onChange: event => setFile(event.currentTarget.files[0])
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Select file", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: disabled,
    className: "button button-default",
    onClick: e => onClickHandler(e)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Import", "complianz-gdpr"), uploading && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "loading",
    color: "grey"
  }))));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_6__.memo)(ImportControl));

/***/ }),

/***/ "./src/utils/upload.js":
/*!*****************************!*\
  !*** ./src/utils/upload.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   upload: () => (/* binding */ upload)
/* harmony export */ });
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);

const upload = (action, file, details) => {
  let formData = new FormData();
  formData.append("data", file);
  if (typeof details !== 'undefined') {
    formData.append("details", JSON.stringify(details));
  }
  return axios__WEBPACK_IMPORTED_MODULE_0___default().post(cmplz_settings.admin_url + '?page=complianz&cmplz_upload_file=1&action=' + action, formData, {
    headers: {
      "Content-Type": "multipart/form-data",
      'X-WP-Nonce': cmplz_settings.nonce
    }
  });
};

/***/ }),

/***/ "./src/Settings/Export/Import.scss":
/*!*****************************************!*\
  !*** ./src/Settings/Export/Import.scss ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Export_ImportControl_js.js.map