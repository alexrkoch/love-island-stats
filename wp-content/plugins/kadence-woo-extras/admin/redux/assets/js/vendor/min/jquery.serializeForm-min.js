!function(i){i.fn.serializeForm=function(){if(this.length<1)return!1;var e={},t=e,n=':input[type!="checkbox"][type!="radio"], input:checked',h=function(){if(!this.disabled){var n=this.name.replace(/\[([^\]]+)?\]/g,",$1").split(","),h=n.length-1,r=i(this);if(n[0]){for(var a=0;a<h;a++)t=t[n[a]]=t[n[a]]||(""===n[a+1]||"0"===n[a+1]?[]:{});void 0!==t.length?t.push(r.val()):t[n[h]]=r.val(),t=e}}};return this.filter(n).each(h),this.find(n).each(h),e}}(jQuery);