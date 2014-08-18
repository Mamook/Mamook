<?php /* application/js/media.php */

$js='$(\'.arrow-prev\').addClass(\'disabled\');'.
	'$(function(){'.
		'c=$(\'.video-feed > li\').size();'.
		'if(c<4){'.
			'$(\'.arrow-next\').addClass(\'disabled\');'.
		'}'.
	'});';