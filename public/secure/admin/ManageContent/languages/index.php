<?php /* public/secure/admin/ManageContent/languages/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageContent/languages/index.php');
	/*
	** In settings we
	 ** define application settings
	 ** define system settings
	 ** start a new session
	 ** connect to the Database
	 */
	require_once '../../../../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the LanguageFormProcessor Class.
	require_once MODULES.'Form'.DS.'LanguageFormProcessor.php';

	$login->checkLogin(ALL_BRANCH_USERS);

	$login->findUserData();

	$display='';
	$general_edit_head='';
	$head='';
	$post_edit_head='';

	$fp=new LanguageFormProcessor();

	# Get the form template.
	require(TEMPLATES.'forms'.DS.'language_form.php');

	//require TEMPLATES.'forms'.DS.'search_form.php';

	# Set the default style sheet(s) we are using for the site. (must be absolute location)
	//$doc->setStyle(THEME.'css/secure.css');

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