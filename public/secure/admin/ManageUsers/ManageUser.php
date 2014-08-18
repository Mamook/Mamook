<?php /* public/secure/admin/ManageUsers/ManageUser.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageUsers/ManageUser.php');
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

	$login->checkLogin(MAN_USERS);

	# Instantiate a new User object.
	$user=new User();

	# Create a variable to hold the User's username and set it to NULL.
	$username=NULL;
	# Get the logged in User's ID.
	$id=$user->findUserID();
	$head='Please use the form below to update your personal information!';

	# Create a variable to hold whether or not the login sessions should be reset.
	$reset_login=TRUE;
	# Check if there is GET data.
	if(isset($_GET['member']))
	{
		# Only allow Admins to do this.
		if($login->checkLogin(ADMIN_USERS)===TRUE)
		{
			# Set the User ID to the data member; effectively cleaning it.
			$user->setID($_GET['member']);
			# Set the data member to a variable.
			$id=$user->getID();
			# Find the User's username and set it to a variable.
			$username=$user->findUsername($id);
			# Set the reset login variable to FALSE.
			$reset_login=FALSE;
			$head='Please use the form below to update user: '.$username.'';
		}
	}
	# Set the User data members.
	$user->findUserData($username);
	# Instantiate a new FormProcessor object.
	$form_processor=new FormProcessor();
	# Update the profile if the form has been submitted.
	$form_processor->processProfile();
	# Get the User's display name and set it to a variable.
	$display_name=$user->getDisplayName();
	# Set the page title.
	$page_title=$display_name.'\'s Profile - <a href="'.APPLICATION_URL.'profile/?member='.$id.'" target="_blank">view</a>';
	# Populate the form variables.
	$form_processor->populateProfileForm();

	# Check if the login sessions should be reset.
	if($reset_login===TRUE)
	{
		# Reset the login sessions.
		$login->setLoginSessions($user->getID(), $user->getDisplayName(), $user->getPassword(), $user->getFirstName(), $user->getLastName(), $user->getTitle(), $user->getRegistered(), $user->getLastLogin(), TRUE, $login->checkRemember());
	}

	# Do we need some javascripts? (Use the script file name before the ".js".)
	//$doc->setJavaScripts('tiny_mce');

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