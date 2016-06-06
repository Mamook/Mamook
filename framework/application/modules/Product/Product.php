<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/*
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



	/*** mutator methods ***/

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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
				$categories_array=explode('-', $value);
			}
			else
			{
				# Create an empty array to hold the categories.
				$categories_array=$value;
			}
			# Get the Category class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category_obj=new Category();
			# Create a variable to hold the "WHERE" clause.
			$where_clause=array();
			# Loop through the $value array to build the "WHERE" clause.
			foreach($categories_array as $category_value)
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
			$category_obj->getCategories(NULL, '*', 'id', 'ASC', $where_clause);
			# Set the returned records to a variable.
			$all_categories=$category_obj->getAllCategories();
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

	/*
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
			# Replace any tokens with their correlating value.
			$content=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $content);
			# Set the data member.
			$this->content=$content;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->content=NULL;
		}
	} #==== End -- setContent

	/*
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

	/*
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
			# Replace any tokens with their correlating value.
			$description=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $description);
			# Set the data member.
			$this->description=$description;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->description=NULL;
		}
	} #==== End -- setDescription

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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
			# Replace any tokens with their correlating value.
			$link=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $link);
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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
			# Replace any tokens with their correlating value.
			$purchase_link=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $purchase_link);
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
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

	/*
	 * countAllRecords
	 *
	 * Returns the number of products in the database that are marked available.
	 *
	 * @param	$category				The id of the category database table to access.
	 * @param	$limit					The limit of records to count.
	 * @param	$and_sql				Extra AND statements in the query.
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
			$category_obj=new Category();
			# Check if all categories are requested.
			if(strtolower($categories)!=='all')
			{
				$category_obj->createWhereSQL($categories, 'category');
			}
			# Set the WHERE portion of the SQL statement for the categories requested to a variable.
			$where=$category_obj->getWhereSQL();
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

	/*
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
						throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
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

	/*
	 * displayProduct
	 *
	 * Creates product HTML elements and sets them to an array for display.
	 *
	 * @param	$page
	 * @param	$identifier
	 * @param $option				An Array of various options for displaying Products. The available options are:
	 *												"image_size" => What size of image should be called from Amazon for Amazon products. the default is
	 *																				"MediumImage". The possible values are:
	 *																				"LargeImage"
	 *																				"MediumImage"
	 *																				"SmallImage"
	 *																				"SwatchImage"
	 *																				"ThumbnailImage"
	 *																				"TinyImage"
	 *												"max_char" => The maximum number of characters to display in product descriptions. The default is NULL
	 *																			(unlimited).
	 *												"access_level" => The access levels needed for a logged in User to modify the products - must be a
	 *																					space sepparated string of numbers. The default is ADMIN_USERS.
	 *												"labels" => TRUE if other buttons should be displayed (ie "download", "more") FALSE if not. The default is TRUE.
	 *												"title_class" => The class for the title container. Default is "title".
	 *												"title_link" => NULL if the title should NOT be wrapped in an anchor tag. Otherwise, the URL to link to. If
	 *																				the title should link to the details page, simply pass the value "default". Default is
	 *																				"default".
	 *												"title_link_title" => This is NOT used if the title should NOT be wrapped in an anchor tag. If it WILL be,
	 *																							pass the text to be used in the "title" attribute for the link. To use the product
	 *																							title, "{PRODUCT_TITLE}", simply pass the value "default". The default is "default".
	 * @access	public
	 */
	public function displayProduct($page, $identifier, $options=array())
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
				# General options defaults.
				$access_level=((array_key_exists('access_level', $options)) ? $options['access_level'] : ADMIN_USERS);
				$labels=((array_key_exists('labels', $options)) ? $options['labels'] : TRUE);
				$label_class=((array_key_exists('label_class', $options)) ? $options['label_class'] : 'label');
				$max_char=((array_key_exists('max_char', $options)) ? $options['max_char'] : NULL);

				# Set the User's ability to edit this product to FALSE as default.
				$edit=FALSE;
				# Set the User's ability to delete this product to FALSE as default.
				$delete=FALSE;
				# Check if the logged in User has the privileges to modify this product.
				if($login->checkAccess($access_level)===TRUE)
				{
					# Set the User's ability to modify this product. Default is TRUE.
					$edit=((array_key_exists('edit', $options)) ? $options['edit'] : TRUE);
					$edit_class=((array_key_exists('edit_class', $options)) ? $options['edit_class'] : 'edit');
					$edit_link_title=((array_key_exists('edit_link_title', $options)) ? $options['edit_link_title'] : 'Edit this product');
					$edit_value=((array_key_exists('edit_value', $options)) ? $options['edit_value'] : 'Edit');
					$delete=((array_key_exists('delete', $options)) ? $options['delete'] : TRUE);
					$delete_class=((array_key_exists('delete_class', $options)) ? $options['delete_class'] : 'delete');
					$delete_link_title=((array_key_exists('delete_link_title', $options)) ? $options['delete_link_title'] : 'Delete this product');
					$delete_value=((array_key_exists('delete_value', $options)) ? $options['delete_value'] : 'Delete');
				}

				# Author options defaults.
				$author_class=((array_key_exists('author_class', $options)) ? $options['author_class'] : 'author');
				$author_label=((array_key_exists('author_label', $options)) ? $options['author_label'] : (($labels===TRUE) ? 'Author:' : ''));
				$author_label_class=((array_key_exists('author_label_class', $options)) ? $options['author_label_class'] : $label_class);
				$author_link=((array_key_exists('author_link', $options)) ? $options['author_link'] : NULL);
				$author_link_title=((array_key_exists('author_link_title', $options)) ? $options['author_link_title'] : 'default');

				# Buy button options defaults.
				$buy_amazon_button_class=((array_key_exists('buy_amazon_button_class', $options)) ? $options['buy_amazon_button_class'] : 'button-amazon');
				$buy_amazon_button_name=((array_key_exists('buy_amazon_button_name', $options)) ? $options['buy_amazon_button_name'] : 'add_cart');
				$buy_amazon_label=((array_key_exists('buy_amazon_label', $options)) ? $options['buy_amazon_label'] : (($labels===TRUE) ? 'Buy now from' : ''));
				$buy_amazon_label_class=((array_key_exists('buy_amazon_label_class', $options)) ? $options['buy_amazon_label_class'] : $label_class);
				$buy_amazon_submit_class=((array_key_exists('buy_amazon_submit_class', $options)) ? $options['buy_amazon_submit_class'] : 'submit-amazon');
				$buy_amazon_submit_image=((array_key_exists('buy_amazon_submit_image', $options)) ? $options['buy_amazon_submit_image'] : NULL);
				$buy_amazon_submit_name=((array_key_exists('buy_amazon_submit_name', $options)) ? $options['buy_amazon_submit_name'] : 'add');
				$buy_amazon_submit_type=((array_key_exists('buy_amazon_submit_type', $options)) ? $options['buy_amazon_submit_type'] : 'submit');
				$buy_amazon_submit_value=((array_key_exists('buy_amazon_submit_value', $options)) ? $options['buy_amazon_submit_value'] : 'Buy from Amazon');
				$buy_class=((array_key_exists('buy_class', $options)) ? $options['buy_class'] : 'buy');
				$buy_link_title=((array_key_exists('buy_link_title', $options)) ? $options['buy_link_title'] : 'default');
				$buy_link_value=((array_key_exists('buy_link_value', $options)) ? $options['buy_link_value'] : 'Buy Now');
				$buy_paypal_button_class=((array_key_exists('buy_paypal_button_class', $options)) ? $options['buy_paypal_button_class'] : 'button-paypal');
				$buy_paypal_button_name=((array_key_exists('buy_paypal_button_name', $options)) ? $options['buy_paypal_button_name'] : 'add_cart');
				$buy_paypal_submit_class=((array_key_exists('buy_paypal_submit_class', $options)) ? $options['buy_paypal_submit_class'] : 'submit-paypal');
				$buy_paypal_submit_image=((array_key_exists('buy_paypal_submit_image', $options)) ? $options['buy_paypal_submit_image'] : NULL);
				$buy_paypal_submit_name=((array_key_exists('buy_paypal_submit_name', $options)) ? $options['buy_paypal_submit_name'] : 'submit');
				$buy_paypal_submit_type=((array_key_exists('buy_paypal_submit_type', $options)) ? $options['buy_paypal_submit_type'] : 'submit');
				$buy_paypal_submit_value=((array_key_exists('buy_paypal_submit_value', $options)) ? $options['buy_paypal_submit_value'] : $buy_link_value);

				# Content options defaults.
				$content_class=((array_key_exists('content_class', $options)) ? $options['content_class'] : 'content cont');
				$content_description_as_one=((array_key_exists('content_description_as_one', $options)) ? $options['content_description_as_one'] : FALSE);
				$content_label=((array_key_exists('content_label', $options)) ? $options['content_label'] : '');
				$content_label_class=((array_key_exists('content_label_class', $options)) ? $options['content_label_class'] : $label_class);
				$content_max_char=((array_key_exists('content_max_char', $options)) ? $options['content_max_char'] : $max_char);
				$content_more_class=((array_key_exists('content_more_class', $options)) ? $options['content_more_class'] : 'more');

				# Description options defaults.
				$description_class=((array_key_exists('description_class', $options)) ? $options['description_class'] : 'content desc');
				$description_label=((array_key_exists('description_label', $options)) ? $options['description_label'] : (($labels===TRUE) ? 'Description:' : ''));
				$description_label_class=((array_key_exists('description_label_class', $options)) ? $options['description_label_class'] : $label_class);
				$description_max_char=((array_key_exists('description_max_char', $options)) ? $options['description_max_char'] : $max_char);
				$description_more_class=((array_key_exists('description_more_class', $options)) ? $options['description_more_class'] : 'more');

				# File options defaults.
				$file_download_class=((array_key_exists('file_download_class', $options)) ? $options['file_download_class'] : 'download');
				$file_download_link_title=((array_key_exists('file_download_link_title', $options)) ? $options['file_download_link_title'] : 'Download Now!');
				$file_download_link_value=((array_key_exists('file_download_link_value', $options)) ? $options['file_download_link_value'] : 'Download');
				$file_name_class=((array_key_exists('file_name_class', $options)) ? $options['file_name_class'] : 'file-name');
				$file_name_label=((array_key_exists('file_name_label', $options)) ? $options['file_name_label'] : (($labels===TRUE) ? 'Name:' : ''));
				$file_name_label_class=((array_key_exists('file_name_label_class', $options)) ? $options['file_name_label_class'] : $label_class);
				$file_name_link=((array_key_exists('file_name_link', $options)) ? $options['file_name_link'] : TRUE);
				$file_name_link_title=((array_key_exists('file_name_link_title', $options)) ? $options['file_name_link_title'] : 'default');
				$file_title_class=((array_key_exists('file_title_class', $options)) ? $options['file_title_class'] : 'file-title');
				$file_title_label=((array_key_exists('file_title_label', $options)) ? $options['file_title_label'] : (($labels===TRUE) ? 'Title:' : ''));
				$file_title_label_class=((array_key_exists('file_title_label_class', $options)) ? $options['file_title_label_class'] : $label_class);
				$file_title_link=((array_key_exists('file_title_link', $options)) ? $options['file_title_link'] : FALSE);
				$file_title_link_title=((array_key_exists('file_title_link_title', $options)) ? $options['file_title_link_title'] : 'default');

				# Image options defaults.
				$image_link=((array_key_exists('image_link', $options)) ? $options['image_link'] : FW_POPUP_HANDLE);
				$image_size=((array_key_exists('image_size', $options)) ? $options['image_size'] : 'MediumImage');

				# Publisher options defaults.
				$publisher_class=((array_key_exists('publisher_class', $options)) ? $options['publisher_class'] : 'publisher');
				$publisher_label=((array_key_exists('publisher_label', $options)) ? $options['publisher_label'] : (($labels===TRUE) ? 'Publisher:' : ''));
				$publisher_label_class=((array_key_exists('publisher_label_class', $options)) ? $options['publisher_label_class'] : $label_class);
				$publisher_link=((array_key_exists('publisher_link', $options)) ? $options['publisher_link'] : 'default');
				$publisher_link_title=((array_key_exists('publisher_link_title', $options)) ? $options['publisher_link_title'] : 'default');

				# Price options defaults.
				$price_class=((array_key_exists('price_class', $options)) ? $options['price_class'] : 'price');
				$price_label=((array_key_exists('price_label', $options)) ? $options['price_label'] : (($labels===TRUE) ? 'Price:' : ''));
				$price_label_class=((array_key_exists('price_label_class', $options)) ? $options['price_label_class'] : $label_class);
				$price_link=((array_key_exists('price_link', $options)) ? $options['price_link'] : 'default');
				$price_link_title=((array_key_exists('price_link_title', $options)) ? $options['price_link_title'] : 'default');

				# Title options defaults.
				$title_class=((array_key_exists('title_class', $options)) ? $options['title_class'] : 'title');
				$title_label=((array_key_exists('title_label', $options)) ? $options['title_label'] : '');
				$title_label_class=((array_key_exists('title_label_class', $options)) ? $options['title_label_class'] : $label_class);
				$title_link=((array_key_exists('title_link', $options)) ? $options['title_link'] : 'default');
				$title_link_title=((array_key_exists('title_link_title', $options)) ? $options['title_link_title'] : 'default');

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
				require_once Utility::locateFile(MODULES.'Product'.DS.'Amazon.php');
				# Instantiate a new Amazon object.
				$amazon=new Amazon(AMAZON_ACCESS_KEY, AMAZON_ASS_TAG, AMAZON_SECRET_KEY);
				# Set the Amazon product display HTML array to a variable.
				$display_amazon=$amazon->displayAmazonProduct($asins, $page, $identifier, $options);

				# Loop through the products.
				foreach($products as $product)
				{
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

						# Create a variable for the detailed page URL.
						$detailed_page_url=Utility::removeIndex(PROTOCAL.FULL_DOMAIN.HERE);
						# Set any potential GET query to a variable.
						$get_query=GET_QUERY;
						$get_query=preg_replace('/(\?page\=[\d]{1,})?((\&|\?)sort_by\=[a-z\s\W\d]{1,})?(\?product\=[\d]{1,})?/i', '', $get_query);
						# Add the product id to the end of the detailed page URL.
						$detailed_page_url.=((empty($get_query)) ? '?': '&').'product='.$id;

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
						# Create a variable for the publisher.
						$publisher=((!empty($publisher_obj)) ? $publisher_obj->getPublisher() : '');
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
										require_once UTILITY_CLASS;
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

						# Set the author content to the array.
						$display_product[$id]['author']=$this->createAuthorMarkup($author, array(
							'author_class'=>$author_class,
							'author_label'=>$author_label,
							'author_label_class'=>$author_label_class,
							'author_link'=>$author_link,
							'title_attribute'=>$author_link_title
						));

						# Check if the Paypal button ID is set.
						if($button_id!==NULL)
						{
							# Get the PayPal Class.
							require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');
							# Instantiate a new PayPal object.
							$paypal=new PayPal();
							# Set the type of Paypal button.
							$paypal->setCmd('_s-xclick');
							# Set the return page if the customer cancels the transaction.
							$paypal->setCancelReturn(PROTOCAL.FULL_URL.'?cancel=yes');
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
							$paypal->setReturn(PROTOCAL.FULL_URL.'?thankyou=yes');
							# Create the submit button. (only needs to be made once.)
							$paypal->setSubmit($buy_paypal_submit_type, $buy_paypal_submit_name, $buy_paypal_submit_value, $buy_paypal_submit_image, $buy_paypal_submit_class);
							# Button ID from PayPal
							$paypal->setHostedButtonID($button_id);
							# Create the PayPal button and set it to the display varable.
							$paypal_button=$paypal->makePayPalButton($buy_paypal_button_name, 'POST', '_top', FALSE, FALSE, DEBUG_APP, $buy_paypal_button_class);
							$display_product[$id]['buy']=$paypal_button;
						}

						# Check if the purchase link is available to display.
						if(!empty($purchase_link))
						{
							# Set the $buy_link_title value to a local variable so it won't get over-written.
							$title_attribute=$buy_link_title;
							# Check if the link title attribute should be the default.
							if($title_attribute=='default')
							{
								$title_attribute='Buy '.htmlentities($title, ENT_QUOTES, 'UTF-8', FALSE).' Now!';
							}
							# Create a variable to hold the product image display XHTML.
							$buy_content='<a href="'.$purchase_link.'" class="'.$buy_class.'" target="_blank" title="'.$title_attribute.'">'.$buy_link_value.'</a>';
							# Set the image content to the array.
							$display_product[$id]['buy']=$buy_content;
						}

						# Check if the content for the product is available to display.
						if(!empty($content))
						{
							# Open the span tag.
							$product_content='<span class="'.$content_class.'">%{insert_content}</span>';
							# Set the $content_label value to a local variable so it won't get over-written.
							$content_label_markup=$content_label;
							# Check if there should be a lable for the content.
							if($content_label_markup!=='')
							{
								$content_label_markup='<span class="'.$content_label_class.'">'.$content_label_markup.'</span>';
							}
							# Put the content inside its container.
							$product_content=str_replace('%{insert_content}', $content_label_markup.$content, $product_content);
							# Set the content content to the array.
							$display_product[$id]['content']=$product_content;
						}

						# Check if the description of the product is available to display.
						if(!empty($description))
						{
							# Set the description display XHTML to a variable.
							$description_content='<span class="'.$description_class.'">%{insert_content}</span>';
							# Set the $description_label value to a local variable so it won't get over-written.
							$description_label_markup=$description_label;
							# Check if labels should be displayed.
							if($description_label_markup!=='')
							{
								# Add the label to the description display HTML.
								$description_label_markup='<span class="'.$description_label_class.'">'.$description_label_markup.'</span>';
							}
							# Put the description inside its container.
							$description_content=str_replace('%{insert_content}', $description_label_markup.$description, $description_content);
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
								$download_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&t=product" class="'.$file_download_class .'" title="'.$file_download_link_title.'">'.$file_download_link_value.'</a>';
								# Set the delete content to the array.
								$display_content[$id]['file']['download']=$download_content;

								# Check if the file name should be wrapped in an anchor tag with the value of $file_name_link as the href.
								if(!empty($file_name_link))
								{
									# Set the $file_name_link_title value to a local variable so it won't get over-written.
									$title_attribute=$file_name_link_title;
									# Check if the link title attribute should be the default.
									if($title_attribute=='default')
									{
										$title_attribute='Download '.htmlentities($file_title, ENT_QUOTES, 'UTF-8', FALSE);
									}
									# Open the anchor tag.
									$file_name_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&t=product" class="'.$file_name_class.'" title="'.$title_attribute.'">%s</a>';
								}
								else
								{
									# Open the span tag.
									$file_name_content='<span class="'.$file_name_class.'">%s</span>';
								}
								# Set the $file_name_label value to a local variable so it won't get over-written.
								$file_name_label_markup=$file_name_label;
								# Check if there should be a label for the file name.
								if($file_name_label_markup!=='')
								{
									$file_name_label_markup='<span class="'.$file_name_label_class.'">'.$file_name_label_markup.'</span>';
								}
								# Put the file name inside its container.
								$file_name_content=$file_name_label_markup.$file_name;
								# Set the delete content to the array.
								$display_content[$id]['file']['name']=$file_name_content;
								# Set the file info content to the array.
								$display_content[$id]['file']['all']=$file_name_content;

								# Check if the file title should be wrapped in an anchor tag with the value of $file_title_link as the href.
								if(!empty($file_title_link))
								{
									# Set the $file_title_link_title value to a local variable so it won't get over-written.
									$title_attribute=$file_title_link_title;
									# Check if the link title attribute should be the default.
									if($title_attribute=='default')
									{
										$title_attribute='Download '.$file_title;
									}
									# Open the anchor tag.
									$file_title_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&t=product" class="'.$file_title_class.'" title="'.$title_attribute.'">%s</a>';
								}
								else
								{
									# Open the span tag.
									$file_title_content='<span class="'.$file_title_class.'">%s</span>';
								}
								# Set the $file_title_label value to a local variable so it won't get over-written.
								$file_title_label_markup=$file_title_label;
								# Check if there should be a label for the file title.
								if($file_title_label_markup!=='')
								{
									$file_title_label_markup='<span class="'.$file_title_label_class.'">'.$file_title_label_markup.'</span>';
								}
								# Put the file title inside its container.
								$file_title_content=$file_title_label_markup.$file_title;
								# Set the delete content to the array.
								$display_content[$id]['file']['title']=$file_title_content;
								# Set the file info content to the array.
								$display_content[$id]['file']['all']=$file_title_content;
							}
						}

						# Set the image content to the array.
						$display_product[$id]['image']=$this->createImageMarkup($image_obj, array(
							'image_link'=>$image_link
						));

						# Set the price content to the array.
						$display_product[$id]['price']=$this->createPriceMarkup($price, array(
							'currency'=>$currency,
							'detailed_page_url'=>$detailed_page_url,
							'price_class'=>$price_class,
							'price_label'=>$price_label,
							'price_label_class'=>$price_label_class,
							'price_link'=>$price_link,
							'product_title'=>$title,
							'title_attribute'=>$price_link_title
						));

						# Set the publisher content to the array.
						$display_product[$id]['publisher']=$this->createPublisherMarkup($publisher, array(
							'publisher_class'=>$publisher_class,
							'publisher_label'=>$publisher_label,
							'publisher_label_class'=>$publisher_label_class,
							'publisher_link'=>$publisher_link,
							'title_attribute'=>$publisher_link_title
						));

						# Set the title content to the array.
						$display_product[$id]['title']=$this->createTitleMarkup($title, array(
							'detailed_page_url'=>$detailed_page_url,
							'title_class'=>$title_class,
							'title_label'=>$title_label,
							'title_label_class'=>$title_label_class,
							'title_link'=>$title_link,
							'title_attribute'=>$title_link_title
						));

						# Check if there should be an edit button displayed.
						if($edit===TRUE)
						{
							# Set the edit button to a variable.
							$edit_content='<a href="'.ADMIN_URL.'product/?product='.$id.'" class="'.$edit_class.'" title="'.$edit_link_title.'">'.$edit_value.'</a>';
							# Set the edit content to the array.
							$display_product[$id]['edit']=$edit_content;
						}

						# Check f there should be a delete button displayed.
						if($delete===TRUE)
						{
							# Set the delete button to a variable.
							$delete_content='<a href="'.ADMIN_URL.'product/?product='.$id.'&delete" class="'.$delete_class.'" title="'.$delete_link_title.'">'.$delete_value.'</a>';
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
							# Check if there is an image available in the system already.
							if(empty($display_product[$id]['image'])&&!empty($display_amazon[$asin]['image']))
							{
								# Set the image content to the array.
								$display_product[$id]['image']=$display_amazon[$asin]['image'];
							}
							# Check if the price is available to display.
							if(!empty($display_amazon[$asin]['price']))
							{
								# Set the price content to the array.
								$display_product[$id]['price']=$display_amazon[$asin]['price'];
							}
							# Check if the publisher is available to display.
							if(empty($display_product[$id]['publisher'])&&!empty($display_amazon[$asin]['publisher']))
							{
								# Set the publisher content to the array.
								$display_product[$id]['publisher']=$display_amazon[$asin]['publisher'];
							}
							# Check if the title is available to display.
							if(!empty($display_amazon[$asin]['title']))
							{
								# Set the title content to the array.
								$display_product[$id]['title']=$display_amazon[$asin]['title'];
							}
							# Check if the edit button is available to display.
							if(empty($display_product[$id]['edit'])&&!empty($display_amazon[$asin]['edit']))
							{
								# Set the edit button content to the array.
								$display_product[$id]['edit']=$display_amazon[$asin]['edit'];
							}
							# Check if the delete button is available to display.
							if(empty($display_product[$id]['delete'])&&!empty($display_amazon[$asin]['delete']))
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

	/*
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
				require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
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
				require_once Utility::locateFile(MODULES.'Product'.DS.'Amazon.php');
				# Instantiate a new Amazon object.
				$amazon_obj=new Amazon(AMAZON_ACCESS_KEY, AMAZON_ASS_TAG, AMAZON_SECRET_KEY);
				# Set the Amazon product display XHTML array to a variable.

				if(isset($asins))
				{
					# Set the Amazon product display XHTML array to a variable.
					$display_amazon=$amazon_obj->displayAmazonProduct($asins, $paginator->getFirstParamName(), 'manage-products', array('image_size'=>'MediumImage', 'max_char'=>50));
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
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
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
						$edit_content='<a href="'.ADMIN_URL.'ManageContent/products/?product='.$product_id.'" class="button-edit" title="Edit '.$product_title.'">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageContent/products/?product='.$product_id.'&amp;delete" class="button-delete" title="Delete '.$product_title.'">Delete</a>';
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

	/*
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
			require_once Utility::locateFile(MODULES.'Document'.DS.'File.php');
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

	/*
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

	/*
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

	/*
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
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
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

	/*
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
			require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
			# Instantiate a new Category object.
			$category_obj=new Category();
			# Check if all categories are requested.
			if(strtolower($categories)!=='all')
			{
				# Create the WHERE portion of the SQL statement for the categories requested.
				$category_obj->createWhereSQL($categories, 'category');
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

	/*
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisProduct

	/*
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

	/*
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

	/*
	 * createAuthorMarkup
	 *
	 * Creates and returns HTML markup for the "author" of a product.
	 *
	 * @param		$author					The author of the product. A String.
	 * @access	protected
	 */
	protected function createAuthorMarkup($author, $author_options)
	{
		# Check if the author is available to display.
		if(!empty($author))
		{
			# Check if the author should be wrapped in an anchor tag with the value of author_link as the href.
			if($author_options['author_link']!==NULL)
			{
				# Check if the link title attribute should be the default.
				if($author_options['title_attribute']=='default')
				{
					$author_options['title_attribute']='More on '.htmlentities($author, ENT_QUOTES, 'UTF-8', FALSE);
				}
				# Open the anchor tag.
				$author_content='<a href="'.$author_options['author_link'].'" class="'.$author_options['author_class'].'" title="'.$author_options['title_attribute'].'">%{insert_content}</a>';
			}
			else
			{
				# Open the span tag.
				$author_content='<span class="'.$author_options['author_class'].'">%{insert_content}</span>';
			}
			# Check if the label should be displayed.
			if($author_options['author_label']!=='')
			{
				# Reset the label display HTML.
				$author_options['author_label']='<span class="'.$author_options['author_label_class'].'">'.$author_options['author_label'].'</span>';
			}
			# Put the author inside its container.
			$author_content=str_replace('%{insert_content}', $author_options['author_label'].$author, $author_content);
			# Return the markup.
			return $author_content;
		}
		return NULL;
	} #==== End -- createAuthorMarkup

	/*
	 * createImageMarkup
	 *
	 * Creates and returns HTML markup for the "image" of a product.
	 *
	 * @param		$image					The image URL of the product. A String.
	 * @param		$image_options	The markup options. An array.
	 * @access	protected
	 */
	protected function createImageMarkup($image_obj, $image_options)
	{
		# Check if the image is available to display.
		if(!empty($image_obj))
		{
			# Create a variable to hold the product image display HTML.
			$image_content=$image_obj->displayImage(TRUE, NULL, NULL, $image_options['image_link']);
			# Return the markup.
			return $image_content;
		}
		return NULL;
	} #==== End -- createImageMarkup

	/*
	 * createPriceMarkup
	 *
	 * Creates and returns HTML markup for the "price" of a product.
	 *
	 * @param		$price					The price of the product. A String.
	 * @param		$price_options	The markup options. An array.
	 * @access	protected
	 */
	protected function createPriceMarkup($price, $price_options, $add_currency_symbol=TRUE)
	{
		# Check if the price is available to display.
		if(!empty($price))
		{
			# Append the type of currency to the end of the price.
			$price=$price.' '.$price_options['currency'];

			# Run a switch through the various currencies to get the correct symbol. See this site for more http://www.currencysymbols.in/
			switch($price_options['currency'])
			{
				case 'AUD':
				case 'CAD':
				case 'MXN':
				case 'USD':
					$currency_symbol='$';
					break;
				case 'GBP':
					$currency_symbol='&pound;';
					break;
				case 'EUR':
					$currency_symbol='&euro;';
					break;
				case 'JPY':
					$currency_symbol='&yen;';
					break;
				default:
					$currency_symbol='';
					break;
			}

			# Prepend the currency symbol to the price.
			$price=(($add_currency_symbol===TRUE) ? $currency_symbol : '').$price;

			# Check if the price should be wrapped in an anchor tag.
			if($price_options['price_link']!==NULL)
			{
				# Check if the price link should be the default detail page.
				if($price_options['price_link']=='default')
				{
					$price_options['price_link']=urldecode($price_options['detailed_page_url']);
				}
				# Check if the title attribute for the link should be the default title.
				if($price_options['title_attribute']=='default')
				{
					$price_options['title_attribute']='More about '.htmlentities($price_options['product_title'], ENT_QUOTES, 'UTF-8', FALSE);
				}
				# Open the anchor tag using the value from price_link as the href.
				$price_content='<a href="'.$price_options['price_link'].'" class="'.$price_options['price_class'].'" title="'.$price_options['title_attribute'].'">%{insert_content}</a>';
			}
			else
			{
				# Open the span tag.
				$price_content='<span class="'.$price_options['price_class'].'">%{insert_content}</span>';
			}
			# Check if the label should be displayed.
			if($price_options['price_label']!=='')
			{
				# Set the label display HTML.
				$price_options['price_label']='<span class="'.$price_options['price_label_class'].'">'.$price_options['price_label'].'</span>';
			}
			# Put the price inside its container.
			$price_content=str_replace('%{insert_content}', $price_options['price_label'].$price, $price_content);
			# Return the markup.
			return $price_content;
		}
		return NULL;
	} #==== End -- createPriceMarkup

	/*
	 * createPublisherMarkup
	 *
	 * Creates and returns HTML markup for the "publisher" of a product.
	 *
	 * @param		$publisher					The publisher of the product. A String.
	 * @param		$publisher_options	The markup options. An array.
	 * @access	protected
	 */
	protected function createPublisherMarkup($publisher, $publisher_options)
	{
		# Check if the publisher is available to display.
		if(!empty($publisher))
		{
			# Check if the publisher should be wrapped in an anchor tag.
			if($publisher_options['publisher_link']!==NULL)
			{
				# Check if the publisher link should be the default detail page.
				if($publisher_options['publisher_link']=='default')
				{
					$publisher_options['publisher_link']=APPLICATION_URL.'profile/?publisher='.$publisher;
				}
				# Check if the title attribute for the link should be the default title.
				if($publisher_options['title_attribute']=='default')
				{
					$publisher_options['title_attribute']='More about '.htmlentities($publisher, ENT_QUOTES, 'UTF-8', FALSE);
				}
				# Open the anchor tag using the value from publisher_link as the href.
				$publisher_content='<a href="'.$publisher_options['publisher_link'].'" class="'.$publisher_options['publisher_class'].'" title="'.$publisher_options['title_attribute'].'">%{insert_content}</a>';
			}
			else
			{
				# Open the span tag.
				$publisher_content='<span class="'.$publisher_options['publisher_class'].'">%{insert_content}</span>';
			}
			# Check if the label should be displayed.
			if($publisher_options['publisher_label']!=='')
			{
				# Set the title display HTML.
				$publisher_options['publisher_label']='<span class="'.$publisher_options['publisher_label_class'].'">'.$publisher_options['publisher_label'].'</span>';
			}
			# Put the publisher inside its container.
			$publisher_content=str_replace('%{insert_content}', $publisher_options['publisher_label'].$publisher, $publisher_content);
			# Return the markup.
			return $publisher_content;
		}
		return NULL;
	} #==== End -- createPublisherMarkup

	/*
	 * createTitleMarkup
	 *
	 * Creates and returns HTML markup for the "title" of a product.
	 *
	 * @param		$title					The title of the product. A String.
	 * @param		$title_options	The markup options. An array.
	 * @access	protected
	 */
	protected function createTitleMarkup($title, $title_options)
	{
		# Check if the title is available to display.
		if(!empty($title))
		{
			# Check if the title should be wrapped in an anchor tag.
			if($title_options['title_link']!==NULL)
			{
				# Check if the title link should be the default detail page.
				if($title_options['title_link']=='default')
				{
					$title_options['title_link']=$title_options['detailed_page_url'];
				}
				# Check if the title attribute for the link should be the default title.
				if($title_options['title_attribute']=='default')
				{
					$title_options['title_attribute']=htmlentities($title, ENT_QUOTES, 'UTF-8', FALSE);
				}
				# Open the anchor tag using the value from title_link as the href.
				$title_content='<a href="'.$title_options['title_link'].'" class="'.$title_options['title_class'].'" title="'.$title_options['title_attribute'].'">%{insert_content}</a>';
			}
			else
			{
				# Open the span tag.
				$title_content='<span class="'.$title_options['title_class'].'">%{insert_content}</span>';
			}
			# Check if the label should be displayed.
			if($title_options['title_label']!=='')
			{
				# Set the title display HTML.
				$title_options['title_label']='<span class="'.$title_options['title_label_class'].'">'.$title_options['title_label'].'</span>';
			}
			# Put the title inside its container.
			$title_content=str_replace('%{insert_content}', $title_options['title_label'].$title, $title_content);
			# Return the markup.
			return $title_content;
		}
		return NULL;
	} #==== End -- createTitleMarkup

	/*
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