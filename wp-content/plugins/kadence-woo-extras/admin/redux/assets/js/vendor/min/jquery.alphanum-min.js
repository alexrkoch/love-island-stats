!function(e){function t(){var e="!@#$%^&*()+=[]\\';,/{}|\":<>?~`.-_";return e+=" "}function n(){var e="¬€£¦";return"¬€£¦"}function r(t,n,r){t.each((function(){var t=e(this);t.bind("keyup change paste",(function(e){var a="";e.originalEvent&&e.originalEvent.clipboardData&&e.originalEvent.clipboardData.getData&&(a=e.originalEvent.clipboardData.getData("text/plain")),setTimeout((function(){i(t,n,r,a)}),0)})),t.bind("keypress",(function(e){var a=e.charCode?e.charCode:e.which;if(!(l(a)||e.ctrlKey||e.metaKey)){var o=String.fromCharCode(a),i=t.selection(),u=i.start,c=i.end,s=t.val(),f=s.substring(0,u)+o+s.substring(c),v;n(f,r)!=f&&e.preventDefault()}}))}))}function a(t,n){var r=parseFloat(e(t).val()),a=e(t);isNaN(r)?a.val(""):(o(n.min)&&r<n.min&&a.val(""),o(n.max)&&r>n.max&&a.val(""))}function o(e){return!isNaN(e)}function l(e){return!(e>=32)&&(10!=e&&13!=e)}function i(e,t,n,r){var a=e.val();""==a&&r.length>0&&(a=r);var o=t(a,n);if(a!=o){var l=e.alphanum_caret();e.val(o),a.length==o.length+1?e.alphanum_caret(l-1):e.alphanum_caret(l)}}function u(t,n){void 0===n&&(n=O);var r,a={};return r="string"==typeof t?j[t]:void 0===t?{}:t,e.extend(a,n,r),void 0===a.blacklist&&(a.blacklistSet=R(a.allow,a.disallow)),a}function c(t){var n,r={};return n="string"==typeof t?k[t]:void 0===t?{}:t,e.extend(r,L,n),r}function s(e,t,n){return!(n.maxLength&&e.length>=n.maxLength)&&(n.allow.indexOf(t)>=0||(!(!n.allowSpace||" "!=t)||!n.blacklistSet.contains(t)&&(!(!n.allowNumeric&&M[t])&&(!(!n.allowUpper&&N(t))&&(!(!n.allowLower&&y(t))&&(!(!n.allowCaseless&&E(t))&&(!(!n.allowLatin&&Q.contains(t))&&(!!n.allowOtherCharSets||!(!M[t]&&!Q.contains(t))))))))))}function f(e,t,n){if(M[t])return!p(e,n)&&(!m(e,n)&&(!h(e,n)&&(!d(e+t,n)&&!g(e+t,n))));if(n.allowPlus&&"+"==t&&""==e)return!0;if(n.allowMinus&&"-"==t&&""==e)return!0;if(t==A&&n.allowThouSep&&b(e,t))return!0;if(t==z){if(e.indexOf(z)>=0)return!1;if(n.allowDecSep)return!0}return!1}function v(e){return(e+="").replace(/[^0-9]/g,"").length}function p(e,t){var n=t.maxDigits,r;return""!=n&&!isNaN(n)&&v(e)>=n}function h(e,t){var n=t.maxDecimalPlaces;if(""==n||isNaN(n))return!1;var r=e.indexOf(z),a,o;return-1!=r&&v(e.substring(r))>=n}function m(e,t){var n=t.maxPreDecimalPlaces,r,a;return""!=n&&!isNaN(n)&&(!(e.indexOf(z)>=0)&&v(e)>=n)}function d(e,t){return!(!t.max||t.max<0)&&parseFloat(e)>t.max;var n}function g(e,t){return!(!t.min||t.min>0)&&parseFloat(e)<t.min;var n}function w(e,t){if("string"!=typeof e)return e;var n=e.split(""),r=[],a=0,o;for(a=0;a<n.length;a++){var l;o=n[a],s(r.join(""),o,t)&&r.push(o)}return r.join("")}function S(e,t){if("string"!=typeof e)return e;var n=e.split(""),r=[],a=0,o;for(a=0;a<n.length;a++){var l;o=n[a],f(r.join(""),o,t)&&r.push(o)}return r.join("")}function x(e){var t=e.split(""),n=0,r=[],a;for(n=0;n<t.length;n++)a=t[n]}function T(e){}function N(e){var t=e.toUpperCase(),n=e.toLowerCase();return e==t&&t!=n}function y(e){var t=e.toUpperCase(),n=e.toLowerCase();return e==n&&t!=n}function E(e){return e.toUpperCase()==e.toLowerCase()}function R(e,t){var n=new D(U+t),r=new D(e),a;return n.subtract(r)}function C(){var e="0123456789".split(""),t={},n=0,r;for(n=0;n<e.length;n++)t[r=e[n]]=!0;return t}function P(){var e="abcdefghijklmnopqrstuvwxyz",t=e.toUpperCase(),n;return new D(e+t)}function b(e,t){if(0==e.length)return!1;var n;if(e.indexOf(z)>=0)return!1;var r=e.indexOf(A);if(r<0)return!0;var a=e.lastIndexOf(A),o,l;return!(e.length-a-1<3)&&!(v(e.substring(r))%3>0)}function D(e){this.map="string"==typeof e?_(e):{}}function _(e){var t={},n=e.split(""),r=0,a;for(r=0;r<n.length;r++)t[a=n[r]]=!0;return t}e.fn.alphanum=function(e){var t,n=this;return r(this,w,u(e)),this},e.fn.alpha=function(e){var t=u("alpha"),n,a=this;return r(this,w,u(e,t)),this},e.fn.numeric=function(e){var t,n=this;return r(this,S,c(e)),this.blur((function(){a(this,e)})),this};var O={allow:"",disallow:"",allowSpace:!0,allowNumeric:!0,allowUpper:!0,allowLower:!0,allowCaseless:!0,allowLatin:!0,allowOtherCharSets:!0,maxLength:NaN},L={allowPlus:!1,allowMinus:!0,allowThouSep:!0,allowDecSep:!0,allowLeadingSpaces:!1,maxDigits:NaN,maxDecimalPlaces:NaN,maxPreDecimalPlaces:NaN,max:NaN,min:NaN},j={alpha:{allowNumeric:!1},upper:{allowNumeric:!1,allowUpper:!0,allowLower:!1,allowCaseless:!0},lower:{allowNumeric:!1,allowUpper:!1,allowLower:!0,allowCaseless:!0}},k={integer:{allowPlus:!1,allowMinus:!0,allowThouSep:!1,allowDecSep:!1},positiveInteger:{allowPlus:!1,allowMinus:!1,allowThouSep:!1,allowDecSep:!1}},U=t()+n(),A=",",z=".",M=C(),Q=P();D.prototype.add=function(e){var t=this.clone();for(var n in e.map)t.map[n]=!0;return t},D.prototype.subtract=function(e){var t=this.clone();for(var n in e.map)delete t.map[n];return t},D.prototype.contains=function(e){return!!this.map[e]},D.prototype.clone=function(){var e=new D;for(var t in this.map)e.map[t]=!0;return e},e.fn.alphanum.backdoorAlphaNum=function(e,t){var n;return w(e,u(t))},e.fn.alphanum.backdoorNumeric=function(e,t){var n;return S(e,c(t))},e.fn.alphanum.setNumericSeparators=function(e){1==e.thousandsSeparator.length&&1==e.decimalSeparator.length&&(A=e.thousandsSeparator,z=e.decimalSeparator)}}(jQuery),function(e){function t(e,t){if(e.createTextRange){var n=e.createTextRange();n.move("character",t),n.select()}else null!=e.selectionStart&&(e.focus(),e.setSelectionRange(t,t))}function n(e){if("selection"in document){var t=e.createTextRange();try{t.setEndPoint("EndToStart",document.selection.createRange())}catch(e){return 0}return t.text.length}if(null!=e.selectionStart)return e.selectionStart}e.fn.alphanum_caret=function(r,a){return void 0===r?n(this.get(0)):this.queue((function(n){if(isNaN(r)){var o=e(this).val().indexOf(r);!0===a?o+=r.length:void 0!==a&&(o+=a),t(this,o)}else t(this,r);n()}))}}(jQuery),function(e){var t=function(e){return e.replace(/([a-z])([a-z]+)/gi,(function(e,t,n){return t+n.toLowerCase()})).replace(/_/g,"")},n=function(e){return e.replace(/^([a-z]+)_TO_([a-z]+)/i,(function(e,t,n){return n+"_TO_"+t}))},r=function(e){return e?e.ownerDocument.defaultView||e.ownerDocument.parentWindow:window},a=function(t,n){var r=e.Range.current(t).clone(),a=e.Range(t).select(t);return r.overlaps(a)?(r.compare("START_TO_START",a)<1?(startPos=0,r.move("START_TO_START",a)):(fromElementToCurrent=a.clone(),fromElementToCurrent.move("END_TO_START",r),startPos=fromElementToCurrent.toString().length),r.compare("END_TO_END",a)>=0?endPos=a.toString().length:endPos=startPos+r.toString().length,{start:startPos,end:endPos}):null},o=function(t){var n=r(t);if(void 0!==t.selectionStart)return document.activeElement&&document.activeElement!=t&&t.selectionStart==t.selectionEnd&&0==t.selectionStart?{start:t.value.length,end:t.value.length}:{start:t.selectionStart,end:t.selectionEnd};if(n.getSelection)return a(t,n);try{if("input"==t.nodeName.toLowerCase()){var o=r(t).document.selection.createRange(),l=t.createTextRange();l.setEndPoint("EndToStart",o);var i=l.text.length;return{start:i,end:i+o.text.length}}var u=a(t,n);if(!u)return u;var c=e.Range.current().clone(),s=c.clone().collapse().range,f=c.clone().collapse(!1).range;return s.moveStart("character",-1),f.moveStart("character",-1),0!=u.startPos&&""==s.text&&(u.startPos+=2),0!=u.endPos&&""==f.text&&(u.endPos+=2),u}catch(e){return{start:t.value.length,end:t.value.length}}},l=function(e,t,n){var a=r(e);if(e.setSelectionRange)void 0===n?(e.focus(),e.setSelectionRange(t,t)):(e.select(),e.selectionStart=t,e.selectionEnd=n);else if(e.createTextRange){var o=e.createTextRange();o.moveStart("character",t),n=n||t,o.moveEnd("character",n-e.value.length),o.select()}else if(a.getSelection){var l=a.document,i=a.getSelection(),c=l.createRange(),s=[t,void 0!==n?n:t];u([e],s),c.setStart(s[0].el,s[0].count),c.setEnd(s[1].el,s[1].count),i.removeAllRanges(),i.addRange(c)}else if(a.document.body.createTextRange){var c;(c=document.body.createTextRange()).moveToElementText(e),c.collapse(),c.moveStart("character",t),c.moveEnd("character",void 0!==n?n:t),c.select()}},i=function(e,t,n,r){"number"==typeof n[0]&&n[0]<t&&(n[0]={el:r,count:n[0]-e}),"number"==typeof n[1]&&n[1]<=t&&(n[1]={el:r,count:n[1]-e})},u=function(e,t,n){var r,a;n=n||0;for(var o=0;e[o];o++)3===(r=e[o]).nodeType||4===r.nodeType?(a=n,n+=r.nodeValue.length,i(a,n,t,r)):8!==r.nodeType&&(n=u(r.childNodes,t,n));return n};jQuery.fn.selection=function(e,t){return void 0!==e?this.each((function(){l(this,e,t)})):o(this[0])},e.fn.selection.getCharElement=u}(jQuery);