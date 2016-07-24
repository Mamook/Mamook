<?php /* application/js/uniform-select.php */

$js='(function(a){a(function(){a("select.select").not("select[multiple]").uniform()})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("select.select").not("select[multiple]")
			.uniform();
	});
})(jQuery);
*/