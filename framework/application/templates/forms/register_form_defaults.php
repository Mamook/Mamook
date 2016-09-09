<?php /* framework/application/templates/forms/register_form_defaults.php */

# Create defaults.
$username=NULL;
$email=NULL;
$email_conf=NULL;
$password=NULL;
$password_conf=NULL;

# The key MUST be the name of a "set" mutator method in either the User, RegisterFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
	'Username'=>$username,
	'Email'=>$email,
	'EmailConf'=>$email_conf,
	'Password'=>$password,
	'PasswordConf'=>$password_conf
);