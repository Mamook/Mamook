<?php /* framework/application/modules/Content/Branch.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Branch
 *
 * The Branch Class is used access and maintain the `branch` table in the database.
 *
 */
class Branch
{
	/*** data members ***/

	private $all_branches=NULL;
	private $id=NULL;
	private $branch=NULL;
	private $domain=NULL;
	private $where_sql=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllBranches
	 *
	 * Sets the data member $all_branches.
	 *
	 * @param	$branches				May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllBranches($branches)
	{
		# Check if the passed value is empty.
		if(!empty($branches))
		{
			# Explicitly make it an array.
			$branches=(array)$branches;
			# Set the data member.
			$this->all_branches=$branches;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_branches=NULL;
		}
	} #==== End -- setAllBranches

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
				throw new Exception('The passed branch id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setBranch
	 *
	 * Sets the data member $branch.
	 *
	 * @param	$branch
	 * @access	protected
	 */
	protected function setBranch($branch)
	{
		# Check if the passed value is empty.
		if(!empty($branch))
		{
			# Clean it up.
			$branch=trim($branch);
			# Set the data member.
			$this->branch=$branch;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->branch=NULL;
		}
	} #==== End -- setBranch

	/**
	 * setDomain
	 *
	 * Sets the data member $domain.
	 *
	 * @param	$domain
	 * @access	protected
	 */
	protected function setDomain($domain)
	{
		# Check if the passed value is empty.
		if(!empty($domain))
		{
			# Clean it up.
			$domain=trim($domain);
			# Replace any domain tokens with the current domain name.
			$domain=str_ireplace('%{domain_name}', DOMAIN_NAME, $domain);
			# Set the data member.
			$this->domain=$domain;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->domain=NULL;
		}
	} #==== End -- setDomain

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
	 * getAllBranches
	 *
	 * Returns the data member $all_branches.
	 *
	 * @access	public
	 */
	public function getAllBranches()
	{
		return $this->all_branches;
	} #==== End -- getAllBranches

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
	 * getBranch
	 *
	 * Returns the data member $branch.
	 *
	 * @access	public
	 */
	public function getBranch()
	{
		return $this->branch;
	} #==== End -- getBranch

	/**
	 * getDomain
	 *
	 * Returns the data member $domain.
	 *
	 * @access	public
	 */
	public function getDomain()
	{
		return $this->domain;
	} #==== End -- getDomain

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
	 * Explodes a dash sepparated list of branches and formats them for the WHERE portion of an sql query.
	 *
	 * @param	$branches				The names and/or id's of the branch(es) to be retrieved.
	 *										May be multiple branches - separate with a dash, ie. '50-70-Archive-110'.
	 *										Use a "!" to designate Branches NOT to be returned, ie. '50-!70-Archive-110'
	 * @access	public
	 */
	public function createWhereSQL($branches=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($branches))
		{
			# Check if the passed value is an array already.
			if(!is_array($branches))
			{
				# Create an array of the branches.
				$branches=explode('-', $branches);
			}
			# Create an empty array to hold the "OR" sql strings.
			$branch_or=array();
			# Create an empty array to hold the "AND" sql strings.
			$branch_and=array();
			foreach($branches as $branch)
			{
				# Clean it up.
				$branch=trim($branch);
				# Get the first character of the string.
				$top=substr($branch, 0, 1);
				# Check if the first character was an "!".
				if($top=='!')
				{
					# Remove the "!" from the front of the string.
					$branch=ltrim($branch, '!');
				}
				# Check if $branch is not an integer.
				if($validator->isInt($branch)!==TRUE)
				{
					# Get the branch data that cooresponds to the passed $branch name.
					$this->getThisBranch($branch, FALSE);
					$branch=$this->getID();
				}
				# Check if the first character was an "!".
				if($top!='!')
				{
					# Set the newly created sql string to the $branch_or array.
					$branch_or[]='`branch` REGEXP '.$db->quote('-'.$branch.'-');
				}
				else
				{
					# Set the newly created sql string to the $branch_and array.
					$branch_and[]='`branch` NOT REGEXP '.$db->quote('-'.$branch.'-');
				}
			}
			# Implode the $branch_or array into one complete sql string.
			$ors=implode(' OR ', $branch_or);
			# Implode the $branch_and array into one complete sql string.
			$ands=implode(' AND ', $branch_and);
			# Concatenate the $ands and $ors together.
			$branches=(((!empty($ors)) ? '('.$ors.')' : '').((!empty($ors) && !empty($ands)) ? ' AND ' : '').((!empty($ands)) ? '('.$ands.')' : ''));
		}
		# Check if the $branch_a array is empty.
		if(!empty($branches))
		{
			# Set the sql string to the data member.
			$this->setWhereSQL($branches);
		}
	} #==== End -- createWhereSQL

	/**
	 * findBranchManagerEmails
	 *
	 * NEEDS FIXING
	 * NOTE: Why does it need fixing? ~Draven
	 *
	 * @param	$branches				The names and/or id's of the branch(es) to be retrieved.
	 * 										May be multiple branches - separate with a dash. ie: '50-70-Archive-110'.
	 * 										Use a "!" to designate Branches NOT to be returned, ie. '50-!70-Archive-110', or an array.
	 * @access	public
	 */
	public function findBranchManagerEmails($branches=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring the User object into scope.
		global $user;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($branches))
		{
			# Check if the passed value is an array already.
			if(!is_array($branches))
			{
				# Create an array of the branches.
				$branches=explode('-', $branches);
			}
			# Create an empty array to hold the "OR" sql strings.
			$branch_or=array();
			# Create an empty array to hold the "AND" sql strings.
			$branch_and=array();
			foreach($branches as $branch)
			{
				# Clean it up.
				$branch=trim($branch);
				# Get the first character of the string.
				$top=substr($branch, 0, 1);
				# Check if the first character was an "!".
				if($top=='!')
				{
					# Remove the "!" from the front of the string.
					$branch=ltrim($branch, '!');
				}
				# Check if $branch is not an integer.
				if($validator->isInt($branch)!==TRUE)
				{
					# Get the branch data that cooresponds to the passed $branch name.
					$this->getThisBranch($branch, FALSE);
					$branch=$this->getID();
				}
				# Replace the last digit of the branch with a 1(indicates manager of the branch.)
				$branch=substr_replace($branch, '1', -1);
				# Check if the first character was an "!".
				if($top!='!')
				{
					# Set the newly created sql string to the $branch_or array.
					$branch_or[]='`level` REGEXP '.$db->quote('-'.$branch.'-');
				}
				else
				{
					# Set the newly created sql string to the $branch_and array.
					$branch_and[]='`level` NOT REGEXP '.$db->quote('-'.$branch.'-');
				}
			}
			# Implode the $branch_or array into one complete sql string.
			$ors=implode(' OR ', $branch_or);
			# Implode the $branch_and array into one complete sql string.
			$ands=implode(' AND ', $branch_and);
			# Concatenate the $ands and $ors together.
			$branches=(((!empty($ors)) ? '('.$ors.')' : '').((!empty($ors) && !empty($ands)) ? ' AND ' : '').((!empty($ands)) ? '('.$ands.')' : ''));
			# Create the WHERE SQL statment.
			$where=' WHERE '.$branches;
			# Get the email addresses from the `users` table.
			$user->getUsers($limit=NULL, $fields='email', $order='ID', $direction='DESC', $where);
			return $user->getAllUsers();
		}
	} #==== End -- findBranchManagerEmails

	/**
	 * getBranches
	 *
	 * Retrieves records from the `branches` table.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getBranches($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `branches` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'branches`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllBranches($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getBranches

	/**
	 * getThisBranch
	 *
	 * Retrieves branch info from the `branches` table in the Database for the passed id or branch name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the branch to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean 				TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisBranch($value, $id=TRUE)
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
				# Set the branch id to the data member "cleaning" it.
				$this->setID($value);
				# Get the branch id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='branch';
				# Set the branch name to the data member "cleaning" it.
				$this->setBranch($value);
				# Get the branch name and reset it to the variable.
				$value=$this->getBranch();
			}
			# Get the branch info from the Database.
			$branch=$db->get_row('SELECT `id`, `branch`, `domain` FROM `'.DBPREFIX.'branches` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($branch!==NULL)
			{
				# Set the branch id to the data member.
				$this->setID($branch->id);
				# Set the branch name to the data member.
				$this->setBranch($branch->branch);
				# Set the branch domain name to the data member.
				$this->setDomain($branch->domain);
				return TRUE;
			}
			# Return FALSE because the branch wasn't in the table.
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
	} #==== End -- getThisBranch

	/*** End public methods ***/

} # End Branch class.