<?php /* application/js/bsmSelect-multiple.php */
$js='(function(a){a(function(){a("select[multiple]").bsmSelect({addItemTarget:"original",hideWhenAdded:!1,removeLabel:"X",animate:!0,plugins:[a.bsmSelect.plugins.compatibility()],containerClass:"bsm",selectClass:"select",listClass:"bsm-list",listItemClass:"bsm-item",listItemLabelClass:"bsm-label",removeClass:"remove"});a(".remove").attr({title:"Remove This"});a("select[multiple]").change(function(){a(".remove").attr({title:"Remove This"})})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("select[multiple]").bsmSelect({
			addItemTarget: "original",
			hideWhenAdded: false,
			removeLabel: "X",
			animate: true,
			plugins: [$.bsmSelect.plugins.compatibility()],
			// Class for container that wraps this widget
			containerClass: "bsm",
			// Class for the <select>
			selectClass: "select",
			// Class for the list (ol)
			listClass: "bsm-list",
			// Class for the <li> list items
			listItemClass: "bsm-item",
			// Class for the label text that appears in list items
			listItemLabelClass: "bsm-label",
			// Class given to the "remove" link
			removeClass: "remove"
		});
		$(".remove").attr({ title:"Remove This" });
		$("select[multiple]").change(function () {
			$(".remove").attr({ title:"Remove This" })
		});
	});
})(jQuery);
*/