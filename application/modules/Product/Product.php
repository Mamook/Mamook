<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Product
 *
 * The Product class is used to access and manipulate data in the `product` table.
 */
class Product
{
	/*** data members ***/

	protected $all_products;
	protected $asin=NULL;
	protected $collected_asins=array();
	protected $author=NULL;
	protected $button_id=NULL;
	protected $categories=NULL;
	protected $content=NULL;
	protected $currency=NULL;
	protected $description=NULL;
	protected $exploded_categories;
	protected $all_files=NULL;
	protected $file=NULL;
	protected $file_id=NULL;
	protected $file_info_display=NULL;
	protected $id=NULL;
	protected $all_images=NULL;
	protected $image=NULL;
	protected $image_id=NULL;
	protected $link=NULL;
	protected $more='more&nbsp;>>';
	protected $price=NULL;
	protected $product_type=NULL;
	protected $all_publishers=NULL;
	protected $publisher=NULL;
	protected $publisher_id=NULL;
	protected $purchase_link=NULL;
	protected $sort_by=NULL;
	protected $title=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllProducts
	 *
	 * Sets the data member $all_products.
	 *
	 * @param	$all_products
	 * @access	protected
	 */
	protected function setAllProducts($all_products)
	{
		$this->all_products=$all_products;
	} #==== End -- setAllProducts

	/***
	 * setASIN
	 *
	 * Sets the data member $asin
	 *
	 * @param	$asin					The Amazon Standard Identification Number
	 * @access	public
	 */
	public function setASIN($asin)
	{
		# Check if the passed value is empty.
		if(!empty($asin))
		{
			# Clean it up.
			$asin=trim($asin);
			# Set the data member.
			$this->asin=$asin;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->asin=NULL;
		}
	} #==== End -- setASIN

	/***
	 * setCollectedASINs
	 *
	 * Sets the data member $collected_asins
	 *
	 * @param	$asin					The Amazon Standard Identification Number. If the string 'reset' is passed, the data member will be set to an empty array.
	 * @access	protected
	 */
	protected function setCollectedASINs($asin)
	{
		# Check if the passed value is empty.
		if(!empty($asin))
		{
			# Clean it up.
			$asin=trim($asin);
			# Check if the passed value is "reset".
			if($asin=='reset')
			{
				# Explicitly set the data member to an empty array.
				$this->collected_asins=array();
			}
			else
			{
				# Set the data member.
				$this->collected_asins[]=$asin;
			}
		}
	} #==== End -- setCollectedASINs

	/**
	 * setAuthor
	 *
	 * Sets the data member $author.
	 *
	 * @param	$author
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

	/***
	 * setButtonID
	 *
	 * Sets the data member $button_id
	 *
	 * @param	$button_id					The Amazon Standard Identification Number
	 * @access	public
	 */
	public function setButtonID($button_id)
	{
		# Check if the passed value is empty.
		if(!empty($button_id))
		{
			# Clean it up.
			$button_id=trim($button_id);
			# Set the data member.
			$this->button_id=$button_id;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->button_id=NULL;
		}
	} #==== End -- setButtonID

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
	 * setContent
	 *
	 * Sets the data member $content.
	 *
	 * @param	$content
	 * @access	public
	 */
	public function setContent($content)
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
	 * setCurrency
	 *
	 * Sets the data member $currency.
	 *
	 * @param	$currency
	 * @access	public
	 */
	public function setCurrency($currency)
	{
		# Check if the passed value is empty.
		if(!empty($currency))
		{
			# Clean it up.
			$currency=trim($currency);
			# Set the data member.
			$this->currency=$currency;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->currency=NULL;
		}
	} #==== End -- setCurrency

	/**
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param	$description
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
	 * @param	$exploded_categories
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
	 * @param	$files
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
	 * @param	$object
	 * @access	protected
	 */
	protected function setFile($object)
	{
		$this->file=$object;
	} #==== End -- setFile

	/**
	 * setFileID
	 *
	 * Sets the data member $file_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setFileID($id)
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
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setID($id)
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
				throw new Exception('The passed product id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setAllImages
	 *
	 * Sets the data member $images.
	 *
	 * @param	$images
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
	 * @param	$object
	 * @access	protected
	 */
	protected function setImage($object)
	{
		# Set the data member.
		$this->image=$object;
	} #==== End -- setImage

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
	 * @param	$link
	 * @access	public
	 */
	public function setLink($link)
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
	 * setMore
	 *
	 * Sets the data member $more.
	 *
	 * @param	$more
	 * @access	public
	 */
	public function setMore($more)
	{
		# Check if the passed value is empty.
		if(!empty($more))
		{
			# Clean it up.
			$more=trim($more);
			# Set the data member.
			$this->tmoreitle=$more;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->more='more&nbsp;>>';
		}
	} #==== End -- setMore

	/**
	 * setPrice
	 *
	 * Sets the data member $price.
	 *
	 * @param	$price
	 * @access	public
	 */
	public function setPrice($price)
	{
		# Check if the passed value is empty.
		if(!empty($price))
		{
			# Clean it up.
			$price=trim($price);
			# Set the data member.
			$this->price=$price;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->price=NULL;
		}
	} #==== End -- setPrice

	/**
	 * setProductType
	 *
	 * Sets the data member $product_type.
	 *
	 * @param	$product_type
	 * @access	public
	 */
	public function setProductType($product_type)
	{
		# Set the data member.
		$this->product_type=$product_type;
	} #==== End -- setProductType

	/**
	 * setAllPublishers
	 *
	 * Sets the data member $all_publishers.
	 *
	 * @param	$publishers
	 * @access	protected
	 */
	protected function setAllPublishers($publishers)
	{
		$this->all_publishers=$publishers;
	} #==== End -- setAllPublishers

	/**
	 * setPublisher
	 *
	 * Sets the data member $publisher.
	 *
	 * @param	$publisher
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
	 * @param	$id
	 * @access	public
	 */
	public function setPublisherID($id)
	{
		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

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
	 * @param	$purchase_link
	 * @access	public
	 */
	public function setPurchaseLink($purchase_link)
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
	 * setSortBy
	 *
	 * Sets the data member $sort_by.
	 *
	 * @param	$sort_by
	 * @access	public
	 */
	public function setSortBy($sort_by)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $sort_by is empty.
		if(!empty($sort_by))
		{
			# Check if the passed $sort_by is an integer.
			if($validator->isInt($sort_by)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->sort_by=(int)$sort_by;
			}
			else
			{
				throw new Exception('The passed "sort by" value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sort_by=NULL;
		}
	} #==== End -- setSortBy

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param	$title
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

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllProducts
	 *
	 * Returns the data member $all_products.
	 *
	 * @access	protected
	 */
	public function getAllProducts()
	{
		return $this->all_products;
	} #==== End -- getAllProducts

	/**
	 * getASIN
	 *
	 * Returns the data member $asin.
	 *
	 * @access	public
	 */
	public function getASIN()
	{
		return $this->asin;
	} #==== End -- getASIN

	/**
	 * getCollectedASINs
	 *
	 * Returns the data member $collected_asins.
	 *
	 * @access	public
	 */
	public function getCollectedASINs()
	{
		return $this->collected_asins;
	} #==== End -- getCollectedASINs

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
	 * getButtonID
	 *
	 * Returns the data member $button_id.
	 *
	 * @access	public
	 */
	public function getButtonID()
	{
		return $this->button_id;
	} #==== End -- getButtonID

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
	 * @access	public
	 */
	public function getContent()
	{
		return $this->content;
	} #==== End -- getContent

	/**
	 * getCurrency
	 *
	 * Returns the data member $currency.
	 *
	 * @access	public
	 */
	public function getCurrency()
	{
		return $this->currency;
	} #==== End -- getCurrency

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
	 * getMore
	 *
	 * Returns the data member $more.
	 *
	 * @access	public
	 */
	protected function getMore()
	{
		return $this->more;
	} #==== End -- getMore

	/**
	 * getPrice
	 *
	 * Returns the data member $price.
	 *
	 * @access	protected
	 */
	public function getPrice()
	{
		return $this->price;
	} #==== End -- getPrice

	/**
	 * getProductType
	 *
	 * Returns the data member $product_type.
	 *
	 * @access	public
	 */
	public function getProductType()
	{
		return $this->product_type;
	} #==== End -- getProductType

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
	 * getPurchaseLink
	 *
	 * Returns the data member $purchase_link.
	 *
	 * @access	public
	 */
	public function getPurchaseLink()
	{
		return $this->purchase_link;
	} #==== End -- getPurchaseLink

	/**
	 * getSortBy
	 *
	 * Returns the data member $sort_by.
	 *
	 * @access	protected
	 */
	public function getSortBy()
	{
		return $this->sort_by;
	} #==== End -- getSortBy

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

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllRecords
	 *
	 * Returns the number of products in the database that are marked available.
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
			require_once MODULES.'Content'.DS.'Category.php';
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
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'products` '.$where.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $e)
		{
			throw new Exception('An error occured counting Products in the Database: '.$e->error.', code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllRecords

	/**
	 * deleteProduct
	 *
	 * Removes an product from the `product` table.
	 *
	 * @param	int						The id of the image in the `images` table.
	 * @access	public
	 */
	public function deleteProduct($id, $redirect=NULL)
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
					# Set the product's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					$this_product=$this->getThisProduct($id);
					# Check if the product was found.
					if($this_product!==TRUE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The product was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the product's name data member to a local variable.
					$product_title=$this->getTitle();
					try
					{
						# Delete the image from the `images` table.
						$deleted=$db->query('DELETE FROM `'.DBPREFIX.'products` WHERE `id` = '.$db->quote($id).' LIMIT 1');
						# Set a nice message to display to the user.
						$_SESSION['message']='The product '.$product_title.' was successfully deleted.';
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
					# Set a nice message to the session.
					$_SESSION['message']='That product was not valid.';
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
	} #==== End -- deleteProduct

	/**
	 * displayProduct
	 *
	 * Creates product XHTML elements and sets them to an array for display.
	 *
	 * @param	$page
	 * @param	$identifier
	 * @param	$image_size
	 * @param	$max_char				The maximum number of characters to display.
	 * @param	$access_level			The access levels needed for a logged in User to modify the products - must be a space sepparated string of numbers.
	 * @param	$labels					TRUE if other buttons should be displayed, ie "download", "more", FLASE if not.
	 * @access	public
	 */
	public function displayProduct($page, $identifier, $image_size, $max_char=NULL, $access_level=ADMIN_USERS, $labels=TRUE)
	{
		# Bring the Login object into scope.
		global $login;

		try
		{
			# Get the product from the data member and set it to a variable.
			$products=$this->getAllProducts();
			# Check if there is product to display.
			if(!empty($products))
			{
				# Create an empty array to hold product record id's after that record has been added to the $display_product variable.
				$used_ids=array();
				# Create new array to hold all display product.
				$display_product=array();
				# Create an empty array to hold the product ASIN's returned for the records.
				$asins=array();
				# Loop through the products to get any Amazon product.
				foreach($products as $product)
				{
					# Check if the Amazon Standard Identification Number(ASIN) is empty.
					if(!empty($product->ASIN))
					{
						# Set it to the ASIN's array.
						$asins[]=$product->ASIN;
					}
				}
				# Get the Amazon class.
				require_once MODULES.'Product'.DS.'Amazon.php';
				# Instantiate a new Amazon object.
				$amazon=new Amazon(AMAZON_ACCESS_KEY, AMAZON_ASS_TAG, AMAZON_SECRET_KEY);
				# Set the Amazon product display XHTML array to a variable.
				$display_amazon=$amazon->displayAmazonProduct($asins, $page, $identifier, $image_size, $max_char, $access_level, $labels);
				# Loop through the products.
				foreach($products as $product)
				{
					# Set the User's ability to edit this product to FALSE as default.
					$edit=FALSE;
					# Set the User's ability to delete this product to FALSE as default.
					$delete=FALSE;
					# Check if the logged in User has the privileges to modify this product.
					if($login->checkAccess($access_level)===TRUE)
					{
						# Set the User's ability to modify this product to TRUE.
						$edit=TRUE;
						$delete=TRUE;
					}
					# Create a variable to hold whether or not a "more" link should be displayed. Default is FALSE.
					$more=FALSE;
					# Set all relevant Data members.
					$this->setDataMembers($product);
					# Create a variable for the id.
					$id=$this->getID();
					# Check if this id has already been used.
					if(!in_array($id, $used_ids))
					{
						# Add this id to the used id's.
						$used_ids[]=$id;
						# Make the display product array multi-dimensional.
						$display_product[$id]=array('author'=>NULL, 'buy'=>NULL, 'content'=>NULL, 'button_id'=>NULL, 'currency'=>NULL, 'description'=>NULL, 'detailed_page_url'=>NULL, 'editorial_review'=>NULL, 'file'=>array(), 'image'=>NULL, 'link'=>NULL, 'price'=>NULL, 'publisher'=>NULL, 'purchase_link'=>NULL, 'title'=>NULL, 'edit'=>NULL, 'delete'=>NULL);
						# Create a variable for the ASIN.
						$asin=$this->getASIN();
						# Set the Product data members to variables. (All the values in the `product` table may override the values returned from Amazon except 'detailed_page_url'.)
						# Set the author to a variable.
						$author=$this->getAuthor();
						# Set the product's Paypal button ID to a variable.
						$button_id=$this->getButtonID();
						# Set the product's content to a variable.
						$content=$this->getContent();
						# Create variable for the product's currency.
						$currency=$this->getCurrency();
						# Set the product's description to a variable.
						$description=$this->getDescription();
						# Create the detailed variable with the default value FALSE.
						$detailed=FALSE;
						# Create a variable for the detailed page URL.
						$detailed_page_url=Utility::removeIndex('http://'.FULL_DOMAIN.HERE);
						# Set any potential GET query to a variable.
						$get_query=GET_QUERY;

						$get_query=preg_replace('/(\?page\=[\d]{1,})?((\&|\?)sort_by\=[a-z\s\W\d]{1,})?(\?product\=[\d]{1,})?/i', '', $get_query);
						# Check if there is already a GET query in the current URL.
						if(empty($get_query))
						{
							# Add the product id to the end of the detailed page URL.
							$detailed_page_url.='?product='.$id;
						}
						else
						{
							# Add the product id to the end of the detailed page URL.
							$detailed_page_url.='&product='.$id;
						}
						# Check if this is a detailed product page.
						if($detailed_page_url==Utility::removeIndex('http://'.FULL_URL))
						{
							$detailed=TRUE;
						}
						# Set the product File object to a variable.
						$file_obj=$this->getFile();
						# Set the product Image object to a variable.
						$image_obj=$this->getImage();
						# Set the product link to a variable.
						$link=$this->getLink();
						# Set the product's price to a variable.
						$price=$this->getPrice();
						# Create variable for the Publisher object.
						$publisher_obj=$this->getPublisher();
						# Set the product's purchase link to a variable.
						$purchase_link=$this->getPurchaseLink();
						# Set the product's title to a variable.
						$title=$this->getTitle();

						# Check if a maximum number of characters to be displayed has been passed.
						if($max_char!==NULL)
						{
							# Check if there is text to display.
							if(!empty($content) || !empty($description))
							{
								# Strip tags from the content and the description and see if combined they contain more characters than allotted in the maximum characters variable.
								if(strlen(strip_tags($content).strip_tags($description)) > $max_char)
								{
									# Reset the content to nothing. It will be displayed on the detailed page.
									$content=NULL;
									# Strip tags from the description and see if it contains more characters than allotted in the maximum characters variable.
									if(strlen(strip_tags($description)) > $max_char)
									{
										# Get the Utility Class.
										require_once MODULES.'Utility'.DS.'Utility.php';
										# Use limitStringLength from the Document class to truncate the description.
										$description=Utility::truncate($description, $max_char, '...%1s', TRUE);
										# Add a "more" link to the description.
										$description=sprintf($description, ' <a class="more" href="'.$detailed_page_url.'" title="more on: '.$title.'">'.$this->getMore().'</a>');
										# Set the $more value to TRUE.
										$more=TRUE;
									}
								}
							}
						}

						# Check if the author is available to display.
						if(!empty($author))
						{
							# Set the author display XHTML to a variable.
							$author_content='<span class="author">';
							# Check if labels should be displayed.
							if($labels===TRUE)
							{
								# Add the label to the author display XHTML.
								$author_content.='<span class="label">Author:</span>';
							}
							$author_content.=$author;
							$author_content.='</span>';
							# Set the author content to the array.
							$display_product[$id]['author']=$author_content;
						}

						# Check if the Paypal button ID is set.
						if($button_id!==NULL)
						{
							# Get the PayPal Class.
							require_once MODULES.'PayPal'.DS.'PayPal.php';

							# Instantiate a new PayPal object.
							$paypal=new PayPal();
							# Set the type of Paypal button.
							$paypal->setCmd('_s-xclick');
							# Set the return page if the customer cancels the transaction.
							$paypal->setCancelReturn('http://'.FULL_URL.'?cancel=yes');
							# Create an empty variable for the user's ID.
							$uid='';
							# Check if the user is logged in.
							if($login->isLoggedIn()===TRUE)
							{
								# Set the user's ID to the variable.
								$uid=$login->findUserID();
							}
							# Set the user's ID as the "custom" pasthrough PayPal variable.
							$paypal->setCustom($uid);
							# Set the return page when the user copmpletes the transaction.
							$paypal->setReturn('http://'.FULL_URL.'?thankyou=yes');
							# Create the submit button. (only needs to be made once.)
							$paypal->setSubmit('submit', 'submit', 'Buy Now', NULL, 'submit-paypal');
							# Button ID from PayPal
							$paypal->setHostedButtonID($button_id);
							### DEBUG ###
							if(DEBUG_APP===TRUE)
							{
								# Create the PayPal button and set it to the display varable.
								$paypal_button=$paypal->makePayPalButton('add_cart', 'POST', '_top', FALSE, FALSE, TRUE, 'button-amazon');
							}
							else
							{
								# Create the PayPal button and set it to the display varable.
								$paypal_button=$paypal->makePayPalButton('add_cart', 'POST', '_top', FALSE, FALSE, FALSE, 'button-amazon');
							}
							$display_product[$id]['buy']=$paypal_button;
						}

						# Check if the purchase link is available to display.
						if(!empty($purchase_link))
						{
							# Instantiate a new FormGenerator object and creat the add to cart form/button.
							//$fg=new FormGenerator('add_cart', $purchase_link, 'POST', '_blank');
							//$fg->addElement('image', array('name'=>'add', 'value'=>'Buy '.$title), '', THEME.'images/transparent.dot.png');
							# Create a variable to hold the product image display XHTML.
							$buy_content='<a href="'.$purchase_link.'" class="buy" target="_blank" title="Buy '.$title.' Now!">Buy Now</a>'."\n";
							# Set the image content to the array.
							$display_product[$id]['buy']=$buy_content;
						}

						# Check if the content for the product is available to display.
						if(!empty($content))
						{
							# Set the content display XHTML to a variable.
							$product_content='<span class="content cont">';
							$product_content.=$content;
							$product_content.='</span>';
							# Set the content content to the array.
							$display_product[$id]['content']=$product_content;
						}

						# Check if the description of the product is available to display.
						if(!empty($description))
						{
							# Set the description display XHTML to a variable.
							$description_content='<span class="content desc">';
							# Check if labels should be displayed.
							if($labels===TRUE)
							{
								# Add the label to the description display XHTML.
								$description_content.='<span class="label">Description:</span>';
							}
							$description_content.=$description;
							$description_content.='</span>';
							# Set the description content to the array.
							$display_product[$id]['description']=$description_content;
						}

						# Set the product detailed page URL to the array.
						$display_product[$id]['detailed_page_url']=$detailed_page_url;

						# Check if there is a file.
						if($file_obj!==NULL)
						{
							# Set the file variables.
							# Set the file's availability to a variable.
							$file_availability=$file_obj->getAvailability();
							$file_id=$file_obj->getID();
							$file_name=$file_obj->getFile();
							$file_title=$file_obj->getTitle();
							# Check if the User is an admin user.
							if($login->checkAccess(ADMIN_USERS)===TRUE)
							{
								# Set the availability to 1(Yes, display) for this user. An admin may see anything.
								$file_availability=1;
							}
							# Check if the User is a managing user.
							if($login->checkAccess(MAN_USERS)===TRUE)
							{
								# Check if the files availability is 2(Internal document only).
								if($file_availability==2)
								{
									# Set the availability to 1(Yes, display) for this user.
									$file_availability=1;
								}
							}
							# Check if the file's availability is 1(Yes, display).
							if($file_availability==1)
							{
								# Set the download button to a variable.
								$download_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&t=product" class="download" title="Download Now!">Download</a>'."\n";
								# Set the delete content to the array.
								$display_content[$id]['file']['download']=$download_content;

								# Set the product file's name to the array.
								$file_name_content='<span class="file-name">';
								# Check if labels should be displayed.
								if($labels===TRUE)
								{
									$file_name_content.='<span class="label">Name:</span>';
								}
								$file_name_content.='<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&t=product" title="'.$file_title.'">'.$file_name.'</a>';
								$file_name_content.='</span>'."\n";
								# Set the delete content to the array.
								$display_content[$id]['file']['name']=$file_name_content;
								# Set the file info content to the array.
								$display_content[$id]['file']['all']=$file_name_content;

								# Set the product file's title to the array.
								$file_title_content='<span class="file-title">';
								# Check if labels should be displayed.
								if($labels===TRUE)
								{
									$file_title_content.='<span class="label">Title:</span>';
								}
								$file_title_content.=$file_title;
								$file_title_content.='</span>'."\n";
								# Set the delete content to the array.
								$display_content[$id]['file']['title']=$file_title_content;
								# Set the file info content to the array.
								$display_content[$id]['file']['all'].=$file_title_content;
							}
						}

						# Check if there is an image to display.
						if(!empty($image_obj))
						{
							# Create a variable to hold the product image display XHTML.
							$image_content=$image_obj->displayImage(TRUE, NULL, NULL);
							# Set the image content to the array.
							$display_product[$id]['image']=$image_content;
						}

						# Check if the price is available to display.
						if(!empty($price))
						{
							$price=$price.' '.$currency;
							if($currency=='USD')
							{
								$price='$'.$price;
							}
							if($currency=='CAD')
							{
								$price='$'.$price;
							}
							if($currency=='GBP')
							{
								$price='&pound;'.$price;
							}
							if($currency=='EUR')
							{
								$price='&euro;'.$price;
							}
							if($currency=='JPY')
							{
								$price='&yen;'.$price;
							}
							# Set the price display XHTML to a variable.
							$price_content='<span class="price">';
							# Check if labels should be displayed.
							if($labels===TRUE)
							{
								# Add the label to the review display XHTML.
								$price_content.='<span class="label">Price:</span>';
							}
							# Add a link to the detailed product page if this is not currently that page.
							if($detailed===FALSE)
							{
								$price_content.='<a href="'.$detailed_page_url.'" target="_blank" title="Find out more">'.$price.'</a>';
							}
							else
							{
								$price_content.=$price;
							}
							$price_content.='</span>';
							# Set the price content to the array.
							$display_product[$id]['price']=$price_content;
						}

						# Check if the publisher is available to display.
						if(!empty($publisher_obj))
						{
							# Set the publisher display XHTML to a variable.
							$publisher_content='<span class="publisher">';
							# Check if labels should be displayed.
							if($labels===TRUE)
							{
								# Add the label to the publisher display XHTML.
								$publisher_content.='<span class="label">Publisher:</span>';
							}
							$publisher_content.='<a href="'.APPLICATION_URL.'profile/?publisher='.$publisher_obj->getPublisher().'" target="_blank" title="'.$publisher_obj->getPublisher().'">'.$publisher_obj->getPublisher().'</a>';
							$publisher_content.='</span>';
							# Set the publisher content to the array.
							$display_product[$id]['publisher']=$publisher_content;
						}

						# Check if there is a title to display.
						if(!empty($title))
						{
							if(!empty($asin))
							{
								$title=$display_amazon[$asin]['title'];
							}
							# Set the title to a variable.
							$title_content='<a href="'.$detailed_page_url.'" class="title" title="'.str_replace('"', '&quot;', $title).'">'.$title.'</a>';
							# Set the title content to the array.
							$display_product[$id]['title']=$title_content;
						}

						# Check if there should be an edit button displayed.
						if($edit===TRUE)
						{
							# Set the edit button to a variable.
							$edit_content='<a href="'.ADMIN_URL.'product/edit/?product='.$id.'" class="edit" title="Edit">Edit</a>'."\n";
							# Set the edit content to the array.
							$display_product[$id]['edit']=$edit_content;
						}
						# Check f there should be a delete button displayed.
						if($delete===TRUE)
						{
							# Set the delete button to a variable.
							$delete_content='<a href="'.ADMIN_URL.'product/edit/?product='.$id.'&delete=yes" class="delete" title="Delete">Delete</a>'."\n";
							# Set the delete content to the array.
							$display_product[$id]['delete']=$delete_content;
						}
						# Check if the ASIN is empty.
						if(!empty($asin))
						{
							# Set the display product XHTML array with values from the display Amazon product array.
							# Check if the author is available to display.
							if(!empty($display_amazon[$asin]['author']))
							{
								# Set the author content to the array.
								$display_product[$id]['author']=$display_amazon[$asin]['author'];
							}
							# Set the buy button content to the array.
							$display_product[$id]['buy']=$display_amazon[$asin]['buy'];
							# Set the detailed page URL content to the array.
							$display_product[$id]['detailed_page_url']=$display_amazon[$asin]['detailed_page_url'];
							# Check if the editorial review is available to display.
							if(!empty($display_amazon[$asin]['editorial_review']))
							{
								# Set the editorial review content to the array.
								$display_product[$id]['editorial_review']=$display_amazon[$asin]['editorial_review'];
							}
							# Set the image content to the array.
							$display_product[$id]['image']=$display_amazon[$asin]['image'];
							# Check if there is an Amazon image.
							if((preg_match('/no\.image\.available\.gif/', $display_amazon[$asin]['image'])>0)&&!empty($image_obj))
							{
								# Create a variable to hold the product image display XHTML.
								$image_content=$image_obj->displayImage(TRUE, NULL, NULL);
								# Set the image content to the array.
								$display_product[$id]['image']=$image_content;
							}
							# Check if the price is available to display.
							if(!empty($display_amazon[$asin]['price']))
							{
								# Set the price content to the array.
								$display_product[$id]['price']=$display_amazon[$asin]['price'];
							}
							# Check if the publisher is available to display.
							if(!empty($display_amazon[$asin]['publisher']))
							{
								# Set the publisher content to the array.
								$display_product[$id]['publisher']=$display_amazon[$asin]['publisher'];
							}
							# Check if the edit button is available to display.
							if(!empty($display_amazon[$asin]['edit']))
							{
								# Set the edit button content to the array.
								$display_product[$id]['edit']=$display_amazon[$asin]['edit'];
							}
							# Check if the delete button is available to display.
							if(!empty($display_amazon[$asin]['delete']))
							{
								# Set the delete button content to the array.
								$display_product[$id]['delete']=$display_amazon[$asin]['delete'];
							}
						}
					}
				}
				return $display_product;
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayProduct

	/**
	 * displayProductList
	 *
	 * Returns a list (table) of products.
	 *
	 * @param	$select					Whether or not the list should be a radio select display.
	 * @access	public
	 */
	public function displayProductList($select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;

		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Count the products.
			$content_count=$this->countAllRecords();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='title';
				# Set the default sort direction of products for the product sorting link to a variable.
				$name_dir='DESC';
				# Check if GET data for product has been passed and it is an integer.
				if(isset($_GET['product']) && $validator->isInt($_GET['product'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['product']='product='.$_GET['product'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "product" GET query; it can't be passed with "select".
					unset($params_a['product']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC'))
				{
					# Set the query to the query parameters array.
					$params_a['by_title']='by_title='.$_GET['by_title'];
					# Check if the order is to be descending.
					if($_GET['by_title']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of products for the product sorting link to "ASC".
						$name_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_title" and "by_title" indexes of the array.
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the product and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to edit a branch.
				if($login->checkAccess(ALL_BRANCH_USERS)===TRUE && $select!=='select')
				{
					# Set the default value for displaying an edit button and a delete button to TRUE.
					$edit=TRUE;
					$delete=TRUE;
				}
				# Get the PageNavigator Class.
				require_once MODULES.'PageNavigator'.DS.'PageNavigator.php';
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(10, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Set the newly created WHERE clause to a variable.
				$and_sql='';
				# Get the Products.
				$this->getProducts('all', $paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned Product records to a variable.
				$all_products=$this->getAllProducts();

				# Create an empty array to hold the product ASIN's returned for the records.
				$asins=array();
				# Loop through the products to get any Amazon product.
				foreach($all_products as $product)
				{
					# Check if the Amazon Standard Identification Number(ASIN) is empty.
					if(!empty($product->ASIN))
					{
						# Set it to the ASIN's array.
						$asins[]=$product->ASIN;
					}
				}

				# Get the Amazon class.
				require_once MODULES.'Product'.DS.'Amazon.php';
				# Instantiate a new Amazon object.
				$amazon_obj=new Amazon(AMAZON_ACCESS_KEY, AMAZON_ASS_TAG, AMAZON_SECRET_KEY);
				# Set the Amazon product display XHTML array to a variable.

				if(isset($asins))
				{
					# Set the Amazon product display XHTML array to a variable.
					$display_amazon=$amazon_obj->displayAmazonProduct($asins, $paginator->getFirstParamName(), 'manage-products', 'MediumImage', 50);
				}

				# Start a table for the products and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the info column to a variable.
				$general_header='<th>Image</th>';
				# Set the table header for the product column to a variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageContent/products/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$name_dir.'" title="Order by product title">Title</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once MODULES.'Form'.DS.'FormGenerator.php';
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', 'http'.($validator->isSSL()===TRUE ? 's' : '').'://'.FULL_URL, 'post', '_top', FALSE, 'product-list');
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
				# Loop through the all_products array.
				foreach($all_products as $row)
				{
					# Instantiate a new Product object.
					$product_obj=new Product();
					# Set the relevant returned field values File data members.
					$product_obj->setASIN($row->ASIN);
					$product_obj->setID($row->id);
					$product_obj->setImageID($row->image);
					$product_obj->setTitle($row->title);
					# Set the relevant Product data members to local variables.
					$product_asin=$product_obj->getASIN();
					$product_id=$product_obj->getID();
					$product_image_id=$product_obj->getImageID();
					$product_title=str_ireplace('%{domain_name}', DOMAIN_NAME, $product_obj->getTitle());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					$display_product_image='';
					if(isset($product_image_id))
					{
						$product_obj->getThisImage($product_image_id);
						$image_obj=$product_obj->getImage();
						$product_image_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $image_obj->getImage());
						$display_product_image=$image_obj->displayImage(TRUE, NULL, NULL);
					}
					elseif(!isset($product_image_id) && isset($display_amazon[$product_asin]))
					{
						$display_product_image=$display_amazon[$product_asin]['image'];
					}
					# Add the product id to the end of the detailed page URL.
					$detailed_page_url='<a href="'.APPLICATION_URL.'store/?product='.$product_id.'" title="'.$product_title.' on '.DOMAIN_NAME.'">'.$product_title.'</a>';
					# Add the title markup to the $general_data variable.
					$general_data='<td>'.$display_product_image.'</td>';
### DRAVEN: Donation categories are linked to a product page even though they don't have product pages.
###		Do we create product pages for donations?
					# Set the product markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="product'.$product_id.'">' : '' ).$detailed_page_url.(($select==='select') ? '</label>' : '' ).'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageContent/products/?product='.$product_id.'" class="edit" title="Edit '.$product_title.'">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageContent/products/?product='.$product_id.'&amp;delete" class="delete" title="Delete '.$product_title.'">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this product.
						$fg->addElement('radio', array('name'=>'product_content', 'value'=>$product_id.':'.$product_title, 'id'=>'product'.$product_id));
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
					$fg->addElement('submit', array('name'=>'product', 'value'=>'Select'), '', NULL, 'submit-product');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a product below</h4>'.$fg->display();
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
				$display='<h3>There are no products to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayProductList

	/**
	 * getFiles
	 *
	 * Retrieves records from the `files` table. A wrapper method for getFiles from the File class.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getFiles($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the File class.
			require_once MODULES.'Document'.DS.'File.php';
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
	 * @param	String $value			The name or id of the file to retrieve.
	 * @param	Boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisFile($value, $id=TRUE)
	{
		try
		{
			# Get the File class.
			require_once MODULES.'Media'.DS.'File.php';
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
		try
		{
			# Get the Image class.
			require_once MODULES.'Media'.DS.'Image.php';
			# Instantiate a new Image object.
			$image=new Image();
			# Get the institutions.
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
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member.
	 * A wrapper method for getThisImage from the Image class.
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
			# Get the image info.
			$image_obj->getThisImage($value, $id);
			# Set the image object to the data member.
			$this->setImage($image_obj);
			# Set the image id to the data member.
			$this->setImageID($image_obj->getID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisImage

	/**
	 * getProducts
	 *
	 * Retrieves Products records from the database.
	 *
	 * @param	$category				The name of the category(ies) to be retrieved.
	 *										May be multiple categories - separate with a dash, ie. 'Music-Books'
	 * @param	$limit					The LIMIT of the records.
	 * @param	$fields					The name of the field(s) to be retrieved.
	 * @param	$order					The name of the field to order the records by.
	 * @param	$direction				The direction to order the records.
	 * @param	$and_sql				Any extra AND queries.
	 * @access	public
	 */
	public function getProducts($categories=NULL, $limit=NULL, $fields='*', $order='title', $direction='DESC', $and_sql=NULL)
	{
		global $db;

		try
		{
			# Get the Category class.
			require_once MODULES.'Content'.DS.'Category.php';
			# Instantiate a new Category object.
			$category_obj=new Category();
			# Check if all categories are requested.
			if(strtolower($categories)!=='all')
			{
				# Create the WHERE portion of the SQL statement for the categories requested.
				$category_obj->createWhereSQL($categories);
			}
			# Set the WHERE portion of the SQL statement for the categories requested to a variable.
			$where=$category_obj->getWhereSQL();
			# Check if there should be a WHERE portion of the SQL statement.
			if(!empty($where) || !empty($and_sql))
			{
				$where='WHERE'.((empty($where)) ? '' : ' '.$where).((empty($and_sql)) ? '' : ' '.((!empty($where)) ? 'AND ' : '').$and_sql);
			}
			# Get the records from the `products` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'products` '.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			# Set the returned records to the data member.
			$this->setAllProducts($records);
		}
		catch(ezDB_Error $e)
		{
			# Throw an error because there was aproblem accessing the database.
			throw new Exception('An error occured retrieving Products from the Database: '.$e->error.'<br />Code: '.$e->errno.'<br />Last query: '.$e->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any uncaught errors.
			throw $e;
		}
	} #==== End -- getProducts

	/**
	 * getThisProduct
	 *
	 * Retrieves product info from the `products` table in the database for the passed id or product name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the product to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean					TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisProduct($value, $id=TRUE)
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
				# Set the publisher id to the data member "cleaning" it.
				$this->setID($value);
				# Get the publisher id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='title';
				# Set the publisher name to the data member "cleaning" it.
				$this->setTitle($value);
				# Get the publisher name and reset it to the variable.
				$value=$this->getTitle();
			}
			# Get the publisher info from the Database.
			$product=$db->get_row('SELECT `id`, `category`, `sort_by`, `ASIN`, `price`, `currency`, `button_id`, `image`, `title`, `author`, `publisher`, `description`, `content`, `purchase_link`, `file` FROM `'.DBPREFIX.'products` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($product!==NULL)
			{
				# Set the product's author to the data member.
				$this->setAuthor($product->author);
				# Set the product's ASIN to the data member.
				$this->setASIN($product->ASIN);
				# Set the product's Paypal Button ID to the data member.
				$this->setButtonID($product->button_id);
				# Set the product's name to the data member.
				$this->setCategories($product->category);
				# Set the content to the data member.
				$this->setContent($product->content);
				# Set the product's currency to the data member.
				$this->setCurrency($product->currency);
				# Set the description to the data memer.
				$this->setDescription($product->description);
				# Set the product's file to the data member.
				$this->setFileID($product->file);
				# Set the product's id to the data member.
				$this->setID($product->id);
				# Set the product's image ID to the data member.
				$this->setImageID($product->image);
				# Set the product's price to the data member.
				$this->setPrice($product->price);
				# Set the product's publisher to the data member.
				$this->setPublisher($product->publisher);
				# Set the product's purchase link to the data member.
				$this->setPurchaseLink($product->purchase_link);
				# Set the product's sort_by to the data member.
				$this->setSortBy($product->sort_by);
				# Set the title to the data member.
				$this->setTitle($product->title);
				return TRUE;
			}
			# Return FALSE because the publisher wasn't in the table.
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
	} #==== End -- getThisProduct

	/**
	 * getPublishers
	 *
	 * Retrieves records from the `publishers` table.
	 * A wrapper method for getPublishers from the Publisher class.
	 *
	 * @param	$limit					The LIMIT of the records.
	 * @param	$fields					The name of the field(s) to be retrieved.
	 * @param	$order					The name of the field to order the records by.
	 * @param	$direction				The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	Boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getPublishers($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Publisher class.
			require_once MODULES.'Content'.DS.'Publisher.php';
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
	 * Retrieves publisher info from the `publishers` table in the Database for the passed id or publisher name and sets it to the data member.
	 * A wrapper method for getThisPublisher from the Publisher class.
	 *
	 * @param	String $value			The name or id of the publisher to retrieve.
	 * @param	Boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisPublisher($value, $id=TRUE)
	{
		try
		{
			# Get the Publisher class.
			require_once MODULES.'Content'.DS.'Publisher.php';
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
	 * Sets all the data returned in a row from the `subcontent` table to the appropriate Data members.
	 *
	 * @param	$row					The returned row of data from a record to set to the data members.
	 * @access	public
	 */
	public function setDataMembers($row)
	{
		try
		{
			# Reset all the data members.
			$this->setID(NULL);
			$this->setASIN(NULL);
			$this->setAuthor(NULL);
			$this->setButtonID(NULL);
			$this->setContent(NULL);
			$this->setDescription(NULL);
			$this->setFile(NULL);
			$this->setImage(NULL);
			$this->setLink(NULL);
			$this->setPrice(NULL);
			$this->setPublisher(NULL);
			$this->setPurchaseLink(NULL);
			$this->setTitle(NULL);
			# Set product id to the data member.
			$this->setID($row->id);
			# Set the returned ASIN value to a variable.
			$asin=$row->ASIN;
			# Set product ASIN to the data member.
			$this->setASIN($asin);
			# Check if the ASIN value is empty.
			//if(empty($asin))
			//{
				# Set the author to the data member.
				$this->setAuthor($row->author);
				# Set the Paypal Button ID to the data member.
				$this->setButtonID($row->button_id);
				# Set product description to the data member.
				$this->setContent($row->content);
				# Set product price's currency to the data member.
				$this->setCurrency($row->currency);
				# Set product description to the data member.
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
				# Set product link to the data member.
				$this->setLink($row->link);
				# Set the price to the data member.
				$this->setPrice($row->price);
				# Check if there is an publisher value.
				if($row->publisher!==NULL)
				{
					# Retrieve the publisher info from the `publishers` table via the publisher id returned in the $row data.
					$this->getThisPublisher($row->publisher);
				}
				# Set the product's purchase link to the data member.
				$this->setPurchaseLink($row->purchase_link);
				# Set the product title to the data member.
				$this->setTitle($row->title);
			//}
			//else
			//{
				# Set the ASIN to the collected ASIN array.
				$this->setCollectedASINs($asin);
			//}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setDataMembers

	/*** End protected methods ***/

} # end Product class