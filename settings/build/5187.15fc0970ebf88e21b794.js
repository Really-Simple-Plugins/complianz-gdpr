"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[5187],{85187:function(e,n,t){t.r(n);var o=t(69307),a=t(55609),l=t(65736),r=t(48399);n.default=(0,o.memo)((()=>{const[e,n]=(0,o.useState)(""),[t,s]=(0,o.useState)(!1);let c=t||0===e.length;return(0,o.createElement)(o.Fragment,null,(0,o.createElement)(a.TextareaControl,{disabled:t,placeholder:(0,l.__)("Type your question here","complianz-gdpr"),onChange:e=>(e=>{n(e)})(e)}),(0,o.createElement)("div",null,(0,o.createElement)("button",{className:"button button-primary",disabled:c,variant:"secondary",onClick:n=>(s(!0),r.doAction("supportData",{}).then((n=>{let t=e.replace(/(?:\r\n|\r|\n)/g,"--br--"),o="https://complianz.io/support?user="+encodeURIComponent(n.customer_name)+"&email="+n.email+"&website="+n.domain+"&license="+encodeURIComponent(n.license_key)+"&question="+encodeURIComponent(t)+"&details="+encodeURIComponent(n.system_status);window.location.assign(o)})))},(0,l.__)("Send","complianz-gdpr"))))}))}}]);