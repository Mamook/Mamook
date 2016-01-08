<?php /* framework/application/templates/forms/delete.php */

$display='<div id="delete_form" class="form">';
# create and display form
$display.=$head;
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
$display.=$fg->display();
$display.='</div>';