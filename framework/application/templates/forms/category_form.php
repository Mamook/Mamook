<?php /* framework/application/templates/forms/category_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'category_form_defaults.php');
$display_delete_form=$fp->processCategory($default_data);

# Set the CategoryFormPopulator object from the CategoryFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Category object from the CategoryFormPopulator data member to a variable.
$category=$populator->getCategoryObject();

$select=TRUE;

$descriptive='category';
if(isset($is_playlist_form) && ($is_playlist_form===TRUE))
{
	$descriptive='playlist';
}

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Set the default sub title of the page to "Add New Category".
	$sub_title='Add New '.ucfirst($descriptive);
	# Set the category's name to a local variable.
	$category_name=$category->getName();
	# Check if this is an edit or delete page.
	if(isset($_GET['category']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$category_name.'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$category_name.'</span>';
		}
	}
	# Set the sub title.
	$main_content->setSubTitle($sub_title);

	$display.='<div id="category_form" class="form">';

	# create and display form.
	$display.=$head;

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('category', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="name"><span class="required">*</span> Name</label>');
	$fg->addElement('text', array('name'=>'name', 'id'=>'name', 'value'=>$category->getName()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Submit';
	# Check if this is an edit page.
	if(isset($_GET['category']) OR isset($_GET['playlist']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'category', 'value'=>$button_value), '', NULL, 'submit-post');
	# Check if this is an edit page.
	if(isset($_GET['category']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/categories/?'.$descriptive.'='.$category->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'category', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'category', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	# Create a new Category object.
	$category=New Category();
	$display.=$category->displayCategoryList();
}
$display=$display_delete_form.$display;