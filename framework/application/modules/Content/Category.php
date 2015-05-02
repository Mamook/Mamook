<?php /* framework/application/modules/Content/Category.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Category
 *
 * The Category Class is used access and maintain the `categories` table in the database.
 *
 */
class Category
{
	/*** data members ***/

	private $all_categories=NULL;
	private $id=NULL;
	private $api=NULL;
	private $category=NULL;
	private $is_private=NULL;
	private $is_product=NULL;
	private $where_sql=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllCategories
	 *
	 * Sets the data member $all_categories.
	 *
	 * @param		$categories (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllCategories($categories)
	{
		# Check if the passed value is empty.
		if(!empty($categories))
		{
			# Explicitly make it an array.
			$categories=(array)$categories;
			# Set the data member.
			$this->all_categories=$categories;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_categories=NULL;
		}
	} #==== End -- setAllCategories

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
				throw new Exception('The passed category id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param		$api
	 * @access	protected
	 */
	protected function setAPI($api)
	{
		# Check if the passed value is empty.
		if(!empty($api))
		{
			# Strip slashes and decode any html entities.
			$api=html_entity_decode(stripslashes($api), ENT_COMPAT, 'UTF-8');
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
	 * setCategory
	 *
	 * Sets the data member $category.
	 *
	 * @param		$category
	 * @access	protected
	 */
	protected function setCategory($category)
	{
		# Check if the passed value is empty.
		if(!empty($category))
		{
			# Strip slashes and decode any html entities.
			$category=html_entity_decode(stripslashes($category), ENT_COMPAT, 'UTF-8');
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
	 * setPrivate
	 *
	 * Sets the data member $is_private.
	 * 0 indicates private, NULL inidcates public.
	 *
	 * @param		$private
	 * @access	protected
	 */
	protected function setPrivate($private)
	{
		# Check if the passed value is 0.
		if(($private===0) OR ($private==='0'))
		{
			# Set the data member.
			$this->is_private=$private;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->is_private=NULL;
		}
	} #==== End -- setPrivate

	/**
	 * setProduct
	 *
	 * Sets the data member $is_product.
	 * 0 indicates a product, NULL inidcates NOT a product.
	 *
	 * @param		$product
	 * @access	protected
	 */
	protected function setProduct($product)
	{
		# Check if the passed value is 0.
		if(($product===0) OR ($product==='0'))
		{
			# Set the data member.
			$this->is_product=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->product=NULL;
		}
	} #==== End -- setProduct

	/**
	 * setWhereSQL
	 *
	 * Sets the data member $where_sql.
	 *
	 * @param		$where_sql
	 * @access	protected
	 */
	protected function setWhereSQL($where_sql)
	{
		# Check if the passed value is empty.
		if(!empty($where_sql))
		{
			# Set the data member.
			$this->where_sql=$where_sql;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->where_sql=NULL;
		}
	} #==== End -- setWhereSQL

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllCategories
	 *
	 * Returns the data member $all_categories.
	 *
	 * @access	public
	 */
	public function getAllCategories()
	{
		return $this->all_categories;
	} #==== End -- getAllCategories

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
	 * getPrivate
	 *
	 * Returns the data member $private.
	 *
	 * @access	public
	 */
	public function getPrivate()
	{
		return $this->is_private;
	} #==== End -- getPrivate

	/**
	 * getProduct
	 *
	 * Returns the data member $is_product.
	 *
	 * @access	public
	 */
	public function getProduct()
	{
		return $this->is_product;
	} #==== End -- getProduct

	/**
	 * getWhereSQL
	 *
	 * Returns the data member $where_sql.
	 *
	 * @access	public
	 */
	public function getWhereSQL()
	{
		return $this->where_sql;
	} #==== End -- getWhereSQL

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * createWhereSQL
	 *
	 * Explodes a dash sepparated list of categories and formats them for the WHERE portion of an sql query.
	 *
	 * @param	$categories				The names and/or id's of the category(ies) to be retrieved - may be multiple categories - separate with a dash, ie. '50-70-Archive-110'.
	 *										Use a "!" to designate Categories NOT to be returned, ie. '50-!70-Archive-110')
	 * @access	protected
	 */
	public function createWhereSQL($categories=NULL, $field_name='category')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value was empty.
		if(!empty($categories))
		{
			# Check if $categories equals "all".
			if(strtolower($categories)=='all')
			{
				# Chenage $categories into an empty array.
				$categories=array();
				# Get all the catagories.
				$this->getCategories();
				# Set the results to a variable.
				$results=$this->getAllCategories();
				# Loop through the categories.
				foreach($results as $row)
				{
					# Set the category id to the $categories array.
					$categories[]=$row->id;
				}
			}
			else
			{
				# Trim any dashes(-) off the ends of the string.
				$categories=trim($categories, '-');
				# Create an array of the categories.
				$categories=explode('-', $categories);
			}
			# Create an empty array to hold the "OR" sql strings.
			$category_or=array();
			# Create an empty array to hold the "AND" sql strings.
			$category_and=array();
			foreach($categories as $category)
			{
				# Clean it up.
				$category=trim($category);
				# Get the first character of the string.
				$top=substr($category, 0, 1);
				# Check if the first character was an "!".
				if($top=='!')
				{
					# Remove the "!" from the front of the string.
					$category=ltrim($category, '!');
				}
				# Check if $category is not an integer.
				if($validator->isInt($category)!==TRUE)
				{
					# Get the category data that cooresponds to the passed $category name.
					$this->getThisCategory($category, FALSE);
					$category=$this->getID();
				}
				# Check if the first character was an "!".
				if($top!='!')
				{
					# Set the newly created sql string to the $category_or array.
					$category_or[]='`'.$field_name.'` REGEXP '.$db->quote('-'.$category.'-');
				}
				else
				{
					# Set the newly created sql string to the $category_and array.
					$category_and[]='`'.$field_name.'` NOT REGEXP '.$db->quote('-'.$category.'-');
				}
			}
			# Implode the $category_or array into one complete sql string.
			$ors=implode(' OR ', $category_or);
			# Implode the $category_and array into one complete sql string.
			$ands=implode(' AND ', $category_and);
			# Concatenate the $ands and $ors together.
			$categories=(((!empty($ors)) ? '('.$ors.')' : '').((!empty($ors) && !empty($ands)) ? ' AND ' : '').((!empty($ands)) ? '('.$ands.')' : ''));
		}
		else
		{
			# Explicitly set categories to NULL.
			$categories=NULL;
		}
		# Check if the $category_a array is empty.
		if(!empty($categories))
		{
			# Set the sql string to the data member.
			$this->setWhereSQL($categories);
		}
	} #==== End -- createWhereSQL

	/**
	 * getCategories
	 *
	 * Retrieves records from the `categories` table.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getCategories($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `languages` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'categories`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllCategories($records);
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
	} #==== End -- getCategories

	/**
	 * getThisCategory
	 *
	 * Retrieves category info from the `categories` table in the Database for the passed id or category name and sets it to the data member.
	 *
	 * @param		String	$value 	(The name or id of the category to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisCategory($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set a variable with the field to search for the passed $value.
				$field='id';
				# Set the category id to the data member "cleaning" it.
				$this->setID($value);
				# Get the category id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set a variable with the field to search for the passed $value.
				$field='category';
				# Set the category name to the data member.
				$this->setCategory($value);
				# Get the category name and reset it to the variable.
				$value=$this->getCategory();
			}
			# Get the category info from the Database.
			$category=$db->get_row('SELECT `id`, `category`, `api`, `private` FROM `'.DBPREFIX.'categories` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($category!==NULL)
			{
				# Set the category id to the data member "cleaning" it.
				$this->setID($category->id);
				# Set the category name to the data member.
				$this->setAPI($category->api);
				# Set the returned API value to a local variable.
				$api=json_decode($this->getAPI());
				# Set the category name to the data member.
				$this->setCategory($category->category);
				# Set the category name to the data member.
				$this->setPrivate($category->private);
				# Check if this category also represnets product.
				if(($api!==NULL) && !empty($api->site_product))
				{
					# Set the category name to the data member.
					$this->setProduct($category->product);
				}
				return TRUE;
			}
			# Return FALSE because the category wasn't in the table.
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
	} #==== End -- getThisCategory

	/*** End public methods ***/

} # End Category class.