<?php /* application/js/scripts-wordpress.php */

$js='$(function(){clearInput(\'#s\',\'value\')});'.
	'$(\'#categories .submit-view\').remove();'.
	'$(\'#categories\').css(\'margin-bottom\',0);'.
	'$(\'#categories select\').change(function(){$(\'#categories\').submit()});'.
	'$(\'li.rss\').hover(function(e){$(this).find(\'img\').fadeTo(0, .7)},function(){$(this).find(\'img\').fadeTo(0, 1)});';