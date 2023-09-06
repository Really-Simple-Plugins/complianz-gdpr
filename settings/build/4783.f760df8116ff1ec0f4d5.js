"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[4783,2175,881,2588,5294,5671,849],{32175:function(e,t,n){n.r(t),n.d(t,{UseCookieScanData:function(){return c}});var a=n(30270),r=n(48399);const c=(0,a.Ue)(((e,t)=>({initialLoadCompleted:!1,setInitialLoadCompleted:t=>e({initialLoadCompleted:t}),iframeLoaded:!1,loading:!1,nextPage:!1,progress:0,cookies:[],lastLoadedIframe:"",setIframeLoaded:t=>e({iframeLoaded:t}),setLastLoadedIframe:t=>e((e=>({lastLoadedIframe:t}))),setProgress:t=>e({progress:t}),fetchProgress:()=>(e({loading:!0}),r.doAction("get_scan_progress",{}).then((t=>(e({initialLoadCompleted:!0,loading:!1,nextPage:t.next_page,progress:t.progress,cookies:t.cookies}),t))))})))},80881:function(e,t,n){n.r(t);var a=n(69307),r=n(99196),c=n(48399),l=n(56293),o=n(82485),s=n(55609),i=n(32175),d=n(82387);t.default=(0,r.memo)((e=>{let{type:t="action",style:n="tertiary",label:u,onClick:p,href:m="",target:f="",disabled:h,action:b,field:g,children:_}=e;if(!u&&!_)return null;const E=(g&&g.button_text?g.button_text:u)||_,{fetchFieldsData:k,showSavedSettingsNotice:v}=(0,l.default)(),{setInitialLoadCompleted:y,setProgress:C}=(0,i.UseCookieScanData)(),{setProgressLoaded:w}=(0,d.default)(),{selectedSubMenuItem:N}=(0,o.default)(),[z,S]=(0,r.useState)(!1),x=`button cmplz-button button--${n} button-${t}`,I=async e=>{await c.doAction(g.action,{}).then((e=>{e.success&&(k(N),"reset_settings"===e.id&&(y(!1),C(0),w(!1)),v(e.message))}))},A=g&&g.warn?g.warn:"";return"action"===t?(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.__experimentalConfirmDialog,{isOpen:z,onConfirm:async()=>{S(!1),await I()},onCancel:()=>{S(!1)}},A),(0,a.createElement)("button",{className:x,onClick:async e=>{"action"===t&&p?p(e):"action"===t&&b?g&&g.warn?S(!0):await I():window.location.href=g.url},disabled:h},E)):"link"===t?(0,a.createElement)("a",{className:x,href:m,target:f},E):void 0}))},382:function(e,t,n){n.r(t),n.d(t,{default:function(){return x}});var a=n(69307),r=n(87462),c=n(99196),l=n(28771),o=n(25360),s=n(36206),i=n(77342),d=n(57898),u=n(7546),p=n(29115),m=n(75320);const f="Checkbox",[h,b]=(0,o.b)(f),[g,_]=h(f),E=(0,c.forwardRef)(((e,t)=>{const{__scopeCheckbox:n,name:a,checked:o,defaultChecked:d,required:u,disabled:p,value:f="on",onCheckedChange:h,...b}=e,[_,E]=(0,c.useState)(null),C=(0,l.e)(t,(e=>E(e))),w=(0,c.useRef)(!1),N=!_||Boolean(_.closest("form")),[z=!1,S]=(0,i.T)({prop:o,defaultProp:d,onChange:h}),x=(0,c.useRef)(z);return(0,c.useEffect)((()=>{const e=null==_?void 0:_.form;if(e){const t=()=>S(x.current);return e.addEventListener("reset",t),()=>e.removeEventListener("reset",t)}}),[_,S]),(0,c.createElement)(g,{scope:n,state:z,disabled:p},(0,c.createElement)(m.WV.button,(0,r.Z)({type:"button",role:"checkbox","aria-checked":v(z)?"mixed":z,"aria-required":u,"data-state":y(z),"data-disabled":p?"":void 0,disabled:p,value:f},b,{ref:C,onKeyDown:(0,s.M)(e.onKeyDown,(e=>{"Enter"===e.key&&e.preventDefault()})),onClick:(0,s.M)(e.onClick,(e=>{S((e=>!!v(e)||!e)),N&&(w.current=e.isPropagationStopped(),w.current||e.stopPropagation())}))})),N&&(0,c.createElement)(k,{control:_,bubbles:!w.current,name:a,value:f,checked:z,required:u,disabled:p,style:{transform:"translateX(-100%)"}}))})),k=e=>{const{control:t,checked:n,bubbles:a=!0,...l}=e,o=(0,c.useRef)(null),s=(0,d.D)(n),i=(0,u.t)(t);return(0,c.useEffect)((()=>{const e=o.current,t=window.HTMLInputElement.prototype,r=Object.getOwnPropertyDescriptor(t,"checked").set;if(s!==n&&r){const t=new Event("click",{bubbles:a});e.indeterminate=v(n),r.call(e,!v(n)&&n),e.dispatchEvent(t)}}),[s,n,a]),(0,c.createElement)("input",(0,r.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:!v(n)&&n},l,{tabIndex:-1,ref:o,style:{...e.style,...i,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function v(e){return"indeterminate"===e}function y(e){return v(e)?"indeterminate":e?"checked":"unchecked"}const C=E,w=(0,c.forwardRef)(((e,t)=>{const{__scopeCheckbox:n,forceMount:a,...l}=e,o=_("CheckboxIndicator",n);return(0,c.createElement)(p.z,{present:a||v(o.state)||!0===o.state},(0,c.createElement)(m.WV.span,(0,r.Z)({"data-state":y(o.state),"data-disabled":o.disabled?"":void 0},l,{ref:t,style:{pointerEvents:"none",...e.style}})))}));var N=n(65736),z=n(23361),S=n(80881),x=(0,c.memo)((e=>{let{indeterminate:t,label:n,value:r,id:l,onChange:o,required:s,disabled:i,options:d={}}=e;const[u,p]=(0,c.useState)(!1);let m=r;Array.isArray(m)||(m=""===m?[]:[m]),(0,c.useEffect)((()=>{let e=1===Object.keys(d).length&&"true"===Object.keys(d)[0];p(e)}),[]),t&&(r=!0);const f=m,[h,b]=(0,c.useState)(!1);let g=!1;Object.keys(d).length>10&&(g=!0);const _=e=>u?r:f.includes(""+e)||f.includes(parseInt(e)),E=()=>{b(!h)};let k=i&&!Array.isArray(i);return 0===d.length?(0,a.createElement)(a.Fragment,null,(0,N.__)("No options found","complianz-gdpr")):(0,a.createElement)("div",{className:"cmplz-checkbox-group"},Object.entries(d).map(((e,c)=>{let[d,p]=e;return(0,a.createElement)("div",{key:d,className:"cmplz-checkbox-group__item"+(!h&&c>10?" cmplz-hidden":"")},(0,a.createElement)(C,{className:"cmplz-checkbox-group__checkbox",id:l+"_"+d,checked:_(d),"aria-label":n,disabled:k||Array.isArray(i)&&i.includes(d),required:s,onCheckedChange:e=>((e,t)=>{if(u)o(!r);else{const e=f.includes(""+t)||f.includes(parseInt(t))?f.filter((e=>e!==""+t&&e!==parseInt(t))):[...f,t];o(e)}})(0,d)},(0,a.createElement)(w,{className:"cmplz-checkbox-group__indicator"},(0,a.createElement)(z.default,{name:t?"indeterminate":"check",size:14,color:"dark-blue"}))),(0,a.createElement)("label",{className:"cmplz-checkbox-group__label",htmlFor:l+"_"+d},p))})),!h&&g&&(0,a.createElement)(S.default,{onClick:E},(0,N.__)("Show more","complianz-gdpr")),h&&g&&(0,a.createElement)(S.default,{onClick:E},(0,N.__)("Show less","complianz-gdpr")))}))},32588:function(e,t,n){n.r(t);var a=n(69307),r=n(99196),c=n(79552),l=n(23361),o=n(65736);t.default=(0,r.memo)((e=>{let{value:t=!1,onChange:n,required:r,defaultValue:s,disabled:i,options:d={},canBeEmpty:u=!0,label:p,innerRef:m}=e;if(Array.isArray(d)){let e={};d.map((t=>{e[t.value]=t.label})),d=e}return u?(""===t||!1===t||0===t)&&(d={0:(0,o.__)("Select an option","complianz-gdpr"),...d}):t||(t=Object.keys(d)[0]),(0,a.createElement)("div",{className:"cmplz-input-group cmplz-select-group",key:p},(0,a.createElement)(c.fC,{value:t,defaultValue:s,onValueChange:n,required:r,disabled:i&&!Array.isArray(i)},(0,a.createElement)(c.xz,{className:"cmplz-select-group__trigger"},(0,a.createElement)(c.B4,null),(0,a.createElement)(l.default,{name:"chevron-down"})),(0,a.createElement)(c.VY,{className:"cmplz-select-group__content",position:"popper"},(0,a.createElement)(c.u_,{className:"cmplz-select-group__scroll-button"},(0,a.createElement)(l.default,{name:"chevron-up"})),(0,a.createElement)(c.l_,{className:"cmplz-select-group__viewport"},(0,a.createElement)(c.ZA,null,Object.entries(d).map((e=>{let[t,n]=e;return(0,a.createElement)(c.ck,{disabled:Array.isArray(i)&&i.includes(t),className:"cmplz-select-group__item",key:t,value:t},(0,a.createElement)(c.eT,null,n))})))),(0,a.createElement)(c.$G,{className:"cmplz-select-group__scroll-button"},(0,a.createElement)(l.default,{name:"chevron-down"})))))}))},38857:function(e,t,n){n.r(t),n.d(t,{default:function(){return C}});var a=n(69307),r=n(99196),c=n(87462),l=n(36206),o=n(28771),s=n(25360),i=n(77342),d=n(57898),u=n(7546),p=n(75320);const m="Switch",[f,h]=(0,s.b)(m),[b,g]=f(m),_=(0,r.forwardRef)(((e,t)=>{const{__scopeSwitch:n,name:a,checked:s,defaultChecked:d,required:u,disabled:m,value:f="on",onCheckedChange:h,...g}=e,[_,v]=(0,r.useState)(null),y=(0,o.e)(t,(e=>v(e))),C=(0,r.useRef)(!1),w=!_||Boolean(_.closest("form")),[N=!1,z]=(0,i.T)({prop:s,defaultProp:d,onChange:h});return(0,r.createElement)(b,{scope:n,checked:N,disabled:m},(0,r.createElement)(p.WV.button,(0,c.Z)({type:"button",role:"switch","aria-checked":N,"aria-required":u,"data-state":k(N),"data-disabled":m?"":void 0,disabled:m,value:f},g,{ref:y,onClick:(0,l.M)(e.onClick,(e=>{z((e=>!e)),w&&(C.current=e.isPropagationStopped(),C.current||e.stopPropagation())}))})),w&&(0,r.createElement)(E,{control:_,bubbles:!C.current,name:a,value:f,checked:N,required:u,disabled:m,style:{transform:"translateX(-100%)"}}))})),E=e=>{const{control:t,checked:n,bubbles:a=!0,...l}=e,o=(0,r.useRef)(null),s=(0,d.D)(n),i=(0,u.t)(t);return(0,r.useEffect)((()=>{const e=o.current,t=window.HTMLInputElement.prototype,r=Object.getOwnPropertyDescriptor(t,"checked").set;if(s!==n&&r){const t=new Event("click",{bubbles:a});r.call(e,n),e.dispatchEvent(t)}}),[s,n,a]),(0,r.createElement)("input",(0,c.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:n},l,{tabIndex:-1,ref:o,style:{...e.style,...i,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function k(e){return e?"checked":"unchecked"}const v=_,y=(0,r.forwardRef)(((e,t)=>{const{__scopeSwitch:n,...a}=e,l=g("SwitchThumb",n);return(0,r.createElement)(p.WV.span,(0,c.Z)({"data-state":k(l.checked),"data-disabled":l.disabled?"":void 0},a,{ref:t}))}));var C=(0,r.memo)((e=>{let{value:t,onChange:n,required:r,disabled:c,className:l,label:o}=e,s=t;return"0"!==t&&"1"!==t||(s="1"===t),(0,a.createElement)("div",{className:"cmplz-input-group cmplz-switch-group"},(0,a.createElement)(v,{className:"cmplz-switch-root "+l,checked:s,onCheckedChange:n,disabled:c,required:r},(0,a.createElement)(y,{className:"cmplz-switch-thumb"})))}))},65294:function(e,t,n){n.r(t);var a=n(69307),r=n(99196);t.default=(0,r.memo)((e=>{let{value:t,onChange:n,required:c,defaultValue:l,disabled:o,id:s,name:i,placeholder:d}=e;const u=s||i,[p,m]=(0,r.useState)("");return(0,r.useEffect)((()=>{m(t||"")}),[t]),(0,r.useEffect)((()=>{const e=setTimeout((()=>{n(p)}),400);return()=>{clearTimeout(e)}}),[p]),(0,a.createElement)("div",{className:"cmplz-input-group cmplz-text-input-group"},(0,a.createElement)("input",{type:"text",id:u,name:i,value:p,onChange:e=>(e=>{m(e)})(e.target.value),required:c,disabled:o,className:"cmplz-text-input-group__input",placeholder:d}))}))},85671:function(e,t,n){n.r(t);var a=n(30270),r=n(12902),c=n(48399);const l=(0,a.Ue)(((e,t)=>({integrationsLoaded:!1,fetching:!1,services:[],plugins:[],scripts:[],placeholders:[],blockedScripts:[],setScript:(t,n)=>{e((0,r.ZP)((e=>{if("block_script"===n){let n=e.blockedScripts;if(t.urls){for(const[e,a]of Object.entries(t.urls)){if(!a||0===a.length)continue;let e=!1;for(const[t,r]of Object.entries(n))a===t&&(e=!0);e||(n[a]=a)}e.blockedScripts=n}}const a=e.scripts[n].findIndex((e=>e.id===t.id));-1!==a&&(e.scripts[n][a]=t)})))},fetchIntegrationsData:async()=>{if(t().fetching)return;e({fetching:!0});const{services:n,plugins:a,scripts:r,placeholders:c,blocked_scripts:l}=await o();let s=r;s.block_script&&s.block_script.length>0&&s.block_script.forEach(((e,t)=>{e.id=t})),s.add_script&&s.add_script.length>0&&s.add_script.forEach(((e,t)=>{e.id=t})),s.whitelist_script&&s.whitelist_script.length>0&&s.whitelist_script.forEach(((e,t)=>{e.id=t})),e((()=>({integrationsLoaded:!0,services:n,plugins:a,scripts:s,fetching:!1,placeholders:c,blockedScripts:l})))},addScript:n=>{e({fetching:!0}),e((0,r.ZP)((e=>{e.scripts[n].push({name:"general",id:e.scripts[n].length,enable:!0})})));let a=t().scripts;return c.doAction("update_scripts",{scripts:a}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},saveScript:(n,a)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const t=e.scripts[a].findIndex((e=>e.id===n.id));-1!==t&&(e.scripts[a][t]=n)})));let l=t().scripts;return c.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},deleteScript:(n,a)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const t=e.scripts[a].findIndex((e=>e.id===n.id));-1!==t&&e.scripts[a].splice(t,1)})));let l=t().scripts;return c.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},updatePluginStatus:async(t,n)=>{e({fetching:!0}),e((0,r.ZP)((e=>{const a=e.plugins.findIndex((e=>e.id===t));-1!==a&&(e.plugins[a].enabled=n)})));const a=await c.doAction("update_plugin_status",{plugin:t,enabled:n}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),a},updatePlaceholderStatus:async(t,n,a)=>{e({fetching:!0}),a&&e((0,r.ZP)((e=>{const a=e.plugins.findIndex((e=>e.id===t));-1!==a&&(e.plugins[a].placeholder=n?"enabled":"disabled")})));const l=await c.doAction("update_placeholder_status",{id:t,enabled:n}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),l}})));t.default=l;const o=()=>c.doAction("get_integrations_data",{}).then((e=>e)).catch((e=>{console.error(e)}))},44783:function(e,t,n){n.r(t);var a=n(69307),r=(n(38857),n(60849)),c=n(65294),l=n(32588),o=n(65736),s=n(85671),i=n(382);t.default=e=>{const{setScript:t,fetching:n,placeholders:d}=(0,s.default)(),u=e.script,p=e.type,m=(n,a)=>{let r={...u};r[a]=n,t(r,e.type)};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,a.createElement)("label",null,(0,o.__)("Placeholder","complianz-gdpr")),(0,a.createElement)(i.default,{id:u.id+"placeholder",disabled:n,value:u.enable_placeholder,onChange:e=>m(e,"enable_placeholder"),options:{true:(0,o.__)("Enable placeholder","complianz-gdpr")}})),!!u.enable_placeholder&&(0,a.createElement)(a.Fragment,null,"block_script"===p&&(0,a.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,a.createElement)(i.default,{id:u.id+"iframe",disabled:n,value:u.iframe||"",onChange:e=>m(e||"","iframe"),options:{true:(0,o.__)("The blocked content is an iframe","complianz-gdpr")}})),!u.iframe&&(0,a.createElement)("div",{className:"cmplz-details-row cmplz-details-row"},(0,a.createElement)("p",null,(0,o.__)("Enter the div class or ID that should be targeted.","complianz-gdpr"),(0,r.default)("https://complianz.io/integrating-plugins/#placeholder/")),(0,a.createElement)(c.default,{disabled:n,value:u.placeholder_class||"",onChange:e=>m(e||"","placeholder_class"),name:"placeholder_class",placeholder:(0,o.__)("Your CSS class","complianz-gdpr")})),(0,a.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,a.createElement)(l.default,{disabled:n,value:u.placeholder?u.placeholder:"default",options:d,onChange:e=>m(e||"default","placeholder")}))))}},60849:function(e,t,n){n.r(t);var a=n(69307),r=n(65736),c=n(99950);t.default=e=>(0,a.createElement)(a.Fragment,null," ",(0,a.createElement)(c.default,{url:e,text:(0,r.__)("For more information, please read this %sarticle%s.","complianz-gdpr")})," ")},29115:function(e,t,n){n.d(t,{z:function(){return o}});var a=n(99196),r=n(91850),c=n(28771),l=n(9981);const o=e=>{const{present:t,children:n}=e,o=function(e){const[t,n]=(0,a.useState)(),c=(0,a.useRef)({}),o=(0,a.useRef)(e),i=(0,a.useRef)("none"),d=e?"mounted":"unmounted",[u,p]=function(e,t){return(0,a.useReducer)(((e,n)=>{const a=t[e][n];return null!=a?a:e}),e)}(d,{mounted:{UNMOUNT:"unmounted",ANIMATION_OUT:"unmountSuspended"},unmountSuspended:{MOUNT:"mounted",ANIMATION_END:"unmounted"},unmounted:{MOUNT:"mounted"}});return(0,a.useEffect)((()=>{const e=s(c.current);i.current="mounted"===u?e:"none"}),[u]),(0,l.b)((()=>{const t=c.current,n=o.current;if(n!==e){const a=i.current,r=s(t);e?p("MOUNT"):"none"===r||"none"===(null==t?void 0:t.display)?p("UNMOUNT"):p(n&&a!==r?"ANIMATION_OUT":"UNMOUNT"),o.current=e}}),[e,p]),(0,l.b)((()=>{if(t){const e=e=>{const n=s(c.current).includes(e.animationName);e.target===t&&n&&(0,r.flushSync)((()=>p("ANIMATION_END")))},n=e=>{e.target===t&&(i.current=s(c.current))};return t.addEventListener("animationstart",n),t.addEventListener("animationcancel",e),t.addEventListener("animationend",e),()=>{t.removeEventListener("animationstart",n),t.removeEventListener("animationcancel",e),t.removeEventListener("animationend",e)}}p("ANIMATION_END")}),[t,p]),{isPresent:["mounted","unmountSuspended"].includes(u),ref:(0,a.useCallback)((e=>{e&&(c.current=getComputedStyle(e)),n(e)}),[])}}(t),i="function"==typeof n?n({present:o.isPresent}):a.Children.only(n),d=(0,c.e)(o.ref,i.ref);return"function"==typeof n||o.isPresent?(0,a.cloneElement)(i,{ref:d}):null};function s(e){return(null==e?void 0:e.animationName)||"none"}o.displayName="Presence"}}]);