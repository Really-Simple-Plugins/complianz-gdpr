"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[5050,5671,4573],{85671:function(e,t,i){i.r(t);var s=i(30270),c=i(12902),n=i(48399);const l=(0,s.Ue)(((e,t)=>({integrationsLoaded:!1,fetching:!1,services:[],plugins:[],scripts:[],placeholders:[],blockedScripts:[],setScript:(t,i)=>{e((0,c.ZP)((e=>{if("block_script"===i){let i=e.blockedScripts;if(t.urls){for(const[e,s]of Object.entries(t.urls)){if(!s||0===s.length)continue;let e=!1;for(const[t,c]of Object.entries(i))s===t&&(e=!0);e||(i[s]=s)}e.blockedScripts=i}}const s=e.scripts[i].findIndex((e=>e.id===t.id));-1!==s&&(e.scripts[i][s]=t)})))},fetchIntegrationsData:async()=>{if(t().fetching)return;e({fetching:!0});const{services:i,plugins:s,scripts:c,placeholders:n,blocked_scripts:l}=await r();let a=c;a.block_script&&a.block_script.length>0&&a.block_script.forEach(((e,t)=>{e.id=t})),a.add_script&&a.add_script.length>0&&a.add_script.forEach(((e,t)=>{e.id=t})),a.whitelist_script&&a.whitelist_script.length>0&&a.whitelist_script.forEach(((e,t)=>{e.id=t})),e((()=>({integrationsLoaded:!0,services:i,plugins:s,scripts:a,fetching:!1,placeholders:n,blockedScripts:l})))},addScript:i=>{e({fetching:!0}),e((0,c.ZP)((e=>{e.scripts[i].push({name:"general",id:e.scripts[i].length,enable:!0})})));let s=t().scripts;return n.doAction("update_scripts",{scripts:s}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},saveScript:(i,s)=>{e({fetching:!0}),e((0,c.ZP)((e=>{const t=e.scripts[s].findIndex((e=>e.id===i.id));-1!==t&&(e.scripts[s][t]=i)})));let l=t().scripts;return n.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},deleteScript:(i,s)=>{e({fetching:!0}),e((0,c.ZP)((e=>{const t=e.scripts[s].findIndex((e=>e.id===i.id));-1!==t&&e.scripts[s].splice(t,1)})));let l=t().scripts;return n.doAction("update_scripts",{scripts:l}).then((t=>(e({fetching:!1}),t))).catch((e=>{console.error(e)}))},updatePluginStatus:async(t,i)=>{e({fetching:!0}),e((0,c.ZP)((e=>{const s=e.plugins.findIndex((e=>e.id===t));-1!==s&&(e.plugins[s].enabled=i)})));const s=await n.doAction("update_plugin_status",{plugin:t,enabled:i}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),s},updatePlaceholderStatus:async(t,i,s)=>{e({fetching:!0}),s&&e((0,c.ZP)((e=>{const s=e.plugins.findIndex((e=>e.id===t));-1!==s&&(e.plugins[s].placeholder=i?"enabled":"disabled")})));const l=await n.doAction("update_placeholder_status",{id:t,enabled:i}).then((e=>e)).catch((e=>{console.error(e)}));return e({fetching:!1}),l}})));t.default=l;const r=()=>n.doAction("get_integrations_data",{}).then((e=>e)).catch((e=>{console.error(e)}))},34573:function(e,t,i){i.r(t);var s=i(69307),c=i(23361);t.default=e=>(0,s.createElement)("div",{className:"cmplz-panel__list__item",key:e.id,style:e.style?e.style:{}},(0,s.createElement)("details",null,(0,s.createElement)("summary",null,e.icon&&(0,s.createElement)(c.default,{name:e.icon}),(0,s.createElement)("h5",{className:"cmplz-panel__list__item__title"},e.summary),(0,s.createElement)("div",{className:"cmplz-panel__list__item__comment"},e.comment),(0,s.createElement)("div",{className:"cmplz-panel__list__item__icons"},e.icons),(0,s.createElement)(c.default,{name:"chevron-down",size:18})),(0,s.createElement)("div",{className:"cmplz-panel__list__item__details"},e.details)))},85050:function(e,t,i){i.r(t);var s=i(69307),c=i(65736),n=i(34573),l=i(85671),r=i(56293);t.default=(0,s.memo)((()=>{const{services:e,integrationsLoaded:t,plugins:i,fetchIntegrationsData:a}=(0,l.default)(),[d,o]=(0,s.useState)([]),{fields:p,getField:u}=(0,r.default)();(0,s.useEffect)((()=>{t||a()}),[t]),(0,s.useEffect)((()=>{f()}),[p,t]);const f=()=>{let t=[...e];t.forEach((function(e,i){let s={...e},c=u(e.source);if("multicheckbox"===c.type){let t=c.value;Array.isArray(t)||(t=[]),s.enabled=t.includes(e.id)}else s.enabled="yes"===c.value;t[i]=s})),t=t.filter((e=>e.enabled)),o(t)},_=e=>Array.isArray(e)?e.map(((e,t)=>(0,s.createElement)("div",{key:t},e.label))):null;let m=Array.isArray(d)?d.length:0,h=Array.isArray(i)?i.length:0;return(0,s.createElement)("div",{className:"cmplz-plugins_overview"},(0,s.createElement)("div",{className:"cmplz-panel__list"},(0,s.createElement)(n.default,{summary:(0,c.__)("We found %s active plugin integrations","complianz-gdpr").replace("%s",h),details:_(i),icon:"plugin"}),(0,s.createElement)(n.default,{summary:(0,c.__)("We found %s active service integrations","complianz-gdpr").replace("%s",m),details:_(d),icon:"services"})))}))}}]);