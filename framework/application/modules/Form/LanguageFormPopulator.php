<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * LanguageFormPopulator
 *
 * The LanguageFormPopulator Class is used populate language forms.
 */
class LanguageFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $language_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setLanguageObject
	 *
	 * Sets the data member $language_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setLanguageObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->language_object=$object;
	} #==== End -- setLanguageObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getLanguageObject
	 *
	 * Returns the data member $language_object.
	 *
	 * @access	public
	 */
	public function getLanguageObject()
	{
		return $this->language_object;
	} #==== End -- getLanguageObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateLanguageForm
	 *
	 * Populates a language form with the default data passed in, which is in turn overwritten by session data, which
	 * in turn is overwritten by POST data.
	 *
	 * @access	public
	 */
	public function populateLanguageForm($data=array())
	{
		try
		{
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Instantiate a new Language object.
			$language=new Language();
			# Set the Language object to the data member.
			$this->setLanguageObject($language);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any language data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('language');

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getLanguageObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateLanguageForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (LanguageFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['language']) && (($_POST['language']=='Submit') OR ($_POST['language']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				/* Capture POST data. */

				# Check if iso POST data was sent.
				if(isset($_POST['iso']))
				{
					# Set the iso to the Language data member.
					$data['ISO']=$_POST['iso'];
				}

				# Check if language POST data was sent.
				if(isset($_POST['language_name']))
				{
					# Set the language to the Language data member.
					$data['Language']=$_POST['language_name'];
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

} # End LanguageFormPopulator class.