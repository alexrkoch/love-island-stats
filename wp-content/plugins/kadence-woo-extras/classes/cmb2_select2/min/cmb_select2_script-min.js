!function($){"use strict";$(".pw_select").each(function(){$(this).select3({allowClear:!0})}),$.fn.extend({select3_sortable:function(){var e=$(this);$(e).select3();var t=$(e).next(".select3-container").first("ul.select3-selection__rendered");t.sortable({containment:"parent",items:"li:not(.select3-search--inline)",tolerance:"pointer",stop:function(){$($(t).find(".select3-selection__choice").get().reverse()).each(function(){var t=$(this).data("data").id,c=e.find('option[value="'+t+'"]')[0];$(e).prepend(c)})}})}}),$(".pw_multiselect").each(function(){$(this).select3_sortable()}),$(".cmb-repeatable-group").on("cmb2_add_group_row_start",function(e,t){var c=$(document.getElementById($(t).data("selector"))),n=c.find(".cmb-repeatable-grouping").last();n.find(".pw_select3").each(function(){$(this).select3("destroy")})}),$(".cmb-repeatable-group").on("cmb2_add_row",function(e,t){$(t).find(".pw_select").each(function(){$("option:selected",this).removeAttr("selected"),$(this).select3({allowClear:!0})}),$(t).find(".pw_multiselect").each(function(){$("option:selected",this).removeAttr("selected"),$(this).select3_sortable()}),$(t).prev().find(".pw_select").each(function(){$(this).select3({allowClear:!0})}),$(t).prev().find(".pw_multiselect").each(function(){$(this).select3_sortable()})}),$(".cmb-repeatable-group").on("cmb2_shift_rows_start",function(e,t){var c=$(t).closest(".cmb-repeatable-group");c.find(".pw_select3").each(function(){$(this).select3("destroy")})}),$(".cmb-repeatable-group").on("cmb2_shift_rows_complete",function(e,t){var c=$(t).closest(".cmb-repeatable-group");c.find(".pw_select").each(function(){$(this).select3({allowClear:!0})}),c.find(".pw_multiselect").each(function(){$(this).select3_sortable()})}),$(".cmb-add-row-button").on("click",function(e){var t=$(document.getElementById($(e.target).data("selector"))),c=t.find(".cmb-row").last();c.find(".pw_select3").each(function(){$(this).select3("destroy")})}),$(".cmb-repeat-table").on("cmb2_add_row",function(e,t){$(t).prev().find(".pw_select").each(function(){$("option:selected",this).removeAttr("selected"),$(this).select3({allowClear:!0})}),$(t).prev().find(".pw_multiselect").each(function(){$("option:selected",this).removeAttr("selected"),$(this).select3_sortable()})})}(jQuery);