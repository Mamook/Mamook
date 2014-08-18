<?php /* templates/forms/delete.php */

$who='my';
$which='this';
if(isset($current_username))
{
	$who=$current_username.'\'s';
	$which=$current_username.'\'s';
}

$display_delete_form='<div id="delete_form" class="form">';
	# create and display form
	$display_delete_form.='<h2>Are you sure you want to delete '.$which.' account? (It will be permanently removed from the system)</h2>';
	# instantiate form generator object
	$fg=new FormGenerator('delete');
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<li>');
	$fg->addElement('checkbox',array('name'=>'delete', 'id'=>'delete'));
	$fg->addFormPart('<label class="label-box" for="delete">Yes, delete '.$who.' account</label>');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</fieldset>');
	$fg->addFormPart('<li>');
	$fg->addElement('submit',array('name'=>'delete', 'value'=>'Delete Account'), '', NULL, 'submit-delete');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$display_delete_form.=$fg->display();
$display_delete_form.='</div>';