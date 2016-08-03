<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * PublisherFormProcessor
 *
 * The PublisherFormProcessor Class is used to create and process publsher forms.
 *
 */
class PublisherFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processPublisher
	 *
	 * Processes a submitted publisher for upload, edit, or deletion.
	 *
	 * @param		$data			(An array of values tp populate the form with.)
	 * @access	public
	 */
	public function processPublisher($data=array())
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
			# Get the PublisherFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'PublisherFormPopulator.php');

			# Remove any un-needed CMS session data. (This needs to happen before populatePublisherForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.)
			$this->loseSessionData('publisher');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('publisher', 'publisher');

			# Instantiate a new instance of PublisherFormPopulator.
			$populator=new PublisherFormPopulator();
			# Populate the form and set the Publisher data members for this publisher.
			$populator->populatePublisherForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			$display_delete_form=$this->processPublisherDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processPublisherBack();
			$this->processPublisherSelect();

			# Get the Publisher object from the PublisherFormPopulator and set it to a local variable.
			$publisher=$populator->getPublisherObject();

			# Set the publisher's id to a variable.
			$id=$publisher->getID();
			# Set the publisher contributor's id to a variable.
			$contributor_id=$publisher->getContID();
			# Set the publisher's posting date to a variable.
			$date=$publisher->getDate();
			# Set the publisher's recent contributor's id to a variable.
			$recent_cont_id=$publisher->getRecentContID();
			# Set the publisher's last edit date to a variable.
			$last_edit=$publisher->getLastEdit();
			# Set the publisher's information to a variable.
			$info=$publisher->getInfo();
			# Set the publisher's name to a variable.
			$name=$publisher->getPublisher();
			# Set the site name to a variable.
			$site_name=$main_content->getSiteName();
			# Set the publisher's unique status to a variable.
			$unique=$populator->getUnique();

			# Check if the form has been submitted and the submit button was the "Submit" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['publisher']) && ($_POST['publisher']==='Submit' OR $_POST['publisher']=='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the name field was empty (or less than 2 characters or more than 255 characters long).
				$empty_name=$fv->validateEmpty('name', 'Please enter a name for the publisher.', 2, 255);

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
					# Check if the publisher data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the publishers table in the Database.
						$fields=array('id', 'name');
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
						$search->setAllResults($search->performSearch($terms, 'publishers', $fields, NULL, $filter));
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
								# Instantiate a new Publisher object.
								$dup_publisher=new Publisher();
								# Get the info for this record.
								$dup_publisher->getThisPublisher($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_publisher->getID()]=array(
									'id'=>$dup_publisher->getID(),
									'contributor'=>$dup_publisher->getContID(),
									'date'=>$dup_publisher->getDate(),
									'recent_contributor'=>$dup_publisher->getRecentContID(),
									'last_edit'=>$dup_publisher->getLastEdit(),
									'info'=>$dup_publisher->getInfo(),
									'name'=>$dup_publisher->getPublisher()
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
						$_SESSION['form']['publisher']['Unique']=$unique;
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the publisher is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Create the default value for the message action.
						$message_action='added';
						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'publishers` ('.
							'`name`, '.
							((!empty($info)) ? ' `info`, ' : '').
							' `contributor`,'.
							'`date`'.
							') VALUES ('.
							$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $name))).', '.
							((!empty($info)) ? ' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $info))).', ' : '').
							$db->quote($contributor_id).', '.
							$db->quote($date).
							')';
						# Check if this is an UPDATE. If there is an ID, it's an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'publishers` SET
								`name` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $name))).','.
								' `contributor` = '.$db->quote($contributor_id).','.
								' `recent_contributor` = '.$db->quote($recent_cont_id).','.
								' `last_edit` = '.$db->quote($last_edit).','.
								((!empty($info)) ? ' `info` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $info))) : '').
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						# Run the sql query.
						$db_post=$db->query($sql);
						# Check if the database query was successful.
						if($db_post>0)
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['publisher']);
							$this->redirectPublisher($name, $message_action);
						}
						elseif(!empty($id))
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['publisher']);
							# Set a nice message for the user in a session.
							$_SESSION['message']="The publisher's record was unchanged.";
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
			throw new Exception('There was an error posting to the `publishers` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processPublisher

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processPublisherBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a publisher.
	 *
	 * @access	private
	 */
	private function processPublisherBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'audio',
				'file',
				'post',
				'product',
				'video'
			);
			# Set the resource value.
			$resource='publisher';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processPublisherBack

	/**
	 * processPublisherDelete
	 *
	 * Removes a publisher from the `publishers` table and the system. A wrapper method for the deletePublisher method in the Publisher class.
	 *
	 * @access	private
	 */
	private function processPublisherDelete()
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
			# Check if the publisher's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['publisher']) && isset($_GET['delete']))
			{
				# Check if the passed publisher id is an integer.
				if($validator->isInt($_GET['publisher'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_publisher']) && ($_POST['delete_publisher']==='delete')))
					{
						# Get the SubContent class. With this class, the File object can be accessed as well as the SubContent.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this publisher and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisPublisher($_GET['publisher']);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the Publisher object to a local variable.
							$publisher=$subcontent->getPublisher();
							# Set the publisher name to a local variable.
							$publisher_name=$publisher->getPublisher();
							# Set the "cleaned id to a local variable.
							$id=$subcontent->getPublisherID();
							# Get all subcontent with this publisher associated.
							$records_returned=$subcontent->getSubContent(NULL, NULL, 'branch', 'date', 'DESC', '`publisher` = '.$db->quote($id));
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
							# Check if this user has access to all records that have this publisher associated.
							if($access===TRUE)
							{
								# Get the Audio class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
								# Instantiate a new Audio object.
								$audio_obj=new Audio();
								# Count all audio records with this publisher associated.
								$count_audio=$audio_obj->countAllAudio('`publisher` = '.$db->quote($id));
								# Check if there where records associated with the publisher.
								if($count_audio>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this publisher associated.
							if($access===TRUE)
							{
								# Get the File class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
								# Instantiate a new File object.
								$file=new File();
								# Count all file records with this publisher associated.
								$count=$file->countAllFiles('all', NULL, 'AND `publisher` = '.$db->quote($id));
								# Check if there where records associated with the publisher.
								if($count>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user has access to all records that have this publisher associated.
							if($access===TRUE)
							{
								# Get the Video class.
								require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
								# Instantiate a new Video object.
								$video_obj=new Video();
								# Count all video records with this publisher associated.
								$count_videos=$video_obj->countAllVideos('`publisher` = '.$db->quote($id));
								# Check if there where records associated with the publisher.
								if($count_videos>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to editing main content.
									$access=$login->checkAccess(ALL_BRANCH_USERS);
								}
							}
							# Check if this user still has access to delete this publisher.
							if($access===TRUE)
							{
								# Get the Product class.
								require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
								# Instantiate a new Product object.
								$product=new Product();
								# Get all product with this publisher associated.
								$count=$product->countAllRecords('all', NULL, '`publisher` = '.$db->quote($id));
								# Check if this publisher a associated with any product.
								if($count>0)
								{
									# Set the records_returned variable to TRUE.
									$records_returned=TRUE;
									# Check if the user has access to this record.
									$access=$login->checkAccess(MAN_USERS);
								}
							}
							# Check if this user still has access to delete this publisher.
							if($access===TRUE)
							{
								if($records_returned===TRUE)
								{
									try
									{
										/** FIX THIS QUERY OR IMPLEMENT nnDB IN MYSQL **/
										# Remove the file from all `subcontent`, `content`, and `product` records.
										$db_submit=$db->query('UPDATE '.
											'`'.DBPREFIX.'audio`, '.
											'`'.DBPREFIX.'files`, '.
											'`'.DBPREFIX.'products`, '.
											'`'.DBPREFIX.'subcontent`, '.
											'`'.DBPREFIX.'videos` '.
											'SET '.
											DBPREFIX.'audio.publisher = NULL, '.
											DBPREFIX.'files.publisher = NULL, '.
											DBPREFIX.'products.publisher = NULL, '.
											DBPREFIX.'subcontent.publisher = NULL, '.
											DBPREFIX.'videos.publisher = NULL '.
											'WHERE '.
											DBPREFIX.'audio.publisher = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'files.publisher = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'products.publisher = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'subcontent.publisher = '.$db->quote($id).' '.
											'OR '.
											DBPREFIX.'videos.publisher = '.$db->quote($id));
								var_dump($db_submit);exit;
										if(empty($db_submit))
										{
											# Set a nice message to the session.
											$_SESSION['message']='The publisher "'.$publisher_name.'" (id: '.$id.') was NOT removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this publisher removed.';
											# Redirect the user back to the page.
											$this->redirectNoDelete();
										}
									}
									catch(ezDB_Error $ez)
									{
										throw new Exception('There was an error removing the publisher "'.$publisher_name.'" (id: '.$id.') from the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
									}
								}
								# Get rid of any CMS form sessions.
								unset($_SESSION['form']['publisher']);
								# Delete the publisher from the Database and set the returned value to a variable.
								$deleted=$publisher->deletePublisher($id, FALSE);
								if($deleted===TRUE)
								{
									$this->redirectPublisher($publisher_name, 'deleted');
								}
								else
								{
									# Set a nice message to the session.
									$_SESSION['message']='The publisher "'.$publisher_name.'" (id: '.$id.') was NOT deleted from the publisher list, though it WAS removed from all records that reference it. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this publisher removed.';
									# Redirect the user back to the page.
									$this->redirectNoDelete();
								}
							}
							# Set a nice message to the session.
							$_SESSION['message']='The publisher was NOT deleted. It is associated with records that you do not have the appropriate authorization to edit. If you still feel it is absolutely necessary to delete this publisher, please write an <a href="'.APPLICATION_URL.'webSupport/">email</a> with your thoughts.';
							# Redirect the user back to the page.
							$this->redirectNoDelete();
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The publisher was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('publisher');
						}
					}
					# Check if the form has been submitted to NOT delete the publisher.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_publisher']) && ($_POST['delete_publisher']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The publisher was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this publisher and request confirmation from the user with the appropriate warnings.
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
	} #==== End -- processPublisherDelete

	/**
	 * processPublisherSelect
	 *
	 * Processes a submitted form selecting a publisher to add to a post.
	 *
	 * @access	private
	 * @return	string
	 */
	private function processPublisherSelect()
	{
		# Bring the alert-title variable into scope.
		global $alert_title;
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Check if this is a publisher select page.
		if(isset($_GET['select']))
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && $_POST['publisher']=='Select Publisher')
			{
				# Check if the publisher id POST data was sent.
				if(isset($_POST['publisher_info']))
				{
					# Get the Populator object and set it to a local variable.
					$populator=$this->getPopulator();
					# Get the Publisher object and set it to a local variable.
					$publisher=$populator->getPublisherObject();
					$colon_pos=strpos($_POST['publisher_info'], ':');
					$publisher_id=substr($_POST['publisher_info'], 0, $colon_pos);
					$publisher_name=substr($_POST['publisher_info'], $colon_pos+1);
					# Set the publisher id to the Publisher data member.
					$publisher->setID($publisher_id);
					# Set the publisher name to the Publisher data member.
					$publisher->setPublisher($publisher_name);
					# Set the publisher's id to a variable.
					$publisher_id=$publisher->getID();
					# Set the publisher's name to a variable.
					$publisher_name=$publisher->getPublisher();
					# Redirect the User back to the form that sent them to fetch a publisher.
					$this->redirectPublisher($publisher_name, 'selected');
				}
				else
				{
					# Set the error message to the Document object datamember so that it may be displayed on the page.
					$doc->setError('Please select a publisher.');
					# Redirect the user to the page they were on with no POST or GET data.
					$doc->redirect(COMPLETE_URL);
				}
			}
		}
	} #==== End -- processPublisherSelect

	/**
	 * redirectPublisher
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire a publisher.
	 *
	 * @access	private
	 */
	private function redirectPublisher($publisher_name, $action, $default_message=TRUE)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Publisher object and set it to a local variable.
			$publisher=$populator->getPublisherObject();
			# Get the data for the new publisher.
			$publisher->getThisPublisher($publisher_name, FALSE);
			# Get the new publisher's id.
			$publisher_id=$publisher->getID();
			# Remove the publisher session.
			unset($_SESSION['form']['publisher']);
			# Check if the default message should be sent.
			if($default_message===TRUE)
			{
				# Set a nice message for the user in a session.
				$_SESSION['message']='The publisher "'.$publisher_name.'" was successfully '.$action.'!';
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
				# Set the video session publisher index name.
				$publisher_index='Publisher';
				if(isset($_SESSION['form']['post']))
				{
					# Set the form's name as "content".
					$origin_form='post';
					# Set the post session publisher index name.
					$publisher_index='PublisherID';
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
					# Set the product session publisher index name.
					$publisher_index='PublisherID';
				}
				# Set the session publisher id.
				$_SESSION['form'][$origin_form][$publisher_index]=$publisher_id;
				# Redirect the user to the original post page.
				$doc->redirect($_SESSION['form'][$origin_form]['FormURL'][0]);
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='publisher';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectPublisher

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
			# Get the Publisher object and set it to a local variable.
			$publisher=$populator->getPublisherObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['publisher']=
				array(
					'ID'=>$publisher->getID(),
					'FormURL'=>$form_url,
					'ContID'=>$publisher->getContID(),
					'Date'=>$publisher->getDate(),
					'RecentContID'=>$publisher->getRecentContID(),
					'LastEdit'=>$publisher->getLastEdit(),
					'Info'=>$publisher->getInfo(),
					'Publisher'=>$publisher->getPublisher(),
					'Unique'=>$populator->getUnique()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End PublisherFormProcessor class.