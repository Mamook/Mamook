<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

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

	private $register_option=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/***
	 * setRegisterOption
	 *
	 * Sets the data member $register_option
	 *
	 * @param	$register_option
	 * @access	protected
	 */
	protected function setRegisterOption($register_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($register_option) OR $register_option!=='Register')
		{
			# Explicitly set the value to NULL.
			$register_option=NULL;
		}
		# Set the data member.
		$this->register_option=$register_option;
	} #==== End -- setRegisterOption

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getRegisterOption
	 *
	 * Returns the data member $register_option.
	 *
	 * @access	public
	 */
	public function getRegisterOption()
	{
		return $this->register_option;
	} #==== End -- getRegisterOption

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateRegisterForm
	 *
	 * Populates a register form.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populateRegisterForm($data=array())
	{
		# Bring the Login class into scope.
		global $login;

		try
		{
			# Set the passed data array to the data member.
			$this->setData($data);

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($login);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateRegisterForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (PostFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		global $db;

		try
		{
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

				# Get the Encryption Class.
				require_once Utility::locateFile(MODULES.'Encryption'.DS.'Encryption.php');
				# Instantiate a new Encryption object.
				$encrypt=new Encryption(MYKEY);

				# Check if there was POST data sent.
				if(isset($_POST['password']) && !empty($_POST['password']))
				{
					# If WordPress is installed add the user the the WordPress users table.
					if(WP_INSTALLED===TRUE)
					{
						$data['WPPassword']=trim($_POST['password']);
					}
					$encrypted_password=$encrypt->enCodeIt(trim($_POST['password']));
					$data['Password']=$encrypted_password;
				}

				# Check if there was POST data sent.
				if(isset($_POST['password_conf']) && !empty($_POST['password_conf']))
				{
					$encrypted_password_conf=$encrypt->enCodeIt(trim($_POST['password_conf']));
					$data['PasswordConf']=$encrypted_password_conf;
				}

				# Check if the register option POST data was sent.
				if(isset($_POST['register']) && ($_POST['register']==='Register'))
				{
					# Set the register option to the data array.
					$data['RegisterOption']=$_POST['register'];
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
	} #==== End -- setPostDataToDataArray

	/*** End private methods ***/

} # End RegisterFormPopulator class.