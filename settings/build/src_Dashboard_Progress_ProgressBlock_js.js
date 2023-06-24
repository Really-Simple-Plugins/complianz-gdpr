"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_Progress_ProgressBlock_js"],{

/***/ "./src/Dashboard/Progress/ProgressBlock.js":
/*!*************************************************!*\
  !*** ./src/Dashboard/Progress/ProgressBlock.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _TaskElement__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../TaskElement */ "./src/Dashboard/TaskElement.js");
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _ProgressData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");







const ProgressBlock = () => {
  const {
    percentageCompleted,
    filter,
    notices,
    progressLoaded,
    fetchProgressData,
    error,
    addNotice
  } = (0,_ProgressData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const {
    fetchAllFieldsCompleted,
    allRequiredFieldsCompleted,
    fields
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const run = async () => {
      if (!progressLoaded) {
        await fetchProgressData();
      }
      fetchAllFieldsCompleted();
    };
    run();
  }, [filter, fields]);
  const getStyles = () => {
    return Object.assign({}, {
      width: percentageCompleted + "%"
    });
  };
  let progressBarColor = '';
  if (percentageCompleted < 80) {
    progressBarColor += 'cmplz-orange';
  }
  if (!progressLoaded || error) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_3__["default"], {
      lines: "9",
      error: error
    });
  }
  let noticesOutput = notices;
  if (filter === 'remaining') {
    noticesOutput = noticesOutput.filter(function (notice) {
      return notice.status !== 'completed';
    });
  }
  if (!allRequiredFieldsCompleted && noticesOutput.filter(notice => notice.id === 'all_fields_completed').length === 0) {
    let notice = {
      id: 'all_fields_completed',
      status: 'urgent',
      message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz-gdpr')
    };
    noticesOutput.push(notice);
  }
  if (allRequiredFieldsCompleted) {
    noticesOutput = noticesOutput.filter(notice => notice.id !== 'all_fields_completed');
  }

  //sorting by status
  noticesOutput.sort(function (a, b) {
    if (a.status === b.status) {
      return 0;
    } else {
      return a.status < b.status ? 1 : -1;
    }
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-block"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-bar"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'cmplz-bar ' + progressBarColor,
    style: getStyles()
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-progress-text"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", {
    className: "cmplz-progress-percentage"
  }, percentageCompleted, "%"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", {
    className: "cmplz-progress-text-span"
  }, percentageCompleted < 100 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Consent Management is activated on your site.', 'complianz-gdpr') + ' ', percentageCompleted < 100 && notices.length === 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('You still have 1 task open.', 'complianz-gdpr'), percentageCompleted < 100 && notices.length > 1 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('You still have %s tasks open.', 'complianz-gdpr').replace('%s', notices.length), percentageCompleted === 100 && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Well done! Your website is ready for your selected regions.', 'complianz-gdpr'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-scroll-container"
  }, noticesOutput.map((notice, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_TaskElement__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: i,
    notice: notice
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressBlock);

/***/ }),

/***/ "./src/Dashboard/TaskElement.js":
/*!**************************************!*\
  !*** ./src/Dashboard/TaskElement.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");
/* harmony import */ var _utils_sleeper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/sleeper */ "./src/utils/sleeper.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Progress/ProgressData */ "./src/Dashboard/Progress/ProgressData.js");
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../Menu/MenuData */ "./src/Menu/MenuData.js");









const TaskElement = props => {
  const {
    dismissNotice,
    fetchProgressData
  } = (0,_Progress_ProgressData__WEBPACK_IMPORTED_MODULE_7__["default"])();
  const {
    getField,
    setHighLightField,
    fetchFieldsData
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const {
    setSelectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_8__["default"])();
  const handleClick = async () => {
    setHighLightField(props.notice.highlight_field_id);
    let highlightField = getField(props.notice.highlight_field_id);
    await setSelectedSubMenuItem(highlightField.menu_id);
  };
  const handleClearCache = async cache_id => {
    let data = {};
    data.cache_id = cache_id;
    _utils_api__WEBPACK_IMPORTED_MODULE_4__.doAction('clear_cache', data).then(async response => {
      const notice = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').createNotice('success', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Re-started test', 'complianz-gdpr'), {
        __unstableHTML: true,
        id: 'cmplz_clear_cache',
        type: 'snackbar',
        isDismissible: true
      }).then((0,_utils_sleeper__WEBPACK_IMPORTED_MODULE_5__["default"])(3000)).then(response => {
        (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.dispatch)('core/notices').removeNotice('rsssl_clear_cache');
      });
      await fetchFieldsData();
      await fetchProgressData();
    });
  };
  let notice = props.notice;
  let premium = notice.icon === 'premium';
  //treat links to complianz.io and internal links different.
  let urlIsExternal = notice.url && notice.url.indexOf('complianz.io') !== -1;
  let statusNice = notice.status.charAt(0).toUpperCase() + notice.status.slice(1);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-element"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: 'cmplz-task-status cmplz-' + notice.status
  }, statusNice), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "cmplz-task-message",
    dangerouslySetInnerHTML: {
      __html: notice.message
    }
  }), urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    target: "_blank",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("More info", "complianz-gdpr")), notice.clear_cache_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClearCache(notice.clear_cache_id)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Re-check", "complianz-gdpr")), !premium && !urlIsExternal && notice.url && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: "cmplz-task-enable button button-secondary",
    href: notice.url
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), !premium && notice.highlight_field_id && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-task-enable button button-secondary",
    onClick: () => handleClick()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Fix", "complianz-gdpr")), notice.plusone && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "cmplz-plusone"
  }, "1"), notice.dismissible && notice.status !== 'completed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-task-dismiss"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    onClick: e => dismissNotice(notice.id)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: "times"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TaskElement);

/***/ }),

/***/ "./src/utils/sleeper.js":
/*!******************************!*\
  !*** ./src/utils/sleeper.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/*
 * helper function to delay after a promise
 * @param ms
 * @returns {function(*): Promise<unknown>}
 */
const sleeper = ms => {
  return function (x) {
    return new Promise(resolve => setTimeout(() => resolve(x), ms));
  };
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (sleeper);

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_Progress_ProgressBlock_js.js.map