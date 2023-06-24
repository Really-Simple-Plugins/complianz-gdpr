"use strict";
(globalThis["webpackChunkcomplianz_gdpr"] = globalThis["webpackChunkcomplianz_gdpr"] || []).push([["src_Settings_CookieBannerPreview_tcf_js"],{

/***/ "./src/Settings/CookieBannerPreview/tcf.js":
/*!*************************************************!*\
  !*** ./src/Settings/CookieBannerPreview/tcf.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   concatenateString: () => (/* binding */ concatenateString),
/* harmony export */   filterArray: () => (/* binding */ filterArray),
/* harmony export */   getPurposes: () => (/* binding */ getPurposes)
/* harmony export */ });
const getPurposes = (category, includeLowerCategories) => {
  //these categories aren't used
  if (category === 'functional' || category === 'preferences') {
    return [];
  }
  if (category === 'marketing') {
    if (includeLowerCategories) {
      return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    } else {
      return [1, 2, 3, 4, 5, 6, 10];
    }
  } else if (category === 'statistics') {
    return [1, 7, 8, 9];
  }
};
const filterArray = (arrayToFilter, arrayToFilterBy) => {
  if (!arrayToFilter) {
    arrayToFilter = {};
  }
  if (!Array.isArray(arrayToFilterBy)) {
    arrayToFilterBy = Object.keys(arrayToFilter);
  }
  const keysToFilterBy = arrayToFilterBy.map(item => parseInt(item));
  return Object.keys(arrayToFilter).filter(key => keysToFilterBy.includes(parseInt(key))).map(key => arrayToFilter[key]);
};
const concatenateString = array => {
  let string = '';
  const max = array.length - 1;
  for (var key in array) {
    if (array.hasOwnProperty(key)) {
      string += array[key];
      if (key < max) {
        string += ', ';
      } else {
        string += '.';
      }
    }
  }
  return string;
};

/***/ })

}]);
//# sourceMappingURL=src_Settings_CookieBannerPreview_tcf_js.js.map