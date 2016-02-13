<?php /* framework/application/templates/forms/publisher_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'publisher_form_defaults.php');
$display_delete_form=$fp->processPublisher($default_data);

# Set the PublisherFormPopulator object from the PublisherFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Publisher object from the PublisherFormPopulator data member to a variable.
$publisher=$populator->getPublisherObject();

$select=TRUE;

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Set the default sub title of the page to "Add New Publisher".
	$sub_title='Add New Publisher';
	# Set the publisher's name to a local variable.
	$publisher_name=$publisher->getPublisher();
	# Check if this is an edit or delete page.
	if(isset($_GET['publisher']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$publisher_name.'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$publisher_name.'</span>';
		}
	}
	# Set the sub title.
	$main_content->setSubTitle($sub_title);

	$display.='<div id="publisher_form" class="form">';

	# create and display form.
	$display.=$head;

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('publisher', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_contributor', 'value'=>(string)$publisher->getContID()));
	$fg->addElement('hidden', array('name'=>'_date', 'value'=>(string)$publisher->getDate()));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="name"><span class="required">*</span> Name</label>');
	$fg->addElement('text', array('name'=>'name', 'id'=>'name', 'value'=>$publisher->getPublisher()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="description">Publisher Info</label>');
	$fg->addElement('textarea', array('name'=>'info', 'id'=>'info', 'text'=>$publisher->getInfo()), '', NULL, 'textarea tinymce');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Submit';
	# Check if this is an edit page.
	if(isset($_GET['publisher']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'publisher', 'value'=>$button_value), '', NULL, 'submit-post');
	# Check if this is an edit page.
	if(isset($_GET['publisher']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/publishers/?publisher='.$publisher->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'publisher', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'publisher', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	# Create a new Publisher object.
	$publisher=New publisher();
	$display.=$publisher->displayPublisherList();
}
$display=$display_delete_form.$display;