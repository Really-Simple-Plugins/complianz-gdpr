"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[7980,7040,6527,2588],{20382:(e,t,n)=>{n.r(t),n.d(t,{default:()=>l});var a=n(30270),o=n(48399);const l=(0,a.Ue)(((e,t)=>({documents:[],documentDataLoaded:!1,processingAgreementOptions:[],proofOfConsentOptions:[],dataBreachOptions:[],region:"",setRegion:t=>{"undefined"!=typeof Storage&&(sessionStorage.cmplzSelectedRegion=t),e((e=>({region:t})))},getRegion:()=>{let t="all";"undefined"!=typeof Storage&&sessionStorage.cmplzSelectedRegion&&(t=sessionStorage.cmplzSelectedRegion),e((e=>({region:t})))},getDocuments:async()=>{const{documents:t,processingAgreementOptions:n,proofOfConsentOptions:a,dataBreachOptions:l}=await o.doAction("documents_block_data").then((e=>e));e((e=>({documentDataLoaded:!0,documents:t,processingAgreementOptions:n,proofOfConsentOptions:a,dataBreachOptions:l})))}})))},57980:(e,t,n)=>{n.r(t),n.d(t,{default:()=>c});var a=n(69307),o=n(65736),l=n(20382),r=n(56293),s=n(26527);const c=()=>{const{getFieldValue:e,fields:t}=(0,r.default)(),[n,c]=(0,a.useState)(!1);(0,a.useEffect)((()=>{c(e("records_of_consent"))}),[t]);const{processingAgreementOptions:i,dataBreachOptions:p,proofOfConsentOptions:m}=(0,l.default)();return(0,a.createElement)(a.Fragment,null,(0,a.createElement)("h3",{className:"cmplz-h4"},(0,o.__)("Other documents")),(0,a.createElement)(s.default,{type:"processing-agreements",link:"#tools/processing-agreements",name:(0,o.__)("Processing Agreement","complianz-gdpr"),options:i}),(0,a.createElement)(s.default,{type:"data-breaches",link:"#tools/data-breach-reports",name:(0,o.__)("Data Breach","complianz-gdpr"),options:p}),(0,a.createElement)(s.default,{type:"proof-of-consent",link:n?"#tools/records-of-consent":"#tools/proof-of-consent",name:(0,o.__)("Proof of Consent","complianz-gdpr"),options:m}))}},26527:(e,t,n)=>{n.r(t),n.d(t,{default:()=>s});var a=n(69307),o=n(20384),l=n(65736),r=n(32588);const s=e=>{const[t,n]=(0,a.useState)(!1),[s,c]=(0,a.useState)(!1),[i,p]=(0,a.useState)(!1);(0,a.useEffect)((()=>{let t=e.options;if(0===t.length){let n={label:(0,l.__)("Generate a %s","complianz-gdpr").replace("%s",e.name),value:0};t.unshift(n)}else if(!t.filter((e=>0===e.value)).length>0){let n={label:(0,l.__)("Select a %s","complianz-gdpr").replace("%s",e.name),value:0};t.unshift(n)}p(t)}),[e.options]);const m=()=>{if(s||!t||0===t)return;c(!0);let e=new XMLHttpRequest;e.responseType="blob",e.open("get",t,!0),e.send(),e.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=window.URL.createObjectURL(this.response),n=window.document.createElement("a");n.setAttribute("href",e),n.setAttribute("download",i.filter((e=>e.value===t))[0].label),window.document.body.appendChild(n),n.click(),setTimeout((function(){window.URL.revokeObjectURL(e)}),6e4)}},e.onprogress=function(e){c(!0)}};return(0,a.createElement)("div",{className:"cmplz-single-document-other-documents"},(0,a.createElement)(r.default,{onChange:e=>n(e),defaultValue:"0",canBeEmpty:!1,value:t,options:i}),(0,a.createElement)("div",{onClick:()=>m()},(0,a.createElement)(o.default,{name:"file-download",color:0==t||s?"grey":"black",tooltip:(0,l.__)("Download file","complianz-gdpr"),size:14})),i.length>0&&(0,a.createElement)("a",{href:e.link},(0,a.createElement)(o.default,{name:"circle-chevron-right",color:"black",tooltip:(0,l.__)("Go to overview","complianz-gdpr"),size:14})),0===i.length&&(0,a.createElement)("a",{href:e.link},(0,a.createElement)(o.default,{name:"plus",color:"black",tooltip:(0,l.__)("Create new","complianz-gdpr"),size:14})))}},32588:(e,t,n)=>{n.r(t),n.d(t,{default:()=>c});var a=n(69307),o=n(99196),l=n(79552),r=n(20384),s=n(65736);const c=(0,o.memo)((e=>{let{value:t=!1,onChange:n,required:o,defaultValue:c,disabled:i,options:p={},canBeEmpty:m=!0,label:d,innerRef:u}=e;if(Array.isArray(p)){let e={};p.map((t=>{e[t.value]=t.label})),p=e}return m?p={0:(0,s.__)("Select an option","complianz-gdpr"),...p}:t||(t=Object.keys(p)[0]),(0,a.createElement)("div",{className:"cmplz-input-group cmplz-select-group",key:d},(0,a.createElement)(l.fC,{value:t,defaultValue:c,onValueChange:n,required:o,disabled:i&&!Array.isArray(i)},(0,a.createElement)(l.xz,{className:"cmplz-select-group__trigger"},(0,a.createElement)(l.B4,null),(0,a.createElement)(r.default,{name:"chevron-down"})),(0,a.createElement)(l.VY,{className:"cmplz-select-group__content",position:"popper"},(0,a.createElement)(l.u_,{className:"cmplz-select-group__scroll-button"},(0,a.createElement)(r.default,{name:"chevron-up"})),(0,a.createElement)(l.l_,{className:"cmplz-select-group__viewport"},(0,a.createElement)(l.ZA,null,Object.entries(p).map((e=>{let[t,n]=e;return(0,a.createElement)(l.ck,{disabled:Array.isArray(i)&&i.includes(t),className:"cmplz-select-group__item",key:t,value:t},(0,a.createElement)(l.eT,null,n))})))),(0,a.createElement)(l.$G,{className:"cmplz-select-group__scroll-button"},(0,a.createElement)(r.default,{name:"chevron-down"})))))}))}}]);