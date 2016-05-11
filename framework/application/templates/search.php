<?php /* framework/application/templates/search.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the SearchFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'SearchFormProcessor.php');
# Instantiate a new SearchFormProcessor object.
$search_form_processor=new SearchFormProcessor();

# Get the search form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'search_form.php');

echo '<section id="searchbox">'.
	$display_search_form.
'</section>';