<?php /* application/modules/Form/LostPasswordFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');

/**
 * LostPasswordFormPopulator
 *
 * The LostPasswordFormPopulator Class is used populate LostPassword forms.
 *
 */
class LostPasswordFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $remember=NULL;
	private $user_object=NULL;
	/*** End data members ***/

	/*** mutator methods ***/

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
	} #==== End -- setUserObject

	/*** End mutator methods ***/

	/*** accessor methods ***/

	/**
	 * populateLostPasswordForm
	 *
	 * Populates a LostPassword form with content from the login table in the Databse using the id passed via GET data.
	 *
	 * @param    $data                    An array of values to populate the form with.
	 * @access    public
	 */
	public function populateLostPasswordForm($data=array())
	{
		try
		{
			# Get the User class.
			require_once Utility::locateFile(MODULES.'User'.DS.'User.php');
			# Instantiate a new User object.
			$user=new User();
			# Set the Login object to the data member.
			$this->setUserObject($user);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any Login data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('lost_password');
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
			# Remove any "resend_email" sessions.
			unset($_SESSION['form']['resend_email']);
			# Remove any "search" sessions.
			unset($_SESSION['form']['search']);
			# Remove any "staff" sessions.
			unset($_SESSION['form']['staff']);
			# Remove any "video" sessions.
			unset($_SESSION['form']['video']);

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getUserObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getUserObject

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
	} #==== End -- populateLoginForm

	/*** End public methods ***/

	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new Login data values from POST data, they are set to the appropriate data
	 * member (LoginFormPopulator or Login).
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
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['send']) && ($_POST['send']=='Send Request')))
			{
				$data=$this->getData();

				# Check if email POST data was sent.
				if(isset($_POST['email']))
				{
					# Set the email to the User data member.
					$data['Email']=trim($_POST['email']);
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

} # End LoginFormPopulator class.