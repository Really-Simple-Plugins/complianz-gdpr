"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[5175,2010,1629,6946],{52010:(e,t,a)=>{a.r(t),a.d(t,{default:()=>c});var l=a(51609),n=a(45111),r=a(86087);const c=e=>{const[t,a]=(0,r.useState)(!1);return(0,l.createElement)("div",{className:"cmplz-panel__list__item",style:e.style?e.style:{}},(0,l.createElement)("details",{open:t},(0,l.createElement)("summary",{onClick:e=>(e=>{e.preventDefault(),a(!t)})(e)},e.icon&&(0,l.createElement)(n.default,{name:e.icon}),(0,l.createElement)("h5",{className:"cmplz-panel__list__item__title"},e.summary),(0,l.createElement)("div",{className:"cmplz-panel__list__item__comment"},e.comment),(0,l.createElement)("div",{className:"cmplz-panel__list__item__icons"},e.icons),(0,l.createElement)(n.default,{name:"chevron-down",size:18})),(0,l.createElement)("div",{className:"cmplz-panel__list__item__details"},t&&e.details)))}},81629:(e,t,a)=>{a.r(t),a.d(t,{default:()=>s});var l=a(81621),n=a(16535),r=a(9588),c=a(73710);const s=(0,l.vt)(((e,t)=>({documentsLoaded:!1,region:"",fileName:"",serviceName:"",fetching:!1,updating:!1,loadingFields:!1,documents:[],regions:[],fields:[],editDocumentId:!1,resetEditDocumentId:t=>{e({editDocumentId:!1,region:"",serviceName:""})},editDocument:async t=>{e({updating:!0}),await r.doAction("load_processing_agreement",{id:t}).then((t=>{e({fields:t.fields,region:t.region,serviceName:t.serviceName,updating:!1,fileName:t.file_name})})).catch((e=>{console.error(e)})),e({editDocumentId:t})},setRegion:t=>{e({region:t})},setServiceName:t=>{e({serviceName:t})},updateField:(a,l)=>{let r=!1,s=!1;e((0,n.Ay)((e=>{e.fields.forEach((function(e,t){e.id===a&&(s=t,r=!0)})),!1!==s&&(e.fields[s].value=l)})));let i=(0,c.updateFieldsListWithConditions)(t().fields);e({fields:i})},save:async(a,l)=>{e({updating:!0});let n=t().editDocumentId;await r.doAction("save_processing_agreement",{fields:t().fields,region:a,serviceName:l,post_id:n}).then((t=>(e({updating:!1}),t))).catch((e=>{console.error(e)})),t().fetchData()},deleteDocuments:async a=>{let l=t().documents.filter((e=>a.includes(e.id)));e((e=>({documents:e.documents.filter((e=>!a.includes(e.id)))})));let n={};n.documents=l,await r.doAction("delete_processing_agreement",n).then((e=>e)).catch((e=>{console.error(e)}))},fetchData:async()=>{if(t().fetching)return;e({fetching:!0});const{documents:a,regions:l}=await r.doAction("get_processing_agreements",{}).then((e=>e)).catch((e=>{console.error(e)}));e((()=>({documentsLoaded:!0,documents:a,regions:l,fetching:!1})))},fetchFields:async t=>{let a={region:t};e({loadingFields:!0});const{fields:l}=await r.doAction("get_processing_agreement_fields",a).then((e=>e)).catch((e=>{console.error(e)}));let n=(0,c.updateFieldsListWithConditions)(l);e((e=>({fields:n,loadingFields:!1})))}})))},45175:(e,t,a)=>{a.r(t),a.d(t,{default:()=>o});var l=a(51609),n=a(27723),r=a(46946),c=a(4219),s=a(86087),i=a(81629);const o=(0,s.memo)((e=>{const{updateField:t,setChangedField:a}=(0,c.default)(),{documentsLoaded:o,fetchData:d}=(0,i.default)();(0,s.useEffect)((()=>{o||d()}),[]);let m=e.field,u=m.value;return Array.isArray(u)||(u=[]),(0,l.createElement)("div",{className:"components-base-control cmplz-processor"},(0,l.createElement)("div",null,(0,l.createElement)("button",{onClick:()=>(()=>{let l=e.field.value;Array.isArray(l)||(l=[]);let r={},c=[...l];r.name=(0,n.__)("New processor","complianz-gdpr"),c.push(r),t(m.id,c),a(m.id,c)})(),className:"button button-default"},(0,n.__)("Add new Processors & Service Providers","complianz-gdpr"))),(0,l.createElement)("div",{className:"cmplz-panel__list"},u.map(((t,a)=>(0,l.createElement)(r.default,{field:e.field,key:a,index:a,processor:t})))))}))},46946:(e,t,a)=>{a.r(t),a.d(t,{default:()=>m});var l=a(51609),n=a(27723),r=a(45111),c=a(52010),s=a(4219),i=a(86087),o=a(81629),d=a(52043);const m=(0,i.memo)((e=>{const{updateField:t,setChangedField:a,saveFields:m}=(0,s.default)(),{documentsLoaded:u,documents:p}=(0,o.default)(),{selectedMainMenuItem:g}=(0,d.default)(),[_,f]=wp.element.useState(e.processor.name?e.processor.name:""),[v,E]=wp.element.useState(e.processor.purpose?e.processor.purpose:""),[h,y]=wp.element.useState(e.processor.country?e.processor.country:""),[z,N]=wp.element.useState(e.processor.data?e.processor.data:""),w=(l,n)=>{let r=[...e.field.value];Array.isArray(r)||(r=[]);let c={...r[e.index]};c[n]=l,r[e.index]=c,t(e.field.id,r),a(e.field.id,r)};(0,i.useEffect)((()=>{const e=setTimeout((()=>{w(_,"name")}),500);return()=>{clearTimeout(e)}}),[_]),(0,i.useEffect)((()=>{const e=setTimeout((()=>{w(z,"data")}),500);return()=>{clearTimeout(e)}}),[z]),(0,i.useEffect)((()=>{const e=setTimeout((()=>{w(h,"country")}),500);return()=>{clearTimeout(e)}}),[h]),(0,i.useEffect)((()=>{const e=setTimeout((()=>{w(v,"purpose")}),500);return()=>{clearTimeout(e)}}),[v]);let A=u?[...p]:[];A.push({id:-1,title:(0,n.__)("A Processing Agreement outside Complianz Privacy Suite","complianz-gdpr"),region:"",service:"",date:""});let C={...e.processor};return C.processing_agreement||(C.processing_agreement=0),(0,l.createElement)(l.Fragment,null,(0,l.createElement)(c.default,{summary:_,details:(c=>(0,l.createElement)(l.Fragment,null,(0,l.createElement)("div",{className:"cmplz-details-row"},(0,l.createElement)("label",null,(0,n.__)("Name","complianz-gdpr")),(0,l.createElement)("input",{onChange:e=>f(e.target.value),type:"text",placeholder:(0,n.__)("Name","complianz-gdpr"),value:_})),(0,l.createElement)("div",{className:"cmplz-details-row"},(0,l.createElement)("label",null,(0,n.__)("Country","complianz-gdpr")),(0,l.createElement)("input",{onChange:e=>y(e.target.value),type:"text",placeholder:(0,n.__)("Country","complianz-gdpr"),value:h})),(0,l.createElement)("div",{className:"cmplz-details-row"},(0,l.createElement)("label",null,(0,n.__)("Purpose","complianz-gdpr")),(0,l.createElement)("input",{onChange:e=>E(e.target.value),type:"text",placeholder:(0,n.__)("Purpose","complianz-gdpr"),value:v})),(0,l.createElement)("div",{className:"cmplz-details-row"},(0,l.createElement)("label",null,(0,n.__)("Data","complianz-gdpr")),(0,l.createElement)("input",{onChange:e=>N(e.target.value),type:"text",placeholder:(0,n.__)("Data","complianz-gdpr"),value:z})),(0,l.createElement)("div",{className:"cmplz-details-row"},(0,l.createElement)("label",null,(0,n.__)("Processing Agreement","complianz-gdpr")),u&&(0,l.createElement)("select",{onChange:e=>w(e.target.value,"processing_agreement"),value:c.processing_agreement},(0,l.createElement)("option",{value:"0"},(0,n.__)("Select an option","complianz-gdpr")),A.map(((e,t)=>(0,l.createElement)("option",{key:t,value:e.id},e.title)))),!u&&(0,l.createElement)("div",{className:"cmplz-documents-loader"},(0,l.createElement)("div",null,(0,l.createElement)(r.default,{name:"loading",color:"grey"})),(0,l.createElement)("div",null,(0,n.__)("Loading...","complianz-gdpr")))),(0,l.createElement)("div",{className:"cmplz-details-row__buttons"},(0,l.createElement)("button",{className:"button button-default cmplz-reset-button",onClick:l=>(async l=>{let n=e.field.value;Array.isArray(n)||(n=[]);let r=[...n];r.hasOwnProperty(e.index)&&r.splice(e.index,1),t(e.field.id,r),a(e.field.id,r),await m(g,!1,!1)})()},(0,n.__)("Delete","complianz-gdpr")))))(C)}))}))}}]);