<?php /* framework/application/templates/forms/email_users_form_defaults.php */

$sender_email=NULL;
$sender_name=NULL;

if($login->isLoggedIn()===TRUE)
{
	$login->findUserData();
	$sender_email=$login->findEmail();
	$sender_name=$login->findDisplayName();
}

# The key MUST be the name of a "set" mutator method in the Email class (ie setMessage).
$default_data=array(
	'Attachment'=>NULL,
	'ConfirmationTemplate'=>Utility::locateFile(TEMPLATES.'fm'.DS.'confirmation_template.php'),
	'EmailPage'=>'email_users',
	'Message'=>NULL,
	'SenderEmail'=>$sender_email,
	'SenderName'=>$sender_name,
	'Subject'=>NULL,
	'Template'=>Utility::locateFile(TEMPLATES.'fm'.DS.'email_users_template.php')
	);