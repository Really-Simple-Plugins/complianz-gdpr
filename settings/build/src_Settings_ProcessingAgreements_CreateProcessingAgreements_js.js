"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ProcessingAgreements_CreateProcessingAgreements_js"],{

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

/***/ "./src/Settings/Inputs/TextInput.js":
/*!******************************************!*\
  !*** ./src/Settings/Inputs/TextInput.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");



const TextInput = _ref => {
  let {
    value,
    onChange,
    required,
    defaultValue,
    disabled,
    id,
    name,
    placeholder
  } = _ref;
  const inputId = id || name;
  const [inputValue, setInputValue] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)('');

  //ensure that the initial value is set
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    setInputValue(value || '');
  }, [value]);

  //because an update on the entire Fields array is costly, we only update after the user has stopped typing
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    // skip first render
    const typingTimer = setTimeout(() => {
      onChange(inputValue);
    }, 500);
    return () => {
      clearTimeout(typingTimer);
    };
  }, [inputValue]);
  const handleChange = value => {
    setInputValue(value);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-text-input-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: inputId,
    name: name,
    value: inputValue,
    onChange: event => handleChange(event.target.value),
    required: required,
    disabled: disabled,
    className: "cmplz-text-input-group__input",
    placeholder: placeholder
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(TextInput));

/***/ }),

/***/ "./src/Settings/ProcessingAgreements/CreateProcessingAgreements.js":
/*!*************************************************************************!*\
  !*** ./src/Settings/ProcessingAgreements/CreateProcessingAgreements.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _ProcessingAgreementsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProcessingAgreementsData */ "./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _Fields_Field__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Fields/Field */ "./src/Settings/Fields/Field.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _utils_upload__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../utils/upload */ "./src/utils/upload.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../Inputs/SelectInput */ "./src/Settings/Inputs/SelectInput.js");
/* harmony import */ var _Inputs_TextInput__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../Inputs/TextInput */ "./src/Settings/Inputs/TextInput.js");
/* harmony import */ var _Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../Fields/LabelWrapper */ "./src/Settings/Fields/LabelWrapper.js");













const CreateProcessingAgreements = () => {
  const {
    fields,
    fileName,
    fetching,
    loadingFields,
    updating,
    regions,
    resetEditDocumentId,
    fetchData,
    fetchFields,
    updateField,
    save,
    editDocumentId,
    region,
    setRegion,
    serviceName,
    setServiceName
  } = (0,_ProcessingAgreementsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [createBtnDisabled, setCreateBtnDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [step, setStep] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const {
    allRequiredFieldsCompleted,
    fetchAllFieldsCompleted,
    fieldsLoaded,
    addHelpNotice,
    showSavedSettingsNotice,
    removeHelpNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();

  // const {addNotice,fetchProgressData, progressLoaded} = useProgress();
  let scrollAnchor = React.createRef();
  const [file, setFile] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [uploading, setUploading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [uploadDisabled, setUploadDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (editDocumentId && scrollAnchor.current) {
      scrollAnchor.current.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  }, [editDocumentId]);
  const fieldsPerStep = 5;
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchAllFieldsCompleted();
  }, [fieldsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (region !== '' && serviceName !== '') {
      setCreateBtnDisabled(false);
    } else {
      setCreateBtnDisabled(true);
    }
  }, [region, serviceName, fetching, editDocumentId]);
  const onChangeHandler = (fieldId, value) => {
    updateField(fieldId, value);
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const handleUpload = async () => {
      if (!file) return;
      if (file.type !== 'application/pdf' && file.type !== 'application/doc' && file.type !== 'application/docx') {
        setUploadDisabled(true);
        addHelpNotice('create-processing-agreements', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("You can only upload .pdf, .doc or .docs files", "complianz-gdpr"), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Incorrect extension", "complianz-gdpr"), false);
      } else {
        setUploadDisabled(false);
        removeHelpNotice('create-processing-agreements');
      }
      if (file) {
        setCreateBtnDisabled(true);
      }
    };
    handleUpload();
  }, [file]);
  const onUploadHandler = e => {
    setUploadDisabled(true);
    setUploading(true);
    (0,_utils_upload__WEBPACK_IMPORTED_MODULE_7__.upload)('upload_processing_agreement', file, {
      region: region,
      serviceName: serviceName
    }).then(response => {
      if (response.data.upload_success) {
        showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Settings imported", "complianz-gdpr"));
      } else {
        addHelpNotice('import_settings', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("You can only upload .json files", "complianz-gdpr"), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Incorrect extension", "complianz-gdpr"), false);
      }
      setUploading(false);
      setFile(false);
      resetEditDocumentId();
      fetchData();
      return true;
    }).catch(error => {
      console.error(error);
    });
  };
  const saveFields = async () => {
    await save(region, serviceName);
    showSavedSettingsNotice();
  };
  const saveAndExit = async () => {
    await save(region, serviceName);
    setStep(0);
    showSavedSettingsNotice();
    resetEditDocumentId();
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (region !== '' && serviceName !== '' && !fetching) {
      setCreateBtnDisabled(false);
    }
  }, [region, serviceName, fetching]);
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
  let visibleFields = fields.filter(field => field => typeof field.conditionallyDisabled === 'undefined' || field.conditionallyDisabled === false);
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
  }, fileName), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_11__["default"], {
    id: 'region_for_processing_agreement',
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
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_LabelWrapper__WEBPACK_IMPORTED_MODULE_11__["default"], {
    id: 'servicename_for_processing_agreement',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Service name", "complianz-gdpr"),
    required: true,
    type: 'text'
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_TextInput__WEBPACK_IMPORTED_MODULE_10__["default"], {
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("e.g. Marketing Agency", "complianz-gdpr"),
    onChange: fieldValue => setServiceName(fieldValue),
    value: serviceName ? serviceName : '',
    disabled: updating,
    required: true
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
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
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", 'complianz-gdpr'))), !editDocumentId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, file && file.name, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.FormFileUpload, {
    accept: "",
    icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_6__["default"], {
      name: "upload",
      color: "black"
    }) //formfile upload overrides size prop. We override that in the icon component
    ,
    onChange: event => setFile(event.currentTarget.files[0])
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Select file", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: uploadDisabled,
    className: "button button-default",
    onClick: e => onUploadHandler(e)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Upload", "complianz-gdpr"), uploading && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_6__["default"], {
    name: "loading",
    color: "grey"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: createBtnDisabled || loadingFields,
    className: "button cmplz-button button-primary",
    onClick: () => onCreateHandler()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Create", 'complianz-gdpr'), loadingFields && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_6__["default"], {
    name: "loading",
    color: "grey"
  })))))), step > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, step <= lastStep && selectedFields.map((field, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Fields_Field__WEBPACK_IMPORTED_MODULE_4__["default"], {
    key: i,
    index: i,
    field: field,
    isCustomField: true,
    customChangeHandler: (field, value) => onChangeHandler(field, value)
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
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
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Cancel", 'complianz-gdpr')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default",
    onClick: () => setStep(step - 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Previous", "complianz-gdpr")), step < lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-primary",
    onClick: () => setStep(step + 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Next", "complianz-gdpr"))), step === lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-primary",
    onClick: () => saveAndExit()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Finish", "complianz-gdpr"), updating && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_6__["default"], {
    name: "loading",
    color: "grey"
  }))), editDocumentId && step < lastStep && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: updating,
    className: "button button-primary",
    onClick: () => saveFields()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", 'complianz-gdpr')))))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_8__.memo)(CreateProcessingAgreements));

/***/ }),

/***/ "./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js":
/*!***********************************************************************!*\
  !*** ./src/Settings/ProcessingAgreements/ProcessingAgreementsData.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_updateFieldsListWithConditions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/updateFieldsListWithConditions */ "./src/utils/updateFieldsListWithConditions.js");




const useProcessingAgreementsData = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  documentsLoaded: false,
  region: '',
  fileName: '',
  serviceName: '',
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
      region: '',
      serviceName: ''
    });
  },
  editDocument: async (e, id) => {
    e.preventDefault();
    set({
      updating: true
    });
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('load_processing_agreement', {
      id: id
    }).then(response => {
      set({
        fields: response.fields,
        region: response.region,
        serviceName: response.serviceName,
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
  setServiceName: serviceName => {
    set({
      serviceName: serviceName
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
  save: async (region, serviceName) => {
    set({
      updating: true
    });
    let postId = get().editDocumentId;
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('save_processing_agreement', {
      fields: get().fields,
      region: region,
      serviceName: serviceName,
      post_id: postId
    }).then(response => {
      set({
        updating: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
    get().fetchData();
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
    await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('delete_processing_agreement', data).then(response => {
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
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_processing_agreements', data).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
    set(() => ({
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
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_processing_agreement_fields', data).then(response => {
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useProcessingAgreementsData);

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
//# sourceMappingURL=src_Settings_ProcessingAgreements_CreateProcessingAgreements_js.js.map