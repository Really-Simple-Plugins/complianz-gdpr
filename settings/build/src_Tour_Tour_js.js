"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Tour_Tour_js"],{

/***/ "./src/Settings/License/LicenseData.js":
/*!*********************************************!*\
  !*** ./src/Settings/License/LicenseData.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");


const UseLicenseData = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  licenseStatus: cmplz_settings.licenseStatus,
  processing: false,
  licenseNotices: [],
  noticesLoaded: false,
  getLicenseNotices: async () => {
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('license_notices', {}).then(response => {
      return response;
    });
    set(state => ({
      noticesLoaded: true,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  activateLicense: async license => {
    let data = {};
    data.license = license;
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('activate_license', data);
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  },
  deactivateLicense: async () => {
    set({
      processing: true
    });
    const {
      licenseStatus,
      notices
    } = await _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('deactivate_license');
    set(state => ({
      processing: false,
      licenseNotices: notices,
      licenseStatus: licenseStatus
    }));
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseLicenseData);

/***/ }),

/***/ "./src/Tour/Tour.js":
/*!**************************!*\
  !*** ./src/Tour/Tour.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Settings/License/LicenseData */ "./src/Settings/License/LicenseData.js");
/* harmony import */ var _Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Settings/Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");





const onTourEnd = () => {
  //remove 'tour' query variable from url
  const url = new URL(window.location.href);
  url.searchParams.delete('tour');
  window.history.pushState({}, '', url.href);
};
const tourOptions = {
  defaultStepOptions: {
    cancelIcon: {
      enabled: true
    },
    keyboardNavigation: false
  },
  useModalOverlay: false,
  margin: 15
};
const TourInstance = _ref => {
  let {
    ShepherdTourContext
  } = _ref;
  const tour = (0,react__WEBPACK_IMPORTED_MODULE_1__.useContext)(ShepherdTourContext);
  tour.on("cancel", onTourEnd);
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (tour) tour.start();
  }, [tour]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
};
const newSteps = [{
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Welcome to Complianz', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Get ready for privacy legislation around the world. Follow a quick tour or start configuring the plugin!', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    type: 'cancel',
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Configure', 'complianz-gdpr'),
    action() {
      const url = new URL(window.location.href);
      url.searchParams.delete('tour');
      window.location.hash = 'wizard';
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Start tour', 'complianz-gdpr'),
    action() {
      window.location.hash = cmplz_settings.is_premium ? 'settings/license' : 'dashboard';
      return this.next();
    }
  }]
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Dashboard', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('This is your Dashboard. When the Wizard is completed, this will give you an overview of tasks, tools, and documentation.', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = cmplz_settings.is_premium ? 'settings/license' : 'dashboard';
      return this.back();
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
    action() {
      window.location.hash = 'wizard/consent';
      return this.next();
    }
  }]
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('The Wizard', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('This is where everything regarding cookies is configured. We will come back to the Wizard soon.', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = 'dashboard';
      return this.back();
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
    action() {
      window.location.hash = 'banner';
      return this.next();
    }
  }]
  // attachTo: { element: '.cmplz-cookie-scan', on: 'auto' },
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Cookie Banner', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Here you can configure and style your cookie banner if the Wizard is completed. An extra tab will be added with region-specific settings.', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = 'wizard/consent';
      return this.back();
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
    action() {
      window.location.hash = 'integrations';
      return this.next();
    }
  }]
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Integrations', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Based on your answers in the Wizard, we will automatically enable integrations with relevant services and plugins. In case you want to block extra scripts, you can add them to the Script Center.', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = 'banner';
      return this.back();
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
    action() {
      window.location.hash = 'tools/proof-of-consent';
      return this.next();
    }
  }]
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Proof of Consent', 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Complianz tracks changes in your Cookie Notice and Cookie Policy with time-stamped documents. This is your consent registration while respecting the data minimization guidelines and won't store any user data.", 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = 'integrations';
      return this.back();
    }
  }, {
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
    action() {
      window.location.hash = 'wizard/visitors';
      return this.next();
    }
  }]
  // attachTo: { element: '.cmplz-field-button', on: 'auto' },
}, {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Let's start the Wizard", 'complianz-gdpr'),
  text: '<p>' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('You are ready to start the Wizard. For more information, FAQ, and support, please visit Complianz.io.', 'complianz-gdpr') + '</p>',
  classes: 'cmplz-shepherd',
  buttons: [{
    classes: 'button button-default',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
    action() {
      window.location.hash = 'tools/proof-of-consent';
      return this.back();
    }
  }, {
    type: 'cancel',
    classes: 'button button-primary',
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('End tour', 'complianz-gdpr')
  }]
}];
const Tour = () => {
  const {
    licenseStatus
  } = (0,_Settings_License_LicenseData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [ShepherdTour, setShepherdTour] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [ShepherdTourContext, setShepherdTourContext] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const {
    fieldsLoaded
  } = (0,_Settings_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (!fieldsLoaded) return;

    //import ShepherdTour and ShepherdTourContext from 'react-shepherd' and set them to the state with setShepherdTour and setShepherdTourContext
    __webpack_require__.e(/*! import() */ "vendors-node_modules_react-shepherd_dist_Shepherd_es_js").then(__webpack_require__.bind(__webpack_require__, /*! react-shepherd */ "./node_modules/react-shepherd/dist/Shepherd.es.js")).then(_ref2 => {
      let {
        ShepherdTour,
        ShepherdTourContext
      } = _ref2;
      setShepherdTour(() => ShepherdTour);
      setShepherdTourContext(() => ShepherdTourContext);
    });
    if (cmplz_settings.is_premium) {
      let licenseText;
      if (licenseStatus === 'valid') {
        licenseText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Great, your license is activated and valid!", 'complianz-gdpr');
      } else {
        licenseText = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("To unlock the wizard and future updates, please enter and activate your license.", 'complianz-gdpr');
      }
      const additionalStep = {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Activate your license', 'complianz-gdpr'),
        text: '<p>' + licenseText + '</p>',
        classes: 'cmplz-shepherd',
        buttons: [{
          classes: 'button button-default',
          text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Previous', 'complianz-gdpr'),
          action() {
            window.location.hash = 'dashboard';
            return this.back();
          }
        }, {
          classes: 'button button-primary',
          text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Next', 'complianz-gdpr'),
          action() {
            window.location.hash = 'dashboard';
            return this.next();
          }
        }]
        // attachTo: { element: '.cmplz-license', on: 'auto' },
      };
      //insert additionalStep after the first step
      newSteps.splice(1, 0, additionalStep);
    }
  }, [fieldsLoaded]);
  if (!ShepherdTour || !ShepherdTourContext) {
    return null;
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ShepherdTour, {
    steps: newSteps,
    tourOptions: tourOptions
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TourInstance, {
    ShepherdTourContext: ShepherdTourContext
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tour);

/***/ })

}]);
//# sourceMappingURL=src_Tour_Tour_js.js.map