<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * LanguageFormProcessor
 *
 * The LanguageFormProcessor Class is used to create and process language forms.
 */
class LanguageFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processLanguage
	 *
	 * Processes a submitted language for upload, edit, or deletion.
	 *
	 * @param		$data			(An array of values tp populate the form with.)
	 * @access	public
	 */
	public function processLanguage($data=array())
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
			# Get the LanguageFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'LanguageFormPopulator.php');

			# Remove any un-needed CMS session data. (This needs to happen before populateLanguageForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.)
			$this->loseSessionData('language');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('language', 'language');

			# Instantiate a new instance of LanguageFormPopulator.
			$populator=new LanguageFormPopulator();
			# Populate the form and set the Language data members for this language.
			$populator->populateLanguageForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			$display_delete_form=$this->processLanguageDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processLanguageBack();

			# Get the Language object from the LanguageFormPopulator and set it to a local variable.
			$language=$populator->getLanguageObject();

			# Set the language's id to a variable.
			$id=$language->getID();
			# Set the language's name to a variable.
			$language_name=$language->getLanguage();
			# Set the language's ISO to a variable.
			$iso=$language->getISO();
			# Set the site name to a variable.
			$site_name=$main_content->getSiteName();
			# Set the language's unique status to a variable.
			$unique=$populator->getUnique();

			# Check if the form has been submitted and the submit button was the "Submit" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['language']) && ($_POST['language']==='Submit' OR $_POST['language']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the language field was empty (or less than 2 characters or more than 255 characters long).
				$empty_name=$fv->validateEmpty('language', 'Please enter a language.', 2, 255);

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
					# Check if the language data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the languages table in the Database.
						$fields=array('id', 'language', 'ISO');
						# Instantiate a new Search object.
						$search=new Search();
						# Make an array of the terms to search for (enclose multiple word strings in double quotes.)
						$terms=$language_name.', '.$iso;
						# Don't compare with the video ID.
						$filter=array('filter_fields'=>array('id'));
						# Check if the id is empty.
						if(!empty($id))
						{
							# Create a search filter that won't return the current record we may be editing.
							$filter=array_merge($filter, array('filter_sql'=>'`id` != '.$db->quote($id)));
						}
						# Search for duplicate records.
						$search->setAllResults($search->performSearch($terms, 'languages', $fields, NULL, $filter));
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
								# Instantiate a new Language object.
								$dup_language=new Language();
								# Get the info for this record.
								$dup_language->getThisLanguage($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_language->getID()]=array(
									'id'=>$dup_language->getID(),
									'language'=>$dup_language->getLanguage(),
									'iso'=>$dup_language->getISO()
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
						$_SESSION['form']['language']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the language is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Create the default value for the message action.
						$message_action='added';
						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'languages` ('.
							'`language`, '.
							'`ISO`'.
							') VALUES ('.
							$db->quote($db->escape($language_name)).', '.
							((!empty($iso)) ? $db->quote($db->escape($iso)) : 'NULL').
							')';
						# Check if this is an UPDATE. If there is an ID, it's an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'languages` SET '.
								'`language` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $language_name))).
								'`ISO` = '.((!empty($iso)) ? $db->quote($db->escape($iso)) : 'NULL').
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						# Run the sql query.
						$db_post=$db->query($sql);
						# Check if the database query was successful.
						if($db_post>0)
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['language']);
							$this->redirectLanguage($language_name, $message_action);
						}
						elseif(!empty($id))
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['language']);
							# Set a nice message for the user in a session.
							$_SESSION['message']="The language's record was unchanged.";
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
			throw new Exception('There was an error posting to the `languages` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processLanguage

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processLanguageBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a language.
	 *
	 * @access	private
	 */
	private function processLanguageBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get a language.
			$indexes=array(
				'audio',
				'file',
				'post',
				'video'
			);
			# Set the resource value.
			$resource='language';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processLanguageBack

	/**
	 * processLanguageDelete
	 *
	 * Removes a language from the `languages` table and the system. A wrapper method for the deleteLanguage method in the Language class.
	 *
	 * @access	private
	 */
	private function processLanguageDelete()
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
			# Check if the language's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['language']) && isset($_GET['delete']))
			{
				# Check if the passed language id is an integer.
				if($validator->isInt($_GET['language'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_language']) && ($_POST['delete_language']==='delete')))
					{
						# Get the instance of the LanguageFormPopulator.
						$populator=$this->getPopulator();
						# Get the instance of the Language from the populator.
						$language=$populator->getLanguageObject();
						# Get the info for this language and set the return boolean to a variable.
						$record_retrieved=$language->getThisLanguage($_GET['language']);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the language name to a local variable.
							$language_name=$language->getLanguage();
							# Set the "cleaned id to a local variable.
							$id=$language->getID();
							# Get the SubContent class. With this class, the File object can be accessed as well as the SubContent.
							require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
							# Instantiate a new SubContent object.
							$subcontent=new SubContent();
							# Get all subcontent with this language associated.
							$records_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`text_language` = '.$db->quote($id).' OR `trans_language` = '.$db->quote($id));
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
							# Check if this user has access to all records that have this language associated.
							if($access===TRUE)
							{
								# Get the Audio class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
								# Instantiate a new Audio object.
								$audio=new Audio();
								# Count all audio records with this language associated.
								$count=$audio->countAllAudio('`language` = '.$db->quote($id));
								# Check if there where records associated with the language.
								if($count>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this language associated.
							if($access===TRUE)
							{
								# Get the File class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
								# Instantiate a new File object.
								$file=new File();
								# Count all file records with this language associated.
								$count=$file->countAllFiles('all', NULL, 'AND `language` = '.$db->quote($id));
								# Check if there where records associated with the language.
								if($count>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this language associated.
							if($access===TRUE)
							{
								# Get the Video class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
								# Instantiate a new Video object.
								$video_obj=new Video();
								# Count all video records with this language associated.
								$count_videos=$video_obj->countAllVideos('`language` = '.$db->quote($id));
								# Check if there where records associated with the language.
								if($count_videos>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user still has access to delete this language.
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
											DBPREFIX.'subcontent.text_language = NULL, '.
											DBPREFIX.'subcontent.trans_language = NULL, '.
											DBPREFIX.'audio.language = NULL, '.
											DBPREFIX.'files.language = NULL, '.
											DBPREFIX.'videos.language = NULL '.
											'WHERE '.
											DBPREFIX.'subcontent.text_language = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'subcontent.trans_language = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'audio.language = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'files.language = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'videos.language = '.$db->quote($id));
										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The language "'.$language_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this language removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the language "'.$language_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['language']);
								# Delete the language from the Database and set the returned value to a variable.
								$deleted=$language->deleteLanguage($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectLanguage($language_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The language "'.$language_name.'" (id: '.$id.') was NOT deleted from the language list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this language removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The language was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this language, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The language was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('language');
						}
					}
					# Check if the form has been submitted to NOT delete the language.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_language']) && ($_POST['delete_language']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The language was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this language and request confirmation from the user with the appropriate warnings.
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
	} #==== End -- processLanguageDelete

	/**
	 * redirectLanguage
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire a language.
	 *
	 * @access	private
	 */
	private function redirectLanguage($language_name, $action, $default_message=TRUE)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Language object and set it to a local variable.
			$language=$populator->getLanguageObject();
			# Get the data for the new language.
			$language->getThisLanguage($language_name, FALSE);
			# Get the new language's id.
			$language_id=$language->getID();
			# Remove the language session.
			unset($_SESSION['form']['language']);
			# Check if the default message should be sent.
			if($default_message===TRUE)
			{
				# Set a nice message for the user in a session.
				$_SESSION['message']='The language "'.$language_name.'" was successfully '.$action.'!';
			}
			else
			{
				# Set the passed custom message.
				$_SESSION['message']=$action;
			}
			# Check if there is a post or content session.
			if(isset($_SESSION['form']['post']) OR isset($_SESSION['form']['file']) OR isset($_SESSION['form']['video']) OR isset($_SESSION['form']['audio']))
			{
				# Set the default origin form's name.
				$origin_form='video';
				# Set the video session language index name.
				$language_index='Language';
				if(isset($_SESSION['form']['post']))
				{
					# Set the form's name as "content".
					$origin_form='post';
					# Set the post session language index name.
					$language_index='LanguageID';
				}
				if(isset($_SESSION['form']['file']))
				{
					# Set the form's name as "file".
					$origin_form='file';
				}
				if(isset($_SESSION['form']['audio']))
				{
					# Set the form's name as "audio".
					$origin_form='audio';
				}
				# Set the session language id.
				$_SESSION['form'][$origin_form][$language_index]=$language_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='language';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectLanguage

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
			# Get the Language object and set it to a local variable.
			$language=$populator->getLanguageObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['language']=
				array(
					'ID'=>$language->getID(),
					'FormURL'=>$form_url,
					'ISO'=>$language->getISO(),
					'Language'=>$language->getLanguage(),
					'Unique'=>$populator->getUnique()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End LanguageFormProcessor class.