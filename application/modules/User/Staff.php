<?php /* Requires PHP5+ */

require_once MODULES.'User'.DS.'User.php';

/**
 * Staff
 *
 * The Staff Class is used to access and manipulate the `staff` table.
 *
 */
class Staff extends User
{
	/*** data members ***/

	protected $affiliation=NULL;
	protected $all_staff=NULL;
	protected $archive;
	protected $credentials=NULL;
	protected $exploded_positions=NULL;
	protected $new_position=NULL;
	protected $position=NULL;
	protected $position_where_sql=NULL;
	protected $staff_id=NULL;
	protected $staff_image=NULL;
	protected $staff_image_title=NULL;
	protected $staff_fname=NULL;
	protected $staff_mname=NULL;
	protected $staff_lname=NULL;
	protected $staff_region=NULL;
	protected $staff_title=NULL;
	protected $text=NULL;
	protected $user=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * @access	public
	 */
	public function __construct()
	{
		return;
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllStaff
	 *
	 * Sets the data member $all_staff.
	 *
	 * @param		$staff (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllStaff($staff)
	{
		# Check if the passed value is empty.
		if(!empty($staff))
		{
			# Explicitly make it an array.
			$staff=(array)$staff;
			# Set the data member.
			$this->all_staff=$staff;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_staff=NULL;
		}
	} #==== End -- setAllStaff

	/**
	 * setStaffID
	 *
	 * Sets the data member $staff_id.
	 *
	 * @param	$staff_id				The staff id number.
	 * @access	public
	 */
	public function setStaffID($staff_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $staff_id is empty.
		if(!empty($staff_id))
		{
			# Clean it up.
			$staff_id=trim($staff_id);
			# Check if the passed $id is an integer.
			if($validator->isInt($staff_id)===TRUE)
			{
				# Explicitly make it an integer.
				$staff_id=(int)$staff_id;
				# Set the data member
				$this->staff_id=$staff_id;
			}
			else
			{
				throw new Exception('The passed staff id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_id=NULL;
		}
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
	 * @param	$new_position				The person's new position.
	 * @access	public
	 */
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
	 * setPositionWhereSQL
	 *
	 * Sets the data member $position_where_sql.
	 *
	 * @param		string 		$positions
	 * @access	protected
	 */
	protected function setPositionWhereSQL($positions)
	{
		# Check if the passed value is empty.
		if(!empty($positions))
		{
			# Set the data member.
			$this->position_where_sql=$positions;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->position_where_sql=NULL;
		}
	} #==== End -- setPositionWhereSQL

	/**
	 * setStaffImage
	 *
	 * Sets the data member $staff_image.
	 *
	 * @param		$image (The person's image.)
	 * @access	protected
	 */
	protected function setStaffImage($image)
	{
		# Check if the passed value is empty.
		if(!empty($image))
		{
			# Set the data member.
			$this->staff_image=trim($image);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_image=NULL;
		}
	} #==== End -- setStaffImage

	/**
	 * setStaffImageTitle
	 *
	 * Sets the data member $staff_image_title.
	 *
	 * @param		$image_title (The title of the person's image.)
	 * @access	protected
	 */
	protected function setStaffImageTitle($image_title)
	{
		# Check if the passed value is empty.
		if(!empty($image_title))
		{
			# Strip slashes and decode any html entities.
			$image_title=html_entity_decode(stripslashes($image_title), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->staff_image_title=trim($image_title);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_image_title=NULL;
		}
	} #==== End -- setStaffImageTitle

	/**
	 * setStaffFirstName
	 *
	 * Sets the data member $staff_fname.
	 *
	 * @param		$staff_fname (The person's first name.)
	 * @access	protected
	 */
	protected function setStaffFirstName($staff_fname)
	{
		# Check if the passed value is empty.
		if(!empty($staff_fname))
		{
			# Strip slashes and decode any html entities.
			$staff_fname=html_entity_decode(stripslashes($staff_fname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->staff_fname=trim($staff_fname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_fname=NULL;
		}
	} #==== End -- setStaffFirstName

	/**
	 * setStaffMiddleName
	 *
	 * Sets the data member $staff_mname.
	 *
	 * @param		$staff_mname (The person's middle name.)
	 * @access	public
	 */
	public function setStaffMiddleName($staff_mname)
	{
		# Check if the passed value is empty.
		if(!empty($staff_mname))
		{
			# Strip slashes and decode any html entities.
			$staff_mname=html_entity_decode(stripslashes($staff_mname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->staff_mname=trim($staff_mname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_mname=NULL;
		}
	} #==== End -- setStaffMiddleName

	/**
	 * setStaffLastName
	 *
	 * Sets the data member $staff_lname.
	 *
	 * @param		$staff_lname (The person's last name.)
	 * @access	protected
	 */
	protected function setStaffLastName($staff_lname)
	{
		# Check if the passed value is empty.
		if(!empty($staff_lname))
		{
			# Strip slashes and decode any html entities.
			$staff_lname=html_entity_decode(stripslashes($staff_lname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->staff_lname=trim($staff_lname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->lname=NULL;
		}
	} #==== End -- setStaffLastName

	/**
	 * setStaffRegion
	 *
	 * Sets the data member $staff_region.
	 *
	 * @param		$staff_region (The person's region.)
	 * @access	protected
	 */
	protected function setStaffRegion($staff_region)
	{
		# Check if the passed value is empty.
		if(!empty($staff_region))
		{
			# Strip slashes and decode any html entities.
			$staff_region=html_entity_decode(stripslashes($staff_region), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->staff_region=trim($staff_region);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_region=NULL;
		}
	} #==== End -- setStaffRegion

	/**
	 * setStaffTitle
	 *
	 * Sets the data member $staff_title.
	 *
	 * @param		$title (The person's title.)
	 * @access	protected
	 */
	protected function setStaffTitle($title)
	{
		if(!empty($title))
		{
			# Strip slashes and decode any html entities.
			$title=html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$title=trim($title);
			# Set the data member.
			$this->staff_title=$title;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->staff_title=NULL;
		}
	} #==== End -- setStaffTitle

	/**
	 * setText
	 *
	 * Sets the data member $text.
	 *
	 * @param		$text (The person's biographical text.)
	 * @access	protected
	 */
	protected function setText($text)
	{
		if(!empty($text))
		{
			# Strip slashes and decode any html entities.
			$text=html_entity_decode(stripslashes($text), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$text=trim($text);
			# Replace any donain tokens with the current domain name.
			$text=str_ireplace('%{domain_name}', DOMAIN_NAME, $text);
			# Set the data member.
			$this->text=$text;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->text=NULL;
		}
	} #==== End -- setText

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param		string 		$user	(The staff's User ID)
	 * @access	protected
	 */
	protected function setUser($user)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed User ID is empty.
		if(!empty($user))
		{
			# Clean it up.
			$user=trim($user);
			# Check if the passed User ID is an integer.
			if($validator->isInt($user)===TRUE)
			{
				# Explicitly make it an integer.
				$user=(int)$user;
				# Set the data member
				$this->user=$user;
			}
			else
			{
				throw new Exception('The passed User ID was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->user=NULL;
		}
	} #==== End -- setStaffUser

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllStaff
	 *
	 * Returns the data member $all_staff.
	 *
	 * @access	public
	 */
	public function getAllStaff()
	{
		return $this->all_staff;
	} #==== End -- getAllStaff

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
	public function getNewPosition()
	{
		return $this->new_position;
	} #==== End -- getNewPosition

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
	 * getPositionWhereSQL
	 *
	 * Returns the data member $position_where_sql.
	 *
	 * @access	protected
	 */
	protected function getPositionWhereSQL()
	{
		return $this->position_where_sql;
	} #==== End -- getPositionWhereSQL

	/**
	 * getStaffImage
	 *
	 * Returns the data member $staff_image if the img data member from the parent User class is empty, otherwise it returns the parent class data member $img.
	 *
	 * @access	public
	 */
	public function getStaffImage()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the img data member from the parent User class to a variable.
			$img=$this->getImg();
			# Check if the img data member from the parent User class is empty.
			if($img!='default-avatar.png')
			{
				# Set the image to the data member.
				$this->setStaffImage($img);
			}
		}
		return $this->staff_image;
	} #==== End -- getStaffImage

	/**
	 * getStaffImageTitle
	 *
	 * Returns the data member $staff_image_title if the img_title data member from the parent User class is empty, otherwise it returns the parent class data member $img_title.
	 *
	 * @access	public
	 */
	public function getStaffImageTitle()
	{

		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the img_title data member from the parent User class to a variable.
			$img_title=$this->getImgTitle();
			# Check if the img_title data member from the parent User class is empty.
			if(!empty($img_title))
			{
				# Set the image_title to the data member.
				$this->setStaffImageTitle($img_title);
			}
		}
		return $this->staff_image_title;
	} #==== End -- getStaffImageTitle

	/**
	 * getStaffFirstName
	 *
	 * Returns the data member $staff_fname if the fname data member from the parent User class is empty, otherwise it returns the parent class data member $fname.
	 *
	 * @access	public
	 */
	public function getStaffFirstName()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the fname data member from the parent User class to a variable.
			$fname=$this->getFirstName();
			# Check if the fname data member from the parent User class is empty.
			if(!empty($fname))
			{
				# Set the first name to the data member.
				$this->setStaffFirstName($fname);
			}
		}
		return $this->staff_fname;
	} #==== End -- getStaffFirstName

	/**
	 * getStaffMiddleName
	 *
	 * Returns the data member $staff_mname if the mname data member from the parent User class is empty, otherwise it returns the parent class data member $mname.
	 *
	 * @access	public
	 */
	public function getStaffMiddleName()
	{
		return $this->staff_mname;
	} #==== End -- getStaffMiddleName

	/**
	 * getStaffLastName
	 *
	 * Returns the data member $staff_lname if the lname data member from the parent User class is empty, otherwise it returns the parent class data member $lname.
	 *
	 * @access	public
	 */
	public function getStaffLastName()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the lname data member from the parent User class to a variable.
			$lname=$this->getLastName();
			# Check if the lname data member from the parent User class is empty.
			if(!empty($lname))
			{
				# Set the last name to the data member.
				$this->setStaffLastName($lname);
			}
		}
		return $this->staff_lname;
	} #==== End -- getStaffLastName

	/**
	 * getStaffRegion
	 *
	 * Returns the data member $staff_region if the region data member from the parent User class is empty, otherwise it returns the parent class data member $region.
	 *
	 * @access	public
	 */
	public function getStaffRegion()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the region data member from the parent User class to a variable.
			$region=$this->getRegion();
			# Check if the region data member from the parent User class is empty.
			if(!empty($region))
			{
				# Set the region to the data member.
				$this->setStaffRegion($region);
			}
		}
		return $this->staff_region;
	} #==== End -- getStaffRegion

	/**
	 * getStaffTitle
	 *
	 * Returns the data member $staff_title if the title data member from the parent User class is empty, otherwise it returns the parent class data member $title.
	 *
	 * @access	public
	 */
	public function getStaffTitle()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the title data member from the parent User class to a variable.
			$title=$this->getTitle();
			# Check if the title data member from the parent User class is empty.
			if(!empty($title))
			{
				# Set the title to the data member.
				$this->setStaffTitle($title);
			}
		}
		return $this->staff_title;
	} #==== End -- getStaffTitle

	/**
	 * getText
	 *
	 * Returns the data member $text.
	 *
	 * @access	public
	 */
	public function getText()
	{
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Set the bio data member from the parent User class to a variable.
			$bio=$this->getBio();
			# Check if the title data member from the parent User class is empty.
			if(!empty($bio))
			{
				# Set the bio to the data member.
				$this->setText($bio);
			}
		}
		return $this->text;
	} #==== End -- getText

	/**
	 * getUser
	 *
	 * Returns the data member $user.
	 *
	 * @access	public
	 */
	public function getUser()
	{
		return $this->user;
	} #==== End -- getUser

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * addStaff
	 *
	 * Adds or updates a person in the `staff` table in the Database.
	 *
	 * @access	public
	 * @return	string
	 */
	public function addStaff()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the staff id and set it to a variable.
			$id=$this->getStaffID();
			# Get the person's affiliation and set it to a variable.
			$affiliation=$this->getAffiliation();
			# Get the person's archive status and set it to a variable.
			$archive=$this->getArchive();
			# Get the person's credentials and set it to a variable.
			$credentials=$this->getCredentials();
			# Get the person's position and set it to a variable.
			$position=$this->getPosition();
			# Get the person's image and set it to a variable.
			$image=$this->getStaffImage();
			# Get the image title for the person's and set it to a variable.
			$image_title=$this->getStaffImageTitle();
			# Get the person's first name and set it to a variable.
			$fname=$this->getStaffFirstName();
			# Get the person's middle name and set it to a variable.
			$mname=$this->getStaffMiddleName();
			# Get the person's last name and set it to a variable.
			$lname=$this->getStaffLastName();
			# Get the person's region and set it to a variable.
			$region=$this->getStaffRegion();
			# Get the person's region and set it to a variable.
			$title=$this->getStaffTitle();
			# Get the person's biographical text and set it to a variable.
			$text=$this->getText();
			# Get the person's User ID and set it to a variable.
			$user=$this->getUser();
			# Create a variable to hold the sql field/value relationships.
			$set=((empty($title)) ? '' : '`title` = '.$db->quote($db->escape($title)).', ').
				((empty($fname)) ? '' : '`fname` = '.$db->quote($db->escape($fname)).', ').
				((empty($mname)) ? '' : '`mname` = '.$db->quote($db->escape($mname)).', ').
				((empty($lname)) ? '' : '`lname` = '.$db->quote($db->escape($lname)).', ').
				((empty($credentials)) ? '' : '`credentials` = '.$db->quote($db->escape($credentials)).', ').
				((empty($region)) ? '' : '`region` = '.$db->quote($db->escape($region)).', ').
				((empty($affiliation)) ? '' : '`affiliation` = '.$db->quote($db->escape($affiliation)).', ').
				'`position` = '.$db->quote($db->escape($position)).', '.
				((empty($image_title)) ? '' : '`image_title` = '.$db->quote($db->escape($image_title)).', ').
				((empty($image)) ? '' : '`image` = '.$db->quote($db->escape($image)).', ').
				((empty($text)) ? '' : '`text` = '.$db->quote($db->escape($text)).', ').
				((empty($user)) ? '' : '`user` = '.$db->quote($db->escape($user)).', ').
				(($archive===NULL) ? '' : '`archive` = '.$db->quote('0'));
			# Add the person to the `staff` table. This will update if the person is already in the table.
			$add=$db->query('INSERT INTO `'.DBPREFIX.'staff` SET '.
				((!empty($id)) ? '`id` = '.$db->quote($id).', ' : '').
				$set.
				'ON DUPLICATE KEY UPDATE'.
				$set
			);
			# Check if the staff id was empty.
			if(!empty($id))
			{
				# Set the staff id to the $value variable.
				$value=$id;
				# Set the $field varaible.
				$field='id';
			}
			# Check if the User ID was empty.
			elseif(!empty($user))
			{
				# Set the staff id to the $value variable.
				$value=$user;
				# Set the $field varaible.
				$field='user';
			}
			# Check if the name variables were empty.
			elseif(!empty($fname) || !empty($mname) || !empty($lname))
			{
				# Set the first name to the $value array.
				$value['fname']=$fname;
				# Set the middle name to the $value array.
				$value['mname']=$mname;
				# Set the last name to the $value array.
				$value['lname']=$lname;
				# Set the $field varaible.
				$field='name';
			}
			# Try to get the person from the `staff` table again to repopulate the data members.
			$this->getThisPerson($value, $field);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error adding the staff\'s data: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addStaff

	/**
	* countAllStaff
	*
	* Returns the number of people in the `staff` table that are not marked archived.
	*
	* @param	$position (The name of the position(s) to be retrieved. May be multiple positions - separate with an asterisk(*), ie. 'Board of Directors*intern*CFO')
	* @param	$limit 		(The LIMIT of the records.)
	* @param	$and_sql 	(Any extra AND queries.)
	* @access	public
	*/
	public function countAllStaff($position=NULL, $limit=NULL, $and_sql=NULL)
	{
		global $db;
		try
		{
			$this->createPositionWhereSQL($position);
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'staff` WHERE ('.$this->getPositionWhereSQL().') '.(($and_sql===NULL) ? '' : $and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception($ez, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllStaff

	/***
	 * displayPerson
	 *
	 * Retrieves the person's information from the database sets to to a display array. This method is designed to feed the displayProfile method of the parent User class.
	 *
	 * @param	String $value			May be the staff id, the person's User ID, the person's email, or the person's first and last names - names must be in an array.
	 * @param	String $field			The field in the `staffs` table that $value is associated with.
	 * @param	Boolean $find			TRUE to try to find the User using the parent User methods, FALSE to not.
	 * @return	array
	 * @access	public
	 */
	public function displayPerson($value, $field='id', $find=FALSE, $label=TRUE)
	{
		# Bring the COntent object into scope.
		global $content;

		# Get the person's info and set it to the data members.
		$person=$this->getThisPerson($value, $field, $find);
		# Check if the contributor was retrieved.
		if($person===TRUE)
		{
			# Check if this record is archived.
			if($this->getArchive()===NULL)
			{
				# Set the person's information to variables in the scope of this method.
				$affiliation=$this->getAffiliation();
				$archive=$this->getArchive();
				$display_name=$this->getStaffName();
				$id=$this->getStaffID();
				$image=$this->getStaffImage();
				$image_title=$this->getStaffImageTitle();
				$positions=$this->getPosition();
				$region=$this->getStaffRegion();
				$text=$this->getText();

				# Create new array to hold all display content.
				$display_content=array('affiliation'=>NULL, 'archive'=>NULL, 'description'=>NULL, 'id'=>$id, 'image'=>NULL, 'image_title'=>NULL, 'name'=>NULL, 'position'=>NULL, 'region'=>NULL, 'text'=>NULL);

				# Set the person's display name to a variable.
				$profile_name='<span class="profile-name">'.$display_name.'</span>';
				# Set the name XHTML to the display content array.
				$display_content['name']=$profile_name;
				# Check if the person's region is available.
				if(!empty($region))
				{
					# Set the person's region to a variable.
					$profile_region='<span class="profile-region">';
					# Check if the label should be displayed.
					if($label===TRUE) { $profile_region.='<span class="label">Region:</span>'; }
					$profile_region.='<span>'.$region.'</span>';
					$profile_region.='</span>';
					# Set the region XHTML to the display content array.
					$display_content['region']=$profile_region;
				}
				# Check if the person's affiliation is available.
				if(!empty($affiliation))
				{
					# Set the person's affiliation to a variable.
					$profile_affiliation='<span class="profile-affiliation">';
					# Check if the label should be displayed.
					if($label===TRUE) { $profile_affiliation.='<span class="label">Affiliation:</span>'; }
					$profile_affiliation.='<span>'.$affiliation.'</span>';
					$profile_affiliation.='</span>';
					# Set the affiliation XHTML to the display content array.
					$display_content['affiliation']=$profile_affiliation;
				}
				$display_content['position']=json_decode($positions, TRUE);
				# Check if the image title is available.
				if(empty($image_title))
				{
					# Set the person's display name as the image title.
					$image_title=$display_name;
				}
				# Set the image title to the display content array.
				$display_content['image_title']=$image_title;
				# Get the Image class.
				require_once MODULES.'Media'.DS.'Image.php';
				# Instantiate a new Image object.
				$image_obj=new Image();
				# Set the person's image to a variable.
				$profile_image='<div class="profile-image">';
				$profile_image.=$image_obj->displayImage(TRUE, $image, $image_title);
				$profile_image.='</div>';
				# Set the image XHTML to the display content array.
				$display_content['image']=$profile_image;
				# Check if the person's biographical text is available.
				if(!empty($text))
				{
					# Set the person's biographical text to a variable.
					$profile_bio='<span class="profile-bio">';
					# Check if the label should be displayed.
					if($label===TRUE) { $profile_bio.='<span class="label">Biographical Information:</span>'; }
					$profile_bio.=$text;
					$profile_bio.='</span>';
					# Set the biographical text XHTML to the display content array.
					$display_content['text']=$profile_bio;
				}
				return $display_content;
			}
		}
		return NULL;
	} #==== End -- displayPerson

	/**
	 * getStaff
	 *
	 * Retrieves records from the `staff` table.
	 *
	 * @param		$positions 	(The names and/or id's of the position(s) to be retrieved. May be multiple positions - separate with dash, ie. '50-60-Archives-110')
	 * @param		$limit 			(The LIMIT of the records.)
	 * @param		$fields 		(The name of the field(s) to be retrieved.)
	 * @param		$order 			(The name of the field to order the records by.)
	 * @param		$direction 	(The direction to order the records.)
	 * @param		$and_sql 		(Extra AND statements in the query.)
	 * @return	Boolean 		(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getStaff($positions=NULL, $limit=NULL, $fields='*', $order='id', $direction='ASC', $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Create a variable to hold the default value for the WHERE clause.
			//$where='`position` != '.$db->quote($db->escape('-'));
			$where='';
			# Check if the passed $positions is empty.
			if(!empty($positions))
			{
				# Get the Position class.
				require_once MODULES.'Content'.DS.'Position.php';
				# Instantiate a new Position object.
				$position_obj=new Position();
				# Create the WHERE clause for the passed $positions string.
				$position_obj->createWhereSQL($positions);
				# Set the newly created WHERE clause to a variable.
				$where=$position_obj->getWhereSQL();
			}
			# Retrieve the records from the `staff` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'staff` WHERE '.$where.(($and_sql===NULL) ? '' : ' '.$and_sql).' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllStaff($records);
				return TRUE;
			}
			else
			{
				$this->setAllStaff(NULL);
				# Return FALSE because no records were returned.
				return FALSE;
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: ' . $ez->message . '<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getStaff

	/**
	 * getThisPerson
	 *
	 * Retrieves a person's info from the `staff` table in the Database for the passed value and related field and sets it to the data member.
	 *
	 * @param		String 	$value 	(May be the staff id, the person's User ID or the person's first, middle, and/or last names - names must be in an array with keys fname, mname, and lname respectively. Empty keys must be set to a value of NULL)
	 * @param		String 	$field 	(The field in the `staff` table that $value is associated with.)
	 * @param		Boolean $find 	(TRUE to try to find the person using the parent User methods, FALSE to not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisPerson($value=NULL, $field='user', $find=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is NULL and if the user should be located.
			if(($value===NULL) && ($find===TRUE))
			{
				# Check if the passsed $value is a User ID.
				if($field=='user')
				{
					# Set the $value variable from the logged in User's ID.
					$value=$this->findUserID();
				}
				# Check if the passed $value is the person's name.
				elseif($field=='name')
				{
					# Set the $value variable from the logged in User's first name.
					$value['fname']=$this->findFirstName();
					# Set the $value variable from the logged in User's last name.
					$value['lname']=$this->findLastName();
				}
			}
			# Check if $value is empty.
			if(!empty($value))
			{
				# Check if the passsed $value is a User ID.
				if($field=='user')
				{
					# Set the User's ID to the data member.
					$this->setUser($value);
					# Reset the $value variable with the User's ID and setting the $field value as the array key.
					$value=array($field=>$this->getUser());
				}
				# Check if the passed $value is the person's name.
				elseif($field=='name')
				{
					# Set the person's first name to the data member.
					$this->setStaffFirstName($value['fname']);
					# Reset the $value variable with the person's first name.
					$value['fname']=$this->getStaffFirstName();
					# Set the person's middle name to the data member.
					$this->setStaffMiddleName($value['mname']);
					# Reset the $value variable with the person's middle name.
					$value['mname']=$this->getStaffMiddleName();
					# Set the person's last name to the data member.
					$this->setStaffLastName($value['lname']);
					# Reset the $value variable with the person's last name.
					$value['lname']=$this->getStaffLastName();
				}
				# Check if the passed $value is the person's id.
				elseif($field=='id')
				{
					# Set the person's id to the data member.
					$this->setStaffID($value);
					# Reset the $value variable with the person's id and setting the $field value as the array key.
					$value=array($field=>$this->getStaffID());
				}
				# Create the WHERE sql statement.
				# Create an empty array to hold the WHERE statement pieces.
				$where=array();
				# Loop throught the $value array.
				foreach($value as $table=>$t_value)
				{
					# Check if $t_value is empty.
					if(!empty($t_value))
					{
						# Set the "`table` = 'value'" portion of the sql statement to the where array.
						$where[]='`'.$table.'` = '.$db->quote($db->escape($t_value));
					}
				}
				# Implode the $where array to join the pieces with "AND".
				$where=implode(' AND ', $where);
				# Retrieve the person from the `staff` table.
				$row=$db->get_row('SELECT `id`, `title`, `fname`, `mname`, `lname`, `credentials`, `region`, `affiliation`, `position`, `image_title`, `image`, `text`, `user`, `archive` FROM `'.DBPREFIX.'staff` WHERE '.$where.' LIMIT 1');
				# Check if a record was returned.
				if($row!==NULL)
				{
					# Set the returned values to the data members.
					$this->setStaffID($row->id);
					$this->setStaffTitle($row->title);
					$this->setStaffFirstName($row->fname);
					$this->setStaffMiddleName($row->mname);
					$this->setStaffLastName($row->lname);
					$this->setCredentials($row->credentials);
					$this->setStaffRegion($row->region);
					$this->setAffiliation($row->affiliation);
					$this->setPosition($row->position);
					$this->setStaffImageTitle($row->image_title);
					$this->setStaffImage($row->image);
					$this->setText($row->text);
					$this->setUser($row->user);
					$this->setArchive($row->archive);
					# Set the User ID to a variable.
					$user=$this->getUser();
					# Check if the User ID is empty.
					if(!empty($user))
					{
						# Get the person's username.
						$username=$this->findUsername($user);
						# Get the user's info.
						$this->findUserData($username, FALSE);
					}
					return TRUE;
				}
			}
			# Return FALSE because there was no record returned.
			return FALSE;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error retrieveing the person\'s data from the `staff` table: '.$e->error.', code: '.$e->errno.'<br />
			Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisPerson

	/**
	 * getStaffName
	 *
	 * Joins the person's title, first, middle, and last names, and credentials to create a full name. If the Display Name data member is set in the parent User class it is set instead.
	 *
	 * @access	public
	 */
	public function getStaffName()
	{
		$name=NULL;
		# Check if this person has a User ID.
		if($this->getUser()!==NULL)
		{
			# Get the person's display name.
			$display=$this->getDisplayName();
			# Set the user's display name as the person's name.
			$name=$display;
		}
		if(empty($name))
		{
			# Set the person's title to a variable.
			$title=$this->getStaffTitle();
			# Set the person's first name to a variable.
			$fname=$this->getStaffFirstName();
			# Set the person's middle name to a variable.
			$mname=$this->getStaffMiddleName();
			# Set the person's last name to a variable.
			$lname=$this->getStaffLastName();
			# Set the person's credentials to a variable.
			$credentials=$this->getCredentials();
			# Set the person's name.
			$name=(
				((!empty($fname)) ? $fname : '').
				((!empty($mname)) ? ((!empty($fname)) ? ' ' : '').$mname : '').
				((!empty($lname)) ? ((!empty($fname) || !empty($mname)) ? ' ' : '').$lname : '').
				((!empty($credentials)) ? ((!empty($fname) || !empty($mname) || !empty($lname)) ? ' ' : '').$credentials : '')
			);
		}
		return $name;
	} #==== End -- getStaffName

	/**
	 * updatePerson
	 *
	 * Updates the person's record in the DataBase.
	 *
	 * @param		array		$where_field (Key= the field, Value= the field value.)
	 * @param		array		$field_value (Key= the field, Value= the field value.)
	 * @access	public
	 */
	public function updatePerson($where_field, $field_value)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

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
				# Check if the field is "archive".
				if($field=='archive' && ($value===0 || $value==='0'))
				{
					# Explicitly update the value to 0.
					$value=0;
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

		try
		{
			# Update the User's data in the `users` table.
			$update_user=$db->query('UPDATE `'.DBPREFIX.'staff` SET '.$set.' WHERE '.$where.' LIMIT 1');
			return $update_user;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error updating Staff person info: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	} #==== End -- updatePerson

	/**
	 * removeUser
	 *
	 * Remove's a User from the person's data in the `staff` table.
	 *
	 * @param		string 	$user_id (The person's User ID.)
	 * @access	public
	 * @return	Boolean
	 */
	public function removeUser($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Set the User ID to the data member, effectively cleaning it up.
			$this->setUser($user_id);
			# Reset the variable from the data member.
			$user_id=$this->getUser();
			# Set the `user` value to NULL for this person in the `staff` table.
			$update_person=$db->query('UPDATE `'.DBPREFIX.'staff` SET `user` = NULL WHERE `user` = '.$db->quote($user_id).' LIMIT 1');
			# Check that there was a result.
			if($update_person==1) { return TRUE; }
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error removing User ID: '.$user_id.' from the person\'s data: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		return FALSE;
	} #==== End -- removeUser

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * createPositionWhereSQL
	 *
	 * Explodes an asterisk(*) sepparated list of positions and formats them for the WHERE portion of an sql query.
	 *
	 * @param		$positions 	(The positions of the person to be retrieved. May be multiple positions - separate with an asterisk(*), ie. 'Core Member*Servant*CFO')
	 * @access	protected
	 */
	public function createPositionWhereSQL($positions=NULL)
	{
		# Bring Database object into scope.
		global $db;

		# Check if the passed $positions is empty.
		if(!empty($positions))
		{
			# Explode $positions into an array.
			$positions=explode('*', $positions);
			# Create an empty array to hold the positions.
			$positions_a=array();
			# Loop though the position array.
			foreach($positions as $position)
			{
				# Set the sql to the array.
				$positions_a[]='`position` = '.$db->quote($db->escape($position));
			}
			# Implode the $positions_a array into a complete mysql WHERE string.
			$position_where_sql=implode(' OR ', $positions_a);

			# Set the sql string to the data member.
			$this->setPositionWhereSQL($position_where_sql);
		}
		else
		{
			throw new Exception('You must provide a position!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- createPositionWhereSQL

	/*** End protected methods ***/

} # End Staff class.