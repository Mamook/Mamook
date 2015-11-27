<?php /* application/js/fileOption-submit.php */

# Minified version.
$js='$(function(){for(var c=$("[class|=submit]").not("[value=Reset]"),b=[$("#fileOption"),$("#imageOption"),$("#institution"),$("#language"),$("#text_language"),$("#trans_language"),$("#publisher")],a=0;a<b.length;++a)b[a].change(function(){var a=$(this).val();("add"==a||"select"==a||"remove"==a)&&c.click()})});';

# Long version.
/*$js='
	// Wrap it all in the jQuery(document).ready method to ensure the internal variables and functions are not invasive to the page and so that this will execute after the document is loaded.
	$(function () {
		// Create a local variable to hold the submit button on the page.
		var submitButton = $("[class|=submit]").not("[value=Reset]");
		// Pass each element as an element of the elementArray to the "clickOnChange" method.
		clickOnChange([
			$("#fileOption"),
			$("#imageOption"),
			$("#institution"),
			$("#language"),
			$("#text_language"),
			$("#trans_language"),
			$("#publisher")
		]);

		// The clickOnChange method accepts an Array of jQuery Objects.
		function clickOnChange(elementArray) {
			// Loop through the jQuery Objects.
			for (var key=0; key<elementArray.length; ++key) {
				// Set the current jQuery Object to a local variable.
				var $element=elementArray[key];
				// Set a funtion to the onChange event for this element.
				$element.change(function () {
					var value = $(this).val()
					// Check if the value of the element on change was "add". If so, trigger the click of the submit button.
					if (value == "add" || value == "select" || value == "remove") {
						submitButton.click();
					}
				});
			}
		};
	});';*/