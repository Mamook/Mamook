<?php /* templates/forms/register.php */

$display_form='<div id="register" class="form">';
# Create and display form.
$display_form.=$head;
# Instantiate FormGenerator object.
$register=new FormGenerator('register', REDIRECT_TO_LOGIN.'register/');
$register->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$register->addElement('hidden', array('name'=>'_reg', 'value'=>'1'));
if($login->getPostLogin() !== NULL)
{
	$register->addElement('hidden', array('name'=>'_post_login', 'value'=>$login->getPostLogin()));
}
$register->addFormPart('<fieldset>');
$register->addFormPart('<ul class="reg">');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="username">Username</label>');
$register->addElement('text', array('name'=>'username', 'value'=>$username, 'id'=>'username'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="email">Email</label>');
$register->addElement('text', array('name'=>'email', 'value'=>$email, 'id'=>'email'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="email_conf">Confirm Email</label>');
$register->addElement('text', array('name'=>'email_conf', 'value'=>$email_conf, 'id'=>'email_conf'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="password">Password</label>');
$register->addElement('password', array('name'=>'password', 'id'=>'password'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="password_conf">Confirm Password</label>');
$register->addElement('password', array('name'=>'password_conf', 'id'=>'password_conf'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label for="recaptcha_response_field" class="label"><a href="http://www.google.com/recaptcha/intro/index.html" title="What is reCaptcha?" target="_blank">reCaptch<span>?</span></a></label>');
$register->addFormPart('<div class="reCaptcha">');
$register->addFormPart($register->reCaptchaGetHTML(PUBLICKEY, $login->getReCaptchaError(), TRUE));
$register->addFormPart('</div>');
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addElement('submit', array('name'=>'register', 'value'=>'Register'), '', NULL, 'submit-login');
$register->addFormPart('</li>');
$register->addFormPart('</ul>');
$register->addFormPart('</fieldset>');
$display_form.=$register->display();
# Clean $register.
$register=NULL;
$display_form.='</div>';