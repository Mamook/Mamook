<?php /* framework/application/modules/Form/FileFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * FileFormProcessor
 *
 * The FileFormProcessor Class is used to create and process file forms.
 *
 */
class FileFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processFile
	 *
	 * Processes a submitted file for upload.
	 *
	 * @param	$data					An array of values tp populate the form with.
	 * @param	$max_size				The maximum allowed size of uploaded files.
	 * @access	public
	 * @return	string
	 */
	public function processFile($data, $max_size=104857600)
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			# Get the FileFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'FileFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populatFileForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('file');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('file', 'file');

			# Instantiate a new instance of FileFormPopulator.
			$populator=new FileFormPopulator();
			# Populate the form and set the File data members for this post.
			$populator->populateFileForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# If the URL params indicate this is a delete, process it. If the submit button hasn't been clicked yet, this will return the delete for itself.
			$display_delete_form=$this->processFileDelete();
			# Check if the delete form was returned.
			if($display_delete_form!==FALSE)
			{
				# Return the delete form and leave this method.
				return $display_delete_form;
			}

			# Check if the user clicked on a form that sends them back to a previous form that sent them to the audio form in the first place.
			$this->processFileBack();
			$this->processFileSelect();

			# Get the File object from the FileFormPopulator object and set it to a variable for use in this method.
			$file_obj=$populator->getFileObject();

			# Get the current file's name and set it to a variable.
			$current_file=$file_obj->getFile();
			# Create an empty variable to hold the new name of an uplaoded file.
			$new_name=NULL;
			# Set a variable to FALSE indicating that a file has not been uploaded.
			$uploaded_document=FALSE;
			# Set the file's id to a variable.
			$id=$file_obj->getID();
			# Set the file's author to a variable.
			$author=$file_obj->getAuthor();
			# Set the file's availability to a variable.
			$availability=$file_obj->getAvailability();
			# Set the file's categories to a variable.
			$categories=$file_obj->getCategories();
			# Create an empty variable for the category id's.
			$category_ids=NULL;
			# Check if there are categories.
			if(!empty($categories))
			{
				# Change the values for the id's.
				$categories=array_flip($categories);
				# Separate the category id's with dashes (-).
				$category_ids='-'.implode('-', $categories).'-';
			}
			# Set the post contributor's id to a variable.
			$contributor_id=$file_obj->getContID();
			# Set the file's posting date to a variable.
			$date=$file_obj->getDate();
			# Set the file's associated institution id to a variable.
			$institution_name=$file_obj->getInstitution();
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution_obj=new Institution();
			# Get the institution info via the institution name.
			$institution_obj->getThisInstitution($institution_name, FALSE);
			# Set the institution id to a variable.
			$institution_id=$institution_obj->getID();
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Set the file's language to a variable.
			$language=$file_obj->getLanguage();
			# Instantiate a new Language object.
			$language_obj=new Language();
			# Get the language info via the language name.
			$language_obj->getThisLanguage($language, FALSE);
			# Set the language id to a variable.
			$language_id=$language_obj->getID();
			# Set the file's publish location to a variable.
			$location=$file_obj->getLocation();
			# Set the file's premium status to a variable.
			$premium=$file_obj->getPremium();
			# Set the indicator of whether the premium status changed to a local variable.
			$premium_changed=$populator->getPremiumChange();
			# Create an empty variable for the name of the "premium" folder.
			$premium_path='';
			# Set the file's publisher name to a variable.
			$publisher_name=$file_obj->getPublisher();
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher_obj=new Publisher();
			# Get the publisher info via the publisher name.
			$publisher_obj->getThisPublisher($publisher_name, FALSE);
			# Set the publisher id to a variable.
			$publisher_id=$publisher_obj->getID();
			# Set the file's title to a variable.
			$title=$file_obj->getTitle();
			# Set the file's unique status to a variable.
			$unique=$populator->getUnique();
			# Set the file's publish year to a variable.
			$year=$file_obj->getYear();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['file']) && ($_POST['file']=='Add File' OR $_POST['file']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('file');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the title field was empty (or less than 2 characters or more than 1024 characters long).
				$empty_title=$fv->validateEmpty('title', 'Please enter a title for the file.', 2, 1024);
				# Check if the title field was empty (or less than 2 characters or more than 1024 characters long).
				$empty_title=$fv->validateEmpty('author', 'Please enter an author for the file.', 2, 1024);
				# Check if the title field was empty (or less than 2 characters or more than 255 characters long).
				$empty_title=$fv->validateEmpty('location', 'Please enter a publish location for the file.', 2, 255);
				$u_file=$_FILES['file'];
				if(((is_uploaded_file($u_file['tmp_name'])!==TRUE) OR ($u_file['error']===UPLOAD_ERR_NO_FILE) OR ($u_file['error']===4)) && empty($current_file))
				{
					# Set an error.
					$fv->setErrors('You must select a file to upload.');
				}
				# Check if a file was uploaded and if there have been no errors so far.
				if(array_key_exists('file', $_FILES) && ($fv->checkErrors()===FALSE))
				{
					# Get the Upload class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
					# Instantiate an Upload object.
					$upload=new Upload($_FILES['file']);

					# Check if the uploaded file size is not NULL.
					if($upload->getSize()!==NULL)
					{
						# Is the uploaded file for "premium" content?
						if($premium===0)
						{
							# Set the name of the "premium" folder (must end with backslash).
							$premium_path='premium'.DS;
						}

						# Get the FileHandler class.
						require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
						# Instantiate the new FileHandler object.
						$file_handler=new FileHandler();
						# Create safe file name based on the title.
						$clean_filename=$file_handler->cleanFilename($title);

						try
						{
							# Upload the document.
							$document_upload=$upload->uploadFile(BODEGA.$premium_path, array('doc', 'docx', 'txt', 'rtf', 'ppt', 'pptx', 'pdf', 'odt', 'images'), BODEGA.$premium_path, $clean_filename, $max_size, FALSE);
							# Reset the file's new name.
							$new_name=$upload->getName();
						}
						catch(Exception $e)
						{
							throw $e;
						}

						# Check for errors.
						if($upload->checkErrors()===TRUE)
						{
							# Remove uploaded file
							$upload->deleteFile(BODEGA.$premium_path.$new_name);
							# Get any errors.
							$document_errors=$upload->getErrors();
							# Loop through the errors.
							foreach($document_errors as $document_error)
							{
								# Set each error to our current error array.
								$fv->setErrors($document_error);
							}
						}
						# Check if the upload was successful.
						elseif($document_upload===TRUE)
						{
							# Set the variable that remembers that a file has been uploaded to TRUE (in case we need to remove the file).
							$uploaded_document=TRUE;
						}
					}
				}
				# Check for errors to display so that the script won't go further.
				if($fv->checkErrors()===TRUE)
				{
					# Create a variable to the error heading.
					$alert_title='Resubmit the form after correcting the following errors:';
					# Concatenate the errors to the heading.
					$error=$fv->displayErrors();
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError($error);
					# Check if there was an uploaded file.
					if($uploaded_document===TRUE)
					{
						# Remove uploaded file.
						$upload->deleteFile(BODEGA.$premium_path.$new_name);
					}
				}
				else
				{
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the `files` table in the Database.
						$fields=array('id', 'title');
						# Instantiate a new Search object.
						$search=new Search();
						# Make an array of the terms to search for (enclose multiple word strings in double quotes.)
						$terms=$title;
						# Don't compare with the video ID.
						$filter=array('filter_fields'=>array('id'));
						# Check if the id is empty.
						if(!empty($id))
						{
							# Create a search filter that won't return the current record we may be editing.
							$filter=array_merge($filter, array('filter_sql'=>'`id` != '.$db->quote($id)));
						}
						# Search for duplicate records.
						$search->setAllResults($search->performSearch($terms, 'files', $fields, NULL, $filter));
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
								# Instantiate a new File object.
								$dup_file=new File();
								# Get the info for this record.
								$dup_file->getThisFile($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_file->getID()]=array(
									'id'=>$dup_file->getID(),
									'author'=>$dup_file->getAuthor(),
									'availability'=>$dup_file->getAvailability(),
									'categories'=>$dup_file->getCategories(),
									'contributor'=>$dup_file->getContID(),
									'date'=>$dup_file->getDate(),
									'file'=>$dup_file->getFile(),
									'institution'=>$dup_file->getInstitution(),
									'language'=>$dup_file->getLanguage(),
									'location'=>$dup_file->getLocation(),
									'premium'=>$dup_file->getPremium(),
									'publisher'=>$dup_file->getPublisher(),
									'title'=>$dup_file->getTitle(),
									'year'=>$dup_file->getYear()
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
						$_SESSION['form']['file']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the post is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Create the default value for the message action.
						$message_action='added';

						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'files` ('.
							'`title`,'.
							' `file`,'.
							((!empty($author)) ? ' `author`,' : '').
							((!empty($year)) ? ' `year`,' : '').
							((!empty($location)) ? ' `location`,' : '').
							((!empty($category_ids)) ? ' `category`,' : '').
							' `availability`,'.
							' `date`,'.
							(($premium===0) ? ' `premium`,' : '').
							' `institution`,'.
							((!empty($publisher_id)) ? ' `publisher`,' : '').
							' `language`,'.
							' `contributor`'.
							') VALUES ('.
							$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
							' '.$db->quote($db->escape($new_name)).','.
							((!empty($author)) ? ' '.$db->quote($db->escape($author)).',' : '').
							((!empty($year)) ? ' '.$db->quote($year).',' : '').
							((!empty($location)) ? ' '.$db->quote($db->escape($location)).',' : '').
							((!empty($category_ids)) ? ' '.$db->quote($category_ids).',' : '').
							' '.$db->quote($availability).','.
							' '.$db->quote($date).','.
							(($premium===0) ? ' '.$db->quote('0').',' : '').
							' '.$db->quote($institution_id).','.
							((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : '').
							' '.$db->quote($language_id).','.
							' '.$db->quote($contributor_id).
							')';
						# Check if this is an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'files` SET
								`title` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
								((!empty($new_name)) ? ' `file` = '.$db->quote($db->escape($new_name)).',' : '').
								' `author` = '.$db->quote($db->escape($author)).','.
								' `year` = '.((empty($year)) ? $db->quote(0000) : $db->quote($year)).','.
								' `location` = '.$db->quote($db->escape($location)).','.
								' `category` = '.$db->quote($category_ids).','.
								' `availability` = '.$db->quote($availability).','.
								' `date` = '.$db->quote($date).','.
								' `premium` = '.(($premium===0) ? $db->quote('0') : 'NULL').','.
								' `institution` = '.$db->quote($institution_id).','.
								' `publisher` = '.((empty($publisher_id)) ? 'NULL' : $db->quote($publisher_id)).','.
								' `language` = '.$db->quote($language_id).','.
								' `contributor` = '.$db->quote($contributor_id).
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						try
						{
							# Run the sql query.
							$db_post=$db->query($sql);
							# Check if the query was successful.
							if(TRUE)//if($db_post>0)
							{
								# Remove the file session.
								unset($_SESSION['form']['file']);
								$name=$current_file;
								if(!empty($new_name))
								{
									$name=$new_name;
									if(!empty($current_file))
									{
										# Check if the premium value changed.
										if($premium_changed)
										{
											# Is the uploaded file for "premium" content?
											if($premium===0)
											{
												# Set the name of the "premium" folder to an empty string.
												$premium_path='';
											}
											else
											{
												# Set the name of the "premium" folder (must end with backslash).
												$premium_path='premium'.DS;
											}
										}
										# Remove uploaded file
										$upload->deleteFile(BODEGA.$premium_path.$current_file);
									}
								}
								# Set a nice message for the user in a session.
								$this->redirectFile($name, $message_action);
							}
							else
							{
								if(!empty($id))
								{
									# Set a nice message for the user in a session.
									$_SESSION['message']='The file\'s record was unchanged.';
									# Redirect the user to the page they were on.
									$this->redirectNoDelete();
								}
								# Check if there was an uploaded file.
								if($uploaded_document===TRUE)
								{
									# Remove uploaded file.
									$upload->deleteFile(BODEGA.$premium_path.$new_name);
								}
							}
						}
						catch(Exception $e)
						{
							# Check if there was an uploaded file.
							if($uploaded_document===TRUE)
							{
								# Remove uploaded file.
								$upload->deleteFile(BODEGA.$premium_path.$new_name);
							}
							throw $e;
						}
					}
				}
			}
			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error posting to the `file` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processFile

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processFileBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a file.
	 *
	 * @access	private
	 */
	private function processFileBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'post',
				'product'
			);
			# Set the resource value.
			$resource='file';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processFileBack

	/**
	 * processFileDelete
	 *
	 * Removes a file from the `files` table and the actual file from the system. A wrapper method for the deleteFile method in the File class.
	 *
	 * @access	private
	 */
	private function processFileDelete()
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
			# Check if the file's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['file']) && isset($_GET['delete']))
			{
				# Check if the passed file id is an integer.
				if($validator->isInt($_GET['file'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_file']) && ($_POST['delete_file']==='delete')))
					{
						# Get the SubContent class. With this class, the File object can be accessed as well as the SubContent.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this file and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisFile($_GET['file'], TRUE);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the File object to a local variable.
							$file_obj=$subcontent->getFile();
							# Set the file name to a local variable.
							$file_name=$file_obj->getFile();
							# Set the "cleaned id to a local variable.
							$id=$subcontent->getFileID();
							# Get all subcontent with this file associated.
							$subcontent_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`file` = '.$db->quote($id));
							# Set the product_returned variable to FALSE by default.
							$product_returned=FALSE;
							# Check if there were any subcontent returned.
							if($subcontent_returned===TRUE)
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
							# Check if this user has access to all subcontent posts that have this file associated.
							if($access===TRUE)
							{
								# Get the Product class.
								require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
								# Instantiate a new Product object.
								$product=new Product();
								# Get all product with this file associated.
								$count=$product->countAllRecords('all', NULL, '`file` = '.$db->quote($id));
								# Check if this file a associated with any product.
								if($count>0)
								{
									# Set the product_returned variable to TRUE.
									$product_returned===TRUE;
									# Check if the user has access to this record.
									$access=$login->checkAccess(MAN_USERS);
								}
							}
							# Check if this user still has access to delete this file.
							if($access===TRUE)
							{
								if(($subcontent_returned===TRUE) OR ($product_returned===TRUE))
								{
									try
									{
										# Remove the file from all `subcontent` and `product` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'subcontent`, `'.DBPREFIX.'products` '.
											'SET '.
											'`'.DBPREFIX.'subcontent`.`file` = NULL, `'.DBPREFIX.'products`.`file` = NULL '.
											'WHERE '.
											'`'.DBPREFIX.'subcontent`.`file` = '.$db->quote($id).' '.
											'OR '.
											'`'.DBPREFIX.'products`.`file` = '.$db->quote($id));
										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The file "'.$file_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this file removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the file "'.$file_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['file']);
								# Delete the file from the Database and set the returned value to a variable.
								$deleted=$file->deleteFile($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectFile($file_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The file "'.$file_name.'" (id: '.$id.') was NOT deleted from the file list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this file removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The file was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this file, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The file was not found.';
							# Redirect the user back to the page.
							$this->redirectNoDelete('file');
						}
					}
					# Check if the form has been submitted to NOT delete the file.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_file']) && ($_POST['delete_file']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The file was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this file and request confirmation from the user with the appropriate warnings.
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
	} #==== End -- processFileDelete

	/**
	 * processFileSelect
	 *
	 * Processes a submitted form selecting a file to add to a post.
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function processFileSelect()
	{
		# Check if this is a file select page.
		if(isset($_GET['select']))
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && ($_POST['file']=='Select File'))
			{
				# Bring the alert-title variable into scope.
				global $alert_title;
				# Set the Document instance to a variable.
				$doc=Document::getInstance();

				# Check if the file id POST data was sent.
				if(isset($_POST['file_info']))
				{
					# Get the Populator object and set it to a local variable.
					$populator=$this->getPopulator();
					# Get the File object and set it to a local variable.
					$file_obj=$populator->getFileObject();
					$colon_pos=strpos($_POST['file_info'], ':');
					$file_id=substr($_POST['file_info'], 0, $colon_pos);
					$file_name=substr($_POST['file_info'], $colon_pos+1);
					# Set the file id to the File data member.
					$file_obj->setID($file_id);
					# Set the file name to the File data member.
					$file_obj->setFile($file_name);
					# Set the file's id to a variable.
					$file_id=$file_obj->getID();
					# Set the file's name to a variable.
					$file_name=$file_obj->getFile();
				}
				else
				{
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError('Please select a file.');
				}
				# Redirect the User to the page they came from with a friendly message.
				$this->redirectFile($file_name, 'selected');
			}
		}
	} #==== End -- processFileSelect

	/**
	 * redirectFile
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire a file.
	 *
	 * @access	protected
	 */
	protected function redirectFile($file_name, $action)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the File object and set it to a local variable.
			$file_obj=$populator->getFileObject();
			# Get the data for the new file.
			$file_obj->getThisFile($file_name, FALSE);
			# Get the new file's id.
			$file_id=$file_obj->getID();
			# Remove the file session.
			unset($_SESSION['form']['file']);
			# Set a nice message for the user in a session.
			$_SESSION['message']='The file "'.$file_name.'" was successfully '.$action.'!';
			# Check if there is a post or content session.
			if(
				isset($_SESSION['form']['post']) OR
				isset($_SESSION['form']['product']))
			{
				# Set the default origin form's name.
				$origin_form='post';
				# Set the default session file index name.
				$file_index='FileID';
				if(isset($_SESSION['form']['product']))
				{
					# Set the default origin form's name.
					$origin_form='product';
				}
				# Set the post session file id.
				$_SESSION['form'][$origin_form][$file_index]=$file_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='file';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectFile

	/**
	 * setSession
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access	protected
	 */
	protected function setSession()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the File object and set it to a local variable.
			$file_obj=$populator->getFileObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Set the file's associated institution name to a variable.
			$institution_name=$file_obj->getInstitution();
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution_obj=new Institution();
			# Get the institution info via the institution name.
			$institution_obj->getThisInstitution($institution_name, FALSE);
			# Set the institution id to a variable.
			$institution_id=$institution_obj->getID();

			# Set the file's language to a variable.
			$language=$file_obj->getLanguage();
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Instantiate a new Language object.
			$language_obj=new Language();
			# Get the language info via the language name.
			$language_obj->getThisLanguage($language, FALSE);
			# Set the language id to a variable.
			$language_id=$language_obj->getID();

			# Set the file's publisher name to a variable.
			$publisher_name=$file_obj->getPublisher();
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher_obj=new Publisher();
			# Get the publisher info via the publisher name.
			$publisher_obj->getThisPublisher($publisher_name, FALSE);
			# Set the publisher id to a variable.
			$publisher_id=$publisher_obj->getID();

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['file']=
				array(
					'ID'=>$file_obj->getID(),
					'FormURL'=>$form_url,
					'Author'=>$file_obj->getAuthor(),
					'Availability'=>$file_obj->getAvailability(),
					'Categories'=>$file_obj->getCategories(),
					'ContID'=>$file_obj->getContID(),
					'Date'=>$file_obj->getDate(),
					'File'=>$file_obj->getFile(),
					'Institution'=>$institution_id,
					'Language'=>$language_id,
					'Location'=>$file_obj->getLocation(),
					'Premium'=>$file_obj->getPremium(),
					'Publisher'=>$publisher_id,
					'Title'=>$file_obj->getTitle(),
					'Unique'=>$populator->getUnique(),
					'Year'=>$file_obj->getYear()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End FileFormProcessor class.