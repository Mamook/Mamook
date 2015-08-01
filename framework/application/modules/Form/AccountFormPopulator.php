<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * AccountFormPopulator
 *
 * The AccountFormPopulator Class is used populate forms in "myAccount".
 *
 */
class AccountFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $user_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setUserObject
	 *
	 * Sets the data member $user_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setUserObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->user_object=$object;
	} #==== End -- setUserObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getUserObject
	 *
	 * Returns the data member $user_object.
	 *
	 * @access	public
	 */
	public function getUserObject()
	{
		return $this->user_object;
	} #==== End -- getUserObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateAccountForm
	 *
	 * Populates a user profile form.
	 *
	 * @param	array $data				An array of values to populate the form with.
	 * @access	public
	 */
	public function populateAccountForm($data=array(), $index='account')
	{
		try
		{
			# Instantiate a new User object.
			$user_obj=new User();
			# Set the Staff object to the staff_object data member for use outside of this method.
			$this->setUserObject($user_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray($index);

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getUserObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateAccountForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (AccountFormPopulator or User).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		global $db;

		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && ((isset($_POST['account']) && ($_POST['account']=='Add User' OR $_POST['account']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				# Check if there was POST data sent.
				if(isset($_POST['address']))
				{
					# Clean it up and set it to the data array index.
					$data['Address']=$db->sanitize($_POST['address']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['address2']))
				{
					# Clean it up and set it to the data array index.
					$data['Address2']=$db->sanitize($_POST['address2']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['bio']))
				{
					# Clean it up and set it to the data array index.
					$data['Bio']=$db->sanitize($_POST['bio']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['city']))
				{
					# Clean it up and set it to the data array index.
					$data['City']=$db->sanitize($_POST['city']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['country']))
				{
					# Clean it up and set it to the data array index.
					$data['Country']=$db->sanitize($_POST['country']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['_cv_current']))
				{
					# Clean it up and set it to the data array index.
					$data['CV']=$db->sanitize($_POST['_cv_current']);
				}
				# Check if there was POST data sent.
				if(isset($_POST['_cv_current_remove']))
				{
					# Clean it up and set it to the data array index.
					$data['CV']=NULL;
				}
			/*** I believe this value is never captured. It will be $_FILE['cv'] that gets captured in the AccountFormProcessor script. **/
				# Check if there was POST data sent.
// 				if(isset($_POST['cv']))
// 				{
// 					# Clean it up and set it to the data array index.
// 					$data['CV']=$db->sanitize($_POST['cv']);
// 				}

				# Check if there was POST data sent.
				if(isset($_POST['display']))
				{
					# Clean it up and set it to the data array index.
					$data['DisplayName']=$db->sanitize($_POST['display']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['email']))
				{
					# Clean it up and set it to the data array index.
					$data['Email']=$db->sanitize($_POST['email']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['fname']))
				{
					# Clean it up and set it to the data array index.
					$data['FirstName']=$db->sanitize($_POST['fname']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['_image_current']))
				{
					# Clean it up and set it to the data array index.
					$data['Img']=$db->sanitize($_POST['_image_current']);
				}
				# Check if there was POST data sent.
				if(isset($_POST['_image_current_remove']))
				{
					# Clean it up and set it to the data array index.
					$data['Img']=NULL;
				}

				# Check if there was POST data sent.
				if(isset($_POST['img_title']))
				{
					# Clean it up and set it to the data array index.
					$data['ImgTitle']=$db->sanitize($_POST['img_title']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['interests']))
				{
					# Clean it up and set it to the data array index.
					$data['Interests']=$db->sanitize($_POST['interests']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['lname']))
				{
					# Clean it up and set it to the data array index.
					$data['LastName']=$db->sanitize($_POST['lname']);
				}

				# Check if there is a WordPress installation.
				if(WP_INSTALLED===TRUE)
				{
					# Check if there was POST data sent.
					if(isset($_POST['nickname']))
					{
						# Clean it up and set it to the data array index.
						$data['Nickname']=$db->sanitize($_POST['nickname']);
					}
				}

				# Check if there was POST data sent.
				if(isset($_POST['phone']))
				{
					# Clean it up and set it to the data array index.
					$data['Phone']=$db->sanitize($_POST['phone']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['state']))
				{
					# Clean it up and set it to the data array index.
					$data['State']=$db->sanitize($_POST['state']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['title']))
				{
					# Clean it up and set it to the data array index.
					$data['Title']=$db->sanitize($_POST['title']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['website']))
				{
					# Clean it up and set it to the data array index.
					$data['Website']=$db->sanitize($_POST['website']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['zipcode']))
				{
					# Clean it up and set it to the data array index.
					$data['Zipcode']=$db->sanitize($_POST['zipcode']);
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

} # End AccountFormPopulator class.