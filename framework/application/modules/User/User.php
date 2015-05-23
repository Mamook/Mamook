<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * User
 *
 * The User class is used to access and manipulate data in the `users` table.
 *
 */
class User
{
	/*** data members ***/

	protected $active=NULL;
	protected $address=NULL;
	protected $address2=NULL;
	protected $affiliation=NULL;
	protected $all_subscriptions=NULL;
	protected $all_users;
	protected $archive=NULL;
	protected $bio=NULL;
	protected $city=NULL;
	protected $country=NULL;
	protected $credentials=NULL;
	protected $cv=NULL;
	protected $display_name=NULL;
	protected $email=NULL;
	protected $fname=NULL;
	protected $id=NULL;
	protected $img=NULL;
	protected $img_title=NULL;
	protected $interests=NULL;
	protected $ip=NULL;
	protected $last_login='0000-00-00';
	protected $level=NULL;
	protected $lname=NULL;
	protected $mname=NULL;
	//protected $new_position=NULL;
	protected $newsletter;
	protected $nickname=NULL;
	protected $notify=NULL;
	protected $organization=NULL;
	protected $password=NULL;
	protected $phone=NULL;
	protected $position=NULL;
	protected $product=NULL;
	protected $questions=NULL;
	protected $region=NULL;
	protected $registered='0000-00-00';
	protected $staff=NULL;
	protected $staff_id=NULL;
	protected $state=NULL;
	protected $title=NULL;
	protected $username=NULL;
	protected $website=NULL;
	protected $zipcode=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id (The User's ID number.)
	 * @access	public
	 */
	public function setID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the value is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Make sure the id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			else
			{
				throw new Exception('The id passed was not a number!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->id=$id;
	} #==== End -- setID

	/**
	 * setStaff
	 *
	 * Sets the data member $staff.
	 *
	 * @param		$object (A Staff object.)
	 * @access	public
	 */
	protected function setStaff($object)
	{
		# Check if the value is empty.
		if(empty($object))
		{
			# Explicitly set it to NULL.
			$object=NULL;
		}
		# Set it to the data member.
		$this->staff=$object;
	} #==== End -- setStaff

	/**
	 * setStaffID
	 *
	 * Sets the data member $staff_id.
	 *
	 * @param	$staff_id				The User's Staff ID number.
	 * @access	protected
	 */
	protected function setStaffID($staff_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the value is empty.
		if(!empty($staff_id))
		{
			# Clean it up.
			$staff_id=trim($staff_id);
			# Make sure the staff id is an integer.
			if($validator->isInt($staff_id)===TRUE)
			{
				# Explicitly make it an integer.
				$staff_id=(int)$staff_id;
			}
			else
			{
				throw new Exception('The staff id passed was not a number!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$staff_id=NULL;
		}
		# Set the data member.
		$this->staff_id=$staff_id;
	} #==== End -- setStaffID

	/**
	 * setAffiliation
	 *
	 * Sets the data member $affiliation.
	 *
	 * @param		$affiliation (The person's affiliation.)
	 * @access	public
	 */
	public function setAffiliation($affiliation)
	{
		# Check if the passed value is empty.
		if(!empty($affiliation))
		{
			# Strip slashes and decode any html entities.
			$affiliation=html_entity_decode(stripslashes($affiliation), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->affiliation=trim($affiliation);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->affiliation=NULL;
		}
	} #==== End -- setAffiliation

	/**
	 * setArchive
	 *
	 * Sets the data member $archive.
	 *
	 * @param		$archive 	(The records archive status.)
	 * @access	public
	 */
	public function setArchive($archive)
	{
		# Check if the passed $archive is NULL.
		if($archive!==NULL)
		{
			# Explicitly set $archive to 0.
			$archive=0;
		}
		# Set the data member.
		$this->archive=$archive;
	} #==== End -- setArchive

	/**
	 * setCredentials
	 *
	 * Sets the data member $credentials.
	 *
	 * @param		$credentials (The person's credentials.)
	 * @access	public
	 */
	public function setCredentials($credentials)
	{
		# Check if the passed value is empty.
		if(!empty($credentials))
		{
			# Strip slashes and decode any html entities.
			$credentials=html_entity_decode(stripslashes($credentials), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->credentials=trim($credentials);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->credentials=NULL;
		}
	} #==== End -- setCredentials

	/**
	 * setNewPosition
	 *
	 * Sets the data member $new_position.
	 *
	 * @param	$new_position				The person's new position(s).
	 * @access	public
	 */
	/*
	public function setNewPosition($new_position)
	{
		# Check if the passed value is empty.
		if(!empty($new_position))
		{
			# Set the data member.
			$this->new_position=$new_position;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->new_position=NULL;
		}
	} #==== End -- setNewPosition
	*/

	/**
	 * setPosition
	 *
	 * Sets the data member $position.
	 *
	 * @param	$position				The person's position.
	 * @access	public
	 */
	public function setPosition($position)
	{
		# Check if the passed value is empty.
		if(!empty($position))
		{
			# Set the data member.
			$this->position=$position;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->position=NULL;
		}
	} #==== End -- setPosition

	/**
	 * setDisplayName
	 *
	 * Sets the data member $display_name.
	 *
	 * @param	$display_name (The User's display name.)
	 * @access	public
	 */
	public function setDisplayName($display_name)
	{
		# Check if the passed value is empty.
		if(!empty($display_name))
		{
			# Clean it up.
			$display_name=trim($display_name);
		}
		else
		{
			# Explicitly set it to NULL.
			$display_name=NULL;
		}
		# Set it to the data member.
		$this->display_name=$display_name;
	} #==== End -- setDisplayName

	/**
	 * setUsername
	 *
	 * Sets the data member $username.
	 *
	 * @param	$username (The User's username.)
	 * @access	public
	 */
	public function setUsername($username)
	{
		# Check if the passed value is empty.
		if(!empty($username))
		{
			# Clean it up.
			$username=trim($username);
		}
		else
		{
			# Explicitly set it to NULL.
			$username=NULL;
		}
		# Set it to the data member.
		$this->username=$username;
	} #==== End -- setUsername

	/**
	 * setUserLevel
	 *
	 * Sets the data member $level.
	 *
	 * @param		level: The User's access level.
	 * @return	Array: Of user levels.
	 * @access	public
	 */
	public function setUserLevel($level)
	{
		# Check if the passed value is empty.
		if(!empty($level))
		{
			# Check if the passed value is an array.
			if(!is_array($level))
			{
				# Trim off the beginning and trailing dashes.
				$level=trim($level, '-');
				# Explode the levels into an array.
				$level=explode('-', $level);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$level=NULL;
		}
		# Set the data member.
		$this->level=$level;
	} #==== End -- setUserLevel

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param	$title (The User's title.)
	 * @access	public
	 */
	public function setTitle($title)
	{
		# Check if the value is empty.
		if(!empty($title))
		{
			# Clean it up and set the data member.
			$title=trim($title);
		}
		else
		{
			# Explicitly set it to NULL.
			$title=NULL;
		}
		# Set the data member.
		$this->title=$title;
	} #==== End -- setTitle

	/**
	 * setFirstName
	 *
	 * Sets the data member $fname.
	 *
	 * @param	$fname (The User's first name.)
	 * @access	public
	 */
	public function setFirstName($fname)
	{
		# Check if the value is empty.
		if(!empty($fname))
		{
			# Clean it up and set the data member.
			$fname=trim($fname);
		}
		else
		{
			# Explicitly set it to NULL.
			$fname=NULL;
		}
		# Set the data member.
		$this->fname=$fname;
	} #==== End -- setFirstName

	/**
	 * setLastName
	 *
	 * Sets the data member $lname.
	 *
	 * @param	$lname (The User's last name.)
	 * @access	public
	 */
	public function setLastName($lname)
	{
		# Check if the value is empty.
		if(!empty($lname))
		{
			# Clean it up and set the data member.
			$lname=trim($lname);
		}
		else
		{
			# Explicitly set it to NULL.
			$lname=NULL;
		}
		# Set the data member.
		$this->lname=$lname;
	} #==== End -- setLastName

	/**
	 * setEmail
	 *
	 * Sets the data member $email.
	 *
	 * @param		$email (The User's Email address.)
	 * @access	public
	 */
	public function setEmail($email)
	{
		# Check if the passed value is empty.
		if(!empty($email))
		{
			# Clean it up and set the data member.
			$email=trim($email);
		}
		else
		{
			# Explicitly set it to NULL.
			$email=NULL;
		}
		# Set the data member.
		$this->email=$email;
	} #==== End -- setEmail

	/**
	 * setRegion
	 *
	 * Sets the data member $region.
	 *
	 * @param		$region (The User's region.)
	 * @access	public
	 */
	public function setRegion($region)
	{
		# Check if the value is empty.
		if(!empty($region))
		{
			# Set the data member.
			$region=trim($region);
		}
		else
		{
			# Explicitly set it to NULL.
			$region=NULL;
		}
		# Set the data member.
		$this->region=$region;
	} #==== End -- setRegion

	/**
	 * setAddress
	 *
	 * Sets the data member $address.
	 *
	 * @param	$address (The User's address.)
	 * @access	public
	 */
	public function setAddress($address)
	{
		# Check if the value is empty.
		if(!empty($address))
		{
			# Clean it up and set the data member.
			$address=trim($address);
		}
		else
		{
			# Explicitly set it to NULL.
			$address=NULL;
		}
		# Set the data member.
		$this->address=$address;
	} #==== End -- setAddress

	/**
	 * setAddress2
	 *
	 * Sets the data member $address2.
	 *
	 * @param	$address2 (The User's address2.)
	 * @access	public
	 */
	public function setAddress2($address2)
	{
		# Check if the value is empty.
		if(!empty($address2))
		{
			# Clean it up and set the data member.
			$address2=trim($address2);
		}
		else
		{
			# Explicitly set it to NULL.
			$address2=NULL;
		}
		# Set the data member.
		$this->address2=$address2;
	} #==== End -- setAddress2

	/**
	 * setCity
	 *
	 * Sets the data member $city.
	 *
	 * @param		$city 	(The User's city.)
	 * @access	public
	 */
	public function setCity($city)
	{
		# Check if the value is empty.
		if(!empty($city))
		{
			# Clean it up.
			$city=trim($city);
		}
		else
		{
			# Explicitly set it to NULL.
			$city=NULL;
		}
		# Set the data member.
		$this->city=$city;
	} #==== End -- setCity

	/**
	 * setState
	 *
	 * Sets the data member $state.
	 *
	 * @param	$state (The User's state.)
	 * @access	public
	 */
	public function setState($state)
	{
		# Check if the value is empty.
		if(!empty($state))
		{
			# Clean it up and set the data member.
			$state=trim($state);
		}
		else
		{
			# Explicitly set it to NULL.
			$state=NULL;
		}
		# Set the data member.
		$this->state=$state;
	} #==== End -- setState

	/**
	 * setCountry
	 *
	 * Sets the data member $country.
	 *
	 * @param	$country (The User's country.)
	 * @access	public
	 */
	public function setCountry($country)
	{
		# Check if the value is empty.
		if(!empty($country))
		{
			# Clean it up and set the data member.
			$country=trim($country);
		}
		else
		{
			# Explicitly set it to NULL.
			$country=NULL;
		}
		# Set the data member.
		$this->country=$country;
	} #==== End -- setCountry

	/**
	 * setZipcode
	 *
	 * Sets the data member $zipcode.
	 *
	 * @param	$zipcode (The User's zipcode.)
	 * @access	public
	 */
	public function setZipcode($zipcode)
	{
		# Check if the value is empty.
		if(!empty($zipcode))
		{
			# Clean it up and set the data member.
			$zipcode=trim($zipcode);
		}
		else
		{
			# Explicitly set it to NULL.
			$zipcode=NULL;
		}
		# Set the data member.
		$this->zipcode=$zipcode;
	} #==== End -- setZipcode

	/**
	 * setPhone
	 *
	 * Sets the data member $phone.
	 *
	 * @param		$phone (The User's phone number.)
	 * @access	public
	 */
	public function setPhone($phone)
	{
		# Check if the value is empty.
		if(!empty($phone))
		{
			# Clean it up and set the data member.
			$phone=trim($phone);
		}
		else
		{
			# Explicitly set it to NULL.
			$phone=NULL;
		}
		# Set the data member.
		$this->phone=$phone;
	} #==== End -- setPhone

	/**
	 * setImg
	 *
	 * Sets the data member $img.
	 *
	 * @param	string $img	The User's avatar image
	 * @access	public
	 */
	public function setImg($img)
	{
		# Check if the value is empty.
		if(!empty($img))
		{
			# Set the variable.
			$img=$img;
		}
		else
		{
			# Explicitly set it to NULL.
			$img=NULL;
		}
		# Set the data member.
		$this->img=$img;
	} #==== End -- setImg

	/**
	 * setImgTitle
	 *
	 * Sets the data member $img_title.
	 *
	 * @param	string $img_title	The title of the User's image
	 * @access	public
	 */
	public function setImgTitle($img_title)
	{
		# Check if the value is empty.
		if(!empty($img_title))
		{
			# Clean it up and set the data member.
			$img_title=trim($img_title);
		}
		else
		{
			# Explicitly set it to NULL.
			$img_title=NULL;
		}
		# Set the data member.
		$this->img_title=$img_title;
	} #==== End -- setImgTitle

	/**
	 * setPassword
	 *
	 * Sets the data member $password.
	 *
	 * @param	$password (The User's password.)
	 * @access	public
	 */
	public function setPassword($password)
	{
		# Check if the value is empty.
		if(!empty($password))
		{
			# Clean it up and set the data member.
			$password=trim($password);
		}
		else
		{
			# Explicitly set it to NULL.
			$password=NULL;
		}
		# Set the data member.
		$this->password=$password;
	} #==== End -- setPassword

	/**
	 * setInterests
	 *
	 * Sets the data member $interests.
	 *
	 * @param	string $img	(The User's interests.)
	 * @access	public
	 */
	public function setInterests($interests)
	{
		# Check if the value is empty.
		if(!empty($interests))
		{
			# Clean it up and set the data member.
			$interests=trim($interests);
			# Replace any tokens with their correlating value.
			$interests=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $interests);
			# Strip slashes and change new lines to <br />.
			$interests=html_entity_decode(stripslashes($interests), ENT_NOQUOTES, 'UTF-8');
		}
		else
		{
			# Explicitly set it to NULL.
			$interests=NULL;
		}
		# Set the data member.
		$this->interests=$interests;
	} #==== End -- setInterests

	/**
	 * setBio
	 *
	 * Sets the data member $bio.
	 *
	 * @param	$bio (The User's biographical information.)
	 * @access	public
	 */
	public function setBio($bio)
	{
		# Check if the value is empty.
		if(!empty($bio))
		{
			# Strip slashes and change new lines to <br />.
			$bio=html_entity_decode(stripslashes($bio), ENT_NOQUOTES, 'UTF-8');
			# Clean it up.
			$bio=trim($bio);
			# Replace any tokens with their correlating value.
			$bio=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $bio);
		}
		else
		{
			# Explicitly set it to NULL.
			$bio=NULL;
		}
		# Set the data member.
		$this->bio=$bio;
	} #==== End -- setBio

	/**
	 * setCV
	 *
	 * Sets the data member $cv.
	 *
	 * @param		$cv (The User's cv file.)
	 * @access	public
	 */
	public function setCV($cv)
	{
		# Check if the value is empty.
		if(!empty($cv))
		{
			# Clean it up and set the data member.
			$cv=trim($cv);
		}
		else
		{
			# Explicitly set it to NULL.
			$cv=NULL;
		}
		# Set the data member.
		$this->cv=$cv;
	} #==== End -- setCV

	/**
	 * setOrganization
	 *
	 * Sets the data member $organization.
	 *
	 * @param		$organization (The User's organization.)
	 * @access	public
	 */
	public function setOrganization($organization)
	{
		# Check if the value is empty.
		if(!empty($organization))
		{
			# Strip slashes and change new lines to <br />.
			$organization=html_entity_decode(stripslashes($organization), ENT_NOQUOTES, 'UTF-8');
			# Clean it up and set the data member.
			$organization=trim($organization);
		}
		else
		{
			# Explicitly set it to NULL.
			$organization=NULL;
		}
		# Set the data member.
		$this->organization=$organization;
	} #==== End -- setOrganization

	/**
	 * setWebsite
	 *
	 * Sets the data member $website.
	 *
	 * @param		$website (The User's website.)
	 * @access	public
	 */
	public function setWebsite($website)
	{
		# Check if the value is empty.
		if(!empty($website))
		{
			# Clean it up.
			$website=trim($website);
			# Replace any tokens with their correlating value.
			$website=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $website);
		}
		else
		{
			# Explicitly set it to NULL.
			$website=NULL;
		}
		# Set the data member.
		$this->website=$website;
	} #==== End -- setWebsite

	/**
	 * setNewsletter
	 *
	 * Sets the data member $newsletter.
	 *
	 * @param		$newsletter (If the User recieves the newsletter.)
	 * @access	public
	 */
	public function setNewsletter($newsletter)
	{
		# Check if the passed value is empty.
		if(empty($newsletter))
		{
			# Check if the value is 0.
			if($newsletter!==0 && $newsletter!=='0')
			{
				# Explicitly set the data member to NULL.
				$newsletter=NULL;
			}
		}
		else
		{
			# Clean it up.
			$newsletter=trim($newsletter);
		}
		# Set the data member.
		$this->newsletter=$newsletter;
	} #==== End -- setNewsletter

	/**
	 * setNotify
	 *
	 * Sets the data member $notify.
	 *
	 * @param		$notify (may be an array or string of branch id's the user wishes to be notified about. If a string, the id's must be separated with a dash('-').)
	 * @access	public
	 */
	public function setNotify($notify)
	{
		# Check if the passed value is empty.
		if(!empty($notify))
		{
			# Check if the passed value is an array.
			if(!is_array($notify))
			{
				# Trim off the beginning and trailing dashes.
				$notify=trim($notify, '-');
				# Explode the notify branch id's into an array.
				$notify=explode('-', $notify);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$notify=NULL;
		}
		# Set the data member.
		$this->notify=$notify;
	} #==== End -- setNotify

	/**
	 * setQuestions
	 *
	 * Sets the data member $questions.
	 *
	 * @param		$questions (0 if the User will accept emails from other users, NULL if not.)
	 * @access	public
	 */
	public function setQuestions($questions)
	{
		# Check if the value is NULL.
		if($questions!==NULL)
		{
			# Explicitly set the value to 0.
			$questions=0;
		}
		# Set the data member.
		$this->questions=$questions;
	} #==== End -- setQuestions

	/**
	 * setActive
	 *
	 * Sets the data member $active.
	 *
	 * @param	$active (Account status. 0=Not Activated, 1=Activated, 2=Suspended.)
	 * @access	public
	 */
	public function setActive($active)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is NULL.
		if($active!==NULL)
		{
			# Clean it up.
			$active=trim($active);
			# Check if the passed value is an integer.
			if($validator->isInt($active)===TRUE)
			{
				# Explicitly make it an integer.
				$active=(int)$active;
			}
			else
			{
				throw new Exception('The passed active status was not a number!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$active=NULL;
		}
		# Set the data member.
		$this->active=$active;
	} #==== End -- setActive

	/**
	 * setAllSubscriptions
	 *
	 * Sets the data member $all_subscriptions.
	 *
	 * @param		array		$subscriptions (The User's subscriptions.)
	 * @access	public
	 */
	public function setAllSubscriptions($subscriptions)
	{
		# Check if the value is empty.
		if(!empty($subscriptions))
		{
			# Explicitly make it an array.
			$subscriptions=(array)$subscriptions;
		}
		else
		{
			# Explicitly set it to NULL.
			$subscriptions=NULL;
		}
		# Set the data member.
		$this->all_subscriptions=$subscriptions;
	} #==== End -- setAllSubscriptions

	/**
	 * setProduct
	 *
	 * Sets the data member $product.
	 *
	 * @param		$product (The User's subscriptions.)
	 * @access	public
	 */
	public function setProduct($product)
	{
		# Check if the value is empty.
		if(!empty($product))
		{
			# Clean it up.
			$product=trim($product);
		}
		else
		{
			# Explicitly set it to NULL.
			$product=NULL;
		}
		# Set the data member.
		$this->product=$product;
	} #==== End -- setProduct

	/**
	 * setRegistered
	 *
	 * Sets the data member $registered.
	 *
	 * @param	$registered (The date the user registered.)
	 * @access	public
	 */
	public function setRegistered($registered)
	{
		# Check if the value is empty.
		if(empty($registered))
		{
			# Explicitly set it to the default.
			$registered='0000-00-00';
		}
		# Clean it up and set the data member.
		$this->registered=trim($registered);
	} #==== End -- setRegistered

	/**
	 * setLastLogin
	 *
	 * Sets the data member $last_login.
	 *
	 * @param	$last_login (The date the user last logged in.)
	 * @access	public
	 */
	public function setLastLogin($last_login)
	{
		# Check if the value is empty.
		if(empty($last_login))
		{
			# Explicitly set it to the default.
			$last_login='0000-00-00';
		}
		# Clean it up and set the data member.
		$this->last_login=trim($last_login);
	} #==== End -- setLastLogin

	/**
	* setAllUsers
	*
	* Sets the data member $all_users.
	*
	* @param	$all_users
	* @access	protected
	*/
	protected function setAllUsers($all_users)
	{
		$this->all_users=$all_users;
	} #==== End -- setAllUsers

	/**
	* setIP
	*
	* Sets the data member $ip.
	*
	* @param	$ip
	* @access	protected
	*/
	protected function setIP($ip)
	{
		# Check if the passed value is empty.
		if(!empty($ip))
		{
			# Clean it up.
			$ip=trim($ip);
		}
		else
		{
			# Explicitly set it to NULL.
			$ip=NULL;
		}
		# Set the data member.
		$this->ip=$ip;
	} #==== End -- setIP

	/**
	 * setNickname
	 *
	 * Sets the data member $nickname.
	 *
	 * @param	$nickname (The User's nickname. This is only used in WordPress instalations.)
	 * @access	public
	 */
	public function setNickname($nickname)
	{
		# Check if the value is empty.
		if(!empty($nickname))
		{
			# Clean it up and set the data member.
			$nickname=trim($nickname);
		}
		else
		{
			# Explicitly set it to NULL.
			$nickname=NULL;
		}
		# Set the data member.
		$this->nickname=$nickname;
	} #==== End -- setNickname

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getID
	 *
	 * Returns the data member $id.
	 *
	 * @access	public
	 */
	public function getID()
	{
		return $this->id;
	} #==== End -- getID

	/**
	 * getStaff
	 *
	 * Returns the data member $staff.
	 *
	 * @access	public
	 */
	public function getStaff()
	{
		return $this->staff;
	} #==== End -- getStaff

	/**
	 * getStaffID
	 *
	 * Returns the data member $staff_id.
	 *
	 * @access	public
	 */
	public function getStaffID()
	{
		return $this->staff_id;
	} #==== End -- getStaffID

	/**
	 * getAffiliation
	 *
	 * Returns the data member $affiliation.
	 *
	 * @access	public
	 */
	public function getAffiliation()
	{
		return $this->affiliation;
	} #==== End -- getAffiliation

	/**
	 * getArchive
	 *
	 * Returns the data member $archive.
	 *
	 * @access	public
	 */
	public function getArchive()
	{
		return $this->archive;
	} #==== End -- getArchive

	/**
	 * getCredentials
	 *
	 * Returns the data member $credentials.
	 *
	 * @access	public
	 */
	public function getCredentials()
	{
		return $this->credentials;
	} #==== End -- getCredentials

	/**
	 * getNewPosition
	 *
	 * Returns the data member $new_position.
	 *
	 * @access	public
	 */
	/*
	public function getNewPosition()
	{
		return $this->new_position;
	} #==== End -- getNewPosition
	*/

	/**
	 * getPosition
	 *
	 * Returns the data member $position.
	 *
	 * @access	public
	 */
	public function getPosition()
	{
		return $this->position;
	} #==== End -- getPosition

	/**
	 * getDisplayName
	 *
	 * Returns the data member $display_name.
	 *
	 * @access	public
	 */
	public function getDisplayName()
	{
		return $this->display_name;
	} #==== End -- getDisplayName

	/**
	 * getUsername
	 *
	 * Returns the data member $username.
	 *
	 * @access	public
	 */
	public function getUsername()
	{
		return $this->username;
	} #==== End -- getUsername

	/**
	 * getUserLevel
	 *
	 * Returns the data member $level.
	 *
	 * @access	public
	 */
	public function getUserLevel()
	{
		return $this->level;
	} #==== End -- getUserLevel

	/**
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access	public
	 */
	public function getTitle()
	{
		return $this->title;
	} #==== End -- getTitle

	/**
	 * getFirstName
	 *
	 * Returns the data member $fname.
	 *
	 * @access	public
	 */
	public function getFirstName()
	{
		return $this->fname;
	} #==== End -- getFirstName

	/**
	 * getLastName
	 *
	 * Returns the data member $lname. Throws an error on failure.
	 *
	 * @access	public
	 */
	public function getLastName()
	{
		return $this->lname;
	} #==== End -- getLastName

	/**
	 * getEmail
	 *
	 * Returns the data member $email.
	 *
	 * @access	public
	 */
	public function getEmail()
	{
		return $this->email;
	} #==== End -- getEmail

	/**
	 * getRegion
	 *
	 * Returns the data member $region.
	 *
	 * @access	public
	 */
	public function getRegion()
	{
		return $this->region;
	} #==== End -- getRegion

	/**
	 * getAddress
	 *
	 * Returns the data member $address.
	 *
	 * @access	public
	 */
	public function getAddress()
	{
		return $this->address;
	} #==== End -- getAddress

	/**
	 * getAddress2
	 *
	 * Returns the data member $address2.
	 *
	 * @access	public
	 */
	public function getAddress2()
	{
		return $this->address2;
	} #==== End -- getAddress2

	/**
	 * getCity
	 *
	 * Returns the data member $city.
	 *
	 * @access	public
	 */
	public function getCity()
	{
		return $this->city;
	} #==== End -- getCity

	/**
	 * getState
	 *
	 * Returns the data member $state.
	 *
	 * @access	public
	 */
	public function getState()
	{
		return $this->state;
	} #==== End -- getState

	/**
	 * getCountry
	 *
	 * Returns the data member $country.
	 *
	 * @access	public
	 */
	public function getCountry()
	{
		return $this->country;
	} #==== End -- getCountry

	/**
	 * getZipcode
	 *
	 * Returns the data member $zipcode.
	 *
	 * @access	public
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	} #==== End -- getZipcode

	/**
	 * getPhone
	 *
	 * Returns the data member $phone.
	 *
	 * @access	public
	 */
	public function getPhone()
	{
		return $this->phone;
	} #==== End -- getPhone

	/**
	 * getImg
	 *
	 * Returns the data member $img.
	 *
	 * @access	public
	 */
	public function getImg()
	{
		return $this->img;
	} #==== End -- getImg

	/**
	 * getImgTitle
	 *
	 * Returns the data member $img_title.
	 *
	 * @access	public
	 */
	public function getImgTitle()
	{
		return $this->img_title;
	} #==== End -- getImgTitle

	/**
	 * getPassword
	 *
	 * Returns the data member $password
	 *
	 * @access	public
	 */
	public function getPassword()
	{
		return $this->password;
	} #==== End -- getPassword

	/**
	 * getInterests
	 *
	 * Returns the data member $interests.
	 *
	 * @access	public
	 */
	public function getInterests()
	{
		return $this->interests;
	} #==== End -- getInterests

	/**
	 * getBio
	 *
	 * Returns the data member $bio.
	 *
	 * @access	public
	 */
	public function getBio()
	{
		return $this->bio;
	} #==== End -- getBio

	/**
	 * getCV
	 *
	 * Returns the data member $cv.
	 *
	 * @access	public
	 */
	public function getCV()
	{
		return $this->cv;
	} #==== End -- getCV

	/**
	 * getOrganization
	 *
	 * Returns the data member $organization.
	 *
	 * @access	public
	 */
	public function getOrganization()
	{
		return $this->organization;
	} #==== End -- getOrganization

	/**
	 * getWebsite
	 *
	 * Returns the data member $website.
	 *
	 * @access	public
	 */
	public function getWebsite()
	{
		return $this->website;
	} #==== End -- getWebsite

	/**
	 * getNewsletter
	 *
	 * Returns the data member $newsletter.
	 *
	 * @access	public
	 */
	public function getNewsletter()
	{
		return $this->newsletter;
	} #==== End -- getNewsletter

	/**
	 * getNotify
	 *
	 * Returns the data member $notify.
	 *
	 * @access	public
	 */
	public function getNotify()
	{
		return $this->notify;
	} #==== End -- getNotify

	/**
	 * getQuestions
	 *
	 * Returns the data member $questions.
	 *
	 * @access	public
	 */
	public function getQuestions()
	{
		return $this->questions;
	} #==== End -- getQuestions

	/**
	 * getActive
	 *
	 * Returns the data member $active. Throws an error on failure.
	 *
	 * @access	public
	 */
	public function getActive()
	{
		return $this->active;
	} #==== End -- getActive

	/**
	 * getProduct
	 *
	 * Returns the data member $product.
	 *
	 * @access	public
	 */
	public function getProduct()
	{
		return $this->product;
	} #==== End -- getProduct

	/**
	 * getRegistered
	 *
	 * Returns the data member $registered. Throws an error on failure.
	 *
	 * @access	public
	 */
	public function getRegistered()
	{
		if(!empty($this->registered))
		{
			return $this->registered;
		}
		else
		{
			throw new Exception('The date the User registered was not set!');
		}
	} #==== End -- getRegistered

	/**
	 * getAllSubscriptions
	 *
	 * Returns the data member $all_subscriptions.
	 *
	 * @access	public
	 */
	public function getAllSubscriptions()
	{
		return $this->all_subscriptions;
	} #==== End -- getAllSubscriptions

	/**
	 * getLastLogin
	 *
	 * Returns the data member $last_login. Throws an error on failure.
	 *
	 * @access	public
	 */
	public function getLastLogin()
	{
		$last_login=$this->last_login;
		if($this->last_login=='0000-00-00')
		{
			$last_login='never';
		}
		return $last_login;
	} #==== End -- getLastLogin

	/**
	 * getAllUsers
	 *
	 * Returns the data member $all_users.
	 *
	 * @access	public
	 */
	public function getAllUsers()
	{
		return $this->all_users;
	} #==== End -- getAllUsers

	/**
	 * getIP
	 *
	 * Returns the data member $ip.
	 *
	 * @access	public
	 */
	public function getIP()
	{
		return $this->ip;
	} #==== End -- getIP

	/**
	 * getNickname
	 *
	 * Returns the data member $nickname.
	 *
	 * @access	public
	 */
	public function getNickname()
	{
		return $this->nickname;
	} #==== End -- getNickname

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * checkUnique
	 *
	 * Performs a check to determine if one parameter is unique in the Database.
	 * Returns FALSE if the value is already in the Database.
	 *
	 * @param		$field (The field to look in.)
	 * @param		$compared (The value to check.)
	 * @param		$params (Any extra parameters.)
	 * @access	public
	 */
	public function checkUnique($field, $compared, $params='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$check=$db->query("SELECT `".$field."` FROM `".DBPREFIX."users` WHERE `".$field."` = ".$db->quote($db->escape($compared)).$params);

		return (($check==0) ? TRUE : FALSE);
	} #==== End -- checkUnique

	/**
	 * countAllUsers
	 *
	 * Returns the number of users in the database.
	 *
	 * @param	$limit (The limit of records to count.)
	 * @param	$and_sql (Extra AND statements in the query.)
	 * @access	public
	 */
	public function countUsers($limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			try
			{
				$count=$db->query('SELECT `ID` FROM `'.DBPREFIX.'users`'.(($and_sql!==NULL) ? ' '.$and_sql : ''));
				return $count;
			}
			catch(ezDB_Error $ez)
			{
				throw new Exception($ez, E_RECOVERABLE_ERROR);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllUsers

	/**
	 * createAccount
	 *
	 * Creates a new account in the database.
	 *
	 * @access	public
	 */
	public function createAccount()
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Bring the Document class into the scope.
			global $doc;
			# Bring Login class into the scope.
			global $login;

			# Set the email data member to a variable.
			$email=$login->getEmail();
			# Set the password data member to a variable.
			$password=$login->getPassword();
			# Set username data member to a variable.
			$username=$login->getUsername();

			# Insert user into the `users` table.
			$insert_user=$db->query('INSERT INTO `'.DBPREFIX.'users` (`display`, `username`, `email`, `password`, `random`, `registered`) VALUES ('.
				$db->quote($db->escape($username)).
				', '.$db->quote($db->escape($username)).
				', '.$db->quote($db->escape($email)).
				', '.$db->quote($db->escape($password)).
				', '.$db->quote($db->escape($login->randomString('alnum', 32))).
				', '.$db->quote($db->escape(YEAR_MM_DD)).
				')');
			# If WordPress is installed add the user the the WordPress users table.
			if(WP_INSTALLED===TRUE)
			{
				# Get the wordpress password.
				$wp_password=$login->getWPPassword();
				$login->createWP_User($wp_password);
			}
			# Account was not created. Return error.
			if($insert_user<=0)
			{
				$_SESSION['message']='There was an error registering your account. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
				$doc->redirect(REDIRECT_TO_LOGIN);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error registering a new user: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- createAccount

	/**
	 * deleteAccount
	 *
	 * Delete's the user's account.
	 *
	 * @param	integer $id					The User's ID.
	 * @access	public
	 */
	public function deleteAccount($id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		if($id===NULL)
		{
			$id=$this->findUserID();
		}
		try
		{
			# Get the Contributor class.
			require Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
			# Instantiate a new Contributor object.
			$contributor=new Contributor();
			# Attempt to retrieve this User from the `contributors` table.
			$contributor_retrieved=$contributor->getThisContributor($id);
			# Check if the contributor was retrieved.
			if($contributor_retrieved===TRUE)
			{
				# Set the User ID to NULL in the `contributors` table.
				$remove_user=$contributor->removeUser($id);
			}

			# Get the Staff class.
			require Utility::locateFile(MODULES.'User'.DS.'Staff.php');
			# Instantiate a new Staff object.
			$staff=new Staff();
			# Attempt to retrieve this User from the `staff` table.
			$person_retrieved=$staff->getThisPerson($id);
			# Check if the person was retrieved.
			if($person_retrieved===TRUE)
			{
				# Set the User ID to NULL in the `staff` table.
				$remove_user=$staff->removeUser($id);
			}

		# DRAVEN: There is no `subscriptions` table...?
		/*
			# Get the Subscription class.
			require Utility::locateFile(MODULES.'Product'.DS.'Subscription.php');
			# Instantiate a new Subscription object.
			$subscription=new Subscription();
			# Attempt to retrieve this User from the `subscriptions` table.
			$user_retrieved=$subscription->countAllSubscriptions(NULL, NULL, ' AND `user` = '.$db->quote($id));
			# Check if the User was retrieved.
			if($user_retrieved>0)
			{
				# Delete the User's subscriptions from the `subscriptions` table.
				$remove_user=$subscription->removeUser($id);
			}
		*/

			# Get the Comment class.
			require Utility::locateFile(MODULES.'Content'.DS.'Comment.php');
			# Instantiate a new Comment object.
			$comment=new Comment();
			# Set the User ID to NULL in the `comments` table.
			$remove_user=$comment->removeUser($id);

			# check if there is a WordPress installation.
			if(WP_INSTALLED===TRUE)
			{
				# Get the WordPressUser class.
				require Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
				# Instantiate a new WordPressUser object.
				$wp=new WordPressUser();

			# DRAVEN: Why reassign? If the user is not found then their display name should be "Unknown User".
				# Find the User ID for "Unknown User" and set it to a variable.
				//$unknown_id=$this->findUserID('unknown');
				# Delete the User from the WordPress installation and reassign their posts to "Unknown User".
				//$delete_wp_user=$wp->deleteWP_User($id, $unknown_id);

				# Get user's username.
				$username=$this->findUsername($id);
				# Get user's WP ID.
				$wp_id=$wp->getWP_UserID($username);
				# Delete the User from the WordPress installation.
				$delete_wp_user=$wp->deleteWP_User($wp_id);
			}

			# Delete from the users table.
			$delete_user=$db->query('DELETE FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id).' LIMIT 1');
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error deleting User ID# '.$id.' from the Database: '.$ez->error.'<br />Code: '.$ez->errno.'<br />
			Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- deleteAccount

	/***
	 * displayProfile
	 *
	 * Retrieves the members information from the database and displays it.
	 *
	 * @param	int $id					The user's id
	 * @param	string $table			The table that the id is related to.
	 * @access	public
	 */
	public function displayProfile($id, $table='user', $image_link=FW_POPUP_HANDLE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;

		# Create new array to hold all display content.
		$display_content=array('affiliation'=>'', 'archive'=>'', 'bio'=>'', 'country'=>'', 'cv'=>'', 'display_name'=>'', 'email'=>'', 'image'=>'', 'interests'=>'', 'name'=>'', 'organization'=>'', 'position'=>NULL, 'privacy'=>NULL, 'questions'=>'', 'region'=>'', 'website'=>'');

		try
		{
			# Check if the passed id is from the `contributor` table.
			if($table=='contributor')
			{
				# Get the Contributor class.
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
				# Instantiate a new Contributor object.
				$$table=new Contributor();
				# Retrieve the contributor's information.
				$display_contributor=$$table->displayContributor($id);
				# Check if the contributor was retrieved.
				if(!empty($display_contributor))
				{
					# Get the contributor's display name.
					$display_name=$$table->getContName();
					# Set the contributor XHTML elements to variables.
					$country=$display_contributor['country'];
					$email=$display_contributor['email'];
					$name=$display_contributor['name'];
					$organization=$display_contributor['organization'];
					$display_content['privacy']=$display_contributor['privacy'];
					$region=$display_contributor['region'];
					# Set the user data member to a variable.
					$user=$$table->getUser();
				}
				else
				{
					return NULL;
				}
			}
			if($table=='user')
			{
				# Instantiate a new User object.
				$user=new User();
				# Get the User's username from the `User` table.
				$username=$$table->findUsername($id);
				# Retrieve this user's information from the `User` table.
				$$table->findUserData($username);
				# Get the User's display name.
				$display_name=$$table->getDisplayName();

				# Check if $country is set or is empty.
				if(!isset($country) || empty($country))
				{
					# Get the User's country and set it to a variable.
					$country=$$table->getCountry();
					# Check if the User's country is available.
					if(!empty($country))
					{
						# Set the person's country to a variable.
						$profile_country='<div class="profile-country">';
						$profile_country.='<span class="label">Country:</span>';
						$profile_country.='<span>'.$country.'</span>';
						$profile_country.='</div>';
						# Set the country XHTML to the display content array.
						$display_content['country']=$profile_country;
					}
				}
				else
				{
					# Set the country XHTML to the display content array.
					$display_content['country']=$country;
				}
				# Get the User's CV(curriculum vitae) file and set it to a variable.
				$cv=$$table->getCV();
				# Check if the person's CV is available.
				if(!empty($cv))
				{
					# Set the person's CV to a variable.
					$profile_cv='<div class="profile-cv">';
					$profile_cv.='<span class="label">'.$display_name.'\'s Curriculum Vitae (<abbr title="Curriculum Vitae">CV</abbr>) is available for download:</span>';
					$profile_cv.='<a href="'.DOWNLOADS.'?f='.$cv.'&t=cv" title="'.$display_name.'\'s CV File">'.$cv.'</a>';
					$profile_cv.='</div>';
					# Set the CV XHTML to the display content array.
					$display_content['cv']=$profile_cv;
				}
			}
			# Check if we have someone to display.
			if((isset($display_person) && !empty($display_person)) || !empty($display_name))
			{
				# Check if $privacy is NULL.
				if($display_content['privacy']!==NULL)
				{
					# Check if the User is logged in.
					if($login->isLoggedIn()!==TRUE)
					{
						# Let the user know why they were redirected.
						$_SESSION['message']='You must be logged in to view that profile. Registering is free and easy. Registered users have access to special content and downloads.';
						$doc->redirect(REDIRECT_TO_LOGIN);
					}
				}
				# Check if $affiliation is set.
				if(isset($affiliation))
				{
					# Set the affiliation XHTML to the display content array.
					$display_content['affiliation']=$affiliation;
				}
				# Check if $bio is set or is empty.
				if(!isset($bio) || empty($bio))
				{
					# Get the User's biographical information and set it to a variable.
					$bio=$$table->getBio();
					# Check if the User's biographical information is available.
					if(!empty($bio))
					{
						# Convert new lines to <br />.
						$bio=nl2br($bio);
						# Set the person's biographical information to a variable.
						$profile_bio='<div class="profile-bio">';
						$profile_bio.='<span class="label">Biographical Information:</span>';
						$profile_bio.='<span>'.$bio.'</span>';
						$profile_bio.='</div>';
						# Set the biographical information XHTML to the display content array.
						$display_content['bio']=$profile_bio;
					}
				}
				else
				{
					# Convert new lines to <br />.
					$bio=nl2br($bio);
					# Set the biographical information XHTML to the display content array.
					$display_content['bio']=$bio;
				}
				# Get the User's email and set it to a variable.
				$email=$$table->getEmail();
				# Set the User's email to the display content array.
				$display_content['email']=$email;
				# Check if $image_title is set.
				if(!isset($image_title) || empty($image_title))
				{
					# Set the person's display name as the image title.
					$image_title=$$table->getDisplayName();
				}
				# Check if $image is set or is empty.
				if(!isset($image) || empty($image))
				{
					# Get the User's image and set it to a variable.
					$image=$$table->getImg();
					# Get the Image class.
					require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
					# Instantiate a new Image object.
					$image_obj=new Image();
					# Set the person's image to a variable.
					$profile_image='<div class="profile-image">';
					$profile_image.=$image_obj->displayImage(TRUE, $image, $image_title, $image_link);
					$profile_image.='</div>';
					# Set the image XHTML to the display content array.
					$display_content['image']=$profile_image;
				}
				else
				{
					# Set the image XHTML to the display content array.
					$display_content['image']=$image;
				}
				# Get the User's interests and set it to a variable.
				$interests=$$table->getInterests();
				# Check if the User's interests are available.
				if(!empty($interests))
				{
					# Set the User's interests to a variable.
					$profile_interests='<div class="profile-interests">';
					$profile_interests.='<span class="label">Interests:</span>';
					$profile_interests.='<span>'.html_entity_decode($interests, ENT_COMPAT, 'UTF-8').'</span>';
					$profile_interests.='</div>';
					# Set the interests XHTML to the display content array.
					$display_content['interests']=$profile_interests;
				}
				# Check if $name is set.
				if(!isset($name) || empty($name))
				{
					# Set the User's display name to a variable.
					$profile_name='<span class="profile-name">';
					$profile_name.=$$table->getDisplayName();
					$profile_name.='</span>';
					# Set the name XHTML to the display content array.
					$display_content['name']=$profile_name;
					$display_content['display_name']=$$table->getDisplayName();
				}
				else
				{
					# Set the name XHTML to the display content array.
					$display_content['name']=$name;
					$display_content['display_name']=strip_tags($name);
				}
				# Check if $organization is set.
				if(!isset($organization) || empty($organization))
				{
					# Get the User's organization and set it to a variable.
					$organization=$$table->getOrganization();
					# Check if the User's organization is available.
					if(!empty($organization))
					{
						# Set the User's organization to a variable.
						$profile_organization='<div class="profile-organization">';
						$profile_organization.='<span class="label">Organization:</span>';
						$profile_organization.='<span>'.$$table->getOrganization().'</span>';
						$profile_organization.='</div>';
						# Set the organization XHTML to the display content array.
						$display_content['organization']=$profile_organization;
					}
				}
				else
				{
					# Set the organization XHTML to the display content array.
					$display_content['organization']=$organization;
				}
				if(isset($positions))
				{
					$display_content['position']=$positions;
				}
				# Check if $region is set or is empty.
				if(!isset($region) || empty($region))
				{
					# Get the User's region and set it to a variable.
					$region=$$table->getRegion();
					# Check if the User's region is available.
					if(!empty($region))
					{
						# Set the person's region to a variable.
						$profile_region='<div class="profile-region">';
						$profile_region.='<span class="label">Region:</span>';
						$profile_region.='<span>'.$region.'</span>';
						$profile_region.='</div>';
						# Set the region XHTML to the display content array.
						$display_content['region']=$profile_region;
					}
				}
				else
				{
					# Set the region XHTML to the display content array.
					$display_content['region']=$region;
				}
				# Get the User's website and set it to a variable.
				$website=$$table->getWebsite();
				# Check if the User's website is available.
				if(!empty($website))
				{
					# Set the User's website to a variable.
					$profile_website='<div class="profile-website">';
					$profile_website.='<span class="label">Website:</span>';
					$profile_website.='<a href="'.$website.'" title="'.$website.'" target="_blank">'.$website.'</a>';
					$profile_website.='</div>';
					# Set the website XHTML to the display content array.
					$display_content['website']=$profile_website;
				}
				# Get the User's question status.
				$questions=$$table->getQuestions();
				$display_content['questions']=$questions;
				return $display_content;
			}
			return NULL;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayProfile

	/**
	 * findDisplayName
	 *
	 * Retrieves the User's display name and sets it to the display_name data member.
	 *
	 * @access	public
	 */
	public function findDisplayName($id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Try to get the display name from the data member.
			$display_name=$this->getDisplayName();
			# Check if there is a display name set.
			if(empty($display_name))
			{
				# Check if there wa a passed ID.
				if(empty($id))
				{
					# Check if the display_name stored in a session.
					if(isset($_SESSION['user_display_name']))
					{
						# Set the display name to the data member.
						$this->setDisplayName($_SESSION['user_display_name']);
					}
					else
					{
						# Get the diplay name from the `users` table.
						$row=$db->get_row('SELECT `display` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($this->findUserID()));
						# Check if a row was returned.
						if(!empty($row))
						{
							# Set the display name to the data member.
							$this->setDisplayName($row->display);
						}
					}
				}
				else
				{
					# Set the ID to the data member effectively cleaning it.
					$this->setID($id);
					# Get the diplay name from the `users` table.
					$row=$db->get_row('SELECT `display` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($this->getID()));
					# Check if a row was returned.
					if(!empty($row))
					{
						# Set the display name to the data member.
						$this->setDisplayName($row->display);
					}
				}
			}
			return $this->getDisplayName();
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->message.'<br />Code: '.$ez->code.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findDisplayName

	/**
	 * findEmail
	 *
	 * Retrieves the User's email and sets it to the email data member.
	 *
	 *
	 * @param		int 		$id 	(The user's id)
	 * @access	public
	 */
	public function findEmail($id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed value is empty.
			if(empty($id))
			{
				# Set the data member to a variable.
				$email=$this->getEmail();
				if(!empty($email))
				{
					return $email;
				}
				# Find the User's ID and set it to a variable.
				$id=$this->findUserID();
			}
			# Get the email from the `users` table.
			$row=$db->get_row('SELECT `email` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id).' LIMIT 1');
			# Check if a record was returned.
			if($row!==NULL)
			{
				# Set the email to the data member.
				$this->setEmail(htmlspecialchars_decode($row->email, ENT_QUOTES));
			}
			return $this->getEmail();
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findEmail

	/**
	 * findIP
	 *
	 * Returns the IP of the visitor
	 *
	 * @access	public
	 * @return	string
	 */
	public function findIP()
	{
		# Check the data member for an IP address.
		if($this->getIP()===NULL)
		{
			# Set the IP address to the data member.
			$this->setIP(getenv('REMOTE_ADDR'));
		}
		# Return the data member.
		return $this->getIP();
	} #==== End -- findIP

	/**
	 * findLastLogin
	 *
	 * Retrieves the date of the User's last login and sets it to the last_login data member.
	 *
	 * @access	public
	 */
	public function findLastLogin()
	{
		# Check if the data member is NULL.
		if($this->getLastLogin()===NULL)
		{
			# Check if the date is stored in a session.
			if(isset($_SESSION['user_last_login']))
			{
				# Set the data member.
				$this->setLastLogin($_SESSION['user_last_login']);
			}
		}
		return $this->getLastLogin();
	} #==== End -- findLastLogin

	/**
	 * findTitle
	 *
	 * Retrieves the User's title and sets it to the title data member.
	 *
	 * @access	public
	 */
	public function findTitle()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Try to get the title from the data member.
			$title=$this->getTitle();
			# Check if there is a title set.
			if(empty($title))
			{
				# Check if the title in a session.
				if(isset($_SESSION['user_title']))
				{
					# Set the title to the data member.
					$this->setTitle($_SESSION['user_title']);
				}
				else
				{
					# Find the User's ID.
					$this->findUserID();
					# Get the title from the Database.
					$row=$db->get_row('SELECT `title` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($this->getID()));
					# Check if a row was returned.
					if($row!==NULL)
					{
						# Set the display name to the data member.
						$this->setTitle($row->title);
					}
				}
			}
			return $this->getTitle();
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->message.'<br />Code: '.$ez->code.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findTitle

	/**
	 * findFirstName
	 *
	 * Retrieves the User's first name and sets it to the fname data member.
	 *
	 * @access	public
	 */
	public function findFirstName()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Attempt to retrieve the First Name from the data member.
			$fname=$this->getFirstName();
			# Check if there was a First Name retrieved.
			if(empty($fname))
			{
				# Is the First Name stored in a session?
				if(isset($_SESSION['user_fname']))
				{
					# Set the data member from the First Name stored in the session.
					$this->setFirstName($_SESSION['user_fname']);
				}
				else
				{
					# Get the logged in User's ID.
					$id=$this->findUserID();
					# Retrieve the First Name from the `users` table.
					$row=$db->get_row('SELECT `fname` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id));
					# Check if a row was returned.
					if($row!==NULL)
					{
						# Set the First Name to the data member.
						$this->setFirstName($row->fname);
					}
				}
				return $this->getFirstName();
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->message.'<br />Code: '.$ez->code.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findFirstName

	/**
	 * findLastName
	 *
	 * Retrieves the User's last name and sets it to the lname data member.
	 *
	 * @access	public
	 */
	public function findLastName()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Attempt to retrieve the Last Name from the data member.
			$lname=$this->getLastName();
			# Check if there was a Last Name retrieved.
			if(empty($lname))
			{
				# Is the Last Name stored in a session?
				if(isset($_SESSION['user_lname']))
				{
					# Set the data member from the Last Name stored in the session.
					$this->setLastName($_SESSION['user_lname']);
				}
				else
				{
					# Get the logged in User's ID.
					$id=$this->findUserID();
					# Retrieve the Last Name from the `users` table.
					$row=$db->get_row('SELECT `lname` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id));
					# Check if a row was returned.
					if($row!==NULL)
					{
						# Set the Last Name to the data member.
						$this->setLastName($row->lname);
					}
				}
			}
			return $this->getLastName();
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->message.'<br />Code: '.$ez->code.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findLastName

	/**
	 * findPassword
	 *
	 * Retrieves the User's password based on the passed variable. Throws an error on failure.
	 *
	 * @param	$field (The users Email or Username.)
	 * @access	public
	 */
	public function findPassword($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();
		try
		{
			# Check if the passed value is empty.
			if(empty($field))
			{
				# Set the password data member to a variable.
				$password=$this->getPassword();
				# Check if the data member is empty.
				if(!empty($password))
				{
					return $password;
				}
				# Create the "WHERE" portion of the sql query and set it to a variable.
				$where='`ID` = '.$db->quote($this->findUserID());
			}
			else
			{
				# Clean the passed value and set it to a new variable.
				$clean_field=$db->sanitize($field);
				$search_field='username';
				if($validator->validEmail($field)===TRUE)
				{
					$search_field='email';
				}
				$where='`'.$search_field.'` = '.$db->quote($db->escape($clean_field));
			}
			# Retrieve the password from the `users` table.
			$row=$db->get_row('SELECT `password` FROM `'.DBPREFIX.'users` WHERE '.$where.' LIMIT 1');
			# Check if there was a row returned.
			if($row!==NULL)
			{
				# Set the password to the data member.
				$this->setPassword($row->password);
				return $row->password;
			}
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error finding the user\'s password: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findPassword

	/**
	 * findPrivacySettings
	 *
	 * Retrieves the privacy settings of a given user.
	 *
	 * @param	  $username (The users username.)
	 * @access	public
	 */
	public function findPrivacySettings($username=NULL)
	{
		try
		{
			if(empty($username))
			{
				# Try to get the values from the data members.
				$newsletter=$this->getNewsletter();
				$notify=$this->getNotify();
				$questions=$this->getQuestions();
				# Check if every privacy data member is empty.
				if(!empty($newsletter) || !empty($notify) || !empty($questions))
				{
					# Set the returned data to the data members.
					return TRUE;
				}
				$username=$this->findUsername();
			}
			$privacy_set=$this->getPrivacySettings($username);
			if($privacy_set===TRUE)
			{
				return TRUE;
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
		return FALSE;
	} #==== End -- findPrivacySettings

	/**
	 * findProduct
	 *
	 * Retrieves the purchased products of a given user.
	 *
	 * @param		string	$field	(The user's ID or email. Empty will attempt to retrieve the data member. If the data member is empty it will try to find and use the user's ID.)
	 * @access	public
	 * @return	string
	 */
	public function findProduct($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			if(empty($field))
			{
				$product=$this->getProduct();
				if(!empty($product))
				{
					return $product;
				}
				$where='`ID` = '.$db->quote($this->findUserID());
			}
			elseif($validator->validEmail($field)===TRUE)
			{
				$where='`email` = '.$db->quote($db->escape(htmlspecialchars($field, ENT_QUOTES, 'UTF-8', FALSE)));
			}
			else
			{
				$where='`ID` = '.$db->quote($field);
			}
			try
			{
				$row=$db->get_row('SELECT `product` FROM `'.DBPREFIX.'users` WHERE '.$where.' LIMIT 1');

				$this->setProduct($row->product);
				return $this->getProduct();
			}
			catch(ezDB_Error $ez)
			{
				throw new Exception('There was an error retrieving the user\'s product: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findProduct

	/**
	 * findRegistered
	 *
	 * Retrieves the date the User registered and sets it to the registered data member.
	 *
	 * @access	public
	 */
	public function findRegistered()
	{
		$registered=$this->getRegistered();
		if($registered===NULL)
		{
			# Is the date stored in a session?
			if(isset($_SESSION['user_registered']))
			{
				$this->setRegistered($_SESSION['user_registered']);
				$registered=$this->getRegistered();
			}
		}
		return $registered;
	} #==== End -- findRegistered

	/**
	 * findStaffID
	 *
	 * Retrieves the staff ID from the `user` table.
	 *
	 * @param	$value					The user's ID.
	 *										If NULL, then the method gets the logged in user's ID.
	 * @access	public
	 */
	public function findStaffID($value=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed value is empty.
			if(empty($value))
			{
				# Find the user ID for the logged in user.
				$id=$this->findUserID();
			}
			else
			{
				# Check if the passed value is an integer.
				if($validator->isInt($value)===TRUE)
				{
					# Set the value to the ID data member effectively "cleaning" it.
					$this->setID($value);
					# Set the data member to a variable.
					$id=$this->getID();
				}
			}
			# Get the User DI from the Database.
			$row=$db->get_row('SELECT `staff_id` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($db->escape($id)).' LIMIT 1');
			# Check if a row was returned.
			if($row!==NULL)
			{
				# Set the user's staff ID to the data member.
				$this->setStaffID($row->staff_id);
				return $this->getStaffID();
			}
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error retrieveing the user\'s staff ID: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findStaffID

	/**
	 * findUserData
	 *
	 * Retrieves the data of a given user.
	 *
	 * @param	string $value			The user's username or id.
	 * @access	public
	 * @return	string
	 */
	public function findUserData($value=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed value is empty.
			if(empty($value))
			{
				# Find the user ID for the logged in user.
				$id=$this->findUserID();
			}
			else
			{
				# Check if the passed value is an integer.
				if($validator->isInt($value)===TRUE)
				{
					# Set the value to the ID data member effectively "cleaning" it.
					$this->setID($value);
					# Set the data member to a variable.
					$id=$this->getID();
				}
				elseif($validator->validEmail($value)===TRUE)
				{
					# Find the users username using the passed value as the email and set it to a variable.
					$username=$this->findUsername($value);
					# Find the user ID $username variable.
					$id=$this->findUserID($username);
					# Set the ID to the data member effectively "cleaning" it.
					$this->setID($id);
					# Reset the id variable from the data member.
					$id=$this->getID();
				}
				else
				{
					# Find the user ID using the passed value as the username and set it to a variable.
					$id=$this->findUserID($value);
					# Set the ID to the data member effectively "cleaning" it.
					$this->setID($id);
					# Reset the id variable from the data member.
					$id=$this->getID();
				}
			}
			# Check if the ID was found.
			if(!empty($id))
			{
				# Retrieve the User data from the `users` table.
				$row=$db->get_row('SELECT `ID`, `staff_id`, `display`, `username`, `level`, `title`, `fname`, `lname`, `email`, `region`, `address`, `address2`, `city`, `state`, `country`, `zipcode`, `phone`, `img`, `img_title`, `password`, `interests`, `bio`, `cv`, `organization`, `website`, `newsletter`, `notify`, `questions`, `active`, `product`, `registered`, `lastlogin` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($id).' LIMIT 1');
				# Check if there was a row returned.
				if($row!==NULL)
				{
					# Set the returned data to it's related data member.
					$this->setActive($row->active);
					$this->setAddress($row->address);
					$this->setAddress2($row->address2);
					$this->setBio($row->bio);
					$this->setCity($row->city);
					$this->setCountry($row->country);
					$this->setCV($row->cv);
					$this->setDisplayName($row->display);
					$this->setEmail($row->email);
					$this->setFirstName($row->fname);
					$this->setID($row->ID);
					$this->setInterests($row->interests);
					$this->setImg($row->img);
					$this->setImgTitle($row->img_title);
					$this->setIP($this->findIP());
					$this->setLastLogin($row->lastlogin);
					$this->setLastName($row->lname);
					$this->setNewsletter($row->newsletter);
					$this->setNotify($row->notify);
					$this->setOrganization($row->organization);
					$this->setPassword($row->password);
					$this->setPhone($row->phone);
					$this->setProduct($row->product);
					$this->setQuestions($row->questions);
					$this->setRegion($row->region);
					$this->setRegistered($row->registered);
					$this->setStaffID($row->staff_id);
					$this->setState($row->state);
					$this->setTitle($row->title);
					$this->setUserLevel($row->level);
					$this->setUsername($row->username);
					$this->setWebsite($row->website);
					$this->setZipcode($row->zipcode);
					return TRUE;
				}
			}
			return FALSE;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error retrieveing the user\'s data: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findUserData

	/**
	 * findUserID
	 *
	 * Retrieves the User's ID and sets it to the id data member. Throws an error on failure.
	 *
	 * @param	string $username			Optional - The user's username.
	 * @access	public
	 */
	public function findUserID($username=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if a username was passed.
			if(!empty($username))
			{
				# Clean the passed username.
				$username=$db->sanitize($username);
				# Get the User DI from the Database.
				$row=$db->get_row('SELECT `ID` FROM `'.DBPREFIX.'users` WHERE `username` = '.$db->quote($db->escape($username)));
				# Check if a row was returned.
				if($row!==NULL)
				{
					# Set the User ID to the data member.
					$this->setID($row->ID);
					# Set the username to the data member.
					$this->setUsername($username);
				}
			}
			else
			{
				# Get the ID data member and set it to a variable.
				$id=$this->getID();
				# Check if there was an ID set to the data member.
				if(empty($id))
				{
					# Check if the ID is stored in a cookie or a session.
					# Check if the ID is set in a cookie.
					if(isset($_COOKIE['cookie_id']))
					{
						# Set the ID to the data member effectively cleaning it.
						$this->setID($_COOKIE['cookie_id']);
					}
					# Check if the ID is set in a session.
					elseif(isset($_SESSION['user_id']))
					{
						# Set the ID to the data member effectively cleaning it.
						$this->setID($_SESSION['user_id']);
					}
				}
			}
			return $this->getID();
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was a Database error: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findUserID

	/**
	 * findUserLevel
	 *
	 * Retrieves the access level of a given user.
	 *
	 * @access	public
	 * @return string
	 */
	public function findUserLevel($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			if(empty($field))
			{
				$user_level=$this->getUserLevel();
				if(!empty($user_level))
				{
					return $user_level;
				}
				$where='`ID` = '.$db->quote($this->findUserID());
			}
			elseif($validator->validEmail($field)===TRUE)
			{
				$where='`email` = '.$db->quote($db->escape(htmlspecialchars($field, ENT_QUOTES, 'UTF-8', FALSE)));
			}
			else
			{
				$where='`ID` = '.$db->quote($field);
			}
			try
			{
				$row=$db->get_row('SELECT `level` FROM `'.DBPREFIX.'users` WHERE '.$where.' LIMIT 1');

				$this->setUserLevel($row->level);
				return $this->getUserLevel();
			}
			catch(ezDB_Error $ez)
			{
				throw new Exception('There was an error checking the user\'s access level: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findUserLevel

	/**
	 * findUsername
	 *
	 * Retrieves the User's username based on the passed variable. Throws an error on failure.
	 *
	 * @param	$field					The users Email or id.
	 * @access	public
	 */
	public function findUsername($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed value $field is empty.
			if(empty($field))
			{
				# Get the username data member and set it to a variable.
				$username=$this->getUsername();
				# Check if the data mamber was empty.
				if(!empty($username))
				{
					return $username;
				}
				# Create the WHERE portion of the sql query and set it to a variable.
				$where='`ID` = '.$db->quote($this->findUserID());
			}
			# Check if the passed $field is a valid email address.
			elseif($validator->validEmail($field)===TRUE)
			{
				# Create the WHERE portion of the sql query and set it to a variable.
				$where='`email` = '.$db->quote($db->escape(htmlspecialchars($field, ENT_QUOTES, 'UTF-8', FALSE)));
			}
			else
			{
				# Set the User ID to the data member effectively cleaning it.
				$this->setID($field);
				# Reset the $field variable from the data member.
				$field=$this->getID();
				# Create the WHERE portion of the sql query and set it to a variable.
				$where='`ID` = '.$db->quote($field);
			}
			# Get the username from the DB.
			$row=$db->get_row('SELECT `username` FROM `'.DBPREFIX.'users` WHERE '.$where.' LIMIT 1');
			# Check if there was a row returned.
			if($row!==NULL)
			{
				# Set the username to the data member.
				$this->setUsername($row->username);
				# Return the data member.
				return $this->getUsername();
			}
			else
			{
				throw new Exception('The User\'s username was not found!', E_RECOVERABLE_ERROR);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error finding the user\'s username: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- findUsername

	/**
	 * getOptInEmails()
	 *
	 * Retrieves User email addresses from the Database that have opted in to receiving news messages.
	 *
	 * @param 	$opt_in (The name of the table that the user has opted into.)
	 * @param 	$csv (Default is TRUE to return comma sepparated values. If FALSE, will return an array.)
	 * @access	public
	 */
	public function getOptInEmails($opt_in, $csv=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Explicitly make the $opt_in table an array.
		$opt_in=(array)$opt_in;
		# Create an empty array to hold th emails retrieved.
		$email_a=array();
		foreach($opt_in as $level)
		{
			try
			{
				# Retrieve the emails from the Database.
				$emails=$db->get_results('SELECT `id`, `email` FROM `'.DBPREFIX.'users` WHERE `notify` REGEXP '.$db->quote('-'.$level.'-'));
				# Check if there were any results.
				if($emails!==NULL)
				{
					# Loop throught the records.
					foreach($emails as $email)
					{
						# Check if the email address has already been set to the email array.
						if(!in_array($email, $email_a))
						{
							# Set the email to the email array.
							$email_a[$email->id]=$email->email;
						}
					}
				}
			}
			catch(ezDB_Error $ez)
			{
				throw new Exception('There was an error retrieving user emails: '.$ez->error.', code: '.$ez->errno.'<br />
				Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		# Set the email array to the $asp_recipients variable.
		$recipients=$email_a;
		# Check if comma sepparated values should be returned.
		if($csv===TRUE)
		{
			# Convert the email array to a string of comma sepparated values.
			$recipients=implode(',', $email_a);
		}

		return $recipients;
	} #==== End -- getOptInEmails

	/**
	 * getPrivacySettings
	 *
	 * Retrieves the privacy settings of a given user from the `usres` table and sets the values to the data members.
	 *
	 * @param	  $username (The users username.)
	 * @access	public
	 */
	public function getPrivacySettings($username)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			if(empty($username))
			{
				$username=$this->findUsername();
			}
			$row=$db->get_row('SELECT `newsletter`, `notify`, `questions` FROM `'.DBPREFIX.'users` WHERE `username` = '.$db->quote($db->escape(htmlspecialchars($username, ENT_QUOTES, 'UTF-8', FALSE))).' LIMIT 1');
			if($row!==NULL)
			{
				# Set the returned data to the data members.
				$this->setNewsletter($row->newsletter);
				$this->setNotify($row->notify);
				$this->setQuestions($row->questions);
				return TRUE;
			}
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error retrieveing the user\'s privacy settings: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		return FALSE;
	} #==== End -- getPrivacySettings

	/**
	 * getSubscriptions
	 *
	 * Retrieves all subscriptions for the passed user ID. A wrapper method for getSubscriptions() from the Subscription calss.
	 *
	 * @access	public
	 */
	public function getSubscriptions($user_id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if the passed ID is empty.
		if(empty($user_id))
		{
			# Find the user's ID and set it to a variable.
			$user_id=$this->findUserID();
		}
		# Get the Subscription class.
		require_once Utility::locateFile(MODULES.'Product'.DS.'Subscription.php');
		# Instantiate a new Subscription object.
		$subscription=new Subscription();
		# Get any subscriptions this user has.
		$subscription->getSubscriptions(NULL, '`id`, `name`, `date`', 'id', 'ASC', ' WHERE `user` = '.$db->quote($db->escape($user_id)));
		$this->setAllSubscriptions($subscription->getAllSubscriptions());
		return $this->getAllSubscriptions();
	} #==== End -- getSubscriptions

	/**
	 * getUsers
	 *
	 * Retrieves User records from the DataBase.
	 *
	 * @param	$limit (The LIMIT of the records.)
	 * @param	$fields (The name of the field(s) to be retrieved.)
	 * @param	$order (The name of the field to order the records by.)
	 * @param	$direction (The direction to order the records.)
	 * @access	public
	 */
	public function getUsers($limit=NULL, $fields='*', $order='ID', $direction='DESC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'users`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			$this->setAllUsers($records);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception($ez, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getUsers

	/**
	 * isStaff
	 *
	 * Will try to determine if the logged in user is staff or not.
	 * A wrapper method for the isStaff method from the Staff class.
	 *
	 * @param	int $value				The user's ID.
	 *										If NULL, then the method gets the logged in user's ID.
	 * @access	public
	 * @return	boolean
	 */
	public function isStaff($value=NULL)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Get the Staff class.
		require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');
		# Set the Staff instance to a variable.
		$staff_obj=new Staff();

		# Check if the passed value is empty.
		if(empty($value))
		{
			# Find the user ID for the logged in user.
			$id=$this->findUserID();
		}
		else
		{
			# Check if the passed value is an integer.
			if($validator->isInt($value)===TRUE)
			{
				# Set the value to the ID data member effectively "cleaning" it.
				$this->setID($value);
				# Set the data member to a variable.
				$id=$this->getID();
			}
		}
		# Check if logged in user is in the `staff` table.
		if($staff_obj->isStaff($id)===TRUE)
		{
			return TRUE;
		}
		return FALSE;
	} #==== End -- isStaff

	/**
	 * processAuthRequest
	 *
	 * Emails the appropriate admin/manager of a request for authorization on an aspect of the site.
	 *
	 * @param	array $fields			An array where the key is the POST Data field to check and the value is the email address to send the request to.
	 *										The Value may be an array as well.
	 * @access	public
	 */
	public function processAuthRequest($fields)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$username=$this->findUsername();
		$id=$this->findUserID();
		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			$message='';
			foreach($fields as $value=>$emails)
			{
				$emails=(array)$emails;
				foreach($emails as $email)
				{
					if(isset($_POST[$value]))
					{
						# Update the database with the new request status.
						$update_request=$db->query('UPDATE `'.DBPREFIX.'users` SET `'.$value.'` = '.$db->quote('1').'
							WHERE `ID` = '.$db->quote($id).' LIMIT 1');
						$to=$email;
						$subject=DOMAIN_NAME.': A request for '.$value.' authorization.';
						$body='The user, '.$username.' has requested to be authorized on '.$value.'<br />';
						$body.='Please log into your account at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a> to process this authorization request at your earliest convenience.<br />';
						$body.='Thank you';
						$doc->sendEmail($subject, $to, $body);
						$message.='You have requested to be authorized to add content to or edit '.strtoupper($value).'<br />';
					}
				}
			}
			$doc->setError($message);
		}
	} #==== End -- processAuthRequest

	/**
	 * updateUser
	 *
	 * Updates the User's record in the DataBase.
	 *
	 * @param	array $where_field		Key= the field, Value= the field value.
	 * @param	array $field_value		Key= the field, Value= the field value.
	 * @access	public
	 */
	public function updateUser($where_field, $field_value)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Create an empty array to hold the "SET" values.
			$set_array=array();
			# Loop through the field_value array.
			foreach($field_value as $field=>$value)
			{
				# Check if the $value is empty.
				if(!empty($value))
				{
					# Clean it up for sql.
					$value=$db->quote($db->escape($value));
				}
				else
				{
					# Check if the field is "bio".
					if($field=='bio')
					{
						# Explicitly update the value to nothing.
						$value=$db->quote($db->escape(''));
					}
					elseif(($field=='questions' OR $field=='newsletter' OR $field=='active') && ($value===0 OR $value==='0'))
					{
						# Explicitly update the value to 0.
						$value=$db->quote(0);
					}
					else
					{
						# Explicitly update the value to NULL.
						$value='NULL';
					}
				}
				# Set the sql to the array.
				$set_array[]='`'.$field.'` = '.$value;
			}
			# Implode the set array to a string of comma separated values.
			$set=implode(', ', $set_array);

			# Create an empty array to hold the "WHERE" values.
			$where_array=array();
			# Loop through the field_value array.
			foreach($where_field as $field=>$value)
			{
				# Check if the $value is empty.
				if($value!==NULL)
				{
					# Clean it up for sql.
					$value=$db->quote($db->escape($value));
				}
				else
				{
					# Explicitly update the value to NULL.
					$value='NULL';
				}
				# Set the sql to the array.
				$where_array[]='`'.$field.'` = '.$value;
			}
			# Implode the where array to a string of "AND" separated values.
			$where=implode(' AND ', $where_array);
			# Update the User's data in the `users` table.
			$update_user=$db->query('UPDATE `'.DBPREFIX.'users` SET '.$set.' WHERE '.$where.' LIMIT 1');
			return $update_user;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error updating User info: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- updateUser

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * ipFirst - let's get a clean ip
	 *
	 * @access	public
	 * @param		$ips (The IP address to clean)
	 * @return	string
	 */
	protected function ipFirst($ips)
	{
		# Check if there is a comma and set it's position to a variable.
		if(($pos=strpos($ips, ',')) !== FALSE)
		{
			# Return everything before the comma.
			return substr($ips, 0, $pos);
		}
		else { return $ips; }
	} #==== End -- ipFirst

	/**
	 * ipValid
	 *
	 * Will try to determine if a given ip is valid or not.
	 * A wrapper method for the ipValid method from the Validator class.
	 *
	 * @access	public
	 * @param		$ips (The IP address to validate)
	 * @return	bool
	 */
	protected function ipValid($ips)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if it is a valid IP address.
		if($validator->ipValid($ips)===TRUE)
		{
			return TRUE;
		}
		else { return FALSE; }
	} #==== End -- ipValid

	/*** End protected methods ***/

} # End User class.