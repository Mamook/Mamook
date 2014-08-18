<?php /* public/secure/MyAccount/profile.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/MyAccount/profile.php');
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
	require_once MODULES.'Form'.DS.'AccountFormProcessor.php';

	$login->checkLogin(ALL_USERS);

	# Instantiate a new User object.
	$user_obj=new User();

	# Create and empty variable to hold the view $display variable.
	$display='';
	# Create a variable to hold the User's username and set it to NULL.
	$username=NULL;
	# Get the logged in User's ID.
	$id=$user_obj->findUserID();
	$head='<h3>Please use the form below to update your personal information!</h3>';

	# Set the User data members.
	$user_obj->findUserData($username);
	# Instantiate a new AccountFormProcessor object.
	$form_processor=new AccountFormProcessor();
	# Get the User's display name and set it to a variable.
	$display_name=$user_obj->getDisplayName();
	# Set the page title.
	$page_title=$display_name.'\'s Profile';

	# Get the profile form template.
	require TEMPLATES.'forms'.DS.'account_form.php';

	# Set the default style sheet(s) we are using for the site. (must be absolute location)
	//$doc->setStyle(THEME.'css/secure.css');
	# Do we need some javascripts? (Use the script file name before the ".js".)
	$doc->setJavaScripts('uniform');
	# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
	$doc->setFooterJS('uniform-file');

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