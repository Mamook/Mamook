<?php /* public/secure/admin/ManageUsers/privacy.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageUsers/privacy.php');
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
	# Get the Branch Class.
	require_once MODULES.'Content'.DS.'Branch.php';

	# Check if the User is logged in and has the proper privileges to be here.
	$login->checkLogin(ADMIN_USERS);

	if(isset($_GET['user']))
	{
		# Instantiate a new User object.
		$user=new User();
		# Set the User ID to the data member; effectively cleaning it.
		$user->setID($_GET['user']);
		# Set the data member to a variable.
		$id=$user->getID();
		$user->findPrivacySettings();
		$current_username=$user->findUsername($id);

		# Instantiate a new Branch object.
		$branch=new Branch();
		# Get all branch id's.
		$branch->getBranches(NULL, '`id`');
		# Create an empty array to hold the branch id's.
		$branch_ids=array();
		# Set the retrieved branch rows to a variable.
		$branch_rows=$branch->getAllBranches();
		# Loop through the branch rows.
		foreach($branch_rows as $row)
		{
			# Set the id's to the branch id's array.
			$branch_ids[]=$row->id;
		}

		# Instantiate a new FormProcessor object.
		$form_processor=new FormProcessor();
		# Process the privacy form if it has been submitted.
		$form_processor->processPrivacy($branch_ids);

		$head='Where would you like to be Authorized?';

		# Get the privacy form.
		require TEMPLATES.'forms'.DS.'privacy.php';
	}
	else
	{
		$doc->redirect(ADMIN_URL.'ManageUsers/');
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