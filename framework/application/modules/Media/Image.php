<?php /* Requires PHP5+ */

/**
 * Image
 *
 * The Image Class is used access and maintain the `images` table in the database.
 *
 */
class Image
{
	/*** data members ***/

	private $all_images=array();
	private $image=NULL;
	private $id=NULL;
	private $categories=array();
	private $cat_object=NULL;
	private $contributor=NULL;
	private $cont_id=NULL;
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $description=NULL;
	private $height=NULL;
	private $hide;
	private $last_edit='0000-00-00';
	private $location=NULL;
	private $title=NULL;
	private $width=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllImages
	 *
	 * Sets the data member $images.
	 *
	 * @param		$images (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllImages($images)
	{
		# Check if the passed value is empty.
		if(!empty($images))
		{
			# Explicitly make it an array.
			$images=(array)$images;
			# Set the data member.
			$this->all_images=$images;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_images=array();
		}
	} #==== End -- setAllImages

	/**
	 * setImage
	 *
	 * Sets the data member $image.
	 *
	 * @param		$image
	 * @access	public
	 */
	public function setImage($image)
	{
		# Check if the passed value is empty.
		if(!empty($image))
		{
			# Clean it up.
			$image=trim($image);
			# Set the data member.
			$this->image=$image;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image=NULL;
		}
	} #==== End -- setImage

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id
	 * @access	public
	 */
	public function setID($id)
	{
		# Check if the passed $id is NULL.
		if(!empty($id) && $id!=='add' && $id!=='select')
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->id=(int)$id;
			}
			else
			{
				throw new Exception('The passed image id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setCategories
	 *
	 * Sets the data member $categories.
	 *
	 * @param		$value
	 * @access	public
	 */
	public function setCategories($value)
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
			# Create an empty array to hold the categories.
			$categories=array();
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category=new Category();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Loop through the array of catagory id's.
			foreach($value as $cat_value)
			{
				# Check if the value passed is a category id.
				if($validator->isInt($cat_value)===TRUE)
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
			# Set the data member.
			$this->categories=$categories;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->categories=array();
		}
	} #==== End -- setCategories

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
	 * setContributor
	 *
	 * Sets the data member $contributor.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setContributor($object)
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
	 * setRecentContributor
	 *
	 * Sets the data member $recent_contributor.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setRecentContributor($object)
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
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param		$description
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
	 * setHeight
	 *
	 * Sets the data member $height.
	 *
	 * @param	$height
	 * @access	public
	 */
	public function setHeight($height)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($height))
		{
			# Clean it up.
			$height=trim($height);
			# Check if the passed $height is an integer.
			if($validator->isInt($height)===TRUE)
			{
				# Explicitly make it an integer.
				$height=(int)$height;
			}
			else
			{
				throw new Exception('The passed height was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$height=NULL;
		}
		# Set the data member.
		$this->height=$height;
	} #==== End -- setHeight

	/**
	 * setHide
	 *
	 * Sets the data member $hide.
	 *
	 * @param		$hide
	 * @access	public
	 */
	public function setHide($hide)
	{
		# Check if it is NULL.
		if($hide!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->hide=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->hide=NULL;
		}
	} #==== End -- setHide

	/**
	 * setLastEdit
	 *
	 * Sets the data member $last_edit.
	 *
	 * @param		$date
	 * @access	public
	 */
	public function setLastEdit($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
		{
			# Clean it up,
			$date=trim($date);
			# Set the data member.
			$this->last_edit=$date;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->last_edit='0000-00-00';
		}
	} #==== End -- setLastEdit

	/**
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
	 * setWidth
	 *
	 * Sets the data member $width.
	 *
	 * @param	$width
	 * @access	public
	 */
	public function setWidth($width)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($width))
		{
			# Clean it up.
			$width=trim($width);
			# Check if the passed $width is an integer.
			if($validator->isInt($width)===TRUE)
			{
				# Explicitly make it an integer.
				$width=(int)$width;
			}
			else
			{
				throw new Exception('The passed width was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$width=NULL;
		}
		# Set the data member.
		$this->width=$width;
	} #==== End -- setWidth

	/*** End mutator methods ***/



	/*** accessor methods ***/

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
	 * getContributor
	 *
	 * Returns the data member $contributor.
	 *
	 * @access	public
	 */
	public function getContributor()
	{
		return $this->contributor;
	} #==== End -- getContID

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
	 * getHeight
	 *
	 * Returns the data member $height.
	 *
	 * @access	public
	 */
	public function getHeight()
	{
		return $this->height;
	} #==== End -- getHeight

	/**
	 * getHide
	 *
	 * Returns the data member $hide.
	 *
	 * @access	public
	 */
	public function getHide()
	{
		return $this->hide;
	} #==== End -- getHide

	/**
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
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access	public
	 */
	public function getTitle()
	{
		return $this->title;
	} #==== End -- getTitle

	/**
	 * getWidth
	 *
	 * Returns the data member $width.
	 *
	 * @access	public
	 */
	public function getWidth()
	{
		return $this->width;
	} #==== End -- getWidth

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllImages
	 *
	 * Returns the number of images in the database.
	 *
	 * @param	$categories				The names and/or id's of the category(ies) to be retrieved.
	 *										May be multiple categories - separate with dash, ie. '50-60-Archives-110'. "!" may be used to exlude categories, ie. '50-!60-Archives-110'
	 * @param	$limit 					The limit of records to count.)
	 * @param	$and_sql				Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllImages($categories=NULL, $limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

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
				require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
				# Instantiate a new Category object.
				$category=new Category();
				# Set the Category object to a data member.
				$this->setCatObject($category);
				# Reset the Category object variable with the instance from the data member.
				$category=$this->getCatObject();
				# Create the WHERE clause for the passed $categories string.
				$category->createWhereSQL($categories);
				# Set the newly created WHERE clause to a variable.
				$where=$category->getWhereSQL();
				try
				{
					# Count the records.
					$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'images` WHERE '.$where.' '.(($and_sql===NULL) ? '' : $and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllImages

	/**
	 * deleteImage
	 *
	 * Removes an image from the `images` table and the actual image from the system.
	 *
	 * @param		int			(The id of the image in the `images` table.
	 * @access	public
	 */
	public function deleteImage($id, $redirect=NULL)
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
					# Check if the image is premium content or not.
					$this_image=$this->getThisImage($id);
					# Check if the image was found.
					if($this_image!==TRUE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The image was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the image's categories data member to a local variable.
					$image_cats=$this->getCategories();
					# Set the image's name data member to a local variable.
					$image_name=$this->getImage();
					# Get the FileHandler class.
					require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Delete the image.
					if(($file_handler->deleteFile(IMAGES_PATH.$image_name)===TRUE) && ($file_handler->deleteFile(IMAGES_PATH.'original'.DS.$image_name)===TRUE))
					{
						try
						{
							# Delete the image from the `images` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'images` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The image '.$image_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: ' . $ez->message . ', but the image itself was deleted.<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					else
					{
						# Set a message to display to the user.
						$_SESSION['message']='That was not a valid image for deletion.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That image was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteImage

	/**
	 * displayImage
	 *
	 * Displays the image associated with the SubContent record.
	 *
	 * @param	$return					TRUE to return the string, FALSE to echo it.
	 * @param	$image_name				The name of the image to display.
	 * @param	$image_title			The title of the image to display.
	 * @param	$image_link
	 * @return	String
	 * @access	public
	 */
	public function displayImage($return=FALSE, $image_name=NULL, $image_title=NULL, $image_link='lightbox')
	{
		try
		{
			# Check if an image name was passed.
			if(!empty($image_name))
			{
				# Set the image name to the data member.
				$this->setImage($image_name);
			}
			# Check if an image title was passed.
			if(!empty($image_title))
			{
				# Set the image title to the data member.
				$this->setTitle($image_title);
			}
			# Try to get the image name from the data member and reset the variable.
			$image_name=$this->getImage();
			# Create an empty variable for the XHTML.
			$display_image='';
			if(!empty($image_name))
			{
				# Check if there should be a link for the image.
				if(!empty($image_link))
				{
					# Check if the image link is lightbox.
					if($image_link=='lightbox')
					{
						$image_link='<a href="'.IMAGES.'original/'.$image_name.'" rel="lightbox" title="'.$this->getTitle().'" class="image-link" target="_blank">%s</a>';
					}
					else
					{
						$image_link=$image_link.'%s</a>';
					}
				}
				else
				{
					# Explicitly set the image link to an empty variable.
					$image_link='%s';
				}
				# Set the image markup to the display variable.
				$display_image.=sprintf($image_link, '<img src="'.IMAGES.$image_name.'" class="image" alt="'.$this->getTitle().'" />');
			}
			if($return===FALSE)
			{
				echo $display_image;
			}
			else { return $display_image; }
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayImage

	/**
	 * displayImageList
	 *
	 * Returns a selectable list of images.
	 *
	 * @param	$categories
	 * @param	$select
	 * @access	public
	 */
	public function displayImageList($categories=NULL, $select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Count the returned images.
			$content_count=$this->countAllImages($categories);
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='image';
				# Set the default sort direction of images for the image sorting link to a variable.
				$image_dir='DESC';
				# Set the default sort direction of titles for the title sorting link to a variable.
				$title_dir='DESC';
				# Check if GET data for image has been passed and it is an integer.
				if(isset($_GET['image']) && $validator->isInt($_GET['image'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['image']='image='.$_GET['image'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "image" GET query; it can't be passed with "select".
					unset($params_a['image']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_image" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_image']) && ($_GET['by_image']==='ASC' OR $_GET['by_image']==='DESC') && !isset($_GET['by_title']))
				{
					# Set the query to the query parameters array.
					$params_a['by_image']='by_image='.$_GET['by_image'];
					# Check if the order is to be descending.
					if($_GET['by_image']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of images for the image sorting link to "ASC".
						$image_dir='ASC';
					}
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_image" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_image']))
				{
					# Set the query to the query parameters array.
					$params_a['by_title']='by_title='.$_GET['by_title'];
					# Reset the default "sort by" to "title".
					$sort_by='title';
					# Check if the order is to be descending.
					if($_GET['by_title']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of titles for the title sorting link to "ASC".
						$title_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_image" and "by_title" indexes of the array.
				unset($params_a['by_image']);
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the image and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to editing a branch.
				if($login->checkAccess(ALL_BRANCH_USERS)===TRUE && $select!=='select')
				{
					# Set the default value for displaying an edit button and a delete button to TRUE.
					$edit=TRUE;
					$delete=TRUE;
				}
				# Get the PageNavigator Class.
				require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Set the Category object created in the countAllImages method to a variable.
				$category=$this->getCatObject();
				# Set the newly created WHERE clause to a variable.
				$and_sql=' WHERE '.$category->getWhereSQL();

				# Get the Images.
				$this->getImages($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned Image records to a variable.
				$all_images=$this->getAllImages();

				# Start a table for the images and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-image">';
				# Set the table header for the image column to a variable.
				$general_header='<th><a href="'.ADMIN_URL.'ManageMedia/images/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_image='.$image_dir.'" title="Order by image name">View</a></th>';
				# Add the table header for the title column to the $general_header variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageMedia/images/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Title</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', PROTOCAL.FULL_URL, 'post', '_top', FALSE, 'image-list');
					# Create the hidden submit check input.
					$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
					# Open the fieldset tag.
					$fg->addFormPart('<fieldset>');
					# Add a table header for the Select column and concatenate the table header.
					$table_header.='<th>Select</th>'.$general_header;
					# Add the table header to the form.
					$fg->addFormPart($table_header);
				}
				else
				{
					# Concatenate the table header.
					$table_header.=$general_header;
					# Check if edit and delete buttons should be displayed.
					if($delete===TRUE OR $edit===TRUE)
					{
						# Concatenate the options header to the table header.
						$table_header.='<th>Options</th>';
					}
				}
				# Creat an empty variable for the table body.
				$table_body='';
				# Loop through the all_images array.
				foreach($all_images as $row)
				{
					# Instantiate a new Image object.
					$image=New Image();
					# Set the relevant returned field values File data members.
					$image->setCategories($row->category);
					$image->setDescription($row->description);
					$image->setID($row->id);
					$image->setImage($row->image);
					$image->setTitle($row->title);
					# Set the relevant Image data members to local variables.
					$image_cats=$image->getCategories();
					$image_desc=str_ireplace('%{domain_name}', DOMAIN_NAME, $image->getDescription());
					$image_id=$image->getID();
					$image_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $image->getImage());
					$image_title=str_ireplace('%{domain_name}', DOMAIN_NAME, $image->getTitle());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the image markup to the $general_data variable.
					$general_data='<td><a href="'.IMAGES.'original/'.$image_name.'" title="'.$image_title.'" rel="lightbox"><img src="'.IMAGES.$image_name.'" alt="'.$image_name.'" /></a></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="image'.$image_id.'">' : '' ).'"'.$image_title.'"'.((!empty($image_desc)) ? ' <span class="entry">'.$image_desc.'</span>' : '').(($select==='select') ? '</label>' : '' ).'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image_id.'" class="edit" title="Edit this">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image_id.'&amp;delete" class="delete" title="Delete This">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this image.
						$fg->addElement('radio', array('name'=>'image_info', 'value'=>$image_id.':'.$image_name, 'id'=>'image'.$image_id));
						# Reset the $table_body variable with the general data closing the radio button's td tag and closing the tr.
						$table_body='</td>'.$general_data.'</tr>';
						# Add the table body to the form.
						$fg->addFormPart($table_body);
					}
					else
					{
						# Concatenate the general data to the $table_body variable first opening a new tr.
						$table_body.='<tr>'.$general_data;
						# Check if there should be edit or Delete buttons displayed.
						if($delete===TRUE OR $edit===TRUE)
						{
							# Concatenate the button(s) to the $table_body variable wrapped in td tags.
							$table_body.='<td>'.$edit_content.$delete_content.'</td>';
						}
						# Close the current tr.
						$table_body.='</tr>';
					}
				}
				# Check if this is a select list.
				if($select==='select')
				{
					# Close the table.
					$fg->addFormPart('</table>');
					# Add the submit button.
					$fg->addElement('submit', array('name'=>'image', 'value'=>'Select Image'), '', NULL, 'submit-image');
					$fg->addElement('submit', array('name'=>'image', 'value'=>'Go Back'), '', NULL, 'submit-back');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select an image below</h4>'.$fg->display();
				}
				else
				{
					# Concatenate the table header and body and close the table setting it all to a local variable.
					$display=$table_header.$table_body.'</table>';
				}
				# Add the pagenavigator to the display variable.
				$display.=$paginator->getNavigator();
			}
			else
			{
				$display='<h3>There are no images to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayImageList

	/**
	 * getImages
	 *
	 * Retrieves records from the `images` table.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getImages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `images` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'images`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllImages($records);
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
	} #==== End -- getImages

	/**
	 * getThisImage
	 *
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the image to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean					TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisImage($value, $id=TRUE)
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
				# Set the image id to the data member "cleaning" it.
				$this->setID($value);
				# Get the image id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='image';
				# Set the image name to the data member "cleaning" it.
				$this->setImage($value);
				# Get the image name and reset it to the variable.
				$value=$this->getImage();
			}
			# Get the image info from the Database.
			$image=$db->get_row('SELECT `id`, `image`, `title`, `description`, `location`, `category`, `contributor`, `recent_contributor`, `last_edit`, `hide` FROM `'.DBPREFIX.'images` WHERE `'.$field.'` = '.$db->quote($value).' LIMIT 1');
			# Check if a row was returned.
			if($image!==NULL)
			{
				# Set the image id to the data member.
				$this->setID($image->id);
				# Set the image name to the data member.
				$this->setImage($image->image);
				# Pass the file category id(s) to the setCategory method, thus setting the data member with the category name(s).
				$this->setCategories($image->category);
				# Set the contributor id to the data member.
				$this->setContID($image->contributor);

				# Set the image description to a variable.
				$description=$image->description;
				# Replace any domain tokens with the current domain name.
				$description=str_ireplace('%{domain_name}', DOMAIN_NAME, $description);
				# Strip slashes and decode any html entities.
				$description=((empty($description)) ? '' : html_entity_decode(stripslashes($description), ENT_COMPAT, 'UTF-8'));
				# Convert new lines to <br />.
				$description=nl2br($description);
				# Set the image description to the data member.
				$this->setDescription($description);

				# Set the whether the image should be hidden or not to the data member.
				$this->setHide($image->hide);
				# Set the image location to the data member.
				$this->setLocation($image->location);
				# Set the image title to the variable.
				$title=$image->title;
				# Decode any html entities.
				$title=html_entity_decode($title);
				# Re-encode any html entities including quotes as UTF-8.
				$title=htmlentities($title, ENT_QUOTES, 'UTF-8', FALSE);
				# Set the image title to the data member.
				$this->setTitle($title);
				return TRUE;
			}
			# Return FALSE because the image wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: ' . $ez->message . ', code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisImage

	/*** End public methods ***/

} # End Image class.