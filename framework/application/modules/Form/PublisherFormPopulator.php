<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * PublisherFormPopulator
 *
 * The PublisherFormPopulator Class is used populate publisher forms.
 *
 */
class PublisherFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $publisher_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setPublisherObject
	 *
	 * Sets the data member $publisher_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setPublisherObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->publisher_object=$object;
	} #==== End -- setPublisherObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getPublisherObject
	 *
	 * Returns the data member $publisher_object.
	 *
	 * @access	public
	 */
	public function getPublisherObject()
	{
		return $this->publisher_object;
	} #==== End -- getPublisherObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populatePublisherForm
	 *
	 * Populates a publisher form with the default data passed in, which is in turn overwritten by session data, which
	 * in turn is overwritten by POST data.
	 *
	 * @access	public
	 */
	public function populatePublisherForm($data=array())
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher=new Publisher();
			# Set the Publisher object to the data member.
			$this->setPublisherObject($publisher);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any publisher data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('publisher');

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getPublisherObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populatePublisherForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (PublisherFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['publisher']) && (($_POST['publisher']=='Submit') OR ($_POST['publisher']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				/* Capture POST data. */

				# Check if info POST data was sent.
				if(isset($_POST['info']))
				{
					# Set the info to the Publisher data member effectively "cleaning" it.
					$data['Info']=$_POST['info'];
				}

				# Check if name POST data was sent.
				if(isset($_POST['name']))
				{
					# Set the name to the Publisher data member.
					$data['Publisher']=$_POST['name'];
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

} # End PublisherFormPopulator class.