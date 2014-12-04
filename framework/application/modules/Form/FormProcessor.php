<?php /* Requires PHP5+ */

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');


/**
 * FormProcessor
 *
 * The FormProcessor Class is used create and process Site specific forms and must be edited to work correctly.
 *
 */
class FormProcessor
{
	/*** data members ***/

	private $duplicates=array();
	private $form_action=NULL;
	private $max_file_size=NULL;
	private $populator=NULL;
	private $target='_top';
	private $upload=FALSE;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setDuplicates
	 *
	 * Sets the data member $duplicates.
	 *
	 * @param		$duplicates (The potential duplicates returned from a duplicate search to display.)
	 * @access	protected
	 */
	protected function setDuplicates($duplicates)
	{
		# Set the variable.
		$this->duplicates=$duplicates;
	} #==== End -- setDuplicates

	/**
	 * setFormAction
	 *
	 * Sets the data member $form_action.
	 *
	 * @param		$url 		(The url where to send the form-data when a form is submitted.)
	 * @access	public
	 */
	public function setFormAction($url)
	{
		# Check if the passed URL is empty.
		if(!empty($url))
		{
			# Clean it up.
			$url=trim($url);
		}
		else
		{
			# Explicitly set the value to NULL.
			$url=NULL;
		}
		# Set the data member.
		$this->form_action=$url;
	} #==== End -- setFormAction

	/**
	 * setMaxFileSize
	 *
	 * Sets the data member $max_file_size.
	 *
	 * @param		$bytes
	 * @access	public
	 */
	public function setMaxFileSize($bytes=NULL)
	{
		# Check if the passed value is NULL.
		if($bytes!==NULL)
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed value is an integer.
			if($validator->isInt($bytes)===TRUE)
			{
				# Explicitly make the passed value an integer.
				$bytes=(int)$bytes;
			}
			else
			{
				# Explicitly set the passed value to NULL.
				$bytes=NULL;
			}
		}
		# Set the data member to NULL.
		$this->max_file_size=$bytes;
	} #==== End -- setMaxFileSize

	/**
	 * setPopulator
	 *
	 * Sets the data member $populator.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setPopulator($object)
	{
		# Check if the passed value is empty and an object.
		if(!empty($object) && is_object($object))
		{
			$this->populator=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->populator=NULL;
		}
	} #==== End -- setPopulator

	/**
	 * setTarget
	 *
	 * Sets the data member $target.
	 *
	 * @param		$target (The target to send the form-data when a form is submitted. ie. _top, _blank, etc)
	 * @access	public
	 */
	public function setTarget($target)
	{
		# Check if the passed target is empty.
		if(!empty($target))
		{
			# Clean it up.
			$target=trim($target);
		}
		else
		{
			# Explicitly set the value to the default.
			$target='_top';
		}
		# Set the data member.
		$this->target=$target;
	} #==== End -- setTarget

	/**
	 * setUpload
	 *
	 * Sets the data member $upload.
	 *
	 * @param		Boolean		$value (Whether or not the form should allow uploads.)
	 * @access	public
	 */
	public function setUpload($value=FALSE)
	{
		# Check if the passed $value is TRUE.
		if($value!==TRUE)
		{
			# Explicitly set the value to FALSE.
			$value=FALSE;
		}
		$this->upload=$value;
	} #==== End -- setUpload

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getDuplicates
	 *
	 * Returns the data member $duplicates.
	 *
	 * @access	public
	 */
	public function getDuplicates()
	{
		return $this->duplicates;
	} #==== End -- getDuplicates

	/**
	 * getFormAction
	 *
	 * Returns the data member $form_action.
	 *
	 * @access	public
	 */
	public function getFormAction()
	{
		return $this->form_action;
	} #==== End -- getFormAction

	/**
	 * getMaxFileSize
	 *
	 * Returns the data member $max_file_size.
	 *
	 * @access	public
	 */
	public function getMaxFileSize()
	{
		return $this->max_file_size;
	} #==== End -- getMaxFileSize

	/**
	 * getPopulator
	 *
	 * Returns the data member $populator.
	 *
	 * @access	public
	 */
	public function getPopulator()
	{
		return $this->populator;
	} #==== End -- getPopulator

	/**
	 * getTarget
	 *
	 * Returns the data member $target.
	 *
	 * @access	public
	 */
	public function getTarget()
	{
		return $this->target;
	} #==== End -- getTarget

	/**
	 * getUpload
	 *
	 * Returns the data member $upload.
	 *
	 * @access	public
	 */
	public function getUpload()
	{
		return $this->upload;
	} #==== End -- getUpload

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * findAuthorization
	 *
	 * Returns an array of the Authorizations of a User.
	 *
	 * @param	array $branches			An array of branches to check.
	 * @param	integer $id				The User's ID
	 * @access	public
	 * @return	string
	 */
	public function findAuthorization($branch_ids, $id=NULL)
	{
		# Brign the Login object into scope.
		global $login;
		# Brign the User object into scope.
		global $user;

		$branch_ids=(array)$branch_ids;

		if($id===NULL)
		{
			$id=$user->findUserID();
		}

		# Get the user's access levels.
		$levels=$user->findUserLevel($id);

		# Get the Branch class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
		# Instantiate a new Branch object.
		$branch=new Branch();

		$auth=array();

		$branch_levels=array();

		foreach($branch_ids as $branch_num)
		{
			# Retrieve the data for this branch from the `branches` table.
			//$branch->getThisBranch($branch_num);
			# Change the branch name to all caps and replace spaces(' ') with underscores('_').
			//$name=strtoupper(str_replace(' ', '_', $branch->getBranch()));
			# Change the value of the branch number to the branch "admin" number (always ends with '1'.)
			$branch_levels[$branch_num.'_admin']=substr_replace($branch_num, 1, -1, 1);
			# Change the value of the branch number to the branch "user" number (always ends with '2'.)
			$branch_levels[$branch_num.'_user']=substr_replace($branch_num, 2, -1, 1);
			# Change the value of the branch number to the branch "not authorized" number (always ends with '3'.)
			$branch_levels[$branch_num.'_never']=substr_replace($branch_num, 3, -1, 1);
			# Change the value of the branch number to the branch candidate number (always ends with '4'.)
			$branch_levels[$branch_num.'_candidate']=substr_replace($branch_num, 4, -1, 1);

			if(in_array($branch_levels[$branch_num.'_admin'], $levels)===TRUE)
			{
				$auth[$branch_num]=TRUE;
			}
			elseif(in_array($branch_levels[$branch_num.'_user'], $levels)===TRUE)
			{
				$auth[$branch_num]=TRUE;
			}
			elseif(in_array($branch_levels[$branch_num.'_candidate'], $levels)===TRUE)
			{
				$auth[$branch_num]='candidate';
			}
			elseif(in_array($branch_levels[$branch_num.'_never'], $levels)===TRUE)
			{
				$auth[$branch_num]='not authorized';
			}
			else
			{
				$auth[$branch_num]=FALSE;
			}
		}
		//print_r($branch_levels);exit;
		return $auth;
	} #==== End -- findAuthorization

	/**
	 * processAuthorize
	 *
	 * Authorizes a user for the selected branch access levels. Emails the user with the news.
	 *
	 * @access	public
	 */
	public function processAuthorize()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the User object into scope.
		global $user;
		# Bring the content object into scope.
		global $main_content;

		try
		{
			$username=$user->getUsername();
			$id=$user->getID();
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST))
			{
				# Create an empty array to hold messages to display to the User.
				$message=array();
				# Get the Branch class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
				# Instantiate a new Branch object.
				$branch=new Branch();
				# Retrieve all branches from the branches table.
				$retrieve_branches=$branch->getBranches(NULL, '`id`, `branch`');
				# Get all retrieved branches.
				$all_branches=$branch->getAllBranches();
				# Get the user's access levels.
				$levels=$user->findUserLevel($id);
				# Format the User's levels into a string suitable for the MySQL field `level` in the User table.
				$levels='-'.implode('-', $levels).'-';
				# Indicate as the defaul that WordPress should NOT be updated.
				$update_WordPress=FALSE;
				# Loop through the branch rows.
				foreach($all_branches as $row)
				{
					# Set the branches and their id's to the branches array.
					$branch_id=$row->id;
					$branch_name=$row->branch;

/*
					# Check that the POST data is set for this branch and that it holds acceptable values.
					if(isset($_POST[$branch_id]) &&
						(($_POST[$branch_id]=='0') OR
						($_POST[$branch_id]=='2') OR
						($_POST[$branch_id]=='3') OR
						($_POST[$branch_id]=='4')))
					{
						$good=TRUE;
					}
					else {
						continue;
					}
*/

					# Check if there was POST data sent for this branch, if the checkbox is checked (on).
					if(isset($_POST[$branch_id]) && $_POST[$branch_id]=='on')
					{
						# Create empty array.
						$branch_ids=array();
						# Loops through array.
						foreach($_POST as $key=>$value)
						{
							# If the array key is an integer.
							if(is_int($key))
							{
								# Replace the 0 in the branch ID (50, 60, 70) with a 2 (52, 62, 72).
								$branch_user=substr_replace($key, 2, -1, 1);
								# Assign the new ID to the value of the array.
								$branch_ids[$key]=$branch_user;
							}
						}
						# Combine the branch IDs.
						$new_branch_level=implode($branch_ids, '-').'-';

						# Check if the new_branch_level is in the $levels string.
						if(strpos($levels, $new_branch_level)===FALSE)
						{
							$levels.=$new_branch_level;
						}
					}
					else
					{
						# Create variables holding each level of access for the current branch.
						$branch_admin=substr_replace($row->id, 1, -1, 1).'-';
						$branch_user=substr_replace($row->id, 2, -1, 1).'-';
						$branch_unauthorized=substr_replace($row->id, 3, -1, 1).'-';
						$branch_candidate=substr_replace($row->id, 4, -1, 1).'-';

						# Replace the branch ID with an empty value to remove their branch permission.
						$levels=str_replace($branch_admin, '', $levels);
						$levels=str_replace($branch_user, '', $levels);
						$levels=str_replace($branch_unauthorized, '', $levels);
						$levels=str_replace($branch_candidate, '', $levels);
					}

/*
						# Check if there is a WordPress installation.
						if(WP_INSTALLED===TRUE && $branch_id==90)
						{
							# Set the WordPress access level to a variable for individual processing.
							$update_WordPress=substr_replace($branch_id, $_POST[$branch_id], -1, 1);
						}

						# Create variables holding each level of access for the current branch.
						$branch_admin=substr_replace($branch_id, 1, -1, 1).'-';
						$branch_user=substr_replace($branch_id, 2, -1, 1).'-';
						$branch_unauthorized=substr_replace($branch_id, 3, -1, 1).'-';
						$branch_candidate=substr_replace($branch_id, 4, -1, 1).'-';

						$new_branch_level='';
						# Check if the user doesn't have access to this branch.
						if($_POST[$branch_id]!=0)
						{
							$new_branch_level=substr_replace($branch_id, $_POST[$branch_id], -1, 1).'-';
						}

						$levels=str_replace($branch_admin, $new_branch_level, $levels);
						$levels=str_replace($branch_user, $new_branch_level, $levels);
						$levels=str_replace($branch_unauthorized, $new_branch_level, $levels);
						$levels=str_replace($branch_candidate, $new_branch_level, $levels);

						# Check if there is a new_branch_level for the user.
						if(!empty($new_branch_level))
						{
							# Check if the new_branch_level is in the $levels string.
							if(strpos($levels, $new_branch_level)===FALSE)
							{
								$levels.=$new_branch_level;
							}
						}
*/
				}

				# Update the database with the new levels.
				$user->updateUser(array('ID'=>$id), array('level'=>$levels));

				# Check if WordPress should be updated.
				if($update_WordPress!==FALSE)
				{
					# Get the WordPresUser class.
					require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
					# Instantiate a new WordPressUser instance.
					$wp_user=new WordPressUser();
					$wp_user->updateWP_UserAccessLevel($username, $update_WordPress);
				}

				# Get the user's display name.
				$display_name=$user->findDisplayName($id);
				# Create the branch admin email constant and set it to the $to variable.
				$to=$user->findEmail($id);
				# Set the email subject.
				$subject=DOMAIN_NAME.': Your authorizations have been updated.';

				# Create the body of the message.
				$body='Hello '.$display_name.','."<br />\n<br />\n";
				$body.='The authorization settings on your account have been updated. You may log in to your account at <a href="'.SECURE_URL.'MyAccount/">'.SECURE_URL.'MyAccount/</a> to view your account settings.'."<br />\n<br />\n";
				$body.='Thank you';
				### DEBUG ###
				if(DEBUG_APP===TRUE)
				{
					$doc->sendEmail($subject, ADMIN_EMAIL, $body);
				}
				else
				{
					$doc->sendEmail($subject, $to, $body);
				}
				$message='You have successfully edited the access levels for '.$username.'. An email has been sent to '.$username.' alerting them to the change in their account.';
				$doc->setError($message);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processAuthorize

	/**
	 * processAuthRequest
	 *
	 * Emails the appropriate admin/manager of a request for authorization on an aspect of the site.
	 *
	 * @param	array $branches_emails	An array where the key is the POST data field ['branch number'] to check and the value is the email address to send the request to.
	 *										The Value may be an array as well.
	 * @access	public
	 */
	public function processAuthRequest()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Database object into scope.
		global $user;

		try
		{
			$username=$user->findUsername();
			$id=$user->findUserID();
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST))
			{
				# Create an empty array to hold messages to display to the User.
				$message=array();
				# Get the Branch class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
				# Instantiate a new Branch object.
				$branch=new Branch();
				# Retrieve all branches from the branches table.
				$retrieve_branches=$branch->getBranches(NULL, '`id`, `branch`');
				# Get all retrieved branches.
				$all_branches=$branch->getAllBranches();
				# Get the user's access levels.
				$levels=$user->findUserLevel($id);
				# Format the User's levels into a string suitable for the MySQL field `level` in the User table.
				$levels='-'.implode('-', $levels).'-';
				# Loop through the branch rows.
				foreach($all_branches as $row)
				{
					# Set the branches and their id's to the branches array.
					$branch_id=$row->id;
					$branch_name=$row->branch;
					# Check if there was POST data sent for this branch.
					if(isset($_POST[$branch_id]) && $_POST[$branch_id]=='on')
					{
						# Change the value of the branch number to the branch candidate number (always ends with '4'.)
						$candiate_num=substr_replace($branch_id, 4, -1, 1);
						# Add the branch candidate number to the levels string.
						$levels=$levels.$candiate_num.'-';
						# Update the database with the new levels.
						$user->updateUser(array('ID'=>$id), array('level'=>$levels));
						# Create the branch admin email constant and set it to the $to variable.
						$to=constant(str_replace(' ', '_', strtoupper($branch_name)).'_ADMIN_EMAIL');
						# Set the email subject.
						$subject=DOMAIN_NAME.': A request for '.$branch_name.' authorization.';
						$body='The user, '.$username.' has requested to be authorized on '.$branch_name.'<br />'."\n";
						$body.='Please log into your account at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a> to process this authorization request at your earliest convenience.<br />'."\n";
						$body.='Thank you';
						$doc->sendEmail($subject, $to, $body);
						$message[]='You have requested to be authorized to add content to or edit '.$branch_name;
						# Implode the message array into a string separated into list items.
						$user_message='<ul><li>'.implode('<li></li>', $message).'</li></ul>'.'<strong>The manager(s) have been notified. You will be sent an email notifying you of the results.</strong>';
					}
				}
				$doc->setError($user_message);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processAuthRequest

	/**
	 * processDeleteAccount
	 *
	 * Delete's the user's account.
	 *
	 * @param	integer $id  				The User's ID
	 * @access	public
	 */
	public function processDeleteAccount($id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;
		# Bring the user object into scope.
		global $user;

		$current_user=$user->findUserID();
		# Check if the passed User ID is empty.
		if(empty($id))
		{
			# Find the logged in User's ID and set it to the variable.
			$id=$current_user;
		}
		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# Check if the POST data is set and that the value is the string sent from the form.
			if(isset($_POST['delete']) && ($_POST['delete']==='Delete Account'))
			{
				try
				{
					# Delete from the users table.
					$user->deleteAccount($id);
					# Check if the user being deleted is the user deleting.
					if($current_user===$id)
					{
						# Log the User out (this will redirect them as well.)
						$login->logout();
					}
					# Redirect the deleting user.
					$_SESSION['message']='The account has been successfully deleted.';
					$doc->redirect(ADMIN_URL.'ManageUsers/');
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('There was an error deleting User ID# '.$id.' from the Database: '.$ez->error.', code: '.$ez->errno.'<br />
						Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
				}
			}
		}
	} #==== End -- processDeleteAccount

	/**
	 * processPassword
	 *
	 * Changes the User's password.
	 *
	 * @param 	string	$id (The User's ID.)
	 * @access	public
	 */
	public function processPassword($id=NULL)
	{
		# Bring the Login object into scope.
		global $login;

		# Create global variables to use outside the method.
		global $checked_value;

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			$checked_value=((isset($_POST['email_password'])) ? 'checked' : '');

			# Instantiate a FormValidator object
			$updated=$login->changePassword($id);
		}
	} #==== End -- processPassword

	/**
	 * processPrivacy
	 *
	 * Updates the Privacy settings in the Database for the logged in user upon submission of the form.
	 *
	 * @param		array 	$branch_ids		(Each value must be the POST Data index that exactly matches the name of the field in the database to be updated.)
	 * @param		integer	$id  					(The User's ID)
	 * @access	public
	 */
	public function processPrivacy($branch_ids, $id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring the User object into scope.
		global $user;

		# Create global variables to use outside the method.
		global $address;

		$branch_ids=(array)$branch_ids;

		# Check if the passed ID is empty.
		if(empty($id))
		{
			# Find the user's ID.
			$id=$user->findUserID();
		}

		# Create an empty array to hold the accepted branch id's.
		$notify_ids=array();

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			try
			{
				# Check if the User selected 'newsletter'.
				if(isset($_POST['newsletter']) && ($_POST['newsletter']=='on'))
				{
					# Set the newsletter value to 0.
					$newsletter=0;
				}
				else
				{
					# Set the newsletter value to the string "NULL".
					$newsletter=NULL;
				}
				# Loop through the passed branch id's.
				foreach($branch_ids as $branch_num)
				{
					# Check if POST data was sent for this branch id.
					if(isset($_POST[$branch_num]) && ($_POST[$branch_num]=='on'))
					{
						# Set the branch id to the accepted branch id's array.
						$notify_ids[]=$branch_num;
					}
				}
				# Check if $notify_ids is still empty.
				if(empty($notify_ids))
				{
					# Set the value to the string "NULL".
					$notify_ids=NULL;
				}
				else
				{
					# Format the branch id's for the `notify` table.
					$notify_ids='-'.implode('-', $notify_ids).'-';
				}
				# Check if the User selected 'questions'.
				if(isset($_POST['questions']) && ($_POST['questions']=='on'))
				{
					# Set the questions value to 0.
					$questions=0;
				}
				else
				{
					# Set the questions value to the string "NULL".
					$questions=NULL;
				}
				# Check if the POST data related to contributor privacy was sent.
				if(isset($_POST['cont_privacy']))
				{
					switch($_POST['cont_privacy'])
					{
					case 'hide':
						# Set the contributor privacy setting to NULL(hide the contributor).
						$cont_privacy=NULL;
						break;
					case 'users':
						# Set the contributor privacy setting to 1(display to Users only).
						$cont_privacy=1;
						break;
					default:
						# Set the contributor privacy setting to 0(display to all).
						$cont_privacy=0;
					}
					# Get the Contibutor class.
					require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
					# Instantiate a new Contributor object.
					$contributor=new Contributor();
					# Update the `privacy` field in the `contributors` table.
					$contributor->updateContributor(array('user'=>$id), array('privacy'=>$cont_privacy));
				}

				# Update the `newsletter`, `notify`, and `questions` fields in the `users` table.
				$user->updateUser(array('ID'=>$id), array('newsletter'=>$newsletter, 'notify'=>$notify_ids, 'questions'=>$questions));
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
	} #==== End -- processPrivacy

	/**
	 * processSearch
	 *
	 * Checks if the search form has been submitted and processes it, returning the results of the search.
	 *
	 * @param		$terms (The term we're searching for.)
	 * @param		$filter (Fields and or terms we would like exluded.)
	 * @access	public
	 */
	public function processSearch($index='searchterms', $filter=NULL, $tables=NULL, $fields=NULL, $id_names='id')
	{
		# Check if the search form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			# Get the Search class.
			require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
			# Instantiate a new Search object.
			$search=new Search($tables, $fields, $id_names);
			$tables=$search->getTables();
			$fields=$search->getFields();
			$id=$search->getID_Names();
			$terms=$_POST[$index];
			$search_results=array();
			foreach($tables as $table)
			{
				$results[$table]=$search->performSearch($terms, $table, $fields[$table], $id[$table], $filter=NULL);
				$search_results=array_merge($search_results, $results);
			}
			$search->setAllResults($search_results);
		}
	} #==== End -- processSearch

	/**
	 * processUsername
	 *
	 * Changes the User's username.
	 *
	 * @param 	string	$id (The User's ID.)
	 * @access	public
	 */
	public function processUsername($id=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the User object into scope.
		global $user;

		# Create global variables to use outside the method.
		global $username;
		global $checked_value;

		# Check if the form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			$checked_value=((isset($_POST['email_username'])) ? 'checked' : '');
			# Check if the ID is empty.
			if(empty($id))
			{
				# Get the logged in User's ID.
				$id=$user->findUserID();
				$message_pre='Your';
				$body_pre='Per your request, your username has been changed';
			}
			else
			{
				$message_pre='The';
				$body_pre='An admin at '.DOMAIN_NAME.' has changed your username';
			}

			# Instantiate a FormValidator object
			$validate=new FormValidator();

			# Clean the POST Data.
			$username=$db->sanitize($_POST['username']);
			$username_conf=$db->sanitize($_POST['confirmed_username']);

			# Validate if the username POST data is empty.
			$empty_username=$validate->validateEmpty('username', 'Please enter a username that is at least 5 characters long.', 5, 64);

			# Check if the username was not empty.
			if($empty_username===FALSE)
			{
				# Check if the username is unique.
				$unique=$this->checkUnique('users', 'username', $username, ' AND `ID` != '.$db->quote($id));
				if($unique===FALSE)
				{
					# Set an message to display to the User.
					$validate->setErrors('The username '.$username.' is already in use, please choose another.');
				}
			}

			# Validate if the username confirmation POST data is empty.
			$empty_username_conf=$validate->validateEmpty('confirmed_username', 'Please confirm your new username.', 5, 64);

			# Check if the username and the confirmation were not empty and that the username is unique.
			if(($empty_username===FALSE) && ($empty_username_conf===FALSE) && ($unique!==FALSE))
			{
				# Check if the username and the confirmation match.
				if($username!=$username_conf)
				{
					# Set a message to display to the User.
					$validate->setErrors('The usernames you entered did not match. Please try again.');
				}
			}

			# Check for errors.
			if($validate->checkErrors()===TRUE)
			{
				# Display errors
				$error='<h3>Resubmit the form after correcting the following errors:</h3>';
				$error.=$validate->displayErrors();
				$doc->setError($error);
			}
			else
			{
				# Check if there is a WordPress installation.
				if(WP_INSTALLED===TRUE)
				{
					try
					{
						# Find the User's username and set it to a variable.
						$current_username=$user->findUsername($id);
						# Get the WordPressUser class.
						require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
						# Instantiate a new WordPressUser object.
						$wp_user=new WordPressUser();
						# Get the WordPress User's ID.
						$wp_id=$wp_user->getWP_UserID($current_username);
						# Update user_login
						$update_wp_username=$wp_user->updateWP_Username($wp_id, $username);
					}
					catch(ezDB_Error $ez)
					{
						throw new Exception('There was an error updating the username for WordPress user ID: '.$wp_id.' in the WordPress Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
					}
				}
				try
				{
					# Create the $where variable.
					$where=array('ID'=>$id);
					# Create $field_value array.
					$field_value=array('username'=>$username);
					# Update the User's data in the `users` table.
					$update_user=$user->updateUser($where, $field_value);
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('There was an error updating the username for user ID: '.$id.' in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
				}
				# Create an empty variable to hold any extra text to include in the message to the User.
				$message='';
				if(isset($_POST['email_username']) && ($_POST['email_username']=='on'))
				{
					$to=$user->findEmail($id);
					$to=htmlspecialchars_decode($to, ENT_QUOTES);
					$subject='Important information about your '.DOMAIN_NAME.' account.';
					$body=$body_pre.' to: '.$username.''."<br />\n<br />\n";
					$body.='You may log in to your account at <a href="'.REDIRECT_TO_LOGIN.'">'.REDIRECT_TO_LOGIN.'</a>'."<br />\n";
					$sent=$doc->sendEmail($subject, $to, $body);
					if($sent===TRUE)
						$message=' and an email has been sent to '.$to;
					else
						$message=' but there was an error sending the confirmation email to '.$to;
				}
				$_SESSION['message']=$message_pre.' username was successfully changed'.$message.'.';
			}
		}
	} #==== End -- processUsername

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * checkUnique
	 *
	 * Performs a check to determine if one parameter is unique in the Database.
	 * Returns FALSE if the value is already in the Database.
	 *
	 * @param		$field (The field to look in.)
	 * @param		$compared (The value to check.)
	 * @param		$params (Any extra parameters.)
	 * @access	protected
	 */
	protected function checkUnique($table, $field, $compared, $params='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$check=$db->query('SELECT `'.$field.'` FROM `'.DBPREFIX.$table.'` WHERE `'.$field.'` = '.$db->quote($db->escape($compared)).$params);

		return (($check==0) ? TRUE : FALSE);
	} #==== End -- checkUnique

	/**
	 * contentRedirect
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form is
	 * needed to add content.
	 *
	 * @access	protected
	 */
	protected function contentRedirect($form_type)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();

			if($populator->getAccountOption()==='add_desc')
			{
				$doc->redirect(ADMIN_URL.'ManageUsers/?user='.$_SESSION['form'][$form_type]['ID'].'&add_desc');
			}

			switch($populator->getFileOption())
			{
				case 'add':
					$doc->redirect(ADMIN_URL.'ManageMedia/files/?add');
				case 'select':
					$doc->redirect(ADMIN_URL.'ManageMedia/files/?select');
				case 'remove':
					$_SESSION['form'][$form_type]['FileID']=NULL;
					# Set a nice message for the user in a session.
					$_SESSION['message']='The file was removed.';
					# Redirect the user to the page they were on.
					$this->redirectNoDelete();
			}

			$image_index=(($form_type=='content') ? 'Image' : 'ImageId');
			switch($populator->getImageOption())
			{
				case 'add':
					$doc->redirect(ADMIN_URL.'ManageMedia/images/?add');
				case 'select':
					$doc->redirect(ADMIN_URL.'ManageMedia/images/?select');
				case 'remove':
					$_SESSION['form'][$form_type][$image_index]=NULL;
					# Set a nice message for the user in a session.
					$_SESSION['message']='The image was removed.';
					# Redirect the user to the page they were on.
					$this->redirectNoDelete();
			}

			if($populator->getCategoryOption()==='add')
			{
				$doc->redirect(ADMIN_URL.'ManageMedia/categories/?add');
			}

			if($populator->getInstitutionOption()==='add')
			{
				$doc->redirect(ADMIN_URL.'ManageContent/institutions/?add');
			}

			if($populator->getLanguageOption()==='add')
			{
				$doc->redirect(ADMIN_URL.'ManageContent/languages/?add');
			}

			if($populator->getPlaylistOption()==='add')
			{
				$doc->redirect(ADMIN_URL.'ManageMedia/categories/?addPlaylist');
			}

			switch($populator->getPositionOption())
			{
				case 'add':
					$doc->redirect(ADMIN_URL.'ManageMedia/positions/?add');
				case 'remove':
					$_SESSION['form'][$form_type]['ID']=NULL;
					# Set a nice message for the user in a session.
					$_SESSION['message']='The position was removed.';
					# Redirect the user to the page they were on.
					$this->redirectNoDelete();
			}

			if($populator->getPublisherOption()==='add')
			{
				$doc->redirect(ADMIN_URL.'ManageContent/publishers/?add');
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- contentRedirect

	/**
	 * ipFirst - let's get a clean ip
	 *
	 * @access	public
	 * @param		$ips (The IP address to clean)
	 * @return	string
	 */
	protected function ipFirst($ips)
	{
		if(($pos=strpos($ips, ',')) !== FALSE)
		{
			return substr($ips, 0, $pos);
		}
		else { return $ips; }
	} #==== End -- ipFirst

	/**
	 * ipValid
	 *
	 * Will try to determine if a given ip is valid or not
	 *
	 * @access	public
	 * @param		$ips (The IP address to validate)
	 * @return	bool
	 */
	protected function ipValid($ips)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		if($validator->ipValid($ips)===TRUE)
		{
			return TRUE;
		}
		else { return FALSE; }
	} #==== End -- ipValid

	/**
	 * loseSessionData
	 *
	 * Gets rid of old CMS session data.
	 *
	 * @access	protected
	 */
	protected function loseSessionData($resource)
	{
		try
		{
			# Check if there is a form session for the passed resource. If so, and the FormURL is not the same as the one the User is on, remove the session data.
			if(isset($_SESSION['form'][$resource]) && ($_SESSION['form'][$resource]['FormURL'][0]!==FormPopulator::getCurrentURL()))
			{
				unset($_SESSION['form']);
			}
			# Check if the User wasn't sent to this form from another form.
			if(isset($_GET['select']) OR isset($_GET['add']))
			{
				return;
			}
			else
			{
				if(isset($_SESSION['form'][$resource]))
				{
					$temp_array=$_SESSION['form'][$resource];
					unset($_SESSION['form']);
					$_SESSION['form'][$resource]=$temp_array;
				}
				else
				{
					unset($_SESSION['form']);
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- loseSessionData

	/**
	 * processBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a resource.
	 *
	 * @param	$resource
	 * @param	$indexes
	 * @access	protected
	 */
	protected function processBack($resource, $indexes)
	{
		try
		{
			# Check if the form has been submitted the submit button was the "Reset" button.
			if(array_key_exists('_submit_check', $_POST) &&
				(isset($_POST[$resource]) && (($_POST[$resource]==='Go Back') OR ($_POST[$resource]==='Back to the form!')))
			)
			{
				# Set the Document instance to a variable.
				$doc=Document::getInstance();
				# Unset any messages.
				unset($_SESSION['message']);
				if(isset($_POST[$resource]) && $_POST[$resource]==='Go Back')
				{
					# Check if the passed indexes are NOT an array.
					if(!is_array($indexes))
					{
						# Explicity make the indexes value into an array.
						$indexes=(array)$indexes;
					}
					# Check if there is a "form" session.
					if(isset($_SESSION['form']))
					{
						# Remove the passed resource's session.
						unset($_SESSION['form'][$resource]);
						# Loop through the "form" session.
						foreach($_SESSION['form'] as $index=>$value)
						{
							# Check if the index is in the indexes array.
							if(in_array($index, $indexes))
							{
								# Redirect the user to the original post page.
								$doc->redirect($_SESSION['form'][$index]['FormURL'][0]);
							}
						}
					}
				}
				elseif(isset($_POST[$resource]) && $_POST[$resource]==='Back to the form!')
				{
					$this->loseSessionData($resource);
					# Check if there is a post or content session.
					if(isset($_SESSION['form'][$resource]))
					{
						# Set the "unique" value to 1 (unique.)
						$_SESSION['form'][$resource]['Unique']=1;
						# Redirect the user to the original post page.
						$doc->redirect($_SESSION['form'][$resource]['FormURL'][0]);
					}
				}
				# Redirect the user to the page they were on.
				$this->redirectNoDelete();
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processBack

	/**
	 * processReset
	 *
	 * Resets the form default values if the "reset" button has been submitted.
	 *
	 * @access	protected
	 */
	protected function processReset($submit_button='post', $session_index=NULL)
	{
		try
		{
			# Check if the form has been submitted and the submit button was the "Reset" button.
			if(array_key_exists('_submit_check', $_POST) && isset($_POST[$submit_button]) && ($_POST[$submit_button]==='Reset'))
			{
				# Check if a session index was passed.
				if($session_index!==NULL)
				{
					# Get rid of the session data.
					unset($_SESSION['form'][$session_index]);
				}
				else
				{
					# Get rid of the CMS sessions in general.
					unset($_SESSION['form']);
				}
				# Get rid of the POST data.
				$_POST=array();
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processReset

	/**
	 * redirectNoDelete
	 *
	 * Redirects to the current page with all GET query params intact except "delete".
	 *
	 * @access	protected
	 */
	protected function redirectNoDelete($param_to_remove=NULL)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			$url=WebUtility::removeIndex(PROTOCAL.FULL_DOMAIN.HERE.preg_replace('/(\&(amp;)?)?delete(=yes)?/i', '', GET_QUERY));
			if($param_to_remove!==NULL)
			{
				$url=preg_replace('/(\&(amp;)?)?'.$param_to_remove.'=(\d)+/i', '', $url);
			}
			$url=str_replace('?&', '?', $url);
			$url=trim($url, '?');
			# Redirect the user.
			$doc->redirect($url);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectNoDelete

	/*** End protected methods ***/

} # End FormProcessor class.