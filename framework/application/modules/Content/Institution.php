<?php /* framework/application/modules/Content/Institution.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Institution
 *
 * The Institution Class is used access and maintain the `institution` table in the database.
 *
 */
class Institution
{
	/*** data members ***/

	private $all_institutions=NULL;
	private $id=NULL;
	private $institution=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllInstitutions
	 *
	 * Sets the data member $all_institutions.
	 *
	 * @param		$institutions (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllInstitutions($institutions)
	{
		# Check if the passed value is empty.
		if(!empty($institutions))
		{
			# Explicitly make it an array.
			$institutions=(array)$institutions;
			# Set the data member.
			$this->all_institutions=$institutions;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_institutions=NULL;
		}
	} #==== End -- setAllInstitutions

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id
	 * @access	public
	 */
	public function setID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is NULL.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			else
			{
				throw new Exception('The passed institution id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->id=$id;
	} #==== End -- setID

	/**
	 * setInstitution
	 *
	 * Sets the data member $institution.
	 *
	 * @param		$institution
	 * @access	public
	 */
	public function setInstitution($institution)
	{
		# Check if the passed value is empty.
		if(!empty($institution))
		{
			# Strip slashes and decode any html entities.
			$institution=html_entity_decode(stripslashes($institution), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$institution=trim($institution);
			# Set the data member.
			$this->institution=$institution;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->institution=NULL;
		}
	} #==== End -- setInstitution

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllInstitutions
	 *
	 * Returns the data member $all_institutions.
	 *
	 * @access	public
	 */
	public function getAllInstitutions()
	{
		return $this->all_institutions;
	} #==== End -- getAllInstitutions

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
	 * getInstitution
	 *
	 * Returns the data member $institution.
	 *
	 * @access	public
	 */
	public function getInstitution()
	{
		return $this->institution;
	} #==== End -- getInstitution

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllInstitutions
	 *
	 * Returns the number of institutions in the database.
	 *
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllInstitutions($limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		try
		{
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'institutions`'.(($and_sql===NULL) ? '' : ' WHERE '.$and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllInstitutions

	/**
	 * deleteInstitution
	 *
	 * Removes an institution from the `institutions` table.
	 *
	 * @param		$id				int	(The id of the institution in the `institutions` table.
	 * @access	public
	 */
	public function deleteInstitution($id, $redirect=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Bring the Login object into scope.
			global $login;
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if a redirect URL was passed.
			if($redirect===NULL)
			{
				# Set the redirect to the default.
				$redirect=PROTOCAL.FULL_DOMAIN.Utility::removeIndex(HERE);
			}
			# Check if the passed redirect URL was FALSE.
			if($redirect===FALSE)
			{
				# Set the value to NULL (no redirect).
				$redirect===NULL;
			}
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Validate the passed id as an integer.
				if($validator->isInt($id)!==TRUE)
				{
					# Set a nice message to the session.
					$_SESSION['message']='That institution was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
				else
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the institution is premium content or not.
					$this_institution=$this->getThisInstitution($id);
					# Check if the institution was found.
					if($this_institution===TRUE)
					{
						try
						{
							# Delete the institution from the `institutions` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'institutions` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Check if the institution was deleted.
							if($deleted!==NULL)
							{
								# Set a nice message to display to the user.
								$_SESSION['message']='The institution "'.$this->getInstitution().'" was successfully deleted.';

								# Redirect the user.
								$doc->redirect($redirect);
								return TRUE;
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
					else
					{
						# Set a nice message to the session.
						$_SESSION['message']='The institution was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteInstitution

	/**
	 * displayInstitutionList
	 *
	 * Returns a list (table) of institutions.
	 *
	 * @param		$select 	(Whether or not the list should be a radio select display.)
	 * @access	public
	 */
	public function displayInstitutionList($select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;
		# Bring the content instance into scope.
		$main_content=Content::getInstance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# The the site name.
			$site_name=$main_content->getSiteName();
			# Count the institutions.
			$content_count=$this->countAllInstitutions();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='institution';
				# Set the default sort direction of institutions for the institution sorting link to a variable.
				$name_dir='DESC';
				# Check if GET data for institution has been passed and it is an integer.
				if(isset($_GET['institution']) && $validator->isInt($_GET['institution'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['institution']='institution='.$_GET['institution'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "institution" GET query; it can't be passed with "select".
					unset($params_a['institution']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_name" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_name']) && ($_GET['by_name']==='ASC' OR $_GET['by_name']==='DESC'))
				{
					# Set the query to the query parameters array.
					$params_a['by_name']='by_name='.$_GET['by_name'];
					# Check if the order is to be descending.
					if($_GET['by_name']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of institutions for the institution sorting link to "ASC".
						$name_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_name" and "by_title" indexes of the array.
				unset($params_a['by_name']);
				# Implode the query parameters array to a string sepparated by ampersands for the institution and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to edit a branch.
				if($login->checkAccess(ALL_BRANCH_USERS)===TRUE && $select!=='select')
				{
					# Set the default value for displaying an edit button and a delete button to TRUE.
					$edit=TRUE;
					$delete=TRUE;
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
				$and_sql='';
				# Get the Institutions.
				$this->getInstitutions($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned Institution records to a variable.
				$all_institutions=$this->getAllInstitutions();

				# Start a table for the institutions and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the institution column to a variable.
				$general_header='<th></th>';
				# Set the table header for the info column to a variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageContent/institutions/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_name='.$name_dir.'" title="Order by institution name">Institution</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', 'http'.($validator->isSSL()===TRUE ? 's' : '').'://'.FULL_URL, 'post', '_top', FALSE, 'institution-list');
					# Create the hidden submit check input.
					$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
					# Open the fieldset tag.
					$fg->addFormPart('<fieldset>');
					# Add a table header for the Select column and concatenate the table header.
					$table_header.='<th>Select</th>'.$general_header;
					# Add the table header to the form.
					$fg->addFormPart($table_header);
				}
				else
				{
					# Concatenate the table header.
					$table_header.=$general_header;
					# Check if edit and delete buttons should be displayed.
					if($delete===TRUE OR $edit===TRUE)
					{
						# Concatenate the options header to the table header.
						$table_header.='<th>Options</th>';
					}
				}
				# Creat an empty variable for the table body.
				$table_body='';
				# Loop through the all_institutions array.
				foreach($all_institutions as $row)
				{
					# Instantiate a new Institution object.
					$institution=new Institution();
					# Set the relevant returned field values File data members.
					$institution->setID($row->id);
					$institution->setInstitution($row->institution);
					# Set the relevant Institution data members to local variables.
					$institution_id=$institution->getID();
					# Replace any tokens with their correlating value.
					$institution_name=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $institution->getInstitution());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the institution markup to the $general_data variable.
					$general_data='<td></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="institution'.$institution_id.'">' : '' ).'<a href="'.APPLICATION_URL.'profile/?institution='.$institution_id.'" title="'.$institution_name.' on '.DOMAIN_NAME.'" target="_blank">'.$institution_name.'</a>'.(($select==='select') ? '</label>' : '' ).'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageContent/institutions/?institution='.$institution_id.'" class="button-edit" title="Edit '.$institution_name.'">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageContent/institutions/?institution='.$institution_id.'&amp;delete" class="button-delete" title="Delete '.$institution_name.'">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this institution.
						$fg->addElement('radio', array('name'=>'institution_info', 'value'=>$institution_id.':'.$institution_name, 'id'=>'institution'.$institution_id));
						# Reset the $table_body variable with the general data closing the radio button's td tag and closing the tr.
						$table_body='</td>'.$general_data.'</tr>';
						# Add the table body to the form.
						$fg->addFormPart($table_body);
					}
					else
					{
						# Concatenate the general data to the $table_body variable first opening a new tr.
						$table_body.='<tr>'.$general_data;
						# Check if there should be edit or Delete buttons displayed.
						if($delete===TRUE OR $edit===TRUE)
						{
							# Concatenate the button(s) to the $table_body variable wrapped in td tags.
							$table_body.='<td>'.$edit_content.$delete_content.'</td>';
						}
						# Close the current tr.
						$table_body.='</tr>';
					}
				}
				# Check if this is a select list.
				if($select==='select')
				{
					# Close the table.
					$fg->addFormPart('</table>');
					# Add the submit button.
					$fg->addElement('submit', array('name'=>'institution', 'value'=>'Select'), '', NULL, 'submit-institution');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a institution below</h4>'.$fg->display();
				}
				else
				{
					# Concatenate the table header and body and close the table setting it all to a local variable.
					$display=$table_header.$table_body.'</table>';
				}
				# Add the pagenavigator to the display variable.
				$display.=$paginator->getNavigator();
			}
			else
			{
				$display='<h3>There are no institutions to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayInstitutionList

	/**
	 * getInstitutions
	 *
	 * Retrieves records from the `institutions` table.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getInstitutions($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `institutions` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'institutions`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllInstitutions($records);
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
	} #==== End -- getInstitutions

	/**
	 * getThisInstitution
	 *
	 * Retrieves institution info from the `institutions` table in the Database for the passed id or institution name and sets it to the data member.
	 *
	 * @param		String	$value 	(The name or id of the institution to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisInstitution($value, $id=TRUE)
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
				# Set the institution id to the data member "cleaning" it.
				$this->setID($value);
				# Get the institution id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='institution';
				# Set the institution name to the data member "cleaning" it.
				$this->setInstitution($value);
				# Get the institution name and reset it to the variable.
				$value=$this->getInstitution();
			}
			# Get the institution info from the Database.
			$institution=$db->get_row('SELECT `id`, `institution` FROM `'.DBPREFIX.'institutions` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($institution!==NULL)
			{
				# Set the institution name to the data member.
				$this->setID($institution->id);
				# Set the institution name to the data member.
				$this->setInstitution($institution->institution);
				return TRUE;
			}
			# Return FALSE because the institution wasn't in the table.
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
	} #==== End -- getThisInstitution

	/*** End public methods ***/

} # End Institution class.