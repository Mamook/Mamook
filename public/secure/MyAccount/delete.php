<?php /* public/secure/MyAccount/delete.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/MyAccount/delete.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the FormProcessor Class.
	require_once MODULES.'Form'.DS.'FormProcessor.php';

	# Check if the User is logged in.
	$login->checkLogin(ALL_USERS);

	# Instantiate a new User object.
	$user=new User();

	# Instantiate a new FormProcessor object.
	$form_processor=new FormProcessor();
	# Process the delete form if it has been submitted.
	$form_processor->processDeleteAccount();

	# Set the default style sheet(s) we are using for the site. (must be absolute location)
	//$doc->setStyle(THEME.'css/secure.css');
	# Do we need some javascripts? (Use the script file name before the ".js".)
	//$doc->setJavaScript('mainMenus');

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