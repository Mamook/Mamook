<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Audio
 *
 * The Audio class is used to access and manipulate audio.
 *
 * @dependencies		Requires "data/path_definitions.php".
 * @dependencies		Requires "{MODULES}/Content/Category.php".
 * @dependencies		Requires "{MODULES}/Content/Language.php".
 * @dependencies		Requires "{MODULES}/FileHandler/FileHandler.php".
 * @dependencies		Requires "{MODULES}/Media/Image.php".
 * @dependencies		Requires "{MODULES}/Media/Soundcloud/Soundcloud.php".
 * @dependencies		Requires "{MODULES}/User/Contributor.php".
 */
class Audio
{
	/*** data members ***/

	private static $audio_obj;
	private $all_audio=array();
	private $api=NULL;
	private $audio_id=NULL;
	private $audio_type=NULL;
	private $audio_url=NULL;
	private $author=NULL;
	private $availability;
	private $category=NULL;
	private $cat_object=NULL;
	private $confirmation_template=NULL;
	# $contributor is an object.
	private $contributor=NULL;
	private $cont_id=NULL;
	private $date='0000-00-00';
	private $description=NULL;
	private $embed=NULL;
	private $embed_code=NULL;
	private $file_name=NULL;
	private $id=NULL;
	private $image_id=NULL;
	private $image_obj=NULL;
	private $institution=NULL;
	private $is_playlist=FALSE;
	private $language=NULL;
	private $last_edit='0000-00-00';
	private $playlists=array();
	private $playlist_object=NULL;
	private $publisher=NULL;
	# $recent_contributor is an object.
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $soundcloud_obj=NULL;
	private $thumbnail_url=NULL;
	private $title=NULL;
	private $year=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllAudio
	 *
	 * Sets the data member $all_audio.
	 *
	 * @param	$all_audio					May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllAudio($all_audio)
	{
		# Check if the passed value is empty.
		if(!empty($all_audio))
		{
			# Explicitly make it an array.
			$all_audio=(array)$all_audio;
			# Set the data member.
			$this->all_audio=$all_audio;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_audio=array();
		}
	} #==== End -- setAllAudio

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param	int $api
	 * @access	public
	 */
	public function setAPI($api)
	{
		# Check if the passed value is empty.
		if(!empty($api))
		{
			# Clean it up.
			$api=trim($api);
			# Set the data member.
			$this->api=$api;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->api=NULL;
		}
	} #==== End -- setAPI

	/**
	 * setAudioId
	 *
	 * Set the data member $audio_id
	 * This is the ID on Soundcloud.
	 *
	 * @param	string $audio_id
	 * @access	public
	 */
	public function setAudioId($audio_id)
	{
		$this->audio_id=$audio_id;
	} #==== End -- setAudioId

	/**
	 * setAudioType
	 *
	 * Sets the data member $audio_type.
	 *
	 * @param	$audio_type
	 * @access	public
	 */
	public function setAudioType($audio_type)
	{
		# Set the data member.
		$this->audio_type=$audio_type;
	} #==== End -- setAudioType

	/**
	 * setAudioUrl
	 *
	 * Set the data member $audio_url
	 *
	 * @param	string $audio_url
	 * @access	public
	 */
	public function setAudioUrl($audio_url)
	{
		$this->audio_url=$audio_url;
	} #==== End -- setAudioUrl

	/**
	 * setAuthor
	 *
	 * Sets the data member $author.
	 *
	 * @param	int $author
	 * @access	public
	 */
	public function setAuthor($author)
	{
		# Check if the passed value is empty.
		if(!empty($author))
		{
			# Strip slashes and decode any html entities.
			$author=html_entity_decode(stripslashes($author), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$author=trim($author);
			# Set the data member.
			$this->author=$author;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->author=NULL;
		}
	} #==== End -- setAuthor

	/**
	 * setAvailability
	 *
	 * Sets the data member $availability.
	 *
	 * @param	int $availability
	 * @access	public
	 */
	public function setAvailability($availability)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Clean it up.
		$availability=trim($availability);
		# Check if the passed value is an integer.
		if($validator->isInt($availability)===TRUE)
		{
			# Set the data member explicitly making it an integer.
			$this->availability=(int)$availability;
		}
		else
		{
			throw new Exception('The passed value for availability was not an integer!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setAvailability

	/**
	 * setCategory
	 *
	 * Sets the data member $category.
	 *
	 * @param	int $category
	 * @access	public
	 */
	public function setCategory($category)
	{
		# Check if the passed value is empty.
		if(!empty($category))
		{
			# Clean it up.
			$category=trim($category);
			# Set the data member.
			$this->category=$category;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->category=NULL;
		}
	} #==== End -- setCategory

	/**
	 * setCatObject
	 *
	 * Sets the data member $cat_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setCatObject($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->cat_object=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cat_object=NULL;
		}
	} #==== End -- setCatObject

	/**
	 * setConfirmationTemplate
	 *
	 * Sets the data member $confirmation_template.
	 *
	 * @param	$path
	 * @access	public
	 */
	public function setConfirmationTemplate($path)
	{
		# Check if the passed value is empty.
		if(!empty($path))
		{
			# Clean it up.
			$path=trim($path);
			# Check if this is a file.
			if(is_file($path)===FALSE)
			{
				# Set the data member.
				$path=NULL;
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$path=NULL;
		}
		# Set the data member.
		$this->confirmation_template=$path;
	} #==== End -- setConfirmationTemplate

	/**
	 * setContributor
	 *
	 * Sets the data member $contributor.
	 *
	 * @param	$object
	 * @access	public
	 */
	public function setContributor($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->contributor=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->contributor=NULL;
		}
	} #==== End -- setContributor

	/**
	 * setContID
	 *
	 * Sets the data member $cont_id.
	 *
	 * @param	int $id
	 * @access	public
	 */
	public function setContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
				# Get the Contributor class.
				require_once MODULES.'User'.DS.'Contributor.php';
				# Instantiate a new Contributor object.
				$cont=new Contributor();
				# Get the contributor name.
				$cont->getThisContributor($id, 'id', FALSE);
				# Set the Contributor object to the data member making it available outside the method.
				$this->setContributor($cont);
			}
			else
			{
				throw new Exception('The passed contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->cont_id=$id;
	} #==== End -- setContID

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param	$date
	 * @access	public
	 */
	public function setDate($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
		{
			# Clean it up,
			$date=trim($date);
			# Set the data member.
			$this->date=$date;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->date='0000-00-00';
		}
	} #==== End -- setDate

	/**
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param	string $description
	 * @access	public
	 */
	public function setDescription($description)
	{
		# Check if the passed value is empty.
		if(!empty($description))
		{
			# Strip slashes and decode any html entities.
			$description=html_entity_decode(stripslashes($description), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$description=trim($description);
			# Set the data member.
			$this->description=$description;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->description=NULL;
		}
	} #==== End -- setDescription

	/**
	 * setEmbed
	 *
	 * Set the data member $embed.
	 * Converts string to UTF-8. If the string is HTML then it strips the HTML and get's the first URL.
	 * Then it get's the value after the last slash (/) in the URL which will be the Audio ID (on Soundcloud).
	 *
	 * @param	string $embed_code
	 * @access	public
	 */
	public function setEmbed($embed_code)
	{
		# Check if the passed value is empty.
		if(!empty($embed_code))
		{
			# Clean it up.
			$embed_code=htmlspecialchars(str_replace('"', '', $embed_code), ENT_COMPAT, 'UTF-8');

			# Is $embed_code an HTML tag?
			if($embed_code==strip_tags($embed_code))
			{
				# Extract the first URL from $embed.
				preg_match('/\b(?:(?:https?):\/\/|www\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $embed_code, $matches);
				# Get's the value after the last slash in the URL (which will be the Soundcloud Audio ID).
				$embed=substr(strrchr(rtrim($matches[0], '/'), '/'), 1);
			}
			# Set the data member.
			$this->embed=$embed;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->embed=NULL;
		}
	} #==== End -- setEmbed

	/**
	 * setEmbedCode
	 *
	 * Sets the data member $embed_code.
	 *
	 * @param	string $embed_code
	 * @access	public
	 */
	public function setEmbedCode($embed_code)
	{
		# Check if the passed value is empty.
		if(!empty($embed_code))
		{
			# Strip slashes and decode any html entities.
			$embed_code=html_entity_decode(stripslashes($embed_code), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$embed_code=trim($embed_code);
			# Get the audio ID from the $emebed_code.
			$this->setEmbed($embed_code);
			# Set the data member.
			$this->embed_code=$embed_code;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->embed_code=NULL;
		}
	} #==== End -- setEmbedCode

	/**
	 * setFileName
	 *
	 * Sets the data member $file_name.
	 *
	 * @param	$file_name
	 * @access	public
	 */
	public function setFileName($file_name)
	{
		# Check if the passed value is empty.
		if(!empty($file_name))
		{
			# Clean it up.
			$file_name=trim($file_name);

			# Set the data member.
			$this->file_name=$file_name;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file_name=NULL;
		}
	} #==== End -- setFileName

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param	int $id
	 * @access	public
	 */
	public function setID($id)
	{
		# Check if the passed $id is empty.
		if(!empty($id) && $id!=='add' && $id!=='select')
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly making it an integer.
				$id=(int)trim($id);
			}
			else
			{
				throw new Exception('The passed audio id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->id=$id;
	} #==== End -- setID

	/**
	 * setImageID
	 *
	 * Sets the data member $image_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setImageID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add' && $id!=='select' && $id!=='remove')
			{
				throw new Exception('The passed image id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->image_id=$id;
	} #==== End -- setImageID

	/**
	 * setImageObj
	 *
	 * Sets the data member $image_obj.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setImageObj($object)
	{
		# Set the data member.
		$this->image_obj=$object;
	} #==== End -- setImageObj

	/**
	 * setInstitution
	 *
	 * Sets the data member $institution.
	 *
	 * @param	$institution
	 * @access	public
	 */
	public function setInstitution($institution)
	{
		# Check if the passed value is empty.
		if(!empty($institution))
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$institution=trim($institution);
			# Check if the value passed is an institution id.
			if($validator->isInt($institution)===TRUE)
			{
				# Get the Institution class.
				require_once MODULES.'Content'.DS.'Institution.php';
				# Instantiate a new Cnstitution object.
				$inst=new institution();
				# Get the institution name.
				$inst->getThisInstitution($institution, TRUE);
				# Set the institution name to a variable.
				$institution=$inst->getInstitution();
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$institution=NULL;
		}
		# Set the data member.
		$this->institution=$institution;
	} #==== End -- setInstitution

	/**
	 * setIsPlaylist
	 *
	 * Set the data member $is_playlist
	 *
	 * @param	boolean $is_playlist
	 * @access	private
	 */
	private function setIsPlaylist($is_playlist)
	{
		$this->is_playlist=$is_playlist;
	} #==== End -- setIsPlaylist

	/**
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param	int $language
	 * @access	public
	 */
	public function setLanguage($language)
	{
		# Check if the passed value is empty.
		if(!empty($language))
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$language=trim($language);
			# Check if the value passed is an language id.
			if($validator->isInt($language)===TRUE)
			{
				# Get the Language class.
				require_once MODULES.'Content'.DS.'Language.php';
				# Instantiate a new Cnstitution object.
				$lang=new language();
				# Get the language name.
				$lang->getThisLanguage($language);
				# Set the language name to a variable.
				$language=$lang->getLanguage();
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$language=NULL;
		}
		# Set the data member.
		$this->language=$language;
	} #==== End -- setLanguage

	/**
	 * setLastEdit
	 *
	 * Sets the data member $last_edit.
	 *
	 * @param	$date
	 * @access	public
	 */
	public function setLastEdit($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00'))
		{
			# Explode the date into an array casting each as an integer.
			$date=explode('-', $date);
			$year=(int)$date[0];
			$month=(int)$date[1];
			$day=(int)$date[2];
			if(checkdate($month, $day, $year)===TRUE)
			{
				# Make sure the day is the correct length.
				if(strlen($day)!=2)
				{
					$day='0'.$day;
				}
				# Make sure the month is the correct length.
				if(strlen($month)!=2)
				{
					$month='0'.$month;
				}
				# Put the date back together in the correct format.
				$date=$year.'-'.$month.'-'.$day;
				# Set the data member.
				$this->last_edit=$date;
			}
			else
			{
				throw new Exception('The passed last edit date was not an acceptable date.', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->last_edit='0000-00-00';
		}
	} #==== End -- setLastEdit

	/**
	 * setPlaylists
	 *
	 * Sets the data member $playlists.
	 *
	 * @param	$value
	 * @access	public
	 */
	public function setPlaylists($value)
	{
		# Check if the passed value if empty.
		if(!empty($value))
		{
			# Check if the passed value is an array.
			if(!is_array($value))
			{
				# Trim dashes(-) off both ends of the string.
				$value=trim($value, '-');
				# Explode the string into an array.
				$value=explode('-', $value);
			}
			# Create an empty array to hold the playlist.
			$playlists=array();
			# Get the Category class.
			require_once MODULES.'Content'.DS.'Category.php';
			# Instantiate a new Category object.
			$playlist=new Category();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Loop through the array of playlist id's.
			foreach($value as $playlist_value)
			{
				# Check if the value passed is a category id.
				if($validator->isInt($playlist_value)===TRUE)
				{
					# Get the playlist name.
					$playlist->getThisCategory($playlist_value);
					# Set the playlist name and id to the $playlist array.
					$playlists[$playlist_value]=$playlist->getCategory();
				}
				else
				{
					# Get the playlist id.
					$playlist->getThisCategory($playlist_value, FALSE);
					# Set the playlist name and id to the $playlist array.
					$playlists[$playlist->getID()]=$playlist_value;
				}
			}
			# Set the data member.
			$this->playlists=$playlists;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->playlists=array();
		}
	} #==== End -- setPlaylists

	/**
	 * setPlaylistObject
	 *
	 * Sets the data member $playlist_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setPlaylistObject($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->playlist_object=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->playlist_object=NULL;
		}
	} #==== End -- setPlaylistObject

	/**
	 * setPublisher
	 *
	 * Sets the data member $publisher.
	 *
	 * @param	int $publisher
	 * @param	boolean $id 				TRUE if the passed value $publisher is an id, FALSE if not.
	 * @access	public
	 */
	public function setPublisher($publisher)
	{
		# Check if the passed value is empty.
		if(!empty($publisher))
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$publisher=trim($publisher);
			# Check if the value passed is an publisher id.
			if($validator->isInt($publisher)===TRUE)
			{
				# Get the Publisher class.
				require_once MODULES.'Content'.DS.'Publisher.php';
				# Instantiate a new Cnstitution object.
				$pub=new publisher();
				# Get the publisher name.
				$pub->getThisPublisher($publisher, $id);
				# Set the publisher name to the variable.
				$publisher=$pub->getPublisher();
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$publisher=NULL;
		}
		# Set the data member.
		$this->publisher=$publisher;
	} #==== End -- setPublisher

	/**
	 * setRecentContributor
	 *
	 * Sets the data member $recent_contributor.
	 *
	 * @param	$object
	 * @access	public
	 */
	public function setRecentContributor($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->recent_contributor=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->recent_contributor=NULL;
		}
	} #==== End -- setRecentContributor

	/**
	 * setRecentContID
	 *
	 * Sets the data member $recent_cont_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setRecentContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer and set the data member.
				$this->recent_cont_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed recent contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->recent_cont_id=NULL;
		}
	} #==== End -- setRecentContID

	/**
	 * setSoundcloudObject
	 *
	 * Set the data member $soundcloud_obj
	 *
	 * @param	string $object
	 * @access	private
	 */
	private function setSoundcloudObject($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			$this->soundcloud_obj=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->soundcloud_obj=NULL;
		}
	} #==== End -- setSoundcloudObject

	/**
	 * setThumbnail
	 *
	 * Sets the data member $thumbnail.
	 *
	 * @param	$thumbnail
	 * @access	public
	 */
	public function setThumbnail($thumbnail)
	{
		# Check if the passed value is empty.
		if(!empty($thumbnail))
		{
			# Clean it up.
			$thumbnail=trim($thumbnail);

			# Set the data member.
			$this->thumbnail=$thumbnail;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->thumbnail=NULL;
		}
	} #==== End -- setThumbnail

	/**
	 * setThumbnailUrl
	 *
	 * Set the data member $thumbnail_url
	 *
	 * @param	string $thumbnail_url
	 * @access	public
	 */
	public function setThumbnailUrl($thumbnail_url)
	{
		$this->thumbnail_url=$thumbnail_url;
	} #==== End -- setThumbnailUrl

	/**
	 * setTitle
	 *
	 * Set the data member $title
	 *
	 * @param	string $title
	 * @access	public
	 */
	public function setTitle($title)
	{
		# Check if the passed value is empty.
		if(!empty($title))
		{
			# Strip slashes and decode any html entities.
			$title=html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$title=trim($title);
			# Set the data member.
			$this->title=$title;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->title=NULL;
		}
	} #==== End -- setTitle

	/**
	 * setYear
	 *
	 * Sets the data member $year.
	 *
	 * @param	int $year
	 * @access	public
	 */
	public function setYear($year)
	{
		# Check if the passed value is empty.
		if(!empty($year))
		{
			# Clean it up.
			$year=trim($year);
			# Set the data member.
			$this->year=$year;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->year=NULL;
		}
	} #==== End -- setYear

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllAudio
	 *
	 * Returns the data member $all_audio.
	 *
	 * @access	public
	 */
	public function getAllAudio()
	{
		return $this->all_audio;
	} #==== End -- getAllAudio

	/**
	 * getAPI
	 *
	 * Returns the data member $api.
	 *
	 * @access	public
	 */
	public function getAPI()
	{
		return $this->api;
	} #==== End -- getAPI

	/**
	 * getAudioId
	 *
	 * Gets the data member $audio_id
	 * This is the ID on Soundcloud.
	 *
	 * @access	public
	 */
	public function getAudioId()
	{
		return $this->audio_id;
	} #==== End -- getAudioId

	/**
	 * getAudioType
	 *
	 * Returns the data member $audio_type.
	 *
	 * @access	public
	 */
	public function getAudioType()
	{
		return $this->audio_type;
	} #==== End -- getAudioType

	/**
	 * getAudioUrl
	 *
	 * Gets the data member $audio_url
	 *
	 * @access	public
	 */
	public function getAudioUrl()
	{
		return $this->audio_url;
	} #==== End -- getAudioUrl

	/**
	 * getAuthor
	 *
	 * Returns the data member $author.
	 *
	 * @access	public
	 */
	public function getAuthor()
	{
		return $this->author;
	} #==== End -- getAuthor

	/**
	 * getAvailability
	 *
	 * Returns the data member $availability.
	 *
	 * @access	public
	 */
	public function getAvailability()
	{
		return $this->availability;
	} #==== End -- getAvailability

	/**
	 * getCategory
	 *
	 * Returns the data member $category.
	 *
	 * @access	public
	 */
	public function getCategory()
	{
		return $this->category;
	} #==== End -- getCategory

	/**
	 * getCatObject
	 *
	 * Returns the data member $cat_object.
	 *
	 * @access	protected
	 */
	protected function getCatObject()
	{
		return $this->cat_object;
	} #==== End -- getCatObject

	/**
	 * getConfirmationTemplate
	 *
	 * Returns the data member $confirmation_template.
	 *
	 * @access	public
	 */
	public function getConfirmationTemplate()
	{
		return $this->confirmation_template;
	} #==== End -- getConfirmationTemplate

	/**
	 * getContributor
	 *
	 * Returns the data member $contributor.
	 *
	 * @access	public
	 */
	public function getContributor()
	{
		return $this->contributor;
	} #==== End -- getContributor

	/**
	 * getContID
	 *
	 * Returns the data member $cont_id.
	 *
	 * @access	public
	 */
	public function getContID()
	{
		return $this->cont_id;
	} #==== End -- getContID

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
	 * getDescription
	 *
	 * Returns the data member $description.
	 *
	 * @access	public
	 */
	public function getDescription()
	{
		return $this->description;
	} #==== End -- getDescription

	/**
	 * getEmbed
	 *
	 * Gets the data member $embed
	 *
	 * @access	public
	 */
	public function getEmbed()
	{
		return $this->embed;
	} #==== End -- getEmbed

	/**
	 * getEmbedCode
	 *
	 * Gets the data member $embed_code
	 *
	 * @access	public
	 */
	public function getEmbedCode()
	{
		return $this->embed_code;
	} #==== End -- getEmbedCode

	/**
	 * getFileName
	 *
	 * Returns the data member $file_name.
	 *
	 * @access	public
	 */
	public function getFileName()
	{
		return $this->file_name;
	} #==== End -- getFileName

	/**
	 * getID
	 *
	 * Returns the data member $id.
	 *
	 * @access	public
	 */
	public function getID()
	{
		return $this->id;
	} #==== End -- getID

	/**
	 * getImageID
	 *
	 * Returns the data member $image_id.
	 *
	 * @access	public
	 */
	public function getImageID()
	{
		return $this->image_id;
	} #==== End -- getImageID

	/**
	 * getImageObj
	 *
	 * Returns the data member $image_obj.
	 *
	 * @access	public
	 */
	public function getImageObj()
	{
		return $this->image_obj;
	} #==== End -- getImageObj

	/**
	 * getInstitution
	 *
	 * Returns the data member $institution.
	 *
	 * @access	public
	 */
	public function getInstitution()
	{
		return $this->institution;
	} #==== End -- getInstitution

	/**
	 * getIsPlaylist
	 *
	 * Gets the data member $is_playlist
	 *
	 * @access	public
	 */
	public function getIsPlaylist()
	{
		return $this->is_playlist;
	} #==== End -- getIsPlaylist

	/**
	 * getLanguage
	 *
	 * Returns the data member $language.
	 *
	 * @access	public
	 */
	public function getLanguage()
	{
		return $this->language;
	} #==== End -- getLanguage

	/**
	 * getLastEdit
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getLastEdit()
	{
		return $this->last_edit;
	} #==== End -- getLastEdit

	/**
	 * getPlaylists
	 *
	 * Returns the data member $playlists.
	 *
	 * @access	public
	 */
	public function getPlaylists()
	{
		return $this->playlists;
	} #==== End -- getPlaylists

	/**
	 * getPlaylistObject
	 *
	 * Returns the data member $playlist_object.
	 *
	 * @access	protected
	 */
	protected function getPlaylistObject()
	{
		return $this->playlist_object;
	} #==== End -- getPlaylistObject

	/**
	 * getPublisher
	 *
	 * Returns the data member $publisher.
	 *
	 * @access	public
	 */
	public function getPublisher()
	{
		return $this->publisher;
	} #==== End -- getPublisher

	/**
	 * getRecentContributor
	 *
	 * Returns the data member $recent_contributor.
	 *
	 * @access	public
	 */
	public function getRecentContributor()
	{
		return $this->recent_contributor;
	} #==== End -- getRecentContributor

	/**
	 * getRecentContID
	 *
	 * Returns the data member $recent_cont_id.
	 *
	 * @access	public
	 */
	public function getRecentContID()
	{
		return $this->recent_cont_id;
	} #==== End -- getRecentContID

	/**
	 * getSoundcloudObject
	 *
	 * Returns the data member $soundcloud_obj.
	 *
	 * @param	$domain						Optional. This is set for cron and CommandLine scripts.
	 * @access	public
	 */
	public function getSoundcloudObject($domain=NULL)
	{
		# Check if there is a Soundcloud object.
		if(empty($this->soundcloud_obj) OR !is_object($this->soundcloud_obj))
		{
			# Get the Soundcloud Class.
			require_once MODULES.'Media'.DS.'Soundcloud'.DS.'Soundcloud.php';

			# Instantiate a new Soundcloud object.
			$soundcloud_obj=Soundcloud::getInstance();

			# Set the Soundcloud object to the data member.
			$this->setSoundcloudObject($soundcloud_obj);

			$soundcloud_obj->setSoundcloudClientId(SOUNDCLOUD_CLIENT_ID);
			$soundcloud_obj->setSoundcloudClientSecret(SOUNDCLOUD_CLIENT_SECRET);
			if($domain!==NULL)
			{
				$soundcloud_obj->setSoundcloudRedirectUri($domain);
			}
			else
			{
				$soundcloud_obj->setSoundcloudRedirectUri(APPLICATION_URL.HERE);
			}
			$soundcloud_obj->setSoundcloudDevKey(SOUNDCLOUD_DEV_KEY);
			$soundcloud_obj->setSoundcloudRefreshToken(SOUNDCLOUD_REFRESH_TOKEN);

			# Start the Soundcloud Service.
			$soundcloud_obj->startSoundcloudService();
		}
		return $this->soundcloud_obj;
	} #==== End -- getSoundcloudObject

	/**
	 * getThumbnail
	 *
	 * Returns the data member $thumbnail.
	 *
	 * @access	public
	 */
	public function getThumbnail()
	{
		return $this->thumbnail;
	} #==== End -- getThumbnail

	/**
	 * getThumbnailUrl
	 *
	 * Gets the data member $thumbnail_url
	 *
	 * @access	public
	 */
	public function getThumbnailUrl()
	{
		return $this->thumbnail_url;
	} #==== End -- getThumbnailUrl

	/**
	 * getTitle
	 *
	 * Gets the data member $title
	 *
	 * @access	public
	 */
	public function getTitle()
	{
		return $this->title;
	} #==== End -- getTitle

	/**
	 * getYear
	 *
	 * Returns the data member $year.
	 *
	 * @access	public
	 */
	public function getYear()
	{
		return $this->year;
	} #==== End -- getYear

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllAudio
	 *
	 * Returns the number of audio files in the database.
	 *
	 * @access	public
	 * @param		$categories (The names and/or id's of the category(ies) to be retrieved. May be multiple categories - separate with dash, ie. '50-60-Archives-110'. "!" may be used to exlude categories, ie. '50-!60-Archives-110')
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 */
	public function countAllAudio($categories=NULL, $limit=NULL, $and_sql=NULL)
	{
		# Check if there were categories passed.
		if($categories===NULL)
		{
			throw new Exception('You must provide a category!', E_RECOVERABLE_ERROR);
		}
		else
		{
			try
			{
				# Get the Category class.
				require_once MODULES.'Content'.DS.'Category.php';
				# Instantiate a new Category object.
				$category=new Category();
				# Set the Category object to a data member.
				$this->setCatObject($category);
				# Reset the Category object variable with the instance from the data member.
				$category=$this->getCatObject();
				# Create the WHERE clause for the passed $categories string.
				$category->createWhereSQL($categories, 'playlist');
				# Set the newly created WHERE clause to a variable.
				$where=$category->getWhereSQL();
				try
				{
					# Set the Database instance to a variable.
					$db=DB::get_instance();
					# Count the records.
					$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'audio` WHERE '.$where.(($and_sql===NULL) ? '' : ' '.$and_sql).' AND `new` = 0'.(($limit===NULL) ? '' : ' LIMIT '.$limit));
					return $count;
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('Error occured: ' . $ez->message . '<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
	} #==== End -- countAllAudio

	/**
	 * createPlaylistMenu
	 *
	 * Creates media XHTML elements and sets them to an array for display.
	 *
	 * @access	public
	 */
	public function createPlaylistMenu()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		try
		{
			# Get the Category class.
			require_once MODULES.'Content'.DS.'Category.php';
			# Instantiate a new Category object.
			$playlist=new Category();
			# get the categories from the `categories` table.
			$playlist->getCategories(NULL, '`id`, `category`', 'category', 'ASC', ' WHERE `api` IS NOT NULL AND `private` IS NULL');
			# Set the playlists to a variable.
			$playlists=$playlist->getAllCategories();

			$playlist_items='<li>No playlists</li>';
			if(!empty($playlists))
			{
				$playlist_items='';
				foreach($playlists as $playlists_data)
				{
					$title=$playlists_data->category;
					$playlist_id=$playlists_data->id;
					$url=APPLICATION_URL.AUDIO_PATH.'?playlist='.$playlist_id;
					$playlist_items.='<li'.$doc->addHereClass($url).'>'.
							'<a href="'.$url.'"'.$doc->addHereClass($url).' title="'.$title.' audio playlist">'.
								$title.
							'</a>'.
						'</li>';
				}
			}
			return $playlist_items;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- createPlaylistMenu

	/**
	 * deleteAudio
	 *
	 * Removes an audio record from the `audio` table and the actual audio file from the system.
	 *
	 * @param	int $id					The id of the audio in the `audio` table.
	 * @param	$redirect
	 * @access	public
	 */
	public function deleteAudio($id, $redirect=NULL)
	{
		# Bring the Login object into scope.
		global $login;

		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Check if a redirect URL was passed.
				if($redirect===NULL)
				{
					# Set the redirect to the default.
					$redirect=PROTOCAL.FULL_DOMAIN.HERE;
				}
				# Check if the passed redirect URL was FALSE.
				if($redirect===FALSE)
				{
					# Set the value to NULL (no redirect).
					$redirect===NULL;
				}
				# Validate the passed id as an integer.
				if($validator->isInt($id)===TRUE)
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the audio is premium content or not.
					$this_audio=$this->getThisAudio($id);
					# Check if the audio was found.
					if($this_audio!==TRUE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The audio was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the audio's categories data member to a local variable.
					$audio_cats=$this->getPlaylists();
					# Set the audio's name data member to a local variable.
					$audio_name=$this->getFileName();
					# Get the FileHandler class.
					require_once MODULES.'FileHandler'.DS.'FileHandler.php';
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Remove the file extension.
					$audio_name_no_ext=substr($audio_name, 0, strrpos($audio_name, '.'));
					# Delete the audio.
					if(($file_handler->deleteFile(AUDIO_PATH.'files'.DS.$audio_name_no_ext.'.mp3')===TRUE) && ($file_handler->deleteFile(BODEGA.'audio'.DS.$audio_name)===TRUE))
					{
						try
						{
							# Delete the audio from the `audio` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'audio` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The audio '.$audio_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: ' . $ez->message . ', but the audio file itself was deleted.<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					else
					{
						# Set a message to display to the user.
						$_SESSION['message']='That was not a valid audio for deletion.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That audio was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteAudio

	/**
	 * displayAudioFeed
	 *
	 * Prints the users audio feed.
	 *
	 * @access	public
	 */
	public function displayAudioFeed()
	{
		try
		{
			# Count the returned files.
			$audio_count=$this->countAllAudio('all');
			# Check if there was returned content.
			if($audio_count>0)
			{
				$audio_id=NULL;
				$no_audio=NULL;
				$playlist=NULL;

				if(isset($_GET['audio']))
				{
					$audio_id=$_GET['audio'];
				}

				if(isset($_GET['playlist']))
				{
					# Assign playlist ID to a variable.
					$playlist='-'.$_GET['playlist'].'-';

					# Get the Audio from the database.
					$this->getAudio(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND `playlist` LIKE \'%'.$playlist.'%\'');
					# Set the returned Audio records to a variable.
					$all_audio=$this->getAllAudio();

					# No audio in the playlist. Return error image.
					if(empty($all_audio))
					{
						return $display='<div id="no_video"></div>';
					}
					else
					{
						# If audio_id is set in the URL.
						if($audio_id!==NULL)
						{
							# Loop through the audio
							foreach($all_audio as $audio_key=>$audio)
							{
								# If the $audio_id does not match the audio Id set the $no_audio to TRUE
								if($audio_id!=$audio->id)
								{
									$no_audio=TRUE;
								}
								else
								{
									$no_audio=FALSE;

									# Display the large audio
									$display=$this->getFirstAudio($all_audio, $audio_key);

									# Remove the audio from the array
									unset($all_audio[$audio_key]);
									break;
								}
							}
						}
					}
				}
				else
				{
					# This is not a playlist.
					$this->setIsPlaylist(FALSE);

					# Get the Audio.
					$this->getAudio(NULL, '*', 'id', 'DESC', ' WHERE `new` = 0');
					# Set the returned Audio records to a variable.
					$all_audio=$this->getAllAudio();

					# If audio_id is set in the URL.
					if(isset($audio_id))
					{
						# Loop through the audio
						foreach($all_audio as $audio_key=>$audio)
						{
							# If the $audio_id does not match the ID set $no_audio to TRUE
							if($audio_id!=$audio->id)
							{
								$no_audio=TRUE;
							}
							else
							{
								$no_audio=FALSE;

								# Display the large audio
								$display=$this->getFirstAudio($all_audio, $audio_key);

								# Remove the audio from the array
								unset($all_audio[$audio_key]);
								break;
							}
						}
					}
				}

				# If the audio doesn't exist ($no_audio=TRUE)
				if($no_audio)
				{
					return $display='<div id="no_video"></div>';
				}

				if(APPLICATION_URL.Utility::removeIndex(HERE)==AUDIO_URL)
				{
					# If there is no audio_id in the URL
					if(!isset($audio_id))
					{
						# Large Audio
						$display=$this->getFirstAudio($all_audio);

						# Remove the first audio from the array
						unset($all_audio[0]);
					}

					# Check if there are more audio to display.
					if(count($all_audio)>0)
					{
						# Remove the first array element
						$display.=$this->markupSmallAudio($all_audio);
					}
				}
				elseif(SECURE_AUDIO_PATH==Utility::removeIndex(HERE))
				{
					$display=$this->markupManageAudio($all_audio);
				}
			}
			else
			{
				$display='<h3>There is no audio to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayAudioFeed

	/**
	 * getAudio
	 *
	 * Retrieves records from the `audio` table.
	 *
	 * @param	$limit						The LIMIT of the records.
	 * @param	$fields						The name of the field(s) to be retrieved.
	 * @param	$order						The name of the field to order the records by.
	 * @param	$direction					The direction to order the records.
	 * @param	$and_sql					Extra AND statements in the query.
	 * @return	boolean						TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getAudio($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `audio` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'audio`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllAudio($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: ' . $ez->message . ', code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getAudio

	/**
	 * getFirstAudio
	 *
	 * Get's the first audio in an array element.
	 *
	 * @param	array $audio_search			Response array from either the database or an API.
	 * @param	int $audio_key				Optional - Array key of $audio_search
	 * @access	public
	 */
	public function getFirstAudio($audio_search, $audio_key=NULL)
	{
		# Grab the first audio.
		if(isset($audio_key))
		{
			$large_audio=array($audio_search[$audio_key]);
		}
		else
		{
			$large_audio=array_slice($audio_search, 0, 1);
		}
		# Get large audio markup.
		$display=$this->markupLargeAudio($large_audio);

		return $display;
	} #==== End -- getFirstAudio

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$audio_obj)
		{
			self::$audio_obj=new Audio();
		}
		return self::$audio_obj;
	} #==== End -- getInstance

	/**
	 * getThisAudio
	 *
	 * Retrieves audio info from the `audio` table in the Database for the passed id or audio name and sets it to the data member.
	 *
	 * @param	string $value				The name or id of the audio to retrieve.
	 * @param	boolean $id					TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean						TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisAudio($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set the field to search for $value.
				$field='id';
				# Set the audio id to the data member "cleaning" it.
				$this->setID($value);
				# Get the audio id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file_name';
				# Set the audio name to the data member "cleaning" it.
				$this->setFileName($value);
				# Get the audio name and reset it to the variable.
				$value=$this->getFileName();
			}
			# Get the audio info from the Database.
			$audio=$db->get_row('SELECT `id`, `title`, `file_name`, `api`, `author`, `year`, `playlist`, `availability`, `date`, `image`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'audio` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($audio!==NULL)
			{
				# Set the audio name to the data member.
				$this->setID($audio->id);
				# Set the audio name to the data member.
				$this->setFileName($audio->file_name);
				# Set the audio API to the data member.
				$this->setAPI($audio->api);
				# Set the audio author to the data member.
				$this->setAuthor($audio->author);
				# Set the audio availability to the data member.
				$this->setAvailability($audio->availability);
				# Pass the audio playlist id(s) to the setPlaylist method, thus setting the data member with the playlist name(s).
				$this->setPlaylists($audio->playlist);
				# Set the contributor id to the data member.
				$this->setContID($audio->contributor);
				# Set the audio post/edit date to the data member.
				$this->setDate($audio->date);
				# Set the audio's image ID to the data member.
				$this->setImageID($audio->image);
				# Pass the audio institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($audio->institution);
				# Pass the audio language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($audio->language);
				# Pass the audio publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($audio->publisher);
				# Set the audio title to the data member.
				$this->setTitle($audio->title);
				# Set the audio publish year to the data member.
				$this->setYear($audio->year);
				return TRUE;
			}
			# Return FALSE because the audio wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: '.$ez->message.'<br />Code: '.$ez->code.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisAudio

	/**
	 * getThisImage
	 *
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member. A wrapper method for getThisImage from the Image class.
	 *
	 * @param	string $value			The name or id of the image to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisImage($value, $id=TRUE)
	{
		try
		{
			# Get the Image class.
			require_once MODULES.'Media'.DS.'Image.php';
			# Instantiate a new Image object.
			$image_obj=new Image();
			# Get the info for this image and set the return boolean to a variable.
			$record_retrieved=$image_obj->getThisImage($value, $id);
			# Set the image object to the data member.
			$this->setImageObj($image_obj);
			# Check if there was an image retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the id to the data member.
				$this->setImageID($image_obj->getID());
				return TRUE;
			}
			# Set the image id data member to NULL.
			$this->setImageID(NULL);
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisImage

	/**
	 * markupManageAudio
	 *
	 * Returns the HTML markup that lists audio from the database or API.
	 *
	 * @param	array $audio_search
	 * @access	public
	 */
	public function markupManageAudio($audio_search)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Set the Soundcloud instance to a variable.
		//$soundcloud_obj=$this->getSoundcloudObject();

		$display='<table class="table-image"><th><a href="'.ADMIN_URL.'ManageMedia/audio/?by_audio_name=DESC" title="Order by audio name">View</a></th><th><a href="'.ADMIN_URL.'ManageMedia/audio/?by_title=DESC" title="Order by title">Title</a></th><th>Options</th>';

		foreach($audio_search as $audio)
		{
			# Get the audio ID and assign it to a variable.
			$this->setID($audio->id);

			# Set the title to a variable
			$this->setTitle($db->sanitize($audio->title));

			# Decode the `api` field.
			$api_decoded=json_decode($audio->api);

			# If the soundcloud_id is in the `api` field then this audio is on Soundcloud.
			if(isset($api_decoded->soundcloud_id))
			{
				# Set Soundcloud ID
				$this->setAudioId($api_decoded->soundcloud_id);

				# Create audio_url variable.
				$audio_url=$soundcloud_obj->getSoundCloudUrl().$this->getAudioId();
			}
			# If vimeo_id is in the `api` then this audio is on Vimeo.
			elseif(isset($api_decoded->vimeo_id))
			{
				$audio_url='vimeo_url';
			}
			# If it's not on Soundcloud or Vimeo, stream from the server.
			else
			{
				# Remove the file extension.
				$file_name_no_ext=substr($audio->file_name, 0, strrpos($audio->file_name, '.'));

				# Create audio_url variable.
				$audio_url=AUDIO_URL.'files'.DS.$file_name_no_ext.'.mp3';
			}

			# Create audio URL.
			$this->setAudioUrl($audio_url);

			if(isset($api_decoded->soundcloud_thumbnails->default->url))
			{
				$this->setThumbnailUrl($api_decoded->soundcloud_thumbnails->default->url);
			}
			else
			{
				# Set the image ID.
				$this->setImageID($audio->image);

				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());

				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();

				# Set the current categories to a variable.
				$image_categories=$image_obj->getCategories();

				# Set the thumbnail to a variable.
				$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));

				# Relative path to thumbnail.
				$image_path=IMAGES_PATH.$image_obj->getImage();
			}

			# Set the markup to a variable
			$display.='<tr>'.
				'<td><a href="'.$this->getAudioUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" rel="lightbox">'.(!file_exists($image_path) ? '<div class="audio_default_thumbnail_manage"></div>' : '<img src="'.$this->getThumbnailUrl().'" alt="'.$this->getTitle().' poster" />').'</a></td>'.
				'<td>'.$this->getTitle().'</td>'.
				'<td><a href="'.ADMIN_URL.'ManageMedia/audio/?audio='.$this->getID().'" class="edit" title="Edit this">Edit</a><a href="'.ADMIN_URL.'ManageMedia/audio/?audio='.$this->getID().'&amp;delete" class="delete" title="Delete This">Delete</a></td>'.
				'</tr>';
		}

		$display.='</table>';

		return $display;
	} #==== End -- markupManageAudio

	/**
	 * markupLargeAudio
	 *
	 * Returns the HTML markup to display a large audio.
	 *
	 * @param	array $large_audio			The array for the large audio.
	 * @access	public
	 */
	public function markupLargeAudio($large_audio)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Get the audio ID and assign it to a variable.
		$this->setID($large_audio[0]->id);

		# Decode the `api` field.
		$api_decoded=json_decode($large_audio[0]->api);

		# If the soundcloud_id is in the `api` field then this audio is on Soundcloud.
		if(isset($api_decoded->soundcloud_id))
		{
			# Set the Soundcloud instance to a variable.
			$soundcloud_obj=$this->getSoundcloudObject();

			# Set Soundcloud ID
			$this->setAudioId($api_decoded->soundcloud_id);

			# Create audio_url variable.
			$audio_url=$soundcloud_obj->getSoundCloudUrl().$this->getAudioId();
		}
		elseif(isset($api_decoded->vimeo_id))
		{
			# Create audio_url variable.
			$audio_url='vimeo_url';
		}
		else
		{
			# Remove the file extension.
			$file_name_no_ext=substr($large_audio[0]->file_name, 0, strrpos($large_audio[0]->file_name, '.'));

			# Create audio_url variable.
			$audio_url=AUDIO_URL.'files'.DS.$file_name_no_ext.'.mp3';
		}

		# Create audio URL.
		$this->setAudioUrl($audio_url);

		# Set the title
		$this->setTitle($db->sanitize($large_audio[0]->title));
		$alt_text=$this->getTitle().' on '.DOMAIN_NAME;

		if(isset($api_decoded->soundcloud_thumbnails->medium->url))
		{
			$this->setThumbnailUrl($api_decoded->soundcloud_thumbnails->medium->url);
		}
		else
		{
			if(!empty($large_audio[0]->image))
			{
				# Set the image ID.
				$this->setImageID($large_audio[0]->image);

				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());

				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();

				# Set the current categories to a variable.
				$image_categories=$image_obj->getCategories();

				# Set the thumbnail to a variable.
				$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));

				# Relative path to thumbnail.
				$image_path=IMAGES_PATH.$image_obj->getImage();
			}
			if(!isset($image_path) || !file_exists($image_path))
			{
				# Set the thumbnail to a variable.
				$this->setThumbnailUrl(IMAGES.'audio-default-thumbnail.jpg');
				$alt_text='The default image for audio on '.DOMAIN_NAME;
			}
		}

		# Set the description
		$this->setDescription($db->sanitize($large_audio[0]->description, 5));

		$display='<div class="audio-lg"><a href="#openAudio" ref="openAudio">'.
				'<img src="'.$this->getThumbnailUrl().'" class="poster" alt="'.$alt_text.'"/>'.
				'<span class="play-static"></span></a>'.
				'<div id="media-text">'.
					'<h3 class="h-audio"><a href="'.$this->getAudioUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" target="_blank">'.$this->getTitle().'</a></h3>'.
					'<p>'.$this->getDescription().'</p>'.
				'</div>'.
			'</div>'.
			'<div id="openAudio" class="show_player">'.
				'<div class="pp_pic_holder" style="display:block">'.
					'<div class="ppt" style="opacity:1;display:block;width:500px;height:20px"></div>'.
					'<div class="pp_content_container">'.
						'<div class="pp_content" style="min-height:248px;width:500px">'.
							'<div class="pp_fade" style="display:block">'.
								'<div id="pp_full_res">'.
									'<img src="'.$this->getThumbnailUrl().'" class="poster" alt="'.$alt_text.'"/>'.
									'<audio class="player" id="player" preload="auto" controls>'.
										'<source src="'.$this->getAudioUrl().'" type="audio/mpeg">'.
										'Your browser does not support the audio element.'.
									'</audio>'.
								'</div>'.
								'<div class="pp_details" style="width:500px">'.
									'<p class="pp_description" style="display:block">'.$this->getTitle().'</p>'.
									'<a class="pp_close" href="#" ref="closeAudio">Close</a>'.
								'</div>'.
							'</div>'.
						'</div>'.
					'</div>'.
				'</div>'.
				'<div class="overlay"></div>'.
			'</div>';

		return $display;
	} #==== End -- markupLargeAudio

	/**
	 * markupSmallAudio
	 *
	 * Returns the HTML markup for the small audio.
	 *
	 * @param	array $small_audio			The array for the small audio.
	 * @access	public
	 */
	public function markupSmallAudio($small_audio)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Small Audio
		$display='<div class="audio-feed-wrapper">'.
			'<div class="arrow-prev"></div>'.
			'<div class="audio-feed-list">'.
			'<ul class="audio-feed">';

		foreach($small_audio as $audio)
		{
			# Get the audio ID and assign it to a variable.
			$this->setID($audio->id);

			# If the audio belong to a playlist
			if(isset($_GET['playlist']))
			{
				# Create audio URL.
				$this->setAudioUrl('?playlist='.$_GET['playlist'].'&audio='.$this->getID());
			}
			else
			{
				$this->setAudioUrl('?audio='.$this->getID());
			}

			# Set the title to a variable
			$this->setTitle($db->sanitize($audio->title));

			# Decode the `api` field.
			$api_decoded=json_decode($audio->api);

			if(isset($api_decoded->soundcloud_thumbnails->default->url))
			{
				$this->setThumbnailUrl($api_decoded->soundcloud_thumbnails->default->url);
			}
			else
			{
				$image_path=NULL;
				if(!empty($audio->image))
				{
					# Set the image ID.
					$this->setImageID($audio->image);

					# Get the image information from the database, and set them to data members.
					$this->getThisImage($this->getImageID());

					# Set the Image object to a variable.
					$image_obj=$this->getImageObj();

					# Set the current categories to a variable.
					$image_categories=$image_obj->getCategories();

					# Set the the thumbnail to a variable.
					$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));

					# Relative path to thumbnail.
					$image_path=IMAGES_PATH.$image_obj->getImage();
				}
			}

			# Set the markup to a variable
			$display.='<li>'.
				'<a href="'.AUDIO_URL.$this->getAudioUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'">'.(!isset($image_path) || !file_exists($image_path) ? '<div class="audio_default_thumbnail_small"></div>' : '<img src="'.$this->getThumbnailUrl().'" alt="'.$this->getTitle().' on '.DOMAIN_NAME.'" />').'</a>'.
				'</li>';
		}

		$display.='</ul>'.
			'</div>'.
			'<div class="arrow-next"></div>'.
			'</div>';

		return $display;
	} #==== End -- markupSmallAudio

	/*** End public methods ***/



	/*** protected methods ***/

	/*** End protected methods ***/

} # end Audio class