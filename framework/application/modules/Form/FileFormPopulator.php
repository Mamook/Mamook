<?php /* framework/application/modules/Form/FileFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * FileFormPopulator
 *
 * The FileFormPopulator Class is used populate file forms.
 *
 */
class FileFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $file_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setFileObject
	 *
	 * Sets the data member $file_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setFileObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->file_object=$object;
	} #==== End -- setFileObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getFileObject
	 *
	 * Returns the data member $file_object.
	 *
	 * @access	public
	 */
	public function getFileObject()
	{
		return $this->file_object;
	} #==== End -- getFileObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateFileForm
	 *
	 * Populates a file form with content from the File table in the Databse using the id passed
	 * via GET data, default file data, values passed via POST, or saved SESSION data.
	 *
	 * @param	$data					An array of values tp populate the form with.
	 * @access	public
	 */
	public function populateFileForm($data=array())
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
			# Instantiate a new File object.
			$file_obj=new File();
			# Set the File object to the file data member for use outside of this method.
			$this->setFileObject($file_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('file');

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getFileObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populatePostForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (FileFormPopulator or File).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['file']) && ($_POST['file']=='Add File' OR $_POST['file']=='Update')))
			{
				# Set the data array to a local variable.
				$data=$this->getData();
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();

				# Check if the author was passed via POST data.
				if(isset($_POST['author']))
				{
					# Set the author File data member effectively "cleaning" it.
					$data['Author']=$_POST['author'];
				}

				# Check if availability was passed via POST data.
				if(isset($_POST['availability']))
				{
					# Set the availability File data member effectively "cleaning" it.
					$data['Availability']=$_POST['availability'];
				}

				# Check if the File category was passed via POST data.
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

				# Explicitly make the month an integer.
				$month=(int)$_POST['month'];
				$month=((strlen($month)==1) ? '0'.$month : $month);
				# Explicitly make the day an integer.
				$day=(int)$_POST['day'];
				$day=((strlen($day)==1) ? '0'.$day : $day);
				# Explicitly make the year an integer.
				$year=(int)$_POST['year'];
				# Concatenate the month, day, year into the proper date format and set it to the File data member.
				$data['Date']=$year.'-'.$month.'-'.$day;

				# Check if the file id POST data was sent.
				if(isset($_POST['_file']))
				{
					# Set the file id to the File data member.
					$data['File']=$_POST['_file'];
				}

				# Check if the institution id POST data was sent.
				if(isset($_POST['institution']))
				{
					# Check if the passed institution was "add".
					if($_POST['institution']==='add')
					{
						$data['InstitutionOption']='add';
					}
					else
					{
						# Set the institution id to the File data member effectively "cleaning" it.
						$data['Institution']=$_POST['institution'];
					}
				}

				# Set the POSt data to a variable.
				$language=$_POST['language'];
				# Check if the passed language value is an id.
				if($validator->isInt($language)===TRUE)
				{
					# Get the Language class.
					require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
					# Instantiate a new Language object.
					$language_object=new Language();
					# Get the language info from the `languages` table.
					$language_object->getThisLanguage($language);
					# Set the language name to the variable.
					$language=$language_object->getLanguage();
				}
				if($language=='add')
				{
					$data['LanguageOption']='add';
				}
				else
				{
					# Set the language to the File data member.
					$data['Language']=$language;
				}

				# Check if the publish location was passed via POST data.
				if(isset($_POST['location']))
				{
					# Set the author File data member effectively "cleaning" it.
					$data['Location']=$_POST['location'];
				}

				# Set the previous premium value to a variable.
				$previous_premium=$data['Premium'];
				# Set the premium value to NULL by default.
				$data['Premium']=NULL;
				# Check if premium POST data was sent.
				if(isset($_POST['premium']))
				{
					# Set the premium value to 0 indicating premium content.
					$data['Premium']=0;
				}

				# Check if the previous value hase changed.
				if($data['Premium']!==$previous_premium)
				{
					$this->setPremiumChange(TRUE);
				}
				else
				{
					$this->setPremiumChange(FALSE);
				}

				# Check if the publisher id POST data was sent.
				if(isset($_POST['publisher']))
				{
					# Set the publisher id to the File data member effectively "cleaning" it.
					$data['Publisher']=$_POST['publisher'];
				}

				# Check if title POST data was sent.
				if(isset($_POST['title']))
				{
					# Set the title to the File data member.
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

				# Check if the unique POST data was sent.
				if(isset($_POST['file_year']))
				{
					$year=$_POST['file_year'];
					if(empty($_POST['file_year']) OR ($_POST['file_year']=='unknown'))
					{
						$year=NULL;
					}
					# Set the unique value to the data member.
					$data['Year']=$year;
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

} # End FileFormPopulator class.