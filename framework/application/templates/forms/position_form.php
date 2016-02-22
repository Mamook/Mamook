<?php /* framework/application/templates/forms/position_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'position_form_defaults.php');
$display_delete_form=$fp->processPosition($default_data);

# Set the PositionFormPopulator object from the PositionFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Position object from the PositionFormPopulator data member to a variable.
$position_obj=$populator->getPositionObject();

$select=TRUE;

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Set the default sub title of the page to "Add New Position".
	$sub_title='Add New Position';
	# Set the position's name to a local variable.
	$position_position=$position_obj->getPosition();
	# Check if this is an edit or delete page.
	if(isset($_GET['position_obj']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$position_position.'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$position_position.'</span>';
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
	$fg=new FormGenerator('position', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$position_unique));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="position"><span class="required">*</span> Position</label>');
	$fg->addElement('text', array('name'=>'position', 'id'=>'position', 'value'=>$position_position));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="description">Position Description</label>');
	$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'text'=>$position_description), '', NULL, 'textarea tinymce');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Submit';
	# Check if this is an edit page.
	if(isset($_GET['position']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'submit', 'value'=>$button_value), '', NULL, 'submit-post');
	# Check if this is an edit page.
	if(isset($_GET['position']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/positions/?position='.$position_id.'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'submit', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'submit', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	$display.=$position_obj->displayPositionList();
}
$display=$display_delete_form.$display;