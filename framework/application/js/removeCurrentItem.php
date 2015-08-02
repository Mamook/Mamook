<?php /* application/js/removeCurrentItem.php */

# Minified version.
$js='$(function(){$(".file-current .remove").click(function(){var a=$(this).closest(".file-current"),b=$(this).prev("input").clone();b.attr({name:b.attr("name")+"_remove"}).appendTo(a.closest("li"));a.remove();return!1})});';

# Long version.
/*$js='// Wrap it all in the jQuery(document).ready method to ensure the internal variables and functions are not invasive to the page and so that this will execute after the document is loaded.
	$(function(){
		// Create a local variable to hold the "remove" button. When it is clicked, remove the parent element with the class "file-current".
		var removeButton = $(".file-current .remove")
			.click(function(){
				var currentFileContainer = $(this).closest(".file-current");
				// Clone the hidden input.
				var hidden = $(this).prev("input").clone();
				hidden.attr({name:hidden.attr("name") + "_remove"})
					.appendTo(currentFileContainer.closest("li"));
				currentFileContainer.remove();
				return false;
			});
	});';*/