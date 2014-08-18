<?php /* public/secure/admin/ManageContent/announcement/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageContent/announcement/index.php');
	/*
	** In settings we
	 ** define application settings
	 ** define system settings
	 ** start a new session
	 ** connect to the Database
	 */
	require_once '../../../../../settings.php';
	# Get the SubContent Class.
	require_once MODULES.'Content'.DS.'SubContent.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the PostFormProcessor Class.
	require_once MODULES.'Form'.DS.'PostFormProcessor.php';

	$login->checkLogin(ANNOUNCEMENT_USERS);

	$login->findUserData();

	$fp=new PostFormProcessor();

	$branch_name='Announcement';
	$display='';
	$file_details='';
	$head='';
	$general_edit_head='';
	$post_edit_head='';

	# Get the form template.
	require(TEMPLATES.'forms'.DS.'post_form.php');

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