<?php /* framework/application/templates/forms/image_form.php */

# Get the image form defaults
require Utility::locateFile(TEMPLATES.'forms'.DS.'image_form_defaults.php');

$display_delete_form=$form_processor->processImage($default_data);

# Set the ImageFormPopulator object from the ImageFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Image object from the ImageFormPopulator data member to a variable.
$image_obj=$populator->getImageObject();

$select=TRUE;

if(!isset($_GET['select']))
{
	$head=(!isset($head) ? '' : $head);
	$select=FALSE;

	$duplicates=$form_processor->getDuplicates();
	if(empty($duplicates))
	{
		# Do we need some javascripts? (Use the script image name before the ".js".)
		$doc->setJavaScripts('uniform,bsmSelect');
		# Add JavaScripts to the footer. (Use the script file name before the ".php".)
		# This form needs uniform-file, bsmSelect-multiple, and uniform-select. uniform-select MUST come after bsmSelect-multiple.
		$doc->setFooterJS('uniform-file,bsmSelect-multiple,uniform-select');

		# Set the image name to a variable.
		$image_name=$image_obj->getImage();

		# Check if this is an edit or delete page.
		if(isset($_GET['image']))
		{
			# Set the page's subtitle as an edit page.
			$sub_title='Images - Edit <span>'.$image_name.'</span>';
			# Check if this is a delete page.
			if(isset($_GET['delete']))
			{
				# Set the page's subtitle as a delete page.
				$sub_title='Images - Delete <span>'.$image_name.'</span>';
			}
			# Set the sub title.
			$main_content->setSubTitle($sub_title);
		}

		$display.='<div id="image_form" class="form">';

		# Create and display form.
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
		$image_categories=array_flip((array)$image_obj->getCategories());
		$category_options[]='';
		# Loop through the categories.
		foreach($categories as $category_name=>$category_id)
		{
			# Create an option for each category.
			$category_options[$category_id]=$category_name;
			# Check if this image currently has a category.
			if(!empty($image_categories))
			{
				# Check if the current category is default or has been selected by the user.
				if(in_array($category_id, $image_categories, TRUE)===TRUE)
				{
					# Set the selected category to the default.
					$category_options['selected']=$category_name;
				}
				elseif(
						(in_array('add', $image_categories)===TRUE) &&
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

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('image', $form_processor->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
		$fg->addElement('hidden', array('name'=>'_contributor', 'value'=>$image_obj->getContID()));
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
		$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$image_obj->getTitle()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="image"><span class="required">*</span> Image</label>');
		$fg->addElement('file', array('name'=>'image', 'id'=>'image'));
		if(!empty($image_name))
		{
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="file-current">');
			$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" alt="'.$image_obj->getTitle().'" /><span>'.$image_name.' - "'.$image_obj->getTitle().'"</span></a>');
			$fg->addElement('hidden', array('name'=>'_image', 'value'=>$image_name));
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="image_width"><span class="required">*</span> Dimensions</label>');
		$fg->addElement('text', array('name'=>'width', 'id'=>'image_width', 'value'=>$image_obj->getWidth())).$fg->addFormPart('px ');
		$fg->addElement('text', array('name'=>'height', 'id'=>'image_height', 'value'=>$image_obj->getHeight())).$fg->addFormPart('px');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="description">Description</label>');
		$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'text'=>$image_obj->getDescription()), '', NULL, 'textarea tinymce');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="location">Location</label>');
		$fg->addElement('text', array('name'=>'location', 'id'=>'location', 'value'=>$image_obj->getLocation()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="category">Category</label>');
		$fg->addElement('select', array('name'=>'category[]', 'id'=>'category'), $category_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$button_value='Add Image';
		# Check if this is an edit page.
		if(isset($_GET['image']))
		{
			$button_value='Update';
		}
		$fg->addElement('submit', array('name'=>'image', 'value'=>$button_value), '', NULL, 'submit-image');
		# Check if this is an edit page.
		if(isset($_GET['image']) && !isset($_GET['delete']))
		{
			$fg->addFormPart('<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image_obj->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
		}
		$fg->addElement('submit', array('name'=>'image', 'value'=>'Reset'), '', NULL, 'submit-reset');
		if(isset($_GET['add']))
		{
			$fg->addElement('submit', array('name'=>'image', 'value'=>'Go Back'), '', NULL, 'submit-back');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</fieldset>');
		$display.=$fg->display();
		$display.='</div>';
	}
}
if(isset($_GET['image']))
{
	# Display pages using this image. Acceptable parameter is 'audio', 'file', 'image', or 'video'.
	$display.=$image_obj->displayMediaUsage('image');
}
# Display the images in the `images` table.
$display.=$image_obj->displayImageList($select);
$display=$display_delete_form.$display;