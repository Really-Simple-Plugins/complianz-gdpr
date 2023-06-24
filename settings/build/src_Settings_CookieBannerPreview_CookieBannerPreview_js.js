"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CookieBannerPreview_CookieBannerPreview_js"],{

/***/ "./src/Settings/CookieBannerPreview/CookieBannerPreview.js":
/*!*****************************************************************!*\
  !*** ./src/Settings/CookieBannerPreview/CookieBannerPreview.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _CookieBannerData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CookieBannerData */ "./src/Settings/CookieBannerPreview/CookieBannerData.js");
/* harmony import */ var react_use__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react-use */ "./node_modules/react-use/esm/useUpdateEffect.js");
/* harmony import */ var _tcf__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./tcf */ "./src/Settings/CookieBannerPreview/tcf.js");
/* harmony import */ var _CookieBannerPreview_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./CookieBannerPreview.scss */ "./src/Settings/CookieBannerPreview/CookieBannerPreview.scss");







/**
 * Render a help notice in the sidebar
 */
const CookieBannerPreview = () => {
  const rootRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)(null);
  const {
    fields,
    updateField,
    getFieldValue,
    getField,
    setChangedField
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const {
    setBannerContainerClass,
    bannerContainerClass,
    cssLoading,
    cssLoaded,
    generatePreviewCss,
    pageLinks,
    selectedBanner,
    selectedBannerId,
    fetchBannerData,
    bannerDataLoaded,
    bannerHtml,
    manageConsentHtml,
    consentType,
    vendorCount,
    setBannerFieldsSynced
  } = (0,_CookieBannerData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  const [timer, setTimer] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const [bannerInitialized, setBannerInitialized] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [tcfActive, setTcfActive] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [InitialCssGenerated, setInitialCssGenerated] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let active = getFieldValue('uses_ad_cookies_personalized') === 'tcf' || getFieldValue('uses_ad_cookies_personalized') === 'yes';
    setTcfActive(active);
  }, [fields]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!bannerDataLoaded) {
      fetchBannerData();
    }
  }, [bannerDataLoaded]);

  //also reload if ab testing is enabled, to get the second banner that may have been added just now.
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    fetchBannerData();
  }, [getFieldValue('a_b_testing_buttons')]);
  (0,react_use__WEBPACK_IMPORTED_MODULE_5__["default"])(() => {
    setUpBanner();
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (bannerDataLoaded) {
      updateField('consent_type', consentType);
      setChangedField('consent_type', consentType);
    }
  }, [consentType]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (bannerDataLoaded) {
      // fill fields with data from selected banner, default the default banner
      let bannerFields = getBannerFields();
      for (const field of bannerFields) {
        if (selectedBanner.hasOwnProperty(field.id)) {
          //load defaults
          let value = selectedBanner[field.id];
          if (value.length === 0 || value.hasOwnProperty('text') && value['text'].length === 0) {
            value = field.default;
          }
          updateField(field.id, value);
        }
      }
      updateField('manage_consent', selectedBanner['revoke']);
      setBannerInitialized(true);
    }
  }, [selectedBannerId, bannerDataLoaded]);

  //should run after banner initialized .
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const run = async () => {
      if (bannerInitialized && !cssLoading) {
        updateField('consent_type', consentType);
        await updatePreview();
        if (consentType === 'optin') {
          let widthChanged = validateBannerWidth();
          if (widthChanged) {
            await updatePreview();
          }
        }
        if (getFieldValue('soft_cookiewall') == 1) {
          setBannerContainerClass('cmplz-soft-cookiewall');
          setTimeout(function () {
            setBannerContainerClass('');
          }, 4000);
        }
        setupClickEvents(true);
      }
    };
    run();
  }, [fields, consentType, selectedBannerId, bannerInitialized]);

  /**
   * delay rendering the preview if the user is still typing
   */
  const updatePreview = () => {
    clearTimeout(timer);
    let bannerFields = getBannerFields();
    if (!InitialCssGenerated) {
      generatePreviewCss(bannerFields);
      setInitialCssGenerated(true);
    } else {
      const newTimer = setTimeout(() => {
        generatePreviewCss(bannerFields);
      }, 500);
      setTimer(newTimer);
    }
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    console.log("banner data tcf");
    if (!tcfActive) return;
    console.log("banner data tcf 2");
    const rootElement = rootRef.current;
    if (!rootRef.current) {
      return;
    }
    console.log("banner data tcf 3");

    // Query the DOM using the root element
    //if tcf, insert categories
    if (consentType === 'optin' && rootElement) {
      console.log("banner data tcf 4");
      let purposesField = getField('tcf_purposes');
      let purposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(purposesField.options, purposesField.value);
      const srcMarketingPurposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.getPurposes)('marketing', false);
      const srcStatisticsPurposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.getPurposes)('statistics', false);
      const marketingPurposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(purposes, srcMarketingPurposes);
      const statisticsPurposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(purposes, srcStatisticsPurposes);
      console.log('marketingPurposes');
      console.log(marketingPurposes);
      let featuresField = getField('tcf_features');
      let features = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(featuresField.options, featuresField.value);
      let specialFeaturesField = getField('tcf_specialFeatures');
      let specialFeatures = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(specialFeaturesField.options, specialFeaturesField.value);
      let specialPurposesField = getField('tcf_specialPurposes');
      let specialPurposes = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.filterArray)(specialPurposesField.options, specialPurposesField.value);
      const marketingPurposesContainer = rootElement.querySelector('.cmplz-tcf .cmplz-marketing .cmplz-description');
      const statisticsPurposesContainer = rootElement.querySelector('.cmplz-tcf .cmplz-statistics .cmplz-description');
      const featuresContainer = rootElement.querySelector('.cmplz-tcf .cmplz-features .cmplz-description');
      const specialFeaturesContainer = rootElement.querySelector('.cmplz-tcf .cmplz-specialfeatures .cmplz-title');
      const specialPurposesContainer = rootElement.querySelector('.cmplz-tcf .cmplz-specialpurposes .cmplz-title');
      let f = rootElement.querySelector('.cmplz-tcf .cmplz-features');
      let sp = rootElement.querySelector('.cmplz-tcf .cmplz-specialpurposes');
      let sf = rootElement.querySelector('.cmplz-tcf .cmplz-specialfeatures');
      let stp = rootElement.querySelector('.cmplz-tcf .cmplz-statistics');
      if (features.length === 0 && f) f.style.display = 'none';
      if (specialPurposes.length === 0 && sp) sp.style.display = 'none';
      if (specialFeatures.length === 0 && sf) sf.style.display = 'none';
      if (statisticsPurposes.length === 0 && stp) stp.style.display = 'none';
      if (marketingPurposesContainer) marketingPurposesContainer.innerHTML = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.concatenateString)(marketingPurposes);
      if (statisticsPurposesContainer) statisticsPurposesContainer.innerHTML = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.concatenateString)(statisticsPurposes);
      if (featuresContainer) featuresContainer.innerHTML = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.concatenateString)(features);
      if (specialFeaturesContainer) specialFeaturesContainer.innerHTML = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.concatenateString)(specialFeatures);
      if (specialPurposesContainer) specialPurposesContainer.innerHTML = (0,_tcf__WEBPACK_IMPORTED_MODULE_3__.concatenateString)(specialPurposes);
    }
  }, [tcfActive, bannerInitialized, bannerDataLoaded, consentType, cssLoading, fields]);

  /**
   * On fields change, update the values in the banner objects
   */
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (bannerInitialized) {
      // fill fields with data from selected banner, default the default banner
      let bannerFields = getBannerFields();
      for (const field of bannerFields) {
        if (selectedBanner.hasOwnProperty(field.id)) {
          selectedBanner[field.id] = field.value;
        }
      }
      setBannerFieldsSynced(true);
    }
  }, [fields]);
  const replace = (string, find, replace) => {
    let re = new RegExp(find, 'g');
    return string.replace(re, replace);
  };
  const htmlDecode = input => {
    var doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
  };
  const setupClickEvents = update => {
    //default hide manage consent button
    let cmplz_manage_consent = document.querySelector('.cmplz-manage-consent');
    let cmplz_banner = document.querySelector('#cmplz-cookiebanner-container .cmplz-cookiebanner');
    if (cmplz_manage_consent) cmplz_manage_consent.style.display = 'none';

    //only do this on updates.
    if (cmplz_banner && update) {
      cmplz_banner.querySelector('.cmplz-view-preferences').style.display = 'block';
      cmplz_banner.querySelector('.cmplz-save-preferences').style.display = 'none';
    }
    document.addEventListener('click', e => {
      if (e.target.closest('.cmplz-manage-consent')) {
        cmplz_banner.style.display = 'block';
        if (cmplz_manage_consent) cmplz_manage_consent.style.display = 'none';
      }
      if (e.target.closest('.cmplz-close') || e.target.closest('.cmplz-accept') || e.target.closest('.cmplz-deny')) {
        cmplz_banner.style.display = 'none';
        if (cmplz_manage_consent) cmplz_manage_consent.style.display = 'block';
      }
      if (e.target.closest('.cmplz-view-preferences')) {
        cmplz_banner.classList.add('cmplz-categories-visible');
        cmplz_banner.querySelector('.cmplz-categories').style.display = 'block';
        cmplz_banner.querySelector('.cmplz-categories').classList.add('cmplz-fade-in');
        cmplz_banner.querySelector('.cmplz-view-preferences').style.display = 'none';
        cmplz_banner.querySelector('.cmplz-save-preferences').style.display = 'block';
      }
      if (e.target.closest('.cmplz-save-preferences')) {
        cmplz_banner.classList.remove('cmplz-categories-visible');
        cmplz_banner.querySelector('.cmplz-categories').style.display = 'none';
        cmplz_banner.querySelector('.cmplz-categories').classList.remove('cmplz-fade-in');
        cmplz_banner.querySelector('.cmplz-view-preferences').style.display = 'block';
        cmplz_banner.querySelector('.cmplz-save-preferences').style.display = 'none';
      }
    });
  };
  const setUpBanner = () => {
    let bannerObject = document.querySelector('#cmplz-cookiebanner-container');
    if (bannerObject) {
      bannerObject.querySelectorAll('.cmplz-links a:not(.cmplz-external), .cmplz-buttons a:not(.cmplz-external)').forEach(docElement => {
        docElement.classList.add('cmplz-hidden');
        for (let pageType in pageLinks) {
          if (pageLinks.hasOwnProperty(pageType) && docElement.classList.contains(pageType)) {
            docElement.setAttribute('href', pageLinks[pageType]['url'] + docElement.getAttribute('data-relative_url'));
            if (docElement.innerText === '{title}') {
              docElement.innerText = htmlDecode(pageLinks[pageType]['title']);
            }
            docElement.classList.remove('cmplz-hidden');
          }
        }
      });
    }
    setupClickEvents(false);
  };
  const getBannerFields = () => {
    return fields.filter(field => field.data_target === 'banner');
  };
  const validateBannerWidth = () => {
    if (getFieldValue('position') === 'bottom') {
      return false;
    }

    //@todo: if TCF, skip
    if (getFieldValue('disable_width_correction') === true) {
      return false;
    }
    if (!document.querySelector('.cmplz-categories')) {
      return;
    }
    //temporarily set cats visibility to visible to be able to measure
    document.querySelector('.cmplz-categories').style.display = 'block';
    //check if cats width is ok
    let cats_width = document.querySelector('.cmplz-categories').offsetWidth;
    document.querySelector('.cmplz-categories').style.display = 'none';
    let message_width = document.querySelector('.cmplz-message').offsetWidth;
    let banner_width = document.querySelector('.cmplz-cookiebanner').offsetWidth;
    let max_banner_change = banner_width * 1.3;
    let new_width_cats = 0;
    let new_width_btns = 0;
    let banner_padding = false;
    let padding_left = window.getComputedStyle(document.querySelector('.cmplz-cookiebanner'), null).getPropertyValue('padding-left');
    let padding_right = window.getComputedStyle(document.querySelector('.cmplz-cookiebanner'), null).getPropertyValue('padding-left');

    //check if the banner padding is in px, and if so get it as int
    if (padding_left.indexOf('px') !== -1 && padding_right.indexOf('px') !== -1) {
      banner_padding = parseInt(padding_left.replace('px', '')) + parseInt(padding_right.replace('px', ''));
    }
    if (cats_width > 0 && banner_padding) {
      if (banner_width - banner_padding > cats_width) {
        let difference = banner_width - 42 - cats_width;
        new_width_cats = parseInt(banner_width) + parseInt(difference);
      }
    }
    let btn_width = 0;
    btn_width = document.querySelectorAll('.cmplz-buttons .cmplz-btn').offsetWidth;
    if (btn_width > message_width) {
      let difference = btn_width - 42 - message_width;
      new_width_btns = parseInt(btn_width) + parseInt(difference);
    }
    let new_width = 0;
    if (new_width_btns > new_width_cats) {
      new_width = new_width_btns;
    } else {
      new_width = new_width_cats;
    }
    if (new_width > banner_width && new_width < max_banner_change) {
      if (new_width % 2 !== 0) new_width++;
      updateField('banner_width', new_width);
      return true;
    }
    return false;
  };
  const convertLegacyFields = fieldId => {
    //conversion of legacy fieldnames
    let mapping = {
      'use_logo': 'logo',
      'category_all': 'category_marketing',
      'category_stats': 'category_statistics',
      'accept_informational': 'accept_optout',
      'accept': 'accept_optin',
      'view_preferences': 'manage_options',
      'save_preferences': 'save_settings'
    };
    if (mapping.hasOwnProperty(fieldId)) {
      return mapping[fieldId];
    }
    return fieldId;
  };
  if (!bannerDataLoaded) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  }

  //render banner with this data
  let resultHtml = bannerHtml;
  let resultManageConsentHtml = manageConsentHtml;
  let bannerFields = getBannerFields();
  resultHtml = replace(resultHtml, '{consent_type}', consentType);
  resultHtml = replace(resultHtml, '{id}', selectedBanner.ID);
  resultHtml = replace(resultHtml, '{vendor_count}', vendorCount);
  resultManageConsentHtml = replace(resultManageConsentHtml, '{id}', selectedBanner.ID);
  let hidePreview = getFieldValue('hide_preview') == 1 || getFieldValue('disable_cookiebanner') == 1;
  for (const field of bannerFields) {
    if (field.id === 'title') {
      continue;
    }
    let fieldId = convertLegacyFields(field.id);
    if (selectedBanner.hasOwnProperty(field.id)) {
      let fieldValue = selectedBanner[field.id];
      if (field.type === 'text_checkbox' && fieldValue && fieldValue.hasOwnProperty('text')) {
        resultHtml = replace(resultHtml, '{' + fieldId + '}', fieldValue['text']);
      } else if (field.type === 'banner_logo') {
        let replaceLogo = selectedBanner.logo_options[fieldValue] ? selectedBanner.logo_options[fieldValue] : '';
        resultHtml = replace(resultHtml, '{' + fieldId + '}', replaceLogo);
      } else {
        resultHtml = replace(resultHtml, '{' + fieldId + '}', fieldValue);
      }
    }
    if (field.id === 'revoke') {
      resultManageConsentHtml = replace(resultManageConsentHtml, '{manage_consent}', selectedBanner['revoke']);
    }
  }
  if (!cssLoaded || hidePreview) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  }

  //load css file
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-preview-banner-container",
    ref: rootRef
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-cookiebanner-container",
    className: bannerContainerClass,
    dangerouslySetInnerHTML: {
      __html: resultHtml
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-manage-consent",
    "data-nosnippet": "true",
    dangerouslySetInnerHTML: {
      __html: resultManageConsentHtml
    }
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CookieBannerPreview);

/***/ }),

/***/ "./src/Settings/CookieBannerPreview/tcf.js":
/*!*************************************************!*\
  !*** ./src/Settings/CookieBannerPreview/tcf.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   concatenateString: () => (/* binding */ concatenateString),
/* harmony export */   filterArray: () => (/* binding */ filterArray),
/* harmony export */   getPurposes: () => (/* binding */ getPurposes)
/* harmony export */ });
const getPurposes = (category, includeLowerCategories) => {
  //these categories aren't used
  if (category === 'functional' || category === 'preferences') {
    return [];
  }
  if (category === 'marketing') {
    if (includeLowerCategories) {
      return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    } else {
      return [1, 2, 3, 4, 5, 6, 10];
    }
  } else if (category === 'statistics') {
    return [1, 7, 8, 9];
  }
};
const filterArray = (arrayToFilter, arrayToFilterBy) => {
  if (!arrayToFilter) {
    arrayToFilter = {};
  }
  if (!Array.isArray(arrayToFilterBy)) {
    arrayToFilterBy = Object.keys(arrayToFilter);
  }
  const keysToFilterBy = arrayToFilterBy.map(item => parseInt(item));
  return Object.keys(arrayToFilter).filter(key => keysToFilterBy.includes(parseInt(key))).map(key => arrayToFilter[key]);
};
const concatenateString = array => {
  let string = '';
  const max = array.length - 1;
  for (var key in array) {
    if (array.hasOwnProperty(key)) {
      string += array[key];
      if (key < max) {
        string += ', ';
      } else {
        string += '.';
      }
    }
  }
  return string;
};

/***/ }),

/***/ "./src/Settings/CookieBannerPreview/CookieBannerPreview.scss":
/*!*******************************************************************!*\
  !*** ./src/Settings/CookieBannerPreview/CookieBannerPreview.scss ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/react-use/esm/useFirstMountState.js":
/*!**********************************************************!*\
  !*** ./node_modules/react-use/esm/useFirstMountState.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   useFirstMountState: () => (/* binding */ useFirstMountState)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

function useFirstMountState() {
    var isFirst = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(true);
    if (isFirst.current) {
        isFirst.current = false;
        return true;
    }
    return isFirst.current;
}


/***/ }),

/***/ "./node_modules/react-use/esm/useUpdateEffect.js":
/*!*******************************************************!*\
  !*** ./node_modules/react-use/esm/useUpdateEffect.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _useFirstMountState__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./useFirstMountState */ "./node_modules/react-use/esm/useFirstMountState.js");


var useUpdateEffect = function (effect, deps) {
    var isFirstMount = (0,_useFirstMountState__WEBPACK_IMPORTED_MODULE_1__.useFirstMountState)();
    (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
        if (!isFirstMount) {
            return effect();
        }
    }, deps);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useUpdateEffect);


/***/ })

}]);
//# sourceMappingURL=src_Settings_CookieBannerPreview_CookieBannerPreview_js.js.map