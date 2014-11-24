<?php /* public/search/index.php */

ob_start(); # Begin output buffering

# Increase the allowed PHP execution time for large searches. (300 seconds = 5 minutes)
ini_set('max_execution_time', 300);

try
{
	# Define the location of this page.
	define('HERE_PATH', 'search/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the Search Class.
	require_once MODULES.'Search'.DS.'Search.php';

	# Create display variables.
	$display_main1='';
	$display_main2='';
	$display_main3='';
	$display_box1a='';
	$display_box1b='';
	$display_box1c='';
	$display_box2='';

	$search=new Search();

	$head='<h3>Use the form below to search the site.</h3>';

	# Creeate the search form.
	$display='<div id="search_form" class="form">';
	$display.=$head;
	# instantiate form generator object
	$search_form=new FormGenerator('general_search', WebUtility::removeIndex(APPLICATION_URL.HERE));
	//$search_form=new FormGenerator('general_search', WebUtility::removeIndex(WP_SITEURL), 'get');
	$search_form->addFormPart('<fieldset>');
	$search_form->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$search_form->addFormPart('<ul>');
	$search_form->addFormPart('<li>');
	$search_form->addFormPart('<label class="label" for="searchterms">Search Terms</label>');
	//$search_form->addFormPart('<label class="label" for="s">Search Terms</label>');
	$search_form->addElement('text', array('name'=>'searchterms', 'value'=>((isset($_POST['searchterms'])) ? $db->sanitize($_POST['searchterms']) : '')));
	//$search_form->addElement('text', array('name'=>'s', 'value'=>((isset($_POST['searchterms'])) ? $db->sanitize($_POST['searchterms']) : '')));
	$search_form->addFormPart('</li>');
	$search_form->addElement('submit', array('name'=>'send', 'value'=>'Search', 'id'=>'go'), NULL, NULL, 'submit-search');
	//$search_form->addElement('submit', array('name'=>'', 'value'=>'', 'id'=>'go'), NULL, NULL, 'submit-search');
	$search_form->addFormPart('</li>');
	$search_form->addFormPart('</ul>');
	$search_form->addFormPart('</fieldset>');
	$display.=$search_form->display();
	$display.='</div>';

	$subcontent_fields=array('title', 'text', 'text_trans');
	//$display_search.=$search->displayPageResults(array('page_title', 'sub_title', 'content'), $and_sql='`sub_domain` = '.$db->quote($db->escape('NULL')), $order='page', $direction='DESC', $limit=NULL);

	$search_count='';
	if(array_key_exists('_submit_check', $_POST))
	{
	}

	# Get the page title and subtitle to display in main-1.
	$display_main1=$main_content->displayTitles();

	# Get the main content to display in main-2. The "image_link" variable is defined in data/init.php.
	$display_main2=$main_content->displayContent($image_link);
	# Add any display content to main-2.
	$display_main2.=$display;

	# Get the quote text to display in main-3.
	$display_main3=$main_content->displayQuote();

	/*
	** In the page template we
	** get the header
	** get the masthead
	** get the subnavbar
	** get the navbar
	** get the page view
	** get the quick registration box
	** get the footer
	*/
	require TEMPLATES.'page.php';
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.