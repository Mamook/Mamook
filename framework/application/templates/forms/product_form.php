<?php /* framework/application/templates/forms/product_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'product_form_defaults.php');
$display_delete_form=$fp->processProduct($default_data);

# Set the ProductFormPopulator object from the ProductFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Product object from the ProductFormPopulator data member to a variable.
$product_obj=$populator->getProductObject();

$select=TRUE;

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Do we need some javascripts? (Use the script file name before the ".js".)
	$doc->setJavaScripts('uniform,bsmSelect');
	# Add JavaScripts to the footer. (Use the script file name before the ".php".)
	# This form needs fileOption-submit, uniform-file, bsmSelect-multiple, uniform-select, and product. uniform-select MUST come after bsmSelect-multiple.
	$doc->setFooterJS('fileOption-submit,uniform-file,bsmSelect-multiple,uniform-select,product');

	# Set the default sub title of the page to "Add New Product".
	$sub_title='Add New Product';
	# Check if this is an edit or delete page.
	if(isset($_GET['product']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$product_obj->getTitle().'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$product_obj->getTitle().'</span>';
		}
	}
	# Set the sub title.
	$main_content->setSubTitle($sub_title);

	$display.='<div id="product_form" class="form">';

	# create and display form.
	$display.=$head;

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Get the Category class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
	# Instantiate a new Category object.
	$category_obj=new Category();
	# get the categories from the `categories` table.
	$category_obj->getCategories(NULL, '`id`, `name`', 'name', 'ASC');
	# Set the categories to a variable.
	$all_categories=$category_obj->getAllCategories();
	# Loop through the categories.
	foreach($all_categories as $row)
	{
		# Create an option for each category.
		$categories[$row->id]=$row->name;
	}
	# Flip the categories.
	$categories=array_flip($categories);
	# Set the current categories to a variable.
	$product_categories=array_flip((array)$product_obj->getCategories());
	$category_options[]='';
	# Loop through the categories.
	foreach($categories as $category_name=>$category_id)
	{
		# Create an option for each category.
		$category_options[$category_id]=$category_name;
		# Check if this product currently has a category.
		if(!empty($product_categories))
		{
			# Check if the current category is default or has been selected by the user.
			if(in_array($category_id, $product_categories, TRUE)===TRUE)
			{
				# Set the selected category to the default.
				$category_options['selected']=$category_name;
			}
			elseif(
					(in_array('add', $product_categories)===TRUE) &&
					(
						isset($category_options['selected']) &&
						in_array('Add Category', $category_options['selected']!==TRUE)
					)
				)
			{
				# Set the "Add Category" option as selected.
				$category_options['selected']['add']='Add Category';
			}
		}
	}

	$file_options[0]='';
	$file_options['select']='Select Existing File (submit this form to select a file from the database)';
	$file_options['add']='Upload File (submit this form to select and upload your file)';
	# Set the file id in the SubContent data member to a variable.
	$file_id=$product_obj->getFileID();
	if(!empty($file_id))
	{
		$file_options['remove']='Remove Current File (submit this form to remove this file)';
	}

	$image_options[0]='';
	$image_options['select']='Select Existing Image (submit this form to select an image from the database)';
	$image_options['add']='Upload Image (submit this form to select and upload your image)';
	# Set the image id in the SubContent data member to a variable.
	$image_id=$product_obj->getImageID();
	if(!empty($image_id))
	{
		$image_options['remove']='Remove Current Image (submit this form to remove this image)';
	}

	# Get the Publisher class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
	# Instantiate a new Publisher object.
	$publisher=new Publisher();
	$publisher->getPublishers(NULL, '`id`, `name`', 'name', 'ASC');
	$publishers=$publisher->getAllPublishers();
	# If there are publisher results.
	if(!empty($publishers))
	{
		$pub_options[0]='';
		$pub_options['add']='Add Publisher';
		foreach($publishers as $row)
		{
			$pub_options[$row->id]=$row->name;
			if($row->id==$product_obj->getPublisher())
			{
				# Set the selected publisher to the default.
				$pub_options['selected']=$row->name;
			}
		}
	}
	else
	{
		$pub_options[]='No Publishers';
	}

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('product', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
	$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$product_obj->getTitle()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="category"><span class="required">*</span> Category</label>');
	$fg->addElement('select', array('name'=>'category[]', 'id'=>'category'), $category_options);
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li class="vis">');
	$fg->addFormPart('<span class="label">Product Type</span>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addElement('radio', array('name'=>'product-type', 'id'=>'product-type-internal', 'value'=>'internal', 'checked'=>$product_obj->getProductType()), NULL, NULL, 'radio product_type_radio');
	$fg->addFormPart('<label class="label-radio" for="product-type-internal">Internal</label>');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('radio', array('name'=>'product-type', 'id'=>'product-type-amazon', 'value'=>'amazon', 'checked'=>$product_obj->getProductType()), NULL, NULL, 'radio product_type_radio');
	$fg->addFormPart('<label class="label-radio" for="product-type-amazon">Amazon</label>');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('radio', array('name'=>'product-type', 'id'=>'product-type-external', 'value'=>'external', 'checked'=>$product_obj->getProductType()), NULL, NULL, 'radio product_type_radio');
	$fg->addFormPart('<label class="label-radio" for="product-type-external">External</label>');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</li>');
	/*
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="sort_by"><span class="required">*</span> Sort By</label>');
	$fg->addElement('select', array('name'=>'sort_by', 'title'=>'Select a Catagory', 'id'=>'sort_by'), $categories);
	$fg->addFormPart('</li>');
	*/
	$fg->addFormPart('<li id="internal">');
	$fg->addFormPart('<label class="label" for="button_id">Paypal Button ID</label>');
	$fg->addElement('text', array('name'=>'button_id', 'id'=>'button_id', 'value'=>$product_obj->getButtonID()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li id="amazon">');
	$fg->addFormPart('<label class="label help" for="asin" title="Amazon Standard Indetification Number">ASIN</label>');
	$fg->addElement('text', array('name'=>'asin', 'id'=>'asin', 'value'=>$product_obj->getASIN()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li id="external">');
	$fg->addFormPart('<label class="label" for="purchase_link">Purchase Link</label>');
	$fg->addElement('text', array('name'=>'purchase_link', 'id'=>'purchase_link', 'value'=>$product_obj->getPurchaseLink()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="price">Price</label>');
	$fg->addElement('text', array('name'=>'price', 'id'=>'price', 'value'=>$product_obj->getPrice()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="currency"><span class="required">*</span> Currency</label>');
	$fg->addElement('text', array('name'=>'currency', 'id'=>'currency', 'value'=>$product_obj->getCurrency()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="fileOption">File</label>');
	$fg->addElement('select', array('name'=>'file_option', 'id'=>'fileOption'), $file_options, NULL, 'select');
	if(!empty($file_id))
	{
		# Get the file info.
		$product_obj->getThisFile($file_id);
		# Set the File object to a variable.
		$file_obj=$product_obj->getFile();
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li class="file-current">');
		$fg->addFormPart('<a href="'.APPLICATION_URL.'download/?f='.$file_obj->getFile().(($file_obj->getPremium()!==NULL) ? '&amp;t=premium' : '').'" title="Current Associated File">'.$file_obj->getFile().' - "'.$file_obj->getTitle().'"</a>');
		$fg->addElement('hidden', array('name'=>'_file_id', 'value'=>$file_id));
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="author">Author</label>');
	$fg->addElement('text', array('name'=>'author', 'id'=>'author', 'value'=>$product_obj->getAuthor()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="publisher">Publisher</label>');
	$fg->addElement('select', array('name'=>'publisher', 'id'=>'publisher'), $pub_options);
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="description">Description</label>');
	$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'text'=>$product_obj->getDescription()), '', NULL, 'textarea tinymce');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="content">Content</label>');
	$fg->addElement('textarea', array('name'=>'content', 'id'=>'content', 'text'=>$product_obj->getContent()), '', NULL, 'textarea tinymce');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="imageOption">Image</label>');
	$fg->addElement('select', array('name'=>'image_option', 'id'=>'imageOption'), $image_options, NULL, 'select');
	if(!empty($image_id))
	{
		# Get the file info.
		$product_obj->getThisImage($image_id);
		# Set the Image object to a variable.
		$image_obj=$product_obj->getImage();
		$image_name=$image_obj->getImage();
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li class="file-current">');
		$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" alt="'.$image_obj->getTitle().'" /><span>'.$image_name.' - "'.$image_obj->getTitle().'"</span></a>');
		$fg->addElement('hidden', array('name'=>'_image_id', 'value'=>$image_id));
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Add Product';
	# Check if this is an edit page.
	if(isset($_GET['product']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'product', 'value'=>$button_value), '', NULL, 'submit-product');
	# Check if this is an edit page.
	if(isset($_GET['product']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/products/?product='.$product_obj->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'product', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'product', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';
	$display.=$product_obj->displayProductList();
}
$display=$display_delete_form.$display;