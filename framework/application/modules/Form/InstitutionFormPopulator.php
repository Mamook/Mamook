<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * InstitutionFormPopulator
 *
 * The InstitutionFormPopulator Class is used populate institution forms.
 */
class InstitutionFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $institution_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setInstitutionObject
	 *
	 * Sets the data member $institution_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setInstitutionObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->institution_object=$object;
	} #==== End -- setInstitutionObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getInstitutionObject
	 *
	 * Returns the data member $institution_object.
	 *
	 * @access	public
	 */
	public function getInstitutionObject()
	{
		return $this->institution_object;
	} #==== End -- getInstitutionObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateInstitutionForm
	 *
	 * Populates an institution form with the default data passed in, which is in turn overwritten by session data, which
	 * in turn is overwritten by POST data.
	 *
	 * @access	public
	 */
	public function populateInstitutionForm($data=array())
	{
		try
		{
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution=new Institution();
			# Set the Institution object to the data member.
			$this->setInstitutionObject($institution);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any institution data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('institution');

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getInstitutionObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateInstitutionForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (InstitutionFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['institution']) && (($_POST['institution']=='Submit') OR ($_POST['institution']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				/* Capture POST data. */

				# Check if name POST data was sent.
				if(isset($_POST['name']))
				{
					# Set the name to the Institution data member.
					$data['Institution']=$_POST['name'];
				}

				# Check if the unique POST data was sent.
				if(isset($_POST['_unique']))
				{
					$unique=1;
					if(empty($_POST['_unique']))
					{
						$unique=0;
					}
					# Set the unique value to the data member.
					$this->setUnique($unique);
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

} # End InstitutionFormPopulator class.