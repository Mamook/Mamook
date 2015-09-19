<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * StaffFormProcessor
 *
 * The StaffFormProcessor Class is used to create and process staff add, edit, or delete forms.
 *
 */
class StaffFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processStaff
	 *
	 * Processes a submitted staff form.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 * @return	string
	 */
	public function processStaff($data, $max_size=7340032)
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
			# Get the StaffFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'StaffFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populateStaffForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			//$this->loseSessionData('staff_desc');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('staff', 'staff');

			# Instantiate a new instance of StaffFormPopulator.
			$populator=new StaffFormPopulator();
			# Populate the form and set the Staff data members for this post.
			$populator->populateStaffForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

/*
			$display_delete_form=$this->processStaffDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processStaffBack();
*/
			//$this->processStaffSelect();
			$this->processStaffFocus();

			# Get the Staff object from the StaffFormPopulator object and set it to a variable for use in this method.
			$staff_obj=$populator->getStaffObject();

			# Set a variable to FALSE indicating that an image file has not been uploaded.
			$uploaded_image=FALSE;
			# Create an empty variable to hold the new name of an uplaoded file.
			$new_image_name=NULL;
			# Set a variable to FALSE indicating that the old image file has not been moved.
			$moved_image=FALSE;
			# Set the staff's affiliation to a variable.
			$affiliation=$staff_obj->getAffiliation();
			# Set the staff's archive to a variable.
			$archive=$staff_obj->getArchive();
			# Set the staff's credentials to a variable.
			$credentials=$staff_obj->getCredentials();
			# Set the staff's first name to a variable.
			$first_name=$staff_obj->getFirstName();
			# Set the staff's id to a variable.
			$id=$staff_obj->getID();
			# Set the staff's associated image id to a variable.
			$image_filename=$staff_obj->getImage();
			# Set the staff's associated image title to a variable.
			$image_title=$staff_obj->getImageTitle();
			# Set the staff's last name to a variable.
			$last_name=$staff_obj->getLastName();
			# Set the staff's middle name to a variable.
			$middle_name=$staff_obj->getMiddleName();
			# Set the staff's new positions to a variable.
			$new_position=$staff_obj->getNewPosition();
			# Set the staff's position to a variable.
			$position=$staff_obj->getPosition();
			# Set the staff's region to a variable.
			$region=$staff_obj->getRegion();
			# Set the staff's bio to a variable.
			$text=$staff_obj->getText();
			# Set the staff's title to a variable.
			$title=$staff_obj->getTitle();
			# Set the staff's user ID to a variable.
			$user_id=$staff_obj->getUser();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['staff']) && ($_POST['staff']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the staff to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('staff');

				# Instantiate FormValidator object
				$fv=new FormValidator();

				# Check if there is a record in the `staff` table associated with this staff.
				if($staff_obj->getID()!==NULL)
				{
					# Validate if the first name is empty.
					$empty_fname=$fv->validateEmpty('fname', 'Please enter a First Name that is at least 2 characters long.', 2, 64);
					# Validate if the last name is empty.
					$empty_lname=$fv->validateEmpty('lname', 'Please enter a Last Name that is at least 2 characters long.', 2, 64);
				}

				# Check if the was POST data sent.
				if(isset($_FILES['image']))
				{
					# Get the Upload class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');

					# Check if an image was uploaded and if there have been no errors so far.
					if(array_key_exists('image', $_FILES) && ($fv->checkErrors()===FALSE))
					{
						# Create an empty variable to hold the name of the previous image file.
						$old_img=NULL;

						# Check if the image name is not the default value.
						if($image_filename!='default-avatar.png')
						{
							# Set the previous image file name to the variable.
							$old_img=$image_filename;
						}

						# Instantiate an Upload object.
						$upload_image=new Upload($_FILES['image']);

						# Check if the uploaded image size is not NULL.
						if($upload_image->getSize()!==NULL)
						{
							# Check if there was a previous staff image.
							if($old_img!==NULL)
							{
								# Copy the original image over to the tmp folder (We'll move it back if anything goes wrong.)
								if(rename(IMAGES_PATH.'original'.DS.$old_img, BASE_PATH.'tmp'.DS.'original.'.$old_img)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the temp folder for deletion.', E_RECOVERABLE_ERROR);
								}
								# Copy the staff image over to the tmp folder (We'll move it back if anything goes wrong.)
								if(rename(IMAGES_PATH.$old_img, BASE_PATH.'tmp'.DS.$old_img)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to the temp folder for deletion.', E_RECOVERABLE_ERROR);
								}
								# Set the $moved_image variable to TRUE so we know there is a previous image file in the temp folder in case anything goes wrong.
								$moved_image=TRUE;
							}

							try
							{
								# Append ".staff" to the end of the $username for the staff image.
								$staff_image_name=$first_name.'.'.$last_name.'.staff';
								# Upload original thumbnail.
								$image_upload=$upload_image->uploadImage(IMAGES_PATH.'original'.DS, IMAGES_PATH, $staff_image_name, $max_size, TRUE, 230, 300, 75, TRUE);

								# Reset the image's new name.
								$new_image_name=$upload_image->getName();
							}
							catch(Exception $e)
							{
								# Check if a previous image file was moved to the temp folder.
								if($moved_image===TRUE)
								{
									# Copy the original image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.'original.'.$old_img, IMAGES_PATH.'original'.DS.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the original folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
									# Copy the staff image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
								}
								throw $e;
							}

							# Check for errors.
							if($upload_image->checkErrors()===TRUE)
							{
								# Remove uploaded image from the Images folder and the Original folder.
								$upload_image->deleteFile(IMAGES_PATH.$new_image_name);
								$upload_image->deleteFile(IMAGES_PATH.'original'.DS.$new_image_name);
								# Get any errors.
								$image_errors=$upload_image->getErrors();
								# Loop through the errors.
								foreach($image_errors as $image_error)
								{
									# Set each error to our current error array.
									$fv->setErrors($image_error);
								}
								# Check if a previous image file was moved to the temp folder.
								if($moved_image===TRUE)
								{
									# Copy the original image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.'original.'.$old_img, IMAGES_PATH.'original'.DS.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the original folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
									# Copy the staff image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
								}
								# Reset the $moved_image variable to FALSE.
								$moved_image=FALSE;
							}
							# Check if the upload was successful.
							elseif($image_upload===TRUE)
							{
								# Set the variable that remembers that an image has been uploaded to TRUE (in case we need to remove the image).
								$uploaded_image=TRUE;
							}
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

					# Check if there was an uploaded image file.
					if($uploaded_image===TRUE)
					{
						# Remove uploaded image from the Images folder and the Original folder.
						$upload_image->deleteFile(IMAGES_PATH.$new_image_name);
						$upload_image->deleteFile(IMAGES_PATH.'original'.DS.$new_image_name);
					}
					# Check if a previous image file was moved to the temp folder.
					if($moved_image===TRUE)
					{
						# Copy the original image back to it's destination directory.
						if(rename(BASE_PATH.'tmp'.DS.'original.'.$old_img, IMAGES_PATH.'original'.DS.$old_img)===FALSE)
						{
							# Create a message and send them to the "starting" point.
							throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the original folder from the temp folder.', E_RECOVERABLE_ERROR);
						}
						# Copy the staff image back to it's destination directory.
						if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
						{
							# Create a message and send them to the "starting" point.
							throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
						}
					}
				}
				else
				{
					# Check if this is an UPDATE.
					if(!empty($id))
					{
						try
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Create the $where variable.
							$where=array('ID'=>$id);
							# Create $field_value array.
							$field_value=array(
								'affiliation'=>$affiliation,
								'archive'=>$archive,
								'credentials'=>$credentials,
								'fname'=>$first_name,
								'image'=>(!empty($new_image_name) ? $new_image_name : $image_filename),
								'image_title'=>$image_title,
								'lname'=>$last_name,
								'mname'=>$middle_name,
								'position'=>$position,
								'region'=>$region,
								'text'=>$text,
								'title'=>$title
								);
							# Update the person's data in the `staff` table.
							$update_person=$staff_obj->updateStaff($where, $field_value);

							# Check if the query was successful.
							if((isset($image_upload) && $image_upload>0) || (isset($update_person) && $update_person>0))
							{
								# Unset the session data.
								unset($_SESSION['form']['staff']);
								unset($_SESSION['form']['staff_desc']);
								# Redirect the staff to the page they were on.
								$this->redirectStaff($message_action);
							}
							else
							{
								# Check if there was an uploaded image file.
								if($uploaded_image===TRUE)
								{
									# Remove uploaded image from the Images folder and the Original folder.
									$upload_image->deleteFile(IMAGES_PATH.$new_image_name);
									$upload_image->deleteFile(IMAGES_PATH.'original'.DS.$new_image_name);
								}
								if($moved_image===TRUE)
								{
									# Copy the original image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.'original.'.$old_img, IMAGES_PATH.'original'.DS.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the original folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
									# Copy the user image back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
									}
								}
								if(!empty($id))
								{
									# Unset the CMS session data.
									unset($_SESSION['form']['staff']);
									unset($_SESSION['form']['staff_desc']);
									# Set a nice message for the staff in a session.
									$_SESSION['message']="The staff's record was unchanged.";
									# Redirect the staff to the page they were on.
									$this->redirectNoDelete();
								}
							}
						}
						# There was an exception error. Delete files, unset session and return error message.
						catch(Exception $e)
						{
							# Check if there was an uploaded image file.
							if($uploaded_image===TRUE)
							{
								# Remove uploaded image from the Images folder and the Original folder.
								$upload_image->deleteFile(IMAGES_PATH.$new_image_name);
								$upload_image->deleteFile(IMAGES_PATH.'original'.DS.$new_image_name);
							}
							if($moved_image===TRUE)
							{
								# Copy the original image back to it's destination directory.
								if(rename(BASE_PATH.'tmp'.DS.'original.'.$old_img, IMAGES_PATH.'original'.DS.$old_img)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the original folder from the temp folder.', E_RECOVERABLE_ERROR);
								}
								# Copy the user image back to it's destination directory.
								if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
								}
							}
							throw $e;
						}
					}
				}
			}
			return NULL;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processStaff

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processStaffBack
	 *
	 * Processes a submitted form indicating that the Staff should be sent back to the form that sent from.
	 *
	 * @access	private
	 */
	private function processStaffBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get staff info.
			$indexes=array(
				'staff'
			);
			# Set the resource value.
			$resource='staff_desc';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processStaffBack

	/**
	 * processStaffDelete
	 *
	 * Removes an staff from the `staff` table along with their profile image from the system.
	 * A wrapper method for the deleteStaff method in the Staff class.
	 *
	 * @access	private
	 */
/*
	private function processStaffDelete()
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
			# Check if the user's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['user']) && isset($_GET['delete']))
			{
				# Check if the passed audio id is an integer.
				if($validator->isInt($_GET['user'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_staff']) && ($_POST['delete_staff']==='delete')))
					{
						# Get the Subcontent class. With this class, the Audio object can be accessed as well as the SubContent.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this audio and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisAudio($_GET['audio'], TRUE);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the Audio object to a local variable.
							$audio_obj=$subcontent->getAudioObj();
							# Set the audio name to a local variable.
							$audio_name=$audio_obj->getFileName();
							# Set the "cleaned id to a local variable.
							$id=$subcontent->getAudioID();
							# Get all subcontent with this audio associated.
							$subcontent_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`audio` = '.$db->quote($id));
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
							# Check if this user still has access to delete this audio.
							if($access===TRUE)
							{
								if(($subcontent_returned===TRUE))
								{
									try
									{
										# Remove the audio from all `subcontent` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'subcontent` '.
											'SET '.
											'`'.DBPREFIX.'subcontent`.`audio` = NULL '.
											'WHERE '.
											'`'.DBPREFIX.'subcontent`.`audio` = '.$db->quote($id));
										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The audio "'.$audio_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this audio removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the audio "'.$audio_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['staff']);
								# Delete the audio from the database and set the returned value to a variable.
								$deleted=$audio_obj->deleteStaff($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectStaff($audio_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The audio "'.$audio_name.'" (id: '.$id.') was NOT deleted from the audio list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this audio removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The audio was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this audio, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The audio was not found.';
							# Redirect the user back to the page.
							$this->redirectNoDelete('audio');
						}
					}
					# Check if the form has been submitted to NOT delete the audio.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_audio']) && ($_POST['delete_audio']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The audio was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this audio and request confirmation from the user with the appropriate warnings.
						require Utility::locateFile(TEMPLATES.'forms'.DS.'delete_form.php');
						return $display;
					}
				}
				# Redirect the user to the default redirect location. They have no business trying to change the delete param value!
				$doc->redirect(DEFAULT_REDIRECT);
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processStaffDelete
*/

	/**
	 * processStaffFocus
	 *
	 * Processes a submitted staff form for position descriptions.
	 *
	 * @access	private
	 * @return	string
	 */
	private function processStaffFocus()
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the StaffFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'StaffFormPopulator.php');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('staff_desc', 'staff_desc');

			$this->processStaffBack();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['staff_desc']) && ($_POST['staff_desc']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSessionFocus();

				# Set a nice message for the user in a session.
				$_SESSION['message']='The position desciptions were successfully added!';
				# Redirect the user to the page they were on with no POST or GET data.
				$doc->redirect(rtrim(COMPLETE_URL, '&add_desc'));
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processStaffFocus

	/**
	 * redirectStaff
	 *
	 * Redirect the user to the appropriate page.
	 *
	 * @param	string $message_action
	 * @access	private
	 */
	private function redirectStaff($message_action)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Staff object and set it to a local variable.
			$staff_obj=$populator->getStaffObject();
			# Remove the staff session.
			unset($_SESSION['form']['staff']);
			# Create the staff name variable using their first and last name.
			$staff_name=$staff_obj->getFirstName().' '.$staff_obj->getLastName();
			# Set a nice message for the staff in a session.
			$_SESSION['message']='The staff "'.$staff_name.'" was successfully '.$message_action.'!';
			# Check if there is a post or content session.
			if(isset($_SESSION['form']['staff']) || isset($_SESSION['form']['staff_desc']))
			{
				# Set the default origin form's name.
				$origin_form='staff';
				# Set the default session staff index name.
				$staff_index='ID';
				if(isset($_SESSION['form']['staff_desc']))
				{
					# Set the form's name as "staff_desc".
					$origin_form='staff_desc';
					# Set the post session staff index name.
					$staff_index='Staff';
					# Set the content session staff name.
					$staff_value=$staff_name;
				}
				# Set the post session staff id.
				$_SESSION['form'][$origin_form][$staff_index]=$staff_id;
				# Redirect the staff to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			/*
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='staff';
			}
			# Redirect the staff to the page they were on.
			$this->redirectNoDelete($remove);
			*/
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectStaff

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
			# Get the Staff object and set it to a local variable.
			$staff_obj=$populator->getStaffObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['staff']=
				array(
					'Affiliation'=>$staff_obj->getAffiliation(),
					'Archive'=>$staff_obj->getArchive(),
					'Credentials'=>$staff_obj->getCredentials(),
					'FirstName'=>$staff_obj->getFirstName(),
					'FormURL'=>$form_url,
					'ID'=>$staff_obj->getID(),
					'Image'=>$staff_obj->getImage(),
					'ImageTitle'=>$staff_obj->getImageTitle(),
					'LastName'=>$staff_obj->getLastName(),
					'MiddleName'=>$staff_obj->getMiddleName(),
					'NewPosition'=>$staff_obj->getNewPosition(),
					'Position'=>$staff_obj->getPosition(),
					'Region'=>$staff_obj->getRegion(),
					'Text'=>$staff_obj->getText(),
					'Title'=>$staff_obj->getTitle(),
					'User'=>$staff_obj->getUser()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/**
	 * setSessionFocus
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access	private
	 */
	private function setSessionFocus()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Staff object and set it to a local variable.
			$staff_obj=$populator->getStaffObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=rtrim(FormPopulator::getCurrentURL(), '&add_desc');
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			$position_json_decoded=json_decode($staff_obj->getPosition(), TRUE);

			foreach($position_json_decoded as $position_key=>$position)
			{
				# Loop through the position's.
				foreach($_POST['position_desc'] as $key=>$value)
				{
					if($value['position']==$position['position'])
					{
						$position_json_decoded[$position_key]['description']=$value['description'];
					}
				}
			}

			# JSON encode the array.
			$position_encoded=json_encode($position_json_decoded, JSON_FORCE_OBJECT);

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['staff_desc']=
				array(
					'FormURL'=>$form_url,
					'Position'=>$position_encoded
				);
			$_SESSION['form']['staff']['Position']=$position_encoded;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSessionFocus

	/*** End private methods ***/

} # End StaffFormProcessor class.