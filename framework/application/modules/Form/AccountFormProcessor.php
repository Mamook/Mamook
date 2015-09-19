<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * AccountFormProcessor
 *
 * The AccountFormProcessor Class is used to create and process account add, edit, or delete forms.
 *
 */
class AccountFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processAccount
	 *
	 * Processes a submitted account.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 * @return	string
	 */
	public function processAccount($data, $max_size=7340032)
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
			# Get the AccountFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'AccountFormPopulator.php');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('account', 'account');

			# Instantiate a new instance of AccountFormPopulator.
			$populator=new AccountFormPopulator();
			# Populate the form and set the Account data members for this post.
			$populator->populateAccountForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

/*
			$display_delete_form=$this->processAccountDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
*/
			//$this->processAccountSelect();

			# Get the User object from the AccountFormPopulator object and set it to a variable for use in this method.
			$user_obj=$populator->getUserObject();

			# Set a variable to FALSE indicating that a CV file has not been uploaded.
			$uploaded_cv=FALSE;
			# Set a variable to FALSE indicating that the old CV file has not been moved.
			$moved_cv=FALSE;
			# Set a variable to FALSE indicating that an image file has not been uploaded.
			$uploaded_image=FALSE;
			# Create an empty variable to hold the new name of an uplaoded file.
			$new_image_name=NULL;
			# Set a variable to FALSE indicating that the old image file has not been moved.
			$moved_image=FALSE;
			# Set the user's id to a variable.
			$id=$user_obj->getID();
			# Set the user's address to a variable.
			$address=$user_obj->getAddress();
			# Set the user's address2 to a variable.
			$address2=$user_obj->getAddress2();
			# Set the user's bio to a variable.
			$bio=$user_obj->getBio();
			# Set the user's city to a variable.
			$city=$user_obj->getCity();
			# Set the user's country to a variable.
			$country=$user_obj->getCountry();
			# Set the user's CV to a variable.
			$cv=$user_obj->getCV();
			# Set the user's display name to a variable.
			$display_name=$user_obj->getDisplayName();
			# Set the user's email to a variable.
			$email=$user_obj->getEmail();
			# Set the user's first name to a variable.
			$first_name=$user_obj->getFirstName();
			# Set the user's associated image id to a variable.
			$image_filename=$user_obj->getImg();
			# Set the user's associated image title to a variable.
			$image_title=$user_obj->getImgTitle();
			# Set the user's interests to a variable.
			$interests=$user_obj->getInterests();
			# Set the user's last name to a variable.
			$last_name=$user_obj->getLastName();
			# Check if there is a WordPress installation.
			if(WP_INSTALLED===TRUE)
			{
				# Set the user's nickname to a variable.
				$nickname=$user_obj->getNickname();
			}
			# Set the user's phone to a variable.
			$phone=$user_obj->getPhone();
			# Set the user's region to a variable.
			$region=$user_obj->getRegion();
			# Set the site name to a vaiable.
			$site_name=$main_content->getSiteName();
			# Set the user's state to a variable.
			$state=$user_obj->getState();
			# Set the user's title to a variable.
			$title=$user_obj->getTitle();
			# Set the user's username to a variable.
			$username=$user_obj->getUsername();
			# Set the user's website to a variable.
			$website=$user_obj->getWebsite();
			# Set the user's zipcode to a variable.
			$zipcode=$user_obj->getZipcode();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['account']) && ($_POST['account']=='Add User' OR $_POST['account']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('account');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if there is a WordPress installation.
				if(WP_INSTALLED===TRUE)
				{
					# Validate if the nickname field was empty.
					$empty_nickname=$fv->validateEmpty('nickname', 'Please enter a Nickname that is at least 6 characters long.', 6, 64);
				}

				# Validate if the display name is empty.
				$empty_display=$fv->validateEmpty('display', 'Please enter a Display Name that is at least 2 characters long that will be the name that is display when you comment or post on the site.', 2, 250);
				# Check if the display name was not empty.
				if($empty_display===FALSE)
				{
					# Set whether or not the display name is unique to a variable.
					$unique_display=$user_obj->checkUnique('display', $display_name, ' AND `ID` != '.$db->quote($id));
					# Check if the display name is not unique.
					if($unique_display===FALSE)
					{
						# Set the error for display to the user.
						$fv->setErrors('The display name "'.$display_name.'" is already being used by somebody in the system, please choose a different name to display when you comment or post on the site.');
					}
				}

				# Set whether or not the email field is empty.
				$empty_email=$fv->validateEmpty('email', 'Please enter your email address.', 4, 100);
				# Check if the email field was not empty.
				if($empty_email===FALSE)
				{
					# Set whether or not the email is valid to a variable.
					$real_email=$fv->validateEmail('email', 'Please enter a valid email address.', TRUE);
					# Check if the email is valid.
					if($real_email===TRUE)
					{
						# Set whether or not the email is unique to a variable.
						$unique_email=$user_obj->checkUnique('email', $email, ' AND `ID` != '.$db->quote($id));
						# Check if the email is not unique.
						if($unique_email===FALSE)
						{
							# Set the error for display to the user.
							$fv->setErrors('An account using the email address "'.$email.'" already exists in the system, please use another.');
						}
					}
					else
					{
						# Unset the email in POST data.
						unset($_POST['email']);
					}
				}

				# Check if the was POST data sent. If so, check if it was the default value "http://" or if it was empty.
				if(isset($website) && ($website!='http://') && !empty($website))
				{
					# Set whether or not the website is a valid URL.
					$valid_url=$fv->validateURL('website', 'Please enter a valid URI in the Website field.');
					# Check if the URL is not valid.
					if($valid_url===FALSE)
					{
						# Unset the website in POST data.
						unset($_POST['website']);
					}
				}

				# Check if the was POST data sent.
				if(isset($_FILES['cv']) || isset($_FILES['image']))
				{
					# Get the Upload class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');

					# If the username is an email address, replace the "@" character.
					$safe_username=str_replace('@', '-', $username);

					# Check if an CV was uploaded and if there have been no errors so far.
					if(array_key_exists('cv', $_FILES) && ($fv->checkErrors()===FALSE))
					{
						# Create a variable to hold the name of the previous cv file.
						$old_cv=$cv;

						# Instantiate an Upload object.
						$upload_cv=new Upload($_FILES['cv']);

						# Create safe CV name based on the username.
						$clean_cv_filename='CV'.$safe_username.'.'.YEAR_MM_DD;

						# Check if the uploaded CV size is not NULL.
						if($upload_cv->getSize()!==NULL)
						{
							# Check if there was a previous CV file.
							if($old_cv!==NULL)
							{
								# Copy the previous file over to the tmp folder (We'll move it back if anything goes wrong.)
								if(rename(BODEGA.'cv'.DS.$old_cv, BASE_PATH.'tmp'.DS.$old_cv)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving the CV file, '.$old_cv.' to the temp folder for deletion.', E_RECOVERABLE_ERROR);
								}
								else
								{
									# Set the $moved_cv variable to TRUE so we know there is a previous file in the temp folder in case anything goes wrong.
									$moved_cv=TRUE;
								}
							}

							try
							{
								# Upload the document.
								$cv_upload=$upload_cv->uploadDoc(BODEGA.'cv'.DS, array('doc', 'docx', 'txt', 'rtf', 'ppt', 'pptx', 'pdf', 'odt'), $clean_cv_filename);

								# Reset the CV file name (ie: cv_file_name.mp4).
								$new_cv_name=$upload_cv->getName();
							}
							catch(Exception $e)
							{
								# Check if a previous file was moved to the temp folder.
								if($moved_cv===TRUE)
								{
									# Copy the previous file back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_cv, BODEGA.'cv'.DS.$old_cv)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving the CV file, '.$old_cv.' back to the bodega from the temp folder.', E_RECOVERABLE_ERROR);
									}
								}
								throw $e;
							}

							# Check for errors.
							if($upload_cv->checkErrors()===TRUE)
							{
								# Remove uploaded CV file.
								$upload_cv->deleteFile(BODEGA.'cv'.DS.$new_cv_name);
								# Get any errors.
								$cv_errors=$upload_cv->getErrors();
								# Loop through the errors.
								foreach($cv_errors as $cv_error)
								{
									# Set each error to our current error array.
									$fv->setErrors($cv_error);
								}
								# Check if a previous file was moved to the temp folder.
								if($moved_cv===TRUE)
								{
									# Copy the file back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_cv, BODEGA.'cv'.DS.$old_cv)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving the CV file, '.$old_cv.' back to the bodega from the temp folder.', E_RECOVERABLE_ERROR);
									}
									# Reset the $moved_cv variable to FALSE.
									$moved_cv=FALSE;
								}
							}
							# Check if the upload was successful.
							elseif($cv_upload===TRUE)
							{
								# Set the variable that remembers that a CV file has been uploaded to TRUE (in case we need to remove the file).
								$uploaded_cv=TRUE;
							}
						}
					}
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
							# Check if there was a previous user image.
							if($old_img!==NULL)
							{
								# Copy the original image over to the tmp folder (We'll move it back if anything goes wrong.)
								if(rename(IMAGES_PATH.'original'.DS.$old_img, BASE_PATH.'tmp'.DS.'original.'.$old_img)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving '.$username.'\'s original image, '.$old_img.' to the temp folder for deletion.', E_RECOVERABLE_ERROR);
								}
								# Copy the user image over to the tmp folder (We'll move it back if anything goes wrong.)
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
								# Upload original thumbnail.
								$image_upload=$upload_image->uploadImage(IMAGES_PATH.'original'.DS, IMAGES_PATH, $safe_username, $max_size, TRUE, 230, 300, 75, TRUE);

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
									# Copy the user image back to it's destination directory.
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
									# Copy the user image back to it's destination directory.
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

					# Check if there was an uploaded CV file.
					if($uploaded_cv===TRUE)
					{
						# Remove uploaded CV file.
						$upload_cv->deleteFile(BODEGA.'cv'.DS.$new_cv_name);
					}
					# Check if a previous cv file was moved to the temp folder.
					if($moved_cv===TRUE)
					{
						# Copy the file back to it's destination directory.
						if(rename(BASE_PATH.'tmp'.DS.$old_cv, BODEGA.'cv'.DS.$old_cv)===FALSE)
						{
							# Create a message and send them to the "starting" point.
							throw new Exception('There was an error moving the CV file, '.$old_cv.' back to the bodega from the temp folder.', E_RECOVERABLE_ERROR);
						}
					}
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
						# Copy the user image back to it's destination directory.
						if(rename(BASE_PATH.'tmp'.DS.$old_img, IMAGES_PATH.$old_img)===FALSE)
						{
							# Create a message and send them to the "starting" point.
							throw new Exception('There was an error moving '.$username.'\'s image, '.$old_img.' to its image folder from the temp folder.', E_RECOVERABLE_ERROR);
						}
					}
				}
				else
				{
					# Add user.
/*
					# Create the default value for the message action.
					$message_action='added';
					# Create the default sql as an INSERT and set it to a variable.
					$sql='INSERT INTO `'.DBPREFIX.'users` ('.
						'`title`, '.
						'`image`, '.
						((!empty($location)) ? ' `location`, ' : '').
						((!empty($category_ids)) ? ' `category`, ' : '').
						((!empty($description)) ? ' `description`, ' : '').
						'`last_edit`, '.
						(($hide===NULL) ? ' `hide`, ' : '').
						' `contributor`'.
						') VALUES ('.
						$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).', '.
						$db->quote($db->escape($new_name)).', '.
						((!empty($location)) ? ' '.$db->quote($db->escape($location)).', ' : '').
						((!empty($category_ids)) ? ' '.$db->quote($category_ids).', ' : '').
						((!empty($description)) ? ' '.$db->quote($db->escape($description)).', ' : '').
						$db->quote($db->escape($last_edit)).','.
						((!empty($hide)) ? '0,' : '').
						' '.$db->quote($contributor_id).
						')';
*/

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
								'address'=>$address,
								'address2'=>$address2,
								'bio'=>$bio,
								'city'=>$city,
								'country'=>$country,
								'cv'=>(!empty($new_cv_name) ? $new_cv_name : $cv),
								'display'=>$display_name,
								'email'=>$email,
								'fname'=>$first_name,
								'img'=>(!empty($new_image_name) ? $new_image_name : $image_filename),
								'img_title'=>$image_title,
								'interests'=>$interests,
								'lname'=>$last_name,
								'phone'=>$phone,
								'region'=>$region,
								'title'=>$title,
								'state'=>$state,
								'website'=>$website,
								'zipcode'=>$zipcode
								);
							# Update the User's data in the `users` table.
							$update_user=$user_obj->updateUser($where, $field_value);

							# Check if there is a WordPress installation.
							if(WP_INSTALLED===TRUE)
							{
								# get the WordPressUser class.
								require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
								# Instantiate a new WordPressUser object.
								$wp_user=new WordPressUser();
								# Get the WordPress User's ID and set ti to a variable.
								$wp_id=$wp_user->getWP_UserID($username);
								# Update nickname.
								$update_nickname=$wp_user->updateWP_Nickname($wp_id, $nickname);
								# Update display name.
								$update_display=$wp_user->updateWP_DisplayName($wp_id, $display_name);
							}
							# Check if the query was successful.
							if((isset($cv_upload) && $cv_upload>0) || (isset($image_upload) && $image_upload>0) || $update_user>0 || (isset($update_person) && $update_person>0) || (isset($update_nickname) && ($update_nickname>0 || $update_display>0)))
							{
								# Unset the CMS session data.
								unset($_SESSION['form']['account']);
								$name=(empty($display_name) ? $username : $display_name);
								$this->redirectAccount($name, $message_action);
							}
							else
							{
								# Check if there was an uploaded CV file.
								if($uploaded_cv===TRUE)
								{
									# Remove uploaded CV file.
									$upload_cv->deleteFile(BODEGA.'cv'.DS.$new_cv_name);
								}
								if($moved_cv===TRUE)
								{
									# Copy the file back to it's destination directory.
									if(rename(BASE_PATH.'tmp'.DS.$old_cv, BODEGA.'cv'.DS.$old_cv)===FALSE)
									{
										# Create a message and send them to the "starting" point.
										throw new Exception('There was an error moving the CV file, '.$old_cv.' back to the bodega from the temp folder.', E_RECOVERABLE_ERROR);
									}
								}
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
									unset($_SESSION['form']['account']);
									# Set a nice message for the user in a session.
									$_SESSION['message']="The account's record was unchanged.";
									# Redirect the user to the page they were on.
									$this->redirectNoDelete();
								}
							}
						}
						# There was an exception error. Delete files, unset session and return error message.
						catch(Exception $e)
						{
							# Check if there was an uploaded CV file.
							if($uploaded_cv===TRUE)
							{
								# Remove uploaded CV file.
								$upload_cv->deleteFile(BODEGA.'cv'.DS.$new_cv_name);
							}
							if($moved_cv===TRUE)
							{
								# Copy the file back to it's destination directory.
								if(rename(BASE_PATH.'tmp'.DS.$old_cv, BODEGA.'cv'.DS.$old_cv)===FALSE)
								{
									# Create a message and send them to the "starting" point.
									throw new Exception('There was an error moving the CV file, '.$old_cv.' back to the bodega from the temp folder.', E_RECOVERABLE_ERROR);
								}
							}
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
	} #==== End -- processAccount

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processAccountDelete
	 *
	 * Removes an user from the `users` table along with their CV file and profile image from the system. A wrapper method for the deleteUser method in the User class.
	 *
	 * @access	private
	 */
/*
	private function processAccountDelete()
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
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_account']) && ($_POST['delete_account']==='delete')))
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
								unset($_SESSION['form']['audio']);
								# Delete the audio from the database and set the returned value to a variable.
								$deleted=$audio_obj->deleteAudio($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectAudio($audio_name, 'deleted');
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
	} #==== End -- processAccountDelete
*/

	/**
	 * redirectAccount
	 *
	 * Redirect the user to the appropriate page.
	 *
	 * @param	string $account_name
	 * @param	string $action
	 * @access	private
	 */
	private function redirectAccount($account_name, $action)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the User object and set it to a local variable.
			$user_obj=$populator->getUserObject();
			# Get the data for the new account.
			$user_obj->findUserData($account_name);
			# Get the new account's id.
			$account_id=$user_obj->getID();
			# Remove the account session.
			unset($_SESSION['form']['account']);
			# Set a nice message for the user in a session.
			$_SESSION['message']='The account "'.$account_name.'" was successfully '.$action.'!';
			# Check if there is a post or content session.
			if(isset($_SESSION['form']['account']))
			{
				# Set the default origin form's name.
				$origin_form='account';
				# Set the default session account index name.
				$account_index='ID';
				# Set the post session account id.
				$_SESSION['form'][$origin_form][$account_index]=$account_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='account';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectAccount

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
			# Get the User object and set it to a local variable.
			$user_obj=$populator->getUserObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['account']=
				array(
					'Address'=>$user_obj->getAddress(),
					'Address2'=>$user_obj->getAddress2(),
					'Bio'=>$user_obj->getBio(),
					'City'=>$user_obj->getCity(),
					'Country'=>$user_obj->getCountry(),
					'CV'=>$user_obj->getCV(),
					'DisplayName'=>$user_obj->getDisplayName(),
					'Email'=>$user_obj->getEmail(),
					'FirstName'=>$user_obj->getFirstName(),
					'FormURL'=>$form_url,
					'ID'=>$user_obj->getID(),
					'Img'=>$user_obj->getImg(),
					'ImgTitle'=>$user_obj->getImgTitle(),
					'Interests'=>$user_obj->getInterests(),
					'LastName'=>$user_obj->getLastName(),
					'Nickname'=>$user_obj->getNickname(),
					'Phone'=>$user_obj->getPhone(),
					'Region'=>$user_obj->getRegion(),
					'State'=>$user_obj->getState(),
					'Title'=>$user_obj->getTitle(),
					'Username'=>$user_obj->getUsername(),
					'Website'=>$user_obj->getWebsite(),
					'Zipcode'=>$user_obj->getZipcode()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End AccountFormProcessor class.