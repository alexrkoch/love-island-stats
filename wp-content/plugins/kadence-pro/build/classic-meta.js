!function(e){var t={};function o(n){if(t[n])return t[n].exports;var r=t[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)o.d(n,r,function(t){return e[t]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=219)}({219:function(e,t,o){"use strict";o.r(t);o(220);window.document.addEventListener("DOMContentLoaded",(function(){new Promise((function(e){console.log(wp.data.select("core/editor").getEditedPostAttribute("meta"));var t=wp.data.subscribe((function(){console.log(wp.data.select("core/editor")),wp.data.select("core/editor").isCleanNewPost()&&(t(),e()),wp.data.select("core/editor").getBlocks().length>0&&(t(),e()),console.log(wp.data.select("core/editor"))}))})).then((function(){console.log("editor ready"),console.log(wp.data.select("core/editor").getEditedPostAttribute("meta"))}))}))},220:function(e,t){!function(){e.exports=this.wp.data}()}});