<?php /* application/js/removeCurrentItem.php */

# Minified version.
$js='(function(a){a(function(){a(".file-current .remove").click(function(){var b=a(this).closest(".file-current"),c=a(this).prev("input").clone();c.attr({name:c.attr("name")+"_remove"}).appendTo(b.closest("li"));b.remove();return!1})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		// Create a local variable to hold the "remove" button. When it is clicked, remove the parent element with the class "file-current".
		$(".file-current .remove").click(function(){
			var currentFileContainer = $(this).closest(".file-current");
			// Clone the hidden input.
			var hidden = $(this).prev("input").clone();
			hidden.attr({name:hidden.attr("name") + "_remove"})
				.appendTo(currentFileContainer.closest("li"));
			currentFileContainer.remove();
			return false;
		});
	});
})(jQuery);
*/