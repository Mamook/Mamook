<?php /* framework/application/templates/forms/change_username.php */

$display='<div id="change_username_form" class="form">';
# Create and display form
# Instantiate FormGenerator object
$fg=new FormGenerator('change_username');
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<ul>');
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="username">New Username</label>');
$fg->addElement('text', array('name'=>'username', 'value'=>$username, 'id'=>'username'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="confirmed_username">Confirm Username</label>');
$fg->addElement('text', array('name'=>'confirmed_username', 'id'=>'confirmed_username'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addElement('checkbox', array('name'=>'email_username', 'checked'=>$checked_value, 'id'=>'email_username'));
$fg->addFormPart('<label class="label-box" for="email_username">'.$email_username.'</label>');
$fg->addFormPart('</li>');
$fg->addFormPart('</fieldset>');
$fg->addFormPart('<li>');
$fg->addElement('submit', array('name'=>'send', 'value'=>'Change Username'),'' , NULL, 'submit-profile');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$display.=$fg->display();
$display.='</div>';