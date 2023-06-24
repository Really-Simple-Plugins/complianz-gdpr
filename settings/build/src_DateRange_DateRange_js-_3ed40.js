"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_DateRange_DateRange_js-_3ed40"],{

/***/ "./src/DateRange/DateRange.js":
/*!************************************!*\
  !*** ./src/DateRange/DateRange.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _mui_material_Popover__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! @mui/material/Popover */ "./node_modules/@mui/material/Popover/Popover.js");
/* harmony import */ var react_date_range__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-date-range */ "./node_modules/react-date-range/dist/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/parseISO/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/startOfDay/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/endOfDay/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/addDays/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/startOfMonth/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/addMonths/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/endOfMonth/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/startOfYear/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/addYears/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/endOfYear/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/isSameDay/index.js");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/format/index.js");
/* harmony import */ var _utils_Icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/Icon */ "./src/utils/Icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _useDateStore__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./useDateStore */ "./src/DateRange/useDateStore.js");




// date range picker and date fns





const DateRange = props => {
  const [anchorEl, setAnchorEl] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const open = Boolean(anchorEl);
  const startDate = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.startDate);
  const endDate = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.endDate);
  const setStartDate = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.setStartDate);
  const setEndDate = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.setEndDate);
  const range = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.range);
  const setRange = (0,_useDateStore__WEBPACK_IMPORTED_MODULE_5__.useDate)(state => state.setRange);
  const selectionRange = {
    startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_6__["default"])(startDate),
    endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_6__["default"])(endDate),
    key: 'selection'
  };
  const countClicks = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(0);
  // select date ranges from settings
  const selectedRanges = ['today', 'yesterday', 'last-7-days', 'last-30-days', 'last-90-days', 'last-month', 'last-year', 'year-to-date'];
  const availableRanges = {
    'today': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Today', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_7__["default"])(new Date()),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])(new Date())
      })
    },
    'yesterday': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Yesterday', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_7__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -1)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -1))
      })
    },
    'last-7-days': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last 7 days', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_7__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -7)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -1))
      })
    },
    'last-30-days': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last 30 days', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_7__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -30)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -1))
      })
    },
    'last-90-days': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last 90 days', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_7__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -90)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_9__["default"])(new Date(), -1))
      })
    },
    'last-month': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last month', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_10__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_11__["default"])(new Date(), -1)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_12__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_11__["default"])(new Date(), -1))
      })
    },
    'year-to-date': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Year to date', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_13__["default"])(new Date()),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_8__["default"])(new Date())
      })
    },
    'last-year': {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Last year', 'complianz-gdpr'),
      range: () => ({
        startDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_13__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_14__["default"])(new Date(), -1)),
        endDate: (0,date_fns__WEBPACK_IMPORTED_MODULE_15__["default"])((0,date_fns__WEBPACK_IMPORTED_MODULE_14__["default"])(new Date(), -1))
      })
    }
  };
  function isSelected(range) {
    const definedRange = this.range();
    return (0,date_fns__WEBPACK_IMPORTED_MODULE_16__["default"])(range.startDate, definedRange.startDate) && (0,date_fns__WEBPACK_IMPORTED_MODULE_16__["default"])(range.endDate, definedRange.endDate);
  }

  // for all selected ranges add daterange and isSelected function
  const dateRanges = [];
  for (const [key, value] of Object.entries(selectedRanges)) {
    if (value) {
      dateRanges.push(availableRanges[value]);
      dateRanges[dateRanges.length - 1].isSelected = isSelected;
    }
  }
  const handleClick = e => {
    setAnchorEl(e.currentTarget);
  };
  const handleClose = e => {
    setAnchorEl(null);
  };
  const updateDateRange = ranges => {
    countClicks.current++;
    // setSelectionRange(ranges.selection);
    let startStr = (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(ranges.selection.startDate, 'yyyy-MM-dd');
    let endStr = (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(ranges.selection.endDate, 'yyyy-MM-dd');
    let range = 'custom';

    // loop through availableRanges and check if the selected range is one of them
    for (const [key, value] of Object.entries(availableRanges)) {
      if (value.isSelected(ranges.selection)) {
        range = key;
      }
    }
    let dateRange = {
      startDate: ranges.selection.startDate,
      endDate: ranges.selection.endDate,
      range: range
    };
    if (countClicks.current === 2 || startStr !== endStr || range !== 'custom') {
      countClicks.current = 0;
      setStartDate(startStr);
      setEndDate(endStr);
      setRange(range);
      handleClose();
    }
  };
  const formatString = 'MMMM d, yyyy';
  const display = {
    startDate: startDate ? (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(new Date(startDate), formatString) : (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(defaultStart, formatString),
    endDate: endDate ? (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(new Date(endDate), formatString) : (0,date_fns__WEBPACK_IMPORTED_MODULE_17__["default"])(defaultEnd, formatString)
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "cmplz-date-range-container"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: handleClick,
    id: "cmplz-date-range-picker-open-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "calendar",
    size: '18'
  }), range === 'custom' && display.startDate + ' - ' + display.endDate, range !== 'custom' && availableRanges[range].label, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_utils_Icon__WEBPACK_IMPORTED_MODULE_3__["default"], {
    name: "chevron-down"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_mui_material_Popover__WEBPACK_IMPORTED_MODULE_18__["default"], {
    anchorEl: anchorEl,
    anchorOrigin: {
      vertical: 'bottom',
      horizontal: 'right'
    },
    transformOrigin: {
      vertical: 'top',
      horizontal: 'right'
    },
    open: open,
    onClose: handleClose,
    className: "burst"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "cmplz-date-range-picker-container"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(react_date_range__WEBPACK_IMPORTED_MODULE_2__.DateRangePicker, {
    ranges: [selectionRange],
    rangeColors: ['var(--rsp-brand-primary)'],
    dateDisplayFormat: formatString,
    monthDisplayFormat: "MMMM"
    // color="var(--rsp-text-color)"
    ,
    onChange: ranges => {
      updateDateRange(ranges);
    },
    inputRanges: [],
    showSelectionPreview: true
    // moveRangeOnFirstSelection={false}
    ,
    months: 2,
    direction: "horizontal",
    minDate: new Date(2022, 0, 1),
    maxDate: new Date(),
    staticRanges: dateRanges
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DateRange);

/***/ })

}]);
//# sourceMappingURL=src_DateRange_DateRange_js-_3ed40.js.map