"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataBreachReports_DataBreachConclusion_js"],{

/***/ "./src/Settings/DataBreachReports/DataBreachConclusion.js":
/*!****************************************************************!*\
  !*** ./src/Settings/DataBreachReports/DataBreachConclusion.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _DataBreachReportsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DataBreachReportsData */ "./src/Settings/DataBreachReports/DataBreachReportsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _DataBreachConclusionItem__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./DataBreachConclusionItem */ "./src/Settings/DataBreachReports/DataBreachConclusionItem.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);







const DataBreachConclusion = () => {
  const {
    savedDocument,
    conclusions
  } = (0,_DataBreachReportsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const {
    addHelpNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (savedDocument.has_to_be_reported) {
      addHelpNotice('create-data-breach-reports', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This wizard is intended to provide a general guide to a possible data breach.", "complianz-gdpr") + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Specialist legal advice should be sought about your specific circumstances.", "complianz-gdpr"), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Specialist legal advice required", "complianz-gdpr"), false);
    }
  }, [savedDocument]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-conclusion"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Your dataleak report:", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "cmplz-conclusion__list"
  }, conclusions.length > 0 && conclusions.map((conclusion, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_DataBreachConclusionItem__WEBPACK_IMPORTED_MODULE_4__["default"], {
    conclusion: conclusion,
    key: i,
    delay: i * 1000
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(DataBreachConclusion));

/***/ }),

/***/ "./src/Settings/DataBreachReports/DataBreachConclusionItem.js":
/*!********************************************************************!*\
  !*** ./src/Settings/DataBreachReports/DataBreachConclusionItem.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);




const DataBreachConclusionItem = _ref => {
  let {
    conclusion,
    delay
  } = _ref;
  const [generating, setGenerating] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    setTimeout(() => {
      show();
    }, delay);
  });
  const show = () => {
    setGenerating(false);
  };
  let iconColor = 'green';
  if (conclusion.report_status === 'warning') iconColor = 'orange';
  if (conclusion.report_status === 'error') iconColor = 'red';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, generating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    className: "cmplz-conclusion__check icon-loading"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: 'loading',
    color: 'grey'
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-conclusion__check--report-text"
  }, " ", conclusion.check_text, " ")), !generating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    className: "cmplz-conclusion__check icon-" + conclusion.report_status
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: conclusion.report_status,
    color: iconColor
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-conclusion__check--report-text",
    dangerouslySetInnerHTML: {
      __html: conclusion.report_text
    }
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_2__.memo)(DataBreachConclusionItem));

/***/ }),

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
//# sourceMappingURL=src_Settings_DataBreachReports_DataBreachConclusion_js.js.map