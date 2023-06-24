"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_InstallPlugin_InstallPlugin_js"],{

/***/ "./src/Settings/InstallPlugin/InstallPlugin.js":
/*!*****************************************************!*\
  !*** ./src/Settings/InstallPlugin/InstallPlugin.js ***!
  \*****************************************************/
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
/* harmony import */ var _InstallPluginData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./InstallPluginData */ "./src/Settings/InstallPlugin/InstallPluginData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);






const InstallPlugin = _ref => {
  let {
    field
  } = _ref;
  const {
    statusLoaded,
    startPluginAction,
    apiRequestActive,
    pluginAction,
    rating
  } = (0,_InstallPluginData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const slug = field.plugin_data.slug;
  const title = field.plugin_data.title;
  const summary = field.plugin_data.summary;
  const description = field.plugin_data.description;
  const image = field.plugin_data.image;
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    //get initial data
    if (!statusLoaded) {
      startPluginAction(slug);
    }
  }, []);
  const onClickHandler = () => {
    startPluginAction(slug);
  };
  const wpStarRating = () => {
    // Calculate the number of each type of star needed.
    let fullStars = Math.floor(rating);
    let halfStars = Math.ceil(rating - fullStars);
    let emptyStars = 5 - fullStars - halfStars;
    fullStars = createArrayFromInt(fullStars);
    halfStars = createArrayFromInt(halfStars);
    emptyStars = createArrayFromInt(emptyStars);
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "star-rating"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "screen-reader-text"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('%s rating based on %d ratings', 'complianz-gdpr').replace('%s', '5').replace('%d', '84')), fullStars.map((star, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "star star-full",
      "aria-hidden": "true"
    })), halfStars.map((star, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "star star-half",
      "aria-hidden": "true"
    })), emptyStars.map((star, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "star star-empty",
      "aria-hidden": "true"
    })));
  };
  const createArrayFromInt = n => {
    let arr = [];
    for (let i = 1; i <= n; i++) {
      arr.push(i);
    }
    return arr;
  };
  let disabled = apiRequestActive;
  let installed = pluginAction === 'installed';
  let buttonString = '';
  switch (pluginAction) {
    case 'upgrade-to-premium':
      buttonString = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Upgrade", "complianz-gdpr");
      break;
    case 'activate':
      buttonString = apiRequestActive ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Activating", "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Activate", "complianz-gdpr");
      break;
    case 'download':
      buttonString = apiRequestActive ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Installing", "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Install", "complianz-gdpr");
      break;
    default:
      disabled = true;
      buttonString = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Installed", "complianz-gdpr");
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "cmplz-suggested-plugin-img",
    src: cmplz_settings.plugin_url + '/upgrade/img/' + image
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin-group-title"
  }, title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin-group-desc"
  }, summary), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin-group-rating"
  }, wpStarRating())), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-suggested-plugin-desc-long"
  }, description), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    disabled: disabled,
    onClick: e => onClickHandler(e),
    className: "button-secondary cmplz-install-plugin"
  }, buttonString, apiRequestActive && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: "loading",
    color: "grey"
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_4__.memo)(InstallPlugin));

/***/ }),

/***/ "./src/Settings/InstallPlugin/InstallPluginData.js":
/*!*********************************************************!*\
  !*** ./src/Settings/InstallPlugin/InstallPluginData.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseInstallPluginData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  apiRequestActive: false,
  pluginAction: 'status',
  wordPressUrl: '#',
  rating: [],
  statusLoaded: false,
  startPluginAction: (slug, action) => {
    let data = {};
    set({
      apiRequestActive: true
    });
    data.pluginAction = typeof action !== 'undefined' ? action : get().pluginAction;
    data.slug = slug;
    let nextAction = false;
    if (data.pluginAction === 'download') {
      nextAction = 'activate';
    }
    _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('plugin_actions', data).then(response => {
      set({
        pluginAction: response.pluginAction,
        wordPressUrl: response.wordpress_url
      }); //'installed', 'download', 'activate', 'upgrade-to-premium'

      //convert to percentage
      let p = Math.round(response.star_rating.rating / 10, 0) / 2;
      set({
        rating: p,
        ratingCount: response.star_rating.rating_count,
        apiRequestActive: false,
        statusLoaded: true
      });
      //if the plugin is installed, go ahead and activate as well
      if (nextAction === 'activate' && response.pluginAction !== 'installed') {
        get().startPluginAction(response.pluginAction);
      }
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseInstallPluginData);

/***/ })

}]);
//# sourceMappingURL=src_Settings_InstallPlugin_InstallPlugin_js.js.map