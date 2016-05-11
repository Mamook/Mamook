<?php /* framework/application/templates/forms/search_form_defaults.php */

$search_branch=NULL;
$search_terms=NULL;
$search_type=(isset($search_type) ? $search_type : array('all'));

# The key MUST be the name of a "set" mutator method in the Search class (ie setSearchTerms).
$default_data=array(
	'SearchBranch'=>$search_branch,
	'SearchTerms'=>$search_terms,
	'SearchType'=>$search_type
);