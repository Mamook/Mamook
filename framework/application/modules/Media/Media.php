<?php /* framework/application/modules/Media/Media.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Media
 *
 * The Media class is used to access and manipulate media data.
 *
 */
class Media
{
	/*** data members ***/

	private $id=NULL;
	private $author=NULL;
	private $availability;
	# $category is an object.
	private $category=NULL;
	private $categories=array();
	private $category_id=NULL;
	private $content=NULL;
	# $contributor is an object.
	private $contributor=NULL;
	private $cont_id=NULL;
	# $recent_contributor is an object.
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $date='0000-00-00';
	private $description=NULL;
	private $all_files=NULL;
	# $file is an object.
	private $file=NULL;
	private $file_id=NULL;
	private $file_info_display=NULL;
	private $all_images=NULL;
	# $image_object is an object.
	private $image_object=NULL;
	private $image_id=NULL;
	private $institution=NULL;
	private $language=NULL;
	private $last_edit='0000-00-00';
	private $link=NULL;
	private $location=NULL;
	private $playlists=NULL;
	private $playlist_obj=NULL;
	private $all_publishers=NULL;
	# $publisher is an object or a string.
	private $publisher=NULL;
	private $publisher_id=NULL;
	private $title=NULL;
	private $year=NULL;
	private $audio_instance=NULL;
	private $video_obj=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id						Integer			A numeric ID representing the media.
	 * @param		$media_type		String			The type of media that the ID represents. Default is "media".
	 * @access	public
	 */
	public function setID($id, $media_type='media')
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
				# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->id=(int)$id;
			}
			else
			{
				throw new Exception('The passed '.$media_type.' id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setAuthor
	 *
	 * Sets the data member $author.
	 *
	 * @param		$author
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
	 * @param		$availability
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

	/*
	 * setCatObject
	 *
	 * Sets the data member $category.
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
			$this->category=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->category=NULL;
		}
	} #==== End -- setCatObject

	/**
	 * setCategories
	 *
	 * Sets the data member $categories.
	 *
	 * @param	$value
	 * @access	public
	 */
	public function setCategories($value)
	{
		# Create an empty array to hold the categories.
		$categories=array();
		# Check if the passed value if empty.
		if(!empty($value))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed value is NOT an array.
			if(!is_array($value))
			{
				# Trim both ends of the string.
				$value=trim($value);
				# Trim any dashes (-) off both ends of the string .
				$value=trim($value, '-');
				# Explode the array to an array separated with dashes (-).
				$value=explode('-', $value);
			}
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category=new Category();
			# Create a variable to hold the "WHERE" clause.
			$where_clause=array();
			# Loop through the $value array to build the "WHERE" clause.
			foreach($value as $category_value)
			{
				# Set the default field name to search the categories tablee as "category".
				$field_name='name';
				# Check if the value is an integer. If so, set the field name to "id".
				if($validator->isInt($category_value))
				{
					$field_name='id';
				}
				$where_clause[]='`'.$field_name.'` = '.$db->quote($category_value);
			}
			# Create the "WHERE" clause.
			$where_clause=' WHERE ('.implode(' OR ', $where_clause).')';
			# Retreive the categories in as single call.
			$category->getCategories(NULL, '*', 'id', 'ASC', $where_clause);
			# Set the returned records to a variable.
			$all_categories=$category->getAllCategories();
			# Check if there WERE any returned records.
			if(!empty($all_categories))
			{
				# Loop through the returned categories.
				foreach($all_categories as $single_category)
				{
					# Set the category name and id to the $categories array.
					$categories[$single_category->id]=$single_category->name;
				}
			}
		}
		# Set the data member.
		$this->categories=$categories;
	} #==== End -- setCategories

	/**
	 * setCategoryID
	 *
	 * Sets the data member $category_id.
	 *
	 * @param		$id
	 * @access	public
	 */
	public function setCategoryID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
				# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->category_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed category id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->category_id=NULL;
		}
	} #==== End -- setCategoryID

	/**
	 * setContent
	 *
	 * Sets the data member $content.
	 *
	 * @param		$content
	 * @access	protected
	 */
	protected function setContent($content)
	{
		# Check if the passed value is empty.
		if(!empty($content))
		{
			# Strip slashes and decode any html entities.
			$content=html_entity_decode(stripslashes($content), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$content=trim($content);
			# Replace any domain tokens with the current domain name.
			$content=str_ireplace('%{domain_name}', DOMAIN_NAME, $content);
			# Set the data member.
			$this->content=$content;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->content=NULL;
		}
	} #==== End -- setContent

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

	/*
	 * setContID
	 *
	 * Sets the data member $cont_id.
	 *
	 * @param		$id
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
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
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

	/*
	 * setRecentContID
	 *
	 * Sets the data member $recent_cont_id.
	 *
	 * @param		$id
	 * @access	public
	 */
	public function setRecentContID($id)
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
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
				# Instantiate a new Contributor object.
				$cont=new Contributor();
				# Get the contributor name.
				$cont->getThisContributor($id, 'id', FALSE);
				# Set the Contributor object to the data member making it available outside the method.
				$this->setRecentContributor($cont);
			}
			else
			{
				throw new Exception('The passed recent contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->recent_cont_id=$id;
	} #==== End -- setRecentContID

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param		$date
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
	 * @param		$description
	 * @access	protected
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
			# Replace any domain tokens with the current domain name.
			$description=str_ireplace('%{domain_name}', DOMAIN_NAME, $description);
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
	 * setAllFiles
	 *
	 * Sets the data member $all_files.
	 *
	 * @param		$files
	 * @access	protected
	 */
	protected function setAllFiles($files)
	{
		# Set the data member.
		$this->all_files=$files;
	} #==== End -- setAllFiles

	/**
	 * setFile
	 *
	 * Sets the data member $file.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setFile($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->file=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file=NULL;
		}
	} #==== End -- setFile

	/**
	 * setFileID
	 *
	 * Sets the data member $file_id.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setFileID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->file_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed file id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file_id=NULL;
		}
	} #==== End -- setFileID

	/**
	 * setFileInfoDisplay
	 *
	 * Sets the data member $file_info_display.
	 *
	 * @param	$file_info_display
	 * @access	protected
	 */
	protected function setFileInfoDisplay($file_info_display)
	{
		# Check if the passed value is empty.
		if(!empty($file_info_display))
		{
			# Clean it up.
			$file_info_display=trim($file_info_display);
			# Set the data member.
			$this->file_info_display=$file_info_display;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file_info_display=NULL;
		}
	} #==== End -- setFileInfoDisplay

	/**
	 * setAllImages
	 *
	 * Sets the data member $images.
	 *
	 * @param		$images
	 * @access	protected
	 */
	protected function setAllImages($images)
	{
		# Set the data member.
		$this->all_images=$images;
	} #==== End -- setAllImages

	/**
	 * setImageObj
	 *
	 * Sets the data member $image_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setImageObj($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->image_object=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image_object=NULL;
		}
	} #==== End -- setImageObj

	/**
	 * setImageID
	 *
	 * Sets the data member $image_id.
	 *
	 * @param		$id
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
	 * setInstitution
	 *
	 * Sets the data member $institution.
	 *
	 * @param		$institution
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
				require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
				# Instantiate a new Cnstitution object.
				$inst=new institution();
				# Get the institution name.
				$inst->getThisInstitution($institution);
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
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param		$language
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
				require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
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
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
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
	 * setLink
	 *
	 * Sets the data member $link.
	 *
	 * @param		$link
	 * @access	protected
	 */
	protected function setLink($link)
	{
		# Check if the passed value is empty.
		if(!empty($link))
		{
			# Clean it up.
			$link=trim($link);
			# Replace any domain tokens with the current domain name.
			$link=str_ireplace('%{domain_name}', DOMAIN_NAME, $link);
			# Set the data member.
			$this->link=$link;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->link=NULL;
		}
	} #==== End -- setLink

	/*
	 * setLocation
	 *
	 * Sets the data member $location.
	 *
	 * @param		$location
	 * @access	public
	 */
	public function setLocation($location)
	{
		# Check if the passed value is empty.
		if(!empty($location))
		{
			# Strip slashes, decode any html entities, and strip tags.
			$location=strip_tags(html_entity_decode(stripslashes($location), ENT_COMPAT, 'UTF-8'));
			# Re-encde any special characters to html entities in UTF-8 encoding including quotes.
			$location=htmlentities($location, ENT_QUOTES, 'UTF-8');
			# Clean it up.
			$location=trim($location);
			# Set the data member.
			$this->location=$location;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->location=NULL;
		}
	} #==== End -- setLocation

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
		# Create an empty array to hold the playlists.
		$playlists=array();
		# Check if the passed value if empty.
		if(!empty($value))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed value is NOT an array.
			if(!is_array($value))
			{
				# Trim both ends of the string.
				$value=trim($value);
				# Trim any dashes (-) off both ends of the string .
				$value=trim($value, '-');
				# Explode the array to an array separated with dashes (-).
				$value=explode('-', $value);
			}
			# Get the Playlist class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Playlist.php');
			# Instantiate a new Playlist object.
			$playlist_obj=new Playlist();
			# Create a variable to hold the "WHERE" clause.
			$where_clause=array();
			# Loop through the $value array to build the "WHERE" clause.
			foreach($value as $playlist_value)
			{
				# Set the default field name to search the playlists tablee as "playlist".
				$field_name='name';
				# Check if the value is an integer. If so, set the field name to "id".
				if($validator->isInt($playlist_value))
				{
					$field_name='id';
				}
				$where_clause[]='`'.$field_name.'` = '.$db->quote($playlist_value);
			}
			# Create the "WHERE" clause.
			$where_clause=' WHERE ('.implode(' OR ', $where_clause).')';
			# Retreive the playlists in as single call.
			$playlist_obj->getPlaylists(NULL, '*', 'id', 'ASC', $where_clause);
			# Set the returned records to a variable.
			$all_playlists=$playlist_obj->getAllPlaylists();
			# Check if there WERE any returned records.
			if(!empty($all_playlists))
			{
				# Loop through the returned playlists.
				foreach($all_playlists as $single_playlist)
				{
					# Set the playlist name and id to the $playlists array.
					$playlists[$single_playlist->id]=$single_playlist->name;
				}
			}
		}
		# Set the data member.
		$this->playlists=$playlists;
	} #==== End -- setPlaylists

	/*
	 * setPlaylistObject
	 *
	 * Sets the data member $playlist_obj.
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
			$this->playlist_obj=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->playlist_obj=NULL;
		}
	} #==== End -- setPlaylistObject

	/**
	 * setAllPublishers
	 *
	 * Sets the data member $all_publishers.
	 *
	 * @param		$publishers
	 * @access	protected
	 */
	protected function setAllPublishers($publishers)
	{
		$this->all_publishers=$publishers;
	} #==== End -- setAllPublishers

	/**
	 * setPublisher
	 *
	 * Sets the data member $publisher. May be used to store an instance of the Publisher class
	 * or the name of a publisher as a string.
	 *
	 * @param		$publisher
	 * @access	public
	 */
	public function setPublisher($publisher)
	{
		# Check if the passed value is empty.
		if(!empty($publisher))
		{
			# Check if the passed value is the Publisher class instance.
			if(!is_object($publisher))
			{
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();
				# Strip slashes and decode any html entities.
				$publisher=html_entity_decode(stripslashes($publisher), ENT_COMPAT, 'UTF-8');
				# Clean it up.
				$publisher=trim($publisher);
				# Check if the value passed is an publisher id.
				if($validator->isInt($publisher)===TRUE)
				{
					# Get the Publisher class.
					require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
					# Instantiate a new Publisher object.
					$publisher_instance=new Publisher();
					# Get the publisher name.
					$publisher_instance->getThisPublisher($publisher);
					# Set the publisher name to the variable.
					$publisher=$publisher_instance->getPublisher();
				}
			}
			# Set the data member.
			$this->publisher=$publisher;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->publisher=NULL;
		}
	} #==== End -- setPublisher

	/**
	 * setPublisherID
	 *
	 * Sets the data member $publisher_id.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setPublisherID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->publisher_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed publisher id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->publisher_id=NULL;
		}
	} #==== End -- setPublisherID

	/**
	 * setPurchaseLink
	 *
	 * Sets the data member $purchase_link.
	 *
	 * @param		$purchase_link
	 * @access	protected
	 */
	protected function setPurchaseLink($purchase_link)
	{
		# Check if the passed value is empty.
		if(!empty($purchase_link))
		{
			# Clean it up.
			$purchase_link=trim($purchase_link);
			# Replace any domain tokens with the current domain name.
			$purchase_link=str_ireplace('%{domain_name}', DOMAIN_NAME, $purchase_link);
			# Set the data member.
			$this->purchase_link=$purchase_link;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->purchase_link=NULL;
		}
	} #==== End -- setPurchaseLink

	/*
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param		$title
	 * @access	public
	 */
	public function setTitle($title)
	{
		# Check if the passed value is empty.
		if(!empty($title))
		{
			# Strip slashes, decode any html entities, and strip tags.
			$title=strip_tags(html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8'));
			# Re-encde any special characters to html entities in UTF-8 encoding including quotes.
			$title=htmlentities($title, ENT_QUOTES, 'UTF-8');
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
	 * @param		$year
	 * @access	public
	 */
	public function setYear($year)
	{
		# Check if the passed value is empty.
		if(empty($year) OR ($year=='0000'))
		{
			# Explicitly set the value to NULL.
			$year=NULL;
		}
		else
		{
			# Clean it up.
			$year=trim($year);
		}
			# Set the data member.
			$this->year=$year;
	} #==== End -- setYear

	/**
	 * setAudioInstance
	 *
	 * Set the data member $audio_instance
	 *
	 * @param	string $audio_instance
	 * @access	private
	 */
	private function setAudioInstance($audio_instance)
	{
		# Check if the passed value is an object.
		if(is_object($audio_instance))
		{
			$this->audio_instance=$audio_instance;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->audio_instance=NULL;
		}
	} #==== End -- setAudioInstance

	/**
	 * setVideoObject
	 *
	 * Set the data member $video_obj
	 *
	 * @param	string $video_obj
	 * @access	private
	 */
	private function setVideoObject($video_obj)
	{
		# Check if the passed value is an object.
		if(is_object($video_obj))
		{
			$this->video_obj=$video_obj;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->video_obj=NULL;
		}
	} #==== End -- setVideoObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

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
	 * getAuthor
	 *
	 * Returns the data member $author.
	 *
	 * @access	protected
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

	/*
	 * getCatObject
	 *
	 * Returns the data member $category.
	 *
	 * @access	protected
	 */
	protected function getCatObject()
	{
		return $this->category;
	} #==== End -- getCatObject

	/**
	 * getCategories
	 *
	 * Returns the data member $categories.
	 *
	 * @access	public
	 */
	public function getCategories()
	{
		return $this->categories;
	} #==== End -- getCategories

	/**
	 * getCategoryID
	 *
	 * Returns the data member $category_id.
	 *
	 * @access	public
	 */
	public function getCategoryID()
	{
		return $this->category_id;
	} #==== End -- getCategoryID

	/**
	 * getContent
	 *
	 * Returns the data member $content.
	 *
	 * @access	protected
	 */
	protected function getContent()
	{
		return $this->content;
	} #==== End -- getContent

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

	/*
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

	/*
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
	 * @access	protected
	 */
	public function getDescription()
	{
		return $this->description;
	} #==== End -- getDescription

	/**
	 * getAllFiles
	 *
	 * Returns the data member $all_files.
	 *
	 * @access	public
	 */
	public function getAllFiles()
	{
		return $this->all_files;
	} #==== End -- getAllFiles

	/**
	 * getFile
	 *
	 * Returns the data member $file.
	 *
	 * @access	public
	 */
	public function getFile()
	{
		return $this->file;
	} #==== End -- getFile

	/**
	 * getFileID
	 *
	 * Returns the data member $file_id.
	 *
	 * @access	public
	 */
	public function getFileID()
	{
		return $this->file_id;
	} #==== End -- getFileID

	/**
	 * getFileInfoDisplay
	 *
	 * Returns the data member $file_info_display.
	 *
	 * @access	public
	 */
	public function getFileInfoDisplay()
	{
		return $this->file_info_display;
	} #==== End -- getFileInfoDisplay

	/**
	 * getAllImages
	 *
	 * Returns the data member $all_images.
	 *
	 * @access	public
	 */
	public function getAllImages()
	{
		return $this->all_images;
	} #==== End -- getAllImages

	/**
	 * getImage
	 *
	 * Returns the data member $image_object.
	 *
	 * @access	public
	 */
	public function getImageObj()
	{
		return $this->image_object;
	} #==== End -- getImage

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

	/*
	 * getLastEdit
	 *
	 * Returns the data member $last_edit.
	 *
	 * @access	public
	 */
	public function getLastEdit()
	{
		return $this->last_edit;
	} #==== End -- getLastEdit

	/**
	 * getLink
	 *
	 * Returns the data member $link.
	 *
	 * @access	protected
	 */
	public function getLink()
	{
		return $this->link;
	} #==== End -- getLink

	/*
	 * getLocation
	 *
	 * Returns the data member $location.
	 *
	 * @access	public
	 */
	public function getLocation()
	{
		return $this->location;
	} #==== End -- getLocation

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

	/*
	 * getPlaylistObject
	 *
	 * Returns the data member $playlist_obj.
	 *
	 * @access	protected
	 */
	protected function getPlaylistObject()
	{
		return $this->playlist_obj;
	} #==== End -- getPlaylistObject

	/**
	 * getAllPublishers
	 *
	 * Returns the data member $all_publishers.
	 *
	 * @access	public
	 */
	public function getAllPublishers()
	{
		return $this->all_publishers;
	} #==== End -- getAllPublishers

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
	 * getPublisherID
	 *
	 * Returns the data member $publisher_id.
	 *
	 * @access	public
	 */
	public function getPublisherID()
	{
		return $this->publisher_id;
	} #==== End -- getPublisherID

	/**
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access	protected
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

	/**
	 * getAudioInstance
	 *
	 * Returns the data member $audio_instance.
	 *
	 * @access	public
	 */
	public function getAudioInstance()
	{
		# Check if there is an Audio object.
		if($this->audio_instance===NULL)
		{
			# Get the Audio Class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
			# Instantiate a new Audio object.
			$audio_instance=Audio::getInstance();
			# Set the Audio object to the data member.
			$this->setAudioObject($audio_instance);
		}
		return $this->audio_instance;
	} #==== End -- getAudioInstance

	/**
	 * getVideoObject
	 *
	 * Returns the data member $video_obj.
	 *
	 * @access	public
	 */
	public function getVideoObject()
	{
		# Check if there is a Video object.
		if($this->video_obj===NULL)
		{
			# Get the Video Class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
			# Instantiate a new Video object.
			$video_obj=Video::getInstance();
			# Set the Video object to the data member.
			$this->setVideoObject($video_obj);
		}
		return $this->video_obj;
	} #==== End -- getVideoObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getFiles
	 *
	 * Retrieves records from the `files` table. A wrapper method for getFiles from the File class.
	 *
	 * @param	$limit			(The LIMIT of the records.)
	 * @param	$fields			(The name of the field(s) to be retrieved.)
	 * @param	$order			(The name of the field to order the records by.)
	 * @param	$direction		(The direction to order the records.)
	 * @param	$and_sql		(Extra AND statements in the query.)
	 * @return	Boolean			(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getFiles($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
			# Instantiate a new File object.
			$file=new File();
			# Get the files.
			$file->getFiles($limit, $fields, $order, $direction, $where);
			# Set the retrieved files to a variable.
			$files=$file->getAllFiles();
			# Check if there were records retrieved.
			if($files!==NULL)
			{
				# Set the categories to the data member.
				$this->setAllFiles($files);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getFiles

	/**
	 * getThisFile
	 *
	 * Retrieves file info from the `files` table in the Database for the passed id or file name and sets it to the data member. A wrapper method for getThisFile from the File class.
	 *
	 * @param	string $value		(The name or id of the file to retrieve.)
	 * @param	boolean $id		(TRUE if the passed $value is an id, FALSE if not.)
	 * @access	public
	 */
	public function getThisFile($value, $id=TRUE)
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
			# Instantiate a new File object.
			$file=new File();
			# Get the file info.
			$file->getThisFile($value, $id);
			# Set the File object to the data member.
			$this->setFile($file);
			# Set the file id to the data member.
			$this->setFileID($file->getID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisFile

	/**
	 * getImages
	 *
	 * Retrieves records from the `images` table. A wrapper method for getImages from the Image class.
	 *
	 * @param	$limit			(The LIMIT of the records.)
	 * @param	$fields			(The name of the field(s) to be retrieved.)
	 * @param	$order			(The name of the field to order the records by.)
	 * @param	$direction		(The direction to order the records.)
	 * @param	$and_sql		(Extra AND statements in the query.)
	 * @return	boolean			(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getImages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
			# Instantiate a new Image object.
			$image=new Image();
			# Get the images.
			$image->getImages($limit, $fields, $order, $direction, $where);
			# Set the retrieved images to a variable.
			$images=$image->getAllImages();
			# Check if there were records retrieved.
			if($images!==NULL)
			{
				# Set the institutions to the data member.
				$this->setAllImages($images);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getImages

	/**
	 * getThisImage
	 *
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member. A wrapper method for getThisImage from the Image class.
	 *
	 * @param		String	$value 	(The name or id of the image to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @access	public
	 */
	public function getThisImage($value, $id=TRUE)
	{
		try
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
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
	 * getPublishers
	 *
	 * Retrieves records from the `publishers` table. A wrapper method for getPublishers from the Publisher class.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getPublishers($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher=new Publisher();
			# Get the publishers.
			$publisher->getPublishers($limit, $fields, $order, $direction, $where);
			# Set the retrieved publishers to a variable.
			$publishers=$publisher->getAllPublishers();
			# Check if there were records retrieved.
			if($publishers!==NULL)
			{
				# Set the categories to the data member.
				$this->setAllPublishers($publishers);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPublishers

	/**
	 * getThisPublisher
	 *
	 * Retrieves publisher info from the `publishers` table in the Database for the passed id or publisher name and sets it to the data member. A wrapper method for getThisPublisher from the Publisher class.
	 *
	 * @param		String	$value 	(The name or id of the publisher to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @access	public
	 */
	public function getThisPublisher($value, $id=TRUE)
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher=new Publisher();
			# Get the publisher info.
			$publisher->getThisPublisher($value, $id);
			# Set the publisher object to the data member.
			$this->setPublisher($publisher);
			# Set the publisher id to the data member.
			$this->setPublisherID($publisher->getID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisPublisher

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * setDataMembers
	 *
	 * Sets all the data returned in a row from the various media tables to the appropriate data members.
	 *
	 * @param		$row 		(The returned row of data from a record to set to the data members.)
	 * @access	protected
	 */
// 	protected function setDataMembers($row)
// 	{
// 		try
// 		{
// 			/* Reset all the data members. */
// 			$this->setID(NULL);
// 			$this->setAuthor(NULL);
// 			$this->setContent(NULL);
// 			$this->setDescription(NULL);
// 			$this->setFile(NULL);
// 			$this->setImage(NULL);
// 			$this->setLink(NULL);
// 			$this->setPublisher(NULL);
// 			$this->setTitle(NULL);
// 			# Set media id to the data member.
// 			$this->setID($row->id);
//
// 			# Set the author to the data member.
// 			$this->setAuthor($row->author);
// 			# Set media description to the data member.
// 			$this->setContent($row->content);
// 			# Set media description to the data member.
// 			$this->setDescription($row->description);
// 			# Check if there is a file value.
// 			if($row->file!==NULL)
// 			{
// 				# Retrieve the file info from the `files` table via the file id returned in the $row data.
// 				$this->getThisFile($row->file);
// 			}
// 			# Check if there is an image value.
// 			if($row->image!==NULL)
// 			{
// 				# Retrieve the image info from the `images` table via the image id returned in the $row data.
// 				$this->getThisImage($row->image);
// 			}
// 			# Set media link to the data member.
// 			$this->setLink($row->link);
// 			# Check if there is an publisher value.
// 			if($row->publisher!==NULL)
// 			{
// 				# Retrieve the publisher info from the `publishers` table via the publisher id returned in the $row data.
// 				$this->getThisPublisher($row->publisher);
// 			}
// 			# Set the media title to the data member.
// 			$this->setTitle($row->title);
// 		}
// 		catch(Exception $e)
// 		{
// 			throw $e;
// 		}
// 	} #==== End -- setDataMembers

	/*** End protected methods ***/

} # end Media class