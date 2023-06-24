"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_Integrations_ScriptCenterControl_js"],{

/***/ "./node_modules/@radix-ui/react-switch/dist/index.module.js":
/*!******************************************************************!*\
  !*** ./node_modules/@radix-ui/react-switch/dist/index.module.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Root: () => (/* binding */ $6be4966fd9bbc698$export$be92b6f5f03c0fe9),
/* harmony export */   Switch: () => (/* binding */ $6be4966fd9bbc698$export$b5d5cf8927ab7262),
/* harmony export */   SwitchThumb: () => (/* binding */ $6be4966fd9bbc698$export$4d07bf653ea69106),
/* harmony export */   Thumb: () => (/* binding */ $6be4966fd9bbc698$export$6521433ed15a34db),
/* harmony export */   createSwitchScope: () => (/* binding */ $6be4966fd9bbc698$export$cf7f5f17f69cbd43)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _radix_ui_primitive__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @radix-ui/primitive */ "./node_modules/@radix-ui/primitive/dist/index.module.js");
/* harmony import */ var _radix_ui_react_compose_refs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @radix-ui/react-compose-refs */ "./node_modules/@radix-ui/react-compose-refs/dist/index.module.js");
/* harmony import */ var _radix_ui_react_context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @radix-ui/react-context */ "./node_modules/@radix-ui/react-context/dist/index.module.js");
/* harmony import */ var _radix_ui_react_use_controllable_state__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @radix-ui/react-use-controllable-state */ "./node_modules/@radix-ui/react-use-controllable-state/dist/index.module.js");
/* harmony import */ var _radix_ui_react_use_previous__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @radix-ui/react-use-previous */ "./node_modules/@radix-ui/react-use-previous/dist/index.module.js");
/* harmony import */ var _radix_ui_react_use_size__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @radix-ui/react-use-size */ "./node_modules/@radix-ui/react-use-size/dist/index.module.js");
/* harmony import */ var _radix_ui_react_primitive__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @radix-ui/react-primitive */ "./node_modules/@radix-ui/react-primitive/dist/index.module.js");



















/* -------------------------------------------------------------------------------------------------
 * Switch
 * -----------------------------------------------------------------------------------------------*/ const $6be4966fd9bbc698$var$SWITCH_NAME = 'Switch';
const [$6be4966fd9bbc698$var$createSwitchContext, $6be4966fd9bbc698$export$cf7f5f17f69cbd43] = (0,_radix_ui_react_context__WEBPACK_IMPORTED_MODULE_2__.createContextScope)($6be4966fd9bbc698$var$SWITCH_NAME);
const [$6be4966fd9bbc698$var$SwitchProvider, $6be4966fd9bbc698$var$useSwitchContext] = $6be4966fd9bbc698$var$createSwitchContext($6be4966fd9bbc698$var$SWITCH_NAME);
const $6be4966fd9bbc698$export$b5d5cf8927ab7262 = /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.forwardRef)((props, forwardedRef)=>{
    const { __scopeSwitch: __scopeSwitch , name: name , checked: checkedProp , defaultChecked: defaultChecked , required: required , disabled: disabled , value: value = 'on' , onCheckedChange: onCheckedChange , ...switchProps } = props;
    const [button, setButton] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
    const composedRefs = (0,_radix_ui_react_compose_refs__WEBPACK_IMPORTED_MODULE_3__.useComposedRefs)(forwardedRef, (node)=>setButton(node)
    );
    const hasConsumerStoppedPropagationRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(false); // We set this to true by default so that events bubble to forms without JS (SSR)
    const isFormControl = button ? Boolean(button.closest('form')) : true;
    const [checked = false, setChecked] = (0,_radix_ui_react_use_controllable_state__WEBPACK_IMPORTED_MODULE_4__.useControllableState)({
        prop: checkedProp,
        defaultProp: defaultChecked,
        onChange: onCheckedChange
    });
    return /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.createElement)($6be4966fd9bbc698$var$SwitchProvider, {
        scope: __scopeSwitch,
        checked: checked,
        disabled: disabled
    }, /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.createElement)(_radix_ui_react_primitive__WEBPACK_IMPORTED_MODULE_5__.Primitive.button, (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
        type: "button",
        role: "switch",
        "aria-checked": checked,
        "aria-required": required,
        "data-state": $6be4966fd9bbc698$var$getState(checked),
        "data-disabled": disabled ? '' : undefined,
        disabled: disabled,
        value: value
    }, switchProps, {
        ref: composedRefs,
        onClick: (0,_radix_ui_primitive__WEBPACK_IMPORTED_MODULE_6__.composeEventHandlers)(props.onClick, (event)=>{
            setChecked((prevChecked)=>!prevChecked
            );
            if (isFormControl) {
                hasConsumerStoppedPropagationRef.current = event.isPropagationStopped(); // if switch is in a form, stop propagation from the button so that we only propagate
                // one click event (from the input). We propagate changes from an input so that native
                // form validation works and form events reflect switch updates.
                if (!hasConsumerStoppedPropagationRef.current) event.stopPropagation();
            }
        })
    })), isFormControl && /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.createElement)($6be4966fd9bbc698$var$BubbleInput, {
        control: button,
        bubbles: !hasConsumerStoppedPropagationRef.current,
        name: name,
        value: value,
        checked: checked,
        required: required,
        disabled: disabled // We transform because the input is absolutely positioned but we have
        ,
        style: {
            transform: 'translateX(-100%)'
        }
    }));
});
/*#__PURE__*/ Object.assign($6be4966fd9bbc698$export$b5d5cf8927ab7262, {
    displayName: $6be4966fd9bbc698$var$SWITCH_NAME
});
/* -------------------------------------------------------------------------------------------------
 * SwitchThumb
 * -----------------------------------------------------------------------------------------------*/ const $6be4966fd9bbc698$var$THUMB_NAME = 'SwitchThumb';
const $6be4966fd9bbc698$export$4d07bf653ea69106 = /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.forwardRef)((props, forwardedRef)=>{
    const { __scopeSwitch: __scopeSwitch , ...thumbProps } = props;
    const context = $6be4966fd9bbc698$var$useSwitchContext($6be4966fd9bbc698$var$THUMB_NAME, __scopeSwitch);
    return /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.createElement)(_radix_ui_react_primitive__WEBPACK_IMPORTED_MODULE_5__.Primitive.span, (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
        "data-state": $6be4966fd9bbc698$var$getState(context.checked),
        "data-disabled": context.disabled ? '' : undefined
    }, thumbProps, {
        ref: forwardedRef
    }));
});
/*#__PURE__*/ Object.assign($6be4966fd9bbc698$export$4d07bf653ea69106, {
    displayName: $6be4966fd9bbc698$var$THUMB_NAME
});
/* ---------------------------------------------------------------------------------------------- */ const $6be4966fd9bbc698$var$BubbleInput = (props)=>{
    const { control: control , checked: checked , bubbles: bubbles = true , ...inputProps } = props;
    const ref = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(null);
    const prevChecked = (0,_radix_ui_react_use_previous__WEBPACK_IMPORTED_MODULE_7__.usePrevious)(checked);
    const controlSize = (0,_radix_ui_react_use_size__WEBPACK_IMPORTED_MODULE_8__.useSize)(control); // Bubble checked change to parents (e.g form change event)
    (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(()=>{
        const input = ref.current;
        const inputProto = window.HTMLInputElement.prototype;
        const descriptor = Object.getOwnPropertyDescriptor(inputProto, 'checked');
        const setChecked = descriptor.set;
        if (prevChecked !== checked && setChecked) {
            const event = new Event('click', {
                bubbles: bubbles
            });
            setChecked.call(input, checked);
            input.dispatchEvent(event);
        }
    }, [
        prevChecked,
        checked,
        bubbles
    ]);
    return /*#__PURE__*/ (0,react__WEBPACK_IMPORTED_MODULE_1__.createElement)("input", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
        type: "checkbox",
        "aria-hidden": true,
        defaultChecked: checked
    }, inputProps, {
        tabIndex: -1,
        ref: ref,
        style: {
            ...props.style,
            ...controlSize,
            position: 'absolute',
            pointerEvents: 'none',
            opacity: 0,
            margin: 0
        }
    }));
};
function $6be4966fd9bbc698$var$getState(checked) {
    return checked ? 'checked' : 'unchecked';
}
const $6be4966fd9bbc698$export$be92b6f5f03c0fe9 = $6be4966fd9bbc698$export$b5d5cf8927ab7262;
const $6be4966fd9bbc698$export$6521433ed15a34db = $6be4966fd9bbc698$export$4d07bf653ea69106;





//# sourceMappingURL=index.module.js.map


/***/ }),

/***/ "./src/Settings/Editor/AceEditorControl.js":
/*!*************************************************!*\
  !*** ./src/Settings/Editor/AceEditorControl.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_ace__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-ace */ "./node_modules/react-ace/lib/index.js");
/* harmony import */ var ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ace-builds/src-noconflict/mode-css */ "./node_modules/ace-builds/src-noconflict/mode-css.js");
/* harmony import */ var ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_mode_css__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ace-builds/src-noconflict/theme-monokai */ "./node_modules/ace-builds/src-noconflict/theme-monokai.js");
/* harmony import */ var ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_theme_monokai__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ace-builds/src-noconflict/ext-language_tools */ "./node_modules/ace-builds/src-noconflict/ext-language_tools.js");
/* harmony import */ var ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(ace_builds_src_noconflict_ext_language_tools__WEBPACK_IMPORTED_MODULE_5__);






const AceEditorControl = props => {
  let mode = props.mode ? props.mode : 'css';
  let height = props.height ? props.height : '200px';
  let placeholder = props.field && props.field.default ? props.field.default : props.placeholder;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(react_ace__WEBPACK_IMPORTED_MODULE_2__["default"], {
    disabled: props.disabled,
    placeholder: placeholder,
    mode: mode,
    theme: "monokai",
    width: "100%",
    height: height,
    onChange: value => props.onChangeHandler(value),
    fontSize: 14,
    showPrintMargin: true,
    showGutter: true,
    highlightActiveLine: true,
    value: props.value,
    setOptions: {
      enableBasicAutocompletion: false,
      enableLiveAutocompletion: false,
      enableSnippets: false,
      showLineNumbers: true,
      tabSize: 2,
      useWorker: false
    }
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(AceEditorControl));

/***/ }),

/***/ "./src/Settings/Inputs/RadioGroup.js":
/*!*******************************************!*\
  !*** ./src/Settings/Inputs/RadioGroup.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _radix_ui_react_radio_group__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @radix-ui/react-radio-group */ "./node_modules/@radix-ui/react-radio-group/dist/index.module.js");
/* harmony import */ var _RadioGroup_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RadioGroup.scss */ "./src/Settings/Inputs/RadioGroup.scss");
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);





const RadioGroup = _ref => {
  let {
    label,
    id,
    value,
    onChange,
    required,
    defaultValue,
    disabled,
    options = {}
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_radio_group__WEBPACK_IMPORTED_MODULE_4__.Root, {
    disabled: disabled && !Array.isArray(disabled),
    className: "cmplz-input-group cmplz-radio-group",
    value: value,
    "aria-label": label,
    onValueChange: onChange,
    required: required,
    default: defaultValue
  }, Object.entries(options).map(_ref2 => {
    let [key, optionLabel] = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: key,
      className: 'cmplz-radio-group__item'
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_radio_group__WEBPACK_IMPORTED_MODULE_4__.Item, {
      disabled: Array.isArray(disabled) && disabled.includes(key),
      value: key,
      id: id + '_' + key
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_radio_group__WEBPACK_IMPORTED_MODULE_4__.Indicator, {
      className: 'cmplz-radio-group__indicator'
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      className: "cmplz-radio-label",
      htmlFor: id + '_' + key
    }, optionLabel));
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_3__.memo)(RadioGroup));

/***/ }),

/***/ "./src/Settings/Inputs/SelectInput.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @radix-ui/react-select */ "./node_modules/@radix-ui/react-select/dist/index.module.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _Input_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Input.scss */ "./src/Settings/Inputs/Input.scss");
/* harmony import */ var _SelectInput_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SelectInput.scss */ "./src/Settings/Inputs/SelectInput.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);







const SelectInput = _ref => {
  let {
    value = false,
    onChange,
    required,
    defaultValue,
    disabled,
    options = {},
    innerRef
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-input-group cmplz-select-group"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Root, {
    //ref={innerRef}
    value: value,
    defaultValue: defaultValue,
    onValueChange: onChange,
    required: required,
    disabled: disabled && !Array.isArray(disabled)
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Trigger, {
    className: "cmplz-select-group__trigger"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Value, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Content, {
    className: "cmplz-select-group__content",
    position: "popper"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollUpButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-up'
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Viewport, {
    className: "cmplz-select-group__viewport"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Group, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
    className: 'cmplz-select-group__item',
    key: 0,
    value: ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Select an option", "complianz-gdpr"))), Object.entries(options).map(_ref2 => {
    let [optionValue, optionText] = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.Item, {
      disabled: Array.isArray(disabled) && disabled.includes(optionValue),
      className: 'cmplz-select-group__item',
      key: optionValue,
      value: optionValue
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ItemText, null, optionText));
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_select__WEBPACK_IMPORTED_MODULE_6__.ScrollDownButton, {
    className: "cmplz-select-group__scroll-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_2__["default"], {
    name: 'chevron-down'
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(SelectInput));

/***/ }),

/***/ "./src/Settings/Inputs/SwitchInput.js":
/*!********************************************!*\
  !*** ./src/Settings/Inputs/SwitchInput.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _radix_ui_react_switch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @radix-ui/react-switch */ "./node_modules/@radix-ui/react-switch/dist/index.module.js");
/* harmony import */ var _SwitchInput_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SwitchInput.scss */ "./src/Settings/Inputs/SwitchInput.scss");




const SwitchInput = _ref => {
  let {
    value,
    onChange,
    required,
    disabled
  } = _ref;
  let val = value;
  //if value is "0" or "1", convert to boolean
  //cookiebanner values can be "0" or "1", because of the way they're loaded, but the switch needs a boolean
  if (value === "0" || value === "1") {
    val = value === "1";
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_switch__WEBPACK_IMPORTED_MODULE_3__.Root, {
    className: "cmplz-switch-root",
    checked: val,
    onCheckedChange: onChange,
    disabled: disabled,
    required: required
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_radix_ui_react_switch__WEBPACK_IMPORTED_MODULE_3__.Thumb, {
    className: "cmplz-switch-thumb"
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_1__.memo)(SwitchInput));

/***/ }),

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

/***/ "./src/Settings/Integrations/Category.js":
/*!***********************************************!*\
  !*** ./src/Settings/Integrations/Category.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Inputs_RadioGroup__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Inputs/RadioGroup */ "./src/Settings/Inputs/RadioGroup.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");




const Category = props => {
  const {
    setScript,
    fetching
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const script = props.script;
  const onChangeHandler = (value, property) => {
    let copyScript = {
      ...script
    };
    copyScript[property] = value;
    setScript(copyScript, props.type);
  };
  const options = {
    statistics: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Statistics", "complianz-gdpr"),
    marketing: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Marketing", "complianz-gdpr")
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Category", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_RadioGroup__WEBPACK_IMPORTED_MODULE_1__["default"], {
    disabled: fetching,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Marketing", "complianz-gdpr"),
    id: "category",
    value: script.category,
    onChange: value => onChangeHandler(value, 'category'),
    defaultValue: "marketing",
    options: options
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Category);

/***/ }),

/***/ "./src/Settings/Integrations/Dependency.js":
/*!*************************************************!*\
  !*** ./src/Settings/Integrations/Dependency.js ***!
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
/* harmony import */ var _Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Inputs/SwitchInput */ "./src/Settings/Inputs/SwitchInput.js");
/* harmony import */ var _Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Inputs/SelectInput */ "./src/Settings/Inputs/SelectInput.js");
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");





const Dependency = props => {
  const {
    setScript,
    blockedScripts,
    fetching
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_4__["default"])();
  const options = blockedScripts;
  // ensure that each url from both whitelist_scripts and blocked_scripts is in the options
  const script = props.script;
  const onChangeHandler = (value, property) => {
    let copyScript = {
      ...script
    };
    copyScript[property] = value;
    setScript(copyScript, props.type);
  };
  const findSelectedDependency = search => {
    if (!script.dependency || script.dependency.length === 0) return '';
    let deps = Object.entries(script.dependency);
    for (const [waitFor, shouldWait] of deps) {
      if (waitFor === search) {
        return shouldWait;
      }
    }
    return '';
  };
  const onChangeDependencyHandler = (shouldWait, waitFor) => {
    let copyScript = {
      ...script
    };
    let dep = {
      ...copyScript.dependency
    };
    dep[waitFor] = shouldWait;
    copyScript.dependency = dep;
    setScript(copyScript, props.type);
  };
  const dropOption = (options, option) => {
    let copyOptions = {
      ...options
    };
    for (const [optionIndex, optionValue] of Object.entries(copyOptions)) {
      if (optionValue === option) {
        //delete 'optionIndex' from the options object
        delete copyOptions[optionIndex];
        break;
      }
    }
    return copyOptions;
  };
  let urls = Object.entries(script.urls);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_2__["default"], {
    disabled: fetching,
    value: script.enable_dependency,
    onChange: value => onChangeHandler(value, 'enable_dependency')
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Dependency", "complianz-gdpr"))), !!script.enable_dependency && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row"
  }, urls.map((_ref, i) => {
    let [index, waitFor] = _ref;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: i,
      className: "cmplz-scriptcenter-dependencies"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_3__["default"], {
      disabled: fetching,
      value: findSelectedDependency(waitFor),
      options: dropOption(options, waitFor),
      onChange: value => onChangeDependencyHandler(value, waitFor)
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("waits for: ", "complianz-gdpr"), waitFor ? waitFor : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Empty URL", "complianz-gdpr")));
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Dependency);

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

/***/ "./src/Settings/Integrations/Placeholder.js":
/*!**************************************************!*\
  !*** ./src/Settings/Integrations/Placeholder.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Inputs/SwitchInput */ "./src/Settings/Inputs/SwitchInput.js");
/* harmony import */ var _utils_readMore__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/readMore */ "./src/utils/readMore.js");
/* harmony import */ var _Inputs_TextInput__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Inputs/TextInput */ "./src/Settings/Inputs/TextInput.js");
/* harmony import */ var _Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Inputs/SelectInput */ "./src/Settings/Inputs/SelectInput.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");







const Category = props => {
  const {
    setScript,
    fetching,
    placeholders
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_6__["default"])();
  const script = props.script;
  const type = props.type;
  const onChangeHandler = (value, property) => {
    let copyScript = {
      ...script
    };
    copyScript[property] = value;
    setScript(copyScript, props.type);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_1__["default"], {
    disabled: fetching,
    value: script.enable_placeholder,
    onChange: value => onChangeHandler(value, 'enable_placeholder')
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Placeholder", "complianz-gdpr"))), !!script.enable_placeholder && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, type === 'block_script' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_1__["default"], {
    disabled: fetching,
    value: script.iframe || '',
    onChange: value => onChangeHandler(value || '', 'iframe')
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("The blocked content is an iframe", "complianz-gdpr"))), !script.iframe && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Enter the div class or ID that should be targeted.', 'complianz-gdpr'), (0,_utils_readMore__WEBPACK_IMPORTED_MODULE_2__["default"])('https://complianz.io/integrating-plugins/#placeholder/')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_TextInput__WEBPACK_IMPORTED_MODULE_3__["default"], {
    disabled: fetching,
    value: script.placeholder_class || '',
    onChange: value => onChangeHandler(value || '', 'placeholder_class'),
    name: "placeholder_class",
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Your CSS class", "complianz-gdpr")
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-details-row cmplz-details-row__checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Inputs_SelectInput__WEBPACK_IMPORTED_MODULE_4__["default"], {
    disabled: fetching,
    value: script.placeholder ? script.placeholder : 'default',
    options: placeholders,
    onChange: value => onChangeHandler(value || 'default', 'placeholder')
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Category);

/***/ }),

/***/ "./src/Settings/Integrations/ScriptCenterControl.js":
/*!**********************************************************!*\
  !*** ./src/Settings/Integrations/ScriptCenterControl.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");
/* harmony import */ var _Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Fields/FieldsData */ "./src/Settings/Fields/FieldsData.js");
/* harmony import */ var _ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ThirdPartyScript */ "./src/Settings/Integrations/ThirdPartyScript.js");
/* harmony import */ var _utils_readMore__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../utils/readMore */ "./src/utils/readMore.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _integrations_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./integrations.scss */ "./src/Settings/Integrations/integrations.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_7__);









const ScriptCenterControl = () => {
  const {
    scripts,
    addScript,
    saveScript,
    integrationsLoaded,
    fetchIntegrationsData
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_1__["default"])();
  const [disabled, setDisabled] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const [disabledText, setDisabledText] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  const {
    getFieldValue
  } = (0,_Fields_FieldsData__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (!integrationsLoaded) fetchIntegrationsData();
    if (integrationsLoaded) {
      if (getFieldValue('safe_mode') == 1) {
        setDisabledText((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Safe Mode enabled. To manage integrations, disable Safe Mode in the general settings.', 'complianz-gdpr'));
        setDisabled(true);
      }
    }
  }, [integrationsLoaded]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("The script center should be used to add and block third-party scripts and iFrames before consent is given, or when consent is revoked. For example Hotjar and embedded video’s.", 'complianz-gdpr'), (0,_utils_readMore__WEBPACK_IMPORTED_MODULE_4__["default"])('https://complianz.io/script-center/')), disabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-settings-overlay-message"
  }, disabledText))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Add a third-party script", 'complianz-gdpr')), !integrationsLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "add_script"
  })), integrationsLoaded && scripts.add_script.length > 0 && scripts.add_script.map((script, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "add_script",
    script: script,
    key: i
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: () => addScript('add_script'),
    className: "button button-default"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Add new", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Block a script, iframe or plugin", 'complianz-gdpr')), !integrationsLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "block_script"
  })), integrationsLoaded && scripts.block_script.length > 0 && scripts.block_script.map((script, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "block_script",
    script: script,
    key: i
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: () => addScript('block_script'),
    className: "button button-default"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Add new", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Whitelist a script, iframe or plugin\n", 'complianz-gdpr')), !integrationsLoaded && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "whitelist_script"
  })), integrationsLoaded && scripts.whitelist_script.length > 0 && scripts.whitelist_script.map((script, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ThirdPartyScript__WEBPACK_IMPORTED_MODULE_3__["default"], {
    type: "whitelist_script",
    script: script,
    key: i
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: () => addScript('whitelist_script'),
    className: "button button-default"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Add new", "complianz-gdpr"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_7__.memo)(ScriptCenterControl));

/***/ }),

/***/ "./src/Settings/Integrations/ThirdPartyScript.js":
/*!*******************************************************!*\
  !*** ./src/Settings/Integrations/ThirdPartyScript.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Panel */ "./src/Settings/Panel.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _IntegrationsData__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./IntegrationsData */ "./src/Settings/Integrations/IntegrationsData.js");
/* harmony import */ var _Editor_AceEditorControl__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Editor/AceEditorControl */ "./src/Settings/Editor/AceEditorControl.js");
/* harmony import */ var _Settings_Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../Settings/Inputs/SwitchInput */ "./src/Settings/Inputs/SwitchInput.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _Category__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Category */ "./src/Settings/Integrations/Category.js");
/* harmony import */ var _Placeholder__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./Placeholder */ "./src/Settings/Integrations/Placeholder.js");
/* harmony import */ var _Dependency__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Dependency */ "./src/Settings/Integrations/Dependency.js");
/* harmony import */ var _Urls__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./Urls */ "./src/Settings/Integrations/Urls.js");












const ThirdPartyScript = props => {
  const {
    setScript,
    saveScript,
    deleteScript
  } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
  const script = props.script;
  const checkboxControl = script => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      onChange: e => onChangeEnabledHandler(e.target.checked, 'enable'),
      type: "checkbox",
      checked: script.enable
    }));
  };
  const onChangeEnabledHandler = (checked, property) => {
    let copyScript = {
      ...script
    };
    copyScript[property] = checked;
    setScript(copyScript, props.type);
    saveScript(copyScript, props.type);
  };
  const onChangeHandler = (value, property) => {
    let copyScript = {
      ...script
    };
    copyScript[property] = value;
    setScript(copyScript, props.type);
  };
  const onSaveHandler = () => {
    saveScript(script, props.type);
  };
  const onDeleteHandler = () => {
    deleteScript(script, props.type);
  };
  const onEditorChangeHandler = value => {
    onChangeHandler(value, 'editor');
  };
  const ScriptDetails = (script, type) => {
    const {
      fetching
    } = (0,_IntegrationsData__WEBPACK_IMPORTED_MODULE_3__["default"])();
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Name", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      disabled: fetching,
      onChange: e => onChangeHandler(e.target.value, 'name'),
      type: "text",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Name", "complianz-gdpr"),
      value: script.name
    })), type === 'add_script' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Editor_AceEditorControl__WEBPACK_IMPORTED_MODULE_4__["default"], {
      disabled: fetching,
      onChangeHandler: value => onEditorChangeHandler(value),
      placeholder: "console.log('marketing enabled')",
      value: script.editor
    })), (type === 'block_script' || type === 'whitelist_script') && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Urls__WEBPACK_IMPORTED_MODULE_10__["default"], {
      script: script,
      type: type
    }), type !== 'whitelist_script' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row cmplz-details-row__checkbox"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Settings_Inputs_SwitchInput__WEBPACK_IMPORTED_MODULE_5__["default"], {
      disabled: fetching,
      value: script.async,
      onChange: value => onChangeHandler(value, 'async')
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("This script contains an async attribute.", "complianz-gdpr"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Category__WEBPACK_IMPORTED_MODULE_7__["default"], {
      script: script,
      type: type
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Placeholder__WEBPACK_IMPORTED_MODULE_8__["default"], {
      script: script,
      type: type
    })), type === 'block_script' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Dependency__WEBPACK_IMPORTED_MODULE_9__["default"], {
      script: script,
      type: type
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "cmplz-details-row cmplz-details-row__buttons"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      disabled: fetching,
      onClick: e => onSaveHandler(),
      className: "button button-default"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Save", "complianz-gdpr")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      disabled: fetching,
      className: "button button-default cmplz-reset-button",
      onClick: e => onDeleteHandler()
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Delete", "complianz-gdpr"))));
  };
  if (!script) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_1__["default"], {
      summary: '...'
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_1__["default"], {
    summary: script.name,
    icons: checkboxControl(script),
    details: ScriptDetails(script, props.type)
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,react__WEBPACK_IMPORTED_MODULE_6__.memo)(ThirdPartyScript));

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


/***/ }),

/***/ "./src/Settings/Inputs/RadioGroup.scss":
/*!*********************************************!*\
  !*** ./src/Settings/Inputs/RadioGroup.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Inputs/SelectInput.scss":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/SelectInput.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Inputs/SwitchInput.scss":
/*!**********************************************!*\
  !*** ./src/Settings/Inputs/SwitchInput.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/Settings/Integrations/integrations.scss":
/*!*****************************************************!*\
  !*** ./src/Settings/Integrations/integrations.scss ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=src_Settings_Integrations_ScriptCenterControl_js.js.map