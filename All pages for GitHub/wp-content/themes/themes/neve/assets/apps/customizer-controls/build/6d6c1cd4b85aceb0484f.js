"use strict";(self.webpackChunkneve=self.webpackChunkneve||[]).push([[841],{540:function(e,t,n){var a=n(307),s=n(444);const l=(0,a.createElement)(s.SVG,{width:"18",height:"18",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 18 18"},(0,a.createElement)(s.Path,{d:"M5 4h2V2H5v2zm6-2v2h2V2h-2zm-6 8h2V8H5v2zm6 0h2V8h-2v2zm-6 6h2v-2H5v2zm6 0h2v-2h-2v2z"}));t.Z=l},841:function(e,t,n){n.r(t);var a=n(196),s=n.n(a),l=n(941),i=n(609),r=n(540),m=n(736),c=n(307),o=n(926);t.default=({items:e})=>{const{builder:t,actions:n,dragging:a}=(0,c.useContext)(o.Z),{onDragStart:d,onDragEnd:v,setSidebarItems:h}=n,u=window.NeveReactCustomize.HFG[t].items;return s().createElement("div",{className:"neve-builder-sidebar-content"},s().createElement("span",{className:"customize-control-title"},(0,m.__)("Available Components","neve")),e&&e.length>0&&s().createElement("div",{className:"sidebar-items droppable-wrap"},s().createElement(l._O,{onEnd:v,animation:0,group:t,className:"droppable",disabled:a,onStart:d,list:e,setList:e=>{const t=e.map((e=>({id:e.id}))).sort(((e,t)=>e.id<t.id?-1:1));h(t)}},e.map((e=>{const{name:t}=u[e.id];return s().createElement("div",{className:"builder-item",key:e.id},s().createElement(i.Icon,{className:"handle",icon:r.Z,size:15}),s().createElement("span",null,t))})))),!e.length&&s().createElement("div",{className:"no-components"},s().createElement("span",null,(0,m.__)("All available components are used inside the builder","neve"))))}}}]);