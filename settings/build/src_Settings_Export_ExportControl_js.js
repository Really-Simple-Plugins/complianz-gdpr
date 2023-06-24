"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Export_ExportControl_js"],{

/***/ "./src/Settings/Export/ExportControl.js":
/*!**********************************************!*\
  !*** ./src/Settings/Export/ExportControl.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);




function ExportControl(_ref) {
  let {
    field,
    label
  } = _ref;
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const Download = () => {
    if (disabled) return;
    setDisabled(true);
    let request = new XMLHttpRequest();
    request.responseType = 'blob';
    request.open('get', field.url, true);
    request.send();
    request.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        var obj = window.URL.createObjectURL(this.response);
        var element = window.document.createElement('a');
        element.setAttribute('href', obj);
        element.setAttribute('download', 'complianz-export.json');
        window.document.body.appendChild(element);
        //onClick property
        element.click();
        setTimeout(function () {
          window.URL.revokeObjectURL(obj);
        }, 60 * 1000);
      }
    };
    request.onprogress = function (e) {
      setDisabled(true);
    };
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-export-container"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default",
    onClick: () => Download()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Export", "complianz-gdpr")));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_2__.memo)(ExportControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_Export_ExportControl_js.js.map