"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_utils_upload_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_utils_upload_js.js.map