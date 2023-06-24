"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Cookiedatabase_Cookie_js"],{

/***/ "./src/Settings/Cookiedatabase/Cookie.js":
/*!***********************************************!*\
  !*** ./src/Settings/Cookiedatabase/Cookie.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _SyncData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SyncData */ "./src/Settings/Cookiedatabase/SyncData.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");






const CookieDetails = cookie => {
  const {
    getFieldValue,
    showSavedSettingsNotice
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const {
    saving,
    purposesOptions,
    services,
    updateCookie,
    toggleDeleteCookie,
    saveCookie
  } = (0,_SyncData__WEBPACK_IMPORTED_MODULE_4__.UseSyncData)();
  let data = {
    id: '',
    type: '',
    value: ''
  };
  //allow for both '0'/'1' and false/true.
  let useCdbApi = getFieldValue('use_cdb_api') === 'yes';
  let sync = useCdbApi ? cookie.sync == 1 : false;
  let disabled = sync;
  if (saving) {
    disabled = true;
  }
  let cdbLink = false;
  if (cookie.slug.length > 0) {
    let service_slug = !cookie.service ? 'unknown-service' : cookie.service;
    cdbLink = 'https://cookiedatabase.org/cookie/' + service_slug + '/' + cookie.slug;
  }
  const onSaveHandler = async id => {
    await saveCookie(id);
    showSavedSettingsNotice((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Saved cookie", "complianz-gd[r"));
  };
  const onDeleteHandler = async id => {
    await toggleDeleteCookie(id);
  };
  const onChangeHandler = (e, id, type) => {
    updateCookie(id, type, e.target.value);
  };
  const onCheckboxChangeHandler = (e, id, type) => {
    updateCookie(id, type, e.target.checked);
  };
  let retentionDisabled = cookie.name.indexOf('cmplz_') !== -1 ? true : sync;
  let deletedClass = cookie.deleted != 1 ? 'cmplz-reset-button' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Sync cookie with cookiedatabase.org", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: !useCdbApi,
    onChange: e => onCheckboxChangeHandler(e, cookie.ID, 'sync'),
    type: "checkbox",
    checked: sync
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Show cookie on Cookie Policy", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: disabled,
    onChange: e => onCheckboxChangeHandler(e, cookie.ID, 'showOnPolicy'),
    type: "checkbox",
    checked: cookie.showOnPolicy
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Name", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: disabled,
    onChange: e => onChangeHandler(e, cookie.ID, 'name'),
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Name", "complianz-gdpr"),
    value: cookie.name
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Service", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    disabled: disabled,
    onChange: e => onChangeHandler(e, cookie.ID, 'serviceID'),
    value: cookie.serviceID
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: -1,
    value: 0
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Select a service", "complianz-gdpr")), services.map((service, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: i,
    value: service.ID
  }, service.name)))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Expiration", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: retentionDisabled,
    onChange: e => onChangeHandler(e, cookie.ID, 'retention'),
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("1 year", "complianz-gdpr"),
    value: cookie.retention
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Cookie function", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    disabled: disabled,
    onChange: e => onChangeHandler(e, cookie.ID, 'cookieFunction'),
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("e.g. store user ID", "complianz-gdpr"),
    value: cookie.cookieFunction
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Purpose", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    disabled: disabled,
    onChange: e => onChangeHandler(e, cookie.ID, 'purpose'),
    value: cookie.purpose
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: -1,
    value: 0
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Select a purpose", "complianz-gdpr")), purposesOptions.map((purpose, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    key: i,
    value: purpose.name
  }, purpose.name)))), cdbLink && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: cdbLink,
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("View cookie on cookiedatabase.org", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__buttons"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: saving,
    onClick: e => onSaveHandler(cookie.ID),
    className: "button button-default"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "button button-default " + deletedClass,
    onClick: e => onDeleteHandler(cookie.ID)
  }, cookie.deleted == 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Restore", "complianz-gdpr"), cookie.deleted != 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Delete", "complianz-gdpr"))));
};
/**
 * Render a help notice in the sidebar
 */
const Cookie = props => {
  const Icons = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, props.cookie.complete && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("The data for this cookie is complete", "complianz-gdpr"),
      name: "success",
      color: "green"
    }), !props.cookie.complete && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie has missing fields", "complianz-gdpr"),
      name: "times",
      color: "red"
    }), props.cookie.sync && props.cookie.synced && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie has been synchronized with cookiedatabase.org.", 'complianz-gdpr'),
      name: "rotate",
      color: "green"
    }), !props.cookie.synced || !props.cookie.sync && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie is not synchronized with cookiedatabase.org.", 'complianz-gdpr'),
      name: "rotate-error",
      color: "red"
    }), props.cookie.showOnPolicy && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie will be on your Cookie Policy", "complianz-gdpr"),
      name: "file",
      color: "green"
    }), !props.cookie.showOnPolicy && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie is not shown on the Cookie Policy", "complianz-gdpr"),
      name: "file-disabled",
      color: "grey"
    }), props.cookie.old && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie has not been detected on your site in the last three months", "complianz-gdpr"),
      name: "calendar-error",
      color: "red"
    }), !props.cookie.old && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
      tooltip: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This cookie has recently been detected", "complianz-gdpr"),
      name: "calendar",
      color: "green"
    }));
  };
  const getStyles = () => {
    if (props.cookie.deleted != 1) return;
    return Object.assign({}, {
      "backgroundColor": "var(--rsp-red-faded)"
    });
  };
  let comment = '';
  if (props.cookie.deleted == 1) {
    comment = " | " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Deleted', 'complianz-gdpr');
  } else if (!props.cookie.showOnPolicy) {
    comment = " | " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Admin, ignored', 'complianz-gdpr');
  } else if (props.cookie.isMembersOnly) {
    comment = " | " + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Logged in users only, ignored', 'complianz-gdpr');
  }
  let description = props.cookie.name;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_3__["default"], {
    summary: description,
    comment: comment,
    icons: Icons(),
    details: CookieDetails(props.cookie),
    style: getStyles()
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Cookie);

/***/ }),

/***/ "./src/Settings/Panel.js":
/*!*******************************!*\
  !*** ./src/Settings/Panel.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");


const Panel = props => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item",
    key: props.id,
    style: props.style ? props.style : {}
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("details", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("summary", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__title"
  }, props.summary), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__comment"
  }, props.comment), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__icons"
  }, props.icons), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: 'chevron-down',
    size: 18
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list__item__details"
  }, props.details))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Panel);

/***/ })

}]);
//# sourceMappingURL=src_Settings_Cookiedatabase_Cookie_js.js.map