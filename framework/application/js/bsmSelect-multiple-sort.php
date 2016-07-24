<?php /* application/js/bsmSelect-multiple-sort.php */

$js='(function(a){a(function(){a("select[multiple]").bsmSelect({addItemTarget:"top",hideWhenAdded:!0,removeLabel:"X",animate:!0,highlight:!0,plugins:[a.bsmSelect.plugins.sortable({axis:"y",opacity:0.5},{listSortableClass:"bsmListSortableCustom"}),a.bsmSelect.plugins.compatibility()]})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("select[multiple]").bsmSelect({
			addItemTarget: "top",
			hideWhenAdded: true,
			removeLabel: "X",
			animate: true,
			highlight: true,
			plugins: [
				$.bsmSelect.plugins.sortable({ axis: "y", opacity: 0.5 }, { listSortableClass: "bsmListSortableCustom" }),
				$.bsmSelect.plugins.compatibility()
			]
		});
	});
})(jQuery);
*/