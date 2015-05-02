<?php /* framework/application/modules/Form/CategoryFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * CategoryFormPopulator
 *
 * The CategoryFormPopulator Class is used populate category forms.
 */
class CategoryFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $category_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setCategoryObject
	 *
	 * Sets the data member $category_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setCategoryObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->category_object=$object;
	} #==== End -- setCategoryObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getCategoryObject
	 *
	 * Returns the data member $category_object.
	 *
	 * @access	public
	 */
	public function getCategoryObject()
	{
		return $this->category_object;
	} #==== End -- getCategoryObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateCategoryForm
	 *
	 * Populates an category form with the default data passed in, which is in turn overwritten by session data, which
	 * in turn is overwritten by POST data.
	 *
	 * @access	public
	 */
	public function populateCategoryForm($data=array())
	{
		try
		{
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category=new Category();
			# Set the Category object to the data member.
			$this->setCategoryObject($category);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any category data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('category');

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getCategoryObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateCategoryForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (CategoryFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['category']) && (($_POST['category']=='Submit') OR ($_POST['category']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				/* Capture POST data. */

				# Check if name POST data was sent.
				if(isset($_POST['name']))
				{
					# Set the name to the Category data member.
					$data['Category']=$_POST['name'];
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

} # End CategoryFormPopulator class.