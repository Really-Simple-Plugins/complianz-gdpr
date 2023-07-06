"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[1499,2175,2089,4573],{41499:(e,a,t)=>{t.r(a),t.d(a,{default:()=>_});var n=t(69307),o=t(48399),l=t(32175),s=t(65736),c=t(34573),r=t(20384),i=t(99057),d=t(82485),m=t(56293),p=t(92089);const _=(0,t(99196).memo)((()=>{const{loadingSyncData:e,syncProgress:a,setSyncProgress:t,fetchSyncProgressData:_}=(0,i.UseSyncData)(),{initialLoadCompleted:u,loading:g,nextPage:f,progress:b,setProgress:k,cookies:z,fetchProgress:h,hasSyncableData:y,lastLoadedIframe:v,setLastLoadedIframe:E}=(0,l.UseCookieScanData)(),[w,C]=(0,n.useState)(!1),{addHelpNotice:N,fieldsLoaded:P}=(0,m.default)(),{selectedSubMenuItem:L}=(0,d.default)();(0,n.useEffect)((()=>{v!==f&&(w||(C(!0),D()))}),[f,v,w]),(0,n.useEffect)((()=>{!w&&!g&&b<100&&h()}),[w,g,b]),(0,n.useEffect)((()=>{P&&(void 0===window.canRunAds&&N("cookie_scan","warning",(0,s.__)("You are using an ad blocker. This will prevent most cookies from being placed. Please run the scan without an adblocker enabled.","complianz-gdpr"),(0,s.__)("Ad Blocker detected.","complianz-gdpr"),null),S()&&N("cookie_scan","warning",(0,s.__)("Your browser has the Do Not Track or Global Privacy Control setting enabled.","complianz-gdpr")+"&nbsp;"+(0,s.__)("This will prevent most cookies from being placed.","complianz-gdpr")+"&nbsp;"+(0,s.__)("Please run the scan with these browser options disabled.","complianz-gdpr"),(0,s.__)("DNT or GPC enabled.","complianz-gdpr"),null))}),[P]);const S=()=>{let e="doNotTrack"in navigator&&"1"===navigator.doNotTrack;return"globalPrivacyControl"in navigator&&navigator.globalPrivacyControl||e},D=()=>{if(!f)return void C(!1);let e=document.getElementById("cmplz_cookie_scan_frame");e||(e=document.createElement("iframe"),e.setAttribute("id","cmplz_cookie_scan_frame"),e.classList.add("hidden")),e.setAttribute("src",f),e.onload=function(e){setTimeout((()=>{C(!1),E(f)}),200)},document.body.appendChild(e)};if("cookie-scan"!==L)return null;let T=z?z.length:0,I="";I=0===T?(0,s.__)("No cookies found on your domain yet.","complianz-gdpr"):1===T?(0,s.__)("The scan found 1 cookie on your domain.","complianz-gdpr"):(0,s.__)("The scan found %s cookies on your domain.","complianz-gdpr").replace("%s",T),b>=100?T>0&&(I+=" "+(0,s.__)("Continue the wizard to categorize cookies and configure consent.","complianz-gdpr")):I+=" "+(0,s.__)("Scanning, %s complete.","complianz-gdpr").replace("%s",Math.round(b)+"%"),u||(I=(0,n.createElement)(r.default,{name:"loading",color:"grey"}));let A=b<100&&b>0;return(0,n.createElement)(n.Fragment,null,(0,n.createElement)("div",{className:"cmplz-table-header"},(0,n.createElement)("button",{disabled:A,className:"button button-default",onClick:e=>(async()=>{k(1),await o.doAction("scan",{scan_action:"restart"}),await h(),100===b&&(await _(),y&&t(1))})()},(0,s.__)("Scan","complianz-gdpr")),(0,n.createElement)("button",{disabled:A,className:"button button-default cmplz-reset-button",onClick:e=>(async()=>{k(1),await o.doAction("scan",{scan_action:"reset"}),await h(),100===b&&(await _(),y&&t(1))})()},(0,s.__)("Clear Cookies","complianz-gdpr"))),(0,n.createElement)("div",{id:"cmplz-scan-progress"},(0,n.createElement)("div",{className:"cmplz-progress-bar",style:Object.assign({},{width:b+"%"})})),(0,n.createElement)("div",null,(0,n.createElement)("div",{className:"cmplz-panel__list"},(0,n.createElement)(c.default,{summary:I,details:(0,p.Details)(u,z)}))))}))},32175:(e,a,t)=>{t.r(a),t.d(a,{UseCookieScanData:()=>l});var n=t(30270),o=t(48399);const l=(0,n.Ue)(((e,a)=>({initialLoadCompleted:!1,iframeLoaded:!1,loading:!1,nextPage:!1,progress:0,cookies:[],lastLoadedIframe:"",setIframeLoaded:a=>e({iframeLoaded:a}),setLastLoadedIframe:a=>e((e=>({lastLoadedIframe:a}))),setProgress:a=>e({progress:a}),fetchProgress:()=>(e({loading:!0}),o.doAction("get_scan_progress",{}).then((a=>(e({initialLoadCompleted:!0,loading:!1,nextPage:a.next_page,progress:a.progress,cookies:a.cookies}),a))))})))},92089:(e,a,t)=>{t.r(a),t.d(a,{Details:()=>o});var n=t(69307);const o=(e,a)=>(0,n.createElement)(n.Fragment,null,e&&a.map(((e,a)=>(0,n.createElement)("div",{key:a},e))))},34573:(e,a,t)=>{t.r(a),t.d(a,{default:()=>l});var n=t(69307),o=t(20384);const l=e=>(0,n.createElement)("div",{className:"cmplz-panel__list__item",key:e.id,style:e.style?e.style:{}},(0,n.createElement)("details",null,(0,n.createElement)("summary",null,e.icon&&(0,n.createElement)(o.default,{name:e.icon}),(0,n.createElement)("h5",{className:"cmplz-panel__list__item__title"},e.summary),(0,n.createElement)("div",{className:"cmplz-panel__list__item__comment"},e.comment),(0,n.createElement)("div",{className:"cmplz-panel__list__item__icons"},e.icons),(0,n.createElement)(o.default,{name:"chevron-down",size:18})),(0,n.createElement)("div",{className:"cmplz-panel__list__item__details"},e.details)))}}]);