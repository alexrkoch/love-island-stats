!function(e){"use strict";redux.field_objects=redux.field_objects||{},redux.field_objects.select=redux.field_objects.select||{},redux.field_objects.select.init=function(t){t||(t=e(document).find(".redux-container-select:visible")),e(t).each((function(){var t=e(this),s=t;t.hasClass("redux-field-container")||(s=t.parents(".redux-field-container:first")),s.is(":hidden")||s.hasClass("redux-field-init")&&(s.removeClass("redux-field-init"),t.find("select.redux-select-item").each((function(){var t={width:"resolve",triggerChange:!0,allowClear:!0};if("multiple"==e(this).attr("multiple")&&(t.width="100%"),e(this).siblings(".select2_params").size()>0){var s=e(this).siblings(".select2_params").val();s=JSON.parse(s),t=e.extend({},t,s)}e(this).hasClass("font-icons")&&(t=e.extend({},{formatResult:redux.field_objects.select.addIcon,formatSelection:redux.field_objects.select.addIcon,escapeMarkup:function(e){return e}},t)),e(this).select2(t),e(this).hasClass("select2-sortable")&&((t={}).bindOrder="sortableStop",t.sortableOptions={placeholder:"ui-state-highlight"},e(this).select2Sortable(t)),e(this).on("change",(function(){redux_change(e(e(this))),e(this).select2SortableOrder()}))})))}))},redux.field_objects.select.addIcon=function(e){if(e.hasOwnProperty("id"))return"<span class='elusive'><i class='"+e.id+"'></i>&nbsp;&nbsp;"+e.text+"</span>"}}(jQuery);