<?php /* Requires PHP5+ */

require_once Utility::locateFile(MODULES.'User'.DS.'User.php');

/**
 * Subscription
 *
 * The Subscription class is used to access and manipulate data in the `subscriptions` table.
 *
 */
class Subscription
{
	/*** data members ***/

	protected $all_subscriptions=NULL;
	protected $subscription_id=NULL;
	protected $name=NULL;
	protected $name_where_sql=NULL;
	protected $position_where_sql=NULL;
	protected $date=NULL;
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
	 * setAllSubscriptions
	 *
	 * Sets the data member $all_subscriptions.
	 *
	 * @param		$subscriptions (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllSubscriptions($subscriptions)
	{
		# Check if the passed value is empty.
		if(!empty($subscriptions))
		{
			# Explicitly make it an array.
			$subscriptions=(array)$subscriptions;
			# Set the data member.
			$this->all_subscriptions=$subscriptions;
		}
	} #==== End -- setAllSubscriptions

	/**
	 * setSubscriptionID
	 *
	 * Sets the data member $subscription_id.
	 *
	 * @param		$id 			(The subscription's id number.)
	 * @access	protected
	 */
	protected function setSubscriptionID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $subscription_id is empty.
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
				$this->subscription_id=$id;
			}
			else
			{
				throw new Exception('The passed subscription id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
	} #==== End -- setSubscriptionID

	/**
	 * setName
	 *
	 * Sets the data member $name.
	 *
	 * @param		$name 		(The subscription's name.)
	 * @access	protected
	 */
	protected function setName($name)
	{
		# Check if the passed value is empty.
		if(!empty($name))
		{
			# Set the data member.
			$this->name=trim($name);
		}
	} #==== End -- setName

	/**
	 * setNameWhereSQL
	 *
	 * Sets the data member $name_where_sql.
	 *
	 * @param		string 		$names
	 * @access	protected
	 */
	protected function setNameWhereSQL($names)
	{
		# Check if the passed value is empty.
		if(!empty($names))
		{
			# Set the data member.
			$this->name_where_sql=$names;
		}
	} #==== End -- setNameWhereSQL

	/**
	 * setPositionWhereSQL
	 *
	 * Sets the data member $position_where_sql.
	 *
	 * @param	string $position
	 * @access	protected
	 */
	protected function setPositionWhereSQL($position)
	{
		# Check if the passed value is empty.
		if(!empty($position))
		{
			# Set the data member.
			$this->position_where_sql=$position;
		}
	} #==== End -- setPositionWhereSQL

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param		$date 		(The subscription's expiration date.)
	 * @access	protected
	 */
	protected function setDate($date)
	{
		if(!empty($date) && ($date!='000-00-00'))
		{
			# Clean it up.
			$date=trim($date);
			# Set the data member.
			$this->date=$date;
		}
	} #==== End -- setDate

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param		string 		$user	(The subscriber's User ID)
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
	} #==== End -- setSubscriptionUser

	/*** End mutator methods ***/



	/*** accessor methods ***/

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
	 * getSubscriptionID
	 *
	 * Returns the data member $subscription_id.
	 *
	 * @access	public
	 */
	public function getSubscriptionID()
	{
		return $this->subscription_id;
	} #==== End -- getSubscriptionID

	/**
	 * getName
	 *
	 * Returns the data member $name.
	 *
	 * @access	public
	 */
	public function getName()
	{
		return $this->name;
	} #==== End -- getName

	/**
	 * getNameWhereSQL
	 *
	 * Returns the data member $name_where_sql.
	 *
	 * @access	protected
	 */
	protected function getNameWhereSQL()
	{
		return $this->name_where_sql;
	} #==== End -- getNameWhereSQL

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
	 * getDate
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getDate()
	{
		return $date->date;
	} #==== End -- getDate

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
	 * addSubscription
	 *
	 * Adds or updates a subscription in the `subscription` table in the Database.
	 *
	 * @access	public
	 * @return	string
	 */
	public function addSubscription()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the subscription id and set it to a variable.
			$id=$this->getSubscriptionID();
			# Get the subscription's affiliation and set it to a variable.
			$affiliation=$this->getAffiliation();
			# Get the subscription's archive status and set it to a variable.
			$archive=$this->getArchive();
			# Get the subscription's credentials and set it to a variable.
			$credentials=$this->getCredentials();
			# Get the subscription's position and set it to a variable.
			$position=$this->getPosition();
			# Get the subscription's image and set it to a variable.
			$image=$this->getSubscriptionImage();
			# Get the image title for the subscription's and set it to a variable.
			$image_title=$this->getSubscriptionImageTitle();
			# Get the subscription's first name and set it to a variable.
			$fname=$this->getSubscriptionFirstName();
			# Get the subscription's middle name and set it to a variable.
			$mname=$this->getSubscriptionMiddleName();
			# Get the subscription's last name and set it to a variable.
			$lname=$this->getSubscriptionLastName();
			# Get the subscription's region and set it to a variable.
			$region=$this->getSubscriptionRegion();
			# Get the subscription's region and set it to a variable.
			$title=$this->getSubscriptionTitle();
			# Get the subscription's biographical text and set it to a variable.
			$text=$this->getText();
			# Get the subscription's User ID and set it to a variable.
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
			# Add the subscription to the `subscription` table. This will update if the subscription is already in the table.
			$add=$db->query('INSERT INTO `'.DBPREFIX.'subscription` SET '.
				((!empty($id)) ? '`id` = '.$db->quote($id).', ' : '').
				$set.
				'ON DUPLICATE KEY UPDATE'.
				$set
			);
			# Check if the subscription id was empty.
			if(!empty($id))
			{
				# Set the subscription id to the $value variable.
				$value=$id;
				# Set the $field varaible.
				$field='id';
			}
			# Check if the User ID was empty.
			elseif(!empty($user))
			{
				# Set the subscription id to the $value variable.
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
			# Try to get the subscription from the `subscription` table again to repopulate the data members.
			$this->getThisSubscription($value, $field, $find);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error adding the subscription\'s data: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addSubscription

	/**
	* countAllSubscriptions
	*
	* Returns the of subscriptions in the `subscription` table.
	*
	* @param	$names					The name(s) of the subscription(s) to be retrieved. May be multiple names - separate with an asterisk(*), ie. 'Daily Download*Podcast*Trailmaker'
	* @param	$limit					The LIMIT of the records.
	* @param	$and_sql				Any extra AND queries.
	* @access	public
	*/
	public function countAllSubscriptions($names=NULL, $limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		try
		{
			# Creat the sql "WHERE" statement for the names.
			$this->createNameWhereSQL($names);
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'subscriptions` WHERE ('.$this->getNameWhereSQL().') '.(($and_sql===NULL) ? '' : $and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllSubscription

	/**
	 * getSubscriptions
	 *
	 * Retrieves records from the `subscriptions` table.
	 *
	 * @param		$limit 			(The LIMIT of the records.)
	 * @param		$fields 		(The name of the field(s) to be retrieved.)
	 * @param		$order 			(The name of the field to order the records by.)
	 * @param		$direction 	(The direction to order the records.)
	 * @param		$and_sql 		(Extra AND statements in the query.)
	 * @return	Boolean 		(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getSubscriptions($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `subscriptions` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'subscriptions`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllSubscriptions($records);
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
	} #==== End -- getSubscriptions

	/**
	 * updateSubscription
	 *
	 * Updates the User's subscription record in the `subscriptions` table.
	 *
	 * @param		array		$where_field (Key= the field, Value= the field value.)
	 * @param		array		$field_value (Key= the field, Value= the field value.)
	 * @access	public
	 */
	public function updateSubscription($where_field, $field_value)
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
				# Explicitly update the value to NULL.
				$value='NULL';
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
			$update_user=$db->query('UPDATE `'.DBPREFIX.'subscriptions` SET '.$set.' WHERE '.$where.' LIMIT 1');
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error updating the User\'s subscription info: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	} #==== End -- updateSubscription

	/**
	 * removeUser
	 *
	 * Remove's a User from the `subscription` table.
	 *
	 * @param		string 	$user_id 	(The subscription's User ID.)
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
			# Delete all subscription records for this User in the `subscription` table.
			$delete_subscription=$db->query('DELETE FROM `'.DBPREFIX.'subscriptions` WHERE `user` = '.$db->quote($user_id));
			# Check that there was a result.
			if($delete_subscription>0) { return TRUE; }
			else { return FALSE; }
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error removing User ID: '.$user_id.' from the subscription\'s data: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
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
	 * createNameWhereSQL
	 *
	 * Explodes an asterisk(*) sepparated list of names and formats them for the WHERE portion of an sql query.
	 *
	 * @param	$names					The names and/or id's of the subscription(es) to be retrieved. May be multiple names - separate with an asterisk(*), ie. 'Daily Download*Podcast*Trailmaker'
	 * @access	public
	 */
	public function createNameWhereSQL($names=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if the passed $names is empty.
		if(!empty($names))
		{
			# Explode $names into an array.
			$names=explode('*', $names);
			# Create an empty array to hold the positions.
			$positions_a=array();
			# Loop though the position array.
			foreach($positions as $position)
			{
				# Set the sql to the array.
				$positions_a[]='`name` = '.$db->quote($db->escape($position));
			}
			# Implode the $positions_a array into a complete mysql WHERE string.
			$position_where_sql=implode(' OR ', $positions_a);

			$this->setPositionWhereSQL($position_where_sql);
		}
		else
		{
			if($names===NULL)
			{
				$this->setPositionWhereSQL('`name` IS NOT NULL');
			}
			else
			{
				throw new Exception('You must provide a name!', E_RECOVERABLE_ERROR);
			}
		}
	} #==== End -- createNameWhereSQL

	/*** End protected methods ***/

} # End Subscription class.