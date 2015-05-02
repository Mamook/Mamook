<?php /* framework/application/modules/Form/FormPopulator.php */

# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');


/**
 * FormPopulator
 *
 * The FormPopulator Class is used populate form values.
 */
class FormPopulator
{
	/*** data members ***/

	private $category_option=NULL;
	private $data=array();
	private $date=NULL;
	private $facebook=NULL;
	private $file_option=NULL;
	protected $form_url=array();
	private $image_option=NULL;
	private $institution_option=NULL;
	private $language_option=NULL;
	private $playlist_option=NULL;
	private $position_option=NULL;
	private $premium_change=FALSE;
	private $publisher_option=NULL;
	private $staff_option=NULL;
	private $to=NULL;
	private $twitter=NULL;
	private $unique=0;

	/*** End data members ***/



	/*** mutator methods ***/

	/***
	 * setCategoryOption
	 *
	 * Sets the data member $category_option
	 *
	 * @param	$category_option
	 * @access	protected
	 */
	protected function setCategoryOption($category_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($category_option) OR ($category_option!=='add' && $category_option!=='remove' && $category_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$category_option=NULL;
		}
		# Set the data member.
		$this->category_option=$category_option;
	} #==== End -- setCategoryOption

	/**
	 * setData
	 *
	 * Sets the data member $data.
	 *
	 * @param	$data_array
	 * @access	protected
	 */
	protected function setData($data_array)
	{
		# Check if the passed value is NOT empty and an array.
		if(!empty($data_array) && is_array($data_array))
		{
			$this->data=$data_array;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->data=array();
		}
	} #==== End -- setData

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param	$date
	 * @access	protected
	 */
	protected function setDate($date)
	{
		# Check if the passed value is empty.
		if(!empty($date))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Clean it up.
			$date=$db->sanitize($date);
		}
		else
		{
			# Explicitly set the value to NULL.
			$date=NULL;
		}
		# Set the data member.
		$this->date=$date;
	} #==== End -- setDate

	/**
	 * setFacebook
	 *
	 * Sets the data member $facebook.
	 *
	 * @param	$value
	 * @access	protected
	 */
	protected function setFacebook($value)
	{
		# Check if the passed value is empty.
		if($value!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->facebook='post';
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->facebook=NULL;
		}
	} #==== End -- setFacebook

	/***
	 * setFileOption
	 *
	 * Sets the data member $file_option
	 *
	 * @param	$file_option
	 * @access	protected
	 */
	protected function setFileOption($file_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($file_option) OR ($file_option!=='add' && $file_option!=='remove' && $file_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$file_option=NULL;
		}
		# Set the data member.
		$this->file_option=$file_option;
	} #==== End -- setFileOption

	/**
	 * setFormURL
	 *
	 * Sets the data member $form_url.
	 *
	 * @param	$url					The complete url where the form is.
	 * @access	public
	 */
	public function setFormURL($url)
	{
		# Check if the passed value is an array.
		if(!is_array($url))
		{
			# Type cast the passed value as an array.
			$url=(array)$url;
		}
		# Set the data member.
		$this->form_url=$url;
	} #==== End -- setFormURL

	/***
	 * setImageOption
	 *
	 * Sets the data member $image_option
	 *
	 * @param	$image_option
	 * @access	protected
	 */
	protected function setImageOption($image_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($image_option) OR ($image_option!=='add' && $image_option!=='remove' && $image_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$image_option=NULL;
		}
		# Set the data member.
		$this->image_option=$image_option;
	} #==== End -- setImageOption

	/***
	 * setInstitutionOption
	 *
	 * Sets the data member $institution_option
	 *
	 * @param	$institution_option
	 * @access	protected
	 */
	protected function setInstitutionOption($institution_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($institution_option) OR ($institution_option!=='add' && $institution_option!=='remove' && $institution_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$institution_option=NULL;
		}
		# Set the data member.
		$this->institution_option=$institution_option;
	} #==== End -- setInstitutionOption

	/***
	 * setLanguageOption
	 *
	 * Sets the data member $language_option
	 *
	 * @param	$language_option
	 * @access	protected
	 */
	protected function setLanguageOption($language_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($language_option) OR ($language_option!=='add' && $language_option!=='remove' && $language_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$language_option=NULL;
		}
		# Set the data member.
		$this->language_option=$language_option;
	} #==== End -- setLanguageOption

	/***
	 * setPlaylistOption
	 *
	 * Sets the data member $playlist_option
	 *
	 * @param	$playlist_option
	 * @access	protected
	 */
	protected function setPlaylistOption($playlist_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the playlist options.
		if(empty($playlist_option) OR $playlist_option!=='add')
		{
			# Explicitly set the value to NULL.
			$playlist_option=NULL;
		}
		# Set the data member.
		$this->playlist_option=$playlist_option;
	} #==== End -- setPlaylistOption

	/***
	 * setPositionOption
	 *
	 * Sets the data member $position_option
	 *
	 * @param	$position_option
	 * @access	protected
	 */
	protected function setPositionOption($position_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the playlist options.
		if(empty($position_option) OR $position_option!=='add')
		{
			# Explicitly set the value to NULL.
			$playlist_option=NULL;
		}
		# Set the data member.
		$this->position_option=$position_option;
	} #==== End -- setPositionOption

	/**
	 * setPremiumChange
	 *
	 * Sets the data member $premium_change.
	 *
	 * @param	$premium_change			TRUE if the premium status changed, FALSE if not.
	 * @access	protected
	 */
	protected function setPremiumChange($premium_change)
	{
		# Check if the passed value is empty.
		if(empty($premium_change))
		{
			# Explicitly set the value to FALSE.
			$premium_change=FALSE;
		}
		else
		{
			# Explicitly set the value to TRUE.
			$premium_change=TRUE;
		}
		# Set the data member.
		$this->premium_change=$premium_change;
	} #==== End -- setPremiumChange

	/***
	 * setPublisherOption
	 *
	 * Sets the data member $publisher_option
	 *
	 * @param	$publisher_option
	 * @access	protected
	 */
	protected function setPublisherOption($publisher_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the publisher options.
		if(empty($publisher_option) OR ($publisher_option!=='add' && $publisher_option!=='remove' && $publisher_option!=='select'))
		{
			# Explicitly set the value to NULL.
			$publisher_option=NULL;
		}
		# Set the data member.
		$this->publisher_option=$publisher_option;
	} #==== End -- setPublisherOption

	/***
	 * setStaffOption
	 *
	 * Sets the data member $staff_option
	 *
	 * @param	$staff_option
	 * @access	protected
	 */
	protected function setStaffOption($staff_option)
	{
		# Check if the passed value is empty or doesn't exactly match one of the image options.
		if(empty($staff_option) OR $staff_option!=='add_desc')
		{
			# Explicitly set the value to NULL.
			$staff_option=NULL;
		}
		# Set the data member.
		$this->staff_option=$staff_option;
	} #==== End -- setStaffOption

	/**
	 * setTo
	 *
	 * Sets the data member $to.
	 *
	 * @param	array $array
	 * @access	protected
	 */
	protected function setTo($array)
	{
		# Check if the passed value is empty.
		if(empty($array) OR !is_array($array))
		{
			# Explicitly set the value to NULL.
			$array=NULL;
		}
		# Set the data member.
		$this->to=$array;
	} #==== End -- setTo

	/**
	 * setTwitter
	 *
	 * Sets the data member $twitter.
	 *
	 * @param	$value
	 * @access	protected
	 */
	protected function setTwitter($value)
	{
		# Check if the passed value is empty.
		if($value!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->twitter='0';
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->twitter=NULL;
		}
	} #==== End -- setTwitter

	/**
	 * setUnique
	 *
	 * Sets the data member $unique.
	 *
	 * @param	Integer $value			Whether or not the form item is unique or not. 1 => it is unique, 0 => it is NOT unique.
	 * @access	public
	 */
	public function setUnique($value)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $value is an integer.
		if($validator->isInt($value)===TRUE)
		{
			# Set the data member explicitly making it an integer.
			$this->unique=(int)$value;
		}
		else
		{
			throw new Exception('The passed "unique" value was not an integer!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setUnique

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getCategoryOption
	 *
	 * Returns the data member $category_option.
	 *
	 * @access	public
	 */
	public function getCategoryOption()
	{
		return $this->category_option;
	} #==== End -- getCategoryOption

	/**
	 * getData
	 *
	 * Returns the data member $data.
	 *
	 * @access	public
	 */
	public function getData()
	{
		return $this->data;
	} #==== End -- getData

	/**
	 * getDate
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getDate()
	{
		return $this->date;
	} #==== End -- getDate

	/**
	 * getFacebook
	 *
	 * Returns the data member $facebook.
	 *
	 * @access	public
	 */
	public function getFacebook()
	{
		return $this->facebook;
	} #==== End -- getFacebook

	/**
	 * getFileOption
	 *
	 * Returns the data member $file_option.
	 *
	 * @access	public
	 */
	public function getFileOption()
	{
		return $this->file_option;
	} #==== End -- getFileOption

	/**
	 * getFormURL
	 *
	 * Returns the data member $form_url.
	 *
	 * @access	public
	 */
	public function getFormURL()
	{
		return $this->form_url;
	} #==== End -- getFormURL

	/**
	 * getImageOption
	 *
	 * Returns the data member $image_option.
	 *
	 * @access	public
	 */
	public function getImageOption()
	{
		return $this->image_option;
	} #==== End -- getImageOption

	/**
	 * getInstitutionOption
	 *
	 * Returns the data member $institution_option.
	 *
	 * @access	public
	 */
	public function getInstitutionOption()
	{
		return $this->institution_option;
	} #==== End -- getInstitutionOption

	/**
	 * getLanguageOption
	 *
	 * Returns the data member $language_option.
	 *
	 * @access	public
	 */
	public function getLanguageOption()
	{
		return $this->language_option;
	} #==== End -- getLanguageOption

	/**
	 * getPlaylistOption
	 *
	 * Returns the data member $playlist_option.
	 *
	 * @access	public
	 */
	public function getPlaylistOption()
	{
		return $this->playlist_option;
	} #==== End -- getPlaylistOption

	/**
	 * getPositionOption
	 *
	 * Returns the data member $position_option.
	 *
	 * @access	public
	 */
	public function getPositionOption()
	{
		return $this->position_option;
	} #==== End -- getPositionOption

	/**
	 * getPremiumChange
	 *
	 * Returns the data member $premium_change.
	 *
	 * @access	public
	 */
	public function getPremiumChange()
	{
		return $this->premium_change;
	} #==== End -- getPremiumChange

	/**
	 * getPublisherOption
	 *
	 * Returns the data member $publisher_option.
	 *
	 * @access	public
	 */
	public function getPublisherOption()
	{
		return $this->publisher_option;
	} #==== End -- getPublisherOption

	/**
	 * getStaffOption
	 *
	 * Returns the data member $staff_option.
	 *
	 * @access	public
	 */
	public function getStaffOption()
	{
		return $this->staff_option;
	} #==== End -- getStaffOption

	/**
	 * getTo
	 *
	 * Returns the data member $to.
	 *
	 * @access	public
	 */
	public function getTo()
	{
		return $this->to;
	} #==== End -- getTo

	/**
	 * getTwitter
	 *
	 * Returns the data member $twitter.
	 *
	 * @access	public
	 */
	public function getTwitter()
	{
		return $this->twitter;
	} #==== End -- getTwitter

	/**
	 * getUnique
	 *
	 * Returns the data member $unique.
	 *
	 * @access	public
	 */
	public function getUnique()
	{
		return $this->unique;
	} #==== End -- getUnique

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getCurrentURL
	 *
	 * Returns the current URL.
	 *
	 * @access	public
	 */
	public static function getCurrentURL()
	{
		try
		{
			return WebUtility::removeIndex(PROTOCAL.FULL_DOMAIN.HERE.str_ireplace(array('&amp;delete','&delete'), '', GET_QUERY));
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getCurrentURL

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * setDataToDataMembers
	 *
	 * Populate the data members with the values in the data array.
	 *
	 * @access	protected
	 *
	 * @param	$class_instance			The instance of the secondary class.
	 */
	protected function setDataToDataMembers($class_instance)
	{
		try
		{
			# Loop through the data array.
			foreach($this->getData() as $key=>$value)
			{
				# Create the name of the SubContent method using the key.
				$method='set'.$key;
				# Check if the method exists in the passed class.
				if(method_exists($class_instance, $method)===TRUE)
				{
					# Set the value to the appropriate data member.
					$class_instance->$method($value);
				}
				else
				{
					# Set the value to the appropriate data member in this class.
					$this->$method($value);
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setDataToDataMembers

	/**
	 * setSessionDataToDataArray
	 *
	 * Checks for data in SESSION. If there is and the current URL matches the post form URL
	 * in the SESSION, it sets the SESSION data to the data array data member.
	 *
	 * @access	protected
	 *
	 * @param	$index					The key of the SESSION in question.
	 */
	protected function setSessionDataToDataArray($index)
	{
		try
		{
			# Check if there is a session file.
			if(isset($_SESSION['form'][$index]))
			{
				# Set the current URL to a variable.
				$current_url=FormPopulator::getCurrentURL();
				# Find the key (if it exists) that indicates the Form URL on this page.
				$key=array_search($current_url, $_SESSION['form'][$index]['FormURL']);
				# Check if this page was found in the FormURL SESSION data.
				if($key!==FALSE)
				{
					# Remove any URL's after the current page.
					array_splice($_SESSION['form'][$index]['FormURL'], $key+1);
					# Reset the data array with the session array.
					$this->setData($_SESSION['form'][$index]);
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSessionDataToDataArray

	/*** End protected methods ***/

} # End FormPopulator class.