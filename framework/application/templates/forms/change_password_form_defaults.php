<?php /* framework/application/templates/forms/change_password_form_defaults.php */

# Create defaults.
$password=NULL;
$password_confirmed=NULL;
$email_password=NULL; # Set the default to NULL

# The key MUST be the name of a "set" mutator method in either the User, PasswordFormPopulator, or FormPopulator classes (ie setEmailPassword, setPassword).
$default_data=array(
    'Password'=>$password,
    'PasswordConfirmed'=>$password_confirmed,
    'EmailPassword'=>$email_password
);