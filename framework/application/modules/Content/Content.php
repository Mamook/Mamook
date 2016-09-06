<?php /* framework/application/modules/Content/Content.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/***
 * Content
 *
 * The Content class is used to get and display content from the database.
 *
 */
class Content
{
	/*** data members ***/

	protected $address1=NULL;
	protected $address2=NULL;
	protected $all_content=NULL;
	protected $api=NULL;
	protected $archive;
	protected $city=NULL;
	protected $date='0000-00-00';
	protected static $content;
	protected $country=NULL;
	protected $email=NULL;
	protected $fax=NULL;
	protected $hide_title;
	protected $id=NULL;
	protected $image=NULL;
	protected $image_title=NULL;
	protected $maintenance;
	protected $page=NULL;
	protected $page_title=NULL;
	protected $phone=NULL;
	protected $quote='';
	protected $registration=NULL;
	protected $site_name;
	protected $slogan=NULL;
	protected $state=NULL;
	protected $sub_domain=NULL;
	protected $sub_title=NULL;
	protected $text='';
	protected $topic='';
	protected $use_social=NULL;
	protected $zipcode=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * @access	public
	 */
	public function __construct()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Get the content from the Database.
		try
		{
			$content=$db->get_row("SELECT `site_name`, `slogan`, `address1`, `address2`, `city`, `state`, `country`, `zipcode`, `phone`, `fax`, `email`, `registration`, `maintenance` FROM `".DBPREFIX."config` WHERE `archive` IS NOT NULL LIMIT 1");
			$this->setSiteName($content->site_name);
			$this->setSlogan($content->slogan);
			$this->setAddress1($content->address1);
			$this->setAddress2($content->address2);
			$this->setCity($content->city);
			$this->setState($content->state);
			$this->setCountry($content->country);
			$this->setZipcode($content->zipcode);
			$this->setPhone($content->phone);
			$this->setFax($content->fax);
			$this->setEmail($content->email);
			$this->setRegistration($content->registration);
			$this->setMaintenance($content->maintenance);
			# Only find this page's content if this is not being run in a command line script.
			if(PHP_SAPI!='cli')
			{
				$this->getContent();
			}
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('Error occured: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllContent
	 *
	 * Sets the data member $all_content.
	 *
	 * @param	$all_content			May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllContent($all_content)
	{
		# Check if the passed value is empty.
		if(!empty($all_content))
		{
			# Explicitly make it an array.
			$files=(array)$all_content;
			# Set the data member.
			$this->all_content=$all_content;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_content=NULL;
		}
	} #==== End -- setAllContent

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param	string $api
	 * @access	protected
	 */
	protected function setAPI($api)
	{
		# Check if the passed value is empty.
		if(!empty($api))
		{
			# Clean it up.
			$api=trim($api);
			# Set the data member.
			$this->api=$api;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->api=NULL;
		}
	} #==== End -- setAPI

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param		$date
	 * @access	public
	 */
	public function setDate($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
		{
			# Explode the date into an array casting each as an integer.
			$date=explode('-', $date);
			$year=(int)$date[0];
			$month=(int)$date[1];
			$day=(int)$date[2];
			if(checkdate($month, $day, $year)===TRUE)
			{
				# Make sure the day is the correct length.
				if(strlen($day)!=2)
				{
					$day='0'.$day;
				}
				# Make sure the month is the correct length.
				if(strlen($month)!=2)
				{
					$month='0'.$month;
				}
				# Put the date back together in the correct format.
				$date=$year.'-'.$month.'-'.$day;
				# Set the data member.
				$this->date=$date;
			}
			else
			{
				throw new Exception('The passed date was not an acceptable date.', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->date='0000-00-00';
		}
	} #==== End -- setDate

	/***
	 * setSiteName
	 *
	 * Sets the data member $site_name.
	 *
	 * @param	$site_name
	 * @access	protected
	 */
	protected function setSiteName($site_name)
	{
		# Strip slashes, decode any html entities, and set the data member.
		$this->site_name=html_entity_decode(stripslashes($site_name), ENT_COMPAT, 'UTF-8');
	} #==== End -- setSiteName

	/***
	 * setSlogan
	 *
	 * Sets the data member $slogan.
	 *
	 * @param	$slogan
	 * @access	protected
	 */
	protected function setSlogan($slogan)
	{
		# Strip slashes, decode any html entities, and set the data member.
		$this->slogan=html_entity_decode(stripslashes($slogan), ENT_COMPAT, 'UTF-8');
	} #==== End -- setSlogan

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setID($id, $class='content')
	{
		# Check if the passed $id is empty.
		if(!empty($id) && $id!=='add' && $id!=='select')
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly making it an integer.
				$id=(int)trim($id);
			}
			else
			{
				throw new Exception('The passed '.$class.' id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->id=$id;
	} #==== End -- setID

	/***
	 * setPageTitle
	 *
	 * Sets the data member $page_title
	 *
	 * @param	$page_title
	 * @param	$form
	 * @access	public
	 */
	public function setPageTitle($page_title, $form=NULL)
	{
		# Check if the passed value is empty.
		if(!empty($page_title))
		{
			# Get the site name.
			$site_name=$this->getSiteName();
			if($form===NULL)
			{
				# Strip slashes, decode any html entities, strip any tags, and set the data member.
				$page_title=strip_tags(html_entity_decode(stripslashes($page_title), ENT_COMPAT, 'UTF-8'), '<abbr>');
			}
			else
			{
				# Strip slashes and decode any html entities in UTF-8 charset.
				$page_title=html_entity_decode(stripslashes($page_title), ENT_NOQUOTES, 'UTF-8');
				# Clean it up.
				$page_title=trim($page_title);
			}
			# Replace any tokens with their correlating value.
			$page_title=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $page_title);
		}
		else
		{
			# Explicitly set the value to NULL.
			$page_title=NULL;
		}
		# Set the data member.
		$this->page_title=$page_title;
	} #==== End -- setPageTitle

	/***
	 * setSubTitle
	 *
	 * Sets the data member $sub_title
	 *
	 * @param		$sub_title
	 * @access	public
	 */
	public function setSubTitle($sub_title)
	{
		# Check if the passed value is empty.
		if(!empty($sub_title))
		{
			# Get the site name.
			$site_name=$this->getSiteName();
			# Strip slashes and decode any html entities in UTF-8 charset.
			$sub_title=html_entity_decode(stripslashes($sub_title), ENT_NOQUOTES, 'UTF-8');
			# Clean it up.
			$sub_title=trim($sub_title);
			# Replace any tokens with their correlating value.
			$this->sub_title=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $sub_title);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sub_title=NULL;
		}
	} #==== End -- setSubTitle

	/***
	 * setHideTitle
	 *
	 * Sets the data member $hide_title
	 *
	 * @param		$hide_title
	 * @access	public
	 */
	public function setHideTitle($hide_title)
	{
		# Check if the passed value is not NULL.
		if($hide_title!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->hide_title=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->hide_title=NULL;
		}
	} #==== End -- setHideTitle

	/***
	 * setText
	 *
	 * Sets the data member $text
	 *
	 * @param		$text
	 * @access	public
	 */
	public function setText($text)
	{
		# Strip slashes and decode any html entities.
		$text=((empty($text)) ? '' : html_entity_decode(stripslashes($text), ENT_COMPAT, 'UTF-8'));
		# Clean it up.
		$text=trim($text);
		# Set the data member.
		$this->text=$text;
	} #==== End -- setText

	/***
	 * setTopic
	 *
	 * Sets the data member $topic
	 *
	 * @param	$topic
	 * @access	public
	 */
	public function setTopic($topic)
	{
		# Strip slashes, decode any html entities, strip any tags, and set the data member.
		$topic=((empty($topic)) ? '' : strip_tags(html_entity_decode(stripslashes($topic), ENT_COMPAT, 'UTF-8')));
		# Clean it up.
		$topic=trim($topic);
		# Set the data member.
		$this->topic=$topic;
	} #==== End -- setTopic

	/***
	 * setQuote
	 *
	 * Sets the data member $quote
	 *
	 * @param		$quote
	 * @access	public
	 */
	public function setQuote($quote)
	{
		# Strip slashes and decode any html entities.
		$quote=((empty($quote)) ? '' : html_entity_decode(stripslashes($quote), ENT_COMPAT, 'UTF-8'));
		# Clean it up.
		$quote=trim($quote);
		# Convert new lines to breaks.
		$quote=nl2br($quote);
		# Set the data member.
		$this->quote=$quote;
	} #==== End -- setQuote

	/***
	 * setImage
	 *
	 * Sets the data member $image
	 *
	 * @param	$image
	 * @access	public
	 */
	public function setImage($image)
	{
		# Check if the passed value is empty.
		if(!empty($image))
		{
			# Strip slashes and decode any html entities.
			$image=html_entity_decode(stripslashes($image), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$image=trim($image);
		}
		else
		{
			# Explicitly set the value to NULL.
			$image=NULL;
		}
		# Set the data member.
		$this->image=$image;
	} #==== End -- setImage

	/***
	 * setImageTitle
	 *
	 * Sets the data member $image_title
	 *
	 * @param		$image_title
	 * @access	public
	 */
	public function setImageTitle($image_title)
	{
		# Check if the passed value is empty.
		if(!empty($image_title))
		{
			# Strip slashes and decode any html entities.
			$image_title=html_entity_decode(stripslashes($image_title), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$image_title=trim($image_title);
		}
		else
		{
			# Explicitly set the value to NULL.
			$image_title=NULL;
		}
		# Set the data member.
		$this->image_title=$image_title;
	} #==== End -- setImageTitle

	/***
	 * setSubDomain
	 *
	 * Sets the data member $sub_domain
	 *
	 * @param	$sub_domain
	 * @access	public
	 */
	public function setSubDomain($sub_domain)
	{
		# Check if the passed value is empty.
		if(!empty($sub_domain))
		{
			# Strip slashes, decode any html entities, strip any tags, and set the data member.
			$this->sub_domain=strip_tags(html_entity_decode(stripslashes($sub_domain), ENT_COMPAT, 'UTF-8'));
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sub_domain=NULL;
		}
	} #==== End -- setSubDomain

	/***
	 * setPage
	 *
	 * Sets the data member $page
	 *
	 * @param	$page
	 * @access	public
	 */
	public function setPage($page)
	{
		# Check if the passed value is empty.
		if(!empty($page))
		{
			# Strip slashes, decode any html entities, strip any tags, and set the data member.
			$this->page=strip_tags(html_entity_decode(stripslashes($page), ENT_COMPAT, 'UTF-8'));
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->page=NULL;
		}
	} #==== End -- setPage

	/***
	 * setArchive
	 *
	 * Sets the data member $archive
	 *
	 * @param	$archive
	 * @access	public
	 */
	public function setArchive($archive)
	{
		# Check if the passed value is not NULL.
		if($archive!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->archive=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->archive=NULL;
		}
	} #==== End -- setArchive

	/***
	 * setAddress1
	 *
	 * Sets the organization address1
	 *
	 * @param		$address1
	 * @access	public
	 */
	public function setAddress1($address1)
	{
		# Check if the passed value is empty.
		if(!empty($address1))
		{
			# Strip slashes and decode any html entities.
			$address1=html_entity_decode(stripslashes($address1), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$address1=trim($address1);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->address1=NULL;
		}
		# Set the data member.
		$this->address1=$address1;
	} #==== End -- setAddress1

	/***
	 * setAddress2
	 *
	 * Sets the organization address2
	 *
	 * @param		$address2
	 * @access	public
	 */
	public function setAddress2($address2)
	{
		# Check if the passed value is empty.
		if(!empty($address2))
		{
			# Strip slashes and decode any html entities.
			$address2=html_entity_decode(stripslashes($address2), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$address2=trim($address2);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->address2=NULL;
		}
		# Set the data member.
		$this->address2=$address2;
	} #==== End -- setAddress2

	/***
	 * setCity
	 *
	 * Sets the organization city
	 *
	 * @param		$city
	 * @access	public
	 */
	public function setCity($city)
	{
		# Check if the passed value is empty.
		if(!empty($city))
		{
			# Strip slashes and decode any html entities.
			$city=html_entity_decode(stripslashes($city), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$city=trim($city);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->city=NULL;
		}
		# Set the data member.
		$this->city=$city;
	} #==== End -- setCity

	/***
	 * setState
	 *
	 * Sets the organization state
	 *
	 * @param		$state
	 * @access	public
	 */
	public function setState($state)
	{
		# Check if the passed value is empty.
		if(!empty($state))
		{
			# Strip slashes and decode any html entities.
			$state=html_entity_decode(stripslashes($state), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$state=trim($state);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->state=NULL;
		}
		# Set the data member.
		$this->state=$state;
	} #==== End -- setState

	/***
	 * setCountry
	 *
	 * Sets the organization country
	 *
	 * @param		$country
	 * @access	public
	 */
	public function setCountry($country)
	{
		# Check if the passed value is empty.
		if(!empty($country))
		{
			# Strip slashes and decode any html entities.
			$country=html_entity_decode(stripslashes($country), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$country=trim($country);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->country=NULL;
		}
		# Set the data member.
		$this->country=$country;
	} #==== End -- setCountry

	/***
	 * setZipcode
	 *
	 * Sets the organization zipcode
	 *
	 * @param	$zipcode
	 * @access	public
	 */
	public function setZipcode($zipcode)
	{
		# Check if the passed value is empty.
		if(!empty($zipcode))
		{
			# Clean it up.
			$zipcode=trim($zipcode);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->zipcode=NULL;
		}
		# Set the data member.
		$this->zipcode=$zipcode;
	} #==== End -- setZipcode

	/***
	 * setPhone
	 *
	 * Sets the organization phone
	 *
	 * @param		$phone
	 * @access	public
	 */
	public function setPhone($phone)
	{
		# Check if the passed value is empty.
		if(!empty($phone))
		{
			# Clean it up.
			$phone=trim($phone);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->phone=NULL;
		}
		# Set the data member.
		$this->phone=$phone;
	} #==== End -- setPhone

	/***
	 * setFax
	 *
	 * Sets the organization fax
	 *
	 * @param		$fax
	 * @access	public
	 */
	public function setFax($fax)
	{
		# Check if the passed value is empty.
		if(!empty($fax))
		{
			# Clean it up.
			$fax=trim($fax);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->fax=NULL;
		}
		# Set the data member.
		$this->fax=$fax;
	} #==== End -- setFax

	/***
	 * setEmail
	 *
	 * Sets the organization email
	 *
	 * @param		$email
	 * @access	public
	 */
	public function setEmail($email)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($email))
		{
			# Clean it up.
			$email=trim($email);
			# Validate if the email is real.
			if($validator->validEmail($email)===TRUE)
			{
				# Set the data member.
				$this->email=$email;
			}
			else
			{
				# Throw an error!
				throw new Exception('The email on file is not a valid email address!', E_USER_NOTICE);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->email=NULL;
		}
	} #==== End -- setEmail

	/***
	 * setRegistration
	 *
	 * Sets the registration data member
	 *
	 * @param		$allow_registration
	 * @access	public
	 */
	public function setRegistration($allow_registration)
	{
		# Check if the passed value is not NULL.
		if($allow_registration!==NULL)
		{
			# Explicitly set the value to 0.
			$allow_registration=0;
		}
		# Set the data member to NULL.
		$this->registration=$allow_registration;
	} #==== End -- setRegistration

	/***
	 * setMaintenance
	 *
	 * Sets the maintenance data member
	 *
	 * @param	$maintenance
	 * @access	public
	 */
	public function setMaintenance($maintenance)
	{
		# Check if the passed value is not NULL.
		if($maintenance!==NULL)
		{
			# Explicitly set the value to 0.
			$maintenance=0;
		}
		# Set the data member to NULL.
		$this->maintenance=$maintenance;
	} #==== End -- setMaintenance

	/***
	 * setUseSocial
	 *
	 * Sets the $use_social data member.
	 *
	 * @param		$use_social
	 * @access	public
	 */
	public function setUseSocial($use_social)
	{
		# Check if it is NULL.
		if($use_social!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->use_social=0;
		}
		else
		{
			# Set the data member to NULL.
			$this->use_social=NULL;
		}
	} #==== End -- setUseSocial

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllContent
	 *
	 * Returns the data member $all_content.
	 *
	 * @access	public
	 */
	public function getAllContent()
	{
		return $this->all_content;
	} #==== End -- getAllContent

	/**
	 * getAPI
	 *
	 * Returns the data member $api.
	 *
	 * @access	public
	 */
	public function getAPI()
	{
		return $this->api;
	} #==== End -- getAPI

	/**
	 * getDate
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getDate()
	{
		return $this->date;
	} #==== End -- getDate

	/***
	 * getSiteName
	 *
	 * Returns the data member $site_name.
	 *
	 * @access	public
	 */
	public function getSiteName()
	{
		return $this->site_name;
	} #==== End -- getSiteName

	/***
	 * getSlogan
	 *
	 * Returns the data member $slogan.
	 *
	 * @access	public
	 */
	public function getSlogan()
	{
		return $this->slogan;
	} #==== End -- getSlogan

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

	/***
	 * getPageTitle
	 *
	 * Returns the data member $page_title.
	 *
	 * @access	public
	 */
	public function getPageTitle($form=NULL)
	{
		if($form===NULL)
		{
			if(isset($this->page_title) && !empty($this->page_title))
			{
				return $this->page_title;
			}
			else
			{
				throw new Exception('Page title is not set', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			return htmlentities($this->page_title, ENT_QUOTES, 'UTF-8', FALSE);
		}
	} #==== End -- getPageTitle

	/***
	 * getSubTitle
	 *
	 * Returns the data member $sub_title.
	 *
	 * @access	public
	 */
	public function getSubTitle($form=NULL)
	{
		if($form===TRUE)
		{
			return htmlentities($this->sub_title, ENT_QUOTES, 'UTF-8', FALSE);
		}
		return $this->sub_title;
	} #==== End -- getSubTitle

	/***
	 * getHideTitle
	 *
	 * Returns the data member $hide_title.
	 *
	 * @access	public
	 */
	public function getHideTitle()
	{
		return $this->hide_title;
	} #==== End -- getHideTitle

	/***
	 * getText
	 *
	 * Returns the data member $text.
	 *
	 * @access	public
	 */
	public function getText($form=NULL)
	{
		if($form===TRUE)
		{
			return htmlentities($this->text, ENT_QUOTES, 'UTF-8', FALSE);
		}
		return $this->text;
	} #==== End -- getText

	/***
	 * getTopic
	 *
	 * Returns the data member $text.
	 *
	 * @access	public
	 */
	public function getTopic()
	{
		return $this->topic;
	} #==== End -- getTopic

	/***
	 * getQuote
	 *
	 * Returns the data member $quote.
	 *
	 * @access	public
	 */
	public function getQuote($form=NULL)
	{
		if($form===TRUE)
		{
			return htmlentities($this->quote, ENT_QUOTES, 'UTF-8', FALSE);
		}
		return $this->quote;
	} #==== End -- getQuote

	/***
	 * getImage
	 *
	 * Returns the data member $image.
	 *
	 * @access	public
	 */
	public function getImage()
	{
		return $this->image;
	} #==== End -- getImage

	/***
	 * getImageTitle
	 *
	 * Returns the data member $image_title.
	 *
	 * @access	public
	 */
	public function getImageTitle()
	{
		return $this->image_title;
	} #==== End -- getImageTitle

	/***
	 * getSubDomain
	 *
	 * Returns the data member $sub_domain.
	 *
	 * @access	public
	 */
	public function getSubDomain()
	{
		return $this->sub_domain;
	} #==== End -- getSubDomain

	/***
	 * getPage
	 *
	 * Returns the data member $page.
	 *
	 * @access	public
	 */
	public function getPage()
	{
		return $this->page;
	} #==== End -- getPage

	/***
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

	/***
	 * getAddress1
	 *
	 * Returns the data member $address1.
	 *
	 * @access	public
	 */
	public function getAddress1()
	{
		return $this->address1;
	} #==== End -- getAddress1

	/***
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

	/***
	 * getgetCityAddress1
	 *
	 * Returns the data member $city.
	 *
	 * @access	public
	 */
	public function getCity()
	{
		return $this->city;
	} #==== End -- getCity

	/***
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

	/***
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

	/***
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

	/***
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

	/***
	 * getFax
	 *
	 * Returns the data member $fax.
	 *
	 * @access	public
	 */
	public function getFax()
	{
		return $this->fax;
	} #==== End -- getFax

	/***
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

	/***
	 * getRegistration
	 *
	 * Returns the data member $registration.
	 *
	 * @access	public
	 */
	public function getRegistration()
	{
		return $this->registration;
	} #==== End -- getRegistration

	/**
	 * getMaintenance
	 *
	 * Returns the data member $maintenance
	 *
	 * @access public
	 */
	public function getMaintenance()
	{
		return $this->maintenance;
	} #=== End -- getMaintenance

	/***
	 * getUseSocial
	 *
	 * Returns the data member $use_social.
	 *
	 * @access	public
	 */
	public function getUseSocial()
	{
		return $this->use_social;
	} #==== End -- getUseSocial

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllContent
	 *
	 * Returns the number of content in the database.
	 *
	 * @param	$limit 		(The limit of records to count.)
	 * @param	$and_sql 	(Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllContent($limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'content`'.(($and_sql===NULL) ? '' : ' WHERE '.$and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllContent

	/***
	 * displayContent
	 *
	 * Display content.
	 *
	 * @access	public
	 */
	public function displayContent()
	{
		# Set variables
		$site_name=$this->getSiteName();
		$text=$this->getText();

		if(!empty($text))
		{
			# Replace any tokens with their correlating value.
			$text=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $text);
			$text='<div class="content-text">'.$text.'</div>';
		}
		return $text;
	} #==== End -- displayContent

	/**
	 * displayContentList
	 *
	 * Returns a selectable list of content.
	 *
	 * @access	public
	 */
	public function displayContentList()
	{
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Count the returned files.
			$content_count=$this->countAllContent();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='content';
				# Set the default sort direction of files for the file sorting link to a variable.
				$file_dir='DESC';
				# Set the default sort direction of titles for the title sorting link to a variable.
				$title_dir='DESC';
				# Check if GET data for file has been passed and it is an integer.
				if(isset($_GET['content']) && $validator->isInt($_GET['content'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['content']='content='.$_GET['content'];
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_file" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_content']))
				{
					# Set the query to the query parameters array.
					$params_a['by_title']='by_title='.$_GET['by_title'];
					# Reset the default "sort by" to "title".
					//$sort_by='`page_title` WHEN SUBSTRING(page_title, 1, LENGTH(page_title)) NOT BETWEEN \'A\' AND \'Z\' AND SUBSTRING(page_title, 1, LENGTH(page_title)) NOT BETWEEN \'a\' AND \'z\' THEN SUBSTRING(page_title, 1, LENGTH(page_title)) ELSE `page_title` END';
					$sort_by='page_title';
					# Check if the order is to be descending.
					if($_GET['by_title']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of titles for the title sorting link to "ASC".
						$title_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_title" indexes of the array.
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the file and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				# DRAVEN: Create seperate access for editing content pages?
				# Check if the logged in User has access to editing a branch.
				if($login->checkAccess(MAN_USERS)===TRUE)
				{
					# Set the default value for displaying an edit button to TRUE.
					$edit=TRUE;
				}
				# Get the PageNavigator Class.
				require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Set the newly created WHERE clause to a variable.
				$and_sql=' WHERE 1';

				# Get the Files.
				$this->getContentPages($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned File records to a variable.
				$all_content=$this->getAllContent();

				# Start a table for the files and set the markup to a variable.
				$table_header='<table class="table-image">';
				# Set the table header for the file column to a variable.
				$general_header='<th>Screenshot</th>';
				# Add the table header for the title column to the $general_header variable.
				$general_header.='<th><a href="//'.DOMAIN_NAME.'/'.UTILITY::removeIndex(HERE).'?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Page Title</a></th>';
				# Concatenate the table header.
				$table_header.=$general_header;
				# Check if edit and delete buttons should be displayed.
				if($edit===TRUE)
				{
					# Concatenate the options header to the table header.
					$table_header.='<th>Options</th>';
				}
				# Creat an empty variable for the table body.
				$table_body='';
				# Loop through the all_content array.
				foreach($all_content as $row)
				{
					# Instantiate a new Content object.
					$content_row=new Content();
					# Set the relevant returned field values File data members.
					$content_row->setID($row->id);
					$content_row->setPageTitle(strip_tags($row->page_title));
					$content_row->setSubTitle(strip_tags($row->sub_title));
					$content_row->setPage($row->page);
					# Set the relevant Content data members to local variables.
					$content_id=$content_row->getID();
					$content_page_title=$content_row->getPageTitle(TRUE);
					$content_sub_title=$content_row->getSubTitle();
					$page=$content_row->getPage();
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					# Set the content markup to the $general_data variable.
					$general_data='<td></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td><label for="page_title"><a href="//'.DOMAIN_NAME.'/'.UTILITY::removeIndex($page).'" title="Click to view the page" target="_blank">'.$content_page_title.((empty($content_sub_title)) ? '' : ': '.$content_sub_title).'</a></label></td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="//'.DOMAIN_NAME.'/'.UTILITY::removeIndex(HERE).'?content='.$content_id.'" class="button-edit" title="Edit this">Edit</a>';
					}
					# Concatenate the general data to the $table_body variable first opening a new tr.
					$table_body.='<tr>'.$general_data;
					# Check if there should be edit or Delete buttons displayed.
					if($edit===TRUE)
					{
						# Concatenate the button(s) to the $table_body variable wrapped in td tags.
						$table_body.='<td>'.$edit_content.'</td>';
					}
					# Close the current tr.
					$table_body.='</tr>';
				}
				# Concatenate the table header and body and close the table setting it all to a local variable.
				$display=$table_header.$table_body.'</table>';
				# Add the pagenavigator to the display variable.
				$display.=$paginator->getNavigator();
			}
			else
			{
				$display='<h3 class="h-3">There are no content pages to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayContentList

	/**
	 * displayImage
	 *
	 * Markup the main page image.  and return it as a Sting
	 *
	 * @access	public
	 */
	public function displayImage($image_link=FW_POPUP_HANDLE)
	{
		# Set variables
		$image_name=$this->getImage();
		$image_title=$this->getImageTitle();

		# Create an empty variable to hold the html content.
		$content='';

		# Check if there is an image to display.
		if(!empty($image_name))
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
			# Instantiate a new Image object.
			$image=new Image();
			# Display the image using the image title if available, otherwise, use the page title.
			$content.=$image->displayImage(TRUE, $image_name, ((empty($image_title)) ? strip_tags($this->getPageTitle()) : $image_title), $image_link);
		}

		return $content;
	} #==== End -- displayImage

	/***
	 * displayQuote
	 *
	 * Returns the optional page quote for display.
	 *
	 * @access	public
	 */
	public function displayQuote()
	{
		# Set the quote to a local variable.
		$quote=$this->getQuote();
		# Build the quote into a paragraph for display. If there is no quote, return an empty string.
		return ((!empty($quote)) ? '<span class="quote">'.$quote.'</span>' : '');
	} #==== End -- displayQuote

	/***
	 * displaySocial
	 *
	 * Display social network button content.
	 *
	 * @access	public
	 */
	public function displaySocial()
	{
		# Set variables
		$use_social=$this->getUseSocial();

		# Create an empty variable to hold the html content.
		$content='';

		if($use_social!==NULL)
		{
			# Get the API Class.
			require_once Utility::locateFile(MODULES.'API'.DS.'API.php');
			$api_obj=new API('addthis');
			# Display the social buttons.
			$content.=$api_obj->displaySocial();
		}

		return $content;
	} #==== End -- displaySocial

	/***
	 * displayTitles
	 *
	 * Display page title and sub title.
	 *
	 * @access	public
	 */
	public function displayTitles()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		$content='';

		# Set variables
		$page_title=$this->getPageTitle();
		if($this->getHideTitle()!==NULL)
		{
			$page_title=$this->getSubTitle();
		}
		else
		{
			$sub_title=$this->getSubTitle();
		}


		if(!empty($page_title))
		{
			# Display the content title
			$content.='<h1 class="h-1">'.$page_title.'</h1>';
		}

		if(!empty($sub_title))
		{
			$content.='<h2 class="h-2">'.$sub_title.'</h2>';
		}

		# Add the error box if we have an error or message to display.
		$content.=$doc->addErrorBox();

		return $content;
	} #==== End -- displayTitles

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$content)
		{
			self::$content=new Content();
		}
		return self::$content;
	} #==== End -- getInstance

	/**
	 * getContentPages
	 *
	 * Retrieves records from the `content` table.
	 *
	 * @param	$limit 			(The LIMIT of the records.)
	 * @param	$fields 		(The name of the field(s) to be retrieved.)
	 * @param	$order 			(The name of the field to order the records by.)
	 * @param	$direction 		(The direction to order the records.)
	 * @param	$and_sql 		(Extra AND statements in the query.)
	 * @return	Boolean 		(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getContentPages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `files` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'content`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllContent($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getContentPages

	/**
	 * getThisContent
	 *
	 * Retrieves content info from the `content` table in the Database for the passed id or page name and sets it to the data member.
	 *
	 * @param	String	$value 			(The name or id of the content to retrieve.)
	 * @param	Boolean $id 			(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisContent($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set the field to search for $value.
				$field='id';
				# Set the content id to the data member "cleaning" it.
				$this->setID($value);
				# Get the content id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='page_title';
				# Set the page name to the data member "cleaning" it.
				$this->setPageTitle($value);
				# Get the page name and reset it to the variable.
				$value=$this->getPageTitle(TRUE);
			}
			if(!empty($value))
			{
				# Get the content info from the Database.
				$content=$db->get_row('SELECT `id`, `page_title`, `sub_title`, `hide_title`, `content`, `quote`, `topic`,  `image`, `image_title`, `sub_domain`, `page`, `archive`, `social`, `api` FROM `'.DBPREFIX.'content` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
				# Check if there was content retrieved from the Database.
				if($content!==NULL)
				{
					# Set the ID to the data member.
					$this->setID($content->id);
					# Set the page title to the data member.
					$this->setPageTitle(((isset($page_title) && !empty($page_title)) ? $page_title : $content->page_title), TRUE);
					# Set the sub title to the data member.
					$this->setSubTitle(((isset($sub_title) && !empty($sub_title)) ? $sub_title : $content->sub_title));
					# Set whether the page should be displayed to the data member.
					$this->setHideTitle($content->hide_title);
					# Set the page text to the data member.
					$this->setText($content->content);
					# Set the page quote to the data member.
					$this->setQuote($content->quote);
					# Set the topic to the data member.
					$this->setTopic($content->topic);
					# Set the image to the data member.
					$this->setImage($content->image);
					# Set the image's title to the data member.
					$this->setImageTitle($content->image_title);
					# Set the content's `sub_domain` to the data member.
					$this->setSubDomain($content->sub_domain);
					# Set the content's `page` to the data member.
					$this->setPage($content->page);
					# Set the content's `archive` to the data member.
					$this->setArchive($content->archive);
					# Set whether the social links should be displayed to the data member.
					$this->setUseSocial($content->social);
					# Set the content's `api` to the data member.
					$this->setAPI($content->api);
					return TRUE;
				}
			}
			# Return FALSE because the content wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisContent

	/*** End public methods ***/



	/*** protected methods ***/

	/***
	 * checkDomain
	 *
	 * Check if URL is a sub-domain
	 *
	 * @access	protected
	 *
	 */
	protected function checkDomain()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Find the sub-domain and set it.
		if(SUB_DOMAIN=="www" || SUB_DOMAIN=="")
		{
			$sub_domain_sql="`sub_domain` IS NULL";
		}
		else
		{
			$sub_domain_sql="`sub_domain` = ".$db->quote($db->escape(SUB_DOMAIN));
		}
		return $sub_domain_sql;
	} #==== End -- checkDomain

	/**
	 * getContent
	 *
	 * Get content from the `content` table.
	 *
	 * @param	$where
	 * @access	protected
	 */
	protected function getContent($where=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring $page_title variable into scope.
		global $page_title;
		# Bring the "page-topic" meta tag variable into scope.
		global $page_topic;

		# Get the preset sub title.
		$sub_title=$this->getSubTitle();

		# Check if there is a WHERE SQL statement.
		if(empty($where))
		{
			# Set the default WHERE SQL statement.
			$where=' WHERE '.$this->checkDomain().' AND `page` = '.$db->quote($db->escape(HERE));
		}
		try
		{
			$content=$db->get_row('SELECT `id`, `page_title`, `sub_title`, `hide_title`, `content`, `quote`, `topic`,  `image`, `image_title`, `archive`, `social`, `api` FROM `'.DBPREFIX.'content`'.$where.' LIMIT 1');
			# Check if there was content retrieved from the Database.
			if($content!==NULL)
			{
				# Set the ID to the data member.
				$this->setID($content->id);
				# Set the page title to the data member.
				$this->setPageTitle(((isset($page_title) && !empty($page_title)) ? $page_title : $content->page_title));
				# Set the sub title to the data member.
				$this->setSubTitle(((isset($sub_title) && !empty($sub_title)) ? $sub_title : $content->sub_title));
				# Set whether the page should be displayed to the data member.
				$this->setHideTitle($content->hide_title);
				# Set the page text to the data member.
				$this->setText($content->content);
				# Set the page quote to the data member.
				$this->setQuote($content->quote);
				# Check if `topic` was empty.
				if(!empty($content->topic))
				{
					# Set the "page-topic" meta tag variable with the `topic` value.
					$page_topic=$content->topic;
				}
				# Set the image to the data member.
				$this->setImage($content->image);
				# Set the image's title to the data member.
				$this->setImageTitle($content->image_title);
				# Set the content's archive to a variable.
				$this->setArchive($content->archive);
				# Set whether the social links should be displayed to the data member.
				$this->setUseSocial($content->social);
				# Set the content's api to a variable.
				$this->setAPI($content->api);
				return TRUE;
			}
			return FALSE;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('Error occured: '.$e->getMessage().', code: '.$e->getCode().'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getContent

	/*** End protected methods ***/

} #=== End Content class.