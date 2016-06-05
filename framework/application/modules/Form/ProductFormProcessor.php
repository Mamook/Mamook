<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormValidator.php');

# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');


/**
 * ProductFormProcessor
 *
 * The ProductFormProcessor Class is used to create and process publsher forms.
 *
 */
class ProductFormProcessor extends FormProcessor
{
	/*** public methods ***/

	/**
	 * processProduct
	 *
	 * Processes a submitted product for upload, edit, or deletion.
	 *
	 * @param	$data					An array of values tp populate the form with.
	 * @access	public
	 */
	public function processProduct($data=array())
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Bring the content instance into scope.
			$main_content=Content::getInstance();
			# Get the ProductFormPopulator Class.
			require_once Utility::locateFile(MODULES.'Form'.DS.'ProductFormPopulator.php');

			# Remove any un-needed CMS session data.
			# This needs to happen before populateProductsForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.
			$this->loseSessionData('product');

			# Reset the form if the "reset" button was submitted.
			$this->processReset('product', 'product');

			# Instantiate a new instance of ProductFormPopulator.
			$populator=new ProductFormPopulator();
			# Populate the form and set the Product data members for this product.
			$populator->populateProductForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);

			$display_delete_form=$this->processProductDelete();
			if($display_delete_form!==FALSE)
			{
				return $display_delete_form;
			}
			$this->processProductBack();
			$this->processProductSelect();

			# Get the Product object from the ProductFormPopulator and set it to a local variable.
			$product_obj=$populator->getProductObject();

			# Set the product's ASIN to a variable.
			$asin=$product_obj->getASIN();
			# Set the product's author to a variable.
			$author=$product_obj->getAuthor();
			# Set the product's Paypal Button ID to a variable.
			$button_id=$product_obj->getButtonID();
			# Set the product's categories to a variable.
			$categories=$product_obj->getCategories();
			# Create an empty variable for the category id's.
			$category_ids=NULL;
			# Check if there are categories.
			if(!empty($categories))
			{
				# Exchange the values for the id's.
				$categories=array_flip($categories);
				# Separate the category id's with dashes (-).
				$category_ids='-'.implode('-', $categories).'-';
			}
			# Set the product's content to a variable.
			$content=$product_obj->getContent();
			# Set the product's currency to a variable.
			$currency=$product_obj->getCurrency();
			# Set the product's description to a variable.
			$description=$product_obj->getDescription();
			# Set the product's file id to a variable.
			$file_id=$product_obj->getFileID();
			# Set the product's id to a variable.
			$id=$product_obj->getID();
			# Set the product's image id to a variable.
			$image_id=$product_obj->getImageID();
			# Set the product's price to a variable.
			$price=$product_obj->getPrice();
			# Get the product type.
			$product_type=$product_obj->getProductType();
			# Set the product's publisher id to a variable.
			$publisher_id=$product_obj->getPublisher();
			# Set the product's purchase link to a variable.
			$purchase_link=$product_obj->getPurchaseLink();
			# Set the site name to a variable.
			$site_name=$main_content->getSiteName();
			# Set the product's sort by to a variable.
			$sort_by=$product_obj->getSortBy();
			# Set the product's title to a variable.
			$title=$product_obj->getTitle();
			# Set the product's unique status to a variable.
			$unique=$populator->getUnique();

			# Check if the form has been submitted and the submit button was the "Submit" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['product']) && ($_POST['product']==='Add Product' OR $_POST['product']==='Update')))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('product');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the title field was empty (or less than 2 characters or more than 255 characters long).
				$empty_name=$fv->validateEmpty('title', 'Please enter a name for the product.', 2, 255);

				# Check for errors to display so that the script won't go further.
				if($fv->checkErrors()===TRUE)
				{
					# Create a variable to the error heading.
					$alert_title='Resubmit the form after correcting the following errors:';
					# Concatenate the errors to the heading.
					$error=$fv->displayErrors();
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError($error);
				}
				else
				{
					# Check if the product data is considered "unique" or not.
					if($unique!=1)
					{
						# Get the Search class.
						require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');
						# Make an array of fields to search in the products table in the Database.
						$fields=array('id', 'title');
						# Instantiate a new Search object.
						$search_obj=new Search();
						# Make an array of the terms to search for (enclose multiple word strings in double quotes.)
						$terms=$title;
						# Don't compare with the video ID.
						$filter=array('filter_fields'=>array('id'));
						# Check if the id is empty.
						if(!empty($id))
						{
							# Create a search filter that won't return the current record we may be editing.
							$filter=array_merge($filter, array('filter_sql'=>'`id` != '.$db->quote($id)));
						}
						# Search for duplicate records.
						$search_obj->setAllResults($search_obj->performSearch($terms, 'products', $fields, NULL, $filter));
						# Set any search results to a variable.
						$duplicates=$search_obj->getAllResults();
						# Create an empty array for the duplicate display.
						$dup_display=array();
						# Check if there were records returned.
						if(!empty($duplicates))
						{
							# Loop through the duplicates.
							foreach($duplicates as $duplicate)
							{
								# Instantiate a new Product object.
								$dup_product=new Product();
								# Get the info for this record.
								$dup_product->getThisProduct($duplicate->id);
								# Set the record fields to the dup_display array.
								$dup_display[$dup_product->getID()]=array(
									'author'=>$dup_product->getAuthor(),
									'categories'=>$dup_product->getCategories(),
									'content'=>$dup_product->getContent(),
									'description'=>$dup_product->getDescription(),
									'id'=>$dup_product->getID(),
									'title'=>$dup_product->getTitle()
								);
							}
							# Explicitly set unique to 0 (not unique).
							$populator->setUnique(0);
						}
						else
						{
							# Explicitly set unique to 1 (unique).
							$populator->setUnique(1);
						}
						$unique=$populator->getUnique();
						# Set the duplicates to display to the data member for retrieval outside of the method.
						$this->setDuplicates($dup_display);
					}
					# Check if the product is considered unique and may be added to the Database.
					if($unique==1)
					{
						# Reset the values in the database for the text fields not being used.
						if($product_type=='amazon')
						{
							$button_id=NULL;
							$purchase_link=NULL;
						}
						elseif($product_type=='external')
						{
							$asin=NULL;
							$button_id=NULL;
						}
						elseif($product_type=='internal')
						{
							$asin=NULL;
							$purchase_link=NULL;
						}

						# Create the default value for the message action.
						$message_action='added';
						# Create the default sql as an INSERT and set it to a variable.
						$sql='INSERT INTO `'.DBPREFIX.'products` ('.
							(($product_type=='amazon' && !empty($asin)) ? '`ASIN`,' : '').
							((!empty($author)) ? ' `author`,' : '').
							(($product_type=='internal' && !empty($button_id)) ? ' `button_id`,' : '').
							((!empty($category_ids)) ? ' `category`,' : '').
							((!empty($content)) ? ' `content`,' : '').
							' `currency`,'.
							((!empty($description)) ? ' `description`,' : '').
							((!empty($file_id)) ? ' `file`,' : '').
							((!empty($image_id)) ? ' `image`,' : '').
							((!empty($price)) ? ' `price`,' : '').
							((!empty($publisher_id)) ? ' `publisher`,' : '').
							(($product_type=='external' && !empty($purchase_link)) ? ' `purchase_link`,' : '').
							' `title`'.
							') VALUES ('.
							(($product_type=='amazon' && !empty($asin)) ? $db->quote($db->escape($asin)).',' : '').
							((!empty($author)) ? ' '.$db->quote($db->escape($author)).',' : '').
							(($product_type=='internal' && !empty($button_id)) ? $db->quote($db->escape($button_id)).',' : '').
							((!empty($category_ids)) ? ' '.$db->quote($category_ids).',' : '').
							((!empty($content)) ? ' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $content))).',' : '').
							$db->quote($db->escape($currency)).','.
							((!empty($description)) ? ' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $description))).',' : '').
							((!empty($file_id)) ? ' '.$db->quote($file_id).',' : '').
							((!empty($image_id)) ? ' '.$db->quote($image_id).',' : '').
							((!empty($price)) ? ' '.$db->quote($db->escape($price)).',' : '').
							((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : '').
							(($product_type=='external' && !empty($purchase_link)) ? ' '.$db->quote($purchase_link).',' : '').
							' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).
							')';
						# Check if this is an UPDATE. If there is an ID, it's an UPDATE.
						if(!empty($id))
						{
							# Reset the value for the message action.
							$message_action='updated';
							# Reset the sql variable with the UPDATE sql.
							$sql='UPDATE `'.DBPREFIX.'products` SET '.
								' `asin` = '.$db->quote($db->escape($asin)).','.
								' `author` = '.((!empty($author)) ? ' '.$db->quote($author).',' : 'NULL,').
								((!empty($category_ids)) ? ' `category` = '.$db->quote($category_ids).',' : '').
								'`button_id` = '.$db->quote($db->escape($button_id)).','.
								' `content` = '.((!empty($content)) ? ' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $content))).',' : 'NULL,').
								' `currency` = '.$db->quote($db->escape($currency)).', '.
								' `description` = '.((!empty($description)) ? ' '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $description))).',' : 'NULL,').
								((!empty($file_id)) ? ' `file` = '.$db->quote($file_id).',' : '').
								((!empty($image_id)) ? ' `image` = '.$db->quote($image_id).',' : '').
								' `price` = '.((!empty($price)) ? ' '.$db->quote($price).',' : 'NULL,').
								' `publisher` = '.((!empty($publisher_id)) ? ' '.$db->quote($publisher_id).',' : 'NULL,').
								' `purchase_link` = '.$db->quote($purchase_link).','.
								' `title` = '.$db->quote($db->escape(str_ireplace(array(DOMAIN_NAME), array('%{domain_name}'), $title))).
								' WHERE `id` = '.$db->quote($id).
								' LIMIT 1';
						}
						# Run the sql query.
						$db_post=$db->query($sql);
						# Check if the database query was successful.
						if($db_post>0)
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['product']);
							$this->redirectProduct($title, $message_action);
						}
						elseif(!empty($id))
						{
							# Unset the CMS session data.
							unset($_SESSION['form']['product']);
							# Set a nice message for the user in a session.
							$_SESSION['message']="The product's record was unchanged.";
							# Redirect the user to the page they were on.
							$this->redirectNoDelete();
						}
					}
				}
			}
			return NULL;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error posting to the `products` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processProduct

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processProductBack
	 *
	 * Processes a submitted form indicating that the User should be sent back to the form that sent them to fetch a product.
	 *
	 * @access	private
	 */
	private function processProductBack()
	{
		try
		{
			# Create an array of possible indexes. These are forms that can send the user to get an institution.
			$indexes=array(
				'audio',
				'file',
				'post',
				'product',
				'video'
			);
			# Set the resource value.
			$resource='product';
			$this->processBack($resource, $indexes);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processProductBack

	/**
	 * processProductDelete
	 *
	 * Removes a product from the `product` table and the system. A wrapper method for the deleteProduct method in the Product class.
	 *
	 * @access	private
	 */
	private function processProductDelete()
	{
		try
		{
			# Bring the Login object into scope.
			global $login;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Explicitly set the delete variable to FALSE; the POST will NOT be deleted.
			$delete=FALSE;
			$access=TRUE;
			# Check if the product's id was passed via GET data and that GET data indicates this is a delete.
			if(isset($_GET['product']) && isset($_GET['delete']))
			{
				# Check if the passed product id is an integer.
				if($validator->isInt($_GET['product'])===TRUE)
				{
					# Check if the form has been submitted.
					if(array_key_exists('_submit_check', $_POST) && isset($_POST['do']) && (isset($_POST['delete_product']) && ($_POST['delete_product']==='delete')))
					{
						# Get the Product class.
						require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');
						# Instantiate a new Product object.
						$product_obj=new Product();
						# Get the info for this product and set the return boolean to a variable.
						$record_retrieved=$product_obj->getThisProduct($_GET['product']);
						# Check if the record was actually returned.
						if($record_retrieved===TRUE)
						{
							# Set the product id to a local variable.
							$product_id=$product_obj->getID();
							# Set the product name to a local variable.
							$product_name=$product_obj->getTitle();
							# Get rid of any product form sessions.
							unset($_SESSION['form']['product']);
							# Delete the product from the database and set the returned value to a variable.
							$deleted=$product_obj->deleteProduct($product_id, FALSE);
							if($deleted===TRUE)
							{
								$this->redirectProduct($product_name, 'deleted');
							}
							else
							{
								# Set a nice message to the session.
								$_SESSION['message']='The product "'.$product_name.'" (id: '.$product_id.') was NOT deleted from the product list. Please contact <a href="'.APPLICATION_URL.'webSupport/">webSupport</a> to have this publisher removed.';
								# Redirect the user back to the page.
								$this->redirectNoDelete();
							}
						}
						else
						{
							# Set a nice message to the session.
							$_SESSION['message']='The product was not found.';
							# Redirect the user back to the page without GET or POST data.
							$this->redirectNoDelete('product');
						}
					}
					# Check if the form has been submitted to NOT delete the publisher.
					elseif(array_key_exists('_submit_check', $_POST) && (isset($_POST['do_not']) OR (isset($_POST['delete_product']) && ($_POST['delete_product']==='keep'))))
					{
						# Set a nice message to the session.
						$_SESSION['message']='The product was NOT deleted.';
						# Redirect the user back to the page.
						$this->redirectNoDelete();
					}
					else
					{
						# Create a delete form for this product and request confirmation from the user with the appropriate warnings.
						require Utility::locateFile(TEMPLATES.'forms'.DS.'delete_form.php');
						return $display;
					}
				}
				# Redirect the user to the default redirect location. They have no business trying to pass a non-integer as an id!
				$doc->redirect(DEFAULT_REDIRECT);
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processProductDelete

	/**
	 * processProductSelect
	 *
	 * Processes a submitted form selecting a product to add to a post.
	 *
	 * @access	private
	 * @return	string
	 */
	private function processProductSelect()
	{
		# Bring the alert-title variable into scope.
		global $alert_title;
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# Check if this is a product select page.
		if(isset($_GET['select']))
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && $_POST['submit']=='Select Product')
			{
				# Check if the product id POST data was sent.
				if(isset($_POST['product_info']))
				{
					# Get the Populator object and set it to a local variable.
					$populator_obj=$this->getPopulator();
					# Get the Product object and set it to a local variable.
					$product_obj=$populator_obj->getProductObject();
					$colon_pos=strpos($_POST['product_info'], ':');
					$product_id=substr($_POST['product_info'], 0, $colon_pos);
					$product_title=substr($_POST['product_info'], $colon_pos+1);
					# Set the product id to the Product data member.
					$product_obj->setID($product_id);
					# Set the product name to the Product data member.
					$product_obj->setTitle($product_title);
					# Set the product's id to a variable.
					$product_id=$product_obj->getID();
					# Set the product's title to a variable.
					$product_title=$product_obj->getTitle();
					# Redirect the User back to the form that sent them to fetch a product.
					$this->redirectProduct($product_title, 'selected');
				}
				else
				{
					# Set the error message to the Document object datamember so that it may be displayed on the page.
					$doc->setError('Please select a product.');
					# Redirect the user to the page they were on with no POST or GET data.
					$doc->redirect(COMPLETE_URL);
				}
			}
		}
	} #==== End -- processProductSelect

	/**
	 * redirectProduct
	 *
	 * Redirect the user to the appropriate page if their post data indicates that another form sent the User
	 * to this form to aquire a product.
	 *
	 * @access	private
	 */
	private function redirectProduct($product_name, $action, $default_message=TRUE)
	{
		try
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the Populator object and set it to a local variable.
			$populator_obj=$this->getPopulator();
			# Get the Product object and set it to a local variable.
			$product_obj=$populator_obj->getProductObject();
			# Get the data for the new product.
			$product_obj->getThisProduct($product_name, FALSE);
			# Get the new product's id.
			$product_id=$product_obj->getID();
			# Remove the product session.
			unset($_SESSION['form']['product']);
			# Check if the default message should be sent.
			if($default_message===TRUE)
			{
				# Set a nice message for the user in a session.
				$_SESSION['message']='The product "'.$product_name.'" was successfully '.$action.'!';
			}
			else
			{
				# Set the passed custom message.
				$_SESSION['message']=$action;
			}
			$remove=NULL;
			if(isset($_GET['delete']) && $action=='deleted')
			{
				$remove='product';
			}
			# Redirect the user to the page they were on.
			$this->redirectNoDelete($remove);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- redirectProduct

	/**
	 * setSession
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access	private
	 */
	private function setSession()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator_obj=$this->getPopulator();
			# Get the Product object and set it to a local variable.
			$product_obj=$populator_obj->getProductObject();

			# Set the form URL's to a variable.
			$form_url=$populator_obj->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['product']=
				array(
					'ASIN'=>$product_obj->getASIN(),
					'Author'=>$product_obj->getAuthor(),
					'Categories'=>$product_obj->getCategories(),
					'Content'=>$product_obj->getContent(),
					'Currency'=>$product_obj->getCurrency(),
					'Description'=>$product_obj->getDescription(),
					'FileID'=>$product_obj->getFileID(),
					'FormURL'=>$form_url,
					'ID'=>$product_obj->getID(),
					'ImageID'=>$product_obj->getImageID(),
					'Price'=>$product_obj->getPrice(),
					'PublisherID'=>$product_obj->getPublisher(),
					'PurchaseLink'=>$product_obj->getPurchaseLink(),
					'SortBy'=>$product_obj->getSortBy(),
					'Title'=>$product_obj->getTitle(),
					'ProductType'=>$product_obj->getProductType(),
					'Unique'=>$populator_obj->getUnique()
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End ProductFormProcessor class.