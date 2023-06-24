"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataBreachReports_DataBreachReportsData_js"],{

/***/ "./src/Settings/DataBreachReports/DataBreachReportsData.js":
/*!*****************************************************************!*\
  !*** ./src/Settings/DataBreachReports/DataBreachReportsData.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/updateFieldsListWithConditions */ "./src/utils/updateFieldsListWithConditions.js");




const useDataBreachReportsData = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  documentsLoaded: false,
  savedDocument: {},
  conclusions: [],
  region: '',
  fileName: '',
  fetching: false,
  updating: false,
  loadingFields: false,
  documents: [],
  regions: [],
  fields: [],
  editDocumentId: false,
  resetEditDocumentId: id => {
    set({
      editDocumentId: false,
      region: ''
    });
  },
  editDocument: async (e, id) => {
    e.preventDefault();
    set({
      updating: true
    });
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('load_databreach_report', {
      id: id
    }).then(response => {
      set({
        fields: response.fields,
        region: response.region,
        updating: false,
        fileName: response.file_name
      });
    }).catch(error => {
      console.error(error);
    });
    set({
      editDocumentId: id
    });
  },
  setRegion: region => {
    set({
      region: region
    });
  },
  updateField: (id, value) => {
    let found = false;
    let index = false;
    set((0,immer__WEBPACK_IMPORTED_MODULE_3__["default"])(state => {
      state.fields.forEach(function (fieldItem, i) {
        if (fieldItem.id === id) {
          index = i;
          found = true;
        }
      });
      if (index !== false) state.fields[index].value = value;
    }));
    let newFields = (0,_utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__.updateFieldsListWithConditions)(get().fields);
    set({
      fields: newFields
    });
  },
  save: async region => {
    set({
      updating: true
    });
    let postId = get().editDocumentId;
    let savedDocumentId = 0;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('save_databreach_report', {
      fields: get().fields,
      region: region,
      post_id: postId
    }).then(response => {
      savedDocumentId = response.post_id;
      set({
        updating: false,
        conclusions: response.conclusions
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
    await get().fetchData();
    let documents = get().documents;
    let savedDocuments = documents.filter(document => document.id === savedDocumentId);
    if (savedDocuments.length > 0) {
      set({
        savedDocument: savedDocuments[0]
      });
    }
  },
  deleteDocuments: async ids => {
    //get array of documents to delete
    let deleteDocuments = get().documents.filter(document => ids.includes(document.id));
    //remove the ids from the documents array
    set(state => ({
      documents: state.documents.filter(document => !ids.includes(document.id))
    }));
    let data = {};
    data.documents = deleteDocuments;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_databreach_report', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  fetchData: async () => {
    if (get().fetching) return;
    set({
      fetching: true
    });
    let data = {};
    const {
      documents,
      regions
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_databreach_reports', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(state => ({
      documentsLoaded: true,
      documents: documents,
      regions: regions,
      fetching: false
    }));
  },
  fetchFields: async region => {
    let data = {
      region: region
    };
    set({
      loadingFields: true
    });
    const {
      fields
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_databreach_report_fields', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    let newFields = (0,_utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__.updateFieldsListWithConditions)(fields);
    set(state => ({
      fields: newFields,
      loadingFields: false
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useDataBreachReportsData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_DataBreachReports_DataBreachReportsData_js.js.map