"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Dashboard_OtherPlugins_OtherPlugins_js"],{

/***/ "./src/Dashboard/OtherPlugins/OtherPlugins.js":
/*!****************************************************!*\
  !*** ./src/Dashboard/OtherPlugins/OtherPlugins.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Placeholder/Placeholder */ "./src/Placeholder/Placeholder.js");
/* harmony import */ var _OtherPluginsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OtherPluginsData */ "./src/Dashboard/OtherPlugins/OtherPluginsData.js");





const OtherPlugins = () => {
  const {
    dataLoaded,
    pluginData,
    pluginActions,
    fetchOtherPluginsData,
    error
  } = (0,_OtherPluginsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!dataLoaded) {
      fetchOtherPluginsData();
    }
  }, []);
  const otherPluginElement = (plugin, i) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: "plugin" + i,
      className: "cmplz-other-plugins-element cmplz-" + plugin.slug
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: plugin.wordpress_url,
      target: "_blank",
      title: plugin.title
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-bullet"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-other-plugins-content"
    }, plugin.title)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-other-plugin-status"
    }, plugin.pluginAction === 'upgrade-to-premium' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      target: "_blank",
      href: plugin.upgrade_url
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Upgrade", "complianz-gdpr"))), plugin.pluginAction !== 'upgrade-to-premium' && plugin.pluginAction !== 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: "#",
      onClick: e => pluginActions(plugin.slug, plugin.pluginAction, e)
    }, plugin.pluginActionNice)), plugin.pluginAction === 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Installed", "complianz-gdpr"))));
  };
  if (!dataLoaded || error) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder_Placeholder__WEBPACK_IMPORTED_MODULE_2__["default"], {
      lines: "3",
      error: error
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-other-plugins-container"
  }, pluginData.map((plugin, i) => otherPluginElement(plugin, i))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OtherPlugins);

/***/ }),

/***/ "./src/Dashboard/OtherPlugins/OtherPluginsData.js":
/*!********************************************************!*\
  !*** ./src/Dashboard/OtherPlugins/OtherPluginsData.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);



const useOtherPlugins = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  error: false,
  dataLoaded: false,
  pluginData: [],
  updatePluginData: (slug, newPluginItem) => {
    let pluginData = get().pluginData;
    pluginData.forEach(function (pluginItem, i) {
      if (pluginItem.slug === slug) {
        pluginData[i] = newPluginItem;
      }
    });
    set(state => ({
      dataLoaded: true,
      pluginData: pluginData
    }));
  },
  getPluginData: slug => {
    let pluginData = get().pluginData;
    return pluginData.filter(pluginItem => {
      return pluginItem.slug === slug;
    })[0];
  },
  fetchOtherPluginsData: async () => {
    const {
      pluginData,
      error
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('otherpluginsdata').then(response => {
      let pluginData = [];
      pluginData = response.plugins;
      let error = response.error;
      if (!error) {
        pluginData.forEach(function (pluginItem, i) {
          pluginData[i].pluginActionNice = pluginActionNice(pluginItem.pluginAction);
        });
      }
      return {
        pluginData,
        error
      };
    });
    set(state => ({
      dataLoaded: true,
      pluginData: pluginData,
      error: error
    }));
  },
  pluginActions: (slug, pluginAction, e) => {
    if (e) e.preventDefault();
    let data = {};
    data.slug = slug;
    data.pluginAction = pluginAction;
    let pluginItem = get().getPluginData(slug);
    if (pluginAction === 'download') {
      pluginItem.pluginAction = "downloading";
    } else if (pluginAction === 'activate') {
      pluginItem.pluginAction = "activating";
    }
    pluginItem.pluginActionNice = pluginActionNice(pluginItem.pluginAction);
    get().updatePluginData(slug, pluginItem);
    if (pluginAction === 'installed' || pluginAction === 'upgrade-to-premium') {
      return;
    }
    _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('plugin_actions', data).then(response => {
      pluginItem = response;
      get().updatePluginData(slug, pluginItem);
      get().pluginActions(slug, pluginItem.pluginAction);
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useOtherPlugins);
const pluginActionNice = pluginAction => {
  const statuses = {
    'download': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Install", "really-simple-ssl"),
    'activate': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Activate", "really-simple-ssl"),
    'activating': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Activating...", "really-simple-ssl"),
    'downloading': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Downloading...", "really-simple-ssl"),
    'upgrade-to-premium': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Downloading...", "really-simple-ssl")
  };
  return statuses[pluginAction];
};

/***/ })

}]);
//# sourceMappingURL=src_Dashboard_OtherPlugins_OtherPlugins_js.js.map