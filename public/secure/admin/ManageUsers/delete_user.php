<?php /* public/secure/admin/ManageUsers/delete_user.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageUsers/delete_user.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the FormProcessor Class.
	require_once MODULES.'Form'.DS.'FormProcessor.php';

	# Check if the User is logged in.
	$login->checkLogin(ADMIN_USERS);

	if(isset($_GET['user']))
	{
		# Instantiate a new User object.
		$user=new User();
		# Set the User ID to the data member; effectively cleaning it.
		$user->setID($_GET['user']);
		# Set the data member to a variable.
		$id=$user->getID();
		# Instantiate a new FormProcessor object.
		$form_processor=new FormProcessor();
		# Process the delete form if it has been submitted.
		$form_processor->processDeleteAccount();
		$current_username=$user->findUsername($id);
	}
	else
	{
		$doc->redirect(ADMIN_URL.'ManageUsers/');
	}

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