<?php /* templates/forms/login.php */

$display.='<div id="login" class="login form">';
# Create and display form.
$display.=$head;
# Instantiate FormGenerator object.
$login_form=new FormGenerator('login', REDIRECT_TO_LOGIN);
$login_form->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$pl=$login->getPostLogin();
if(!empty($pl))
{
	$login_form->addElement('hidden', array('name'=>'_post_login', 'value'=>$pl));
}
$login_form->addFormPart('<fieldset>');
$login_form->addFormPart('<ul>');
$login_form->addFormPart('<li>');
$login_form->addFormPart('<label class="label" for="username">Username</label>');
$login_form->addElement('text', array('name'=>'username', 'value'=>((isset($_POST['username'])) ? $db->sanitize($_POST['username'], 2) : ''), 'id'=>'username'));
$login_form->addFormPart('</li>');
$login_form->addFormPart('<li>');
$login_form->addFormPart('<label class="label" for="password">Password</label>');
$login_form->addElement('password', array('name'=>'password', 'id'=>'password'));
$login_form->addFormPart('</li>');
//$login_form->addFormPart('<li class="remember">');
//$login_form->addElement('checkbox', array('name'=>'remember', 'checked'=>((isset($_POST['remember'])) ? 'checked' : '')));
//$login_form->addFormPart('<label for="remember">Remember Me</label>');
//$login_form->addFormPart('</li>');
$login_form->addFormPart('<li>');
$login_form->addElement('submit', array('name'=>'login', 'value'=>'Login'), '', NULL, 'submit-login');
$login_form->addFormPart('</li>');
$login_form->addFormPart('</ul>');
$login_form->addFormPart('</fieldset>');
$login_form->addFormPart('<a href="'.REDIRECT_TO_LOGIN.'LostPassword/" class="helper" title="I lost my password">I lost my password</a>');
$display.=$login_form->display();
# Clean update
$login_form=NULL;
$display.='</div>';