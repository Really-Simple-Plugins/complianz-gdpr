"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[6265,2175,8189,5927,881,2588,4573],{32175:function(e,t,a){a.r(t),a.d(t,{UseCookieScanData:function(){return o}});var n=a(30270),l=a(48399);const o=(0,n.Ue)(((e,t)=>({initialLoadCompleted:!1,setInitialLoadCompleted:t=>e({initialLoadCompleted:t}),iframeLoaded:!1,loading:!1,nextPage:!1,progress:0,cookies:[],lastLoadedIframe:"",setIframeLoaded:t=>e({iframeLoaded:t}),setLastLoadedIframe:t=>e((e=>({lastLoadedIframe:t}))),setProgress:t=>e({progress:t}),fetchProgress:()=>(e({loading:!0}),l.doAction("get_scan_progress",{}).then((t=>(e({initialLoadCompleted:!0,loading:!1,nextPage:t.next_page,progress:t.progress,cookies:t.cookies}),t))))})))},68189:function(e,t,a){a.r(t);var n=a(69307),l=a(23361),o=a(65736),c=a(34573),r=a(99057),i=a(56293),s=a(382),d=a(32588);const m=e=>{const{getFieldValue:t,showSavedSettingsNotice:a}=(0,i.default)(),{language:l,saving:c,purposesOptions:m,services:u,updateCookie:p,toggleDeleteCookie:g,saveCookie:_}=(0,r.default)(),[f,v]=(0,n.useState)(""),[b,E]=(0,n.useState)(""),[h,k]=(0,n.useState)(""),[y,z]=(0,n.useState)([]);let w="no"!==t("use_cdb_api"),N=!!w&&1==e.sync,C=N;c&&(C=!0);let S=!1;e.slug.length>0&&(S="https://cookiedatabase.org/cookie/"+(e.service?e.service:"unknown-service")+"/"+e.slug),(0,n.useEffect)((()=>{e&&e.cookieFunction&&k(e.cookieFunction)}),[e]);const D=(e,t,a)=>{p(t,a,e)};(0,n.useEffect)((()=>{e&&e.name&&v(e.name)}),[e.name]),(0,n.useEffect)((()=>{const t=setTimeout((()=>{p(e.ID,"name",f)}),500);return()=>{clearTimeout(t)}}),[f]),(0,n.useEffect)((()=>{const t=setTimeout((()=>{p(e.ID,"cookieFunction",h)}),500);return()=>{clearTimeout(t)}}),[h]),(0,n.useEffect)((()=>{e&&e.retention&&E(e.retention)}),[e.retention]),(0,n.useEffect)((()=>{const t=setTimeout((()=>{p(e.ID,"retention",b)}),500);return()=>{clearTimeout(t)}}),[b]),(0,n.useEffect)((()=>{let e=m&&m.hasOwnProperty(l)?m[l]:[];e=e.map((e=>({label:e.label,value:e.label}))),z(e)}),[l,m]);const I=(e,t,a)=>{p(t,a,e)};let O=-1!==e.name.indexOf("cmplz_")||N,T=1!=e.deleted?"cmplz-reset-button":"",x=u.map(((e,t)=>({value:e.ID,label:e.name}))),P=!1,L="Marketing";y.forEach((function(e,t){e.value&&-1!==e.value.indexOf("/")&&(P=!0,L=e.value,L=L.substring(0,L.indexOf("/")))}));let A=-1!==e.purpose.indexOf("/");A&&(L=e.purpose.substring(0,e.purpose.indexOf("/"))),P&&!A&&y.forEach((function(e,t){e.value&&-1!==e.value.indexOf("/")&&(e.value=L,e.label=L,y[t]=e)}));let U=e.purpose;return!P&&A&&(U=L),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,n.createElement)(s.default,{id:e.ID+"_cdb_api",disabled:!w,value:N,onChange:t=>I(t,e.ID,"sync"),options:{true:(0,o.__)("Sync cookie with cookiedatabase.org","complianz-gdpr")}})),(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,n.createElement)(s.default,{id:e.ID+"showOnPolicy",disabled:C,value:e.showOnPolicy,onChange:t=>I(t,e.ID,"showOnPolicy"),options:{true:(0,o.__)("Show cookie on Cookie Policy","complianz-gdpr")}})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,o.__)("Name","complianz-gdpr")),(0,n.createElement)("input",{disabled:C,onChange:e=>v(e.target.value),type:"text",placeholder:(0,o.__)("Name","complianz-gdpr"),value:f})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,o.__)("Service","complianz-gdpr")),(0,n.createElement)(d.default,{disabled:C,value:e.serviceID,options:x,onChange:t=>D(t,e.ID,"serviceID")})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,o.__)("Expiration","complianz-gdpr")),(0,n.createElement)("input",{disabled:O,onChange:e=>E(e.target.value),type:"text",placeholder:(0,o.__)("1 year","complianz-gdpr"),value:b})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,o.__)("Cookie function","complianz-gdpr")),(0,n.createElement)("input",{disabled:C,onChange:e=>k(e.target.value),type:"text",placeholder:(0,o.__)("e.g. store user ID","complianz-gdpr"),value:h})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,o.__)("Purpose","complianz-gdpr")),(0,n.createElement)(d.default,{disabled:C,value:U,options:y,onChange:t=>D(t,e.ID,"purpose")})),S&&(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("a",{href:S,target:"_blank"},(0,o.__)("View cookie on cookiedatabase.org","complianz-gdpr"))),(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__buttons"},(0,n.createElement)("button",{disabled:c,onClick:t=>(async e=>{await _(e),a((0,o.__)("Saved cookie","complianz-gd[r"))})(e.ID),className:"button button-default"},(0,o.__)("Save","complianz-gdpr")),(0,n.createElement)("button",{className:"button button-default "+T,onClick:t=>(async e=>{await g(e)})(e.ID)},1==e.deleted&&(0,o.__)("Restore","complianz-gdpr"),1!=e.deleted&&(0,o.__)("Delete","complianz-gdpr"))))};t.default=(0,n.memo)((e=>{let{cookie:t}=e,a="";t.deleted?a=" | "+(0,o.__)("Deleted","complianz-gdpr"):t.showOnPolicy?t.isMembersOnly&&(a=" | "+(0,o.__)("Logged in users only, ignored","complianz-gdpr")):a=" | "+(0,o.__)("Admin, ignored","complianz-gdpr");let r=t.name;return(0,n.createElement)(n.Fragment,null,(0,n.createElement)(c.default,{summary:r,comment:a,icons:(0,n.createElement)(n.Fragment,null,t.complete&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("The data for this cookie is complete","complianz-gdpr"),name:"success",color:"green"}),!t.complete&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie has missing fields","complianz-gdpr"),name:"times",color:"red"}),t.sync&&t.synced&&(0,n.createElement)(l.default,{name:"rotate",color:"green"}),!t.synced||!t.sync&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie is not synchronized with cookiedatabase.org.","complianz-gdpr"),name:"rotate-error",color:"red"}),t.showOnPolicy&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie will be on your Cookie Policy","complianz-gdpr"),name:"file",color:"green"}),!t.showOnPolicy&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie is not shown on the Cookie Policy","complianz-gdpr"),name:"file-disabled",color:"grey"}),t.old&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie has not been detected on your site in the last three months","complianz-gdpr"),name:"calendar-error",color:"red"}),!t.old&&(0,n.createElement)(l.default,{tooltip:(0,o.__)("This cookie has recently been detected","complianz-gdpr"),name:"calendar",color:"green"})),details:m(t),style:(()=>{if(t.deleted)return Object.assign({},{backgroundColor:"var(--rsp-red-faded)"})})()}))}))},36265:function(e,t,a){a.r(t);var n=a(69307),l=a(99057),o=a(45927),c=a(65736),r=a(56293),i=a(382);t.default=(0,n.memo)((()=>{const{showDeletedCookies:e,setShowDeletedCookies:t,syncDataLoaded:a,loadingSyncData:s,language:d,setLanguage:m,languages:u,cookies:p,cookieCount:g,addCookie:_,addService:f,services:v,syncProgress:b,curlExists:E,hasSyncableData:h,setSyncProgress:k,restart:y,fetchSyncProgressData:z,errorMessage:w}=(0,l.default)(),{addHelpNotice:N,removeHelpNotice:C,getFieldValue:S}=(0,r.default)(),[D,I]=(0,n.useState)(!1),[O,T]=(0,n.useState)(!1),[x,P]=(0,n.useState)([]);return(0,n.useEffect)((()=>{!s&&b<100&&z()}),[b]),(0,n.useEffect)((()=>{if("no"!==S("use_cdb_api"))if(E)if(""!==w)I(!0),N("cookiedatabase_sync","warning",w,"Cookiedatabase","complianz-gdpr");else if(h){if(a)if(0===g){T(!0);let e=(0,c.__)("No cookies have been found currently. Please try another site scan, or check the most common causes in the article below ","complianz-gdpr");N("cookiedatabase_sync","warning",e,(0,c.__)("No cookies found","complianz-gdpr"),"https://complianz.io/cookie-scan-results/")}else O&&C("cookiedatabase_sync")}else{I(!0);let e=(0,c.__)("Synchronization disabled: All detected cookies and services have been synchronised.","complianz-gdpr");N("cookiedatabase_sync","warning",e,"Cookiedatabase","complianz-gdpr")}else{I(!0);let e=(0,c.__)("CURL is not enabled on your site, which is required for the Cookiedatabase sync to function.","complianz-gdpr");N("cookiedatabase_sync","warning",e,"Cookiedatabase","complianz-gdpr")}else{I(!0);let e=(0,c.__)("You have opted out of the use of the Cookiedatabase.org synchronization.","complianz-gdpr");N("cookiedatabase_sync","warning",e,"Cookiedatabase","complianz-gdpr")}}),[S("use_cdb_api"),E,w,h,x,a,p]),(0,n.useEffect)((()=>{b<100&&b>0&&I(!0)}),[b]),(0,n.useEffect)((()=>{let t=[...p].filter((t=>t.language===d&&(e||!e&&1!=t.deleted))).sort(((e,t)=>e.name.localeCompare(t.name)));const a={};[...v].filter((e=>e.language===d)).sort(((e,t)=>e.name.localeCompare(t.name))).forEach((function(e){a[e.ID]={id:e.ID,name:e.name,service:e,cookies:[]}})),t.forEach((function(e){let t=e.service?e.serviceID:0;a[t]||(a[t]={id:t,name:e.service?e.service:(0,c.__)("Unknown Service","complianz-gdpr"),service:v.filter((e=>e.ID===t))[0],cookies:[]}),a[t].cookies.push(e)})),P(Object.values(a))}),[v,p,d,e]),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("div",{className:"cmplz-cookiedatabase-controls"},(0,n.createElement)("button",{disabled:D,className:"button button-default",onClick:e=>(k(1),void y())},(0,c.__)("Sync","complianz-gdpr")),u.length>1&&(0,n.createElement)("select",{value:d,onChange:e=>m(e.target.value)},u.map(((e,t)=>(0,n.createElement)("option",{key:t,value:e},e)))),(0,n.createElement)(i.default,{id:"show_deleted_cookies",value:e,onChange:e=>t(e),options:{true:(0,c.__)("Show deleted cookies","complianz-gdpr")}})),(0,n.createElement)("div",{id:"cmplz-scan-progress"},(0,n.createElement)("div",{className:"cmplz-progress-bar",style:Object.assign({},{width:b+"%"})})),(0,n.createElement)("div",{className:"cmplz-panel__list"},x.map(((e,t)=>(0,n.createElement)(o.default,{key:t,addCookie:_,id:e.id,cookies:e.cookies,name:e.name,service:e.service})))),(0,n.createElement)("div",{className:"cmplz-panel__buttons"},(0,n.createElement)("button",{disabled:s,onClick:e=>{f()},className:"button button-default"},(0,c.__)("Add service","complianz-gdpr"))))}))},45927:function(e,t,a){a.r(t);var n=a(69307),l=a(68189),o=a(34573),c=a(99057),r=a(65736),i=a(23361),s=a(56293),d=a(382),m=a(32588);const u=e=>{const{getFieldValue:t,showSavedSettingsNotice:a}=(0,s.default)(),[l,o]=(0,n.useState)(""),[i,u]=(0,n.useState)(""),{language:p,saving:g,deleteService:_,serviceTypeOptions:f,updateService:v,saveService:b}=(0,c.default)();let E="yes"===t("use_cdb_api");const[h,k]=(0,n.useState)([]);(0,n.useEffect)((()=>{let e=f&&f.hasOwnProperty(p)?f[p]:[];e=e.map((e=>({label:e.label,value:e.label}))),k(e)}),[p,f]);const y=(e,t,a)=>{v(t,a,e)},z=(e,t,a)=>{v(t,a,e)};if((0,n.useEffect)((()=>{e&&e.name&&o(e.name)}),[e]),(0,n.useEffect)((()=>{if(l.length<5)return;const t=setTimeout((()=>{y(l,e.ID,"name")}),400);return()=>{clearTimeout(t)}}),[l]),(0,n.useEffect)((()=>{e&&e.privacyStatementURL&&u(e.privacyStatementURL)}),[e]),(0,n.useEffect)((()=>{if(0===i.length)return;const t=setTimeout((()=>{y(i,e.ID,"privacyStatementURL")}),400);return()=>{clearTimeout(t)}}),[i]),!e)return null;let w=!!E&&1==e.sync,N=w;g&&(N=!0);let C=!1;return e.slug.length>0&&(C="https://cookiedatabase.org/service/"+e.slug),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,n.createElement)(d.default,{id:e.ID+"thirdParty",disabled:N,value:e.thirdParty,onChange:t=>z(t,e.ID,"thirdParty"),options:{true:(0,r.__)("Data is shared with this service","complianz-gdpr")}})),(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__checkbox"},(0,n.createElement)(d.default,{id:e.ID+"sync",disabled:!E,value:w,onChange:t=>z(t,e.ID,"sync"),options:{true:(0,r.__)("Sync service with cookiedatabase.org","complianz-gdpr")}})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,r.__)("Name","complianz-gdpr")),(0,n.createElement)("input",{disabled:N,onChange:e=>o(e.target.value),type:"text",placeholder:(0,r.__)("Name","complianz-gdpr"),value:l})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,r.__)("Service Types","complianz-gdpr")),(0,n.createElement)(m.default,{disabled:N,value:e.serviceType,options:h,onChange:t=>y(t,e.ID,"serviceType")})),(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("label",null,(0,r.__)("Privacy Statement URL","complianz-gdpr")),(0,n.createElement)("input",{disabled:N,onChange:e=>u(e.target.value),type:"text",value:i})),C&&(0,n.createElement)("div",{className:"cmplz-details-row"},(0,n.createElement)("a",{href:C,target:"_blank"},(0,r.__)("View service on cookiedatabase.org","complianz-gdpr"))),(0,n.createElement)("div",{className:"cmplz-details-row cmplz-details-row__buttons"},(0,n.createElement)("button",{disabled:g,onClick:t=>(async e=>{await b(e),a((0,r.__)("Saved service","complianz-gd[r"))})(e.ID),className:"button button-default"},(0,r.__)("Save","complianz-gdpr")),(0,n.createElement)("button",{className:"button button-default cmplz-reset-button",onClick:t=>(async e=>{await _(e)})(e.ID)},(0,r.__)("Delete Service","complianz-gdpr"))))};t.default=(0,n.memo)((e=>{const{adding:t}=(0,c.default)(),a=e.service&&e.service.ID>0&&e.service.hasOwnProperty("name"),s=!e.service||e.service.ID<=0,d=e.service&&e.service.name?e.service.name:(0,r.__)("New Service","complianz-gdpr");return(0,n.createElement)(n.Fragment,null,(0,n.createElement)(o.default,{summary:e.name,icons:e.service?(0,n.createElement)(n.Fragment,null,e.service.complete&&(0,n.createElement)(i.default,{tooltip:(0,r.__)("The data for this service is complete","complianz-gdpr"),name:"success",color:"green"}),!e.service.complete&&(0,n.createElement)(i.default,{tooltip:(0,r.__)("This service has missing fields","complianz-gdpr"),name:"times",color:"red"}),e.service.synced&&(0,n.createElement)(i.default,{tooltip:(0,r.__)("This service has been synchronized with cookiedatabase.org","complianz-gdpr"),name:"rotate",color:"green"}),!e.service.synced&&(0,n.createElement)(i.default,{tooltip:(0,r.__)("This service is not synchronized with cookiedatabase.org","complianz-gdpr"),name:"rotate-error",color:"red"})):(0,n.createElement)(n.Fragment,null),details:(0,n.createElement)(n.Fragment,null,(0,n.createElement)("div",null,u(e.service)),e.cookies&&e.cookies.length>0&&(0,n.createElement)("div",{className:"cmplz-panel__cookie_list"},e.cookies.map(((e,t)=>(0,n.createElement)(l.default,{key:t,cookie:e})))),!s&&(0,n.createElement)("div",null,(0,n.createElement)("button",{disabled:t||!a,onClick:t=>((t,a)=>{e.addCookie(t,a)})(e.service.ID,d),className:"button button-default"},(0,r.__)("Add cookie to %s","complianz-gdpr").replace("%s",d),t&&(0,n.createElement)(i.default,{name:"loading",color:"grey"})),!a&&(0,n.createElement)("div",{className:"cmplz-comment"},(0,r.__)("Save service to be able to add cookies","complianz-gdpr"))))}))}))},80881:function(e,t,a){a.r(t);var n=a(69307),l=a(48399),o=a(56293),c=a(82485),r=a(55609),i=a(32175),s=a(82387);t.default=(0,n.memo)((e=>{let{type:t="action",style:a="tertiary",label:d,onClick:m,href:u="",target:p="",disabled:g,action:_,field:f,children:v}=e;if(!d&&!v)return null;const b=(f&&f.button_text?f.button_text:d)||v,{fetchFieldsData:E,showSavedSettingsNotice:h}=(0,o.default)(),{setInitialLoadCompleted:k,setProgress:y}=(0,i.UseCookieScanData)(),{setProgressLoaded:z}=(0,s.default)(),{selectedSubMenuItem:w}=(0,c.default)(),[N,C]=(0,n.useState)(!1),S=`button cmplz-button button--${a} button-${t}`,D=async e=>{await l.doAction(f.action,{}).then((e=>{e.success&&(E(w),"reset_settings"===e.id&&(k(!1),y(0),z(!1)),h(e.message))}))},I=f&&f.warn?f.warn:"";return"action"===t?(0,n.createElement)(n.Fragment,null,r.__experimentalConfirmDialog&&(0,n.createElement)(r.__experimentalConfirmDialog,{isOpen:N,onConfirm:async()=>{C(!1),await D()},onCancel:()=>{C(!1)}},I),(0,n.createElement)("button",{className:S,onClick:async e=>{if("action"!==t||!m)return"action"===t&&_?r.__experimentalConfirmDialog?void(f&&f.warn?C(!0):await D()):void await D():void(window.location.href=f.url);m(e)},disabled:g},b)):"link"===t?(0,n.createElement)("a",{className:S,href:u,target:p},b):void 0}))},382:function(e,t,a){a.r(t),a.d(t,{default:function(){return D}});var n=a(69307),l=a(87462),o=a(99196),c=a(28771),r=a(25360),i=a(36206),s=a(77342),d=a(57898),m=a(7546),u=a(29115),p=a(75320);const g="Checkbox",[_,f]=(0,r.b)(g),[v,b]=_(g),E=(0,o.forwardRef)(((e,t)=>{const{__scopeCheckbox:a,name:n,checked:r,defaultChecked:d,required:m,disabled:u,value:g="on",onCheckedChange:_,...f}=e,[b,E]=(0,o.useState)(null),z=(0,c.e)(t,(e=>E(e))),w=(0,o.useRef)(!1),N=!b||Boolean(b.closest("form")),[C=!1,S]=(0,s.T)({prop:r,defaultProp:d,onChange:_}),D=(0,o.useRef)(C);return(0,o.useEffect)((()=>{const e=null==b?void 0:b.form;if(e){const t=()=>S(D.current);return e.addEventListener("reset",t),()=>e.removeEventListener("reset",t)}}),[b,S]),(0,o.createElement)(v,{scope:a,state:C,disabled:u},(0,o.createElement)(p.WV.button,(0,l.Z)({type:"button",role:"checkbox","aria-checked":k(C)?"mixed":C,"aria-required":m,"data-state":y(C),"data-disabled":u?"":void 0,disabled:u,value:g},f,{ref:z,onKeyDown:(0,i.M)(e.onKeyDown,(e=>{"Enter"===e.key&&e.preventDefault()})),onClick:(0,i.M)(e.onClick,(e=>{S((e=>!!k(e)||!e)),N&&(w.current=e.isPropagationStopped(),w.current||e.stopPropagation())}))})),N&&(0,o.createElement)(h,{control:b,bubbles:!w.current,name:n,value:g,checked:C,required:m,disabled:u,style:{transform:"translateX(-100%)"}}))})),h=e=>{const{control:t,checked:a,bubbles:n=!0,...c}=e,r=(0,o.useRef)(null),i=(0,d.D)(a),s=(0,m.t)(t);return(0,o.useEffect)((()=>{const e=r.current,t=window.HTMLInputElement.prototype,l=Object.getOwnPropertyDescriptor(t,"checked").set;if(i!==a&&l){const t=new Event("click",{bubbles:n});e.indeterminate=k(a),l.call(e,!k(a)&&a),e.dispatchEvent(t)}}),[i,a,n]),(0,o.createElement)("input",(0,l.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:!k(a)&&a},c,{tabIndex:-1,ref:r,style:{...e.style,...s,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function k(e){return"indeterminate"===e}function y(e){return k(e)?"indeterminate":e?"checked":"unchecked"}const z=E,w=(0,o.forwardRef)(((e,t)=>{const{__scopeCheckbox:a,forceMount:n,...c}=e,r=b("CheckboxIndicator",a);return(0,o.createElement)(u.z,{present:n||k(r.state)||!0===r.state},(0,o.createElement)(p.WV.span,(0,l.Z)({"data-state":y(r.state),"data-disabled":r.disabled?"":void 0},c,{ref:t,style:{pointerEvents:"none",...e.style}})))}));var N=a(65736),C=a(23361),S=a(80881),D=(0,n.memo)((e=>{let{indeterminate:t,label:a,value:l,id:o,onChange:c,required:r,disabled:i,options:s={}}=e;const[d,m]=(0,n.useState)(!1),[u,p]=(0,n.useState)(!1);let g=l;Array.isArray(g)||(g=""===g?[]:[g]),(0,n.useEffect)((()=>{let e=1===Object.keys(s).length&&"true"===Object.keys(s)[0];m(e)}),[]),t&&(l=!0);const _=g;let f=!1;Object.keys(s).length>10&&(f=!0);const v=e=>d?l:_.includes(""+e)||_.includes(parseInt(e)),b=()=>{p(!u)};let E=i&&!Array.isArray(i);return 0===Object.keys(s).length?(0,n.createElement)(n.Fragment,null,(0,N.__)("No options found","complianz-gdpr")):(0,n.createElement)("div",{className:"cmplz-checkbox-group"},Object.entries(s).map(((e,s)=>{let[m,p]=e;return(0,n.createElement)("div",{key:m,className:"cmplz-checkbox-group__item"+(!u&&s>9?" cmplz-hidden":"")},(0,n.createElement)(z,{className:"cmplz-checkbox-group__checkbox",id:o+"_"+m,checked:v(m),"aria-label":a,disabled:E||Array.isArray(i)&&i.includes(m),required:r,onCheckedChange:e=>((e,t)=>{if(d)c(!l);else{const e=_.includes(""+t)||_.includes(parseInt(t))?_.filter((e=>e!==""+t&&e!==parseInt(t))):[..._,t];c(e)}})(0,m)},(0,n.createElement)(w,{className:"cmplz-checkbox-group__indicator"},(0,n.createElement)(C.default,{name:t?"indeterminate":"check",size:14,color:"dark-blue"}))),(0,n.createElement)("label",{className:"cmplz-checkbox-group__label",htmlFor:o+"_"+m},p))})),!u&&f&&(0,n.createElement)(S.default,{onClick:()=>b()},(0,N.__)("Show more","complianz-gdpr")),u&&f&&(0,n.createElement)(S.default,{onClick:()=>b()},(0,N.__)("Show less","complianz-gdpr")))}))},32588:function(e,t,a){a.r(t);var n=a(69307),l=a(40683),o=a(23361),c=a(65736);t.default=(0,n.memo)((e=>{let{value:t=!1,onChange:a,required:r,defaultValue:i,disabled:s,options:d={},canBeEmpty:m=!0,label:u,innerRef:p}=e;if(Array.isArray(d)){let e={};d.map((t=>{e[t.value]=t.label})),d=e}return m?(""===t||!1===t||0===t)&&(d={0:(0,c.__)("Select an option","complianz-gdpr"),...d}):t||(t=Object.keys(d)[0]),(0,n.createElement)("div",{className:"cmplz-input-group cmplz-select-group",key:u},(0,n.createElement)(l.fC,{value:t,defaultValue:i,onValueChange:a,required:r,disabled:s&&!Array.isArray(s)},(0,n.createElement)(l.xz,{className:"cmplz-select-group__trigger"},(0,n.createElement)(l.B4,null),(0,n.createElement)(o.default,{name:"chevron-down"})),(0,n.createElement)(l.VY,{className:"cmplz-select-group__content",position:"popper"},(0,n.createElement)(l.u_,{className:"cmplz-select-group__scroll-button"},(0,n.createElement)(o.default,{name:"chevron-up"})),(0,n.createElement)(l.l_,{className:"cmplz-select-group__viewport"},(0,n.createElement)(l.ZA,null,Object.entries(d).map((e=>{let[t,a]=e;return(0,n.createElement)(l.ck,{disabled:Array.isArray(s)&&s.includes(t),className:"cmplz-select-group__item",key:t,value:t},(0,n.createElement)(l.eT,null,a))})))),(0,n.createElement)(l.$G,{className:"cmplz-select-group__scroll-button"},(0,n.createElement)(o.default,{name:"chevron-down"})))))}))},34573:function(e,t,a){a.r(t);var n=a(69307),l=a(23361);t.default=e=>(0,n.createElement)("div",{className:"cmplz-panel__list__item",key:e.id,style:e.style?e.style:{}},(0,n.createElement)("details",null,(0,n.createElement)("summary",null,e.icon&&(0,n.createElement)(l.default,{name:e.icon}),(0,n.createElement)("h5",{className:"cmplz-panel__list__item__title"},e.summary),(0,n.createElement)("div",{className:"cmplz-panel__list__item__comment"},e.comment),(0,n.createElement)("div",{className:"cmplz-panel__list__item__icons"},e.icons),(0,n.createElement)(l.default,{name:"chevron-down",size:18})),(0,n.createElement)("div",{className:"cmplz-panel__list__item__details"},e.details)))},29115:function(e,t,a){a.d(t,{z:function(){return r}});var n=a(99196),l=a(91850),o=a(28771),c=a(9981);const r=e=>{const{present:t,children:a}=e,r=function(e){const[t,a]=(0,n.useState)(),o=(0,n.useRef)({}),r=(0,n.useRef)(e),s=(0,n.useRef)("none"),d=e?"mounted":"unmounted",[m,u]=function(e,t){return(0,n.useReducer)(((e,a)=>{const n=t[e][a];return null!=n?n:e}),e)}(d,{mounted:{UNMOUNT:"unmounted",ANIMATION_OUT:"unmountSuspended"},unmountSuspended:{MOUNT:"mounted",ANIMATION_END:"unmounted"},unmounted:{MOUNT:"mounted"}});return(0,n.useEffect)((()=>{const e=i(o.current);s.current="mounted"===m?e:"none"}),[m]),(0,c.b)((()=>{const t=o.current,a=r.current;if(a!==e){const n=s.current,l=i(t);e?u("MOUNT"):"none"===l||"none"===(null==t?void 0:t.display)?u("UNMOUNT"):u(a&&n!==l?"ANIMATION_OUT":"UNMOUNT"),r.current=e}}),[e,u]),(0,c.b)((()=>{if(t){const e=e=>{const a=i(o.current).includes(e.animationName);e.target===t&&a&&(0,l.flushSync)((()=>u("ANIMATION_END")))},a=e=>{e.target===t&&(s.current=i(o.current))};return t.addEventListener("animationstart",a),t.addEventListener("animationcancel",e),t.addEventListener("animationend",e),()=>{t.removeEventListener("animationstart",a),t.removeEventListener("animationcancel",e),t.removeEventListener("animationend",e)}}u("ANIMATION_END")}),[t,u]),{isPresent:["mounted","unmountSuspended"].includes(m),ref:(0,n.useCallback)((e=>{e&&(o.current=getComputedStyle(e)),a(e)}),[])}}(t),s="function"==typeof a?a({present:r.isPresent}):n.Children.only(a),d=(0,o.e)(r.ref,s.ref);return"function"==typeof a||r.isPresent?(0,n.cloneElement)(s,{ref:d}):null};function i(e){return(null==e?void 0:e.animationName)||"none"}r.displayName="Presence"}}]);