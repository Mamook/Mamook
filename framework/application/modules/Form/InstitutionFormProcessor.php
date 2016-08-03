<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * InstitutionFormProcessor
 *
 * The InstitutionFormProcessor Class is used to create and process institution forms.
 */
class InstitutionFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processInstitution
	 *
	 * Processes a submitted institution for upload, edit, or deletion.
	 *
	 * @param		$data			(An array of values tp populate the form with.)
	 * @access	public
	 */
	public function processInstitution($data=array())
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Bring the content instance into scope.
			$main_content=Content::getInstance();
			# Get the InstitutionFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'InstitutionFormPopulator.php');

			# Remove any un-needed CMS session data. (This needs to happen before populateInstitutionForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.)
			$this->loseSessionData('institution');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('institution', 'institution');

			# Instantiate a new instance of InstitutionFormPopulator.
			$populator=new InstitutionFormPopulator();
			# Populate the form and set the Institution data members for this institution.
			$populator->populateInstitutionForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			$display_delete_form=$this->processInstitutionDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processInstitutionBack();
			$this->processInstitutionSelect();

			# Get the Institution object from the InstitutionFormPopulator and set it to a local variable.
			$institution=$populator->getInstitutionObject();

			# Set the institution's id to a variable.
			$id=$institution->getID();
			# Set the institution's name to a variable.
			$name=$institution->getInstitution();
			# Set the site name to a variable.
			$site_name=$main_content->getSiteName();
			# Set the institution's unique status to a variable.
			$unique=$populator->getUnique();

			# Check if the form has been submitted and the submit button was the "Submit" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['institution']) && ($_POST['institution']==='Submit' OR $_POST['institution']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the name field was empty (or less than 2 characters or more than 255 characters long).
				$empty_name=$fv->validateEmpty('name', 'Please enter a name for the institution.', 2, 255);

				# Check for errors to display so that the script won't go further.
				if($fv->checkErrors()===TRUE)
				{
					# Create a variable to the error heading.
					$alert_title='Resubmit the form after correcting the following errors:';
					# Concatenate the errors to the heading.
					$error=$fv->displayErrors();
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError($error);
				}
				else
				{
					# Check if the institution data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the institutions table in the Database.
						$fields=array('id', 'institution');
						# Instantiate a new Search object.
						$search=new Search();
						# Make an array of the terms to search for (enclose multiple word strings in double quotes.)
						$terms=$name;
						# Don't compare with the video ID.
						$filter=array('filter_fields'=>array('id'));
						# Check if the id is empty.
						if(!empty($id))
						{
							# Create a search filter that won't return the current record we may be editing.
							$filter=array_merge($filter, array('filter_sql'=>'`id` != '.$db->quote($id)));
						}
						# Search for duplicate records.
						$search->setAllResults($search->performSearch($terms, 'institutions', $fields, NULL, $filter));
						# Set any search results to a variable.
						$duplicates=$search->getAllResults();
						# Create an empty array for the duplicate display.
						$dup_display=array();
						# Check if there were records returned.
						if(!empty($duplicates))
						{
							# Loop through the duplicates.
							foreach($duplicates as $duplicate)
							{
								# Instantiate a new Institution object.
								$dup_institution=new Institution();
								# Get the info for this record.
								$dup_institution->getThisInstitution($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_institution->getID()]=array(
									'id'=>$dup_institution->getID(),
									'name'=>$dup_institution->getInstitution()
								);
							}
							# Explicitly set unique to 0 (not unique).
							$populator->setUnique(0);
						}
						else
						{
							# Explicitly set unique to 1 (unique).
							$populator->setUnique(1);
						}
						$unique=$populator->getUnique();
						$_SESSION['form']['institution']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the institution is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Create the default value for the message action.
						$message_action='added';
						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'institutions` ('.
							'`institution`'.
							') VALUES ('.
							$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $name))).
							')';
						# Check if this is an UPDATE. If there is an ID, it's an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'institutions` SET
								`institution` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $name))).
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						# Run the sql query.
						$db_post=$db->query($sql);
						# Check if the database query was successful.
						if($db_post>0)
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['institution']);
							$this->redirectInstitution($name, $message_action);
						}
						elseif(!empty($id))
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['institution']);
							# Set a nice message for the user in a session.
							$_SESSION['message']="The institution's record was unchanged.";
							# Redirect the user to the page they were on.
							$this->redirectNoDelete();
						}
					}
				}
			}
			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error posting to the `institutions` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processInstitution

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processInstitutionBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch an institution.
	 *
	 * @access	private
	 */
	private function processInstitutionBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'audio',
				'file',
				'post',
				'video'
			);
			# Set the resource value.
			$resource='institution';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processInstitutionBack

	/**
	 * processInstitutionDelete
	 *
	 * Removes an institution from the `institutions` table and the system. A wrapper method for the deleteInstitution method in the Institution class.
	 *
	 * @access	private
	 */
	private function processInstitutionDelete()
	{
		try
		{
			# Bring the Login object into scope.
			global $login;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Explicitly set the delete variable to FALSE; the POST will NOT be deleted.
			$delete=FALSE;
			$access=TRUE;
			# Check if the institution's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['institution']) && isset($_GET['delete']))
			{
				# Check if the passed institution id is an integer.
				if($validator->isInt($_GET['institution'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_institution']) && ($_POST['delete_institution']==='delete')))
					{
						# Get the SubContent class. With this class, the File object can be accessed as well as the SubContent.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this institution and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisInstitution($_GET['institution']);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the Institution object to a local variable.
							$institution=$subcontent->getInstitution();
							# Set the institution name to a local variable.
							$institution_name=$institution->getInstitution();
							# Set the "cleaned id to a local variable.
							$id=$institution->getID();
							# Get all subcontent with this institution associated.
							$records_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`institution` = '.$db->quote($id));
							# Check if there were any subcontent returned.
							if($records_returned===TRUE)
							{
								# Set the returned subcontent records to a local variable.
								$rows=$subcontent->getAllSubContent();
								# Loop throught the returned rows.
								foreach($rows as $row)
								{
									$branches=trim(str_replace('-', ' ', $row->branch).' '.MAN_USERS);
									# Check if the user has access to this record.
									$access=$login->checkAccess($branches);
									if($access===FALSE) { break; }
								}
							}
							# Check if this user has access to all records that have this institution associated.
							if($access===TRUE)
							{
								# Get the Audio class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
								# Instantiate a new Audio object.
								$audio_obj=new Audio();
								# Count all audio records with this institution associated.
								$count_audio=$audio_obj->countAllAudio('`institution` = '.$db->quote($id));
								# Check if there where records associated with the institution.
								if($count_audio>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this institution associated.
							if($access===TRUE)
							{
								# Get the File class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
								# Instantiate a new File object.
								$file=new File();
								# Count all file records with this institution associated.
								$count=$file->countAllFiles('all', NULL, 'AND `institution` = '.$db->quote($id));
								# Check if there where records associated with the institution.
								if($count>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this institution associated.
							if($access===TRUE)
							{
								# Get the Video class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
								# Instantiate a new Video object.
								$video_obj=new Video();
								# Count all video records with this institution associated.
								$count_videos=$video_obj->countAllVideos('`institution` = '.$db->quote($id));
								# Check if there where records associated with the institution.
								if($count_videos>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user still has access to delete this institution.
							if($access===TRUE)
							{
								if($records_returned===TRUE)
								{
									try
									{
										/** FIX THIS QUERY OR IMPLEMENT nnDB IN MYSQL **/
										# Remove the file from all `subcontent`, `content`, and `product` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'subcontent`, '.
											'`'.DBPREFIX.'audio`, '.
											'`'.DBPREFIX.'files`, '.
											'`'.DBPREFIX.'videos` '.
											'SET '.
											DBPREFIX.'subcontent.institution = NULL, '.
											DBPREFIX.'audio.institution = NULL, '.
											DBPREFIX.'files.institution = NULL, '.
											DBPREFIX.'videos.institution = NULL '.
											'WHERE '.
											DBPREFIX.'subcontent.institution = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'audio.institution = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'files.institution = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'videos.institution = '.$db->quote($id));
										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The institution "'.$institution_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this institution removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the institution "'.$institution_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['institution']);
								# Delete the institution from the Database and set the returned value to a variable.
								$deleted=$institution->deleteInstitution($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectInstitution($institution_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The institution "'.$institution_name.'" (id: '.$id.') was NOT deleted from the institution list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this institution removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The institution was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this institution, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The institution was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('institution');
						}
					}
					# Check if the form has been submitted to NOT delete the institution.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_institution']) && ($_POST['delete_institution']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The institution was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this institution and request confirmation from the user with the appropriate warnings.
						require Utility::locateFile(TEMPLATES.'forms'.DS.'delete_form.php');
						return $display;
					}
				}
				# Redirect the user to the default redirect location. They have no business trying to pass a non-integer as an id!
				$doc->redirect(DEFAULT_REDIRECT);
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processInstitutionDelete

	/**
	 * processInstitutionSelect
	 *
	 * Processes a submitted form selecting an institution to add to a post.
	 *
	 * @access	private
	 * @return	string
	 */
	private function processInstitutionSelect()
	{
		# Bring the alert-title variable into scope.
		global $alert_title;
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Check if this is an institution select page.
		if(isset($_GET['select']))
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && $_POST['institution']=='Select Institution')
			{
				# Check if the institution id POST data was sent.
				if(isset($_POST['institution_info']))
				{
					# Get the Populator object and set it to a local variable.
					$populator=$this->getPopulator();
					# Get the Institution object and set it to a local variable.
					$institution=$populator->getInstitutionObject();
					$colon_pos=strpos($_POST['institution_info'], ':');
					$institution_id=substr($_POST['institution_info'], 0, $colon_pos);
					$institution_name=substr($_POST['institution_info'], $colon_pos+1);
					# Set the institution id to the Institution data member.
					$institution->setID($institution_id);
					# Set the institution name to the Institution data member.
					$institution->setInstitution($institution_name);
					# Set the institution's id to a variable.
					$institution_id=$institution->getID();
					# Set the institution's name to a variable.
					$institution_name=$institution->getInstitution();
					# Redirect the User back to the form that sent them to fetch an institution.
					$this->redirectInstitution($institution_name, 'selected');
				}
				else
				{
					# Set the error message to the Document object datamember so that it may be displayed on the page.
					$doc->setError('Please select an institution.');
					# Redirect the user to the page they were on with no POST or GET data.
					$doc->redirect(COMPLETE_URL);
				}
			}
		}
	} #==== End -- processInstitutionSelect

	/**
	 * redirectInstitution
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire an institution.
	 *
	 * @access	private
	 */
	private function redirectInstitution($institution_name, $action, $default_message=TRUE)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Institution object and set it to a local variable.
			$institution=$populator->getInstitutionObject();
			# Get the data for the new institution.
			$institution->getThisInstitution($institution_name, FALSE);
			# Get the new institution's id.
			$institution_id=$institution->getID();
			# Remove the institution session.
			unset($_SESSION['form']['institution']);
			# Check if the default message should be sent.
			if($default_message===TRUE)
			{
				# Set a nice message for the user in a session.
				$_SESSION['message']='The institution "'.$institution_name.'" was successfully '.$action.'!';
			}
			else
			{
				# Set the passed custom message.
				$_SESSION['message']=$action;
			}
			# Check if there is a post or content session.
			if(isset($_SESSION['form']['post']) OR isset($_SESSION['form']['file']) OR isset($_SESSION['form']['video']) OR isset($_SESSION['form']['audio']) OR isset($_SESSION['form']['product']))
			{
				# Set the default origin form's name.
				$origin_form='video';
				# Set the video session institution index name.
				$institution_index='Institution';
				if(isset($_SESSION['form']['post']))
				{
					# Set the form's name as "content".
					$origin_form='post';
					# Set the post session institution index name.
					$institution_index='InstitutionID';
				}
				elseif(isset($_SESSION['form']['file']))
				{
					# Set the form's name as "file".
					$origin_form='file';
				}
				elseif(isset($_SESSION['form']['audio']))
				{
					# Set the form's name as "audio".
					$origin_form='audio';
				}
				elseif(isset($_SESSION['form']['product']))
				{
					# Set the form's name as "product".
					$origin_form='product';
					# Set the product session institution index name.
					$institution_index='InstitutionID';
				}
				# Set the session institution id.
				$_SESSION['form'][$origin_form][$institution_index]=$institution_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='institution';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectInstitution

	/**
	 * setSession
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access	private
	 */
	private function setSession()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Institution object and set it to a local variable.
			$institution=$populator->getInstitutionObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['institution']=
				array(
					'ID'=>$institution->getID(),
					'FormURL'=>$form_url,
					'Institution'=>$institution->getInstitution(),
					'Unique'=>$populator->getUnique()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End InstitutionFormProcessor class.