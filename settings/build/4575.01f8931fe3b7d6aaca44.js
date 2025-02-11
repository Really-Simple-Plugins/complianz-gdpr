"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[4575,8432,622],{38432:(e,t,a)=>{a.r(t),a.d(t,{default:()=>d});var r=a(81621),s=a(72346),o=a(31127),n=a(979),c=a(66212);const d=(0,r.vt)((e=>({startDate:(0,s.default)((0,o.default)((0,n.A)(new Date,7)),"yyyy-MM-dd"),setStartDate:t=>e((e=>({startDate:t}))),endDate:(0,s.default)((0,c.default)((0,n.A)(new Date,1)),"yyyy-MM-dd"),setEndDate:t=>e((e=>({endDate:t}))),range:"last-7-days",setRange:t=>e((e=>({range:t})))})))},44575:(e,t,a)=>{a.r(t),a.d(t,{default:()=>l});var r=a(51609),s=a(27723),o=a(86087),n=a(90622),c=a(45111),d=a(38432);const l=(0,o.memo)((()=>{const{noData:e,startExport:t,exportLink:l,fetchExportDatarequestsProgress:i,generating:u,progress:p}=(0,n.default)(),[g,f]=(0,o.useState)(null),{startDate:m,endDate:h}=(0,d.default)();return(0,o.useEffect)((()=>{Promise.all([a.e(20),a.e(393),a.e(799),a.e(7660)]).then(a.bind(a,95279)).then((({default:e})=>{f((()=>e))}))}),[]),(0,o.useEffect)((()=>{i(!0)}),[]),(0,o.useEffect)((()=>{p<100&&u&&i(!1,m,h)}),[p]),(0,r.createElement)(r.Fragment,null,(0,r.createElement)("div",{className:"cmplz-table-header-controls"},g&&(0,r.createElement)(g,null),(0,r.createElement)("button",{disabled:u,className:"button button-default cmplz-field-button",onClick:()=>t()},(0,s.__)("Export to CSV","complianz-gdpr"),u&&(0,r.createElement)(r.Fragment,null,(0,r.createElement)(c.default,{name:"loading",color:"grey"})," ",p,"%"))),p>=100&&(""!==l||e)&&(0,r.createElement)("div",{className:"cmplz-selected-document"},!e&&(0,s.__)("Your Data Requests Export has been completed.","complianz-gdpr"),e&&(0,s.__)("Your selection does not contain any data.","complianz-gdpr"),(0,r.createElement)("div",{className:"cmplz-selected-document-controls"},!e&&(0,r.createElement)("a",{className:"button button-default",href:l},(0,s.__)("Download","complianz-gdpr")))))}))},90622:(e,t,a)=>{a.r(t),a.d(t,{default:()=>n});var r=a(81621),s=a(9588),o=a(16535);a(86087);const n=(0,r.vt)(((e,t)=>({recordsLoaded:!1,searchValue:"",setSearchValue:t=>e({searchValue:t}),status:"open",setStatus:t=>e({status:t}),selectedRecords:[],setSelectedRecords:t=>e({selectedRecords:t}),fetching:!1,generating:!1,progress:!1,records:[],totalRecords:0,totalOpen:0,exportLink:"",noData:!1,indeterminate:!1,setIndeterminate:t=>e({indeterminate:t}),paginationPerPage:10,pagination:{currentPage:1},setPagination:t=>e({pagination:t}),orderBy:"ID",setOrderBy:t=>e({orderBy:t}),order:"DESC",setOrder:t=>e({order:t}),deleteRecords:async a=>{let r={};r.per_page=t().paginationPerPage,r.page=t().pagination.currentPage,r.order=t().order.toUpperCase(),r.orderBy=t().orderBy,r.search=t().searchValue,r.status=t().status;let o=t().records.filter((e=>a.includes(e.ID)));e((e=>({records:e.records.filter((e=>!a.includes(e.ID)))}))),r.records=o,await s.doAction("delete_datarequests",r).then((e=>e)).catch((e=>{console.error(e)})),await t().fetchData(),t().setSelectedRecords([]),t().setIndeterminate(!1)},resolveRecords:async a=>{let r={};r.per_page=t().paginationPerPage,r.page=t().pagination.currentPage,r.order=t().order.toUpperCase(),r.orderBy=t().orderBy,r.search=t().searchValue,r.status=t().status,e((0,o.Ay)((e=>{e.records.forEach((function(t,r){a.includes(t.ID)&&(e.records[r].resolved=!0)}))}))),r.records=t().records.filter((e=>a.includes(e.ID))),await s.doAction("resolve_datarequests",r).then((e=>e)).catch((e=>{console.error(e)})),await t().fetchData(),t().setSelectedRecords([]),t().setIndeterminate(!1)},fetchData:async()=>{if(t().fetching)return;e({fetching:!0});let a={};a.per_page=t().paginationPerPage,a.page=t().pagination.currentPage,a.order=t().order.toUpperCase(),a.orderBy=t().orderBy,a.search=t().searchValue,a.status=t().status;const{records:r,totalRecords:o,totalOpen:n}=await s.doAction("get_datarequests",a).then((e=>e)).catch((e=>{console.error(e)}));e((()=>({recordsLoaded:!0,records:r,totalRecords:o,totalOpen:n,fetching:!1})))},startExport:async()=>{e({generating:!0,progress:0,exportLink:""})},fetchExportDatarequestsProgress:async(t,a,r)=>{(t=void 0!==t&&t)||e({generating:!0});let o={};o.startDate=a,o.endDate=r,o.statusOnly=t;const{progress:n,exportLink:c,noData:d}=await s.doAction("export_datarequests",o).then((e=>e)).catch((e=>{console.error(e)}));let l=!1;n<100&&(l=!0),e({progress:n,exportLink:c,generating:l,noData:d})}})))}}]);