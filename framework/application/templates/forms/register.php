<?php /* templates/forms/register.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'register_form_defaults.php');
$delete_form_display=$fp->processRegistration($default_data);

# Do we need some javascripts? (Use the script content name before the ".js".)
$doc->setJavaScripts('uniform,bsmSelect');
# Do we need some JavaScripts in the footer? (Use the script content name before the ".php".)
$doc->setFooterJS('uniform-select,fileOption-submit');

$display='<div id="register" class="register form">';
# Create and display form.
$display.=$head;
# Instantiate FormGenerator object.
$register=new FormGenerator('register', $fp->getFormAction());
$register->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$register->addElement('hidden', array('name'=>'_reg', 'value'=>'1'));
if($login->getPostLogin() !== NULL)
{
	$register->addElement('hidden', array('name'=>'_post_login', 'value'=>$login->getPostLogin()));
}
$register->addFormPart('<fieldset>');
$register->addFormPart('<ul class="reg">');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="username">Username:</label>');
$register->addElement('text', array('name'=>'username', 'value'=>$login->getUsername(), 'id'=>'username'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="email">Email:</label>');
$register->addElement('email', array('name'=>'email', 'value'=>$login->getEmail(), 'id'=>'email'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="email_conf">Confirm Email</label>');
$register->addElement('email', array('name'=>'email_conf', 'value'=>$login->getEmailConf(), 'id'=>'email_conf'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="password">Password</label>');
$register->addElement('password', array('name'=>'password', 'id'=>'password'));
$register->addFormPart('</li>');
$register->addFormPart('<li>');
$register->addFormPart('<label class="label" for="password_conf">Confirm Password</label>');
$register->addElement('password', array('name'=>'password_conf', 'id'=>'password_conf'));
$register->addFormPart('</li>');
if(CAPTCHA_PUBLICKEY!='' && CAPTCHA_PRIVATEKEY!='')
{
	$register->addFormPart('<li>');
	$register->addFormPart('<label for="recaptcha_response_field" class="label"><a href="http://www.google.com/recaptcha/intro/index.html" title="What is reCaptcha?" target="_blank">reCaptch<span>?</span></a></label>');
	$register->addFormPart('<div class="reCaptcha">');
	$register->addFormPart($register->reCaptchaGetHTML(CAPTCHA_PUBLICKEY, $login->getReCaptchaError(), TRUE));
	$register->addFormPart('</div>');
	$register->addFormPart('</li>');
}
$register->addFormPart('<li>');
$register->addElement('submit', array('name'=>'register', 'value'=>'Register'), '', NULL, 'submit-login');
$register->addFormPart('</li>');
$register->addFormPart('</ul>');
$register->addFormPart('</fieldset>');
$display.=$register->display();
# Clean $register.
$register=NULL;
$display.='</div>';