<?php /* framework/application/modules/Form/AudioFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * AudioFormProcessor
 *
 * The AudioFormProcessor Class is used to create and process audio forms.
 *
 */
class AudioFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processAudio
	 *
	 * Processes a submitted audio for upload.
	 *
	 * @param array $data   An array of values to populate the form with.
	 * @param int $max_size The maximum allowed size of uploaded audio.
	 * @return string
	 * @throws Exception
	 */
	public function processAudio($data, $max_size=314572800)
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Get the AudioFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'AudioFormPopulator.php');
			# Get CommandLine class.
			require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populateAudioForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('audio');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('audio');

			# Instantiate a new instance of the AudioFormPopulator class.
			$populator=new AudioFormPopulator();
			# Populate the form and set the Audio data members for this post.
			$populator->populateAudioForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# If the URL params indicate this is a delete, process it. If the submit button hasn't been clicked yet, this will return the delete for itself.
			$display_delete_form=$this->processAudioDelete();
			# Check if the delete form was returned.
			if($display_delete_form!==FALSE)
			{
				# Return the delete form and leave this method.
				return $display_delete_form;
			}

			# Check if the user clicked on a form that sends them back to a previous form that sent them to the audio form in the first place.
			$this->processAudioBack();
			//$this->processAudioSelect();

			# Get the Audio object from the AudioFormPopulator object and set it to a variable for use in this method.
			$audio_obj=$populator->getAudioObject();

			# Set the audio's id to a variable.
			$id=$audio_obj->getID();
			# Get the audio type (file or embed code)
			$audio_type=$audio_obj->getAudioType();
			# Get the current audio's name and set it to a variable.
			if($audio_type=='file')
			{
				# Get the current audio's name and set it to a variable.
				$current_audio=$audio_obj->getFileName();
			}
			elseif($audio_type=='embed')
			{
				# Set the audio embed code to a variable.
				$embed_code=$audio_obj->getEmbedCode();
			}
			# Set a variable to FALSE indicating that a audio has not been uploaded.
			$uploaded_document=FALSE;
			# Set a variable to FALSE indicating that a thumbnail has not been uploaded.
			$uploaded_thumbnail=FALSE;
			# Set the audio API to a variable.
			$api=$audio_obj->getAPI();
			$api_array=json_decode($api);
			# Set the audio's author to a variable.
			$author=$audio_obj->getAuthor();
			# Set the audio's availability to a variable.
			$availability=$audio_obj->getAvailability();
			# Set the audio's categories to a variable.
			$categories=$audio_obj->getCategories();
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
			# Set the confirmation email template to a variable.
			$confirmation_template=$audio_obj->getConfirmationTemplate();
			# Set the audio contributor's id to a variable.
			$contributor_id=$audio_obj->getContID();
			# Set the audio's posting date to a variable.
			$date=$audio_obj->getDate();
			# Set the audio's description to a variable.
			$description=$audio_obj->getDescription();
			# Set the audio's Facebook value to a variable.
			$facebook=$populator->getFacebook();
			# Set the audio's associated image id to a variable.
			$image_id=$audio_obj->getImageID();
			# If $image_id is not NULL then this audio is being editted.
			#	We want the old image ID.
			if($data['ImageID']!=$image_id)
			{
				$old_image_id=$data['ImageID'];
			}
			# Set the audio's associated institution id to a variable.
			$institution_name=$audio_obj->getInstitution();
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
			# Set the audio's language to a variable.
			$language=$audio_obj->getLanguage();
			# Instantiate a new Language object.
			$language_obj=new Language();
			# Get the language info via the language name.
			$language_obj->getThisLanguage($language, FALSE);
			# Set the language id to a variable.
			$language_id=$language_obj->getID();
			# Set the audio's playlists to a variable.
			$playlists=$audio_obj->getPlaylists();
			# Create an empty variable for the playlist id's.
			$playlist_ids=NULL;
			# Check if there are categories.
			if(!empty($playlists))
			{
				# Change the values for the id's.
				$playlists=array_flip($playlists);
				# Separate the category id's with dashes (-).
				$playlist_ids='-'.implode('-', $playlists).'-';
			}
			# Set the audio's publisher name to a variable.
			$publisher_name=$audio_obj->getPublisher();
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher_obj=new Publisher();
			# Get the publisher info via the publisher name.
			$publisher_obj->getThisPublisher($publisher_name, FALSE);
			# Set the publisher id to a variable.
			$publisher_id=$publisher_obj->getID();
			# Set the audio's title to a variable.
			$title=$audio_obj->getTitle();
			# Set the audio's Twitter value to a variable.
			$twitter=$populator->getTwitter();
			# Set the audio's unique status to a variable.
			$unique=$populator->getUnique();
			# Set the audio's publish year to a variable.
			$year=$audio_obj->getYear();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['audio']) && (($_POST['audio']==='Add Audio') OR ($_POST['audio']==='Update'))))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('audio');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the title field was empty (or less than 2 characters or more than 1024 characters long).
				$empty_title=$fv->validateEmpty('title', 'Please enter a title for the audio.', 2, 1024);
				# Check if the title field was empty (or less than 2 characters or more than 1024 characters long).
				$empty_title=$fv->validateEmpty('author', 'Please enter an author for the audio.', 2, 1024);
				if(empty($category_ids))
				{
					# Set an error.
					$fv->setErrors('You must select at least one category for this audio.');
				}

				# Check if the image is an id.
				if($validator->isInt($image_id)!==TRUE)
				{
					# Get the image info from the `images` table.
					$audio_obj->getThisImage($image_id, FALSE);
					# Reset the variable with the id.
					$image_id=$audio_obj->getImageID();
				}
				else
				{
					# Get the image info from the `images` table.
					$audio_obj->getThisImage($image_id);
				}

				if($image_id!==NULL)
				{
					# Get the Image object.
					$image_obj=$audio_obj->getImageObj();
					# Set the variable that remembers that a custom thumbnail has been chosen to TRUE.
					$uploaded_thumbnail=TRUE;
				}

				# Get the FileHandler class.
				require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
				# Instantiate the new FileHandler object.
				$file_handler=new FileHandler();
				# Create safe image name based on the title.
				$clean_filename=$file_handler->cleanFilename($title);
				# Set an empty variable.
				$new_audio_name='';
				if(empty($id) && $audio_type=='file')
				{
					# Assign $_FILES to a variable.
					$u_audio=$_FILES['audio'];
					if(((is_uploaded_file($u_audio['tmp_name'])!==TRUE) OR ($u_audio['error']===UPLOAD_ERR_NO_FILE) OR ($u_audio['error']===4)) && empty($current_audio))
					{
						# Set an error.
						$fv->setErrors('You must select an audio file to upload.');
					}
					# Check if a audio was uploaded and if there have been no errors so far.
					if(array_key_exists('audio', $_FILES) && ($fv->checkErrors()===FALSE))
					{
						# Get the Upload class.
						require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
						# Instantiate an Upload object.
						$upload_obj=new Upload($_FILES['audio']);

						# Check if the uploaded audio size is not NULL.
						if($upload_obj->getSize()!==NULL)
						{
							try
							{
								# NOTE: How can we move this to a background script?
								# Upload the audio file.
								$document_upload=$upload_obj->uploadFile(BODEGA.'audio'.DS, array('mp3', 'flac', 'm4a', 'wav', 'wma'), BODEGA.'audio'.DS, $clean_filename, $max_size, FALSE);
								# Reset the audio file's name (ie: audio_file_name.mp3).
								$new_audio_name=$upload_obj->getName();
							}
							catch(Exception $e)
							{
								throw $e;
							}

							# Check for errors.
							if($upload_obj->checkErrors()===TRUE)
							{
								# Remove uploaded audio.
								$upload_obj->deleteFile(BODEGA.'audio'.DS.$new_audio_name);
								# Get any errors.
								$document_errors=$upload_obj->getErrors();
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
								# Set the variable that remembers that a audio has been uploaded to TRUE (in case we need to remove the audio).
								$uploaded_document=TRUE;
							}
						}
					}
				}
				elseif(empty($id) && $audio_type=='embed')
				{
					# Check if the embed field was empty (or less than 10 characters or more than 1024 characters long).
					$empty_title=$fv->validateEmpty('embed_code', 'Please enter an embed code for the audio.', 10, 1024);
					# Set to FALSE, just in case.
					$uploaded_document=FALSE;
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
					# Check if there was an uploaded audio file.
					if($uploaded_document===TRUE)
					{
						# Remove uploaded audio file.
						$upload_obj->deleteFile(BODEGA.'audio'.DS.$new_audio_name);
					}
				}
				else
				{
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the `audio` table in the Database.
						$fields=array('id', 'title');
						# Instantiate a new Search object.
						$search=new Search();
						# Make an array of the terms to search for (enclose multiple word strings in double quotes.)
						$terms=$title;
						# Don't compare with the audio ID.
						$filter=array('filter_fields'=>array('id'));
						# Check if the id is empty.
						if(!empty($id))
						{
							# Create a search filter that won't return the current record we may be editing.
							$filter=array_merge($filter, array('filter_sql'=>'`id` != '.$db->quote($id)));
						}
						# Search for duplicate records.
						$search->performSearch($terms, 'audio', $fields, NULL, $filter);
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
								# Instantiate a new Audio object.
								$dup_audio=new Audio();
								# Get the info for this record.
								$dup_audio->getThisAudio($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_audio->getID()]=array(
									'id'=>$dup_audio->getID(),
									'api'=>$dup_audio->getAPI(),
									'author'=>$dup_audio->getAuthor(),
									'availability'=>$dup_audio->getAvailability(),
									'contributor'=>$dup_audio->getContID(),
									'date'=>$dup_audio->getDate(),
									'description'=>$dup_audio->getDescription(),
									'file_name'=>$dup_audio->getFileName(),
									'image'=>$dup_audio->getImageID(),
									'institution'=>$dup_audio->getInstitution(),
									'language'=>$dup_audio->getLanguage(),
									'playlists'=>$dup_audio->getPlaylists(),
									'publisher'=>$dup_audio->getPublisher(),
									'title'=>$dup_audio->getTitle(),
									'year'=>$dup_audio->getYear()
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
						$_SESSION['form']['audio']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the audio is considered unique and may be added to the Database.
					if($unique==1)
					{
						/*
						# Check if there was no passed image ID.
						if($image_id===NULL)
						{
							# Get the default image information from the database, and set them to data members.
							$audio_obj->getThisImage('Audio Default Thumbnail', 'title');
							# Set the returned image ID to the image_id variable.
							$image_id=$audio_obj->getImageID();
						}
						*/

						# If there is no custom thumbnail image, get the image from the audio file to use as a thumbnail.
						if($uploaded_thumbnail===FALSE)
						{
							# If this is not a audio being edited and it's a file.
							if(empty($id) && $audio_type=='file')
							{
								# If the image does not already exists, create one.
								if(file_exists(IMAGES_PATH.'original'.DS.$clean_filename.'.jpg')===FALSE)
								{
									# Get the getID3 Class.
									//require_once Utility::locateFile(MODULES.'Vendor'.DS.'getID3'.DS.'getid3'.DS.'getid3.php');
									# Instantiate the new getID3 object.
									$getID3_obj=new getID3;
									$audio_file_info=$getID3_obj->analyze(BODEGA.'audio'.DS.$new_audio_name);
									if(isset($audio_file_info['comments']['picture'][0]))
									{
										# Get image data.
										$thumbnail_data=$audio_file_info['comments']['picture'][0]['data'];
										# Create original thumbnail image.
										file_put_contents(IMAGES_PATH.'original'.DS.$clean_filename.'.jpg', $thumbnail_data);
									}

									if(file_exists(IMAGES_PATH.'original'.DS.$clean_filename.'.jpg'))
									{
										# Resize the image and save the new image to the target folder.
										$resize_image=$file_handler->reduceImage(IMAGES_PATH.'original'.DS.$clean_filename.'.jpg', IMAGES_PATH.$clean_filename.'.jpg', '320', '180', '75', FALSE);

										# Insert the thumbnail image into the `images` table.
										$sql='INSERT INTO `'.DBPREFIX.'images` ('.
											'`title`,'.
											' `image`,'.
											((!empty($category_ids)) ? ' `category`,' : '').
											' `contributor`'.
											') VALUES ('.
											$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $title))).', '.
											$db->quote($db->escape($clean_filename.'.jpg')).', '.
											((!empty($category_ids)) ? $db->quote($category_ids).', ' : '').
											$db->quote($contributor_id).
											')';
										# Run the SQL query.
										$db->query($sql);
										# Assign the image ID to a variable.
										$image_id=$db->get_insert_id();
									}
								}
								# There is already an original thumbnail.
								else
								{
									# Search for this image.
									$audio_obj->getThisImage($clean_filename.'.jpg', FALSE);
									# Look for file and get it's ID.
									$image_id=$audio_obj->getImageID();
								}
							}
						}

						# Create the default value for the message action.
						$message_action='added';

						# Check if the api array is NOT empty.
						if(!empty($api_array))
						{
							# Convert the api array to JSON.
							$api=json_encode($api_array, JSON_FORCE_OBJECT);
						}
						# Clean up the description and prepare it for the DB.
						$sql_description=(
							(!empty($description))
							?
								$db->quote(
									$db->escape(
										preg_replace(
											"/<p>(.*?)<\/p>(\n?\r?(\n\r)?)/i",
											"$1\n",
											str_replace(
												array("\r\n", "\n", "\r", DOMAIN_NAME),
												array('', '', '', '%{domain_name}'),
												htmlspecialchars_decode($description)
											)
										)
									)
								)
							:
								$db->quote('')
						);

						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'audio` ('.
							'`title`,'.
							((!empty($sql_description)) ? ' `description`,' : '').
							((!empty($new_audio_name)) ? ' `file_name`,' : '').
							((!empty($embed_code)) ? ' `embed_code`,' : '').
							((!empty($api)) ? ' `api`,' : '').
							((!empty($author)) ? ' `author`,' : '').
							((!empty($year)) ? ' `year`,' : '').
							((!empty($category_ids)) ? ' `category`,' : '').
							((!empty($playlist_ids)) ? ' `playlist`,' : '').
							' `availability`,'.
							' `date`,'.
							(($image_id!==NULL) ? ' `image`,' : '').
							' `institution`,'.
							((!empty($publisher_id)) ? ' `publisher`,' : '').
							' `language`,'.
							' `contributor`'.
							') VALUES ('.
							$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
							((!empty($sql_description)) ? ' '.$sql_description.',' : '').
							((!empty($new_audio_name)) ? ' '.$db->quote($db->escape($new_audio_name)).',' : '').
							((!empty($embed_code)) ? ' '.$db->quote($db->escape($embed_code)).',' : '').
							((!empty($api)) ? ' '.$db->quote($api).',' : '').
							((!empty($author)) ? ' '.$db->quote($db->escape($author)).',' : '').
							((!empty($year)) ? ' '.$db->quote($year).',' : '').
							((!empty($category_ids)) ? ' '.$db->quote($category_ids).',' : '').
							((!empty($playlist_ids)) ? ' '.$db->quote($playlist_ids).',' : '').
							' '.$db->quote($availability).','.
							' '.$db->quote($date).','.
							(($image_id!==NULL) ? ' '.$db->quote($image_id).',' : '').
							' '.$db->quote($institution_id).','.
							((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : '').
							' '.$db->quote($language_id).','.
							' '.$db->quote($contributor_id).
							')';
						$new_media=TRUE;

						# Check if this is an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'audio` SET'.
								((!empty($api)) ? ' `api` = '.$db->quote($api).',' : '').
								' `author` = '.((!empty($author)) ? ' '.$db->quote($author).',' : 'NULL,').
								' `availability` = '.$db->quote($availability).','.
								' `contributor` = '.$db->quote($contributor_id).','.
								' `date` = '.$db->quote($date).','.
								' `description` = '.$sql_description.','.
								((!empty($embed_code)) ? ' `embed_code` = '.$db->quote($db->escape($embed_code)).',' : '').
								((!empty($new_audio_name)) ? ' `file_name` = '.$db->quote($db->escape($new_audio_name)).',' : '').
								' `institution` = '.$db->quote($institution_id).','.
								($image_id!==NULL ? ' `image` = '.$db->quote($image_id).',' : '').
								' `language` = '.$db->quote($language_id).','.
								((!empty($category_ids)) ? ' `category` = '.$db->quote($category_ids).',' : '').
								((!empty($playlist_ids)) ? ' `playlist` = '.$db->quote($playlist_ids).',' : '').
								' `publisher` = '.((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : 'NULL,').
								' `title` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
								' `year` = '.((!empty($year)) ? ' '.$db->quote($year).'' : 'NULL').
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
							$new_media=FALSE;
						}
						try
						{
							# Run the sql query.
							$db_post=$db->query($sql);
							# Check if the query was successful.
							if(TRUE)
							{
								# Set the ID from the insert SQL query.
								$insert_id=$db->get_insert_id();

								# If this is a new audio (not editted).
								if(!empty($insert_id))
								{
									# Add API stuff here.
								}

								# Instantiate the new CommandLine object.
								$commandline_obj=new CommandLine();

								# Create an array with audio data.
								$audio_data=array(
									'Environment'=>DOMAIN_NAME,
									'DevEnvironment'=>DEVELOPMENT_DOMAIN,
									'StagingEnvironment'=>STAGING_DOMAIN,
									'Availability'=>$availability,
									'Categories'=>$category_ids,
									'ContID'=>$contributor_id,
									'Description'=>$description,
									'FileNameNoExt'=>($insert_id>0 ? $clean_filename : ''),
									'FileName'=>($insert_id>0 ? $new_audio_name : $_SESSION['form']['audio']['FileName']),
									'ID'=>($insert_id>0 ? $insert_id : $_SESSION['form']['audio']['ID']),
									'ImageID'=>($insert_id>0 ? $image_id : $_SESSION['form']['audio']['ImageID']),
									'MediaType'=>'audio',
									'NewMedia'=>$new_media,
									'OldImageID'=>(isset($old_image_id) ? $old_image_id : ''),
									'Playlists'=>$playlist_ids,
									'Title'=>$title,
								);

								# Check if a new audio file was uploaded.
								if(!empty($new_audio_name))
								{
									# NOTE: There's no point in having a AudioUpload command line script since we can't upload the audio in the background.
									/*
									# Run the upload script.
									#	runScript() turns a multidimensional array into a single dimensional array and seperates the keys from the values.
									#		ex: php ScriptName.php Key1|Key2|Key3 Value1|Value2|Value3
									$commandline_obj->runScript(Utility::locateFile(COMMAND_LINE.'Media'.DS.'AudioUpload.php'), $audio_data);
									*/

									# Convert audio to other file types (128bit mp3).
									$commandline_obj->runScript(Utility::locateFile(COMMAND_LINE.'Media'.DS.'ConvertMedia.php'), $audio_data);

									# NOTE: Moved to ConvertMedia.php script.
									# Convert to 128bit mp3.
									//$cl2=new CommandLine('ffmpeg');
									//$cl2->runScript("-i ".BODEGA.'audio'.DS.$new_audio_name." -acodec libmp3lame -ac 2 -ab 128k ".AUDIO_PATH."files".DS.$clean_filename.".mp3");
								}

								# Check if the availability allows posting to social networks.
								if($availability==1)
								{
									# Check if the post should be posted on Twitter.com or Facebook.com.
									if($twitter==='0' OR $facebook==='post')
									{
										# Get the API Class.
										require_once Utility::locateFile(MODULES.'API'.DS.'API.php');
										$post_url=AUDIO_URL.'?audio='.$db->get_insert_id();
									}
									# Check if the post should be posted on Twitter.com.
									if($twitter==='0')
									{
										# Instantiate a new API object.
										$api_obj=new API('twitter');
										$api_obj->post($title, $post_url);
									}
									# Check if the post should be posted on Facebook.com.
									if($facebook==='post')
									{
										require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
										$contributor_obj=new Contributor();
										$contributor_obj->getThisContributor($contributor_id, 'id');
										$cont_privacy=$contributor_obj->getContPrivacy();
										$contributor_name='';
										# Check if the contributor should be hidden.
										if($cont_privacy!==NULL)
										{
											$contributor_name='Posted by '.$contributor_obj->getContName().' - ';
										}
										# Instantiate a new API object.
										$api_obj=new API('facebook');
										$api_obj->post($contributor_name.'Read more at '.DOMAIN_NAME, $post_url, $title, $image_id);
									}
								}
								# Remove the audio's session.
								unset($_SESSION['form']['audio']);
								# Set a nice message for the user in a session.
								$_SESSION['message']='Your audio was successfully '.$message_action.'!';
								# Redirect the user to the page they were on.
								$this->redirectNoDelete('audio');
							}
							else
							{
								if(!empty($id))
								{
									# Set a nice message for the user in a session.
									$_SESSION['message']='The audio\'s record was unchanged.';
								}
								# Check if there was an uploaded audio file.
								if($uploaded_document===TRUE)
								{
									# Remove uploaded audio file.
									$upload_obj->deleteFile(BODEGA.'audio'.DS.$new_audio_name);
								}
							}
						}
						catch(Exception $e)
						{
							# Check if there was an uploaded audio file.
							if($uploaded_document===TRUE)
							{
								# Remove uploaded audio file.
								$upload_obj->deleteFile(BODEGA.'audio'.DS.$new_audio_name);
							}
							throw $e;
						}
					}
					else
					{
						# Check if there was an uploaded audio file.
						if($uploaded_document===TRUE)
						{
							# Remove uploaded audio file.
							$upload_obj->deleteFile(BODEGA.'audio'.DS.$new_audio_name);
						}
					}
				}
			}
			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error posting to the `audio` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processFile

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processAudioSelect
	 *
	 * Processes a submitted form selecting a audio to add to a post.
	 *
	 * @access	private
	 * @return	string
	 */
	private function processAudioSelect()
	{
		# Check if this is a audio select page.
		if(isset($_GET['select']) && $_GET['select']==='yes')
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && ($_POST['audio']=='Select Audio'))
			{
				# Bring the alert-title variable into scope.
				global $alert_title;
				# Set the Document instance to a variable.
				$doc=Document::getInstance();

				# Check if the audio id POST data was sent.
				if(isset($_POST['audio_info']))
				{
					# Get the Audio class.
					require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
					# Instantiate a new Audio object.
					$audio_obj=new Audio();
					$colon_pos=strpos($_POST['audio_info'], ':');
					$audio_id=substr($_POST['audio_info'], 0, $colon_pos);
					$audio_name=substr($_POST['audio_info'], $colon_pos+1);
					# Set the audio id to the Audio data member.
					$audio_obj->setID($audio_id);
					# Set the audio name to the Audio data member.
					$audio_obj->setAudio($audio_name);
					# Set the audio's id to a variable.
					$audio_id=$audio_obj->getID();
					# Set the audio's name to a variable.
					$audio_name=$audio_obj->getAudio();
				}
				else
				{
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError('Please select an audio file.');
				}
				# Redirect the User to the page they came from with a friendly message.
				$this->redirectAudio($audio_name, 'selected');
			}
		}
	} #==== End -- processAudioSelect

	/**
	 * processAudioDelete
	 *
	 * Removes a audio from the `audio` table and the actual file from the system. A wrapper method for the deleteAudio method in the Audio class.
	 *
	 * @access	private
	 */
	private function processAudioDelete()
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
			# Check if the audio's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['audio']) && isset($_GET['delete']))
			{
				# Check if the passed audio id is an integer.
				if($validator->isInt($_GET['audio'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_audio']) && ($_POST['delete_audio']==='delete')))
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
							if(empty($audio_name))
							{
								$audio_name=$audio_obj->getTitle();
							}
							# Set the "cleaned" id to a local variable.
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
				# Redirect the user to the default redirect location. They have no business trying to pass a non-integer as an id!
				$doc->redirect(DEFAULT_REDIRECT);
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processAudioDelete

	/**
	 * processAudioBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a file.
	 *
	 * @access	private
	 */
	private function processAudioBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'audio',
				'post',
				'product'
			);
			# Set the resource value.
			$resource='audio';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processAudioBack

	/**
	 * redirectAudio
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire a audio.
	 *
	 * @access	private
	 */
	private function redirectAudio($audio_name, $action)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Audio object and set it to a local variable.
			$audio_obj=$populator->getAudioObject();
			# Get the data for the new audio.
			$audio_obj->getThisAudio($audio_name, FALSE);
			# Get the new audio's id.
			$audio_id=$audio_obj->getID();
			# Remove the audio session.
			unset($_SESSION['form']['audio']);
			# Set a nice message for the user in a session.
			$_SESSION['message']='The audio "'.$audio_name.'" was successfully '.$action.'!';
			# Check if there is a post or content session.
			if(isset($_SESSION['form']['audio']))
			{
				# Set the default origin form's name.
				$origin_form='audio';
				# Set the default session audio index name.
				$audio_index='ID';
				# Set the post session audio id.
				$_SESSION['form'][$origin_form][$audio_index]=$audio_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='audio';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectAudio

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
			# Get the Audio object and set it to a local variable.
			$audio_obj=$populator->getAudioObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Set the audio's associated institution name to a variable.
			$institution_name=$audio_obj->getInstitution();
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution_obj=new Institution();
			# Get the institution info via the institution name.
			$institution_obj->getThisInstitution($institution_name, FALSE);
			# Set the institution id to a variable.
			$institution_id=$institution_obj->getID();

			# Set the audio's language to a variable.
			$language=$audio_obj->getLanguage();
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Instantiate a new Language object.
			$language_obj=new Language();
			# Get the language info via the language name.
			$language_obj->getThisLanguage($language, FALSE);
			# Set the language id to a variable.
			$language_id=$language_obj->getID();

			# Set the audio's publisher name to a variable.
			$publisher_name=$audio_obj->getPublisher();
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher_obj=new Publisher();
			# Get the publisher info via the publisher name.
			$publisher_obj->getThisPublisher($publisher_name, FALSE);
			# Set the publisher id to a variable.
			$publisher_id=$publisher_obj->getID();

			# Get the audio type (file or embed).
			$audio_type=$audio_obj->getAudioType();
			# If the audio type file.
			if($audio_type=='file')
			{
				# Set the session key is FileName.
				$file_embed='FileName';
				# Set the value of $file_embed.
				$file_name=$audio_obj->getFileName();
			}
			else
			{
				# Set the session key to Embed.
				$file_embed='EmbedCode';
				# Set the value of $file_embed.
				$file_name=$audio_obj->getEmbedCode();
			}

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['audio']=
				array(
					'ID'=>$audio_obj->getID(),
					'FormURL'=>$form_url,
					'API'=>$audio_obj->getAPI(),
					'AudioType'=>$audio_obj->getAudioType(),
					'Author'=>$audio_obj->getAuthor(),
					'Availability'=>$audio_obj->getAvailability(),
					'Categories'=>$audio_obj->getCategories(),
					'ContID'=>$audio_obj->getContID(),
					'Date'=>$audio_obj->getDate(),
					'Description'=>$audio_obj->getDescription(),
					'Facebook'=>$populator->getFacebook(),
					$file_embed=>$file_name,
					'ImageID'=>$audio_obj->getImageID(),
					'Institution'=>$institution_id,
					'Language'=>$language_id,
					'Playlists'=>$audio_obj->getPlaylists(),
					'Publisher'=>$publisher_id,
					'Title'=>$audio_obj->getTitle(),
					'Twitter'=>$populator->getTwitter(),
					'Unique'=>$populator->getUnique(),
					'Year'=>$audio_obj->getYear()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End AudioFormProcessor class.