<?php /* framework/application/templates/forms/login_form_defaults.php */

# Create defaults.
$username=NULL;
$password=NULL;
$remember='';

# The key MUST be the name of a "set" mutator method in either the User, LoginFormPopulator, or FormPopulator classes (ie setUsername, setPassword).
$default_data=array(
	'Remember'=>$remember,
	'Password'=>$password,
	'Username'=>$username
);