<?php /* public/secure/MyAccount/change_username.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/MyAccount/change_username.php');
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

	# Create an empty ID variable.
	$id=NULL;
	$email_username='Email me my username';

	# Instantiate a new User object.
	$user=new User();

	# Check if there is GET data.
	if(isset($_GET['member']))
	{
		# Only allow Admins to do this.
		if($login->checkAccess(ADMIN_USERS)===TRUE)
		{
			# Set the User ID to the data member; effectively cleaning it.
			$user->setID($_GET['member']);
			# Set the data member to a variable.
			$id=$user->getID();
			$email_username='Email the new username';
		}
	}
	# Instantiate a new FormProcessor object.
	$form_processor=new FormProcessor();
	# Process the username form.
	$form_processor->processUsername($id);

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