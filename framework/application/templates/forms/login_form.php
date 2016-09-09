<?php /* framework/application/templates/forms/login_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'login_form_defaults.php');
$fp->processLogin($default_data);

# Set the LoginFormPopulator object from the LoginFormProcessor data member to a variable.
$populator=$fp->getPopulator();
$user_object=$populator->getUserObject();
# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('uniform,bsmSelect');
# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
$doc->setFooterJS('uniform-select,fileOption-submit,bsmSelect-multiple'.((!isset($_GET['post'])) ? ',disable-social-checkboxes' : ''));

//$remember=($populator->getRemember()=='remember') ? 'checked' : '');

$login_form_display='<div id="login" class="form login">';
# Create and display form.
$login_form_display.='<h3 class="h-3">'.$head.'</h3>';

# Instantiate FormGenerator object.
$login_form=new FormGenerator('login', REDIRECT_TO_LOGIN);
$login_form->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$post_login=$login->getPostLogin();
if(!empty($post_login))
{
	$login_form->addElement('hidden', array('name'=>'_post_login', 'value'=>$post_login));
}
$login_form->addFormPart('<fieldset>');
$login_form->addFormPart('<ul>');
$login_form->addFormPart('<li>');
$login_form->addFormPart('<label class="label" for="username">Username</label>');
$login_form->addElement('text', array('name'=>'username', 'value'=>$user_object->getUsername(), 'id'=>'username'));
$login_form->addFormPart('</li>');
$login_form->addFormPart('<li>');
$login_form->addFormPart('<label class="label" for="password">Password</label>');
$login_form->addElement('password', array('name'=>'password', 'id'=>'password'));
$login_form->addFormPart('</li>');
//$login_form->addFormPart('<li class="remember">');
//$login_form->addElement('checkbox', array('name'=>'remember', 'value'=>'remember', 'checked'=>$remember));
//$login_form->addFormPart('<label for="remember">Remember Me</label>');
//$login_form->addFormPart('</li>');
$login_form->addFormPart('<li>');
$login_form->addElement('submit', array('name'=>'login', 'value'=>'Login'), '', NULL, 'submit-login');
$login_form->addFormPart('</li>');
$login_form->addFormPart('</ul>');
$login_form->addFormPart('</fieldset>');
$login_form->addFormPart('<a href="'.REDIRECT_TO_LOGIN.'LostPassword/" class="helper" title="I lost my password">I lost my password</a>');

$login_form_display.=$login_form->display();
# Clean update
$login_form=NULL;
$login_form_display.='</div>';
$display.=$login_form_display;