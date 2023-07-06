"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[938,2588,3252],{50938:(e,a,t)=>{t.r(a),t.d(a,{default:()=>c});var l=t(69307),o=t(65736),r=t(56293),n=t(23252),s=t(32588);const c=()=>{const{consentType:e,setConsentType:a,consentTypes:t,fetchStatisticsData:c,loaded:i}=(0,n.default)(),{fields:d,getFieldValue:b}=(0,r.default)(),[g,m]=(0,l.useState)(!1);(0,l.useEffect)((()=>{let e=1==b("a_b_testing");m(e)}),[b("a_b_testing")]),(0,l.useEffect)((()=>{g&&!i&&c()}),[g]);let u=[];return t&&(u=t.map((e=>({value:e.id,label:e.label})))),(0,l.createElement)(l.Fragment,null,(0,l.createElement)("h3",{className:"cmplz-grid-title cmplz-h4"},g&&(0,o.__)("Statistics","complianz-gdpr"),!g&&(0,o.__)("Tools","complianz-gdpr")),(0,l.createElement)("div",{className:"cmplz-grid-item-controls"},g&&u&&u.length>1&&(0,l.createElement)(s.default,{canBeEmpty:!1,onChange:e=>a(e),options:u})))}},32588:(e,a,t)=>{t.r(a),t.d(a,{default:()=>c});var l=t(69307),o=t(99196),r=t(79552),n=t(20384),s=t(65736);const c=(0,o.memo)((e=>{let{value:a=!1,onChange:t,required:o,defaultValue:c,disabled:i,options:d={},canBeEmpty:b=!0,label:g,innerRef:m}=e;if(Array.isArray(d)){let e={};d.map((a=>{e[a.value]=a.label})),d=e}return b?d={0:(0,s.__)("Select an option","complianz-gdpr"),...d}:a||(a=Object.keys(d)[0]),(0,l.createElement)("div",{className:"cmplz-input-group cmplz-select-group",key:g},(0,l.createElement)(r.fC,{value:a,defaultValue:c,onValueChange:t,required:o,disabled:i&&!Array.isArray(i)},(0,l.createElement)(r.xz,{className:"cmplz-select-group__trigger"},(0,l.createElement)(r.B4,null),(0,l.createElement)(n.default,{name:"chevron-down"})),(0,l.createElement)(r.VY,{className:"cmplz-select-group__content",position:"popper"},(0,l.createElement)(r.u_,{className:"cmplz-select-group__scroll-button"},(0,l.createElement)(n.default,{name:"chevron-up"})),(0,l.createElement)(r.l_,{className:"cmplz-select-group__viewport"},(0,l.createElement)(r.ZA,null,Object.entries(d).map((e=>{let[a,t]=e;return(0,l.createElement)(r.ck,{disabled:Array.isArray(i)&&i.includes(a),className:"cmplz-select-group__item",key:a,value:a},(0,l.createElement)(r.eT,null,t))})))),(0,l.createElement)(r.$G,{className:"cmplz-select-group__scroll-button"},(0,l.createElement)(n.default,{name:"chevron-down"})))))}))},23252:(e,a,t)=>{t.r(a),t.d(a,{default:()=>s});var l=t(30270),o=t(48399);const r={optin:{labels:["Functional","Statistics","Marketing","Do Not Track","No choice","No warning"],categories:["functional","statistics","marketing","do_not_track","no_choice","no_warning"],datasets:[{data:["0","0","0","0","0","0"],backgroundColor:"rgba(46, 138, 55, 1)",borderColor:"rgba(46, 138, 55, 1)",label:"A (default)",fill:"false",borderDash:[0,0]},{data:["0","0","0","0","0","0"],backgroundColor:"rgba(244, 191, 62, 1)",borderColor:"rgba(244, 191, 62, 1)",label:"B",fill:"false",borderDash:[0,0]}],max:5},optout:{labels:["Functional","Statistics","Marketing","Do Not Track","No choice","No warning"],categories:["functional","statistics","marketing","do_not_track","no_choice","no_warning"],datasets:[{data:["0","0","0","0","0","0"],backgroundColor:"rgba(46, 138, 55, 1)",borderColor:"rgba(46, 138, 55, 1)",label:"A (default)",fill:"false",borderDash:[0,0]},{data:["0","0","0","0","0","0"],backgroundColor:"rgba(244, 191, 62, 1)",borderColor:"rgba(244, 191, 62, 1)",label:"B",fill:"false",borderDash:[0,0]}],max:5}},n={optin:{labels:["Functional","Statistics","Marketing","Do Not Track","No choice","No warning"],categories:["functional","statistics","marketing","do_not_track","no_choice","no_warning"],datasets:[{data:["29","747","174","292","30","10"],backgroundColor:"rgba(46, 138, 55, 1)",borderColor:"rgba(46, 138, 55, 1)",label:"Demo A (default)",fill:"false",borderDash:[0,0]},{data:["3","536","240","389","45","32"],backgroundColor:"rgba(244, 191, 62, 1)",borderColor:"rgba(244, 191, 62, 1)",label:"Demo B",fill:"false",borderDash:[0,0]}],max:5},optout:{labels:["Functional","Statistics","Marketing","Do Not Track","No choice","No warning"],categories:["functional","statistics","marketing","do_not_track","no_choice","no_warning"],datasets:[{data:["29","747","174","292","30","10"],backgroundColor:"rgba(46, 138, 55, 1)",borderColor:"rgba(46, 138, 55, 1)",label:"A (default)",fill:"false",borderDash:[0,0]},{data:["3","536","240","389","45","32"],backgroundColor:"rgba(244, 191, 62, 1)",borderColor:"rgba(244, 191, 62, 1)",label:"Demo B",fill:"false",borderDash:[0,0]}],max:5}},s=(0,l.Ue)(((e,a)=>({consentType:"optin",setConsentType:a=>{e({consentType:a})},statisticsLoading:!1,consentTypes:[],regions:[],defaultConsentType:"optin",loaded:!1,statisticsData:complianz.is_premium?n:r,emptyStatisticsData:r,bestPerformerEnabled:!1,daysLeft:"",abTrackingCompleted:!1,labels:[],setLabels:a=>{e({labels:a})},fetchStatisticsData:async()=>{if(e({saving:!0}),a().loaded)return;const{daysLeft:t,abTrackingCompleted:l,consentTypes:r,statisticsData:n,defaultConsentType:s,regions:c,bestPerformerEnabled:i}=await o.doAction("get_statistics_data",{}).then((e=>e)).catch((e=>{console.error(e)}));e({saving:!1,loaded:!0,consentType:s,consentTypes:r,statisticsData:n,defaultConsentType:s,bestPerformerEnabled:i,regions:c,daysLeft:t,abTrackingCompleted:l})}})))}}]);