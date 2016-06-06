<?php /* framework/application/modules/Content/Language.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Language
 *
 * The Language Class is used access and maintain the `languages` table in the database.
 *
 */
class Language
{
	/*** data members ***/

	private $all_languages=NULL;
	private $id=NULL;
	private $iso=NULL;
	private $language=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllLanguages
	 *
	 * Sets the data member $all_languages.
	 *
	 * @param		$languages (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllLanguages($languages)
	{
		# Check if the passed value is empty.
		if(!empty($languages))
		{
			# Explicitly make it an array.
			$languages=(array)$languages;
			# Set the data member.
			$this->all_languages=$languages;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_languages=NULL;
		}
	} #==== End -- setAllLanguages

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
				throw new Exception('The passed language id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setISO
	 *
	 * Sets the data member $iso.
	 *
	 * @param		$iso
	 * @access	public
	 */
	public function setISO($iso)
	{
		# Check if the passed value is empty.
		if(!empty($iso))
		{
			# Clean it up.
			$iso=trim($iso);
			# Set the data member.
			$this->iso=$iso;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->iso=NULL;
		}
	} #==== End -- setISO

	/**
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param		$language
	 * @access	public
	 */
	public function setLanguage($language)
	{
		# Check if the passed value is empty.
		if(!empty($language))
		{
			# Check if the passed value is an object.
			if(!is_object($language))
			{
				# Strip slashes and decode any html entities.
				$language=html_entity_decode(stripslashes($language), ENT_COMPAT, 'UTF-8');
				# Clean it up.
				$language=trim($language);
			}
			# Set the data member.
			$this->language=$language;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->language=NULL;
		}
	} #==== End -- setLanguage

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * deleteLanguage
	 *
	 * Removes a language from the `languages` table.
	 *
	 * @param		$id				int (The id of the language in the `languages` table.
	 * @access	public
	 */
	public function deleteLanguage($id, $redirect=NULL)
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
					$_SESSION['message']='That language was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
				else
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the language is premium content or not.
					$this_language=$this->getThisLanguage($id);
					# Check if the language was found.
					if($this_language===TRUE)
					{
						try
						{
							# Delete the language from the `languages` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'languages` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Check if the language was deleted.
							if($deleted!==NULL)
							{
								# Set a nice message to display to the user.
								$_SESSION['message']='The language "'.$this->getLanguage().'" was successfully deleted.';

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
						$_SESSION['message']='The language was not found.';
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
	} #==== End -- deleteLanguage

	/**
	 * getAllLanguages
	 *
	 * Returns the data member $all_languages.
	 *
	 * @access	public
	 */
	public function getAllLanguages()
	{
		return $this->all_languages;
	} #==== End -- getAllLanguages

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
	 * getISO
	 *
	 * Returns the data member $iso.
	 *
	 * @access	public
	 */
	public function getISO()
	{
		return $this->iso;
	} #==== End -- getISO

	/**
	 * getLanguage
	 *
	 * Returns the data member $language.
	 *
	 * @access	public
	 */
	public function getLanguage()
	{
		return $this->language;
	} #==== End -- getLanguage

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllLanguages
	 *
	 * Returns the number of languages in the database.
	 *
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllLanguages($limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		try
		{
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'languages`'.(($and_sql===NULL) ? '' : ' WHERE '.$and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllLanguages

	/**
	 * displayLanguageList
	 *
	 * Returns a list (table) of languages.
	 *
	 * @param		$select 	(Whether or not the list should be a radio select display.)
	 * @access	public
	 */
	public function displayLanguageList($select=FALSE)
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
			# Count the languages.
			$content_count=$this->countAllLanguages();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='language';
				# Set the default sort direction of languages for the language sorting link to a variable.
				$name_dir='DESC';
				# Check if GET data for language has been passed and it is an integer.
				if(isset($_GET['language']) && $validator->isInt($_GET['language'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['language']='language='.$_GET['language'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "language" GET query; it can't be passed with "select".
					unset($params_a['language']);
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
						# Reset the sort direction of languages for the language sorting link to "ASC".
						$name_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_name" and "by_title" indexes of the array.
				unset($params_a['by_name']);
				# Implode the query parameters array to a string sepparated by ampersands for the language and title sorting links.
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
				# Get the Languages.
				$this->getLanguages($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned Language records to a variable.
				$all_languages=$this->getAllLanguages();

				# Start a table for the languages and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the language column to a variable.
				$general_header='<th><abbr title="International Organization for Standardization">ISO</abbr></th>';
				# Set the table header for the info column to a variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageContent/languages/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_name='.$name_dir.'" title="Order by language name">Language</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', 'http'.($validator->isSSL()===TRUE ? 's' : '').'://'.FULL_URL, 'post', '_top', FALSE, 'language-list');
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
				# Loop through the all_languages array.
				foreach($all_languages as $row)
				{
					# Instantiate a new Language object.
					$language=New Language();
					# Set the relevant returned field values File data members.
					$language->setID($row->id);
					$language->setLanguage($row->language);
					$language->setISO($row->ISO);
					# Set the relevant Language data members to local variables.
					$language_id=$language->getID();
					# Replace any tokens with their correlating value.
					$language_name=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $language->getLanguage());
					$language_iso=$language->getISO();
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the language markup to the $general_data variable.
					$general_data='<td>'.$language_iso.'</td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="language'.$language_id.'">' : '' ).'<a href="'.APPLICATION_URL.'profile/?language='.$language_id.'" title="'.$language_name.' on '.DOMAIN_NAME.'" target="_blank">'.$language_name.'</a>'.(($select==='select') ? '</label>' : '' ).'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageContent/languages/?language='.$language_id.'" class="button-edit" title="Edit '.$language_name.'">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageContent/languages/?language='.$language_id.'&amp;delete" class="button-delete" title="Delete '.$language_name.'">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this language.
						$fg->addElement('radio', array('name'=>'language_info', 'value'=>$language_id.':'.$language_name, 'id'=>'language'.$language_id));
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
					$fg->addElement('submit', array('name'=>'language', 'value'=>'Select'), '', NULL, 'submit-language');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a language below</h4>'.$fg->display();
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
				$display='<h3>There are no languages to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayLanguageList

	/**
	 * getLanguages
	 *
	 * Retrieves records from the `languages` table.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getLanguages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `languages` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'languages`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllLanguages($records);
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
	} #==== End -- getLanguages

	/**
	 * getThisLanguage
	 *
	 * Retrieves language info from the `languages` table in the Database for the passed id or language name and sets it to the data member.
	 *
	 * @param		String	$value 	(The name or id of the language to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisLanguage($value, $id=TRUE)
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
				# Set the language id to the data member "cleaning" it.
				$this->setID($value);
				# Get the language id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='language';
				# Set the language name to the data member "cleaning" it.
				$this->setLanguage($value);
				# Get the language name and reset it to the variable.
				$value=$this->getLanguage();
			}
			# Get the language info from the Database.
			$language=$db->get_row('SELECT `id`, `language`, `ISO` FROM `'.DBPREFIX.'languages` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($language!==NULL)
			{
				# Set the language id to the data member.
				$this->setID($language->id);
				# Set the language ISO Code to the data member.
				$this->setISO($language->ISO);
				# Set the language name to the data member.
				$this->setLanguage($language->language);
				return TRUE;
			}
			# Return FALSE because the language wasn't in the table.
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
	} #==== End -- getThisLanguage

	/*** End public methods ***/

} # End Language class.