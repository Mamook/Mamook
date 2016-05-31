<?php /* public/secure/admin/ManageUsers/delete_user.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');

# Check if the User is logged in.
$login->checkLogin(ADMIN_USERS);

$page_class='manageUserspage-deleteuser';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';

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
	$form_processor->processDeleteAccount($id);
	$username=$user->findUsername($id);
	$who=$username.'\'s';
	$head='<h3 class="h-3">Are you sure you want to delete '.$who.' account? (It will be permanently removed from the system)</h3>';
	# Get the delete_user form.
	require Utility::locateFile(TEMPLATES.'forms'.DS.'delete_user.php');
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