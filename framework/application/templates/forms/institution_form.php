<?php /* framework/application/templates/forms/institution_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'institution_form_defaults.php');
$display_delete_form=$fp->processInstitution($default_data);

# Set the InstitutionFormPopulator object from the InstitutionFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Institution object from the InstitutionFormPopulator data member to a variable.
$institution=$populator->getInstitutionObject();

$select=TRUE;

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Set the default sub title of the page to "Add New Institution".
	$sub_title='Add New Institution';
	# Set the institution's name to a local variable.
	$institution_name=$institution->getInstitution();
	# Check if this is an edit or delete page.
	if(isset($_GET['institution']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$institution_name.'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$institution_name.'</span>';
		}
	}
	# Set the sub title.
	$main_content->setSubTitle($sub_title);

	$display.='<div id="institution_form" class="form">';

	# create and display form.
	$display.=$head;

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('institution', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="name"><span class="required">*</span> Name</label>');
	$fg->addElement('text', array('name'=>'name', 'id'=>'name', 'value'=>$institution->getInstitution()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Submit';
	# Check if this is an edit page.
	if(isset($_GET['institution']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'institution', 'value'=>$button_value), '', NULL, 'submit-post');
	# Check if this is an edit page.
	if(isset($_GET['institution']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/institutions/?institution='.$institution->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'institution', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'institution', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	# Create a new Institution object.
	$institution=New institution();
	$display.=$institution->displayInstitutionList();
}
$display=$display_delete_form.$display;