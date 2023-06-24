"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Onboarding_Onboarding_js"],{

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

/***/ "./src/Onboarding/Onboarding.js":
/*!**************************************!*\
  !*** ./src/Onboarding/Onboarding.js ***!
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
/* harmony import */ var _InstallPlugin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./InstallPlugin */ "./src/Onboarding/InstallPlugin.js");
/* harmony import */ var _OnboardingData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./OnboardingData */ "./src/Onboarding/OnboardingData.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");







const Onboarding = () => {
  const {
    email,
    setEmail,
    setIncludeTips,
    includeTips,
    sendTestEmail,
    saveEmail,
    setSendTestEmail,
    plugins,
    loaded,
    isUpgrade,
    processing,
    dismissModal,
    modalVisible,
    getRecommendedPluginsStatus
  } = (0,_OnboardingData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [modalStep, setModalStep] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const {
    updateField
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const [waiting, setWaiting] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [nextDisabled, setNextDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const startTour = e => {
    e.preventDefault();
    window.location.href = window.location.href.replace('onboarding', 'tour');
  };
  const steps = ['plugins', 'email'];
  const isValidEmail = email => {
    if (email.length === 0) return true;
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  };
  const goToWizard = async e => {
    e.preventDefault();
    await saveEmail();
    if (isValidEmail(email) && email.length > 0) {
      updateField('notifications_email_address', email);
      updateField('send_notifications_email', true);
    }
    dismissModal();
    window.location.hash = '#wizard';
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!loaded) {
      getRecommendedPluginsStatus();
    }
  }, [loaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (steps[modalStep] === 'plugins') {
      setNextDisabled(true);
      if (!waiting) {
        setNextDisabled(false);
      }
    }
    if (steps[modalStep] === 'email') {
      setNextDisabled(true);
      if (isValidEmail(email)) {
        setNextDisabled(false);
      }
    }
  }, [email, modalStep, waiting]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    //set an interval, to set waiting to false after 1 second.
    const interval = setInterval(() => {
      setWaiting(false);
    }, 2000);
    return () => clearInterval(interval);
  }, []);
  if (!modalVisible) {
    return null;
  }
  let emailClass = isValidEmail(email) ? 'cmplz-valid' : 'cmplz-invalid';
  let processingClass = steps[modalStep] === 'email' && processing ? 'cmplz-processing' : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal-backdrop"
  }, "\xA0"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal cmplz-onboarding"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal-header-branding"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "cmplz-header-logo",
    src: cmplz_settings.plugin_url + 'assets/images/cmplz-logo.svg',
    alt: "Complianz logo"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    className: "cmplz-modal-close",
    "data-dismiss": "modal",
    "aria-label": "Close",
    onClick: () => dismissModal()
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    "aria-hidden": "true",
    focusable: "false",
    role: "img",
    xmlns: "http://www.w3.org/2000/svg",
    viewBox: "0 0 320 512",
    height: "24"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fill: "#000000",
    d: "M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"
  })))), steps[modalStep] === 'plugins' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("A lot has changed, you can take a quick to tour to familiar yourself or discover on your own pace. If you have any questions, let us know, but for now: ", "complianz-gdpr"), "\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "https://complianz.io/meet-complianz-7",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Meet Complianz 7.0", "complianz-gdpr"))), steps[modalStep] === 'email' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("We use email notification to explain important updates in plugin settings. Add your email address below.", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal-content " + processingClass
  }, steps[modalStep] === 'plugins' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, plugins.map((plugin, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_InstallPlugin__WEBPACK_IMPORTED_MODULE_2__["default"], {
    key: i,
    plugin: plugin,
    processing: processing
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-onboarding-item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
    name: waiting ? 'loading' : 'circle-check',
    color: waiting ? 'grey' : 'green',
    size: 14
  }), (waiting || !loaded) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Upgrading", "complianz-gdpr")), !waiting && loaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, isUpgrade && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Thanks for updating!", "complianz-gdpr"), !isUpgrade && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Thanks for installing!", "complianz-gdpr")))), steps[modalStep] === 'email' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "email",
    className: emailClass,
    value: email,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Your email address", "complianz-gdpr"),
    onChange: e => setEmail(e.target.value)
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    onChange: e => setIncludeTips(e.target.checked),
    type: "checkbox",
    checked: includeTips
  }), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Include 6 Tips & Tricks to get started with Complianz GDPR.", "complianz-gdpr"), "\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "https://complianz.io/legal/privacy-statement/",
    target: "_blank"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Privacy Statement", "complianz-gdpr")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    onChange: e => setSendTestEmail(e.target.checked),
    type: "checkbox",
    checked: sendTestEmail
  }), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Send a notification test email - Notification emails are sent from your server.", "complianz-gdpr"))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-modal-footer"
  }, modalStep > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    onClick: e => setModalStep(modalStep - 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Previous", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    type: "button",
    className: "button button-default",
    onClick: () => dismissModal()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Dismiss", "complianz-gdpr")), modalStep < steps.length - 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    disabled: nextDisabled,
    className: "button button-primary",
    onClick: e => setModalStep(modalStep + 1)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Next", "complianz-gdpr")), modalStep === steps.length - 1 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    disabled: nextDisabled,
    href: "#",
    onClick: e => goToWizard(e),
    className: "button button-primary"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Start wizard", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: "#",
    onClick: e => startTour(e)
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Take a tour", "complianz-gdpr")))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Onboarding);

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
//# sourceMappingURL=src_Onboarding_Onboarding_js.js.map