<?php /* Requires PHP5+ */

/**
 * File
 *
 * The File Class is used access and maintain the `files` table in the database.
 *
 */
class File
{
	/*** data members ***/

	private $all_files=array();
	private $file=NULL;
	private $id=NULL;
	private $author=NULL;
	private $availability;
	private $categories=array();
	private $cat_object=NULL;
	private $contributor=NULL;
	private $cont_id=NULL;
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $date='0000-00-00';
	private $last_edit='0000-00-00';
	private $institution=NULL;
	private $language=NULL;
	private $location=NULL;
	private $premium;
	private $publisher=NULL;
	private $title=NULL;
	private $year=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllFiles
	 *
	 * Sets the data member $files.
	 *
	 * @param		$files (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllFiles($files)
	{
		# Check if the passed value is empty.
		if(!empty($files))
		{
			# Explicitly make it an array.
			$files=(array)$files;
			# Set the data member.
			$this->all_files=$files;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_files=array();
		}
	} #==== End -- setAllFiles

	/**
	 * setFile
	 *
	 * Sets the data member $file.
	 *
	 * @param		$file
	 * @access	public
	 */
	public function setFile($file)
	{
		# Check if the passed value is empty.
		if(!empty($file))
		{
			# Clean it up.
			$file=trim($file);
			# Set the data member.
			$this->file=$file;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file=NULL;
		}
	} #==== End -- setFile

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
				throw new Exception('The passed file id was not an integer!', E_RECOVERABLE_ERROR);
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
			require_once MODULES.'Content'.DS.'Category.php';
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
	 * setRecentContributor
	 *
	 * Sets the data member $recent_contributor.
	 *
	 * @param		$object
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
				require_once MODULES.'User'.DS.'Contributor.php';
				# Instantiate a new Contributor object.
				$cont=new Contributor();
				# Get the contributor name.
				$cont->getThisContributor($id, 'id', FALSE);
				# Set the Contributor object to the data member making it available outside the method.
				$this->setRecentContributor($cont);
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
				require_once MODULES.'Content'.DS.'Institution.php';
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
			# Strip slashes and decode any html entities.
			$location=html_entity_decode(stripslashes($location), ENT_COMPAT, 'UTF-8');
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
	 * setPremium
	 *
	 * Sets the data member $premium.
	 *
	 * @param		$premium 	(NULL=Not Premium Content, 0=Premium Content)
	 * @access	public
	 */
	public function setPremium($premium)
	{
		# Check if the passed value is NULL.
		if($premium!==NULL)
		{
			# Set the value to 0.
			$premium=0;
		}
		# Explicitly set the data member to NULL.
		$this->premium=$premium;
	} #==== End -- setPremium

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
				$pub->getThisPublisher($publisher);
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

	/*** End mutator methods ***/



	/*** accessor methods ***/

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
	} #==== End -- getContributor

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
	 * getPremium
	 *
	 * Returns the data member $premium.
	 *
	 * @access	public
	 */
	public function getPremium()
	{
		return $this->premium;
	} #==== End -- getPremium

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
	 * countAllFiles
	 *
	 * Returns the number of files in the database.
	 *
	 * @param		$categories (The names and/or id's of the category(ies) to be retrieved. May be multiple categories - separate with dash, ie. '50-60-Archives-110'. "!" may be used to exlude categories, ie. '50-!60-Archives-110')
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllFiles($categories=NULL, $limit=NULL, $and_sql=NULL)
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
				require_once MODULES.'Content'.DS.'Category.php';
				# Instantiate a new Category object.
				$category=new Category();
				# Set the Category object to a data member.
				$this->setCatObject($category);
				# Create the WHERE clause for the passed $categories string.
				$category->createWhereSQL($categories);
				# Set the newly created WHERE clause to a variable.
				$where=$category->getWhereSQL();
				try
				{
					# Count the records.
					$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'files` WHERE '.$where.' '.(($and_sql===NULL) ? '' : $and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllFiles

	/**
	 * createFileList
	 *
	 * Returns a selectable list of files.
	 *
	 * @param		$select				(TRUE if there should be check boxes to select files, FALSE if not.)
	 * @access	public
	 */
	public function createFileList($select=FALSE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Create an empty array to hold query parameters.
			$params_a=array();
			# Set the default sort order to a variable.
			$sort_dir='ASC';
			# Set the default "sort by" to a variable.
			$sort_by='file';
			# Set the default sort direction of files for the file sorting link to a variable.
			$file_dir='DESC';
			# Set the default sort direction of titles for the title sorting link to a variable.
			$title_dir='DESC';
			# Check if GET data for file has been passed and it is an integer.
			if(isset($_GET['file']) && $validator->isInt($_GET['file'])===TRUE)
			{
				# Set the query to the query parameters array.
				$params_a['file']='file='.$_GET['file'];
			}
			# Check if GET data for "add" has been passed.
			if(isset($_GET['add']))
			{
				# Set the query to the query parameters array.
				$params_a['add']='add';
			}
			# Check if this should be a selectable list and that GET data for "select" has been passed.
			if($select===TRUE && isset($_GET['select']))
			{
				# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
				$select='select';
				# Get rid of any "file" GET query; it can't be passed with "select".
				unset($params_a['file']);
				# Set the query to the query parameters array.
				$params_a['select']='select';
			}
			# Check if GET data for "by_file" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
			if(isset($_GET['by_file']) && ($_GET['by_file']==='ASC' OR $_GET['by_file']==='DESC') && !isset($_GET['by_title']))
			{
				# Set the query to the query parameters array.
				$params_a['by_file']='by_file='.$_GET['by_file'];
				# Check if the order is to be descending.
				if($_GET['by_file']==='DESC')
				{
					# Reset the default "sort by" to "DESC".
					$sort_dir='DESC';
					# Reset the sort direction of files for the file sorting link to "ASC".
					$file_dir='ASC';
				}
			}
			# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_file" has not also been passed.
			if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_file']))
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
			# Get rid of the "by_file" and "by_title" indexes of the array.
			unset($params_a['by_file']);
			unset($params_a['by_title']);
			# Implode the query parameters array to a string sepparated by ampersands for the file and title sorting links.
			$query_params=implode('&amp;', $params_a);
			# Set the default value for displaying an edit button and a delete button to FALSE.
			$edit=FALSE;
			$delete=FALSE;

			# Check if the logged in User has access to editing a branch.
			if($login->checkAccess(ALL_BRANCH_USERS)===TRUE)
			{
				# Set the default value for displaying an edit button and a delete button to TRUE.
				$edit=TRUE;
				$delete=TRUE;
			}
			# Set the returned File records to a variable.
			$all_files=$this->getAllFiles();

			# Start a table for the files and set the markup to a variable.
			$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
			# Set the table header for the file column to a variable.
			$general_header='<th class="download-file"><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_file='.$file_dir.'" title="Order by file name">Download</a></th>';
			# Add the table header for the title column to the $general_header variable.
			$general_header.='<th><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Title</a></th>';
			# Check if this is a select list.
			if($select==='select')
			{
				# Get the FormGenerator class.
				require_once MODULES.'Form'.DS.'FormGenerator.php';
				# Instantiate a new FormGenerator object.
				$fg=new FormGenerator('post', PROTOCAL.FULL_URL, 'post', '_top', FALSE, 'file-list');
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
			# Loop through the all_files array.
			foreach($all_files as $row)
			{
				# Instantiate a new File object.
				$file_row=New File();
				# Set the relevant returned field values File data members.
				$file_row->setID($row->id);
				$file_row->setFile($row->file);
				$file_row->setPremium($row->premium);
				$file_row->setTitle($row->title);
				$file_id=$file_row->getID();
				# Set the relevant File data members to local variables.
				$file_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $file_row->getFile());
				$file_title=str_ireplace('%{domain_name}', DOMAIN_NAME, $file_row->getTitle());
				# Create empty variables for the edit and delete buttons.
				$edit_content=NULL;
				$delete_content=NULL;
				# Set the file markup to the $general_data variable.
				$general_data='<td><a href="'.DOWNLOADS.'?f='.$file_name.(($file_row->getPremium()===0) ? '&amp;t=premium' : '').'" title="'.$file_title.'">'.$file_name.'</a></td>';
				# Add the title markup to the $general_data variable.
				$general_data.='<td>'.(($select==='select') ? '<label for="file'.$file_id.'">' : '').$file_title.(($select==='select') ? '</label>' : '').'</td>';
				# Check if there should be an edit button displayed.
				if($edit===TRUE)
				{
					# Set the edit button to a variable.
					$edit_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'" class="edit" title="Edit this">Edit</a>';
				}
				# Check f there should be a delete button displayed.
				if($delete===TRUE)
				{
					# Set the delete button to a variable.
					$delete_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'&amp;delete=yes" class="delete" title="Delete This">Delete</a>';
				}
				# Check if this is a select list.
				if($select==='select')
				{
					# Open a tr and td tag and add them to the form.
					$fg->addFormPart('<tr><td>');
					# Create the radio button for this file.
					$fg->addElement('radio', array('name'=>'file_info', 'value'=>$file_id.':'.$file_name, 'id'=>'file'.$file_id));
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
				$fg->addElement('submit', array('name'=>'file', 'value'=>'Select File'), '', NULL, 'submit-file');
				$fg->addElement('submit', array('name'=>'file', 'value'=>'Go Back'), '', NULL, 'submit-back');
				# Close the fieldset.
				$fg->addFormPart('</fieldset>');
				# Set the form to a local variable.
				$display='<h4>Select a file below</h4>'.$fg->display();
			}
			else
			{
				# Concatenate the table header and body and close the table setting it all to a local variable.
				$display=$table_header.$table_body.'</table>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- createFileList

	/**
	 * deleteFile
	 *
	 * Removes a file from the `files` table and the actual file from the system.
	 *
	 * @param		int			(The id of the file in the `files` table.
	 * @access	public
	 */
	public function deleteFile($id, $redirect=NULL)
	{
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
					$redirect=PROTOCAL.FULL_URL.HERE;
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
					# Check if the file is premium content or not.
					$this_file=$this->getThisFile($id);
					# Check if the file was found.
					if($this_file==TRUE)
					{
						# Create an empty variable to hold the name of a folder for the file.
						$folder='';
						# Check if was premium content.
						if($this->getPremium()!==NULL)
						{
							# Set the name of the folder to the variable.
							$folder='premium';
						}
						# Get the FileHandler class.
						require_once MODULES.'FileHandler'.DS.'FileHandler.php';
						# Instantiate a new FileHandler object.
						$file_handler=new FileHandler();
						# Delete the file.
						if($file_handler->deleteFile(BODEGA.$folder.DS.$this->getFile())===TRUE)
						{
							try
							{
								# Remove the file from all posts in the `subcontent` table.
								$db->query('UPDATE `'.DBPREFIX.'subcontent` SET `file` = NULL WHERE `file` = '.$db->quote($id));
								# Delete the file from the `files` table.
								$deleted=$db->query('DELETE FROM `'.DBPREFIX.'files` WHERE `id` = '.$db->quote($id).' LIMIT 1');
								# Check if the file was deleted.
								if($deleted!==NULL)
								{
									# Set a nice message to display to the user.
									$_SESSION['message']='The file "'.$this->getFile().'" was successfully deleted.';
									# Check if there is a redirect.
									if(!empty($redirect))
									{
										# Redirect the user.
										$doc->redirect($redirect);
									}
									return TRUE;
								}
								return FALSE;
							}
							catch(ezDB_Error $ez)
							{
								throw new Exception('Error occured: ' . $ez->message . ', but the file itself was deleted.<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
							}
							catch(Exception $e)
							{
								throw $e;
							}
						}
						else
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid file for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
					}
					else
					{
						# Set a nice message to the session.
						$_SESSION['message']='The file was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That file was not valid.';
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
	} #==== End -- deleteFile

	/**
	 * displayFileList
	 *
	 * Returns a selectable list of files.
	 *
	 * @param		$categories 	(The categories of the files to display.)
	 * @param		$select				(TRUE if there should be check boxes to select files, FALSE if not.)
	 * @access	public
	 */
/*
	public function displayFileList($categories=NULL, $select=FALSE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Set a default variable for the "AND" portion of the sql statement (1=have the legal rights to display this material 2=Internal document only).
			$and_sql=' AND `availability` = 1';
			# Check if the logged in User is a Managing User.
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND (`availability` = 1 || `availability` = 2)';
			}
			# Check if the logged in User is an Admin.
			if($login->checkAccess(ADMIN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND `availability` = 1';
			}
			# Count the returned files.
			$content_count=$this->countAllFiles($categories, NULL, $and_sql);
			# Check if there was returned content.
			if($content_count>0)
			{
				# Get the PageNavigator Class.
				require_once MODULES.'PageNavigator'.DS.'PageNavigator.php';
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Set the Category object created in the countAllFiles method to a variable.
				$category=$this->getCatObject();
				# Set the newly created WHERE clause to a variable.
				$and_sql=' WHERE '.$category->getWhereSQL().$and_sql;

				# Get the Files.
				$this->getFiles($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Create the file list and set it to a local variable in preparation to be displayed.
				$display=$this->createFileList($select);
				# Add the pagenavigator to the display variable.
				$display.=$paginator->getNavigator();
			}
			else
			{
				$display='<h3>There are no files to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayFileList
	*/

	/**
	 * displayFileList
	 *
	 * Returns a selectable list of files.
	 *
	 * @param		$branch 	(The name or id of the branch to display.)
	 * @access	public
	 */
	public function displayFileList($categories=NULL, $select=FALSE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Set a default variable for the "AND" portion of the sql statement (1=have the legal rights to display this material 2=Internal document only).
			$and_sql=' AND `availability` = 1';
			# Check if the logged in User is a Managing User.
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND (`availability` = 1 || `availability` = 2)';
			}
			# Check if the logged in User is an Admin.
			if($login->checkAccess(ADMIN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND `availability` = 1';
			}
			# Count the returned files.
			$content_count=$this->countAllFiles($categories, NULL, $and_sql);
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='file';
				# Set the default sort direction of files for the file sorting link to a variable.
				$file_dir='DESC';
				# Set the default sort direction of titles for the title sorting link to a variable.
				$title_dir='DESC';
				# Check if GET data for file has been passed and it is an integer.
				if(isset($_GET['file']) && $validator->isInt($_GET['file'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['file']='file='.$_GET['file'];
				}
				# Check if GET data for "add" has been passed.
				if(isset($_GET['add']))
				{
					# Set the query to the query parameters array.
					$params_a['add']='add';
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "file" GET query; it can't be passed with "select".
					unset($params_a['file']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_file" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_file']) && ($_GET['by_file']==='ASC' OR $_GET['by_file']==='DESC') && !isset($_GET['by_title']))
				{
					# Set the query to the query parameters array.
					$params_a['by_file']='by_file='.$_GET['by_file'];
					# Check if the order is to be descending.
					if($_GET['by_file']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of files for the file sorting link to "ASC".
						$file_dir='ASC';
					}
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_file" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_file']))
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
				# Get rid of the "by_file" and "by_title" indexes of the array.
				unset($params_a['by_file']);
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the file and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to editing a branch.
				if($login->checkAccess(ALL_BRANCH_USERS)===TRUE)
				{
					# Set the default value for displaying an edit button and a delete button to TRUE.
					$edit=TRUE;
					$delete=TRUE;
				}
				# Get the PageNavigator Class.
				require_once MODULES.'PageNavigator'.DS.'PageNavigator.php';
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Set the Category object created in the countAllFiles method to a variable.
				$category=$this->getCatObject();
				# Set the newly created WHERE clause to a variable.
				$and_sql=' WHERE '.$category->getWhereSQL().$and_sql;

				# Get the Files.
				$this->getFiles($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned File records to a variable.
				$all_files=$this->getAllFiles();

				# Start a table for the files and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the file column to a variable.
				$general_header='<th class="download-file"><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_file='.$file_dir.'" title="Order by file name">Download</a></th>';
				# Add the table header for the title column to the $general_header variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageMedia/files/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Title</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once MODULES.'Form'.DS.'FormGenerator.php';
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', PROTOCAL.FULL_URL, 'post', '_top', FALSE, 'file-list');
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
				# Loop through the all_files array.
				foreach($all_files as $row)
				{
					# Instantiate a new File object.
					$file_row=New File();
					# Set the relevant returned field values File data members.
					$file_row->setID($row->id);
					$file_row->setFile($row->file);
					$file_row->setPremium($row->premium);
					$file_row->setTitle($row->title);
					$file_id=$file_row->getID();
					# Set the relevant File data members to local variables.
					$file_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $file_row->getFile());
					$file_title=str_ireplace('%{domain_name}', DOMAIN_NAME, $file_row->getTitle());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the file markup to the $general_data variable.
					$general_data='<td><a href="'.DOWNLOADS.'?f='.$file_name.(($file_row->getPremium()===0) ? '&amp;t=premium' : '').'" title="'.$file_title.'">'.$file_name.'</a></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="file'.$file_id.'">' : '').$file_title.(($select==='select') ? '</label>' : '').'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'" class="edit" title="Edit this">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file_id.'&amp;delete=yes" class="delete" title="Delete This">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this file.
						$fg->addElement('radio', array('name'=>'file_info', 'value'=>$file_id.':'.$file_name, 'id'=>'file'.$file_id));
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
					$fg->addElement('submit', array('name'=>'file', 'value'=>'Select File'), '', NULL, 'submit-file');
					$fg->addElement('submit', array('name'=>'file', 'value'=>'Go Back'), '', NULL, 'submit-back');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a file below</h4>'.$fg->display();
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
				$display='<h3>There are no files to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayFileList

	/**
	 * getFiles
	 *
	 * Retrieves records from the `files` table.
	 *
	 * @param		$limit 			(The LIMIT of the records.)
	 * @param		$fields 		(The name of the field(s) to be retrieved.)
	 * @param		$order 			(The name of the field to order the records by.)
	 * @param		$direction 	(The direction to order the records.)
	 * @param		$and_sql 		(Extra AND statements in the query.)
	 * @return	Boolean 		(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getFiles($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `files` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'files`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllFiles($records);
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
	} #==== End -- getFiles

	/**
	 * getThisFile
	 *
	 * Retrieves file info from the `files` table in the Database for the passed id or file name and sets it to the data member.
	 *
	 * @param		String	$value 	(The name or id of the file to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisFile($value, $id=TRUE)
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
				# Set the file id to the data member "cleaning" it.
				$this->setID($value);
				# Get the file id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file';
				# Set the file name to the data member "cleaning" it.
				$this->setFile($value);
				# Get the file name and reset it to the variable.
				$value=$this->getFile();
			}
			# Get the file info from the Database.
			$file=$db->get_row('SELECT `id`, `file`, `title`, `author`, `year`, `location`, `category`, `availability`, `date`, `premium`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'files` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($file!==NULL)
			{
				# Set the file name to the data member.
				$this->setID($file->id);
				# Set the file name to the data member.
				$this->setFile($file->file);
				# Set the file author to the data member.
				$this->setAuthor($file->author);
				# Set the file availability to the data member.
				$this->setAvailability($file->availability);
				# Pass the file category id(s) to the setCategory method, thus setting the data member with the category name(s).
				$this->setCategories($file->category);
				# Set the contributor id to the data member.
				$this->setContID($file->contributor);
				# Set the file post/edit date to the data member.
				$this->setDate($file->date);
				# Pass the file institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($file->institution);
				# Pass the file language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($file->language);
				# Set the file location to the data member.
				$this->setLocation($file->location);
				# Set whether or not the file is "premium" content to the data member.
				$this->setPremium($file->premium);
				# Pass the file publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($file->publisher);
				# Set the file title to the data member.
				$this->setTitle($file->title);
				# Set the file publish year to the data member.
				$this->setYear($file->year);
				return TRUE;
			}
			# Return FALSE because the file wasn't in the table.
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
	} #==== End -- getThisFile

	/*** End public methods ***/

} # End File class.