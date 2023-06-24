"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CookieBannerPreview_BannerLogoControl_js"],{

/***/ "./src/Settings/CookieBannerPreview/BannerLogoControl.js":
/*!***************************************************************!*\
  !*** ./src/Settings/CookieBannerPreview/BannerLogoControl.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Inputs/SelectInput */ "./src/Settings/Inputs/SelectInput.js");
/* harmony import */ var _CookieBannerData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CookieBannerData */ "./src/Settings/CookieBannerPreview/CookieBannerData.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);






const BannerLogoControl = props => {
  const {
    customizeUrl,
    selectedBanner,
    bannerDataLoaded
  } = (0,_CookieBannerData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const {
    updateField,
    setChangedField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const onChangeHandler = value => {
    updateField(props.id, value);
    setChangedField(props.id, value);
    document.querySelector('.cmplz-cookiebanner .cmplz-logo').innerHTML = selectedBanner.logo_options[value];
  };

  //document.querySelector('.cmplz-logo-preview.cmplz-theme-image a').attr('href', customizeUrl);
  let previewClass = "cmplz-logo-preview";
  if (props.value === 'complianz') {
    previewClass += ' cmplz-complianz-logo';
  } else if (props.value === 'site') {
    previewClass += ' cmplz-theme-image';
  }
  let frame;
  const runUploader = event => {
    // If the media frame already exists, reopen it.
    if (frame) {
      frame.open();
      return;
    }

    // Create a new media frame
    frame = wp.media({
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Select a logo', 'complianz-gdpr'),
      button: {
        text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Set logo', 'complianz-gdpr')
      },
      multiple: false // Set to true to allow multiple files to be selected
    });

    // When an image is selected in the media frame...
    frame.on('select', function () {
      var length = frame.state().get("selection").length;
      var images = frame.state().get("selection").models;
      for (var iii = 0; iii < length; iii++) {
        var thumbnail_id = images[iii].id;
        var image = false;
        image = images[iii].attributes.sizes['cmplz_banner_image'];
        if (!image) {
          image = images[iii].attributes.sizes['medium'];
        }
        if (!image) {
          image = images[iii].attributes.sizes['thumbnail'];
        }
        if (!image) {
          image = images[iii].attributes.sizes['full'];
        }
        if (image) {
          var image_url = image['url'];
          updateField('logo_attachment_id', thumbnail_id);
          setChangedField('logo_attachment_id', thumbnail_id);
          let img = document.createElement("img");
          document.querySelector(".cmplz-cookiebanner .cmplz-logo").appendChild(img);
          document.querySelector('.cmplz-cookiebanner .cmplz-logo img').src = image_url;
          document.querySelector('.cmplz-custom-image img').src = image_url;
        }
      }
    });

    // Finally, open the modal on click
    frame.open();
  };
  //https://wordpress.stackexchange.com/questions/368238/how-use-wp-media-upload-liberary-in-react-components
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-logo-container"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_1__["default"]
  // disabled={ props.disabled }
  , {
    label: props.label,
    onChange: fieldValue => onChangeHandler(fieldValue),
    value: props.value,
    options: props.options
  }), props.value === 'complianz' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: previewClass
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    dangerouslySetInnerHTML: {
      __html: selectedBanner.logo_options[props.value]
    }
  })), props.value === 'site' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: previewClass
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    dangerouslySetInnerHTML: {
      __html: selectedBanner.logo_options[props.value]
    }
  }), props.value === 'site' && selectedBanner.logo_options[props.value].length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('No logo found. Please add a logo in the customizer.', 'complianz-gdpr')))), props.value === 'custom' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-logo-preview cmplz-clickable",
    onClick: () => runUploader()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-custom-image",
    dangerouslySetInnerHTML: {
      __html: selectedBanner.logo_options[props.value]
    }
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(BannerLogoControl));

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
//# sourceMappingURL=src_Settings_CookieBannerPreview_BannerLogoControl_js.js.map