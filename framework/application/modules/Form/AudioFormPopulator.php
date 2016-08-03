<?php /* framework/application/modules/Form/AudioFormPopulator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * AudioFormPopulator
 *
 * The AudioFormPopulator Class is used populate audio forms.
 *
 */
class AudioFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $audio_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAudioObject
	 *
	 * Sets the data member $audio_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setAudioObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->audio_object=$object;
	} #==== End -- setAudioObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAudioObject
	 *
	 * Returns the data member $audio_object.
	 *
	 * @access	public
	 */
	public function getAudioObject()
	{
		return $this->audio_object;
	} #==== End -- getAudioObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateAudioForm
	 *
	 * Populates a audio form with content from the `audio` table in the Databse using the id passed
	 * via GET data, default audio data, values passed via POST, or saved SESSION data.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populateAudioForm($data=array())
	{
		try
		{
			# Get the Audio class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
			# Instantiate a new Audio object.
			$audio_object=new Audio();
			# Set the Audio object to the audio_object data member for use outside of this method.
			$this->setAudioObject($audio_object);

			# Set the passed data to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('audio');

			# Set any POST values to the appropriate data members.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getAudioObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateAudioForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new data values from POST data, they are set to the appropriate data
	 * member (AudioFormPopulator or Audio).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['audio']) && ($_POST['audio']=='Add Audio' OR $_POST['audio']=='Update')))
			{
				# Set the data array to a local variable.
				$data=$this->getData();
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();

				# Check if the author was passed via POST data.
				if(isset($_POST['author']))
				{
					# Set the author to the data array.
					$data['Author']=$_POST['author'];
				}

				# Check if availability was passed via POST data.
				if(isset($_POST['availability']))
				{
					# Set the availability to the data array.
					$data['Availability']=$_POST['availability'];
				}

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

				# Explicitly make the month an integer.
				$month=(int)$_POST['month'];
				$month=((strlen($month)==1) ? '0'.$month : $month);
				# Explicitly make the day an integer.
				$day=(int)$_POST['day'];
				$day=((strlen($day)==1) ? '0'.$day : $day);
				# Explicitly make the year an integer.
				$year=(int)$_POST['year'];
				# Concatenate the month, day, year into the proper date format and set it to the data array.
				$data['Date']=$year.'-'.$month.'-'.$day;

				# Check if description POST data was sent.
				if(isset($_POST['description']))
				{
					# Set the text to the data array.
					$data['Description']=$_POST['description'];
				}

				# Check if the audio type is an embed code.
				if(isset($_POST['audio-type']) && $_POST['audio-type']=='embed' && isset($_POST['embed_code']))
				{
					# Set the embed code to the data array.
					$data['EmbedCode']=$_POST['embed_code'];
				}

				# If the Facebook POST data was sent and the value is "post" set 'post' to the data array.
				if(isset($_POST['facebook']) && ($_POST['facebook']==='post'))
				{
					# Set the Facebook value to the data array as "post".
					$data['Facebook']='post';
				}
				else
				{
					# Set the Facebook value to 0 in the data array.
					$data['Facebook']=NULL;
				}

				# Check if the file id POST data was sent.
				if(isset($_POST['_audio']))
				{
					# Set the file id to the FileName data member.
					$data['FileName']=$_POST['_audio'];
				}

				# Check if the image id POST data was sent.
				if(isset($_POST['_image_id']))
				{
					# Set the image id to the data array.
					$data['ImageID']=$_POST['_image_id'];
				}

				# Check if image POST data was sent.
				if(isset($_POST['image_option']) && !empty($_POST['image_option']))
				{
					# Set the image option ("add", "remove", or "select") to the Content data member.
					$data['ImageOption']=$_POST['image_option'];
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
						# Set the institution id to the data array.
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
				if($language==='add')
				{
					$data['LanguageOption']='add';
				}
				else
				{
					# Set the language to the data array.
					$data['Language']=$language;
				}

				# Check if the Audio playlist was passed via POST data.
				if(isset($_POST['playlist']))
				{
					# Check if the playlist option "add" was passed in POST data.
					if(($key=array_search('add', $_POST['playlist']))!==FALSE)
					{
						# Remove the index from the array that holds the "add" value.
						unset($_POST['playlist'][$key]);
						# Set "add" to the "PlaylistOption" index of the data array.
						$data['PlaylistOption']='add';
					}
					# Set the Audio playlists data member.
					$data['Playlists']=$_POST['playlist'];
				}

				# Check if the publisher id POST data was sent.
				if(isset($_POST['publisher']))
				{
					# Check if the passed publisher was "add".
					if($_POST['publisher']==='add')
					{
						$data['PublisherOption']='add';
					}
					else
					{
						# Set the publisher id to the data array.
						$data['Publisher']=$_POST['publisher'];
					}
				}

				# Check if title POST data was sent.
				if(isset($_POST['title']))
				{
					# Set the title to the File data member.
					$data['Title']=$_POST['title'];
				}

				# If the Twitter POST data was sent and the value is "tweet" set 0 to the variable, otherwise set NULL.
				if(isset($_POST['twitter']) && ($_POST['twitter']==='tweet'))
				{
					# Set the Twitter value to the data array as "tweet".
					$data['Twitter']='tweet';
				}
				else
				{
					# Set the Twitter value to 0 in the data array.
					$data['Twitter']=NULL;
				}

				# Check if the unique POST data was sent.
				if(isset($_POST['_unique']))
				{
					$unique=1;
					if(empty($_POST['_unique']))
					{
						$unique=0;
					}
					# Set the unique value to the data array.
					$data['Unique']=$unique;
				}

				# Check for the audio type.
				if(isset($_POST['audio-type']))
				{
					# Set the Audio Type to the data array.
					$data['AudioType']=$_POST['audio-type'];
				}

				# Check if the unique POST data was sent.
				if(isset($_POST['audio_year']))
				{
					$year=$_POST['audio_year'];
					if(empty($_POST['audio_year']) OR ($_POST['audio_year']=='unknown'))
					{
						$year='0000';
					}
					# Set the year the audio was first published to the data array.
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

} # End AudioFormPopulator class.