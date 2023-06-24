"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Integrations_Urls_js"],{

/***/ "./src/Settings/Inputs/TextInput.js":
/*!******************************************!*\
  !*** ./src/Settings/Inputs/TextInput.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");



const TextInput = _ref => {
  let {
    value,
    onChange,
    required,
    defaultValue,
    disabled,
    id,
    name,
    placeholder
  } = _ref;
  const inputId = id || name;
  const [inputValue, setInputValue] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)('');

  //ensure that the initial value is set
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    setInputValue(value || '');
  }, [value]);

  //because an update on the entire Fields array is costly, we only update after the user has stopped typing
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    // skip first render
    const typingTimer = setTimeout(() => {
      onChange(inputValue);
    }, 500);
    return () => {
      clearTimeout(typingTimer);
    };
  }, [inputValue]);
  const handleChange = value => {
    setInputValue(value);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-text-input-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: inputId,
    name: name,
    value: inputValue,
    onChange: event => handleChange(event.target.value),
    required: required,
    disabled: disabled,
    className: "cmplz-text-input-group__input",
    placeholder: placeholder
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(TextInput));

/***/ }),

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

/***/ "./src/Settings/Integrations/Urls.js":
/*!*******************************************!*\
  !*** ./src/Settings/Integrations/Urls.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _utils_readMore__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/readMore */ "./src/utils/readMore.js");
/* harmony import */ var _Inputs_TextInput__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Inputs/TextInput */ "./src/Settings/Inputs/TextInput.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");






const Urls = props => {
  const {
    setScript,
    fetching
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_5__["default"])();
  const script = props.script;
  const type = props.type;
  const onChangeUrlHandler = (index, url) => {
    let copyScript = {
      ...script
    };
    let urls = {
      ...copyScript.urls
    };
    urls[index] = url;
    copyScript.urls = urls;
    setScript(copyScript, props.type);
  };
  const addUrl = () => {
    let copyScript = {
      ...script
    };
    let curLength = Object.keys(copyScript.urls).length;
    let urls = {
      ...copyScript.urls
    };
    urls[curLength + 1] = '';
    copyScript.urls = urls;
    setScript(copyScript, props.type);
  };
  const deleteUrl = key => {
    let copyScript = {
      ...script
    };
    let urls = {
      ...copyScript.urls
    };
    //delete index 'key' from copyScript.urls
    delete urls[key];
    copyScript.urls = urls;
    setScript(copyScript, props.type);
  };
  let urls = Object.entries(script.urls);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, type === 'block_script' && (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("URLs that should be blocked before consent.", "complianz-gdpr"), type === 'whitelist_script' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("URLs that should be whitelisted.", "complianz-gdpr"), (0,_utils_readMore__WEBPACK_IMPORTED_MODULE_2__["default"])("https://complianz.io/whitelisting-inline-script/"))), urls.map((_ref, i) => {
    let [index, url] = _ref;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "cmplz-scriptcenter-url"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_TextInput__WEBPACK_IMPORTED_MODULE_3__["default"], {
      disabled: fetching,
      value: url ? url : '',
      onChange: value => onChangeUrlHandler(index, value),
      id: i + "_url",
      name: "url"
    }), i === 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button button-default",
      onClick: () => addUrl()
    }, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
      name: "plus",
      size: 14
    })), i !== 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button button-default",
      onClick: () => deleteUrl(index)
    }, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_4__["default"], {
      name: "minus",
      size: 14
    })));
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Urls);

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

/***/ }),

/***/ "./src/Settings/Inputs/Input.scss":
/*!****************************************!*\
  !*** ./src/Settings/Inputs/Input.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Integrations_Urls_js.js.map