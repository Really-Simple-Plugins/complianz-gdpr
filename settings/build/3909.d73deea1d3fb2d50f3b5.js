"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[3909,4064],{53909:function(e,t,a){a.r(t);var c=a(69307),s=a(65736),l=a(23361),n=a(9818),i=a(48399),r=a(14064),m=a(56293),o=a(82387),d=a(82485);t.default=e=>{let{notice:t,index:a}=e;const{dismissNotice:u,fetchProgressData:p}=(0,o.default)(),{getField:_,setHighLightField:h,fetchFieldsData:f}=(0,m.default)(),{setSelectedSubMenuItem:g}=(0,d.default)();let b="premium"===t.icon,k=t.url&&-1!==t.url.indexOf("complianz.io"),z=t.status.charAt(0).toUpperCase()+t.status.slice(1);return(0,c.createElement)("div",{key:a,className:"cmplz-task-element"},(0,c.createElement)("span",{className:"cmplz-task-status cmplz-"+t.status},z),(0,c.createElement)("p",{className:"cmplz-task-message",dangerouslySetInnerHTML:{__html:t.message}}),k&&t.url&&(0,c.createElement)("a",{target:"_blank",href:t.url},(0,s.__)("More info","complianz-gdpr")),t.clear_cache_id&&(0,c.createElement)("span",{className:"cmplz-task-enable button button-secondary",onClick:()=>(async e=>{let t={};t.cache_id=e,i.doAction("clear_cache",t).then((async e=>{(0,n.dispatch)("core/notices").createNotice("success",(0,s.__)("Re-started test","complianz-gdpr"),{__unstableHTML:!0,id:"cmplz_clear_cache",type:"snackbar",isDismissible:!0}).then((0,r.default)(3e3)).then((e=>{(0,n.dispatch)("core/notices").removeNotice("rsssl_clear_cache")})),await f(),await p()}))})(t.clear_cache_id)},(0,s.__)("Re-check","complianz-gdpr")),!b&&!k&&t.url&&(0,c.createElement)("a",{className:"cmplz-task-enable button button-secondary",href:t.url},(0,s.__)("View","complianz-gdpr")),!b&&t.highlight_field_id&&(0,c.createElement)("span",{className:"cmplz-task-enable button button-secondary",onClick:()=>(async()=>{h(t.highlight_field_id);let e=_(t.highlight_field_id);await g(e.menu_id)})()},(0,s.__)("View","complianz-gdpr")),t.plus_one&&(0,c.createElement)("span",{className:"cmplz-plusone"},"1"),t.dismissible&&"completed"!==t.status&&(0,c.createElement)("div",{className:"cmplz-task-dismiss"},(0,c.createElement)("button",{type:"button",onClick:e=>u(t.id)},(0,c.createElement)(l.default,{name:"times"}))))}},14064:function(e,t,a){a.r(t),t.default=e=>function(t){return new Promise((a=>setTimeout((()=>a(t)),e)))}}}]);