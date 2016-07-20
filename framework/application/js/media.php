<?php /* application/js/media.php */

$js='(function(a){a(function(){a(".arrow-prev").addClass("disabled");var b=a(".feed-video > li").size(),c=a(".feed-audio > li").size();4>b&&7>c&&a(".arrow-next").addClass("disabled")})})(jQuery);';


/* Big
	// Wrap the script to protect the global namespace.
	(function($){
			// Wait for document ready.
			$(function(){
					// Initially add the "disabled" class to the "previous" button.
					$(".arrow-prev").addClass("disabled");
					var numberOfVideos = $(".feed-video > li").size();
					var numberOfAudioFiles = $(".feed-audio > li").size();
					if(numberOfVideos<4 && numberOfAudioFiles<7) {
							$(".arrow-next").addClass("disabled");
					}
			})
	})(jQuery);*/