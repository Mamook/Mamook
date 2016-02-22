<?php /* framework/templates/forms/change_password.php */

$display='<div id="change_password_form" class="form">';
# Create and display form
# Instantiate FormGenerator object
$fg=new FormGenerator('change_password');
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<ul>');
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="password">New Password</label>');
$fg->addElement('password', array('name'=>'password', 'id'=>'password'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="confirmed_password">Confirm Password</label>');
$fg->addElement('password', array('name'=>'confirmed_password', 'id'=>'confirmed_password'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addElement('checkbox', array('name'=>'email_password', 'checked'=>$checked_value, 'id'=>'email_password'));
$fg->addFormPart('<label class="label-box" for="email_password">'.$email_password.'</label>');
$fg->addFormPart('</li>');
$fg->addFormPart('</fieldset>');
$fg->addFormPart('<li>');
$fg->addElement('submit', array('name'=>'send', 'value'=>'Change Password'), '', NULL, 'submit-profile');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$display.=$fg->display();
$display.='</div>';