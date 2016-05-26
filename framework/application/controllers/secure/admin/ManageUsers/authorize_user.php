<?php /* public/secure/admin/ManageUsers/authorize_user.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');
# Get the Branch Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');

# Check if the User is logged in.
$login->checkLogin(ALL_ADMIN_MAN);

$page_class='manageUserspage-authorizeuser';

# Get the User's access levels.
$levels=$login->findUserLevel();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$head='';

if(isset($_GET['user']))
{
	# Instantiate a new User object.
	$user_obj=new User();
	# Set the User ID to the data member effectively cleaning it.
	$user_obj->setID($_GET['user']);
	# Set the data member to a variable.
	$id=$user_obj->getID();

	$current_username=$user_obj->findUsername($id);
	# Instantiate a new FormProcessor object.
	$form_processor=new FormProcessor();
	# Process the request_auth_form.
	$form_processor->processAuthorize();

	# Instantiate a new Branch object.
	$branch_obj=new Branch();
	# Get all the branch id's.
	$branch_obj->getBranches(NULL, 'id');
	# Create an empty array to hold the branch id's.
	$branch_ids=array();
	# Loop through the returned branch rows.
	foreach($branch_obj->getAllBranches() as $row)
	{
		$branch_admin_level=substr_replace($row->id, 1, -1, 1);
		if(in_array($branch_admin_level, $levels) OR ($login->isAdmin()===TRUE))
		{
			$branch_ids[]=$row->id;
		}
	}
	$auth=$form_processor->findAuthorization($branch_ids, $id);

	$head='<h3>Access levels for '.$current_username.':</h3>';

	# Get the request authorization form.
	require Utility::locateFile(TEMPLATES.'forms'.DS.'request_auth.php');
}
else
{
	$doc->redirect(ADMIN_URL.'ManageUsers/');
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add some content to display in main-2. The "image_link" variable is defined in data/init.php.
$display_main2='<p>Use the form below to authorize or de-authorize '.$current_username.' to contribute and/or edit content for various aspects or "branches" of '.DOMAIN_NAME.'.</p>';
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

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
require Utility::locateFile(TEMPLATES.'page.php');