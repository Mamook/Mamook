<?php /* application/modules/Form/LostPasswordFormProcessor.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');

# Get the LostPasswordFormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'LostPasswordFormPopulator.php');

/**
 * LostPasswordFormProcessor
 *
 * The LostPasswordFormProcessor Class is used to create and process LostPassword forms.
 *
 */
class LostPasswordFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processLostPassword
	 *
	 * Processes a submitted LostPassword form.
	 *
	 * @param    $data                    An array of values tp populate the form with.
	 * @access    public
	 * @return    string
	 */
	public function processLostPassword($data=array())
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the LostPasswordFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'LostPasswordFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populateLostPasswordForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('lost_password');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('submit');

			# Instantiate a new instance of LostPasswordFormPopulator.
			$populator=new LostPasswordFormPopulator();
			# Populate the form and set the LostPassword data members.
			$populator->populateLostPasswordForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['send']) && ($_POST['send']=='Send Request')))
			{
				# Get the User object from the LostPasswordFormPopulator and set it to a local variable.
				$user_object=$populator->getUserObject();

				# Set the user's email to a variable.
				$email=$user_object->getEmail();

				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the username was empty (or less than 4 characters).
				$empty_email=$fv->validateEmpty('email', 'Please enter the email address you entered when you registered at '.DOMAIN_NAME.'.', 4);
				if(($empty_email===FALSE))
				{
					$valid_email=$fv->validateEmail('email', 'Please enter the valid email address you entered when you registered at '.DOMAIN_NAME.'.', FALSE);
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
				# There are no errors so far. Continue.
				else
				{
					try
					{
						# Send the account info to the provided email address.
						$user_object->sendAccountInfo($email);
					}
					catch(Exception $e)
					{
						if($e->getCode()==E_USER_NOTICE)
						{
							# Create a variable to the error heading.
							$alert_title='There was an error sending credentials for '.$email.'.';
							$fv->setErrors('The username: "'.$username.'" was not found in the system.');
							# Set the FormValidator class errors to a variable.
							$error=$fv->displayErrors();
							# Set the error message to the Document object data member so that it me be displayed on the page.
							$doc->setError($error);
						}
						else
						{
							throw $e;
							die;
						}
					}
				}
			}

			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error submitting the LostPassword form: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processLostPassword

	/*** End public methods ***/

	/*** Protected methods ***/

	/*** End protected methods ***/

	/*** private methods ***/

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
			$_SESSION['form']['lost_password']=
				array(
					'FormURL'=>$form_url,
					'Email'=>$user_object->getEmail()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession
	/*** End private methods ***/

} # End LostPasswordFormProcessor class.