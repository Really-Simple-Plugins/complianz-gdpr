"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[1655],{71655:(e,t,c)=>{c.r(t),c.d(t,{default:()=>n});var r=c(69307),l=c(20384);const n=(0,c(99196).memo)((e=>{let{conclusion:t,delay:c}=e;const[n,a]=(0,r.useState)(!0);(0,r.useEffect)((()=>{setTimeout((()=>{o()}),c)}));const o=()=>{a(!1)};let s="green";return"warning"===t.report_status&&(s="orange"),"error"===t.report_status&&(s="red"),(0,r.createElement)(r.Fragment,null,n&&(0,r.createElement)("li",{className:"cmplz-conclusion__check icon-loading"},(0,r.createElement)(l.default,{name:"loading",color:"grey"}),(0,r.createElement)("div",{className:"cmplz-conclusion__check--report-text"}," ",t.check_text," ")),!n&&(0,r.createElement)("li",{className:"cmplz-conclusion__check icon-"+t.report_status},(0,r.createElement)(l.default,{name:t.report_status,color:s}),(0,r.createElement)("div",{className:"cmplz-conclusion__check--report-text",dangerouslySetInnerHTML:{__html:t.report_text}})))}))}}]);