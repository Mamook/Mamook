<?php /* application/js/media.php */

$js='$(".arrow-prev").addClass("disabled");'.
	'var c=$(".feed-video > li").size(),'.
	'd=$(".feed-audio > li").size();'.
	'if(c<4 && d<7)'.
		'$(".arrow-next").addClass("disabled");';

/* Big
	// Initially add the "disabled" class to the "previous" button.
	$(".arrow-prev").addClass("disabled");
	var numberOfVideos=$(".video-feed > li").size();
	var numberOfAudioFiles=$(".audio-feed > li").size();
	if(numberOfVideos<4 && numberOfAudioFiles<7)
		$(".arrow-next").addClass("disabled");
*/