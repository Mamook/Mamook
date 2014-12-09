<?php /* templates/forms/search_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'search_form_defaults.php');
# Process the search form.
$search_results=$search_form_processor->processSearch($default_data);
# Set the SearchFormPopulator object to a variable.
$populator=$search_form_processor->getPopulator();
# Set the Search object created in SearchFormPopulator to a variable.
$search_obj=$populator->getSearchObject();

# Creeate the search form.
$display.='<div id="search_form" class="form">';
$display.=$head;
# instantiate form generator object
$fg=new FormGenerator('search', $search_form_processor->getFormAction(), 'POST', '_top', TRUE);
$fg->addFormPart('<fieldset>');
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<ul>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="searchterms">Search Terms</label>');
$fg->addElement('text', array('name'=>'searchterms', 'id'=>'searchterms', 'value'=>$search_obj->getSearchTerms()));
$fg->addElement('submit', array('name'=>'search', 'value'=>'Search'), NULL, NULL, 'submit-search');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$fg->addFormPart('</fieldset>');
$display.=$fg->display();
$display.='</div>';