<?php /* framework/application/modules/User/Staff.php */

/**
 * Staff
 *
 * The Staff Class is used to access and manipulate the `staff` table.
 *
 */
class Staff
{
	protected $affiliation=NULL;
	protected $all_staff=NULL;
	protected $archive;
	protected $credentials=NULL;
	protected $fname=NULL;
	protected $id=NULL;
	protected $image=NULL;
	protected $image_title=NULL;
	protected $lname=NULL;
	protected $mname=NULL;
	protected $new_position=NULL;
	protected $position=NULL;
	protected $region=NULL;
	protected $title=NULL;
	protected $text=NULL;
	protected $user=NULL;

	/**
	 * setAffiliation
	 *
	 * Sets the data member $affiliation.
	 *
	 * @param $affiliation            The person's affiliation.
	 * @access public
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
	}

	/**
	 * setArchive
	 *
	 * Sets the data member $archive.
	 *
	 * @param    $archive                The records archive status.
	 * @access    public
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
	}

	/**
	 * setCredentials
	 *
	 * Sets the data member $credentials.
	 *
	 * @param    $credentials            The person's credentials.
	 * @access    public
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
	}

	/**
	 * setStaffFirstName
	 *
	 * Sets the data member $fname.
	 *
	 * @param    string $fname The person's first name.
	 * @access    public
	 */
	public function setFirstName($fname)
	{
		# Check if the passed value is empty.
		if(!empty($fname))
		{
			# Strip slashes and decode any html entities.
			$fname=html_entity_decode(stripslashes($fname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->fname=trim($fname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->fname=NULL;
		}
	}

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param    int $id The staff id number.
	 * @access    public
	 */
	public function setID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $staff_id is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
				# Set the data member
				$this->id=$id;
			}
			else
			{
				throw new Exception('The passed staff id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	}

	/**
	 * setImage
	 *
	 * Sets the data member $image.
	 *
	 * @param    $image                    The person's image.
	 * @access    public
	 */
	public function setImage($image)
	{
		# Check if the passed value is empty.
		if(!empty($image))
		{
			# Set the data member.
			$this->image=trim($image);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image=NULL;
		}
	}

	/**
	 * setImageTitle
	 *
	 * Sets the data member $image_title.
	 *
	 * @param    string $image_title The title of the person's image.
	 * @access    public
	 */
	public function setImageTitle($image_title)
	{
		# Check if the passed value is empty.
		if(!empty($image_title))
		{
			# Strip slashes and decode any html entities.
			$image_title=html_entity_decode(stripslashes($image_title), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->image_title=trim($image_title);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image_title=NULL;
		}
	}

	/**
	 * setLastName
	 *
	 * Sets the data member $lname.
	 *
	 * @param    string $lname The person's last name.
	 * @access    public
	 */
	public function setLastName($lname)
	{
		# Check if the passed value is empty.
		if(!empty($lname))
		{
			# Strip slashes and decode any html entities.
			$lname=html_entity_decode(stripslashes($lname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->lname=trim($lname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->lname=NULL;
		}
	}

	/**
	 * setMiddleName
	 *
	 * Sets the data member $mname.
	 *
	 * @param    string $mname The person's middle name.
	 * @access    public
	 */
	public function setMiddleName($mname)
	{
		# Check if the passed value is empty.
		if(!empty($mname))
		{
			# Strip slashes and decode any html entities.
			$mname=html_entity_decode(stripslashes($mname), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->mname=trim($mname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->mname=NULL;
		}
	}

	/**
	 * setNewPosition
	 *
	 * Sets the data member $new_position.
	 *
	 * @param    $new_position                The person's new position.
	 * @access    public
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
	}

	/**
	 * setPosition
	 *
	 * Sets the data member $position.
	 *
	 * @param    $position                The person's position.
	 * @access    public
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
	}

	/**
	 * setRegion
	 *
	 * Sets the data member $region.
	 *
	 * @param    string $region The person's region.
	 * @access    public
	 */
	public function setRegion($region)
	{
		# Check if the passed value is empty.
		if(!empty($region))
		{
			# Strip slashes and decode any html entities.
			$region=html_entity_decode(stripslashes($region), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->region=trim($region);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->region=NULL;
		}
	}

	/**
	 * setText
	 *
	 * Sets the data member $text.
	 *
	 * @param    string $text The person's biographical text.
	 * @access    public
	 */
	public function setText($text)
	{
		if(!empty($text))
		{
			# Strip slashes and decode any html entities.
			$text=html_entity_decode(stripslashes($text), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$text=trim($text);
			# Replace any tokens with their correlating value.
			$text=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $text);
			# Set the data member.
			$this->text=$text;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->text=NULL;
		}
	}

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param    string $title The person's title.
	 * @access    public
	 */
	public function setTitle($title)
	{
		if(!empty($title))
		{
			# Strip slashes and decode any html entities.
			$title=html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$title=trim($title);
			# Set the data member.
			$this->title=$title;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->title=NULL;
		}
	}

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param    string $user The staff's User ID
	 * @access    public
	 */
	public function setUser($user)
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
	}

	/**
	 * getAffiliation
	 *
	 * Returns the data member $affiliation.
	 *
	 * @access    public
	 */
	public function getAffiliation()
	{
		return $this->affiliation;
	}

	/**
	 * getAllStaff
	 *
	 * Returns the data member $all_staff.
	 *
	 * @access    public
	 */
	public function getAllStaff()
	{
		return $this->all_staff;
	}

	/**
	 * getArchive
	 *
	 * Returns the data member $archive.
	 *
	 * @access    public
	 */
	public function getArchive()
	{
		return $this->archive;
	}

	/**
	 * getCredentials
	 *
	 * Returns the data member $credentials.
	 *
	 * @access    public
	 */
	public function getCredentials()
	{
		return $this->credentials;
	}

	/**
	 * getFirstName
	 *
	 * Returns the data member $fname.
	 *
	 * @access    public
	 */
	public function getFirstName()
	{
		return $this->fname;
	}

	/**
	 * getID
	 *
	 * Returns the data member $id.
	 *
	 * @access    public
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * getImage
	 *
	 * Returns the data member $image.
	 *
	 * @access    public
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * getImageTitle
	 *
	 * Returns the data member $image_title.
	 *
	 * @access    public
	 */
	public function getImageTitle()
	{
		return $this->image_title;
	}

	/**
	 * getLastName
	 *
	 * Returns the data member $lname.
	 *
	 * @access    public
	 */
	public function getLastName()
	{
		return $this->lname;
	}

	/**
	 * getMiddleName
	 *
	 * Returns the data member $mname.
	 *
	 * @access    public
	 */
	public function getMiddleName()
	{
		return $this->mname;
	}

	/**
	 * getNewPosition
	 *
	 * Returns the data member $new_position.
	 *
	 * @access    public
	 */
	public function getNewPosition()
	{
		return $this->new_position;
	}

	/**
	 * getPosition
	 *
	 * Returns the data member $position.
	 *
	 * @access    public
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * getRegion
	 *
	 * Returns the data member $region.
	 *
	 * @access    public
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * getText
	 *
	 * Returns the data member $text.
	 *
	 * @access    public
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access    public
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * getUser
	 *
	 * Returns the data member $user.
	 *
	 * @access    public
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * displayStaff
	 *
	 * Retrieves the person's information from the database sets to to a display array.
	 *
	 * @param    string $value                May be the staff id, the person's User ID, the person's email, or the person's first and last names.
	 *                                        Names must be in an array.
	 * @param    boolean $label               TRUE to display HTML labeled sections, FALSE to not.
	 * @return    array
	 * @access    public
	 */
	public function displayStaff($value, $label=TRUE, $image_link=FW_POPUP_HANDLE)
	{
		# Get the person's info and set it to the data members.
		$person=$this->getThisStaff($value);
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
				$id=$this->getID();
				$image=$this->getImage();
				$image_title=$this->getImageTitle();
				$positions=$this->getPosition();
				$region=$this->getRegion();
				$text=$this->getText();

				# Create new array to hold all display content.
				$display_content=array('affiliation'=>NULL, 'archive'=>NULL, 'id'=>$id, 'image'=>NULL, 'image_title'=>NULL, 'name'=>NULL, 'position'=>NULL, 'region'=>NULL, 'text'=>NULL);

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
					if($label===TRUE)
					{
						$profile_region.='<span class="label">Region:</span>';
					}
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
					if($label===TRUE)
					{
						$profile_affiliation.='<span class="label">Affiliation:</span>';
					}
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
				require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
				# Instantiate a new Image object.
				$image_obj=new Image();
				# Set the person's image to a variable.
				$profile_image='<div class="profile-image">';
				$profile_image.=$image_obj->displayImage(TRUE, $image, $image_title, $image_link);
				$profile_image.='</div>';
				# Set the image XHTML to the display content array.
				$display_content['image']=$profile_image;
				# Check if the person's biographical text is available.
				if(!empty($text))
				{
					# Set the person's biographical text to a variable.
					$profile_bio='<span class="profile-bio">';
					# Check if the label should be displayed.
					if($label===TRUE)
					{
						$profile_bio.='<span class="label">Biographical Information:</span>';
					}
					$profile_bio.='<span>'.$text.'</span>';
					$profile_bio.='</span>';
					# Set the biographical text XHTML to the display content array.
					$display_content['text']=$profile_bio;
				}

				return $display_content;
			}
		}

		return NULL;
	}

	/**
	 * getStaff
	 *
	 * Retrieves records from the `staff` table.
	 *
	 * @param    $positions                   The names and/or id's of the position(s) to be retrieved.
	 *                                        May be multiple positions - separate with dash, ie. '50-60-Archives-110'
	 * @param    $limit                       The LIMIT of the records.
	 * @param    $fields                      The name of the field(s) to be retrieved.
	 * @param    $order                       The name of the field to order the records by.
	 * @param    $direction                   The direction to order the records.
	 * @param    $and_sql                     Extra AND statements in the query.
	 * @return    boolean                    TRUE if records are returned, FALSE if not.
	 * @access    public
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
				require_once Utility::locateFile(MODULES.'Content'.DS.'Position.php');
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
			throw new Exception('Error occured: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getStaffName
	 *
	 * Joins the person's title, first, middle, and last names, and credentials to create a full name.
	 *
	 * @param    int $value The staff's ID.
	 * @access    public
	 */
	public function getStaffName($value=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed ID is empty.
			if($validator->isInt($value)===TRUE)
			{
				# Retrieve the staffs names from `staff` table.
				$row=$db->get_row('SELECT `title`, `fname`, `mname`, `lname`, `credentials` FROM `'.DBPREFIX.'staff` WHERE `id` = '.$db->quote($db->escape($value)).' LIMIT 1');
				# Check if a record was returned.
				if($row!==NULL)
				{
					# Set the returned values to the data members.
					$this->setCredentials($row->credentials);
					$this->setFirstName($row->fname);
					$this->setLastName($row->lname);
					$this->setMiddleName($row->mname);
					$this->setTitle($row->title);
				}
			}

			# Set the person's title to a variable.
			$title=$this->getTitle();
			# Set the person's first name to a variable.
			$fname=$this->getFirstName();
			# Set the person's middle name to a variable.
			$mname=$this->getMiddleName();
			# Set the person's last name to a variable.
			$lname=$this->getLastName();
			# Set the person's credentials to a variable.
			$credentials=$this->getCredentials();
			# Set the person's name.
			$name=(
				((!empty($fname)) ? $fname : '').
				((!empty($mname)) ? ((!empty($fname)) ? ' ' : '').$mname : '').
				((!empty($lname)) ? ((!empty($fname) || !empty($mname)) ? ' ' : '').$lname : '').
				((!empty($credentials)) ? ((!empty($fname) || !empty($mname) || !empty($lname)) ? ' ' : '').$credentials : '')
			);

			# If name is empty, then user doesn't exist and throw an error.
			if(empty($name))
			{
				throw new Exception('The staff\'s name was not found!', E_RECOVERABLE_ERROR);
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error finding the staff\'s name: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}

		return $name;
	}

	/**
	 * getThisStaff
	 *
	 * Retrieves a staff's info from the `staff` table in the database for the passed value and related field and sets it to the data member.
	 *
	 * @param    string $value The person's staff ID.
	 * @return    boolean                TRUE if a record is returned, FALSE if not.
	 * @access    public
	 */
	public function getThisStaff($value)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the person from the `staff` table.
			$row=$db->get_row('SELECT `id`, `title`, `fname`, `mname`, `lname`, `credentials`, `region`, `affiliation`, `position`, `image_title`, `image`, `text`, `user`, `archive` FROM `'.DBPREFIX.'staff` WHERE `id` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a record was returned.
			if($row!==NULL)
			{
				# Set the returned values to the data members.
				$this->setAffiliation($row->affiliation);
				$this->setArchive($row->archive);
				$this->setCredentials($row->credentials);
				$this->setFirstName($row->fname);
				$this->setID($row->id);
				$this->setImage($row->image);
				$this->setImageTitle($row->image_title);
				$this->setLastName($row->lname);
				$this->setMiddleName($row->mname);
				$this->setPosition($row->position);
				$this->setRegion($row->region);
				$this->setText($row->text);
				$this->setTitle($row->title);
				$this->setUser($row->user);
				# Set the User ID to a variable.
				$user=$this->getUser();

				return TRUE;
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
	}

	/**
	 * findUsername
	 *
	 * Retrieves the User's username based on the passed variable. Throws an error on failure.
	 * A wrapper method for findUsername in the User calss.
	 *
	 * @param    int $value The user's ID for whome we want to get the username for.
	 * @access    public
	 */
	public function findUsername($value=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();
		# Bring the Login class into scope.
		global $login;

		# Check if the passed ID is empty.
		if($validator->isInt($value)===TRUE)
		{
			# Find the user's ID and set it to a variable.
			return $login->findUsername($value);
		}

		return FALSE;
	}

	/**
	 * findStaffID
	 *
	 * Retrieves the Staff's ID and sets it to the id data member. Throws an error on failure.
	 *
	 * @access    public
	 */
	/*
	public function findStaffID()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring the Login class into scope.
		global $login;

		try
		{
			# Get the logged in User's ID.
			$user_id=$login->findUserID();
			# Retrieve the records from the `staff` table.
			$records=$db->get_results('SELECT `id` FROM `'.DBPREFIX.'staff` WHERE `user`='.$user_id.' LIMIT 1');
			if($records!==NULL)
			{
				$this->setStaffID($records->id);
				return TRUE;
			}
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was a Database error: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	*/

	/*
	 * isStaff
	 *
	 * Checks if the user ID is in the `staff` table.
	 *
	 * @param	int $value				The user's ID.
	 *										If NULL, then the method gets the logged in user's ID.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function isStaff($value=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();
		# Bring the User class into scope.
		global $login;

		try
		{
			# Check if the passed value is empty.
			if(empty($value))
			{
				# Find the user ID for the logged in user.
				$id=$login->findUserID();
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
			# Retrieve the records from the `staff` table.
			$row=$db->get_results('SELECT `id` FROM `'.DBPREFIX.'staff` WHERE `user`='.$id.' LIMIT 1');
			if($row!==NULL)
			{
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
	}

	/**
	 * updateStaff
	 *
	 * Updates the staff's record in the database.
	 *
	 * @param    array $where_field Key=the field, Value=the field value.
	 * @param    array $field_value Key=the field, Value=the field value.
	 * @access    public
	 */
	public function updateStaff($where_field, $field_value)
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
			throw new Exception('There was an error updating staff info: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * setAllStaff
	 *
	 * Sets the data member $all_staff.
	 *
	 * @param    $staff                    May be an array or a string. The method makes it into an array regardless.
	 * @access    protected
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
	}
}