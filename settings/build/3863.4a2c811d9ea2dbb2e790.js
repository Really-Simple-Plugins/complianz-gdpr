"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[3863,881,2588,5671],{80881:(e,t,n)=>{n.r(t),n.d(t,{default:()=>i});var c=n(69307),r=n(99196),a=n(48399),l=n(56293),s=n(82485),o=n(55609);const i=(0,r.memo)((e=>{let{type:t="action",style:n="tertiary",label:i,onClick:d,href:u="",target:p="",disabled:m,action:f,field:h,children:b}=e;if(!i&&!b)return null;const g=(h&&h.button_text?h.button_text:i)||b,{fetchFieldsData:E,showSavedSettingsNotice:k}=(0,l.default)(),{selectedSubMenuItem:_}=(0,s.default)(),[y,v]=(0,r.useState)(!1),w=`button cmplz-button button--${n} button-${t}`,N=async e=>{await a.doAction(h.action,{}).then((e=>{e.success&&(E(_),k(e.message))}))},C=h&&h.warn?h.warn:"";return"action"===t?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(o.__experimentalConfirmDialog,{isOpen:y,onConfirm:async()=>{v(!1),await N()},onCancel:()=>{v(!1)}},C),(0,c.createElement)("button",{className:w,onClick:async e=>{"action"===t&&d?d(e):"action"===t&&f?h&&h.warn?v(!0):await N():window.location.href=h.url},disabled:m},g)):"link"===t?(0,c.createElement)("a",{className:w,href:u,target:p},g):void 0}))},382:(e,t,n)=>{n.r(t),n.d(t,{default:()=>A});var c=n(69307),r=n(87462),a=n(99196),l=n(28771),s=n(25360),o=n(36206),i=n(77342),d=n(57898),u=n(7546),p=n(29115),m=n(75320);const f="Checkbox",[h,b]=(0,s.b)(f),[g,E]=h(f),k=(0,a.forwardRef)(((e,t)=>{const{__scopeCheckbox:n,name:c,checked:s,defaultChecked:d,required:u,disabled:p,value:f="on",onCheckedChange:h,...b}=e,[E,k]=(0,a.useState)(null),w=(0,l.e)(t,(e=>k(e))),N=(0,a.useRef)(!1),C=!E||Boolean(E.closest("form")),[z=!1,S]=(0,i.T)({prop:s,defaultProp:d,onChange:h}),A=(0,a.useRef)(z);return(0,a.useEffect)((()=>{const e=null==E?void 0:E.form;if(e){const t=()=>S(A.current);return e.addEventListener("reset",t),()=>e.removeEventListener("reset",t)}}),[E,S]),(0,a.createElement)(g,{scope:n,state:z,disabled:p},(0,a.createElement)(m.WV.button,(0,r.Z)({type:"button",role:"checkbox","aria-checked":y(z)?"mixed":z,"aria-required":u,"data-state":v(z),"data-disabled":p?"":void 0,disabled:p,value:f},b,{ref:w,onKeyDown:(0,o.M)(e.onKeyDown,(e=>{"Enter"===e.key&&e.preventDefault()})),onClick:(0,o.M)(e.onClick,(e=>{S((e=>!!y(e)||!e)),C&&(N.current=e.isPropagationStopped(),N.current||e.stopPropagation())}))})),C&&(0,a.createElement)(_,{control:E,bubbles:!N.current,name:c,value:f,checked:z,required:u,disabled:p,style:{transform:"translateX(-100%)"}}))})),_=e=>{const{control:t,checked:n,bubbles:c=!0,...l}=e,s=(0,a.useRef)(null),o=(0,d.D)(n),i=(0,u.t)(t);return(0,a.useEffect)((()=>{const e=s.current,t=window.HTMLInputElement.prototype,r=Object.getOwnPropertyDescriptor(t,"checked").set;if(o!==n&&r){const t=new Event("click",{bubbles:c});e.indeterminate=y(n),r.call(e,!y(n)&&n),e.dispatchEvent(t)}}),[o,n,c]),(0,a.createElement)("input",(0,r.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:!y(n)&&n},l,{tabIndex:-1,ref:s,style:{...e.style,...i,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function y(e){return"indeterminate"===e}function v(e){return y(e)?"indeterminate":e?"checked":"unchecked"}const w=k,N=(0,a.forwardRef)(((e,t)=>{const{__scopeCheckbox:n,forceMount:c,...l}=e,s=E("CheckboxIndicator",n);return(0,a.createElement)(p.z,{present:c||y(s.state)||!0===s.state},(0,a.createElement)(m.WV.span,(0,r.Z)({"data-state":v(s.state),"data-disabled":s.disabled?"":void 0},l,{ref:t,style:{pointerEvents:"none",...e.style}})))}));var C=n(65736),z=n(20384),S=n(80881);const A=(0,a.memo)((e=>{let{label:t,value:n,id:r,onChange:l,required:s,disabled:o,options:i={}}=e,d=n;Array.isArray(d)||(d=""===d?[]:[d]);const u=d,[p,m]=(0,a.useState)(!1);let f=!1;Object.keys(i).length>10&&(f=!0);const h=e=>u.includes(e),b=()=>{m(!p)};let g=o&&!Array.isArray(o);return(0,c.createElement)("div",{className:"cmplz-checkbox-group"},Object.entries(i).map(((e,a)=>{let[d,m]=e;return(0,c.createElement)("div",{key:d,className:"cmplz-checkbox-group__item"+(!p&&a>10?" cmplz-hidden":"")},(0,c.createElement)(w,{className:"cmplz-checkbox-group__checkbox",id:r+"_"+d,checked:1===Object.keys(i).length?n:h(d),"aria-label":t,disabled:g||Array.isArray(o)&&o.includes(d),required:s,onCheckedChange:e=>((e,t)=>{if(1===Object.keys(i).length)l(!n);else{const e=u.includes(t)?u.filter((e=>e!==t)):[...u,t];l(e)}})(0,d)},(0,c.createElement)(N,{className:"cmplz-checkbox-group__indicator"},(0,c.createElement)(z.default,{name:"check",size:14,color:"dark-blue"}))),(0,c.createElement)("label",{className:"cmplz-checkbox-group__label",htmlFor:r+"_"+d},m))})),!p&&f&&(0,c.createElement)(S.default,{onClick:b},(0,C.__)("Show more","complianz-gdpr")),p&&f&&(0,c.createElement)(S.default,{onClick:b},(0,C.__)("Show less","complianz-gdpr")))}))},32588:(e,t,n)=>{n.r(t),n.d(t,{default:()=>o});var c=n(69307),r=n(99196),a=n(79552),l=n(20384),s=n(65736);const o=(0,r.memo)((e=>{let{value:t=!1,onChange:n,required:r,defaultValue:o,disabled:i,options:d={},canBeEmpty:u=!0,label:p,innerRef:m}=e;if(Array.isArray(d)){let e={};d.map((t=>{e[t.value]=t.label})),d=e}return u?d={0:(0,s.__)("Select an option","complianz-gdpr"),...d}:t||(t=Object.keys(d)[0]),(0,c.createElement)("div",{className:"cmplz-input-group cmplz-select-group",key:p},(0,c.createElement)(a.fC,{value:t,defaultValue:o,onValueChange:n,required:r,disabled:i&&!Array.isArray(i)},(0,c.createElement)(a.xz,{className:"cmplz-select-group__trigger"},(0,c.createElement)(a.B4,null),(0,c.createElement)(l.default,{name:"chevron-down"})),(0,c.createElement)(a.VY,{className:"cmplz-select-group__content",position:"popper"},(0,c.createElement)(a.u_,{className:"cmplz-select-group__scroll-button"},(0,c.createElement)(l.default,{name:"chevron-up"})),(0,c.createElement)(a.l_,{className:"cmplz-select-group__viewport"},(0,c.createElement)(a.ZA,null,Object.entries(d).map((e=>{let[t,n]=e;return(0,c.createElement)(a.ck,{disabled:Array.isArray(i)&&i.includes(t),className:"cmplz-select-group__item",key:t,value:t},(0,c.createElement)(a.eT,null,n))})))),(0,c.createElement)(a.$G,{className:"cmplz-select-group__scroll-button"},(0,c.createElement)(l.default,{name:"chevron-down"})))))}))},38857:(e,t,n)=>{n.r(t),n.d(t,{default:()=>w});var c=n(69307),r=n(99196),a=n(87462),l=n(36206),s=n(28771),o=n(25360),i=n(77342),d=n(57898),u=n(7546),p=n(75320);const m="Switch",[f,h]=(0,o.b)(m),[b,g]=f(m),E=(0,r.forwardRef)(((e,t)=>{const{__scopeSwitch:n,name:c,checked:o,defaultChecked:d,required:u,disabled:m,value:f="on",onCheckedChange:h,...g}=e,[E,y]=(0,r.useState)(null),v=(0,s.e)(t,(e=>y(e))),w=(0,r.useRef)(!1),N=!E||Boolean(E.closest("form")),[C=!1,z]=(0,i.T)({prop:o,defaultProp:d,onChange:h});return(0,r.createElement)(b,{scope:n,checked:C,disabled:m},(0,r.createElement)(p.WV.button,(0,a.Z)({type:"button",role:"switch","aria-checked":C,"aria-required":u,"data-state":_(C),"data-disabled":m?"":void 0,disabled:m,value:f},g,{ref:v,onClick:(0,l.M)(e.onClick,(e=>{z((e=>!e)),N&&(w.current=e.isPropagationStopped(),w.current||e.stopPropagation())}))})),N&&(0,r.createElement)(k,{control:E,bubbles:!w.current,name:c,value:f,checked:C,required:u,disabled:m,style:{transform:"translateX(-100%)"}}))})),k=e=>{const{control:t,checked:n,bubbles:c=!0,...l}=e,s=(0,r.useRef)(null),o=(0,d.D)(n),i=(0,u.t)(t);return(0,r.useEffect)((()=>{const e=s.current,t=window.HTMLInputElement.prototype,r=Object.getOwnPropertyDescriptor(t,"checked").set;if(o!==n&&r){const t=new Event("click",{bubbles:c});r.call(e,n),e.dispatchEvent(t)}}),[o,n,c]),(0,r.createElement)("input",(0,a.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:n},l,{tabIndex:-1,ref:s,style:{...e.style,...i,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function _(e){return e?"checked":"unchecked"}const y=E,v=(0,r.forwardRef)(((e,t)=>{const{__scopeSwitch:n,...c}=e,l=g("SwitchThumb",n);return(0,r.createElement)(p.WV.span,(0,a.Z)({"data-state":_(l.checked),"data-disabled":l.disabled?"":void 0},c,{ref:t}))})),w=(0,r.memo)((e=>{let{value:t,onChange:n,required:r,disabled:a,className:l,label:s}=e,o=t;return"0"!==t&&"1"!==t||(o="1"===t),(0,c.createElement)("div",{className:"cmplz-input-group cmplz-switch-group"},(0,c.createElement)(y,{className:"cmplz-switch-root "+l,checked:o,onCheckedChange:n,disabled:a,required:r},(0,c.createElement)(v,{className:"cmplz-switch-thumb"})))}))},13863:(e,t,n)=>{n.r(t),n.d(t,{default:()=>o});var c=n(69307),r=n(65736),a=(n(38857),n(32588)),l=n(85671),s=n(382);const o=e=>{const{setScript:t,blockedScripts:n,fetching:o}=(0,l.default)(),i=n,d=e.script,u=e=>{if(!d.dependency||0===d.dependency.length)return"";let t=Object.entries(d.dependency);for(const[n,c]of t)if(n===e)return c;return""},p=(e,t)=>{let n={...e};for(const[e,c]of Object.entries(n))if(c===t){delete n[e];break}return n};let m=d.hasOwnProperty("urls")?Object.entries(d.urls):[""];return(0,c.createElement)(c.Fragment,null,(0,c.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,c.createElement)(s.default,{id:d.id+"dependency",disabled:o,value:d.enable_dependency,onChange:n=>((n,c)=>{let r={...d};r.enable_dependency=n,t(r,e.type)})(n),options:{dependency:(0,r.__)("Enable dependency","complianz-gdpr")}})),!!d.enable_dependency&&(0,c.createElement)("div",{className:"cmplz-details-row cmplz-details-row"},m.length>1&&m.map(((n,l)=>{let[s,m]=n;return(0,c.createElement)("div",{key:l,className:"cmplz-scriptcenter-dependencies"},(0,c.createElement)(a.default,{disabled:o,value:u(m),options:p(i,m),onChange:n=>((n,c)=>{let r={...d},a={...r.dependency};a[c]=n,r.dependency=a,t(r,e.type)})(n,m)}),(0,c.createElement)("div",null,(0,r.__)("waits for: ","complianz-gdpr"),m||(0,r.__)("Empty URL","complianz-gdpr")))})),m.length<=1&&(0,c.createElement)(c.Fragment,null,(0,r.__)("Add a URL to create a dependency between two URLs","complianz-gdpr"))))}},85671:(e,t,n)=>{n.r(t),n.d(t,{default:()=>l});var c=n(30270),r=n(12902),a=n(48399);const l=(0,c.Ue)(((e,t)=>({integrationsLoaded:!1,fetching:!1,services:[],plugins:[],scripts:[],placeholders:[],blockedScripts:[],setScript:(t,n)=>{e((0,r.ZP)((e=>{if("block_script"===n){let n=e.blockedScripts;if(t.urls){for(const[e,c]of Object.entries(t.urls)){if(!c||0===c.length)continue;let e=!1;for(const[t,r]of Object.entries(n))c===t&&(e=!0);e||(n[c]=c)}e.blockedScripts=n}}const c=e.scripts[n].findIndex((e=>e.id===t.id));-1!==c&&(e.scripts[n][c]=t)})))},fetchIntegrationsData:async()=>{if(t().fetching)return;e({fetching:!0});const{services:n,plugins:c,scripts:r,placeholders:a,blocked_scripts:l}=await s();let o=r;o.block_script.forEach(((e,t)=>{e.id=t})),o.add_script.forEach(((e,t)=>{e.id=t})),o.whitelist_script.forEach(((e,t)=>{e.id=t})),e((()=>({integrationsLoaded:!0,services:n,plugins:c,scripts:o,fetching:!1,placeholders:a,blockedScripts:l})))},addScript:n=>{e({fetching:!0}),e((0,r.ZP)((e=>{e.scripts[n].push({name:"general",id:e.scripts[n].length,enable:!0})})));let c=t().scripts;return a.doAction("update_scripts",{scripts:c}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},saveScript:(n,c)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const t=e.scripts[c].findIndex((e=>e.id===n.id));-1!==t&&(e.scripts[c][t]=n)})));let l=t().scripts;return a.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},deleteScript:(n,c)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const t=e.scripts[c].findIndex((e=>e.id===n.id));-1!==t&&e.scripts[c].splice(t,1)})));let l=t().scripts;return a.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},updatePluginStatus:async(t,n)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const c=e.plugins.findIndex((e=>e.id===t));-1!==c&&(e.plugins[c].enabled=n)})));const c=await a.doAction("update_plugin_status",{plugin:t,enabled:n}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),c},updatePlaceholderStatus:async(t,n,c)=>{e({fetching:!0}),c&&e((0,r.ZP)((e=>{const c=e.plugins.findIndex((e=>e.id===t));-1!==c&&(e.plugins[c].placeholder=n?"enabled":"disabled")})));const l=await a.doAction("update_placeholder_status",{id:t,enabled:n}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),l}}))),s=()=>a.doAction("get_integrations_data",{}).then((e=>e)).catch((e=>{console.error(e)}))},29115:(e,t,n)=>{n.d(t,{z:()=>s});var c=n(99196),r=n(91850),a=n(28771),l=n(9981);const s=e=>{const{present:t,children:n}=e,s=function(e){const[t,n]=(0,c.useState)(),a=(0,c.useRef)({}),s=(0,c.useRef)(e),i=(0,c.useRef)("none"),d=e?"mounted":"unmounted",[u,p]=function(e,t){return(0,c.useReducer)(((e,n)=>{const c=t[e][n];return null!=c?c:e}),e)}(d,{mounted:{UNMOUNT:"unmounted",ANIMATION_OUT:"unmountSuspended"},unmountSuspended:{MOUNT:"mounted",ANIMATION_END:"unmounted"},unmounted:{MOUNT:"mounted"}});return(0,c.useEffect)((()=>{const e=o(a.current);i.current="mounted"===u?e:"none"}),[u]),(0,l.b)((()=>{const t=a.current,n=s.current;if(n!==e){const c=i.current,r=o(t);e?p("MOUNT"):"none"===r||"none"===(null==t?void 0:t.display)?p("UNMOUNT"):p(n&&c!==r?"ANIMATION_OUT":"UNMOUNT"),s.current=e}}),[e,p]),(0,l.b)((()=>{if(t){const e=e=>{const n=o(a.current).includes(e.animationName);e.target===t&&n&&(0,r.flushSync)((()=>p("ANIMATION_END")))},n=e=>{e.target===t&&(i.current=o(a.current))};return t.addEventListener("animationstart",n),t.addEventListener("animationcancel",e),t.addEventListener("animationend",e),()=>{t.removeEventListener("animationstart",n),t.removeEventListener("animationcancel",e),t.removeEventListener("animationend",e)}}p("ANIMATION_END")}),[t,p]),{isPresent:["mounted","unmountSuspended"].includes(u),ref:(0,c.useCallback)((e=>{e&&(a.current=getComputedStyle(e)),n(e)}),[])}}(t),i="function"==typeof n?n({present:s.isPresent}):c.Children.only(n),d=(0,a.e)(s.ref,i.ref);return"function"==typeof n||s.isPresent?(0,c.cloneElement)(i,{ref:d}):null};function o(e){return(null==e?void 0:e.animationName)||"none"}s.displayName="Presence"}}]);