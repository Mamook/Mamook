<?php /* framework/application/templates/forms/submit_email_form_defaults.php */

# Create defaults.
$email=NULL;

# The key MUST be the name of a "set" mutator method in either the User, LostPasswordFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
	'Email'=>$email
);