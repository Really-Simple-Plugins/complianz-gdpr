"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[8857],{38857:function(e,t,c){c.r(t),c.d(t,{default:function(){return y}});var r=c(69307),n=c(87462),a=c(99196),o=c(36206),u=c(28771),l=c(25360),s=c(77342),d=c(57898),i=c(7546),p=c(75320);const h="Switch",[b,m]=(0,l.b)(h),[f,k]=b(h),v=(0,a.forwardRef)(((e,t)=>{const{__scopeSwitch:c,name:r,checked:l,defaultChecked:d,required:i,disabled:h,value:b="on",onCheckedChange:m,...k}=e,[v,g]=(0,a.useState)(null),C=(0,u.e)(t,(e=>g(e))),y=(0,a.useRef)(!1),z=!v||Boolean(v.closest("form")),[S=!1,_]=(0,s.T)({prop:l,defaultProp:d,onChange:m});return(0,a.createElement)(f,{scope:c,checked:S,disabled:h},(0,a.createElement)(p.WV.button,(0,n.Z)({type:"button",role:"switch","aria-checked":S,"aria-required":i,"data-state":E(S),"data-disabled":h?"":void 0,disabled:h,value:b},k,{ref:C,onClick:(0,o.M)(e.onClick,(e=>{_((e=>!e)),z&&(y.current=e.isPropagationStopped(),y.current||e.stopPropagation())}))})),z&&(0,a.createElement)(w,{control:v,bubbles:!y.current,name:r,value:b,checked:S,required:i,disabled:h,style:{transform:"translateX(-100%)"}}))})),w=e=>{const{control:t,checked:c,bubbles:r=!0,...o}=e,u=(0,a.useRef)(null),l=(0,d.D)(c),s=(0,i.t)(t);return(0,a.useEffect)((()=>{const e=u.current,t=window.HTMLInputElement.prototype,n=Object.getOwnPropertyDescriptor(t,"checked").set;if(l!==c&&n){const t=new Event("click",{bubbles:r});n.call(e,c),e.dispatchEvent(t)}}),[l,c,r]),(0,a.createElement)("input",(0,n.Z)({type:"checkbox","aria-hidden":!0,defaultChecked:c},o,{tabIndex:-1,ref:u,style:{...e.style,...s,position:"absolute",pointerEvents:"none",opacity:0,margin:0}}))};function E(e){return e?"checked":"unchecked"}const g=v,C=(0,a.forwardRef)(((e,t)=>{const{__scopeSwitch:c,...r}=e,o=k("SwitchThumb",c);return(0,a.createElement)(p.WV.span,(0,n.Z)({"data-state":E(o.checked),"data-disabled":o.disabled?"":void 0},r,{ref:t}))}));var y=(0,r.memo)((e=>{let{value:t,onChange:c,required:n,disabled:a,className:o,label:u}=e,l=t;return"0"!==t&&"1"!==t||(l="1"===t),(0,r.createElement)("div",{className:"cmplz-input-group cmplz-switch-group"},(0,r.createElement)(g,{className:"cmplz-switch-root "+o,checked:l,onCheckedChange:c,disabled:a,required:n},(0,r.createElement)(C,{className:"cmplz-switch-thumb"})))}))},57898:function(e,t,c){c.d(t,{D:function(){return n}});var r=c(99196);function n(e){const t=(0,r.useRef)({value:e,previous:e});return(0,r.useMemo)((()=>(t.current.value!==e&&(t.current.previous=t.current.value,t.current.value=e),t.current.previous)),[e])}}}]);