<?php /* framework/application/templates/forms/account_form_defaults.php */

# Create defaults.
$staff_affiliation=NULL;
$staff_archive=NULL;
$staff_credentials=NULL;
$staff_first_name=NULL;
$staff_id=NULL;
$staff_image_filename='default-avatar.png';
$staff_image_title=NULL;
$staff_last_name=NULL;
$staff_middle_name=NULL;
$staff_new_position=NULL;
$staff_position=NULL;
$staff_region=NULL;
$staff_text=NULL;
$staff_title=NULL;
$staff_user=NULL;

# Get the user from the `users` table.
if($staff_obj->getThisStaff($staff_obj->getID())===TRUE)
{
	# Reset the defaults.
	$staff_affiliation=$staff_obj->getAffiliation();
	$staff_archive=$staff_obj->getArchive();
	$staff_credentials=$staff_obj->getCredentials();
	$staff_first_name=$staff_obj->getFirstName();
	$staff_id=$staff_obj->getID();
	$staff_image_filename=$staff_obj->getImage();
	$staff_image_title=$staff_obj->getImageTitle();
	$staff_last_name=$staff_obj->getLastName();
	$staff_middle_name=$staff_obj->getMiddleName();
	$staff_new_position=$staff_obj->getNewPosition();
	$staff_position=$staff_obj->getPosition();
	$staff_region=$staff_obj->getRegion();
	$staff_text=$staff_obj->getText();
	$staff_title=$staff_obj->getTitle();
	$staff_user=$staff_obj->getUser();
}

# The key MUST be the name of a "set" mutator method in the Video class (ie setID).
$default_data=array(
		'Affiliation'=>$staff_affiliation,
		'Archive'=>$staff_archive,
		'Credentials'=>$staff_credentials,
		'FirstName'=>$staff_first_name,
		'ID'=>$staff_id,
		'Image'=>$staff_image_filename,
		'ImageTitle'=>$staff_image_title,
		'LastName'=>$staff_last_name,
		'MiddleName'=>$staff_middle_name,
		'NewPosition'=>$staff_new_position,
		'Position'=>$staff_position,
		'Region'=>$staff_region,
		'Text'=>$staff_text,
		'Title'=>$staff_title,
		'User'=>$staff_user
	);