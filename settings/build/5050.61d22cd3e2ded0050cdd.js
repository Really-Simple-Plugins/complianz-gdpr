"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[5050,5671,4573],{85671:function(e,t,s){s.r(t);var i=s(30270),c=s(12902),n=s(48399);const r=(0,i.Ue)(((e,t)=>({integrationsLoaded:!1,fetching:!1,services:[],plugins:[],scripts:[],placeholders:[],blockedScripts:[],setScript:(t,s)=>{e((0,c.ZP)((e=>{if("block_script"===s){let s=e.blockedScripts;if(t.urls){for(const[e,i]of Object.entries(t.urls)){if(!i||0===i.length)continue;let e=!1;for(const[t,c]of Object.entries(s))i===t&&(e=!0);e||(s[i]=i)}e.blockedScripts=s}}const i=e.scripts[s].findIndex((e=>e.id===t.id));-1!==i&&(e.scripts[s][i]=t)})))},fetchIntegrationsData:async()=>{if(t().fetching)return;e({fetching:!0});const{services:s,plugins:i,scripts:c,placeholders:n,blocked_scripts:r}=await l();let a=c;a.block_script&&a.block_script.length>0&&a.block_script.forEach(((e,t)=>{e.id=t})),a.add_script&&a.add_script.length>0&&a.add_script.forEach(((e,t)=>{e.id=t})),a.whitelist_script&&a.whitelist_script.length>0&&a.whitelist_script.forEach(((e,t)=>{e.id=t})),e((()=>({integrationsLoaded:!0,services:s,plugins:i,scripts:a,fetching:!1,placeholders:n,blockedScripts:r})))},addScript:s=>{e({fetching:!0}),t().scripts[s]||e((0,c.ZP)((e=>{e.scripts[s]=[]}))),e((0,c.ZP)((e=>{e.scripts[s].push({name:"general",id:e.scripts[s].length,enable:!0})})));let i=t().scripts;return n.doAction("update_scripts",{scripts:i}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},saveScript:(s,i)=>{e({fetching:!0}),t().scripts[i]||e((0,c.ZP)((e=>{e.scripts[i]=[]}))),e((0,c.ZP)((e=>{const t=e.scripts[i].findIndex((e=>e.id===s.id));-1!==t&&(e.scripts[i][t]=s)})));let r=t().scripts;return n.doAction("update_scripts",{scripts:r}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},deleteScript:(s,i)=>{e({fetching:!0}),t().scripts[i]||e((0,c.ZP)((e=>{e.scripts[i]=[]}))),e((0,c.ZP)((e=>{const t=e.scripts[i].findIndex((e=>e.id===s.id));-1!==t&&e.scripts[i].splice(t,1)})));let r=t().scripts;return n.doAction("update_scripts",{scripts:r}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},updatePluginStatus:async(t,s)=>{e({fetching:!0}),e((0,c.ZP)((e=>{const i=e.plugins.findIndex((e=>e.id===t));-1!==i&&(e.plugins[i].enabled=s)})));const i=await n.doAction("update_plugin_status",{plugin:t,enabled:s}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),i},updatePlaceholderStatus:async(t,s,i)=>{e({fetching:!0}),i&&e((0,c.ZP)((e=>{const i=e.plugins.findIndex((e=>e.id===t));-1!==i&&(e.plugins[i].placeholder=s?"enabled":"disabled")})));const r=await n.doAction("update_placeholder_status",{id:t,enabled:s}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),r}})));t.default=r;const l=()=>n.doAction("get_integrations_data",{}).then((e=>e)).catch((e=>{console.error(e)}))},34573:function(e,t,s){s.r(t);var i=s(69307),c=s(23361);t.default=e=>(0,i.createElement)("div",{className:"cmplz-panel__list__item",key:e.id,style:e.style?e.style:{}},(0,i.createElement)("details",null,(0,i.createElement)("summary",null,e.icon&&(0,i.createElement)(c.default,{name:e.icon}),(0,i.createElement)("h5",{className:"cmplz-panel__list__item__title"},e.summary),(0,i.createElement)("div",{className:"cmplz-panel__list__item__comment"},e.comment),(0,i.createElement)("div",{className:"cmplz-panel__list__item__icons"},e.icons),(0,i.createElement)(c.default,{name:"chevron-down",size:18})),(0,i.createElement)("div",{className:"cmplz-panel__list__item__details"},e.details)))},85050:function(e,t,s){s.r(t);var i=s(69307),c=s(65736),n=s(34573),r=s(85671),l=s(56293);t.default=(0,i.memo)((()=>{const{services:e,integrationsLoaded:t,plugins:s,fetchIntegrationsData:a}=(0,r.default)(),[d,o]=(0,i.useState)([]),{fields:p,getField:u}=(0,l.default)();(0,i.useEffect)((()=>{t||a()}),[t]),(0,i.useEffect)((()=>{f()}),[p,t]);const f=()=>{let t=[...e];t.forEach((function(e,s){let i={...e},c=u(e.source);if("multicheckbox"===c.type){let t=c.value;Array.isArray(t)||(t=[]),i.enabled=t.includes(e.id)}else i.enabled="yes"===c.value;t[s]=i})),t=t.filter((e=>e.enabled)),o(t)},_=e=>Array.isArray(e)?e.map(((e,t)=>(0,i.createElement)("div",{key:t},e.label))):null;let m=Array.isArray(d)?d.length:0,h=Array.isArray(s)?s.length:0;return(0,i.createElement)("div",{className:"cmplz-plugins_overview"},(0,i.createElement)("div",{className:"cmplz-panel__list"},(0,i.createElement)(n.default,{summary:(0,c.__)("We found %s active plugin integrations","complianz-gdpr").replace("%s",h),details:_(s),icon:"plugin"}),(0,i.createElement)(n.default,{summary:(0,c.__)("We found %s active service integrations","complianz-gdpr").replace("%s",m),details:_(d),icon:"services"})))}))}}]);