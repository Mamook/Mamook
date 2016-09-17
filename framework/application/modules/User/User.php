<?php /* framework/application/modules/User/User.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

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
	protected $all_subscriptions=NULL;
	protected $all_users;
	protected $bio=NULL;
	protected $city=NULL;
	protected $country=NULL;
	protected $cv=NULL;
	protected $display_name=NULL;
	protected $email=NULL;
	protected $first_name=NULL;
	protected $id=NULL;
	protected $img=NULL;
	protected $img_title=NULL;
	protected $interests=NULL;
	protected $ip=NULL;
	protected $last_login='0000-00-00';
	protected $last_name=NULL;
	protected $level=NULL;
	protected $newsletter;
	protected $nickname=NULL;
	protected $notify=NULL;
	protected $organization=NULL;
	protected $password=NULL;
	protected $phone=NULL;
	protected $post_login=NULL;
	protected $product=NULL;
	protected $questions=NULL;
	protected $random=NULL;
	protected $region=NULL;
	protected $registered='0000-00-00';
	protected $staff_id=NULL;
	protected $state=NULL;
	protected $title=NULL;
	protected $username=NULL;
	protected $website=NULL;
	protected $wp_password=NULL;
	protected $zipcode=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * Sets the data member $active.
	 *
	 * @param mixed $active Account status. 0=Not Activated, 1=Activated, 2=Suspended.
	 * @throws Exception
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
	}

	/**
	 * Sets the data member $address.
	 *
	 * @param string $address The User's address.
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
	}

	/**
	 * Sets the data member $address2.
	 *
	 * @param string $address2 The User's address2.
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
	}

	/**
	 * Sets the data member $all_subscriptions.
	 *
	 * @param array $subscriptions The User's subscriptions.
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
	}

	/**
	 * Sets the data member $bio.
	 *
	 * @param string $bio The User's biographical information.
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
			$bio=str_ireplace(array(
				'%{domain_name}',
				'%{fw_popup_handle}'
			), array(
				DOMAIN_NAME,
				FW_POPUP_HANDLE
			), $bio);
		}
		else
		{
			# Explicitly set it to NULL.
			$bio=NULL;
		}
		# Set the data member.
		$this->bio=$bio;
	}

	/**
	 * Sets the data member $city.
	 *
	 * @param string $city The User's city.
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
	}

	/**
	 * Sets the data member $country.
	 *
	 * @param string $country The User's country.
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
	}

	/**
	 * Sets the data member $cv.
	 *
	 * @param  string $cv The User's cv file.
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
	}

	/**
	 * setDisplayName
	 *
	 * Sets the data member $display_name.
	 *
	 * @param string $display_name The User's display name.
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
	}

	/**
	 * Sets the data member $email.
	 *
	 * @param string $email The User's Email address.
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
	}

	/**
	 * Sets the data member $first_name.
	 *
	 * @param string $first_name The User's first name.
	 */
	public function setFirstName($first_name)
	{
		# Check if the value is empty.
		if(!empty($first_name))
		{
			# Clean it up and set the data member.
			$first_name=trim($first_name);
		}
		else
		{
			# Explicitly set it to NULL.
			$first_name=NULL;
		}
		# Set the data member.
		$this->first_name=$first_name;
	}

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param int $id The User's ID number.
	 * @throws Exception
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
	}

	/**
	 * Sets the data member $img.
	 *
	 * @param string $img The User's avatar image
	 */
	public function setImg($img)
	{
		# Check if the value is empty.
		$img=!empty($img) ? $img : NULL;
		# Set the data member.
		$this->img=$img;
	}

	/**
	 * Sets the data member $img_title.
	 *
	 * @param string $img_title The title of the User's image
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
	}

	/**
	 * Sets the data member $interests.
	 *
	 * @param string $interests The User's interests.
	 */
	public function setInterests($interests)
	{
		# Check if the value is empty.
		if(!empty($interests))
		{
			# Clean it up and set the data member.
			$interests=trim($interests);
			# Replace any tokens with their correlating value.
			$interests=str_ireplace(array(
				'%{domain_name}',
				'%{fw_popup_handle}'
			), array(
				DOMAIN_NAME,
				FW_POPUP_HANDLE
			), $interests);
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
	}

	/**
	 * Sets the data member $last_login.
	 *
	 * @param string $last_login The date the user last logged in.
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
	}

	/**
	 * Sets the data member $last_name.
	 *
	 * @param string $last_name The User's last name.
	 */
	public function setLastName($last_name)
	{
		# Check if the value is empty.
		if(!empty($last_name))
		{
			# Clean it up and set the data member.
			$last_name=trim($last_name);
		}
		else
		{
			# Explicitly set it to NULL.
			$last_name=NULL;
		}
		# Set the data member.
		$this->last_name=$last_name;
	}

	/**
	 * setNewsletter
	 *
	 * Sets the data member $newsletter.
	 *
	 * @param int $newsletter If the User recieves the newsletter.
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
	}

	/**
	 * Sets the data member $nickname.
	 *
	 * @param string $nickname The User's nickname. This is only used in WordPress instalations.
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
	}

	/**
	 * Sets the data member $notify.
	 *
	 * @param string $notify May be an array or string of branch id's the user wishes to be notified about.
	 *                       If a string, the id's must be separated with a dash('-').
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
	}

	/**
	 * Sets the data member $organization.
	 *
	 * @param string $organization The User's organization.
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
	}

	/**
	 * Sets the data member $password.
	 *
	 * @param string $password The User's password.
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
	}

	/**
	 * Sets the data member $phone.
	 *
	 * @param string $phone The User's phone number.
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
	}

	/**
	 * Sets the data member $post_login.
	 *
	 * @param string $url The url to redirect the User to.
	 */
	public function setPostLogin($url)
	{
		if(!empty($url))
		{
			$this->post_login=trim($url);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->post_login=NULL;
		}
	}

	/**
	 * Sets the data member $product.
	 *
	 * @param string $product The User's subscriptions.
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
	}

	/**
	 * Sets the data member $questions.
	 *
	 * @param int $questions 0 if the User will accept emails from other users, NULL if not.
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
	}

	/**
	 * Sets the data member $random.
	 *
	 * @param string $random A random generated value.
	 */
	public function setRandom($random)
	{
		# Check if the value is empty.
		if(!empty($random))
		{
			# Set the data member.
			$random=trim($random);
		}
		else
		{
			# Explicitly set it to NULL.
			$random=NULL;
		}
		# Set the data member.
		$this->random=$random;
	}

	/**
	 * Sets the data member $region.
	 *
	 * @param string $region The User's region.
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
	}

	/**
	 * Sets the data member $registered.
	 *
	 * @param string $registered The date the user registered.
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
	}

	/**
	 * Sets the data member $state.
	 *
	 * @param string $state The User's state.
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
	}

	/**
	 * Sets the data member $title.
	 *
	 * @param string $title The User's title.
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
	}

	/**
	 * Sets the data member $level.
	 *
	 * @param string $level The User's access level.
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
	}

	/**
	 * Sets the data member $username.
	 *
	 * @param string $username The User's username.
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
	}

	/**
	 * Sets the data member $website.
	 *
	 * @param string $website The User's website.
	 */
	public function setWebsite($website)
	{
		# Check if the value is empty.
		if(!empty($website))
		{
			# Clean it up.
			$website=trim($website);
			# Replace any tokens with their correlating value.
			$website=str_ireplace(array(
				'%{domain_name}',
				'%{fw_popup_handle}'
			), array(
				DOMAIN_NAME,
				FW_POPUP_HANDLE
			), $website);
		}
		else
		{
			# Explicitly set it to NULL.
			$website=NULL;
		}
		# Set the data member.
		$this->website=$website;
	}

	/**
	 * Sets the data member $wp_password.
	 *
	 * @param string $wp_password The User's encoded password.
	 */
	public function setWPPassword($wp_password)
	{
		# Check if the value is empty.
		if(!empty($wp_password))
		{
			# Clean it up and set the data member.
			$wp_password=trim($wp_password);
		}
		else
		{
			# Explicitly set it to NULL.
			$wp_password=NULL;
		}
		# Set the data member.
		$this->wp_password=$wp_password;
	}

	/**
	 * Sets the data member $zipcode.
	 *
	 * @param string $zipcode The User's zipcode.
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
	}

	/**
	 * Returns the data member $active.
	 * Throws an error on failure.
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Returns the data member $address.
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Returns the data member $address2.
	 */
	public function getAddress2()
	{
		return $this->address2;
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * Returns the data member $all_subscriptions.
	 */
	public function getAllSubscriptions()
	{
		return $this->all_subscriptions;
	}

	/**
	 * Returns the data member $all_users.
	 */
	public function getAllUsers()
	{
		return $this->all_users;
	}

	/**
	 * Returns the data member $bio.
	 */
	public function getBio()
	{
		return $this->bio;
	}

	/**
	 * Returns the data member $city.
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Returns the data member $country.
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Returns the data member $cv.
	 */
	public function getCV()
	{
		return $this->cv;
	}

	/**
	 * Returns the data member $display_name.
	 */
	public function getDisplayName()
	{
		return $this->display_name;
	}

	/**
	 * Returns the data member $email.
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Returns the data member $first_name.
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * Returns the data member $id.
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * Returns the data member $img.
	 */
	public function getImg()
	{
		return $this->img;
	}

	/**
	 * Returns the data member $img_title.
	 */
	public function getImgTitle()
	{
		return $this->img_title;
	}

	/**
	 * Returns the data member $interests.
	 */
	public function getInterests()
	{
		return $this->interests;
	}

	/**
	 * Returns the data member $ip.
	 */
	public function getIP()
	{
		return $this->ip;
	}

	/**
	 * Returns the data member $last_login. Throws an error on failure.
	 */
	public function getLastLogin()
	{
		$last_login=$this->last_login;
		if($this->last_login=='0000-00-00')
		{
			$last_login='never';
		}

		return $last_login;
	}

	/**
	 * Returns the data member $last_name. Throws an error on failure.
	 */
	public function getLastName()
	{
		return $this->last_name;
	}

	/**
	 * Returns the data member $newsletter.
	 */
	public function getNewsletter()
	{
		return $this->newsletter;
	}

	/**
	 * Returns the data member $nickname.
	 */
	public function getNickname()
	{
		return $this->nickname;
	}

	/**
	 * Returns the data member $notify.
	 */
	public function getNotify()
	{
		return $this->notify;
	}

	/**
	 * Returns the data member $organization.
	 */
	public function getOrganization()
	{
		return $this->organization;
	}

	/**
	 * Returns the data member $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Returns the data member $phone.
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * Returns the data member $post_login. Throws an error on failure.
	 */
	public function getPostLogin()
	{
		return $this->post_login;
	}

	/**
	 * Returns the data member $product.
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * Returns the data member $questions.
	 */
	public function getQuestions()
	{
		return $this->questions;
	}

	/**
	 * Returns the data member $region.
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * Returns the data member $registered. Throws an error on failure.
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
	}

	/**
	 * Returns the data member $staff_id.
	 */
	public function getStaffID()
	{
		return $this->staff_id;
	}

	/**
	 * Returns the data member $state.
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * Returns the data member $title.
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns the data member $level.
	 */
	public function getUserLevel()
	{
		return $this->level;
	}

	/**
	 * Returns the data member $username.
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Returns the data member $website.
	 */
	public function getWebsite()
	{
		return $this->website;
	}

	/**
	 * Returns the data member $wp_password.
	 */
	public function getWPPassword()
	{
		return $this->wp_password;
	}

	/**
	 * Returns the data member $zipcode.
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	}

	/**
	 * Captures post(after) login data sent from the previous page.
	 */
	public function capturePostLogin()
	{
		# Set the Session instance to a variable.
		$session=Session::getInstance();

		# Create an empty variable to hold post login.
		$post_login=NULL;

		# Is it in a session?
		if(isset($_SESSION['_post_login']) && !empty($_SESSION['_post_login']))
		{
			# Get the post login from a session.
			$post_login=$_SESSION['_post_login'];
		}

		# Is it in POST data?
		if(isset($_POST['_post_login']))
		{
			# Get the post login from POST data (This has precedence over $_SESSION['post_login'].)
			$post_login=$_POST['_post_login'];
		}

		# Get the PayPal Class.
		require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');
		# Create a new PayPal object.
		$paypal=new PayPal();
		# If user is coming from a PayPal button, catch the POST data so we can redirect to PayPal after logging in. This has precedence over $_POST['post_login'].
		if($paypal->getPayPalPOST()!==FALSE)
		{
			$post_login=$paypal->getPayPalPOST();
		}
		$this->setPostLogin($post_login);
		# Reset it to the session.
		$session->setPostLogin($this->getPostLogin());
	}

	/**
	 * Checks the user's level and compares it to the passed access levels.
	 *
	 * @param string $access_levels The level number(s) to accept - ie. '1 2 5'
	 * @param int $id               Optional.
	 * @return bool
	 * @throws Exception
	 */
	public function checkAccess($access_levels, $id=NULL)
	{
		# Split the access level string at spaces(' ') and set each piece to the $level_a array.
		$level_a=explode(' ', $access_levels);

		# Assume access is FALSE. Make the method prove it.
		$access=FALSE;

		# Check if the User is logged in.
		if($this->isLoggedIn()===TRUE)
		{
			try
			{
				# Check the User's level access.
				$levels=(array)$this->findUserLevel($id);

				# Loop through the User's levels.
				foreach($levels as $level)
				{
					# Set the general_level variable to a crazy default that will NEVER match.
					$general_level='Orange Apples';
					# Check if the level is more than one digit.
					if(strlen($level)>1)
					{
						$general_level=substr($level, 0, -1).'0';
					}
					# Check if the User's level is in the level array ($level_a).
					if(in_array($level, $level_a) OR in_array($general_level, $level_a))
					{
						# Grant the User access.
						$access=TRUE;
					}
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}

		return $access;
	}

	/**
	 * checkLogin
	 *
	 * Applies restrictions to visitors based on membership and level access
	 * Also handles cookie based "remember me" feature
	 *
	 * @param string $levels The access_level number(s) to accept - ie. '1 2 5'
	 */
	public function checkLogin($levels)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Check the user's access.
		$access=$this->checkAccess($levels);

		#  If $access is FALSE, send them to the login page (Prevents login page from looping continuously.).
		if(($access===FALSE) && (strpos(Utility::removeIndex(LOGIN_PAGE), Utility::removeIndex(FULL_URL))===FALSE))
		{
			# Check if the user is logged in already.
			if($this->isLoggedIn()===FALSE)
			{
				# Send them to the login page
				$doc->redirect(REDIRECT_TO_LOGIN);
			}
			else
			{
				# Let the user know why they were redirected.
				$_SESSION['message']='You do not have permission to access that page.';
				# Redirect them.
				$doc->redirect(REDIRECT_AFTER_LOGIN);
			}
		}
		# Prevent login page from being accessed while logged in.
		elseif(($access===TRUE) && (strpos(Utility::removeIndex(LOGIN_PAGE), Utility::removeIndex(FULL_URL))!==FALSE))
		{
			$doc->redirect(REDIRECT_AFTER_LOGIN);
		}
	}

	/*** End accessor methods ***/

	/*** public methods ***/

	/**
	 * Performs a check to determine if one parameter is unique in the Database.
	 * Returns FALSE if the value is already in the Database.
	 *
	 * @param string $field    The field to look in.
	 * @param string $compared The value to check.
	 * @param string $params   Any extra parameters.
	 * @return bool
	 */
	public function checkUnique($field, $compared, $params='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$check=$db->query("SELECT `".$field."` FROM `".DBPREFIX."users` WHERE `".$field."` = ".$db->quote($db->escape($compared)).$params);

		return (($check==0) ? TRUE : FALSE);
	}

	/**
	 * Clears the cookies
	 * Not used by default but present if needed
	 *
	 * TODO: MOVE TO WEBUTILITIES
	 */
	public function clearCookies()
	{
		# Unset cookies
		if(isset($_SERVER['HTTP_COOKIE']))
		{
			$cookies=explode(';', $_SERVER['HTTP_COOKIE']);
			# Loop through the array of cookies and set them in the past
			foreach($cookies as $cookie)
			{
				$parts=explode('=', $cookie);
				$name=trim($parts[0]);
				setcookie($name, '', time()-LOGIN_LIFE);
				setcookie($name, '', time()-LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			}
		}
	}

	/**
	 * Returns the number of users in the database.
	 *
	 * @param string $and_sql Extra AND statements in the query.
	 * @throws Exception
	 */
	public function countUsers($and_sql=NULL)
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
	}

	/**
	 * Activates user's newsletter subscription.
	 */
	public function confirmNewsletter()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if there is GET Data and that we have valid ID and random key.
		if((strtoupper($_SERVER['REQUEST_METHOD'])=='GET') && !empty($_GET['ID']) && ($validator->isNumber($_GET['ID'])===TRUE))
		{
			# Set ID to a variable.
			$user_id=(int)$_GET['ID'];
			# Get user data from the DB
			$username=$this->findUsername($user_id);
			# Does this user exist?
			if($this->findUserData($username)!==FALSE)
			{
				try
				{
					$row=$db->get_row('SELECT `user_id` FROM `'.DBPREFIX.'user_newsletter` WHERE `user_id`='.$db->quote($user_id).' LIMIT 1');
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('There was an error retrieving the "user_id" field for '.$username.' from the Database: '.$ez->error.', code: '.$ez->errno.'<br />
					Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
				}
				# User was not found in the `user_newsletter` table.
				if($row===NULL)
				{
					# Has the user opted-in, but not confirmed?
					if((int)$this->getNewsletter()===1)
					{
						try
						{
							# Activate the user's into the `users` table.
							$db->query('UPDATE '.DBPREFIX.'`users` SET'.
								' `newsletter`='.$db->quote(0).
								' WHERE'.
								' `ID`='.$db->quote($user_id).
								' LIMIT 1'
							);
							# Set the IP address to a variable.
							#	findIP() passes the IP to the $validator->ipValid().
							#	ipValid() checks if the IP is valid, and if it's an IPv4 or IPv6 address, then it sets the version number.
							$ip=$this->findIP(TRUE);
							# Insert user into the `user_newsletter` table.
							$db->query('INSERT INTO '.DBPREFIX.'`user_newsletter` (`user_id`, `ip`) VALUES ('.
								$db->quote($user_id).
								', '.$ip.
								')');
							$_SESSION['message']='Congratulations! You just confirmed your newsletter subscription with '.DOMAIN_NAME.'!';
							$doc->redirect(REDIRECT_AFTER_LOGIN);
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('There was an error confirming '.$username.'\'s newsletter subscription in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					# `newsletter` field is not set to 1, so the user has not opted-in yet.
					elseif((int)$this->getNewsletter()!==1)
					{
						$_SESSION['message']='You have not opted-in to receive the newsletter.<br>'.
							'Go to your <a href="'.SECURE_URL.'MyAccount/privacy.php">Privacy Settings</a> page and opt-in.';
						$doc->redirect(REDIRECT_AFTER_LOGIN);
					}
				}
				else
				{
					$_SESSION['message']='You have already confirmed your newsletter subscription.';
					$doc->redirect(DEFAULT_REDIRECT);
				}
			}
			else
			{
				$_SESSION['message']='User not found!';
				$doc->redirect(DEFAULT_REDIRECT);
			}
		}
		else
		{
			$_SESSION['message']='There was an error processing your newsletter subscription. Please copy the the confirmation link that was sent to you in your email and paste it into your browser if clicking on the link isn\'t working. If you are still having issues, write to the <a href="'.APPLICATION_URL.'webSupport/" title="Write to webSupport.">webmaster by clicking here</a>. Please give details as to what you are seeing (or not seeing) and any errors that may be displayed.';
			$doc->redirect(DEFAULT_REDIRECT);
		}
	}

	/**
	 * Description
	 *
	 * @param int $user_id   The user's ID.
	 * @param bool $redirect Do we do a redirect?
	 * @throws Exception
	 */
	public function unsubscribeNewsletter($user_id, $redirect=FALSE)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			$db->query('DELETE FROM `'.DBPREFIX.'user_newsletter` WHERE `user_id`='.$db->quote($user_id).' LIMIT 1');
			# Redirect user, and give them a message.
			if($redirect)
			{
				$_SESSION['message']='You have unsubscribed from the '.DOMAIN_NAME.' newsletter.';
				$doc->redirect(DEFAULT_REDIRECT);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error unsubscribing user '.$user_id.': '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End accessor methods ***/

	/*** public methods ***/

	/**
	 * Creates a new account in the database.
	 */
	public function createAccount()
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			# Set the email data member to a variable.
			$email=$this->getEmail();
			# Set the IP address to a variable.
			#	findIP() passes the IP to the $validator->ipValid().
			#	ipValid() checks if the IP is valid, and if it's an IPv4 or IPv6 address, then it sets the version number.
			$ip=$this->findIP(TRUE);
			# Set the password data member to a variable.
			$password=$this->getPassword();
			# Set username data member to a variable.
			$username=$this->getUsername();

			# Insert user into the `users` table.
			$insert_user=$db->query('INSERT INTO `'.DBPREFIX.'users` (`display`, `username`, `email`, `password`, `random`, `registered`, `reg_ip`) VALUES ('.$db->quote($db->escape($username)).', '.$db->quote($db->escape($username)).', '.$db->quote($db->escape($email)).', '.$db->quote($db->escape($password)).', '.$db->quote($db->escape($this->randomString('alnum', 32))).', '.$db->quote($db->escape(YEAR_MM_DD)).', '.$db->quote($db->escape($ip)).')');
			# If WordPress is installed add the user the the WordPress users table.
			if(WP_INSTALLED===TRUE)
			{
				# Get the wordpress password.
				$this->createWP_User();
			}
			# Account was not created. Return error.
			if($insert_user<=0)
			{
				$_SESSION['message']='There was an error registering your account. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
				$doc->redirect(REDIRECT_TO_LOGIN);
			}
			$this->sendActivationEmail($email, TRUE);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error registering a new user: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Creates a WordPress user in the WordPress Database tables.
	 * Assumes a user was just created in the main users table.
	 */
	public function createWP_User()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Format the password
		$this->ecodeWP_Password();

		# Get the username.
		$username=$this->getUsername();
		# Get the email address.
		$email=$this->getEmail();
		# Get the password.
		$wp_password=$this->getWPPassword();

		try
		{
			$db->query('INSERT INTO `'.WP_DBPREFIX.'users` (`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES ('.$db->quote($db->escape($username)).', '.$db->quote($db->escape($wp_password)).', '.$db->quote($db->escape($username)).', '.$db->quote($db->escape($email)).', '.$db->quote($db->escape('')).', '.$db->quote($db->escape(YEAR_MM_DD_TIME)).', '.$db->quote($db->escape('')).', '.$db->quote($db->escape('0')).', '.$db->quote($db->escape($username)).')');
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error inserting the new WordPress user info for "'.$username.'" into the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		$row=$db->get_row('SELECT `ID` FROM `'.WP_DBPREFIX.'users` WHERE `user_login` = '.$db->quote($db->escape($username)).' LIMIT 1');

		$wp_user_id=$row->ID;
		$meta_data=array(
			WP_DBPREFIX.'user_level'=>0,
			WP_DBPREFIX.'capabilities'=>'a:1:{s:10:"subscriber";b:1;}',
			'nickname'=>$username,
			'rich_editing'=>'true',
			'comment_shortcuts'=>'false',
			'admin_color'=>'fresh',
			'use_ssl'=>0,
			's2_excerpt'=>'excerpt',
			's2_format'=>'text'
		);
		try
		{
			foreach($meta_data as $meta_key=>$meta_value)
			{
				$insert_user_meta=$db->query('INSERT INTO `'.WP_DBPREFIX.'usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('.$db->quote($wp_user_id).', '.$db->quote($db->escape($meta_key)).', '.$db->quote($db->escape($meta_value)).')');
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error inserting the new WordPress usermeta info for "'.$username.'" into the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Delete's the user's account.
	 *
	 * @param mixed $id                The User's ID.
	 *                                 Can be an array of users to delete.
	 * @throws Exception
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
		# An array of users was passed into the method.
		elseif(is_array($id))
		{
			# Create where statement.
			$where=' IN ('.implode(', ', $id).')';
		}
		# Check if $id is an integer.
		if($validator->isInt($id)===TRUE)
		{
			# Create where statement.
			$where=' = '.$db->quote($id).' LIMIT 1';
		}
		try
		{
			# Get the Contributor class.
			require Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
			# Instantiate a new Contributor object.
			$contributor_obj=new Contributor();
			# Set the User ID to NULL in the `contributors` table.
			$contributor_obj->removeUser($id);

			# NOTE: There is no removeUser() method in the Staff class.
			/*
			# Get the Staff class.
			require Utility::locateFile(MODULES.'User'.DS.'Staff.php');
			# Instantiate a new Staff object.
			$staff_obj=new Staff();
			# Set the User ID to NULL in the `staff` table.
			$staff_obj->removeUser($id);
			*/

			# NOTE BY DRAVEN: There is no `subscriptions` table...?
			/*
			# Get the Subscription class.
			require Utility::locateFile(MODULES.'Product'.DS.'Subscription.php');
			# Instantiate a new Subscription object.
			$subscription_obj=new Subscription();
			# Attempt to retrieve this User from the `subscriptions` table.
			$user_retrieved=$subscription_obj->countAllSubscriptions(NULL, NULL, ' AND `user` = '.$db->quote($id));
			# Check if the User was retrieved.
			if($user_retrieved>0)
			{
				# Delete the User's subscriptions from the `subscriptions` table.
				$subscription_obj->removeUser($id);
			}
			*/

			# Get the Comment class.
			require Utility::locateFile(MODULES.'Content'.DS.'Comment.php');
			# Instantiate a new Comment object.
			$comment_obj=new Comment();
			# Set the User ID to NULL in the `comments` table.
			$comment_obj->removeUser($id);

			# Remove the user from the user_inactive table.
			$this->deleteInactiveUser($id);

			# check if there is a WordPress installation.
			if(WP_INSTALLED===TRUE)
			{
				# Get the WordPressUser class.
				require Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
				# Instantiate a new WordPressUser object.
				$wp_obj=new WordPressUser();

				if($validator->isInt($id)===TRUE)
				{
					# NOTE BY DRAVEN: Why reassign? If the user is not found then their display name should be "Unknown User".
					# Find the User ID for "Unknown User" and set it to a variable.
					//$unknown_id=$this->findUserID('unknown');
					# Delete the User from the WordPress installation and reassign their posts to "Unknown User".
					//$wp_obj->deleteWP_User($id, $unknown_id);

					# Get user's username.
					$username=$this->findUsername($id);
					# Get user's WP ID.
					$wp_id=$wp_obj->getWP_UserID($username);
					# Delete the User from the WordPress installation.
					$wp_obj->deleteWP_User($wp_id);
				}
				else
				{
					# Loop through the users.
					foreach($id as $key=>$user_id)
					{
						# Get user's username.
						$username=$this->findUsername($user_id);
						# Get user's WP ID.
						$wp_id=$wp_obj->getWP_UserID($username);
						# If the user exists in the Wordpress users table.
						if($wp_id!==NULL)
						{
							# Change the `users` ID in the $id array to the Wordpress ID.
							$id[$key]=$wp_id;
						}
						else
						{
							# User does not exist in Wordpress so unset the array element.
							unset($id[$key]);
						}
					}
					# Delete the User from the WordPress installation.
					$wp_obj->deleteWP_User($id);
				}
			}

			if(isset($where))
			{
				# Delete from the users table.
				$db->query('DELETE FROM `'.DBPREFIX.'users` WHERE `ID`'.$where);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error deleting User ID# '.$id.' from the Database: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	}

	/**
	 * Retrieves the members information from the database and displays it.
	 *
	 * @param int $id       The user's id
	 * @param string $table The table that the id is related to.
	 * @param string $image_link
	 * @return mixed
	 * @throws Exception
	 */
	public function displayProfile($id, $table='user', $image_link=FW_POPUP_HANDLE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Create new array to hold all display content.
		$display_content=array(
			'affiliation'=>'',
			'archive'=>'',
			'bio'=>'',
			'country'=>'',
			'cv'=>'',
			'display_name'=>'',
			'email'=>'',
			'image'=>'',
			'interests'=>'',
			'name'=>'',
			'organization'=>'',
			'position'=>NULL,
			'privacy'=>NULL,
			'questions'=>'',
			'region'=>'',
			'website'=>''
		);

		try
		{
			# Check if the passed id is from the `contributor` table.
			if($table=='contributors')
			{
				# Get the Contributor class.
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
				# Instantiate a new Contributor object.
				$contributors=new Contributor();
				# Retrieve the contributor's information.
				$display_contributor=$contributors->displayContributor($id);
				# Check if the contributor was retrieved.
				if(!empty($display_contributor))
				{
					# Get the contributor's display name.
					$display_name=$contributors->getContName();
					# Set the contributor XHTML elements to variables.
					$country=$display_contributor['country'];
					//$email=$display_contributor['email'];
					$name=$display_contributor['name'];
					$organization=$display_contributor['organization'];
					$display_content['privacy']=$display_contributor['privacy'];
					$region=$display_contributor['region'];
					# Set the user data member to a variable.
					//$user=$contributors->getUser();
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
				$username=$user->findUsername($id);
				# Retrieve this user's information from the `User` table.
				$user->findUserData($username);
				# Get the User's display name.
				$display_name=$user->getDisplayName();

				# Check if $country is set or is empty.
				if(!isset($country) || empty($country))
				{
					# Get the User's country and set it to a variable.
					$country=$user->getCountry();
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
				$cv=$user->getCV();
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
					if($this->isLoggedIn()!==TRUE)
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
	}

	/**
	 * Retrieves the User's display name and sets it to the display_name data member.
	 *
	 * @param int $id The User's ID.
	 * @return null
	 * @throws Exception
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Retrieves the User's email and sets it to the email data member.
	 *
	 * @param int $id The user's ID.
	 * @return null
	 * @throws Exception
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
	}

	/**
	 * Retrieves the User's first name and sets it to the first_name data member.
	 */
	public function findFirstName()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Attempt to retrieve the First Name from the data member.
			$first_name=$this->getFirstName();
			# Check if there was a First Name retrieved.
			if(empty($first_name))
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * A wrapper method for findIP() from the WebUtility calss.
	 *
	 * Returns the IP of the visitor.
	 *
	 * @param bool $for_insert_query Convert IP addresss to binary for database.
	 * @return string
	 */
	public function findIP($for_insert_query=TRUE)
	{
		# find the visitor's IP address.
		$ip=WebUtility::findIP($for_insert_query);

		# Return the visitor's IP address.
		return $ip;
	}

	/**
	 * Retrieves the date of the User's last login and sets it to the last_login data member.
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
	}

	/**
	 * Retrieves the User's last name and sets it to the last_name data member.
	 */
	public function findLastName()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Attempt to retrieve the Last Name from the data member.
			$last_name=$this->getLastName();
			# Check if there was a Last Name retrieved.
			if(empty($last_name))
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Retrieves the User's title and sets it to the title data member.
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Retrieves the User's password based on the passed variable. Throws an error on failure.
	 *
	 * @param null $user
	 * @param string $field The users Email or Username.
	 * @return bool
	 * @throws Exception
	 */
	public function findPassword($user=NULL, $field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();
		try
		{
			# Check if the passed value is empty.
			if(empty($user) || ($field=='ID' || empty($field)))
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
				$clean_user=$db->sanitize($user);
				if($field=='username')
				{
					$search_field='username';
				}
				elseif($validator->validEmail($user)===TRUE)
				{
					$search_field='email';
				}
				$where='`'.$search_field.'` = '.$db->quote($db->escape($clean_user));
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
	}

	/**
	 * Retrieves the privacy settings of a given user.
	 *
	 * @param string $username The users username.
	 * @return bool
	 * @throws Exception
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
	}

	/**
	 * Retrieves the purchased products of a given user.
	 *
	 * @param string $field                   The user's ID or email. Empty will attempt to retrieve the data member.
	 *                                        If the data member is empty it will try to find and use the user's ID.
	 * @return string
	 * @throws Exception
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
	}

	/**
	 * Retrieves the date the User registered and sets it to the registered data member.
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
	}

	/**
	 * Retrieves the staff ID from the `user` table.
	 *
	 * @param int $value                       The user's ID.
	 *                                         If NULL, then the method gets the logged in user's ID.
	 * @return null
	 * @throws Exception
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
	}

	/**
	 * Retrieves the data of a given user.
	 *
	 * @param string $value The user's username or id.
	 * @return string
	 * @throws Exception
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
				elseif($validator->validEmail($value)===TRUE && $this->getUsername()===NULL)
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
					$this->setIP($this->findIP(FALSE));
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
	}

	/**
	 * Retrieves the User's ID and sets it to the id data member. Throws an error on failure.
	 *
	 * @param string $username Optional - The user's username.
	 * @return null
	 * @throws Exception
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
			throw new Exception('There was a Database error: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Retrieves the access level of a given user.
	 *
	 * @param string $field
	 * @return string
	 * @throws Exception
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
	}

	/**
	 * Retrieves the User's username based on the passed variable. Throws an error on failure.
	 *
	 * @param string $field The users Email or id.
	 * @return null
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
	}

	/**
	 * Retrieves User email addresses from the Database that have opted in to receiving news messages.
	 *
	 * @param string $opt_in   The name of the table that the user has opted into.
	 * @param bool|string $csv Default is TRUE to return comma sepparated values. If FALSE, will return an array.
	 * @return array|string
	 * @throws Exception
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
	}

	/**
	 * Retrieves the privacy settings of a given user from the `usres` table and sets the values to the data members.
	 *
	 * @param string $username The users username.
	 * @return bool
	 * @throws Exception
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
	}

	/**
	 * Retrieves all subscriptions for the passed user ID.
	 * A wrapper method for getSubscriptions() from the Subscription calss.
	 *
	 * @param int $user_id The user's ID.
	 * @return null
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
	}

	/**
	 * Retrieves User records from the DataBase.
	 *
	 * @param int $limit        The LIMIT of the records.
	 * @param string $fields    The name of the field(s) to be retrieved.
	 * @param string $order     The name of the field to order the records by.
	 * @param string $direction The direction to order the records.
	 * @param string $where
	 * @throws Exception
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
	}

	/**
	 * Determines if the logged in user is an admin
	 *
	 * @param string $field May be the user ID or email. NULL assumes the user is logged in.
	 * @return boolean
	 */
	public function isAdmin($field=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Assume the User is not and admin. Make the method prove it.
		$admin=FALSE;
		# Get the User's access levels.
		$levels=(array)$this->findUserLevel($field);
		# Split the Admin levels string at spaces(' ') and set the pieces to the admin levels array.
		$admin_levels=explode(' ', ADMIN_USERS);
		# loop throught the User's levels.
		foreach($levels as $level)
		{
			# Check if the User's level is in the admin levels array.
			if(in_array($level, $admin_levels)===TRUE)
			{
				# The User is an Admin.
				$admin=TRUE;
			}
		}

		return $admin;
	}

	/**
	 * Checks if user is logged in or not. Returns TRUE if logged in, FALSE if not.
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function isLoggedIn()
	{
		if(!isset($_SESSION['user_logged_in']))
		{
			# Check if we have a cookie
			if(isset($_COOKIE['cookie_id']))
			{
				try
				{
					$this->setID($_COOKIE['cookie_id']);
				}
				catch(Exception $e)
				{
					unset($_COOKIE['user_ip']);
					unset($_COOKIE['athenticate']);
					unset($_COOKIE['cookie_id']);

					return FALSE;
				}
				# Get the User class.
				$id=$this->getID();
				# Set variables
				$password=$this->findPassword($this->findUsername($id));
				$ip=$this->findIP();

				# Let's see if we pass the validation.
				$authenticate=md5($password);
				if(($_COOKIE['authenticate']==$authenticate) && (md5($ip)==$_COOKIE['ip']))
				{
					# Set the sessions so we don't repeat this step over and over again.
					try
					{
						# Get the user's data.
						$this->findUserData();
						# Set variables.
						$display=$this->findDisplayName();
						$fname=$this->findFirstName();
						$lname=$this->findLastName();
						$title=$this->findTitle();
						$registered=$this->findRegistered();
						$last_login=$this->findLastLogin();

						$this->setLoginSessions($id, $display, $password, $fname, $lname, $title, $registered, $last_login, TRUE, TRUE);

						return TRUE;
					}
					catch(Exception $e)
					{
						throw $e;
					}
				}
				else
				{
					unset($_COOKIE['user_ip']);
					unset($_COOKIE['athenticate']);
					unset($_COOKIE['cookie_id']);

					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}
		elseif($_SESSION['user_logged_in']===TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Will try to determine if the logged in user is staff or not.
	 * A wrapper method for the isStaff method from the Staff class.
	 *
	 * @param int $value                      The user's ID.
	 *                                        If NULL, then the method gets the logged in user's ID.
	 * @return boolean
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
	}

	/**
	 * Logs the User out.
	 */
	public function logout()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Set the Session instance to a variable.
		$session=Session::getInstance();

		# Unset the sessions (all of them - array given)
		$session->loseAllSessionData();

		# If WordPress is installed, log the user out of WordPress.
		$this->clearWP_Cookies();

		# Uncomment the following line if you wish to remove all cookies.
		#	Don't forget to comment or delete the following 2 lines if you decide to use the clearCookies method)
		$this->clearCookies();
		//setcookie('cookie_id', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);
		//setcookie('authenticate', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);
		//setcookie('ip', '', time() -KEEP_LOGGED_IN_FOR, COOKIE_PATH, ".".DOMAIN_NAME);

		# Redirect the user to the default "logout" page.
		$doc->redirect(REDIRECT_ON_LOGOUT);
		exit;
	}

	/**
	 * Emails the appropriate admin/manager of a request for authorization on an aspect of the site.
	 *
	 * @param array $fields                   An array where the key is the POST Data field to check and the value is
	 *                                        the email address to send the request to. The Value may be an array as
	 *                                        well.
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
	}

	/**
	 * Create a Random String (Useful for generating passwords or hashes.)
	 *
	 * TODO: MOVE TO UTILITIES
	 *
	 * @param string $type The type of random string.  Options: alunum, numeric, nozero, unique
	 * @param int $len     The string length. Default is 8 characters.
	 * @return string
	 */
	public function randomString($type='alnum', $len=8)
	{
		switch($type)
		{
			case 'alnum':
			case 'numeric':
			case 'nozero':
				switch($type)
				{
					case 'alnum'    :
						$pool='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric':
						$pool='0123456789';
						break;
					case 'nozero':
						$pool='123456789';
						break;
				}

				$str='';
				for($i=0; $i<$len; $i++)
				{
					$str.=substr($pool, mt_rand(0, strlen($pool)-1), 1);
				}

				return $str;
				break;
			case 'unique':
				return md5(uniqid(mt_rand()));
				break;
		}
	}

	/**
	 * Sends account info in an email to the user.
	 *
	 * @param $email
	 * @throws Exception
	 */
	public function sendAccountInfo($email)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Database instance to a variable.
			$db=DB::get_instance();

			$email=$db->sanitize($email, 2);
			$row=$db->get_row('SELECT `password`, `display`, `username` FROM '.DBPREFIX.'users WHERE `email` = '.$db->quote($db->escape($email)).' LIMIT 1');
			if($row!==NULL)
			{
				require_once Utility::locateFile(MODULES.'Encryption/Encryption.php');
				$encrypt=new Encryption(MYKEY);
				$password=$encrypt->deCodeIt($row->password);
				# Send the confirmation email.
				$subject="Important email from ".DOMAIN_NAME;
				$to_address=trim($_POST['email']);
				$message=$row->display.','."<br />\n<br />\n".'This email has been sent from <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n<br />\n".'You have received this email because this email address was used during registration for our site.'."<br />\n".'If you did not register at '.DOMAIN_NAME.', please disregard this email. You do not need to unsubscribe or take any further action.'."<br />\n<br />\n".'---------------------------'."<br />\n".' Account Info'."<br />\n".'---------------------------'."<br />\n<br />\n".'You or someone at this email address has requested your password for <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n".'Your username is: <strong>'.$row->username.'</strong>'."<br />\n\r".'Your password is: <strong>'.$password.'</strong>'."<br />\n\r<br />\n\r".'You may login at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>.';
				try
				{
					$doc->sendEmail($subject, $to_address, $message);
					$_SESSION['message']='Account info sent. Please check your email for details. The email may not arrive instantly in your email inbox. Please give it some time. Please make sure to check your "junk mail" folder in case the email gets routed there. After your account is activated, you may sign in to '.DOMAIN_NAME.'. Once signed in, you will be able to access special features and download content.';
					$doc->redirect(REDIRECT_TO_LOGIN);
				}
				catch(Exception $e)
				{
					$_SESSION['message']='I couldn\'t send the activation email. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
					$doc->redirect(REDIRECT_TO_LOGIN);
				}
			}
			else
			{
				$doc->setError('The user was not found. Please check the email address you entered.');
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error retrieving the "random" field for the user with the email address"'.$email.'" from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Sends account info in an email to the user.
	 *
	 * @param string $email
	 * @param bool $new_account
	 * @throws Exception
	 */
	public function sendActivationEmail($email, $new_account=FALSE)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Database instance to a variable.
			$db=DB::get_instance();

			$email=$db->sanitize($email, 2);
			$row=$db->get_row('SELECT `ID`, `random`, `username` FROM '.DBPREFIX.'users WHERE `email` = '.$db->quote($db->escape($email)).' LIMIT 1');
			if($row!==NULL)
			{
				# Set email subject to a variable.
				$subject="Activation email from ".DOMAIN_NAME;
				$to_address=trim($email);
				# Set email body to a variable.
				$message=$row->username.','."<br />\n<br />\n".'This email has been sent from <a href="'.APPLICATION_URL.'">'.DOMAIN_NAME.'</a>.'."<br />\n<br />\n".'You have received this email because this email address was used during registration for our site.'."<br />\n".'If you did not register at '.DOMAIN_NAME.', please disregard this email. You do not need to unsubscribe or take any further action.'."<br />\n<br />\n".'------------------------------------------------'."<br />\n".' Activation Instructions'."<br />\n".'------------------------------------------------'."<br />\n<br />\n".'Thank you for registering.'."<br />\n".'We require that you "validate" your registration to ensure that the email address you entered was correct. This protects against unwanted spam and malicious abuse.'."<br />\n<br />\n".'To activate your account, simply click on the following link:'."<br />\n<br />\n".'<a href="'.REDIRECT_TO_LOGIN.'confirm.php?ID='.$row->ID.'&key='.$row->random.'">'.REDIRECT_TO_LOGIN.'confirm.php?ID='.$row->ID.'&key='.$row->random.'</a>'."<br />\n<br />\n".'(You may need to copy and paste the link into your web browser).'."<br />\n<br />\n".'Once you confirm your status, you may login at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>.';
				try
				{
					$doc->sendEmail($subject, $to_address, $message);
					$_SESSION['message']=(($new_account!==FALSE) ? 'Account created. ' : '').'Please check your email for details on how to activate it. The email may not arrive instantly in your email inbox. Please give it some time. Please make sure to check your "junk mail" folder in case the email gets routed there. After your account is activated, you may sign in to the '.DOMAIN_NAME.'. Once signed in, you will be able to access special features and download content.';
				}
				catch(Exception $e)
				{
					$_SESSION['message']='I managed to create your profile but failed to send the validation email. Please contact the admin at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';
					$doc->redirect(REDIRECT_TO_LOGIN);
				}
				$doc->redirect(REDIRECT_TO_LOGIN);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error retrieving the new user info for "'.$email.'" from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Sets the login sessions.
	 *
	 * @param int $user_id
	 * @param string $display_name
	 * @param string $password
	 * @param string $fname
	 * @param string $lname
	 * @param string $title
	 * @param string $registered
	 * @param string $last_login
	 * @param bool $logged_in
	 * @param bool $remember
	 * @param bool $secure
	 * @throws Exception
	 */
	public function setLoginSessions($user_id=NULL, $display_name=NULL, $password=NULL, $fname=NULL, $lname=NULL, $title=NULL, $registered=NULL, $last_login=NULL, $logged_in=NULL, $remember=FALSE, $secure=FALSE)
	{
		# Check if the user is logged in.
		if($this->isLoggedIn()===TRUE)
		{
			if($user_id===NULL)
			{
				try
				{
					# Get the User's data.
					$this->findUserData();
					$user_id=$this->getID();
					$display_name=$this->getDisplayName();
					$title=$this->getTitle();
					$fname=$this->getFirstName();
					$lname=$this->getLastName();
					$password=$this->getPassword();
					$registered=$this->getRegistered();
					$last_login=$this->getLastLogin();
					$logged_in=TRUE;
					//$remember=$this->checkRemember();
				}
				catch(Exception $e)
				{
					throw $e;
				}
			}
		}
		# Reset the time on the session cookie.
		if(isset($_COOKIE[SESSIONS_NAME]))
		{
			setcookie(SESSIONS_NAME, $_COOKIE[SESSIONS_NAME], (($remember!==TRUE) ? LOGIN_LIFE_SHORT : LOGIN_LIFE), COOKIE_PATH, '.'.DOMAIN_NAME, $secure);
		}
		# Set the User's login sessions.
		$_SESSION['user_id']=$user_id;
		$_SESSION['user_display_name']=$display_name;
		$_SESSION['user_title']=$title;
		$_SESSION['user_fname']=$fname;
		$_SESSION['user_lname']=$lname;
		$_SESSION['user_registered']=$registered;
		$_SESSION['user_last_login']=$last_login;
		$_SESSION['user_logged_in']=$logged_in;
		$_SESSION['remember']=$remember;
		# Do we have "remember me"?
		if($remember===TRUE)
		{
			# Get the User's IP address and encrypt it.
			$ip=md5($this->findIP());
			# Encrypt the password.
			$authenticate=md5($password);
			# Set the cookies.
			setcookie('cookie_id', $user_id, LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			setcookie('authenticate', $authenticate, LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
			setcookie('user_ip', $ip, LOGIN_LIFE, COOKIE_PATH, '.'.DOMAIN_NAME);
		}
	}

	/**
	 * Updates the User's record in the DataBase.
	 *
	 * @param array $where_field Key= the field, Value= the field value.
	 * @param array $field_value Key= the field, Value= the field value.
	 * @return
	 * @throws Exception
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
	}

	/**
	 * Check if the user is already in the users_inactive table.
	 *
	 * @param int $user_id
	 * @return
	 * @throws Exception
	 */
	public function getInactiveUsers($user_id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			if($user_id!==NULL)
			{
				$results=$db->get_row('SELECT `user_id`, `delete_date` FROM `'.DBPREFIX.'users_inactive` WHERE `user_id` = '.$db->quote($user_id).' LIMIT 1');
			}
			else
			{
				$results=$db->get_results('SELECT `user_id` FROM `'.DBPREFIX.'users_inactive` WHERE `delete_date` <= CURDATE()', ARRAY_N);
			}

			return $results;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error checking the users_inactive table: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Deletes the user(s) from the system.
	 */
	public function deleteInactiveUsers()
	{
		try
		{
			# Get all of the inactive users that are ready to be deleted.
			$inactive_users=$this->getInactiveUsers();
			# If there are any users ready to be deleted.
			if($inactive_users)
			{
				# If there is more then 1 result.
				if(count($inactive_users)>1)
				{
					$user_id_array=array();
					# Loop through the multidimensional array.
					foreach((array)$inactive_users as $user_id)
					{
						# Convert it to a single dimension.
						$user_id_array[]=$user_id[0];
					}
					$user_id=$user_id_array;
				}
				# Only one result so let's assign only that one result to a variable.
				else
				{
					$user_id=$inactive_users[0][0];
				}
				# Delete the users.
				$this->deleteAccount($user_id);

				# Return how many users were deleted.
				return count($inactive_users);
			}
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error deleting the inactive user: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Sets the data member $all_users.
	 *
	 * @param array $all_users
	 */
	protected function setAllUsers($all_users)
	{
		$this->all_users=$all_users;
	}

	/**
	 * Sets the data member $ip.
	 *
	 * @param string $ip
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
	}

	/**
	 * Deletes the user from the user_inactive table.
	 *
	 * @param int /array $user_id
	 * @throws Exception
	 */
	protected function deleteInactiveUser($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed $user_id is an integer.
			if($validator->isInt($user_id)===TRUE)
			{
				$where=' = '.$db->quote($user_id).' LIMIT 1';
			}
			# An array of users was passed into the method.
			#	Let's create the WHERE statement.
			elseif(is_array($user_id))
			{
				$where=' IN ('.implode(', ', $user_id).')';
			}
			if(isset($where))
			{
				$db->query('DELETE FROM `'.DBPREFIX.'users_inactive` WHERE `user_id`'.$where);
			}
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error deleting the user from the user_inactive table: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * Sets the data member $staff_id.
	 *
	 * @param int $staff_id The User's Staff ID number.
	 * @throws Exception
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
	}

	/**
	 * Will try to determine if a given ip is valid or not.
	 * A wrapper method for the ipValid method from the Validator class.
	 *
	 * @param string $ips The IP address to validate
	 * @return bool
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

		return FALSE;
	}

	/*** End protected methods ***/

	/*** private methods ***/

	/**
	 * Clears the WordPress cookies
	 *
	 * TODO: MOVE TO WEBUTILITIES
	 */
	private function clearWP_Cookies()
	{
		# If WordPress is installed, clear the cookies.
		if(WP_INSTALLED===TRUE)
		{
			# Unset cookies
			setcookie(AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time()-31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time()-31536000, ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time()-31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time()-31536000, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(LOGGED_IN_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(LOGGED_IN_COOKIE, '', time()-31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(LOGGED_IN_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(LOGGED_IN_COOKIE, '', time()-31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Old cookies
			setcookie(AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time()-31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(AUTH_COOKIE, '', time()-31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time()-31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(SECURE_AUTH_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(SECURE_AUTH_COOKIE, '', time()-31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Even older cookies
			setcookie(USER_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(USER_COOKIE, ' ', time()-31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(PASS_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(PASS_COOKIE, ' ', time()-31536000, COOKIEPATH, COOKIE_DOMAIN);
			setcookie(USER_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(USER_COOKIE, ' ', time()-31536000, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie(PASS_COOKIE, '', time()-LOGIN_LIFE);
			setcookie(PASS_COOKIE, ' ', time()-31536000, SITECOOKIEPATH, COOKIE_DOMAIN);

			# Settings and Test Cookies
			setcookie('wp-settings-1', '', time()-LOGIN_LIFE);
			setcookie('wp-settings-1', '', time()-LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('wp-settings-time-1', '', time()-LOGIN_LIFE);
			setcookie('wp-settings-time-1', '', time()-LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wp-settings-time-1', '', time()-LOGIN_LIFE);
			setcookie('wp-settings-time-1', '', time()-LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('settings', '', time()-LOGIN_LIFE);
			setcookie('settings', '', time()-LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wordpress_test_cookie', '', time()-LOGIN_LIFE);
			setcookie('wordpress_test_cookie', '', time()-LOGIN_LIFE, SITECOOKIEPATH, COOKIE_DOMAIN);
			setcookie('wordpress_test_cookie', '', time()-LOGIN_LIFE);
			setcookie('wordpress_test_cookie', '', time()-LOGIN_LIFE, COOKIEPATH, COOKIE_DOMAIN);
		}
	}

	/**
	 * Encodes a password for WordPress. A wrapper method for HashPassword from the PasswordHash class.
	 *
	 * @param string $wp_password Optional. Used only for Login->changePassword() method.
	 * @return null
	 */
	private function ecodeWP_Password($wp_password=NULL)
	{
		# Get the PasswordHash Class.
		require_once Utility::locateFile(MODULES.'Vendor'.DS.'PasswordHash'.DS.'PasswordHash.php');
		# Instantiate a PasswordHash object
		$hasher=new PasswordHash(8, TRUE);
		# If $password param is NOT set.
		if($wp_password===NULL)
		{
			# Get the Wordpress password.
			$wp_password=$this->getWPPassword();
		}
		# Format the password.
		$wp_password=$hasher->HashPassword($wp_password);
		# Set the formatted password.
		$this->setWPPassword($wp_password);

		# Return the password (for backwards compatibility).
		return $this->getWPPassword();
	}
	/*** End protected methods ***/
}