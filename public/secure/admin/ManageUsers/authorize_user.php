<?php /* public/secure/admin/ManageUsers/authorize_user.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageUsers/authorize_user.php');
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

	# Check if the User is logged in.
	$login->checkLogin(ALL_ADMIN_MAN);

	# Get the User's access levels.
	$levels=$login->findUserLevel();

	if(isset($_GET['user']))
	{
		# Instantiate a new User object.
		$user=new User();
		# Set the User ID to the data member effectively cleaning it.
		$user->setID($_GET['user']);
		# Set the data member to a variable.
		$id=$user->getID();

		$current_username=$user->findUsername($id);
		# Instantiate a new FormProcessor object.
		$form_processor=new FormProcessor();
		# Process the request_auth_form.
		$form_processor->processAuthorize();

		# Instantiate a new Branch object.
		$branch=new Branch();
		# Get all the branch id's.
		$branch->getBranches(NULL, 'id');
		# Create an empty array to hold the branch id's.
		$branch_ids=array();
		# Loop through the returned branch rows.
		foreach($branch->getAllBranches() as $row)
		{
			$branch_admin_level=substr_replace($row->id, 1, -1, 1);
			if(in_array($branch_admin_level, $levels)OR($login->isAdmin()===TRUE))
			{
				$branch_ids[]=$row->id;
			}
		}
		$auth=$form_processor->findAuthorization($branch_ids, $id);

		$head='<h3>Access levels for '.$current_username.':</h3>';

		# Get the request authorization form.
		require TEMPLATES.'forms'.DS.'request_auth.php';
	}
	else
	{
		$doc->redirect(ADMIN_URL.'ManageUsers/');
	}
	# Set the default style sheet(s) we are using for the site. (must be absolute location)
	//$doc->setStyle(THEME.'css/secure.css');;

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