<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');

# Get the PostFormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'PostFormPopulator.php');


/**
 * PostFormProcessor
 *
 * The PostFormProcessor Class is used to create and process post forms.
 *
 */
class PostFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processPost
	 *
	 * Processes a submitted Post.
	 *
	 * @param	$data					An array of values tp populate the form with.
	 * @access	public
	 * @return	string
	 */
	public function processPost($data=array())
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
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Get the PostFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'PostFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populatPostForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('post');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('post');

			# Instantiate a new instance of PostFormPopulator.
			$populator=new PostFormPopulator();
			# Populate the form and set the SubContent data members for this post.
			$populator->populatePostForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			$display_delete_form=$this->processPostDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processPostBack();

			# Get the SubContent object from the PostFormPopulator and set it to a local variable.
			$sc=$populator->getSubContentObject();

			# Set the post's id to a variable.
			$id=$sc->getID();
			# Set the post's availability to a variable.
			$availability=$sc->getAvailability();
			# Set the post's wanted branches to a variable.
			$record_branches=$sc->getRecordBranches();
			# Set the post contributor's id to a variable.
			$contributor_id=$sc->getContID();
			# Set the post's recent contributor's id to a variable.
			$recent_cont_id=$sc->getRecentContID();
			# Set the post's posting date to a variable.
			$date=$sc->getDate();
			# Set the post's last edit date to a variable.
			$last_edit=$sc->getLastEdit();
			# Set the post's Facebook value to a variable.
			$facebook=$populator->getFacebook();
			# Set the post's associated file id to a variable.
			$file_id=$sc->getFileID();
			# Set the post's hide status to a variable.
			$hide=$sc->getHide();
			# Set the post's associated image id to a variable.
			$image_id=$sc->getImageID();
			# Set the post's associated institution id to a variable.
			$institution_id=$sc->getInstitutionID();
			# Set the post's related link to a variable.
			$link=$sc->getLink();
			# Set the post's premium status to a variable.
			$premium=$sc->getPremium();
			# Set the post's publisher id to a variable.
			$publisher_id=$sc->getPublisherID();
			# Set the site name to a variable.
			$site_name=$main_content->getSiteName();
			# Set the post's text to a variable.
			$text=$sc->getText();
			# Set the post's text language to a variable.
			$text_language_id=$sc->getTextLanguage();
			# Set the post's text translation to a variable.
			$text_trans=$sc->getTextTrans();
			# Set the post's text translation language to a variable.
			$trans_language_id=$sc->getTransLanguage();
			# Set the post's title to a variable.
			$title=$sc->getTitle();
			# Set the post's Twitter value to a variable.
			$twitter=$populator->getTwitter();
			# Set the post's unique status to a variable.
			$unique=$populator->getUnique();
			# Set the post's visibility status to a variable.
			$visibility=$sc->getVisibility();

			# Check if the form has been submitted and the submit button was the "Post" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['post']) && (($_POST['post']==='Post') OR ($_POST['post']==='Update'))))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('post');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the title field was empty (or less than 2 characters or more than 255 characters long).
				$empty_title=$fv->validateEmpty('title', 'Please enter a title for the post.', 2, 255);
				$branches_validator=trim($record_branches, '-');
				# Check if at least one project was selected (the post branch string should NOT be empty.)
				if(empty($branches_validator))
				{
					# Set an error.
					$fv->setErrors('You must select at least one branch for your post.');
				}

				# Check if the image is an id.
				if($validator->isInt($image_id)!==TRUE)
				{
					# Get the image info from the `images` table.
					$sc->getThisImage($image_id, FALSE);
					# Reset the variable with the id.
					$image_id=$sc->getImageID();
				}
				# Check if the text language is an id.
				if($validator->isInt($text_language_id)!==TRUE)
				{
					# Get the language info from the `languages` table.
					$sc->getThisLanguage($text_language_id, FALSE);
					# Reset the variable with the id.
					$text_language_id=$sc->getLanguageID();
				}
				# Check if the text translation language is an id.
				if($validator->isInt($trans_language_id)!==TRUE)
				{
					# Get the language info from the `languages` table.
					$sc->getThisLanguage($trans_language_id, FALSE);
					# Reset the variable with the id.
					$trans_language_id=$sc->getLanguageID();
				}

				# Check for errors to display so that the script won't go further.
				if($fv->checkErrors()===TRUE)
				{
					# Create a variable to the error heading.
					$alert_title='Resubmit the form after correcting the following errors:';
					# Set the FormValidator class errors to a variable.
					$error=$fv->displayErrors();
					# Set the error message to the Document object data member so that it me be displayed on the page.
					$doc->setError($error);
				}
				# The post is considered "unique" and may be added to the database.
				else
				{
					# Check if the post data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the Subcontent table in the Database.
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
						$search->performSearch($terms, 'subcontent', $fields, NULL, $filter);
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
								# Instantiate a new SubContent object.
								$dup_content=new SubContent();
								# Get the info for this record.
								$dup_content->getThisSubContent($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_content->getID()]=array(
									'id'=>$dup_content->getID(),
									'availability'=>$dup_content->getAvailability(),
									'branch'=>$dup_content->getRecordBranches(),
									'contributor'=>$dup_content->getContID(),
									'date'=>$dup_content->getDate(),
									'file'=>$dup_content->getFileID(),
									'hide'=>$dup_content->getHide(),
									'image'=>$dup_content->getImageID(),
									'institution'=>$dup_content->getInstitutionID(),
									'link'=>$dup_content->getLink(),
									'premium'=>$dup_content->getPremium(),
									'publisher'=>$dup_content->getPublisherID(),
									'text'=>$dup_content->getText(),
									'text_language'=>$dup_content->getTextLanguage(),
									'text_trans'=>$dup_content->getTextTrans(),
									'trans_language'=>$dup_content->getTransLanguage(),
									'title'=>$dup_content->getTitle(),
									'visibility'=>$dup_content->getVisibility()
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
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					if($unique==1)
					{
						# Check if this is an INSERT or an UPDATE. If there is no ID, it's an INSERT.
						if(empty($id))
						{
							# Create the default value for the message action.
							$message_action='added';
							$sql='INSERT INTO `'.DBPREFIX.'subcontent` ('.
								'`title`,'.
								' `link`,'.
								' `file`,'.
								' `availability`,'.
								' `visibility`,'.
								' `date`,'.
								' `premium`,'.
								' `branch`,'.
								' `institution`,'.
								' `publisher`,'.
								' `text_language`,'.
								' `text`,'.
								' `trans_language`,'.
								' `text_trans`,'.
								' `hide`,'.
								' `image`,'.
								' `contributor`,'.
								' `recent_contributor`,'.
								' `last_edit`'.
								') VALUES ('.
								$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
								((!empty($link)) ? ' '.$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $link))).',' : ' NULL,').
								((!empty($file_id)) ? ' '.$db->quote($file_id).',' : ' NULL,').
								' '.$db->quote($availability).','.
								(($visibility!==NULL) ? (($visibility!=0) ? ' '.$db->quote((int)$visibility.'-').',' : ' '.$db->quote(0).',' ) : ' NULL,').
								' '.$db->quote($date).','.
								(($premium!==NULL) ? ' '.$db->quote(0).',' : ' NULL,').
								' '.$db->quote($db->escape($record_branches)).','.
								' '.$db->quote($institution_id).','.
								((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : ' NULL,').
								' '.$db->quote($text_language_id).','.
								((!empty($text)) ? ' '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($text))))).',' : ' \'\',').
								' '.$db->quote($trans_language_id).','.
								((!empty($text_trans)) ? ' '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($text_trans))))).',' : ' \'\',').
								(($hide!==NULL) ? ' '.$db->quote(0).',' : ' NULL,').
								(($image_id!==NULL) ? ' '.$db->quote($image_id).',' : ' NULL,').
								' '.$db->quote($contributor_id).','.
								((!empty($recent_cont_id)) ? ' '.$db->quote($recent_cont_id).',' : ' NULL,').
								((!empty($last_edit)) ? ' '.$db->quote($last_edit) : ' NULL').
								')';
						}
						else
						{
							# Reset the value for the message action.
							$message_action='updated';
							$sql='UPDATE `'.DBPREFIX.'subcontent` SET'.
								' `title` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).','.
								' `link` = '.((!empty($link)) ? ' '.$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $link))).',' : ' NULL,').
								' `file` = '.((!empty($file_id)) ? ' '.$db->quote($file_id).',' : ' NULL,').
								' `availability` = '.$db->quote($availability).','.
								' `visibility` = '.(($visibility!==NULL) ? (($visibility!=0) ? ' '.$db->quote((int)$visibility.'-').',' : ' '.$db->quote(0).',' ) : ' NULL,').
								' `date` = '.$db->quote($date).','.
								' `premium` = '.(($premium!==NULL) ? ' '.$db->quote(0).',' : ' NULL,').
								' `branch` = '.$db->quote($db->escape($record_branches)).','.
								' `institution` = '.$db->quote($institution_id).','.
								' `publisher` = '.((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : ' NULL,').
								' `text_language` = '.$db->quote($text_language_id).','.
								' `text` = '.((!empty($text)) ? ' '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($text))))).',' : ' \'\',').
								' `trans_language` = '.$db->quote($trans_language_id).','.
								' `text_trans` = '.((!empty($text_trans)) ? ' '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($text_trans))))).',' : ' \'\',').
								' `hide` = '.(($hide!==NULL) ? ' '.$db->quote(0).',' : ' NULL,').
								' `image` = '.(($image_id!==NULL) ? ' '.$db->quote($image_id).',' : ' NULL,').
								' `contributor` = '.$db->quote($contributor_id).','.
								' `recent_contributor` = '.((!empty($recent_cont_id)) ? ' '.$db->quote($recent_cont_id).',' : ' NULL,').
								' `last_edit` = '.((!empty($last_edit)) ? ' '.$db->quote($last_edit) : ' NULL').
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						# Run the sql query.
						$db_post=$db->query($sql);
						# Check if the database query was successful.
						if(TRUE)//if($db_post>0)
						{
							$id=$db->get_insert_id();
							# Check if the visibility allows posting to social networks.
							if($visibility===NULL)
							{
								# Check if the post should be posted on Twitter.com or Facebook.com.
								if($twitter==='0' OR $facebook==='post')
								{
									# Get the API Class.
									require_once Utility::locateFile(MODULES.'API'.DS.'API.php');
									# Get the Branch class.
									require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
									# Instantiate a new Branch object.
									$branch=new Branch();
									# Trim the post branches.
									$the_branch=trim($record_branches, '-');
									# Explode the post branches to an array.
									$the_branch=explode('-', $the_branch);
									# Get the branch info from the database of the first branch in the array.
									$branch->getThisBranch($the_branch[0]);
									# Set the branch domain to a variable.
									$branch_domain=$branch->getDomain();
									$post_url='http://'.$branch_domain.'/?post='.$id;
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
							# Unset the CMS session data.
							unset($_SESSION['form']);
							# Set a nice message for the user in a session.
							$_SESSION['message']='Your post was successfully '.$message_action.'!';
							# Redirect the user to the page they were on with no POST or GET data.
							$this->redirectNoDelete('post');
						}
						elseif(!empty($id))
						{
							# Unset the CMS session data.
							unset($_SESSION['form']);
							# Set a nice message for the user in a session.
							$_SESSION['message']='The post was unchanged.';
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
			throw new Exception('There was an error posting to the `subcontent` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processPost

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processPostDelete
	 *
	 * Removes a post from the `subcontent`. A wrapper method for the deleteSubContent method in the SubContent class.
	 *
	 * @access	private
	 */
	private function processPostDelete()
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Explicitly set the dlete variable to FALSE; the POST will NOT be deleted.
			$delete=FALSE;
			# Check if the post's id was pssed via GET data and if the GET data indicates this is a delete.
			if(isset($_GET['post']) && isset($_GET['delete']))
			{
				# Check if the passed post id is an interger.
				if($validator->isInt($_GET['post'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && isset($_POST['delete_post']) && ($_POST['delete_post']==='delete'))
					{
						# Get rid of any CMS session data.
						unset($_SESSION['form']);
						# Get the SubContent class.
						require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
						# Instantiate a new SubContent object.
						$subcontent=new SubContent();
						# Get the info for this record and set the return boolean to a variable.
						$record_retrieved=$subcontent->getThisSubContent($_GET['post']);
						# Check if the record was actually returned.
						if($record_retrieved!==FALSE)
						{
							# Delete the post from the Database and set the returned value to a variable.
							$deleted=$subcontent->deleteSubContent($subcontent->getID());
							# Check if the post was deleted.
							if(!empty($deleted))
							{
								# Set a nice message to the session.
								$_SESSION['message']='The post was successfully deleted.';
								# Redirect the user back to the page without GET or POST data.
								$this->redirectNoDelete('post');
							}
							else
							{
								# Set a nice message to the session.
								$_SESSION['message']='The post was NOT deleted.';
								# Redirect the user back to the page.
								$this->redirectNoDelete();
							}
							$_SESSION['message']='The post was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('post');
						}
					}
					# Check if the form has been submitted to NOT delete the post.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_post']) && ($_POST['delete_post']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The post was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this post and request confirmation from the user with the appropriate warnings.
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
	} #==== End -- processPostDelete

	/**
	 * processPostBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a file.
	 *
	 * @access	private
	 */
	private function processPostBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'post'
			);
			# Set the resource value.
			$resource='post';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processPostBack

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
			# Get the SubContent object and set it to a local variable.
			$sc=$populator->getSubContentObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['post']=
				array(
					'ID'=>$sc->getID(),
					'FormURL'=>$form_url,
					'Availability'=>$sc->getAvailability(),
					'RecordBranches'=>$sc->getRecordBranches(),
					'ContID'=>$sc->getContID(),
					'RecentContID'=>$sc->getRecentContID(),
					'Date'=>$sc->getDate(),
					'LastEdit'=>$sc->getLastEdit(),
					'Facebook'=>$populator->getFacebook(),
					'FileID'=>$sc->getFileID(),
					'Hide'=>$sc->getHide(),
					'ImageID'=>$sc->getImageID(),
					'InstitutionID'=>$sc->getInstitutionID(),
					'Link'=>$sc->getLink(),
					'Premium'=>$sc->getPremium(),
					'PublisherID'=>$sc->getPublisherID(),
					'Text'=>$sc->getText(),
					'TextLanguage'=>$sc->getTextLanguage(),
					'TextTrans'=>$sc->getTextTrans(),
					'TransLanguage'=>$sc->getTransLanguage(),
					'Title'=>$sc->getTitle(),
					'Twitter'=>$populator->getTwitter(),
					'Unique'=>$populator->getUnique(),
					'Visibility'=>$sc->getVisibility()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End PostFormProcessor class.