<?php /* application/js/video.php */

$js='(function(a){a(function(){function c(b){b=b.val();a("#embed").toggle("embed"==b);a("#file").toggle("file"==b)}c(a(".video_type_radio:checked"));a(".video_type_radio").click(function(){c(this)})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		checkFields($('.video_type_radio:checked'));
		$('.video_type_radio').click(function () {
			checkFields(this)
		});
		function checkFields($jQueryElement) {
			var value = $jQueryElement.val();
			$('#embed').toggle(value == 'embed');
			$('#file').toggle(value == 'file');
		};
	});
})(jQuery);
*/