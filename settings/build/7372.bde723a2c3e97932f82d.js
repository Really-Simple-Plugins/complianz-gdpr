"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[7372,4550,9810],{24550:function(e,t,a){a.r(t);var l=a(69307),n=a(23361),i=a(65736),s=a(89810);t.default=e=>{let{plugin:t,processing:a}=e;const{pluginAction:c}=(0,s.default)();let o="grey",r=a||t.processing?"loading":"info";return"activated"===t.status&&(o="green",r="circle-check"),(0,l.createElement)("div",{className:"cmplz-onboarding-item"},(0,l.createElement)(n.default,{name:r,color:o,size:14}),t.description," ","not-installed"===t.status&&(0,l.createElement)("a",{href:"#",onClick:e=>(async e=>{await c(e,"install_plugin"),await c(e,"activate_plugin")})(t.slug)},!t.processing&&(0,i.__)("Install","complianz-gdpr"),t.processing&&(0,i.__)("Installing...","complianz-gdpr")),"installed"===t.status&&(0,l.createElement)("a",{href:"#",onClick:e=>(async e=>{await c(e,"activate_plugin")})(t.slug)},!t.processing&&(0,i.__)("Activate","complianz-gdpr"),t.processing&&(0,i.__)("Activating...","complianz-gdpr")),"activated"===t.status&&(0,i.__)("Installed!","complianz-gdpr"))}},77372:function(e,t,a){a.r(t);var l=a(69307),n=a(65736),i=a(24550),s=a(89810),c=a(23361),o=a(56293);t.default=()=>{const{email:e,setEmail:t,setIncludeTips:a,includeTips:r,sendTestEmail:m,saveEmail:p,setSendTestEmail:d,plugins:u,loaded:g,isUpgrade:_,processing:z,dismissModal:f,modalVisible:h,getRecommendedPluginsStatus:E}=(0,s.default)(),[v,b]=(0,l.useState)(0),{updateField:y}=(0,o.default)(),[w,k]=(0,l.useState)(!0),[C,T]=(0,l.useState)(!0),N=["plugins","email"],S=e=>0===e.length||/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);if((0,l.useEffect)((()=>{g||E()}),[g]),(0,l.useEffect)((()=>{"plugins"===N[v]&&(T(!0),w||T(!1)),"email"===N[v]&&(T(!0),S(e)&&T(!1))}),[e,v,w]),(0,l.useEffect)((()=>{const e=setInterval((()=>{k(!1)}),2e3);return()=>clearInterval(e)}),[]),!h)return null;let I=S(e)?"cmplz-valid":"cmplz-invalid",A="email"===N[v]&&z?"cmplz-processing":"";return(0,l.createElement)(l.Fragment,null,(0,l.createElement)("div",{className:"cmplz-modal-backdrop"}," "),(0,l.createElement)("div",{className:"cmplz-modal cmplz-onboarding"},(0,l.createElement)("div",{className:"cmplz-modal-header"},(0,l.createElement)("div",{className:"cmplz-modal-header-branding"},(0,l.createElement)("img",{className:"cmplz-header-logo",src:cmplz_settings.plugin_url+"assets/images/cmplz-logo.svg",alt:"Complianz logo"}),(0,l.createElement)("button",{type:"button",className:"cmplz-modal-close","data-dismiss":"modal","aria-label":"Close",onClick:()=>f()},(0,l.createElement)("svg",{"aria-hidden":"true",focusable:"false",role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 320 512",height:"24"},(0,l.createElement)("path",{fill:"#000000",d:"M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"})))),"plugins"===N[v]&&(0,l.createElement)("p",null,(0,n.__)("Take a quick tour to familiarize yourself with Complianz, or discover on your own pace. If you have any questions, let us know, but for now: ","complianz-gdpr")," ",(0,l.createElement)("a",{href:"https://complianz.io/meet-complianz-7/ref/76/?campaign=onboarding-zero",target:"_blank"},(0,n.__)("Meet Complianz 7.0","complianz-gdpr"))),"email"===N[v]&&(0,l.createElement)("p",null,(0,n.__)("We use email notifications to explain important updates in your plugin settings. Add your email address below.","complianz-gdpr"))),(0,l.createElement)("div",{className:"cmplz-modal-content "+A},"plugins"===N[v]&&(0,l.createElement)(l.Fragment,null,u.map(((e,t)=>(0,l.createElement)(i.default,{key:t,plugin:e,processing:z}))),(0,l.createElement)("div",{className:"cmplz-onboarding-item"},(0,l.createElement)(c.default,{name:w?"loading":"circle-check",color:w?"grey":"green",size:14}),(w||!g)&&(0,n.__)("Upgrading","complianz-gdpr"),!w&&g&&(0,l.createElement)(l.Fragment,null,_&&(0,n.__)("Thanks for updating!","complianz-gdpr"),!_&&(0,n.__)("Thanks for installing!","complianz-gdpr")))),"email"===N[v]&&(0,l.createElement)(l.Fragment,null,(0,l.createElement)("div",null,(0,l.createElement)("input",{type:"email",className:I,value:e,placeholder:(0,n.__)("Your email address","complianz-gdpr"),onChange:e=>t(e.target.value)})),(0,l.createElement)("div",null,(0,l.createElement)("label",null,(0,l.createElement)("input",{onChange:e=>a(e.target.checked),type:"checkbox",checked:r}),(0,n.__)("Include 8 Tips & Tricks to get started with Complianz GDPR.","complianz-gdpr")," ",(0,l.createElement)("a",{href:"https://complianz.io/legal/privacy-statement/",target:"_blank"},(0,n.__)("Privacy Statement","complianz-gdpr")))),(0,l.createElement)("div",null,(0,l.createElement)("label",null,(0,l.createElement)("input",{onChange:e=>d(e.target.checked),type:"checkbox",checked:m}),(0,n.__)("Send a notification test email - Notification emails are sent from your server.","complianz-gdpr"))))),(0,l.createElement)("div",{className:"cmplz-modal-footer"},v>0&&(0,l.createElement)("a",{href:"#",onClick:e=>b(v-1)},(0,n.__)("Previous","complianz-gdpr")),(0,l.createElement)("button",{type:"button",className:"button button-default",onClick:()=>f()},(0,n.__)("Dismiss","complianz-gdpr")),v<N.length-1&&(0,l.createElement)("button",{disabled:C,className:"button button-primary",onClick:e=>b(v+1)},(0,n.__)("Next","complianz-gdpr")),v===N.length-1&&(0,l.createElement)("a",{disabled:C,href:"#",onClick:t=>(async t=>{t.preventDefault(),await p(),S(e)&&e.length>0&&(y("notifications_email_address",e),y("send_notifications_email",!0)),f(),window.location.hash="#wizard"})(t),className:"button button-primary"},(0,n.__)("Start wizard","complianz-gdpr")),v===N.length-1&&(0,l.createElement)("a",{href:"#",onClick:e=>(e=>{e.preventDefault(),window.location.href=window.location.href.replace("onboarding","tour")})(e)},(0,n.__)("Take a tour","complianz-gdpr")))))}},89810:function(e,t,a){a.r(t);var l=a(30270),n=a(12902),i=a(65736),s=a(48399);const c=(0,l.Ue)(((e,t)=>({loaded:!1,plugins:[{slug:"complianz-terms-conditions",description:(0,i.__)("Need Terms & Conditions? Configure now.","complianz-gdpr"),status:"not-installed",processing:!1},{slug:"burst-statistics",premium:"burst-pro",description:(0,i.__)("Privacy-Friendly Analytics? Here you go!","complianz-gdpr"),status:"not-installed",processing:!1},{slug:"really-simple-ssl",description:(0,i.__)("Really Simple Security? Install now!","complianz-gdpr"),status:"not-installed",processing:!1}],isUpgrade:!1,processing:!0,email:"",includeTips:!1,sendTestEmail:!0,actionStatus:"",modalVisible:!0,setIncludeTips:t=>{e((e=>({includeTips:t})))},setSendTestEmail:t=>{e((e=>({sendTestEmail:t})))},setEmail:t=>{e((e=>({email:t})))},dismissModal:()=>{const t=new URL(window.location.href);t.searchParams.delete("onboarding"),window.history.pushState({},"",t.href),e((e=>({modalVisible:!1})))},saveEmail:async()=>{let a={};a.email=t().email,a.includeTips=t().includeTips,a.sendTestEmail=t().sendTestEmail,e((e=>({processing:!0}))),await s.doAction("update_email",a).then((e=>e)),e((()=>({processing:!1})))},getRecommendedPluginsStatus:async()=>{const a={};a.plugins=t().plugins;const{plugins:l,isUpgrade:n}=await s.doAction("get_recommended_plugins_status",a).then((async e=>e));e({processing:!1,plugins:l,isUpgrade:n,loaded:!0})},setProcessing:(t,a)=>{e((0,n.Uy)((e=>{const l=e.plugins.findIndex((e=>e.slug===t));-1!==l&&(e.plugins[l].processing=a)})))},pluginAction:async(a,l)=>{const n={};n.slug=a,n.plugins=t().plugins,t().setProcessing(a,!0);const{plugins:i}=await s.doAction(l,n).then((async e=>e));e({plugins:i})}})));t.default=c}}]);