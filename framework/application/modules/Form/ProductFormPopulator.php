<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * ProductFormPopulator
 *
 * The ProductFormPopulator Class is used populate product forms.
 *
 */
class ProductFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $product_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setProductObject
	 *
	 * Sets the data member $product_object.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setProductObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->product_object=$object;
	} #==== End -- setProductObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getProductObject
	 *
	 * Returns the data member $product_object.
	 *
	 * @access	public
	 */
	public function getProductObject()
	{
		return $this->product_object;
	} #==== End -- getProductObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateProductForm
	 *
	 * Populates a product form with the default data passed in, which is in turn overwritten by session data, which
	 * in turn is overwritten by POST data.
	 *
	 * @access	public
	 */
	public function populateProductForm($data=array())
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
			# Instantiate a new Publisher object.
			$product_obj=new Product();
			# Set the Product object to the data member.
			$this->setProductObject($product_obj);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any publisher data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('product');

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getProductObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateProductForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (PublisherFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['product']) && (($_POST['product']=='Add Product') OR ($_POST['product']=='Update'))))
			{
				# Set the data array to a local variable.
				$data=$this->getData();

				# Check if ASIN POST data was sent.
				if(isset($_POST['asin']))
				{
					# Set the ASIN to the Product data member.
					$data['ASIN']=$_POST['asin'];
				}

				# Check if author POST data was sent.
				if(isset($_POST['author']))
				{
					# Set the author to the Product data member.
					$data['Author']=$_POST['author'];
				}

				# Check if ASIN POST data was sent.
				if(isset($_POST['button_id']))
				{
					# Set the ASIN to the Product data member.
					$data['ButtonID']=$_POST['button_id'];
				}

				# Check if the Product category was passed via POST data.
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
					# Set the Image categories data member.
					$data['Categories']=$_POST['category'];
				}

				# Check if content POST data was sent.
				if(isset($_POST['content']))
				{
					# Set the content to the Product data member.
					$data['Content']=$_POST['content'];
				}

				# Check if currency POST data was sent.
				if(isset($_POST['currency']))
				{
					# Set the currency to the Product data member.
					$data['Currency']=$_POST['currency'];
				}

				# Check if description POST data was sent.
				if(isset($_POST['description']))
				{
					# Set the description to the Product data member.
					$data['Description']=$_POST['description'];
				}

				# Check if file POST data was sent.
				if(isset($_POST['file_option']) && !empty($_POST['file_option']))
				{
					# Set the file option ("add", "remove", or "select") to the Content data member.
					$this->setFileOption($_POST['file_option']);
				}

				# Check if image POST data was sent.
				if(isset($_POST['image_option']) && !empty($_POST['image_option']))
				{
					# Set the image option ("add", "remove", or "select") to the Content data member.
					$this->setImageOption($_POST['image_option']);
				}

				# Check if price POST data was sent.
				if(isset($_POST['price']))
				{
					# Set the price to the Product data member.
					$data['Price']=$_POST['price'];
				}

				# Check for the product type.
				if(isset($_POST['product-type']))
				{
					# Set the Product Type to the data array.
					$data['ProductType']=$_POST['product-type'];
				}

				# Check if publisher POST data was sent.
				if(isset($_POST['publisher']))
				{

					if($_POST['publisher']=='add')
					{
						# Set the publisher option to the Populator data member.
						$this->setPublisherOption($_POST['publisher']);
					}
					# Set the publisher Type to the Product data array.
					$data['Publisher']=$_POST['publisher'];
				}

				# Check if purchase link POST data was sent.
				if(isset($_POST['purchase_link']))
				{
					# Set the purchase link to the Product data member.
					$data['PurchaseLink']=$_POST['purchase_link'];
				}

				# Check if sort_by POST data was sent.
				if(isset($_POST['sort_by']))
				{
					# Set the sort_by to the Product data member.
					$data['SortBy']=$_POST['sort_by'];
				}

				# Check if title POST data was sent.
				if(isset($_POST['title']))
				{
					# Set the title to the Product data member.
					$data['Title']=$_POST['title'];
				}

				# Check if the unique POST data was sent.
				if(isset($_POST['_unique']))
				{
					$unique=1;
					if(empty($_POST['_unique']))
					{
						$unique=0;
					}
					# Set the unique value to the data member.
					$this->setUnique($unique);
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

} # End ProductFormPopulator class.