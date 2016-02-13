<?php /* framework/application/templates/forms/language_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'language_form_defaults.php');
$display_delete_form=$fp->processLanguage($default_data);

# Set the LanguageFormPopulator object from the LanguageFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Language object from the LanguageFormPopulator data member to a variable.
$language=$populator->getLanguageObject();

$select=TRUE;

$duplicates=$fp->getDuplicates();
if(empty($duplicates))
{
	# Set the default sub title of the page to "Add New Language".
	$sub_title='Add New Language';
	# Set the language's name to a local variable.
	$language_name=$language->getLanguage();
	# Check if this is an edit or delete page.
	if(isset($_GET['language']))
	{
		# Set the page's subtitle as an edit page.
		$sub_title='Edit <span>'.$language_name.'</span>';
		# Check if this is a delete page.
		if(isset($_GET['delete']))
		{
			# Set the page's subtitle as a delete page.
			$sub_title='Delete <span>'.$language_name.'</span>';
		}
	}
	# Set the sub title.
	$main_content->setSubTitle($sub_title);

	$display.='<div id="language_form" class="form">';

	# create and display form.
	$display.=$head;

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('language', $fp->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="language"><span class="required">*</span> Language</label>');
	$fg->addElement('text', array('name'=>'language_name', 'id'=>'language', 'value'=>$language->getLanguage()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="iso">ISO</label>');
	$fg->addElement('text', array('name'=>'iso', 'id'=>'iso', 'value'=>$language->getISO()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Submit';
	# Check if this is an edit page.
	if(isset($_GET['language']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'language', 'value'=>$button_value), '', NULL, 'submit-post');
	# Check if this is an edit page.
	if(isset($_GET['language']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageContent/languages/?language='.$language->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'language', 'value'=>'Reset'), '', NULL, 'submit-reset');
	if(isset($_GET['add']))
	{
		$fg->addElement('submit', array('name'=>'language', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	# Create a new Language object.
	$language=New language();
	$display.=$language->displayLanguageList();
}
$display=$display_delete_form.$display;