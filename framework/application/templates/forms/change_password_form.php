<?php /* framework/templates/forms/change_password_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'change_password_form_defaults.php');
$fp->processPassword($default_data);

# Set the CategoryFormPopulator object from the CategoryFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the User object from the PasswordFormPopulator data member to a variable.
$user_obj=$populator->getUserObject();

# Create a default value for the $email_password_label variable.
if(empty($email_password_label))
{
    $email_password_label='Email me my password';
}

$email_password_value='off';
# Check if the "email password" was checked before.
if($fp->email_password=='checked')
{
    $email_password_value=$fp->email_password;
}

$display.='<div id="change_password_form" class="form">';
$display.=$head;
# Create and display form
# Instantiate a new FormGenerator object.
$fg=new FormGenerator('change_password', $fp->getFormAction(), 'POST', '_top', TRUE);
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<ul>');
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="password">New Password</label>');
$fg->addElement('password', array('name'=>'password', 'id'=>'password'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="password_confirmed">Confirm Password</label>');
$fg->addElement('password', array('name'=>'password_confirmed', 'id'=>'password_confirmed'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addElement('checkbox', array('name'=>'email_password', 'checked'=>$fp->email_password, 'value'=>$email_password_value, 'id'=>'email_password'));
$fg->addFormPart('<label class="label-box" for="email_password">'.$email_password_label.'</label>');
$fg->addFormPart('</li>');
$fg->addFormPart('</fieldset>');
$fg->addFormPart('<li>');
$fg->addElement('submit', array('name'=>'send', 'value'=>'Change Password'), '', NULL, 'submit-profile');
$fg->addElement('submit', array('name'=>'send', 'value'=>'Reset'), '', NULL, 'submit-reset');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$display.=$fg->display();
$display.='</div>';