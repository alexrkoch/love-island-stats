!function(e){"use strict";redux.field_objects=redux.field_objects||{},redux.field_objects.slider=redux.field_objects.slider||{},e(document).ready((function(){})),redux.field_objects.slider.init=function(i){i||(i=e(document).find(".redux-group-tab:visible").find(".redux-container-slider:visible")),e(i).each((function(){var i=e(this),t=i;if(i.hasClass("redux-field-container")||(t=i.parents(".redux-field-container:first")),!t.is(":hidden")&&t.hasClass("redux-field-init")){t.removeClass("redux-field-init"),i.find("div.redux-slider-container").each((function(){var t,d,l,r,a,s=0,n=1,o=2,u=3,c=e(this).data("id"),f=e(this).data("min"),v=e(this).data("max"),x=e(this).data("step"),h=e(this).data("handles"),p=e(this).data("default-one"),b=e(this).data("default-two"),w=e(this).data("resolution"),_=parseInt(e(this).data("display")),j=Boolean(e(this).data("rtl")),m=e(this).data("float-mark"),k=Boolean(e(this).data("forced")),g;g=!0===j?"rtl":"ltr";var C=[f,v],S=[p,b],y=[p],F,B,I,z;if(2==_?(F=l=i.find(".redux-slider-input-one-"+c),B=r=i.find(".redux-slider-input-two-"+c)):3==_?(l=i.find(".redux-slider-select-one-"+c),r=i.find(".redux-slider-select-two-"+c),redux.field_objects.slider.loadSelect(l,f,v,w,x),2===h&&redux.field_objects.slider.loadSelect(r,f,v,w,x)):1==_?(l=i.find("#redux-slider-label-one-"+c),r=i.find("#redux-slider-label-two-"+c)):0==_&&(l=i.find(".redux-slider-value-one-"+c),r=i.find(".redux-slider-value-two-"+c)),1==_){var D=[l,"html"],J;I=[D],z=[D,[r,"html"]]}else I=[l],z=[l,r];2===h?(t=S,d=z,a=!0):(t=y,d=I,a="lower");var N=e(this).noUiSlider({range:C,start:t,handles:h,step:x,connect:a,behaviour:"tap-drag",direction:g,serialization:{resolution:w,to:d,mark:m},slide:function(){if(1==_)if(2===h){var t=N.val();i.find("input.redux-slider-value-one-"+c).attr("value",t[0]),i.find("input.redux-slider-value-two-"+c).attr("value",t[1])}else i.find("input.redux-slider-value-one-"+c).attr("value",N.val());3==_&&(2===h?(i.find(".redux-slider-select-one").select2("val",N.val()[0]),i.find(".redux-slider-select-two").select2("val",N.val()[1])):i.find(".redux-slider-select-one").select2("val",N.val())),redux_change(e(this).parents(".redux-field-container:first").find("input"))}});2===_&&(F.keydown((function(e){var i=N.val(),t=parseInt(i[0]);switch(e.which){case 38:N.val([t+1,null]);break;case 40:N.val([t-1,null]);break;case 13:e.preventDefault();break}})),2===h&&B.keydown((function(e){var i=N.val(),t=parseInt(i[1]);switch(e.which){case 38:N.val([null,t+1]);break;case 40:N.val([null,t-1]);break;case 13:e.preventDefault();break}})))}));var d={width:"resolve",triggerChange:!0,allowClear:!0},l=i.find(".select2_params");if(l.size()>0){var r=l.val();r=JSON.parse(r),d=e.extend({},d,r)}i.find("select.redux-slider-select-one, select.redux-slider-select-two").select2(d)}}))},redux.field_objects.slider.isFloat=function(e){return+e===e&&!isFinite(e)||Boolean(e%1)},redux.field_objects.slider.decimalCount=function(e){var i;return e.toString().split(".")[1].length},redux.field_objects.slider.loadSelect=function(i,t,d,l,r){for(var a=t;a<=d;a+=l){var s=a;if(redux.field_objects.slider.isFloat(l)){var n=redux.field_objects.slider.decimalCount(l);s=a.toFixed(n)}e(i).append('<option value="'+s+'">'+s+"</option>")}}}(jQuery);