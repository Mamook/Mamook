<?php

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
	private $all_media;
	private $author=NULL;
	private $categories=array();
	private $content=NULL;
	private $description=NULL;
	private $exploded_categories;
	private $all_files=NULL;
	private $file=NULL;
	private $file_id=NULL;
	private $file_info_display=NULL;
	private $all_images=NULL;
	private $image=NULL;
	private $image_id=NULL;
	private $link=NULL;
	private $all_publishers=NULL;
	private $publisher=NULL;
	private $publisher_id=NULL;
	private $title=NULL;
	private $audio_obj=NULL;
	private $video_obj=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setID($id)
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
				throw new Exception('The passed media id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setAllMedia
	 *
	 * Sets the data member $all_media.
	 *
	 * @param		$all_media
	 * @access	protected
	 */
	protected function setAllMedia($all_media)
	{
		$this->all_media=$all_media;
	} #==== End -- setAllMedia

	/**
	 * setAuthor
	 *
	 * Sets the data member $author.
	 *
	 * @param		$author
	 * @access	protected
	 */
	protected function setAuthor($author)
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
	 * setCategories
	 *
	 * Sets the data member $categories.
	 *
	 * @param		$value
	 * @param		$id 			   (TRUE if the passed value $value is an id, FALSE if not.)
	 * @access	protected
	 */
	protected function setCategories($value, $id=TRUE)
	{
		# Check if the passed value if empty.
		if(!empty($value))
		{
			# Check if the passed value is an array.
			if(is_array($value))
			{
				$categories=$value;
			}
			else
			{
				# Trim dashes(-) off both ends of the string.
				$value=trim($value, '-');
				# Explode the string into an array.
				$value=explode('-', $value);
				# Create an empty array to hold the categories.
				$categories=array();
				# Get the Category class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
				# Instantiate a new Category object.
				$category=new Category();
				# Loop through the array of catagory id's.
				foreach($value as $cat_value)
				# Check if the value passed is a category id.
				if($id===TRUE)
				{
					# Get the category name.
					$category->getThisCategory($cat_value);
					# Set the category name and id to the $categories array.
					$categories[$cat_value]=$category->getCategory();
				}
				else
				{
					# Get the category id.
					$category->getThisCategory($cat_value, FALSE);
					# Set the category name and id to the $categories array.
					$categories[$category->getID()]=$cat_value;
				}
			}
		}
		else
		{
			# Explicitly set the value to an empty array.
			$categories=array();
		}
		# Set the data member.
		$this->categories=$categories;
	} #==== End -- setCategories

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
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param		$description
	 * @access	protected
	 */
	protected function setDescription($description)
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
	 * setExplodedCategories
	 *
	 * Sets the data member $exploded_categories.
	 *
	 * @param		$exploded_categories
	 * @access	protected
	 */
	protected function setExplodedCategories($exploded_categories)
	{
		$this->exploded_categories=$exploded_categories;
	} #==== End -- setExplodedCategories

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
	 * setImage
	 *
	 * Sets the data member $image.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setImage($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			# Set the data member.
			$this->image=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image=NULL;
		}
	} #==== End -- setImage

	/**
	 * setImageID
	 *
	 * Sets the data member $image_id.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setImageID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is NULL.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->image_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed image id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image_id=NULL;
		}
	} #==== End -- setImageID

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
	 * or the name of a publisher as astring.
	 *
	 * @param		$publisher
	 * @access	protected
	 */
	protected function setPublisher($publisher)
	{
		# Check if the passed value is empty.
		if(!empty($publisher))
		{
			# Check if the passed value is the Publisher class instance.
			if(!is_object($publisher))
			{
				# Strip slashes and decode any html entities.
				$publisher=html_entity_decode(stripslashes($publisher), ENT_COMPAT, 'UTF-8');
				# Clean it up.
				$publisher=trim($publisher);
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

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param		$title
	 * @access	protected
	 */
	protected function setTitle($title)
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
	 * setAudioObject
	 *
	 * Set the data member $audio_obj
	 *
	 * @param	string $audio_obj
	 * @access	private
	 */
	private function setAudioObject($audio_obj)
	{
		# Check if the passed value is an object.
		if(is_object($audio_obj))
		{
			$this->audio_obj=$audio_obj;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->audio_obj=NULL;
		}
	} #==== End -- setAudioObject

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
	 * getAllMedia
	 *
	 * Returns the data member $all_media.
	 *
	 * @access	protected
	 */
	public function getAllMedia()
	{
		return $this->all_media;
	} #==== End -- getAllMedia

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
	 * getExplodedCategories
	 *
	 * Returns the data member $exploded_categories.
	 *
	 * @access	protected
	 */
	protected function getExplodedCategories()
	{
		return $this->exploded_categories;
	} #==== End -- getExplodedCategories

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
	 * Returns the data member $image.
	 *
	 * @access	public
	 */
	public function getImage()
	{
		return $this->image;
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
	 * getAudioObject
	 *
	 * Returns the data member $audio_obj.
	 *
	 * @access	public
	 */
	public function getAudioObject()
	{
		# Check if there is an Audio object.
		if($this->audio_obj===NULL)
		{
			# Get the Audio Class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
			# Instantiate a new Audio object.
			$audio_obj=Audio::getInstance();
			# Set the Audio object to the data member.
			$this->setAudioObject($audio_obj);
		}
		return $this->audio_obj;
	} #==== End -- getAudioObject

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
	 * countAllRecords
	 *
	 * Returns the number of media in the database that are marked available.
	 *
	 * @param	$category (The id of the category database table to access.)
	 * @param	$limit (The limit of records to count)
	 * @param	$and_sql (Extra AND statements in the query)
	 * @access	public
	 */
	public function countAllRecords($categories=NULL, $limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category=new Category();
			# Check if all categories are requested.
			if(strtolower($categories)!=='all')
			{
				$category->createWhereSQL($categories);
			}
			# Set the WHERE portion of the SQL statement for the categories requested to a variable.
			$where=$category->getWhereSQL();
			# Check if there should be a WHERE portion of the SQL statement.
			if(!empty($where) || !empty($and_sql))
			{
				$where='WHERE'.((empty($where)) ? '' : ' '.$where).((empty($and_sql)) ? '' : ' '.((!empty($where)) ? 'AND ' : '').$and_sql);
			}
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'media` '.$where.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('An error occured counting Media in the Database: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllRecords

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
			$image_obj=new Image();
			# Get the institutions.
			$image_obj->getImages($limit, $fields, $order, $direction, $where);
			# Set the retrieved images to a variable.
			$images=$image_obj->getAllImages();
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
			$image=new Image();
			# Get the image info.
			$image->getThisImage($value, $id);
			# Set the image object to the data member.
			$this->setImage($image);
			# Set the image id to the data member.
			$this->setImageID($image->getID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisImage

	/**
	 * getMedia
	 *
	 * Retrieves Media records from the DataBase.
	 *
	 * @param	$category					The name of the category(ies) to be retrieved. May be multiple categories - separate with a dash, ie. 'Music-Books'.
	 * @param	$limit						The LIMIT of the records.
	 * @param	$fields						The name of the field(s) to be retrieved.
	 * @param	$order						The name of the field to order the records by.
	 * @param	$direction					The direction to order the records.
	 * @param	$and_sql					Any extra AND queries.
	 * @access	public
	 */
	public function getMedia($categories=NULL, $limit=NULL, $fields='*', $order='title', $direction='DESC', $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category=new Category();
			# Check if all categories are requested.
			if(strtolower($categories)!=='all')
			{
				# Create the WHERE portion of the SQL statement for the categories requested.
				$category->createWhereSQL($categories);
			}
			# Set the WHERE portion of the SQL statement for the categories requested to a variable.
			$where=$category->getWhereSQL();
			# Check if there should be a WHERE portion of the SQL statement.
			if(!empty($where) || !empty($and_sql))
			{
				$where='WHERE'.((empty($where)) ? '' : ' '.$where).((empty($and_sql)) ? '' : ' '.((!empty($where)) ? 'AND ' : '').$and_sql);
			}
			# Get the records from the `media` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'media` '.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			# Set the returned records to the data member.
			$this->setAllMedia($records);
		}
		catch(ezDB_Error $e)
		{
			# Throw an error because there was aproblem accessing the database.
			throw new Exception('An error occured retrieving Media from the Database: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any uncaught errors.
			throw $e;
		}
	} #==== End -- getMedia

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
	 * @access	public
	 */
	public function setDataMembers($row)
	{
		try
		{
			/* Reset all the data members. */
			$this->setID(NULL);
			$this->setAuthor(NULL);
			$this->setContent(NULL);
			$this->setDescription(NULL);
			$this->setFile(NULL);
			$this->setImage(NULL);
			$this->setLink(NULL);
			$this->setPublisher(NULL);
			$this->setTitle(NULL);
			# Set media id to the data member.
			$this->setID($row->id);

			# Set the author to the data member.
			$this->setAuthor($row->author);
			# Set media description to the data member.
			$this->setContent($row->content);
			# Set media description to the data member.
			$this->setDescription($row->description);
			# Check if there is a file value.
			if($row->file!==NULL)
			{
				# Retrieve the file info from the `files` table via the file id returned in the $row data.
				$this->getThisFile($row->file);
			}
			# Check if there is an image value.
			if($row->image!==NULL)
			{
				# Retrieve the image info from the `images` table via the image id returned in the $row data.
				$this->getThisImage($row->image);
			}
			# Set media link to the data member.
			$this->setLink($row->link);
			# Check if there is an publisher value.
			if($row->publisher!==NULL)
			{
				# Retrieve the publisher info from the `publishers` table via the publisher id returned in the $row data.
				$this->getThisPublisher($row->publisher);
			}
			# Set the media title to the data member.
			$this->setTitle($row->title);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setDataMembers

	/*** End protected methods ***/

} # end Media class
