<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Video
 *
 * The Video class is used to access and manipulate data from the YouTube API & Vimeo API.
 *
 */
class Video
{
	/*** data members ***/

	private static $video_obj;
	private $all_videos=array();
	private $api=NULL;
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
	private $google_client=NULL;
	private $id=NULL;
	private $image_id=NULL;
	private $image_obj=NULL;
	private $institution=NULL;
	private $is_playlist=FALSE;
	private $file_name=NULL;
	private $language=NULL;
	private $last_edit='0000-00-00';
	private $playlists=array();
	private $playlist_object=NULL;
	private $publisher=NULL;
	# $recent_contributor is an object.
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $thumbnail_url=NULL;
	private $title=NULL;
	private $video_id=NULL;
	private $video_type=NULL;
	private $video_url=NULL;
	private $year=NULL;
	private $youtube_obj=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllVideos
	 *
	 * Sets the data member $all_videos.
	 *
	 * @param	$videos							May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllVideos($videos)
	{
		# Check if the passed value is empty.
		if(!empty($videos))
		{
			# Explicitly make it an array.
			$videos=(array)$videos;
			# Set the data member.
			$this->all_videos=$videos;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_videos=array();
		}
	} #==== End -- setAllVideos

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
	 * Then it get's the value after the last slash (/) in the URL which will be the Video ID (on YouTube).
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
				# Get's the value after the last slash in the URL (which will be the YouTube Video ID).
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
			# Get the video ID from the $emebed_code.
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
	 * setGoogleClient
	 *
	 * Sets the data member $google_client.
	 *
	 * @param	$google_client
	 * @access	public
	 */
	public function setGoogleClient($google_client)
	{
		# Check if the passed value is empty and an object.
		if(!empty($google_client) && is_object($google_client))
		{
			$this->google_client=$google_client;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->google_client=NULL;
		}
	} #==== End -- setGoogleClient

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
				throw new Exception('The passed video id was not an integer!', E_RECOVERABLE_ERROR);
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
				$pub->getThisPublisher($publisher, TRUE);
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
	 * setVideoId
	 *
	 * Set the data member $video_id
	 * This is the ID on YouTube.
	 *
	 * @param	string $video_id
	 * @access	public
	 */
	public function setVideoId($video_id)
	{
		$this->video_id=$video_id;
	} #==== End -- setVideoId

	/**
	 * setVideoType
	 *
	 * Sets the data member $video_type.
	 *
	 * @param	$video_type
	 * @access	public
	 */
	public function setVideoType($video_type)
	{
		# Set the data member.
		$this->video_type=$video_type;
	} #==== End -- setVideoType

	/**
	 * setVideoUrl
	 *
	 * Set the data member $video_url
	 *
	 * @param	string $video_url
	 * @access	public
	 */
	public function setVideoUrl($video_url)
	{
		$this->video_url=$video_url;
	} #==== End -- setVideoUrl

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

	/**
	 * setYouTubeObject
	 *
	 * Set the data member $youtube_obj
	 *
	 * @param	string $object
	 * @access	private
	 */
	private function setYouTubeObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->youtube_obj=$object;
	} #==== End -- setYouTubeObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllVideos
	 *
	 * Returns the data member $all_videos.
	 *
	 * @access	public
	 */
	public function getAllVideos()
	{
		return $this->all_videos;
	} #==== End -- getAllVideos

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
	 * getGoogleClient
	 *
	 * Returns the data member $google_client.
	 *
	 * @access	public
	 */
	public function getGoogleClient()
	{
		return $this->google_client;
	} #==== End -- getGoogleClient

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
	 * getVideoId
	 *
	 * Gets the data member $video_id
	 * This is the ID on YouTube.
	 *
	 * @access	public
	 */
	public function getVideoId()
	{
		return $this->video_id;
	} #==== End -- getVideoId

	/**
	 * getVideoType
	 *
	 * Returns the data member $video_type.
	 *
	 * @access	public
	 */
	public function getVideoType()
	{
		return $this->video_type;
	} #==== End -- getVideoType

	/**
	 * getVideoUrl
	 *
	 * Gets the data member $video_url
	 *
	 * @access	public
	 */
	public function getVideoUrl()
	{
		return $this->video_url;
	} #==== End -- getVideoUrl

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

	/**
	 * getYouTubeObject
	 *
	 * Returns the data member $youtube_obj.
	 *
	 * @param	$domain						Optional. This is set for cron and CommandLine scripts.
	 * @access	public
	 */
	public function getYouTubeObject($domain=NULL)
	{
		# Check if there is a YouTube object.
		if(empty($this->youtube_obj) OR !is_object($this->youtube_obj))
		{
			# Get the YouTube Class.
			require_once MODULES.'Media'.DS.'YouTube.php';

			# Instantiate a new YouTube object.
			$yt=YouTube::getInstance();

			# Set the YouTube object to the data member.
			$this->setYouTubeObject($yt);

			$yt->setYouTubeClientId(YOUTUBE_CLIENT_ID);
			$yt->setYouTubeClientSecret(YOUTUBE_CLIENT_SECRET);
			if($domain!==NULL)
			{
				$yt->setYouTubeRedirectUri($domain);
			}
			else
			{
				$yt->setYouTubeRedirectUri(APPLICATION_URL.HERE);
			}
			$yt->setYouTubeDevKey(YOUTUBE_DEV_KEY);
			$yt->setYouTubeRefreshToken(YOUTUBE_REFRESH_TOKEN);

			# Start the YouTube Service.
			$yt->startYouTubeService();

			# Set the Google Client object to the data member.
			$this->setGoogleClient($yt->getGoogleClient());
		}
		return $this->youtube_obj;
	} #==== End -- getYouTubeObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllVideos
	 *
	 * Returns the number of videos in the database.
	 *
	 * @access	public
	 * @param		$categories (The names and/or id's of the category(ies) to be retrieved. May be multiple categories - separate with dash, ie. '50-60-Archives-110'. "!" may be used to exlude categories, ie. '50-!60-Archives-110')
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 */
	public function countAllVideos($categories=NULL, $limit=NULL, $and_sql=NULL)
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
					$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'videos` WHERE '.$where.(($and_sql===NULL) ? '' : ' '.$and_sql).' AND `new` = 0'.(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllVideos

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
					$url=VIDEOS_URL.'?playlist='.$playlist_id;
					$playlist_items.='<li'.$doc->addHereClass($url).'>'.
							'<a href="'.$url.'"'.$doc->addHereClass($url).' title="'.$title.' video playlist">'.
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
	 * deleteVideo
	 *
	 * Removes an video record from the `videos` table and the actual video file from the system.
	 *
	 * @param	int $id					The id of the video in the `videos` table.
	 * @param	$redirect
	 * @access	public
	 */
	public function deleteVideo($id, $redirect=NULL)
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
					# Check if the video is premium content or not.
					$this_video=$this->getThisVideo($id);
					# Check if the video was found.
					if($this_video!==TRUE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The video was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the video's categories data member to a local variable.
					$video_cats=$this->getPlaylists();
					# Set the video's name data member to a local variable.
					$video_name=$this->getFileName();
					# Get the FileHandler class.
					require_once MODULES.'FileHandler'.DS.'FileHandler.php';
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Remove the file extension.
					$video_name_no_ext=substr($video_name, 0, strrpos($video_name, '.'));
					# If $video_name is not set then there shouldn't be a copy on the server.
					if(!empty($video_name))
					{
						# Get the YouTube instance. Starts the YouTubeService if it's not already started.
						$yt=$this->getYouTubeObject();

						# Decode the `api` field.
						$api_decoded=json_decode($this->getAPI());
						$yt_id=$api_decoded->youtube_id;

						# Delete the video.
						# DRAVEN: Change when we do video streaming from the server.
						//if(($file_handler->deleteFile(VIDEOS_PATH.'files'.DS.$video_name_no_ext.'.mp3')===TRUE) && ($file_handler->deleteFile(BODEGA.'videos'.DS.$video_name)===TRUE) && ($yt->deleteVideo($yt_id)===TRUE))
						if(($file_handler->deleteFile(BODEGA.'videos'.DS.$video_name)===TRUE) && ($yt->deleteVideo($yt_id)===TRUE))
						{
							try
							{
								# Delete the video from the `videos` table.
								$deleted=$db->query('DELETE FROM `'.DBPREFIX.'videos` WHERE `id` = '.$db->quote($id).' LIMIT 1');
								# Set a nice message to display to the user.
								$_SESSION['message']='The video '.$video_name.' was successfully deleted.';
								# Redirect the user back to the page without GET or POST data.
								$doc->redirect($redirect);
								# If there is no redirect, return TRUE.
								return TRUE;
							}
							catch(ezDB_Error $ez)
							{
								throw new Exception('Error occured: ' . $ez->message . ', but the video file itself was deleted.<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
							}
							catch(Exception $e)
							{
								throw $e;
							}
						}
						else
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid video for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
					}
					else
					{
						# Set the video's name data member to a local variable.
						$video_name=$this->getTitle();
						# Delete the video.
						try
						{
							# Delete the video from the `videos` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'videos` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The video '.$video_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: ' . $ez->message . ', but the video itself was deleted.<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That video was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteVideo

	/**
	 * displayRecentVideo
	 *
	 * Generate markup for most recent video posted.
	 *
	 * @acess	public
	 */
	public function displayRecentVideo()
	{
		# Get's the Recent Video from the Video class.
		return $this->getRecentVideo();
	} #==== End -- displayRecentVideo

	/**
	 * displayVideoFeed
	 *
	 * Prints the users video feed.
	 *
	 * @access	public
	 */
	public function displayVideoFeed()
	{
		global $login;

		try
		{
			# Count the returned files.
			$video_count=$this->countAllVideos('all');
			# Check if there was returned content.
			if($video_count>0)
			{
				$video_id=NULL;
				$no_video=NULL;
				$playlist=NULL;

				if(isset($_GET['video']))
				{
					$video_id=$_GET['video'];
				}

				if(isset($_GET['playlist']))
				{
					# Assign playlist ID to a variable.
					$playlist='-'.$_GET['playlist'].'-';

					# Get the Videos from the database.
					$this->getVideos(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND `playlist` LIKE \'%'.$playlist.'%\'');
					# Set the returned Video records to a variable.
					$all_videos=$this->getAllVideos();

					# No videos in the playlist. Return error image.
					if(empty($all_videos))
					{
						return $display='<div id="no_video"></div>';
					}
					else
					{
						# If video_id is set in the URL.
						if($video_id!==NULL)
						{
							# Loop through the videos
							foreach($all_videos as $video_key=>$video)
							{
								# If the $video_id does not match the videoId set the $no_video to TRUE
								if($video_id!=$video->id)
								{
									$no_video=TRUE;
								}
								else
								{
									$no_video=FALSE;

									# Display the large video
									$display=$this->getFirstVideo($all_videos, $video_key);

									# Remove the video from the array
									unset($all_videos[$video_key]);
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

					if($login->checkAccess(MAN_USERS)===TRUE)
					{
						# Get the Videos.
						$this->getVideos(NULL, '*', 'id', 'DESC', ' WHERE `new` = 0');
					}
					else
					{
						# Get the Videos.
						$this->getVideos(NULL, '*', 'id', 'DESC', ' WHERE `availability` = 1 AND `new` = 0');
					}
					# Set the returned Video records to a variable.
					$all_videos=$this->getAllVideos();

					# If video_id is set in the URL.
					if(isset($video_id))
					{
						# Loop through the videos
						foreach($all_videos as $video_key=>$video)
						{
							# If the $video_id does not match the ID set $no_video to TRUE
							if($video_id!=$video->id)
							{
								$no_video=TRUE;
							}
							else
							{
								$no_video=FALSE;

								# Display the large video
								$display=$this->getFirstVideo($all_videos, $video_key);

								# Remove the video from the array
								unset($all_videos[$video_key]);
								break;
							}
						}
					}
				}

				# If the video doesn't exist ($no_video=TRUE)
				if($no_video)
				{
					return $display='<div id="no_video"></div>';
				}

				if(APPLICATION_URL.Utility::removeIndex(HERE)==VIDEOS_URL)
				{
					# If there is no video_id in the URL
					if(!isset($video_id))
					{
						# Large Video
						$display=$this->getFirstVideo($all_videos);

						# Remove the first video from the array
						unset($all_videos[0]);
					}

					# Check if there are more videos to display.
					if(count($all_videos)>0)
					{
						# Remove the first array element
						$display.=$this->markupSmallVideos($all_videos);
					}
				}
				elseif(SECURE_VIDEOS_PATH==Utility::removeIndex(HERE))
				{
					$display=$this->markupManageVideos($all_videos);
				}
			}
			else
			{
				$display='<h3>There are no videos to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayVideoFeed

	/**
	 * getFirstVideo
	 *
	 * Get's the first video in an array element.
	 *
	 * @param	array $video_search			Response array from either the database or an API.
	 * @param	int $video_key				Optional - Array key of $video_search
	 * @access	public
	 */
	public function getFirstVideo($video_search, $video_key=NULL)
	{
		# Grab the first video.
		if(isset($video_key))
		{
			$large_video=array($video_search[$video_key]);
		}
		else
		{
			$large_video=array_slice($video_search, 0, 1);
		}
		# Get large video markup.
		$display=$this->markupLargeVideo($large_video);

		return $display;
	} #==== End -- getFirstVideo

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$video_obj)
		{
			self::$video_obj=new Video();
		}
		return self::$video_obj;
	} #==== End -- getInstance

	/**
	 * getRecentVideo
	 *
	 * Get's the recent video from YouTube or Vimeo.
	 *
	 * @access	public
	 */
	public function getRecentVideo()
	{
		global $login;

		if($login->checkAccess(MAN_USERS)===TRUE)
		{
			# Get the Videos.
			$this->getVideos('1', '*', 'id', 'DESC', ' WHERE `new` = 0');
		}
		else
		{
			# Get the Videos.
			$this->getVideos('1', '*', 'id', 'DESC', ' WHERE `availability` = 1 AND `new` = 0');
		}
		# Set the returned Video records to a variable.
		$recent_video=$this->getAllVideos();

		$display=$this->markupLargeVideo($recent_video);

		return $display;
	} #==== End -- getRecentVideo

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
	 * getThisVideo
	 *
	 * Retrieves video info from the `videos` table in the Database for the passed id or video name and sets it to the data member.
	 *
	 * @param	string $value				The name or id of the video to retrieve.
	 * @param	boolean $id					TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean						TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisVideo($value, $id=TRUE)
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
				# Set the video id to the data member "cleaning" it.
				$this->setID($value);
				# Get the video id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file_name';
				# Set the video name to the data member "cleaning" it.
				$this->setFileName($value);
				# Get the video name and reset it to the variable.
				$value=$this->getFileName();
			}
			# Get the video info from the Database.
			$video=$db->get_row('SELECT `id`, `title`, `description`, `file_name`, `api`, `author`, `year`, `playlist`, `availability`, `date`, `image`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'videos` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($video!==NULL)
			{
				# Set the video name to the data member.
				$this->setID($video->id);
				# Set the video name to the data member.
				$this->setFileName($video->file_name);
				# Set the video API to the data member.
				$this->setAPI($video->api);
				# Set the video author to the data member.
				$this->setAuthor($video->author);
				# Set the video availability to the data member.
				$this->setAvailability($video->availability);
				# Pass the video playlist id(s) to the setPlaylist method, thus setting the data member with the playlist name(s).
				$this->setPlaylists($video->playlist);
				# Set the contributor id to the data member.
				$this->setContID($video->contributor);
				# Set the video post/edit date to the data member.
				$this->setDate($video->date);
				# Set the video description to the data member.
				$this->setDescription($video->description);
				# Set the video's image ID to the data member.
				$this->setImageID($video->image);
				# Pass the video institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($video->institution);
				# Pass the video language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($video->language);
				# Pass the video publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($video->publisher);
				# Set the video title to the data member.
				$this->setTitle($video->title);
				# Set the video publish year to the data member.
				$this->setYear($video->year);
				return TRUE;
			}
			# Return FALSE because the video wasn't in the table.
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
	} #==== End -- getThisVideo

	/**
	 * getVideos
	 *
	 * Retrieves records from the `videos` table.
	 *
	 * @param	$limit						The LIMIT of the records.
	 * @param	$fields						The name of the field(s) to be retrieved.
	 * @param	$order						The name of the field to order the records by.
	 * @param	$direction					The direction to order the records.
	 * @param	$and_sql					Extra AND statements in the query.
	 * @return	Boolean						TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getVideos($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `videos` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'videos`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllVideos($records);
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
	} #==== End -- getVideos

	/**
	 * markupManageVideos
	 *
	 * Returns the HTML markup that lists video from the database or API.
	 *
	 * @param	array $video_search
	 * @access	public
	 */
	public function markupManageVideos($video_search)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Set the YouTube instance to a variable.
		$yt=$this->getYouTubeObject();

		$display='<table class="table-image"><th><a href="'.ADMIN_URL.'ManageMedia/videos/?by_video_name=DESC" title="Order by video name">View</a></th><th><a href="'.ADMIN_URL.'ManageMedia/videos/?by_title=DESC" title="Order by title">Title</a></th><th>Options</th>';

		foreach($video_search as $videos)
		{
			# Get the video ID and assign it to a variable.
			$this->setID($videos->id);

			# Set the title to a variable
			$this->setTitle($db->sanitize($videos->title));

			# Decode the `api` field.
			$api_decoded=json_decode($videos->api);

			# If the youtube_id is in the `api` field then this video is on YouTube.
			if(isset($api_decoded->youtube_id))
			{
				# Set YouTube ID
				$this->setVideoId($api_decoded->youtube_id);

				# Create video_url variable.
				$video_url=$yt->getYoutubeUrl().$this->getVideoId();
			}
			# If vimeo_id is in the `api` then this video is on Vimeo.
			elseif(isset($api_decoded->vimeo_id))
			{
				$video_url='vimeo_url';
			}
			# If it's not on YouTube or Vimeo, stream from the server.
			else
			{
				$video_url=VIDEOS_URL.$videos->file_name;
			}

			# Create video URL.
			$this->setVideoUrl($video_url);

			if(isset($api_decoded->youtube_thumbnails->default->url))
			{
				$this->setThumbnailUrl($api_decoded->youtube_thumbnails->default->url);
			}
			else
			{
				# Set the image ID.
				$this->setImageID($videos->image);

				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());

				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();

				# Set the current categories to a variable.
				$image_categories=$image_obj->getCategories();

				# Set the thumbnail to a variable.
				$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));
			}

			# Set the markup to a variable
			$display.='<tr>'.
				'<td><a href="'.$this->getVideoUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" rel="lightbox"><img src="'.$this->getThumbnailUrl().'" alt="'.$this->getTitle().' poster" /></a></td>'.
				'<td>'.$this->getTitle().'</td>'.
				'<td><a href="'.ADMIN_URL.'ManageMedia/videos/?video='.$this->getID().'" class="edit" title="Edit this">Edit</a><a href="'.ADMIN_URL.'ManageMedia/videos/?video='.$this->getID().'&amp;delete" class="delete" title="Delete This">Delete</a></td>'.
				'</tr>';
		}

		$display.='</table>';

		return $display;
	} #==== End -- markupManageVideos

	/**
	 * markupLargeVideo
	 *
	 * Returns the HTML markup to display a large video.
	 *
	 * @param	array $large_video		The array for the large video.
	 * @access	public
	 */
	public function markupLargeVideo($large_video)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if(!empty($large_video))
		{
			# Get the video ID and assign it to a variable.
			$this->setID($large_video[0]->id);

			# Decode the `api` field.
			$api_decoded=json_decode($large_video[0]->api);

			# If the youtube_id is in the `api` field then this video is on YouTube.
			if(isset($api_decoded->youtube_id))
			{
				# Set the YouTube instance to a variable.
				$yt=$this->getYouTubeObject();

				# Set YouTube ID
				$this->setVideoId($api_decoded->youtube_id);

				# Create video_url variable.
				$video_url=$yt->getYoutubeUrl().$this->getVideoId();
			}
			elseif(isset($api_decoded->vimeo_id))
			{
				$video_url='vimeo_url';
			}
			else
			{
				$video_url='server_url';
			}

			# Set the availability.
			$this->setAvailability($large_video[0]->availability);

			# Create video URL.
			$this->setVideoUrl($video_url);

			# Set the title.
			$this->setTitle($db->sanitize($large_video[0]->title));

			if(isset($api_decoded->youtube_thumbnails->medium->url))
			{
				$this->setThumbnailUrl($api_decoded->youtube_thumbnails->medium->url);
			}
			else
			{
				# Set the image ID.
				$this->setImageID($large_video[0]->image);

				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());

				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();

				# Set the current categories to a variable.
				$image_categories=$image_obj->getCategories();

				# Set the thumbnail to a variable.
				$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));
			}

			# Set the description
			$this->setDescription($db->sanitize($large_video[0]->description, 5));

			# Set the markup to a variable
			$display='<div class="video-lg">'.
				'<a href="'.$this->getVideoUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'"'.($this->getAvailability()==1 ? ' rel="lightbox"' : ' target="_blank"').'><img src="'.$this->getThumbnailUrl().'" class="poster" alt="'.$this->getTitle().' on '.DOMAIN_NAME.'" /><span class="play-static"></span></a>'.
				'<h3 class="h-video"><a href="'.$this->getVideoUrl().'" title="'.$this->getTitle().' on YouTube" target="_blank">'.$this->getTitle().'</a></h3>'.
				'<p>'.$this->getDescription().'</p>'.
				'</div>';

			return $display;
		}
	} #==== End -- markupLargeVideo

	/**
	 * markupSmallVideos
	 *
	 * Returns the HTML markup for the small videos.
	 *
	 * @param	array $small_videos			The array for the small videos.
	 * @access	public
	 */
	public function markupSmallVideos($small_videos)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Small Videos
		$display='<div class="video-feed-wrapper">'.
			'<div class="arrow-prev"></div>'.
			'<div class="video-feed-list">'.
			'<ul class="video-feed">';

		foreach($small_videos as $videos)
		{
			# Get the video ID and assign it to a variable.
			$this->setID($videos->id);

			# If the videos belong to a playlist
			if(isset($_GET['playlist']))
			{
				# Create video URL.
				$this->setVideoUrl('?playlist='.$_GET['playlist'].'&video='.$this->getID());
			}
			else
			{
				$this->setVideoUrl('?video='.$this->getID());
			}

			# Set the title to a variable
			$this->setTitle($db->sanitize($videos->title));

			# Decode the `api` field.
			$api_decoded=json_decode($videos->api);

			if(isset($api_decoded->youtube_thumbnails->default->url))
			{
				$this->setThumbnailUrl($api_decoded->youtube_thumbnails->default->url);
			}
			else
			{
				# Set the image ID.
				$this->setImageID($videos->image);

				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());

				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();

				# Set the current categories to a variable.
				$image_categories=$image_obj->getCategories();

				# Set the the thumbnail to a variable.
				$this->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));
			}

			# Set the markup to a variable
			$display.='<li>'.
				'<a href="'.VIDEOS_URL.$this->getVideoUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'"><img src="'.$this->getThumbnailUrl().'" alt="'.$this->getTitle().' on '.DOMAIN_NAME.'" class="thumbnail_small" /></a>'.
				'</li>';
		}

		$display.='</ul>'.
			'</div>'.
			'<div class="arrow-next"></div>'.
			'</div>';

		return $display;
	} #==== End -- markupSmallVideos

	/*** End public methods ***/



	/*** protected methods ***/

	/*** End protected methods ***/

} # end Video class