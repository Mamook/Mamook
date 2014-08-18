<?php /* templates/forms/account_form_defaults.php */

# Create defaults.
$account_address=NULL;
$account_address2=NULL;
$account_affiliation=NULL;
$account_archive=NULL;
$account_bio=NULL;
$account_city=NULL;
$account_country=NULL;
$account_credentials=NULL;
$account_cv=NULL;
$account_display_name=NULL;
$account_email=NULL;
$account_first_name=NULL;
$account_id=NULL;
$account_image_filename='default-avatar.png';
$account_image_title=NULL;
$account_interests=NULL;
$account_last_name=NULL;
$account_middle_name=NULL;
$account_new_position=NULL;
$account_nickname=NULL;
$account_phone=NULL;
$account_position=NULL;
$account_region=NULL;
$account_staff_id=NULL;
$account_state=NULL;
$account_title=NULL;
$account_username=NULL;
$account_website='http://';
$account_zipcode=NULL;

# Check if there is GET data called "user".
if(isset($_GET['user']))
{
	# Instantiate a new instance of the Staff class.
	$staff_obj=new Staff();
	# Set the passed user ID to the Video data member, effectively "cleaning" it.
	$staff_obj->setID($_GET['user']);

### DRAVEN: GO GO HACKS! ###
	//if(!isset($_SESSION['form']['account']))
	//{
		# Get the user from the `users` table.
		if($staff_obj->findUserData($staff_obj->getID())===TRUE)
		{
			# Reset the defaults.
			$account_address=$staff_obj->getAddress();
			$account_address2=$staff_obj->getAddress2();
			$account_affiliation=$staff_obj->getAffiliation();
			$account_archive=$staff_obj->getArchive();
			$account_bio=$staff_obj->getBio();
			$account_city=$staff_obj->getCity();
			$account_country=$staff_obj->getCountry();
			$account_credentials=$staff_obj->getCredentials();
			$account_cv=$staff_obj->getCV();
			$account_display_name=$staff_obj->getDisplayName();
			$account_email=$staff_obj->getEmail();
			$account_first_name=$staff_obj->getFirstName();
			$account_id=$staff_obj->getID();
			$account_image_filename=$staff_obj->getImg();
			$account_image_title=$staff_obj->getImgTitle();
			$account_interests=$staff_obj->getInterests();
			$account_last_name=$staff_obj->getLastName();
			$account_middle_name=$staff_obj->getStaffMiddleName();
			$account_phone=$staff_obj->getPhone();
			$account_new_position=$staff_obj->getNewPosition();
			$account_position=$staff_obj->getPosition();
			$account_region=$staff_obj->getRegion();
			$account_staff_id=$staff_obj->getStaffID();
			$account_state=$staff_obj->getState();
			$account_title=$staff_obj->getTitle();
			$account_username=$staff_obj->getUsername();
			$account_website=$staff_obj->getWebsite();
			$account_zipcode=$staff_obj->getZipcode();
			# Check if there is a WordPress installation.
			if(WP_INSTALLED===TRUE)
			{
				# Get the WordPressUser class.
				require_once MODULES.'User'.DS.'WordPressUser.php';
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
	/*
	}
	else
	{
		$account_address=$_SESSION['form']['account']['Address'];
		$account_address2=$_SESSION['form']['account']['Address2'];
		$account_affiliation=$_SESSION['form']['account']['Affiliation'];
		$account_archive=$_SESSION['form']['account']['Archive'];
		$account_bio=$_SESSION['form']['account']['Bio'];
		$account_city=$_SESSION['form']['account']['City'];
		$account_country=$_SESSION['form']['account']['Country'];
		$account_credentials=$_SESSION['form']['account']['Credentials'];
		//$account_cv=$_SESSION['form']['account']['CV'];
		$account_display_name=$_SESSION['form']['account']['DisplayName'];
		$account_email=$_SESSION['form']['account']['Email'];
		$account_first_name=$_SESSION['form']['account']['FirstName'];
		$account_id=$_SESSION['form']['account']['ID'];
		$account_image_filename=$_SESSION['form']['account']['Img'];
		$account_image_title=$_SESSION['form']['account']['ImgTitle'];
		$account_interests=$_SESSION['form']['account']['Interests'];
		$account_last_name=$_SESSION['form']['account']['LastName'];
		$account_middle_name=$_SESSION['form']['account']['StaffMiddleName'];
		$account_phone=$_SESSION['form']['account']['Phone'];
		$account_new_position=$_SESSION['form']['account']['NewPosition'];
		if(!isset($_SESSION['form']['account_desc']))
		{
			$account_position=$_SESSION['form']['account']['Position'];
		}
		else
		{
			$account_position=$_SESSION['form']['account_desc']['Position'];
		}
		$account_region=$_SESSION['form']['account']['Region'];
		$account_staff_id=$_SESSION['form']['account']['StaffID'];
		$account_state=$_SESSION['form']['account']['State'];
		$account_title=$_SESSION['form']['account']['Title'];
		$account_username=$_SESSION['form']['account']['Username'];
		$account_website=$_SESSION['form']['account']['Website'];
		$account_zipcode=$_SESSION['form']['account']['Zipcode'];
		# Check if there is a WordPress installation.
		if(WP_INSTALLED===TRUE)
		{
			$account_nickname=$_SESSION['form']['account']['Nickname'];
		}
	}
	*/
}
else
{
	# Instantiate a new instance of the Staff class.
	$user_obj=new User();
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
			require_once MODULES.'User'.DS.'WordPressUser.php';
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
}

# The key MUST be the name of a "set" mutator method in the Video class (ie setID).
$default_data=array(
		'Address'=>$account_address,
		'Address2'=>$account_address2,
		'Affiliation'=>$account_affiliation,
		'Archive'=>$account_archive,
		'Bio'=>$account_bio,
		'City'=>$account_city,
		'Country'=>$account_country,
		'Credentials'=>$account_credentials,
		'CV'=>$account_cv,
		'DisplayName'=>$account_display_name,
		'Email'=>$account_email,
		'FirstName'=>$account_first_name,
		'ID'=>$account_id,
		'Img'=>$account_image_filename,
		'ImgTitle'=>$account_image_title,
		'Interests'=>$account_interests,
		'LastName'=>$account_last_name,
		'StaffMiddleName'=>$account_middle_name,
		//'NewPosition'=>$account_new_position,
		'Nickname'=>$account_nickname,
		'Phone'=>$account_phone,
		'Position'=>$account_position,
		'Region'=>$account_region,
		'StaffID'=>$account_staff_id,
		'State'=>$account_state,
		'Title'=>$account_title,
		'Username'=>$account_username,
		'Website'=>$account_website,
		'Zipcode'=>$account_zipcode
	);