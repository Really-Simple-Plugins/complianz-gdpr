"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CookieScan_CookieScanControl_js"],{

/***/ "./src/Settings/CookieScan/CookieScanControl.js":
/*!******************************************************!*\
  !*** ./src/Settings/CookieScan/CookieScanControl.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _CookieScanData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CookieScanData */ "./src/Settings/CookieScan/CookieScanData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Cookiedatabase_SyncData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Cookiedatabase/SyncData */ "./src/Settings/Cookiedatabase/SyncData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Details__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Details */ "./src/Settings/CookieScan/Details.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_10__);












const CookieScanControl = () => {
  const {
    loadingSyncData,
    syncProgress,
    setSyncProgress,
    fetchSyncProgressData
  } = (0,_Cookiedatabase_SyncData__WEBPACK_IMPORTED_MODULE_6__.UseSyncData)();
  const {
    initialLoadCompleted,
    loading,
    nextPage,
    progress,
    setProgress,
    cookies,
    fetchProgress,
    hasSyncableData,
    lastLoadedIframe,
    setLastLoadedIframe
  } = (0,_CookieScanData__WEBPACK_IMPORTED_MODULE_2__.UseCookieScanData)();
  const [iframeLoading, setIframeLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const {
    addHelpNotice,
    fieldsLoaded
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  const {
    selectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_7__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (lastLoadedIframe === nextPage) return;
    if (iframeLoading) return;
    setIframeLoading(true);
    loadIframe();
  }, [nextPage, lastLoadedIframe, iframeLoading]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!iframeLoading && !loading && progress < 100) {
      fetchProgress();
    } else if (!iframeLoading && !loading && progress === 100) {}
  }, [iframeLoading, loading, progress]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!fieldsLoaded) return;
    if (window.canRunAds === undefined) {
      addHelpNotice('cookie_scan', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("You are using an ad blocker. This will prevent most cookies from being placed. Please run the scan without an adblocker enabled.", 'complianz-gdpr'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Ad Blocker detected.", 'complianz-gdpr'), null);
    }
    if (doNotTrack()) {
      addHelpNotice('cookie_scan', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Your browser has the Do Not Track or Global Privacy Control setting enabled.", "complianz-gdpr") + "&nbsp;" + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("This will prevent most cookies from being placed.", "complianz-gdpr") + "&nbsp;" + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please run the scan with these browser options disabled.", 'complianz-gdpr'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("DNT or GPC enabled.", 'complianz-gdpr'), null);
    }
  }, [fieldsLoaded]);
  const doNotTrack = () => {
    let dnt = 'doNotTrack' in navigator && navigator.doNotTrack === '1';
    let gpc = 'globalPrivacyControl' in navigator && navigator.globalPrivacyControl;
    return gpc || dnt;
  };
  const loadIframe = () => {
    if (!nextPage) {
      setIframeLoading(false);
      return;
    }
    // Get a handle to the iframe element
    let iframe = document.getElementById("cmplz_cookie_scan_frame");
    if (!iframe) {
      iframe = document.createElement('iframe');
      iframe.setAttribute('id', 'cmplz_cookie_scan_frame');
      iframe.classList.add('hidden');
    }
    iframe.setAttribute('src', nextPage);
    // Check if loading is complete
    iframe.onload = function (response) {
      setTimeout(() => {
        setIframeLoading(false);
        setLastLoadedIframe(nextPage);
      }, 200);
    };
    document.body.appendChild(iframe);
  };
  const getStyles = () => {
    return Object.assign({}, {
      width: progress + "%"
    });
  };
  const Start = async () => {
    let data = {};
    data.scan_action = 'restart';
    setProgress(1);
    await _utils_api__WEBPACK_IMPORTED_MODULE_1__.doAction('scan', data);
    await fetchProgress();
    if (progress === 100) {
      await fetchSyncProgressData();
      if (hasSyncableData) {
        setSyncProgress(1);
      }
    }
  };
  const clearCookies = async () => {
    let data = {};
    data.scan_action = 'reset';
    setProgress(1);
    await _utils_api__WEBPACK_IMPORTED_MODULE_1__.doAction('scan', data);
    await fetchProgress();
    if (progress === 100) {
      await fetchSyncProgressData();
      if (hasSyncableData) {
        setSyncProgress(1);
      }
    }
  };

  //this item can be loaded on other pages, but should then not show anything.
  if (selectedSubMenuItem !== 'cookie-scan') return null;
  let cookieCount = cookies ? cookies.length : 0;
  let description = '';
  if (cookieCount === 0) {
    description = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("No cookies found on your domain yet.", "complianz-gdpr");
  } else if (cookieCount === 1) {
    description = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("The scan found 1 cookie on your domain.", "complianz-gdpr");
  } else {
    description = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("The scan found %s cookies on your domain.", "complianz-gdpr").replace('%s', cookieCount);
  }
  if (progress >= 100) {
    if (cookieCount > 0) description += ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Continue the wizard to categorize cookies and configure consent.', 'complianz-gdpr');
  } else {
    description += ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Scanning, %s complete.', 'complianz-gdpr').replace('%s', Math.round(progress) + '%');
  }
  if (!initialLoadCompleted) {
    description = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_5__["default"], {
      name: "loading",
      color: "grey"
    });
  }
  let scanDisabled = progress < 100 && progress > 0;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: scanDisabled,
    className: "button button-default",
    onClick: e => Start(e)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Scan", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: scanDisabled,
    className: "button button-default cmplz-reset-button",
    onClick: e => clearCookies(e)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Clear Cookies", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-scan-progress"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-bar",
    style: getStyles()
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_4__["default"], {
    summary: description,
    details: (0,_Details__WEBPACK_IMPORTED_MODULE_9__.Details)(initialLoadCompleted, cookies)
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_10__.memo)(CookieScanControl));

/***/ }),

/***/ "./src/Settings/CookieScan/CookieScanData.js":
/*!***************************************************!*\
  !*** ./src/Settings/CookieScan/CookieScanData.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   UseCookieScanData: () => (/* binding */ UseCookieScanData)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseCookieScanData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  initialLoadCompleted: false,
  iframeLoaded: false,
  loading: false,
  nextPage: false,
  progress: 0,
  cookies: [],
  lastLoadedIframe: '',
  setIframeLoaded: iframeLoaded => set({
    iframeLoaded
  }),
  setLastLoadedIframe: lastLoadedIframe => set(state => ({
    lastLoadedIframe
  })),
  setProgress: progress => set({
    progress
  }),
  fetchProgress: () => {
    let data = {};
    set({
      loading: true
    });
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_scan_progress', data).then(response => {
      set({
        initialLoadCompleted: true,
        loading: false,
        nextPage: response.next_page,
        progress: response.progress,
        cookies: response.cookies
      });
      return response;
    });
  }
}));

/***/ }),

/***/ "./src/Settings/CookieScan/Details.js":
/*!********************************************!*\
  !*** ./src/Settings/CookieScan/Details.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Details: () => (/* binding */ Details)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const Details = (initialLoadCompleted, cookies) => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, initialLoadCompleted && cookies.map((cookie, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: i
  }, cookie)));
};

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
//# sourceMappingURL=src_Settings_CookieScan_CookieScanControl_js.js.map