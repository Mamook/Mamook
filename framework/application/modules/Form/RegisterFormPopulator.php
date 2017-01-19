<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');

/**
 * RegisterFormPopulator
 *
 * The RegisterFormPopulator Class is used populate register forms.
 *
 */
class RegisterFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $email_conf=NULL;
	private $password_conf=NULL;
	private $user_object=NULL;
	/*** End data members ***/

	/*** mutator methods ***/

	/**
	 * getEmailConf
	 *
	 * Returns the data member $email_conf.
	 *
	 * @access    public
	 */
	public function getEmailConf()
	{
		return $this->email_conf;
	}

	/**
	 * getPasswordConf
	 *
	 * Returns the data member $password_conf.
	 *
	 * @access    public
	 */
	public function getPasswordConf()
	{
		return $this->password_conf;
	}

	/**
	 * getUserObject
	 *
	 * Returns the data member $user_object.
	 *
	 * @access    public
	 */
	public function getUserObject()
	{
		return $this->user_object;
	}

	/*** End mutator methods ***/

	/*** accessor methods ***/

	/**
	 * populateRegisterForm
	 *
	 * Populates a register form.
	 *
	 * @param    $data                    An array of values to populate the form with.
	 * @access    public
	 */
	public function populateRegisterForm($data=array())
	{
		# Get the User class.
		require_once Utility::locateFile(MODULES.'User'.DS.'User.php');
		# Instantiate a new User object.
		$user=new User();
		# Set the Login object to the data member.
		$this->setUserObject($user);

		try
		{
			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any Login data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('register');
			# Remove any "account" sessions.
			unset($_SESSION['form']['account']);
			# Remove any "audio" sessions.
			unset($_SESSION['form']['audio']);
			# Remove any "category" sessions.
			unset($_SESSION['form']['category']);
			# Remove any "content" sessions.
			unset($_SESSION['form']['content']);
			# Remove any "file" sessions.
			unset($_SESSION['form']['file']);
			# Remove any "image" sessions.
			unset($_SESSION['form']['image']);
			# Remove any "institution" sessions.
			unset($_SESSION['form']['institution']);
			# Remove any "language" sessions.
			unset($_SESSION['form']['language']);
			# Remove any "login" sessions.
			unset($_SESSION['form']['login']);
			# Remove any "post" sessions.
			unset($_SESSION['form']['post']);
			# Remove any "product" sessions.
			unset($_SESSION['form']['product']);
			# Remove any "publisher" sessions.
			unset($_SESSION['form']['publisher']);
			# Remove any "register" sessions.
			unset($_SESSION['form']['register']);
			# Remove any "search" sessions.
			unset($_SESSION['form']['search']);
			# Remove any "staff" sessions.
			unset($_SESSION['form']['staff']);
			# Remove any "video" sessions.
			unset($_SESSION['form']['video']);

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($user);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * setEmailConf
	 *
	 * Sets the data member $email_conf.
	 *
	 * @param    $email_conf            The User's email.
	 * @access    protected
	 */
	protected function setEmailConf($email_conf)
	{
		# Check if the value is empty.
		if(!empty($email_conf))
		{
			# Clean it up and set the data member.
			$email_conf=trim($email_conf);
		}
		else
		{
			# Explicitly set it to NULL.
			$email_conf=NULL;
		}
		# Set the data member.
		$this->email_conf=$email_conf;
	}

	/**
	 * setPasswordConf
	 *
	 * Sets the data member $password_conf.
	 *
	 * @param    $password_conf            The User's password.
	 * @access    protected
	 */
	protected function setPasswordConf($password_conf)
	{
		# Check if the value is empty.
		if(!empty($password_conf))
		{
			# Clean it up and set the data member.
			$password_conf=trim($password_conf);
		}
		else
		{
			# Explicitly set it to NULL.
			$password_conf=NULL;
		}
		# Set the data member.
		$this->password_conf=$password_conf;
	}

	/*** End accessor methods ***/

	/*** public methods ***/

	/**
	 * setUserObject
	 *
	 * Sets the data member $user_object.
	 *
	 * @param        $object
	 * @access    private
	 */
	private function setUserObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->user_object=$object;
	}

	/*** End public methods ***/

	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (PostFormPopulator or SubContent).
	 *
	 * @access    private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();

			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['register']) && ($_POST['register']==='Register')))
			{
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();
				# Set the data array to a local variable.
				$data=$this->getData();

				# Check if there was POST data sent.
				if(isset($_POST['email']))
				{
					# Clean it up and set it to the data array index.
					$data['Email']=$db->sanitize($_POST['email'], 2);
				}

				# Check if there was POST data sent.
				if(isset($_POST['email_conf']))
				{
					# Clean it up and set it to the data array index.
					$data['EmailConf']=$db->sanitize($_POST['email_conf'], 2);
				}

				# Check if there was POST data sent.
				if(isset($_POST['password']) && !empty($_POST['password']))
				{
					$data['Password']=trim($_POST['password']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['password_conf']) && !empty($_POST['password_conf']))
				{
					$data['PasswordConf']=$_POST['password_conf'];
				}

				# Check if there was POST data sent.
				if(isset($_POST['username']))
				{
					# Clean it up and set it to the data array index.
					$data['Username']=$db->sanitize($_POST['username'], 2);
				}

				# Reset the data array to the data member.
				$this->setData($data);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	/*** End private methods ***/

}