"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DocumentsMenu_MenuData_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_DocumentsMenu_MenuData_js.js.map