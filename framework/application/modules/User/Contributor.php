<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

require_once Utility::locateFile(MODULES.'User'.DS.'User.php');

/**
 * Contributor
 *
 * The Contributor Class is used to access and manipulate info in the `contributors` table.
 *
 */
class Contributor extends User
{
	/*** data members ***/

	protected $all_contributors=NULL;
	protected $cont_id=NULL;
	protected $cont_fname=NULL;
	protected $cont_lname=NULL;
	protected $cont_email=NULL;
	protected $cont_ip=NULL;
	protected $cont_region=NULL;
	protected $cont_country=NULL;
	protected $cont_organization=NULL;
	protected $cont_privacy;
	protected $user=NULL;
	/*** End data members ***/

	/*** mutator methods ***/

	/**
	 * getAllContributors
	 *
	 * Returns the data member $all_contributors.
	 *
	 * @access    public
	 */
	public function getAllContributors()
	{
		return $this->all_contributors;
	}

	/**
	 * getContID
	 *
	 * Returns the data member $cont_id.
	 *
	 * @access    public
	 */
	public function getContID()
	{
		return $this->cont_id;
	}

	/**
	 * getContFirstName
	 *
	 * Returns the data member $cont_fname if the fname data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContFirstName()
	{
		# Set the fname data member from the parent User class to a variable.
		$fname=$this->getFirstName();
		# Check if the fname data member from the parent User class is NULL.
		if($fname===NULL)
		{
			# Set the fname data member to the variable.
			$fname=$this->cont_fname;
		}

		return $fname;
	}

	/**
	 * getContLastName
	 *
	 * Returns the data member $cont_lname if the lname data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContLastName()
	{
		# Set the lname data member from the parent User class to a variable.
		$lname=$this->getLastName();
		# Check if the lname data member from the parent User class is NULL.
		if($lname===NULL)
		{
			# Set the lname data member to the variable.
			$lname=$this->cont_lname;
		}

		return $lname;
	}

	/**
	 * getContEmail
	 *
	 * Returns the data member $cont_email if the email data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContEmail()
	{
		# Set the email data member from the parent User class to a variable.
		$email=$this->getEmail();
		# Check if the email data member from the parent User class is NULL.
		if($email===NULL)
		{
			# Set the region data member to the variable.
			$email=$this->cont_email;
		}

		return $email;
	}

	/**
	 * getContIP
	 *
	 * Returns the data member $cont_ip.
	 *
	 * @access    public
	 */
	public function getContIP()
	{
		return $this->cont_ip;
	}

	/**
	 * getContRegion
	 *
	 * Returns the data member $cont_region if the region data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContRegion()
	{
		# Set the region data member from the parent User class to a variable.
		$region=$this->getRegion();
		# Check if the region data member from the parent User class is NULL.
		if($region===NULL)
		{
			# Set the region data member to the variable.
			$region=$this->cont_region;
		}

		return $region;
	}

	/**
	 * getContCountry
	 *
	 * Returns the data member $cont_country if the country data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContCountry()
	{
		# Set the country data member from the parent User class to a variable.
		$country=$this->getCountry();
		# Check if the country data member from the parent User class is NULL.
		if($country===NULL)
		{
			# Set the country data member to the variable.
			$country=$this->cont_country;
		}

		return $country;
	}

	/**
	 * getContOrganization
	 *
	 * Returns the data member $cont_organization if the organization data member from the parent User class is NULL, otherwise it returns that.
	 *
	 * @access    public
	 */
	public function getContOrganization()
	{
		# Set the organization data member from the parent User class to a variable.
		$organization=$this->getOrganization();
		# Check if the organization data member from the parent User class is NULL.
		if($organization===NULL)
		{
			# Set the organization data member to the variable.
			$organization=$this->cont_organization;
		}

		return $organization;
	}

	/**
	 * getContPrivacy
	 *
	 * Returns the data member $cont_privacy.
	 *
	 * @access    public
	 */
	public function getContPrivacy()
	{
		return $this->cont_privacy;
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

	/*** End mutator methods ***/

	/*** accessor methods ***/

	/**
	 * addContributor
	 *
	 * Adds or updates a User's data in the `contributors` table in the Database.
	 *
	 * @param string $user_id (The contributor's User ID.)
	 * @param bool $find
	 * @return string
	 * @throws Exception
	 */
	public function addContributor($user_id=NULL, $find=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the user ID is empty.
			if(empty($user_id))
			{
				# Set the user ID from the logged in User's ID.
				$user_id=$this->findUserID();
			}
			# Set the user ID to the data member effectively "cleaning" it.
			$this->setUser($user_id);
			# Reset the $user_id variable from the data member.
			$user_id=$this->getUser();
			# Get the user's current IP address.
			$ip=$this->findIP();
			# Try to get the contributor from the Database first by User ID.
			$contributor=$this->getThisContributor($user_id, 'user', $find);
			# Get the user's data.
			$this->findUserData($user_id);
			# Check if the contributor was found by user ID.
			if($contributor===FALSE)
			{
				# Try to get the contributor from the Database next by email.
				$contributor=$this->getThisContributor($this->getEmail(), 'email', $find);
				# Check if the contributor was found by email.
				if($contributor===FALSE)
				{
					# Try to get the contributor from the Database next by name.
					$contributor=$this->getThisContributor(array('fname'=>$this->getFirstName(), 'lname'=>$this->getLastName()), 'name', $find);
				}
			}
			# Check if a contributor was located.
			if($contributor===FALSE)
			{
				# Get the user's first name.
				$fname=$this->getFirstName();
				# Get the user's last name.
				$lname=$this->getLastName();
				# Get the user's email address.
				$email=$this->getEmail();
				# Get the user's region.
				$region=$this->getRegion();
				# Get the user's country.
				$country=$this->getCountry();
				# Get the user's organization.
				$organization=$this->getOrganization();
				# Set the User's privacy setting to "Not Hidden".
				$privacy=0;
				# Build the SQL query.
				$sql='INSERT INTO `'.DBPREFIX.'contributors` ('.
					((empty($fname)) ? '' : '`fname`, ').
					((empty($lname)) ? '' : '`lname`, ').
					'`email`, '.
					'`ip`, '.
					((empty($region)) ? '' : '`region`, ').
					((empty($country)) ? '' : '`country`, ').
					((empty($organization)) ? '' : '`organization`, ').
					'`user`) VALUES ('.
					((empty($fname)) ? '' : $db->quote($db->escape($fname)).', ').
					((empty($lname)) ? '' : $db->quote($db->escape($lname)).', ').
					$db->quote($db->escape($email)).', '.
					'INET_ATON('.$db->quote($db->escape($ip)).'), '.
					((empty($region)) ? '' : $db->quote($db->escape($region)).', ').
					((empty($country)) ? '' : $db->quote($db->escape($country)).', ').
					((empty($organization)) ? '' : $db->quote($db->escape($organization)).', ').
					$db->quote($user_id).
					')';
			}
			else
			{
				# Get the contributor's first name.
				$fname=$this->getContFirstName();
				# Get the contributor's last name.
				$lname=$this->getContLastName();
				# Get the contributor's email address.
				$email=$this->getContEmail();
				# Get the contributor's region.
				$region=$this->getContRegion();
				# Get the contributor's country.
				$country=$this->getContCountry();
				# Get the contributor's organization.
				$organization=$this->getContOrganization();
				# Get the contributor's privacy setting.
				$privacy=$this->getContPrivacy();
				$sql='UPDATE `'.DBPREFIX.'contributors` SET '.
					((empty($fname)) ? '' : '`fname` = '.$db->quote($db->escape($fname)).', ').
					((empty($lname)) ? '' : '`lname` = '.$db->quote($db->escape($lname)).', ').
					'`email` = '.$db->quote($db->escape($email)).', '.
					'`ip` = INET_ATON('.$db->quote($db->escape($ip)).'), '.
					((empty($region)) ? '' : '`region` = '.$db->quote($db->escape($region)).', ').
					((empty($country)) ? '' : '`country` = '.$db->quote($db->escape($country)).', ').
					((empty($organization)) ? '' : '`organization` = '.$db->quote($db->escape($organization)).', ').
					'`privacy` = '.(($privacy===NULL) ? 'NULL, ' : $db->quote($privacy)).', '.
					'`user` = '.$db->quote($user_id).
					' WHERE '.
					'`id` = '.$db->quote($this->getContID()).
					' LIMIT 1';
			}
			# Add the contributor to the `contributor` table. This will update if the contributor is already in the table.
			$db->query($sql);
			# Try to get the Contributor from the Database again to repopulate the data members.
			$this->getThisContributor($user_id);
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error adding the contributor\'s data: '.$e->error.', code: '.$e->errno.'<br />
			Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/***
	 * displayContributor
	 *
	 * Retrieves the contributor's information from the database sets to to a display array. This method is designed to feed the displayProfile method of the parent User class.
	 *
	 * @param    string $value May be the contributor id, user ID, email, or first and last names (names must be in an array).
	 * @param    string $field The field in the `contributors` table that $value is associated with.
	 * @param    boolean $find TRUE to try to find the User using the parent User methods, FALSE to not.
	 * @return    array
	 * @access    public
	 */
	public function displayContributor($value, $field='id', $find=FALSE)
	{
		# Get the contributor info and set it to the data members.
		$contributor=$this->getThisContributor($value, $field, $find);
		# Check if the contributor was retrieved.
		if($contributor===TRUE)
		{
			# Set the contributor's information to variables in the scope of this method.
			$name=$this->getContName();
			$region=$this->getContRegion();
			$country=$this->getContCountry();
			$organization=$this->getContOrganization();
			$privacy=$this->getContPrivacy();
			$email=$this->getContEmail();

			# Create new array to hold all display content.
			$display_content=array('name'=>'', 'region'=>'', 'country'=>'', 'organization'=>'', 'privacy'=>'', 'email'=>'');

			# Set the contributor's display name to a variable.
			$profile_name='<span class="profile-name">'.$name.'</span>';
			# Set the name XHTML to the display content array.
			$display_content['name']=$profile_name;
			# Check if the contributor's region is available.
			if(!empty($region))
			{
				# Set the contributor's region to a variable.
				$profile_region='<div class="profile-region">';
				$profile_region.='<span class="label">Region:</span>';
				$profile_region.='<span>'.$region.'</span>';
				$profile_region.='</div>';
				# Set the region XHTML to the display content array.
				$display_content['region']=$profile_region;
			}
			# Check if the contributor's country is available.
			if(!empty($country))
			{
				# Set the contributor's country to a variable.
				$profile_country='<div class="profile-country">';
				$profile_country.='<span class="label">Country:</span>';
				$profile_country.='<span>'.$country.'</span>';
				$profile_country.='</div>';
				# Set the country XHTML to the display content array.
				$display_content['country']=$profile_country;
			}
			# Check if the contributor's organization is available.
			if(!empty($organization))
			{
				# Set the contributor's organization to a variable.
				$profile_organization='<div class="profile-organization">';
				$profile_organization.='<span class="label">Organization:</span>';
				$profile_organization.='<span>'.$organization.'</span>';
				$profile_organization.='</div>';
				# Set the organization XHTML to the display content array.
				$display_content['organization']=$profile_organization;
			}
			# Set the contributor's privacy to the display content array.
			$display_content['privacy']=$privacy;
			# Set the contributor's email to the display content array.
			$display_content['email']=$email;

			return $display_content;
		}

		return NULL;
	}

	/**
	 * getContributors
	 *
	 * Retrieves records from the `contributors` table.
	 *
	 * @param        $limit     (The LIMIT of the records.)
	 * @param        $fields    (The name of the field(s) to be retrieved.)
	 * @param        $order     (The name of the field to order the records by.)
	 * @param        $direction (The direction to order the records.)
	 * @param        $and_sql   (Extra AND statements in the query.)
	 * @return    Boolean (TRUE if records are returned, FALSE if not.)
	 * @access    public
	 */
	public function getContributors($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `contributors` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'contributors`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllContributors($records);

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
	 * getThisContributor
	 *
	 * Retrieves a contributor's info from the `contributors` table in the Database for the passed value and related field and sets it to the data member.
	 *
	 * @param        String $value (May be the contributor id, the contributor's User ID, the contributor's email, or the contributor's first and last names - names must be in an
	 *                             array.)
	 * @param        String $field (The field in the `contributors` table that $value is associated with.)
	 * @param        Boolean $find (TRUE to try to find the contributor using the parent User methods, FALSE to not.)
	 * @return    Boolean                (TRUE if a record is returned, FALSE if not.)
	 * @access    public
	 */
	public function getThisContributor($value=NULL, $field='user', $find=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed User ID is NULL and if the user should be located..
			if(($value===NULL) && ($find===TRUE))
			{
				# Check if the passsed $value is a User ID.
				if($field=='user')
				{
					# Set the $value variable from the logged in User's ID.
					$value=$this->findUserID();
				}
				# Check if the passed $value is the contributor's email.
				elseif($field=='email')
				{
					# Set the $value variable from the logged in User's email.
					$value=$this->findEmail();
				}
				# Check if the passed $value is the contributor's name.
				elseif($field=='name')
				{
					# Set the $value variable from the logged in User's first name.
					$value['fname']=$this->findFirstName();
					# Set the $value variable from the logged in User's last name.
					$value['lname']=$this->findLastName();
				}
			}
			# Check if the passed User ID is NULL.
			if($value!==NULL)
			{
				# Check if the passsed $value is a User ID.
				if($field=='user')
				{
					# Set the User's ID to the data member.
					$this->setUser($value);
					# Reset the $value variable with the User's ID.
					$value=array($field=>$this->getUser());
				}
				# Check if the passed $value is the contributor's email.
				elseif($field=='email')
				{
					# Set the contributor's email to the data member.
					$this->setContEmail($value);
					# Reset the $value variable with the contributor's email.
					$value=array($field=>$this->getContEmail());
				}
				# Check if the passed $value is the contributor's name.
				elseif($field=='name')
				{
					# Set the contributor's first name to the data member.
					$this->setContFirstName($value['fname']);
					# Reset the $value variable with the contributor's first name.
					$value['fname']=$this->getContFirstName();
					# Set the contributor's last name to the data member.
					$this->setContLastName($value['lname']);
					# Reset the $value variable with the contributor's last name.
					$value['lname']=$this->getContLastName();
				}
				# Check if the passed $value is the contributor's id.
				elseif($field=='id')
				{
					# Set the contributor's id to the data member.
					$this->setContID($value);
					# Reset the $value variable with the contributor's id.
					$value=array($field=>$this->getContID());
				}
				# Create the WHERE sql statement.
				# Create an empty array to hold the WHERE statement pieces.
				$where=array();
				# Loop throught the $value array.
				foreach($value as $table=>$t_value)
				{
					# Check if $t_value is empty.
					if(empty($t_value))
					{
						# Reset $t_value to search for NULL fields.
						$t_value='IS NULL';
					}
					else
					{
						# Set $t_value to search for the $t_value.
						$t_value='= '.$db->quote($db->escape($t_value));
					}
					$where[]='`'.$table.'` '.$t_value;
				}
				# Implode the $where array to join the pieces with "AND".
				$where=implode(' AND ', $where);
				# Get the IP Class.
				require_once Utility::locateFile(MODULES.'IP'.DS.'IP.php');
				# Create a new IP object.
				$ip_obj=IP::getInstance();
				# Will return the correct MySQL function to use.
				$ip_field=$ip_obj->createSelectQueryParam('ip');
				# If $ip_field is FALSE, then the server is not running MySQL 5.6.3+ so just use the `ip` field.
				#	Then after the query use PHP's inet_ntop() function.
				$ip_field=($ip_field===FALSE ? '`ip`' : $ip_field.' AS ip');
				# Retrieve the contributor from the `contributors` table.
				$row=$db->get_row('SELECT `id`, `fname`, `lname`, `email`, '.$ip_field.', `region`, `country`, `organization`, `privacy`, `user` FROM `'.DBPREFIX.'contributors` WHERE '.$where.' LIMIT 1');
				# Check if a record was returned.
				if($row!==NULL)
				{
					# Set the returned values to the data members.
					$this->setContID($row->id);
					$this->setContFirstName($row->fname);
					$this->setContLastName($row->lname);
					$this->setContEmail($row->email);
					# NOTE: Should put this in the IP class?
					$this->setContIP(($ip_field===FALSE ? inet_ntop($row->ip) : $row->ip));
					$this->setContRegion($row->region);
					$this->setContCountry($row->country);
					$this->setContOrganization($row->organization);
					$this->setContPrivacy($row->privacy);
					$this->setUser($row->user);
					# Set the User ID to a variable.
					$user_id=$this->getUser();
					# Check if the User ID is empty.
					if(!empty($user_id))
					{
						# Get the contributor's username.
						$username=$this->findUsername($user_id);
						# Get the user's info.
						$this->findUserData($username);
					}

					return TRUE;
				}
			}

			# Return FALSE because there was no record returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error retrieveing the contributor\'s data: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getContName
	 *
	 * Joins the Contributor's first and last names to create a full name. If the Display Name data member is set in the parent User class it is set instead.
	 *
	 * @access    public
	 */
	public function getContName()
	{
		# Get the contributor's display name.
		$display=$this->getDisplayName();
		# Check if the display name is empty.
		if(!empty($display))
		{
			# Set the user's display name as the contributor's name.
			$name=$display;
		}
		else
		{
			# Set the contributor's first name to a variable.
			$fname=$this->getContFirstName();
			# Set the contributor's last name to a variable.
			$lname=$this->getContLastName();
			# Set the contributor's name.
			$name=(
				((!empty($fname)) ? $fname : '').
				((!empty($lname)) ? ((!empty($fname)) ? ' ' : '').$lname : '')
			);
		}

		return $name;
	}

	/**
	 * removeUser
	 *
	 * Remove's a User's from the contributor's data in the `contributors` table.
	 *
	 * @param    int                          /array $user_id        The contributor's User ID.
	 *                                        If it's an array, multiple users are being deleted.
	 * @access    public
	 * @return    boolean
	 */
	public function removeUser($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			$where='';
			# Check if the passed $user_id is an integer.
			if($validator->isInt($user_id)===TRUE)
			{
				# Set the User ID to the data member, effectively cleaning it up.
				$this->setUser($user_id);
				# Reset the variable from the data member.
				$user_id=$this->getUser();
				# Create where statement.
				$where='= '.$db->quote($user_id).' LIMIT 1';
			}
			# An array of users was passed into the method.
			elseif(is_array($user_id))
			{
				# Create where statement.
				$where='IN ('.implode(', ', $user_id).')';
			}
			# Set the `user` value to NULL for this contributor in the `contributors` table.
			$update_cont=$db->query('UPDATE `'.DBPREFIX.'contributors` SET `user` = NULL WHERE `user` '.$where);
			# Check that there was a result.
			if($update_cont)
			{
				return TRUE;
			}

			return FALSE;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error removing User ID: '.$user_id.' from the contributor\'s data: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * updateContributor
	 *
	 * Updates the contributor's record in the DataBase.
	 *
	 * @param        array $where_field (Key= the field, Value= the field value.)
	 * @param        array $field_value (Key= the field, Value= the field value.)
	 * @access    public
	 */
	public function updateContributor($where_field, $field_value)
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
				# Check if the field is "ip".
				if($field=='ip')
				{
					$value='INET_ATON('.$value.')';
				}
			}
			else
			{
				# Check if the field is "privacy".
				if($field=='privacy' && ($value===0 || $value==='0'))
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
			$update_user=$db->query('UPDATE `'.DBPREFIX.'contributors` SET '.$set.' WHERE '.$where.' LIMIT 1');
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error updating Contributor\'s info: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * setAllContributors
	 *
	 * Sets the data member $all_contributors.
	 *
	 * @param    $contributors                May be an array or a string.
	 *                                        The method makes it into an array regardless.
	 * @access    protected
	 */
	protected function setAllContributors($contributors)
	{
		# Check if the passed value is empty.
		if(!empty($contributors))
		{
			# Explicitly make it an array.
			$contributors=(array)$contributors;
			# Set the data member.
			$this->all_contributors=$contributors;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_contributors=NULL;
		}
	}

	/**
	 * setContID
	 *
	 * Sets the data member $cont_id.
	 *
	 * @param        $cont_id (The Contributor's ID number.)
	 * @access    protected
	 */
	protected function setContID($cont_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $cont_id is empty.
		if(!empty($cont_id))
		{
			# Clean it up.
			$cont_id=trim($cont_id);
			# Check if the passed $id is an integer.
			if($validator->isInt($cont_id)===TRUE)
			{
				# Explicitly make it an integer.
				$cont_id=(int)$cont_id;
			}
			else
			{
				throw new Exception('The passed contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$cont_id=NULL;
		}
		# Set the data member
		$this->cont_id=$cont_id;
	}

	/**
	 * setContFirstName
	 *
	 * Sets the data member $cont_fname.
	 *
	 * @param        $cont_fname (The contributor's first name.)
	 * @access    protected
	 */
	protected function setContFirstName($cont_fname)
	{
		# Check if the passed value is empty.
		if(!empty($cont_fname))
		{
			# Set the data member.
			$this->cont_fname=trim($cont_fname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_fname=NULL;
		}
	}

	/**
	 * setContLastName
	 *
	 * Sets the data member $cont_lname.
	 *
	 * @param        $cont_lname (The contributor's last name.)
	 * @access    protected
	 */
	protected function setContLastName($cont_lname)
	{
		# Check if the passed value is empty.
		if(!empty($cont_lname))
		{
			# Set the data member.
			$this->cont_lname=trim($cont_lname);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_lname=NULL;
		}
	}

	/*** End accessor methods ***/

	/*** public methods ***/

	/**
	 * setContEmail
	 *
	 * Sets the data member $cont_email.
	 *
	 * @param        $cont_email (The contributor's Email address.)
	 * @access    protected
	 */
	protected function setContEmail($cont_email)
	{
		# Check if the passed value is empty.
		if(!empty($cont_email))
		{
			# Set the data member.
			$this->cont_email=trim($cont_email);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_email=NULL;
		}
	}

	/**
	 * setContIP
	 *
	 * Sets the data member $cont_ip.
	 *
	 * @param        $cont_ip (The contributor's IP address.)
	 * @access    protected
	 */
	protected function setContIP($cont_ip)
	{
		# Check if the passed value is empty.
		if(!empty($cont_ip))
		{
			# Set the data member.
			$this->cont_ip=trim($cont_ip);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_ip=NULL;
		}
	}

	/**
	 * setContRegion
	 *
	 * Sets the data member $cont_region.
	 *
	 * @param        $cont_region (The contributor's region.)
	 * @access    protected
	 */
	protected function setContRegion($cont_region)
	{
		# Check if the passed value is empty.
		if(!empty($cont_region))
		{
			# Strip slashes and decode any html entities.
			$cont_region=html_entity_decode(stripslashes($cont_region), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->cont_region=trim($cont_region);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_region=NULL;
		}
	}

	/**
	 * setContCountry
	 *
	 * Sets the data member $country.
	 *
	 * @param        $cont_country (The contributor's country.)
	 * @access    protected
	 */
	protected function setContCountry($cont_country)
	{
		# Check if the passed value is empty.
		if(!empty($cont_country))
		{
			# Strip slashes and decode any html entities.
			$cont_country=html_entity_decode(stripslashes($cont_country), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->cont_country=trim($cont_country);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_country=NULL;
		}
	}

	/**
	 * setContOrganization
	 *
	 * Sets the data member $cont_organization.
	 *
	 * @param        $cont_organization (The contributor's organization.)
	 * @access    protected
	 */
	protected function setContOrganization($cont_organization)
	{
		# Check if the passed value is empty.
		if(!empty($cont_organization))
		{
			# Strip slashes and decode any html entities.
			$cont_organization=html_entity_decode(stripslashes($cont_organization), ENT_COMPAT, 'UTF-8');
			# Set the data member.
			$this->cont_organization=trim($cont_organization);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_organization=NULL;
		}
	}

	/**
	 * setContPrivacy
	 *
	 * Sets the data member $cont_privacy.
	 *
	 * @param        $cont_privacy (The contributor's privacy preference. Must be NULL or an integer.)
	 * @access    protected
	 */
	protected function setContPrivacy($cont_privacy)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value $cont_privacy is NULL.
		if($cont_privacy!==NULL)
		{
			# Clean it up.
			$cont_privacy=trim($cont_privacy);
			# Check if the passed $id is an integer.
			if($validator->isInt($cont_privacy)===TRUE)
			{
				# Explicitly make it an integer.
				$cont_privacy=(int)$cont_privacy;
			}
			else
			{
				throw new Exception('The passed value for the contributor\'s privacy was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		# Set the data member.
		$this->cont_privacy=$cont_privacy;
	}

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param    string $user The contributor's User ID
	 * @access    protected
	 */
	protected function setUser($user)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $user is empty.
		if(!empty($user))
		{
			# Clean it up.
			$user=trim($user);
			# Check if the passed $id is an integer.
			if($validator->isInt($user)===TRUE)
			{
				# Explicitly make it an integer.
				$user=(int)$user;
			}
			else
			{
				throw new Exception('The passed User ID was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$user=NULL;
		}
		# Set the data member
		$this->user=$user;
	}
	/*** End public methods ***/
}