"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_PluginsOverviewControl_js"],{

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

/***/ }),

/***/ "./src/Settings/PluginsOverviewControl.js":
/*!************************************************!*\
  !*** ./src/Settings/PluginsOverviewControl.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _Integrations_IntegrationsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Integrations/IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);







const PluginsOverviewControl = () => {
  const {
    services,
    integrationsLoaded,
    plugins,
    fetchIntegrationsData
  } = (0,_Integrations_IntegrationsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const [activeServices, setActiveServices] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const {
    fields,
    getField
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!integrationsLoaded) {
      fetchIntegrationsData();
    }
  }, [integrationsLoaded]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    syncServicesWithFields();
  }, [fields, integrationsLoaded]);
  const syncServicesWithFields = () => {
    // //for each service, update the value from the field
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
    // //filter out all services that are not enabled
    servicesCopy = servicesCopy.filter(service => service.enabled);
    setActiveServices(servicesCopy);
  };
  const integrationsList = items => {
    if (!Array.isArray(items)) {
      return null;
    }
    return items.map((item, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i
    }, item.label));
  };
  let servicesCount = !Array.isArray(activeServices) ? 0 : activeServices.length;
  let pluginsCount = !Array.isArray(plugins) ? 0 : plugins.length;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-plugins_overview"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_2__["default"], {
    summary: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("We found %s active plugin integrations", "complianz-gdpr").replace('%s', pluginsCount),
    details: integrationsList(plugins)
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-panel__list"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_2__["default"], {
    summary: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("We found %s active service integrations", "complianz-gdpr").replace('%s', servicesCount),
    details: integrationsList(activeServices)
  })));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_5__.memo)(PluginsOverviewControl));

/***/ })

}]);
//# sourceMappingURL=src_Settings_PluginsOverviewControl_js.js.map