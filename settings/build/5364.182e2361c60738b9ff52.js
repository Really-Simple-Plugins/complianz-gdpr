"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[5364,5294,5671,849],{65294:(e,t,s)=>{s.r(t),s.d(t,{default:()=>r});var c=s(69307),n=s(99196);const r=(0,n.memo)((e=>{let{value:t,onChange:s,required:r,defaultValue:i,disabled:l,id:a,name:o,placeholder:d}=e;const p=a||o,[u,h]=(0,n.useState)("");return(0,n.useEffect)((()=>{h(t||"")}),[t]),(0,n.useEffect)((()=>{const e=setTimeout((()=>{s(u)}),500);return()=>{clearTimeout(e)}}),[u]),(0,c.createElement)("div",{className:"cmplz-input-group cmplz-text-input-group"},(0,c.createElement)("input",{type:"text",id:p,name:o,value:u,onChange:e=>(e=>{h(e)})(e.target.value),required:r,disabled:l,className:"cmplz-text-input-group__input",placeholder:d}))}))},85671:(e,t,s)=>{s.r(t),s.d(t,{default:()=>i});var c=s(30270),n=s(12902),r=s(48399);const i=(0,c.Ue)(((e,t)=>({integrationsLoaded:!1,fetching:!1,services:[],plugins:[],scripts:[],placeholders:[],blockedScripts:[],setScript:(t,s)=>{e((0,n.ZP)((e=>{if("block_script"===s){let s=e.blockedScripts;if(t.urls){for(const[e,c]of Object.entries(t.urls)){if(!c||0===c.length)continue;let e=!1;for(const[t,n]of Object.entries(s))c===t&&(e=!0);e||(s[c]=c)}e.blockedScripts=s}}const c=e.scripts[s].findIndex((e=>e.id===t.id));-1!==c&&(e.scripts[s][c]=t)})))},fetchIntegrationsData:async()=>{if(t().fetching)return;e({fetching:!0});const{services:s,plugins:c,scripts:n,placeholders:r,blocked_scripts:i}=await l();let a=n;a.block_script.forEach(((e,t)=>{e.id=t})),a.add_script.forEach(((e,t)=>{e.id=t})),a.whitelist_script.forEach(((e,t)=>{e.id=t})),e((()=>({integrationsLoaded:!0,services:s,plugins:c,scripts:a,fetching:!1,placeholders:r,blockedScripts:i})))},addScript:s=>{e({fetching:!0}),e((0,n.ZP)((e=>{e.scripts[s].push({name:"general",id:e.scripts[s].length,enable:!0})})));let c=t().scripts;return r.doAction("update_scripts",{scripts:c}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},saveScript:(s,c)=>{e({fetching:!0}),e((0,n.ZP)((e=>{const t=e.scripts[c].findIndex((e=>e.id===s.id));-1!==t&&(e.scripts[c][t]=s)})));let i=t().scripts;return r.doAction("update_scripts",{scripts:i}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},deleteScript:(s,c)=>{e({fetching:!0}),e((0,n.ZP)((e=>{const t=e.scripts[c].findIndex((e=>e.id===s.id));-1!==t&&e.scripts[c].splice(t,1)})));let i=t().scripts;return r.doAction("update_scripts",{scripts:i}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},updatePluginStatus:async(t,s)=>{e({fetching:!0}),e((0,n.ZP)((e=>{const c=e.plugins.findIndex((e=>e.id===t));-1!==c&&(e.plugins[c].enabled=s)})));const c=await r.doAction("update_plugin_status",{plugin:t,enabled:s}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),c},updatePlaceholderStatus:async(t,s,c)=>{e({fetching:!0}),c&&e((0,n.ZP)((e=>{const c=e.plugins.findIndex((e=>e.id===t));-1!==c&&(e.plugins[c].placeholder=s?"enabled":"disabled")})));const i=await r.doAction("update_placeholder_status",{id:t,enabled:s}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),i}}))),l=()=>r.doAction("get_integrations_data",{}).then((e=>e)).catch((e=>{console.error(e)}))},25364:(e,t,s)=>{s.r(t),s.d(t,{default:()=>o});var c=s(69307),n=s(65736),r=s(60849),i=s(65294),l=s(20384),a=s(85671);const o=e=>{const{setScript:t,fetching:s}=(0,a.default)(),o=e.script,d=e.type;let p=o.hasOwnProperty("urls")?Object.entries(o.urls):[""];return(0,c.createElement)("div",{className:"cmplz-details-row"},(0,c.createElement)("label",null,"block_script"===d&&(0,n.__)("URLs that should be blocked before consent.","complianz-gdpr"),"whitelist_script"===d&&(0,c.createElement)(c.Fragment,null,(0,n.__)("URLs that should be whitelisted.","complianz-gdpr"),(0,r.default)("https://complianz.io/whitelisting-inline-script/"))),p.map(((n,r)=>{let[a,d]=n;return(0,c.createElement)("div",{key:r,className:"cmplz-scriptcenter-url"},(0,c.createElement)(i.default,{disabled:s,value:d||"",onChange:s=>((s,c)=>{let n={...o},r={...n.urls};r[s]=c,n.urls=r,t(n,e.type)})(a,s),id:r+"_url",name:"url"}),0===r&&(0,c.createElement)("button",{className:"button button-default",onClick:()=>(()=>{let s={...o},c=Object.keys(s.urls).length,n={...s.urls};n[c+1]="",s.urls=n,t(s,e.type)})()}," ",(0,c.createElement)(l.default,{name:"plus",size:14})),0!==r&&(0,c.createElement)("button",{className:"button button-default",onClick:()=>(s=>{let c={...o},n={...c.urls};delete n[s],c.urls=n,t(c,e.type)})(a)}," ",(0,c.createElement)(l.default,{name:"minus",size:14})))})))}},60849:(e,t,s)=>{s.r(t),s.d(t,{default:()=>i});var c=s(69307),n=s(65736),r=s(99950);const i=e=>(0,c.createElement)(c.Fragment,null," ",(0,c.createElement)(r.default,{url:e,text:(0,n.__)("For more information, please read this %sarticle%s.","complianz-gdpr")})," ")}}]);