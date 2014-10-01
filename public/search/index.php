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
	require_once MODULES.'Search'.DS.'CustomSearch.php';
	$search=new CustomSearch();

	$head='<h3>Use the form below to search the <a href="',WP_SITEURL,'" title="The FWE, CWIS\'s online blog.">Forth World Eye</a></h3>';

	# Creeate the search form.
	$display='<div id="search_form">';
	$display.=$head;
	# instantiate form generator object
	//$search_form=new FormGenerator('general_search', WebUtility::removeIndex(APPLICATION_URL.HERE));
	$search_form=new FormGenerator('general_search', WebUtility::removeIndex(WP_SITEURL), 'get');
	$search_form->addFormPart('<fieldset>');
	//$search_form->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$search_form->addFormPart('<ul>');
	$search_form->addFormPart('<li>');
	//$search_form->addFormPart('<label class="label" for="searchterms">Search Terms</label>');
	$search_form->addFormPart('<label class="label" for="s">Search Terms</label>');
	//$search_form->addElement('text', array('name'=>'searchterms', 'value'=>((isset($_POST['searchterms'])) ? $db->sanitize($_POST['searchterms']) : '')));
	$search_form->addElement('text', array('name'=>'s', 'value'=>((isset($_POST['searchterms'])) ? $db->sanitize($_POST['searchterms']) : '')));
	$search_form->addFormPart('</li>');
	//$search_form->addElement('submit', array('name'=>'send', 'value'=>'Search', 'id'=>'go'), NULL, NULL, 'submit-search');
	$search_form->addElement('submit', array('name'=>'', 'value'=>'', 'id'=>'go'), NULL, NULL, 'submit-search');
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