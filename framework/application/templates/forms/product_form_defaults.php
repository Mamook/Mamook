<?php /* framework/application/templates/forms/product_form_defaults.php */

# Create defaults.
$product_asin=NULL;
$product_author=NULL;
$product_button_id=NULL;
$product_categories=25; # Set the default to "Education" (25)
$product_content=NULL;
$product_currency='USD';
$product_description=NULL;
$product_file_id=NULL;
$product_id=NULL;
$product_image_id=NULL;
$product_price='0.00';
$product_publisher=NULL;
$product_purchase_link=NULL;
$product_sort_by=NULL;
$product_title=NULL;
$product_unique=0; # Set the default to "Not Unique" (0)
$product_product_type='internal';

# Check if there is GET data called "product".
if(isset($_GET['product']))
{
	# Get the Publisher class.
	require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
	# Instantiate a new instance of the Publisher class.
	$product_obj=new Product();
	# Set the passed product ID to the Publisher data member, effectively "cleaning" it.
	$product_obj->setID($_GET['product']);
	# Set the cleaned SubContent id to a local variable.
	$pro_id=$product_obj->getID();
	# Get the product's content from the `product` table.
	if($product_obj->getThisProduct($pro_id)===TRUE)
	{
		# Reset the defaults.
		$product_asin=$product_obj->getASIN();
		$product_author=$product_obj->getAuthor();
		$product_button_id=$product_obj->getButtonID();
		# Get the product's categories and set them to a local variable as a dash (-) separated string of the category id's.
		# Set the categories to a local variable.
		$categories_array=$product_obj->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$product_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key=>$value)
			{
				# Add the category id to the string appended with a dash (-).
				$product_categories.=$key.'-';
			}
		}
		$product_categories=$product_categories;
		$product_content=$product_obj->getContent();
		$product_currency=$product_obj->getCurrency();
		$product_description=$product_obj->getDescription();
		$product_file_id=$product_obj->getFileID();
		$product_id=$pro_id;
		$product_image_id=$product_obj->getImageID();
		$product_price=$product_obj->getPrice();
		$product_publisher=$product_obj->getPublisher();
		$product_purchase_link=$product_obj->getPurchaseLink();
		$product_sort_by=$product_obj->getSortBy();
		$product_title=$product_obj->getTitle();
		if(isset($product_asin))
		{
			$product_product_type='amazon';
		}
		elseif(isset($product_purchase_link))
		{
			$product_product_type='external';
		}
		elseif(isset($product_button_id))
		{
			$product_product_type='internal';
		}
		$product_unique=1; # Set to "Unique" (1) since it is already a product.
	}
}

# The key MUST be the name of a "set" mutator method in either the Product, ProductFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
	'ASIN'=>$product_asin,
	'Author'=>$product_author,
	'ButtonID'=>$product_button_id,
	'Categories'=>$product_categories,
	'Content'=>$product_content,
	'Currency'=>$product_currency,
	'Description'=>$product_description,
	'FileID'=>$product_file_id,
	'ID'=>$product_id,
	'ImageID'=>$product_image_id,
	'Price'=>$product_price,
	'Publisher'=>$product_publisher,
	'PurchaseLink'=>$product_purchase_link,
	'SortBy'=>$product_sort_by,
	'Title'=>$product_title,
	'ProductType'=>$product_product_type,
	'Unique'=>$product_unique
	);