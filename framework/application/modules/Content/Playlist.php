<?php /* framework/application/modules/Content/Playlist.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the parent Category class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');


/**
 * Playlist
 *
 * The Playlist Class is used access and maintain the `playlists` table in the database.
 *
 */
class Playlist extends Category
{
	/*** data members ***/

	private $all_playlists=NULL;
	private $api=NULL;
	private $id=NULL;
	private $name=NULL;
	private static $playlist_obj;
	private $where_sql=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllPlaylists
	 *
	 * Sets the data member $all_playlists.
	 *
	 * @param	$playlists				May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllPlaylists($playlists)
	{
		# Check if the passed value is empty.
		if(!empty($playlists))
		{
			# Explicitly make it an array.
			$playlists=(array)$playlists;
			# Set the data member.
			$this->all_playlists=$playlists;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_playlists=NULL;
		}
	} #==== End -- setAllPlaylists

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param	$api
	 * @access	protected
	 */
	protected function setAPI($api)
	{
		# Check if the passed value is empty.
		if(!empty($api))
		{
			# Strip slashes and decode any html entities.
			$api=html_entity_decode(stripslashes($api), ENT_COMPAT, 'UTF-8');
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

		# Check if the passed $id is empty.
		if(!empty($id))
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
				throw new Exception('The passed playlist id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setName
	 *
	 * Sets the data member $name.
	 *
	 * @param	$name
	 * @access	protected
	 */
	protected function setName($name)
	{
		# Check if the passed value is empty.
		if(!empty($name))
		{
			# Strip slashes and decode any html entities.
			$name=html_entity_decode(stripslashes($name), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$name=trim($name);
			# Set the data member.
			$this->name=$name;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->name=NULL;
		}
	} #==== End -- setName

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
		if(!empty($where_sql))
		{
			# Set the data member.
			$this->where_sql=$where_sql;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->where_sql=NULL;
		}
	} #==== End -- setWhereSQL

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllPlaylists
	 *
	 * Returns the data member $all_playlists.
	 *
	 * @access	public
	 */
	public function getAllPlaylists()
	{
		return $this->all_playlists;
	} #==== End -- getAllPlaylists

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
	 * Creates the SQL from an array of Playlist IDs.
	 *
	 * @param	$playlists			An array or string of Playlist IDs.
	 * @param	$field_name
	 * @access	public
	 */
	public function createWhereSQL($playlists=NULL, $field_name='id', $use_where_in=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value was empty.
		if(!empty($playlists))
		{
			# If not an array, turn it into an array.
			if(!is_array($playlists))
			{
				# Check if $categories equals "all".
				if(strtolower($playlists)=='all')
				{
					# Chenage $playlists into an empty array.
					$playlists=array();
					# Get all the playlists.
					$this->getPlaylists();
					# Set the results to a variable.
					$results=$this->getAllPlaylists();
					# Loop through the playlists.
					foreach($results as $row)
					{
						# Set the playlist id to the $playlists array.
						$playlists[]=$row->id;
					}
				}
				else
				{
					# Trim any dashes(-) off the ends of the string.
					$playlists=trim($playlists, '-');
					# Create an array of the playlists.
					$playlists=explode('-', $playlists);
				}
			}
			if($use_where_in===TRUE)
			{
				# Turn array into comma-separated-values.
				$csv_playlists=implode(',', $playlists);
				$playlists='`'.$field_name.'` IN ('.$csv_playlists.')';
			}
			else
			{
				# Create an empty array to hold the "OR" sql strings.
				$playlist_or=array();
				# Create an empty array to hold the "AND" sql strings.
				$playlist_and=array();
				foreach($playlists as $playlist)
				{
					# Clean it up.
					$playlist=trim($playlist);
					# Get the first character of the string.
					$top=substr($playlist, 0, 1);
					# Check if the first character was an "!".
					if($top=='!')
					{
						# Remove the "!" from the front of the string.
						$playlist=ltrim($playlist, '!');
					}
					# Check if $playlist is not an integer.
					if($validator->isInt($playlist)!==TRUE)
					{
						# Get the category data that cooresponds to the passed $playlist name.
						$this->getThisPlaylist($playlist, FALSE);
						$playlist=$this->getID();
					}
					# Check if the first character was an "!".
					if($top!='!')
					{
						# Set the newly created sql string to the $playlist_or array.
						$playlist_or[]='`'.$field_name.'` REGEXP '.$db->quote('-'.$playlist.'-');
					}
					else
					{
						# Set the newly created sql string to the $playlist_and array.
						$playlist_and[]='`'.$field_name.'` NOT REGEXP '.$db->quote('-'.$playlist.'-');
					}
				}
				# Implode the $playlist_or array into one complete sql string.
				$ors=implode(' OR ', $playlist_or);
				# Implode the $playlist_and array into one complete sql string.
				$ands=implode(' AND ', $playlist_and);
				# Concatenate the $ands and $ors together.
				$playlists=(((!empty($ors)) ? '('.$ors.')' : '').((!empty($ors) && !empty($ands)) ? ' AND ' : '').((!empty($ands)) ? '('.$ands.')' : ''));
			}
		}
		else
		{
			# Explicitly set playlists to NULL.
			$playlists=NULL;
		}
		# Check if the $playlists array is empty.
		if(!empty($playlists))
		{
			# Set the sql string to the data member.
			$this->setWhereSQL($playlists);
		}
	} #==== End -- createWhereSQL

/*
			# Check if $playlists equals "all".
			if(!is_array($playlists) && strtolower($playlists)=='all')
			{
				# Chenage $playlists into an empty array.
				$playlists=array();
				# Get all the playlists.
				$this->getPlaylists();
				# Set the results to a variable.
				$results=$this->getAllPlaylists();
				# Loop through the categories.
				foreach($results as $row)
				{
					# Set the category id to the $categories array.
					$playlists[]=$row->id;
				}
			}
			elseif(!is_array($playlists))
			{
				# Trim any dashes(-) off the ends of the string.
				$playlists=trim($playlists, '-');
				# Create an array of the playlists.
				$playlists=explode('-', $playlists);
			}

				# If not an array, turn it into an array.
				if(!is_array($playlists))
				{
					# Turn array into comma-separated-values.
					$csv_playlists=implode(',', $playlists);
					$playlists='`'.$field_name.'` IN ('.$csv_playlists.')';
				}
*/

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$playlist_obj)
		{
			self::$playlist_obj=new Playlist();
		}
		return self::$playlist_obj;
	} #==== End -- getInstance

	/**
	 * getPlaylists
	 *
	 * Retrieves records from the `playlists` table.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getPlaylists($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `playlists` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'playlists`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllPlaylists($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: ' . $ez->message . ', code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPlaylists

	/**
	 * getThisPlaylist
	 *
	 * Retrieves playlist info from the `playlists` table in the database for the passed id or playlist name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the playlist to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean 				TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisPlaylist($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set a variable with the field to search for the passed $value.
				$field='id';
				# Set the playlist id to the data member "cleaning" it.
				$this->setID($value);
				# Get the playlist id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set a variable with the field to search for the passed $value.
				$field='name';
				# Set the playlist name to the data member.
				$this->setName($value);
				# Get the playlist name and reset it to the variable.
				$value=$this->getName();
			}
			# Get the playlist info from the database.
			$playlist=$db->get_row('SELECT `id`, `name`, `api` FROM `'.DBPREFIX.'playlists` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($playlist!==NULL)
			{
				# Set the playlist id to the data member "cleaning" it.
				$this->setID($playlist->id);
				# Set the playlist API to the data member.
				$this->setAPI($playlist->api);
				# Set the playlist name to the data member.
				$this->setName($playlist->name);
				return TRUE;
			}
			# Return FALSE because the playlist wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: ' . $ez->message . ', code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisPlaylist

	/*** End public methods ***/

} # End Playlist class.