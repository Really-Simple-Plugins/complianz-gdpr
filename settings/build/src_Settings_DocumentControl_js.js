"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DocumentControl_js"],{

/***/ "./src/Settings/DocumentControl.js":
/*!*****************************************!*\
  !*** ./src/Settings/DocumentControl.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_select_async__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-select/async */ "./node_modules/react-select/async/dist/react-select-async.esm.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var react_use__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react-use */ "./node_modules/react-use/esm/useUpdateEffect.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");









const DocumentControl = _ref => {
  let {
    id,
    value,
    options,
    defaultValue,
    disabled
  } = _ref;
  const [pageId, setPageId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [pageUrl, setPageUrl] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [pages, setPages] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [pagesListLoaded, setPagesListLoaded] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [pageUrlLoaded, setPageUrlLoaded] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [timer, setTimer] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const currentType = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)(value);
  const {
    updateField,
    setChangedField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const loadSubFields = onLoad => {
    let loadData = currentType.current !== value || onLoad;
    if (value === 'custom' && !pagesListLoaded) {
      currentType.current = value;
      if (loadData) loadOptions(false);
    }
    if (value === 'url' && !pageUrlLoaded) {
      let data = {};
      currentType.current = value;
      data.type = id;
      _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction('get_custom_legal_document_url', data).then(response => {
        setPageUrl(response.pageUrl);
        setPageUrlLoaded(true);
      });
    }
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let values = options.map(option => option.value);
    if (!values.includes(value)) {
      //we need to save it in the page props, otherwise it's not seen by the conditions validator
      updateField(id, defaultValue);
    }
    loadSubFields(true);
  }, []);
  (0,react_use__WEBPACK_IMPORTED_MODULE_7__["default"])(() => {
    loadSubFields(false);
  });
  const loadOptions = search => {
    let data = {};
    data.type = id;
    data.search = search;
    return _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction('get_pages_list', data).then(response => {
      //get option from pages pages list
      let selectedPage = response.pages.filter(function (element) {
        return element.value === response.pageId;
      });
      setPageId(selectedPage);
      setPagesListLoaded(true);
      setPages(response.pages);
      return response.pages;
    });
  };
  const onChangeHandler = value => {
    updateField(id, value);
    setChangedField(id, value);
  };
  const promisePages = inputValue => new Promise(resolve => {
    setTimeout(() => {
      resolve(loadOptions(inputValue));
    }, 1000);
  });
  const onChangeSelectHandler = element => {
    let data = {};
    data.pageId = element.value;
    data.type = id;
    setPageId(element);
    _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction('update_custom_legal_document_id', data).then(response => {});
  };

  /*
  * Only call api if user stops typing, after 500 ms.
  */
  const onChangeUrlHandler = e => {
    let data = {};
    let value = e.target.value;
    data.pageUrl = value;
    data.type = id;
    setPageUrl(value);
    clearTimeout(timer);
    const newTimer = setTimeout(() => {
      _utils_api__WEBPACK_IMPORTED_MODULE_3__.doAction('update_custom_legal_document_url', data).then(response => {});
    }, 500);
    setTimer(newTimer);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, options.map((option, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: 'container_' + i,
    className: "components-radio-control__option"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "radio",
    disabled: Array.isArray(disabled) && disabled.indexOf(option.value) != -1,
    checked: value == option.value,
    key: i,
    index: i,
    onChange: e => onChangeHandler(option.value)
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    key: 'label_' + i,
    htmlFor: option.value,
    index: i
  }, option.label))), value === 'custom' && !pagesListLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-documents-loader"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: "loading",
    color: "grey"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Loading...", "complianz-gdpr"))), value === 'custom' && pagesListLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(react_select_async__WEBPACK_IMPORTED_MODULE_1__["default"], {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Link to custom page", "complianz-gdpr"),
    defaultOptions: pages,
    loadOptions: promisePages,
    menuPortalTarget: document.body,
    menuPosition: 'fixed',
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Type at least two characters"),
    onChange: fieldValue => onChangeSelectHandler(fieldValue),
    value: pageId
  })), value === 'url' && !pageUrlLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-documents-loader"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: "loading",
    color: "grey"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Loading...", "complianz-gdpr"))), value === 'url' && pageUrlLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    value: pageUrl,
    onChange: onChangeUrlHandler,
    placeholder: "https://domain.com/your-policy"
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(DocumentControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_DocumentControl_js.js.map