<?php /* application/modules/Form/LoginFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');
# Get the LoginFormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'LoginFormPopulator.php');

/**
 * LoginFormProcessor
 *
 * The LoginFormProcessor Class is used to create and process Login forms.
 *
 */
class LoginFormProcessor extends FormProcessor
{
	/**
	 * processLogin
	 *
	 * Processes a submitted Login.
	 *
	 * @param array $data An array of values tp populate the form with.
	 * @return string
	 * @throws Exception
	 * @access public
	 */
	public function processLogin($data=array())
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the LoginFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'LoginFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populateLoginForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('Login');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('submit');

			# Instantiate a new instance of LoginFormPopulator.
			$populator=new LoginFormPopulator();
			# Populate the form and set the Login data members.
			$populator->populateLoginForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['login']) && ($_POST['login']=='Login')))
			{
				# Get the User object from the LoginFormPopulator and set it to a local variable.
				$user_object=$populator->getUserObject();

				# Set the "remember" value to a variable.
				$remember=$populator->getRemember();
				# Set the user's password to a variable.
				$password=$user_object->getPassword();
				# Set the user's username to a variable.
				$username=$user_object->getUsername();

				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the password field was empty (or less than 1 character or more than 256 characters long).
				$fv->validateEmpty('password', 'Please enter your password.');
				# Check if the username was empty (or less than 5 characters or more than 64 characters long).
				$fv->validateEmpty('username', 'Please enter your username.', 5, 64);

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
				# There are no errors so far. Continue.
				else
				{
					try
					{
						if($this->validatePassword($password)!==TRUE)
						{
							$fv->setErrors('The password was incorrect.');
						}
						else
						{
							# Get user's data.
							if($user_object->findUserData($username)!==FALSE)
							{
								# Check if user's account is active.
								if($user_object->getActive()==1)
								{
									$user_id=$user_object->getID();
									$user_object->setLoginSessions($user_id, $user_object->getDisplayName(), $user_object->getPassword(), $user_object->getFirstName(), $user_object->getLastName(), $user_object->getTitle(), $user_object->getRegistered(), $user_object->getLastLogin(), TRUE, (($remember=='remember') ? TRUE : FALSE));
									$this->updateLastLogin($user_id);
									$doc->redirect((($user_object->getPostLogin()===NULL) ? REDIRECT_AFTER_LOGIN : (('http://'.$user_object->getPostLogin()==ERROR_PAGE.'404.php') ? REDIRECT_AFTER_LOGIN : 'http://'.$user_object->getPostLogin())));
								}
								else
								{
									# User's account is not active, set an error and return FALSE.
									$fv->setErrors('You\'re account is not active yet. Check your email to active it.');
								}
							}
							else
							{
								$fv->setErrors('I was unable to retrieve the user "'.$username.'". Please check your username and password and try again. If you are still having trouble, use the "<a href="'.LOGIN_PAGE.'LostPassword/" title="Lost Password">Lost Password</a>" feature.');
							}
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
					}
					catch(Exception $e)
					{
						if($e->getCode()==E_USER_NOTICE)
						{
							# Create a variable to the error heading.
							$alert_title='There was an error logging in.';
							$fv->setErrors('The username: "'.$username.'" was not found in the system.');
							# Set the FormValidator class errors to a variable.
							$error=$fv->displayErrors();
							# Set the error message to the Document object data member so that it me be displayed on the page.
							$doc->setError($error);
						}
						else
						{
							throw $e;
						}
					}
				}
			}

			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error posting to the `users` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * setSession
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access    private
	 */
	private function setSession()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the User object and set it to a local variable.
			$user_object=$populator->getUserObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url))
			{
				$form_url[]=$current_url;
			}
			# Create a session that holds all the POST data (it will be destroyed if it is not needed.) DO NOT SET THE PASSWORD TO THE SESSION DATA.
			$_SESSION['form']['login']=
				array(
					'FormURL'=>$form_url,
					'Username'=>$user_object->getUsername(),
					'Remember'=>$populator->getRemember()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * updateLastLogin
	 *
	 * Updates the date of the User's last login in the Database.
	 *
	 * @param    $user_id                The User's ID.
	 * @throws Exception
	 * @access    public
	 */
	private function updateLastLogin($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$db->query('UPDATE `'.DBPREFIX.'users` SET `lastlogin` = '.$db->quote($db->escape(YEAR_MM_DD)).' WHERE `ID` = '.$db->quote($user_id));
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('There was an error updating the user\'s lastlogin date: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * validatePassword
	 *
	 * Determines if the passed password matches the encoded password on file.
	 * Returns FALSE if the password didn't match.
	 *
	 * @param    $password                The password to check.
	 * @access    private
	 * @return bool
	 */
	private function validatePassword($password)
	{
		# Get the Populator object and set it to a local variable.
		$populator=$this->getPopulator();
		# Get the Login object and set it to a local variable.
		$user_object=$populator->getUserObject();

		$valid=FALSE;

		# Get the user's actual password.
		$real_password=$user_object->findPassword($user_object->getUsername(), 'username');
		# Get the Encryption Class.
		require_once Utility::locateFile(MODULES.'Encryption'.DS.'Encryption.php');
		# Instantiate a Encryption object.
		$encrypt=new Encryption(MYKEY);
		$encrypted_password=$encrypt->enCodeIt($password);
		if($encrypted_password==$real_password)
		{
			# The password didn't match.
			$valid=TRUE;
		}

		return $valid;
	}
}