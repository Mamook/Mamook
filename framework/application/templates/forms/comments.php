<?php /* framework/application/templates/forms/comments.php */

	# Instantiate FormGenerator object.
	$fg=new FormGenerator('comments_form', $url);
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label for="message">Message</label>');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('textarea', array('name'=>'message'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('image', array('name'=>'send', 'value'=>'Send'), '', THEME.'images/transparent.dot.png');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');

	$display.=$fg->display();

?>
