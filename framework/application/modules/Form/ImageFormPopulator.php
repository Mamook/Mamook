<?php /* framework/application/modules/Form/ImageFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * ImageFormPopulator
 *
 * The ImageFormPopulator Class is used populate image select, upload, edit, or delete forms.
 *
 */
class ImageFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $image_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setImageObject
	 *
	 * Sets the data member $image_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setImageObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->image_object=$object;
	} #==== End -- setImageObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getImageObject
	 *
	 * Returns the data member $image_object.
	 *
	 * @access	public
	 */
	public function getImageObject()
	{
		return $this->image_object;
	} #==== End -- getImageObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateImageForm
	 *
	 * Populates a image form with content from the `images` table in the databse using the id passed
	 * via GET data, default image data, values passed via POST, or saved SESSION data.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populateImageForm($data=array())
	{
		try
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
			# Instantiate a new Image object.
			$image_obj=Image::getInstance();
			# Set the Image object to the image data member for use outside of this method.
			$this->setImageObject($image_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('image');

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getImageObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateImageForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (ImageFormPopulator or Image).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['image']) && ($_POST['image']=='Add Image' OR $_POST['image']=='Update')))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				# Check if the category was passed via POST data.
				if(isset($_POST['category']))
				{
					# Check if the category option "add" was passed in POST data.
					if(($key=array_search('add', $_POST['category']))!==FALSE)
					{
						# Remove the index from the array that holds the "add" value.
						unset($_POST['category'][$key]);
						# Set "add" to the "CategoryOption" index of the data array.
						$data['CategoryOption']='add';
					}
					# Set the categories data member.
					$data['Categories']=$_POST['category'];
				}

				# Check if description POST data was sent.
				if(isset($_POST['description']))
				{
					# Set the description to the Image data member.
					$data['Description']=$_POST['description'];
				}

				# Check if height POST data was sent.
				if(isset($_POST['height']))
				{
					# Set the height to the Image data member.
					$data['Height']=$_POST['height'];
				}

				# Check if the image id POST data was sent.
				if(isset($_POST['_image']))
				{
					# Set the image id to the Image data member.
					$data['Image']=$_POST['_image'];
				}

				# Check if the publish location was passed via POST data.
				if(isset($_POST['location']))
				{
					# Set the location to the Image data member.
					$data['Location']=$_POST['location'];
				}

				# Check if title POST data was sent.
				if(isset($_POST['title']))
				{
					# Set the title to the Image data member.
					$data['Title']=$_POST['title'];
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
					$data['Unique']=$unique;
				}

				# Check if width POST data was sent.
				if(isset($_POST['width']))
				{
					# Set the width to the Image data member.
					$data['Width']=$_POST['width'];
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

} # End ImageFormPopulator class.