<?php /* public/store/books/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'store/books/index.php');
	/*
	** In settings we
	 ** define application settings
	 ** define system settings
	 ** start a new session
	 ** connect to the Database
	 */
	require_once '../../../settings.php';
	# Get the PageNavigator Class.
	require_once MODULES.'PageNavigator'.DS.'PageNavigator.php';
	# Get the Product Class.
	require_once MODULES.'Product'.DS.'Product.php';

	# Check if the transaction was cancelled.
	if(isset($_GET['cancel']) && ($_GET['cancel']=='yes'))
	{
		# Set the Document error data member with a message to display to the user.
		$doc->setError('Your transaction has been cancelled.');
	}

	# Check if the transaction was completed.
	if(isset($_GET['thankyou']) && ($_GET['thankyou']=='yes'))
	{
		# Set the Document error data member with a message to display to the user.
		$doc->setError('Thank you for your support! Your product has been added to your account.');
	}

	# Set "Books" as the page's sub title.
	$main_content->setSubTitle('Books');

	# Create a new Product object
	$product=new Product();

	# Create an empty variable to hold the XHTML.
	$display='';

	# Create a variable to hold the "AND" portion of the sql statement.
	$and_sql='';
	$identifier='';

	$params='';
	$detailed=FALSE;

	if(strtoupper($_SERVER['REQUEST_METHOD'])==='GET')
	{
		# Check if there is GET data.
		if(isset($_GET['product']))
		{
			# Get the Product content.
			$product->getProducts('Books', 1, '*', 'title', 'ASC', '`id` = '.$db->quote($_GET['product']));
			# Display the Product content.
			$displayed_products=$product->displayProduct('product', $product->getTitle(), 'MediumImage');
			# Check if there is a product to display.
			if(!empty($displayed_products))
			{
				$detailed=TRUE;
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
				$params='product='.$_GET['product'];
			}
			else
			{
				# Set a message session to be displayed after redirect.
				$_SESSION['message']='I couldn\'t locate that product.';
				# Redirect back to the maps page.
				$doc->redirect(APPLICATION_URL.HERE);
			}
		}
		else
		{
			if(isset($_GET['sort_by']))
			{
				$sort_by=urldecode($_GET['sort_by']);
				switch($sort_by)
				{
					case 'Recommended Reading':
						$category_id=23;
						break;
					case 'Publications of Interest':
						$category_id=24;
						break;
					default:
						$sort_by='Top Picks';
						$category_id=22;
						break;
				}
				$identifier='.'.$sort_by;
				# Add the "sort by" to the page's sub title.
				$main_content->setSubTitle($main_content->getSubTitle().' - '.$sort_by);
				# Create the "AND" portion of the sql statement that requires the category id for "Top Picks".
				$and_sql='(`category` REGEXP \'-'.$category_id.'-\')';
				$params='sort_by='.$sort_by;
			}
		}
	}

	# Check if this is a detailed product page.
	if($detailed===FALSE)
	{
		# Set the total number of returned products to a variable.
		$total_products=$product->countAllRecords('Books', NULL, $and_sql);
		# Check if there were products returned.
		if(!empty($total_products))
		{
			# Create a new PageNavigator object.
			$paginator=new PageNavigator(8, 4, CURRENT_PAGE, 'page', $total_products, $params);
			$paginator->setStrFirst('');
			$paginator->setStrLast('');
			$paginator->setStrNext('Next Page');
			$paginator->setStrPrevious('Previous Page');

			# Get the Product content.
			$product->getProducts('Books', $paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', 'title', 'ASC', $and_sql);

			# Display the Product content.
			$displayed_products=$product->displayProduct($paginator->getFirstParamName(), ltrim(rtrim(WebUtility::removeIndex(HERE), '/'), 'store/').$identifier, 'MediumImage', 50);
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
		else
		{
			# Set a message session to be displayed after redirect.
			$display='Nothing to display in this category.';
		}
	}

	# Get the books navigation.
	require TEMPLATES.'books_nav.php';

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
	require TEMPLATES.'page.php';
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.