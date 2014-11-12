<?php /* application/js/media.php */

$js='$(".arrow-prev").addClass("disabled");'.
	'$(function(){'.
		'c=$(".video-feed > li").size();'.
		'd=$(".audio-feed > li").size();'.
		'if(c<4 && d<7)'.
			'$(".arrow-next").addClass("disabled");'.
	'});';