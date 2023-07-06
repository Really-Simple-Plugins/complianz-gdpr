"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[7471],{23541:(e,t,n)=>{n.d(t,{Ry:()=>u});var r=new WeakMap,o=new WeakMap,i={},a=0,c=function(e){return e&&(e.host||c(e.parentNode))},u=function(e,t,n){void 0===n&&(n="data-aria-hidden");var u=Array.from(Array.isArray(e)?e:[e]),l=t||function(e){return"undefined"==typeof document?null:(Array.isArray(e)?e[0]:e).ownerDocument.body}(e);return l?(u.push.apply(u,Array.from(l.querySelectorAll("[aria-live]"))),function(e,t,n,u){var l=function(e,t){return t.map((function(t){if(e.contains(t))return t;var n=c(t);return n&&e.contains(n)?n:(console.error("aria-hidden",t,"in not contained inside",e,". Doing nothing"),null)})).filter((function(e){return Boolean(e)}))}(t,Array.isArray(e)?e:[e]);i[n]||(i[n]=new WeakMap);var s=i[n],d=[],f=new Set,p=new Set(l),v=function(e){e&&!f.has(e)&&(f.add(e),v(e.parentNode))};l.forEach(v);var m=function(e){e&&!p.has(e)&&Array.prototype.forEach.call(e.children,(function(e){if(f.has(e))m(e);else{var t=e.getAttribute(u),i=null!==t&&"false"!==t,a=(r.get(e)||0)+1,c=(s.get(e)||0)+1;r.set(e,a),s.set(e,c),d.push(e),1===a&&i&&o.set(e,!0),1===c&&e.setAttribute(n,"true"),i||e.setAttribute(u,"true")}}))};return m(t),f.clear(),a++,function(){d.forEach((function(e){var t=r.get(e)-1,i=s.get(e)-1;r.set(e,t),s.set(e,i),t||(o.has(e)||e.removeAttribute(u),o.delete(e)),i||e.removeAttribute(n)})),--a||(r=new WeakMap,r=new WeakMap,o=new WeakMap,i={})}}(u,l,n,"aria-hidden")):function(){return null}}},7219:(e,t,n)=>{n.d(t,{Z:()=>F});var r=function(){return r=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},r.apply(this,arguments)};function o(e,t){var n={};for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&t.indexOf(r)<0&&(n[r]=e[r]);if(null!=e&&"function"==typeof Object.getOwnPropertySymbols){var o=0;for(r=Object.getOwnPropertySymbols(e);o<r.length;o++)t.indexOf(r[o])<0&&Object.prototype.propertyIsEnumerable.call(e,r[o])&&(n[r[o]]=e[r[o]])}return n}Object.create,Object.create,"function"==typeof SuppressedError&&SuppressedError;var i=n(99196),a="right-scroll-bar-position",c="width-before-scroll-bar";function u(e){return e}var l=function(e){void 0===e&&(e={});var t=function(e,t){void 0===t&&(t=u);var n=[],r=!1;return{read:function(){if(r)throw new Error("Sidecar: could not `read` from an `assigned` medium. `read` could be used only with `useMedium`.");return n.length?n[n.length-1]:e},useMedium:function(e){var o=t(e,r);return n.push(o),function(){n=n.filter((function(e){return e!==o}))}},assignSyncMedium:function(e){for(r=!0;n.length;){var t=n;n=[],t.forEach(e)}n={push:function(t){return e(t)},filter:function(){return n}}},assignMedium:function(e){r=!0;var t=[];if(n.length){var o=n;n=[],o.forEach(e),t=n}var i=function(){var n=t;t=[],n.forEach(e)},a=function(){return Promise.resolve().then(i)};a(),n={push:function(e){t.push(e),a()},filter:function(e){return t=t.filter(e),n}}}}}(null);return t.options=r({async:!0,ssr:!1},e),t}(),s=function(){},d=i.forwardRef((function(e,t){var n=i.useRef(null),a=i.useState({onScrollCapture:s,onWheelCapture:s,onTouchMoveCapture:s}),c=a[0],u=a[1],d=e.forwardProps,f=e.children,p=e.className,v=e.removeScrollBar,m=e.enabled,h=e.shards,g=e.sideCar,y=e.noIsolation,w=e.inert,b=e.allowPinchZoom,E=e.as,x=void 0===E?"div":E,C=o(e,["forwardProps","children","className","removeScrollBar","enabled","shards","sideCar","noIsolation","inert","allowPinchZoom","as"]),S=g,P=function(e,t){return n=t||null,r=function(t){return e.forEach((function(e){return function(e,t){return"function"==typeof e?e(t):e&&(e.current=t),e}(e,t)}))},(o=(0,i.useState)((function(){return{value:n,callback:r,facade:{get current(){return o.value},set current(e){var t=o.value;t!==e&&(o.value=e,o.callback(e,t))}}}}))[0]).callback=r,o.facade;var n,r,o}([n,t]),T=r(r({},C),c);return i.createElement(i.Fragment,null,m&&i.createElement(S,{sideCar:l,removeScrollBar:v,shards:h,noIsolation:y,inert:w,setCallbacks:u,allowPinchZoom:!!b,lockRef:n}),d?i.cloneElement(i.Children.only(f),r(r({},T),{ref:P})):i.createElement(x,r({},T,{className:p,ref:P}),f))}));d.defaultProps={enabled:!0,removeScrollBar:!0,inert:!1},d.classNames={fullWidth:c,zeroRight:a};var f=function(e){var t=e.sideCar,n=o(e,["sideCar"]);if(!t)throw new Error("Sidecar: please provide `sideCar` property to import the right car");var a=t.read();if(!a)throw new Error("Sidecar medium not found");return i.createElement(a,r({},n))};f.isSideCarExport=!0;var p=function(){var e=0,t=null;return{add:function(r){var o,i;0==e&&(t=function(){if(!document)return null;var e=document.createElement("style");e.type="text/css";var t=n.nc;return t&&e.setAttribute("nonce",t),e}())&&(i=r,(o=t).styleSheet?o.styleSheet.cssText=i:o.appendChild(document.createTextNode(i)),function(e){(document.head||document.getElementsByTagName("head")[0]).appendChild(e)}(t)),e++},remove:function(){!--e&&t&&(t.parentNode&&t.parentNode.removeChild(t),t=null)}}},v=function(){var e,t=(e=p(),function(t,n){i.useEffect((function(){return e.add(t),function(){e.remove()}}),[t&&n])});return function(e){var n=e.styles,r=e.dynamic;return t(n,r),null}},m={left:0,top:0,right:0,gap:0},h=function(e){return parseInt(e||"",10)||0},g=v(),y=function(e,t,n,r){var o=e.left,i=e.top,u=e.right,l=e.gap;return void 0===n&&(n="margin"),"\n  .".concat("with-scroll-bars-hidden"," {\n   overflow: hidden ").concat(r,";\n   padding-right: ").concat(l,"px ").concat(r,";\n  }\n  body {\n    overflow: hidden ").concat(r,";\n    overscroll-behavior: contain;\n    ").concat([t&&"position: relative ".concat(r,";"),"margin"===n&&"\n    padding-left: ".concat(o,"px;\n    padding-top: ").concat(i,"px;\n    padding-right: ").concat(u,"px;\n    margin-left:0;\n    margin-top:0;\n    margin-right: ").concat(l,"px ").concat(r,";\n    "),"padding"===n&&"padding-right: ".concat(l,"px ").concat(r,";")].filter(Boolean).join(""),"\n  }\n  \n  .").concat(a," {\n    right: ").concat(l,"px ").concat(r,";\n  }\n  \n  .").concat(c," {\n    margin-right: ").concat(l,"px ").concat(r,";\n  }\n  \n  .").concat(a," .").concat(a," {\n    right: 0 ").concat(r,";\n  }\n  \n  .").concat(c," .").concat(c," {\n    margin-right: 0 ").concat(r,";\n  }\n  \n  body {\n    ").concat("--removed-body-scroll-bar-size",": ").concat(l,"px;\n  }\n")},w=function(e){var t=e.noRelative,n=e.noImportant,r=e.gapMode,o=void 0===r?"margin":r,a=i.useMemo((function(){return function(e){if(void 0===e&&(e="margin"),"undefined"==typeof window)return m;var t=function(e){var t=window.getComputedStyle(document.body),n=t["padding"===e?"paddingLeft":"marginLeft"],r=t["padding"===e?"paddingTop":"marginTop"],o=t["padding"===e?"paddingRight":"marginRight"];return[h(n),h(r),h(o)]}(e),n=document.documentElement.clientWidth,r=window.innerWidth;return{left:t[0],top:t[1],right:t[2],gap:Math.max(0,r-n+t[2]-t[0])}}(o)}),[o]);return i.createElement(g,{styles:y(a,!t,o,n?"":"!important")})},b=!1;if("undefined"!=typeof window)try{var E=Object.defineProperty({},"passive",{get:function(){return b=!0,!0}});window.addEventListener("test",E,E),window.removeEventListener("test",E,E)}catch(e){b=!1}var x=!!b&&{passive:!1},C=function(e,t){var n=window.getComputedStyle(e);return"hidden"!==n[t]&&!(n.overflowY===n.overflowX&&!function(e){return"TEXTAREA"===e.tagName}(e)&&"visible"===n[t])},S=function(e,t){var n=t;do{if("undefined"!=typeof ShadowRoot&&n instanceof ShadowRoot&&(n=n.host),P(e,n)){var r=T(e,n);if(r[1]>r[2])return!0}n=n.parentNode}while(n&&n!==document.body);return!1},P=function(e,t){return"v"===e?function(e){return C(e,"overflowY")}(t):function(e){return C(e,"overflowX")}(t)},T=function(e,t){return"v"===e?[(n=t).scrollTop,n.scrollHeight,n.clientHeight]:function(e){return[e.scrollLeft,e.scrollWidth,e.clientWidth]}(t);var n},A=function(e){return"changedTouches"in e?[e.changedTouches[0].clientX,e.changedTouches[0].clientY]:[0,0]},O=function(e){return[e.deltaX,e.deltaY]},R=function(e){return e&&"current"in e?e.current:e},k=function(e){return"\n  .block-interactivity-".concat(e," {pointer-events: none;}\n  .allow-interactivity-").concat(e," {pointer-events: all;}\n")},L=0,W=[];const M=(D=function(e){var t=i.useRef([]),n=i.useRef([0,0]),r=i.useRef(),o=i.useState(L++)[0],a=i.useState((function(){return v()}))[0],c=i.useRef(e);i.useEffect((function(){c.current=e}),[e]),i.useEffect((function(){if(e.inert){document.body.classList.add("block-interactivity-".concat(o));var t=function(e,t,n){if(n||2===arguments.length)for(var r,o=0,i=t.length;o<i;o++)!r&&o in t||(r||(r=Array.prototype.slice.call(t,0,o)),r[o]=t[o]);return e.concat(r||Array.prototype.slice.call(t))}([e.lockRef.current],(e.shards||[]).map(R),!0).filter(Boolean);return t.forEach((function(e){return e.classList.add("allow-interactivity-".concat(o))})),function(){document.body.classList.remove("block-interactivity-".concat(o)),t.forEach((function(e){return e.classList.remove("allow-interactivity-".concat(o))}))}}}),[e.inert,e.lockRef.current,e.shards]);var u=i.useCallback((function(e,t){if("touches"in e&&2===e.touches.length)return!c.current.allowPinchZoom;var o,i=A(e),a=n.current,u="deltaX"in e?e.deltaX:a[0]-i[0],l="deltaY"in e?e.deltaY:a[1]-i[1],s=e.target,d=Math.abs(u)>Math.abs(l)?"h":"v";if("touches"in e&&"h"===d&&"range"===s.type)return!1;var f=S(d,s);if(!f)return!0;if(f?o=d:(o="v"===d?"h":"v",f=S(d,s)),!f)return!1;if(!r.current&&"changedTouches"in e&&(u||l)&&(r.current=o),!o)return!0;var p=r.current||o;return function(e,t,n,r,o){var i=function(e,t){return"h"===e&&"rtl"===t?-1:1}(e,window.getComputedStyle(t).direction),a=i*r,c=n.target,u=t.contains(c),l=!1,s=a>0,d=0,f=0;do{var p=T(e,c),v=p[0],m=p[1]-p[2]-i*v;(v||m)&&P(e,c)&&(d+=m,f+=v),c=c.parentNode}while(!u&&c!==document.body||u&&(t.contains(c)||t===c));return(s&&(0===d||!1)||!s&&(0===f||!1))&&(l=!0),l}(p,t,e,"h"===p?u:l)}),[]),l=i.useCallback((function(e){var n=e;if(W.length&&W[W.length-1]===a){var r="deltaY"in n?O(n):A(n),o=t.current.filter((function(e){return e.name===n.type&&e.target===n.target&&(t=e.delta,o=r,t[0]===o[0]&&t[1]===o[1]);var t,o}))[0];if(o&&o.should)n.cancelable&&n.preventDefault();else if(!o){var i=(c.current.shards||[]).map(R).filter(Boolean).filter((function(e){return e.contains(n.target)}));(i.length>0?u(n,i[0]):!c.current.noIsolation)&&n.cancelable&&n.preventDefault()}}}),[]),s=i.useCallback((function(e,n,r,o){var i={name:e,delta:n,target:r,should:o};t.current.push(i),setTimeout((function(){t.current=t.current.filter((function(e){return e!==i}))}),1)}),[]),d=i.useCallback((function(e){n.current=A(e),r.current=void 0}),[]),f=i.useCallback((function(t){s(t.type,O(t),t.target,u(t,e.lockRef.current))}),[]),p=i.useCallback((function(t){s(t.type,A(t),t.target,u(t,e.lockRef.current))}),[]);i.useEffect((function(){return W.push(a),e.setCallbacks({onScrollCapture:f,onWheelCapture:f,onTouchMoveCapture:p}),document.addEventListener("wheel",l,x),document.addEventListener("touchmove",l,x),document.addEventListener("touchstart",d,x),function(){W=W.filter((function(e){return e!==a})),document.removeEventListener("wheel",l,x),document.removeEventListener("touchmove",l,x),document.removeEventListener("touchstart",d,x)}}),[]);var m=e.removeScrollBar,h=e.inert;return i.createElement(i.Fragment,null,h?i.createElement(a,{styles:k(o)}):null,m?i.createElement(w,{gapMode:"margin"}):null)},l.useMedium(D),f);var D,N=i.forwardRef((function(e,t){return i.createElement(d,r({},e,{ref:t,sideCar:M}))}));N.classNames=d.classNames;const F=N},64369:(e,t,n)=>{n.d(t,{XB:()=>f});var r=n(87462),o=n(99196),i=n(36206),a=n(75320),c=n(28771),u=n(79698);const l="dismissableLayer.update";let s;const d=(0,o.createContext)({layers:new Set,layersWithOutsidePointerEventsDisabled:new Set,branches:new Set}),f=(0,o.forwardRef)(((e,t)=>{var n;const{disableOutsidePointerEvents:f=!1,onEscapeKeyDown:m,onPointerDownOutside:h,onFocusOutside:g,onInteractOutside:y,onDismiss:w,...b}=e,E=(0,o.useContext)(d),[x,C]=(0,o.useState)(null),S=null!==(n=null==x?void 0:x.ownerDocument)&&void 0!==n?n:null===globalThis||void 0===globalThis?void 0:globalThis.document,[,P]=(0,o.useState)({}),T=(0,c.e)(t,(e=>C(e))),A=Array.from(E.layers),[O]=[...E.layersWithOutsidePointerEventsDisabled].slice(-1),R=A.indexOf(O),k=x?A.indexOf(x):-1,L=E.layersWithOutsidePointerEventsDisabled.size>0,W=k>=R,M=function(e,t=(null===globalThis||void 0===globalThis?void 0:globalThis.document)){const n=(0,u.W)(e),r=(0,o.useRef)(!1),i=(0,o.useRef)((()=>{}));return(0,o.useEffect)((()=>{const e=e=>{if(e.target&&!r.current){const o={originalEvent:e};function a(){v("dismissableLayer.pointerDownOutside",n,o,{discrete:!0})}"touch"===e.pointerType?(t.removeEventListener("click",i.current),i.current=a,t.addEventListener("click",i.current,{once:!0})):a()}r.current=!1},o=window.setTimeout((()=>{t.addEventListener("pointerdown",e)}),0);return()=>{window.clearTimeout(o),t.removeEventListener("pointerdown",e),t.removeEventListener("click",i.current)}}),[t,n]),{onPointerDownCapture:()=>r.current=!0}}((e=>{const t=e.target,n=[...E.branches].some((e=>e.contains(t)));W&&!n&&(null==h||h(e),null==y||y(e),e.defaultPrevented||null==w||w())}),S),D=function(e,t=(null===globalThis||void 0===globalThis?void 0:globalThis.document)){const n=(0,u.W)(e),r=(0,o.useRef)(!1);return(0,o.useEffect)((()=>{const e=e=>{e.target&&!r.current&&v("dismissableLayer.focusOutside",n,{originalEvent:e},{discrete:!1})};return t.addEventListener("focusin",e),()=>t.removeEventListener("focusin",e)}),[t,n]),{onFocusCapture:()=>r.current=!0,onBlurCapture:()=>r.current=!1}}((e=>{const t=e.target;[...E.branches].some((e=>e.contains(t)))||(null==g||g(e),null==y||y(e),e.defaultPrevented||null==w||w())}),S);return function(e,t=(null===globalThis||void 0===globalThis?void 0:globalThis.document)){const n=(0,u.W)(e);(0,o.useEffect)((()=>{const e=e=>{"Escape"===e.key&&n(e)};return t.addEventListener("keydown",e),()=>t.removeEventListener("keydown",e)}),[n,t])}((e=>{k===E.layers.size-1&&(null==m||m(e),!e.defaultPrevented&&w&&(e.preventDefault(),w()))}),S),(0,o.useEffect)((()=>{if(x)return f&&(0===E.layersWithOutsidePointerEventsDisabled.size&&(s=S.body.style.pointerEvents,S.body.style.pointerEvents="none"),E.layersWithOutsidePointerEventsDisabled.add(x)),E.layers.add(x),p(),()=>{f&&1===E.layersWithOutsidePointerEventsDisabled.size&&(S.body.style.pointerEvents=s)}}),[x,S,f,E]),(0,o.useEffect)((()=>()=>{x&&(E.layers.delete(x),E.layersWithOutsidePointerEventsDisabled.delete(x),p())}),[x,E]),(0,o.useEffect)((()=>{const e=()=>P({});return document.addEventListener(l,e),()=>document.removeEventListener(l,e)}),[]),(0,o.createElement)(a.WV.div,(0,r.Z)({},b,{ref:T,style:{pointerEvents:L?W?"auto":"none":void 0,...e.style},onFocusCapture:(0,i.M)(e.onFocusCapture,D.onFocusCapture),onBlurCapture:(0,i.M)(e.onBlurCapture,D.onBlurCapture),onPointerDownCapture:(0,i.M)(e.onPointerDownCapture,M.onPointerDownCapture)}))}));function p(){const e=new CustomEvent(l);document.dispatchEvent(e)}function v(e,t,n,{discrete:r}){const o=n.originalEvent.target,i=new CustomEvent(e,{bubbles:!1,cancelable:!0,detail:n});t&&o.addEventListener(e,t,{once:!0}),r?(0,a.jH)(o,i):o.dispatchEvent(i)}},27552:(e,t,n)=>{n.d(t,{EW:()=>i});var r=n(99196);let o=0;function i(){(0,r.useEffect)((()=>{var e,t;const n=document.querySelectorAll("[data-radix-focus-guard]");return document.body.insertAdjacentElement("afterbegin",null!==(e=n[0])&&void 0!==e?e:a()),document.body.insertAdjacentElement("beforeend",null!==(t=n[1])&&void 0!==t?t:a()),o++,()=>{1===o&&document.querySelectorAll("[data-radix-focus-guard]").forEach((e=>e.remove())),o--}}),[])}function a(){const e=document.createElement("span");return e.setAttribute("data-radix-focus-guard",""),e.tabIndex=0,e.style.cssText="outline: none; opacity: 0; position: fixed; pointer-events: none",e}},95420:(e,t,n)=>{n.d(t,{M:()=>d});var r=n(87462),o=n(99196),i=n(28771),a=n(75320),c=n(79698);const u="focusScope.autoFocusOnMount",l="focusScope.autoFocusOnUnmount",s={bubbles:!1,cancelable:!0},d=(0,o.forwardRef)(((e,t)=>{const{loop:n=!1,trapped:d=!1,onMountAutoFocus:v,onUnmountAutoFocus:g,...y}=e,[w,b]=(0,o.useState)(null),E=(0,c.W)(v),x=(0,c.W)(g),C=(0,o.useRef)(null),S=(0,i.e)(t,(e=>b(e))),P=(0,o.useRef)({paused:!1,pause(){this.paused=!0},resume(){this.paused=!1}}).current;(0,o.useEffect)((()=>{if(d){function e(e){if(P.paused||!w)return;const t=e.target;w.contains(t)?C.current=t:m(C.current,{select:!0})}function t(e){if(P.paused||!w)return;const t=e.relatedTarget;null!==t&&(w.contains(t)||m(C.current,{select:!0}))}function n(e){const t=document.activeElement;for(const n of e)n.removedNodes.length>0&&(null!=w&&w.contains(t)||m(w))}document.addEventListener("focusin",e),document.addEventListener("focusout",t);const r=new MutationObserver(n);return w&&r.observe(w,{childList:!0,subtree:!0}),()=>{document.removeEventListener("focusin",e),document.removeEventListener("focusout",t),r.disconnect()}}}),[d,w,P.paused]),(0,o.useEffect)((()=>{if(w){h.add(P);const e=document.activeElement;if(!w.contains(e)){const t=new CustomEvent(u,s);w.addEventListener(u,E),w.dispatchEvent(t),t.defaultPrevented||(function(e,{select:t=!1}={}){const n=document.activeElement;for(const r of e)if(m(r,{select:t}),document.activeElement!==n)return}(f(w).filter((e=>"A"!==e.tagName)),{select:!0}),document.activeElement===e&&m(w))}return()=>{w.removeEventListener(u,E),setTimeout((()=>{const t=new CustomEvent(l,s);w.addEventListener(l,x),w.dispatchEvent(t),t.defaultPrevented||m(null!=e?e:document.body,{select:!0}),w.removeEventListener(l,x),h.remove(P)}),0)}}}),[w,E,x,P]);const T=(0,o.useCallback)((e=>{if(!n&&!d)return;if(P.paused)return;const t="Tab"===e.key&&!e.altKey&&!e.ctrlKey&&!e.metaKey,r=document.activeElement;if(t&&r){const t=e.currentTarget,[o,i]=function(e){const t=f(e);return[p(t,e),p(t.reverse(),e)]}(t);o&&i?e.shiftKey||r!==i?e.shiftKey&&r===o&&(e.preventDefault(),n&&m(i,{select:!0})):(e.preventDefault(),n&&m(o,{select:!0})):r===t&&e.preventDefault()}}),[n,d,P.paused]);return(0,o.createElement)(a.WV.div,(0,r.Z)({tabIndex:-1},y,{ref:S,onKeyDown:T}))}));function f(e){const t=[],n=document.createTreeWalker(e,NodeFilter.SHOW_ELEMENT,{acceptNode:e=>{const t="INPUT"===e.tagName&&"hidden"===e.type;return e.disabled||e.hidden||t?NodeFilter.FILTER_SKIP:e.tabIndex>=0?NodeFilter.FILTER_ACCEPT:NodeFilter.FILTER_SKIP}});for(;n.nextNode();)t.push(n.currentNode);return t}function p(e,t){for(const n of e)if(!v(n,{upTo:t}))return n}function v(e,{upTo:t}){if("hidden"===getComputedStyle(e).visibility)return!0;for(;e;){if(void 0!==t&&e===t)return!1;if("none"===getComputedStyle(e).display)return!0;e=e.parentElement}return!1}function m(e,{select:t=!1}={}){if(e&&e.focus){const n=document.activeElement;e.focus({preventScroll:!0}),e!==n&&function(e){return e instanceof HTMLInputElement&&"select"in e}(e)&&t&&e.select()}}const h=function(){let e=[];return{add(t){const n=e[0];t!==n&&(null==n||n.pause()),e=g(e,t),e.unshift(t)},remove(t){var n;e=g(e,t),null===(n=e[0])||void 0===n||n.resume()}}}();function g(e,t){const n=[...e],r=n.indexOf(t);return-1!==r&&n.splice(r,1),n}},54581:(e,t,n)=>{n.d(t,{ee:()=>N,Eh:()=>I,VY:()=>F,fC:()=>D,D7:()=>x});var r=n(87462),o=n(99196),i=n(88301),a=n(55863),c=n(91850),u="undefined"!=typeof document?o.useLayoutEffect:o.useEffect;function l(e,t){if(e===t)return!0;if(typeof e!=typeof t)return!1;if("function"==typeof e&&e.toString()===t.toString())return!0;let n,r,o;if(e&&t&&"object"==typeof e){if(Array.isArray(e)){if(n=e.length,n!=t.length)return!1;for(r=n;0!=r--;)if(!l(e[r],t[r]))return!1;return!0}if(o=Object.keys(e),n=o.length,n!==Object.keys(t).length)return!1;for(r=n;0!=r--;)if(!{}.hasOwnProperty.call(t,o[r]))return!1;for(r=n;0!=r--;){const n=o[r];if(!("_owner"===n&&e.$$typeof||l(e[n],t[n])))return!1}return!0}return e!=e&&t!=t}function s(e){return"undefined"==typeof window?1:(e.ownerDocument.defaultView||window).devicePixelRatio||1}function d(e,t){const n=s(e);return Math.round(t*n)/n}function f(e){const t=o.useRef(e);return u((()=>{t.current=e})),t}var p=n(75320);const v=(0,o.forwardRef)(((e,t)=>{const{children:n,width:i=10,height:a=5,...c}=e;return(0,o.createElement)(p.WV.svg,(0,r.Z)({},c,{ref:t,width:i,height:a,viewBox:"0 0 30 10",preserveAspectRatio:"none"}),e.asChild?n:(0,o.createElement)("polygon",{points:"0,0 30,0 15,10"}))}));var m=n(28771),h=n(25360),g=n(79698),y=n(9981),w=n(7546);const b="Popper",[E,x]=(0,h.b)(b),[C,S]=E(b),P=(0,o.forwardRef)(((e,t)=>{const{__scopePopper:n,virtualRef:i,...a}=e,c=S("PopperAnchor",n),u=(0,o.useRef)(null),l=(0,m.e)(t,u);return(0,o.useEffect)((()=>{c.onAnchorChange((null==i?void 0:i.current)||u.current)})),i?null:(0,o.createElement)(p.WV.div,(0,r.Z)({},a,{ref:l}))})),T="PopperContent",[A,O]=E(T),R=(0,o.forwardRef)(((e,t)=>{var n,v,h,b,E,x,C,P;const{__scopePopper:O,side:R="bottom",sideOffset:k=0,align:D="center",alignOffset:N=0,arrowPadding:F=0,collisionBoundary:I=[],collisionPadding:B=0,sticky:j="partial",hideWhenDetached:_=!1,avoidCollisions:Y=!0,onPlaced:H,...X}=e,Z=S(T,O),[$,z]=(0,o.useState)(null),K=(0,m.e)(t,(e=>z(e))),[V,q]=(0,o.useState)(null),U=(0,w.t)(V),G=null!==(n=null==U?void 0:U.width)&&void 0!==n?n:0,J=null!==(v=null==U?void 0:U.height)&&void 0!==v?v:0,Q=R+("center"!==D?"-"+D:""),ee="number"==typeof B?B:{top:0,right:0,bottom:0,left:0,...B},te=Array.isArray(I)?I:[I],ne=te.length>0,re={padding:ee,boundary:te.filter(L),altBoundary:ne},{refs:oe,floatingStyles:ie,placement:ae,isPositioned:ce,middlewareData:ue}=function(e){void 0===e&&(e={});const{placement:t="bottom",strategy:n="absolute",middleware:r=[],platform:i,elements:{reference:p,floating:v}={},transform:m=!0,whileElementsMounted:h,open:g}=e,[y,w]=o.useState({x:0,y:0,strategy:n,placement:t,middlewareData:{},isPositioned:!1}),[b,E]=o.useState(r);l(b,r)||E(r);const[x,C]=o.useState(null),[S,P]=o.useState(null),T=o.useCallback((e=>{e!=k.current&&(k.current=e,C(e))}),[C]),A=o.useCallback((e=>{e!==L.current&&(L.current=e,P(e))}),[P]),O=p||x,R=v||S,k=o.useRef(null),L=o.useRef(null),W=o.useRef(y),M=f(h),D=f(i),N=o.useCallback((()=>{if(!k.current||!L.current)return;const e={placement:t,strategy:n,middleware:b};D.current&&(e.platform=D.current),(0,a.oo)(k.current,L.current,e).then((e=>{const t={...e,isPositioned:!0};F.current&&!l(W.current,t)&&(W.current=t,c.flushSync((()=>{w(t)})))}))}),[b,t,n,D]);u((()=>{!1===g&&W.current.isPositioned&&(W.current.isPositioned=!1,w((e=>({...e,isPositioned:!1}))))}),[g]);const F=o.useRef(!1);u((()=>(F.current=!0,()=>{F.current=!1})),[]),u((()=>{if(O&&(k.current=O),R&&(L.current=R),O&&R){if(M.current)return M.current(O,R,N);N()}}),[O,R,N,M]);const I=o.useMemo((()=>({reference:k,floating:L,setReference:T,setFloating:A})),[T,A]),B=o.useMemo((()=>({reference:O,floating:R})),[O,R]),j=o.useMemo((()=>{const e={position:n,left:0,top:0};if(!B.floating)return e;const t=d(B.floating,y.x),r=d(B.floating,y.y);return m?{...e,transform:"translate("+t+"px, "+r+"px)",...s(B.floating)>=1.5&&{willChange:"transform"}}:{position:n,left:t,top:r}}),[n,m,B.floating,y.x,y.y]);return o.useMemo((()=>({...y,update:N,refs:I,elements:B,floatingStyles:j})),[y,N,I,B,j])}({strategy:"fixed",placement:Q,whileElementsMounted:a.Me,elements:{reference:Z.anchor},middleware:[(0,i.cv)({mainAxis:k+J,alignmentAxis:N}),Y&&(0,i.uY)({mainAxis:!0,crossAxis:!1,limiter:"partial"===j?(0,i.dr)():void 0,...re}),Y&&(0,i.RR)({...re}),(0,i.dp)({...re,apply:({elements:e,rects:t,availableWidth:n,availableHeight:r})=>{const{width:o,height:i}=t.reference,a=e.floating.style;a.setProperty("--radix-popper-available-width",`${n}px`),a.setProperty("--radix-popper-available-height",`${r}px`),a.setProperty("--radix-popper-anchor-width",`${o}px`),a.setProperty("--radix-popper-anchor-height",`${i}px`)}}),V&&(le={element:V,padding:F},{name:"arrow",options:le,fn(e){const{element:t,padding:n}="function"==typeof le?le(e):le;return t&&(r=t,{}.hasOwnProperty.call(r,"current"))?null!=t.current?(0,i.x7)({element:t.current,padding:n}).fn(e):{}:t?(0,i.x7)({element:t,padding:n}).fn(e):{};var r}}),W({arrowWidth:G,arrowHeight:J}),_&&(0,i.Cp)({strategy:"referenceHidden"})]});var le;const[se,de]=M(ae),fe=(0,g.W)(H);(0,y.b)((()=>{ce&&(null==fe||fe())}),[ce,fe]);const pe=null===(h=ue.arrow)||void 0===h?void 0:h.x,ve=null===(b=ue.arrow)||void 0===b?void 0:b.y,me=0!==(null===(E=ue.arrow)||void 0===E?void 0:E.centerOffset),[he,ge]=(0,o.useState)();return(0,y.b)((()=>{$&&ge(window.getComputedStyle($).zIndex)}),[$]),(0,o.createElement)("div",{ref:oe.setFloating,"data-radix-popper-content-wrapper":"",style:{...ie,transform:ce?ie.transform:"translate(0, -200%)",minWidth:"max-content",zIndex:he,"--radix-popper-transform-origin":[null===(x=ue.transformOrigin)||void 0===x?void 0:x.x,null===(C=ue.transformOrigin)||void 0===C?void 0:C.y].join(" ")},dir:e.dir},(0,o.createElement)(A,{scope:O,placedSide:se,onArrowChange:q,arrowX:pe,arrowY:ve,shouldHideArrow:me},(0,o.createElement)(p.WV.div,(0,r.Z)({"data-side":se,"data-align":de},X,{ref:K,style:{...X.style,animation:ce?void 0:"none",opacity:null!==(P=ue.hide)&&void 0!==P&&P.referenceHidden?0:void 0}}))))})),k={top:"bottom",right:"left",bottom:"top",left:"right"};function L(e){return null!==e}const W=e=>({name:"transformOrigin",options:e,fn(t){var n,r,o,i,a;const{placement:c,rects:u,middlewareData:l}=t,s=0!==(null===(n=l.arrow)||void 0===n?void 0:n.centerOffset),d=s?0:e.arrowWidth,f=s?0:e.arrowHeight,[p,v]=M(c),m={start:"0%",center:"50%",end:"100%"}[v],h=(null!==(r=null===(o=l.arrow)||void 0===o?void 0:o.x)&&void 0!==r?r:0)+d/2,g=(null!==(i=null===(a=l.arrow)||void 0===a?void 0:a.y)&&void 0!==i?i:0)+f/2;let y="",w="";return"bottom"===p?(y=s?m:`${h}px`,w=-f+"px"):"top"===p?(y=s?m:`${h}px`,w=`${u.floating.height+f}px`):"right"===p?(y=-f+"px",w=s?m:`${g}px`):"left"===p&&(y=`${u.floating.width+f}px`,w=s?m:`${g}px`),{data:{x:y,y:w}}}});function M(e){const[t,n="center"]=e.split("-");return[t,n]}const D=e=>{const{__scopePopper:t,children:n}=e,[r,i]=(0,o.useState)(null);return(0,o.createElement)(C,{scope:t,anchor:r,onAnchorChange:i},n)},N=P,F=R,I=(0,o.forwardRef)((function(e,t){const{__scopePopper:n,...i}=e,a=O("PopperArrow",n),c=k[a.placedSide];return(0,o.createElement)("span",{ref:a.onArrowChange,style:{position:"absolute",left:a.arrowX,top:a.arrowY,[c]:0,transformOrigin:{top:"",right:"0 0",bottom:"center 0",left:"100% 0"}[a.placedSide],transform:{top:"translateY(100%)",right:"translateY(50%) rotate(90deg) translateX(-50%)",bottom:"rotate(180deg)",left:"translateY(50%) rotate(-90deg) translateX(50%)"}[a.placedSide],visibility:a.shouldHideArrow?"hidden":void 0}},(0,o.createElement)(v,(0,r.Z)({},i,{ref:t,style:{...i.style,display:"block"}})))}))},42651:(e,t,n)=>{n.d(t,{h:()=>c});var r=n(87462),o=n(99196),i=n(91850),a=n(75320);const c=(0,o.forwardRef)(((e,t)=>{var n;const{container:c=(null===globalThis||void 0===globalThis||null===(n=globalThis.document)||void 0===n?void 0:n.body),...u}=e;return c?i.createPortal((0,o.createElement)(a.WV.div,(0,r.Z)({},u,{ref:t})),c):null}))}}]);