"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_ThirdParties_ThirdPartyElement_js"],{

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

/***/ }),

/***/ "./src/Settings/ThirdParties/ThirdPartyElement.js":
/*!********************************************************!*\
  !*** ./src/Settings/ThirdParties/ThirdPartyElement.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Menu/MenuData */ "./src/Menu/MenuData.js");






const ThirdPartyElement = props => {
  const {
    updateField,
    setChangedField
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const {
    selectedMainMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const onChangeHandler = (e, id) => {
    let thirdParties = [...props.field.value];
    if (!Array.isArray(thirdParties)) {
      thirdParties = [];
    }

    //update thirdParty with index props.index
    let currentThirdParty = {
      ...thirdParties[props.index]
    };
    currentThirdParty[id] = e.target.value;
    thirdParties[props.index] = currentThirdParty;
    updateField(props.field.id, thirdParties);
    setChangedField(props.field.id, thirdParties);
  };
  const onDeleteHandler = async e => {
    let thirdParties = props.field.value;
    if (!Array.isArray(thirdParties)) {
      thirdParties = [];
    }

    //remove thirdParty by props.index
    let thirdPartiesCopy = [...thirdParties];
    if (thirdPartiesCopy.hasOwnProperty(props.index)) {
      thirdPartiesCopy.splice(props.index, 1);
    }
    updateField(props.field.id, thirdPartiesCopy);
    setChangedField(props.field.id, thirdPartiesCopy);
    await saveFields(selectedMainMenuItem, false, false);
  };
  const Details = thirdParty => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Name", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      onChange: e => onChangeHandler(e, 'name'),
      type: "text",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Name", "complianz-gdpr"),
      value: thirdParty.name
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Country", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      onChange: e => onChangeHandler(e, 'country'),
      type: "text",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Country", "complianz-gdpr"),
      value: thirdParty.country
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Purpose", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      onChange: e => onChangeHandler(e, 'purpose'),
      type: "text",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Purpose", "complianz-gdpr"),
      value: thirdParty.purpose
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Data", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      onChange: e => onChangeHandler(e, 'data'),
      type: "text",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Data", "complianz-gdpr"),
      value: thirdParty.data
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row__buttons"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button button-default cmplz-reset-button",
      onClick: e => onDeleteHandler(e)
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Delete", "complianz-gdpr"))));
  };

  //ensure defaults
  let thirdParty = {
    ...props.thirdParty
  };
  if (!thirdParty.name) thirdParty.name = '';
  if (!thirdParty.purpose) thirdParty.purpose = '';
  if (!thirdParty.country) thirdParty.country = '';
  if (!thirdParty.data) thirdParty.data = '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_1__["default"], {
    summary: thirdParty.name,
    details: Details(thirdParty)
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(ThirdPartyElement));

/***/ })

}]);
//# sourceMappingURL=src_Settings_ThirdParties_ThirdPartyElement_js.js.map