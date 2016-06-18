<?php /* framework/application/modules/Form/EmailFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * EmailFormProcessor
 *
 * The EmailFormProcessor Class is used to create and process emailing forms.
 *
 */
class EmailFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * editFormMailIni
	 *
	 * Adds email addresses to the FormMail.ini file.
	 *
	 * @param 	string $value			A string of comma separated email addresses to add to a new "Recipient".
	 * @param 	string $name
	 * @param	bool $reset				Reset the formmail.ini file. TRUE or FALSE.
	 * @access	public
	 */
	public function editFormMailIni($value, $name, $reset=FALSE)
	{
		if(is_writable(DATA_FILES.'formmail.ini'))
		{
			# Get the contents of the ini file and set it to a string.
			$ini_file=file_get_contents(DATA_FILES.'formmail.ini');
			# Open the ini file for writing.
			$handle=fopen(DATA_FILES.'formmail.ini', 'w+');
			# Check if the ini file should be reset.
			if($reset!==FALSE)
			{
				# Remove all Custom email addresses for $name.
				$ini_file=preg_replace('/'.$name.'\ \=\ \"(.*)?\"[\n\r]/i', '', $ini_file);
			}
			else
			{
				# Replace all the "@" symbols in the email addresses with "_form_" for the Mangle feature in FormMail.
				$emails=str_replace('@', '_form_', $value);
				# Create a string with the email addresses.
				$string=$name.' = "'.$emails.'"'."\n";
				# Find $name in the ini content.
				if(strpos($ini_file, $name)!==FALSE)
				{
					# Add the Custom emails to the current ini file contents.
					$ini_file=preg_replace('/'.$name.'\ \=\ \"([.*^\"])?\"\n/i', $string, $ini_file);
				}
				else
				{
					# Add the Custom emails to the current ini file contents.
					$ini_file=preg_replace('/\[email_addresses\]\n/', "[email_addresses]\n".$string, $ini_file);
				}
			}
			# Write to the ini file.
			$file_written=fwrite($handle, $ini_file);
			# Close the file.
			fclose($handle);
		}
		else
		{
			throw new Exception('The file "formmail.ini" is not writeable.', E_USER_WARNING);
		}
	} #==== End -- editFormMailIni

	/**
	 * processEmail
	 *
	 * Processes a submitted email form.
	 *
	 * @param	array $data				An array of values tp populate the form with.
	 * @param	array $allowed_types	An array of allowed file types.
	 * @access	public
	 * @return	string
	 */
	public function processEmail($data=array(), $allowed_types=NULL)
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Bring the Content class into scope.
			global $main_content;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			# Get the EmailFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'EmailFormPopulator.php');
			# Instantiate a new instance of EmailFormPopulator.
			$populator=new EmailFormPopulator();
			# Populate the form and set the Email data members for this post.
			$populator->populateEmailForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# Get the Email object and set it to a local variable.
			$email=$populator->getEmailObject();

			# Set the attachment to a variable.
			$attachment=$email->getAttachment();
			# Set the template to a variable.
			$confirmation_template=$email->getConfirmationTemplate();
			# Set the email page to a variable.
			$email_page=$email->getEmailPage();
			# Set the html to a variable.
			$is_html=$email->getIsHTML();
			# Set the max file size to a variable
			$max_size=$email->getMaxFileSize();
			# Set the message to a variable.
			$message=$email->getMessage();
			# Set the recipients to a variable.
			$recipients=$email->getRecipients();
			# Set the sender email to a variable.
			$sender_email=$email->getSenderEmail();
			# Set the sender name to a variable.
			$sender_name=$email->getSenderName();
			# Set the subject to a variable.
			$subject=$email->getSubject();
			# Set the template to a variable.
			$template=$email->getTemplate();
			# Set a variable to FALSE indicating that an attachment has not been uploaded.
			$uploaded_document=FALSE;

			# Check if the allowed upload files types have been defined.
			if($allowed_types===NULL)
			{
				# Set the allowed types with the most common audio, video, and document file types.
				$allowed_types=array('doc', 'docx', 'pdf', 'txt', 'rtf', 'jpg', 'gif', 'png', 'mp3', 'm4a', 'mp4', 'm4v', 'mpeg', 'mpg', 'mpe', 'mov', 'avi', 'wmv', '3gp', 'odt');
			}

			# Check if the maximum file size for uploads has been defined.
			if($max_size===NULL)
			{
				# Default is 7 Megabytes (7340032 bytes).
				$max_size=7340032;
			}

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['send']) && ($_POST['send']==='Send Email')))
			{
				# Instantiate a new FormValidator object.
				$fv=new FormValidator();
				if(isset($email_page) && $email_page='email_users')
				{
					# Validate the passed user's name.
					$empty_to=$fv->validateEmpty('to', 'Enter a recipient.', 2);
				}
				# Validate the passed user's name.
				$empty_sender_name=$fv->validateEmpty('realname', 'Enter your name.', 2);
				# Validate the passed user's email.
				$valid_sender_email=$fv->validateEmail('email', 'Please enter a valid email address.', TRUE);
				# Validate the passed email subject.
				$empty_subject=$fv->validateEmpty('subject', 'Please enter a subject.', 2, 256);
				# Validate the passed email message.
				$empty_message=$fv->validateEmpty('mesg', 'Please enter a message.', 2, 10000);

				# Check if the local variable for the file is empty.
				if($attachment!==NULL)
				{
					# Check if the file was successfully uploaded.
					if(((is_uploaded_file($attachment['tmp_name'])===TRUE) && (($attachment['error']!==UPLOAD_ERR_NO_FILE) OR ($attachment['error']!==4))))
					{
						# Get the FileHandler class.
						require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
						# Instantiate a new FileHandler object.
						$fh=new FileHandler();
						# Check if the filename is acceptable.
						if($fh->checkFileName($attachment['name'])!==TRUE)
						{
							# Set the error.
							$fv->setErrors($fh->checkFileName($attachment['name']));
						}
						# Check if the file's size is within acceptanble parameters.
						if($attachment['size']>$max_size)
						{
							# Set the error.
							$fv->setErrors('The file you uploaded exceeds the allowed file size ('.(($max_size/1024)/1024).' Megabytes.)');
						}
						# Check if the file's type is within acceptanble parameters.
						if($fh->checkFileType($attachment['name'], $allowed_types)!==TRUE)
						{
							# Set the error.
							$fv->setErrors('The file type you uploaded is not allowed. Please use a different file format or contact the person to whom you are attempting to send the file to and make arrangements to send the file to them via another route.');
						}
						# Check if the file is an image.
						if(($fh->getExtension()!==FALSE) && (in_array($fh->getExtension(), $fh->getGDSupportedImageTypes())))
						{
							# Get the file extension of the image from the file name.
							$file_ext=$fh->getExtension();
							# Get the image info and set the image data members.
							$fh->getImageInfo($attachment['tmp_name']);
							# Get the file extension of the image from the IMAGETYPE_XXX constant.
							$image_ext=$fh->getImageTypeExtenstion($fh->getImageType(), FALSE);
							# Make sure the image type and the extension match (jpeg image type matches the jpg extension.)
							if((($image_ext=='jpeg') && ($file_ext=='jpg')) || ($image_ext==$file_ext))
							{
								# Set the image type and the extension match to TRUE.
								$ext_match=TRUE;
							}
							else
							{
								# We don't have a match.
								$fv->setErrors('The file you uploaded is invalid.');
							}
						}
					/*
						# Check if there have been no errors so far.
						if($fv->checkErrors()===FALSE)
						{
							# Get the Upload class.
							require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
							# Instantiate a new Upload object.
							$upload=new Upload($_FILES['file']);

							# Check if the uploaded file size is not NULL.
							if($upload->getSize()!==NULL)
							{
								# Get the FileHandler class.
								require_once (MODULES.'FileHandler'.DS.'FileHandler.php');
								# Instantiate the new FileHandler object.
								$file_handler=new FileHandler();
								# Create safe image name based on the title.
								$clean_filename=$file_handler->cleanFilename($subject);

								try
								{
									# Upload the document.
									$document_upload=$upload->uploadFile(TEMP, array('doc', 'docx', 'txt', 'rtf', 'ppt', 'pptx', 'pdf', 'odt', 'images'), TEMP, $clean_filename, 104857600, FALSE);
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
									$upload->deleteFile(TEMP.$new_name);
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
									# Set the name of the document to the $document variable.
									$document=$upload->getName();
									# Set the variable that remembers that a file has been uploaded to TRUE (in case we need to remove the file).
									$uploaded_document=TRUE;
								}
							}
						}
					*/
					}
				}
				# check for errors
				if($fv->checkErrors())
				{
					# Display errors.
					$alert_title='';
					$error='<h3>Resubmit the form after correcting the following errors:</h3>';
					$error.=$fv->displayErrors();
					$doc->setError($error);
				}
				else
				{
					if(isset($email_page) && $email_page='email_users')
					{
						# Make array with recipients.
						$recipients_array=explode(',', $recipients);

						# Create new array.
						$new_recipients=array();
						# Loop through recipients.
						foreach($recipients_array as $recipients_key=>$recipients_value)
						{
							if(defined($recipients_value))
							{
								# Convert the recipients to pre-defined constants and assign them to the new array.
								$new_recipients[].=constant($recipients_value);
							}
							else
							{
								$new_recipients[].=$recipients_value;
							}
						}
						# Seperate the constants (now integers from user_definitions) with a space.
						$implode_recipients=trim(implode(' ', $new_recipients));
						# First we turn $implode_recipients into an array using the space separation.
						# 	Then we look for unique values in the array.
						# 	Lastly, we convert the array into a string separated by a comma (,).
						$recipients=implode(',', array_unique(explode(' ', $implode_recipients)));

						# Create an array with email data.
						$email_data=array(
							'Environment'=>DOMAIN_NAME,
							'DevEnvironment'=>DEVELOPMENT_DOMAIN,
							'StagingEnvironment'=>STAGING_DOMAIN,
							'Attachment'=>$attachment,
							'ConfirmationTemplate'=>$confirmation_template,
							'IsHTML'=>$is_html,
							'MaxFileSize'=>$max_size,
							'Message'=>$message,
							'Recipients'=>$recipients,
							'SenderEmail'=>$sender_email,
							'SenderName'=>$sender_name,
							'SiteName'=>$main_content->getSiteName(),
							'Subject'=>$subject,
							'Template'=>$template
						);

						# Get CommandLine class.
						require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');
						# Instantiate the new CommandLine object.
						$commandline_obj=new CommandLine();
						$commandline_obj->runScript(Utility::locateFile(COMMAND_LINE.'Email'.DS.'EmailUsers.php'), $email_data);

						# Set a nice message for the user in a session.
						$_SESSION['message']='Your email has been initiated. You will receive an email at '.$sender_email.' notifying you as to the success of the mailing. Thank you!';
						# Redirect the user to the page they were on.
						$doc->redirect(SECURE_URL.SECURE_HERE);
					}
					else
					{
						# Send the email.
						$email->sendFormEmail();
					}
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processPost

	/*** End public methods ***/

} # End EmailFormProcessor class.