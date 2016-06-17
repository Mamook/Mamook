<?php /* framework/application/modules/Form/ImageFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * ImageFormProcessor
 *
 * The ImageFormProcessor Class is used to create and process image select, upload, edit, or delete forms.
 *
 */
class ImageFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processImage
	 *
	 * Processes a submitted image for upload, selection, or property editing.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 * @return	string
	 */
	public function processImage($data)
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			# Get the ImageFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'ImageFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populatImageForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('image');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('image', 'image');

			# Instantiate a new instance of ContentFormPopulator.
			$populator=new ImageFormPopulator();
			# Populate the form and set the SubContent data members for this post.
			$populator->populateImageForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# If the URL params indicate this is a delete, process it. If the submit button hasn't been clicked yet, this will return the delete for itself.
			$display_delete_form=$this->processImageDelete();
			# Check if the delete form was returned.
			if($display_delete_form!==FALSE)
			{
				# Return the delete form and leave this method.
				return $display_delete_form;
			}

			# Check if the user clicked on a form that sends them back to a previous form that sent them to the audio form in the first place.
			$this->processImageBack();
			$this->processImageSelect();

			# Get the Image object from the ImageFormPopulator object and set it to a variable for use in this method.
			$image_obj=$populator->getImageObject();

			# Get the current image's name and set it to a variable.
			$current_image=$image_obj->getImage();
			# Create an empty variable to hold the new name of an uplaoded file.
			$new_name=NULL;
			# Set a variable to FALSE indicating that an image has not been uploaded.
			$uploaded_document=FALSE;
			# Set the image's id to a variable.
			$id=$image_obj->getID();
			# Set the image's categories to a variable.
			$categories=$image_obj->getCategories();
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
			# Set the image contributor's id to a variable.
			$contributor_id=$image_obj->getContID();
			# Set the image's recent contributor's id to a variable.
			$recent_cont_id=$image_obj->getRecentContID();
			# Set the image's last edit date to a variable.
			$last_edit=$image_obj->getLastEdit();
			# Set the image's description to a variable.
			$description=$image_obj->getDescription();
			# Set the image's height to a variable.
			$height=$image_obj->getHeight();
			# Set the image's hide status to a variable.
			$hide=$image_obj->getHide();
			# Set the image's location to a variable.
			$location=$image_obj->getLocation();
			# Set the image's title to a variable.
			$title=$image_obj->getTitle();
			# Set the image's unique status to a variable.
			$unique=$populator->getUnique();
			# Set the image's width to a variable.
			$width=$image_obj->getWidth();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['image']) && ($_POST['image']=='Add Image' OR $_POST['image']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('image');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the title field was empty (or less than 2 characters or more than 1024 characters long).
				$empty_title=$fv->validateEmpty('title', 'Please enter a title for the image.', 2, 1024);
				$u_image=$_FILES['image'];
				if(((is_uploaded_file($u_image['tmp_name'])!==TRUE) OR ($u_image['error'] === UPLOAD_ERR_NO_FILE) OR ($u_image['error'] === 4)) && empty($current_image))
				{
					# Set an error.
					$fv->setErrors('You must select an image to upload.');
				}
				# Check if an image was uploaded and if there have been no errors so far.
				if(array_key_exists('image', $_FILES) && ($fv->checkErrors()===FALSE))
				{
					# Get the Upload class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
					# Instantiate an Upload object.
					$upload=new Upload($_FILES['image']);

					# Check if the uploaded image size is not NULL.
					if($upload->getSize()!==NULL)
					{
						# Get the FileHandler class.
						require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
						# Instantiate the new FileHandler object.
						$file_handler=new FileHandler();
						# Create safe image name based on the title.
						$new_name=$file_handler->cleanFilename($title);

						try
						{
							# Set a variable to FALSE indicating that this image is not related to media.
							$media_image=FALSE;
							# If the Audio or Videos key exists in the $categories array.
							if(array_key_exists('Videos', $categories) || array_key_exists('Audio', $categories))
							{
								# Set the variable to TRUE since Videos was in the array.
								$media_image=TRUE;
							}
							if($media_image===TRUE)
							{
								# Upload original thumbnail.
								$document_upload=$upload->uploadImage(IMAGES_PATH.'original'.DS, IMAGES_PATH, $new_name, 7340032, TRUE, $width, $height, 75, TRUE, 800, 800, 100, FALSE);
							}
							else
							{
								# Upload the image.
								$document_upload=$upload->uploadImage(IMAGES_PATH.'original'.DS, IMAGES_PATH, $new_name, 7340032, TRUE, $width, $height, 75, TRUE, 800, 800, 100, TRUE);
							}
							# Reset the image's new name.
							$new_name=$upload->getName();
						}
						catch(Exception $e)
						{
							throw $e;
						}

						# Check for errors.
						if($upload->checkErrors()===TRUE)
						{
							# Remove uploaded image from the Images folder and the Original folder.
							$upload->deleteFile(IMAGES_PATH.$new_name);
							$upload->deleteFile(IMAGES_PATH.'original'.DS.$new_name);
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
							# Set the variable that remembers that an image has been uploaded to TRUE (in case we need to remove the image).
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
					# Check if there was an uploaded image.
					if($uploaded_document===TRUE)
					{
						# Remove uploaded image from the Images folder and the Original folder.
						$upload->deleteFile(IMAGES_PATH.$new_name);
						$upload->deleteFile(IMAGES_PATH.'original'.DS.$new_name);
					}
				}
				else
				{
					# Check if the image data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the `images` table in the Database.
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
						$search->performSearch($terms, 'images', $fields, NULL, $filter);
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
								# Instantiate a new Image object.
								$dup_image=new Image();
								# Get the info for this record.
								$dup_image->getThisImage($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_image->getID()]=array(
									'id'=>$dup_image->getID(),
									'categories'=>$dup_image->getCategories(),
									'contributor'=>$dup_image->getContID(),
									'description'=>$dup_image->getDescription(),
									'image'=>$dup_image->getImage(),
									'hide'=>$dup_image->getHide(),
									'location'=>$dup_image->getLocation(),
									'title'=>$dup_image->getTitle()
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
						$_SESSION['form']['image']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the image is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Create the default value for the message action.
						$message_action='added';

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
						$sql='INSERT INTO `'.DBPREFIX.'images` ('.
							'`title`, '.
							'`image`, '.
							((!empty($location)) ? ' `location`, ' : '').
							((!empty($category_ids)) ? ' `category`, ' : '').
							((!empty($sql_description)) ? ' `description`, ' : '').
							'`last_edit`, '.
							(($hide===NULL) ? ' `hide`, ' : '').
							' `contributor`'.
							') VALUES ('.
							$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).', '.
							$db->quote($db->escape($new_name)).', '.
							((!empty($location)) ? ' '.$db->quote($db->escape($location)).', ' : '').
							((!empty($category_ids)) ? ' '.$db->quote($category_ids).', ' : '').
							((!empty($sql_description)) ? ' '.$sql_description.', ' : '').
							$db->quote($db->escape($last_edit)).','.
							((!empty($hide)) ? '0,' : '').
							' '.$db->quote($contributor_id).
							')';
						# Check if this is an UPDATE. If there is an ID, it's an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'images` SET
								`title` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
								((!empty($new_name)) ? ' `image` = '.$db->quote($db->escape($new_name)).',' : '').
								' `location` = '.((empty($location)) ? $db->quote('') : $db->quote($db->escape($location))).','.
								' `category` = '.$db->quote($db->escape($category_ids)).','.
								' `contributor` = '.$db->quote($contributor_id).','.
								' `recent_contributor` = '.$db->quote($recent_cont_id).','.
								' `last_edit` = '.$db->quote($last_edit).','.
								' `description` = '.$sql_description.','.
								' `hide` = '.(($hide===NULL) ? 'NULL' : 0).
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
								# Unset the CMS session data.
								unset($_SESSION['form']['image']);
								$name=((empty($id)) ? $new_name : $current_image );
								$this->redirectImage($name, $message_action);
							}
							else
							{
								# Check if there was an uploaded image.
								if($uploaded_document===TRUE)
								{
									# Remove uploaded image from the Images folder and the Original folder.
									$upload->deleteFile(IMAGES_PATH.$new_name);
									$upload->deleteFile(IMAGES_PATH.'original'.DS.$new_name);
								}
								if(!empty($id))
								{
									# Unset the CMS session data.
									unset($_SESSION['form']['image']);
									# Set a nice message for the user in a session.
									$_SESSION['message']="The image's record was unchanged.";
									# Redirect the user to the page they were on.
									$this->redirectNoDelete();
								}
							}
						}
						catch(Exception $e)
						{
							# Check if there was an uploaded image.
							if($uploaded_document===TRUE)
							{
								# Remove uploaded image from the Images folder and the Original folder.
								$upload->deleteFile(IMAGES_PATH.$new_name);
								$upload->deleteFile(IMAGES_PATH.'original'.DS.$new_name);
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
	} #==== End -- processImage

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * processImageBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch an image.
	 *
	 * @access	protected
	 */
	protected function processImageBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'audio',
				'content',
				'post',
				'product',
				'video'
			);
			# Set the resource value.
			$resource='image';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processImageBack

	/**
	 * processImageDelete
	 *
	 * Removes an image from the `images` table and the actual image from the system. A wrapper method for the deleteImage method in the Image class.
	 *
	 * @access	private
	 */
	protected function processImageDelete()
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
			# Check if the image's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['image']) && isset($_GET['delete']))
			{
				# Check if the passed image id is an integer.
				if($validator->isInt($_GET['image'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_image']) && ($_POST['delete_image']==='delete')))
					{
						# Get the SubContent class. With this class, the File object can be accessed as well as the SubContent.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this image and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisImage($_GET['image']);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the Image object to a local variable.
							$image_obj=$subcontent->getImageObj();
							# Set the image name to a local variable.
							$image_name=$image_obj->getImage();
							# Set the "cleaned id to a local variable.
							$id=$subcontent->getImageID();
							# Get all subcontent with this image associated.
							$subcontent_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`image` = '.$db->quote($id));
							# Set the content_returned variable to FALSE by default.
							$content_returned=FALSE;
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
							# Check if this user has access to all subcontent posts that have this image associated.
							if($access===TRUE)
							{
								# Get the Content class.
								require_once Utility::locateFile(MODULES.'Content'.DS.'Content.php');
								# Instantiate a new Content object.
								$content=new Content();
								# Count all content with this image associated.
								$count=$content->countAllContent(NULL, '`image` = '.$db->quote($db->escape($image_name)));
								# Check if there where records associated with the image.
								if($count>0)
								{
									# Set the content_returned variable to TRUE.
									$content_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(MAN_USERS);
								}
							}
							# Check if this user still has access to delete this image.
							if($access===TRUE)
							{
								# Get the Product class.
								require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
								# Instantiate a new Product object.
								$product=new Product();
								# Get all product with this image associated.
								$count=$product->countAllRecords('all', NULL, '`image` = '.$db->quote($id));
								# Check if this image a associated with any product.
								if($count>0)
								{
									# Set the product_returned variable to TRUE.
									$product_returned=TRUE;
									# Check if the user has access to this record.
									$access=$login->checkAccess(MAN_USERS);
								}
							}
							# Check if this user still has access to delete this image.
							if($access===TRUE)
							{
								if(($subcontent_returned===TRUE) OR ($content_returned===TRUE) OR ($product_returned===TRUE))
								{
									try
									{
										# Remove the file from all `content` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'content` '.
											'SET '.
											DBPREFIX.'content.image = NULL '.
											'WHERE '.
											DBPREFIX.'content.image = '.$db->quote($db->escape($image_name)));

										# Remove the file from all `products` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'products` '.
											'SET '.
											DBPREFIX.'products.image = NULL '.
											'WHERE '.
											DBPREFIX.'products.image = '.$db->quote($id));

										# Remove the file from all `subcontent` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'subcontent` '.
											'SET '.
											DBPREFIX.'subcontent.image = NULL '.
											'WHERE '.
											DBPREFIX.'subcontent.image = '.$db->quote($id));

										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The image "'.$image_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this file removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the image "'.$image_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['image']);
								# Delete the image from the Database and set the returned value to a variable.
								$deleted=$image_obj->deleteImage($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectImage($image_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The image "'.$image_name.'" (id: '.$id.') was NOT deleted from the image list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this image removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The image was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this image, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The image was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('image');
						}
					}
					# Check if the form has been submitted to NOT delete the image.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_image']) && ($_POST['delete_image']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The image was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this image and request confirmation from the user with the appropriate warnings.
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
	} #==== End -- processImageDelete

	/**
	 * processImageSelect
	 *
	 * Processes a submitted form selecting an image to add to a post.
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function processImageSelect()
	{
		# Check if this is a image select page.
		if(isset($_GET['select']))
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && ($_POST['image']=='Select Image'))
			{
				# Bring the alert-title variable into scope.
				global $alert_title;
				# Set the Document instance to a variable.
				$doc=Document::getInstance();

				# Check if the image id POST data was sent.
				if(isset($_POST['image_info']))
				{
					# Get the Populator object and set it to a local variable.
					$populator=$this->getPopulator();
					# Get the Image object and set it to a local variable.
					$image_obj=$populator->getImageObject();
					$colon_pos=strpos($_POST['image_info'], ':');
					$image_id=substr($_POST['image_info'], 0, $colon_pos);
					$image_name=substr($_POST['image_info'], $colon_pos+1);
					# Set the image id to the Image data member.
					$image_obj->setID($image_id);
					# Set the image name to the Image data member.
					$image_obj->setImage($image_name);
					# Set the image's id to a variable.
					$image_id=$image_obj->getID();
					# Set the image's name to a variable.
					$image_name=$image_obj->getImage();
					# Redirect the User back to the form that sent them to fetch an image.
					$this->redirectImage($image_name, 'selected');
				}
				else
				{
					# Set the error message to the Document object datamember so that it may be displayed on the page.
					$doc->setError('Please select an image.');
					# Redirect the user to the page they were on with no POST or GET data.
					$doc->redirect(COMPLETE_URL);
				}
			}
		}
	} #==== End -- processImageSelect

	/**
	 * redirectImage
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire an image.
	 *
	 * @access	protected
	 */
	protected function redirectImage($image_name, $action)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Image object and set it to a local variable.
			$image_obj=$populator->getImageObject();
			# Get the data for the new image.
			$image_obj->getThisImage($image_name, FALSE);
			# Get the new image's id.
			$image_id=$image_obj->getID();
			# Remove the image session.
			unset($_SESSION['form']['image']);
			# Set a nice message for the user in a session.
			$_SESSION['message']='The image "'.$image_name.'" was successfully '.$action.'!';
			# Check if there is a post or content session.
			if(
				isset($_SESSION['form']['post']) OR
				isset($_SESSION['form']['audio']) OR
				isset($_SESSION['form']['video']) OR
				isset($_SESSION['form']['content']) OR
				isset($_SESSION['form']['product']))
			{
				# Set the default origin form's name.
				$origin_form='product';
				# Set the default session image index name.
				$image_index='ImageID';
				# Set the default session image value.
				$image_value=$image_id;
				if(isset($_SESSION['form']['post']))
				{
					# Set the form's name as "post".
					$origin_form='post';
				}
				if(isset($_SESSION['form']['audio']))
				{
					# Set the form's name as "audio".
					$origin_form='audio';
				}
				if(isset($_SESSION['form']['video']))
				{
					# Set the form's name as "video".
					$origin_form='video';
				}
				if(isset($_SESSION['form']['content']))
				{
					# Set the form's name as "content".
					$origin_form='content';
					# Set the post session image index name.
					$image_index='Image';
					# Set the content session image name.
					$image_value=$image_name;
				}
				# Set the post session image id.
				$_SESSION['form'][$origin_form][$image_index]=$image_value;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='image';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectImage

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
			# Get the Image object and set it to a local variable.
			$image_obj=$populator->getImageObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['image']=
				array(
					'ID'=>$image_obj->getID(),
					'Categories'=>$image_obj->getCategories(),
					'ContID'=>$image_obj->getContID(),
					'Description'=>$image_obj->getDescription(),
					'FormURL'=>$form_url,
					'Image'=>$image_obj->getImage(),
					'Height'=>$image_obj->getHeight(),
					'Hide'=>$image_obj->getHide(),
					'Location'=>$image_obj->getLocation(),
					'Title'=>$image_obj->getTitle(),
					'Unique'=>$populator->getUnique(),
					'Width'=>$image_obj->getWidth()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End protected methods ***/

} # End ImageFormProcessor class.