<?php /* templates/forms/image_form.php */

require TEMPLATES.'forms'.DS.'image_form_defaults.php';
$display_delete_form=$form_processor->processImage($default_data);

# Set the ImageFormPopulator object from the ImageFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Image object from the ImageFormPopulator data member to a variable.
$image=$populator->getImageObject();

$select=TRUE;

if(!isset($_GET['select']))
{
	$select=FALSE;

	$duplicates=$form_processor->getDuplicates();
	if(empty($duplicates))
	{
		# Do we need some javascripts? (Use the script image name before the ".js".)
		$doc->setJavaScripts('uniform,bsmSelect');
		# Do we need some JavaScripts in the footer? (Use the script image name before the ".php".)
		$doc->setFooterJS('uniform-select,uniform-file,bsmSelect-multiple');

		# Set the image name to a variable.
		$image_name=$image->getImage();

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

		# create and display form.
		$display.=$head;

		# Add the statement about requirements.
		$display.='<span class="required">* = required field</span>';

		# Get the Category class.
		require_once MODULES.'Content'.DS.'Category.php';
		# Instantiate a new Category object.
		$category=new Category();
		# get the categories from the `categories` table.
		$category->getCategories(NULL, '`id`, `category`', 'category', 'ASC', ' WHERE `category` != \'Subscription\' AND `category` != \'Donation\' AND `category` != \'Music\'');
		# Set the categories to a variable.
		$categories=$category->getAllCategories();
		# Create the "Add Category" option.
		//$cat_options['add']='Add Category';
		# Set the current categories to a variable.
		$image_categories=array_flip((array)$image->getCategories());

		# Loop through the categories.
		foreach($categories as $row)
		{
			# Create an option for each category.
			$cat_options[$row->id]=$row->category;
			# Check if this image currently has a category.
			if(!empty($image_categories))
			{
				# Check if the current category is default or has been selected by the user.
				if(in_array($row->id, $image_categories)===TRUE)
				{
					# Set the selected category to the default.
					$cat_options['multiple_selected'][$row->id]=$row->category;
				}
				elseif(
						(in_array('add', $image_categories)===TRUE) &&
						(
							isset($cat_options['multiple_selected']) &&
							in_array('Add Category', $cat_options['multiple_selected']!==TRUE)
						)
					)
				{
					# Set the "Add Category" option as selected.
					$cat_options['multiple_selected']['add']='Add Category';
				}
			}
		}

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('image', $form_processor->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
		$fg->addElement('hidden', array('name'=>'_contributor', 'value'=>$image->getContID()));
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
		$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$image->getTitle()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="image"><span class="required">*</span> Image</label>');
		$fg->addElement('file', array('name'=>'image', 'id'=>'image'));
		if(!empty($image_name))
		{
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="file-current">');
			$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="lightbox"><img src="'.IMAGES.$image_name.'" alt="'.$image->getTitle().'" /><span>'.$image_name.' - "'.$image->getTitle().'"</span></a>');
			$fg->addElement('hidden', array('name'=>'_image', 'value'=>$image_name));
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="image_width"><span class="required">*</span> Dimensions</label>');
		$fg->addElement('text', array('name'=>'image_width', 'id'=>'image_width', 'value'=>$image->getWidth())).$fg->addFormPart('px ');
		$fg->addElement('text', array('name'=>'image_height', 'id'=>'image_height', 'value'=>$image->getHeight())).$fg->addFormPart('px');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="description">Description</label>');
		$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'wrap'=>'physical', 'text'=>$image->getDescription()), '', NULL, 'textarea tinymce');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="location">Location</label>');
		$fg->addElement('text', array('name'=>'location', 'id'=>'location', 'value'=>$image->getLocation()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li class="mult">');
		$fg->addFormPart('<label class="label" for="category"><span class="required">*</span> Category</label>');
		$fg->addElement('select', array('name'=>'category[]', 'multiple'=>'multiple', 'title'=>'Select a Catagory', 'id'=>'category'), $cat_options);
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
			$fg->addFormPart('<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
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

$display.=$image->displayImageList('all', $select);

$display=$display_delete_form.$display;