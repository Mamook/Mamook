<?php /* application/js/audio.php */

$js='(function(a){a(function(){function c(b){b=a(b).val();a("#embed").toggle("embed"==b);a("#file").toggle("file"==b)}c(a(".audio_type_radio:checked"));a(".audio_type_radio").click(function(){c(this)})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		checkFields($('.audio_type_radio:checked'));
		$('.audio_type_radio').click(function () {
			checkFields(this);
		});
		function checkFields(selector) {
			var value=$(selector).val();
			$('#embed').toggle(value == 'embed');
			$('#file').toggle(value == 'file');
		};
	});
})(jQuery);
*/