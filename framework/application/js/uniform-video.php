<?php /* application/js/uniform-video.php */

global $max_file_size;
# Set the Validator instance to a variable.
$validator=Validator::getInstance();
$max_size='314572800';
if($validator->isInt($max_file_size)===TRUE)
{
	$max_size=$max_file_size;
}
$js='(function(a){a(function(){a("input.file").uniform();a("input.file").bind("change",function(){var a=this.files[0].size;".$max_size."<a&&alert("Please try a different video","The video you are attampting to attach is too large ("+a+"). The video must be smaller than '.($max_size/1024/1024).'MB.")})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("input.file").uniform();
		$("input.file").bind("change", function () {
			var size = this.files[0].size;
			if (size>'.$max_size.') {
				alert("Please try a different video", "The video you are attampting to attach is too large ("+size+"). The video must be smaller than '.($max_size/1024/1024).'MB.");
			}
		});
	});
})(jQuery);
*/