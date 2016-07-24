<?php /* application/js/scripts-wordpress.php */

$js='(function(a){a(function(){clearInput("#s","value");a("#categories .submit-view").remove();a("#categories").css("margin-bottom",0);a("#categories select").change(function(){a("#categories").submit()});a("li.rss").hover(function(){a(this).find("img").fadeTo(0,0.7)},function(){a(this).find("img").fadeTo(0,1)})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		clearInput("#s", "value");
		$("#categories .submit-view").remove();
		$("#categories").css("margin-bottom", 0);
		$("#categories select").change(function () {
			$("#categories").submit();
		});
		$("li.rss").hover(function (e) {
			$(this).find("img").fadeTo(0, .7)
		}, function () {
			$(this).find("img").fadeTo(0, 1)
		});
	});
})(jQuery);
*/