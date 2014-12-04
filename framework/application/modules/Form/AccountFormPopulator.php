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

	private $staff_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setStaffObject
	 *
	 * Sets the data member $staff_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setStaffObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->staff_object=$object;
	} #==== End -- setStaffObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getStaffObject
	 *
	 * Returns the data member $staff_object.
	 *
	 * @access	public
	 */
	public function getStaffObject()
	{
		return $this->staff_object;
	} #==== End -- getStaffObject

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
			# Get the Staff class. The Staff class extends the User class so everything is User will also be available.
			require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');
			# Instantiate a new User object.
			$staff_obj=new Staff();
			# Set the Staff object to the staff_object data member for use outside of this method.
			$this->setStaffObject($staff_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray($index);

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getStaffObject());
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
			if(array_key_exists('_submit_check', $_POST) && ((isset($_POST['account']) && ($_POST['account']=='Add User' OR $_POST['account']=='Update') || (isset($_POST['account_desc']) && $_POST['account_desc']=='Update'))))
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
				if(isset($_POST['affiliation']))
				{
					# Clean it up and set it to the data array index.
					$data['Affiliation']=$db->sanitize($_POST['affiliation']);
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
				if(isset($_POST['credentials']))
				{
					# Clean it up and set it to the data array index.
					$data['Credentials']=$db->sanitize($_POST['credentials']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['cv']))
				{
					# Clean it up and set it to the data array index.
					$data['CV']=$db->sanitize($_POST['cv']);
				}

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

				# Check if there was POST data sent.
				if(isset($_POST['mname']))
				{
					# Clean it up and set it to the data array index.
					$data['StaffMiddleName']=$db->sanitize($_POST['mname']);
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
				if(isset($_POST['position']))
				{
					# Create an empty array.
					$position_array=array();
					$new_array=array();
					# Loop through the user's positions.
					foreach($_POST['position'] as $position)
					{
						# Create an empty array.
						$temp_array=array();
						if(isset($_POST['position_desc']))
						{
							# Loop through the position's decriptions.
							foreach($_POST['position_desc'] as $key=>$value)
							{
								$temp_array=array(
									'position'=>$position,
									'description'=>''
								);
								if($value['position']==$position)
								{
									$temp_array['description']=$value['description'];
									unset($_POST['position_desc'][$key]);
									break;
								}
### DRAVEN: This doesn't work. ####
### Redirects you to the position form when you remove user positions! ###
								else
								{
									$new_array[$key]['position']=$position;
									$new_array[$key]['description']='';
									$data['AccountOption']='add_desc';
									break;
								}
							}

### DRAVEN: USED HACK TO FIX ABOVE PROBLEM :( ###
							$position_search=$this->recursive_array_search($position, $_POST['position_desc']);
							if($position_search!==FALSE)
							{
								# Don't redirect to position form if removing positions from user.
								$data['AccountOption']='';
								# Needed or else the description is set to NULL for all positions.
								$temp_array['description']=$_POST['position_desc'][$position_search]['description'];
							}
							$position_array[]=$temp_array;
						}
					}

					# JSON encode the array.
					$position=json_encode($position_array, JSON_FORCE_OBJECT);

					if(isset($new_array) && !empty($new_array))
					{
						$data['NewPosition']=$new_array;
					}
					if(isset($position))
					{
						# Clean it up and set it to the data array index.
						$data['Position']=$position;
					}
				}
				elseif(!isset($_POST['position']) && isset($_POST['position_desc']))
				{
					# JSON decode the user's current positions.
					$current_positions=json_decode($data['Position'], TRUE);
					print_r($current_positions);
					print_r($_POST['position_desc']);
					exit;

					/*
					$new_array=array();
					foreach($current_positions as $key=>$current_position)
					{
						$new_array[$key]['position']=$current_position['position'];
						$new_array[$key]['description']=$current_position['description'];
					}

					$new_array2=array();
					foreach($_POST['position_desc'] as $new_key=>$new_position)
					{
						$new_array2[$new_key]['position']=$new_position['position'];
						$new_array2[$new_key]['description']=$new_position['description'];
					}
					$new_array3=array_merge($new_array, $new_array2);
					*/

					$new_array=array();
					$position_array=array();
					foreach($current_positions as $current_position)
					{
						$temp_array=array();
						# Loop through the position's decriptions.
						foreach($_POST['position_desc'] as $key=>$value)
						{
							$temp_array=array(
								'position'=>$current_position['position'],
								'description'=>''
							);
							if($value['position']==$current_position['position'])
							{
								$temp_array['description']=$value['description'];
								unset($_POST['position_desc'][$key]);
								break;
							}
						}
						$position_array[]=$temp_array;
					}
					print_r($position_array);exit;

					# JSON encode the array.
					$position=json_encode($new_array3, JSON_FORCE_OBJECT);
					print_r($position);exit;
					# Clean it up and set it to the data array index.
					$data['Position']=$position;
				}

				# Check if there was POST data sent.
				if(isset($_POST['region']))
				{
					# Clean it up and set it to the data array index.
					$data['Region']=$db->sanitize($_POST['region']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['_staff_id']))
				{
					# Clean it up and set it to the data array index.
					$data['StaffID']=$db->sanitize($_POST['_staff_id']);
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

	private function recursive_array_search($needle, $haystack)
	{
		foreach($haystack as $key=>$value)
		{
			$current_key=$key;
			if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false))
			{
				return $current_key;
			}
		}
		return false;
	}

	/*** End private methods ***/

} # End AccountFormPopulator class.