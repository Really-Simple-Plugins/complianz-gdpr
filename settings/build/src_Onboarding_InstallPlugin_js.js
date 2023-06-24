"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Onboarding_InstallPlugin_js"],{

/***/ "./src/Onboarding/InstallPlugin.js":
/*!*****************************************!*\
  !*** ./src/Onboarding/InstallPlugin.js ***!
  \*****************************************/
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
/* harmony import */ var _OnboardingData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OnboardingData */ "./src/Onboarding/OnboardingData.js");




const InstallPlugin = _ref => {
  let {
    plugin,
    processing
  } = _ref;
  const {
    pluginAction
  } = (0,_OnboardingData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const installPlugin = async slug => {
    await pluginAction(slug, 'install_plugin');
    await pluginAction(slug, 'activate_plugin');
  };
  const activatePlugin = async slug => {
    await pluginAction(slug, 'activate_plugin');
  };
  let iconColor = 'grey';
  let iconName = processing || plugin.processing ? 'loading' : 'info';
  if (plugin.status === 'activated') {
    iconColor = 'green';
    iconName = 'circle-check';
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-onboarding-item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_1__["default"], {
    name: iconName,
    color: iconColor,
    size: 14
  }), plugin.description, "\xA0", plugin.status === 'not-installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    onClick: e => installPlugin(plugin.slug)
  }, !plugin.processing && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Install", "complianz-gdpr"), plugin.processing && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("installing...", "complianz-gdpr"))), plugin.status === 'installed' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    onClick: e => activatePlugin(plugin.slug)
  }, !plugin.processing && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Activate", "complianz-gdpr"), plugin.processing && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("activating...", "complianz-gdpr"))), plugin.status === 'activated' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Installed!", "complianz-gdpr")));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InstallPlugin);

/***/ }),

/***/ "./src/Onboarding/OnboardingData.js":
/*!******************************************!*\
  !*** ./src/Onboarding/OnboardingData.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/api */ "./src/utils/api.js");




const useOnboardingData = (0,zustand__WEBPACK_IMPORTED_MODULE_2__.create)((set, get) => ({
  loaded: false,
  plugins: [{
    'slug': 'complianz-terms-conditions',
    'description': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Need Terms & Conditions? Configure now.", "complianz-gdpr"),
    'status': 'not-installed',
    'processing': false
  }, {
    'slug': 'burst-statistics',
    'description': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Privacy-Friendly Analytics? Here you go!", "complianz-gdpr"),
    'status': 'not-installed',
    'processing': false
  }, {
    'slug': 'really-simple-ssl',
    'description': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Really Simple Security? Install now!", "complianz-gdpr"),
    'status': 'not-installed',
    'processing': false
  }],
  isUpgrade: false,
  processing: true,
  email: '',
  includeTips: false,
  sendTestEmail: true,
  actionStatus: '',
  modalVisible: true,
  setIncludeTips: includeTips => {
    set(state => ({
      includeTips
    }));
  },
  setSendTestEmail: sendTestEmail => {
    set(state => ({
      sendTestEmail
    }));
  },
  setEmail: email => {
    set(state => ({
      email
    }));
  },
  dismissModal: () => {
    const url = new URL(window.location.href);
    url.searchParams.delete('onboarding');
    window.history.pushState({}, '', url.href);
    set(state => ({
      modalVisible: false
    }));
  },
  saveEmail: async () => {
    let data = {};
    data.email = get().email;
    data.includeTips = get().includeTips;
    data.sendTestEmail = get().sendTestEmail;
    set(state => ({
      processing: true
    }));
    await _utils_api__WEBPACK_IMPORTED_MODULE_1__.doAction('update_email', data).then(response => {
      return response;
    });
    set(() => ({
      processing: false
    }));
  },
  getRecommendedPluginsStatus: async () => {
    const data = {};
    data.plugins = get().plugins;
    const {
      plugins,
      isUpgrade
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_1__.doAction('get_recommended_plugins_status', data).then(async response => {
      return response;
    });
    set({
      processing: false,
      plugins: plugins,
      isUpgrade: isUpgrade,
      loaded: true
    });
  },
  setProcessing: (slug, processing) => {
    set((0,immer__WEBPACK_IMPORTED_MODULE_3__.produce)(state => {
      const pluginIndex = state.plugins.findIndex(plugin => {
        return plugin.slug === slug;
      });
      if (pluginIndex !== -1) {
        state.plugins[pluginIndex].processing = processing;
      }
    }));
  },
  pluginAction: async (slug, action) => {
    const data = {};
    data.slug = slug;
    data.plugins = get().plugins;
    get().setProcessing(slug, true);
    const {
      plugins
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_1__.doAction(action, data).then(async response => {
      return response;
    });
    set({
      plugins: plugins
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useOnboardingData);

/***/ })

}]);
//# sourceMappingURL=src_Onboarding_InstallPlugin_js.js.map