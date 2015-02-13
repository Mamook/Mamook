<?php /* public/store/index.php */

# Get the PageNavigator Class.
require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
# Get the Product Class.
require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

# Create an empty variable to hold the XHTML.
$display='';
$page_class='store';

# Create a new Product object
$product_obj=new Product();

# Check if there is GET data.
if(isset($_GET['product']))
{
	# Get the Product content.
	$product_obj->getProducts('Books-Maps-Music', 1, '*', 'title', 'ASC', ' `id` = '.$db->quote($_GET['product']));
	# Display the Product content.
	$displayed_products=$product_obj->displayProduct('product', $product_obj->getTitle(), array('image_size'=>'MediumImage'));
	foreach($displayed_products as $displayed_product)
	{
		# Add the XHTML to the display variable.
		$display.='<div class="product detailed">';
		$display.=$displayed_product['buy'];
		$display.=$displayed_product['price'];
		$display.=$displayed_product['image'];
		$display.='<div class="info">';
		$display.=$displayed_product['author'];
		$display.=$displayed_product['publisher'];
		$display.='</div>';
		$display.=$displayed_product['description'];
		$display.=$displayed_product['content'];
		$display.=$displayed_product['editorial_review'];
		$display.='</div>';
	}
	# Set the product's title as the page title.
	$page_title=$displayed_product['title'];
	if(!empty($page_title))
	{
		$main_content->setPageTitle($page_title);
	}
}
else
{
	# Create the "AND" portion of the sql statement that requires the category id for "Top Picks".
	$and_sql='(`category` REGEXP \'-22-\')';

	# Set "Top Picks" as the page's sub title.
	$main_content->setSubTitle('Top Picks');

	# Create a new PageNavigator object.
	$paginator=new PageNavigator(8, 4, CURRENT_PAGE, 'page', $product_obj->countAllRecords('Books-Maps-Music-Top Picks', NULL, $and_sql));
	$paginator->setStrFirst('');
	$paginator->setStrLast('');
	$paginator->setStrNext('Next Page');
	$paginator->setStrPrevious('Previous Page');

	# Get the Product content.
	$product_obj->getProducts('Books-Maps-Music-Top Picks', $paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', 'title', 'ASC', $and_sql);

	# Display the Product content.
	$displayed_products=$product_obj->displayProduct($paginator->getFirstParamName(), rtrim(WebUtility::removeIndex(HERE), '/').'.TopPicks', array('image_size'=>'MediumImage', 'max_char'=>50));
	if(!empty($displayed_products))
	{
		foreach($displayed_products as $displayed_product)
		{
			# Add the XHTML to the display variable.
			$display.='<div class="product">';
			$display.=$displayed_product['image'];
			$display.='<div class="info">';
			$display.=$displayed_product['title'];
			if(empty($displayed_product['author']))
			{
				$display.=$displayed_product['publisher'];
			}
			else
			{
				$display.=$displayed_product['author'];
			}
			$display.=$displayed_product['price'];
			$display.='</div>';
			$display.=$displayed_product['buy'];
			$display.='</div>';
		}

		# Add the pagenavigator to the display variable.
		$display.=$paginator->getNavigator();
	}
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Do we need some more CSS?
$doc->setStyle(THEME.'css/store.css');

/*
 ** In the page template we
 ** get the header
 ** get the masthead
 ** get the subnavbar
 ** get the navbar
 ** get the page view
 ** get the quick registration box
 ** get the footer
 */
require Utility::locateFile(TEMPLATES.'page.php');