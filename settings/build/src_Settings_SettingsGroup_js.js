"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_SettingsGroup_js"],{

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

/***/ })

}]);
//# sourceMappingURL=src_Settings_SettingsGroup_js.js.map