<?php /* framework/application/modules/Form/SearchFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * SearchFormPopulator
 *
 * The SearchFormPopulator Class is used populate search forms.
 *
 */
class SearchFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $search_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setSearchObject
	 *
	 * Sets the data member $search_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setSearchObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->search_object=$object;
	} #==== End -- setSearchObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getSearchObject
	 *
	 * Returns the data member $search_object.
	 *
	 * @access	public
	 */
	public function getSearchObject()
	{
		return $this->search_object;
	} #==== End -- getSearchObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateSearchForm
	 *
	 * Populates a search form with default search data, values passed via POST, or saved SESSION data.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populateSearchForm($data=array())
	{
		try
		{
			# Get the Search class.
			require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
			# Instantiate a new Search object.
			$search_object=Search::getInstance();
			# Set the Search object to the search_object data member for use outside of this method.
			$this->setSearchObject($search_object);

			# Set the passed data to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('search');

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getSearchObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateSearchForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new data values from POST data, they are set to the appropriate data
	 * member (SearchFormPopulator or Search).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['search'])))
			{
				# Set the data array to a local variable.
				$data=$this->getData();
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();

				# Check if title POST data was sent.
				if(isset($_POST['branch']))
				{
					# Set the search terms to the searchterms data member.
					$data['SearchBranch']=$_POST['branch'];
				}

				# Check if title POST data was sent.
				if(isset($_POST['searchterms']))
				{
					# Set the search terms to the searchterms data member.
					$data['SearchTerms']=$_POST['searchterms'];
				}

				# Set the tables to the tables data member.
				$data['SearchType']=$_POST['_type'];

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

} # End SearchFormPopulator class.