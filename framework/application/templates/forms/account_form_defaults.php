<?php /* framework/application/templates/forms/account_form_defaults.php */

# Create defaults.
$account_address=NULL;
$account_address2=NULL;
$account_bio=NULL;
$account_city=NULL;
$account_country=NULL;
$account_cv=NULL;
$account_display_name=NULL;
$account_email=NULL;
$account_first_name=NULL;
$account_id=NULL;
$account_image_filename='default-avatar.png';
$account_image_title=NULL;
$account_interests=NULL;
$account_last_name=NULL;
$account_nickname=NULL;
$account_phone=NULL;
$account_state=NULL;
$account_title=NULL;
$account_username=NULL;
$account_website='http://';
$account_zipcode=NULL;

# Instantiate a new instance of the User class.
$user_obj=new User();

# Check if there is GET data called "user".
if(isset($_GET['user']))
{
	# Set the passed user ID to the User data member, effectively "cleaning" it.
	$user_obj->setID($_GET['user']);
}

# Get the user from the `users` table.
if($user_obj->findUserData($user_obj->getID())===TRUE)
{
	# Reset the defaults.
	$account_address=$user_obj->getAddress();
	$account_address2=$user_obj->getAddress2();
	$account_bio=$user_obj->getBio();
	$account_city=$user_obj->getCity();
	$account_country=$user_obj->getCountry();
	$account_cv=$user_obj->getCV();
	$account_display_name=$user_obj->getDisplayName();
	$account_email=$user_obj->getEmail();
	$account_first_name=$user_obj->getFirstName();
	$account_id=$user_obj->getID();
	$account_image_filename=$user_obj->getImg();
	$account_image_title=$user_obj->getImgTitle();
	$account_interests=$user_obj->getInterests();
	$account_last_name=$user_obj->getLastName();
	$account_phone=$user_obj->getPhone();
	$account_region=$user_obj->getRegion();
	$account_state=$user_obj->getState();
	$account_title=$user_obj->getTitle();
	$account_username=$user_obj->getUsername();
	$account_website=$user_obj->getWebsite();
	$account_zipcode=$user_obj->getZipcode();
	# Check if there is a WordPress installation.
	if(WP_INSTALLED===TRUE)
	{
		# Get the WordPressUser class.
		require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
		# Instantiate a new WordPressUser object.
		$wp_user=new WordPressUser();
		# get the WordPress User's ID.
		$wp_id=$wp_user->getWP_UserID($account_username);
		# Get the WordPress User's nickname.
		$wp_user->getWP_Nickname($wp_id);
		# Set the nickname from the DB to the global.
		$account_nickname=$wp_user->getNickname();
	}
}

# The key MUST be the name of a "set" mutator method in the Video class (ie setID).
$default_data=array(
		'Address'=>$account_address,
		'Address2'=>$account_address2,
		'Bio'=>$account_bio,
		'City'=>$account_city,
		'Country'=>$account_country,
		'CV'=>$account_cv,
		'DisplayName'=>$account_display_name,
		'Email'=>$account_email,
		'FirstName'=>$account_first_name,
		'ID'=>$account_id,
		'Img'=>$account_image_filename,
		'ImgTitle'=>$account_image_title,
		'Interests'=>$account_interests,
		'LastName'=>$account_last_name,
		'Nickname'=>$account_nickname,
		'Phone'=>$account_phone,
		'State'=>$account_state,
		'Title'=>$account_title,
		'Username'=>$account_username,
		'Website'=>$account_website,
		'Zipcode'=>$account_zipcode
	);