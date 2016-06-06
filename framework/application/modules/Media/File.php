<?php

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the Media class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Media.php');

/**
 * File
 *
 * The File Class is used access and maintain the `files` table in the database.
 *
 */
class File extends Media
{
	/*** data members ***/

	private $all_files=array();
	private $file=NULL;
	private static $file_obj;
	private $premium;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllFiles
	 *
	 * Sets the data member $files.
	 *
	 * @param	$files					May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllFiles($files)
	{
		# Check if the passed value is empty.
		if(!empty($files))
		{
			# Explicitly make it an array.
			$files=(array)$files;
			# Set the data member.
			$this->all_files=$files;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_files=array();
		}
	} #==== End -- setAllFiles

	/**
	 * setFile
	 *
	 * Sets the data member $file.
	 *
	 * @param	$file
	 * @access	public
	 */
	public function setFile($file)
	{
		# Check if the passed value is empty.
		if(!empty($file))
		{
			# Clean it up.
			$file=trim($file);
			# Set the data member.
			$this->file=$file;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file=NULL;
		}
	} #==== End -- setFile

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 * Extends setID in Media.
	 *
	 * @param	int $id					A numeric ID representing the file.
	 * @param	string $media_type		The type of media that the ID represents. Default is "file".
	 * @access	public
	 */
	public function setID($id, $media_type='file')
	{
		try
		{
			# Check if the passed $id is empty.
			if($id=='add' OR $id=='select')
			{
				# Explicitly set the data member to NULL.
				$id=NULL;
			}
			parent::setID($id, $media_type);
		}
		catch(Exception $error)
		{
			throw $error;
		}
	} #==== End -- setID

	/**
	 * setPremium
	 *
	 * Sets the data member $premium.
	 *
	 * @param	$premium				NULL=Not Premium Content, 0=Premium Content
	 * @access	public
	 */
	public function setPremium($premium)
	{
		# Check if the passed value is NULL.
		if($premium!==NULL)
		{
			# Set the value to 0.
			$premium=0;
		}
		# Explicitly set the data member to NULL.
		$this->premium=$premium;
	} #==== End -- setPremium

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllFiles
	 *
	 * Returns the data member $all_files.
	 *
	 * @access	public
	 */
	public function getAllFiles()
	{
		return $this->all_files;
	} #==== End -- getAllFiles

	/**
	 * getFile
	 *
	 * Returns the data member $file.
	 *
	 * @access	public
	 */
	public function getFile()
	{
		return $this->file;
	} #==== End -- getFile

	/**
	 * getPremium
	 *
	 * Returns the data member $premium.
	 *
	 * @access	public
	 */
	public function getPremium()
	{
		return $this->premium;
	} #==== End -- getPremium

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllFiles
	 *
	 * Returns the number of files in the database.
	 *
	 * @param	$limit					The limit of records to count.
	 * @param	$where					WHERE statements in the query.
	 * @access	public
	 */
	public function countAllFiles($limit=NULL, $where=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'files`'.(($where===NULL) ? '' : ' WHERE '.$where).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllFiles

	/**
	 * deleteFile
	 *
	 * Removes a file from the `files` table and the actual file from the system.
	 *
	 * @param	int $id					The id of the file in the `files` table.
	 * @access	public
	 */
	public function deleteFile($id, $redirect=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Check if a redirect URL was passed.
				if($redirect===NULL)
				{
					# Set the redirect to the default.
					$redirect=PROTOCAL.FULL_DOMAIN.HERE;
				}
				# Check if the passed redirect URL was FALSE.
				if($redirect===FALSE)
				{
					# Set the value to NULL (no redirect).
					$redirect===NULL;
				}
				# Validate the passed id as an integer.
				if($validator->isInt($id)===TRUE)
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the file is premium content or not.
					$this_file=$this->getThisFile($id);
					# Check if the file was found.
					if($this_file===TRUE)
					{
						# Create an empty variable to hold the name of a folder for the file.
						$folder='';
						# Check if was premium content.
						if($this->getPremium()!==NULL)
						{
							# Set the name of the folder to the variable.
							$folder='premium';
						}
						# Get the FileHandler class.
						require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
						# Instantiate a new FileHandler object.
						$file_handler=new FileHandler();
						# Delete the file.
						if($file_handler->deleteFile(BODEGA.$folder.DS.$this->getFile())===TRUE)
						{
							try
							{
								# Remove the file from all posts in the `subcontent` table.
								$db->query('UPDATE `'.DBPREFIX.'subcontent` SET `file` = NULL WHERE `file` = '.$db->quote($id));
								# Delete the file from the `files` table.
								$deleted=$db->query('DELETE FROM `'.DBPREFIX.'files` WHERE `id` = '.$db->quote($id).' LIMIT 1');
								# Check if the file was deleted.
								if($deleted!==NULL)
								{
									# Set a nice message to display to the user.
									$_SESSION['message']='The file "'.$this->getFile().'" was successfully deleted.';
									# Check if there is a redirect.
									if(!empty($redirect))
									{
										# Redirect the user.
										$doc->redirect($redirect);
									}
									return TRUE;
								}
								return FALSE;
							}
							catch(ezDB_Error $ez)
							{
								throw new Exception('Error occured: '.$ez->error.', but the file itself was deleted.<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
							}
							catch(Exception $e)
							{
								throw $e;
							}
						}
						else
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid file for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
					}
					else
					{
						# Set a nice message to the session.
						$_SESSION['message']='The file was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That file was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteFile

	/**
	 * displayFileList
	 *
	 * Returns a selectable list of files.
	 *
	 * @param	$select
	 * @access	public
	 */
	public function displayFileList($select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Set a default variable for the "WHERE" portion of the sql statement (1=have the legal rights to display this material 2=Internal document only).
			$where_statement=NULL;
			# Check if the logged in User is a Managing User.
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$where_statement='(`availability` = 1 || `availability` = 2)';
			}
			# Check if the logged in User is an Admin.
			if($login->checkAccess(ADMIN_USERS)===TRUE)
			{
				# Set a variable for the "WHERE" portion of the sql statement.
				$where_statement='`availability` = 1';
			}
			# Count the returned files.
			$content_count=$this->countAllFiles(NULL, $where_statement);
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='file';
				# Set the default sort direction of files for the file sorting link to a variable.
				$file_dir='DESC';
				# Set the default sort direction of titles for the title sorting link to a variable.
				$title_dir='DESC';
				# Check if GET data for file has been passed and it is an integer.
				if(isset($_GET['file']) && $validator->isInt($_GET['file'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['file']='file='.$_GET['file'];
				}
				# NOTE: What's this? Old code?
				/*
				# Check if GET data for "add" has been passed.
				if(isset($_GET['add']))
				{
					# Set the query to the query parameters array.
					$params_a['add']='add';
				}
				*/
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "file" GET query; it can't be passed with "select".
					unset($params_a['file']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_file" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_file']) && ($_GET['by_file']==='ASC' OR $_GET['by_file']==='DESC') && !isset($_GET['by_title']))
				{
					# Set the query to the query parameters array.
					$params_a['by_file']='by_file='.$_GET['by_file'];
					# Check if the order is to be descending.
					if($_GET['by_file']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of files for the file sorting link to "ASC".
						$file_dir='ASC';
					}
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_file" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_file']))
				{
					# Set the query to the query parameters array.
					$params_a['by_title']='by_title='.$_GET['by_title'];
					# Reset the default "sort by" to "title".
					$sort_by='title';
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
				# Get rid of the "by_file" and "by_title" indexes of the array.
				unset($params_a['by_file']);
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the file and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to editing a branch.
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

				# Get the Files.
				$this->getFiles($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir);
				# Set the returned File records to a variable.
				$all_files=$this->getAllFiles();

				# Start a table for the files and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the file column to a variable.
				$general_header='<th class="download-file"><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_file='.$file_dir.'" title="Order by file name">Download</a></th>';
				# Add the table header for the title column to the $general_header variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Title</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', PROTOCAL.FULL_URL, 'post', '_top', FALSE, 'file-list');
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
				# Loop through the all_files array.
				foreach($all_files as $row)
				{
					# Instantiate a new File object.
					$file_obj=new File();
					# Set the relevant returned field values File data members.
					$file_obj->setID($row->id);
					$file_obj->setFile($row->file);
					$file_obj->setPremium($row->premium);
					$file_obj->setTitle($row->title);
					# Get the relevant File data members to local variables.
					$file_id=$file_obj->getID();
					$file_name=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $file_obj->getFile());
					$file_title=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $file_obj->getTitle());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the file markup to the $general_data variable.
					$general_data='<td><a href="'.DOWNLOADS.'?f='.$file_name.(($file_obj->getPremium()===0) ? '&amp;t=premium' : '').'" title="'.$file_title.'">'.$file_name.'</a></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="file'.$file_id.'">' : '').$file_title.(($select==='select') ? '</label>' : '').'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'" class="button-edit" title="Edit this">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'&amp;delete=yes" class="button-delete" title="Delete This">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this file.
						$fg->addElement('radio', array('name'=>'file_info', 'value'=>$file_id.':'.$file_name, 'id'=>'file'.$file_id));
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
					$fg->addElement('submit', array('name'=>'file', 'value'=>'Select File'), '', NULL, 'submit-file');
					$fg->addElement('submit', array('name'=>'file', 'value'=>'Go Back'), '', NULL, 'submit-back');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a file below</h4>'.$fg->display();
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
				$display='<h3>There are no files to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayFileList

	/**
	 * getFiles
	 *
	 * Retrieves records from the `files` table.
	 *
	 * @param	$limit					The LIMIT of the records.
	 * @param	$fields					The name of the field(s) to be retrieved.
	 * @param	$order					The name of the field to order the records by.
	 * @param	$direction				The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getFiles($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `files` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'files`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllFiles($records);
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
	} #==== End -- getFiles

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$file_obj)
		{
			self::$file_obj=new File();
		}
		return self::$file_obj;
	} #==== End -- getInstance

	/**
	 * getThisFile
	 *
	 * Retrieves file info from the `files` table in the Database for the passed id or file name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the file to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean 				TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisFile($value, $id=TRUE)
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
				# Set the file id to the data member "cleaning" it.
				$this->setID($value);
				# Get the file id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file';
				# Set the file name to the data member "cleaning" it.
				$this->setFile($value);
				# Get the file name and reset it to the variable.
				$value=$this->getFile();
			}
			# Get the file info from the Database.
			$file=$db->get_row('SELECT `id`, `file`, `title`, `author`, `year`, `location`, `category`, `availability`, `date`, `premium`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'files` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($file!==NULL)
			{
				# Set the file name to the data member.
				$this->setID($file->id);
				# Set the file name to the data member.
				$this->setFile($file->file);
				# Set the file author to the data member.
				$this->setAuthor($file->author);
				# Set the file availability to the data member.
				$this->setAvailability($file->availability);
				# Pass the file category id(s) to the setCategory method, thus setting the data member with the category name(s).
				$this->setCategories($file->category);
				# Set the contributor id to the data member.
				$this->setContID($file->contributor);
				# Set the file post/edit date to the data member.
				$this->setDate($file->date);
				# Pass the file institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($file->institution);
				# Pass the file language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($file->language);
				# Set the file location to the data member.
				$this->setLocation($file->location);
				# Set whether or not the file is "premium" content to the data member.
				$this->setPremium($file->premium);
				# Pass the file publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($file->publisher);
				# Set the file title to the data member.
				$this->setTitle($file->title);
				# Set the file publish year to the data member.
				$this->setYear($file->year);
				return TRUE;
			}
			# Return FALSE because the file wasn't in the table.
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
	} #==== End -- getThisFile

	/*** End public methods ***/

} # End File class.