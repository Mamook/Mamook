<?php /* templates/forms/search_form_defaults.php */

$search_terms=NULL;
$search_type=(isset($search_type) ? $search_type : array('all'));

# The key MUST be the name of a "set" mutator method in the Search class (ie setSearchTerms).
$default_data=array(
	'SearchTerms'=>$search_terms,
	'SearchType'=>$search_type
	);