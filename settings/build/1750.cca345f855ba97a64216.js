"use strict";(self.webpackChunkcomplianz_gdpr=self.webpackChunkcomplianz_gdpr||[]).push([[1750],{41629:(e,r,t)=>{t.r(r),t.d(r,{default:()=>c});var l=t(69307),n=t(872);const c=(0,t(99196).memo)((e=>{let{label:r,id:t,value:c,onChange:a,required:o,defaultValue:u,disabled:i,options:s={}}=e;return(0,l.createElement)(n.fC,{disabled:i&&!Array.isArray(i),className:"cmplz-input-group cmplz-radio-group",value:c,"aria-label":r,onValueChange:a,required:o,default:u},Object.entries(s).map((e=>{let[r,c]=e;return(0,l.createElement)("div",{key:r,className:"cmplz-radio-group__item"},(0,l.createElement)(n.ck,{disabled:Array.isArray(i)&&i.includes(r),value:r,id:t+"_"+r},(0,l.createElement)(n.z$,{className:"cmplz-radio-group__indicator"})),(0,l.createElement)("label",{className:"cmplz-radio-label",htmlFor:t+"_"+r},c))})))}))},65936:(e,r,t)=>{t.d(r,{B:()=>o});var l=t(99196),n=t(25360),c=t(28771),a=t(88426);function o(e){const r=e+"CollectionProvider",[t,o]=(0,n.b)(r),[u,i]=t(r,{collectionRef:{current:null},itemMap:new Map}),s=e+"CollectionSlot",d=e+"CollectionItemSlot",m="data-radix-collection-item";return[{Provider:e=>{const{scope:r,children:t}=e,n=l.useRef(null),c=l.useRef(new Map).current;return l.createElement(u,{scope:r,itemMap:c,collectionRef:n},t)},Slot:l.forwardRef(((e,r)=>{const{scope:t,children:n}=e,o=i(s,t),u=(0,c.e)(r,o.collectionRef);return l.createElement(a.g7,{ref:u},n)})),ItemSlot:l.forwardRef(((e,r)=>{const{scope:t,children:n,...o}=e,u=l.useRef(null),s=(0,c.e)(r,u),f=i(d,t);return l.useEffect((()=>(f.itemMap.set(u,{ref:u,...o}),()=>{f.itemMap.delete(u)}))),l.createElement(a.g7,{[m]:"",ref:s},n)}))},function(r){const t=i(e+"CollectionConsumer",r);return l.useCallback((()=>{const e=t.collectionRef.current;if(!e)return[];const r=Array.from(e.querySelectorAll(`[${m}]`));return Array.from(t.itemMap.values()).sort(((e,t)=>r.indexOf(e.ref.current)-r.indexOf(t.ref.current)))}),[t.collectionRef,t.itemMap])},o]}},78990:(e,r,t)=>{t.d(r,{gm:()=>c});var l=t(99196);const n=(0,l.createContext)(void 0);function c(e){const r=(0,l.useContext)(n);return e||r||"ltr"}},91276:(e,r,t)=>{var l;t.d(r,{M:()=>u});var n=t(99196),c=t(9981);const a=(l||(l=t.t(n,2)))["useId".toString()]||(()=>{});let o=0;function u(e){const[r,t]=n.useState(a());return(0,c.b)((()=>{e||t((e=>null!=e?e:String(o++)))}),[e]),e||(r?`radix-${r}`:"")}},57898:(e,r,t)=>{t.d(r,{D:()=>n});var l=t(99196);function n(e){const r=(0,l.useRef)({value:e,previous:e});return(0,l.useMemo)((()=>(r.current.value!==e&&(r.current.previous=r.current.value,r.current.value=e),r.current.previous)),[e])}}}]);