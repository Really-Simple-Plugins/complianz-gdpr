"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_DataBreachReports_CreateDataBreachReport_js"],{

/***/ "./src/Settings/DataBreachReports/CreateDataBreachReport.js":
/*!******************************************************************!*\
  !*** ./src/Settings/DataBreachReports/CreateDataBreachReport.js ***!
  \******************************************************************/
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
/* harmony import */ var _Fields_Field__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Fields/Field */ "./src/Settings/Fields/Field.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _DataBreachConclusion__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./DataBreachConclusion */ "./src/Settings/DataBreachReports/DataBreachConclusion.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Fields/LabelWrapper */ "./src/Settings/Fields/LabelWrapper.js");
/* harmony import */ var _Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../Inputs/SelectInput */ "./src/Settings/Inputs/SelectInput.js");











const CreateDataBreachReport = _ref => {
  let {
    label,
    field
  } = _ref;
  const {
    fields,
    fileName,
    fetching,
    loadingFields,
    updating,
    regions,
    documentsLoaded,
    resetEditDocumentId,
    savedDocument,
    fetchData,
    fetchFields,
    updateField,
    save,
    editDocumentId,
    region,
    setRegion
  } = (0,_DataBreachReportsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [createBtnDisabled, setCreateBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [downloadBtnDisabled, setDownloadBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [step, setStep] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const {
    allRequiredFieldsCompleted,
    fetchAllFieldsCompleted,
    fieldsLoaded,
    showSavedSettingsNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  let scrollAnchor = React.createRef();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (editDocumentId) {
      setStep(0);
    }
    if (editDocumentId && scrollAnchor.current) {
      scrollAnchor.current.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  }, [editDocumentId]);
  const fieldsPerStep = 5;
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!documentsLoaded && cmplz_settings.is_premium) fetchData();
  }, [documentsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchAllFieldsCompleted();
  }, [fieldsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (region !== '') {
      setCreateBtnDisabled(false);
    } else {
      setCreateBtnDisabled(true);
    }
  }, [region, fetching, editDocumentId]);
  const onChangeHandler = (fieldId, value) => {
    updateField(fieldId, value);
  };
  const download = async () => {
    if (savedDocument.downloadUrl !== '') {
      setDownloadBtnDisabled(true);
      const url = savedDocument.download_url;
      try {
        let request = new XMLHttpRequest();
        request.responseType = 'blob';
        request.open('get', url, true);
        request.send();
        request.onreadystatechange = function () {
          if (this.readyState === 4 && this.status === 200) {
            let obj = window.URL.createObjectURL(this.response);
            let element = window.document.createElement('a');
            element.setAttribute('href', obj);
            element.setAttribute('download', savedDocument.title);
            window.document.body.appendChild(element);
            //onClick property
            element.click();
            setDownloadBtnDisabled(false);
            setTimeout(function () {
              window.URL.revokeObjectURL(obj);
            }, 60 * 1000);
          }
        };
      } catch (error) {
        console.error(error);
        setDownloadBtnDisabled(false);
      }
    }
  };
  const saveFields = async () => {
    await save(region);
    showSavedSettingsNotice();
  };
  const saveAndExit = async () => {
    await save(region);
    showSavedSettingsNotice();
    setStep(step + 1);
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (region !== '' && !fetching) {
      setCreateBtnDisabled(false);
    }
  }, [region, fetching]);
  const onCreateHandler = async () => {
    await fetchFields(region);
    setStep(1);
  };

  //select the next 5 fields from fields
  const getStepFields = activeFields => {
    const start = (step - 1) * fieldsPerStep;
    const end = start + fieldsPerStep;
    return activeFields.slice(start, end);
  };
  let visibleFields = fields.filter(field => typeof field.conditionallyDisabled === 'undefined' || field.conditionallyDisabled === false);
  let lastStep = Math.ceil(visibleFields.length / fieldsPerStep);
  let selectedFields = getStepFields(fields);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, !allRequiredFieldsCompleted && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-status cmplz-warning"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Incomplete", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("The wizard has not been completed yet, but this field requires information from the wizard. Please complete the wizard first.", "complianz-gdpr")))), step === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, editDocumentId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-selected-document"
  }, fileName), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_8__["default"], {
    id: 'region_for_databreaches',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Region", "complianz-gdpr"),
    required: true,
    type: 'select'
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_9__["default"], {
    innerRef: scrollAnchor,
    disabled: updating,
    onChange: fieldValue => setRegion(fieldValue),
    options: regions,
    value: region,
    required: true
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_8__["default"], {
    id: 'region_for_databreaches',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Create Data Breach report", 'complianz-gdpr'),
    type: 'button'
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, editDocumentId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-default",
    onClick: () => {
      resetEditDocumentId();
      setStep(0);
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Cancel", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-primary",
    onClick: () => setStep(step + 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Next", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-primary",
    onClick: () => saveFields()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", 'complianz-gdpr'))), !editDocumentId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: createBtnDisabled || loadingFields,
    className: "button button-primary",
    onClick: () => onCreateHandler()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Create", 'complianz-gdpr'), loadingFields && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_5__["default"], {
    name: "loading",
    color: "grey"
  })))))), step > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, step <= lastStep && selectedFields.map((field, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_Field__WEBPACK_IMPORTED_MODULE_3__["default"], {
    key: i,
    index: i,
    field: field,
    isCustomField: true,
    customChangeHandler: (field, value) => onChangeHandler(field, value)
  })), step > lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_DataBreachConclusion__WEBPACK_IMPORTED_MODULE_6__["default"], null)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-default",
    onClick: () => {
      resetEditDocumentId();
      setStep(0);
    }
  }, step <= lastStep && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Cancel", 'complianz-gdpr'), step > lastStep && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Exit", 'complianz-gdpr')), step <= lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default",
    onClick: () => setStep(step - 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Previous", "complianz-gdpr")), step < lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-primary",
    onClick: () => setStep(step + 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Next", "complianz-gdpr"))), step === lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-primary",
    onClick: () => saveAndExit()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Finish", "complianz-gdpr"), updating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_5__["default"], {
    name: "loading",
    color: "grey"
  }))), step > lastStep && savedDocument && savedDocument.has_to_be_reported && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: downloadBtnDisabled,
    className: "button button-primary",
    onClick: () => download()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Download", "complianz-gdpr"))), editDocumentId && step < lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-primary",
    onClick: () => saveFields()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", 'complianz-gdpr')))))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_7__.memo)(CreateDataBreachReport));

/***/ }),

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

/***/ }),

/***/ "./src/Settings/Inputs/SelectInput.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @radix-ui/react-select */ "./node_modules/@radix-ui/react-select/dist/index.module.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");
/* harmony import */ var _SelectInput_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SelectInput.scss */ "./src/Settings/Inputs/SelectInput.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);







const SelectInput = _ref => {
  let {
    value = false,
    onChange,
    required,
    defaultValue,
    disabled,
    options = {},
    innerRef
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-select-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Root, {
    //ref={innerRef}
    value: value,
    defaultValue: defaultValue,
    onValueChange: onChange,
    required: required,
    disabled: disabled && !Array.isArray(disabled)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Trigger, {
    className: "cmplz-select-group__trigger"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Value, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Content, {
    className: "cmplz-select-group__content",
    position: "popper"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollUpButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-up'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Viewport, {
    className: "cmplz-select-group__viewport"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Group, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
    className: 'cmplz-select-group__item',
    key: 0,
    value: ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Select an option", "complianz-gdpr"))), Object.entries(options).map(_ref2 => {
    let [optionValue, optionText] = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
      disabled: Array.isArray(disabled) && disabled.includes(optionValue),
      className: 'cmplz-select-group__item',
      key: optionValue,
      value: optionValue
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, optionText));
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollDownButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(SelectInput));

/***/ }),

/***/ "./src/Settings/Inputs/Input.scss":
/*!****************************************!*\
  !*** ./src/Settings/Inputs/Input.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Inputs/SelectInput.scss":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_DataBreachReports_CreateDataBreachReport_js.js.map