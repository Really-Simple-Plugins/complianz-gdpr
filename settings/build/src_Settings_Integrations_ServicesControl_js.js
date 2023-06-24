"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Integrations_ServicesControl_js"],{

/***/ "./src/Settings/Integrations/IntegrationsData.js":
/*!*******************************************************!*\
  !*** ./src/Settings/Integrations/IntegrationsData.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var zustand__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! zustand */ "./node_modules/zustand/esm/index.mjs");
/* harmony import */ var immer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! immer */ "./node_modules/immer/dist/immer.esm.mjs");
/* harmony import */ var _utils_api__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/api */ "./src/utils/api.js");



const useIntegrations = (0,zustand__WEBPACK_IMPORTED_MODULE_1__.create)((set, get) => ({
  integrationsLoaded: false,
  fetching: false,
  services: [],
  plugins: [],
  scripts: [],
  placeholders: [],
  blockedScripts: [],
  setScript: (script, type) => {
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      //update blocked scripts options list if new urls were added.
      if (type === 'block_script') {
        let options = state.blockedScripts;
        if (script.urls) {
          for (const [index, url] of Object.entries(script.urls)) {
            if (!url || url.length === 0) continue;
            //check if url exists in the options object
            let found = false;
            for (const [optionIndex, optionValue] of Object.entries(options)) {
              if (url === optionIndex) found = true;
            }
            if (!found) {
              options[url] = url;
            }
          }
          state.blockedScripts = options;
        }
      }
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      if (index !== -1) state.scripts[type][index] = script;
    }));
  },
  fetchIntegrationsData: async () => {
    if (get().fetching) return;
    set({
      fetching: true
    });
    const {
      services,
      plugins,
      scripts,
      placeholders,
      blocked_scripts
    } = await fetchData();
    let scriptsWithId = scripts;
    //add a unique id to each script
    scriptsWithId.block_script.forEach((script, i) => {
      script.id = i;
    });
    scriptsWithId.add_script.forEach((script, i) => {
      script.id = i;
    });
    scriptsWithId.whitelist_script.forEach((script, i) => {
      script.id = i;
    });
    set(() => ({
      integrationsLoaded: true,
      services: services,
      plugins: plugins,
      scripts: scriptsWithId,
      fetching: false,
      placeholders: placeholders,
      blockedScripts: blocked_scripts
    }));
  },
  addScript: type => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      state.scripts[type].push({
        'name': 'general',
        'id': state.scripts[type].length,
        'enable': true
      });
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  saveScript: (script, type) => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      if (index !== -1) state.scripts[type][index] = script;
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  deleteScript: (script, type) => {
    set({
      fetching: true
    });
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.scripts[type].findIndex(item => {
        return item.id === script.id;
      });
      //drop script with this index
      if (index !== -1) state.scripts[type].splice(index, 1);
    }));
    let scripts = get().scripts;
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_scripts', {
      scripts: scripts
    }).then(response => {
      set({
        fetching: false
      });
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  updatePluginStatus: async (pluginId, enabled) => {
    set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
      const index = state.plugins.findIndex(plugin => {
        return plugin.id === pluginId;
      });
      if (index !== -1) state.plugins[index].enabled = enabled;
    }));
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_plugin_status', {
      plugin: pluginId,
      enabled: enabled
    }).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  },
  updatePlaceholderStatus: async (id, enabled, isPlugin) => {
    if (isPlugin) {
      set((0,immer__WEBPACK_IMPORTED_MODULE_2__["default"])(state => {
        const index = state.plugins.findIndex(plugin => {
          return plugin.id === id;
        });
        if (index !== -1) state.plugins[index].placeholder = enabled ? 'enabled' : 'disabled';
      }));
    }
    return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('update_placeholder_status', {
      id: id,
      enabled: enabled
    }).then(response => {
      return response;
    }).catch(error => {
      console.error(error);
    });
  }
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useIntegrations);
const fetchData = () => {
  return _utils_api__WEBPACK_IMPORTED_MODULE_0__.doAction('get_integrations_data', {}).then(response => {
    return response;
  }).catch(error => {
    console.error(error);
  });
};

/***/ }),

/***/ "./src/Settings/Integrations/ServicesControl.js":
/*!******************************************************!*\
  !*** ./src/Settings/Integrations/ServicesControl.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _utils_readMore__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/readMore */ "./src/utils/readMore.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _Menu_MenuData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../Menu/MenuData */ "./src/Menu/MenuData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _utils_Hyperlink__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../utils/Hyperlink */ "./src/utils/Hyperlink.js");










const ServicesControl = () => {
  const {
    updatePlaceholderStatus,
    integrationsLoaded,
    services,
    fetchIntegrationsData
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [updatedServices, setUpdatedServices] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [searchValue, setSearchValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [disabledText, setDisabledText] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const [disabledReadmore, setDisabledReadmore] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const {
    updateField,
    getField,
    getFieldValue,
    saveFields,
    setChangedField,
    addHelpNotice
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const {
    selectedSubMenuItem
  } = (0,_Menu_MenuData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const [DataTable, setDataTable] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    __webpack_require__.e(/*! import() */ "vendors-node_modules_react-data-table-component_dist_index_cjs_js").then(__webpack_require__.bind(__webpack_require__, /*! react-data-table-component */ "./node_modules/react-data-table-component/dist/index.cjs.js")).then(_ref => {
      let {
        default: DataTable
      } = _ref;
      setDataTable(() => DataTable);
    });
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!integrationsLoaded) fetchIntegrationsData();
    if (integrationsLoaded) {
      //filter enabled services
      if (getFieldValue('safe_mode') == 1) {
        setDisabledText((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Safe Mode enabled. To manage integrations, disable Safe Mode in the general settings.', 'complianz-gdpr'));
        setDisabled(true);
      } else if (getFieldValue('uses_thirdparty_services') !== 'yes' && getFieldValue('uses_social_media') !== 'yes' && getFieldValue('uses_ad_cookies') !== 'yes') {
        setDisabledText((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Third-party services and social media are marked as not being used on your website in the wizard.', 'complianz-gdpr'));
        setDisabledReadmore('#wizard/services');
        setDisabled(true);
      }
    }
  }, [integrationsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    syncServicesWithFields();
  }, [services]);
  const syncServicesWithFields = () => {
    //for each service, update the value from the field
    let servicesCopy = [...services];
    servicesCopy.forEach(function (service, i) {
      let serviceCopy = {
        ...service
      };
      let field = getField(service.source);
      if (field.type === 'multicheckbox') {
        let value = field.value;
        if (!Array.isArray(value)) value = [];
        serviceCopy.enabled = value.includes(service.id);
      } else {
        serviceCopy.enabled = field.value === 'yes';
      }
      servicesCopy[i] = serviceCopy;
    });
    setUpdatedServices(servicesCopy);
    let reCaptcha = getFieldValue('block_recaptcha_service') === 'yes';
    //get service with id == recaptcha from the services list, and check if it's enabled
    let recaptchaService = services.filter(service => service.id === 'google-recaptcha')[0];
    if (reCaptcha && recaptchaService && recaptchaService.enabled) {
      addHelpNotice('integrations-services', 'warning', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("reCaptcha is connected and will be blocked before consent. To change your settings, disable reCaptcha in the list.", 'complianz-gdpr'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('reCaptcha blocking enabled', 'complianz-gdpr'), '#wizard/services');
    }
  };
  const customStyles = {
    headCells: {
      style: {
        paddingLeft: '0',
        paddingRight: '0'
      }
    },
    cells: {
      style: {
        paddingLeft: '0',
        paddingRight: '0'
      }
    }
  };
  const onChangePlaceholderHandler = async (service, enabled) => {
    //set placeholder to 'disabled' or 'enabled' in updatedServices
    let services = [...updatedServices];
    let serviceIndex = services.findIndex(item => item.id === service.id);
    services[serviceIndex].placeholder = enabled ? 'enabled' : 'disabled';
    setUpdatedServices(services);
    await updatePlaceholderStatus(service.id, enabled);
  };
  const onChangeHandler = async (service, enabled) => {
    let field = getField(service.source);
    if (service.source.indexOf('service') !== -1) {
      updateField('uses_thirdparty_services', enabled ? 'yes' : 'no');
      setChangedField('uses_thirdparty_services', enabled ? 'yes' : 'no');
    } else if (service.source.indexOf('social_media') !== -1) {
      updateField('uses_social_media', enabled ? 'yes' : 'no');
      setChangedField('uses_social_media', enabled ? 'yes' : 'no');
    }
    let value;
    if (field.type === 'multicheckbox') {
      value = [...field.value];
      if (!Array.isArray(value)) value = [];
      if (enabled) {
        value.push(service.id);
      } else {
        value = value.filter(item => item !== service.id);
      }
    } else {
      value = enabled ? 'yes' : 'no';
    }
    updateField(service.source, value);
    setChangedField(service.source, value);
    saveFields(selectedSubMenuItem, false).then(() => {
      fetchIntegrationsData().then(() => {
        syncServicesWithFields();
      });
    });
  };
  const enabledDisabledPlaceholderSort = (rowA, rowB) => {
    const a = rowA.placeholder;
    const b = rowB.placeholder;
    if (a > b) {
      return 1;
    }
    if (b > a) {
      return -1;
    }
    return 0;
  };
  const enabledDisabledSort = (rowA, rowB) => {
    const a = rowA.enabled;
    const b = rowB.enabled;
    if (a > b) {
      return 1;
    }
    if (b > a) {
      return -1;
    }
    return 0;
  };
  const columns = [{
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Service', "complianz-gdpr"),
    selector: row => row.label,
    sortable: true
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Placeholder', "complianz-gdpr"),
    selector: row => row.placeholderControl,
    sortable: true,
    sortFunction: enabledDisabledPlaceholderSort
  }, {
    name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Status', "complianz-gdpr"),
    selector: row => row.enabledControl,
    sortable: true,
    sortFunction: enabledDisabledSort
  }];

  //filter the services by search value
  let filteredServices = updatedServices.filter(service => {
    return service.label.toLowerCase().includes(searchValue.toLowerCase());
  });

  //sort the services alphabetically by label
  filteredServices.sort((a, b) => {
    if (a.label < b.label) {
      return -1;
    }
    if (a.label > b.label) {
      return 1;
    }
    return 0;
  });
  filteredServices.forEach(service => {
    let value = getFieldValue(service.source);
    if (Array.isArray(value)) {
      service.enabled = value.includes(service.id);
    } else {
      service.enabled = value === 'yes';
    }
    service.enabledControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.ToggleControl, {
      checked: service.enabled,
      onChange: fieldValue => onChangeHandler(service, fieldValue)
    });
    service.placeholderControl = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.ToggleControl, {
      label: service.placeholder === 'none' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('N/A', "complianz-gdpr") : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Placeholder", "complianz-gdpr"),
      disabled: service.placeholder === 'none',
      checked: service.placeholder === 'enabled',
      onChange: fieldValue => onChangePlaceholderHandler(service, fieldValue)
    });
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Enabled services will be blocked on the front-end of your website until the user has given consent (opt-in), or after the user has revoked consent (opt-out). When possible a placeholder is activated. You can also disable or configure the placeholder to your liking.", 'complianz-gdpr'), (0,_utils_readMore__WEBPACK_IMPORTED_MODULE_4__["default"])("https://complianz.io/blocking-recaptcha-manually/")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-table-header-controls"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Search", "complianz-gdpr"),
    value: searchValue,
    onChange: e => setSearchValue(e.target.value)
  }))), (disabled || filteredServices.length === 0) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay-message"
  }, disabledText, disabledReadmore && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, "\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: disabledReadmore
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('View services.', 'complianz-gdpr')))))), filteredServices.length === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-integrations-placeholder"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null))), !disabled && filteredServices.length > 0 && DataTable && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(DataTable, {
    columns: columns,
    data: filteredServices,
    dense: true,
    pagination: true,
    paginationPerPage: 5,
    noDataComponent: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-no-documents"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("No services", "really-simple-ssl")),
    persistTableHead: true,
    theme: "really-simple-plugins",
    customStyles: customStyles
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_7__.memo)(ServicesControl));

/***/ }),

/***/ "./src/utils/readMore.js":
/*!*******************************!*\
  !*** ./src/utils/readMore.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Hyperlink__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Hyperlink */ "./src/utils/Hyperlink.js");



const readMore = url => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, "\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Hyperlink__WEBPACK_IMPORTED_MODULE_2__["default"], {
    url: url,
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('For more information, please read this %sarticle%s.', 'complianz-gdpr')
  }), "\xA0");
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (readMore);

/***/ })

}]);
//# sourceMappingURL=src_Settings_Integrations_ServicesControl_js.js.map