"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DocumentsMenu_MenuPerDocument_js"],{

/***/ "./src/Settings/DocumentsMenu/MenuData.js":
/*!************************************************!*\
  !*** ./src/Settings/DocumentsMenu/MenuData.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   UseMenuData: () => (/* binding */ UseMenuData)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var react_toastify__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-toastify */ "./node_modules/react-toastify/dist/react-toastify.esm.mjs");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);





const UseMenuData = (0,zustand__WEBPACK_IMPORTED_MODULE_3__.create)((set, get) => ({
  menuDataLoaded: false,
  saving: false,
  menu: [],
  menuChanged: false,
  changedMenuType: 'per_document',
  emptyMenuLink: '#',
  requiredDocuments: [],
  createdDocuments: [],
  genericDocuments: [],
  documentsNotInMenu: [],
  pageTypes: [],
  regions: [],
  fetchMenuData: async () => {
    const response = await fetchMenuData(false);
    let createdDocuments = response.required_documents.filter(document => document.page_id);
    set({
      menuDataLoaded: true,
      emptyMenuLink: response.empty_menu_link,
      menu: response.menu,
      requiredDocuments: response.required_documents,
      genericDocuments: response.generic_documents_list,
      createdDocuments: createdDocuments,
      pageTypes: response.page_types,
      documentsNotInMenu: response.documents_not_in_menu,
      regions: response.regions
    });
  },
  updateMenu: (page_id, menu_id) => {
    let menuType = isNaN(page_id) ? 'per_type' : 'per_document';
    set({
      menuType: menuType
    });
    if (menuType === 'per_type') {
      set((0,immer__WEBPACK_IMPORTED_MODULE_4__["default"])(state => {
        let genIndex = state.genericDocuments.findIndex(function (page, i) {
          return page.page_id === page_id || page.type === page_id;
        });
        let createdIndex = state.createdDocuments.findIndex(function (page, i) {
          return page.page_id === page_id || page.type === page_id;
        });
        if (genIndex !== -1) {
          state.genericDocuments[genIndex].menu_id = menu_id;
          if (createdIndex !== -1) state.createdDocuments[createdIndex].menu_id = -1;
          state.menuChanged = true;
        }
      }));
    } else {
      set((0,immer__WEBPACK_IMPORTED_MODULE_4__["default"])(state => {
        let genIndex = state.genericDocuments.findIndex(function (page, i) {
          return page.page_id === page_id || page.type === page_id;
        });
        let createdIndex = state.createdDocuments.findIndex(function (page, i) {
          return page.page_id === page_id || page.type === page_id;
        });
        ;
        if (createdIndex !== -1) {
          state.createdDocuments[createdIndex].menu_id = menu_id;
          if (genIndex !== -1) state.genericDocuments[genIndex].menu_id = -1;
          state.menuChanged = true;
        }
      }));
    }
  },
  saveDocumentsMenu: async (hasChangedFields, showNotice) => {
    set({
      saving: true
    });
    let menuChanged = get().menuChanged;
    if (menuChanged || hasChangedFields) {
      let data = {};
      //post for generic documents only the redirected ones.
      data.genericDocuments = get().genericDocuments.filter(document => document.can_region_redirect);
      data.createdDocuments = get().createdDocuments;
      const response = _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('save_documents_menu_data', data).then(response => {
        set({
          saving: false
        });
        return response;
      }).catch(error => {
        console.error(error);
      });
      showNotice && react_toastify__WEBPACK_IMPORTED_MODULE_1__.toast.promise(response, {
        pending: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Saving menu...', 'complianz-gdpr'),
        success: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Menu saved', 'complianz-gdpr'),
        error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Something went wrong', 'complianz-gdpr')
      });
    } else {
      showNotice && react_toastify__WEBPACK_IMPORTED_MODULE_1__.toast.info((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Settings have not been changed', 'complianz-gdpr'));
    }
  }
}));
const fetchMenuData = () => {
  let data = {};
  data.generate = false;
  return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('documents_menu_data', data).then(response => {
    return response;
  }).catch(error => {
    console.error(error);
  });
};

/***/ }),

/***/ "./src/Settings/DocumentsMenu/MenuPerDocument.js":
/*!*******************************************************!*\
  !*** ./src/Settings/DocumentsMenu/MenuPerDocument.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _MenuData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MenuData */ "./src/Settings/DocumentsMenu/MenuData.js");
/* harmony import */ var _SingleDocumentMenuControl__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SingleDocumentMenuControl */ "./src/Settings/DocumentsMenu/SingleDocumentMenuControl.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);




const MenuPerDocument = props => {
  const {
    createdDocuments
  } = (0,_MenuData__WEBPACK_IMPORTED_MODULE_1__.UseMenuData)();
  //filter out this region from the documents
  let regionDocuments = createdDocuments.filter(document => document.region === props.region.id);
  if (regionDocuments.length === 0) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-h4"
  }, props.region.label), regionDocuments.map((document, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SingleDocumentMenuControl__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: i,
    document: document
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(MenuPerDocument));

/***/ }),

/***/ "./src/Settings/DocumentsMenu/SingleDocumentMenuControl.js":
/*!*****************************************************************!*\
  !*** ./src/Settings/DocumentsMenu/SingleDocumentMenuControl.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _MenuData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MenuData */ "./src/Settings/DocumentsMenu/MenuData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);




const SingleDocumentMenuControl = props => {
  const {
    menu,
    updateMenu
  } = (0,_MenuData__WEBPACK_IMPORTED_MODULE_1__.UseMenuData)();
  const onChangeHandler = e => {
    updateMenu(props.document.page_id, e.target.value);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-single-document-menu"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-document-menu-title"
  }, props.document.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    value: props.document.menu_id,
    onChange: e => onChangeHandler(e)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    value: -1,
    key: -1
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Select a menu", "complianz-gdpr")), menu.map((menuItem, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: i,
    value: menuItem.id
  }, menuItem.label))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(SingleDocumentMenuControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_DocumentsMenu_MenuPerDocument_js.js.map