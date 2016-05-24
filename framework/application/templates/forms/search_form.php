<?php /* framework/application/templates/forms/search_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'search_form_defaults.php');
# Process the search form.
$search_results=$search_form_processor->processSearch($default_data);
# Set the SearchFormPopulator object to a variable.
$populator=$search_form_processor->getPopulator();
# Set the Search object created in SearchFormPopulator to a variable.
$search_obj=$populator->getSearchObject();

# Set the search type to a variable.
$search_type=$search_obj->getSearchType();

$branch_id='';
if(isset($branch) && !is_object($branch))
{
	# Get the Branch class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
	# Instantiate a new Branch object.
	$branch_obj=new Branch();
	$branch_obj->getThisBranch($branch, FALSE);
	$branch_id=$branch_obj->getID();
}

# Creeate the search form.
$display_search_form='<div id="search_form" class="form">';
# instantiate form generator object
$fg=new FormGenerator('search', $search_form_processor->getFormAction(), 'POST', '_top', TRUE);
# Loops through the tables to search in.
foreach($search_type as $type)
{
	# Create hidden field for the search type.
	# NOTE: This will be a multiple selection dropdown box in advanced options.
	$fg->addElement('hidden', array('name'=>'_type[]', 'value'=>$type));
}
# Create hidden field for the search type.
# NOTE: This will be a multiple selection dropdown box in advanced search options.
if(!empty($branch_id))
{
	$fg->addElement('hidden', array('name'=>'branch', 'value'=>$branch_id));
}
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<ul>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="searchterms">'.(!isset($search_label) ? 'Search Terms' : $search_label).'</label>');
$fg->addElement('text', array('name'=>'searchterms', 'id'=>'searchterms', 'value'=>$search_obj->getSearchTerms()));
$fg->addElement('submit', array('name'=>'search', 'value'=>'Search'), NULL, NULL, 'submit-search');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$fg->addFormPart('</fieldset>');
$display_search_form.=$fg->display();
$display_search_form.='</div>';