<?php /* application/js/disable-social-checkboxes.php */

$js='(function(a){a(function(){a("[id|=visibility]").change(function(){this.checked?(a("#facebook").attr({checked:!1,disabled:"disabled"}),a("#twitter").attr({checked:!1,disabled:"disabled"})):(a("#facebook").removeAttr("disabled"),a("#twitter").removeAttr("disabled"))});a("#visibility-all_users").change(function(){this.checked&&(a("#facebook").removeAttr("disabled"),a("#twitter").removeAttr("disabled"))})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("[id|=visibility]").change(function () {
			if (this.checked) {
				$("#facebook").attr({ checked: false, disabled: "disabled" });
				$("#twitter").attr({ checked: false, disabled: "disabled" });
			} else {
				$("#facebook").removeAttr("disabled");
				$("#twitter").removeAttr("disabled");
			}
		});
		$("#visibility-all_users").change(function () {
			if (this.checked) {
				$("#facebook").removeAttr("disabled");
				$("#twitter").removeAttr("disabled");
			}
		});
	});
})(jQuery);
*/