<?php /* framework/application/modules/Form/ContentFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * ContentFormPopulator
 *
 * The ContentFormPopulator Class is used populate content forms.
 *
 */
class ContentFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $content_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setContentObject
	 *
	 * Sets the data member $content_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setContentObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->content_object=$object;
	} #==== End -- setContentObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getContentObject
	 *
	 * Returns the data member $content.
	 *
	 * @access	public
	 */
	public function getContentObject()
	{
		return $this->content_object;
	} #==== End -- getContentObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateContentForm
	 *
	 * Populates a content form.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populateContentForm($data=array())
	{
		try
		{
			# Get the Content class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Content.php');
			# Instantiate a new Content object.
			$content_object=new Content();
			# Set the Content object to the content_object data member for use outside of this method.
			$this->setContentObject($content_object);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed default data.
			$this->setSessionDataToDataArray('content');
			# Remove any "post" sessions.
			unset($_SESSION['form']['post']);
			# Remove any "product" sessions.
			unset($_SESSION['form']['product']);

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with the data in the data array.
			$this->setDataToDataMembers($this->getContentObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateContentForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (ContentFormPopulator or Content).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['content']) && ($_POST['content']=='Update')))
			{
				$data=$this->getData();

				# Check if archive POST data was sent.
				if(isset($_POST['archive']))
				{
					# Set the archive value to 0.
					$data['Archive']=0;
				}
				else
				{
					# Set the archive value to NULL in the data array.
					$data['Archive']=NULL;
				}

				# Check if hide_title POST data was sent.
				if(isset($_POST['hide_title']))
				{
					# Set the hide_title value to 0.
					$data['HideTitle']=0;
				}
				else
				{
					# Set the hide_title value to NULL in the data array.
					$data['HideTitle']=NULL;
				}

				# Check if image POST data was sent.
				if(isset($_POST['_image']))
				{
					# Set the image file name to the Image data index.
					$data['Image']=$_POST['_image'];
				}

				# Check if image POST data was sent.
				if(isset($_POST['image_option']) && !empty($_POST['image_option']))
				{
					# Set the image option ("add", "remove", or "select") to the Content data member.
					$data['ImageOption']=$_POST['image_option'];
				}

				# Check if image_title POST data was sent.
				if(isset($_POST['image_title']))
				{
					# Set the image_title to the ImageTitle data index.
					$data['ImageTitle']=$_POST['image_title'];
				}

				# Check if page POST data was sent.
				if(isset($_POST['page']))
				{
					# Set the page to the Page data index.
					$data['Page']=$_POST['page'];
				}

				# Check if page_title POST data was sent.
				if(isset($_POST['page_title']) && !empty($_POST['page_title']))
				{
					# Set the page_title to the PageTitle data index.
					$data['PageTitle']=$_POST['page_title'];
				}

				# Check if social POST data was sent.
				if(isset($_POST['social']))
				{
					# Set the social value to 0.
					$data['UseSocial']=0;
				}
				else
				{
					# Set the social value to NULL in the data array.
					$data['UseSocial']=NULL;
				}

				# Check if sub_domain POST data was sent.
				if(isset($_POST['sub_domain']))
				{
					# Set the sub_domain to the SubDomain data index.
					$data['SubDomain']=$_POST['sub_domain'];
				}

				# Check if sub_title POST data was sent.
				if(isset($_POST['sub_title']))
				{
					# Set the sub_title to the SubTitle data index.
					$data['SubTitle']=$_POST['sub_title'];
				}

				# Check if text POST data was sent.
				if(isset($_POST['text']))
				{
					# Set the text to the Text data index.
					$data['Text']=$_POST['text'];
				}

				# Check if topic POST data was sent.
				if(isset($_POST['topic']))
				{
					# Set the topic to the Topic data index.
					$data['Topic']=$_POST['topic'];
				}

				# Check if quote POST data was sent.
				if(isset($_POST['quote']))
				{
					# Set the quote to the Text data index.
					$data['Quote']=$_POST['quote'];
				}
				# Reset the "data" data member.
				$this->setData($data);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setPostDataToDataArray

	/*** End private methods ***/

} # End ContentFormPopulator class.