<?php /* framework/application/templates/forms/submit_email_form.php */

# Set the FormPopulator object from the FormProcessor data member to a variable.
$populator=$fp->getPopulator();
$user_object=$populator->getUserObject();

$display='<div id="submit_email_form" class="form">';
	# Create and display form.
	$display.=$head;
	# Instantiate the FormGenerator object.
	$fg=new FormGenerator('submit_email');
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="email">Email</label>');
$fg->addElement('text', array('name'=>'email', 'value'=>$user_object->getEmail(), 'id'=>'email'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('submit', array('name'=>'send', 'value'=>'Send Request'), '', NULL, 'submit-email');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
$display.='</div>';