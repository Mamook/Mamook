<?php /* framework/application/modules/Content/Position.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Position
 *
 * The Position Class is used access and maintain the `position` table in the database.
 *
 */
class Position
{
	/*** data members ***/

	private $all_positions=NULL;
	private $id=NULL;
	private $position=NULL;
	private $description=NULL;
	private $where_sql=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllPositions
	 *
	 * Sets the data member $all_positions.
	 *
	 * @param	$positions				May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllPositions($positions)
	{
		# Check if the passed value is empty.
		if(!empty($positions))
		{
			# Explicitly make it an array.
			$positions=(array)$positions;
			# Set the data member.
			$this->all_positions=$positions;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_positions=NULL;
		}
	} #==== End -- setAllPositions

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param	$id
	 * @access	protected
	 */
	protected function setID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is NULL.
		if($id!==NULL)
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->id=(int)$id;
			}
			else
			{
				//throw new Exception('The passed position id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setPosition
	 *
	 * Sets the data member $position.
	 *
	 * @param	$position
	 * @access	protected
	 */
	protected function setPosition($position)
	{
		# Check if the passed value is empty.
		if(!empty($position))
		{
			# Strip slashes and decode any html entities.
			$position=html_entity_decode(stripslashes($position), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$position=trim($position);
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
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param	$description
	 * @access	protected
	 */
	protected function setDescription($description)
	{
		# Check if the passed value is empty.
		if(!empty($description))
		{
			# Strip slashes and decode any html entities.
			$description=html_entity_decode(stripslashes($description), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$description=trim($description);
			# Set the data member.
			$this->description=$description;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->description=NULL;
		}
	} #==== End -- setDescription

	/**
	 * setWhereSQL
	 *
	 * Sets the data member $where_sql.
	 *
	 * @param	$where_sql
	 * @access	protected
	 */
	protected function setWhereSQL($where_sql)
	{
		# Check if the passed value is empty.
		if(empty($where_sql))
		{
			# Explicitly set the value to NULL.
			$where_sql=NULL;
		}
		# Set the data member.
		$this->where_sql=$where_sql;
	} #==== End -- setWhereSQL

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllPositions
	 *
	 * Returns the data member $all_positions.
	 *
	 * @access	public
	 */
	public function getAllPositions()
	{
		return $this->all_positions;
	} #==== End -- getAllPositions

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
	 * getDescription
	 *
	 * Returns the data member $description.
	 *
	 * @access	public
	 */
	public function getDescription()
	{
		return $this->description;
	} #==== End -- getDescription

	/**
	 * getWhereSQL
	 *
	 * Returns the data member $where_sql.
	 *
	 * @access	public
	 */
	public function getWhereSQL()
	{
		return $this->where_sql;
	} #==== End -- getWhereSQL

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * createWhereSQL
	 *
	 * Explodes a dash sepparated list of positions and formats them for the WHERE portion of an sql query.
	 *
	 * @param	$positions				The names and/or id's of the position(es) to be retrieved.
	 *										May be multiple projects - separate with a dash (ie. '50-70-Archive-110').
	 *										Use a "!" to designate Positions NOT to be returned, ie. '50-!70-Archive-110'.
	 * @access	public
	 */
	public function createWhereSQL($positions=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		if($positions!==NULL)
		{
			# Check if $position is not an integer.
			if($validator->isInt($positions)!==TRUE)
			{
				# Get the position data that cooresponds to the passed $position name.
				$this->getThisPosition($positions, FALSE);
				$positions=$this->getID();
			}
			$positions='`position` REGEXP \'"position":"'.$db->quote($positions).'"\'';
		}
		# Check if the $position_a array is empty.
		if(!empty($positions))
		{
			# Set the sql string to the data member.
			$this->setWhereSQL($positions);
		}
	} #==== End -- createPositionWhereSQL

	/**
	 * getPositions
	 *
	 * Retrieves records from the `positions` table.
	 *
	 * @param	$limit					The LIMIT of the records.
	 * @param	$fields					The name of the field(s) to be retrieved.
	 * @param	$order					The name of the field to order the records by.
	 * @param	$direction				The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	Boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getPositions($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `positions` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'positions`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllPositions($records);
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
	} #==== End -- getPositions

	/**
	 * getThisPosition
	 *
	 * Retrieves position info from the `positions` table in the Database for the passed id or position name and sets it to the data member.
	 *
	 * @param	String $value			The name or id of the position to retrieve.
	 * @param	Boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	Boolean					TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisPosition($value, $id=TRUE)
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
				# Set the position id to the data member "cleaning" it.
				$this->setID($value);
				# Get the position id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='position';
				# Set the position name to the data member "cleaning" it.
				$this->setPosition($value);
				# Get the position name and reset it to the variable.
				$value=$this->getPosition();
			}
			# Get the position info from the Database.
			$position=$db->get_row('SELECT `id`, `position`, `description` FROM `'.DBPREFIX.'positions` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($position!==NULL)
			{
				# Set the position id to the data member.
				$this->setID($position->id);
				# Set the position name to the data member.
				$this->setPosition($position->position);
				# Set the position description to the data member.
				$this->setDescription($position->description);
				return TRUE;
			}
			# Return FALSE because the position wasn't in the table.
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
	} #==== End -- getThisPosition

	/**
	 * setDataMembers
	 *
	 * Sets all the data returned in a row from the `positions` table to the appropriate Data members.
	 *
	 * @param	$row					The returned row of data from a record to set to the data members.
	 * @access	public
	 */
	public function setDataMembers($row)
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();

		try
		{
			# Set Position id to the data member.
			$this->setID($row->id);

			# Set Position name to the data member.
			$this->setPosition($row->position);

			# The the site name.
			$site_name=$main_content->getSiteName();
			# Set the Position description to a variable.
			$description=$row->description;
			# Replace any tokens with their correlating value.
			$description=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $description);
			# Set Position link to the data member.
			$this->setDescription($description);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setDataMembers

	/*** End public methods ***/

} # End Position class.