<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * StaffFormPopulator
 *
 * The StaffFormPopulator Class is used populate staff profile forms.
 *
 */
class StaffFormPopulator extends FormPopulator
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
	 * populateStaffForm
	 *
	 * Populates a staff profile form.
	 *
	 * @param	array $data				An array of values to populate the form with.
	 * @access	public
	 */
	public function populateStaffForm($data=array())
	{
		try
		{
			# Get the Staff class.
			require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');
			# Instantiate a new Staff object.
			$staff_obj=new Staff();
			# Set the Staff object to the staff_object data member for use outside of this method.
			$this->setStaffObject($staff_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('staff');

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getStaffObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateStaffForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data member.
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		# Bring the Database class into scope.
		global $db;

		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && ((isset($_POST['staff']) && ($_POST['staff']=='Update') || (isset($_POST['staff_desc']) && $_POST['staff_desc']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				# Check if there was POST data sent.
				if(isset($_POST['affiliation']))
				{
					# Clean it up and set it to the data array index.
					$data['Affiliation']=$db->sanitize($_POST['affiliation']);
				}

				# Check if archive POST data was sent.
				if(isset($_POST['archive']))
				{
					# Set the archive value to 0.
					$data['Archive']=0;
				}
				else
				{
					$data['Archive']=NULL;
				}

				# Check if there was POST data sent.
				if(isset($_POST['credentials']))
				{
					# Clean it up and set it to the data array index.
					$data['Credentials']=$db->sanitize($_POST['credentials']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['fname']))
				{
					# Clean it up and set it to the data array index.
					$data['FirstName']=$db->sanitize($_POST['fname']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['image_title']))
				{
					# Clean it up and set it to the data array index.
					$data['ImageTitle']=$db->sanitize($_POST['image_title']);
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
					$data['MiddleName']=$db->sanitize($_POST['mname']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['position']))
				{
					# Create an empty array.
					$position_array=array();
					$new_array=array();
					$temp_array=array();
					# Loop through the positions in $_POST['position'].
					foreach($_POST['position_desc'] as $position_desc)
					{
						# Check if there is descriptions for the position's that have been submitted.
						if(isset($_POST['position_desc']) && !isset($_SESSION['form']['staff_desc']))
						{
							# Loop through the position's decriptions.
							foreach($_POST['position'] as $key=>$value)
							{
								$temp_array[$key]['position']=$value;

								# If the position matches the descriptions position.
								if($position_desc['position']==$value)
								{
									$temp_array[$key]['description']=$position_desc['description'];
									unset($_POST['position_desc'][$key]);
								}
								else
								{
									$new_array[$key]['position']=$value;
									$new_array[$key]['description']='';
								}

								# There is no description set for this position so redirect to the add description form.
								if(empty($temp_array[$key]['description']))
								{
									$data['StaffOption']='add_desc';
								}
							}
						}
					}

					# JSON encode the array.
					$position=json_encode($temp_array, JSON_FORCE_OBJECT);
					# If the position description form was submitted, assign the new json encoded position to a variable.
					if(isset($_SESSION['form']['staff_desc']))
					{
						$position=$_SESSION['form']['staff_desc']['Position'];
					}

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

					# JSON encode the array.
					$position=json_encode($new_array3, JSON_FORCE_OBJECT);
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
				if(isset($_POST['text']))
				{
					# Clean it up and set it to the data array index.
					$data['Text']=$db->sanitize($_POST['text']);
				}

				# Check if there was POST data sent.
				if(isset($_POST['title']))
				{
					# Clean it up and set it to the data array index.
					$data['Title']=$db->sanitize($_POST['title']);
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

	# NOTE: Move to Utilities class.
	/**
	 * recursiveArraySearch
	 *
	 * Searches multi-dimensional array for $needle.
	 *
	 * @param	$needle
	 * @param	array $haystack
	 * @access	private
	 */
	private function recursiveArraySearch($needle, $haystack)
	{
		foreach($haystack as $key=>$value)
		{
			$current_key=$key;
			if($needle===$value OR (is_array($value) && $this->recursiveArraySearch($needle, $value)!==FALSE))
			{
				return $current_key;
			}
		}
		return FALSE;
	} #==== End -- recursiveArraySearch

	/*** End private methods ***/

} # End StaffFormPopulator class.