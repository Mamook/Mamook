<?php /* application/js/product.php */

$js='(function(a){a(function(){function c(b){b=a(b).val();a("#amazon").toggle("amazon"==b);a("#external").toggle("external"==b);a("#internal").toggle("internal"==b)}c(a(".audio_type_radio:checked"));a(".product_type_radio").click(function(){c(this)})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		checkFields($('.audio_type_radio:checked'));
		$('.product_type_radio').click(function () {
			checkFields(this);
		});
		function checkFields(selector) {
			var value=$(selector).val();
			$('#amazon').toggle(value == 'amazon');
			$('#external').toggle(value == 'external');
			$('#internal').toggle(value == 'internal');
		};
	});
})(jQuery);
*/