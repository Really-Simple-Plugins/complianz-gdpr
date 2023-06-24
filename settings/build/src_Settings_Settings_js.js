"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Settings_js"],{

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

/***/ "./src/Settings/Help.js":
/*!******************************!*\
  !*** ./src/Settings/Help.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);




/**
 * Render a help notice in the sidebar
 */
const Help = props => {
  let notice = props.help;
  if (!notice.title) {
    notice.title = notice.text;
    notice.text = false;
  }
  let openStatus = props.noticesExpanded ? 'open' : '';
  //we can use notice.linked_field to create a visual link to the field.

  let target = notice.url && notice.url.indexOf("complianz.io") !== -1 ? "_blank" : '_self';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, notice.title && notice.text && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("details", {
    className: "cmplz-wizard-help-notice cmplz-" + notice.label.toLowerCase(),
    open: openStatus
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("summary", null, notice.title, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: "chevron-down"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    dangerouslySetInnerHTML: {
      __html: notice.text
    }
  }), notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-help-more-info"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    target: target,
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("More info", "complianz-gdpr")))), notice.title && !notice.text && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-wizard-help-notice cmplz-" + notice.label.toLowerCase()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, notice.title)));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Help);

/***/ }),

/***/ "./src/Settings/License/LicenseData.js":
/*!*********************************************!*\
  !*** ./src/Settings/License/LicenseData.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseLicenseData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  licenseStatus: cmplz_settings.licenseStatus,
  processing: false,
  licenseNotices: [],
  noticesLoaded: false,
  getLicenseNotices: async () => {
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('license_notices', {}).then(response => {
      return response;
    });
    set(state => ({
      noticesLoaded: true,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  activateLicense: async license => {
    let data = {};
    data.license = license;
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('activate_license', data);
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  deactivateLicense: async () => {
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('deactivate_license');
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseLicenseData);

/***/ }),

/***/ "./src/Settings/Settings.js":
/*!**********************************!*\
  !*** ./src/Settings/Settings.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_lib__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/lib */ "./src/utils/lib.js");
/* harmony import */ var _SettingsGroup__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SettingsGroup */ "./src/Settings/SettingsGroup.js");
/* harmony import */ var _Help__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Help */ "./src/Settings/Help.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var _DocumentsMenu_MenuData__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./DocumentsMenu/MenuData */ "./src/Settings/DocumentsMenu/MenuData.js");
/* harmony import */ var _CookieBannerPreview_CookieBannerData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./CookieBannerPreview/CookieBannerData */ "./src/Settings/CookieBannerPreview/CookieBannerData.js");
/* harmony import */ var _Dashboard_Progress_ProgressData__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../Dashboard/Progress/ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Placeholder_SettingsPlaceholder__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../Placeholder/SettingsPlaceholder */ "./src/Placeholder/SettingsPlaceholder.js");













/**
 * Renders the selected settings
 *
 */
const Settings = () => {
  const [noticesExpanded, setNoticesExpanded] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const {
    progressLoaded,
    notices,
    fetchProgressData
  } = (0,_Dashboard_Progress_ProgressData__WEBPACK_IMPORTED_MODULE_9__["default"])();
  const {
    getFieldNotices,
    fieldNotices,
    fieldNoticesLoaded,
    fieldsLoaded,
    saveFields,
    changedFields,
    fields,
    notCompletedRequiredFields,
    completableFields,
    fetchAllFieldsCompleted,
    nextButtonDisabled
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const {
    subMenuLoaded,
    saveButtonsRequired,
    subMenu,
    selectedSubMenuItem,
    selectedMainMenuItem,
    nextMenuItem,
    previousMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const {
    saveBanner,
    setBannerDataLoaded
  } = (0,_CookieBannerPreview_CookieBannerData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  const {
    saveDocumentsMenu
  } = (0,_DocumentsMenu_MenuData__WEBPACK_IMPORTED_MODULE_7__.UseMenuData)();
  const [CookieBannerPreview, setCookieBannerPreview] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (selectedMainMenuItem === 'banner' && !CookieBannerPreview) {
      __webpack_require__.e(/*! import() */ "src_Settings_CookieBannerPreview_CookieBannerPreview_js").then(__webpack_require__.bind(__webpack_require__, /*! ./CookieBannerPreview/CookieBannerPreview */ "./src/Settings/CookieBannerPreview/CookieBannerPreview.js")).then(_ref => {
        let {
          default: CookieBannerPreview
        } = _ref;
        setCookieBannerPreview(() => CookieBannerPreview);
      });
    }
  }, [selectedMainMenuItem]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    getFieldNotices();
    fetchAllFieldsCompleted();
  }, [fields]);
  const toggleNotices = () => {
    setNoticesExpanded(!noticesExpanded);
  };
  const getProgressBarWidth = () => {
    let fieldsCompletedPercentage = notCompletedRequiredFields.length > 0 ? Math.round((completableFields.length - notCompletedRequiredFields.length) / completableFields.length * 100) : 100;
    return Object.assign({}, {
      width: fieldsCompletedPercentage + "%"
    });
  };
  const saveData = async (finish, showNotice) => {
    const regionIndex = changedFields.findIndex(field => {
      return field.id === 'regions';
    });
    if (regionIndex !== -1) {
      //if the region field is changed, we need to update the banner
      setBannerDataLoaded(false);
    }
    if (selectedSubMenuItem === 'document-menu') {
      await saveFields(selectedSubMenuItem, showNotice, false);
      await saveDocumentsMenu(changedFields.length > 0, showNotice);
    } else if (selectedMainMenuItem === 'banner') {
      await saveBanner(fields);
    } else {
      await saveFields(selectedSubMenuItem, showNotice, finish);
      await fetchProgressData();
    }
  };
  const {
    menu_items: menuItems
  } = subMenu;
  if (!subMenuLoaded || !fieldsLoaded || menuItems.length === 0) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_SettingsPlaceholder__WEBPACK_IMPORTED_MODULE_10__["default"], null);
  }
  let selectedFields = fields.filter(field => field.menu_id === selectedSubMenuItem);
  let groups = [];
  for (const selectedField of selectedFields) {
    if (!(0,_utils_lib__WEBPACK_IMPORTED_MODULE_1__.in_array)(selectedField.group_id, groups)) {
      groups.push(selectedField.group_id);
    }
  }
  let btnSaveText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Save', 'complianz-gdpr');
  let helpNotices = [];

  //add some notices conditionally for fields
  if (fieldNoticesLoaded) {
    for (const fieldNotice of fieldNotices) {
      let noticeFields = selectedFields.filter(field => fieldNotice.field_id === field.id);
      if (noticeFields.length > 0) {
        helpNotices.push(fieldNotice);
      }
    }
  }

  //convert progress notices to an array useful for the help blocks
  if (progressLoaded) {
    for (const notice of notices) {
      let noticeIsLinkedToField = false;

      //notices that are linked to a field. Only in case of warnings.
      if (notice.show_with_options && notice.status === 'warning') {
        let noticeFields = selectedFields.filter(field => notice.show_with_options.includes(field.id));
        noticeIsLinkedToField = noticeFields.length > 0;
      }
      //notices that are linked to a menu id.
      if (noticeIsLinkedToField || notice.menu_id === selectedSubMenuItem) {
        let help = {};
        help.title = notice.title ? notice.title : false;
        help.label = notice.label;
        help.id = notice.id;
        help.text = notice.message;
        help.url = notice.url;
        help.linked_field = notice.show_with_option;
        helpNotices.push(help);
      }
    }
  }

  //help items belonging to a field
  //if field is hidden, hide the notice as well
  for (const notice of selectedFields.filter(field => field.help && !field.conditionallyDisabled)) {
    let help = notice.help;
    //check if the notices array already includes this help item
    //this can happen in case of dynamic fields, like details per purpose
    let existingNotices = helpNotices.filter(noticeItem => noticeItem.id && noticeItem.id === help.id);
    if (existingNotices.length === 0) {
      helpNotices.push(notice.help);
    }
  }
  helpNotices = helpNotices.filter(notice => notice.label.toLowerCase() !== 'completed');
  let cookiebannerEnabled = fields.filter(field => field.id === 'enable_cookie_banner' && field.value === 'yes').length > 0;
  let continueLink = nextButtonDisabled ? `#${selectedMainMenuItem}/${selectedSubMenuItem}` : nextMenuItem;
  let finishLink = cookiebannerEnabled ? `#banner` : `#dashboard`;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-wizard-settings cmplz-column-2"
  }, groups.map((group, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SettingsGroup__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: i,
    index: i,
    group: group,
    fields: selectedFields
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-footer"
  }, selectedMainMenuItem === 'wizard' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-footer-progress-bar"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-bar",
    style: getProgressBarWidth()
  })), selectedMainMenuItem !== 'wizard' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-footer-upsell-bar"
  }, !cmplz_settings.is_premium && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "button button-default",
    href: "https://complianz.io/pricing",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Get Premium", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-grid-item-footer-buttons'
  }, previousMenuItem !== '#' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: previousMenuItem
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Previous', 'complianz-gdpr')), saveButtonsRequired() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default",
    onClick: e => saveData(false, true)
  }, btnSaveText), selectedSubMenuItem !== menuItems[menuItems.length - 1].id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, saveButtonsRequired() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    disabled: nextButtonDisabled,
    className: "button button-primary",
    href: continueLink,
    onClick: e => saveData(false, false)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Save and Continue', 'complianz-gdpr')), !saveButtonsRequired() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "button button-primary",
    href: continueLink,
    onClick: e => saveData(false, false)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Continue', 'complianz-gdpr'))), selectedMainMenuItem === 'wizard' && selectedSubMenuItem === menuItems[menuItems.length - 1].id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    disabled: nextButtonDisabled,
    className: "button button-primary",
    href: finishLink,
    onClick: e => saveData(true, false)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Finish', 'complianz-gdpr')))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-wizard-help"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-help-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-h4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Notifications", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-help-control",
    onClick: () => toggleNotices()
  }, !noticesExpanded && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Expand all", "complianz-gdpr"), noticesExpanded && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Collapse all", "complianz-gdpr"))), helpNotices.map((field, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Help__WEBPACK_IMPORTED_MODULE_3__["default"], {
    key: i,
    noticesExpanded: noticesExpanded,
    help: field,
    fieldId: field.id
  }))), selectedMainMenuItem === 'banner' && CookieBannerPreview && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CookieBannerPreview, null));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Settings);

/***/ }),

/***/ "./src/Settings/SettingsGroup.js":
/*!***************************************!*\
  !*** ./src/Settings/SettingsGroup.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Hyperlink__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/Hyperlink */ "./src/utils/Hyperlink.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var _License_LicenseData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./License/LicenseData */ "./src/Settings/License/LicenseData.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _CookieBannerPreview_CookieBannerData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./CookieBannerPreview/CookieBannerData */ "./src/Settings/CookieBannerPreview/CookieBannerData.js");









/**
 * Render a grouped block of settings
 */
const SettingsGroup = props => {
  const {
    highLightField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  //@todo uncomment uselicense
  const {
    licenseStatus
  } = (0,_License_LicenseData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    bannerDataLoaded
  } = (0,_CookieBannerPreview_CookieBannerData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  let upgrade = 'https://complianz.io/pricing/';
  const {
    subMenu,
    getMenuRegions,
    selectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  let regions = getMenuRegions();
  const [Field, setField] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    Promise.resolve(/*! import() */).then(__webpack_require__.bind(__webpack_require__, /*! ./Fields/Field */ "./src/Settings/Fields/Field.js")).then(_ref => {
      let {
        default: Field
      } = _ref;
      setField(() => Field);
    });
  }, []);
  let selectedFields = [];
  //get all fields with group_id props.group_id
  for (const selectedField of props.fields) {
    if (selectedField.group_id === props.group) {
      selectedFields.push(selectedField);
    }
  }
  let activeGroup;
  //first, set the selected menu item as active group, so we have a default in case there are no groups
  for (const item of subMenu.menu_items) {
    if (item.id === selectedSubMenuItem) {
      activeGroup = item;
    } else if (item.menu_items) {
      activeGroup = item.menu_items.filter(menuItem => menuItem.id === selectedSubMenuItem)[0];
    }
    if (activeGroup) {
      break;
    }
  }

  //now check if we have actual groups
  for (const item of subMenu.menu_items) {
    if (item.id === selectedSubMenuItem && item.hasOwnProperty('groups')) {
      let currentGroup = item.groups.filter(group => group.id === props.group);
      if (currentGroup.length > 0) {
        activeGroup = currentGroup[0];
      }
    }
  }
  if (!activeGroup) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  }
  let msg = activeGroup.premium_text ? activeGroup.premium_text : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Learn more about %sPremium%s", "complianz-gdpr");
  if (cmplz_settings.is_premium) {
    if (licenseStatus === 'empty' || licenseStatus === 'deactivated') {
      msg = cmplz_settings.messageInactive;
    } else {
      msg = cmplz_settings.messageInvalid;
    }
  }
  let disabled = licenseStatus !== 'valid' && activeGroup.premium;
  //if a feature can only be used on networkwide or single site setups, pass that info here.
  upgrade = activeGroup.upgrade ? activeGroup.upgrade : upgrade;
  let helplinkText = activeGroup.helpLink_text ? activeGroup.helpLink_text : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Instructions", "complianz-gdpr");
  let disabledClass = disabled ? 'cmplz-disabled' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item cmplz-" + activeGroup.id + ' ' + disabledClass
  }, activeGroup.title && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
    className: "cmplz-h4"
  }, activeGroup.title), regions.length > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, regions.map((region, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "cmplz-settings-region",
    src: cmplz_settings.plugin_url + '/assets/images/' + region + '.svg'
  })))), regions.length === 0 && activeGroup.helpLink && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Hyperlink__WEBPACK_IMPORTED_MODULE_1__["default"], {
    target: "_blank",
    className: "cmplz-helplink",
    text: helplinkText,
    url: activeGroup.helpLink
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-grid-item-content"
  }, activeGroup.intro && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-block-intro",
    dangerouslySetInnerHTML: {
      __html: activeGroup.intro
    }
  }), Field && selectedFields.map((field, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Field, {
    key: field.id,
    field: field,
    highLightField: highLightField
  }))), disabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-status cmplz-premium"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Upgrade", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, cmplz_settings.is_premium && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, msg, "\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "cmplz-locked-link",
    href: "#settings/license"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Check license", "complianz-gdpr"))), !cmplz_settings.is_premium && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Hyperlink__WEBPACK_IMPORTED_MODULE_1__["default"], {
    target: "_blank",
    text: msg,
    url: upgrade
  })))), subMenu.id === 'banner' && !bannerDataLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-locked-overlay"
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SettingsGroup);

/***/ }),

/***/ "./src/utils/lib.js":
/*!**************************!*\
  !*** ./src/utils/lib.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   in_array: () => (/* binding */ in_array)
/* harmony export */ });
const in_array = (needle, haystack) => {
  let length = haystack.length;
  for (let i = 0; i < length; i++) {
    if (haystack[i] == needle) return true;
  }
  return false;
};

/***/ })

}]);
//# sourceMappingURL=src_Settings_Settings_js.js.map