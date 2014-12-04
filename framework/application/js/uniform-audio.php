<?php /* application/js/uniform-audio.php */

global $max_file_size;
# Set the Validator instance to a variable.
$validator=Validator::getInstance();
$max_size='314572800';
if($validator->isInt($max_file_size)===TRUE)
{
	$max_size=$max_file_size;
}
$js='$(function(){$(\'input.file\').uniform()});'.
	'$(\'input.file\').bind(\'change\', function(){'.
		'size=this.files[0].size;'.
		'if(size>'.$max_size.'){'.
			'alert(\'Please try a different audio\', \'The audio you are attampting to attach is too large (\'+size+\'). The audio must be smaller than '.($max_size/1024/1024).'MB.\')'.
		'}'.
	'});';