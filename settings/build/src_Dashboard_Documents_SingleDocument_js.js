"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Documents_SingleDocument_js"],{

/***/ "./src/Dashboard/Documents/SingleDocument.js":
/*!***************************************************!*\
  !*** ./src/Dashboard/Documents/SingleDocument.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);





const SingleDocument = props => {
  const [url, setUrl] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [options, setOptions] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let options = props.options;
    if (options.length === 0) {
      let emptyOption = {
        label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Generate a %s", "complianz-gdpr").replace('%s', props.name),
        value: 0
      };
      options.unshift(emptyOption);
    } else {
      //if options does not include an option with value 0, add it.
      if (!options.filter(option => option.value === 0).length > 0) {
        let emptyOption = {
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Select a %s", "complianz-gdpr").replace('%s', props.name),
          value: 0
        };
        options.unshift(emptyOption);
      }
    }
    setOptions(options);
  }, [props.options]);
  const Download = () => {
    if (disabled) return;
    setDisabled(true);
    let request = new XMLHttpRequest();
    request.responseType = 'blob';
    request.open('get', url, true);
    request.send();
    request.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var obj = window.URL.createObjectURL(this.response);
        var element = window.document.createElement('a');
        element.setAttribute('href', obj);
        element.setAttribute('download', options.filter(option => option.value === url)[0].label);
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
    className: "cmplz-single-document-other-documents"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    onChange: fieldValue => setUrl(fieldValue),
    value: url,
    options: options
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    onClick: () => Download()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'file-download',
    color: url != 0 && !disabled ? 'black' : 'grey',
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Download file", "complianz-gdpr"),
    size: 14
  })), options.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: props.link
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'circle-chevron-right',
    color: "black",
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Go to overview", "complianz-gdpr"),
    size: 14
  })), options.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: props.link
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'plus',
    color: "black",
    tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Create new", "complianz-gdpr"),
    size: 14
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SingleDocument);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Documents_SingleDocument_js.js.map